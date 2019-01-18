<?php 

/**
 * @desc 网银账单serivce
 */
class BankBills_service extends Admin_service
{
	/**
	 * @var table name 
	 */
	public $table_name = 'bankbills_report';
	
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library('mongo_manager', ['table_name' => $this->table_name]);
		$this->load->service('public/Moxie_service', 'moxie');
		$this->load->helper(['array']);
	}

	/**
	 * @name 从魔蝎获取网银账单报告 落地到mongo
	 * @url https://api.51datakey.com/bank/v3/bills/{bill_id}/expense-records?page={page}&limit={limit}
	 * @param $bill_id string
	 * @param $task_id string
	 * @return boolean
	 */
	public function record_report($bill_id, $task_id)
	{
		$page = 1;
		$limit = 30;
		$report_temp = [];
		$report['total_size'] = 100;
		//检查数量
		for ($page = 1; $page*$limit - $report['total_size'] < 0; $page++) {
			$path = '/bank/v3/bills/' . $bill_id . '/expense-records?page=' . $page . '&limit=' . $limit;
			$report = $this->moxie->exec_curl_get_data('', $path);
			$report = ArrayHelper::object_to_array($report);
			$report_temp[] = $report;
		}
		$report = $report_temp;
		if (!empty($report)) {
			$res = $this->mongo_manager->insert(['data' => $report, 'task_id' => $task_id, 'bill_id' => $bill_id], 'bankbills_report');
			if ($res) {
				Slog::log('网银账单报告落地成功');
				return true;
			} else {
				Slog::log('网银账单报告写入失败');
			}
		} else {
			Slog::log('获取网银账单报告失败');
		}
		return false;
	}

	/**
	 * @name 获取网银报告
	 * @param $task_id string
	 * @param $bill_id string
	 * @return array
	 */
	public function get_report($bill_id, $task_id)
	{
		if ($bill_id && $task_id) {
			$result = $this->mongo_manager
			->select(['task_id', 'data', 'bill_id'])
			->where(['task_id' => $task_id, 'bill_id' => $bill_id])
			->find_one();
		} else {
			$result = [];
			Slog::log('输入错误');
		}
		return $result;
	}

	public function get_all_bills_report($task_id)
	{
		if ($task_id) {
			$result = $this->mongo_manager
			->select(['task_id', 'data', 'bill_id'])
			->where(['task_id' => $task_id])
			->find();
		} else {
			$result = [];
			Slog::log('输入错误');
		}
		return $result;
	}

	/**
	 * @name 检查网银账单记录是否存在
	 * @param $task_id string
	 * @param $bill_id string
	 * @return array
	 */
	public function check_bill_id($bill_id, $task_id) 
	{
		if ($bill_id && $task_id) {
			$result = $this->mongo_manager
			->select(['task_id', 'bill_id'])
			->where(['task_id' => $task_id, 'bill_id' => $bill_id])
			->find_one('bankbills_report');
		} else {
			$result = [];
			Slog::log('输入错误');
		}
		return $result;
	}

	/**
	 * @name 测试
	 */
	public function test()
	{
		$res = $this->mongo_manager
			->insert(['name' => 'asdasda']);
		return $res;

		Slog::log(1);
		// $order_by = ['id' => -1];
		// $where    = ['status' => 1];
		$result = $this->mongo_manager
					->where(['name'=>'asdasd'])
		            ->select(['name'])
		            // ->order_by($order_by)
		            // ->limit(21)
		            // ->where_between('time_stamp', $start_date, $end_date)
		            // ->where($where)
		            ->find();
		Slog::log(json_encode($result));
		return $result;
	}
}