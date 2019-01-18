<?php

include "autoloader.php";
include_once 'Domparse.php';
include_once 'codereg.php';
class Yufu_api
{
    private $service = [];
    private $jar;
    private $client;
    private $headers = [
        [
            'Origin'=>'https://biz.yufu99.com:9630',
            'Referer'=>'https://biz.yufu99.com:9630/mscmer/jsp/index.jsp',
            'Upgrade-Insecure-Requests:'=>1,
            'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0'
        ],
        [
            'DNT'=>1,
            'Host'=>'biz.yufu99.com:9630',
            'Pragma'=>'no-cache',
            'Referer'=>'https://biz.yufu99.com:9630/mscmer/jsp/index.jsp',
            'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.221 Safari/537.36 SE 2.X MetaSr 1.0',
            'X-Requested-With'=>'XMLHttpReques'
        ]
    ];

    public function __construct()
    {
//        if(!isset($_SESSION['uuid']))
//            $_SESSION['uuid'] = uniqid();

        $this->service = [
//            'merchant'=>[
//                'uri'=>"/merchants/transaction/gateway-order/list.do?queryType=0&date=".rawurlencode(date("D M j Y H:i:s"))."%20GMT+0800%20(%E4%B8%AD%E5%9B%BD%E6%A0%87%E5%87%86%E6%97%B6%E9%97%B4)"
//            ],
			'login'=>[
				'uri'=>'/mscmer/app/login'
			],
			'upload'=>[
				'uri'=>'/mscmer/dedu/payment/list/uploadPm'
			],
            'beats'=>[
                'uri'=>'/mscmer/jsp/index.jsp'
            ],
            'query'=>[
                'uri'=>'/mscmer/dedu/payment/list/paymentListPage'
            ]
        ];
        $this->domparse = new domparse();
        $this->client = new GuzzleHttp\Client();
        //$this->jar = new GuzzleHttp\Cookie\SessionCookieJar($_SESSION['uuid'],true);
        $this->jar = new GuzzleHttp\Cookie\FileCookieJar('/usr/local/nginx/html/assets/cookie/yufu',true);
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
        $uri = ['beats'];
        $response = $this->getPage('beats');
        $this->domparse->initDom($response);
        //$ifMenuExists = $this->domparse->find('#leftTreeLink_5');
        //echo $response;exit;
        if(strstr($response,'999581044580589')){
        //if($ifMenuExists){
            $status = "OK";
        }else{
            $status = "FAIL";
        }

        printf("当前状态%s".PHP_EOL,$status);
        return $status;
    }

    //获取验证码
    public function getImg(){
        $response = $this->client->request('get',"https://biz.yufu99.com:9630/mscmer/index/captcha",
            [
                'verify'=>false,
                'cookies'=>$this->jar
            ]);
        //var_dump($response->getBody()->getContents());exit();
        //header("Content-Type:",$response->getHeaderLine("Content-Type"));
        //file_put_contents('/usr/local/nginx/html/assets/verify/olms.jpg',$response->getBody()->getContents());
        $codereg= new codereg();
        $codereg->setImgString($response->getBody()->getContents());
        $codereg->recognize('yf');
        return trim(str_ireplace(' ','',`/usr/bin/tesseract /usr/local/nginx/html/assets/verify/yf.jpg stdout -l kailiantong -psm 7 -c "tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"`));
    }

    //获取页面
    public function getPage($actionType,$method='post',$formParamers = [])
    {
        if(!isset($this->service[$actionType])){
            return false;
        }
        $url = "https://biz.yufu99.com:9630".$this->service[$actionType]['uri'];
        $response = $this->client->request($method,$url,[
            'verify'=>false,
            'cookies'=>$this->jar,
            'headers'=>$actionType == 'login' ? $this->headers[0] : $this->headers[1],
            'form_params'=>$formParamers
        ])->getBody()->getContents();

        return $response;
    }
    
    //获取页面
    public function uploadPage($actionType,$method='post',$file_name)
    {
        if(!isset($this->service[$actionType])){
            return false;
        }
        $url = "https://biz.yufu99.com:9630".$this->service[$actionType]['uri'];
        $response = $this->client->request('POST', $url, [
        	'verify'=>false,
            'cookies'=>$this->jar,
            'headers'=>$actionType == 'login' ? $this->headers[0] : $this->headers[1],
		    'multipart' => [
		        [
		            'name'     => 'file',
		            'contents' => fopen('/usr/local/nginx/html/assets/upload/'.$file_name, 'r'),
		            'filename' => $file_name
		        ]
		    ]
		]);

        return $response;
    }
}