<?

class Qudao extends Admin_Controller
{
	public function __construct()
    {
        parent::__construct();
    }

// 渠道联系人重构 by 奚晓俊 开始=========================

    //废弃代码 作用不明
	// public function add()
	// {
	// 	if ($_SESSION['fms_userrole'] != 1)
	// 	{
	// 		echo "没有权限！";exit;
	// 	}
	// 	$formid=uniqid();
	// 	$_SESSION['formid'] = $formid;
	// 	$data = array('qid'=>$formid);
	// 	$this->showpage('fms/qudaoadd',$data);
	// }

    //废弃代码 作用不明
	// public function edit($q_id)
 //    {
 //        $this->load->model('qudao_model','qm');
 //        $qudaoInfo=$this->qm->getqudaobyid($q_id);
 //        $this->showpage('fms/qudaoedit',['qinfo'=>$qudaoInfo]);
 //    }

    //废弃代码 作用不明
    // public function del($id)
    // {
    //     if(!filter_var($id,FILTER_VALIDATE_INT)){
    //         $this->_response('ID错误',500);
    //     }
    //     $this->load->model('qudao_model','qm');
    //     $ret=$this->qm->del($id);
    //     $this->_response($ret[1],$ret[0]);
    // }

    //废弃代码 作用不明
    // public function del_dc($id)
    // {
    //     if(!filter_var($id,FILTER_VALIDATE_INT)){
    //         $this->_response('ID错误',500);
    //     }
    //     $this->load->model('qudao_model','qm');
    //     $ret=$this->qm->del_dc($id);
    //     $this->_response($ret[1],$ret[0]);
    // }

    //废弃代码 输出渠道对接人列表
    // public function getpickers($id)
    // {
    //     $this->load->model('qudao_model','qm');
    //     $qudaoInfo=$this->qm->getqudaobyid($id);
    //     $mdepart=$this->qm->getzamktype('qudao_depa');
    //     $mgroup =$this->qm->getzamktype('qudao_grou');
    //     $mrol =$this->qm->getzamktype('qudao_rol');
    //     $this->showpage('fms/qudaolistpickers',['qinfo'=>$qudaoInfo,'group'=>$mgroup,'mrol'=>$mrol,'mdepart'=>$mdepart]);
    // }

    //废弃代码 获取渠道对接人数据
    // public function getpickerslist()
    // {
    //     $page = $this->input->get('page',true);
    //     $rows = $this->input->get('rows',true);
    //     $dcname=$this->input->get('dcname',true);
    //     $this->load->model('qudao_model','qm');
    //     $reponseData=$this->qm->query_pickers($page,$rows,$dcname);
    //     foreach ($reponseData['rows'] as $k=>$v){
    //         $reponseData['rows'][$k]['op']=sprintf("<a href='#' onclick='edit_dc(%d)'>%s</a>&nbsp;<a href='#' onclick='del(%d)'>%s</a>",
    //             $v['dc_id'],
    //             '编辑',
    //             $v['dc_id'],
    //             '删除'
    //         );
    //     }
    //     echo json_encode($reponseData);
    // }



    /**
     * 输出渠道对接人列表
     */
    public function list_qudao_contact(){
        $this->load->helper(['checkrolepower','publicstatus']);
        $q_id = $this->input->get('q_id',true);
        if ($q_id) {
            $this->load->model("Qudao_model",'QD');
            $data = $this->QD->get_qudao_by_id($q_id);
            $this->showpage('fms/list_qudao_contact',$data);
        }else{
            $this->showpage('fms/list_qudao_contact');
        }
    }

    /**
     * 输出渠道名称列表供选择
     */
    public function get_qudao_name(){
        $obj_status = $this->input->get('obj_status',true);
        if ($obj_status) {
            $where = ['obj_status'=>$obj_status];
        }else{
            $where = [];
        }
        $this->load->model("Qudao_model",'QD');
        $res = $this->QD->get_qudao_name($where);

        echo json_encode($res);
    }

    /**
     * 获取渠道对接人数据
     */
    public function get_contact(){
        $ct_id = $this->input->get('ct_id',true);
        $this->load->model("Qudao_model",'QD');

        //获取指定渠道产品id
        if($ct_id) {
            $res = $this->QD->get_contact_by_id($ct_id);

        //获取渠道产品列表
        }else{
            $q_id = $this->input->get('q_id',true);
            $q_status = $this->input->get('q_status',true);
            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            if($sort=='q_status_info') $sort='q_status';
            $order = $this->input->get('order',true);
            $res = $this->QD->list_contact($q_status,$q_id,$like,$page,$rows,$sort,$order);
        }

        echo json_encode($res);
    }

