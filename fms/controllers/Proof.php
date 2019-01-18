<?php

class Proof extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pool_sample_model');//加载model类
        $this->load->model('pool_model');       //加载model类
        $this->load->library('form_validation');//表单验证类
    }

    public function index()
    {
        $this->load->helper('publicstatus');
        $data['statusColor'] = json_encode($this->statusColor);
        $this->showpage('fms/prove', $data);
    }

    //获取证据池样本表（fms_pool_sample）的数据
    public function provelist( $rows = '')
    {
        $page     = $_GET['page'];
        $pageSize = $_GET['rows'];
        $first    = $pageSize * ($page - 1);
        //排序
        $sort     = $_GET['sort'];
        $order    = $_GET['order'];
        //搜索
        $search       = isset($_GET['like']) ? trim( $_GET['like']) : '';
        $res['rows']  = $this->pool_sample_model->getmessage( $search, $sort, $order, $pageSize, $first);//调用model类的方法
        //获取总数
        $res['total'] = $this->pool_sample_model->get_total();
        echo json_encode($res);
    }
    /**
     *往证据池样本表（fms_pool_sample）添加数据
     */
    public function insert_ps()
    {
        // 设置验证规则
        $this->form_validation->set_rules('pool_key','证据池名称','required');
        if ($this->form_validation->run() == false) {
            // 未通过验证
            $res['code']    = 0;
            $res['message'] = "证据池名称不能为空！";
            $res['data']    = '';
            echo json_encode($res);
        }else{
            //通过验证
            $data['pool_key']    = $this->input->post('pool_key',true);
            $data['create_date'] = date('Y-m-d H:i:s');
            if ($this->pool_sample_model->add_pool_sample_info($data)) {
                $res['code']    = 1;
                $res['message'] = '数据添加成功！';
                $res['data']    = '';
                echo json_encode($res);
            }else{
                $res['code']    = 0;
                $res['message'] = '数据添加失败！';
                $res['data']    = '';
                echo json_encode($res);
            }
        }
    }
    /**
     *往证据池样本表（fms_pool_sample）添加数据
     */
    public function edit_ps()
    {
        // 设置验证规则
        $this->form_validation->set_rules('id','ID','required');
        $this->form_validation->set_rules('pool_key','证据池名称','required');
        if ($this->form_validation->run() == false) {
            // 未通过验证
            $res['code']    = 0;
            $res['message'] = htmlentities(validation_errors());
            $res['data']    = '';
            echo json_encode($res);
        }else{
            //通过验证
            $id = $this->input->post('id');
            $data['pool_key']    = $this->input->post('pool_key',true);
            $data['edit_date'] = date('Y-m-d H:i:s');
            if ($this->pool_sample_model->edit_pool_sample_info( $id, $data)) {
                $res['code']    = 1;
                $res['message'] = '数据更新成功！';
                $res['data']    = '';
                echo json_encode($res);
            }else{
                $res['code']    = 0;
                $res['message'] = '数据更新失败！';
                $res['data']    = '';
                echo json_encode($res);
            }
        }
    }
    //显示证据池数据
    public function index_pool()
    {
        $this->showpage('fms/prove_pool');
    }
    //返回证据池表的数据
    public function pool( $rows = '')
    {
        $page     = $_GET['page'];
        $pageSize = $_GET['rows'];
        $first    = $pageSize * ($page - 1);
        //排序
        // $sort     = $_GET['sort'];
        // $order    = $_GET['order'];
        //搜索
        $search = isset($_GET['like']) ? trim( $_GET['like']) : '';
        $res['rows']    = $this->pool_model->get_pool_info( $page, $pageSize, $first, $search);
        $res['total']   = $this->pool_model->get_total();
//        print_r($res);die;
        echo json_encode($res);
    }
    //往证据池里添加数据
    public function add_pool()
    {
        // 设置验证规则
        $this->form_validation->set_rules('user_id','客户id','required|integer');
        $this->form_validation->set_rules('pool_sample_id','数据池样本id（名称）','required|integer');
        if ($this->form_validation->run() == false) {
            // 未通过验证
            $res['code']    = 0;
            $res['message'] = htmlentities(validation_errors());
            echo json_encode($res);
        }else{
            //通过验证
            $data['user_id']        = $this->input->post('user_id',true);
            $data['pool_sample_id'] = $this->input->post('pool_sample_id',true);
            $data['create_date']    = date('Y-m-d H:i:s');
            if ($this->pool_model->add_pool_info($data)) {
                $res['code']    = 1;
                $res['message'] = '添加数据成功！';
                echo json_encode($res);
            }else{
                $res['code']    = 0;
                $res['message'] = '添加数据失败！';
                echo json_encode($res);
            }
        }
    }
    // 下拉框 fms_pool_sample
    public function get_xlinfo()
    {
        $res = $this->pool_sample_model->getxl();
        echo json_encode($res);
    }
    //下拉框 fms_user
    // public function get_userinfo()
    // {
    //     $res = $this->
    // }
    //修改界面的显示数据
    public function show_edit()
    {
        //通过验证
        $data['id']    = $this->input->get('id',true);
        if (empty($data['id'])){
            $map['code']    = 0;
            $map['message'] = 'id不能为空！';
            $map['data']    = '';
            echo json_encode($res);
        }
        $res = $this->pool_sample_model->get_one_info($data);
        if ($res) {
            $map['code']    = 1;
            $map['message'] = '数据查找成功！';
            $map['data']    = $res;
            echo json_encode($map);
        }else{
            $map['code']    = 0;
            $map['message'] = '数据查找失败！';
            $map['data']    = '';
            echo json_encode($res);
        }
    }
    //删除一条fms_pool_sample的记录
    public function del_pool_s(){
        $id = $this->input->get('id');
        if (empty($id)){
            $map['code']    = 0;
            $map['message'] = 'id不能为空！';
            $map['data']    = '';
            echo json_encode($res);
        }
        $res = $this->pool_sample_model->del_ps($id);
        if ($res){
            $map['code']    = 1;
            $map['message'] = '数据删除成功！';
            echo json_encode($map);
        }else{
            $map['code']    = 0;
            $map['message'] = '数据删除失败！';
            echo json_encode($map);
        }
    }







}