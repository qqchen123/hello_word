<?php

class Robot extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->load->library('yufu_api');
    }

    //模拟登录
    public function login()
    {

         $code = $this->yufu_api->getImg();
        $responseMsg = $this->yufu_api->getPage('login','post',[
            'organization_id'=>'1458104070',
            'username'=>'yugui01',
            'password'=>'123456',
            'captcha'=>$code]);
            //var_dump($responseMsg);exit;
        if(class_exists('Domparse')){
            $this->Domparse = new Domparse();
        }else{
            $this->load->library('Domparse');
        }
        
        $this->Domparse->initDom($responseMsg);
        $ifMenuExists = $this->Domparse->find('errTitle');
        if($ifMenuExists){
            echo "登录成功";
        }else{
            echo "登录失败";
        }
    }
    
    function my_json_encode($ary)
	{
	    if (isset($ary['success']) && isset($ary['msg']))
	    {
	        $json_txt = "{";
	        $json_txt.= "\"success\":\"" . $ary['success'] . "\",";
	        $json_txt.= "\"msg\":\"" . $ary['msg'] . "\"}";
	    }
	    return $json_txt;
	}

	public function upload_file()
	{
		$file_name = $this->input->get_post('file_name');
		if($file_name == '')
		{
			$ret['success'] = 'error';
			$ret['msg'] = '文件名不能为空';
			echo $this->my_json_encode($ret);
			exit;
		}
		$file_content = $this->input->get_post('file_content');
		if($file_content == '')
		{
			$ret['success'] = 'error';
			$ret['msg'] = '文件内容不能为空';
			echo $this->my_json_encode($ret);
			exit;
		}
		$content = base64_decode($file_content);
		
		$fp = fopen('/usr/local/nginx/html/assets/upload/'.$file_name, 'w');
		if(fwrite($fp, $content))
		{
			$response = $this->yufu_api->uploadPage('upload',$method='post',$file_name);
			echo $response->getBody();
		}
		else
		{
			$ret['success'] = 'error';
			$ret['msg'] = '上传文件失败';
			echo json_encode($ret);
			exit;
		}
	}
    
    
    public function upload_file_test()
	{
		$filename = '/usr/local/nginx/html/assets/upload/999581044580589_20170823_000001_GP.xlsx';
		$handle = fopen($filename, "r");
		$contents = fread($handle, filesize($filename));
		$contents = base64_encode($contents);
		$fp = fopen('/usr/local/nginx/html/assets/upload/999581044580589_20170823_000002_GP.xlsx', 'w');
		$content = base64_decode($contents);
		fwrite($fp, $content);
		
		$response=$this->yufu_api->uploadPage('upload',$method='post','999581044580589_20170823_000002_GP.xlsx');
		echo $response->getBody();
		
	}
	
	public function query_test()
	{
		
		$response=$this->yufu_api->getPage('query');
		echo $response;
		
	}
	
    //获取验证码
    public function verify_img()
    {
        $this->yufu_api->getImg();
    }

    public function beats()
    {
        $this->yufu_api->beats();
    }

    
}