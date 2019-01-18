<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 公共方法-工作日志管理
 * @filename worklog_helper.php
 * @author xixiaojun
 * @version 1.0.0
 * @date 2018-05-30
 */

// /*
// * 完成日志
// */
// if ( ! function_exists('completeWorkLog')){
// 	function completeWorkLog($obj_type,$obj_id,$content,$for_uid=$_SESSION['fms_id'],$from_uid=$_SESSION['fms_id']){
// 		$CI =& get_instance();
// 		$CI->load->model("WorkLog_model", "WL");
// 	}
// }

// /*
// * 新建日志
// */
// if ( ! function_exists('addWorkLog')){
// 	function addWorkLog($obj_type,$obj_id,$content,$for_uid=$_SESSION['fms_id'],$from_uid=$_SESSION['fms_id']){
// 		$CI =& get_instance();
// 		$CI->load->model("WorkLog_model", "WL");
// 	}
// }



/*
* 公共状态改变时，触发添加日志
*/
if ( ! function_exists('addStatusWorkLog')) {
	function addStatusWorkLog($obj_type,$obj_id,$content,$for_uid=[],$from_uid=0){
		$CI = &get_instance();
		$CI->load->model("WorkLog_model", "WL");

		$data = [
		    'obj_type' => $obj_type,
            'obj_id' => $obj_id,
            'wl_content' => $content,//工作内容
            'wl_type' => 1,//类型状态自动添加

            'create_date' => date('Y-m-d H:i:s'),
            'edit_date' => date('Y-m-d H:i:s'),
        ];

        //当前用户
        if($from_uid==0){
			$data['from_rid'] = @$_SESSION['fms_userrole'];
           	$data['from_rname'] = @$_SESSION['fms_role_name'];
           	$data['from_uid'] = @$_SESSION['fms_id'];
           	$data['from_uname'] = @$_SESSION['fms_username'];

        //非当前用户 取数据库
        }else{
        	$arr = $CI->WL->getUser(['id'=>$from_uid]);
        	$data['from_rid'] = $arr['userrole'];
            $data['from_rname'] = $arr['role_name'];
            $data['from_uid'] = $arr['id'];
            $data['from_uname'] = $arr['username'];
        }

        //当前用户
        if($for_uid==[]){
        	$data['for_rid'] = @$_SESSION['fms_userrole'];
            $data['for_rname'] = @$_SESSION['fms_role_name'];
            $data['for_uid'] = @$_SESSION['fms_id'];
            $data['for_uname'] = @$_SESSION['fms_username'];
            $data2 = [$data];
        //非当前用户 取数据库
        }else{
        	$arr = $CI->WL->getUser([],['id',$for_uid]);
        	foreach ($arr as $key => $val) {
        		$data['for_rid'] = $val['userrole'];
	            $data['for_rname'] = $val['role_name'];
	            $data['for_uid'] = $val['id'];
	            $data['for_uname'] = $val['username'];
        		$data2[] = $data;
        	}
        }

        return $CI->WL->addWorkLog($data2);    
	}
}

/*
* 公共状态改变时，触发完成一条工作日志
*/
if ( ! function_exists('completeStatusWorkLog')) {
	function completeStatusWorkLog($obj_type,$obj_id,$complete_uid=0,$note=''){
		$CI = &get_instance();
		$CI->load->model("WorkLog_model", "WL");

		$where = [
		    'obj_type' => $obj_type,
            'obj_id' => $obj_id,
            'wl_type' => 1,//类型为公共状态自动添加
            'complete_status' => 0//完成状态为未完成的
        ];
        $data = [
        	'complete_status' => 1,
        	'complete_date' => date('Y-m-d H:i:s'),
        	'note' => $note,
        ];

        //当前用户
        if($complete_uid==0){
        	$data['complete_uid'] = @$_SESSION['fms_id'];
        }else{
        	$data['complete_uid'] = $complete_uid;
        }

        return $CI->WL->completeWorkLog($data,$where);    
	}
}

/*
* 手动添加我的一条工作日志
*/
// if ( ! function_exists('addMeWorkLog')) {
// 	function addMeWorkLog($wl_content='',$plan_date=null){
// 		$data['wl_content'] = $wl_content;
// 		$data['plan_date'] = $plan_date;

// 		$data['for_rid'] = @$_SESSION['fms_userrole'];
//         $data['for_rname'] = @$_SESSION['fms_role_name'];
//         $data['for_uid'] = @$_SESSION['fms_id'];
//         $data['for_uname'] = @$_SESSION['fms_username'];

// 		$data['from_rid'] = @$_SESSION['fms_userrole'];
//        	$data['from_rname'] = @$_SESSION['fms_role_name'];
//        	$data['from_uid'] = @$_SESSION['fms_id'];
//        	$data['from_uname'] = @$_SESSION['fms_username'];

//        	$data['create_date'] = date('Y-m-d H:i:s');
//        	$data['edit_date'] = date('Y-m-d H:i:s');

// 		$CI =& get_instance();
// 		$CI->load->model("WorkLog_model",'wl');
//         return  $CI->wl->addOneWorkLog($data);
// 	}
// }

