<?php 

/**
 * @desc 银信订单详情-还款中的订单-还款计划
 */
class YxRepayingPlan_model extends Admin_model
{
	public $table_name = 'fms_yx_repaying_plan';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	public function record_data($data, $account, $loan_title)
	{
		if (empty($data['期数'])) {
			return false;
		}
		$check = $this->check_reocrd($account, $loan_title, $data['期数']);
		if (empty($check)) {
			$insert_array = [
				'account' => $account,
				'loan_title' => $loan_title,
				'periods' => isset($data['期数']) ? $data['期数'] : '',
				'repaying_time' => isset($data['还款日期']) ? $data['还款日期'] : '',
				'should_repaying_amount' => isset($data['应还本息']) ? $data['应还本息'] : '',
				'principal' => isset($data['本金']) ? $data['本金'] : '',
				'interest' => isset($data['利息']) ? $data['利息'] : '',
				'default_interest' => isset($data['罚息']) ? $data['罚息'] : '',
				'status' => isset($data['状态']) ? $data['状态'] : '',
				'ctime' => date('Y-m-d H:i:s', time()),
				'lutime' => date('Y-m-d H:i:s', time()),
			];
			$this->db->insert($this->table_name, $insert_array);
			return $this->db->insert_id();
		} else {
			//update
			$this->db->set('periods', isset($data['期数']) ? $data['期数'] : '未知');
			$this->db->set('repaying_time', isset($data['还款日期']) ? $data['还款日期'] : '未知');
			$this->db->set('should_repaying_amount', isset($data['应还本息']) ? $data['应还本息'] : '未知');
			$this->db->set('principal', isset($data['本金']) ? $data['本金'] : '未知');
			$this->db->set('interest', isset($data['利息']) ? $data['利息'] : '未知');
			$this->db->set('default_interest', isset($data['罚息']) ? $data['罚息'] : '未知');
			$this->db->set('status', isset($data['状态']) ? $data['状态'] : '未知');
			$this->db->set('lutime', date('Y-m-d H:i:s', time()));
			$this->db->where('account', $account);
			$this->db->where('loan_title', $loan_title);
			$this->db->where('periods', $data['期数']);
			$ret = $this->db->update($this->table_name);
			return $ret;
		}
	}

	public function get_record($account, $loan_title) {
		$loan_title = $this->deal_loan_title($loan_title);
		$ret =  $this->db->select([
			'periods',
			'repaying_time',
			'convert(should_repaying_amount/100,decimal(10,2)) as should_repaying_amount',
			'principal',
			'convert(interest/100,decimal(10,2)) as interest',
			'convert(default_interest/100,decimal(10,2)) as default_interest',
			'status',
		])
		->where(" account = '" . $account . "' AND loan_title = '" . $loan_title . "' ")
		->get($this->table_name)->result_array();
		return $ret;
	}

	public function get_records($accounts, $loan_titles)
	{
		$ret =  $this->db->select([
			'account',
			'loan_title',
			'periods',
			'repaying_time',
			'convert(should_repaying_amount/100,decimal(10,2)) as should_repaying_amount',
			'convert(principal/100,decimal(10,2)) as principal',
			'convert(interest/100,decimal(10,2)) as interest',
			'convert(default_interest/100,decimal(10,2)) as default_interest',
			'status',
		])
		->where_in("account", $accounts)
		->get($this->table_name)->result_array();
		$temp = [];
		foreach ($ret as $key => $value) {
			if (in_array($value['loan_title'], $loan_titles)) {
				$loan_titles = array_diff($loan_titles, [$value['loan_title']]);
			} else {
				$temp[$key] = $value;
			}
		}

		foreach ($temp as $key => $value) {
			foreach ($loan_titles as $item) {
				if (strstr($value['loan_title'], $item)) {
					$ret[$key]['loan_title'] = $item;
				}
			}
		}

		return $ret;
	}

	/**
	 * @name 检查记录
	 */
	public function check_reocrd($account, $loan_title, $periods)
	{
		return $this->db->select(['account'])
			->where('account', $account)
			->where('loan_title', $loan_title)
			->where('periods', $periods)
			->get($this->table_name)
			->row_array();
	}

	public function deal_loan_title($loan_title)
	{
		//新手专享 前缀的支持
		if (strstr($loan_title, '新手专享-')) {
			$loan_title = preg_replace('/新手专享\-/', '', $loan_title);
		}
	}

	/**
	 * @name 获取今天更新的数据
	 * @return array
	 */
	public function get_today_update()
	{
		return $this->db
			->where('lutime >= \'' . date('Y-m-d', time()) . '\'')
			->get($this->table_name)
			->result_array();
	}

}
?>

