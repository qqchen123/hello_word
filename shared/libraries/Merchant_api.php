<?php

include "autoloader.php";
include_once 'Domparse.php';
include_once 'codereg.php';
class Merchant_api
{
    private $service = [];
    private $jar;
    private $client;
    private $headers = [
        [
            'Origin'=>'https://mcht.openepay.com',
            'Referer'=>'https://mcht.openepay.com/merchants/merchant/login/agentAuth.do',
            'Upgrade-Insecure-Requests:'=>1,
            'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0'
        ],
        [
            'DNT'=>1,
            'Host'=>'mcht.openepay.com',
            'Pragma'=>'no-cache',
            'Referer'=>'https://mcht.openepay.com/merchants/merchant/login/agentAuth.do',
            'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0',
            'X-Requested-With'=>'XMLHttpReques'
        ]
    ];

    public function __construct()
    {
//        if(!isset($_SESSION['uuid']))
//            $_SESSION['uuid'] = uniqid();

        $this->service = [
            'merchant'=>[
                'uri'=>"/merchants/transaction/gateway-order/list.do?queryType=0&date=".rawurlencode(date("D M j Y H:i:s"))."%20GMT+0800%20(%E4%B8%AD%E5%9B%BD%E6%A0%87%E5%87%86%E6%97%B6%E9%97%B4)"
            ],
            'beats'=>[
                'uri'=>'/merchants/merchant/login/agentAuth.do'
            ],
            'query'=>[
                'uri'=>'/merchants/transaction/gateway-order/agentQuery.do'
            ]
        ];
        $this->domparse = new domparse();
        $this->client = new GuzzleHttp\Client();
        //$this->jar = new GuzzleHttp\Cookie\SessionCookieJar($_SESSION['uuid'],true);
        $this->jar = new GuzzleHttp\Cookie\FileCookieJar('/usr/local/nginx/html/assets/cookie/merchant',true);
    }

    public function getElements()
    {
        $response = $this->getPage('merchant');
        $this->domparse->initDom($response);

        $payztNodeList = $this->domparse->find('select[name=state] option');
        $merchantNodeList = $this->domparse->find('select[name=childMchtNo] option');
        $bankNodeList = $this->domparse->find('select[name=txOrgId] option');
        $tradeNodeList = $this->domparse->find('select[name=payType] option');

        $data = [];
        $actionList = ['payzt','merchant','bank','trade'];

        foreach ($actionList as $_act){
            foreach (${$_act.'NodeList'} as $_ele){
                if($_ele->getAttribute('value') == "") continue;
                $data[$_act][$_ele->getAttribute('value')] = trim($_ele->text());
            }
        }

        return $data;
    }

    //链接保持
    public function beats()
    {
        $uri = ['merchant','query'];
        $response = $this->getPage($uri[mt_rand(0,sizeof($uri)-1)]);
        $this->domparse->initDom($response);
        $ifMenuExists = $this->domparse->find('#startDate');
        //echo $response;
        if($ifMenuExists){
            $status = "OK";
        }else{
            $status = "FAIL";
        }

        printf("[%s] 当前状态%s".PHP_EOL,date("Y-m-d H:i:s"),$status);
        return $status;
    }

    //获取验证码
    public function getImg(){
        $response = $this->client->request('get',"https://mcht.openepay.com/merchants/merchant/login/initLogin.do?currentTimeMillis=".time(),
            [
                'verify'=>false,
                'cookies'=>$this->jar
            ]);
        //var_dump($this->jar);exit();
        //header("Content-Type:",$response->getHeaderLine("Content-Type"));
        //file_put_contents('/usr/local/nginx/html/assets/verify/olms.jpg',$response->getBody()->getContents());
        $codereg= new codereg();
        $codereg->setImgString($response->getBody()->getContents());
        $codereg->recognize(olms);
        return trim(str_ireplace(' ','',`/usr/bin/tesseract /usr/local/nginx/html/assets/verify/olms.jpg stdout -l kailiantong -psm 7 -c "tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"`));
    }

    //获取页面
    public function getPage($actionType,$method='get',$formParamers = [])
    {
        if(!isset($this->service[$actionType])){
            return false;
        }
        $url = "https://mcht.openepay.com".$this->service[$actionType]['uri'];
        $response = $this->client->request($method,$url,[
            'verify'=>false,
            'cookies'=>$this->jar,
            'headers'=>$actionType == 'beats' ? $this->headers[0] : $this->headers[1],
            'form_params'=>$formParamers
        ])->getBody()->getContents();

        return $response;
    }
}