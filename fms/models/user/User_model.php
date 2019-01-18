<?php

/**
 * @desc 用户表
 * @notice 获取数据时必须指定最后返回的字段 否则会调用show_fields配置的字段内容
 *			fucntion get_user_record 目前不知道怎么迁
 */
class User_model extends Admin_Model
{
	public $table_name = 'fms_user';
	/**
	 * @name 客户证据池样本大类ID
	 */
	private $user_sample_id = 2;

	/**
	 * @name 默认显示的字段
	 */
	private $show_fields = [
		'id',
		'idnumber',
		'name',
		'fuserid',
		'channel',
		'ctime'
	];

	/**
	 * @name 基础字段 列表
	 */
	public $basic_fields = [
		'idnumber',//身份证号
		'name',//姓名
		'channel',//渠道
		'fuserid',//用户ID
		'cjyg',//创建人
		'ctime',//创建时间
	];

	/**
	 * @name 扩展字段 列表 次部分字段是已离散数据形式存在
	 */
	public $expand_fields = [];

	/**
	 * @name 样本页面配置信息
	 */
	public $sample_config = [];

	public $controller_conf = [
		'edit' => [
			'service' => ['service_name' => 'user/User_service', 'service_as' => 'us'],
		],
		'editdo' => [
			'service' => ['service_name' => 'sevice/User_service', 'service_as' => 'us'],
			'pre' => [
				'servcei_as' => 'us',
				'fn' => 'regfunc'
			],
			'data' => [
				//业务类型 单业务类型有效
				'business_type' => 'user',
				'business_model' => ['user/User_model', 'um'],
				'business_service' => ['user/User_service', 'us'],
				'business_pool_sample_method' => 'get_pool_array',
				//返回的url
				'finish_url' => 'qiye/qiyequery',
				//去要前置接收的数据
				//['post_key',['new_key'=>'post_key']] 两种写法
				'pre_post' => [
					'fuserid', 'idnumber', 'name', 'channel',
				],
				//业务规则拦截设置 业务拦截的参数必须前置接收  不然不生效
				//['post_key' => method]  [需要拦截的key => 拦截调用的方法]
				'rule_post' => [
					'staple_mobile' => 'mobile_check',
				],
				//上传文件错误提示前缀
				'upload_error_notice' => '身份证',
				//上传文件所存放的文件夹名
				'upload_floder' => 'idnumber',
				//上传文件的$_FILE key名 
				'upload_key' => 'idnumber',
			],
			'fn' => 'update_user_info',
		],
	];

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
		//装载 expand_fields 内容
		$this->load->model('Pool_sample_model', 'psm');
		$this->load->model('pool/OperationPool_model','opm');
		$t = $this->psm->show_tree('id, `type`, `class`, is_json , `data-options`, js, fore_end_check, back_end_check, parent_id, level, lft, rgt', $this->user_sample_id, 0, false);
		unset($t[0]);
		foreach ($t as $value) {
			$this->expand_fields['sample_'.$value['id']] = $value['id'];
			$this->sample_config['sample_'.$value['id']] = $value;
		}
	}

