<?php
/**
 * Created by chenenjie.
 * Date: 2018/7/5
 * Time: 下午12:14
 */
/**
 * Class Changepass_model（此类已废弃，请看 /home.class）
 */
class Changepass extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('changepass_model','cp_model');
        $this->load->library('form_validation');//表单验证类
    }
    /**
     * 修改密码
     */
    public function change_password()
    {
        $this->form_validation->set_rules('oldpassword', '原始密码', 'required|max_length[30]');
        $this->form_validation->set_rules('newpassword', '新密码', 'required|max_length[30]');
        $this->form_validation->set_rules('newpassword2', '新密码', 'required|max_length[30]');
        if($this->form_validation->run()){
            $oldpassword = $this->input->post('oldpassword',true);
            $newpassword = $this->input->post('newpassword',true);
            $newpassword2 = $this->input->post('newpassword2',true);
            if ($newpassword!==$newpassword2){
                $message['code'] = 0;
                $message['message'] = '两次输入不一致，请重新输入！';
                echo json_encode($message);die;
            }
            $user_res = $this->cp_model->get_user_info($_SESSION['fms_id']);
            if ($user_res == false){
                echo json_encode($user_res);die;
            }
            if (md5($user_res['userid'].$user_res['salt'].$oldpassword)==$user_res['usermm']){
                $md5newpasswd = md5($user_res['userid'].$user_res['salt'].$newpassword);
                $change_res = $this->cp_model->change_passwd($user_res['id'],$md5newpasswd);
                if ($change_res == false){
                    $message['code'] = 0;
                    $message['message'] = '密码修改失败！';
                    echo json_encode($message);die;
                }else{
                    $message['code'] = 1;
                    $message['message'] = '密码修改成功！';
                    echo json_encode($message);die;
                }
            }else{
                $message['code'] = 0;
                $message['message'] = '原始密码不正确！';
                echo json_encode($message);die;
            }
        }else{
            $message['ret'] = 0;
            $this->form_validation->set_error_delimiters('', '<br>');
            $message['info'] = validation_errors();
            echo  json_encode($message);
        }
    }
}







