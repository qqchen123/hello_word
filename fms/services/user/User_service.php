<?php

/**
 * @desc 用户注册相关
 */
class User_service extends Admin_Service
{

	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user/User_model', 'model');
		$this->load->model('user/NativePlace_model', 'NativePlace');
	}

/*###################开户注册begin###################################*/
	/**
	 * @name 用户注册
	 * @param array $data
	 * @return [val=>boolean, code=>int]
	 */
	public function register_user($data)
	{
		$code = -2;
		$val = '';
		$ret = $this->model->db
		->select(['id', 'fuserid'])
		->where('login_name', $data['login_name'])->get('fms_user')->result_array();
		Slog::log($ret);
		if (empty($ret)) {
			//可以注册
			//处理数据
			$ret = $this->model->add_new_user([
				'name' => $data['name'],
				'idnumber' => $data['idnumber'],
				'fuserid' => $this->create_uid($data['channel']),//商户ID
				'cjyg' => !empty($_SESSION['fms_userid']) ? $_SESSION['fms_userid'] : 'H5',//录入员工  从seession 取
				'channel' => $data['channel'],//渠道编号
				'ctime' => $data['ctime'],
				'login_name' => $data['login_name'],
			]);
			Slog::log('用户id:'.$ret);
			if ($ret) {
				//目前注册不带身份证号 所这一段没有
				//用户基础信息保存
				// Slog::log('用户基础信息保存');
				// $this->load->service('public/Idnumber_service', 'is');
				// $data = array_merge(
				// 	['age' => $this->is->get_age_by_id($data['idnumber'])], 
				// 	$this->is->checkId($data['idnumber']),
				// 	['birth' => $this->is->get_birth_date($data['idnumber'])]
				// );
				// //保存到数据库
				// $user_info = $this->find_user_edit($ret);
				// $data = [
				// 	'fuserid' => $user_info['fuserid'],
				// 	'sample_9' => $data['age'],
				// 	'sample_11' => $data['sex'],
				// 	'sample_17' => $data['check'],
				// 	'sample_12' => $data['area'],
				// 	'sample_10' => $data['birth'],
				// 	'sample_6' => empty($data['mobile']) ? '' : $data['mobile']
				// ];
				// $this->update_user_info($data);
				// Slog::log('新增客户资料完成');

				$code = 1;
				$val = true;
				// $ret['id'] = $user_info['id'];
			} else {
				$code = -1;
				$val = false;
			}
		} else {
			$ret['id'] = !empty($ret[0]['id']) ? $ret[0]['id'] : '';
			$code = -3;
			$val = false;
		}

		return [
			'val' => $val,
			'code' => $code,
			'data' => !empty($ret['id']) ? $ret['id'] : '', 
		];
	}

	/**
	 * @name 修改用户注册的资料
	 * @param array $data 
	 * @return array ['code'=>int, 'message'=>string] 修改结果
	 */
	public function edit_register_user($data)
	{
		//验证数据
		$idnumber_ret = $this->model->get_user_info_by_uid($data['fuserid']);
		//本地调试不需要  减少开销
		//$this->load->service('public/Aliyun_service', 'as');
        //$check = $this->as->idnumber_check($data['idnumber'], $data['name']);
        $check = 1;
        $ret = -1;
        $str = '二要素检查不通过';
        if (1 == $check) {
        	//更新数据
        	$ret = $this->model->update_new_user($data);
        	Slog::log(['更新用户数据 model 返回结果', $ret]);
        	if ($ret) {
        		$str = '用户信息修改成功';
        	} else {
        		$str = '用户信息修改失败';
        	}
        }
        return [
        	'code' => $ret,
        	'msg' => $str,
        ];
	}

	/**
	 * @name 检查用户是否已存在
	 * @param string $idnumber 身份证号
	 * @param string $name 姓名
	 */
	public function check_user($idnumber, $name = '')
	{
		$idnumber_ret = $this->model->get_user_info_by_id_number($idnumber);
		$this->load->service('public/Aliyun_service', 'as');
		//本地调试不需要  减少开销
        // $check = $this->as->idnumber_check($idnumber, $name);
        $check = 1;
        if (isset($idnumber_ret['fuserid'])) {
        	$idnumber_ret_tmp = [];
        	$idnumber_ret_tmp[] = $idnumber_ret;
        	$idnumber_ret = $idnumber_ret_tmp;
        }
        if (1 == $check) {
        	if (!empty($idnumber_ret[0]) && $name == $idnumber_ret[0]['name']) {
				//用户已存在
				$str = '用户已存在';
				$result = 0;
			} else if(!empty($idnumber_ret[0]) && $name != $idnumber_ret[0]['name']) {
				//idnumber 存在但是名字不同
				$str = '身份证号已存在 姓名不匹配 ' . $name . '||' . $idnumber_ret[0]['name'];
				$result = 0;
			} else {
				$result = 1;
				$str = '';
			}
        } else if (0 == $check) {
        	$result = 0;
        	$str = '身份证与姓名不一致 请检查';
        } else {
        	$result = 0;
        	$str = $check;
        }
		return [
			'data' => $result,
			'message' => $str
		];
	}

	/**
	 * @name 生成userId
	 */
	public function create_uid($channel_id)
	{
		// $ret = $this->model->get_max_id_by_channel($channel_id);
		$ret = $this->model->get_max_id();
		if (!isset($ret[0])) {
			$ret = [];
			$ret[0] = [];
			$ret[0]['fuserid'] = 0;
		}
		Slog::log(preg_replace('/' . $channel_id . '/', '', $ret[0]['fuserid']));
		return $channel_id . str_pad(intval(preg_replace('/' . $channel_id . '/', '', $ret[0]['fuserid'])) + 1, 4, "0", STR_PAD_LEFT);
	}
