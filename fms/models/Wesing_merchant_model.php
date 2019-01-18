<?php
class Wesing_merchant_model extends CI_Model{
    function check_userid($userid,$id){
        return $this->db
            ->select('count(id) num')
            ->where(['userid'=>$userid,'id !='=>$id])
            ->get('wesing_merchant')
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

    public $show_field = [
        'wesing_merchant.id',
        'wesing_merchant.userid',
        'wesing_merchant.username',
        'wesing_merchant.dzt',
        'wesing_merchant.userrole',
        'wesing_merchant.idno',
        'wesing_merchant.mobile',
        'wesing_merchant.email',
        'wesing_merchant.rzdate',
        'wesing_merchant.departmentid',
        'wesing_merchant.cdate',
        'wesing_merchant.udate',
        'wesing_merchant.openid',
    ];

	function add($data,$less_role_id){
        $this->db->trans_start();//开启事务
            //插入新对象
            $this->db->insert('wesing_merchant',$data);
            $id = $this->db->insert_id();
            foreach ($less_role_id as $key => $val) {
                $this->db->insert('wesing_merchant_role',[
                    'wmid' => $id,
                    'roleid' => $val,
                ]);
            }
        $this->db->trans_complete();

        if ($this->db->trans_status()){
            return $id;
        }else{
            return false;
        }
    }

    function edit($data,$less_role_id,$where){
        $this->db->trans_start();//开启事务
            $this->db->update('wesing_merchant',$data,$where);
            $this->db->delete('wesing_merchant_role',['wmid' => $where['id']]);
            foreach ($less_role_id as $key => $val) {
                $this->db->insert('wesing_merchant_role',[
                    'wmid' => $where['id'],
                    'roleid' => $val,
                ]);
            }
        $this->db->trans_complete();

        if ($this->db->trans_status()){
            return true;
        }else{
            return false;
        }
    }

    function list_wm($like='',$page=1,$rows=10,$sort='id',$order='ASC'){
        if ($like){
            $this->db->or_like('department_name',$like);
            $this->db->or_like('main_role.role_name',$like);
            $this->db->or_like('less_role.role_name',$like);

            $this->db->or_like('username',$like);
            $this->db->or_like('userid',$like);

            $this->db->or_like('idno',$like);
            $this->db->or_like('mobile',$like);
            $this->db->or_like('email',$like);
        }
        $total = $this->db
            ->select(array_merge($this->show_field,[
                'main_role.role_name main_role_name',
                'group_concat(less_role.role_name) less_role_name',
                'fms_department.department_name'
            ]))
            ->join('fms_role main_role','main_role.role_id = userrole','left')
            ->join('wesing_merchant_role','wmid = id','left')
            ->join('fms_role less_role','less_role.role_id=roleid','left')
            ->join('fms_department','department_id=departmentid','left')
            ->group_by('id')
            ->count_all_results('wesing_merchant',false);
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

    function get_one($id){
        return $this->db
            ->select(array_merge($this->show_field,[
                'group_concat(less_role.roleid) less_role_id',
            ]))
            ->join('wesing_merchant_role less_role','wmid = id','left')
            ->group_by('id')
            ->get_where('wesing_merchant',['id'=>$id])
            ->row_array();
    }

    function get_one_by_name($name,$dzt=null){
        if($dzt)
            $this->db->where(['dzt'=>$dzt]);
        return $this->db
            ->select([
                'wesing_merchant.*',
                'main_role.role_name role_name',
                'group_concat(less_role.role_id) less_role_id',
                'group_concat(less_role.role_name) less_role_name',
                'fms_department.department_name',
            ])
            ->join('fms_role main_role','main_role.role_id = userrole','left')
            ->join('wesing_merchant_role','wmid = id','left')
            ->join('fms_role less_role','less_role.role_id=roleid','left')
            ->join('fms_department','department_id=departmentid','left')
            ->group_by('id')
            ->get_where('wesing_merchant',['userid'=>$name])
            ->row_array();
    }

    // function if_son($id){
    //     return $this->db->select('count(department_id) num')->where(['parent_department_id'=>$department_id])->get('fms_department')->row_array()['num'];
    // }

    function del($id){
        $this->db->delete('wesing_merchant_role',['wmid'=>$id]);
        return $this->db->delete('wesing_merchant',['id'=>$id]);
    }

    function get_by_openid($openid){
        // $this->db->select($this->show_field)->get_where('wesing_merchant',['openid'=>$openid])->row_array();
        return $this->db
            ->select([
                'wesing_merchant.*',
                'main_role.role_name role_name',
                'group_concat(less_role.role_id) less_role_id',
                'group_concat(less_role.role_name) less_role_name',
                'fms_department.department_name',
            ])
            ->join('fms_role main_role','main_role.role_id = userrole','left')
            ->join('wesing_merchant_role','wmid = id','left')
            ->join('fms_role less_role','less_role.role_id=roleid','left')
            ->join('fms_department','department_id=departmentid','left')
            ->group_by('id')
            ->get_where('wesing_merchant',['openid'=>$openid,'dzt'=>'01'])
            ->row_array();
    }

    function update_openid($openid,$id){
        return $this->db->update('wesing_merchant',['openid'=>$openid],['id'=>$id]);
    }
}
?>