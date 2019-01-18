<?php

/**
 * @desc 银信借款 订单表(数据来源 脚本同步)
 */
class YxOrder_model extends Admin_Model
{
	public $table_name = 'fms_yx_order';

	/**
	 * table 字段
	 * c_time e_time 不用维护进该数组
	 * 注意：增删字段时必须要维护下列数组
	 */
	public $fields_list = [
		'loan_title',
		'account',
		'status',
		'step_status',
		'step_num',
		'step_total',
		'amount',
		'next_repay_time',
		'should_repaying_amount',
		'principal',
		'interest',
		'last_repay_time',
		'last_step_status'
	];

	/**
	 * @name 允许更新的字段
	 */
	public $update_fields_list = [
		'status',
		'step_status',
		'step_num',
		'step_total',
		'next_repay_time',
		'should_repaying_amount',
		'principal',
		'interest',
		'last_repay_time',
		'last_step_status'
	];

	/**
	 * @name 新增或更新记录
	 * @param $data array 
	 * @return boole true|false
	 */
	public function record_data($data)
	{
		$array = [];
		$data['loan_title'] = preg_replace('/新手专享\-/', '', $data['loan_title']);
		if (empty($this->findone_by_loantitle($data['loan_title']))) {
			//数据不存在 新增
			foreach ($this->fields_list as $value) {
				if (isset($data[$value]) && !empty($data[$value])) {
					$array[$value] = $data[$value];
				}
			}
			if (!empty($array)) {
				$array['c_time'] = date('Y-m-d H:i:s', time());
				$array['e_time'] = date('Y-m-d H:i:s', time());
				return $this->db->insert(
					$this->table_name, 
					$array
				);
			} else {
				return false;
			}
		} else {
			//数据存在 更新
			foreach ($this->update_fields_list as $value) {
				if (isset($data[$value]) && !empty($data[$value])) {
					$array[$value] = $data[$value];
				}
			}
			return $this->db->update(
				$this->table_name,
				$array,
				['loan_title' => $data['loan_title']]
			);
		}
	}

	/**
	 * @name 通过订单标题获得记录
	 */
	public function findone_by_loantitle($loan_title)
	{
		return $this->db
			->where('loan_title', $loan_title)
			->get($this->table_name)
			->row_array();
	}

	/**
	 *
	 */
	public function find_by_date($start_date, $end_date = '')
	{
		$this->db->where(' next_repay_time >= \'' . $start_date . '\'');
		if (!empty($end_date)) {
			$this->db->where(' next_repay_time <= \'' . $end_date . '\'');
			$this->db->or_where(' (last_repay_time >= \'' . $start_date . '\' AND  last_repay_time <= \'' . $end_date . '\')');
		} else {
			$this->db->or_where(' last_repay_time >= \'' . $start_date . '\'');
		}
		return $this->db->get($this->table_name)->result_array();
	}

	/**
	 * @name 检查全部数据是否已更新
	 */
	public function check_update_status()
	{
		$today = date('Y-m-d 00:00:00', time());
		return $this->db
			->where('next_repay_time >= \'' . $today . '\'')
			->where('e_time < \'' . $today . '\'')
			->get($this->table_name)
			->result_array();
	}

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}
}

?>

