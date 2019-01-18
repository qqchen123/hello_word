<?php

/**
 * Created by PhpStorm.
 * User: a
 * Date: 2019/1/9
 * Time: 11:29 AM
 */
class MiniProgramCheck_model extends CI_Model {
	private $fms_bd_status_tab = 'fms_baodan_status';
	private $fms_bd_tab = 'fms_baodan';
	private $fms_fgg_hp = 'fms_minipro_house_price';

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 房产评估列表----代办、在办、已办
	 * @return mixed
	 */
	public function get_pg_list_data()
	{
		$this->pinggu_list_where();
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$first = $rows * ($page - 1);
		if ($page !== '' && $first !== '')
		{
			$this->db->limit($rows, $first);
		}
		$res['rows'] = $this->db
			->select('b.*,c.order_num,c.c_date,a.user_name,a.get_money,c.ZheKouZongJia,a.admin_id')
			->from($this->fms_bd_tab.' a')
			->join($this->fms_bd_status_tab.' b', 'a.bd_id=b.bd_id')
			->join($this->fms_fgg_hp.' c', 'a.house_price_id=c.id')
//			->where('b.status',$status)
			->get()
			->result_array();
//		 file_put_contents(
//		 	'./upload/get_pg_list_data.txt'
//			,json_encode($this->db->last_query()).'----'.date('Y-m-d H:i:s').PHP_EOL
//			,FILE_APPEND
//		 );
		$this->pinggu_list_where();
		$res['total'] = $this->db
			->select('b.*,c.order_num,c.c_date,a.user_name,a.get_money,c.ZheKouZongJia,a.admin_id')
			->from($this->fms_bd_tab.' a')
			->join($this->fms_bd_status_tab.' b', 'a.bd_id=b.bd_id')
			->join($this->fms_fgg_hp.' c', 'a.house_price_id=c.id')
//			->where('b.status',$status)
			->count_all_results();

		return $res;
	}
	/**
	 * 评估审核
	 * @return array
	 */
	public function get_look_pg_data()
	{
		$bd_id = $this->input->get('bd_id');
		$judge_look_deal = $this->input->get('judge_ld');
		$judge_info = $this->judge_status($bd_id);//判断状态是否可以进行评估
//		$judge_info = true;
		if ($judge_info)
		{
			if ($judge_look_deal){//查看 不用修改状态----只有处理的时候 修改状态
				$this->chang_status($bd_id, ['pg_admin_id' => $_SESSION['fms_id'], 'status' => 33]);//开始评估--修改评估状态
			}
			$bd_order_res = $this->db
				->select('*')
				->from($this->fms_bd_tab.' a')
				->join($this->fms_bd_status_tab.' b', 'a.bd_id=b.bd_id')
				->join($this->fms_fgg_hp.' c', 'a.house_price_id=c.id')
				->where('a.bd_id', $bd_id)
				->get()
				->row_array();
			$user_res = $this->db->where('bd_id', 44)->get('fms_miniprogram_order_userinfo')->row_array();
			$house_res = $this->db->where('bd_id', 44)->get('fms_miniprogram_order_houseinfo')->row_array();
			$res = ['order' => $bd_order_res, 'user' => $user_res, 'house' => $house_res];

			return $res;
		}else{
			return ['code'=>0,'msg'=>'此信息正在评估，请评估其他信息！'];
		}
	}

	/**
	 * 改变状态--禁止其他人《评估》
	 */
	public function chang_status($bd_id, $status_data)
	{
		$res = $this->db->where('bd_id', $bd_id)->update('fms_baodan_status', $status_data);
	}

	/**
	 * 判断评估数据的-状态-是否 可以进行 《评估》 ；
	 */
	public function judge_status($bd_id, $status = 30)
	{
		$res = $this->db
			->where('bd_id', $bd_id)
			->get('fms_baodan_status')
			->row_array();
		if ($res['status'] == $status)
		{
			return TRUE;
		} else
		{
			return FALSE;
		}
	}

	/**
	 * 评估--处理\回退
	 * @return bool
	 */
	public function deal()
	{
//		$this->load->model('BaodanStatus_model');
		$post = $this->input->post();
//		$x_judge = $this->BaodanStatus_model->ifStatus($post['bd_id'],$post['status']);
		$deal_db = $this->db
			->where('bd_id',$post['bd_id'])
			->update('fms_baodan_status',$post);
		if ($deal_db){
			return true;
		}else{
			return false;
		}
	}

	private function pinggu_list_where()
	{
		$status = $this->input->post('status');
		switch ($status)
		{
			case 'daiban':
				$this->db->where('b.status', 30);
				break;
			case 'zaiban':
				$status = [31, 33, 34, 38];
				$this->db->where_in('b.status', $status);
				$this->db->where('b.pg_admin_id', $_SESSION['fms_id']);
				break;
			case 'yiban':
				$this->db->where_not_in('b.status', [30, 31, 33, 34, 38, 39]);
				break;
			default:
				echo json_encode(['code' => 0, 'msg' => '参数错误！！']);
		}
	}













}