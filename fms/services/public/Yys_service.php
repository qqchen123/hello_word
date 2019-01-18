<?php 

/**
 * @desc 运营商serivce
 */
class Yys_service extends Admin_service
{
	/**
	 * @var table name 
	 */
	public $table_name = 'yys_report';
	
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
	 * @name 从魔蝎获取运营商报告 落地到mongo
	 * @url https://api.51datakey.com/carrier/v3/mobiles/$mobile/mxreport
	 * @param $mobile string
	 * @return boolean
	 */
	public function record_report($mobile, $task_id = '')
	{
		$path = '/carrier/v3/mobiles/' . $mobile . '/mxreport';
		$report = $this->moxie->exec_curl_get_data('', $path);
		$report = ArrayHelper::object_to_array($report);
		if (!empty($report['report'])) {
			if (!empty($task_id)) {
				$res = $this->mongo_manager->insert(['data' => $report, 'mobile' => $mobile, 'task_id' => $task_id]);
			} else {
				$res = $this->mongo_manager->insert(['data' => $report, 'mobile' => $mobile]);
			}
			if ($res) {
				Slog::log('运营商报告落地成功');
				return true;
			} else {
				Slog::log('运营商报告写入失败');
			}
		} else {
			Slog::log('获取运营商报告失败');
		}
		return false;
	}

	/**
	 * @name 获取运营商原始报告
	 * @url https://api.51datakey.com/carrier/v3/mobiles/{mobile}/mxdata-ex?task_id={task_id}
	 * @param string $task_id
	 * @param string $mobile
	 * @return boolean
	 */
	public function record_original_report($mobile, $task_id)
	{
		$path = '/carrier/v3/mobiles/' . $mobile . '/mxdata-ex?task_id=' . $task_id;
		$report = $this->moxie->exec_curl_get_data('', $path);
		$report = ArrayHelper::object_to_array($report);
		if (!empty($report)) {
			$res = $this->mongo_manager->update(['mobile' => $mobile, 'task_id' => $task_id], ['original_data' => $report]);
			if ($res) {
				Slog::log('运营商原始报告落地成功');
				return true;
			} else {
				Slog::log('运营商原始报告写入失败');
			}
		} else {
			Slog::log('获取运营商原始报告失败');
		}
		return false;
	}

	/**
	 * @name 从魔蝎获取运营商报告 落地到mongo 更新
	 * @url https://api.51datakey.com/carrier/v3/mobiles/$mobile/mxreport
	 * @param $mobile string
	 * @return boolean
	 */
	public function update_report($mobile, $task_id = '')
	{
		$path = '/carrier/v3/mobiles/' . $mobile . '/mxreport';
		$report = $this->moxie->exec_curl_get_data('', $path);
		$report = ArrayHelper::object_to_array($report);
		if (!empty($report['report'])) {
			if (!empty($task_id)) {
				$res = $this->mongo_manager->update(['mobile' => $mobile], ['data' => $report, 'mobile' => $mobile, 'task_id' => $task_id]);
			} else {
				$res = $this->mongo_manager->update(['mobile' => $mobile], ['data' => $report, 'mobile' => $mobile]);
			}
			if ($res) {
				Slog::log('运营商报告落地成功');
				return true;
			} else {
				Slog::log('运营商报告写入失败');
			}
		} else {
			Slog::log('获取运营商报告失败');
		}
		return false;
	}

	/**
	 * @name 获取运营商报告
	 * @param $mobile string
	 * @return array
	 */
	public function get_report($mobile = '', $user_id = '')
	{
		if ($mobile || $user_id) {
			if ($mobile) {
				$result = $this->mongo_manager
				->select(['mobile', 'data', 'fuserid'])
				->where(['mobile' => $mobile])
				->find_one();
			} else {
				$result = $this->mongo_manager
				->select(['mobile', 'data', 'fuserid'])
				->where(['fuserid' => $user_id])
				->find_one();
			}
		} else {
			$result = [];
			Slog::log('手机号输入错误');
		}
		return $result;
	}

	/**
	 * @name 获取原始报告数据
	 * @param $mobile string
	 * @return array
	 */
	public function get_original_report($mobile = '', $user_id = '')
	{
		if ($mobile || $user_id) {
			if ($mobile) {
				$result = $this->mongo_manager
				->select(['mobile', 'original_data', 'fuserid'])
				->where(['mobile' => $mobile])
				->find_one();
			} else {
				$result = $this->mongo_manager
				->select(['mobile', 'original_data', 'fuserid'])
				->where(['fuserid' => $user_id])
				->find_one();
			}
		} else {
			$result = [];
			Slog::log('手机号输入错误');
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

	public function insert()
	{
		$bulk = new MongoDB\Driver\BulkWrite;
		$document = ['_id' => new MongoDB\BSON\ObjectID, 'name' => '测试111'];

		$_id= $bulk->insert($document);

		var_dump($_id);

		$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  
		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		$result = $manager->executeBulkWrite('test.runoob', $bulk, $writeConcern);
		Slog::log(json_encode($result));
		return $result;
	}

	/**
	 * @name
	 */
	public function update()
	{
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->update(
		    ['x' => 2],
		    ['$set' => ['name' => '菜鸟工具', 'url' => 'tool.runoob.com']],
		    ['multi' => false, 'upsert' => false]
		);

		$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  
		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		$result = $manager->executeBulkWrite('test.sites', $bulk, $writeConcern);
	}

}