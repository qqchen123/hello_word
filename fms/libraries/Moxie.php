<?php

/**
 * @desc 魔蝎
 */
class Moxie 
{
	private $host = 'api.51datakey.com';
	private $config_file = 'moxie';
	private $apikey = '';
	private $userid = '';
	private $token = '';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		$this->config->load($this->config_file);
		$moxie_config_tmp = $this->CI->config->item('moxie');
		$key = $moxie_config_tmp['active'];
        $moxie_config = $moxie_config_tmp[$key];
        $this->apikey = $this->moxie_config['apikey'];
        $this->userid = $this->moxie_config['userid'];
        $this->token = $this->moxie_config['token'];
	}

	/**
     * @name 处理curl返回的内容
     */
    public function exec_curl_get_data($querys = '', $path)
    {
        $ret = [];

        $str = '';
        foreach ($querys as $key => $value) {
            if (empty($str)) {
                $str .= $key . '=' . $value;
            } else {
                $str .= '&' . $key . '=' . $value;
            }
        }

        $querys = $str;
        $method = "GET";
        $headers = array();
        array_push($headers, "Authorization:token " . $this->token);
        $bodys = "";
        $url = $this->host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
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

}




?>