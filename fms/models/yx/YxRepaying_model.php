<?php 

/**
 * 
 */
class YxRepaying_model extends Admin_Model
{
	public $table_name = 'fms_yx_repaying';

	public $task_time = '';//任务时间 查询方法中 必用变量
	
	function __construct()
	{
		parent::__construct();
	}

	public $feild_map = [
		'account',	//银信账户
		'ctime',	//创建时间
		'loan_title',	//借款标题
		'loan_amount',	//借款金额 单位分
		'periods',	//期数
		'next_repay_amount',	//下期还款本息 单位分
		'next_repay_time',	//下期还款日
		'repay_type',	//还款方式
		'contract',		//合同信息
		'status',	//状态
		'json_bak',	//数据json备份
		'task_time', //任务时间 具体到分钟 
	];

	/**
	 * @name 写入数据
	 * @param array $data 需要写入的数据
	 */
	public function record_data($data, $account, $task_time)
	{
		$array = [
			'account' => $account,	//银信账户
			'ctime' => date('Y-m-d H:i:s', time()),	//创建时间
			'loan_title' => !empty($data['借款标题']) ? $data['借款标题'] : '',	//借款标题
			'loan_amount' => !empty($data['借款金额']) ? $data['借款金额'] : '',	//借款金额
			'periods' => !empty($data['期数']) ? $data['期数'] : '',	//期数
			'next_repay_amount' => !empty($data['下期还款本息']) ? $data['下期还款本息'] : '',	//下期还款本息
			'next_repay_time' => !empty($data['下期还款日']) ? $data['下期还款日'] : '',	//下期还款日
			'repay_type' => !empty($data['还款方式']) ? $data['还款方式'] : '',	//还款方式
			'contract' => !empty($data['合同信息']) ? $data['合同信息'] : '',	//合同信息
			'status' => !empty($data['状态']) ? $data['状态'] : '',	//状态
			'json_bak' => json_encode([$data,$account], JSON_UNESCAPED_UNICODE),	//数据json备份
			'task_time' => $task_time,	//任务时间
		];
		$this->db->insert($this->table_name, $array);
		return $this->db->insert_id();
	}

	public function record_check($account, $task_time)
	{
		return $this->db->select(['id'])
		->where(' account = \'' . $account . '\' AND task_time = \'' . $task_time . '\'')
		->get($this->table_name)
		->result_array();
	}

	public function get_record($account)
	{
		$ret = [];
		//获取当前日期
		if (!$this->task_time) {
			$this->get_effective_task_time();
		}
		$ret = $this->db->select([
			'account',	//银信账户
			'ctime',	//创建时间
			'loan_title',	//借款标题
			'convert(loan_amount/100,decimal(15,2)) as loan_amount',	//借款金额 单位分
			'periods',	//期数
			'convert(next_repay_amount/100,decimal(15,2)) as next_repay_amount',	//下期还款本息 单位分
			'left(next_repay_time, 10) as next_repay_time',	//下期还款日
			'repay_type',	//还款方式
			'contract',		//合同信息
			'status',	//状态
		])
		->where(' account = \'' . $account . '\' AND task_time >= ' . $this->task_time)
		->order_by('ctime', 'DESC')
		->get($this->table_name)
		->row_array();
		if ($ret) {
			return [$ret];
		}
	}

	/**
	 * @name 获取还款中的订单的信息
	 * @param string $account 银信账号 
	 * @param string $loan_title 借款标题
	 * @return array
	 */
	public function get_repaying_record($account, $loan_title)
	{
		$ret = [];
		//获取当前日期
		if (!$this->task_time) {
			$this->get_effective_task_time();
		}
		$ret = $this->db
		->where('account', $account)
		->where('loan_title', $loan_title)
		->get($this->table_name)
		->row_array();
		return $ret;
	}

	/**
	 * @name 获取有效的task_time
	 * @other 检查最新的task_time的生成时间 判断脚本是否更新完成
	 * 			仅初略估计
	 * 约定脚本执行时间为30~60分钟
	 */
	public function get_effective_task_time()
	{
		$max_task_time = $this->db->select('max(task_time) as task_time')
		->get($this->table_name)
		->row_array();

		$task_duration = $this->db->select([
			'min(ctime) as min_ctime',
			'max(ctime) as max_ctime'
		])
		->where('task_time', $max_task_time['task_time'])
		->get($this->table_name)
		->row_array();
		
		//简单判断
		//最后一条记录的结束时间 超过十分钟
		if (abs(strtotime($task_duration['max_ctime']) - time()) > 10*60) {
			if ((strtotime($task_duration['max_ctime']) - strtotime($task_duration['min_ctime'])) > 30*60 && (strtotime($task_duration['max_ctime']) - strtotime($task_duration['min_ctime'])) < 60*60 ) {
				$this->task_time = $max_task_time['task_time'];
				return $this->task_time;
			}
		}
		//获取上一个task_time
		$max_task_time = $this->db->select('max(task_time) as task_time')
		->where('task_time < ' . $max_task_time['task_time'])
		->get($this->table_name)
		->row_array();
		$this->task_time = $max_task_time['task_time'];
		return $this->task_time;
	}

