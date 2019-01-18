<?php 

/**
 * @desc 银信账户概况
 */
class YxAccountAbout_model extends Admin_model
{
	
	public $table_name = 'fms_yx_account_about';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	public function record_data($data, $account)
	{
		$check = $this->db->select(['account'])
		->where('account', $account)
		->get($this->table_name)
		->row_array();
		if (empty($check)) {
			//insert
			$insert_array = [
				'account' => $account,	//银信账号
				'ctime' => date('Y-m-d H:i:s', time()),	//创建时间
				'ex_service' => !empty($data['专属客服']) ? $data['专属客服'] : '未知',	//专属客服
				'have_sp_dep' => !empty($data['存管专户是否开通']) ? $data['存管专户是否开通'] : '未知',		//存管专户是否开通
				'sp_dep_account' => !empty($data['存管专户账号']) ? $data['存管专户账号'] : '未知',		//存管专户账号
				'is_real_name' => !empty($data['是否实名认证']) ? $data['是否实名认证'] : '未知',	//是否实名认证
				'real_name' => !empty($data['认证人姓名']) ? $data['认证人姓名'] : '未知',	//认证人姓名
				'is_bind_phone' => !empty($data['是否绑定手机号']) ? $data['是否绑定手机号'] : '未知',	//是否绑定手机号
				'bind_phone' => !empty($data['绑定手机']) ? $data['绑定手机'] : '未知',	//绑定手机
				'is_bind_email' => !empty($data['是否绑定邮箱']) ? $data['是否绑定邮箱'] : '未知',	//是否绑定邮箱
				'last_login_time' => !empty($data['上次登录时间']) ? $data['上次登录时间'] : '未知',		//上次登录时间
				'rec_pay_message' => !empty($data['回款短信']) ? $data['回款短信'] : '未知',		//回款短信
				'repay_message' => !empty($data['还款短信']) ? $data['还款短信'] : '未知',	//还款短信
				'lutime' => date('Y-m-d H:i:s', time()),	//最后更新时间
				'json_bak' => json_encode([$data, $account], JSON_UNESCAPED_UNICODE),	//数据json备份
			];
			$this->db->insert($this->table_name, $insert_array);
			return $this->db->insert_id();
		} else {
			//update
			$this->db->set('ex_service', !empty($data['专属客服']) ? $data['专属客服'] : '未知');
			$this->db->set('have_sp_dep', !empty($data['存管专户是否开通']) ? $data['存管专户是否开通'] : '未知');
			$this->db->set('sp_dep_account', !empty($data['存管专户账号']) ? $data['存管专户账号'] : '未知');
			$this->db->set('is_real_name', !empty($data['是否实名认证']) ? $data['是否实名认证'] : '未知');
			$this->db->set('real_name', !empty($data['认证人姓名']) ? $data['认证人姓名'] : '未知');
			$this->db->set('is_bind_phone', !empty($data['是否绑定手机号']) ? $data['是否绑定手机号'] : '未知');
			$this->db->set('bind_phone', !empty($data['绑定手机']) ? $data['绑定手机'] : '未知');
			$this->db->set('is_bind_email', !empty($data['是否绑定邮箱']) ? $data['是否绑定邮箱'] : '未知');
			$this->db->set('last_login_time', !empty($data['上次登录时间']) ? $data['上次登录时间'] : '未知');
			$this->db->set('rec_pay_message', !empty($data['回款短信']) ? $data['回款短信'] : '未知');
			$this->db->set('repay_message', !empty($data['还款短信']) ? $data['还款短信'] : '未知');
			$this->db->set('json_bak', json_encode([$data, $account], JSON_UNESCAPED_UNICODE));
			$this->db->set('lutime', date('Y-m-d H:i:s', time()));
			$this->db->where('account', $account);
			$ret = $this->db->update($this->table_name);
			return $ret;
		}
	}

	public function get_account_about($account)
	{
		$ret = $this->db->select([
			'account', 
			'ex_service', 
			'have_sp_dep',
			'sp_dep_account',
			'is_real_name',
			'real_name',
			'is_bind_phone',
			'bind_phone',
			'is_bind_email',
			'last_login_time',
			'rec_pay_message',
			'repay_message',
		])
		->where('account', $account)
		->get($this->table_name)
		->row_array();
		return [
			'登录用户名' => !empty($ret['account']) ? $ret['account'] : '',
			'专属客服' => !empty($ret['ex_service']) ? $ret['ex_service'] : '',
			'存管专户是否开通' => !empty($ret['have_sp_dep']) ? $ret['have_sp_dep'] : '',
			'存管专用账号' => !empty($ret['sp_dep_account']) ? $ret['sp_dep_account'] : '',
			'是否实名认证' => !empty($ret['is_real_name']) ? $ret['is_real_name'] : '',
			'认证人姓名' => !empty($ret['real_name']) ? $ret['real_name'] : '',
			'是否绑定手机号' => !empty($ret['is_bind_phone']) ? $ret['is_bind_phone'] : '',
			'绑定手机' => !empty($ret['bind_phone']) ? $ret['bind_phone'] : '',
			'是否绑定邮箱' => !empty($ret['is_bind_email']) ? $ret['is_bind_email'] : '',
			'上次登录时间' => !empty($ret['last_login_time']) ? $ret['last_login_time'] : '',
			'回款短信' => !empty($ret['rec_pay_message']) ? $ret['rec_pay_message'] : '',
			'还款短信' => !empty($ret['repay_message']) ? $ret['repay_message'] : '',
		];
	}



}

?>


