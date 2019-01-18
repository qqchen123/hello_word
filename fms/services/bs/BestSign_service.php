<?php 

class BestSign_service extends Admin_service{
    
    
    private $_developerId = '2048989602525479459';
    private $_pem = '-----BEGIN RSA PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC0STgE/XqmcOYX2EZ
IDgYv/UByFCKtkVI9Bq0lB+kIGOAglRkiRVKSW67Sm07sv0HMDB0nhQfM0PEdfS72IV
Ly4ACk4aUOYk9OOT19cBnpLxG+3BZs8v9CQfuj8T9c1uLUFH8u3e21bfk70MTIGTuUe
QeEk00ruuGZMnsQAk6n97EBDYAJNiLXTHgLLDoLuicWedcxJmeC6qHZbQh5nz1iKe6f
+ibZ5wdYbIRwh/pEgL7jHF6eSciTPtC5nYzUMKsrjZjYDPJbUoVQPqLyQ1iESb/6WpL
yoo8WuvTjUTNB/hchOkUtr/XHvazsDlpDkjugr8wGshH1HQ5evWAOeva1AgMBAAECgg
EANm6g3kjV3ijaH7rpxVmOnrCc45SWv8bPiKNaZYLU5d/004GQvBsoCg5qYyYHF03Lh
NrndoYgy8MN+XRRWHjJPZFKQH3sQsKF5T09BoFxIUlX10NF9hEL5qpbWq09Dz/F7nhd
yf2xhoS0qk2nDC8qIPLFxU5gD2L2ODXYmAXKT8Xjh8SU9ozFqxHajshlepxlQbI7DgU
wtPm8ifSFNg5aU1+8qYtds6oU1DA0vVaBusDcZ5sq7xT1kLQb8A9z3iztPr4rdvBp4P
dWOpoTmFLoFCS0B2K7eU/YWC51tgEomFQedvD3635TQbbvpuvTjiS/fazGj/BVfjGpW
E/bdzXKBQKBgQDvoaU7e2PoGbLIpco8KFJ9hRo8PciNzKVNr5sZqWUYcMaHGFmFjN3t
DXPeeaL4Gaej5UUQOzg+ptMPToaYybieIWJiXTPX/kR7wLb/LaDsXXOnPfOFALSk8Qm
l2UFjtKSisMF6VEfsBgczOkesC8hkptMTi/fRVURUmBHOr2T8EwKBgQDAmdHohpr4Ju
GccmYW8t4BiyuCDhsYUNiD67R4q8YqQD5JEhMJJvMTgo0zbp4fF8h3JDG1Xlj8XNQhG
p/f9AzoXYjXRPAYhXuNnhFmE1dqulfFFD2DV4niVRy06xpzZyXZQ0L5FTpBJ+FIIbHY
M5EZbpVdm6sisUWTxzKxZ46LFwKBgBuqH7dEQX50KQ29BiH3zb4r9aFqyJKGQ7c6RL+
rrL4rlt/V0c/3OU+6s9vFUyktXQsw1s5O5+ljvQ4RVLyi3St0UzVj6S5QbnuS/g1rqR
5gk4+FOW3rbO913FVHRaNLIY6etVd4D22SLJafbdLQ8WudTtT83blPDLpva+7elNv9A
oGAPM6TkhklRiFoa29GwgoNg2k/5EC11zh3EzdQdCSvXNmJkKJPNj2A0vENyWYsL97f
YJYhx4QxDgP0yTrRrPAtPxOAx44xS1yhRHXaQmLq33xTh5o7TYNOuhFrDceIQ1UWgwC
UXXWRPc7sdkyFDKBDOEdMixBBuKNFN0HVxGLQgyMCgYEAz0w3R0Lls0vxMFMS2ZEitq
gfy1X6zDGGn31A8lMSUvpHSqTpPmr2Sn72PJ83WY5v4guiP0eeCmI6FRFYPSW5oV1Go
q1i28dPZLKm2Hq/fFqB4sDrtKqV+MvIssh9o/AjAvCTHDOlqrlN8wuutpU2VkEw5cLj
7w9ZmU+5/So7BPg=
-----END RSA PRIVATE KEY-----';
    private $_host = 'https://openapi.bestsign.info/openapi/v2'; //测试
    // private $_host = 'https://openapi.bestsign.cn/openapi/v2'; //生产
    
   
    