	/**
	 * @name 获取在借用户
	 */
	public function get_repaying_username()
	{
		if (!$this->task_time) {
			$this->get_effective_task_time();
		}
		$ret = $this->db->select('distinct(account) as account')
		->where('task_time', $this->task_time)
		->get($this->table_name)
		->result_array();
		$arr = [];
		foreach ($ret as $value) {
			$arr[] = $value['account'];
		}
		return $arr;
	}

	//获取在贷账户余额明细列表数据 by 
	public function get_repaying_detail($like='',$page=1,$rows=10,$sort='reg_time',$order='DESC',$where=[]){
		if (!$this->task_time) $this->get_effective_task_time();

		$sub_accounts = '('.$this->db
			->select('distinct(account) as account')
			->where('task_time', $this->task_time)
			->get_compiled_select($this->table_name).') as accounts';

        if ($like){
	        $this->db->or_like('fms_yx_account_have_money.account',$like)->where($where);
	        $this->db->or_like('fms_user.fuserid',$like)->where($where);
	        $this->db->or_like('fms_user.name',$like)->where($where);
	        $this->db->or_like('fms_user.idnumber',$like)->where($where);
	        $this->db->or_like('fms_yx_account.binding_phone',$like)->where($where);
        }else{
        	$this->db->where($where);
        }
			
		$this->db
			->join($sub_accounts,'accounts.account=fms_yx_account_have_money.account')
			->join('fms_yx_account','fms_yx_account.account=accounts.account','left')
			->join('fms_user','fms_user.yx_account=accounts.account','left');

		$this->db->select([
			'fms_yx_account_have_money.account maccount',
			'abs(fms_yx_account_have_money.acctBal) acctBal',
			'abs(fms_yx_account_have_money.acctAmount) acctAmount',
			'abs(fms_yx_account_have_money.frozBl) frozBl',

			'fms_yx_account.binding_phone',
			'fms_user.fuserid',
			'fms_user.name',
			'fms_user.idnumber',

			'fms_yx_account.pwd',
		]);
        $total = $this->db->count_all_results('fms_yx_account_have_money',false);
        $rs = $this->db->order_by($sort,$order)->limit($rows,($page-1)*$rows)->get();
        if ($rs->num_rows() > 0) {
            $res["total"]= $total;
            $res['rows'] = $rs->result_array();
            $rs->free_result();
        } else {
            $res["total"]= 0;
            $res["rows"] = '';
        }
        return $res;
	}

	/**
	 * @name 获取在借用户总数
	 */
	public function get_repaying_username_total()
	{
		return count($this->get_repaying_username());
	}

	/**
	 * @name 获取在借订单数量
	 */
	public function get_repaying_order_total()
	{
		if (!$this->task_time) {
			$this->get_effective_task_time();
		}
		$ret = $this->db->select('count(id) as total')
		->where('task_time', $this->task_time)
		->get($this->table_name)
		->row_array();
		return $ret['total'];
	}

	/**
	 * @name 在借总额
	 * @return 在借总额 单位：元
	 */
	public function get_repaying_total_amount()
	{
		if (!$this->task_time) {
			$this->get_effective_task_time();
		}
		$ret = $this->db->select('sum(loan_amount)/100 as loan_amount')
		->where('task_time', $this->task_time)
		->get($this->table_name)
		->row_array();
		return $ret['loan_amount'];
	}

	/**
	 * @name 在借账户 账户总余额
	 */
	public function get_repaying_account_acct_amount()
	{
		$users = $this->get_repaying_username();
		$ret = $this->db->select([
			'sum(acctAmount*100)/100 as total_amount'
		])
		->where_in('account', $users)
		->get('fms_yx_account_have_money')
		->row_array();
		return $ret['total_amount'];
	}

	public function get_last_time()
	{
		$ret = $this->db->select('max(ctime) as ctime')
		->get($this->table_name)
		->row_array();
		return $ret['ctime'];
	}


	public function get_working_progress()
	{
		$ret = $this->db->select('account')
		->order_by('id DESC')
		->limit(1)
		->get($this->table_name)
		->row_array();
		$this->load->model('yx/YxAccount_model', 'yxaccount_model');
		$ret = $this->yxaccount_model->db->select('id')
		->where('account', $ret['account'])
		->get('fms_yx_account')
		->row_array();
		$ret = $this->yxaccount_model->db->select('count(id) as count')
		->where('id <= ' . $ret['id'])
		->get('fms_yx_account')
		->row_array();
		return $ret['count'];
	}

	/**
	 * @name 获取上一个有效任务的全部数据
	 * @return array
	 */
	public function get_last_task_data()
	{
		return $this->db->where('task_time', $this->get_effective_task_time())
		->get($this->table_name)
		->result_array();
	}

}

?>