/*###################开户注册end###################################*/

/*##################客户基础信息管理begin#######################################*/
	/**
	 * @name 查找用户
	 * @param string $idnumber
	 * @param string $name
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function find_user($idnumber = '', $name = '', $page = 1, $rows = 10)
	{
		return $this->model->find_info_by_userinfo($idnumber, $name, '', $page, $rows);
	}

	/**
	 * @name 查找用户 第二版
	 * @param string $login_name 手机号
	 * @param string $fuserid 客户编号
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function find_user_second($login_name = '', $fuserid = '', $page = 1, $rows = 10)
	{
		return $this->model->find_info_second($login_name, $fuserid, $page, $rows);
	}

	// /**
	//  * @name 获取所有数据
	//  * @param int $id id
	//  * @return array
	//  */
	// public function get_all_data($id)
	// {
	// 	return $this->find_user_edit($id);
	// }

	/**
	 * @name 客户信息 修改
	 */
	public function find_user_edit($id)
	{
		$result = [];
		if (!empty($id)) {
			$result = $this->model->get_info_by_id($id);
		}
		return $result;
	}

	/**
	 * @name 提取身份证号码上的信息
	 * @other 预留获取其它信息
	 * @param string $idnumber
	 */
	public function get_id_number_info($idnumber)
	{
		$this->load->service('public/Idnumber_service', 'is');
		$ret = $this->is->checkId($idnumber);
		$age = $this->is->get_age_by_id($idnumber);
		return [
			'age' => $age,
			'sex' => $ret['sex'],
			'check' => $ret['check'],
			'area' => $ret['area'],
		];
	}

    /**
     * @name 通过用户ID 获取用户信息
     */
    public function find_user_by_fuserid($fuserid)
    {
    	return $this->model->get_user_info_by_uid($fuserid);
    }

	//新代码
	/**
	 * @name 新增客户信息时的检查
	 * @param string $idnumber 身份证号
	 * @return bool|array
	 */
	public function add_check($idnumber)
	{	
		//检查用户是否开户
		$user_info = $this->model->get_user_info_by_id_number($idnumber);

		$user_info = !empty($user_info[0]) ? $user_info[0] : '';
		if (!empty($user_info)) {
			//用户已开户 检查是否已经录入客户信息
			$info = $this->model->get_info_by_uid($user_info['fuserid']);
			if (empty($info)) {
				//没有客户信息记录 允许新增
				return [
					'fuserid' => $user_info['fuserid'],
					'idnumber' => $user_info['idnumber'],
					'name' => $user_info['name'],
				];
			} else {
				return '记录已存在';
			}
		}
		return '用户不存在,请先开户';
	}

	/**
	 * @name 客户信息查询列表 通过身份证或者姓名
	 * @param $idnumber 身份证号
	 * @param $name 姓名
	 * @return array
	 */
	public function user_list($idnumber = '', $name = '', $id = '')
	{
		if ($idnumber || $name) {
			$ret = $this->model->find_info_by_userinfo($idnumber, $name);
		} else {
			$ret = $this->model->get_info_by_id($id);
		}
    	return $ret;
	}

	/** 
	 * @name 新增客户
	 * @param array $data 
	 * @return 
	 */
	public function add_record($data)
	{
		$ret = $this->model->add_new_record($data);
		return $ret;
	}

	/**
	 * @name 编辑客户
	 * @param int $id 
	 */
	public function edit($id)
	{
		$ret = $this->model->get_info_by_id($id);
		$data = $ret;
		//身份证有效期计算
		$youtu_conf = $this->config->item('youtu_id_number');
		if (!empty($data['sample_' . $youtu_conf['valid_date_end']])) {
			if ('长期' != $data['sample_' . $youtu_conf['valid_date_end']]['val']) {
				//重新计算有效期
				$data['sample_' . $youtu_conf['valid_date_remaining_time']]['val'] = ceil((strtotime($data['sample_' . $youtu_conf['valid_date_end']]['val']) - strtotime("now")) / 86400);
			} else {
				$data['sample_' . $youtu_conf['valid_date_remaining_time']]['val'] = '长期';
			}
		}
		return $data;
	}

	/** 
	 * @name 获取全部数据
	 */
	public function get_all_data($id)
	{
		$ret = $this->model->get_info_by_id($id);
		Slog::log($ret);
		return [$ret];
	}
	/**
	 * @name 更新客户信息
	 */
	public function update_user_info($data)
	{
		//起优图service
		Slog::log('需要更新的内容 检查是否需要走优图');
		Slog::log($data);
		$front_img = 'sample_' . $this->config->item('id_number_front_img');
		$back_img = 'sample_' . $this->config->item('id_number_back_img');
		$youtu_data = [];
		$last_data = $this->model->get_user_info_by_uid($data['fuserid']);
		//数据合并flag
		$data_flag = 1;
		if ($last_data) {
			$user_info[$front_img] = [];
			$user_info[$front_img]['val'] = '';
			$user_info[$back_img] = [];
			$user_info[$back_img]['val'] = '';
			if (!empty($data[$front_img]) || !empty($data[$back_img])) {
				if (!empty($data[$front_img])) {
					if (!empty($last_data[$front_img])) {
						if ($last_data[$front_img]['val'] != $data[$front_img]) {
							$user_info[$front_img]['val'] = $data[$front_img];
						}
					} else {
						$user_info[$front_img]['val'] = $data[$front_img];
					}
				}

				if (!empty($data[$back_img])) {
					if (!empty($last_data[$back_img])) {
						if ($last_data[$back_img]['val'] != $data[$back_img]) {
							$user_info[$back_img]['val'] = $data[$back_img];
						}
					} else {
						$user_info[$back_img]['val'] = $data[$back_img];
					}
				}
				
				$this->load->service('public/Youtu_service', 'youtu_service');
				if (!empty($user_info[$back_img]['val']) || !empty($user_info[$front_img]['val'])) {
					$youtu_data = $this->youtu_service->do_youtu($user_info);
				}
				Slog::log($youtu_data);
				foreach ($youtu_data as $item) {
					if (isset($item['errorcode']) && $item['errorcode'] <= -1) {
						Slog::log('图片类型错误: ' . $item['errormsg']);
						return [
				        	'code' => -2,
				        	'msg' => '图片类型错误',
				        	'data' => [],
				        ];
					}
					if ('CONNECT_FAIL' == $item) {
						Slog::log('优图连接失败: ' . $item);
						$youtu_data = $this->youtu_service->do_youtu($user_info);
						foreach ($youtu_data as $item) {
							if (isset($item['errorcode']) && $item['errorcode'] <= -1) {
								Slog::log('图片类型错误: ' . $item['errormsg']);
								return [
						        	'code' => -2,
						        	'msg' => '图片类型错误',
						        	'data' => [],
						        ];
							}
						}
					}
				}

				$check_flag = 1;
				foreach ($youtu_data as $value) {
					if (!empty($value)) {
						$check_flag = 0;
					}
				}
				if ($check_flag) {
					return [
			        	'code' => -3,
			        	'msg' => '图片分析失败',
			        	'data' => [],
			        ];
				}

				//校验数据
				//姓名 身份证校验
				if (isset($youtu_data['id']) && $last_data['idnumber'] != $youtu_data['id']) {
					Slog::log('身份证号码比较失败');
					$data_flag = 0;
				}
				if (isset($youtu_data['name']) && $last_data['name'] != $youtu_data['name']) {
					Slog::log('姓名比较失败');
					$data_flag = 0;
				}
				//有效期 校验 
				if (isset($youtu_data['valid_date_end']) && ('长期' == $youtu_data['valid_date_end'])) {
					Slog::log('身份证是长期');
				} else if (isset($youtu_data['valid_date_end']) && (strtotime($youtu_data['valid_date_end']) <= strtotime("now"))) {
					Slog::log('有效期 校验失败');
					$data_flag = 0;
				}

				//数据转义
				if ($data_flag) {
					foreach ($this->config->item('youtu_id_number') as $key => $value) {
						if (!empty($youtu_data[$key])) {
							$data['sample_' . $value] = $youtu_data[$key];
						}
					}
					Slog::log('整理好的数据');
					Slog::log($data);
				} else {
					Slog::log('数据不需要合并');
					//直接返回
					$check_str = '图片信息与注册信息不符 请检查';
				}

			}
			if (empty($data['sample_23'])) {
				unset($data['sample_23']);
			}
			if (empty($data['sample_24'])) {
				unset($data['sample_24']);
			}
			
		} else {
			Slog::log('用户查询失败');
		}
		$ret = $this->model->update_info_by_edit($data);

		//更新数据状态为通过
		if ($data_flag) {
			$change_status = [];
			foreach ($this->config->item('youtu_id_number') as $key => $value) {
				if (!empty($youtu_data[$key])) {
					$change_status[] = $value;
				}
			}
			// Slog::log('修改下列样本为通过');
			// Slog::log($change_status);
			//公共状态方法
			$this->load->helper(['publicstatus', 'checkrolepower']);
			$last_data = $this->model->get_user_info_by_uid($data['fuserid']);
			Slog::log('修改数据');
			Slog::log($last_data);
			sleep(2);
			foreach ($change_status as $value) {
				Slog::log([$last_data['sample_' . $value]['type'], $last_data['sample_' . $value]['id']]);
				editStatus(
					$last_data['sample_' . $value]['type'],
					$last_data['sample_' . $value]['id'],
					20,
					'',
					'>= 0'
				);
			}
		}
		
	 	Slog::log(['更新用户数据 model 返回结果', $ret]);
    	if ($ret['code'] > -1) {
    		$str = '用户信息修改成功';
    	} else {
    		$str = '用户信息修改失败';
    	}

    	if (!empty($youtu_data)) {
    		//加工数据
	    	$result = [];
			$result_color = [];
			$result_check = [];
			$result_edit = [];
			//load service 
			$business_type = 'user';
			$id = $last_data['id'];
			$role_array = [
				'edit' => getRolePowerDetails('Qiye','edit'),
				'editdo' => getRolePowerDetails('Qiye','editdo'),
				'BaoShen' => getRolePowerDetails('Qiye','BaoShen'),
				'GuoShen' => getRolePowerDetails('Qiye','GuoShen'),
				'BackShen' => getRolePowerDetails('Qiye','BackShen'),
				'Stop' => getRolePowerDetails('Qiye','Stop'),
				'Start' => getRolePowerDetails('Qiye','Start'),
				'PleaseEdit' => getRolePowerDetails('Qiye','PleaseEdit'),
				'YesEdit' => getRolePowerDetails('Qiye','YesEdit'),
				'NoEdit' => getRolePowerDetails('Qiye','NoEdit'),
			];
			//调model获取数据
			if (!empty($id)) {
				$info = $this->edit(ucfirst($id));
				$result = $info;
				if (!empty($info)) {
					foreach ($info as $key => $value) {
						if (isset($info[$key]['val'])) {
							$result[$key] = $value['val'];
						}
						if (isset($info[$key]['status'])) {
							$result_color[$key] = $value['status'];
						}
						if (isset($info[$key]['is_check'])) {
							$result_check[$key] = $value['is_check'];
						}
					}
					$result['business_type'] = $business_type;
				} else {
					$result = [];
				}
			} else {
				$result = [];
			}
			foreach ($role_array as $key => $item) {
				foreach ($item as $value) {
					$result_role[$key]['sample_'.$value] = 1;
				}
			}
			$data = json_encode([
				'data' => $result, 
				'status'=> $result_color, 
				'check' => $result_check, 
				'edit' => !empty($result_role['edit']) ? $result_role['edit'] : [],
				'editdo' => !empty($result_role['editdo']) ? $result_role['editdo'] : [],
				'BaoShen' => !empty($result_role['BaoShen']) ? $result_role['BaoShen'] : [],
				'GuoShen' => !empty($result_role['GuoShen']) ? $result_role['GuoShen'] : [],
				'BackShen' => !empty($result_role['BackShen']) ? $result_role['BackShen'] : [],
				'Stop' => !empty($result_role['Stop']) ? $result_role['Stop'] : [],
				'Start' => !empty($result_role['Start']) ? $result_role['Start'] : [],
				'PleaseEdit' => !empty($result_role['PleaseEdit']) ? $result_role['PleaseEdit'] : [],
				'YesEdit' => !empty($result_role['YesEdit']) ? $result_role['YesEdit'] : [],
				'NoEdit' => !empty($result_role['NoEdit']) ? $result_role['NoEdit'] : [],
			]);
			if (!empty($check_str)) {
				$str = '优图分析完成 ' . $check_str;
				$youtu_code = -1;
			} else {
				$str = '优图分析完成';
			} 
			if (!isset($youtu_code)) {
				$youtu_code = 100;
			}
    	}
    	
        return [
        	'code' => (!empty($youtu_data) && isset($youtu_code)) ? $youtu_code : $ret['code'],
        	'msg' => $str,
        	'data' => !empty($youtu_data) ? $data : [],
        ];
	}
	
