<?php

/**
 *
 */
class Yinxin_model extends CI_Model {
	private $user_tab = 'fms_user';
	private $yx_account_tab = 'fms_yx_account';
	private $task_time = '';

	function __construct()
	{
		parent::__construct();
		$this->load->model('yx/YxRepaying_model', 'YxRepaying_model');
		$this->task_time = $this->YxRepaying_model->get_effective_task_time();
	}

	/**
	 * 新增银信用户
	 */
	public function add_new_user_info($newuserinfo)
	{
		$select_account = $this->db->where('account', $newuserinfo['account'])->limit(0)->get(
			$this->yx_account_tab
		)->row_array();
		if ($select_account)
		{
			$msg = ['code' => 2, 'msg' => '此银信账户已存在！'];

			return $msg;
			die;
		}

		$account_data['account'] = $newuserinfo['account'];
		$account_data['pwd'] = $newuserinfo['pwd'];
		$account_data['reg_phone'] = $newuserinfo['reg_phone'];
//		$account_data['reg_time'] = $newuserinfo['reg_time'];
		$account_data['ctime'] = date('Y-m-d H:i:s');
		$account_data['binding_phone'] = $newuserinfo['binding_phone'];

		$user_data['fuserid'] = $newuserinfo['fuserid'];
		$user_data['name'] = $newuserinfo['name'];
		$user_data['idnumber'] = $newuserinfo['idnumber'];
		$user_data['channel'] = $newuserinfo['channel'];
		$user_data['ctime'] = date('Y-m-d H:i:s');

//		$user_data['cjyg'] = $newuserinfo['cjyg'];
		$user_data['yx_account'] = $newuserinfo['account'];

		$this->db->trans_begin();
		$account_res = $this->db->insert($this->yx_account_tab, $account_data);
		if ( ! $account_res)
		{
			$msg = ['code' => 2, 'msg' => '此银信账户已存在！'];

			return $msg;
		}
		$user_res = $this->db->insert($this->user_tab, $user_data);
		if ( ! $user_res)
		{
			$msg = ['code' => 2, 'msg' => '此银信账户已存在！'];

			return $msg;
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$msg = ['code' => 0, 'msg' => '信账户添加失败！'];

			return $msg;
		} else
		{
			$this->db->trans_commit();
			$msg = ['code' => 1, 'msg' => '信账户添加成功！'];
			return $msg;
		}
	}

	/**
	 *银信新用户审核列表
	 */
	public function get_new_yx_user_list($page = '', $first = '')
	{
		$n_list = $this->db->select()
			->from($this->yx_account_tab.' as a')
			->join($this->user_tab.' as b', 'a.account=b.yx_account')
			->where('a.check_status', '0')
			->get()
			->result_array();
		return $n_list;
	}

	/**
	 * 审核
	 * @param $data
	 */
	public function do_check_new_user($data)
	{
		$check = $this->db->where('account', $data['account'])->update(
			$this->yx_account_tab,
			['check_status' => $data['check_status']]
		);
		return $check;
	}

	/**
	 * 获取银信账户密码--作为爬虫的参数
	 * @param $account
	 * @return mixed
	 */
	public function get_user_info_by_account($account)
	{
		$userres = $this->db->select('account,pwd')->where('account',$account)->get('fms_yx_account')->row_array();

		return $userres;
	}

	/**
	 * 已完成数据爬取
	 * @param string $account
	 * @param string $pwd
	 */
	public function do_rep_finish($account = 'YX13901722417', $pwd = 'yxt722417')
	{
//		echo 111;
		$base_url = 'http://120.26.89.131:60523/fms/index.php/reptiles/';
		$url_arr = ['rep_backplan/yx_login', 'rep_contract/yx_login', 'rep_finish/yx_login'];
		foreach ($url_arr as $k => $v)
		{
			$end_url = $base_url.$v.'?account='.$account.'&pwd='.$pwd.'&status=999999';
			file_get_contents($end_url);//https://www.baidu.com/
		}
//		echo 222;
	}

	/**
	 * 还款中数据爬取
	 * @param $account
	 */
	public function d_rep_repaying($account)
	{
		$base_url = 'http://120.26.89.131:60523/fms/index.php/reptiles/';///reptiles/yx/getalldata?cmd=1&account=
		$url_fun = 'yx/getinformationpage?cmd=1&account=';
		file_get_contents($base_url.$url_fun.$account);
	}

	/**
	 * 爬取总账户的信息
	 * @param $account
	 */
	public function all_account($account)
	{
		 $base_url = 'http://120.26.89.131:60523/fms/index.php/reptiles/';
//		$base_url = 'php /www/fms/index.php /reptiles/';
		$url_arr = [
			'yx/getAccountHaveMoney/1',
			 'yx/getAccountAssessment/a',
			 'yxz/get_in_money',
			 'yxz/get_out_money'
		];
		foreach ($url_arr as $k => $v){
			$end_url = $base_url.$v.'/'.$account;
			 file_get_contents($end_url);
		}
	}

	/**
	 * 调用所有爬虫
	 * @param $data
	 */
	public function do_rep($data)
	{
//		ignore_user_abort();
//		set_time_limit(0);
//		echo 1;
//		header("Content-Length: 1");
//		header("Connection: Close");
//		ob_flush();
//		flush();
		$account_pwd = $this->get_user_info_by_account($data);
		$this->do_rep_finish($account_pwd['account'], $account_pwd['pwd']);
		$this->d_rep_repaying($data);
		$this->all_account($data);
	}

	public function import_excel_user($account,$user)
	{
		$select_account = $this->db->where('account', $account['account'])->limit(1)->get(
			$this->yx_account_tab
		)->row_array();
		if (!$select_account)
		{
			$this->db->trans_begin();
			$account_res = $this->db->insert($this->yx_account_tab, $account);
			if ( ! $account_res)
			{
				$msg = ['code' => 2, 'msg' => '此银信账户已存在！'.$user['name'].$account['reg_phone']];

				return $msg;
			}
			$user_res = $this->db->insert($this->user_tab, $user);
			if ( ! $user_res)
			{
				$msg = ['code' => 2, 'msg' => '此银信账户已存在！'.$account['name'].$account['reg_phone']];

				return $msg;
			}
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$msg = ['code' => 0, 'msg' => '信账户添加失败！'];

				return $msg;
			} else
			{
				$this->db->trans_commit();
			}
		}else{
			file_put_contents("../shared/logs/import_excel_user.txt",$account['account'].date('Y-m-d H:i:s').'---',FILE_APPEND);
		}
	}

	/**
	 * 批量审核
	 * @return mixed
	 */
	public function update_check_status()
	{
		$up_res = $this->db->where('check_status',0)->update('fms_yx_account',['check_status'=>1]);
		return $up_res;
	}

}