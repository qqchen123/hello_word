<?php

/**
 * @desc 用户机构开户表
 * @notice 获取数据时必须指定最后返回的字段 否则会调用show_fields配置的字段内容
 *			fucntion get_user_org_record 目前不知道怎么迁
 */
class UserOrganization_model extends Admin_Model
{
	/**
	 * @name model 操作的表名
	 */
	public $table_name = 'fms_user_organization';

	/**
	 * @name 默认显示的字段
	 */
	private $show_fields = [
		'fuserid',
	];

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 添加银行卡
	 * @param array $data 
	 */
	public function add_new_user_org(array $data)
	{
		$data['fuserid'] = $data['fuserid'];
		$data['lutime'] = $data['ctime'] = date("Y-m-d H:i:s");
        $this->db->insert($this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
        	$id = $this->db->insert_id();
        	//插入公共状态
        	// $this->add_public_status('user', $id);
        	$res['val'] = true;
            $res['code'] = '01';//'success';
            return $res;
        }

        $res['code'] = '-2';//'插入失败';
        return $res;
	}

	/**
	 * @name 通过uid 获取银行号信息
	 * @param int $uid 用户ID
	 * @return array
	 */
	public function get_user_org_by_uid($uid)
	{
		return $this->get_user_org_record(['fuserid' => $uid]);
	}

	/**
	 * @name 通过idnumber 获取银行号信息
	 * @param string $user_org_no 银行号
	 * @return array
	 */
	public function get_user_org_by_org_no(string $org_no)
	{
		return $this->get_user_org_record(['orgNo' => $org_no]);
	}

	/**
     * @name 获取记录
     * @param array $condition [key => value]
     * @param array $fields [原名, [别名 => 原名] ...] 可以是两种写法混合
     * @param int $public_status 是否join public_status
     * @return array
     */
    public function get_user_org_record(array $condition, array $fields = [], $public_status = 0)
    {
        $ret = [];
        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                if (!preg_match('/;/', $value)) {
                    $this->db->where($key, $value); 
                } else {
                    return $ret;
                }
            } 
            if (1 == $public_status) {
                $this->join_public_status();
            }
            $str = $this->ret_select($this->show_fields, $public_status);
            $this->db->select($str);
            $ret = $this->db->get($this->table_name)->result_array();
        }
        return $ret;
    }

}