<?php 

/**
 * @desc API 测试
 */
class Test extends Admin_Controller {

    private $myexcel;


	public function __construct() 
	{
		parent::__construct();
		// //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);
	}

	public function index()
	{
        phpinfo();exit;
	}

    /**
     * @name 微信服务器验证
     */
    public function wx()
    {

        if (empty($_GET)) {
            Slog::log(1);
            echo '入参为空 请检查';exit;
        }
        if ($this->checkSignature()) {
            echo $_GET['echostr'];
        } else {
            echo $_GET['echostr'];
        }
        exit;
    }

    /**
     * @name 签名检查
     */
    private function checkSignature()
    {
        Slog::log('微信端数据');
        Slog::log($_GET);
        $token = 'youdoujinfu';
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        Slog::log($tmpStr);
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
     * @name 处理curl返回的内容
     */
    public function exec_curl_get_data($querys = '', $path)
    {
        $ret = [];

        $str = '';
        if (is_array($querys)) {
            foreach ($querys as $key => $value) {
                if (empty($str)) {
                    $str .= $key . '=' . $value;
                } else {
                    $str .= '&' . $key . '=' . $value;
                }
            }
        }

        $querys = $str;
        $method = "GET";
        $headers = array();
        array_push($headers, "Authorization:token " . $this->token);
        $bodys = "";
        if ($str) {
            $url = $this->host . $path . "?" . $querys;
        } else {
            $url = $this->host . $path;
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        //gzip解压
        // curl_setopt($curl, CURLOPT_ENCODING, "gzip");

        if (1 == strpos("$".$this->host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $response = curl_exec($curl);
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
            $ret = json_decode($body, JSON_UNESCAPED_UNICODE);
        } else {
            $ret = ['error' => curl_getinfo($curl, CURLINFO_HTTP_CODE)];
        }
        return $ret;
    }

    /**
     * @name 手动插入用户
     * 
     */
    public function insertuser()
    {
        $data = [
            'channel' => 'A001',
            'ctime' => date('Y-m-d H:i:s'),
            'login_name' => '13901722417',
        ];
        $this->load->service('user/User_service','user_service');
        $this->user_service->register_user($data);

    }


}

?>

