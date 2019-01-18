<?php
require('../fms/models/PHPExcel.php');///usr/share/nginx/html/fms/models
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->need_login = TRUE;//控制是否需要登录
		$this->load->model('Login_model');
		$this->load->library('session');
		$this->load->helper('url');
	}

	/**
	 * 登陆界面展示
	 */
	public function test1()
	{
		$this->load->view('yglogin');
	}
	/**
	 * 登陆验证
	 */
	public function check_login_info()
	{
		$login_res = $this->input->post();
		$log_res = $this->get_login_info($login_res);
		if ($login_res['password']==$log_res['password']){
			$this->session->set_userdata(['user_info'=>$login_res['username'].md5($login_res['password'])]);
//			print_r($this->session->get_userdata('user_info'));die;
			echo json_encode(['code'=>1,'msg'=>'登陆成功！']);
		}else{
			echo json_encode(['code'=>0,'msg'=>'密码错误！']);
		}
	}

	/**
	 *
	 * @param $login_res
	 * @return mixed
	 */
	public function get_login_info($login_res)
	{
//		print_r($login_res);die;
		$b_num = $login_res['b_num'];
		$username = $login_res['username'];
//		$password = $login_res['password'];
		$loginfo = $this->Login_model->get_login_info($username,$b_num);
		if ($loginfo){
			return $loginfo;
		}else{
			'error';
		}
	}

	//交易管理（上传代付文件）
	public function test10()
	{
		$this->newview('trsaction_mg2');
	}

//	public function getjson()
//	{
//		$res = $this->db->get('daifu_order_info')->result_array();
//		echo json_encode($res);die;
//	}
	public function logout()
	{
		session_destroy();
		redirect('/Login/test1', 'refresh');
	}



}