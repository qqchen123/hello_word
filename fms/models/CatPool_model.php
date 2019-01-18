<?php
class CatPool_model extends CI_Model{
    function list_cat_pool($like=null,$page=1,$rows=10,$sort='cp_id',$order='ASC'){
        if($like!==null){
            $this->db->or_like('fuserid',$like);
            $this->db->or_like('sim_num',$like);
            $this->db->or_like('name',$like);
        }
        $total = $this->db->join('fms_user','user_id=fms_user.id','left')->count_all_results('fms_cat_pool',false);
        $rs = $this->db->order_by($sort,$order)->limit($rows,($page-1)*$rows)->get();
        // return $this->db->last_query();
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

    //获取全部的sim卡
    function get_cat_pool($where=[]){
        if($where!=[]) $this->db->where($where);
        return $this->db/*->where('status !="无卡"')*/->get('fms_cat_pool')->result_array();
    }

    //获取指定cp_id的一条数据
    function get_cat_pool_by_cpid($cp_id){
        return $this->db->join('fms_user','user_id=fms_user.id','left')->where(['cp_id'=>$cp_id])->get('fms_cat_pool')->row_array();
    }

    //批量新增sim卡
    function add($data){
        return $this->db->insert_batch('fms_cat_pool',$data);
    }

    //改状态 无效,有效,无卡
    function update_status($where,$status){
        $this->db->where_in('iccid',$where)->update('fms_cat_pool',['status'=>$status]);
        return $this->db->affected_rows();
    }

    //批量更新sim卡信息
    function update_batch($data,$where_status=['无效','有效','无卡'],$where_filed='iccid'){
        if($where_status != ['无效','有效','无卡']){
            foreach ($where_status as $key => $val) {
                $this->db->or_where(['status'=>$val]);
            }
        }
        $this->db->update_batch('fms_cat_pool',$data,$where_filed);
        // echo  $this->db->last_query();
        return $this->db->affected_rows();
    }

    //根据线路取cp_id
    function get_id_by_lines($lines=[]){
        return $this->db->select(['line_id','cp_id'])->where_in('line_id',$lines)->where('status != "无卡"')->get('fms_cat_pool')->result_array();
    }

    //批量更新
    function update_pay_list($data){
        $this->db->replace('fms_cat_pool_pay_list',$data);
        return $this->db->affected_rows();
    }

    //根据cp_id更新
    function update_by_cpid($data,$cp_id){
        $this->db->update('fms_cat_pool',$data,['cp_id'=>$cp_id]);
        return $this->db->affected_rows();
    }

    //开关自动更新
    function update_auto_pay($cp_id){
        return $this->db->query("
            update `fms_cat_pool` set `auto_pay` = mod(`auto_pay`,2)+1 where `cp_id`={$cp_id};
        ");
    }

    //充值记录
    function get_pay_data($cp_id,$like=null,$page=1,$rows=10,$sort='cp_id',$order='ASC'){
        if($like!==null){
            $this->db->or_like('money',$like);
            $this->db->or_like('get_money_time',$like);
            $this->db->or_like('pay_money',$like);
            $this->db->or_like('pay_money_time',$like);
        }
        if($sort=='cp_id' || $sort ==null){
            $this->db->select('*,ifnull(pay_money_time,get_money_time) as tmp ')->order_by("tmp",$order);
        }else{
            $this->db->order_by($sort,$order);
        }

        // $sort = "CONCAT(pay_money_time,get_money_time)";
        
        $total = $this->db->where(['cp_id'=>$cp_id])->count_all_results('fms_cat_pool_pay_list',false);
        $rs = $this->db->limit($rows,($page-1)*$rows)->get();
        // return $this->db->last_query();
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