/*################证据池用##########################################*/
	//like string  fuserid AND name
    //
    /**
     * @name 证据池 客户管理页
     * @param string $condition 用于like的条件
     * @param int $page_num 页码
     * @param int $page_size 分页大小
     * @return  array ['list' => [], 'total' => int]
     */
    public function get_user_list_for_pool($condition = '', $page_num = 0, $page_size = 10)
    {
    	if ($condition) {
    		//生成条件  多字段like
    		$condition = ' CONCAT(`fuserid`,`name`) LIKE "%'. $condition .'%"';
    	} else {
    		$condition = ' 1 = 1 ';
    	}
    	return $this->model->get_user_list_like_page($condition, $page_size, $page_num);
    }

/*################首页用########################################*/
	public function search_by_condition($condition)
	{
		if (preg_match('/\d/', $condition)) {
			if (strlen($condition) > 10) {
				//身份证查询
				$ret = $this->model->db
				->like('idnumber', $condition, 'after')
				->get('fms_user')
				->result_array();
			} else {
				//客户编号查询
				$ret = $this->model->db
				->like('fuserid', $condition, 'after')
				->get('fms_user')
				->result_array();
			}
		} else {
			//姓名查询
			$ret = $this->model->db
				->like('name', $condition, 'after')
				->get('fms_user')
				->result_array();
		}
		Slog::log($ret);
		// return ['data' => $ret, 'msg' => $this->model->db->last_query()];
		return $ret;
	}

/*################前端用########################################*/
	/**
 	 * @name 用户登录 
 	 * @param string $login_name 登录账户
 	 * @param string $pwd 登录密码
 	 * @return array ['msg'=>string, 'code'=>int]
 	 */
	public function front_end_login($login_name, $pwd)
	{
		return $this->model->user_login($login_name, $pwd);
	}

	/**
	 * @name 检查账户名
	 * @param string $login_name
	 * @return array ['msg' => string, 'code' => int]
	 */
	public function check_login_name($login_name)
	{
		return $this->model->check_login_name($login_name);
	}

	/**
	 * @name 注册时设置密码
	 * @param string $login_name 登录账户
 	 * @param string $pwd 登录密码
 	 * @return array ['msg'=>string, 'code'=>int] 
	 */
	public function front_end_set_pwd($login_name, $pwd)
	{
		return $this->model->front_end_set_pwd($login_name, $pwd);
	}	

	
}