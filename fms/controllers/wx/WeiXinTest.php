<?php 

/**
 * @desc WeiXin 测试
 */
class WeiXinTest extends Admin_Controller 
{
    /**
     * @var 环境 是否启用转发
     */
    private $env = 0;

    /**
     * @var 用户环境 是否启用转发
     */
    private $remote_env = 0;

    private $toWX = [];

    private $fromWX = [];

    /**
     * @name 构造函数
     * @other 其中有一个跳板功能 需要按需配置控制器
     */
	public function __construct() 
	{
		parent::__construct();
		//日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);

       

        /*****************跳板******************************/
        //从WX去测试环境的action
        $this->fromWX = ['index'];

        //从本地|测试去WX的action
        $this->toWX = ['get_access_token'];

        $this->weixin();

        $this->remote();   

        $this->load->service('public/WeiXinRetransmission_service', 'wx');

        //环境需要跳转 且 属于来自微信端的请求时
        Slog::log(in_array($this->uri->segment(3), $this->fromWX));
        if ($this->env && in_array($this->uri->segment(3), $this->fromWX)) {
            $path = $this->uri->segment(2) . '/' . $this->uri->segment(3);
            //接收get参数
            if (!empty($_GET)) {
                $param = $_GET;
                $method = "GET";
            }
            $change_flag = 0;
            //接收post参数
            if (!empty($_POST)) {
                $param = $_POST;
                if ($method) {
                    $change_flag = 1;
                }
                $method = "POST";
            }
            //接收json参数
            if (@file_get_contents('php://input')) {
                $body = @file_get_contents('php://input');
                Slog::log($body);
                $body = json_decode($body);
                if (!empty($body)) {
                    $param = json_encode($body, JSON_UNESCAPED_UNICODE);
                    if ($method) {
                        $change_flag = 1;
                    }
                    $method = "JSON";
                } else if (empty($param)) {
                    $param = json_encode($body, JSON_UNESCAPED_UNICODE);
                    $method = "JSON";
                }
            }
            //检查post json 入参时是否还有get参数
            if ($change_flag) {
                $str = '';
                foreach ($_GET as $key => $value) {
                    if (empty($str)) {
                        $str .= $key . '=' . $value;
                    } else {
                        $str .= '&' . $key . '=' . $value;
                    }
                }
                $path .= '?' . $str;
            }
            Slog::log($param);
            //执行请求
            $ret = $this->wx->exec_curl_get(
                'http://120.26.89.131:60523/fms/index.php/wx/', 
                $param_temp, 
                $method, 
                $path
            );
        }
        Slog::log('construct end');
	}

    /**
     * @name 执行curl请求
     * @other 在这里判断是否走跳板
     * @param string $host 请求的host
     * @param string|array $param 请求参数
     * @param string $method 请求方式 GET POST JSON
     * @param string $action 执行请求的方法
     * @return array
     */
    public function exec_curl($host, $param, $method, $path, $action = '')
    {
        if (!empty($action) && in_array($action, $this->toWX) && $this->remote_env) {
            Slog::log('请求跳板');
            $param_temp = [
                'host' => $host,
                'param' => $param,
                'method' => $method,
                'path' => $path
            ];
            $ret = $this->wx->exec_curl_get(
                'http://www.yuandoujinfu.com/fms/index.php/wx/WeiXinTest/', 
                $param_temp, 
                $method, 
                $action
            );
        } else {
            Slog::log('请求微信');

            $ret = $this->wx->exec_curl_get(
                $host, 
                $param, 
                $method, 
                $path
            );
        }
        return $ret;
    }

    /**
     * @url weixin/WeiXinTest/index
     */
	public function index()
	{
        echo 1;exit;
	}

    /**
     * @name 微信服务器验证
     */
    public function test()
    {
        if (empty($_GET)) {
            echo '入参为空 请检查';exit;
        }
        if ($this->checkSignature()) {
            echo $_GET['echostr'];
        } else {
            echo false;
        }
        exit;
    }

