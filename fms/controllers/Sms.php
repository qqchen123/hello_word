<?php 



/**
 * @desc 短信控制器
 */
class Sms extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		$this->load->service('public/Sms_service', 'sms_service');
	}

	/**
	 * @name 发送验证码
	 * @url Sms/send_sms/{mobile}
	 */
	public function send_sms()
	{
		$mobile = $this->uri->segment(3);
		//@fixme  计划识别是否是手机号
		// ini_set("display_errors", "on"); // 显示错误提示，仅用于测试时排查问题
		// error_reporting(E_ALL); // 显示所有错误提示，仅用于测试时排查问题
		set_time_limit(0); // 防止脚本超时，仅用于测试使用，生产环境请按实际情况设置
		// header("Content-Type: text/plain; charset=utf-8"); // 输出为utf-8的文本格式，仅用于测试
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: POST, GET");

		$white_list = [
			'18717816183',
			// '18016349419',
		];

		if (in_array($mobile, $white_list)) {
			$flag = 0;//启用接口
		} else {
			$flag = 1;//启用接口
		}
		$this->load->service('public/Sms_service', 'sms_service');
		$this->load->model('public/SmsLog_model', 'smslog_model');
		$code = $this->sms_service->randStr();
		//写入日志
		$this->smslog_model->write_log($mobile, $code);
		if ($flag) {
			$ret = $this->sms_service->send_identifying_code($mobile, $code);
		} else {
			$ret = 1;
		}
		$this->smslog_model->update_log($mobile, $code, 1);
		//
		if ($ret > 0) {
			echo json_encode(['code' => 0, 'msg' => '发送成功', 'data' => $ret], JSON_UNESCAPED_UNICODE);
		} else {
			echo json_encode(['code' => -1, 'msg' => $ret .' 请稍后重试', 'data' => 0], JSON_UNESCAPED_UNICODE);
		}
	}

	/**
	 * @name 环境检测
	 */
	public function test()
	{
		$this->showpage('fms/sms/Test', []);
	}

}


?>
