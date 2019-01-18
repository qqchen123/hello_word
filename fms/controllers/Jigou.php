<?
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 机构管理
 * @filename Jigou.php
 * @author xixiaojun
 * @version 1.0.0
 * @date 2018-05-20
 */

// require ('PublicStatusMethod.php');

class Jigou extends Admin_Controller{

// use PublicStatusMethod{
//     PublicStatusMethod::baoShen2 as baoShen3;
// }

	public function __construct(){
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
    }
//验证  开始============================
    /**
     * 验证日期
     */
    function check_date($date){
        if (date('Y-m-d',strtotime($date))==$date) {
            return true;
        }else{
            $this->form_validation->set_message('check_date', '“{field}”不符合日期格式！');
            return false;
        }
    }

    /**
     * 验证百分率
     */
    function check_rate($float){
        if (round($float,4)==$float && floor($float)<100) {
            return true;
        }else{
            $this->form_validation->set_message('check_rate', '“{field}”百分率保留2位整数和4位小数！');
            return false;
        }
    }

    /**
     * 验证机构有效状态
     */
    function check_jg_ok_status($jg_id){
        $this->load->helper('publicstatus');
        $bool = checkOneOkStatus('jg',$jg_id);
        if($bool){
            return true;
        }else{
            $this->form_validation->set_message('check_jg_ok_status',"只有当机构状态为“审核完成”，才能进行此操作！");
            return false;
        }
    }

    /**
     * 验证机构产品可编辑状态
     */
    function check_product_edit_status($product_id){
        $this->load->helper('publicstatus');
        $bool = checkOneEditStatus('jg_product',$product_id);
        if($bool){
            return true;
        }else{
            $this->form_validation->set_message('check_product_edit_status',"只有当机构产品可编辑时，才能进行此操作！");
            return false;
        }
    }

//验证  结束============================


//机构 开始=========================
    /**
     * 输出机构列表
     */
    public function list_jigou(){
        $this->load->helper(['checkrolepower','publicstatus']);
        $data['statusColor'] = json_encode($this->statusColor);
        $data['jg_id'] = $this->input->get('jg_id',true);
        $this->showpage('fms/list_jg',$data);
    }

    /**
     * 获取机构
     */
    public function get_jigou(){
        $jg_id = $this->input->get('jg_id',true);
        $this->load->model("Jigou_model",'jg');

        //获取指定机构id
        if($jg_id) {
            $res = $this->jg->get_jg_by_id($jg_id);

        //获取列表
        }else{
            $jg_id = $this->input->get('jg',true);
            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            if($sort=='obj_status_info') $sort='obj_status';
            $order = $this->input->get('order',true);
            $res = $this->jg->list_jg($like,$page,$rows,$sort,$order,$jg_id);
        }
        echo json_encode($res);
    }

