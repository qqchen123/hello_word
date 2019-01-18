<?

/**
 * @desc 银信数据统计页面
 */
class YxStatis extends Admin_Controller
{
	
	function __construct()
	{
		parent::__construct();
		// $this->load->model('ViewLog_model', 'viewlog_model');
	}

	/**
	 * @url /YxStatis/test
	 */
	public function test()
	{
		$this->load->model('yx/YxRepaying_model', 'yxrepaying_model');
		$ret = $this->yxrepaying_model->get_repaying_total_amount();
		var_dump($ret);
		exit;
	}

	/**
	 * @name 银信基础统计页
	 * @url /YxStatis/YxBaseStatis
	 */
	public function YxBaseStatis()
	{
		$this->load->model('yx/YxRepaying_model', 'yxrepaying_model');
		$this->load->model('yx/YxAccount_model', 'yxaccount_model');
		$this->load->model('yx/YxOutMoney_model', 'yxoutmoney_model');
		$this->load->model('yx/YxFinish_model', 'yxfinsih_model');
		$total = $this->yxaccount_model->get_account_total();
		$master_total = $this->yxoutmoney_model->get_total();

		$total_amount = $this->yxrepaying_model->get_repaying_total_amount();
		$repaying_total = $this->yxrepaying_model->get_repaying_username_total();
		$repaying_order_total = $this->yxrepaying_model->get_repaying_order_total();
		$acctAmount = $this->yxrepaying_model->get_repaying_account_acct_amount();
		$finish_total = $this->yxfinsih_model->get_finish_username_total();
		$finish_total_acctAmount = $this->yxfinsih_model->get_finish_user_acctAmount_total();

		$no_loan_total = $this->yxaccount_model->get_no_loan_total();
		$no_loan_total_acctAmount = $this->yxaccount_model->get_no_loan_total_acct_amount();

		//检查是否在更新
		$repaying_time = $this->yxrepaying_model->get_last_time();
		if ((time() - strtotime($repaying_time)) < 10*60) {
			if ((time() - strtotime($repaying_time)) < 1*60) {
				$progress = $this->yxrepaying_model->get_working_progress();
				$rate = round($progress/$total*100,2);
				$flag = '数据爬取中，请稍后查看，爬取完成度：' . $rate . '%';
			} else {
				$flag = '数据整理中，预计' . ceil(10 - (time() - strtotime($repaying_time))/60) . '分钟内整理完成，请稍后查看';
			}
		} else {
			$flag = '更新完成 更新时间：' . date('Y-m-d H:i:s', strtotime($repaying_time)+10*60);
		}
		$data = [
			'total' => $total,
			'master_total' => $master_total,
			'repaying_total' => $repaying_total,
			'total_amount' => $total_amount,
			'repaying_order_total' => $repaying_order_total,
			'acct_amount' => $acctAmount,
			'finish_total' => $finish_total,
			'finish_total_acctAmount' => $finish_total_acctAmount,
			'no_loan_total' => $no_loan_total,
			'no_loan_total_acctAmount' => $no_loan_total_acctAmount,
			'flag' => $flag,
		];
		$this->showpage('fms/statis/base', $data);
	}

	/**
	 * @name 还款计划
	 * @url /YxStatis/RepayingPlan
	 */
	public function RepayingPlan()
	{
		$data = [];

		$today = date('Y-m-d 00:00:00');

		//当周
		//当前日期
		$sdefaultDate = date("Y-m-d");
		//$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
		$first = 2;
		//获取当前周的第几天 周日是 0 周一到周六是 1 - 6
		$w = date('w',strtotime($sdefaultDate));
		//获取本周开始日期，如果$w是0，则表示周日，减去 6 天
		$week_start = date('Y-m-d',strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days'));
		//本周结束日期
		$week_end = date('Y-m-d',strtotime("$week_start +6 days"));
		// echo "$week_start"."$week_end";
		$week_start = strtotime($week_start);
		$week_end = strtotime($week_end);

		//下周
		$nextweek_start = $week_start+86400*7;
		$nextweek_end = $week_end+86400*7;

		//当月
		$month_start = mktime(0, 0 , 0,date("m"),1,date("Y"));
		$month_end = mktime(23,59,59,date("m"),date("t"),date("Y"));

		//获取各周时间点
		$data = [
			'today' => $today,
			'week_start' => $week_start,
			'week_end' => $week_end,
			'nextweek_start' => $nextweek_start,
			'nextweek_end' => $nextweek_end,
			'month_start' => $month_start,
			'month_end' => $month_end,
		];
		// $this->viewlog_model->record_data('repaying_plan');
		$this->showpage('fms/statis/repaying_plan', $data);
	}

	/**
	 * @name 还款计划数据(待还和垫付版)
	 * @url /YxStatis/RepayingPlanPriv
	 */
	public function RepayingPlanPriv()
	{
		$data = [];

		$firstday=date('Y-m-01',time());
		$lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));

