<?php 

/**
 * @desc 短信 接口请求日志
 */
class SmsLog_model extends Admin_model
{
	/**
	 * @name model 操作的表名
	 */
	public $table_name = 'fms_sms_log';

	public $lot_type = [
		'01' => '身份证一致性验证',
	];

	/**
	 * @name 默认显示的字段
	 */
	public $show_fields = [
		'mobile',
		'code',
		'ctime',
		'able_time'
	];

	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 写日志
	 * @other 这个方法计划落在 aliyun service中 自记录
	 * @param array|string|int $input 请求的业务入参
	 * @param int $tyoe 请求的类型
	 * @param string $url 请求的地址
	 * @return insert id
	 */
	public function write_log($mobile, $code)
	{
		$ctime = $lutime = date('Y-m-d H:i:s', time());
		$data = [
			"mobile" => $mobile,
			"code" => $code,
			"ctime" => $ctime,
			"able_time" => date('Y-m-d H:i:s', (time() + 60*5)),
			"status" => 0
		];
		$this->db->insert($this->table_name, $data);
		if ($this->db->affected_rows() > 0) {
        	$id = $this->db->insert_id();
            return $id;
        }
        return 0;
	}

	/**
	 * @name 更新日志
	 */
	public function update_log($mobile, $code, $status)
	{
		$this->db->set("status", $status);
		$this->db->where(" mobile = " . $mobile . " AND code = " . $code);
		$ret = $this->db->update($this->table_name);
	}

	/**
	 * @name 短信记录查询
	 */
	public function find_log($mobile)
	{
		return $this->db->where(" mobile = " . $mobile . " AND able_time >= '" . date('Y-m-d H:i:s') . "'")
		->order_by('ctime', 'DESC')
		->get($this->table_name)
		->row_array();
	}

}