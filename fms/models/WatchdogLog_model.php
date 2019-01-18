<?php

/**
 * @desc 脚本监控日志
 */
class WatchdogLog_model extends Admin_Model
{
	public $table_name = 'fms_watchdog_log';

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * table 字段
	 * c_time 不用维护进该数组
	 * 注意：增删字段时必须要维护下列数组
	 */
	public $fields_list = [
		'type',
		'info',
		'remark',
	];

	/**
	 * @name 新增记录 | 只有新增
	 * @param $data array 
	 * @return boole true|false
	 */
	public function record_data($data)
	{
		$array = [];
		//数据不存在 新增
		foreach ($this->fields_list as $value) {
			if (isset($data[$value]) && !empty($data[$value])) {
				$array[$value] = $data[$value];
			}
		}
		if (!empty($array)) {
			$array['c_time'] = date('Y-m-d H:i:s', time());
			return $this->db->insert(
				$this->table_name, 
				$array
			);
		} else {
			return false;
		}
	}

}

?>