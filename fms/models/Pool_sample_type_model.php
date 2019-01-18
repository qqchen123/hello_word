<?php

class Pool_sample_type_model extends Admin_Model{
    public function __construct(){
        parent::__construct();
    }

    //获取真假类型 by 奚晓俊
    function getShowTypes($level){
        if ($level) $this->db->where(['level'=>$level]);
        return $this->db->order_by('level')->get('fms_pool_sample_type')->result_array();
    }

    //获取指定假类型 by 奚晓俊
    function getShowType($show_type){
        return $this->db->get_where('fms_pool_sample_type',['show_type'=>$show_type])->row_array();
    }

    //新增假类型 by 奚晓俊
    function add_show_type($data){
        return $this->db->insert('fms_pool_sample_type',$data);
    }

    //编辑假类型 by 奚晓俊
    function edit_show_type($data){
        return $this->db->update('fms_pool_sample_type',$data,['show_type'=>$data['show_type']]);
    }

    //删除假类型 by 奚晓俊
    function delShowType($show_type){
        return $this->db->delete('fms_pool_sample_type',['show_type'=>$show_type]);
    }

}