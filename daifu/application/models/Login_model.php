<?php

/**
 *
 */
class Login_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 获取登陆用户信息
	 * @param $username
	 * @param $password
	 * @return mixed
	 */
	public function get_login_info($username,$b_num)
	{
		$login_res = $this->db
			->where('username',$username)
			->where('bussiness_num',$b_num)
			->get('daifu_login')
			->row_array();
		return $login_res;
	}
}