<?php 

/**
 * @desc  FrontPayHelper_service   代付助手
 * url：https://域名/服务名
 * 代付业务服务名：AsynAgentPay.do
 * 查询业务服务名：AsynTranStatusQuery.do
 * 报文格式为XML格式
 * 编码格式采用UTF-8
 * 请求方使用post方式提交请求
 *
 * test报文
 *
 * 加密顺序   1.报文 先用key做sdes加密 再base64 key是自己约定的
 *           2.key 用私钥加密 再base64 
 *           3.报文 MD5 
 *
 * 获得的报文 1.报文 先base64解 再解3DES
 *           2.报文 MD5
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */
class FrontPayHelper extends My_service
{
	/**
	 * @name 测试用报文
	 */
	private $test_response =
            "1|QzHOE0tRePMeHr3jcpSH5dzW5Vz6b8UZgkVDXWIVoJZkUA7UoHtfaNkX049ikXPemGzXkz1FX89yLKM8YLBDOHuLxoTFCK6ZfjmXAVLxc79J8CEG1BvnC+lSnPnmGQiN7C5nq9K0mR9rkY5lciwHmFb1jGX+8vj5jNVChpnFK0vnbDDz6kFYq2QWp+ROp7a7Q/+DHq7zgCO71QbCGE2rOoGE/oADI8l2P1AfFq5L22Ta09RPum4zfIfzAjV0+ptcJ9JI0DuLK0y4I9FPAkrqx+YDkn4ObpLSexBCnr8qCEEY76eBnTgISK1+U1bhrn6uYI6XG7npDtKSAmhhMecDzcj6zVla9FU2EqQOF0fjPY0tN4ZRQLrOzfdEzTPMxnFg3uwvCxUJSwjfzTOBAasoBtBIo1+z18yMgHe64o7iw0VStG1inUivdTUpILaTAVa5h6MM4roQXXudlc8uh+E6bbqzm76V3zXBmcaH042IrCX6mszLZCc2+7iOyaC+L6nFKZfdeNEPbA7gMmn3mj1A4pPxEC60VzyYK53kwtvaCE/MlmB/ks+Zdet186LyiYTf0UeILB8bzauDO6xbXhVmI7ezE7IEa5/J|2F6B33F44679D8809C19C72587D2219A";

    private $test_base64PrivateKey
                = "-----BEGIN PRIVATE KEY-----
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
-----END PRIVATE KEY-----";

    private $test_base64PublicKey
                = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9fkvEL6PKpQHed2ZRKe3qAWlE
jOURHPrsXmqg/HgtcbZ7cjjHT8gOfMJl/pLQtLHCDwozKJb8pbUDEAtaUM97i1gp
HyDHprf0Gy5fE+ky/XTSJVpr4a/IwcsFblHshLsNTazLjz4GlOtFKEPEC/rERfno
g911t6QMsuqC1CValQIDAQAB
-----END PUBLIC KEY-----";


	/**
	 * @var hex
	 * @other 产生一个16进制32位的数据.里面数字可以自已修改，但是所有生成32位的地方最好都保持一样
	 */
    private $desKey = '';

    /**
     * @var 请求地址 string
     */
    private $url = '';

    /**
     * @var path string
     */
    private $path = '';

    /**
     * @var 私钥 string 
     */
    private $privateKey = '';

    /**
     * @var 公钥
     */
    private $publicKey = '';

    /** 
     * @var 商户编号
     */
    private $merchantId = '';

    /**
     * @var 商户等级
     */
    private $merLevel = '';

	/**
     * @name 构造函数
     */
    function __construct()
    {
        parent::__construct();
        $this->load->library('frontpay/Encrypt','','Encrypt');
    }

