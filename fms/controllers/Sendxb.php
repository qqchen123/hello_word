<?php

class Sendxb extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Xibao_model');
        $this->load->service('public/Sms_service','sms_service');//加载 样本model类
    }
//发送短信
    public function send_xb_message()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch', '部门', 'required');
        $this->form_validation->set_rules('user_id', '业务员id', 'required');
        $this->form_validation->set_rules('business', '业务', 'required');
        $this->form_validation->set_rules('money', '金额', 'required');

        if ($this->form_validation->run()){
            $xbres['branch']   = $this->input->post('branch',true);
            $xbres['user_id'] = $this->input->post('user_id',true);
            $xbres['username'] = $this->get_username($xbres['user_id']);
            $xbres['business'] = $this->input->post('business',true);
            $xbres['money']    = $this->input->post('money',true).'万';
            $arr_phone = $this->Xibao_model->get_phone();
            if ($arr_phone){
                $count = 0;
                foreach ($arr_phone as $v){
                    sleep(1);//睡眠1秒钟；
                    $res_sms = $this->sms_service->send_xb($v['phone'],$xbres);//调用阿里云短信接口，发送短信
//                    $res_sms = '喜报发送异常';
                    if ($res_sms!=='喜报发送异常'){
                        $status = 1;
                        $str = '【九盾科技】恭喜销售'.$xbres['branch'].'部'.$xbres['username'].'成功'.$xbres['business'].'一笔订单'.$xbres['money'].'。我之所以成功，是因为我把勤奋放在昨天，努力放在今天！加油小伙伴，相信自己，目标就在面前，成功就在眼前！';
                        $count = $count +1;
                    }else{
                        $status = 0;
                        $str = $res_sms;
                    }
                    $arr_sms_log = array(
                        'phone'=>$v['phone'],
                        'message'=>$str,
                        's_time'=>date('Y-m-d H:i:s'),
                        'status'=>$status,
                        'xb_user_id'=>$xbres['user_id']
                    );
                    $this->Xibao_model->add_xb_sms_log($arr_sms_log);//添加日志
                }
                $task['phones'] = json_encode(array_column($arr_phone,'phone'));
                $task['task_name'] = '喜报'.$xbres['username'];
                $task['message'] = $str;
                $task['s_time'] = date('Y-m-d H:i:s');
                $task['status'] = $count;
                $this->Xibao_model->add_xb_task($task);//添加任务
                $this->Xibao_model->update_count($xbres['user_id']);//更新业务员喜报数量-- count字段
                $ret['code'] = 1;
                $ret['message'] = '喜报发送成功！';
            }else{
                $ret['code'] = 0;
                $ret['message'] = '没有获取到用户手机号！';
            }
        }else{
            $ret['ret'] = 0;
            $this->form_validation->set_error_delimiters('', '');
            $ret['message'] = validation_errors();
        }
        echo json_encode($ret);die;
    }
//输出页面
    public function show_xb()
    {
        $this->showpage('fms/index_xb');
    }
//获取手机并返回到页面
    public function phone()
    {
        $page = $this->input->get('page');
        $rows = $this->input->get('rows');
        $first = $rows * ($page - 1);
        $phone_res = $this->Xibao_model->get_all_phone($rows, $first);
        echo json_encode($phone_res);die;
    }
//添加手机号
    public function add_phone()
    {
        $phone = $this->input->post();
        if (empty(trim($phone['username']))){
            $ret['code'] = 0;
            $ret['message'] = '用户名不能为空！';
            echo json_encode($ret);die;
        }
        $checkphone = $this->Xibao_model->check_phone($phone['phone']);
        if ($checkphone){
            $ret['code'] = 0;
            $ret['message'] = '此手机号已添加！';
        }else{
            $phone['create_time'] = date('Y-m-d H:i:s');
            $phone['status'] = 1;
            $res = $this->Xibao_model->add_phone($phone);
            if ($res){
                $ret['code'] = 1;
                $ret['message'] = '手机添加成功！';
            }else{
                $ret['code'] = 0;
                $ret['message'] = '手机添加失败！';
            }
        }
        echo json_encode($ret);
    }
//删除
    public function delete_sms()
    {

        $idres = $this->input->get('id',true);
        if(empty($idres)){
            $ret['code'] = 0;
            $ret['message'] = 'id不能为空！';
            echo json_encode($ret);die;
        }
        $deleteres = $this->Xibao_model->delete_sms_info($idres);
        echo $deleteres;die;
    }
//启用--禁用
    public function toggle_sms()
    {
        $id = $this->input->post('id',true);
        $status = $this->input->post('status',true);
        $togres = $this->Xibao_model->toggle_sms_info($id,$status);
        echo $togres;
    }
    //编辑--获取一条数据
    public function get_one_info()
    {
        $id = $this->input->post('id',true);
        $one_res = $this->Xibao_model->get_one_phone($id);
        echo json_encode($one_res);die;
    }
    //编辑
    public function edit_phone_info()
    {
        $postinfo = $this->input->post();
        $edit_res = $this->Xibao_model->edit_phone_info($postinfo);
        echo $edit_res;die;
    }
    //获取用户名
    public function get_username($user_id= '')
    {
        if (!empty($user_id)){
            $userinfo = $this->Xibao_model->get_username_info($user_id);
            return $userinfo['username'];
        }else{
            $userinfo = $this->Xibao_model->get_username_info();
        }
        echo json_encode($userinfo);die;
    }
    //输出喜报日志html页面
    public function show_xb_log()
    {
        $this->showpage('fms/xb_log');
    }
    //获取喜报日志
    public function get_xb_log()
    {
        $page = $this->input->post('page');
        $rows = $this->input->post('rows');
        $first = $rows * ($page - 1);
        $xb_log_res = $this->Xibao_model->get_all_xb_log($rows, $first);
        echo json_encode($xb_log_res);
    }
    public function phpinfo()
    {
        echo phpinfo();
    }



}