     public function __construct()
    {
        //var_dump($params);exit;
        parent::__construct();
        $this->load->service('bs/HttpUtils','_http_utils');
        //$this->_pem = $this->_formatPem($this->pem, 'RSA');
        //$this->developerId = $params['developerId'];
        //$this->host = $params['host'];
       
    } 
    
    //********************************************************************************
    // 接口
    //********************************************************************************
    public function regUser($map,$path)
    {
        
        $post_data = json_encode($map);
        //rtick
        $rtick = time().rand(1000, 9999);
        //sign data
        $sign_data = $this->_genSignData($path, null, $rtick, md5($post_data));
        //sign
        $sign = $this->getRsaSign($sign_data);
        $params['developerId'] = $this->_developerId;
        $params['rtick'] = $rtick;
        $params['signType'] = 'RSA';
        $params['sign'] =$sign;
        //url
        $url = $this->_getRequestUrl($path, null, $sign, $rtick);
        //header data
        $header_data = array();
        //content
        $response = $this->execute('POST', $url, $post_data, $header_data, true);
        return $response;
    }
    public function getCert($map,$path){
        //$path = "/user/getCert/";
        $post_data = json_encode($map);
        //rtick
        $rtick = time().rand(1000, 9999);
        //sign data
        $sign_data = $this->_genSignData($path, null, $rtick, md5($post_data));
        //sign
        $sign = $this->getRsaSign($sign_data);
        $params['developerId'] = $this->_developerId;
        $params['rtick'] = $rtick;
        $params['signType'] = 'RSA';
        $params['sign'] =$sign;
        //url
        $url = $this->_getRequestUrl($path, null, $sign, $rtick);
        //header data
        $header_data = array();
        //content
        $response = $this->execute('POST', $url, $post_data, $header_data, true);
        return $response;
    }
    
    public function publicAction($map,$path){
        
        $post_data = json_encode($map);
        //rtick
        $rtick = time().rand(1000, 9999);
        //sign data
        $sign_data = $this->_genSignData($path, null, $rtick, md5($post_data));
        //sign
        $sign = $this->getRsaSign($sign_data);
        $params['developerId'] = $this->_developerId;
        $params['rtick'] = $rtick;
        $params['signType'] = 'RSA';
        $params['sign'] =$sign;
        //url
        $url = $this->_getRequestUrl($path, null, $sign, $rtick);
        //header data
        $header_data = array();
        //content
        $response = $this->execute('POST', $url, $post_data, $header_data, true);
        return $response;
    }
    public function publicActionG($map,$path){
        
        $post_data = json_encode($map);
        //rtick
        $rtick = time().rand(1000, 9999);
        //sign data
        $sign_data = $this->_genSignData($path, null, $rtick, md5($post_data));
        //sign
        $sign = $this->getRsaSign($sign_data);
        $params['developerId'] = $this->_developerId;
        $params['rtick'] = $rtick;
        $params['signType'] = 'RSA';
        $params['sign'] =$sign;
        //url
        $url = $this->_getRequestUrl($path, null, $sign, $rtick);
        //header data
        $header_data = array();
        //content
        $response = $this->execute('POST', $url, $post_data, $header_data, true);
        return $response;
    }
    public function downloadSignatureImage($url_params, $path)
    {
        //$path = "/signatureImage/user/download/";
        
        //$url_params['account'] = $account;
       // $url_params['imageName'] = $image_name;
        
        //rtick
        $rtick = time() . rand(1000, 9999);
        
        //sign
        $sign_data = $this->_genSignData($path, $url_params, $rtick, null);
        $sign = $this->getRsaSign($sign_data);
        
        $url = $this->_getRequestUrl($path, $url_params, $sign, $rtick);
        var_dump("url: ".$url);
        
        //header data
        $header_data = array();
        
        //content
        $response = $this->execute('GET', $url, null, $header_data, true);
        
        return $response;
    }
    
    public function downloadContract($map,$path)
    {
        //$path = "/storage/contract/download/";
        
       // $url_params['contractId'] = $contractId;
        
        //rtick
        $rtick = time() . rand(1000, 9999);
        
        //sign
        $sign_data = $this->_genSignData($path, $map, $rtick, null);
        $sign = $this->getRsaSign($sign_data);
        
        $url = $this->_getRequestUrl($path, $map, $sign, $rtick);
        //var_dump("url: ".$url);
        
        //header data
        $header_data = array();
        
        //content
        $response = $this->execute('GET', $url, null, $header_data, true);
        
        return $response;
    }
    
