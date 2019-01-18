<?php
class PublicStatus_model extends CI_Model{
    function addStatus($data){
        $this->db->insert('fms_status_history',$data);
        $this->db->insert('fms_status',$data);
        // return $this->db->insert_id();
        return $this->db->affected_rows();
    }

    function editStatus($data,$where){
        $num = $this->db->where($where)->where([
            'obj_type' => $data['obj_type'],
            'obj_id' => $data['obj_id']
        ])->count_all_results('fms_status',false);
        // return $this->db->last_query();
        if($num==0) return 0;

        $this->db->replace('fms_status',$data);
        $this->db->insert('fms_status_history',$data);
        // return $this->db->insert_id();
        return $this->db->affected_rows();
    }

    function getOneStatus($obj_type,$obj_id){
        return $this->db->get_where('fms_status',[
            'obj_type' => $obj_type,
            'obj_id' => $obj_id
        ],1)->row_array();
    }

    function getOneHistoryStatus($obj_type,$obj_id){
        return $this->db->order_by('status_edit_time')->get_where('fms_status_history',[
            'obj_type' => $obj_type,
            'obj_id' => $obj_id
        ])->result_array();
    }
    
    function getStatusObjType($obj_type){
        return $this->db
            ->select('obj_name')
            ->get_where('fms_obj',[
                'type' => $obj_type
            ],1)->row_array()['obj_name'];
    }

    function getStatusUser($where,$obj_status_in,$limit){
        if ($obj_status_in!=[]) 
            $this->db->where_in('obj_status',$obj_status_in);
        return $this->db
            ->select(['admin_id','admin_name','role_id','role_name'])
            ->where($where)
            ->order_by('status_edit_time','desc')
            ->limit($limit)
            ->get('fms_status_history')
            ->result_array();
        // return $this->db->last_query();
    }

}
?>