/*###########开户begin##################################*/
	/**
	 * @name 注册新用户
	 * @param array $data 
	 * @return int $id 
	 */
	public function add_new_user(array $data)
	{
		//用户表数据
		$insert_data = [];
		foreach ($this->basic_fields as $value) {
			if (isset($data[$value])) {
				$insert_data[$value] = $data[$value];
			}
		}
        $this->db->insert($this->table_name, $data);
        $id = $this->db->insert_id();
        //插入公共状态
    	$this->add_public_status('user', $id);
    	//是否有常用手机号
    	if (isset($sample_usermobile[0]['id'])) {
    		$sample_usermobile = $this->opm->get_sample_id_by_pool_key('客户常用手机号');	
			Slog::log('常用手机号sample_id: ' . $sample_usermobile[0]['id']);
	    	if (isset($data['sample_' . $sample_usermobile[0]['id']])) {
	    		$this->opm->record_data(
	    			$id, 
	    			$data['sample_' . $sample_usermobile[0]['id']], 
	    			['sample_' . $sample_usermobile[0]['id'] => $sample_usermobile[0]['id']]
				);
	    	}
    	}
        return $id;
	}

	/**
	 * @name 获取该渠道的最后一个id || 编码规则调整暂时不需要
	 * @param int $channel_id 渠道ID
	 * @return array
	 */
	public function get_max_id_by_channel($channel_id)
	{
		$ret = $this->db->query('SELECT fuserid FROM ' . $this->table_name . ' WHERE fuserid LIKE \'' . $channel_id . '%\' ORDER BY ctime DESC LIMIT 1')->result_array();
		return $ret;
	}

	/**
	 * @name 获取最新的一个客户的序号
	 */
	public function get_max_id()
	{
		$ret = $this->db->query('SELECT id FROM ' . $this->table_name . ' ORDER BY id DESC LIMIT 1')->result_array();
		if ($ret) {
			Slog::log($this->db->last_query());
			Slog::log($ret[0]['id']);
			return [
				[
					'fuserid' => intval($ret[0]['id'])
				]
			];
		}
	}

	/**
	 * @name 更新用户的注册信息
	 * @param array $data 修改的信息
	 * @param array $condition 匹配条件 
	 * @return bool 执行结果
	 */
	public function update_new_user(array $data)
	{
		$this->db->set("idnumber", $data['idnumber']);
		$this->db->set("name", $data['name']);
		$this->db->set("channel", $data['channel']);
        $this->db->where(" fuserid = '" . $data['fuserid'] . "'");
		$ret = $this->db->update($this->table_name);
		Slog::log(json_encode($data));
		$fuserinfo = $this->get_user_info_by_uid($data['fuserid']);
		if (!empty($fuserinfo[0]['id'])) {
			$opm_ret = $this->opm->record_data($fuserinfo[0]['id'], $data, $this->expand_fields, 'user');
	    	if ($opm_ret) {
	    		$res['val'] = true;
	        	$res['code'] = '01';//'success';
	    	} else {
	    		$res['val'] = false;
	        	$res['code'] = '-1';//证据池写入失败;
	    	}
		} else {
			$res['val'] = false;
        	$res['code'] = '-2';//用户信息不存在;
		}
        return $res;
	}

	/**
     * @name 创建一条记录
     * @return bool|int
     */
    public function insert_record($type, $fuserid)
    {
    	if ('user' == $type) {
    		$ret = $this->get_user_info_by_uid($fuserid);
    	}
    	return $ret[0]['id'];
    }
/*##########开户end#################################*/