		$this->load->model('yx/YxOrder_model', 'yxorder_model');
		$ret = $this->yxorder_model->find_by_date($firstday, $lastday);
		
		$accounts = [];
		$this->load->model('yx/YxRepayingPlan_model', 'yxrepayingplan_model');
		foreach ($ret as $key => $value) {
			$accounts[] = $value['account'];
		}
		$this->load->model('user/User_model', 'user_model');
		$user_info = $this->user_model->get_userbasicinfo_by_account($accounts);
		
		//合并用户基础信息
		foreach ($ret as $key => $value) {
			foreach ($user_info as $uvalue) {
				if ($value['account'] == $uvalue['account']) {
					$value['status'] = $this->translate_status($value['status']);
					$value['step_status'] = $this->translate_step_status($value['step_status'], $value['status']);
					$value['last_step_status'] = $this->translate_step_status($value['last_step_status'], $value['status']);
					$ret[$key] = array_merge($value, $uvalue);
				}
			}
		}

		//排序
		$sort_arry = array_column($ret, 'next_repay_time');
		array_multisort($sort_arry, SORT_ASC, $ret);
		$data = [];

		//整理数据
		$today = date('Y-m-d 00:00:00');
		$data['today'] = [];
		$data['week'] = [];
		$data['nextweek'] = [];
		$data['month'] = [];
		$data['debug'] = [];
		$data['channel'] = [];

		//当周
		//当前日期
		$sdefaultDate = date("Y-m-d");
		//$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
		$first = 2;
		//获取当前周的第几天 周日是 0 周一到周六是 1 - 6
		$w = date('w',strtotime($sdefaultDate));
		//获取本周开始日期，如果$w是0，则表示周日，减去 6 天
		$week_start = date('Y-m-d',strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days'));
		//本周结束日期
		$week_end = date('Y-m-d',strtotime("$week_start +6 days"));
		// echo "$week_start"."$week_end";
		$week_start = strtotime($week_start);
		$week_end = strtotime($week_end);

		//下周
		$nextweek_start = $week_start+86400*7;
		$nextweek_end = $week_end+86400*7;

		//当月
		$month_start = mktime(0, 0 , 0,date("m"),1,date("Y"));
		$month_end = mktime(23,59,59,date("m"),date("t"),date("Y"));

		foreach ($ret as $key => $value) {
			if (empty($value)) { continue; }
			//隐藏身份证后四位
			if (!empty($value['idnumber'])) {
				if (strlen($value['idnumber']) == 15) {
					$value['idnumber'] = substr_replace($value['idnumber'], "****", 8, 4);
				} else {
					$value['idnumber'] = substr_replace($value['idnumber'], "****", 6, 8);
				}
			}
			if (!in_array($value['channel'], $data['channel'])) {
				$data['channel'][] = $value['channel'];
			}
			//当日判断
			if ($value['next_repay_time'] == $today) {
				$data = $this->add_data_to_array(
					$data, 
					'today', 
					$value, 
					'next_repay_time'
				);
			}
			
			//本周
			if ($week_start <= strtotime($value['next_repay_time']) && strtotime($value['next_repay_time']) <= $week_end) {
				$data = $this->add_data_to_array(
					$data, 
					'week', 
					$value, 
					'next_repay_time'
				);
			}
			if ($week_start <= strtotime($value['last_repay_time']) && strtotime($value['last_repay_time']) <= $week_end) {
				$data = $this->add_data_to_array(
					$data, 
					'week', 
					$value, 
					'last_repay_time'
				);
			}

			//下周
			if ($nextweek_start <= strtotime($value['next_repay_time']) && strtotime($value['next_repay_time']) <= $nextweek_end) {
				$data = $this->add_data_to_array(
					$data, 
					'nextweek', 
					$value, 
					'next_repay_time'
				);
			}

			//本月
			if ($month_start <= strtotime($value['next_repay_time']) && strtotime($value['next_repay_time']) <= $month_end) {
				$data = $this->add_data_to_array(
					$data, 
					'month', 
					$value, 
					'next_repay_time'
				);
			}
			if ($month_start <= strtotime($value['last_repay_time']) && strtotime($value['last_repay_time']) <= $month_end) {
				$data = $this->add_data_to_array(
					$data, 
					'month', 
					$value, 
					'last_repay_time'
				);
			}
		}
		//周数据重新排序
		$sort_arry = array_column($data['week'], 'repay_date');
		array_multisort($sort_arry, SORT_ASC, $data['week']);

		//月数据重新排序
		$sort_arry = array_column($data['month'], 'repay_date');
		array_multisort($sort_arry, SORT_ASC, $data['month']);

		//为时间增加星期几
		foreach ($data as $key => $value) {
			foreach ($value as $value_key => $item) {
				if (isset($item['repay_date'])) {
					$data[$key][$value_key]['repay_date'] = $item['repay_date'] . '(' . $this->get_week_name($item['repay_date']) . ')';
				}
			}
		}

		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}