    /**
     * @param $path：接口名
     * @param $url_params: get请求需要放进参数中的参数
     * @param $rtick：随机生成，标识当前请求
     * @param $post_md5：post请求时，body的md5值
     * @return string
     */
    private function _genSignData($path, $url_params, $rtick, $post_md5)
    {
        $request_path = parse_url($this->_host . $path)['path'];
        
        $url_params['developerId'] = $this -> _developerId;
        $url_params['rtick'] = $rtick;
        $url_params['signType'] = 'rsa';
        
        ksort($url_params);
        
        $sign_data = '';
        foreach ($url_params as $key => $value)
        {
            $sign_data = $sign_data . $key . '=' . $value;
        }
        $sign_data = $sign_data . $request_path;
        
        if (null != $post_md5)
        {
            $sign_data = $sign_data . $post_md5;
        }
        return $sign_data;
    }
    
    private function _getRequestUrl($path, $url_params, $sign, $rtick)
    {
        $url = $this->_host .$path . '?';
        
        //url
        $url_params['sign'] = $sign;
        $url_params['developerId'] = $this -> _developerId;
        $url_params['rtick'] = $rtick;
        $url_params['signType'] = 'rsa';
        
        foreach ($url_params as $key => $value)
        {
            $value = urlencode($value);
            $url = $url . $key . '=' . $value . '&';
        }
        
        $url = substr($url, 0, -1);
        return $url;
    }
    
    private function _formatPem($rsa_pem, $pem_type = '')
    {
        //如果是文件, 返回内容
        if (is_file($rsa_pem))
        {
            return file_get_contents($rsa_pem);
        }
        
        //如果是完整的证书文件内容, 直接返回
        $rsa_pem = trim($rsa_pem);
        $lines = explode("\n", $rsa_pem);
        if (count($lines) > 1)
        { 
            return $rsa_pem;
        }
       
        //只有证书内容, 需要格式化成证书格式
        $pem = '';
        for ($i = 0; $i < strlen($rsa_pem); $i++)
        {
            $ch = substr($rsa_pem, $i, 1);
            $pem .= $ch;
            if (($i + 1) % 64 == 0)
            {
                $pem .= "\n";
            }
        }
        $pem = trim($pem);
        if (0 == strcasecmp('RSA', $pem_type))
        {
            $pem = "-----BEGIN RSA PRIVATE KEY-----\n{$pem}\n-----END RSA PRIVATE KEY-----\n";
        }
        else
        {
            $pem = "-----BEGIN PRIVATE KEY-----\n{$pem}\n-----END PRIVATE KEY-----\n";
        }
        return $pem;
    }
    
    /**
     * 获取签名串
     * @param $args
     * @return
     */
    public function getRsaSign()
    {
        $pkeyid = openssl_pkey_get_private($this->_pem);//var_dump($pkeyid);exit;
        if (!$pkeyid)
        {
            throw new \Exception("openssl_pkey_get_private wrong!", -1);
        }
        
        if (func_num_args() == 0) {
            throw new \Exception('no args');
        }
        $sign_data = func_get_args();
        $sign_data = trim(implode("\n", $sign_data));
        
        openssl_sign($sign_data, $sign, $this->_pem);
        openssl_free_key($pkeyid);
        return base64_encode($sign);
    }
    
    //执行请求
    public function execute($method, $url, $request_body = null, array $header_data = array(), $auto_redirect = true, $cookie_file = null)
    {
        $response = $this->request($method, $url, $request_body, $header_data, $auto_redirect, $cookie_file);
        //var_dump($response);exit;
        $http_code = $response['http_code'];
        if ($http_code != 200)
        {
            throw new \Exception("Request err, code: " . $http_code . "\nmsg: " . $response['response'] );
        }
        
        return $response['response'];
    }
    
    public function request($method, $url, $post_data = null, array $header_data = array(), $auto_redirect = true, $cookie_file = null)
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Connection: keep-alive';
        
        foreach ($header_data as $name => $value)
        {
            $line = $name . ': ' . rawurlencode($value);
            $headers[] = $line;
        }
        
        if (strcasecmp('POST', $method) == 0)
        {
            $ret = $this->_http_utils->post($url, $post_data, null, $headers, $auto_redirect, $cookie_file);
        }
        else
        {
            $ret = $this->_http_utils->get($url, $headers, $auto_redirect, $cookie_file);
        }
        return $ret;
    }
}