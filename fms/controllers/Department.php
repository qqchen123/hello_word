<?
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 部门管理
 * @filename Department.php
 * @author xixiaojun
 * @version 1.0.0
 * @date 2018-12
 */

// require ('PublicStatusMethod.php');

class Department extends Admin_Controller{

// use PublicStatusMethod{
//     PublicStatusMethod::baoShen2 as baoShen3;
// }

	public function __construct(){
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
    }

//部门  开始============================
    /**
     * 验证部门名称唯一
     */
    function check_department_name($department_name){
        $num = $this->dm->check_department_name($department_name,$_POST['department_id']);
        if($num==0){
            return true;
        }else{
            $this->form_validation->set_message('check_department_name',"“部门名称”已存在！");
            return false;
        }
    }

    /**
     * 输出部门列表
     */
    public function list_department(){
        $this->load->helper(['checkrolepower']);
        $this->showpage('fms/list_department');
    }

    // /**
    //  * 获取部门
    //  */
    // public function get_department(){
    //     $department_id = $this->input->get('department_id',true);
    //     $this->load->model("Department_model",'dm');

    //     //获取指定机构id
    //     if($department_id) {
    //         $res = $this->dm->get_jg_by_id(@$department_id);

    //     //获取列表
    //     }else{
    //         $department_id = $this->input->get('department_id',true);
    //         $like = $this->input->get('like',true);
    //         $rows = $this->input->get('rows',true);
    //         $page = $this->input->get('page',true);
    //         $sort = $this->input->get('sort',true);
    //         // if($sort=='obj_status_info') $sort='obj_status';
    //         $order = $this->input->get('order',true);
    //         $res = $this->dm->list($like,$page,$rows,$sort,$order,$department_id);
    //     }
    //     echo json_encode($res);
    // }


    /**
     * 获取部门树列表
     */
    public function get_department_tree(){
        $this->load->model("Department_model",'dm');

        $sort = $this->input->get('sort',true);
        $order = $this->input->get('order',true);

        $select = $this->input->get('select',true);
        $root = $this->input->get('root',true);
        if($select==='select'){
            $res = $this->dm->tree($select,$sort,$order);
            $res = $this->list_to_tree($res,'id');
            if($root) $res = [['id'=>'0','text'=>'根部门','children'=>$res]];
        }else{
            $res = $this->dm->tree(null,$sort,$order);
            $res = $this->list_to_tree($res);
        }

        echo json_encode($res);
    }

