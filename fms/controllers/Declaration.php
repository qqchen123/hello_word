<?php

// Declaration
/**
 * @desc 报单系统
 */
class Declaration extends Admin_Controller
{
	/**
     * @name 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->basicloadhelper();

        //根据业务类型加载不同的service 组合
        $business_type = $this->uri->segment(2);
        //load 配置项
        if (in_array($business_type, ['index'])) {
            $this->load->service('public/Html_service', 'html_service');
        }
        //加载报单页面
        $this->load->model('public/Declaration_model');
    }

    /**
     * @name 报单列表
     * @url Declaration/index
     */
    public function index()
    {
    	$data = [];
    	$this->showpage('fms/declaration/index',$data);
    }


/*####基础begin############################################*/
    /**
     * @name 加载基本的helper
     */
    private function basicloadhelper()
    {
        //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);
    }
/*####基础end############################################*/

    public function get_de_info()
    {
        $de_res = $this->Declaration_model->select_declara_info(['needs_money','user_id'],['user_id'=>'371102']);
        if ($de_res){
            echo json_encode($de_res);die;
        }else{
            echo 0;die;
        }
    }
//输出保单页面
    public function show_declara_page()
    {
        $this->showpage('fms/declaration');
    }
//获取一条信息
    public function get_one_de_info()
    {   $id_info = $this->input->post();
        if ($id_info){
            $one_res = $this->Declaration_model->get_one_info(['d_id'=>$id_info['id']],['_id','d_id','name','idnumber','address','amount','useby','repaymentby','ctime','front_img','back_img','codepage_string','content_string','notes_string']);
        }else{
            $one_res = '暂无数据！';
        }
        echo json_encode($one_res);die;
    }
    //获取所有信息
    public function get_info()
    {
        $searchinfo = $this->input->post();
        $first = $searchinfo['rows'] * ($searchinfo['page'] - 1);
        if ( !empty($searchinfo['user_name'])){
            $searchinfotype = substr($searchinfo['user_name'],0,10);
            if ( is_numeric($searchinfotype)){
                $infores = $this->Declaration_model
                    ->select_declara_info(['idnumber'=>trim($searchinfo['user_name'])],['d_id','name','idnumber','address','amount','useby','repaymentby','ctime']);
            }else{
                $infores = $this->Declaration_model
                    ->select_declara_info(['name'=>trim($searchinfo['user_name'])],['d_id','name','idnumber','address','amount','useby','repaymentby','ctime']);
            }
        }else{
            $infores = $this->Declaration_model->get_info(['d_id','name','idnumber','address','amount','useby','repaymentby','ctime'],$first,$searchinfo['rows']);
        }
        echo json_encode($infores);die;
    }
    //插入一条信息
    public function insert_info()
    {
        $insert_info = ['d_id'=>'371102','name'=>'蜡笔小新'.rand(11,99),'idnumber'=>'371102199310152993','address'=>'上海市，静安区，共和新路共和大厦966号','amount'=>rand(11,99),'userby'=>'做生意','repaymentby'=>'做生意'];
        $insert_res = $this->Declaration_model->insert_declara_info($insert_info);
        if($insert_res){
            echo 'ok';
        }else{
            echo 'err';
        }
    }
    //获取评论信息
    public function get_chat_info()
    {
        $chat_info = $this->Declaration_model->get_info($insert_info);
        echo  json_encode($chat_info);
    }
//插入评论信息
    public function insert_chat_info()
    {
        $insert_info = ['user_num'=>'371103','chat_content'=>'评论11',];
        $chat_info = $this->Declaration_model->insert_declara_info($insert_info);
        echo  json_encode($chat_info);
    }

    public function update_declara_info()
    {
        $upres = $this->Declaration_model->update_declara_info();
        print_r($upres);die;
    }












}

?>
