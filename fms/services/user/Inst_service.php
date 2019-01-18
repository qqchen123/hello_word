<?php 

/**
 * @desc 机构账户  Institution
 */
class Inst_service extends Admin_service
{
	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user/Inst_model', 'inst');
		$this->load->model('user/User_model', 'user');
	}

	/**
	 * @name 新增客户机构账户记录时的检查
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
			$info = $this->inst->get_info_by_uid($user_info['fuserid']);
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
	 * @name 客户机构账户信息查询列表 通过身份证或者姓名
	 * @param $idnumber 身份证号
	 * @param $name 姓名
	 * @param int $page 页码数
	 * @param int $rows 每页行数
	 * @return array
	 */
	public function institution_list($idnumber = '', $name = '', $no = '', $page = 1, $rows = 10)
	{
		if ($idnumber || $name) {
			$ret = $this->inst->find_info_by_userinfo($idnumber, $name, $page, $rows);
		} else {
			$ret = $this->inst->get_info_by_no($no, $page, $rows);
		}
    	return $ret;
	}

	/** 
	 * @name 新增客户机构账户信息
	 * @param array $data 
	 * @return 
	 */
	public function add_record($data)
	{
		$ret = $this->inst->add_new_record($data);
		return $ret;
	}

	/**
	 * @name 编辑客户机构账户信息
	 * @param int $id 
	 */
	public function edit($id)
	{
		$ret = $this->inst->find_info_for_edit($id);
		//根据后台账户权限 删减相应内容
		return $ret;
	}

	/** 
	 * @name 获取客户机构账户全部数据
	 */
	public function get_all_data($id)
	{
		$ret = $this->inst->find_info_for_edit($id);
		Slog::log($ret);
		return [$ret];
	}
	/**
	 * @name 更新客户机构账户信息
	 */
	public function update_institution($data)
	{
		$ret = $this->inst->update_info_by_edit($data);
	 	Slog::log(['更新用户数据 model 返回结果', $ret]);
    	if (!$ret['code']) {
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