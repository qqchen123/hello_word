<?php

/**
 *
 */
class Order_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 插入Excel导入的用户数据
	 * @param $datas
	 * @return mixed
	 */
	public function insert_excel_info($datas)
	{
//		print_r($datas);die;
		$this->insert_b_pc_num($datas);
		return $this->db->insert_batch('daifu_order_info', $datas);
	}

	public function insert_b_pc_num($datas)
	{
		foreach ($datas as $k => $v)
		{
//			print_r($v['business_pc_num']);die;
			$select_res = $this->db->where('business_pc_num',$v['business_pc_num'])->get('daifu_order_b_pc_num')->row_array();
			if (!$select_res){
				$this->db->insert('daifu_order_b_pc_num',['business_pc_num'=>$v['business_pc_num']]);
			}
		}

	}

	/**
	 * @param $offset
	 * @param $per_page_nums
	 * @param $table
	 * @return mixed
	 */
	public function select_order($offset, $per_page_nums, $table,$business_pc_num)
	{
		$post_data = $this->input->post();
		$this->detail_where($business_pc_num, $post_data);
		$res = $this->db
//			->where('business_pc_num',$business_pc_num)
			->limit($per_page_nums, $offset)
			->get($table)
			->result_array();
//		echo $this->db->last_query();die;
		return $res;
	}

	/**
	 * @param $table
	 * @return mixed
	 */
	public function allnums($table)
	{
		return $this->db->get($table)->num_rows();
	}

	public function get_sum_num()
	{
		return $this->db->select('*')->group_by('business_pc_num')->get('daifu_order_info')->result_array();
	}

	public function get_sum_info($offset='', $per_page_nums='')
	{
		$res = $this->db->select('*')
			->limit($per_page_nums, $offset)
			->get('daifu_order_b_pc_num')->result_array();
		foreach ($res as $k => $v)//count_all_results
		{
			$res[$k]['sum'] = $this->db->select_sum('trade_money')->where('business_pc_num',$v['business_pc_num'])->get('daifu_order_info')->row_array()['trade_money'];
			$res[$k]['count'] = $this->db->where('business_pc_num',$v['business_pc_num'])->count_all_results('daifu_order_info');
		}
		return $res;
	}

	/**
	 * @param $business_pc_num
	 * @param $post_data
	 */
	private function detail_where($business_pc_num, $post_data)
	{
		if (isset($post_data['date']) && ! empty($post_data['date']))
		{
			$str1 = substr($post_data['date'], 0, 11);
			$str2 = substr($post_data['date'], 12);

			if ($str1 > 3)
			{
				$this->db->where('up_date>', trim($str1));
			}
			if ($str2 > 3)
			{
				$this->db->where('up_date<', trim($str2));
			}
		}
		if (isset($post_data['business_pc_num']) && ! empty($post_data['business_pc_num']))
		{
			$this->db->where('business_pc_num', trim($post_data['business_pc_num']));
		}
		if (isset($post_data['bussiness_account']) && ! empty($post_data['bussiness_account']))
		{
			$this->db->where('bussiness_account', trim($post_data['bussiness_account']));
		}

		if ($business_pc_num)
		{
			$this->db->where('business_pc_num', $business_pc_num);
		}
	}

}