 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('./vendor/autoload.php');

class Test extends My_Controller 
{

	function __construct() 
	{
		parent::__construct();
	}

	public function test_send()
	{
		$merchantId = 'JG2288607622';
		$merLevel = '2';
		$xmlMsg = '<?xml version="1.0" encoding="UTF-8"?>
			<forpay application="Pay.Req">
				<merchantId>JG2288607622</merchantId>
				<merLevel>2</merLevel>
				<merchantOrderId>100000000000</merchantOrderId>
				<transTime>20160604130000</transTime>
				<agentPayTp>0</agentPayTp>
				<amount>10</amount>
				<account>6226090000000048</account>
				<accName>张三</accName>
				<accType>1</accType>
				<accBankCode>11111111</accBankCode>
			</forpay>';
		$this->load->library('frontpay/FrontPayHelper','','Frontpay');
		$ret = $this->Frontpay->send($merchantId, $merLevel, $xmlMsg);
		return $ret;
	}

	public function test_dsend()
	{
		$msg = $this->test_send();
		$public_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9fkvEL6PKpQHed2ZRKe3qAWlE
jOURHPrsXmqg/HgtcbZ7cjjHT8gOfMJl/pLQtLHCDwozKJb8pbUDEAtaUM97i1gp
HyDHprf0Gy5fE+ky/XTSJVpr4a/IwcsFblHshLsNTazLjz4GlOtFKEPEC/rERfno
g911t6QMsuqC1CValQIDAQAB
-----END PUBLIC KEY-----";
		$key = openssl_get_publickey($public_key);
		$arr = explode('|', $msg);
		$key_temp = base64_decode($arr[1]);
		echo '<br/>------------------------<br/>';
        var_dump($key_temp);

		openssl_public_decrypt($key_temp, $key_tt, $key);
		echo '<br/>-----------3des的key-------------<br/>';
		//3des的key
        var_dump($key_tt);
        $this->load->library('frontpay/Encrypt','','Encrypt');
        if ($this->Encrypt->getKey() == $key_tt) {
        	echo 'key 正确开始解密<br/>';
        	$this->load->library('frontpay/FrontPayHelper','','Frontpay');
        	var_dump($arr[2]);
        	$ret = $this->Encrypt->decrypt3DES(base64_decode($arr[2]));
        	var_dump($ret);
        	//验签
        	if (md5($ret) == $arr[3]) {
        		echo "验签通过<br/>";
        	} else {
        		echo "验签失败<br/>";
        	}
        } else {
        	echo "key 错误 备案key:" . $this->Encrypt->getKey() . " 实际key: " . $key_tt;
        }
	}

