<?php

class Pool_model extends Admin_Model
{
    public function __construct(){
        parent::__construct();
        //加载池子配置项
        $this->config->load('pool');
        $this->_get_config();
    }

    private function _get_config(){
        $this->pool = $this->config->item('pool');
        $this->pool['sample_type'] = @$this->sample_type?$this->sample_type:'user';

        //验证池子类型是否存在
        if(!in_array($this->pool['sample_type'],array_column($this->pool['sample_types'],'key')))
            exit(json_encode(['ret'=>false,'info'=>'m请选择资料项归属类型.'],256));

        //获取对应类型表名、对象id字段、主方法、状态类型
        foreach ($this->pool['sample_types'] as $key => $val) {
            if($val['key'] == $this->pool['sample_type']){
                foreach ($val as $k => $v) {
                    $this->$k = $v;
                }
                break; 
            }
        }
    }

    //返回证据池表的数据 废弃代码
    // public function get_pool_info( $pageSize, $first, $like='',$user_id='', $where)
    // {
    //     $this->load->helper('publicstatus');
    //     joinStatus('pool','pool_id');

    //     if ($like) {
    //         $this->db->like('pool_val',$like);
    //     }

    //     if ($where) {
    //         $this->db->where_in('fms_pool.pool_sample_id', $where);
    //     }
    //     $this->db->select('fms_pool.*,fms_pool_sample.id,fms_pool_sample.pool_key,pool_status.*,fms_user.id as uid,fms_user.fuserid,fms_user.name');
    //     if ($user_id) {
    //         $this->db->where('fms_user.id',$user_id);
    //     }
    //     $this->db->order_by('fms_pool.pool_id desc');
    //     $this->db->join('fms_user','fms_user.id=fms_pool.user_id');
    //     $this->db->join('fms_pool_sample','fms_pool_sample.id = fms_pool.pool_sample_id');
    //     $res = $this->db->limit( $pageSize, $first)->get('fms_pool')->result_array();
    //     return $res;
    //     // print_r($this->db->last_query());die;
    // }
    //返回证据池表的树数据 write_by 陈恩杰
    // public function get_pool_tree2($user_id, $where){
    //     $this->load->helper('publicstatus');
    //     joinStatus('pool','pool_id');

    //     if ($where) {
    //         $this->db->where_in('fms_pool.pool_sample_id', $where);
    //     }
    //     $this->db->select('fms_pool.*,fms_pool_sample.pool_key,fms_pool_sample.id as id,fms_pool_sample.parent_id,fms_pool_sample.lft,fms_pool_sample.rgt,pool_status.*,fms_user.id as uid,fms_user.fuserid,fms_user.name');
    //     if ($user_id) {
    //         $this->db->where(['fms_user.id'=>$user_id,])->or_where('fms_user.id is null');
    //     }
    //     //$this->db->order_by('fms_pool.pool_id desc');
    //     $this->db->join('fms_user', 'fms_user.id = fms_pool.user_id');
    //     $this->db->join('fms_pool_sample', 'fms_pool_sample.id = fms_pool.pool_sample_id','right');
    //     $this->db->order_by('level desc ,lft');
    //     $res = $this->db->get('fms_pool')->result_array();
    //     $treeres = $this->list_to_tree($res,$pk='id', $pid = 'parent_id', $child = 'children', $root = 0);
        
    //     return $treeres;

    // }