    /**
     * @name send
     * @param string $merchantId 商户号
     * @param string $merLevel 商户级别
     * @param string $xmlMsg 报文
     * 
     */
    public function send($merchantId, $merLevel, $xmlMsg)
    {
    	/** 分四段处理 */
    	$first = base64_encode($merchantId . '|' . $merLevel);//BASE64(商户号|商户级别)
        // echo '<br/>------------------------<br/>';
        // var_dump($first);

        openssl_private_encrypt($this->Encrypt->getKey(), $second, openssl_get_privatekey($this->test_base64PrivateKey));//RAS 加密  BASE64(RSA(报文加密密钥))
        $second = base64_encode($second);
        // echo '<br/>------------------------<br/>';
        // var_dump($second);

		$third = base64_encode($this->Encrypt->encrypt3DES($xmlMsg));//BASE64(3DES(报文原文))
        // echo '<br/>------------------------<br/>';
        // var_dump($third);

    	$fourth = md5($xmlMsg);//MD5(报文原文)
        // echo '<br/>------------------------<br/>';
        // var_dump($fourth);
    	/** 加密后报文 */
    	$message = $first . "|" . $second . "|" . $third . "|" . $fourth;

        // echo '<br/>------------------------<br/>';
        // var_dump($message);

    	/** 输出 */
    	return $message;
    }

    /**
     * @name doParse 对返回的内容格式整理(暂译)
     * @param string $response
     * @return string
     */
    public function doParse($response = '')
    {
    	$response = !empty($response) ? $response : $this->test_response;
    	$parts = explode("|", $response);
    	if (count($parts) == 3) {
    		$respFlag = $parts[0];
    		if ($respFlag) {
    			$retXml = $this->opensslDecrypt(base64_decode($parts[1]), base64_decode($this->test_base64PublicKey));//解密处理  调用 3DES加密
    			Slog::log($retXml);
    			return $retXml;
    		} else {
    			//报错
    			$errorCode = $parts[1];
    			$errorDescription = base64_decode($parts[2]);
    			Slog::log('操作失败');
    			Slog::log("错误代码: " . $errorCode . '||' . "错误描述: " . $errorDescription);
    		}
    	}
    	return "";
    }

    /**
     * @name 执行curl
     * @param string $host
     * @param string|array $querys
     * @param string $method
     * @param string $path
     * @return array
     */
    public function exec_curl($host, $querys, $method = "GET", $path)
    {
    	$ret = [];
        $curl = curl_init();
        if ($method == 'POST' || $method == 'JSON') {
            //设置post方式提交
            curl_setopt($curl, CURLOPT_POST, 1);
            //设置post数据
            
            if (is_array($querys) || is_object($querys)) {
                $post_data = http_build_query($querys);
            } else {
                $post_data = $querys;
            }
            $headers = array();
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            $url = $host . $path;
        } else {
            $str = '';
            foreach ($querys as $key => $value) {
                if (empty($str)) {
                    $str .= $key . '=' . $value;
                } else {
                    if (is_array($value)) {
                        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                    }
                    $str .= '&' . $key . '=' . $value;
                }
            }

            $querys = $str;
            $method = "GET";
            $headers = array();
            $bodys = "";
            $url = $host . $path . "?" . $querys;
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
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
/*************************基础方法*******************************/
    /**
     * @name 数组转xml
     * @param array $data 数据
     * @param string $type 类型  pay | query
     * @return string
     */
    public function array_to_xml($data, $type)
    {
    	$str = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
    	if (!empty($data)) {
    		switch ($type) {
    			case 'pay' : //支付
    				$str .= "<forpay application=\"Pay.Req\" version=\"1.0.0\">\n";
    				break;
    			case 'query' : //查询
    				$str .= "<forpay application=\"PayStatus.Req\" version=\"1.0.0\">\n";
    				break;	
    			default : 
    				Slog::log('非法类型 ' . $type . ' 请检查');
    				break;
    		}
    		
    		foreach ($data as $key => $value) {
    			$str .= '<' . $key . '>' . $value . '<' . $key . ">\n";
    		}
		}
		$str .= "</forpay>";
		return $str;
    }
    
    /**
     * @name xml转数组
     * @param $xml_string string
     * @return array
     */
    public function xml_to_array($xml_string)
    {
    	$tmp = [];
    	preg_match('/standalone\=\"[a-zA-Z.]*\"/', $xml_string, $match);
    	$standalone = preg_replace('/\"/', '', preg_replace('/standalone\=/', '', $match[0]));
    	$tmp['head_standalone'] = $standalone;
    	$match = [];
    	$pos = strpos($xml_string, 'forpay');
    	if ($pos > 0) {
			$tmp_obj = simplexml_load_string($xml_string);
            $array = json_decode(json_encode($tmp_obj), true); 
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    if (isset($value['application'])) {
                        switch($value['application']) {
                            case 'Pay.Rsp' : //支付返回
                                $tmp['head_type'] = 'pay';
                                break;
                            case 'PayStatus.Rsp' : //查询返回
                                $tmp['head_type'] = 'query';
                                break;
                        }
                    }
                    unset($array[$key]);
                }
            }
			$tmp = array_merge($tmp, $array);
    	} else {
    		Slog::log('格式错误 请检查' . $xml_string);
    	}
    	return $tmp;
    }

