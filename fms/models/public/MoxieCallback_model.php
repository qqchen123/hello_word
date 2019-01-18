<?php

/**
 * 
 */
class MoxieCallback_model extends Admin_model
{
	/**
	 * @name model 操作的表名
	 */
	public $table_name = 'fms_moxie_callback';


	public $report_type = [
		'yys',
		'jd',
		'taobao',
		'credit_card',
		'bank_card',
		'gjj'
	];

	/**
	 * @name 默认显示的字段
	 */
	public $show_fields = [
		'fuserid',
		'type',
		'status',
	];

	/**
	 * @name task status enum
	 */
	public $status_map = [
		'task_failure' => -6,//任务失败
		'report_failure' => -5,//报告失效
		'report_record_failure' => -4,//报告落地失败
		'report_get_failure' => -3,//报告获取失效
		'login_failure' => -2,//授权登录失败
		'task_create_failure' => -1,//任务创建失败
		'unknow' => 0,//未知
		'task_creating' => 1,//任务创建中
		'task_create_success' => 2,//任务创建成功
		'logging' => 3,//登录中
		'login_success' => 4,//登录成功
		'report_waiting' => 5,//报告获取中
		'report_get_success' => 6,//报告获取成功
		'report_recording' => 7,//落地中
		'report_record_success' => 8,//落地成功
		'report_able' => 9,//生效中
	];

	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 创建记录
	 * @other 用户开户后创建
	 * @param array|string|int $input 请求的业务入参
	 * @param int $tyoe 请求的类型
	 * @param string $url 请求的地址
	 * @return insert id
	 */
	public function create_task($fuserid, $type)
	{
		if (empty($fuserid) || empty($type)) {
			Slog::log('入参错误 ' . $fuserid . ' || ' . $type);
			return true;
		}
		$check = $this->find_task($fuserid, $type);
		if (!empty($check)) {
			//已存在直接返回
			return true;
		}
		$ctime = $lutime = date('Y-m-d H:i:s', time());
		$data = [
			"type" => $type,
			"fuserid" => $fuserid,
			"ctime" => $ctime,
			"lutime" => $lutime,
			'is_del' => 0
		];
		$this->db->insert($this->table_name, $data);
		if ($this->db->affected_rows() > 0) {
        	$id = $this->db->insert_id();
            return $id;
        }
        return 0;
	}

	/**
	 * @name 创建全部任务
	 */
	public function create_task_all($fuserid)
	{
		$check = $this->db->where(' fuserid = \'' . $fuserid . '\' AND  is_del = 0')
		->get($this->table_name)->result_array();
		if (count($check) == count($this->report_type)) {
			//任务已创建  不用再创建
			Slog::log('任务已创建  不用再创建');
		} else {
			foreach ($this->report_type as $value) {
				$id = $this->create_task($fuserid, $value);
				Slog::log('创建任务 ' . $value . ',' .  $fuserid . ' id: ' . $id);
			}
		}
	}

	/**
	 * @name 更新记录
	 * @param int $id 更新的ID
	 * @param json string $result 请求返回结果
	 * @param int $code 接口业务码 主要代表实际内容是否有效而不是接口是否通
	 * @param array $content 接口返回的内容 已除去业务码
	 */
	public function update_task($condition, $type, $data)
	{
		foreach ($data as $key => $value) {
			$this->db->set($key, $value);
		} 
		if (10 >= strlen($condition)) {
			$this->db->where(" fuserid = '" . $condition . "' AND type = '" . $type . "' AND is_del = 0");
		} else {
			$this->db->where(" task_id = '" . $condition . "' AND type = '" . $type . "' AND is_del = 0");
		}
		return $this->db->update($this->table_name);
	}

	/**
	 * @name 查询某一个任务
	 */
	public function find_task($fuserid, $type) 
	{
		$task_info = $this->db->where(" fuserid = '" . $fuserid . "' AND type = '" . $type . "' AND is_del = 0 ")
		->get($this->table_name)
		->row_array();
		Slog::log($this->db->last_query());
		Slog::log('find task');
		Slog::log($task_info);
		return $task_info;
	}

	/**
	 * @name 获取全部任务
	 */
	public function find_task_all($fuserid)
	{
		$task_all_info = $this->db->where(" fuserid = '" . $fuserid . "' AND is_del = 0 ")
		->get($this->table_name)
		->result_array();
		// Slog::log('find task');
		// Slog::log($task_all_info);
		return $task_all_info;
	}

	public function find_by_task_id($task_id)
	{
		return $this->db->where(" task_id = '" . $task_id . "' AND is_del = 0 ")
		->get($this->table_name)
		->row_array();
	}

	public function get_active_list()
	{
		return $this->db
		->select([
			'task_id',
			'type',
			'login_status',
			'bill_status',
			'report_status',
		])
		->where(' is_del = 0 AND create_status = 1 AND (login_status = 0 OR bill_status = 0 OR report_status = 0)')
		->get($this->table_name)
		->result_array();
	}
}

/*
CREATE TABLE `fms_moxie_callback` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `task_id` varchar(128) NOT NULL COMMENT '任务ID',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `create_status` tinyint(4) DEFAULT '0' COMMENT '任务创建状态 1:成功 0:没有回执 2:失败',
  `login_status` tinyint(4) DEFAULT '0' COMMENT '授权登录状态 1:成功 0:没有回执 2:失败',
  `bill_status` tinyint(4) DEFAULT '0' COMMENT '账单状态 1:成功 0:没有回执 2:失败',
  `report_status` tinyint(4) DEFAULT '0' COMMENT '报告状态 1:成功 0:没有回执 2:失败',
  `record_status` tinyint(4) DEFAULT '0' COMMENT '落地状态 1:成功 0:没有回执 2:失败',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 0:未知 1:任务创建中 2:任务创建成功 3:授权登录中 4:授权登录成功 5:报告获取中 6:报告获取成功 7:落地中 8:落地成功 9:生效中 -1:任务创建失败 -2:授权登录失败 -3:报告获取失败 -4:报告落地失败 -5:报告失效',
  `fuserid` varchar(20) DEFAULT NULL COMMENT 'fuserid',
  `ctime` datetime DEFAULT NULL,
  `lutime` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_del` tinyint(4) DEFAULT '0' COMMENT '是否删除  0:未删除  1:已删除',
  `report_message` varchar(255) DEFAULT NULL COMMENT '报告message',
  `task_failure_message` varchar(255) DEFAULT NULL COMMENT '任务失败message',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fuid_type_del` (`type`,`fuserid`,`is_del`) USING BTREE COMMENT 'fuserid_type_is_del 唯一',
  KEY `task_id` (`task_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
*/


?>