	/**
	 * @name 获取今天是星期几
	 */
	private function get_week_name($date)
	{
		$weekarray=["日","一","二","三","四","五","六"]; //先定义一个数组
		return "星期".$weekarray[date("w", strtotime($date))];
	}

	/**
	 * 还款状态 0:未知 1:还款中 2:已还清 3:逾期 4:其他
	 */
	private function translate_status($status)
	{
		$string_status = '';
		switch ($status) {
			case 1:
				$string_status = '还款中';
				break;
			case 2:
				$string_status = '已还清';
				break;
			case 3:
				$string_status = '逾期';
				break;
			case 4:
				$string_status = '其他';
				break;
			default:
				$string_status = '未知';
				break;
		}
		return $string_status;
	}

	/**
	 * 当前还款状态-还款计划中的标识 1:待还款  2:已垫付 3:垫付后已还 4:已还款 5:借付 6:其他
	 */
	private function translate_step_status($status, $parent_status)
	{
		$string_status = '';
		switch ($status) {
			case 1:
				if (2 == $parent_status || '已还清' == $parent_status) {
					$string_status = '已还款';
				} else {
					$string_status = '待还款';
				}
				break;
			case 2:
				$string_status = '已垫付';
				break;
			case 3:
				$string_status = '垫付后已还';
				break;
			case 4:
				$string_status = '已还款';
				break;
			case 5:
				$string_status = '借付';
				break;
			case 6:
				$string_status = '其他';
				break;
			default:
				$string_status = '未知';
				break;
		}
		return $string_status;
	}

	/**
	 * @name 把数据装进data 数组中
	 */
	private function add_data_to_array($data, $type, $value, $repay_date_key)
	{
		$status = $value['status'] . ' ';
		if ('已还清' != $value['status']) {
			if ('last_repay_time' != $repay_date_key) {
				$status .= $value['step_status'];
			} else {
				$status .= $value['last_step_status'];
			}
		} else {
			$status .= '已还款';//这里是用来临时补偿 还款已完结之后无法更新还款计划的问题
		}
		
		$data[$type][] = [
			'repay_date' => mb_substr($value[$repay_date_key], 0, 10),
			'account' => $value['account'],
			'loan_title' => $value['loan_title'],
			'loan_amount' => $value['amount'] / 100,
			'principal' => $value['principal'] / 100,
			'interest' => $value['interest'] / 100,
			'status' => $status,
			'repay_amount' => $value['should_repaying_amount'] / 100,
			'name' => !empty($value['name']) ? $value['name'] : '',
			'channel' => !empty($value['channel']) ? $value['channel'] : '',
			'fuserid' => !empty($value['fuserid']) ? $value['fuserid'] : '',
			'idnumber' => !empty($value['idnumber']) ? $value['idnumber'] : '',
		];
		return $data;
	}

