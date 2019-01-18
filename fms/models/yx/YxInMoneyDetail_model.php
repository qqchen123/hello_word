<?php 

/**
 * @desc 银信总账号借款详情
 */
class YxInMoneyDetail_model extends Admin_Model
{
	public $table_name = 'fms_yx_in_money_detail';

	function __construct(){
		parent::__construct();
	}

	public function replace_data($data){
		$add = trim(strstr($data['in_cyc'],'月',true));
		$data['expire_time'] = date("Y-m-d", strtotime("+".$add." months", strtotime($data['loan_date'])));

		return $this->db->replace($this->table_name,$data);
	}

	public function update_if_assessment($data,$where){
		return $this->db->update($this->table_name,$data,$where);
	}

	public function list_in_money_detail($like='',$page=1,$rows=10,$sort='in_date',$order='DESC',$account){
        $this->db->where('account',$account);
        $total = $this->db->count_all_results('fms_yx_in_money_detail',false);
        $rs = $this->db->order_by($sort,$order)->limit($rows,($page-1)*$rows)->get();
        if ($rs->num_rows() > 0) {
            $res["total"]= $total;
            $res['rows'] = $rs->result_array();
            $rs->free_result();
        } else {
            $res["total"]= 0;
            $res["rows"] = '';
        }
        return $res;
	}

	/**
	 * @name 查询借款详情
	 * @param $account 银信账号
	 * @author 梁俸嘉
	 * @return array 
	 */
	public function find_by_userinfo($account)
	{
		return $this->db->where('account', $account)
		->get($this->table_name)
		->result_array();
	}

	/**
	 * @name 查询到期时间最晚的订单
	 * @param $account 银信账号
	 * @author 梁俸嘉
	 * @return array
	 */
	public function find_last_record($account)
	{
		return $this->db->where('account', $account)
		->order_by('expire_time', 'DESC')
		->get($this->table_name)
		->row_array();
	}

}
?>
