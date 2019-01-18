<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//判断当前角色对一方法或方法参数权限 返回1或0
if ( ! function_exists('checkRolePower')){
	function checkRolePower($class,$method,$detail=[],$dir=''){
        $admin_role = 1;
        $roleId = $_SESSION['fms_userrole'];
        // $admin_role = 1;
        $loginClass = 'Auth';//程序放开登陆口
        $loginMethod = ['login','logout','checkPass'];//程序放开登陆口

        //跳过角色1
        if($roleId==$admin_role) return 1;

        //登录口不受控
        if($class==$loginClass && in_array($method, $loginMethod)) return 1;

		$CI =& get_instance();
		if (!isset($CI->rolePower))
			$CI->rolePower = [];

        $dir = trim(strtolower($dir),'/');
		$class = ucfirst($class);
        $method = strtolower($method);

        //查询方法权限
        if ($detail===[]) {
            $str = $dir.'@'.$class.'@'.$method.'@'.$roleId;
            if(!isset($CI->rolePower[$str])){
                $CI->load->model("Sys_model", "Sys");
                $CI->rolePower[$str] = $CI->Sys->get_role_power($dir,$class,$method,$roleId);
            }
            return $CI->rolePower[$str];

        //查询方法参数
        }else{
            $str = $dir.'@'.$class.'@'.$method.'@'.$roleId.'@detail';
            if(!isset($CI->rolePower[$str])){
                $CI->load->model("Sys_model", "Sys");
                $CI->rolePower[$str] = $CI->Sys->get_role_detail_power($dir,$class,$method,$roleId);
            }
            if(array_diff($detail, $CI->rolePower[$str])===[]){
                return 1;
            }else{
                return 0;
            }
        }
	}
}

//获取当前角色对一方法拥有的参数权限 返回数组
if ( ! function_exists('getRolePowerDetails')){
    function getRolePowerDetails($class,$method,$dir=''){
        $admin_role = 1;
        $roleId = $_SESSION['fms_userrole'];
        // $admin_role = 1;
        // $loginClass = 'Auth';//程序放开登陆口
        // $loginMethod = ['login','logout','checkPass'];//程序放开登陆口
        $dir = trim(strtolower($dir),'/');
        $class = ucfirst($class);
        $method = strtolower($method);
        $str = $dir.'@'.$class.'@'.$method.'@'.$roleId.'@detail';

        $CI =& get_instance();
        if (!isset($CI->rolePower))
            $CI->rolePower = [];

        if(!isset($CI->rolePower[$str])){
            $CI->load->model("Sys_model", "Sys");
            //角色1取全部
            if($roleId==$admin_role) {
                $CI->rolePower[$str] = $CI->Sys->get_role_detail_power($dir,$class,$method);

            //非角色1取拥有权限
            }else{
                $CI->rolePower[$str] = $CI->Sys->get_role_detail_power($dir,$class,$method,$roleId);
            }
        }
        return $CI->rolePower[$str];
    }
}

//验证当前方法参数组权限
if ( ! function_exists('checkDetails')){
    function checkDetails($details=[],$is_exit=false){
        $CI = &get_instance();
        $admin_role = 1;
        $roleId = $_SESSION['fms_userrole'];

        //跳过角色1
        if($roleId==$admin_role){
            $diff_arr = [];
        }else{
            $diff_arr = array_diff($details,$CI->rolePowerDetails);
        }

        //全部通过
        if($diff_arr==[]){
            $ret['ret'] = true;
            $ret['ok_details'] = $details;
            $ret['no_details'] = [];

        //未全部通过
        }else{
            $ret['ret'] = false;
            $CI->load->model("Sys_model", "Sys");
            $names = $CI->Sys->get_class_method(
                ['name','detail'],
                [
                    'dir' => trim(strtolower($this->CI->router->fetch_directory()),'/'),
                    'class' => strtolower($CI->router->fetch_class()),
                    'method' => strtolower($CI->router->fetch_method()),
                ],
                $diff_arr
            );
            $names = array_column($names,'name','detail');

            //exit
            if($is_exit){
                //获取无参数权限的中文名称

                $ret['info'] = '';
                foreach ($diff_arr as $key => $val) {
                    if(isset($names[$val])){
                        $ret['info'] .= '没有'.$names[$val].'权限<br>';
                    }else{
                        $ret['info'] .= '没有'.$val.'权限<br>';
                    }
                }
                http_response_code(403);
                exit(json_encode($ret));

            //return
            }else{
                $ret['no_details'] = $diff_arr;
                $ret['no_details_name'] = $names;
                $ret['ok_details'] = array_diff($details,$diff_arr);
            }
        }
        return $ret;
    }
}

//添加池子样本 同步添加权限参数
if ( ! function_exists('poolSampleAddDetail')){
    function poolSampleAddDetail($name,$detail,$methods=[]){
        //默认需要添加参数权限的方法
        if($methods==[])
            $methods = [
                // ['class'=>'Jigou','method'=>'list_jigou'],
            ];

        $CI = &get_instance();
        $CI->load->model("Sys_model",'sys');

        return $CI->sys->pool_sample_add_detail($name,$detail,$methods);
    }
}

//编辑池子样本 同步修改权限参数名称
if ( ! function_exists('poolSampleEditDetail')){
    function poolSampleEditDetail($name,$detail,$methods=[]){
        //默认需要添加参数权限的方法
        if($methods==[])
            $methods = [
                // ['class'=>'Jigou','method'=>'list_jigou'],
            ];

        $CI = &get_instance();
        $CI->load->model("Sys_model",'sys');

        return $CI->sys->pool_sample_edit_detail($name,$detail,$methods);
    }
}

//假删除池子样本 同步删除权限参数权限绑定
if ( ! function_exists('poolSampleDelDetail')){
    function poolSampleDelDetail($name,$detail){
        $CI = &get_instance();
        $CI->load->model("Sys_model",'sys');

        return $CI->sys->pool_sample_del_detail($name,$detail);
    }
}