	/**
	 * @name 还款计划进度
	 * @url /YxStatis/RepayingPlanProgress
	 */
	public function RepayingPlanProgress()
	{
		$data = [];
		$this->load->model('yx/YxRepayingPlan_model', 'yxrepayingplan_model');
		$firstday = date('Y-m-01',time());
		$lastday = date('Y-m-d',strtotime("$firstday +1 month -1 day"));
		$ret = $this->yxrepayingplan_model->db
		->select([
			'account', 
			'loan_title', 
			'periods', 
			'repaying_time as repay_date', 
			'should_repaying_amount as repay_amount', 
			'principal', 
			'interest', 
			'default_interest', 
			'status'
		])
		->where('repaying_time >= \'' . $firstday . '\'')
		->where('repaying_time <= \'' . $lastday . '\'')
		->order_by('repaying_time ASC')
		->get($this->yxrepayingplan_model->table_name)
		->result_array();

		$accounts = [];
		$titles = [];
		foreach ($ret as $key => $value) {
			if (strstr($value['loan_title'], '新手专享')) {
				$ret[$key]['loan_title'] = preg_replace('/新手专享\-/', '', $value['loan_title']);
			}
			$ret[$key]['repay_date'] = mb_substr($value['repay_date'], 0, 10);
			$accounts[] = $value['account'];
			$titles[] = $ret[$key]['loan_title'];
		}
		$this->load->model('user/User_model', 'user_model');
		$user_info = $this->user_model->get_userbasicinfo_by_account($accounts);
		$this->load->model('yx/YxFinish_model', 'yxfinish_model');
		$finish_data_temp = $this->yxfinish_model->get_day_data(date('Y-m-d', time()));
		$finish_data = [];
		foreach ($finish_data_temp as $key => $value) {
			if (!in_array($value['pname'], $finish_data) && '已还清' == $value['f_status']) {
				$finish_data[] = $value['pname'];
			}
		}

		$data = [];
		$data['channel'] = [];
		$data['status'] = [];
		foreach ($ret as $key => $value) {
			if (in_array($value['loan_title'], $finish_data)) {
				$value['status'] = '已还款';
			}
			if (!isset($data[$value['status']])) {
				$data[$value['status']] = [];
			}

			//合并用户基础信息
			foreach ($user_info as $uvalue) {
				if ($value['account'] == $uvalue['account']) {
					//隐藏身份证后四位
					if (!empty($uvalue['idnumber'])) {
						if (strlen($uvalue['idnumber']) == 15) {
							$uvalue['idnumber'] = substr_replace($uvalue['idnumber'], "****", 8, 4);
						} else {
							$uvalue['idnumber'] = substr_replace($uvalue['idnumber'], "****", 6, 8);
						}
					}
					$value = array_merge($value, $uvalue);
				}
			}
			if (!in_array($value['channel'], $data['channel'])) {
				$data['channel'][] = $value['channel'];
			}
			if (!in_array($value['status'], $data['status'])) {
				$data['status'][] = $value['status'];
			}
			$data[$value['status']][] = $value;
		}

		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}




	/*************************************************/
	/*合同*/
	/**
	 * @name 合同浏览页面 
	 * @url /YxStatis/YxContractVeiw
	 * @other 白名单限制
	 */
	public function YxContractVeiw()
	{
		echo $_SESSION['fms_userid'];
		if ('M000001' == $_SESSION['fms_userid']) {

		} else {
			$data = [];
		}
		$this->showpage('fms/yx_contract', $data);
	}

	/**
	 * @name 查询合同
	 * @url /YxStatis/FindContract
	 * @request type POST
	 * @request param ['account' => account string, 'loan_title' => loan_title string]
	 * @response json string ['code' => code int, 'msg' => msg string, 'data' => array contract list] 
	 */
	public function FindContract()
	{
		include_once(APPPATH . 'libraries/'.'Smalot/pdfparser/vendor/autoload.php');

		include_once(APPPATH . 'libraries/'.'Smalot/TCPDF/vendor/autoload.php');

		$account  = $this->input->post('account',true);
		$loan_title  = $this->input->post('loan_title',true);
		//查询是否已存档


		$this->load->model('yx/YxContractInfo_model', 'yxcontractinfo_model');
		//先去查询是否是已完结订单
		$this->load->model('yx/YxFinish_model', 'yxfinish_model');
		$finish_info = $this->yxfinish_model->find_loan_detail($account, $loan_title);
		if (!empty($finish_info)) {
			//处理内容
			$this->load->model('yx/YxFinishContract_model', 'yxfinishcontract_model');
			$temp = $this->yxfinishcontract_model->get_contract_info($account, $loan_title);
			$contract_array = [];
			foreach ($temp as $value) {
				$contract_array[] = [
					'account' => $value['account'],
					'loan_title' => $value['loan_title'],
					'contract_url' => $value['down_contract_url'],
				];
			}
		} else {
			//再查询订单是否是进行中
			$this->load->model('yx/YxRepaying_model', 'yxrepaying_model');
			$task_time = $this->yxrepaying_model->get_effective_task_time();
			$temp = $this->yxrepaying_model->get_repaying_record($account, $loan_title);
			if (!empty($temp['contract'])) {
				$temp['contract'] = json_decode($temp['contract'], true);
				if (strstr($temp['contract'][0], '单一')) {
					$code = explode('-', $temp['contract'][0]);
					//单一合同处理流程
					$url = $temp['contract'][1];
			        $save_dir = '/www/upload/yxcontract';
			        $filename = $code[2] . '.pdf';
			        $type = 1;
					$this->getFile($url, $save_dir, $filename, $type);
					$this->yxcontractinfo_model->record_data([
						'code' => 'YXCH' . $code[2],
						'upload_dir' => $filename,
						'type' => 1,
					], $account, $loan_title);
				} else {
					//多合同处理流程
					foreach ($temp['contract'][1] as  $value) {
						$url = $value['previewImgUrl'];
						$save_dir = '/www/upload/yxcontract';
			        	$filename = $code[2] . mt_rand() . '.pdf';
						$this->getFile($url, $save_dir, $filename, $type);
						$code = $this->viewword($filename);
						$this->yxcontractinfo_model->record_data([
							'code' => $code,
							'upload_dir' => $filename,
							'type' => 2,
						], $account, $loan_title);
					}
				}
			}
		}

		//如果都不是那么查询订单状态


		//整理返回
		
		return json_encode([
			'code' => 0,
			'msg' => '获取成功',
			'data' => $data
		], JSON_UNESCAPED_UNICODE);

	}

