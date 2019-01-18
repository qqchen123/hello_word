<?php
/**
 * @desc 基于SeasLog扩展实现日志类库的封装
 * @filesource Seaslog官网： http://neeke.github.io/SeasLog/
 *
 *
 * 支持的log等级      emergency alert critical error warning notice info debug
 * RFC 5424 下 PRI    8         9     10       11    12      13     14   15
 *
 * PRI计算公式： Facility * 8 + Severity
 * Facility: 1 user-level messages
 * Severity: 0 emergency 1 alert 2 critical 3 error 4 warning 5 notice 6 informational 7 debug
 *
 * rsyslog的格式如下:
 *       <PRI>VERSION  TIMESTAMP                  HOSTNAME    APP-NAME[PROCID]:      MSG
 * e.g.  <14>1         2017-09-28T12:22:03+08:00  Think-PC    <rp>xfp</rp>[10704]:   日志内容
*/

// use Seaslog;
define("SYS", "fms");
class SLog
{
    public static $LOG_MSG_MAX_LENGTH = 4096;
    // 投入使用的 rsyslog的服务器列表
    // 是否使用 rseaslog
    protected static $useRSeaslog;
    // 是否装载了seaslog
    protected static $hasSeaslog;
    // 唯一标识
    private static $indentity;
    // seaslog支持的日志等级
    private static $logLevel = ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];

    /**
     * 对外暴露完整的 多级别日志的输出
     */
    public static function __callStatic($level, $params)
    {
        if (in_array($level, self::$logLevel)) {
            // 格式化成字符串
            return self::log(self::_formatToString($params[0]), $level);
        }
    }

    /**
     * 可以自定义设定日志的长度
     *
     * 在底层依赖的项目。日志需要打全。可以使用当前方法
     */
    public static function setLength(int $length)
    {
        self::$LOG_MSG_MAX_LENGTH = $length;
    }

    /**
     * 给日志的头部信息添加唯一标识
     */
    public static function setIndentity(string $indentity)
    {
        self::$indentity = $indentity;
    }

    /**
     * 对日志添加相应的标识信息
     * @param multi $info
     * @param float $consumedTime
     * @param string $type
     */
    public static function rpc($info, $consumedTime = 0.00, $type = 'api')
    {
        $info = mb_substr($info, 0, self::$LOG_MSG_MAX_LENGTH, 'utf-8');
        $consumedTime = sprintf('%.4f', $consumedTime);
        // 根据消耗时间生成不同色彩的日志信息
        if ($consumedTime < 0.01) {
            $consumedStr = "\033[0;32;40m<$consumedTime>\033[0m";
        } elseif ($consumedTime < 0.1) {
            $consumedStr = "\033[0;36;40m<$consumedTime>\033[0m";
        } elseif ($consumedTime < 0.5) {
            $consumedStr = "\033[0;33;40m<$consumedTime>\033[0m";
        } else {
            $consumedStr = "\033[0;31;40m<$consumedTime>\033[0m";
        }

        // 拼接日志信息
        $consumedStr = "->\33[4m[$type]\033[0m$consumedStr$info";
        $consumedStr = str_replace('  ERROR ####', "  \033[0;31;40m ERROR \033[0m #", $consumedStr);
        $consumedStr = str_replace('  OK ####', "\033[0;36;40m OK \033[0m #", $consumedStr);

        self::log($consumedStr);
    }

    /**
     * 加入日志请求的入口信息
     * @param multi $logMsg
     */
    public static function log($logMsg, $level = 'info')
    {
        $env = 'dev';

        if ('dev' == $env) {
            log_message('error', json_encode($logMsg, JSON_UNESCAPED_UNICODE));
        }
        // 格式化日志
        $logMsg = self::_formatToString($logMsg);

        $debugStrace = debug_backtrace();

        // 本类代理过来
        if ($debugStrace[1]['class'] == self::class) {
            $indexKey = 2;
            $lineIndex = 1;
        } else {
            $indexKey = 1;
            $lineIndex = 0;
        }

        $strace['class'] = '';
        if (isset($debugStrace[$indexKey]['class'])) {
            $strace['class'] = 'class : ' . $debugStrace[$indexKey]['class'] . ' | ';
        }

        $strace['function'] = '';
        if (isset($debugStrace[$indexKey]['function'])) {
            $strace['function'] = ' function : ' . $debugStrace[$indexKey]['function'] . ' | ';
        }

        $strace['line'] = '';
        if (isset($debugStrace[$lineIndex]['line'])) {
            $strace['line'] = ' line : ' . $debugStrace[$lineIndex]['line'] . ' | ';
        }
        // 发布日志
        return self::_publish($logMsg, $strace, $level);
    }

    /**
     * 异常日志统一格式化
     * @param Exception $e
     * @param string $msg
     * @return boolean
     */
    public static function except($e, $msg = '')
    {
        self::log(ArrayHelper::toStringWithKeys([
            "\ncustomlize msg" => $msg,
            'from ip' => ToolsHelper::getClientIp(),
            "last_error" => json_encode(error_get_last()),
            "except msg" => $e->getMessage(),
            "except trace" => $e->__toString(),
        ], $topOfLine = '', $concat = ' : ', $lineFeed = "\n"), 'error');
    }

    /**
     * 格式化日志信息
     * @param multi $message
     * @return multi
     */
    static private function _formatToString($message)
    {
        // 数组需要转化成json的格式写入日志
        if (is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }

        if (is_bool($message)) {
            $message = "(boolean)" . ($message ? 'true' : 'false');
        }

        return $message;
    }

    /**
     * 将日志信息写入
     * @param string $msg
     * @param array $db
     * @param array $params
     * @return boolean
     */
    private static function _publish($msg, $db = [], $level = 'info')
    {
        // 日志文本长度控制
        $msg = mb_substr($msg, 0, self::$LOG_MSG_MAX_LENGTH, 'utf-8');

        // 在日志中添加唯一标识
        $content = "{$db['class']}{$db['function']}{$db['line']}" . self::getIndentityFormat() . "content: $msg\n";

        if (defined("LOCAL_FILE_LOG_DIR"))
            return file_put_contents( LOCAL_FILE_LOG_DIR . SYS . '.log', $content, FILE_APPEND);

        // 如果安装了 SeasLog 扩展
        if (self::$hasSeaslog || self::$hasSeaslog = extension_loaded('SeasLog')) {
            Seaslog::setLogger(SYS);
            return Seaslog::$level($content);
        }

        // 不然就使用 原始的  落伍的 愚蠢的 socket 方式
        return self::useSocket($content);
    }

    private static function getIndentityFormat()
    {
        // 在日志中添加唯一标识
        if (self::$indentity) {
            return "indentity: " . self::$indentity . " | ";
        }
    }

    // 利用socket通讯，传输日志，需要在服务器上部署对应的 接收器。目前在 10.65.178.31 的 /usr/local/service下的用c实现了接收器
    private static function useSocket($content)
    {
        $content = date('Y-m-d H:i:s') . ' ' . $content;
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if (!$socket) return false;

        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ["sec" => 0, "usec" => 100000]);

        if (is_resource($socket) && strlen($content) < 22000) {
            socket_sendto($socket, $content, strlen($content), 0, '10.65.178.31', 8893);
        }
        socket_close($socket);
        return true;
    }
}