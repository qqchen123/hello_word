<?php

/**
 * @desc 手机卡表
 * @other 这是一张假model  实际数据是落地在 fms_pool 表
 */
class Mobile_model extends Admin_Model
{
	/**
	 * @name 客户证据池样本大类ID
	 */
	private $mobile_basic_sample_id = 95;

	private $mobile_detail_sample_id = 104;

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
			'service' => ['service_name' => 'user/Mobile_service', 'service_as' => 'ms'],
		],
		'editdo' => [
			'data' => [
				'business_type' => 'mobile',
				'business_model' => ['user/Mobile_model', 'mm'],
				'business_service' => ['user/Mobile_service', 'ms'],
				'business_pool_sample_method' => 'get_pool_array',
				//返回的url
				'finish_url' => 'qiye/mobilelist',
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
				'upload_error_notice' => '手机卡',
				//上传文件所存放的文件夹名
				'upload_floder' => 'mobile',
				//上传文件 属于哪个以身份证加密的文件夹下资料  
				'upload_key' => 'idnumber',
			],
			'service' => ['service_name' => 'user/Mobile_service', 'service_as' => 'mobile_service'],
			'fn' => 'update_mobile_card',
		],
		'add' => [
			'data' => [
				'business_type' => 'mobile',
				'business_model' => ['user/Mobile_model', 'mm'],
				'business_service' => ['user/Mobile_service', 'ms'],
				'business_pool_sample_method' => 'get_pool_array',
				//返回的url
				'finish_url' => 'qiye/mobilelist',
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
				'upload_error_notice' => '手机卡',
				//上传文件所存放的文件夹名
				'upload_floder' => 'mobile',
				//上传文件 属于哪个以身份证加密的文件夹下资料  
				'upload_key' => 'idnumber',
			],
			'service' => ['service_name' => 'user/Mobile_service', 'service_as' => 'ms'],
			'fn' => 'add_mobile_card',
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
		$tc = $this->psm->show_tree('id, `type`, `class`, is_json , `data-options`, js, fore_end_check, back_end_check, parent_id, level, lft, rgt', $this->mobile_detail_sample_id, 0, false);
		$tb = $this->psm->show_tree('id, `type`, `class`, is_json , `data-options`, js, fore_end_check, back_end_check, parent_id, level, lft, rgt', $this->mobile_basic_sample_id, 0, false);
		unset($tc[0]);
		unset($tb[0]);
		foreach ($tb as $value) {
			$this->expand_fields['sample_'.$value['id']] = $value['id'];
			$this->sample_config['sample_'.$value['id']] = $value;
		}
		foreach ($tc as $value) {
			$this->expand_fields['sample_'.$value['id']] = $value['id'];
			$this->sample_config['sample_'.$value['id']] = $value;
		}
	}

	/**
	 * @name 添加手机卡
	 * @param array $data 
	 * @return array ['val'=>添加结果, 'code'=>错误代码]
	 */
	public function add_new_mobile_card(array $data)
	{
        if (isset($data['fuserid'])) {
        	$fuserinfo = $this->insert_record($data['fuserid']);
			if (!empty($fuserinfo['id'])) {
				$opm_ret = $this->opm->record_data($fuserinfo['id'], $data, $this->expand_fields, 'user');
	        	if ($opm_ret) {
	        		$res['val'] = true;
	            	$res['code'] = 0;//'success';
	            	$res['msg'] = '手机卡增加成功';
	        	} else {
	        		$res['val'] = false;
	            	$res['code'] = -1;//证据池写入失败;
	            	$res['msg'] = '手机卡增加失败';

	        	}
	        } else {
				$res['val'] = false;
	        	$res['code'] = -2;//用户信息不存在;
        		$res['msg'] = '用户信息不存在';

			}
        } else {
        	$res['code'] = -3;//参数不完整;
        	$res['msg'] = '数据不完整';

        }
        return $res;
	}

	/**
	 * @name 通过 fuserid 获取记录  编辑记录时使用
	 * @param $fuserid
	 * @return array
	 */
	public function find_mobile_card_info_for_edit($fuserid)
	{
    	return $this->get_mobile_card_by_uid($fuserid);
	}

	/**
	 * @name 修改手机卡信息到数据库
	 * @param $info array
	 * @return array
	 */
	public function update_mobile_card_by_edit($data)
	{
		Slog::log(json_encode($data));
		$fuserinfo = $this->um->get_user_info_by_uid($data['fuserid']);
		if (!empty($fuserinfo['id'])) {
			$opm_ret = $this->opm->record_data($fuserinfo['id'], $data, $this->expand_fields, 'user');
	    	if ($opm_ret) {
	    		$res['val'] = true;
	        	$res['code'] = 0;//'success';
	    	} else {
	    		$res['val'] = false;
	        	$res['code'] = -1;//证据池写入失败;
	    	}
		} else {
			$res['val'] = false;
        	$res['code'] = -2;//用户信息不存在;
		}
        return $res;

	}

	/**
	 * @name 通过idnumber 获取手机号信息
	 * @param string $mobile_no 手机号
	 * @return array
	 */
	public function get_mobile_card_by_mobile_no(string $mobile_no)
	{
		//去证据池查
		// 样本名 绑定手机号   获取样本ID
		$sample_id_array_tmp = $this->db
			->select('id')
			->where('pool_key','绑定手机号')
			->or_where('pool_key','客户常用手机号')
			->get('fms_pool_sample')->result_array();

		Slog::log($sample_id_array_tmp);
		$sample_id_array = [];
		foreach ($sample_id_array_tmp as $value) {
			$sample_id_array[] = $value['id'];
		}
		
		return $this->db
			->select('user_id')
			->where_in('pool_sample_id', $sample_id_array)
			->where('pool_val', $mobile_no)
			->get('fms_pool')->result_array();
	}

	/**
	 * @name 通过uid 获取手机号信息
	 * @param int $id 用户ID
	 * @return array
	 */
	public function get_mobile_card_by_uid($id)
	{
		$ret = [];
		$fuserinfo = $this->um->get_info_by_id($id);
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
	 * @name 手机卡信息查询 通过身份证或者姓名
	 * @param $idnumber 身份证号
	 * @param $name 姓名
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function find_mobile_info_by_userinfo($idnumber = '', $name = '', $page = 1, $rows = 10)
	{
		if ($idnumber != 'err' && $idnumber != '') {
			$this->db->where('q.idnumber', $idnumber);
		}
		if ($name != 'err' && $name != '') {
			$this->db->like('q.name', $name);
		}
		$this->join_public_status('mobile', 'q.id');
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
		$this->db->order_by('q.ctime', 'DESC');
		$this->db->limit($rows, ($page-1)*$rows);
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
	 * @name 手机卡信息查询 通过手机卡ID
	 * @param $mobileid 手机卡ID
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function  find_mobile_info_by_mobileid($mobileid = '', $page = 1, $rows = 10)
	{
		if ($mobileid) {
			// 样本名 绑定手机号   获取样本ID
			$sample_id_array_tmp = $this->db
				->select('id')
				->where('pool_key','常用手机号')
				->get('fms_pool_sample')->result_array();

			Slog::log($sample_id_array_tmp);
			$sample_id_array = [];
			foreach ($sample_id_array_tmp as $value) {
				$sample_id_array[] = $value['id'];
			}
			
			return $this->db
				->select('user_id')
				->where_in('pool_sample_id', $sample_id_array)
				->where('pool_val', $no)
				->get('fms_pool')->result_array();
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
			foreach ($ret as $value) {
				$fuserid_array[] = $value['fuserid'];
			}
			$this->db->where_in('fuserid', $fuserid_array);
			//然后两个记录组合
			$this->join_public_status('mobile', 'q.id');
			// $this->db->join('wesing_merchant w', 'w.userid = q.cjyg');
			$this->db->select([
				'q.fuserid',
				// 'w.username',
				'mobile_status.obj_status'
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
				return ['data' => $ret, 'total' => $total];
			}
			return ['data' => [], 'total' => 0];
		}
	}

    /**
     * @name 创建一条记录 只增加记录到status表中 
     * @return array user info
     */
    public function insert_record($fuserid)
    {
		$user_info = $this->um->get_user_info_by_uid($fuserid);
		if (isset($user_info['id'])){
			$this->add_public_status('mobile', $user_info['id']);
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