/*##########基础信息begin####################################*/
	/**
	 * @name 检查id是否有效
	 */
	public function check_id($id)
	{
		$ret = $this->find_info_by_userinfo('', '', $id);
		return $ret['data'];
	}

	/**
	 * @name 通过uid 获取用户信息
	 * @param int $uid 用户ID
	 * @return array
	 */
	public function get_user_info_by_uid($uid)
	{
		$userinfo = $this->db
			->where('fuserid', $uid)
			->get($this->table_name)
			->result_array();
		if (isset($userinfo[0]['id'])) {
			return $this->get_info_by_id($userinfo[0]['id'], 1);
		}
		Slog::log('error get_user_info_by_uid 通过fuserid 查询用户信息失败');
		return [];
	}

	/**
	 * @name 通过idnumber 获取用户信息
	 * @param string $idnumber 身份证号
	 * @return array
	 */
	public function get_user_info_by_id_number($idnumber)
	{
		$userinfo = $this->db
			->where('idnumber', $idnumber)
			->get($this->table_name)
			->result_array();
		if (isset($userinfo[0]['id'])) {
			return $this->get_info_by_id($userinfo[0]['id'], 1);
		}
		Slog::log('error get_user_info_by_id_number 通过身份证 查询用户信息失败');
		return [];
	}



	/**
	 * @name 通过name 获取用户信息
	 * @param string $name 姓名
	 * @return array
	 */
	public function get_user_info_by_name(string $name)
	{
		$userinfo = $this->db
			->like('name', $name, 'after')
			->get($this->table_name)
			->result_array();
		if (isset($userinfo[0]['id'])) {
			return $this->get_info_by_id($userinfo[0]['id'], 1);
		}
		Slog::log('error get_user_info_by_name 通过姓名 查询用户信息失败');
		return [];
	}

	/**
	 * @name 添加客户基础信息
	 * @param array $data 
	 * @return array ['val'=>添加结果, 'code'=>错误代码]
	 */
	public function add_new_record(array $data)
	{
        if (isset($data['fuserid'])) {
        	$info = $this->get_user_info_by_uid($data['fuserid']);
        	if (isset($info['fuserid'])) {
        		$info_tmp = [];
        		$info_tmp[] = $info;
        		$info = $info_tmp;
        	}
			$opm_ret = $this->opm->record_data($info[0]['id'], $data, $this->expand_fields, 'user');
        	if ($opm_ret) {
        		$res['val'] = true;
            	$res['code'] = '客户基础信息增加成功';//'success';
        	} else {
        		$res['val'] = false;
            	$res['code'] = '客户基础信息增加失败';//证据池写入失败;
        	}
        } else {
        	$res['code'] = '数据不完整';//参数不完整;
        }
        return $res;
	}

	/**
	 * @name 修改客户信息到数据库
	 * @param $info array
	 * @return array
	 */
	public function update_info_by_edit($data)
	{
		// Slog::log($data);
		$fuserinfo = $this->get_user_info_by_uid($data['fuserid']);
		//身份证有效期计算
		$youtu_conf = $this->config->item('youtu_id_number');
		if (!empty($data[$youtu_conf['valid_date_end']])) {
			if ('长期' != $data[$youtu_conf['valid_date_end']]) {
				//重新计算有效期
				$data[$youtu_conf['valid_date_remaining_time']] = ceil((strtotime($result['valid_date_end']) - strtotime("now")) / 86400);
			}
		}

		// Slog::log($fuserinfo);
		if (!empty($fuserinfo['id'])) {
			$opm_ret = $this->opm->record_data($fuserinfo['id'], $data, $this->expand_fields, 'user');
	    	if ($opm_ret) {
	    		$res['val'] = true;
	        	$res['code'] = 0;//'success';
	    	} else {
	    		$res['val'] = false;
	        	$res['code'] = '-1';//证据池写入失败;
	    	}
		} else {
			$res['val'] = false;
        	$res['code'] = '-2';//用户信息不存在;
		}
        return $res;
	}

	/**
	 * @name 过优图
	 * @param $fuserinfo 用户数据
	 * @param 
	 */
	public function to_youtu($fuserinfo)
	{
		//抓正反面的编辑时间以及内容
		if (isset($fuserinfo['sample_23']) && !empty($data['sample_23'])) {
			//比较数据
			if ($fuserinfo['sample_23']['val'] != $data['sample_23']) {
				//样本23 需要过优图
				$flage_font = 1;
			}
		}
		if (isset($fuserinfo['sample_24']) && !empty($data['sample_24'])) {
			//比较数据
			if ($fuserinfo['sample_24']['val'] != $data['sample_24']) {
				//样本24 需要过优图
				$flag_back = 1;
			}
		}

		$youtu = [];
		$youtu['sample_23'] = [];
		$youtu['sample_24'] = [];
		$youtu['sample_23']['val'] = !empty($flage_font) ? $data['sample_23'] : '';
		$youtu['sample_24']['val'] = !empty($flag_back) ? $data['sample_24'] : '';
		Slog::log($youtu);
		$this->load->service('public/Youtu_service', 'youtu_service');
		$youtu_result = $this->youtu_service->do_youtu($youtu);
		Slog::log('优图结果');
		Slog::log($youtu_result);
		return $youtu_result;
	}


	public function get_user_info_by_id($id)
	{
		return $this->get_info_by_id($id);
	}

	/**
	 * @name 通过uid 获取客户基本信息
	 * @param int $id fms_user id
	 * @return array
	 */
	public function get_info_by_id($id)
	{
		$ret = [];
		$fuserinfo = $this->check_id($id);
		if (!empty($fuserinfo[0]['id'])) {
			//去证据池里获取数据
			$ret = $this->opm->get_data($fuserinfo[0]['id'], $this->expand_fields);
			//增加是否可审核标识
			foreach ($ret as $key => $value) {
				if (!in_array($key, ['id']) && $value['status']) {
					$ret[$key] = array_merge($ret[$key], ['is_check' => 1]);
				}
			}
			unset($ret['id']);
			// unset($fuserinfo[0]['id']);
			$ret = array_merge($ret, $fuserinfo[0]);
		}
    	return $ret;
	}

	/**
	 * @name 客户信息查询 通过身份证或者姓名
	 * @param $idnumber 身份证号
	 * @param $name 姓名
	 * @param $id id
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array ['data' => array, 'total' => int]
	 */
	public function find_info_by_userinfo($idnumber = '', $name = '', $id = '', $page = 1, $rows = 10)
	{
		if ($idnumber != 'err' && $idnumber != '') {
			$this->db->where('q.idnumber', $idnumber);
		}
		if ($name != 'err' && $name != '') {
			$this->db->like('q.name', $name, 'after');
		}
		if ($id != '') {
			$this->db->where('q.id', $id);
		}
		$this->join_public_status('user', 'q.id');
		// $this->db->join('wesing_merchant w', 'w.userid = q.cjyg');
		$this->db->select([
			'q.name',
			'q.idnumber',
			'q.id',
			'q.fuserid',
			'q.channel',
			'q.ctime',
			'q.cjyg',
			// 'w.username',
			'user_status.obj_status'
		]);
		$this->db->order_by('q.ctime', 'DESC');
		$total = $this->db->count_all_results('fms_user q', false);
		$this->db->limit($rows, ($page-1)*$rows);
		$ret = $this->db->get()->result_array();
		if (isset($ret[0]['id'])) {
			foreach ($ret as $key => $item) {
				$tmp = $this->opm->get_data($item['id'], array_values($this->expand_fields), 'user');
				// Slog::log($tmp);
				$tmp_array = [];
				foreach ($tmp as $value) {
					if (is_array($value) && !in_array($value['status'], $tmp_array)) {
						$tmp_array[] = $value['status'];
					}
				}
				$ret[$key]['status'] = $tmp_array;
			}
			return ['data' => $ret, 'total' => $total];
		}
    	return ['data' => [], 'total' => 0];
	}

	/**
	 * @name 客户信息查询 第二版 通过手机号或客户编号
	 * @param $login_name 手机号
	 * @param $fuserid 客户编号
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array ['data' => array, 'total' => int]
	 */
	public function find_info_second($login_name, $fuserid, $id, $page, $rows)
	{
		if ($login_name != 'err' && $login_name != '') {
			$this->db->where('q.login_name', $login_name);
		}
		if ($fuserid != 'err' && $fuserid != '') {
			$this->db->like('q.fuserid', $name, 'fuserid');
		}
		if ($id != '') {
			$this->db->where('q.id', $id);
		}
		$this->join_public_status('user', 'q.id');
		// $this->db->join('wesing_merchant w', 'w.userid = q.cjyg');
		$this->db->select([
			'q.name',
			'q.idnumber',
			'q.id',
			'q.fuserid',
			'q.channel',
			'q.ctime',
			'q.cjyg',
			'q.login_name',
			// 'w.username',
			'user_status.obj_status'
		]);
		$this->db->order_by('q.ctime', 'DESC');
		$total = $this->db->count_all_results('fms_user q', false);
		$this->db->limit($rows, ($page-1)*$rows);
		$ret = $this->db->get()->result_array();
		if (isset($ret[0]['id'])) {
			foreach ($ret as $key => $item) {
				$tmp = $this->opm->get_data($item['id'], array_values($this->expand_fields), 'user');
				// Slog::log($tmp);
				$tmp_array = [];
				foreach ($tmp as $value) {
					if (is_array($value) && !in_array($value['status'], $tmp_array)) {
						$tmp_array[] = $value['status'];
					}
				}
				$ret[$key]['status'] = $tmp_array;
			}
			return ['data' => $ret, 'total' => $total];
		}
    	return ['data' => [], 'total' => 0];
	}

    /**
     * @name 获取编辑页的配置信息
     */
    public function get_config()
    {
    	return $this->edit_box_config;
    }

    /**
     * @name 获取样本 列表
     */
    public function get_pool_array()
    {
    	return $this->expand_fields;
    }

    /**
     * @name 获取样本的页面配置信息
     */
    public function get_sample_config()
    {
    	return $this->sample_config;
    }

    /**
     * @name 通过银信账号获取用户基础信息
     * @param $yx_account array
     * @return array 
     */
    public function get_userbasicinfo_by_account($yx_account)
    {
    	if (!is_array($yx_account)) {
    		if (is_string($yx_account)) {
    			$yx_account = [$yx_account];
    		} else {
    			$yx_account = [];
    		}
    	}
    	if (empty($yx_account)) {
    		return [];
    	}
    	$ret = $this->db->select([
    		'fuserid',
    		'name',
    		'channel',
    		'yx_account as account',
    		'idnumber'
    	])
    	->where_in('yx_account', $yx_account)
    	->get($this->table_name)
    	->result_array();
    	return $ret;
    }

