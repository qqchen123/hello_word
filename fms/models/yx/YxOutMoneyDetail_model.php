<?php 

/**
 * @desc 银信总账号出借详情
 */
class YxOutMoneyDetail_model extends Admin_Model
{
	public $table_name = 'fms_yx_out_money_detail';

	function __construct(){
		parent::__construct();
	}

	public function replace_data($data){
		$add = trim(strstr($data['out_cyc'],'个月',true));
		var_dump($add);
		$data['expire_time'] = date("Y-m-d", strtotime("+".$add." months", strtotime($data['out_date'])));

		return $this->db->replace($this->table_name,$data);
	}

	public function update_if_assessment($data,$where){
		return $this->db->update($this->table_name,$data,$where);
	}

	public function list_out_money_detail($like='',$page=1,$rows=10,$sort='out_date',$order='DESC',$account){
        $this->db->where('account',$account);
        $total = $this->db->count_all_results('fms_yx_out_money_detail',false);
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