    //返回证据池表的树数据 write_by 陈恩杰 ？？？估计有问题 用户交叉数据时没有is null(修复 by xxj)
    public function get_pool_tree($obj_id, $sample_ids=[],$formatChildren=true,$select=[]){
        $this->load->helper('publicstatus');
        joinStatus($this->status_type,'pool_id');

        if ($sample_ids) {
            $this->db->where_in('id', $sample_ids);
        }

        if(empty($select))
            $select = $this->table.'.*,pool_key,id,parent_id,lft,rgt,'.$this->status_type.'_status.*';
        $this->db->select($select);

        $this->db->join('fms_pool_sample', "
            fms_pool_sample.id = {$this->table}.pool_sample_id and `{$this->id_field}`={$obj_id} and `sample_type`='{$this->pool['sample_type']}'
        ",'right');
        $this->db->where(['sample_type'=>$this->pool['sample_type']]);
        $this->db->order_by('level desc ,lft');
        $o = $this->db->get($this->table);
        $res = $o->result_array();
        $o->free_result();
        // echo $this->db->last_query();
        if($formatChildren){
            $treeres = $this->list_to_tree($res,$pk='id', $pid = 'parent_id', $child = 'children', $root = 0);

            // return $this->db->last_query();
            return $treeres;
        }else{
            return $res;
        }
    }

    //返回证据池表、样本表的某客户某节点的所有子孙数据 by 奚晓俊
    // public function get_pools_by_poolid($pool_id,$detailPowers=[]){
    //     $pool = $this->db
    //         ->select('user_id,lft,rgt,level')
    //         ->join('fms_pool_sample','id=pool_sample_id')
    //         ->get_where('fms_pool',['pool_id'=>$pool_id])
    //         ->row_array();

    //     $this->load->helper('publicstatus');
    //     joinStatus('pool','pool_id');

    //     if ($detailPowers) 
    //         $this->db->where_in('fms_pool.pool_sample_id',$detailPowers);
        
    //     $res = $this->db
    //         ->select('fms_pool.*,fms_pool_sample.pool_key,fms_pool_sample.id as id,fms_pool_sample.parent_id,fms_pool_sample.lft,fms_pool_sample.rgt,pool_status.*')
    //         // ->select('fms_pool.*,pool_status.obj_status')
    //         ->join('fms_pool_sample', "
    //             id = pool_sample_id and 'user_id'={$pool['user_id']}
    //         ",'right')
    //         ->where(['lft >='=>$pool['lft'],'lft <'=>$pool['rgt'],'is_del'=>0])
    //         ->order_by('lft')
    //         ->get('fms_pool')
    //         ->result_array();
    //     // return $this->db->last_query();
    //     return $res;
    // }

    //根据pool_id获取一条pool by 奚晓俊
    function get_pool_by_poolid($pool_id){
        return $this->db
            ->select($this->id_field.',lft,rgt,level')
            ->join('fms_pool_sample','id=pool_sample_id')
            ->get_where($this->table,['pool_id'=>$pool_id])
            ->row_array();
    }

    // //根据sample_id、user_id获取一条pool by 奚晓俊
    // function get_pool_by_sampleid_userid($pool_sample_id,$user_id){
    //     return $this->db
    //         ->select('user_id,lft,rgt,level')
    //         // ->join('fms_pool_sample',"id=pool_sample_id and user_id = {$user_id}",'right')
    //         ->get_where($this->table,['id'=>$pool_sample_id])
    //         ->row_array();
    // }

    // 获取指定一个pool数据的所有子孙数据
    function get_pools_by_pool($pool=[],$detailPowers=[]){
        $this->load->helper('publicstatus');
        joinStatus($this->status_type,'pool_id');

        if ($detailPowers) 
            $this->db->where_in('id',$detailPowers);
        
        $res = $this->db
            // ->select('fms_pool.*,fms_pool_sample.pool_key,fms_pool_sample.id as id,fms_pool_sample.parent_id,fms_pool_sample.lft,fms_pool_sample.rgt,pool_status.*')
            // ->select('fms_pool.*,pool_status.obj_status')
            ->join('fms_pool_sample', "
                (`id` = `pool_sample_id` and `{$this->id_field}`={$pool[$this->id_field]})
            ",'right')
            ->where(['lft >='=>$pool['lft'],'lft <'=>$pool['rgt'],'is_del'=>0])
            ->order_by('lft')
            ->get($this->table)
            ->result_array();
        // echo  $this->db->last_query();
        return $res;
    }

    // 按引用无限级分类并规避掉完全无数据支线 修改by 奚晓俊
    function list_to_tree($list, $pk='id', $pid = 'parent_id', $child = 'children', $root = 0) {
        // 创建Tree
        $tree = $arr = [];
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }

            $num = 0;
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId ) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId]) && (isset($data[$child]) || $data['pool_id']!==null)) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
                if($data['pool_id']==null) $list[$key]['pool_id'] = --$num;
            }

            foreach ($tree as $key => $val) {
                if($val['pool_id'] > 0 || isset($val[$child])) $arr[] =& $tree[$key];
            }
        }
        return $arr;
    }

    //往证据池表（fms_pool_??）添加数据(liangfengjia 也用)
    public function add_pool_info( $data )
    {
        $this->load->helper('publicstatus');
        $this->db->trans_start();//开启事务
            $res = $this->db->insert($this->table,$data);
            $id  = $this->db->insert_id();
            //插入公共状态
            addStatus($this->status_type,$id);
        $this->db->trans_complete();
        if ($this->db->trans_status()){
            return $id;
        }else{
            return false;
        }
    }
    // 编辑证据池数据
    public function edit_pool_info( $data )
    {
        $this->db->where('pool_id', $data['pool_id']);
        return $this->db->update($this->table,$data);
    }

    //获取总数 枪毙代码
    // public function get_total($like='',$user_id='')
    // {
    //     $this->load->helper('publicstatus');
    //     joinStatus('pool','pool_id');

    //     if (!empty($like)) {
    //         $this->db->like('pool_val',$like);
    //     }

    //     if ($user_id) {
    //         $this->db->where('fms_user.id',$user_id);
    //     }
    //     $this->db->order_by('fms_pool.pool_id desc');
    //     $this->db->join('fms_user', 'fms_user.id = fms_pool.user_id');
    //     $this->db->join('fms_pool_sample', 'fms_pool_sample.id = fms_pool.pool_sample_id');
    //     $res = $this->db->count_all_results('fms_pool');
    //     return $res;
    // }

    // 返回数据 to liangfengjia
    public function getpoolinfo_l( $obj_id, $pool_sample_id=[]){
        if (empty(trim($obj_id))) {
            $data['code']    = 0;
            $data['message'] = $this->id_field.'不能为空！';
            return $data;
        }
        if (empty($pool_sample_id)) {
            $data['code']    = 0;
            $data['message'] = '$pool_sample_id不能为空！';
            return $data;
        }
        $this->load->helper('publicstatus');
        joinStatus($this->status_type,'pool_id');
        $maps = array($this->id_field=>$obj_id);
        $this->db->where($maps);
        $this->db->where_in('pool_sample_id', $pool_sample_id);
        return $this->db->get($this->table)->result_array();
    }

    // 数据更新
    public function edit_poolinfo( $data ,$id)
    {

        return $this->db->update($this->table, $data, array('pool_id'=>$id));
    }
    //获取一条证据池数据
    public function get_one_pool_info($pool_id, $where)
    {
        $this->load->helper('publicstatus');
        joinStatus('pool','pool_id');
        if ($where) {
            $this->db->where_in($this->table.'.pool_sample_id', $where);
        }
        $this->db->select($this->table.'.*, fms_pool_sample.pool_key');
        $this->db->join('fms_pool_sample', 'fms_pool_sample.id = '.$this->table.'.pool_sample_id');
        $res = $this->db->where('pool_id',$pool_id)->get($this->table)->row_array();
        // $user = $this->db->where('id',$res['user_id'])->get('fms_user')->row_array();
        return $res;
    }

    public function get_sample_id_by_obj_id($pool_id)
    {
        return $this->db->select('pool_sample_id')->where('pool_id', $pool_id)->get($this->table)->row_array();
    }

    //池子ids获取样本ids by 奚晓俊
    function get_sample_ids_by_obj_ids($pool_ids=[]){
        return $this->db->select('pool_sample_id,pool_id')->where_in('pool_id',$pool_ids)->get($this->table)->result_array();
    }

    // public function getrepeatinfo($data)
    // {
    //     $res = $this->db->where('user_id',$data['user_id'])->where('pool_sample_id',$data['pool_sample_id'])->get($this->table)->result_array();
    //     return $res;
    // }

    //获取指定样本数据数量 by 奚晓俊
    function getSampleUseNum($pool_sample_id){
        return $this->db->select('count(*) num')->get_where($this->table,['pool_sample_id'=>$pool_sample_id])->row_array()['num'];
    }

    //write by 陈恩杰 --城市三级联动
    public function get_address($id='')
    {
        if (empty($id)){
            $res = $this->db->where('parent_id',1)->get('ps_region');
        }else{
            $res = $this->db->where('parent_id',$id)->get('ps_region');
        }
        return $res->result_array();
    }
    
    //write by 陈恩杰 --验证省市区是否符合层级关系 2018/7/24
    public function check_city_level_info($arr)
    {
        $province_res = $this->db->where('region_name',$arr[0])->get('ps_region')->row_array();
        $pro_chil_res = $this->db->where('parent_id',$province_res['region_id'])->get('ps_region')->result_array();
        $pro_chil_name = array_column($pro_chil_res,'region_name','region_id');
        $pro_res = array_search($arr[1],$pro_chil_name);
        if ($pro_res){
            $city_chil_res = $this->db->where('parent_id',$pro_res)->get('ps_region')->result_array();
            $city_chil_name = array_column($city_chil_res,'region_name','region_id');
            $city_res = array_search($arr[2],$city_chil_name);
            if (!$city_res){
                $ret['code'] = 0;
                $ret['info'] = '市与区不符合层级关系！';
                return $ret;
            }
            $ret['code'] = 1;
            $ret['info'] = '符合层级关系！';
            return $ret;
        }else{
            $ret['code'] = 0;
            $ret['info'] = '省与市不符合层级关系！';
            return $ret;
        }
    }

    //递归获取派生值公式 by 奚晓俊 
    function getRecursionDeriveValue($fun,$eval_length=100){
        //防死循环措施
        $this->recursionNum++;
        if($this->recursionNum>$eval_length) exit(json_encode(['ret'=>false,'info'=>'递归超数量，请检查代码防止死循环.'],256));

        //解析fun的{{id}}
        preg_match_all("/{{(\d*)}}/", $fun, $fun_arr);
        //获取客户样本值
        if($fun_arr[1]!=[]){
            //补充不存在的样本
            $diff_arr = array_diff($fun_arr[1],array_keys($this->samples_ref));
            if(!empty($diff_arr)){
                $arr = $this->pool_model->get_pool_tree($this->user_id,$diff_arr,false,['pool_id','id','pool_val','obj_status','obj_status_info','pool_key','class','data-options']);
                foreach ($arr as $key => $val) {
                    $samples_ref[$val['id']] =& $arr[$key];
                }
            }
            // var_dump($samples_ref);
            //循环公式样本
            foreach ($fun_arr[1] as $id){
                if($samples_ref[$id]['class']=='easyui-textbox derive'){
                    $tmp = json_decode($samples_ref[$id]['data-options'],true);
                    if(!isset($tmp['fun'])){
                        $res['ret'] = false;
                        @$res['info'][$id] = html_entity_decode($samples_ref[$id]['pool_key'].'{{'.$samples_ref[$id]['id'].'}}'.'未定义公式！');
                    }else{
                        $tmp = $this->getRecursionDeriveValue($tmp['fun']);
                        if(!$tmp['ret']){
                            $res['ret'] = false;
                            if(isset($res['info'])){
                                $res['info'] += $tmp['info'];
                            }else{
                                $res['info'] = $tmp['info'];
                            }
                        }else{
                            $copy_arr['{{'.$id.'}}'] = $tmp['info'];
                        }
                    }
                }else{
                    //样本是否赋值
                    if($samples_ref[$id]['pool_val']===null){
                        $res['ret'] = false;
                        @$res['info'][$id] = html_entity_decode($samples_ref[$id]['pool_key'].'{{'.$samples_ref[$id]['id'].'}}值为空！');

                    //样本值是否过审
                    }elseif(!checkOneOkStatus('','',$samples_ref[$id]['obj_status'])){
                        $res['ret'] = false;
                        @$res['info'][$id] = html_entity_decode($samples_ref[$id]['pool_key'].'{{'.$samples_ref[$id]['id'].'}}的状态为：'.$samples_ref[$id]['obj_status_info'].'！');
                    }else{
                        $copy_arr['{{'.$id.'}}'] = $samples_ref[$id]['pool_val'];
                    }
                }
            }
        }else{
            $copy_arr = [];
        }

        if(!isset($res['ret'])){
            $fun = html_entity_decode(strtr($fun,$copy_arr));
            if(!strpos($fun, 'return')) $fun = 'return '.$fun;
            if($fun{strlen($fun)-1}!==';') $fun .= ';';
            $res['info'] = @eval($fun);
            $res['ret'] = true;
            $res['fun'] = $fun;
        }
        // var_dump($fun);
        return $res;
    }
}