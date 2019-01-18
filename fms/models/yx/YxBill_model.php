<?php 

/**
 * @desc 银信电子账单model
 * @other 查询方式 先查询 用户最新的一条记录的写入时间
 *		select * from fms_yx_bill where account = 'XXXXX' order by task_time desc limit 1;
 * 		再通过 account 和 task_time 获取全部记录
 *		select account,.... from fms_yx_bill where account = 'XXXXX' and task_time = 'XXXX' order by `date`; 
 */
class YxBill_model extends Admin_Model
{
	public $table_name = 'fms_yx_bill';

	public $feild_map = [
		'account',	//银信账户
		'ctime',	//创建时间
		'serial_number',	//流水号
		'date',	//流水日期
		'income',	//收入
		'pay',	//支出
		'type',	//交易类型
		'status',	//状态
		'json_bak',	//数据json备份
		'task_time', //任务时间 具体到分钟 
	];

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 写入数据
	 * @param array $data 需要写入的数据
	 */
	public function record_data($data, $account, $task_time)
	{
		$array = [
			'account' => $account,	//银信账户
			'ctime' => date('Y-m-d H:i:s', time()),	//创建时间
			'serial_number' => !empty($data['流水号']) ? $data['流水号'] : '',	//流水号
			'date' => !empty($data['日期']) ? $data['日期'] : '',	//流水日期
			'income' => !empty($data['收入']) ? $data['收入'] : '',	//收入
			'pay' => !empty($data['支出']) ? $data['支出'] : '',	//支出
			'type' => !empty($data['交易类型']) ? $data['交易类型'] : '',	//交易类型
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

	public function get_el_bill_data($account='',$first='',$rows='')
	{
		$oneres = $this->db
				->where('account',$account)
				->order_by('task_time','desc')
				->limit(1)
				->get($this->table_name)
				->row_array();
		$allres = $this->db
			->where('account',$account)
			->where('task_time',$oneres['task_time'])
			->limit($rows,$first)
			->get($this->table_name)
			->result_array();
		$res['rows'] = $allres;
		$res['total'] = $this->db
			->where('account',$account)
			->where('task_time',$oneres['task_time'])
			->count_all_results($this->table_name);
		return $res;
	}










}



