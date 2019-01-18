<?php
class WorkLog_model extends CI_Model{

    // function getUser($where=[],$where_in=[]){
    //     if($where_in!=[]) $this->db->where_in($where_in[0],$where_in[1]);
    //     return $this->db
    //         ->select(['wesing_merchant.*','role_name'])
    //         ->join('fms_role','role_id=userrole')
    //         ->where($where)
    //         ->get('wesing_merchant')
    //         ->result_array();
    //         // return $this->db->last_query();
    // }

    //批量添加工作日志
    function addWorkLog($data){
        // $data = $this->checkUser($data);
        $this->db->insert_batch('fms_work_log',$data);
        // return $this->db->insert_id();
        return $this->db->affected_rows();
    }

    //添加某人一条工作日志
    // function addOneWorkLog($data){
    //     $this->db->insert('fms_work_log',$data);
    //     return $this->db->insert_id();
    // }

    //完成工作日志
    function completeWorkLog($data,$where){
        $this->db->update('fms_work_log',$data,$where);
        // return $this->db->insert_id();
        return $this->db->affected_rows();
    }

    //工作日志列表
    function list_me_wl($for_from='for',$complete_status='',$page=1,$rows=10,$sort='create_date',$order='desc'){
        if(!$sort) $sort='create_date';
        if(!$order) $order='desc';
        $admin_id = $_SESSION['fms_id'];
        if($for_from=='from'){
            $this->db->where(['from_uid'=>$admin_id]);
        }else{
            $this->db->where(['for_uid'=>$admin_id]);
        }

        if ($complete_status!=='' && $complete_status!==null) $this->db->where('complete_status',$complete_status);

        //关联杂码取对象中文名称
        $this->db->join('fms_obj obj','type=obj_type','left');
        $total = $this->db->count_all_results('fms_work_log wl',false);
        $rs = $this->db->select(['wl.*','obj_name','obj_url'])->order_by($sort,$order)->limit($rows,($page-1)*$rows)->get();
        // return $this->db->last_query();
        if ($rs->num_rows() > 0) {
            $res["total"]= $total;
            $res['rows'] = $rs->result_array();
            $rs->free_result();
        } else {
            $res["total"]= 0;
            $res["rows"] = '';
        }
        //return $this->db->last_query();
        return $res;
    }

    //根据主键获取单条数据
    function get_wl_by_id($id,$select=['*']){
        $this->db->select($select);
        return $this->db->get_where('fms_work_log',['wl_id'=>$id],1)->row_array();
    }

    //工作日志进展附表
    function list_me_wl_march($parent_id,$page=1,$rows=10,$sort='create_date',$order='desc'){
        //不指定归属工作日志 不得访问
        if ($parent_id) $this->db->where(['parent_id'=>$parent_id]);
        $total = $this->db->count_all_results('fms_work_log_march wlm',false);
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
        //return $this->db->last_query();
        return $res;
    }

    //增加工作进展
    function addWorkLogMarch($data){
        $this->db->insert('fms_work_log_march',$data);
        // return $this->db->insert_id();
        return $this->db->affected_rows();
    }
    

}
?>