    /**
     * @name 联机单笔代发
     * @other 商户联机发起单笔代付交易
     * @param array $data
     * @return xml string 
     * application  	商户：Pay.Req   平台：Pay.Rsp
	 * version 			1.1.0
	 * merchantId		商户编号
	 * merLevel			商户等级 1 一级机构  2 二级机构 4.商户
	 * merchantOrderId	商户流水号
	 * transTime		交易时间
	 * agentPayTp		代付类型 0表示T0业务，1表示T1业务
	 * amount			付款金额 分为单位
	 * account 			收款账号
	 * accName 			收款人名称
	 * accType			账户类型 0对公；1对私 ，目前只支持对私业务
	 * accBankCode		收款行编号
	 * msgExt 			[可选]附加信息 目前没有使用
	 * respCode 		[返回]应答码 本字段表示代付受理状态。其中0000：代付受理成功； 0003、0004、0005为未知状态；其他均表示代付失败。除去明确失败的其他均需要后续发起结果查询。
	 * respDesc			[返回]应答码描述 返回详细的操作结果信息
	 * transStatus 		[返回&可选]交易状态 保留使用，目前不会应答发起方
	 * 说明：
	 * 1.代付的应答码respCode只能体现代付受理是成功还是失败，不表示最终的支付结果。支付结果请以查询结果为准。
	 * 2.如果代付的应答码是明确失败，此种情况没有必要继续发结果查询，请查明原因解决后再发起代付；如果代付的应答码是未知状态、成功或者代付没有应答，请针对此笔订单发起结果查询。
	 * 3.由于异常情况，机构发出代付交易后如果在10秒内没有收到应答，可以做超时处理，后续通过结果查询获取订单状态。
     */
    public function create_trade($data)
    {

    	$array = [
    		'merchantId' => $this->merchantId,
    		'merLevel' => $this->merLevel,
    		'merchantOrderId' => $data['merchantOrderId'],
    		'transTime' => $data['transTime'],
    		'agentPayTp' => $data['agentPayTp'],
    		'amount' => $data['amount'],
    		'account' => $data['account'],
    		'accName' => $data['accName'],
    		'accType' => $data['accType'],
    		'accBankCode' => $data['accBankCode'],
    		// 'msgExt' => '',  //可选
    	];	
    	return $this->array_to_xml($array, 'pay');
    }

