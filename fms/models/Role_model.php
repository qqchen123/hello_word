<?php
class Role_model extends CI_Model{
    function check_role_name($role_name,$role_id,$dpm_id){
        if($role_id) $this->db->where(['role_id !='=>$role_id]);
        return $this->db
            ->select('count(role_id) num')
            ->where(['role_name'=>$role_name,'department'=>$dpm_id])
            ->get('fms_role')
            ->row_array()['num'];
    }

    private function sort($id,$parent_id){
        if($parent_id=='0'){
            $parent_id_sort = '0';
        }else{
            $parent_id_sort = $this->db->where(['role_id'=>$parent_id])->select('sort')->get('fms_role')->row_array()['sort'];
        }
        $this->db->update('fms_role',['sort'=>$parent_id_sort.','.$id],['role_id'=>$id]);
    }

	function add($data){
        // $data['create_time'] = date('Y-m-d H:i:s');

        $this->db->trans_start();//开启事务
            //插入新对象
            $this->db->insert('fms_role',$data);
            $id = $this->db->insert_id();
            $this->sort($id,$data['parent_role_id']);
        $this->db->trans_complete();

        if ($this->db->trans_status()){
            return $id;
        }else{
            return false;
        }
    }

    function edit($data,$where){
        // $data['last_edit_time'] = date('Y-m-d H:i:s');
        $this->db->trans_start();//开启事务
            $this->db->update('fms_role',$data,$where);
            $this->sort($where['role_id'],$data['parent_role_id']);
        $this->db->trans_complete();

        if ($this->db->trans_status()){
            return true;
        }else{
            return false;
        }
    }

    // function list($like='',$page=1,$rows=10,$sort='role_id',$order='ASC',$role_id=null){

    //     //$this->load->helper('publicstatus');
    //     //joinStatus('jg','jg_id');

    //     if ($role_id) $this->db->where('role_id',$jg_id);
    //     if ($like) $this->db->like('role_name',$like);
    //     $total = $this->db->count_all_results('fms_role',false);
    //     $rs = $this->db->order_by($sort,$order)->limit($rows,($page-1)*$rows)->get();
    //     // return $this->db->last_query();
    //     if ($rs->num_rows() > 0) {
    //         $res["total"]= $total;
    //         $res['rows'] = $rs->result_array();
    //         $rs->free_result();
    //         // $res['rows'] = showStatusColor($res['rows']);
    //     } else {
    //         $res["total"]= 0;
    //         $res["rows"] = '';
    //     }
    //     return $res;
    // }

    function tree($select,$sort,$order,$department_id){
        if($select!==null){
            $this->db->select(['parent_role_id','role_id id','concat(department_name," ",role_name) text']);
        }else{
            $this->db->select(['a.*',/*'b.role_name parent_role_name',*/'c.department_name']);
        }
        if($department_id) $this->db->where(['department'=>$department_id]);
        return $this->db
            ->join('fms_department c','department=department_id','left')
            // ->join('fms_role b','b.role_id=a.parent_role_id','left')
            ->order_by($sort,$order)
            ->get('fms_role a')
            ->result_array();
    }

    function get_role($role_id){
        return $this->db->get_where('fms_role',['role_id'=>$role_id])->row_array();
    }

    function if_son($role_id){
        return $this->db->select('count(role_id) num')->where(['parent_role_id'=>$role_id])->get('fms_role')->row_array()['num'];
    }

    function del($role_id){
        $this->db->trans_start();//开启事务
            $this->db->delete('fms_role',['role_id'=>$role_id]);
            $this->db->delete('wesing_merchant_role',['roleid'=>$role_id]);
            $this->db->delete('sys_role_method',['role_id'=>$role_id]);
        $this->db->trans_complete();

        if ($this->db->trans_status()){
            return true;
        }else{
            return false;
        }
    }

    function get_by_department($department_id){
        return $this->db->get_where('fms_role',['department'=>$department_id])->result_array();
    }
}
?>