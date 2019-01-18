<?php   

/**
 * @desc 出借记录
 */
class YxRepayingLoanLog_model extends Admin_model
{
	public $table_name = 'fms_yx_repaying_loan_log';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	public function record_data($data, $account, $loan_title)
	{
		if (empty($data['序号'])) {
			return false;
		}
		$check = $this->db->select(['account'])
		->where('account', $account)
		->where('loan_title', $loan_title)
		->where('num', $data['序号'])
		->get($this->table_name)
		->row_array();
		if (empty($check)) {
			$insert_array = [
				'account' => $account,
				'loan_title' => $loan_title,
				'num' => isset($data['序号']) ? $data['序号'] : '',
				'loan_user' => isset($data['出借用户']) ? $data['出借用户'] : '',
				'loan_amount' => isset($data['出借金额']) ? $data['出借金额'] : '',
				'loan_time' => isset($data['出借时间']) ? $data['出借时间'] : '',
				'loan_way' => isset($data['出借方式']) ? $data['出借方式'] : '',
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
