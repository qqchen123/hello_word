<?php

/**
 * 
 */
class Zhaiquan_model extends Admin_Model
{
	
	public $table_name = 'fms_zhaiquan_list';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 客户信息查询 通过身份证或者姓名
	 * @param $idnumber 身份证号
	 * @param $name 姓名
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array ['data' => array, 'total' => int]
	 */
	public function find_record($idnumber = '', $name = '', $page = 1, $rows = 10, $sort = 'id', $order = 'DESC')
	{
		$this->update_remaining_time();
		if ($idnumber != 'err' && $idnumber != '') {
			$this->db->where('q.idnumber', $idnumber);
		}
		if ($name != 'err' && $name != '') {
			$this->db->like('q.name', $name, 'after');
		}
		if ($sort != 'err' && $sort != '' && !empty($sort)) {
			$sort = 'q.' . $sort;
		} else {
			$sort = 'q.id';
		}
		if ($order != 'err' && $order != '' && !empty($order)) {
			$order = $order;
		} else {
			$order = 'DESC';
		}
		$this->db->select([
			'q.name',
			'q.idnumber',
			'q.id',
			'q.fuserid',
			'q.account_info',
			'q.area',
			'q.quanli_name',
			'q.address',
			'q.house_loan_amount',
			'q.house_loan_term',
			'q.house_loan_start',
			'q.house_loan_end',
			'q.house_loan_remaining_time',
			'q.house_loan_quanli',
			'q.terrace_loan_amount',
			'q.terrace_loan_term',
			'q.terrace_loan_start',
			'q.terrace_loan_end',
			'q.terrace_loan_remaining_time',
			'q.mispairing_time',
		]);
		$this->db->order_by($sort, $order);
		$total = $this->db->count_all_results($this->table_name . ' q', false);
		$this->db->limit($rows, ($page-1)*$rows);
		$ret = $this->db->get()->result_array();
		// echo $this->db->last_query();
    	return ['data' => $ret, 'total' => $total];
	}

	/**
	 * @name 更新到期时间
	 * @other 更新依据 e_time 为今天则不更新
	 * @return array
	 */
	public function update_remaining_time($flag = 0)
	{
		$check = $this->db->where('e_time > "2018-01-01"')
		->get($this->table_name)
		->row_array();
		if (!empty($check)) {
			$check = $this->db->select('max(e_time) as e_time')
			->where('e_time > "' . date('Y-m-d', time()) . '"')
			->get($this->table_name)
			->row_array();
		}
		if (empty($check) || $flag) {
			$sql_part_house_loan_remaining_time = "UPDATE " . $this->table_name . " SET house_loan_remaining_time = TIMESTAMPDIFF(DAY,DATE(now()),house_loan_end) ";
			$this->db->query($sql_part_house_loan_remaining_time);

			$sql_part_terrace_loan_remaining_time = "UPDATE " . $this->table_name . " SET terrace_loan_remaining_time = TIMESTAMPDIFF(DAY,DATE(now()),terrace_loan_end) ";
			$this->db->query($sql_part_terrace_loan_remaining_time);

			$sql_part_mispairing_time = "UPDATE " . $this->table_name . " SET mispairing_time = (house_loan_remaining_time-terrace_loan_remaining_time) ";
			$this->db->query($sql_part_mispairing_time);
		}
	}
}

?>
