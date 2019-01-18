<?php

class Consume extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('consume_model','cm');
    }

    public function pay()
    {
        $paytype=$this->input->post('paytype',true);
        $mer_id = $this->input->post('mer_id',true);
        $enrypd =$this->input->post('token',true);
        $payno   =$this->input->post('cno',true);
        $paypass=$this->input->post('passwd',true);
        $balance=$this->input->post('balance',true);
        $orderid=$this->input->post('orderid',true);

        $this->load->model('merchant_model','mers');
        $adminInfo = $this->mers->getByField('userid',$mer_id);
        $adminInfo = current($adminInfo);
        if($adminInfo === false){
            $this->_response(500,'Invalid Mer id');
        }

        $salt= $adminInfo['salt'];

        if(md5($paytype.$mer_id.$payno.$salt.$paypass.$balance)!=$enrypd){
            $this->_response(500,'Invalid Sign');
        }

        if(!in_array($paytype,['C001','C002'],true)){
            $this->_response(500,'Invalid PayType');
        }
        if($paytype == 'C001')
            $rest= $this->cm->pay($mer_id,$balance,$payno,$paypass,$orderid);
        else
            $rest=$this->cm->refund($mer_id,$balance,$payno,$paypass,$orderid);

        $this->_response(
            $rest['err']==1 ? 200 : 500,
            $rest['see']
        );
    }

    private function _response($code,$message){
        echo json_encode([
            'responseCode'=>$code,
            'responseMsg'=>$message
        ]);
        exit;
    }
}