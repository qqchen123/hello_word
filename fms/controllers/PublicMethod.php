<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 公共方法控制器入口
 * @filename .php
 * @author xixiaojun
 * @version 1.0.0
 * @date 2018-05-30
 */
class PublicMethod extends Admin_Controller {
    public function __construct(){
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
    }

    /*
    * 获取公司内部员工供筛选 by 奚晓俊
    */
	public function getAdmin(){
        $this->load->model('PublicMethod_model','PM');
        echo json_encode($this->PM->getAdmin());
    }

    // /*
    // * 获取公司内部员工供筛选
    // */
    // public function doRemind(){
    //     $this->load->library('form_validation');
    //     $this->form_validation->set_rules('obj_type', '', 'integer|required');
    //     $this->form_validation->set_rules('obj_id', '', 'integer|required');

    //     $this->load->model('PublicMethod_model','PM');
    //     echo json_encode($this->PM->getAdmin());
    // }

    /**
     * @url PublicMethod/getTemplate
     */
    public function getTemplate()
    {
        $dir = $this->uri->segment(3);
        $name = $this->uri->segment(4);
        $name_plus = $this->uri->segment(5);
        if (!empty($name_plus)) {
            $name .= '/' . $name_plus;
        }
        $this->load->helper(['array', 'tools', 'slog']);
        $pagefile = $dir . '/' . $name;
        $args = [];
        $this->load->view($pagefile, $args, false);
    }

    /*
    * 获取派生值 by 奚晓俊
    * $user_id 客户id
    * $fun 派生值规则 如{{1}}+{{2}}
    * $class 主调查询数据的控制器（ajax来源页面获取非派生值数据的控制器）
    * $method 主调查询数据的方法（ajax来源页面获取非派生值数据的方法）
    */
    public function getDeriveValue(){
        $this->samples_ref = [];
        $this->sample_vals = [];
        $this->user_id = null;
        $this->recursionNum = 0;

        $this->load->library('form_validation');
        $this->load->helper('checkrolepower');
        $this->load->model('pool_sample_model');
        $this->load->model('pool_model');

        $this->form_validation->set_rules('user_id', '', 'integer|required');
        $this->form_validation->set_rules('id', '', 'integer|required');
        // $this->form_validation->set_rules('fun', '', 'required');
        $this->form_validation->set_rules('class', '', 'required');
        $this->form_validation->set_rules('method', '', 'required');
        if (!$this->form_validation->run()) {
            $res['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $res['info'] = validation_errors();
            exit(json_encode($res));
        }

        $this->user_id = $this->input->post('user_id',true);
        $sample_id = $this->input->post('id',true);
        $class = $this->input->post('class',true);
        $method = $this->input->post('method',true);

        //判断样本id参数权限
        $this->rolePowerDetails = getRolePowerDetails($class,$method);
        checkDetails([$sample_id],true);

        $sample = $this->pool_sample_model->get_one_info($sample_id);
        if($sample['class']!=='easyui-textbox derive'){
            $res['ret'] = false;
            $res['info'] = '不是派生值！';
            exit(json_encode($res));
        }
        $fun = json_decode($sample['data-options'],true);
        if(isset($fun['fun'])){
            $fun = $fun['fun'];
        }else{
            $res['ret'] = false;
            $res['info'] = '未定义公式！';
            exit(json_encode($res));
        }

        $res = $this->pool_model->getRecursionDeriveValue($fun);
        // if(is_array($res['info'])) $res['info'] = html_entity_decode(join($res['info']));
        echo json_encode($res,256);
    }


    //write by 陈恩杰 --城市三级联动
    public function get_city()
    {
        $this->load->model('pool_model');//san ji lian dong
        $region_id = $this->input->get('c_id');
        $region_id?$region_id:'';
        $pro = $this->pool_model->get_address($region_id);
        echo json_encode($pro);die;
    }

    //输出全部 by 奚晓俊
    public function get_user_name_for_select(){
        $this->load->model('user/User_model','user');
        $data = $this->user->get_user_base_filed(['id','name','fuserid']);
        echo json_encode($data,256);
    }

    public function getImg(){
        // ob_start();
        ob_clean();
        $name = $this->input->get('name',true);
        $base_path = "/home/upload/";
        if (file_exists($base_path . $name)) {
            header("Content-Disposition: attachment; filename='"+$name+"'");
            echo file_get_contents($base_path . $name); 
        } else {
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
        }
    }
}
