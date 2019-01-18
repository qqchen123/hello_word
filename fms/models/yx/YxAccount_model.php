<?php 

/**
 * @desc 银信账号model
 */
class YxAccount_model extends Admin_Model
{
	public $table_name = 'fms_yx_account';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	public function find_pwd($account)
	{
		$ret = $this->db->select(['pwd'])
		->where('account', $account)
		->where('check_status', 1)
		->get($this->table_name)
		->row_array();
		return !empty($ret['pwd']) ? $ret['pwd'] : '';
	}

	public function find_all_account()
	{
		return $this->db->select(['account'])
		->where('check_status', 1)
		->get($this->table_name)
		->result_array();
	}

	/**
	 * @name 标记密码错误
	 * @param $account string 银信账号
	 */
	public function mark_pwd_error($account)
	{
		if ('' != $account) {
			$this->db->set('pwd_effective', 0);
			$this->db->where('account', $account);
			$this->db->where('check_status', 1);
			$ret = $this->db->update($this->table_name);
			return $ret;
		}
		return 0;
	}

	/**
	 * @name 标记密码有效
	 * @param $account string 银信账号
	 */ 
	public function mark_pwd_effective($account)
	{
		if ('' != $account) {
			$this->db->set('pwd_effective', 1);
			$this->db->where('account', $account);
			$this->db->where('check_status', 1);
			$ret = $this->db->update($this->table_name);
			return $ret;
		}
		return 0;
	}

	/**
	 * @name 修改密码
	 * @param $account string 银信账号
	 * @param $pwd string 密码
	 */
	public function change_pwd($account, $pwd)
	{
		if ('' != $account) {
			$this->db->set('pwd', $pwd);
			$this->db->where('account', $account);
			$this->db->where('check_status', 1);
			$ret = $this->db->update($this->table_name);
			if ($ret) {
				$ret = $this->mark_pwd_effective($account);
			}
			return $ret;
		}
		return 0;
	}

	/**
	 * @name 账户表中人数
	 */
	public function get_account_total()
	{
		$ret = $this->db->select('count(id) as total')
		->where('check_status', 1)
		->get($this->table_name)
		->row_array();
		return $ret['total'];
	}

	public function get_one_by_account($account){
		return $this->db->where(['account'=>$account])->get($this->table_name)->row_array();
	}

	public function replace_one($account,$data){
		$r = $this->get_one_by_account($account);
		//新增
		if($r===null){
			$data['account'] = $account;
			//银信开户手机号=银信账号去YX
            $data['reg_phone'] = substr($account,2);
            //银信登录密码=yxt.银信开户手机号后6位 insert才写入
            $data['pwd'] = 'yxt'.substr($account,-6);
            $data['ctime'] = date('Y-m-d H:i:s');
            return $this->db->insert($this->table_name,$data);
		//编辑
		}else{
			return $this->db->update($this->table_name,$data,['account'=>$account]);
		}
	}

	/**
	 * @name 获取无借款的人
	 */
	public function get_no_loan_username()
	{
		$ret = $this->db->select('account')
		->get($this->table_name)
		->result_array();
		$arr = [];
		foreach ($ret as $value) {
			$arr[] = $value['account'];
		}

		$this->load->model('yx/YxRepaying_model', 'yxrepaying_model');
		$this->load->model('yx/YxFinish_model', 'yxfinsih_model');
		$this->load->model('yx/YxOutMoney_model', 'yxoutmoney_model');

		$repaying = $this->yxrepaying_model->get_repaying_username();
		$finish = $this->yxfinsih_model->get_finish_username();
		$active_arr = array_merge($repaying, $finish);
		foreach ($arr as $key => $value) {
			if (in_array($value, $active_arr)) {
				unset($arr[$key]);
			}
		}
		return $arr;
	}

	/**
	 * @name 获取没有借款的用户数
	 */
	public function get_no_loan_total()
	{
		return count($this->get_no_loan_username());
	}

	/**
	 * @name 获取没有借款的用户的总余额
	 */
	public function get_no_loan_total_acct_amount()
	{
		$users = $this->get_no_loan_username();
		$ret = $this->db->select([
			'sum(acctAmount*100)/100 as total_amount'
		])
		->where_in('account', $users)
		->get('fms_yx_account_have_money')
		->row_array();
		return $ret['total_amount'];
	}
}
?>
