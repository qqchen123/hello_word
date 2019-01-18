<?php 

/**
 * @desc 回调控制器
 */
class CallBack extends Admin_Controller {

	public function __construct() 
	{
		parent::__construct();
		$this->load->service('public/Moxie_service', 'moxie_service');
	}

	/**
	 * @name index
	 * @url CallBack/index
	 */
	public function index()
	{
		echo '回调控制器';
		// $this->load->service('bestsign/BestSign_service', 'bs');
		// $this->bs->tt();
	}

	/**
	 * @name 创建任务  测试入口
	 * @url CallBack/create_task/{fuserid}
	 * http://120.26.89.131:60523/fms/index.php/CallBack/create_task/fuserid
	 */
	public function create_task()
	{
		$fuseid = $this->uri->segment(3);
		$this->load->model('public/MoxieCallback_model', 'MoxieCallback_model');
		$this->MoxieCallback_model->create_task_all($fuseid);
	}

	/**
	 * @name 接收任务ID
	 * @url CallBack/get_task_id/{type}
	 * http://h5.yuandoujinfu.com/apis/fms/index.php/CallBack/get_task_id/yys_report
	 */
	public function get_task_id()
	{
		$type = $this->uri->segment(3);
		$res = $this->get_request();
		Slog::log('接收到的数据');
		Slog::log($res);
		$ret = $this->moxie_service->task_call_back($res, $type);
		if ($ret) {
			$this->_response('接收通知成功', 201);
		} else {
			$this->_response('接收通知失败', 200);
		}
		exit;
	}

	/**
	 * @name 接收状态
	 * @url CallBack/get_login_status/{type}
	 * http://120.26.89.131:60523/fms/index.php/CallBack/get_login_status/yys_report
	 */
	public function get_login_status()
	{
		$type = $this->uri->segment(3);
		$res = $this->get_request();
		Slog::log($res);
		$ret = $this->moxie_service->login_status_call_back($res, $type);
		if ($ret) {
			$this->_response('接收状态成功', 201);
		} else {
			$this->_response('接收状态失败', 200);
		}
		exit;
	} 

	/**
	 * @name 账单通知
	 * @url CallBack/get_bill_status/{type}
	 * http://120.26.89.131:60523/fms/index.php/CallBack/get_bill_status/yys_report
	 */
	public function get_bill_status()
	{
		$type = $this->uri->segment(3);
		$res = $this->get_request();
		$ret = $this->moxie_service->bill_status_call_back($res, $type);
		if ($ret) {
			$this->_response('接收状态成功', 201);
		} else {
			$this->_response('接收状态失败', 200);
		}
		exit;
	} 

	/**
	 * @name 报告通知
	 * @url CallBack/get_report_status/{type}
	 * http://120.26.89.131:60523/fms/index.php/CallBack/get_report_status/yys_report
	 */
	public function get_report_status()
	{
		$type = $this->uri->segment(3);
		$res = $this->get_request();
		Slog::log($res);
		$ret = $this->moxie_service->report_status_call_back($res, $type);
		if ($ret) {
			$this->_response('接收状态成功', 201);
		} else {
			$this->_response('接收状态失败', 200);
		}
		exit;
	} 

	/**
	 * @name 任务采集失败通知
	 * @url CallBack/get_fail_response/{type}
	 * http://120.26.89.131:60523/fms/index.php/CallBack/get_fail_response/yys_report
	 */
	public function get_fail_response()
	{
		$type = $this->uri->segment(3);
		$res = $this->get_request();
		Slog::log($res);
		$ret = $this->moxie_service->task_fail_call_back($res, $type);
		if ($ret) {
			$this->_response('接收状态成功', 201);
		} else {
			$this->_response('接收状态失败', 200);
		}
		exit;
	}

	/**
	 * @name 接收内容
	 */
	public function get_request()
	{
		if (!empty($_POST)) {
			$res = $_POST;
        } else {
            $body = @file_get_contents('php://input');
            $tmp = json_decode($body);
            if (!empty($tmp)) {
            	$res = ArrayHelper::object_to_array($tmp);
            } else {
            	$res = [];
            }
        }
        //如果不行就尝试从get取值
        if (empty($res)) {
        	$res = $_GET;
        	//处理
        	if (isset($res['taskId'])) {
        		$res['task_id'] = $res['taskId'];
        		unset($res['taskId']);
        	}
        	Slog::log($res);
        }
        return $res;
	} 

	/**
     * @name 处理curl返回的内容
     */
    public function exec_curl_get($host, $querys, $appcode, $path)
    {
        $ret = [];

        $str = '';
        foreach ($querys as $key => $value) {
            if (empty($str)) {
                $str .= $key . '=' . $value;
            } else {
                $str .= '&' . $key . '=' . $value;
            }
        }

        $querys = $str;
        $method = "GET";
        $headers = array();
        array_push($headers, "Authorization:token " . $appcode);
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $response = curl_exec($curl);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
            $ret = json_decode($body, JSON_UNESCAPED_UNICODE);
        } else {
            $ret = ['error' => curl_getinfo($curl, CURLINFO_HTTP_CODE)];
        }
        return $ret;
    }

	/**
	 * @name 魔蝎 主动触发回调通知
	 * @url CallBack/update_call_back_event
	 * @event bill,report
	 */
	public function update_call_back_event()
	{
		$host = "https://api.51datakey.com";
        $path = "/gateway/callback/v1/recall";
        $appcode = 'd74ea104c9274d40aaf873eb972cfa87';

		$this->load->model('public/MoxieCallback_model', 'moxiecallback_model');
		$variable = $this->moxiecallback_model->get_active_list();

		foreach ($variable as $value) {
			if (!$value['bill_status']) {
				$str = 'bill';
			}
			if (!$value['login_status']) {
				$str += empty($str) ? 'task' : ',task';
			}
			if ($value['report_status']) {
				$str += empty($str) ? 'report' : ',report';
			}
			
			$querys = [
				'taskId' => $value['task_id'],
				'userId' => '',
				'event' => $str
			];
			$ret = $this->exec_curl_get($host, $querys, $appcode, $path);
			Slog::log('回调内容 : ' . $str . ' task_id : ' . $value['task_id']);
			Slog::log($ret);
			sleep(1);
		}
		exit;
	}

}


?>
