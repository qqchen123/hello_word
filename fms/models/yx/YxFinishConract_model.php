<?php

/**
 * @name 已完结合同表
 */
class YxFinishContract_model extends Admin_model
{
	public $table_name = 'yx_finish_contract';

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 获取借款合同
	 * @param string $account 
	 * @param string $loan_title
	 * @return array 
	 */
	public function get_contract_info($account, $loan_title)
	{
		$ret = $this->db->select('rep_time')
		->where('account', $account)
		->where('loan_title', $loan_title)
		->order_by('rep_time DESC')
		->limit(1)
		->get($this->table_name)
		->row_array();

		if (!empty($ret)) {
			$ret = $this->db
			->select(['account', 'loan_title', 'down_contract_url'])
			->where('account', $account)
			->where('loan_title', $loan_title)
			->where('rep_time', $ret['rep_time'])
			->result_array();
		} else {
			$ret = [];
		}
		return $ret;
	}

	
}

?>
