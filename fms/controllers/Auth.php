<?php

class Auth extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		// session_start();
	}

	public function login()
	{
		$this->load->view('fms/login/loginpage2');
	}

	public function logout()
	{
		foreach ($_SESSION as $_key => $_val)
		{
			if (preg_match('/^fms/', $_key))
			{
				unset($_SESSION[$_key]);
			}
		}
		header("Location: ".site_url('Auth/login'));
	}

	public function checkPass()
	{
		$uname = $this->input->get('uname', TRUE);
		$upass = $this->input->get('upass', TRUE);
		if ( ! $uname || ! $upass)
		{
			$this->_response(['responseCode' => 403, 'responseMsg' => '账号或者密码不能为空'], 200);
		}

		//修改by奚晓俊 开始----------------
		// $this->load->model('merchant_model','mers');
		// $adminInfo = $this->mers->getByField('userid',$uname);

		//增加roel_name Merchant_model读不了？？？
		// $this->load->model('WorkLog_model','wl');
		//保持用userid代替username？？？
		// $adminInfo = $this->wl->getUser(['userid'=>$uname]);
		$this->load->model('Wesing_merchant_model', 'wm');
		$adminInfo = $this->wm->get_one_by_name($uname,'01');
		// if ($adminInfo) $adminInfo = $adminInfo[0];

		//修改by奚晓俊 结束----------------
		if ( ! $adminInfo)
		{
			$this->_response(['responseCode' => 401, 'responseMsg' => '账号或者密码错误'], 200);
		}

		$adminPass = $adminInfo['usermm'];
		$salt = $adminInfo['salt'];
		//echo md5($uname.$salt.$upass);
//		echo md5($uname.$salt.$upass);
//		echo '<br/>';
//		echo $adminPass;die;
		if (md5($uname.$salt.$upass) != $adminPass)
		{
			$this->_response(['responseCode' => 402, 'responseMsg' => '账号或者密码错误'], 200);
		}

		unset($adminInfo['upass']);
        unset($adminInfo['usermm']);
        unset($adminInfo['salt']);
        unset($adminInfo['openid']);
		$adminInfo['uname'] = $adminInfo['username'];
		foreach ($adminInfo as $_key => $_val)
		{
			$_SESSION['fms_'.$_key] = $_val;
		}
		$_SESSION['login_time'] = $_SESSION['check_time'] = date('Y-m-d H:i:s', time());

		$this->_response(['responseCode' => 200, 'responseMsg' => '用户登录成功'], 200);
	}

	protected function _response(array $responseMsg, $httpCode = 200)
	{
		$httpCode = (int)$httpCode;
		http_response_code($httpCode);
		//logmsg(json_encode($responseMsg));
		echo json_encode($responseMsg);
		exit;
	}
}
