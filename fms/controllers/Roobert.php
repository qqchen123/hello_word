<?php

class Roobert extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->load->library('merchant_api');
    }

    //模拟登录
    public function login()
    {
        if(!isset($_GET['code'])){
            $actionUri = site_url(__CLASS__."/login");
            $imgUri = site_url(__CLASS__."/verify_img");

            echo <<<EOD
        <form action="$actionUri" method="get">
            <img src="$imgUri" alt="">
            <input type="text" value="" name="code">
            <input type="submit">
        </form>
EOD;
            return;
        }

        $code = $_GET['code'];
        $responseMsg = $this->merchant_api->getPage('beats','post',[
            'language'=>1,
            'merchantId'=>'205840170420001',
            'operatorId'=>'agentAdmin',
            'password1'=>'',
            'password'=>'agent@5678',
            'checkCode'=>$code]);
        if(class_exists('Domparse')){
            $this->Domparse = new Domparse();
        }else{
            $this->load->library('Domparse');
        }
        
        $this->Domparse->initDom($responseMsg);
        $ifMenuExists = $this->Domparse->find('#menu_area');
        if($ifMenuExists){
            echo "登录成功";
        }else{
            echo "登录失败";
        }
    }

    public function transdata()
    {
        $responseMsg = $this->merchant_api->getPage('query','post',[
            'pageCount'=>'',
            'pageNo'=>'',
            'currPageNo'=>1,
            'customField'=>'',
            'payerPan6'=>'',
            'payerPan4'=>'',
            'mchtOrderId'=>'',
            'mchtOrderLs'=>'',
            'startDate'=>date("Y-m-d 00:00:00",strtotime('-1 day')),
            'endDate'=>date("Y-m-d 23:59:59",strtotime('-1 day')),
            'startMchtOrderDate'=>'',
            'endMchtOrderDate'=>'',
            'state'=>'',
            'childMchtNo'=>'',
            'txOrgId'=>'',
            'payType'=>'',
            'amount'=>'']);

        if(class_exists('Domparse')){
            $this->Domparse = new Domparse();
        }else{
            $this->load->library('Domparse');
        }

        $this->Domparse->initDom($responseMsg);
        $ifMenuExists = $this->Domparse->find('.Metronic-alerts');
        if($ifMenuExists && trim($ifMenuExists[0]->text())=="不存在符合查询条件的订单信息！"){
            echo trim($ifMenuExists[0]->text())."-->FAIL";
            return;
        }

    }

    //获取验证码
    public function verify_img()
    {
        $this->merchant_api->getImg();
    }

    public function beats()
    {
        $this->merchant_api->beats();
    }

    public function getmerchants()
    {
        $data = $this->merchant_api->getElements();
        $this->load->model("merchant_model");
        $this->merchant_model->update($data);
        var_dump(234234);
    }
}