    private function list_to_tree($list, $pk='department_id', $pid = 'parent_department_id', $child = 'children', $root = 0) {
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

    // 获取一条部门
    public function get_one(){
        $department_id = $this->input->get('department_id',true);
        $this->load->model("Department_model",'dm');
        $r = $this->dm->get_one($department_id);
        echo json_encode($r);
    }

    // 判断是否有子孙
    // private function if_son(){
    //     $department_id = $this->input->get('department_id',true);
    //     $this->load->model("Department_model",'dm');
    //     echo $this->dm->if_son($department_id);
    // }

    //删除部门
    public function del(){
        $department_id = $this->input->get('department_id',true);
        $this->load->model("Department_model",'dm');
        if($this->dm->if_son($department_id)>0)
            exit(json_encode(['ret'=>false,'msg'=>'请先删除其下级部门！']));
        
        $this->load->model('Role_model','role');
        $r = $this->role->get_by_department($department_id);
        if(count($r)>0){
            exit(json_encode(['ret'=>false,'msg'=>'请先更换 “ '.join('、',array_column($r,'role_name')).' ” 职位的部门！']));
        }

        if($this->dm->del($department_id)){
            echo json_encode(['ret'=>true,'msg'=>'删除成功！']);
        }else{
            echo json_encode(['ret'=>false,'msg'=>'删除失败！']);
        }   
    }

    /**
     * 添加编辑部门
     */
    public function do_department(){
        $this->load->library('form_validation');
        $this->load->model("Department_model",'dm');

        $this->form_validation->set_rules('department_id', '', 'integer');
        $this->form_validation->set_rules('parent_department_id', '上级部门', 'required|integer');
        $this->form_validation->set_rules('department_name', '部门名称', 'required|max_length[20]|callback_check_department_name[]');

        $this->form_validation->set_rules('leader_role_id', '负责人', 'integer');
        $this->form_validation->set_rules('department_function', '部门职责', 'max_length[255]');

        if ($this->form_validation->run()) {
            $where['department_id'] = $this->input->post('department_id',TRUE);
            $data['parent_department_id'] = $this->input->post('parent_department_id',TRUE);
            $data['department_name'] = $this->input->post('department_name',TRUE);
            $data['leader_role_id'] = $this->input->post('leader_role_id',TRUE);
            $data['department_function'] = $this->input->post('department_function',TRUE);
            if(!$data['leader_role_id']) $data['leader_role_id'] = null;

            //编辑
            if ($where['department_id']) {
                $ret['ret'] = $this->dm->edit($data,$where);
                $ret['info'] = $ret['ret']?'部门修改成功':'部门修改失败';
            //新建
            }else{
                $ret['ret'] = $this->dm->add($data);
                $ret['info'] = $ret['ret']?'部门添加成功':'部门添加失败';
            }

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

        echo json_encode($ret);
    }

//部门  结束============================

//职位  开始============================
    /**
     * 验证职位名称唯一
     */
    function check_role_name($role_name){
        $num = $this->role->check_role_name($role_name,$_POST['role_id'],$_POST['department']);
        if($num==0){
            return true;
        }else{
            $this->form_validation->set_message('check_role_name',"“该部门职位名称”已存在！");
            return false;
        }
    }

    /**
     * 输出职位列表
     */
    public function list_role(){
        $data['department_id'] = $this->input->get('department_id',true);
        $this->load->helper(['checkrolepower']);
        $this->showpage('fms/list_role',$data);
    }

    /**
     * 获取职位树列表
     */
    public function get_role_tree(){
        $this->load->model("role_model",'role');

        $department_id = $this->input->get('department_id',true);
        $sort = $this->input->get('sort',true);
        $order = $this->input->get('order',true);
        $select = $this->input->get('select',true);
        $root = $this->input->get('root',true);

        if($select==='select'){
            $res = $this->role->tree($select,$sort,$order,$department_id);
            $res = $this->list_to_tree($res,'id','parent_role_id');
            if($root)
                $res = [['id'=>'0','text'=>'根职位','children'=>$res]];
        }else{
            $res = $this->role->tree(null,$sort,$order,$department_id);
            $res = $this->list_to_tree($res,'role_id','parent_role_id');
        }

        echo json_encode($res);
    }

    // 获取一条职位
    public function get_role(){
        $role_id = $this->input->get('role_id',true);
        $this->load->model("role_model",'role');
        $r = $this->role->get_role($role_id);
        echo json_encode($r);
    }

    /**
     * 添加编辑职位
     */
    public function do_role(){
        $this->load->library('form_validation');
        $this->load->model("role_model",'role');

        $this->form_validation->set_rules('role_id', '', 'integer');
        $this->form_validation->set_rules('department', '所属部门', 'required|integer');
        $this->form_validation->set_rules('parent_role_id', '上级职位', 'required|integer');
        $this->form_validation->set_rules('role_name', '职位名称', 'required|max_length[20]|callback_check_role_name[]');

        $this->form_validation->set_rules('level_range', '职位职级', 'max_length[20]');
        $this->form_validation->set_rules('role_function', '职位职责', 'max_length[255]');

        if ($this->form_validation->run()) {
            $where['role_id'] = $this->input->post('role_id',TRUE);
            $data['parent_role_id'] = $this->input->post('parent_role_id',TRUE);
            $data['department'] = $this->input->post('department',TRUE);
            $data['role_name'] = $this->input->post('role_name',TRUE);
            $data['level_range'] = $this->input->post('level_range',TRUE);
            $data['role_function'] = $this->input->post('role_function',TRUE);

            // if(!$data['leader_role_id']) $data['leader_role_id'] = null;

            //编辑
            if ($where['role_id']) {
                $ret['ret'] = $this->role->edit($data,$where);
                $ret['info'] = $ret['ret']?'职位修改成功':'职位修改失败';
            //新建
            }else{
                $ret['ret'] = $this->role->add($data);
                $ret['info'] = $ret['ret']?'职位添加成功':'职位添加失败';
            }

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

        echo json_encode($ret);
    }

    //删除职位
    public function del_role(){
        $role_id = $this->input->get('role_id',true);

        $this->load->model("role_model",'role');
        if($this->role->if_son($role_id)>0)
            exit(json_encode(['ret'=>false,'msg'=>'请先删除其下级职位！']));

        $this->load->model('Department_model','dpt');
        $r = $this->dpt->get_by_leader_role_id($role_id);

        if(count($r)>0){
            exit(json_encode(['ret'=>false,'msg'=>'请先更换 “ '.join('、',array_column($r,'department_name')).' ” 的部门负责人职位！']));
        }
        
        if($this->role->del($role_id)){
            echo json_encode(['ret'=>true,'msg'=>'删除成功！']);
        }else{
            echo json_encode(['ret'=>false,'msg'=>'删除失败！']);
        }
        
    }
//职位  结束============================

//员工  开始============================
     /**
     * 验证员工编号唯一
     */
    function check_userid($userid){
        $num = $this->wm->check_userid($userid,$_POST['id']);
        if($num==0){
            return true;
        }else{
            $this->form_validation->set_message('check_userid',"“该员工编号”已存在！");
            return false;
        }
    }

    public function list_wesing_merchant(){
        $this->load->helper(['checkrolepower']);
        $this->showpage('fms/list_wesing_merchant');
    }

    public  function get_wesing_merchant(){
        $this->load->model("Wesing_merchant_model",'wm');
        $like = trim($this->input->get('like',true));
        $rows = $this->input->get('rows',true);
        $page = $this->input->get('page',true);
        $sort = $this->input->get('sort',true);
        $order = $this->input->get('order',true);
        $res = $this->wm->list_wm($like,$page,$rows,$sort,$order);
        echo json_encode($res);
    }

    public function do_wesing_merchant(){
        $this->load->library('form_validation');
        $this->load->model("Wesing_merchant_model",'wm');
        $this->form_validation->set_rules('id', '', 'integer');
        $this->form_validation->set_rules('dzt', '状态', 'required|max_length[2]');
        $this->form_validation->set_rules('departmentid', '主属所部门', 'required|integer');
        $this->form_validation->set_rules('userrole', '主要职位', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('less_role_id', '次要职位','integer');
        $this->form_validation->set_rules('userid', '员工编号', 'required|max_length[10]|callback_check_userid[]');
        $this->form_validation->set_rules('username', '员工名称', 'required|max_length[50]');
        $this->form_validation->set_rules('idno', '身份证', 'max_length[18]|min_length[18]');
        $this->form_validation->set_rules('mobile', '手机号', 'max_length[11]|min_length[11]');
        $this->form_validation->set_rules('email', '邮件', 'max_length[51]');
        $this->form_validation->set_rules('rzdate', '入职日期', 'required');

        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
            exit(json_encode($ret));
        }
        $where['id'] = $this->input->post('id',true);
        $data['dzt'] = $this->input->post('dzt',true);
        $data['departmentid'] = $this->input->post('departmentid',true);
        $data['userrole'] = $this->input->post('userrole',true);
        $less_role_id = $this->input->post('less_role_id',true);
        if(!$less_role_id) $less_role_id = [];

        $data['userid'] = $this->input->post('userid',true);
        $data['username'] = $this->input->post('username',true);

        $data['idno'] = $this->input->post('idno',true);
        $data['mobile'] = $this->input->post('mobile',true);
        $data['email'] = $this->input->post('email',true);
        $data['rzdate'] = $this->input->post('rzdate',true);

        if($where['id']){
            $ret['ret'] = $this->wm->edit($data,$less_role_id,$where);
            $ret['info'] = $ret['ret']?'员工修改成功':'员工修改失败';
        }else{
            $data['salt'] = substr(str_shuffle(uniqid(time())),3,6);
            $data['usermm'] = md5($data['userid'].$data['salt'].'123456');
            $ret['ret'] = $this->wm->add($data,$less_role_id);
            $ret['info'] = $ret['ret']?'员工添加成功':'员工添加失败';
        }
        echo json_encode($ret);
    }
    
    // 获取一条员工
    public function get_wesing_merchant_by_id(){
        $id = $this->input->get('id',true);
        $this->load->model("Wesing_merchant_model",'wm');
        $r = $this->wm->get_one($id);
        if($r['less_role_id']) $r['less_role_id[]'] = $r['less_role_id'];
        echo json_encode($r);
    }

    public function del_wesing_merchant(){
        $id = $this->input->get('id',true);
        if($this->load->model("Wesing_merchant_model",'wm')){
            echo json_encode(['ret'=>true,'msg'=>'删除成功！']);
        }else{
            echo json_encode(['ret'=>false,'msg'=>'删除失败！']);
        }
    }
//员工  结束============================


}
?>