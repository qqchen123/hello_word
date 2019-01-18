<?php 

/**
 * @desc 用户渠道表
 */
class Channel_model extends Admin_model
{
	/**
	 * @name model 操作的表名
	 */
	public $table_name = 'fms_qudao';

	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 获取渠道列表
	 */
	public function get_channel_list()
	{
		$ret = $this->db->select(['q_name','q_code'])
		->get_where($this->table_name, ['q_status' => 1])
		->result_array();
        return $ret; 
	}

}