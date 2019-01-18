<?
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 工作日志管理
 * @filename WorkLog.php
 * @author xixiaojun
 * @version 1.0.0
 * @date 2018-05-30
 */

class WorkLog extends Admin_Controller{

	public function __construct(){
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
    }
//验证  开始============================
    /**
     * 验证日期
     */
    function check_datetime($date){
        if (date('Y-m-d H:i:s',strtotime($date))==$date) {
            return true;
        }else{
            $this->form_validation->set_message('check_datetime', '“{field}”不符合日期格式！');
            return false;
        }
    }

//验证  结束============================

//叮我的工作 开始========================
    /**
     * 输出叮我的工作日志列表
     */
    public function list_remind_me(){
        $this->load->helper('checkrolepower');
        $this->showpage('fms/list_remind_me');
    }

    /**
     * 获取我的工作日志
     */
    public function get_me_work_log(){
        $for_from = $this->input->get('for_from',true);
        $complete_status = $this->input->get('complete_status',true);
        $rows = $this->input->get('rows',true);
        $page = $this->input->get('page',true);
        $sort = $this->input->get('sort',true);
        $order = $this->input->get('order',true);

        $this->load->model("WorkLog_model",'wl');
        $res = $this->wl->list_me_wl($for_from,$complete_status,$page,$rows,$sort,$order);
        echo json_encode($res);
    }

    /**
     * 手动添加我的工作日志
     */
    public function add_me_work_log(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('wl_content', '工作内容', 'required|max_length[250]');
        $this->form_validation->set_rules('plan_date', '计划完成时间', 'callback_check_datetime');
        if (!$this->form_validation->run()) {
           $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors(); 
        }

        $wl_content = $this->input->post('wl_content',TRUE);
        $plan_date = $this->input->post('plan_date',TRUE);

        $this->load->helper('worklog');
        $ret['ret'] = addWorkLog($wl_content,$plan_date);
        $ret['info'] = $ret['ret']?'工作日志添加成功':'工作日志添加失败';

        echo json_encode($ret);
    }

    /**
     * 手动完成我的工作日志
     */
    public function complete_me_work_log(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('wl_id', '', 'required|integer');
        $this->form_validation->set_rules('note', '完成备注', 'required|max_length[250]');
        if (!$this->form_validation->run()) {
           $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors(); 
        }

        $wl_id = $this->input->post('wl_id',TRUE);
        $note = $this->input->post('note',TRUE);
        $this->load->helper('worklog');
        $ret['ret'] = completeMeWorkLog($note,$wl_id);
        $ret['info'] = $ret['ret']?'完成工作成功':'完成工作失败';

        echo json_encode($ret);
    }
//叮我的工作 结束========================

//叮我的工作进展 开始=====================
    /**
     * 输出叮我的工作日志进展附表
     */
    public function list_remind_me_march(){
        $data['parent_id'] = $this->input->get('parent_id',true);
        $data['complete_status'] = $this->input->get('complete_status',true);
        $data['for_from'] = $this->input->get('for_from',true);
        $this->load->helper('checkrolepower');
        $this->showpage('fms/list_remind_me_march',$data);
    }

    /**
     * 获取我的工作日志进展数据
     */
    public function get_me_work_log_march(){
        $parent_id = $this->input->get('parent_id',true);

        $this->load->model("WorkLog_model",'wl');
        //验证是否叮我的或我叮的工作日志
        $admin_id = $this->wl->get_wl_by_id($parent_id,['for_uid','from_uid']);
        if($admin_id['for_uid']!=$_SESSION['fms_id'] && $admin_id['from_uid']!=$_SESSION['fms_id']){
            $res = [];
        }else{
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            $order = $this->input->get('order',true);

            $res = $this->wl->list_me_wl_march($parent_id,$page,$rows,$sort,$order);
        }
        echo json_encode($res);
    }

    /**
     * 添加我的工作日志进展
     */
    public function add_me_work_log_march(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('parent_id', '', 'required|integer');
        $this->form_validation->set_rules('content', '进展内容', 'required|max_length[250]');
        $this->form_validation->set_rules('complete_rate', '预估完成率', 'required|integer|less_than_equal_to[100]|greater_than_equal_to[0]');
        if (!$this->form_validation->run()) {
           $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors(); 
        }

        //验证是否叮我的工作日志
        $this->load->model("WorkLog_model",'wl');
        $data['parent_id'] = $this->input->post('parent_id',TRUE);
        $admin_id = $this->wl->get_wl_by_id($data['parent_id'],['for_uid']);
        if($admin_id['for_uid']!=$_SESSION['fms_id']){
            $ret['ret'] = 0;

        }else{
            $data['content'] = $this->input->post('content',TRUE);
            $data['complete_rate'] = $this->input->post('complete_rate',TRUE);
            $data['c_date'] = date('Y-m-d H:i:s');

            $ret['ret'] = $this->wl->addWorkLogMarch($data);
        }
        $ret['info'] = $ret['ret']?'工作进展添加成功':'工作进展添加失败';
        echo json_encode($ret);
    }
//叮我的工作进展 结束=====================

//我叮的工作 开始========================
    /**
     * 输出我叮的工作日志列表
     */
    public function list_me_remind(){
        $this->load->helper('checkrolepower');
        $this->showpage('fms/list_me_remind');
    }

    /**
     * 新建手动提醒
     */
    public function add_work_log(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('for_admins', '提醒对象', 'required');
        $this->form_validation->set_rules('wl_content', '工作内容', 'required|max_length[250]');
        $this->form_validation->set_rules('plan_date', '计划完成时间', 'callback_check_datetime');
        if (!$this->form_validation->run()) {
           $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors(); 
        }

        $for_admins = $this->input->post('for_admins',TRUE);
        $wl_content = $this->input->post('wl_content',TRUE);
        $plan_date = $this->input->post('plan_date',TRUE);

        $this->load->helper('worklog');
        $ret['ret'] = addWorkLog($wl_content,$plan_date,$for_admins);
        $ret['info'] = $ret['ret']?'提醒成功':'提醒失败';

        echo json_encode($ret);
    }

    /**
     * 再次提醒
     */
    public function again_work_log(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('wl_id', '', 'required|integer');
        $this->form_validation->set_rules('for_admins', '提醒对象', 'required');
        $this->form_validation->set_rules('wl_content', '工作内容', 'required|max_length[250]');
        $this->form_validation->set_rules('plan_date', '计划完成时间', 'callback_check_datetime');
        if (!$this->form_validation->run()) {
           $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors(); 
        }

        $wl_id = $this->input->post('wl_id',TRUE);
        $for_admins = $this->input->post('for_admins',TRUE);
        $wl_content = $this->input->post('wl_content',TRUE);
        $plan_date = $this->input->post('plan_date',TRUE);

        $this->load->helper('worklog');
        $ret['ret'] = againWorkLog($wl_id,$wl_content,$plan_date,$for_admins);
        $ret['info'] = $ret['ret']?'再次提醒成功':'再次提醒失败';

        echo json_encode($ret);
    }

//我叮的工作 结束========================

}
?>