/*#############证据池用############*/
	/**
     * @name 获取用户list 分页形式
     * @param string $condition 查询条件
     * @param int $page_size 分页大小
     * @param int $page_num 页码数
     * @return array ['list' => [], 'total' => int]
     */
    public function get_user_list_like_page($condition, $page_size, $page_num)
    {
    	$sql_list = 'SELECT id, fuserid, idnumber, name, channel, ctime';
    	$sql_total = 'SELECT count(id) as total ';
    	$sql = ' FROM ' . $this->table_name ;
    	$sql .= ' WHERE ' . $condition;
    	$sql .= ' ORDER BY id DESC LIMIT ' . $page_num . ',' . $page_size;
    	$sql_list .= $sql;
    	$sql_total .= $sql;
    	$tmp = $this->db->query($sql_total)->result_array();
    	return [
    		'list' => $this->db->query($sql_list)->result_array(),
    		'total' => intval($tmp[0]['total']),
    	];
    }

 /*#############前端用############*/   
 	/**
 	 * @name 用户登录 
 	 * @param string $login_name 登录账户
 	 * @param string $pwd 登录密码
 	 * @return array ['msg'=>string, 'code'=>int]
 	 */
    public function user_login($login_name, $pwd)
    {
    	$ret = $this->db
    	->where('login_name', $login_name)
    	->select([
    		'fuserid',
    		'login_name',
    		'login_pwd',
    		'salt'
    	])
    	->get($this->table_name)
    	->row_array();
    	if (isset($ret['login_name'])) {
    		//校验密码
    		if (md5($login_name.$ret['salt'].$pwd) != $ret['login_pwd']) {
    			//密码错误
    			$msg = '账号或密码错误';
    			$code = -1;
    		} else {
    			//登录成功
    			$_SESSION['login_flag'] = 1;
    			$_SESSION['login_name'] = $login_name;
    			$_SESSION['login_time'] = date('Y-m-d H:i:s');
    			$_SESSION['user_id'] = $ret['fuserid'];

    			$msg = '登录成功';
    			$code = 0;
    		}
    	} else {
    		//账户不存在
    		$msg = '账户不存在';
    		$code = -2;
    	}
    	return [
    		'msg' => $msg,
    		'code' => $code,
    	];
    }

    /**
     * @name 检查账户名是否存在
     * @param string $login_name
     * @return array ['msg' => string, 'code' => int]
     */
    public function check_login_name($login_name)
    {
    	$ret = $this->db->where('login_name', $login_name)
    	->select(['fuserid'])
    	->get($this->table_name)
    	->row_array();
    	if (isset($ret['fuserid'])) {
    		return [
    			'message' => '账户已存在',
    			'code' => 0,
    			'data' => 0,
    		];
    	} else {
    		return [
    			'message' => '账户不存在',
    			'code' => 1,
    			'data' => 1,
    		];
    	}
    }

    /**
     * @name 设置密码
     * @param string $login_name 登录账户
 	 * @param string $pwd 登录密码
 	 * @return array ['msg'=>string, 'code'=>int]
     */
    public function front_end_set_pwd($login_name, $pwd)
    {
    	$check = $this->db->where('login_name', $login_name)
    	->select(['login_name'])
    	->get($this->table_name)
    	->row_array();
    	if (isset($check['login_name'])) {
    		//开始设置密码
    		try {
    			$salt = substr(str_shuffle(uniqid(time())),3,6);
				$this->db->set("salt", $salt);
				$this->db->set("login_pwd", md5($login_name.$salt.$pwd));
		        $this->db->where(" login_name", $login_name);
				$ret = $this->db->update($this->table_name);
				if ($ret) {
					$msg = '设置成功';
					$code = 0;
				} else {
					$msg = '设置失败';
					$code = -1;
				}
    		} catch (\Exception $e) {
    			Slog::log(json_encode($e->getMessage()));
    			$msg = '操作失败';
				$code = -2;
    		}
    	} else {
    		$msg = '账户不存在';
			$code = -3;
    	}
    	
		return [
			'msg' => $msg,
			'code' => $code
		];
    }

    //获取全部客户指定基本字段（非池子）数据 by 奚晓俊
    public function get_user_base_filed($fileds=['id'],$order='fuserid'){
    	return $this->db->select($fileds)->order_by($order)->get('fms_user')->result_array();
    }

    //银信获取客户编号 身份证 姓名 绑定手机号 by 奚晓俊
	public function get_account_user_info($account){
		return $this->db
			->select([
				'fms_user.fuserid',
				'fms_user.name',
				'fms_user.idnumber',
				'binding_phone',
			])
			->join('fms_yx_account','fms_user.yx_account=fms_yx_account.account','left')
			->where(['fms_user.yx_account'=>$account])
			->get('fms_user')
			->row_array();
	}

	//获取一条客户信息 by 奚晓俊
	public function get_one($where){
		return $this->db->where($where)->get($this->table_name)->row_array();
	}

	public function replace_one($account,$data){
		$r = $this->get_one(['yx_account'=>$account]);
		//新增
		if($r===null){
			$r = $this->get_one(['fuserid'=>$data['fuserid']]);
			if($r!==null) return '客户编号已存在！';

			$r = $this->get_one(['idnumber'=>$data['idnumber']]);
			if($r!==null) return '客户身份证号已存在！';

			$data['yx_account'] = $account;
			//渠道编号=客户编号前4位
            $data['channel'] = substr($data['fuserid'],0,4);
            //创建员工
            $data['cjyg'] = $_SESSION['fms_username'];
            $data['ctime'] = date('Y-m-d H:i:s');

            return $this->db->insert($this->table_name,$data);

		//编辑
		}else{
			$r = $this->get_one([
				'fuserid'=>$data['fuserid'],
				'yx_account !='=>$account,
			]);
			if($r!==null) return '客户编号已存在！';

			$r = $this->get_one([
				'idnumber'=>$data['idnumber'],
				'yx_account !='=>$account,
			]);
			if($r!==null) return '客户身份证号已存在！';
			
			return $this->db->update($this->table_name,$data,['yx_account'=>$account]);
		}
	}

}