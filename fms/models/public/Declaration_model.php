<?php

/**
 * @desc 报单
 */
class Declaration_model extends Admin_model
{
    /**
     * @name 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('mongo_manager', ['table_name' => 'declaration']);
    }
    //----------------write by 陈恩杰---------------------

    /**
     * 插入数据
     * @param array $declar_arr 插入数据
     * @return int
     */
    public function insert_declara_info($declar_arr = [])
    {
        $insert_res = $this->mongo_manager->insert($declar_arr);
        if ($insert_res){
            return 1;
        }else{
            return 0;
        }
    }
    /**
     * 更新数据
     * @param array $declar_arr  更新字段
     * @param array $user_id  更新条件
     * @return int
     */
    public function update_declara_info($user_id = [],$declar_arr = [])
    {
        $insert_res = $this->mongo_manager->update($user_id,$declar_arr);
        if ($insert_res){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 查询
     * @param array $select 查询字段
     * @param array $user_id  查询条件
     * @return array
     * $de_res = $this->Declaration_model->select_declara_info(['needs_money','user_id'],['user_id'=>'371102']);
     */
    public function select_declara_info( $user_id = [], $select = [])
    {
        $select_res = $this->mongo_manager->select($select)->where($user_id)->where(['status'=>1])->find();
//        $select_res = $this->mongo_manager->select($select)->where($user_id)->find();
        if ($select_res){
            return $select_res;
        }else{
            return 0;
        }
    }

    /**
     * 获取所有数据
     * @param array $select 查询条件
     * @return int
     */
    public function get_info( $select = [],$page='',$rows='')
    {
        $num_infores = $this->mongo_manager->select($select)->where(['status'=>1])->find();
        $infores = $this->mongo_manager->select($select)
                                        ->where(['status'=>1])
                                        ->limit($rows)
                                        ->offset($page)
                                        ->order_by(['ctime','desc'])
                                        ->find();
        $res['total'] = count($num_infores);
        $res['rows'] = $infores;
        if ($infores){
            return $res;
        }else{
            return 0;
        }
    }

    /**
     * 获取一条数据
     * @param array $select
     * @return int
     */
    public function get_one_info( $where = [], $select= [])
    {
        $oneres = $this->mongo_manager->where( $where)->select( $select)->find_one();
        if ($oneres){
            return $oneres;
        }else{
            return 0;
        }
    }







}