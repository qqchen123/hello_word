<?php

/**
 * @name 
 */
class YxFinish_model extends Admin_model
{
	public $table_name = 'yx_finish';

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 获取订单信息
	 * @param string $account 银信账户
	 * @param string $loan_title 借款标题
	 * @return array
	 */
	public function find_loan_detail($account, $loan_title) 
	{
		$ret = $this->db
		->where('yx_account', $account)
		->where('pname', $loan_title)
		->order_by('id DESC')
		->limit(1)
		->get($this->table_name)
		->row_array();
		return $ret;
	}

	/**
	 * @name 获取处于已结清状态的用户
	 */
	public function get_finish_username()
	{
		$this->load->model('yx/YxRepaying_model', 'yxrepaying_model');
		$ret = $this->db->select('distinct(yx_account) as account')
		->where('f_status', '已还清')
		->get($this->table_name)
		->result_array();
		$have_finish = [];
		foreach ($ret as $value) {
			$have_finish[] = $value['account'];
		}
		$repaying = $this->yxrepaying_model->get_repaying_username();
		foreach ($have_finish as $key => $value) {
			if (in_array($value, $repaying)) {
				unset($have_finish[$key]);
			}
		}
		return $have_finish;
	}

	/**
	 * @name 获取处于已结清状态的用户数量 
	 */
	public function get_finish_username_total()
	{
		$have_finish = $this->get_finish_username();
		return count($have_finish);
	}

	/**
	 * @name 获取处于已结清状态的账户的总余额
	 */
	public function get_finish_user_acctAmount_total()
	{
		$have_finish = $this->get_finish_username();
		$ret = $this->db->select([
			'sum(acctAmount*100)/100 as total_amount'
		])
		->where_in('account', $have_finish)
		->get('fms_yx_account_have_money')
		->row_array();
		return $ret['total_amount'];
	}

	/**
	 * @name 获取某一天爬取的数据
	 * @param $data string 'xxxx-xx-xx'
	 * @return array
	 */
	public function get_day_data($day)
	{
		$input_day = $day;
		$day = date('Ymd', strtotime($day));
		$result = $this->db
			->where('order_date', $day)
			->group_by('pname, order_date')
			->having('MAX(add_time) > ', $input_day)
			->get($this->table_name)
			->result_array();
		return $result;
	}
}

?>

