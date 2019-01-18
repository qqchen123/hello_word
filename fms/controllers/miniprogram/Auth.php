<?php
class Auth extends CI_Controller{
    // private $appid;
    // private $secret;

    private $data = [];
    private $code2Session_url = 'https://api.weixin.qq.com/sns/jscode2session?';//登录凭证校验地址
    private $err_num = 0;

    private $img_path = '/home/upload/';
    // private $img_path = '/upload/';

    public function __construct(){
        parent::__construct();
        $this->config->load('miniprogram');
        $wx = $this->config->item('WeiXin')['miniprogram'];
        $this->data['appid'] = $wx['appid'];
        $this->data['secret'] = $wx['secret'];
    }

    /*
    发送微信小程序服务器数据
        $url 微信接口地址
        $query 参数（query字符串或数组）
    */
    private function send_wx($url,$query=''){
        $url = trim($url,'?');
        if (is_array($query)) {
            $url .= '?'.http_build_query($query);
        }else{
            $url .= $query;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            ),
        ));

        $res = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err)  exit( "cURL Error #:" . $err );

        $res = json_decode($res,true);
        //返回正确
        if(!@$res['errcode']) return $res;

        //返回错误
        if($res['errcode']==-1){
            sleep(1);
            $this->err_num++;
            if($this->err_num<3){
                $r = $this->send_wx($url,$query);
            }else{
                exit(json_encode(['ret'=>false,'msg'=>'微信系统繁忙']));
            }
        }else{
            exit(json_encode(['ret'=>false,'msg'=>$res['errmsg']]));
        }
    }

    //判断是否首次登录 是否绑定过openid
    public function is_bind(){
        header('SESSIONID:'.session_id());        
        
        //获取openid、session_key
        $code = $this->input->get_post('code',true);
        $r = $this->code_get_openid($code);
        $_SESSION['wx']['session_key'] = $r['session_key'];
        $_SESSION['wx']['openid'] = $r['openid'];

        //判断是否绑定openid
        $this->load->model('Wesing_merchant_model','wm');
        $res = $this->wm->get_by_openid($r['openid']);
        if($res==[]){
            //未绑定
            echo json_encode(['bind' => false,'powers' => [],'session'=>$this->get_session()]);
        }else{
            //已绑定
            $this->construct_session($res);
            $powers = $this->getRolePower();
            echo json_encode(['bind' => true,'powers' => $powers,'session'=>$this->get_session()]);
            // echo $this->db->last_query();
        }
    }

    //解绑openid
    public function unbind(){
        $id = @$_SESSION['fms_id'];
        if(!$id) exit(json_encode(['ret'=>true]));
        $this->load->model('Wesing_merchant_model','wm');
        $res = $this->wm->update_openid(null,$id);
        if($res){
            // session_unset();
            // session_destroy();
            $this->destruct_session();
            echo json_encode(['ret'=>true]);
        }else{
            echo json_encode(['ret'=>false]);
        }
    }

    //code换openid
    private function code_get_openid($code){
        $this->data['js_code'] = $code;
        $this->data['grant_type'] = 'authorization_code';
        return $this->send_wx($this->code2Session_url,$this->data);
    }

    //绑定openid与员工关系
    public function bind(){
        $name = $this->input->post('name',true);
        $password = $this->input->post('password',true);
        if($name=='undefined') $name = null;
        if($password=='undefined') $password = null;
        if(! strlen($name) || ! strlen($password)){
            $this->_response(array('ret'=>false,'msg'=>'账号密码为空'),200);
        }
        $this->load->model('Wesing_merchant_model','wm');
        $adminInfo = $this->wm->get_one_by_name($name,'01');

        if(!$adminInfo)
            $this->_response(array('ret'=>false,'msg'=>'账号密码错误'),200);

        $adminPass = $adminInfo['usermm'];
        $salt = $adminInfo['salt'];
        //echo md5($uname.$salt.$upass);
        if(md5($name.$salt.$password)!=$adminPass)
            $this->_response(array('ret'=>false,'msg'=>'账号密码错误'),200);

        //判断是非存储相同openid
        if(isset($_SESSION['wx']) && $adminInfo['openid']!=$_SESSION['wx']['openid']){
            $this->wm->update_openid($_SESSION['wx']['openid'],$adminInfo['id']);
            $adminInfo['openid'] = $_SESSION['wx']['openid'];
        }

        //构成session
        $this->construct_session($adminInfo);
        $powers = $this->getRolePower();
        $this->_response(['ret'=>true,'msg'=>'用户登录成功','bind'=>true,'powers' => $powers,'session'=>$this->get_session()],200);
    }

    //构成session
    private function construct_session($adminInfo){
        unset($adminInfo['upass']);
        unset($adminInfo['usermm']);
        unset($adminInfo['salt']);
        unset($adminInfo['openid']);
        $adminInfo['uname']=$adminInfo['username'];
        foreach ($adminInfo as $_key=>$_val){
            $_SESSION['fms_'.$_key] = $_val;
        }
        $_SESSION['login_time'] = $_SESSION['check_time'] = date('Y-m-d H:i:s', time());
    }

    //销毁session
    private function destruct_session(){
        foreach ($_SESSION as $_key=>$_val){
            if($_key!='wx') unset($_SESSION[$_key]);
        }
    }

    //传递前端session
    private function get_session(){
        $department = null;
        if(@$_SESSION['fms_departmentid']){
            $this->load->model('Department_model','dm');
            $department = $this->dm->get_parent_name($_SESSION['fms_departmentid']);
            // var_dump($department);
            array_shift($department);
            $department = join(array_column($department, 'department_name'),'  ');
        }
        return [
            'fms_department_name' => $department,
            'fms_less_role_name' => @$_SESSION['fms_less_role_name'],
            'fms_role_name' => @$_SESSION['fms_role_name'],
            'fms_uname' => @$_SESSION['fms_uname'],
            'login_time' => @$_SESSION['login_time'],
            'fms_username' => @$_SESSION['fms_username'],
            // '' => $_SESSION[''],
        ];
    }

    //获取当前角色所有小程序权限
    private function getRolePower(){
        $this->load->model('Sys_model','sys');
        $arr = explode(',',$_SESSION['fms_less_role_id']);
        array_push($arr, $_SESSION['fms_userrole']);
        return $this->sys->get_role_mini_powers($arr);
    }

    // public function logout()
    // {
    //     foreach ($_SESSION as $_key =>$_val){
    //         if(preg_match('/^fms/',$_key)) unset($_SESSION[$_key]);
    //     }
    //     header("Location: ".site_url('Auth/login'));
    // }

    // public function checkPass()
    // {
    //     $uname = $this->input->get('uname',true);
    //     $upass = $this->input->get('upass',true);
    //     if(!$uname || !$upass){
    //         $this->_response(array('code'=>403,'info'=>'账号或者密码不能为空'),200);
    //     }

    //     //修改by奚晓俊 开始----------------
    //         // $this->load->model('merchant_model','mers');
    //         // $adminInfo = $this->mers->getByField('userid',$uname);

    //         //增加roel_name Merchant_model读不了？？？
    //         // $this->load->model('WorkLog_model','wl');
    //         //保持用userid代替username？？？
    //         // $adminInfo = $this->wl->getUser(['userid'=>$uname]);
    //         $this->load->model('Wesing_merchant_model','wm');
    //         $adminInfo = $this->wm->get_one_by_name($uname);
    //         // if ($adminInfo) $adminInfo = $adminInfo[0];

    //     //修改by奚晓俊 结束----------------
    //     if(!$adminInfo)
    //         $this->_response(array('code'=>401,'info'=>'账号或者密码错误'),200);

    //     $adminPass = $adminInfo['usermm'];
    //     $salt = $adminInfo['salt'];
    //     //echo md5($uname.$salt.$upass);
    //     if(md5($uname.$salt.$upass)!=$adminPass)
    //         $this->_response(array('code'=>402,'info'=>'账号或者密码错误'),200);

    //     unset($adminInfo['upass']);
    //     $adminInfo['uname']=$adminInfo['username'];
    //     foreach ($adminInfo as $_key=>$_val){
    //         $_SESSION['fms_'.$_key] = $_val;
    //     }
    //     $_SESSION['login_time'] = date('Y-m-d H:i:s', time());
    //     $this->_response(array('code'=>200,'info'=>'用户登录成功'),200);
    // }

    protected function _response(array $info,$httpCode = 200)
    {
        $httpCode = (int)$httpCode;
        http_response_code($httpCode);
        //logmsg(json_encode($info));
        echo json_encode($info);
        exit;
    }

    public function getImg(){
        // ob_start();
        ob_clean();
        $name = $this->input->get('name',true);
        if(!is_readable($this->img_path.$name)){
            http_response_code(404);
            exit();
        }

        header("Content-Disposition: attachment; filename='"+$name+"'");
        echo file_get_contents($this->img_path.$name);
    }
}
