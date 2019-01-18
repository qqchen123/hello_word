<?php

/**
 * @name 报单model
 */
class Baodan_model extends Admin_Model
{
	public $table_name = 'fms_baodan';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 通过条件查询列表
	 * @param $condition string default ''
	 * @param $type array default [] 
	 * @param $size int default 10
	 * @param $page int default 1
	 * @return array ['listdata' => list data(array), 'info' => info include total num params etc(array)]
	 */
	public function findlist_by_condition($condition = '', $type = [], $size = 10, $page = 1)
	{
		//执行查询
		$obj = $this->db;

		$order_info = [
			'c_time',
			'DESC'
		];

		if (!empty($type)) {
			if (is_array($type)) {
				//create_where_sql
				$type = 's.status IN (' . implode(',', $type) . ')';
				$obj->where($type);
			} else {
				$obj->where_not_in('s.status', [20,21,22,23,24,25,26,27,28,29]);
			}
		}

		if (empty($order_info)) {
			return [
				'listdata' => [],
				'info' => [
					'condition' => $condition,
					'type' => $type,
					'size' => $size,
					'page' => $page,
					'msg' => '入参错误',
				]
			];
		}
		
		if (!empty($condition)) {
			$obj->where('CONCAT(`b.user_name`,`b.bd_id`) LIKE \'%'.$condition.'%\'');
		}
		$obj->join('fms_baodan_status s', 's.bd_id = b.bd_id');
		$total_obj = $obj;
		$total = $total_obj->count_all_results($this->table_name . ' b', false);

		$listdata = $obj
		->select([
			'b.user_name','b.bd_id','b.c_time','b.get_money','b.house_price_id','s.status'
		])
		->order_by('b.' . $order_info[0], $order_info[1])
		->limit($size, $size*($page-1))
		->get()
		->result_array();
		// echo $this->db->last_query();
		return [
			'listdata' => $listdata,
			'info' => [
				'condition' => $condition,
				'type' => $type,
				'size' => $size,
				'page' => $page,
				'total' => $total,
				'msg' => '查询成功',
			]
		];
	}

	/**
	 * @name 更新报单数据
	 * @param $data array 更新的数据
	 * @param $bd_id 条件
	 * @return bool
	 */
	public function update_record($data, $bd_id)
	{
		return $this->db->update($this->table_name, $data, ' bd_id = \''.$bd_id.'\'');
	}

	/**
	 * @name 获取报单全部信息
	 * @param $id int 
	 * @return array
	 */
	public function find_by_id($id)
	{
		return $this->db->where('bd_id', $id)
		->get($this->table_name)
		->row_array();
	}

	function add($data){
		$this->db->insert($this->table_name,$data);
		return $this->db->insert_id();
	}
}

?>