	public function test_rsa()
	{
		// $config = array(
		//     "digest_alg"    => "sha512",
		//     "private_key_bits" => 1024,           //字节数  512 1024 2048  4096 等 ,不能加引号，此处长度与加密的字符串长度有关系，可以自己测试一下
		//     "private_key_type" => OPENSSL_KEYTYPE_RSA,   //加密类型
		//   );
		// $res =    openssl_pkey_new($config); 

		// //提取私钥
		// openssl_pkey_export($res, $private_key);

		// //生成公钥
		// $public_key = openssl_pkey_get_details($res);
		// // var_dump($public_key);

		// $public_key=$public_key["key"];

		$private_key = '-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAL1+S8Qvo8qlAd53
ZlEp7eoBaUSM5REc+uxeaqD8eC1xtntyOMdPyA58wmX+ktC0scIPCjMolvyltQMQ
C1pQz3uLWCkfIMemt/QbLl8T6TL9dNIlWmvhr8jBywVuUeyEuw1NrMuPPgaU60Uo
Q8QL+sRF+eiD3XW3pAyy6oLUJVqVAgMBAAECgYBgyJ3oITD2MpsmxjMzJ0hF6dyb
T587w2KB0aOCgBDdnSPIH0nSuvQCOkSMFZ9lC7Vy2X2rLYMYnYY7fzldMsMVgr7u
IiokwkWfBMpf26waU0sEZPbN99IDjVvHudN46L0Y8i8b6CpgmiwBiwZ5xZxR6Jve
SVON8FH9DmJdD/UeAQJBAPxTiNge/69DUV45z5RtL4G89QFNY83p3bfzgLsCSJGU
r7eN6iUo0XvCU4zJhNIDUta9pBLldrwNlE5UtpT9+xUCQQDAQJJZhzcwCjhGgihP
uSbAq2hfOrC7mZhqLEeuL3mjw5g6kQiAQIjsXm2Ygo3gmXnyfcfQ/XJt+pJNJBWq
I8GBAkEAqy1QbK26739PuAioFh1sWSuDWvrRdmPtklmTP0rxSDICcxfHfKYQV1Eh
tSURAhhXHm9Q27Dnt/POZMV7h+A1cQJAEqFvTUvENlyXLYYJgAhSUBOMTsYyQEvX
MFrQK3ogUJVw2CQb7cnTOwy/lCr6ssxMvAoiZgdZonzI1r7rdox2gQJBAIOYUukn
dIxZhIoYuMXPcjvjAk/+WEuJ/rUGpeaAYKgrm6+hyNPQh2DTKVfdn9hGW5DBtfVN
qPG6Sr0LuT3k3FM=
-----END PRIVATE KEY-----';
		$public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9fkvEL6PKpQHed2ZRKe3qAWlE
jOURHPrsXmqg/HgtcbZ7cjjHT8gOfMJl/pLQtLHCDwozKJb8pbUDEAtaUM97i1gp
HyDHprf0Gy5fE+ky/XTSJVpr4a/IwcsFblHshLsNTazLjz4GlOtFKEPEC/rERfno
g911t6QMsuqC1CValQIDAQAB
-----END PUBLIC KEY-----';

		//显示数据
		var_dump($private_key);    //私钥
		var_dump($public_key);     //公钥

		echo "<br/>";

		//要加密的数据
		$data = md5('123456');
		echo '加密的数据：'.$data."\r\n";  

		//私钥加密后的数据
		openssl_private_encrypt($data,$encrypted,openssl_get_privatekey($private_key));

		//加密后的内容通常含有特殊字符，需要base64编码转换下
		$encrypted = base64_encode($encrypted);
		echo "私钥加密后的数据:".$encrypted."\r\n";  

		//公钥解密  
		openssl_public_decrypt(base64_decode($encrypted), $decrypted, openssl_get_publickey($public_key));
		echo "公钥解密后的数据:".$decrypted,"\r\n";  
		  
		// //----相反操作。公钥加密 
		// openssl_public_encrypt($data, $encrypted, $public_key);
		// $encrypted = base64_encode($encrypted);  
		// echo "公钥加密后的数据:".$encrypted."\r\n";
		  
		// openssl_private_decrypt(base64_decode($encrypted), $decrypted, $private_key);//私钥解密  
		// echo "私钥解密后的数据:".$decrypted."n";
	}
	
	/**
	 * @name /test/test_wxml
	 */
	public function test_wxml()
	{
		//生成xml
		$this->newview('test/wxml');
	}


	//测试流程
	public function send()
	{
		if (!empty($_POST)) {
            $content = $this->input->post('content', true);
            $merchantId = $this->input->post('merchantId', true);
            $merLevel = $this->input->post('merLevel', true);

        } else {
            $body = @file_get_contents('php://input');
            $body = json_decode($body);
            $content = !empty($body->content) ? $body->content : '';
            $merchantId = !empty($body->merchantId) ? $body->merchantId : '';
            $merLevel = !empty($body->merLevel) ? $body->merLevel : '';
        }

        //加密
        $this->load->library('frontpay/FrontPayHelper','','Frontpay');
		$ret = $this->Frontpay->send($merchantId, $merLevel, $content);

        echo json_encode(['data'=>$ret], JSON_UNESCAPED_UNICODE);
        exit;
	}

