<?php
class Department_model extends CI_Model{
    function check_department_name($department_name,$department_id){
        if($department_id) $this->db->where(['department_id !='=>$department_id]);
        return $this->db
            ->select('count(department_id) num')
            ->where(['department_name'=>$department_name])
            ->get('fms_department')
            ->row_array()['num'];
    }

    private function sort($id,$parent_id){
        if($parent_id=='0'){
            $parent_id_sort = '0';
        }else{
            $parent_id_sort = $this->db->where(['department_id'=>$parent_id])->select('id_sort')->get('fms_department')->row_array()['id_sort'];
        }
        $this->db->update('fms_department',['id_sort'=>$parent_id_sort.','.$id],['department_id'=>$id]);
    }

	function add($data){
        // $data['create_time'] = date('Y-m-d H:i:s');

        $this->db->trans_start();//开启事务
            //插入新对象
            $this->db->insert('fms_department',$data);
            $id = $this->db->insert_id();
            $this->sort($id,$data['parent_department_id']);
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
            $this->db->update('fms_department',$data,$where);
            $this->sort($where['department_id'],$data['parent_department_id']);
        $this->db->trans_complete();

        if ($this->db->trans_status()){
            return true;
        }else{
            return false;
        }
    }

    // function list($like='',$page=1,$rows=10,$sort='department_id',$order='ASC',$department_id=null){

    //     //$this->load->helper('publicstatus');
    //     //joinStatus('jg','jg_id');

    //     if ($department_id) $this->db->where('department_id',$jg_id);
    //     if ($like) $this->db->like('department_name',$like);
    //     $total = $this->db->count_all_results('fms_department',false);
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

    function tree($select,$sort,$order){
        if($select!==null){
            $this->db->select(['parent_department_id','department_id id','department_name text']);
        }else{
            $this->db->select(['fms_department.*','fms_role.role_name leader_role_id']);
        }
        return $this->db
            ->join('fms_role','leader_role_id=role_id','left')
            ->order_by($sort,$order)
            ->get('fms_department')
            ->result_array();
    }

    function get_one($department_id){
        return $this->db->get_where('fms_department',['department_id'=>$department_id])->row_array();
    }

    function if_son($department_id){
        return $this->db->select('count(department_id) num')->where(['parent_department_id'=>$department_id])->get('fms_department')->row_array()['num'];
    }

    function del($department_id){
        return $this->db->delete('fms_department',['department_id'=>$department_id]);
    }

    function get_by_leader_role_id($role_id){
        return $this->db->get_where('fms_department',['leader_role_id'=>$role_id])->result_array();
    }

    function get_parent_name($department_id,$level=1){
        $level;
        $max_level = 10;
        if($level>$max_level) return [];

        $arr[] = $this->db->select(['department_name','department_id','parent_department_id'])->where(['department_id'=>$department_id])->get('fms_department')->row_array();

        if($arr[0]['parent_department_id']==0){
            return $arr;
        }else{
            return array_merge($this->get_parent_name($arr[0]['parent_department_id'],++$level),$arr);
        }
    }
}
?>