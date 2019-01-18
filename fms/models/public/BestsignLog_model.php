<?php 

/**
 * @desc 上上签服务日志
 */
class BestsignLog_model extends Admin_model
{
	/**
	 * @name model 操作的表名
	 */
	public $table_name = 'fms_bestsign_log';

	public $lot_type = [
		// '01' => '身份证一致性验证',
	];

	/**
	 * @name 默认显示的字段
	 */
	public $show_fields = [
		'fuserid',
		'contractId',
		'ctime',
		'type'
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
	 * @param array|string|int $input 请求的业务入参
	 * @param int $tyoe 请求的类型
	 * @param string $url 请求的地址
	 * @return insert id
	 */
	public function write_log($fuserid, $contractId, $filename, $type, $contract)
	{
		$ctime = $lutime = date('Y-m-d H:i:s', time());
		$data = [
			"fuserid" => $fuserid,
			"type" => $type,
			"contract" => $contract,
			"contractId" => $contractId,
			"filename" => $filename,
			"ctime" => $ctime,
			"lutime" => $lutime
		];
		$this->db->insert($this->table_name, $data);
		if ($this->db->affected_rows() > 0) {
        	$id = $this->db->insert_id();
            return $id;
        }
        return 0;
	}

	/**
	 * @name 记录返回结果
	 * @param int $id 更新的ID
	 * @param json string $result 请求返回结果
	 * @param int $code 接口业务码 主要代表实际内容是否有效而不是接口是否通
	 * @param array $content 接口返回的内容 已除去业务码
	 */
	public function update_log($id, $result, $code, $content)
	{
		$this->db->set("result", json_encode($result, JSON_UNESCAPED_UNICODE));
		$this->db->set("code", $code);
		$this->db->set("content", json_encode($content, JSON_UNESCAPED_UNICODE));
		$this->db->where(" id = " . $id);
		$ret = $this->db->update($this->table_name);
	}

	/**
	 * @name 查找合同
	 */
	public function find_log($fuserid) 
	{
		return $this->db->where(" fuserid = '" . $fuserid . "'")
		->get($this->table_name)
		->result_array();
	}


}