    /**
     * @name 签名检查
     */
    private function checkSignature()
    {
        $this->load->library(SHAREPATH . '/weixin/SHA1');
        Slog::log('微信端数据');
        Slog::log($_GET);
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        Slog::log('签名校验');
        Slog::log('signature: ' . $signature);
        Slog::log('计算的签名: ' . $tmpStr);
        if ( $signature == $tmpStr) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @name 获取token 
     * @other token有效期是2小时
     * @url https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
     */
    public function get_access_token()
    {
        Slog::log('get_access_token');
        $wx = $this->config->item('WeiXin');
        $ret = $this->exec_curl(
            'https://api.weixin.qq.com', 
            [
                'grant_type' => 'client_credential',
                'appid' => $wx['key']['AppID'],
                'secret' => $wx['key']['AppSecret']
            ], 
            "GET", 
            '/cgi-bin/token', 
            'get_access_token'
        );
        Slog::log('返回结果');
        Slog::log($ret);
        if (!$this->remote_env) {
            echo json_encode($ret, JSON_UNESCAPED_UNICODE);exit;
        } else {
            return $ret;
        }
    }

    /** 
     * @name 设置自定义菜单
     * 
     */
    public function set_btn()
    {
        $param = [
            [
                'type' => 'view',
                'name' => '环境检查',
                'url' => 'http://yuandoujinfu.com/fms/index.php/wx/WeiXinTest/index'
            ]
        ];
        $this->exec_curl(
            'https://api.weixin.qq.com', 
            [
                [
                    'type' => 'view',
                    'name' => '环境检查',
                    'url' => 'http://yuandoujinfu.com/fms/index.php/wx/WeiXinTest/index'
                ]
            ], 
            "POST", 
            '/cgi-bin/menu/create?access_token=ACCESS_TOKEN', 
            'set_btn'
        );
        if (!$this->wx->get_env()) {
            $ret = $this->wx->exec_curl_get($target, $param, "POST", '/cgi-bin/menu/create?access_token=ACCESS_TOKEN');
        } else {
            $param_temp = [
                'target' => $target,
                'param' => $param,
                'method' => "POST",
                'path' => '/cgi-bin/menu/create?access_token=ACCESS_TOKEN'
            ];
            $ret = $this->wx->exec_curl_get('http://www.yuandoujinfu.com/fms/index.php', $param_temp, "POST", '/wx/WeiXinRetransmission/Retransmission');
        }
        Slog::log('返回结果');
        Slog::log($ret);
    }

    /**
     * @name 检查主机环境
     * @other 此方向为 本地|测试-->生产-->WX服务器|WX客户端
     */
    public function weixin()
    {
        //获取当前IP  判断工作模式
        // echo $_SERVER['SERVER_ADDR'];
        $weixin = $this->config->item('WeiXin');
        $env = '';
        Slog::log($weixin['IPList']);
        foreach ($weixin['IPList']['WhiteList'] as $key => $value) {
            if ($value == $_SERVER['SERVER_ADDR']) {
                $env = $key;
                break;
            }
        }
        switch ($env) {
            case 'Prod':
                //生产环境 不使用转发
                // echo '生产环境 不使用转发';
                break;
            case 'Dev':
                //测试环境 使用转发
                // echo '测试环境 使用转发';
                $this->env = 1;
                break;
            default:
                //本地环境 使用转发
                // echo '本地环境 使用转发';
                $this->env = 1;
                break;
        }
        $_SESSION['WeiXin'] = $this->env;
        // exit;
    }

    /**
     * @name 检查目标机器地址
     * @other 此方向为 WX服务器|WX客户端-->生产-->本地|测试
     */
    public function remote()
    {
        $weixin = $this->config->item('WeiXin');
        foreach ($weixin['IPList']['WhiteList'] as $value) {
            if ($value == $_SERVER['REMOTE_ADDR']) {
                $this->remote_env = 1;
                break;
            }
        }
        if ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']) {
            $_SESSION['remote'] = $this->remote_env;
        }
    }

    /**
     * @name 获取|同时维护token
     * @url wx/WeiXinTest/get_token
     * @return string
     */
    public function get_token()
    {
        $this->db->where(' ztype = \'token\'');
        $token_array = $this->db->get('wesing_zamk')->result_array();
        $old_zname = '';
        foreach ($token_array as $value) {
            if (!empty($value)) {
                $token = $value['zval'];
                $old_zname = $value['zname'];
                break;
            }
        }
        
        $zname = date('mdHi', time());
        if (!$this->effective_time_check(115, $zname, $old_zname)) {
            $this->db->trans_start();
            //屏蔽A Database Error Occurred 之后还原
            $tmp = $this->db->db_debug;
            $this->db->db_debug = false;
            $this->db->insert(
                'wesing_zamk', 
                [
                    'ztype' => 'token',
                    'zname' => $zname,
                    'zval' => ''
                ]
            );
            $this->db->db_debug = $tmp;
            $id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE)
            {
                Slog::log('事务失败');
            } else {
                Slog::log('事务成功');
                //事务成功 请求token  更新token
                $new_token_tmp = $this->get_access_token();
                $new_token = !empty($new_token_tmp['access_token']) ? $new_token_tmp['access_token'] : '';
                $this->db->set("zval", $new_token);
                $this->db->where(" ztype = 'token' AND zname = '" . $zname . "'");
                $ret = $this->db->update('wesing_zamk');

                //删除old token
                $this->db->delete('wesing_zamk', "ztype = 'token' AND zname = '" . $old_zname . "'", 1);

                $token = $new_token;
            }
        }
        return $token;
    }

    /**
     * @name 有效时间检查
     * @param int $min 预设有效分钟
     * @param string $zname 当前时间字符串 格式：月天时分
     * @param string $old_zname 旧的时间字符串 格式：月天时分
     * @return boolean true:有效  false:失效
     */
    public function effective_time_check($min, $zname, $old_zname)
    {
        if ('' == $old_zname) {
            //更新 token
            return false;
        }
        //时间判断
        $zname = date('mdHi', time());
        $dif_day = 0;
        $dif_hours = 0;
        $dif_min = 0;
        //日期比较
        if (substr($zname, 0, 2) - substr($old_zname, 0, 2)) {
            //更新 token
            return false;
        }
        $dif_day = (substr($zname, 2, 2) - substr($old_zname, 2, 2));
        $dif_hours = (substr($zname, 4, 2) + 24 * $dif_day) - substr($old_zname, 4, 2);
        $dif_min = (substr($zname, 6, 2) + 60 * $dif_hours) - substr($old_zname, 6, 2);
        if ($dif_min > $min) {
            //更新 token
            return false;
        } else {
            //不更新 token
            return true;
        }
    }
}

?>

