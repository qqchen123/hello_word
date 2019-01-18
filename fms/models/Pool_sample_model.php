<?php

class Pool_sample_model extends Admin_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->sample_type = @$this->sample_type?$this->sample_type:'user';
    }

    // //分页显示所有数据
    // public function getmessage( $like, $sort, $order, $pageSize, $first)
    // {
    //     if ($sort && $order) {
    //         $this->db->order_by($sort, $order);
    //     }
    //     if ($like) {
    //         $this->db->like('pool_key',$like);
    //     }
    //     $res = $this->db->where('is_del','0')->limit( $pageSize, $first)->get('fms_pool_sample')->result_array();
    //     foreach ($res as $k => $v) {
    //         if ($v['is_del'] == 0) {
    //             $res[$k]['is_del'] = '未删除';
    //         }else{
    //             $res[$k]['is_del'] = '已删除';
    //         }
    //     }
    //     return $res;
    // }

    //获取enum选项 by 奚晓俊
    function getEnumOptions($field){
        $sql = "show columns from `fms_pool_sample` like '{$field}'";
        $enum = $this->db->query($sql)->row_array()['Type'];
        $enum_arr=explode("(",$enum);
        $enum=$enum_arr[1];
        $enum_arr=explode(")",$enum);
        $enum=$enum_arr[0];
        $enum=explode(",",$enum);
        foreach ($enum as $key => $val) {
            $val = explode("'", $val)[1];
            $res[] = [
                'key' => $val,
                'text' => $val,
            ];
        }
        return $res;
    }

    //获取树样本 by 奚晓俊
    function show_tree($select='*',$id=0,$getLevel=0,$formatChildren=true,$state='closed'/*,$detailPowers=[]*/){
        $type_sql = " and `sample_type`='".$this->sample_type."'";
        if ($id==0) {
            if ($getLevel=='all') {
                $sql = "SELECT {$select},`pool_key` `text` from `fms_pool_sample` where `is_del`=0 {$type_sql} order by `lft`";
            } else {
                $sql = "SELECT {$select},`pool_key` `text`  from `fms_pool_sample` where `is_del`=0 {$type_sql} and `level`<={$getLevel} order by `lft`";
            }
        }else{
            // 获取 $id节点
            $idvs = $this->db->query("select `lft`,`rgt`,`level` from `fms_pool_sample` where `is_del`=0 {$type_sql} and `id`={$id}")->row_array();
            if($idvs==[]) return [];
            $sql = "SELECT {$select},`pool_key` `text`  FROM `fms_pool_sample` WHERE `is_del`=0 {$type_sql} and `lft` BETWEEN {$idvs['lft']} and {$idvs['rgt']}"
            .($getLevel !== 0? " and `level` < {$idvs['level']} + {$getLevel} ":"")
            // .($detailPowers !== []?" and `id` in (".join(',',$detailPowers).")":"")
            ." ORDER BY `lft`";
        }
        $arr = $this->db->query($sql)->result_array();
        //echo $this->db->last_query();
        if($formatChildren){
            if($state=='closed') {
                foreach ($arr as $key => $val) {
                    if($val['rgt']-$val['lft']==1){
                        $arr[$key]['state'] = 'open';
                    }else{
                        $arr[$key]['state'] = 'closed';
                    }
                }
            }
            return $this->list_to_tree($arr);
        }else{
            return $arr;
        }
    }

    //获取指定id的多个样本 by 奚晓俊
    function get_samples_by_ids($ids=[]){
        return $this->db
            ->where_in('id',$ids)
            // ->where(['sample_type'=>$this->sample_type])
            ->get('fms_pool_sample')
            ->result_array();
    }

    //新插入一条树 by 奚晓俊 
    private function insert_tree($parent_id = 0,$data){
        $fields = '`sample_type`,`pool_key`,`create_date`,`edit_date`,`show_type`,`type`,`class`,`is_json`,`data-options`,`js`,`fore_end_check`,`back_end_check` ';
        $values = "'{$this->sample_type}','{$data['pool_key']}','{$data['create_date']}','{$data['edit_date']}','{$data['show_type']}','{$data['type']}','{$data['class']}','{$data['is_json']}','{$data['data-options']}','{$data['js']}','{$data['fore_end_check']}','{$data['back_end_check']}'";

        if ($parent_id==0){
            $sql="INSERT into `fms_pool_sample` (`lft`,`rgt`,`level`,`parent_id`,{$fields} ) select ifnull(max(`rgt`),0)+1,ifnull(max(`rgt`),0)+2,1,0,{$values} from `fms_pool_sample`";
            $this->db->query($sql);
            $id = $this->db->insert_id();
            //return $this->db->last_query();
        }else{
            $this->db->trans_begin();
            $idvs = $this->db->query("SELECT `id`,`level`,`rgt`,`lft` from `fms_pool_sample` where `id`={$parent_id} lock in share mode")->row_array();
            $this->db->query("UPDATE `fms_pool_sample` SET `rgt`=`rgt`+2 WHERE `rgt`>={$idvs['rgt']}");
            $this->db->query("UPDATE `fms_pool_sample` SET `lft`=`lft`+2 WHERE `lft`>{$idvs['rgt']}");
            $this->db->query("INSERT into `fms_pool_sample` (`parent_id`,`level`,`lft`,`rgt`,{$fields}) values ({$idvs['id']},{$idvs['level']}+1,{$idvs['rgt']},{$idvs['rgt']}+1,$values)");
            $id = $this->db->insert_id();
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
            }
        }

        return $id;
    }

    //未完成
    private function change_tree_sort($parent_id,$id){
        $parent = $this->db->get_where('fms_pool_sample',['id'=>$parent_id])->row_array();
        $idData = $this->db->get_where('fms_pool_sample',['id'=>$id])->row_array();

    }

    //往证据池样本表（fms_pool_sample）添加数据
    public function add_pool_sample_info($data){
        $id = $this->insert_tree($data['parent_id'],$data);
        if($id){
            /*添加池子样本时，同步添加参数权限*/
            $name = $data['pool_key'];
            $detail = $id;
            $this->load->helper('checkrolepower');
            poolSampleAddDetail($name, $detail, $this->methods);
        }
        return $id;
    }

    //往证据池样本表（fms_pool_sample）修改数据
    public function edit_pool_sample_info($where,$data){
        $this->db->where($where)->update('fms_pool_sample',$data);
        $ret = $this->db->affected_rows()?$where['id']:false;
        if($ret){
            /*编辑池子样本时，同步编辑参数权限名称*/
            $name = $data['pool_key'];
            $detail = $where['id'];
            $this->load->helper('checkrolepower');
            poolSampleEditDetail($name, $detail, $this->methods);
        }
        return $ret;
    }

    //获取总数
    // public function get_total()
    // {
    //     return $this->db->count_all('fms_pool_sample');
    // }