	public function dsend()
	{
		if (!empty($_POST)) {
            $content = $this->input->post('content', true);

        } else {
            $body = @file_get_contents('php://input');
            $body = json_decode($body);
            $content = !empty($body->content) ? $body->content : '';
        }

		//content
		$public_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9fkvEL6PKpQHed2ZRKe3qAWlE
jOURHPrsXmqg/HgtcbZ7cjjHT8gOfMJl/pLQtLHCDwozKJb8pbUDEAtaUM97i1gp
HyDHprf0Gy5fE+ky/XTSJVpr4a/IwcsFblHshLsNTazLjz4GlOtFKEPEC/rERfno
g911t6QMsuqC1CValQIDAQAB
-----END PUBLIC KEY-----";
		$key = openssl_get_publickey($public_key);
		$arr = explode('|', $content);
		$key_temp = base64_decode($arr[1]);
		// echo '<br/>------------------------<br/>';
        // var_dump($key_temp);

		openssl_public_decrypt($key_temp, $key_tt, $key);
		// echo '<br/>-----------3des的key-------------<br/>';
		//3des的key
        // var_dump($key_tt);
        $this->load->library('frontpay/Encrypt','','Encrypt');
        $ret = '';
        if ($this->Encrypt->getKey() == $key_tt) {
        	// echo 'key 正确开始解密<br/>';
        	$this->load->library('frontpay/FrontPayHelper','','Frontpay');
        	$ret = $this->Encrypt->decrypt3DES(base64_decode($arr[2]));
        	
        	//验签
        	if (md5($ret) == $arr[3]) {
        		// echo "验签通过<br/>";
        	} else {
        		// echo "验签失败<br/>";
        	}
        } else {
        	// echo "key 错误 备案key:" . $this->Encrypt->getKey() . " 实际key: " . $key_tt;
        }
        // header("Content-type:text/xml");

      	//页面转义处理
      	$ret = preg_replace('/.? version/', '<?xml version', $ret);
      	$ret = preg_replace('/\&gt\;/', '>', $ret);
        var_dump($ret);exit;
        echo json_encode(['data'=>$ret], JSON_UNESCAPED_UNICODE);
        exit;
	}

	public function indsend()
	{
		if (!empty($_POST)) {
            $content = $this->input->post('content', true);
            $merchantId = $this->input->post('merchantId', true);
            $merLevel = $this->input->post('merLevel', true);

        } else {
            $body = @file_get_contents('php://input');
            $body = json_decode($body);
            $content = !empty($body->content) ? $body->content : '';
            $merchantId = !empty($body->merchantId) ? $body->merchantId : '';
            $merLevel = !empty($body->merLevel) ? $body->merLevel : '';
        }

        //加密
        $this->load->library('frontpay/FrontPayHelper','','Frontpay');
		$ret = $this->Frontpay->send($merchantId, $merLevel, $content);

		$public_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9fkvEL6PKpQHed2ZRKe3qAWlE
jOURHPrsXmqg/HgtcbZ7cjjHT8gOfMJl/pLQtLHCDwozKJb8pbUDEAtaUM97i1gp
HyDHprf0Gy5fE+ky/XTSJVpr4a/IwcsFblHshLsNTazLjz4GlOtFKEPEC/rERfno
g911t6QMsuqC1CValQIDAQAB
-----END PUBLIC KEY-----";
		$key = openssl_get_publickey($public_key);
		$arr = explode('|', $ret);
		$key_temp = base64_decode($arr[1]);
		// echo '<br/>------------------------<br/>';
  //       var_dump($key_temp);

		openssl_public_decrypt($key_temp, $key_tt, $key);
		// echo '<br/>-----------3des的key-------------<br/>';
		//3des的key
        // var_dump($key_tt);
        $this->load->library('frontpay/Encrypt','','Encrypt');
        $ret = '';
        if ($this->Encrypt->getKey() == $key_tt) {
        	// echo 'key 正确开始解密<br/>';
        	$this->load->library('frontpay/FrontPayHelper','','Frontpay');
        	$ret = $this->Encrypt->decrypt3DES(base64_decode($arr[2]));
        	// var_dump($ret);
        	//验签
        	if (md5($ret) == $arr[3]) {
        		// echo "验签通过<br/>";
        	} else {
        		// echo "验签失败<br/>";
        	}
        } else {
        	// echo "key 错误 备案key:" . $this->Encrypt->getKey() . " 实际key: " . $key_tt;
        }
        echo json_encode(['data'=>$ret], JSON_UNESCAPED_UNICODE);
        exit;
	}

	public function tsend()
	{
		if (!empty($_POST)) {
            $content = $this->input->post('content', true);
            $merchantId = $this->input->post('merchantId', true);
            $merLevel = $this->input->post('merLevel', true);

        } else {
            $body = @file_get_contents('php://input');
            $body = json_decode($body);
            $content = !empty($body->content) ? $body->content : '';
            $merchantId = !empty($body->merchantId) ? $body->merchantId : '';
            $merLevel = !empty($body->merLevel) ? $body->merLevel : '';
        }

        //加密
        $this->load->library('frontpay/FrontPayHelper','','Frontpay');
		$ret = $this->Frontpay->send($merchantId, $merLevel, $content);
		$result = $this->Frontpay->exec_curl(site_url('/'), $ret, "POST", 'test/get_req');

		echo json_encode(['data'=>$result], JSON_UNESCAPED_UNICODE);
		exit;
	}

	public function get_req()
	{
		echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
		exit;
	}
}

?>

