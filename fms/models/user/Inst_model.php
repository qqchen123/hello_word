<?php

/**
 * @desc 机构账户表  Institution
 * @notice 获取数据时必须指定最后返回的字段 否则会调用show_fields配置的字段内容
 */
class inst_model extends Admin_Model
{
	public $model_name = 'inst';
    /**
	 * @name 客户证据池样本大类ID
	 */
	private $inst_a_sample_id = 155;

	private $inst_b_sample_id = 174;

	/**
	 * @name 扩展字段 列表 次部分字段是已离散数据形式存在
	 */
	public $expand_fields = [];

	/**
	 * @name 样本在页面上的元素配置
	 */
	public $sample_config = [];

	public $controller_conf = [
		'edit' => [
			'service' => ['service_name' => 'user/Inst_service', 'service_as' => 'is'],
		],
		'editdo' => [
			'data' => [
				'business_type' => 'inst',
				'business_model' => ['user/Inst_model', 'im'],
				'business_service' => ['user/Inst_service', 'is'],
				'business_pool_sample_method' => 'get_pool_array',
				//返回的url
				'finish_url' => 'qiye/instlist',
				//去要前置接收的数据
				//['post_key',['new_key'=>'post_key']] 两种写法
				'pre_post' => [
					'id', 'fuserid', 'name', 'idnumber', 'mobileNo'
				],
				//业务规则拦截设置 业务拦截的参数必须前置接收  不然不生效
				//['post_key' => method]  [需要拦截的key => 拦截调用的方法]
				'rule_post' => [
					'mobile' => 'mobile_check',
				],
				//上传文件错误提示前缀
				'upload_error_notice' => '机构账户',
			],
			'service' => ['service_name' => 'user/Inst_service', 'service_as' => 'is'],
			'fn' => 'update_institution',
		],
		'add' => [
			'data' => [
				'business_type' => 'inst',
				'business_model' => ['user/Inst_model', 'im'],
				'business_service' => ['user/Inst_service', 'is'],
				'business_pool_sample_method' => 'get_pool_array',
				//返回的url
				'finish_url' => 'qiye/instlist',
				//去要前置接收的数据
				//['post_key',['new_key'=>'post_key']] 两种写法
				'pre_post' => [
					'id', 'fuserid', 'name', 'idnumber', 'mobileNo'
				],
				//业务规则拦截设置 业务拦截的参数必须前置接收  不然不生效
				//['post_key' => method]  [需要拦截的key => 拦截调用的方法]
				'rule_post' => [
					'mobile' => 'mobile_check',
				],
				//上传文件错误提示前缀
				'upload_error_notice' => '机构账户',
				// //上传文件所存放的文件夹名
				// 'upload_floder' => 'bank',
				// //上传文件 属于哪个以身份证加密的文件夹下资料  
				// 'upload_key' => 'idnumber',
			],
			'service' => ['service_name' => 'user/Inst_service', 'service_as' => 'is'],
			'fn' => 'add_record',
		]
	];

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
		//装载 expand_fields 内容
		$this->load->model('user/User_model', 'um');
		$this->load->model('Pool_sample_model', 'psm');
		$this->load->model('pool/OperationPool_model', 'opm');
		$td = $this->psm->show_tree('id, `type`, `class`, is_json , `data-options`, js, fore_end_check, back_end_check, parent_id, level, lft, rgt', $this->inst_a_sample_id, 0, false);
		$tb = $this->psm->show_tree('id, `type`, `class`, is_json , `data-options`, js, fore_end_check, back_end_check, parent_id, level, lft, rgt', $this->inst_b_sample_id, 0, false);
		unset($td[0]);
		unset($tb[0]);
		foreach ($td as $value) {
			$this->expand_fields['sample_'.$value['id']] = $value['id'];
			$this->sample_config['sample_'.$value['id']] = $value;
		}
		foreach ($tb as $value) {
			$this->expand_fields['sample_'.$value['id']] = $value['id'];
			$this->sample_config['sample_'.$value['id']] = $value;
		}
		// Slog::log('初始化model');
		// Slog::log($this->expand_fields);
	}

	/**
	 * @name 添加客户机构账户
	 * @param array $data 
	 * @return array ['code'=>添加结果, 'msg'=>错误信息]
	 */
	public function add_new_record(array $data)
	{
        if (isset($data['fuserid'])) {
        	$fuserinfo = $this->insert_record($data['fuserid']);
			if (!empty($fuserinfo['id'])) {
				$opm_ret = $this->opm->record_data($fuserinfo['id'], $data, $this->expand_fields, 'user');
	        	if ($opm_ret) {
	        		$res['code'] = 0;
	            	$res['msg'] = '客户机构账户增加成功';//'success';
	        	} else {
	        		$res['code'] = -1;
	            	$res['msg'] = '客户机构账户增加失败';//证据池写入失败;
	        	}
	        } else {
				$res['code'] = -1;
	        	$res['msg'] = '用户信息不存在';//用户信息不存在;
			}
        } else {
        	$res['code'] = -1;
        	$res['msg'] = '数据不完整';//参数不完整;
        }
        return $res;
	}

	/**
	 * @name 通过 fuserid 获取记录  编辑记录时使用
	 * @param $fuserid
	 * @return array
	 */
	public function find_info_for_edit($fuserid)
	{
    	return $this->get_info_by_uid($fuserid);
	}

	/**
	 * @name 修改客户机构账户信息到数据库
	 * @param $info array
	 * @return array
	 */
	public function update_info_by_edit($data)
	{
		Slog::log(json_encode($data));
		$fuserinfo = $this->um->get_user_info_by_uid($data['fuserid']);
		if (!empty($fuserinfo['id'])) {
			$opm_ret = $this->opm->record_data($fuserinfo['id'], $data, $this->expand_fields, 'user');
	    	if ($opm_ret) {
	    		$res['msg'] = 'success';
	        	$res['code'] = 0;//'success';
	    	} else {
	    		$res['msg'] = '证据池写入失败';
	        	$res['code'] = -1;//证据池写入失败;
	    	}
		} else {
			$res['msg'] = '用户信息不存在';
        	$res['code'] = -2;//用户信息不存在;
		}
        return $res;

	}

	////没有精确查询的 业务场景  方法 先置空
	/**
	 * @name 通过银行卡号 获取银行卡号信息
	 * @param string $no 
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function get_info_by_no($no, $page = 1, $rows = 10)
	{
		if ($no) {
			Slog::log('客户机构账户 接收到 no 请求 | 请分析业务场景' . $no);
			return [];
		} else {
			//两个sql
			$this->db->select([
				'name',
				'idnumber',
				'id',
				'fuserid',
				'channel',
				'ctime',
				'cjyg',
			]);
			$total = $this->db->count_all_results('fms_user q', false);
			$this->db->order_by('ctime', 'DESC')->limit($rows, ($page-1)*$rows);
			$ret = $this->db->get()->result_array();
			$fuserid_array = [];
			foreach ($ret as $value) {
				$fuserid_array[] = $value['fuserid'];
			}
			$this->db->where_in('fuserid', $fuserid_array);

			//然后两个记录组合
			$this->join_public_status('inst', 'q.id');
			// $this->db->join('wesing_merchant w', 'w.userid = q.cjyg');
			$this->db->select([
				'q.fuserid',
				// 'w.username',
				'inst_status.obj_status'
			]);
			$this->db->order_by('q.ctime', 'DESC');
			$ret_status = $this->db->get('fms_user q')->result_array();
			if (isset($ret[0]['id'])) {
				foreach ($ret as $key => $item) {
					$tmp = $this->opm->get_data($item['id'], array_values($this->expand_fields));
					Slog::log($tmp);
					$tmp_array = [];
					foreach ($tmp as $value) {
						if (is_array($value) && !in_array($value['status'], $tmp_array)) {
							$tmp_array[] = $value['status'];
						}
					}
					$ret[$key]['status'] = $tmp_array;
					foreach ($ret_status as $value) {
						if ($item['fuserid'] == $value['fuserid']) {
							$ret[$key]['obj_status'] = $value['obj_status'];
							break;
						}
					}
				}
			}
			return ['data' => $ret, 'total' => $total];
		}
	}

	/**
	 * @name 通过uid 获取客户机构账户信息
	 * @param int $uid 用户ID
	 * @return array
	 */
	public function get_info_by_uid($id)
	{
		$ret = [];
		$fuserinfo = $this->um->get_user_info_by_id($id);
		if (!empty($fuserinfo['id'])) {
			//去证据池里获取数据
			$ret = $this->opm->get_data($fuserinfo['id'], $this->expand_fields);
			//增加是否可审核标识
			foreach ($ret as $key => $value) {
				if (!in_array($key, ['id']) && $value['status']) {
					$ret[$key] = array_merge($ret[$key], ['is_check' => 1]);
				}
			}
			unset($ret['id']);
			unset($fuserinfo['id']);
			$ret = array_merge($ret, $fuserinfo);
		}
    	return $ret;
	}

	/**
	 * @name 客户机构账户信息查询 通过身份证或者姓名
	 * @param $idnumber 身份证号
	 * @param $name 姓名
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function find_info_by_userinfo($idnumber = '', $name = '', $page, $rows)
	{
		if ($idnumber != 'err' && $idnumber != '') {
			$this->db->where('q.idnumber', $idnumber);
		}
		if ($name != 'err' && $name != '') {
			$this->db->like('q.name', $name);
		}
		$this->join_public_status('inst', 'q.id');
		$this->db->select([
			'q.name',
			'q.idnumber',
			'q.id',
			'q.fuserid',
			'q.channel',
			'q.cjyg',
			'q.ctime'
		]);
		$total = $this->db->count_all_results('fms_user q', false);
		$this->db->order_by('ctime', 'DESC')->limit($rows, ($page-1)*$rows);
		$ret = $this->db->get()->result_array();
		if (isset($ret[0]['id'])) {
			foreach ($ret as $key => $item) {
				$tmp = $this->opm->get_data($item['id'], array_values($this->expand_fields), 'user');
				Slog::log($tmp);
				$tmp_array = [];
				foreach ($tmp as $value) {
					if (is_array($value) && !in_array($value['status'], $tmp_array)) {
						$tmp_array[] = $value['status'];
					}
				}
				$ret[$key]['status'] = $tmp_array;
			}
		} else {
			$ret = $this->um->find_info_by_userinfo($idnumber, $name);
			foreach ($ret as $key => $value) {
				unset($ret[$key]['obj_status']);
				unset($ret[$key]['status']);
			}
			$total = $ret['total'];
			$ret = $ret['data'];
		}
    	return ['data' => $ret, 'total' => $total];
	}

    /**
     * @name 创建一条记录 只增加记录到status表中 
     * @return array user info
     */
    public function insert_record($fuserid)
    {
		$user_info = $this->um->get_user_info_by_uid($fuserid);
		if (isset($user_info['id'])){
			$this->add_public_status('inst', $user_info['id']);
			return $user_info;
		}
		return [];
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

}