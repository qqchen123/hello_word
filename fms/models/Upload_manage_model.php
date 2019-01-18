<?php
class Upload_manage_model extends CI_Model{

    //添加小程序上传文件
	function add_mini_upload_tmp($file_name,$for_type=''){
        $data = [
            'name' => $file_name,
            'from_type' => @$_SESSION['fms_id']?'员工':'游客',
            'from_id' => @$_SESSION['fms_id'],//游客功能未做
            'status'=>'临时文件',
            'for_type'=>$for_type
        ];

        if ($this->db->insert('fms_upload_manage',$data)){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    //上传文件使用
    function use_upload($file_names=[],$for_id=null){
        if($file_names)
            $this->db
                ->where_in('name',$file_names)
                ->update('fms_upload_manage',['status'=>'已使用','for_id'=>$for_id]
            );
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }

    //上传文件删除
    function del_upload($file_names=[]){
        $this->db
            ->where_in('name',$file_names)
            ->update('fms_upload_manage',['status'=>'已删除']
        );
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }

    //按时间删除临时文件
    function get_upload($where){
        return $this->db->where($where)->get('fms_upload_manage')->result_array();
    }


    //按时间删除临时文件
    function del_upload_by_time($where){
        $this->db->where($where)->update('fms_upload_manage',['status'=>'已删除']);
        return $this->db->affected_rows();
    }
}
?>