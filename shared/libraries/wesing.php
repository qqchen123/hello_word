<?php
include "autoloader.php";
class wesing
{

    //交大数据
    //private $key="wx421c2fcf3645f366";
    //private $secret="fdbb815ecc80f28fceb9c66bc8b8baf7";

    private $key="";
    private $secret="";
    private $token = "";
    private $expTime = 0;
    private $baseUri = "https://api.weixin.qq.com/cgi-bin/";
    private $usr = "";

    public function __construct($user)
    {
        if(!$user) {
            log_message(E_USER_ERROR,'参数不正确');
            show_error("参数错误",500,'出错了');
        }
        $this->usr = current($user);

        get_instance()->load->model('apis_model','am');
        $apiInfo = get_instance()->am->getApiInfo('wesing',$this->usr);
        if(!$apiInfo) log_message(E_USER_ERROR,"未获取到参数信息");
        $this->key = $apiInfo['key'];
        $this->secret = $apiInfo['secret'];
        $this->token = $apiInfo['token'];
        $this->expTime = $apiInfo['exptime'];

        !$this->token && self::getToken();
    }

    public function getUserList($nextId="")
    {
        $apiUri=sprintf("user/get?access_token=%s&next_openid=%s",$this->token,$nextId);
        $client = new GuzzleHttp\Client(['base_uri' =>$this->baseUri]);
        $responseJson = $client->request('get',$apiUri,['verify'=>false])->getBody()->getContents();
        $responseArr = json_decode($responseJson,true);

        if(!$responseJson || isset($responseArr['errcode'])){
            log_message(E_USER_ERROR,'获取用户列表出错：'.$responseJson);
            show_error("微信接口异常",500,'出错了');
        }

        print_r($responseArr);

        //{"total":2,"count":2,"data":{"openid":["","OPENID1","OPENID2"]},"next_openid":"NEXT_OPENID"}
        $responseArr['total'] == $responseArr['count'] && $responseArr['next_openid'] = '';
        return $responseArr;
    }

    public function getUserInfo($openid)
    {
        $apiUri=sprintf("user/info?access_token=%s&openid=%s&lang=zh_CN",$this->token,$openid);var_dump($apiUri);
        $client = new GuzzleHttp\Client(['base_uri' =>$this->baseUri]);
        $responseJson = $client->request('get',$apiUri,['verify'=>false])->getBody()->getContents();
        $responseArr = json_decode($responseJson,true);

        if(!$responseJson || isset($responseArr['errcode'])){
            log_message(E_USER_ERROR,'获取用户信息：'.$responseJson);
            show_error("微信接口异常",500,'出错了');
        }

        print_r($responseArr);

        //{"total":2,"count":2,"data":{"openid":["","OPENID1","OPENID2"]},"next_openid":"NEXT_OPENID"}
        return $responseArr;
    }

    private function getToken()
    {
        if($this->token && time() < $this->expTime){
            return $this->token;
        }

        $apiUri =sprintf("token?grant_type=client_credential&appid=%s&secret=%s",
            $this->key,$this->secret);

        $client=new GuzzleHttp\Client(['base_uri' =>$this->baseUri]);
        $response = $client->request('get',$apiUri,['verify'=>false]);
        $responseJson = $response->getBody()->getContents();

        $responseArr = json_decode($responseJson,true);
        if(!isset($responseArr['access_token'])){
            log_message(E_USER_ERROR,'获取token出错：'.$responseJson);
            show_error("微信接口异常",500,'出错了');
        }

        $this->token = $responseArr['access_token'];
        $this->expTime = time()+$responseArr['expires_in'];
        get_instance()->am->setApiToken('wesing',$this->usr,$this->token,$this->expTime);
    }
}