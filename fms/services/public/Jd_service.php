<?php 

/**
 * @desc 京东serivce
 */
class Jd_service extends Admin_service
{
	/**
	 * @var table name 
	 */
	public $table_name = 'jd_report';
	
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
	 * @name 从魔蝎获取京东报告 落地到mongo
	 * @url https://api.51datakey.com/gateway/jingdong/v6/data/{task_id}
	 * @param $mobile string
	 * @return boolean
	 */
	public function record_report($task_id)
	{
		$path = '/gateway/jingdong/v6/data/' . $task_id;
		$report = $this->moxie->exec_curl_get_data('', $path);
		$report = ArrayHelper::object_to_array($report);
		if (!empty($report)) {
			$res = $this->mongo_manager->insert(['data' => $report, 'task_id' => $task_id]);
			if ($res) {
				Slog::log('京东报告落地成功');
				return true;
			} else {
				Slog::log('京东报告写入失败');
			}
		} else {
			Slog::log('获取京东报告失败');
		}
		return false;
	}

	/**
	 * @name 获取京东报告
	 * @param $task_id string
	 * @return array
	 */
	public function get_report($task_id)
	{
		if ($task_id) {
			$result = $this->mongo_manager
			->select(['task_id', 'data'])
			->where(['task_id' => $task_id])
			->find_one();
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