    /**
     * @name 5.2联机代发结果查询
     * @other 本接口用于查询指定交易的交易结果状态
     * @param $data array
     * @return xml string
     * application		应用名称
     * version 			1.1.0
     * merchantId 		商户编号
     * transSerialsId	交易流水号
     * merLevel 		商户等级
     * merchantOrderId	商户流水号
     * transTime		交易时间
     * settleDate		清算日期
     * transList 		交易明细 金额,帐户,姓名,备注,明细支付状态(注 *1：成功，2：失败，3：处理中，4：解冻状态【订单交易状态判断以此状态为准，若此节点为空或者状态给出的不明确请进行后续的查询处理机制】),交易流水号，交易结果描述，账户类型,手续费 (姓名字段、手续费字段保留使用,目前暂时填null,后续优化)  ...以"|"竖线隔开.具体格式参照例子
注意:此域只有在respCode为成功时才会应答
     * respCode 		应答码
     * respDesc 		应答码描述
     * 说明：
	 * 1.查询交易只支持针对当天代付交易的查询。
	 * 2.查询交易建议在代付交易十分钟后再发，可以提高查询的质量。
	 * 3.应答码respCode只体现查询自身是成功或者失败，跟实际的支付状态没有关系。如果查询自身是失败，需要定位原因解决后再发起查询。实际的支付状态只有在查询应答码是成功的情况下才会在交易明细transList字段中明细支付状态项体现。
	 * 4.如果查询到支付状态是明确的失败或者失败，没有必要针对此笔订单继续发查询，继续发查询依然是明确的成功或者失败。只有在查询到的支付状态是未知状态时需要重新发起查询。如果支付状态是失败，应该定位失败的原因解决后重新提起代付。如果支付状态是解冻状态，应该是在此之前已经进行了人工处理，需要电话沟通。
	 * 5.异常情况下，如果查询10秒内没有应答的话可做超时处理，后续再发结果查询。
     */
    public function create_query($data)
    {

    	$array = [
    		'merchantId' => $this->merchantId,
    		'merLevel' => $this->merLevel,
    		'merchantOrderId' => $data['merchantOrderId'],
    		'transTime' => $data['transTime'],
    	];
    	return $this->array_to_xml($array, 'query');
    }

    /**
     * @name 处理查询返回报文
     * @param string $xml
     * @return array
     */
    public function deal_response($xml)
    {
    	$data = $this->xml_to_array($xml);
    	//获取类型 
    	$type = $data['head_type'];
    	unset($data['head_type']);

    	//获取standalone
    	$standalone = $data['head_standalone'];
    	unset($data['head_standalone']);

    	return [
    		'type' => $type,
    		'standalone' => $standalone,
    		'response' => $data,
    	];
    }

    /**
     * des加密密 ecb模式
     * @param string $input
     */
    public function encrypt($input) {
        if (empty($input)){
            return null;
        }
        
        $data = $this->Encrypt->encrypt3DES($str);
        return $data;
    }
    
    /**
     * des解密 ecb模式
     * @param string $input
     */
    public function decrypt($encrypted) {
        if(!$encrypted || empty($encrypted)){
            return null;
        }
        $encrypted = base64_decode($encrypted);
        if(!$encrypted || empty($encrypted)){
            return null;
        }
        $decrypted = $this->Encrypt->decrypt3DES($decrypted);
        return $decrypted;
    }

    /**
     *  signTag Rsa签名
     * @param string $xml  body参数
     */
    private function signHash($xml)
    {
        if(!is_array($xml)){
            if ((array)simplexml_load_string($xml)) {
                $pri = $this->test_base64PrivateKey;
                $res = openssl_pkey_get_private($pri);
                openssl_sign($xml, $out, $res,OPENSSL_ALGO_SHA1);
                $ret = base64_encode($out);
                return $ret;
            } else {
                echo '报文格式错误';
            }
        }
        return false;
    }
    
     function Opensinghash($arr,$sign)
     {
        $sign = base64_decode($sign);
        $hash = '';
        foreach ($arr as $key =>$value){
            $hash .= $value;
        }
        $pri = $this->test_base64PublicKey;
        $res = openssl_get_publickey($pri);
        $resdata = openssl_verify($hash, $sign, $res);
        return $resdata;
    }

    /**
     * @name 补位
     * @param $text
     * @return string
     */
    private function pkcs5Pad($text)
    {
        $pad = 8 - (strlen($text) % 8);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * @name 去除补位
     * @param $text
     * @return bool|string
     */
    private function pkcs5UnPad($text)
    {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text)-1});
        if($pad>strlen($text)){
            return false;
        }
        if(strspn($text,chr($pad),strlen($text)-$pad) != $pad){
            return false;
        }
        return substr($text,0,-1*$pad);
    }

/*******************test*************************/
    public function test()
    {
        /*$xml = '<?xml version="1.0" encoding="UTF-8"?>
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
        $ret = $this->signHash($xml);*/
        $response = $this->test_response;
        $parts = explode("|", $response);
        $ret = $this->decrypt($parts[1]);
        var_dump($ret);exit;
    }


}

?>



