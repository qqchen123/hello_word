<?php
/**
 * @desc 银信视图数据处理脚本
 */
class YxViewData extends Admin_Controller
{
	/**
	 * @name 更新银信借款订单信息数据
	 *		 更新当天的数据到表里
	 * @step 1.更新还款中的数据 | 借款标题只会在step中进行新增
	 * @step 2.同步还款完成的数据
	 * @step 3.检查数据完成情况记录日志
	 * @url /reptiles/YxViewData/update_yxorder
	 */
	public function update_yxorder()
	{
		$today = date('Y-m-d', time());
		$this->load->model('yx/YxOrder_model', 'yxorder_model');
		//step 1
		$this->load->model('yx/YxRepaying_model', 'yxrepaying_model');
		$this->load->model('yx/YxRepayingPlan_model', 'yxrepayingplan_model');
		$today_data = $this->yxrepaying_model->get_last_task_data();
		$today_update_data = $this->yxrepayingplan_model->get_today_update();
		$today_update_data = $this->deal_repayingplan_fields($today_update_data, $today_data);

		foreach ($today_data as $value) {
			$value = $this->deal_repaying_fields($value);
			$this->yxorder_model->record_data($value);
		}
		foreach ($today_update_data as $value) {
			$this->yxorder_model->record_data($value);
		}

		//step 2
		$this->load->model('yx/YxFinish_model', 'yxfinsih_model');
		$finish_list = $this->yxfinsih_model->get_day_data($today);
		$finish_list = $this->deal_finish_data($finish_list);

		foreach ($finish_list as $value) {
			$this->yxorder_model->record_data($value);
		}

		//step 3
		$this->load->model('WatchdogLog_model', 'watchdoglog_model');
		if (empty($this->yxorder_model->check_update_status())) {
			//维护正常 写入日志
			$data = [
				'type' => 'yx_view_data',
				'info' => '维护正常',
			];
		} else {
			//维护异常 保存错误信息到日志中
			$data = [
				'type' => 'yx_view_data',
				'info' => '维护异常',
				'remark' => '存在没有更新完成的记录',
			];
		}
		$this->watchdoglog_model->record_data($data);
		exit;
	}

	/**
	 * @name 对数据和字段类型进行转换 还款表
	 * @param $data array
	 * @return array
	 */
	private function deal_repaying_fields($data)
	{
		$data['amount'] = $data['loan_amount'];	//借款金额 单位分
		$data['step_total'] = explode('/', $data['periods'])[1];	//期数
		switch ($data['status']) {//'还款状态 0:未知 1:还款中 2:已完结 3:逾期 4:其他
			case '还款中':
				$data['status'] = 1;
				break;
			case '逾期':
				$data['status'] = 3;
				break;
			default:
				$data['status'] = 4;
				break;
		}
		return $data;
	}

	/**
	 * @name 对数据和字段类型进行转换 还款计划
	 * @param $data array
	 * @return array
	 */
	private function deal_repayingplan_fields($data, $repay_data)
	{
		//获取下列借款订单 下一次的还款时间
		//筛选方式 还款时间 > 今天 && 距离今天最近
		$today = date('Y-m-d 00:00:00', time());
		$array = [];
		//先处理 next_repay_time
		foreach ($data as $value) {
			$flag = 0;
			$value['loan_title'] = preg_replace('/新手专享\-/', '', $value['loan_title']);
			if (isset($array[$value['loan_title']])) {
				if ($value['repaying_time'] >= $today && $value['repaying_time'] < $array[$value['loan_title']]['next_repay_time']) {
					$flag = 1;
				}
			} else {
				if ($value['repaying_time'] >= $today) {
					$flag = 1;
				}
			}
			if ($flag) {
				switch ($value['status']) {
					case '待还款':
						$value['status'] = 1;
						break;
					case '已垫付':
						$value['status'] = 2;
						break;
					case '垫付后已还':
						$value['status'] = 3;
						break;
					case '已还款':
						$value['status'] = 4;
						break;
					case '借付':
						$value['status'] = 5;
						break;
					default://其他
						$value['status'] = 6;
						break;
				}
				$array[$value['loan_title']] = [
					'loan_title' => $value['loan_title'],
					'account' => $value['account'],
					'next_repay_time' => $value['repaying_time'],
					'step_num' => $value['periods'],
					'step_status' => $value['status'],
					'should_repaying_amount' => $value['should_repaying_amount'],
					'principal' => $value['principal'],
					'interest' => $value['interest'],
					'last_repay_time' => '0000-00-00 00:00:00',
					'last_step_status' => 6,//6：其他
				];
			}
		}


		//再处理 last_repay_time
		foreach ($data as $value) {
			$flag = 0;
			$value['loan_title'] = preg_replace('/新手专享\-/', '', $value['loan_title']);
			if (isset($array[$value['loan_title']])) {
				if ($value['repaying_time'] < $today && strtotime($value['repaying_time']) > strtotime($array[$value['loan_title']]['last_repay_time'])) {
					$flag = 1;
				}
			} else {
				if ($value['repaying_time'] < $today) {
					$flag = 1;
				}
			}
			if ($flag) {
				switch ($value['status']) {
					case '待还款':
						$value['status'] = 1;
						break;
					case '已垫付':
						$value['status'] = 2;
						break;
					case '垫付后已还':
						$value['status'] = 3;
						break;
					case '已还款':
						$value['status'] = 4;
						break;
					case '借付':
						$value['status'] = 5;
						break;
					default://其他
						$value['status'] = 6;
						break;
				}

				if (!isset($array[$value['loan_title']])) {
					$array[$value['loan_title']] = [
						'loan_title' => $value['loan_title'],
						'account' => $value['account'],
						'next_repay_time' => '0000-00-00 00:00:00',
						'step_num' => 1,
						'step_status' => 6,//6：其他  说明该借款已没有下期还款计划 
						'should_repaying_amount' => 0,
						'principal' => 0,
						'interest' => 0,
						'last_repay_time' => '0000-00-00 00:00:00',
						'last_step_status' => 6,//6：其他
					];
				}
				if ($array[$value['loan_title']]['step_num'] < $value['periods']) {
					$array[$value['loan_title']]['step_num'] = $value['periods'];
					$array[$value['loan_title']]['should_repaying_amount'] = $value['should_repaying_amount'];
					$array[$value['loan_title']]['principal'] = $value['principal'];
					$array[$value['loan_title']]['interest'] = $value['interest'];
				}
				$array[$value['loan_title']]['last_repay_time'] = $value['repaying_time'];
				$array[$value['loan_title']]['last_step_status'] = $value['status'];
			}
		}
		return $array;
	}

	/**
	 * @name 处理订单完成的数据
	 * @param $data array
	 * @return array
	 */
	private function deal_finish_data($data)
	{
		$array = [];
		foreach ($data as $value) {
			if ('已还清' == $value['f_status']) {
				//完结表中 只有已还清的数据是需要的
				//流标 初/终审拒绝 不需要同步
				//变更状态 通知将其余动态状态数据清除
				$array[] = [
					'loan_title' => $value['pname'],
					'account' => $value['yx_account'],
					'status' => 2,
					'next_repay_time' => '0000-00-00 00:00:00',
					'step_num' => 0,
					'step_status' => 0,
					'should_repaying_amount' => 0,
					'principal' => 0,
					'interest' => 0,
					'amount' => preg_replace('/\./', '', preg_replace('/\,/', '', $value['lend_money']))
				];
			}
		}
		return $array;
	}


	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->basicloadhelper();
	}

	/**
     * @name 加载基本的helper
     */
    private function basicloadhelper()
    {
        //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);
    }
}

?>