	/* 
    *功能：php完美实现下载远程图片保存到本地 
    *参数：文件url,保存文件目录,保存文件名称，使用的下载方式 
    *当保存文件名称为空时则使用远程文件原来的名称 
    */ 
    function getFile($url,$save_dir='',$filename='',$type=0){ 
        if(trim($url)==''){ 
            return array('code'=>1,'error'=>'文件url不合法'); 
        } 
        if(trim($save_dir)==''){ 
            $save_dir='./'; 
        } 
        if(trim($filename)==''){//保存文件名 
            $ext=strrchr($url,'.'); 
            if($ext!='.pdf'){ 
                return array('code'=>3,'error'=>'文件类型不合法'); 
            } 
            $filename=substr($url,strripos($url,"/")+1); 
        } 
        if(0!==strrpos($save_dir,'/')){ 
            $save_dir.='/'; 
        } 
        //创建保存目录 
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){ 
            return array('code'=>5,'error'=>'创建文件失败'); 
        } 
        //获取远程文件所采用的方法  
        if($type){ 
            $ch=curl_init(); 
            $timeout=5; 
            curl_setopt($ch,CURLOPT_URL,$url); 
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
            $file=curl_exec($ch); 
            curl_close($ch); 
        }else{ 
            ob_start();  
            readfile($url); 
            $file=ob_get_contents();  
            ob_end_clean();  
        } 
        //$size=strlen($img); 
        //文件大小  
        $fp2=@fopen($save_dir.$filename,'a'); 
        if (!$fp2) {
            var_dump($fp2);exit;
        }
        fwrite($fp2,$file); 
        fclose($fp2); 
        unset($img,$url); 
        return array('code'=>0,'error'=>"文件".$filename."保存成功"); 
    }

    /**
     * @url /reptiles/YxContract/viewword
     */
    public function viewword($filename)
    {
        $obj = new Smalot\PdfParser\Parser();
        try {
            $document = $obj->parseFile(APPPATH . '../upload/yxcontract/' . $filename . '.pdf');
        } catch(\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }   
        $pages = $document->getPages();
        // // 逐页提取文本
        $text = '';
        foreach ($pages as $page) {
            $text .= $page->getText();
        }
        $temp = preg_replace('/.?借款合同/', '', $text);
        $temp = mb_substr($temp, 1, 50);
        $temp = preg_replace('/合同编号：/', '', $temp);
        $temp = preg_replace('/\r/', '|', $temp);
        $temp = preg_replace('/\t/', '|', $temp);
        $temp = preg_replace('/\n/', '|', $temp);
        $first_pos = stripos($temp, 'YXCH');
        $temp = mb_substr($temp, $first_pos);
        $second_pos = stripos($temp, '|');
        $temp = mb_substr($temp, 0, $second_pos);
        return $temp;
    }

    //获取在贷账户余额明细 by 奚晓俊
	public function get_repaying_detail(){
		$this->load->model('yx/YxRepaying_model', 'yrpm');

        $like = $this->input->get('like',true);
        $max = $this->input->get('max',true);
        $min = $this->input->get('min',true);
        $where = [];
        if($max!=='') $where['acctAmount <='] = $max;
        if($min!=='') $where['acctAmount >='] = $min;

        $rows = $this->input->get('rows',true);
        $page = $this->input->get('page',true);
        $sort = $this->input->get('sort',true);
        $order = $this->input->get('order',true);
       
        if($sort===null) $sort='reg_time';
        if($order===null) $order = 'desc';
        
        $res = $this->yrpm->get_repaying_detail($like,$page,$rows,$sort,$order,$where);
        echo json_encode($res);
	}

}


?>
