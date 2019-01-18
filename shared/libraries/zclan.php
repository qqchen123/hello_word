<?php

include "autoloader.php";
class zclan
{

    private $jar;
    private $client;
    private $headers = [
        'Origin'=>'https://mcht.openepay.com',
        'Referer'=>'https://mcht.openepay.com/merchants/merchant/login/agentAuth.do',
        'Upgrade-Insecure-Requests:'=>1,
        'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0'
    ];

    public function __construct()
    {
        session_start();
        if(!isset($_SESSION['uuid']))
            $_SESSION['uuid'] = uniqid();
        $this->jar = new GuzzleHttp\Cookie\SessionCookieJar($_SESSION['uuid'],true);
        $this->client = new GuzzleHttp\Client();
    }

    public function getImg(){
        $response = $this->client->request('get',"https://mcht.openepay.com/merchants/merchant/login/initLogin.do?currentTimeMillis=".time(),
            [
                'verify'=>false,
                'cookies'=>$this->jar
            ]);
        header("Content-Type:",$response->getHeaderLine("Content-Type"));
        echo $response->getBody()->getContents();
    }

    public function login($code)
    {
        $url = "https://mcht.openepay.com/merchants/merchant/login/agentAuth.do";
        $response = $this->client->request('POST',$url,[
            'verify'=>false,
            'cookies'=>$this->jar,
            'headers'=>$this->headers,
            'form_params'=>[
                'language'=>1,
                'merchantId'=>'205840170420001',
                'operatorId'=>'agentAdmin',
                'password1'=>'',
                'password'=>'agent@5678',
                'checkCode'=>$code
                ]
        ]);

        echo $response->getBody()->getContents();

    }
}