    /**
     * 添加编辑机构
     */
    public function do_jigou(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('obj_id', '', 'integer');
        $this->form_validation->set_rules('jg_name', '机构名称', 'required|max_length[30]');
        $this->form_validation->set_rules('jg_code', '机构代码', 'required|max_length[30]');

        $this->form_validation->set_rules('proxy_name', '机构代理名称', 'required|max_length[20]');
        $this->form_validation->set_rules('proxy_area', '机构代理区域', 'required|max_length[20]');
        $this->form_validation->set_rules('proxy_level', '机构代理级别', 'required');
        $this->form_validation->set_rules('jg_signing_years', '签约年限', '');

        $this->form_validation->set_rules('jg_company', '机构公司名称', 'max_length[30]');
        if($_POST['jg_signing_begin']===''){
            $_POST['jg_signing_begin'] = null;
        }else{
            $this->form_validation->set_rules('jg_signing_begin', '签约开始日期', 'callback_check_date');
        }

        if($_POST['jg_signing_end']===''){
            $_POST['jg_signing_end'] = null;
        }else{
            $this->form_validation->set_rules('jg_signing_end', '签约结束日期', 'callback_check_date');
        }
        if($_POST['jg_credit_money']===''){
            $_POST['jg_credit_money'] = null;
        }else{
            $this->form_validation->set_rules('jg_credit_money', '授信额度', 'max_length[10]|integer');
        }
        // $this->form_validation->set_rules('add_credit_money', '增加授信额度', 'max_length[10]|integer');
        // $this->form_validation->set_rules('margin_rate', '机构保证金费率', 'numeric|callback_check_rate');
        // $this->form_validation->set_rules('payable_margin_money', '机构应付保证金金额', 'max_length[10]|integer');
        // $this->form_validation->set_rules('paid_margin_money', '机构已付保证金金额', 'max_length[10]|integer');

        if ($this->form_validation->run()) {
            $where['jg_id'] = $this->input->post('obj_id',TRUE);
            $data['jg_name'] = $this->input->post('jg_name',TRUE);
            $data['jg_code'] = $this->input->post('jg_code',TRUE);

            $data['proxy_name'] = $this->input->post('proxy_name',TRUE);
            $data['proxy_area'] = $this->input->post('proxy_area',TRUE);
            $data['proxy_level'] = $this->input->post('proxy_level',TRUE);

            $data['jg_company'] = $this->input->post('jg_company',TRUE);
            $data['jg_signing_begin'] = $this->input->post('jg_signing_begin',TRUE);
            $data['jg_signing_end'] = $this->input->post('jg_signing_end',TRUE);
            $data['jg_credit_money'] = $this->input->post('jg_credit_money',TRUE);
            $data['jg_signing_years'] = $this->input->post('jg_signing_years',TRUE);

            // $data['add_credit_money'] = $this->input->post('add_credit_money',TRUE);
            // var_dump($data['add_credit_money']);
            // $data['margin_rate'] = $this->input->post('margin_rate',TRUE)*10000;
            // $data['payable_margin_money'] = $this->input->post('payable_margin_money',TRUE);
            // $data['paid_margin_money'] = $this->input->post('paid_margin_money',TRUE);

            $this->load->model("Jigou_model",'jg');
            //编辑
            if ($where['jg_id']) {
                $ret['ret'] = $this->jg->edit_jg($data,$where);
                $ret['info'] = $ret['ret']?'机构修改成功':'机构修改失败';
            //新建
            }else{
                $ret['ret'] = $this->jg->add_jg($data);
                $ret['info'] = $ret['ret']?'机构添加成功':'机构添加失败';
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
        // var_dump($this->rolePowerDetails);
        // $this->load->helper('checkrolepower');
        // $r = checkDetails(['79','a2','a1','a3']);
        // var_dump($r);
        // exit();
        $this->load->library('form_validation');
        //改机构产品 验证机构状体是不是有效
        if($obj_type=='jg_product'){
            $this->form_validation->set_rules('jg_id', '', 'integer|required|callback_check_jg_ok_status');
        }else{
            $this->form_validation->set_rules('obj_id', '', 'integer|required');
        }
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
     * 报审机构
     */
    public function baoShen(){
        echo $this->do_status('baoShenStatus','jg');
    }

    /**
     * 初审通过机构
     */
    public function guoChuShen(){
        echo $this->do_status('guoChuShenStatus','jg');
    }

    /**
     * 初审驳回机构
     */
    public function backChuShen(){
        echo $this->do_status('backChuShenStatus','jg');
    }

    /**
     * 复审通过机构
     */
    public function guoFuShen(){
        echo $this->do_status('guoFuShenStatus','jg');
    }

    /**
     * 复审驳回机构
     */
    public function backFuShen(){
        echo $this->do_status('backFuShenStatus','jg');
    }

    /**
     * 审核通过机构
     */
    public function guoShen(){
        echo $this->do_status('guoShenStatus','jg');
    }

    /**
     * 审核驳回机构
     */
    public function backShen(){
        echo $this->do_status('backShenStatus','jg');
    }

    /**
     * 停用机构
     */
    public function stop(){
        echo $this->do_status('stopStatus','jg');
    }

    /**
     * 启用机构
     */
    public function start(){
        echo $this->do_status('startStatus','jg');
    }

    /**
     * 申请修改机构
     */
    public function pleaseEdit(){
        echo $this->do_status('pleaseEditStatus','jg');
    }

    /**
     * 批准修改机构
     */
    public function yesEdit(){
        echo $this->do_status('yesEditStatus','jg');
    }

    /**
     * 驳回修改机构
     */
    public function noEdit(){
        echo $this->do_status('noEditStatus','jg');
    }

    /**
     * 显示机构流程信息
     */
    public function history(){
        $jg_id = $this->input->get('obj_id',true);
        $this->load->helper('publicstatus');
        $arr = historyStatus('jg',$jg_id);
        echo json_encode($arr);
    }

    /**
     * 提醒机构流程
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
            $ret['ret'] = remindStatus('jg',$obj_id,$remind_admins,$remind_info);
            if($ret['ret']){
                $ret['info'] = '提醒成功！';
            }else{
                $ret['info'] = '提醒失败！';
            }
        }

        echo json_encode($ret);
    }


//机构 结束=========================

//机构产品 开始=========================

    /**
     * 输出机构产品列表
     */
    public function list_jg_product(){
        $data = [];
        $this->load->helper(['checkrolepower','publicstatus']);
        $product_id = $this->input->get('product_id',true);
        if(!$product_id){
            $jg_id = $this->input->get('jg_id',true);
            if ($jg_id) {
                $this->load->model("Jigou_model",'jg');
                $data = $this->jg->get_jg_by_id($jg_id);
            }
        }else{
            // $this->load->model("Jigou_model",'jg');
            // $data = $this->jg->get_jg_by_productid($product_id);
            $data['product_id'] = $product_id;
        }
        $this->showpage('fms/list_jg_product',$data);
    }

    /**
     * 输出机构名称列表供选择
     */
    public function get_jg_name(){
        $obj_status = $this->input->get('obj_status',true);
        if ($obj_status) {
            $where = ['obj_status'=>$obj_status];
        }else{
            $where = [];
        }
        $this->load->model("Jigou_model",'jg');
        $res = $this->jg->get_jg_name($where);

        echo json_encode($res);
    }

    /**
     * 获取机构产品列表
     */
    public function get_product(){
        $product_id = $this->input->get('product_id',true);
        $this->load->model("Jigou_model",'jg');

        //获取指定机构产品id
        if($product_id) {
            $res = $this->jg->get_product_by_id($product_id);

        //获取机构产品列表
        }else{
            $jg_id = $this->input->get('jg_id',true);
            $jg_status = $this->input->get('jg_status',true);
            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            if($sort=='jg_status_info') $sort='jg_status';
            if($sort=='obj_status_info') $sort='obj_status';
            $order = $this->input->get('order',true);
            $product_id = $this->input->get('product',true);
            $res = $this->jg->list_product($jg_status,$jg_id,$like,$page,$rows,$sort,$order,$product_id);
        }

        echo json_encode($res);
    }

    /**
     * 添加编辑机构产品
     */
    public function do_product(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('obj_id', '', 'integer');
        $this->form_validation->set_rules('jg_id', '机构', 'integer|required|callback_check_jg_ok_status');
        $this->form_validation->set_rules('product_name', '机构产品名称', 'required|max_length[30]');
        $this->form_validation->set_rules('product_code', '机构产品编号', 'required|max_length[30]');
        $this->form_validation->set_rules('product_type', '机构产品类型', 'required|max_length[30]');
        $this->form_validation->set_rules('product_date', '机构产品期限', 'required|integer|max_length[5]');


        if ($this->form_validation->run()) {
            $where['product_id'] = $this->input->post('obj_id',TRUE);
            $data['jg_id'] = $this->input->post('jg_id',TRUE);
            $data['product_name'] = $this->input->post('product_name',TRUE);
            $data['product_code'] = $this->input->post('product_code',TRUE);
            $data['product_type'] = $this->input->post('product_type',TRUE);
            $data['product_date'] = $this->input->post('product_date',TRUE);

            $this->load->model("Jigou_model",'jg');
            //编辑
            if ($where['product_id']) {
                $ret['ret'] = $this->jg->edit_product($data,$where);
                $ret['info'] = $ret['ret']?'机构产品修改成功':'机构产品修改失败';
            //新建
            }else{
                $ret['ret'] = $this->jg->add_product($data);
                $ret['info'] = $ret['ret']?'机构产品添加成功':'机构产品添加失败';
            }

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

        echo json_encode($ret);
    }



    /**
     * 报审机构产品
     */
    public function baoShenProduct(){
        echo $this->do_status('baoShenStatus','jg_product');
    }

    /**
     * 初审通过机构产品
     */
    public function guoChuShenProduct(){
        echo $this->do_status('guoChuShenStatus','jg_product');
    }

    /**
     * 初审驳回机构产品
     */
    public function backChuShenProduct(){
        echo $this->do_status('backChuShenStatus','jg_product');
    }

    /**
     * 复审通过机构产品
     */
    public function guoFuShenProduct(){
        echo $this->do_status('guoFuShenStatus','jg_product');
    }

    /**
     * 复审驳回机构产品
     */
    public function backFuShenProduct(){
        echo $this->do_status('backFuShenStatus','jg_product');
    }

    /**
     * 审核通过机构产品
     */
    public function guoShenProduct(){
        echo $this->do_status('guoShenStatus','jg_product');
    }

    /**
     * 审核驳回机构产品
     */
    public function backShenProduct(){
        echo $this->do_status('backShenStatus','jg_product');
    }

    /**
     * 停用机构产品
     */
    public function stopProduct(){
        echo $this->do_status('stopStatus','jg_product');
    }

    /**
     * 启用机构产品
     */
    public function startProduct(){
        echo $this->do_status('startStatus','jg_product');
    }

    /**
     * 申请修改机构产品
     */
    public function pleaseEditProduct(){
        echo $this->do_status('pleaseEditStatus','jg_product');
    }

    /**
     * 批准修改机构产品
     */
    public function yesEditProduct(){
        echo $this->do_status('yesEditStatus','jg_product');
    }

    /**
     * 驳回修改机构产品
     */
    public function noEditProduct(){
        echo $this->do_status('noEditStatus','jg_product');
    }

    /**
     * 显示机构产品流程信息
     */
    public function historyProduct(){
        $id = $this->input->get('obj_id',true);
        $this->load->helper('publicstatus');
        $arr = historyStatus('jg_product',$id);
        echo json_encode($arr);
    }


    /**
     * 提醒机构流程
     */
    public function remindProduct(){
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
            $ret['ret'] = remindStatus('jg_product',$obj_id,$remind_admins,$remind_info);
            if($ret['ret']){
                $ret['info'] = '提醒成功！';
            }else{
                $ret['info'] = '提醒失败！';
            }
        }

        echo json_encode($ret);
    }

    // /**
    //  * 提交机构产品
    //  */
    // public function bao_shen_product(){
    //     $status = 2;
    //     $status_info = '待审核';

    //     $this->change_product_status($status,$status_info);
    // }

    // /**
    //  * 过审机构产品
    //  */
    // public function guo_shen_product(){
    //     $status = 3;
    //     $status_info = '正常';

    //     $this->change_product_status($status,$status_info);
    // }

    // *
    //  * 审核退回产品

    // public function go_back_product(){
    //     $status = 1;
    //     $status_info = '退回信息：'.$this->input->post('goback_info',true);

    //     $this->change_product_status($status,$status_info);
    // }

    // /**
    //  * 停用机构产品
    //  */
    // public function stop_product(){
    //     $status = 4;
    //     $status_info = '停用';

    //     $this->change_product_status($status,$status_info);
    // }

    // /**
    //  * 启用机构产品
    //  */
    // public function start_product(){
    //     $status = 1;
    //     $status_info = '待提交';

    //     $this->change_product_status($status,$status_info);
    // }

    /**
     * 共用改变机构产品状态
     */
    // private function change_product_status($status,$status_info){
    //     $this->load->library('form_validation');
    //     $this->form_validation->set_rules('product_id', '机构产品', 'integer|required|callback_check_product_status');

    //     if ($this->form_validation->run()) {
    //         $product_id = $this->input->post('product_id',true);
    //         $this->load->model("Jigou_model",'jg');
    //         $ret['ret'] =  $this->jg->edit_product(['status'=>$status,'status_info'=>$status_info],['product_id'=>$product_id]);

    //     }else{
    //         $ret['ret'] = false;
    //         $this->form_validation->set_error_delimiters('', '<br>');
    //         $ret['info'] = validation_errors();
    //     }
    //     echo json_encode($ret);
    // }

//机构产品 结束=========================

//机构产品收费标准 开始=========================

    /**
     * 输出机构产品收费标准列表
     */
    public function list_jg_product_cost(){
        $this->load->helper(['checkrolepower','publicstatus']);
        $product_id = $this->input->get('product_id',true);
        if ($product_id) {
            $this->load->model("Jigou_model",'jg');
            $data = $this->jg->get_product_by_id($product_id);
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            $this->showpage('fms/list_jg_product_cost',$data);
        }else{
            $this->showpage('fms/list_jg_product_cost');
        }
    }

    /**
     * 获取机构产品收费标准列表
     */
    public function get_cost(){
        $cost_id = $this->input->get('cost_id',true);
        $this->load->model("Jigou_model",'jg');

        //获取指定机构产品id
        if($cost_id) {
            $res = $this->jg->get_cost_by_id($cost_id);

        //获取机构产品列表
        }else{
            $jg_id = $this->input->get('jg_id',true);
            $product_id = $this->input->get('product_id',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            if($sort=='jg_status_info') $sort='jg_status';
            if($sort=='product_status_info') $sort='product_status';
            $order = $this->input->get('order',true);
            $res = $this->jg->list_cost($jg_id,$product_id,$page,$rows,$sort,$order);
        }

        echo json_encode($res);
    }

    /**
     * 输出机构产品名称列表供选择
     */
    public function get_product_name(){
        $jg_id = $this->input->get('jg_id',true);
        if ($jg_id) {
            $where = ['jg_id'=>$jg_id];
        }else{
            $where = [];
        }
        $this->load->model("Jigou_model",'jg');
        $res = $this->jg->get_product_name($where);

        echo json_encode($res);
    }

    /**
     * 输出机构产品费率名称列表供选择
     */
    public function get_cost_name(){
        $this->load->model("Jigou_model",'jg');
        $res = $this->jg->get_cost_name();

        echo json_encode($res);
    }

    /**
     * 添加编辑机构产品收费标准
     */
    public function do_cost(){
        $this->load->library('form_validation');
        // $this->form_validation->set_rules('jg_id', '', 'integer');
        $this->form_validation->set_rules('cost_id', '', 'integer');
        $this->form_validation->set_rules('jg_id', '机构', 'integer|required|callback_check_jg_ok_status');
        $this->form_validation->set_rules('product_id', '机构产品', 'integer|required|callback_check_product_edit_status');
        $this->form_validation->set_rules('cost_type', '收费类型', 'required|integer');
        $this->form_validation->set_rules('cost_rate', '机构保证金费率', 'required|numeric|callback_check_rate');
        $this->form_validation->set_rules('is_before', '是否前置', 'required|integer|in_list[1,2]');
        $this->form_validation->set_rules('pay_type', '付息方式', 'required|integer|in_list[1,2]');


        if ($this->form_validation->run()) {
            $where['cost_id'] = $this->input->post('cost_id',TRUE);
            $data['product_id'] = $this->input->post('product_id',TRUE);
            $data['cost_type'] = $this->input->post('cost_type',TRUE);
            $data['cost_rate'] = $this->input->post('cost_rate',TRUE)*10000;
            $data['is_before'] = $this->input->post('is_before',TRUE);
            $data['pay_type'] = $this->input->post('pay_type',TRUE);

            $this->load->model("Jigou_model",'jg');
            //编辑
            if ($where['cost_id']) {
                $ret['ret'] = $this->jg->edit_cost($data,$where);
                $ret['info'] = $ret['ret']?'机构产品收费标准修改成功':'机构产品收费标准修改失败';
            //新建
            }else{
                $ret['ret'] = $this->jg->add_cost($data);
                $ret['info'] = $ret['ret']?'机构产品收费标准添加成功':'机构产品收费标准添加失败';
            }

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

        echo json_encode($ret);
    }
//机构产品收费标准 结束=========================

//机构联系人 开始=========================

    /**
     * 输出机构对接人列表
     */
    public function list_jg_contact(){
        $this->load->helper(['checkrolepower','publicstatus']);
        $jg_id = $this->input->get('jg_id',true);
        if ($jg_id) {
            $this->load->model("Jigou_model",'jg');
            $data = $this->jg->get_jg_by_id($jg_id);
            $this->showpage('fms/list_jg_contact',$data);
        }else{
            $this->showpage('fms/list_jg_contact');
        }
    }

    /**
     * 获取机构对接人数据
     */
    public function get_contact(){
        $ct_id = $this->input->get('ct_id',true);
        $this->load->model("Jigou_model",'jg');

        //获取指定机构产品id
        if($ct_id) {
            $res = $this->jg->get_contact_by_id($ct_id);

        //获取机构产品列表
        }else{
            $jg_id = $this->input->get('jg_id',true);
            $jg_status = $this->input->get('jg_status',true);
            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            if($sort=='jg_status_info') $sort='jg_status';
            $order = $this->input->get('order',true);
            $res = $this->jg->list_contact($jg_status,$jg_id,$like,$page,$rows,$sort,$order);
        }

        echo json_encode($res);
    }

    /**
     * 添加编辑机构对接人
     */
    public function do_contact(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ct_id', '', 'integer');
        $this->form_validation->set_rules('jg_id', '机构', 'integer|required');
        $this->form_validation->set_rules('ct_name', '姓名', 'required|max_length[10]');
        $this->form_validation->set_rules('ct_define', '对接人定义', 'max_length[10]');
        $this->form_validation->set_rules('ct_department', '所属部门', 'max_length[10]');
        $this->form_validation->set_rules('ct_class', '组别', 'max_length[10]');
        $this->form_validation->set_rules('ct_role', '角色', 'max_length[10]');
        $this->form_validation->set_rules('ct_call', '手机', 'max_length[11]');
        $this->form_validation->set_rules('note1_val', '评估邮件组', 'max_length[30]');
        $this->form_validation->set_rules('note2_val', '订单邮件组', 'max_length[30]');



        if ($this->form_validation->run()) {
            $where['ct_id'] = $this->input->post('ct_id',TRUE);
            $data['belong_id'] = $this->input->post('jg_id',TRUE);
            $data['ct_name'] = $this->input->post('ct_name',TRUE);
            $data['ct_define'] = $this->input->post('ct_define',TRUE);
            $data['ct_department'] = $this->input->post('ct_department',TRUE);
            $data['ct_class'] = $this->input->post('ct_class',TRUE);
            $data['ct_role'] = $this->input->post('ct_role',TRUE);
            $data['ct_call'] = $this->input->post('ct_call',TRUE);
            $data['note1_val'] = $this->input->post('note1_val',TRUE);
            $data['note2_val'] = $this->input->post('note2_val',TRUE);
            $data['note1_key'] = '评估邮件组';
            $data['note2_key'] = '订单邮件组';
            $data['ct_type'] = 'jg';

            $this->load->model("Jigou_model",'jg');
            //编辑
            if ($where['ct_id']) {
                $ret['ret'] = $this->jg->edit_contact($data,$where);
                $ret['info'] = $ret['ret']?'机构对接人修改成功':'机构对接人修改失败';
            //新建
            }else{
                $ret['ret'] = $this->jg->add_contact($data);
                $ret['info'] = $ret['ret']?'机构对接人添加成功':'机构对接人添加失败';
            }

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

        echo json_encode($ret);
    }

//机构联系人 结束=========================

}
?>