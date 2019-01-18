<?php

/**
 * @desc 转让记录
 */
class YxRepayingTransLog_model extends Admin_model
{
	public $table_name = 'fms_yx_repaying_trans_log';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	public function record_data($data, $account, $loan_title)
	{
		if (empty($data['交易时间']) || empty($data['交易金额'])) {
			return false;
		}
		$check = $this->db->select(['account'])
		->where('account', $account)
		->where('loan_title', $loan_title)
		->where('amount', $data['交易金额'])
		->where('trade_time', $data['交易时间'])
		->get($this->table_name)
		->row_array();
		if (empty($check)) {
			$insert_array = [
				'account' => $account,
				'loan_title' => $loan_title,
				'amount' => isset($data['交易金额']) ? $data['交易金额'] : '',
				'trade_time' => isset($data['交易时间']) ? $data['交易时间'] : '',
				'creditors_right_buyer' => isset($data['债权买入者']) ? $data['债权买入者'] : '',
				'creditors_tight_seller' => isset($data['债权卖出者']) ? $data['债权卖出者'] : '',
				'ctime' => date('Y-m-d H:i:s', time()),
				'lutime' => date('Y-m-d H:i:s', time()),
			];
			$this->db->insert($this->table_name, $insert_array);
			return $this->db->insert_id();
		} else {
			return 'no update';
		}
	}
}

?>
