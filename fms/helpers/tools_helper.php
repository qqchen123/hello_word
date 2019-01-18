<?php

/**
 */
class ToolsHelper {

    public static function getCallerFunction($depth = 2)
    {
        return debug_backtrace()[$depth]['function'];
    }

    public static function getCallerClass($depth = 2)
    {
        return debug_backtrace()[$depth]['class'];
    }

    public static function getCallerObj($depth = 2)
    {
        return debug_backtrace()[$depth]['object'];
    }

    // 获取调用类的命名空间
    public static function getCallerNamespace($depth = 2)
    {
        // 获取调用函数的命名空间，因为命名空间是psr4规范，所以取了个巧直接用dirname方法
        return dirname(self::getCallerClass($depth + 1));
    }

    // 重写一个类似 各系统通用的  dirname方法
    public static function getDirPart($path, $separator = DIRECTORY_SEPARATOR)
    {
        $dirPart = dirname(str_replace('\\', '/', $path));

        return str_replace('/', $separator, $dirPart);
    }

    /**
     * 获取文件内的IP地址
     * @return string|Ambigous <string, unknown>
     */
    public static function getIpAddr()
    {
        // win
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return gethostbyname(getenv('COMPUTERNAME'));
        }

        // linux
        $file = '/opt/app/conf/ip.txt';
        if (file_exists($file)) {
            return file_get_contents($file);
        }
        // backup
        return self::getClientIp();
    }

    /**
     * 获取服务器的ip
     */
    public static function getServerIp()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return $_SERVER["SERVER_ADDR"];
        } else {
            // linux 下 $_SERVER["SERVER_ADDR"] 不准，因为可能有代理
            // 在linux下展示的格式为
            // eth0      Link encap:Ethernet  HWaddr 00:50:56:B9:59:3F
            // inet addr:10.65.209.30  Bcast:10.65.209.255  Mask:255.255.255.0
            // inet6 addr: fe80::250:56ff:feb9:593f/64 Scope:Link
            preg_match("/inet addr:(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/", shell_exec('/sbin/ifconfig'), $matches);
            if (count($matches) == 2) {
                return $matches[1];
            }
        }
        // 匹配不到的情况下默认返回
        return '127.0.0.1';
    }

    /**
     * 获取客户端的ip
     */
    public static function getClientIp()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            return $_SERVER["REMOTE_ADDR"];
        } else {
            return '127.0.0.1';
        }
    }

    public static function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 获取唯一字符串
     * @param string $prefix
     * @return string
     */
    public static function getUniqId($prefix = '')
    {
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) ;
        $uuid .= substr($str, 8, 4);
        $uuid .= substr($str, 12, 4);
        $uuid .= substr($str, 16, 4);
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }

    /**
     * 获取请求的真实ip地址
     */
    public static function getRealIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }
    }
}