    //废弃代码 添加渠道联系人
    // public function adddc($qid)
    // {
    //     $this->load->model('qudao_model','qm');
    //     $qudaoInfo=$this->qm->getqudaobyid($qid);
    //     $mdepart=$this->qm->getzamktype('qudao_depa');
    //     $mgroup =$this->qm->getzamktype('qudao_grou');
    //     $mrol =$this->qm->getzamktype('qudao_rol');
    //     $pick =$this->qm->getzamktype('qudao_pik');
    //     $this->showpage('fms/qudaolistpickersadd',['qinfo'=>$qudaoInfo,'group'=>$mgroup,'mrol'=>$mrol,'mdepart'=>$mdepart,'pick'=>$pick]);
    // }

    //废弃代码 编辑渠道联系人
    // public function edit_dc($dcid)
    // {
    //     $this->load->model('qudao_model','qm');
    //     $dcinfo=$this->qm->getdcbyid($dcid);
    //     $qudaoInfo=$this->qm->getqudaobyid($dcinfo['q_id']);
    //     $mdepart=$this->qm->getzamktype('qudao_depa');
    //     $mgroup =$this->qm->getzamktype('qudao_grou');
    //     $mrol =$this->qm->getzamktype('qudao_rol');
    //     $pick =$this->qm->getzamktype('qudao_pik');
    //     $this->showpage('fms/qudaolistpickersedit',['qinfo'=>$qudaoInfo,'group'=>$mgroup,'mrol'=>$mrol,'mdepart'=>$mdepart,'pick'=>$pick,'dcinfo'=>$dcinfo]);
    // }

