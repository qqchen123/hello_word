<?php

/**
 * @desc 银行卡  service
 */
class Bank_service extends Admin_service
{
	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user/Bank_model', 'bank_model');
		$this->load->model('user/User_model', 'user');
	}

	/**
	 * @name 新增银行卡记录时的检查
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
			$info = $this->bank_model->get_info_by_uid($user_info['fuserid']);
			if (empty($info)) {
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
	 * @name 银行卡信息查询列表 通过身份证或者姓名
	 * @param $idnumber 身份证号
	 * @param $name 姓名
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function bank_card_list($idnumber = '', $name = '', $no = '', $page = 1, $rows = 10)
	{
		if ($idnumber || $name) {
			$ret = $ret = $this->bank_model->find_info_by_userinfo($idnumber, $name, $page, $rows);
		} else {
			Slog::log($no);
			$ret = $this->bank_model->get_info_by_no($no, $page, $rows);
		}
    	return $ret;
	}

	/** 
	 * @name 新增银行卡
	 * @param array $data 
	 * @return 
	 */
	public function add_record($data)
	{
		$ret = $this->bank_model->add_new_record($data);
		return $ret;
	}

	/**
	 * @name 编辑银行卡
	 * @param int $id 
	 */
	public function edit($id)
	{
		$ret = $this->bank_model->find_info_for_edit($id);
		//根据后台账户权限 删减相应内容
		return $ret;
	}

	/** 
	 * @name 获取全部数据
	 */
	public function get_all_data($id)
	{
		$ret = $this->bank_model->find_info_for_edit($id);
		Slog::log($ret);
		return [$ret];
	}
	/**
	 * @name 更新银行信息
	 */
	public function update_bank_card($data)
	{
		$front_img = 'sample_' . $this->config->item('bank_card_front_img');
		$login_name = 'sample_' . $this->config->item('bank_card_login_name_img');
		$youtu_data = [];
		$this->load->service('user/User_service', 'user_service');
		$user_info = $this->user_service->find_user_by_fuserid($data['fuserid']);
		$last_data = $this->bank_model->find_info_for_edit($user_info['id']);
		if ($last_data) {
			if (!empty($data[$front_img])) {
				if (empty($data[$front_img])) {
					unset($data[$front_img]);
				}
			} else {
				unset($data[$front_img]);
			}

			if (!empty($data[$login_name])) {
				if (empty($data[$login_name])) {
					unset($data[$login_name]);
				}
			} else {
				unset($data[$login_name]);
			}
		}
		$ret = $this->bank_model->update_info_by_edit($data);
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
}