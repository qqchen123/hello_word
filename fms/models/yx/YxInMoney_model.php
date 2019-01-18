<?php 

/**
 * @desc 银信总账号借款
 */
class YxInMoney_model extends Admin_Model
{
	public $table_name = 'fms_yx_in_money';

	function __construct(){
		parent::__construct();
	}

	public function replace_data($data){
		return $this->db->replace($this->table_name,$data);
	}

	public function update_if_assessment($data,$where){
		return $this->db->update($this->table_name,$data,$where);
	}


	public function list_in_money($like='',$page=1,$rows=10,$sort='reg_time',$order='DESC'){

        if ($like){
	        $this->db->or_like('fms_yx_in_money.account',$like);
	        $this->db->or_like('fms_user.fuserid',$like);
	        $this->db->or_like('fms_user.name',$like);
	        $this->db->or_like('fms_user.idnumber',$like);
	        $this->db->or_like('fms_yx_account.binding_phone',$like);
        }

        $sub_detail_sql = '(select `account`,`user_name`,`id_number`,`call_number` from `fms_yx_in_money_detail` GROUP BY `user_name`,`id_number`,`call_number`) as tmp_detail';
		$this->db
			->join('fms_yx_account','fms_yx_account.account=fms_yx_in_money.account','left')
			->join('fms_user','fms_user.yx_account=fms_yx_in_money.account','left')
			->join($sub_detail_sql,'tmp_detail.account=fms_yx_in_money.account','left');
			;

		$this->db->select([
			'fms_yx_in_money.*',

			'fms_yx_account.account fms_yx_account-acount',
			'fms_yx_account.binding_phone fms_yx_account-bind_phone',

			'fms_user.yx_account fms_user-yx_account',
			'fms_user.fuserid fms_user-fuserid',
			'fms_user.name fms_user-name',
			'fms_user.idnumber fms_user-idnumber',

			'tmp_detail.user_name tmp_detail-user_name',
			'tmp_detail.id_number tmp_detail-id_number',
			'tmp_detail.call_number tmp_detail-call_number',
		]);
        $total = $this->db->count_all_results('fms_yx_in_money',false);
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
}
?>