    /**
     * 添加编辑渠道对接人
     */
    public function do_contact(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ct_id', '', 'integer');
        $this->form_validation->set_rules('q_id', '渠道', 'integer|required');
        $this->form_validation->set_rules('ct_name', '姓名', 'required|max_length[10]');
        $this->form_validation->set_rules('ct_define', '对接人定义', 'max_length[10]');
        $this->form_validation->set_rules('ct_department', '所属部门', 'max_length[10]');
        $this->form_validation->set_rules('ct_class', '组别', 'max_length[10]');
        $this->form_validation->set_rules('ct_role', '角色', 'max_length[10]');
        $this->form_validation->set_rules('ct_call', '手机', 'max_length[11]');
        $this->form_validation->set_rules('ct_mail', '公司邮箱', 'max_length[30]');
        // $this->form_validation->set_rules('note1_val', '评估邮件组', 'max_length[10]');
        // $this->form_validation->set_rules('note2_val', '订单邮件组', 'max_length[10]');



        if ($this->form_validation->run()) {
            $where['ct_id'] = $this->input->post('ct_id',TRUE);
            $data['belong_id'] = $this->input->post('q_id',TRUE);
            $data['ct_name'] = $this->input->post('ct_name',TRUE);
            $data['ct_define'] = $this->input->post('ct_define',TRUE);
            $data['ct_department'] = $this->input->post('ct_department',TRUE);
            $data['ct_class'] = $this->input->post('ct_class',TRUE);
            $data['ct_role'] = $this->input->post('ct_role',TRUE);
            $data['ct_call'] = $this->input->post('ct_call',TRUE);
            $data['ct_mail'] = $this->input->post('ct_mail',TRUE);
            // $data['note1_val'] = $this->input->post('note1_val',TRUE);
            // $data['note2_val'] = $this->input->post('note2_val',TRUE);
            // $data['note1_key'] = '评估邮件组';
            // $data['note2_key'] = '订单邮件组';
            $data['ct_type'] = 'qd';

            $this->load->model("Jigou_model",'jg');
            //编辑
            if ($where['ct_id']) {
                $ret['ret'] = $this->jg->edit_contact($data,$where);
                $ret['info'] = $ret['ret']?'渠道对接人修改成功':'渠道对接人修改失败';
            //新建
            }else{
                $ret['ret'] = $this->jg->add_contact($data);
                $ret['info'] = $ret['ret']?'渠道对接人添加成功':'渠道对接人添加失败';
            } 

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

        echo json_encode($ret);
    }

// 渠道联系人重构 by 奚晓俊 结束=========================


// 渠道页面重构 by 奚晓俊 开始======================
    //废弃代码 输出渠道列表
    // public function query()
    // {
    //     if ($_SESSION['fms_userrole'] != 1)
    //     {
    //         echo "没有权限！";exit;
    //     }
    //     $data = array();
    //     $this->showpage('fms/qudaoquery',$data);
    // }
    
    // //废弃代码 获取渠道列表数据
    // public function qudaolist()
    // {
    //     $page = $this->input->get('page',true);
    //     $rows = $this->input->get('rows',true);
    //     $qname=$this->input->get('spmc',true);
    //     $this->load->model('qudao_model','qm');
    //     $reponseData=$this->qm->query_qudao_list($page,$rows,$qname);
    //     foreach ($reponseData['rows'] as $k=>$v){
    //         $reponseData['rows'][$k]['op']=sprintf("<a href='#' onclick='edit_sp(%d)'>%s</a>&nbsp;<a href='#' onclick='del(%d)'>%s</a>&nbsp;<a href='#' onclick='showpicker(%d)'>%s</a>",
    //             $v['q_id'],
    //             '编辑',
    //             $v['q_id'],
    //             '删除',
    //             $v['q_id'],
    //             '接口人'
    //         );
    //     }
    //     echo json_encode($reponseData);
    // }

    /**
     * 输出渠道列表
     */
    public function list_qudao(){
        $this->load->helper(['checkrolepower','publicstatus']);
        $data['statusColor'] = json_encode($this->statusColor);
        $data['q_id'] = $this->input->get('q_id',true);
        $this->showpage('fms/list_qudao',$data);
    }

    /**
     * 获取渠道
     */
    public function get_qudao(){
        $q_id = $this->input->get('q_id',true);
        $this->load->model("Qudao_model",'QD');

        //获取指定渠道id
        if($q_id) {
            $res = $this->QD->get_qudao_by_id($q_id);

        //获取列表
        }else{
            $q_id = $this->input->get('q',true);
            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            if($sort=='obj_status_info') $sort='obj_status';
            $order = $this->input->get('order',true);
            $res = $this->QD->list_qudao($like,$page,$rows,$sort,$order,$q_id);
        }
        echo json_encode($res);
    }

    //废弃代码 添加渠道
//     public function addrec()
//     {
// //        if(!isset($_POST['formid']) || $_POST['formid'] != $_SESSION['formid']){
// //            printf("<script>parent.complete('401','无效表单')</script>");
// //            exit();
// //        }

//         $this->load->model('qudao_model','qm');
//         $ret=$this->qm->addrec();
//         printf("<script>parent.complete('%s','%s')</script>",$ret[0],$ret[1]);
//         unset($_SESSION['formid']);
//     }

    /**
     * 添加编辑渠道
     */
    public function do_qudao(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('obj_id', '', 'integer');
        $this->form_validation->set_rules('q_name', '渠道名称', 'required|max_length[100]');
        $this->form_validation->set_rules('q_code', '渠道编号', 'required|max_length[25]');
        $this->form_validation->set_rules('q_company', '渠道公司名称', 'required|max_length[100]');
        $this->form_validation->set_rules('q_level', '渠道等级', 'required|max_length[4]');
        // $this->form_validation->set_rules('q_picker', '对接人姓名', 'required|max_length[50]');
        // $this->form_validation->set_rules('q_picker_phone', '电话号码', 'required|max_length[15]');
        $this->form_validation->set_rules('q_picker_company', '公司名称', 'required|max_length[150]');
        $this->form_validation->set_rules('q_picker_company_addr', '公司地址', 'required|max_length[250]');
        $this->form_validation->set_rules('q_picker_company_mail', '唯一指定邮箱', 'required|valid_email|max_length[150]');
        $this->form_validation->set_rules('q_picker_business_time', '经营时间', 'required|max_length[20]');
        $this->form_validation->set_rules('q_picker_business', '主营业务', 'required|max_length[250]');
        $this->form_validation->set_rules('q_team_numbers', '现有团队人数', 'required|integer|max_length[5]');
        $this->form_validation->set_rules('q_if_has_risk_team', '是否有风控团队', 'required|max_length[1]');
        $this->form_validation->set_rules('q_if_has_stock', '是否有自有存量', 'required|max_length[1]');

        if ($this->form_validation->run()) {
            $where['q_id'] = $this->input->post('obj_id',TRUE);
            $data = [
                'q_name'=>$this->input->post('q_name',true),
                'q_code'=>$this->input->post('q_code',true),
                'q_company'=>$this->input->post('q_company',true),
                'q_level'=>$this->input->post('q_level',true),
                // 'q_picker'=>$this->input->post('q_picker',true),
                // 'q_picker_phone'=>$this->input->post('q_picker_phone',true),
                'q_picker_company'=>$this->input->post('q_picker_company',true),
                'q_picker_company_addr'=>$this->input->post('q_picker_company_addr',true),
                'q_picker_company_mail'=>$this->input->post('q_picker_company_mail',true),
                'q_picker_business_time'=>$this->input->post('q_picker_business_time',true),
                'q_picker_business'=>$this->input->post('q_picker_business',true),
                'q_team_numbers'=>$this->input->post('q_team_numbers',true),
                'q_if_has_risk_team'=>$this->input->post('q_if_has_risk_team',true),
                'q_if_has_stock'=>$this->input->post('q_if_has_stock',true),
            ];

            $this->load->model("Qudao_model",'QD');
            //编辑
            if ($where['q_id']) {
                $ret['ret'] = $this->QD->edit_qudao($data,$where);
                $ret['info'] = $ret['ret']?'渠道修改成功':'渠道修改失败';
            //新建
            }else{
                $ret['ret'] = $this->QD->add_qudao($data);
                $ret['info'] = $ret['ret']?'渠道添加成功':'渠道添加失败';
            } 

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

        echo json_encode($ret);
    }
   
    /**
     * 改变状态
     */
    private function do_status($fun,$obj_type){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('obj_id', '', 'integer|required');

        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
            return json_encode($ret);
        }
        $obj_id = $this->input->post('obj_id',true);
        $status_info = $this->input->post('status_info',true);
        $for_admins = $this->input->post('for_admins',true);
        $this->load->helper('publicstatus');
        return ($fun($obj_type,$obj_id,$status_info,$for_admins));
    }

    /**
     * 报审渠道
     */
    public function baoShen(){
        echo $this->do_status('baoShenStatus','qd');
    }

    // /**
    //  * 初审通过渠道
    //  */
    // public function guoChuShen(){
    //     echo $this->do_status('guoChuShenStatus','qd');
    // }

    // /**
    //  * 初审驳回渠道
    //  */
    // public function backChuShen(){
    //     echo $this->do_status('backChuShenStatus','qd');
    // }

    // /**
    //  * 复审通过渠道
    //  */
    // public function guoFuShen(){
    //     echo $this->do_status('guoFuShenStatus','qd');
    // }

    // /**
    //  * 复审驳回渠道
    //  */
    // public function backFuShen(){
    //     echo $this->do_status('backFuShenStatus','qd');
    // }

    /**
     * 审核通过渠道
     */
    public function guoShen(){
        echo $this->do_status('guoShenStatus','qd');
    }

    /**
     * 审核驳回渠道
     */
    public function backShen(){
        echo $this->do_status('backShenStatus','qd');
    }

    /**
     * 停用渠道
     */
    public function stop(){
        echo $this->do_status('stopStatus','qd');
    }

    /**
     * 启用渠道
     */
    public function start(){
        echo $this->do_status('startStatus','qd');
    }

    /**
     * 申请修改渠道
     */
    public function pleaseEdit(){
        echo $this->do_status('pleaseEditStatus','qd');
    }

    /**
     * 批准修改渠道
     */
    public function yesEdit(){
        echo $this->do_status('yesEditStatus','qd');
    }

    /**
     * 驳回修改渠道
     */
    public function noEdit(){
        echo $this->do_status('noEditStatus','qd');
    }

    /**
     * 显示渠道流程信息
     */
    public function history(){

        $jg_id = $this->input->get('obj_id',true);
        $this->load->helper('publicstatus');
        $arr = historyStatus('qd',$jg_id);
        echo json_encode($arr);
    }

    /**
     * 提醒渠道流程
     */
    public function remind(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('obj_id', '', 'required|integer');
        $this->form_validation->set_rules('remind_admins[]', '', 'required');
        $this->form_validation->set_rules('remind_info', '', 'max_length[240]');
        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }else{
            $obj_id = $this->input->post('obj_id',true);
            $remind_admins = $this->input->post('remind_admins[]',true);
            $remind_info = $this->input->post('remind_info',true);
            $this->load->helper('publicstatus');
            $ret['ret'] = remindStatus('qd',$obj_id,$remind_admins,$remind_info);
            if($ret['ret']){
                $ret['info'] = '提醒成功！';
            }else{
                $ret['info'] = '提醒失败！';
            }
        }
        
        echo json_encode($ret);
    }

// 渠道页面重构 by 奚晓俊 结束======================



    // //废弃代码 作用不明
    // public function adddc_row()
    // {
    //     $this->load->model('qudao_model','qm');
    //     $ret=$this->qm->adddc_row();
    //     printf("<script>parent.complete('%s','%s')</script>",$ret[0],$ret[1]);
    //     unset($_SESSION['formid']);
    // }

    // //废弃代码 作用不明
    // public function saverec()
    // {
    //     $this->load->model('qudao_model','qm');
    //     $ret=$this->qm->saverec();
    //     printf("<script>parent.complete('%s','%s')</script>",$ret[0],$ret[1]);
    // }

    // //废弃代码 作用不明
    // public function savedc_row()
    // {
    //     $this->load->model('qudao_model','qm');
    //     $ret=$this->qm->savedc_row();
    //     printf("<script>parent.complete('%s','%s')</script>",$ret[0],$ret[1]);
    // }
}
?>