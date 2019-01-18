<?php

class Little_house_price_model extends CI_Model {
	private $table = 'fms_minipro_house_price';
	private $upload_dir = '/home/upload/';

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 根据评估ID获取订单详情
	 * @return mixed
	 */
	public function get_order_detail_by_oid()
	{
		$oid = $this->input->get('oid');
		$lores = $this->db
			->select(
				'fms_baodan.openid as bd_openid,
				fms_baodan.user_name,
				fms_baodan.product_type,
				fms_baodan.zhiye_type,
				fms_baodan.get_money_term,
				fms_baodan.house_type,
				fms_baodan.house_type,
				fms_baodan.di_ya_type,
				fms_baodan.product_name,
				fms_baodan.c_time as bdc_time,
				fms_minipro_house_price.*'
			)
			->where('fms_baodan.admin_id', $_SESSION['fms_id'])
			->where('fms_minipro_house_price.id', $oid)
			->join('fms_baodan', 'fms_minipro_house_price.id = fms_baodan.house_price_id', 'left')
			->get('fms_minipro_house_price')
			->row_array();

		file_put_contents(
			$this->upload_dir.'get_order_detail_by_oid.txt',
			$_GET['oid'].'+'.json_encode($this->db->last_query()).'----'.date('Y-m-d H:i:s').'\n\r',
			FILE_APPEND
		);

		return $lores;
	}

}