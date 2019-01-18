<?php 

/**
 * @desc 公积金serivce
 */
class Gjj_service extends Admin_service
{
	/**
	 * @var table name 
	 */
	public $table_name = 'gjj_report';
	
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
	 * @name 从魔蝎获取公积金报告 落地到mongo
	 * @url https://api.51datakey.com/report/api/v1/fund/{taskId}
	 * @param $mobile string
	 * @return boolean
	 */
	public function record_report($task_id)
	{
		$path = '/report/api/v1/fund/' . $task_id;
		$report = $this->moxie->exec_curl_get_data('', $path);
		$report = ArrayHelper::object_to_array($report);
		if (!empty($report)) {
			//获取身份证
			if (!empty($report['user_basic_info']['certificate_number'])) {
				$idnumber = $report['user_basic_info']['certificate_number'];
			} else {
				$idnumber = '';
			}
			$res = $this->mongo_manager->insert(['data' => $report, 'task_id' => $task_id, 'idnumber' => $idnumber]);
			if ($res) {
				Slog::log('公积金报告落地成功');
				return true;
			} else {
				Slog::log('公积金报告写入失败');
			}
		} else {
			Slog::log('获取公积金报告失败');
		}
		return false;
	}

	/**
	 * @name 获取公积金报告
	 * @param $idnumber string
	 * @return array
	 */
	public function get_report($idnumber)
	{
		if ($idnumber) {
			$result = $this->mongo_manager
			->select(['task_id', 'data', 'idnumber'])
			->where(['idnumber' => $idnumber])
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