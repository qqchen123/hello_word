<?php

class Pool_decision_tree_model extends Admin_Model
{
    public function __construct(){
        parent::__construct();
    }

    //返回策略表的树数据 by 奚晓俊
    function get_decision_tree($select='*',$tree_id='tree_id',$child_index='num'){
        $res = $this->db
            ->join('fms_pool_decision','id=decision_id','left')
            ->select($select)
            ->order_by('level desc ,lft')
            ->where('fms_pool_decision_tree.is_del',0)
            ->get('fms_pool_decision_tree')
            ->result_array();
        // return $res;
        $treeres = $this->list_to_tree($res,$pk=$tree_id, $pid = 'parent_id', $child = 'children', $root = 0,$child_index);

        // return $this->db->last_query();
        return $treeres;
    }

    // 按引用无限级分类
    private function list_to_tree($list, $pk='id', $pid = 'parent_id', $child = 'children', $root = 0,$child_index='num') {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        if($child_index=='num'){
                            $parent[$child][] =& $list[$key];
                        }else{
                            $parent[$child][$data['parent_return']] =& $list[$key];
                        }
                    }
                }
            }
        }
        return $tree;
    }

    //添加策略 by 奚晓俊
    function add_decision_tree($data){
        $data['tree_edit_admin_id'] = $_SESSION['fms_id'];
        $parent_id = $data['parent_id'];
        unset($data['parent_id']);
        return $this->insert_mptt_tree('fms_pool_decision_tree',$data,$parent_id);
    }

    /*
    * $data [字段=>值]
    */
    function insert_mptt_tree($table,$data,$parent_id=0,$id_field='tree_id'){
        $fields = '';
        $values = '';
        $num = 0;
        foreach ($data as $key => $val) {
            if($num>0) {
                $fields .= ',';
                $values .= ',';
            }else{
                $num++;
            }

            $fields .= '`'.$key.'`';
            $values .= '\''.$val.'\'';
        }
        if ($parent_id==0){
            $sql="INSERT into `{$table}` (`lft`,`rgt`,`level`,`parent_id`,{$fields} ) select ifnull(max(`rgt`),0)+1,ifnull(max(`rgt`),0)+2,1,0,{$values} from `{$table}`";
            $this->db->query($sql);
            $id = $this->db->insert_id();
        }else{
            $this->db->trans_start();
            $idvs = $this->db->query("SELECT `{$id_field}`,`level`,`rgt`,`lft` from `{$table}` where `{$id_field}`={$parent_id} lock in share mode")->row_array();
            $this->db->query("UPDATE `{$table}` SET `rgt`=`rgt`+2 WHERE `rgt`>={$idvs['rgt']}");
            $this->db->query("UPDATE `{$table}` SET `lft`=`lft`+2 WHERE `lft`>{$idvs['rgt']}");
            $this->db->query("INSERT into `{$table}` (`parent_id`,`level`,`lft`,`rgt`,{$fields}) values ({$idvs[$id_field]},{$idvs['level']}+1,{$idvs['rgt']},{$idvs['rgt']}+1,$values)");
            $id = $this->db->insert_id();
            $this->db->trans_complete();
        }
        return $id;
    }

    //编辑策略 by 奚晓俊
    function edit_decision_tree($data,$where){
        $data['tree_edit_date'] = date('Y-m-d H:i:s');
        $data['tree_edit_admin_id'] = $_SESSION['fms_id'];
        $this->db->update('fms_pool_decision_tree',$data,$where);
        return $this->db->affected_rows();
    }

    //根据父id和父结果 查找树
    function get_one_by_parentid_and_parentreturn($parent_id,$parent_return){
        return $this->db->get_where('fms_pool_decision_tree',[
            'is_del'=>0,
            'parent_id'=>$parent_id,
            'parent_return'=>$parent_return,
        ])->row_array();
    }

    //获取一组父子策略树(含策略详情) by 奚晓俊
    function get_one_group_by_treeid($tree_id){
        $res = $this->db
            ->join('fms_pool_decision','id=decision_id','left')
            // ->select($select)
            ->order_by('lft')
            ->where('tree_id='.$tree_id.' and fms_pool_decision_tree.is_del=0 or parent_id='.$tree_id.' and fms_pool_decision_tree.is_del=0')
            ->get('fms_pool_decision_tree')
            ->result_array();
            // echo $this->db->last_query();
        return $res;
    }

    function get_decision_tree_by_treeid($tree_id){
        return $this->db->get_where('fms_pool_decision_tree',['tree_id'=>$tree_id])->row_array();
    }

    function del_decision_tree($tree_id){
        $this->db->update('fms_pool_decision_tree',['is_del'=>1],['tree_id'=>$tree_id]);
        return $this->db->affected_rows();
    }

}