/*
* 手动完成我的一条工作日志
*/
if ( ! function_exists('completeMeWorkLog')) {
	function completeMeWorkLog($note='',$wl_id=0){
		$where['wl_id'] = $wl_id;
        $where['for_uid'] = $_SESSION['fms_id'];//自己的工作日志
        $where['wl_type'] = 2;//工作日志类型为手动添加
        $where['complete_status'] = 0;//完成状态为未完成

        $data['note'] = $note;
        $data['complete_status'] = 1;
        $data['edit_date'] = date('Y-m-d H:i:s');
        $data['complete_date'] = date('Y-m-d H:i:s');
        $data['complete_uid'] = $_SESSION['fms_id'];

		$CI =& get_instance();
        $CI->load->model("WorkLog_model",'wl');
        return $CI->wl->completeWorkLog($data,$where);
	}
}


/*
* 手动批量添加工作日志
*/
if ( ! function_exists('addWorkLog')) {
	function addWorkLog($wl_content='',$plan_date=null,$for_uid=[],$from_uid=0){
		$CI = &get_instance();
		$CI->load->model("WorkLog_model", "WL");
		if(!$plan_date) $plan_date=null;
		$data = [
		    //'obj_type' => $obj_type,
            //'obj_id' => $obj_id,
            'wl_content' => $wl_content,//工作内容
            'wl_type' => 2,//类型为手动添加
            'plan_date' => $plan_date,//计划完成时间
            'create_date' => date('Y-m-d H:i:s'),
            'edit_date' => date('Y-m-d H:i:s'),
        ];

        //当前用户
        if($from_uid==0){
			$data['from_rid'] = @$_SESSION['fms_userrole'];
           	$data['from_rname'] = @$_SESSION['fms_role_name'];
           	$data['from_uid'] = @$_SESSION['fms_id'];
           	$data['from_uname'] = @$_SESSION['fms_username'];

        //非当前用户 取数据库
        }else{
        	$arr = $CI->WL->getUser(['id'=>$from_uid]);
        	$data['from_rid'] = $arr['userrole'];
            $data['from_rname'] = $arr['role_name'];
            $data['from_uid'] = $arr['id'];
            $data['from_uname'] = $arr['username'];
        }

        //当前用户
        if($for_uid==[]){
        	$data['for_rid'] = @$_SESSION['fms_userrole'];
            $data['for_rname'] = @$_SESSION['fms_role_name'];
            $data['for_uid'] = @$_SESSION['fms_id'];
            $data['for_uname'] = @$_SESSION['fms_username'];
            $data2 = [$data];
        //非当前用户 取数据库
        }else{
        	$arr = $CI->WL->getUser([],['id',$for_uid]);
        	foreach ($arr as $key => $val) {
        		$data['for_rid'] = $val['userrole'];
	            $data['for_rname'] = $val['role_name'];
	            $data['for_uid'] = $val['id'];
	            $data['for_uname'] = $val['username'];
        		$data2[] = $data;
        	}
        }

        return $CI->WL->addWorkLog($data2);    
	}
}

/*
* 手动批量再次提醒（状态日志或手动日志）
*/
if ( ! function_exists('againWorkLog')) {
	function againWorkLog($wl_id,$wl_content='',$plan_date=null,$for_uid=[],$from_uid=0){
		$CI = &get_instance();
		$CI->load->model("WorkLog_model", "WL");
		//取原日志
		$data = $CI->WL->get_wl_by_id($wl_id);

		if($data['complete_status']==1) return 0;//已完成不得再次提醒

		if(!$plan_date) $plan_date=null;
		$data = [
			'obj_type' => $data['obj_type'],
			'obj_id' => $data['obj_id'],
			'wl_type' => 1,

            'wl_content' => $wl_content,//工作内容
            'plan_date' => $plan_date,//计划完成时间
            'create_date' => date('Y-m-d H:i:s'),
            'edit_date' => date('Y-m-d H:i:s'),
        ];

        //当前用户
        if($from_uid==0){
			$data['from_rid'] = @$_SESSION['fms_userrole'];
           	$data['from_rname'] = @$_SESSION['fms_role_name'];
           	$data['from_uid'] = @$_SESSION['fms_id'];
           	$data['from_uname'] = @$_SESSION['fms_username'];

        //非当前用户 取数据库
        }else{
        	$arr = $CI->WL->getUser(['id'=>$from_uid]);
        	$data['from_rid'] = $arr['userrole'];
            $data['from_rname'] = $arr['role_name'];
            $data['from_uid'] = $arr['id'];
            $data['from_uname'] = $arr['username'];
        }

        //当前用户
        if($for_uid==[]){
        	$data['for_rid'] = @$_SESSION['fms_userrole'];
            $data['for_rname'] = @$_SESSION['fms_role_name'];
            $data['for_uid'] = @$_SESSION['fms_id'];
            $data['for_uname'] = @$_SESSION['fms_username'];
            $data2 = [$data];
        //非当前用户 取数据库
        }else{
        	$arr = $CI->WL->getUser([],['id',$for_uid]);
        	foreach ($arr as $key => $val) {
        		$data['for_rid'] = $val['userrole'];
	            $data['for_rname'] = $val['role_name'];
	            $data['for_uid'] = $val['id'];
	            $data['for_uname'] = $val['username'];
        		$data2[] = $data;
        	}
        }

        return $CI->WL->addWorkLog($data2);    
	}
}



