<?php 

/**
 * @desc 银信账号余额model
 */
class YxAccountHaveMoney_model extends Admin_Model
{
	public $table_name = 'fms_yx_account_have_money';

	/**
	 * @name 构造函数
	 * acctBal 			可用余额
	 * acctAmount 		总余额
	 * walletAmount		银信宝 //不用
	 * frozBl			冻结金额
	 * if_assessment	是否已做风险问卷
	 * 
	 * 
	 * 
	 */
	function __construct()
	{
		parent::__construct();
	}

	public function replace_data($data){
		return $this->db->replace($this->table_name,$data);
	}

	public function update_if_assessment($data,$where){
		return $this->db->update($this->table_name,$data,$where);
	}
}
?>
