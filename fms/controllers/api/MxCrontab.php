<?php

/**
 * 
 */
class MxCrontab extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();
		// //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);
	}

	/**
	 * @name 落地账单数据
	 * 获取24小时内 授权的银行卡信息
	 * @param $time string 日期
	 * @param $fuserid string 用户ID
	 * @url /api/MxCrontab/record_bill_data
	 */
	public function record_bill_data($time = '', $fuserid = '')
	{
		$config = $_GET;
        if (!empty($time) || !empty($fuserid)) {
            $config = [
                'time' => $time,
                'fuserid' => $fuserid,
            ];
        }
        var_dump($config);
        //
        if (empty($config['time']) || strtotime($time) <= strtotime('2018-12-01')) {
        	$config['time'] = date('Y-m-d H:i:s', time()-86400);
        }

        //
		var_dump('任务开始 ' . date('Y-m-d H:i:s', time()));
		$this->load->service('public/Bank_service', 'bank_service');
		$this->load->service('public/BankBills_service','bankbills_service');
		if (!empty($config['fuserid'])) {
			//检查用户
			$this->load->service('user/User_service', 'user_service');
			$ret = $this->user_service->find_user_by_fuserid($config['fuserid']);
			if ($ret) {
				//用户存在 获取用户银行卡回调信息
				$this->load->model('public/MoxieCallback_model', 'moxiecallback_model');
				$task_list_temp = $this->moxiecallback_model->find_task($config['fuserid'], 'bank_card');
				if (!empty($task_list_temp['task_id'])) {
					$task_list = [];
					$task_list[] = $task_list_temp;
				}
			}	
		}
		if (!isset($task_list) || empty($task_list)) {
			$task_list = $this->bank_service->mongo_manager
			->select(['task_id'])
			->where_gte('ctime', $config['time'])
			->find();
		}
		foreach ($task_list as $value) {
			if (!isset($value['task_id'])) {
				continue;
			}
			var_dump($value['task_id']);
			$data = $this->bank_service->mongo_manager
			->select(['data'])
			->where(['task_id' => $value['task_id']])
			->find_one();
			if (!isset($data['data']['allcards-ex2'])) {
				continue;
			}
			$data = $data['data']['allcards-ex2'];
			// var_dump($data[0]['bills']);
			//获取账单信息
			if (!empty($data[0]['bills'])) {
				foreach ($data[0]['bills'] as $item) {
					// var_dump($item);
					$bill_id = $item['bill_id'];
					// var_dump($bill_id);
					//去检查是否已落地
					if (empty($this->bankbills_service->check_bill_id($bill_id, $value['task_id']))) {
						//未落地 请求魔蝎 获取
						var_dump($bill_id);
						$this->bankbills_service->record_report($bill_id, $value['task_id']);
					}
				}
			}
		}
		var_dump('任务结束 ' . date('Y-m-d H:i:s', time()));
		exit;	
	}



}

?>

