<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class MethodHook {

    private $directory; //目录
    private $class; //控制器名
    private $method;  //方法名
    private $admin_role = 1;//系统管理员角色
    private $is_test = 0;//测试环境显示成功步骤
    private $loginClass = 'auth';//程序放开登陆口
    private $loginMethod = ['login','logout','checkpass'];//程序放开登陆口
    private $indexClass = 'home';//后台首页
    private $indexMethod = 'index';//后台首页

    private $wx_dir = ['miniprogram'];//微信目录

    private $checkSessionTime = 300;//检查session时间 默认5分钟

    /**
     * 构造函数
     */
    public function __construct() {
        $this->CI = & get_instance();
        $this->directory = trim(strtolower($this->CI->router->fetch_directory()),'/');
        $this->class = strtolower($this->CI->router->fetch_class());
        $this->method = strtolower($this->CI->router->fetch_method());
        if(!in_array($this->directory,$this->wx_dir)){
            session_start();
        }else{
            $this->wx_session();
        }
        $this->CI->rolePowerDetails = [];
        //检查session
        $this->checkSession();
        //主要角色
        $this->role_id = @$_SESSION['fms_userrole'];
        //次要角色
        $this->role_ids = explode(',', @$_SESSION['fms_less_role_id']);
        $this->role_ids[] = $this->role_id;
    }

    /*
    *  检查session
    */
    private function checkSession(){
        // var_dump($_SESSION);
        if(isset($_SESSION['fms_id']) && @strtotime($_SESSION['check_time']) + $this->checkSessionTime < time()){

            $this->CI->load->model('Wesing_merchant_model','wm');
            $adminInfo = $this->CI->wm->get_one_by_name($_SESSION['fms_userid'],'01');
            if($adminInfo===null){
                foreach ($_SESSION as $k => $v) {
                    if(strpos($k, 'fms')===0) unset($_SESSION[$k]);
                }
                $_SESSION['check_time'] = date('Y-m-d H:i:s', time());
            }else{
                unset($adminInfo['upass']);
                unset($adminInfo['usermm']);
                unset($adminInfo['salt']);
                unset($adminInfo['openid']);
                $adminInfo['uname'] = $adminInfo['username'];
                foreach ($adminInfo as $_key => $_val){
                    $_SESSION['fms_'.$_key] = $_val;
                }
                $_SESSION['login_time'] = $_SESSION['check_time'] = date('Y-m-d H:i:s', time());
            }
        }
    }

    /*
    *  权限控制
    */
    function my_init(){

        //获取数据库（缓存）控制器方法
        $this->CI->load->model("Sys_model", "Sys");
        $method = $this->CI->Sys->get_method_by_classmethod($this->directory,$this->class,$this->method);

        if ($this->is_test) echo 1;
        //系统管理员 完全权限
        if ($this->role_id == $this->admin_role){
            $detail_arr = $this->CI->Sys->get_role_detail_power($this->directory,$this->class,$this->method);
            goto go; 
        }
        
        if($this->class == 'mx') goto go;//魔蝎demo
        
        //if($this->class == 'youtuai') goto go;//优图demo
        //开放登录口
        if ($this->class == $this->loginClass && in_array($this->method, $this->loginMethod)) goto go;
        //开放首页
        // if($this->class==$this->indexClass && $this->method==$this->indexMethod) goto go;

        //控制器方法未注册（后台首页免注册）
        if($this->is_test) echo 2;
        if($method===null && $this->class!=$this->indexClass && $this->method!=$this->indexMethod){
            if(!in_array($this->directory,$this->wx_dir)){
                self::show_message(403, '该模块未注册');
            }else{
                http_response_code(403);
                exit(json_encode(['ret' => false,'msg'=>'该模块未注册']));
            }
        }  

        //无需登录 跳过权限
        if($this->is_test) echo 3;
        if($method['is_login'] == 2) goto go;

        //判断是否登录
        if($this->is_test) echo 4;
        if(!@$_SESSION['fms_id']){
            if(!in_array($this->directory,$this->wx_dir)){
                redirect('/Auth/login');
            }
            // else{
            //     var_dump($_SESSION);
            //     http_response_code(403);
            //     exit(json_encode(['ret' => false,'sessionid'=>false,'msg'=>'请重新登录']));
            // }
        } 

        //判断角色是否有权限
        if($this->is_test) echo 5;
        // $power = $this->CI->Sys->get_method_by_role($this->role_id,$method['id']);
        // if (count($power)==0) self::show_message(403, '您无权访问该模块');
        //增加参数权限控制
        // $powerNum = $this->CI->Sys->get_role_power($this->class,$this->method,$this->role_id);
        // if ($powerNum==0) self::show_message(403, '您无权访问该模块');

        //增加参数权限控制 开放首页
        $detail_arr = $this->CI->Sys->get_role_detail_power($this->directory,$this->class,$this->method,$this->role_ids);
        
        if ($detail_arr==[] && $this->class!=$this->indexClass && $this->method!=$this->indexMethod){
            if(!in_array($this->directory,$this->wx_dir)){
                self::show_message(403, '您无权访问该模块');
            }else{
                http_response_code(403);
                exit(json_encode(['ret' => false,'msg'=>'您无权访问该模块']));
            }
        }

        go:

        //记录访问日志
        if($this->is_test) echo 6;
        if($method['is_loged'] == 1) self::autolog($method['id']);

        //传当前方法有权参数
        if(@$detail_arr){
            $k = array_search('',$detail_arr,true);
            if($k!==false) unset ($detail_arr[$k]);
            if($detail_arr!=[]) {
                // $_POST['rolePowerDetail'] = $detail_arr;
                $this->CI->rolePowerDetails = $detail_arr;
            }
        }
    }

    /*
    *  记录数据库日志 只能取得view输出 取不到echo的ajax结果
    */
    // public function my_end(){
    //     if(@$_SESSION['my_end']){
    //         unset($_SESSION['my_end']);
    //         $result = $this->CI->output->get_output();
    //         var_dump($result);
    //     }
    //     echo 'myamend';
    // }

    /**
     * 信息提示
     * @param string $msg 错误提示
     * @param bool $auto 是否自动跳转
     * @param string $goto 跳转地址
     * @param string $fix 
     * @param int $pause 跳转等待毫秒
     * @return  void
     */
    private function show_message($code, $msg, $auto = false, $goto = '',  $fix = '', $pause = 3000) {
        if ($auto && $goto == '')
            $goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();

        $goto .= $fix;

        http_response_code($code);
        $this->CI->load->view('sys/sys_entry', ['msg' => $msg, 'goto' => $goto, 'auto' => $auto, 'pause' => $pause]);
        exit($this->CI->output->get_output());
    }

    /**
     * 记录访问日志
     */
    private function autolog($method_id) {
        //注意mysql的concurrent_insert值
        $log_table = 'sys_visit_log_'.date('Y_m');
        // $log_table = 'sys_visit_log_2018_04';

        //时段分区 读并merger表

        if(@$_SERVER['REQUEST_METHOD']=='GET'){
            $request_method = 1;
            $query_string = $_SERVER['QUERY_STRING'];
        }else{
            $request_method = 2;
            $query_string = urldecode(http_build_query($_POST));
        }

        $this->CI->Sys->add_visit_log($log_table,[
            'user_ip' => @$_SERVER['REMOTE_ADDR'],
            'role_id' => @$_SESSION['fms_userrole'],
            'role_name' => @$_SESSION['fms_role_name'],
            'user_id' => @$_SESSION['fms_id'],
            //'user_name' => @$_SESSION['fms_userid'],
            'user_name' => @$_SESSION['fms_username'],
            'method_id' => @$method_id,
            'dir' => $this->directory,
            'class' => $this->class,
            'method' => $this->method,
            'request_method' => $request_method,
            'query_string' => $query_string
        ]);
    }

    /*
    * 微信登录态
    */
    private function wx_session(){
        $session_id = @$_SERVER['HTTP_SESSIONID'];
        // $session_id = $this->CI->input->get_post('session_id',true);
        if($session_id) session_id($session_id);
        session_start();
// var_dump($_SESSION);
        if($this->class != 'auth' && $this->method != 'is_bind')
        if(!isset($_SESSION['wx'])){
            file_put_contents(
                "../shared/logs/mini_logs.log",
                "\n\r".date('Y-m-d H:i:s')." SESSION.wx缺失\n\r".var_export($_SESSION,true),
                FILE_APPEND
            );
            http_response_code(403);
            exit(json_encode(['ret' => false,'sessionid'=>false,'msg'=>'会话过期，已重新连接']));
        }
    }
}
