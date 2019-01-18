<?php

class PublicMethod_model extends CI_Model{
    function getAdmin(){
        return $this->db
            ->select([
                'wesing_merchant.id',
                'wesing_merchant.userid',
                'wesing_merchant.username',
                'fms_role.*',
                'fms_department.department_name department'
            ])
            ->join('fms_role','role_id=userrole')
            ->join('fms_department','department=department_id','left')
            ->order_by('fms_role.department','asc')
            ->get('wesing_merchant')
            ->result_array();
    }

}
?>