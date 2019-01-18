<?php

class Pool_decision_model extends Admin_Model
{
    public function __construct(){
        parent::__construct();
    }

    // //返回策略表的树数据 by 奚晓俊
    // function get_decision_tree($select='*'){
    //     $res = $this->db->select($select)->order_by('level desc ,lft')->where('is_del',0)->get('fms_pool_decision')->result_array();

    //     $treeres = $this->list_to_tree($res,$pk='id', $pid = 'parent_id', $child = 'children', $root = 0);

    //     // return $this->db->last_query();
    //     return $treeres;
    // }

    // // 按引用无限级分类
    // private function list_to_tree($list, $pk='id', $pid = 'parent_id', $child = 'children', $root = 0) {
    //     // 创建Tree
    //     $tree = array();
    //     if(is_array($list)) {
    //         // 创建基于主键的数组引用
    //         $refer = array();
    //         foreach ($list as $key => $data) {
    //             $refer[$data[$pk]] =& $list[$key];
    //         }
            
    //         foreach ($list as $key => $data) {
    //             // 判断是否存在parent
    //             $parentId =  $data[$pid];
    //             if ($root == $parentId) {
    //                 $tree[] =& $list[$key];
    //             }else{
    //                 if (isset($refer[$parentId])) {
    //                     $parent =& $refer[$parentId];
    //                     $parent[$child][] =& $list[$key];
    //                 }
    //             }
    //         }
    //     }
    //     return $tree;
    // }

    //策略列表数据 by 奚晓俊
    function list_decision($like='',$page=1,$rows=10,$sort='id',$order='ASC',$id=null){

        // $this->load->helper('publicstatus');
        // joinStatus('jg','jg_id');

        if ($id) $this->db->where('id',$id);
        if ($like) $this->db->like('name',$like);
        $total = $this->db->count_all_results('fms_pool_decision',false);
        $rs = $this->db->where('is_del=0')->order_by($sort,$order)->limit($rows,($page-1)*$rows)->get();
        // return $this->db->last_query();
        if ($rs->num_rows() > 0) {
            $res["total"]= $total;
            $res['rows'] = $rs->result_array();
            $rs->free_result();
            // $res['rows'] = showStatusColor($res['rows']);
        } else {
            $res["total"]= 0;
            $res["rows"] = '';
        }
        return $res;
    }

    //获取全部策略名称供下拉框 by 奚晓俊
    function select_decision(){
        return $this->db->where('is_del',0)->select('id,name text')->get('fms_pool_decision')->result_array();
    }

    //添加策略 by 奚晓俊
    function add_decision($data){
        $this->db->insert('fms_pool_decision',$data);
        return $this->db->insert_id();
    }

    //编辑策略 by 奚晓俊
    function edit_decision($data,$where){
        $this->db->update('fms_pool_decision',$data,$where);
        return $this->db->affected_rows();
    }

    function get_decision_by_id($id){
        return $this->db->get_where('fms_pool_decision',['id'=>$id])->row_array();
    }

    function del_decision($id){
        $this->db->update('fms_pool_decision',['is_del'=>1],['id'=>$id]);
        return $this->db->affected_rows();
    }

}