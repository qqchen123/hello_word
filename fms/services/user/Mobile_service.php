<?php 

/**
 * @desc 手机卡
 */
class Mobile_service extends Admin_service
{
	
	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user/Mobile_model', 'mobile');
		$this->load->model('user/User_model', 'user');
	}

	/**
	 * @name 新增手机卡记录时的检查
	 * @param string $idnumber 身份证号
	 * @return bool|array
	 */
	public function add_check($idnumber)
	{	
		//检查用户是否开户
		$user_info = $this->user->get_user_info_by_id_number($idnumber);

		$user_info = !empty($user_info[0]) ? $user_info[0] : '';
		if (!empty($user_info)) {
			//用户已开户 检查是否已经录入银行卡
			$mobile_info = $this->mobile->get_mobile_card_by_uid($user_info['fuserid']);
			if (empty($mobile_info)) {
				//没有银行卡记录 允许新增
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
	 * @name 手机卡信息查询列表 通过身份证或者姓名
	 * @param $idnumber 身份证号
	 * @param $name 姓名
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function mobile_card_list($idnumber = '', $name = '', $mobileid = '', $page = 1, $rows = 10)
	{
		if ($idnumber || $name) {
			Slog::log($idnumber.$name);
			$ret = $this->mobile->find_mobile_info_by_userinfo($idnumber, $name, $page, $rows);
		} else {
			$ret = $this->mobile->find_mobile_info_by_mobileid($mobileid, $page, $rows);
		}
    	return $ret;
	}

	/** 
	 * @name 新增手机卡
	 * @param array $data 
	 * @return 
	 */
	public function add_mobile_card($data)
	{
		$ret = $this->mobile->add_new_mobile_card($data);
		return $ret;
	}

	/**
	 * @name 编辑手机卡
	 * @param int $id 
	 */
	public function edit($id)
	{
		$ret = $this->mobile->find_mobile_card_info_for_edit($id);
		//根据后台账户权限 删减相应内容
		return $ret;
	}

	/** 
	 * @name 获取全部数据
	 */
	public function get_all_data($id)
	{
		$ret = $this->mobile->find_mobile_card_info_for_edit($id);
		Slog::log($ret);
		return [$ret];
	}
	/**
	 * @name 更新手机信息
	 */
	public function update_mobile_card($data)
	{
		$front_img = 'sample_' . $this->config->item('mobile_card_front_img');
		$youtu_data = [];
		//fuserid 换 id
		$this->load->service('user/User_service', 'user_service');
		$user_info = $this->user_service->find_user_by_fuserid($data['fuserid']);
		$last_data = $this->mobile->find_mobile_card_info_for_edit($user_info['id']);
		if ($last_data) {
			if (!empty($data[$front_img])) {
				if (empty($data[$front_img])) {
					unset($data[$front_img]);
				}
			} else {
				unset($data[$front_img]);
			}
		}
		$ret = $this->mobile->update_mobile_card_by_edit($data);
	 	Slog::log(['更新用户数据 model 返回结果', $ret]);
    	if (0 == $ret['code']) {
    		$str = '用户信息修改成功';
    	} else {
    		$str = '用户信息修改失败';
    	}
        return [
        	'code' => $ret['code'],
        	'msg' => $str,
        ];
		return $ret;
	}
	
	/**
	 * @name 手机卡报审
	 * @param string $fuserid 
	 * @param string $status_info 报审附加信息
	 * @param array $for_admins 通知到谁
	 * @return bool 
	 */
	public function applying_mobile_card($fuserid, $status_info = '', $for_admins = [])
	{
		$result = 0;

		$this->load->helper(['publicstatus']);
		//获取手机卡全部数据
		$user_info = $this->user->get_user_info_by_uid($fuserid);
		if (!isset($user_info[0]['id'])) {
			$result = -2;
		}
		$id = $user_info[0]['id'];
		$data = $this->edit($id);
		//提取需要报审的id
		$id_array = [];
		//证据池数据报审
		foreach ($data as $value) {
			if (isset($value['status']) && 1 == $value['status']) {
				$ret = baoShenStatus($value['type'], $value['id'], $status_info, $for_admins);
				if (empty($ret)) {
					$result = -1;
					return $result;
				} else {
					$result = 1;
				}
			}
		}
		//基础数据报审
		Slog::log(['mobile', $id, $status_info, $for_admins]);
		$result = baoShenStatus('mobile', $id, $status_info, $for_admins);
		return $result;
	}

	/**
	 * @name 初审通过
	 * @param int $id 手机卡ID
	 * @param array $pass_array 初审通过的证据池数据id
	 * @param string $status_info 报审附加信息
	 * @param array $for_admins 通知到谁
	 * @return bool 
	 */
	public function first_trial_pass($id, $pass_array = [], $status_info = '', $for_admins = [])
	{
		$result = 0;
		$this->load->helper(['publicstatus']);
		//证据池数据通过
		foreach ($pass_array as $value) {
			$ret = guoChuShenStatus($value['type'], $value['id'], $status_info, $for_admins);
			if (!$ret) {
				$result = -1;
				return $result;
			} else {
				$result = 1;
			}
		}
		return $result;
	}
}