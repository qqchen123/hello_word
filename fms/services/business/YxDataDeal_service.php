<?php

/**
 * @desc 银信数据整理
 */
class YxDataDeal_service extends Admin_service
{
	/**
	 * @var array 充值提现汇总 中文映射修正
	 * @other ['mysql字段名' => '爬虫数据字段名']
	 */
	private $rw_collect_map = [
		'登录用户名' => '登录用户名',
		'属性' => '属性',
		'流水号' => '流水号',
		'时间' => '时间',
		'金额' => '金额',
		'状态' => '状态',
	];

	private $table_name = 'data_temp';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Sqlserv_model', 'sqlserv_model');
		$this->load->library('mongo_manager', ['table_name' => $this->table_name]);
	}

	/**
	 * @name 更新充值提现汇总信息到 myexcel 充值提现汇总_主表
	 */
	public function deal_rw_collect($start_time = '', $end_time = '', $type = 0)
	{
		$feild_info = $this->sqlserv_model->get_field_by_zn_name('充值提现汇总_主表');
		//字段信息检查 如果发现字段信息出现不匹配的记录到日志 数据更新直接退出 
		foreach ($feild_info as $value) {
			if (!isset($this->rw_collect_map[$value['feildName']])) {
				//字段异常 后续安排建立日志表直接查看
				Slog::log('myexcel模板 字段异常');
				exit;
			}
		}

		if (empty($start_time)) {
			$start_time = date('Y-m-d 0:0:0', time());
		}

		$data = $this->mongo_manager
		->where_gte('ctime', $start_time)
		->where(['type' => 'rw_collect'])
		->find();
		var_dump($data);
		exit;
	}
}

?>