// 下拉框信息
    // public function getxl($user_id ,$resrpd_arr,$formatChildren=true)
    // {
    //     if ($user_id) {
    //         $poolres = $this->db->select('pool_sample_id')->where('user_id',$user_id)->get('fms_pool')->result_array();
    //         if ($poolres) {
    //             $this->db->where_not_in('fms_pool_sample.id',array_column($poolres, 'pool_sample_id'));
    //         }
    //     }
    //     if ($resrpd_arr) {
    //         $this->db->where_in('fms_pool_sample.id', $resrpd_arr);
    //     }

    //     $res = $this->db->select('*,pool_key as text')->order_by('id desc')->where('is_del',0)->get('fms_pool_sample')->result_array();
    //     if($formatChildren){
    //         $treeres = $this->list_to_tree($res);
    //         return $treeres;
    //     }else{
    //         return $res;
    //     }
    // }

//展示修改信息
    public function get_one_info($id)
    {
        $res = $this->db->where(['id'=>$id,'is_del'=>0])->get('fms_pool_sample')->row_array();
        return $res;
    }
//    删除一条数据
    public function del_ps($id){
        $data = array('is_del'=>1);
        $this->db->where('id',$id);
        $res = $this->db->update('fms_pool_sample',$data);
        $map = $this->db->select('pool_key')->where('id',$id)->get('fms_pool_sample')->row_array();
        $this->load->helper('checkrolepower');
        poolSampleDelDetail($map['pool_key'],$id);
        return $res;
    }

// 按引用无限级分类
    function list_to_tree($list, $pk='id', $pid = 'parent_id', $child = 'children', $root = 0) {
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
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    //检验样本key同层下唯一性 by 奚晓俊
    function get_key_num($pool_key){
        return $this->db->select('count(*) num')->where(['pool_key'=>$pool_key,'id !='=>$_POST['id'],'parent_id'=>$_POST['parent_id'],'is_del'=>0])->get('fms_pool_sample')->row_array()['num'];
    }

    //验证某节点样本下属子孙is_del=1数量>0 不得删除 by 奚晓俊
    function get_no_del_son_num($id){
        $idvs = $this->db->select(['lft','rgt'])->get_where('fms_pool_sample',['id'=>$id])->row_array();
        return $this->db->select('count(*) num')->where(
            ['lft >'=>$idvs['lft'],'lft <'=>$idvs['rgt'],'is_del'=>0])->get('fms_pool_sample')->row_array()['num'];
    }












}