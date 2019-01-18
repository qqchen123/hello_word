<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 公共方法-状态管理
 * @filename publicstatus_helper.php
 * @author xixiaojun
 * @version 1.0.0
 * @date 2018-07-11
 */

	$CI =& get_instance();
	$CI->statusArr = [
		//录入阶段 0<=status<10 可修改可报审
		1 => '待录入完成',//->初审
		3 => '开放修改',//->初审
		5 => '初审驳回',//->初审
		7 => '复审驳回',//->初审
		9 => '审核驳回',//->审核(一级审核制)
		//审核阶段 10<=status<20
		15 => '已录入待初审',//->复审
		17 => '已初审待复审',//->审核完成
		19 => '已录入待审核',//->审核完成(一级审核制)
		//有效阶段 20<=status<30
		20 => '审核完成',
		23 => '申请修改',//->开放修改
		//停用
		40 => '停用',//->初审
	];

	$CI->statusColor = [
		1 => '#d9edee',//待录入完成=>绿
		3 => '#d9edee',//申请修改=>绿
		5 => '#d9edee',//初审驳回=>绿
		7 => '#d9edee',//复审驳回=>绿
		9 => '#d9edee',//审核驳回=>绿
		//审核阶段
		15 => '#fdf19f',//已录入待初审=>蓝
		17 => '#fdf19f',//已初审待复审=>黄
		19 => '#fdf19f',//已初审待复审=>黄
		//有效阶段
		20 => '',//审核完成=>白
		23 => '',//申请修改=>黄
		//停用
		40 => '#fad2da',//停用=>淡红
	];

	$CI->statusAction = [
		//录入阶段 0<=status<10 可修改可报审
		1 => '新增',//->初审
		3 => '开放修改',//->初审
		5 => '初审驳回',//->初审
		7 => '复审驳回',//->初审
		9 => '审核驳回',//->审核（一级审核制）
		//审核阶段 10<=status<20
		15 => '报审',//->复审
		17 => '初审通过',//->审核完成
		19 => '审核通过',//->审核完成(一级审核制)
		//有效阶段 20<=status<30
		20 => '复审通过',
		23 => '申请修改',//->开放修改
		//停用
		40 => '停用',//->初审
	];

	//审核制度 一级or二级(默认一级审核)
	$CI->shen_he_type = 1;
	//对象id字段名称
	$CI->id_field = 'row.id';

	//参数权限字段名称
	$CI->detail_field = 'row.pool_sample_id';

	//对象状态字段名称
	$CI->status_field = 'row.obj_status';

	//对象默认控制器
	$CI->default_controller = get_class($CI);

	//当前对象类型
	$CI->obj_type = '';

	//当前对象类型中文名称
	$CI->obj_type_cn_name = '';

	//状体按钮默认设置
	$CI->sbtn_option = (object)[
		//编辑
		'edit'=>(object)[
			//判定状态条件是否显示
				'if_status'=>'<10',
			//判定权限
				'class'=> &$CI->default_controller,//访问后端控制器
				'method'=>'edit',//访问后端方法
			//按钮class
				'html_class'=>'btn btn-primary btn-xs p310',
			//按钮访问地址
				'href'=>'javascript:void(0)',
			//按钮点击事件
				'click_fun'=>'edit',
			//按钮点击事件参数
				'click_parame'=> [&$CI->id_field],
			//按钮文字
				'btn_str'=>'编辑'
		],
		//报审
		'baoShen'=>(object)[
			'if_status'=>'<10',
			'class'=> &$CI->default_controller,
			'method'=>'baoShen',
			'html_class'=>'btn btn-primary btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'baoShen',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'报审'
		],
		//初审通过（二级审核制）
		'guoChuShen'=>(object)[
			'if_status'=>'==15',
			'class'=> &$CI->default_controller,
			'method'=>'guoChuShen',
			'html_class'=>'btn btn-success btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'guoChuShen',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'初审通过'
		],
		//初审驳回（二级审核制）
		'backChuShen'=>(object)[
			'if_status'=>'==15',
			'class'=> &$CI->default_controller,
			'method'=>'backChuShen',
			'html_class'=>'btn btn-danger btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'backChuShen',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'初审驳回'
		],
		//复审通过（二级审核制）
		'guoFuShen'=>(object)[
			'if_status'=>'==17',
			'class'=> &$CI->default_controller,
			'method'=>'guoFuShen',
			'html_class'=>'btn btn-success btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'guoFuShen',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'复审通过'
		],
		//复审驳回（二级审核制）
		'backFuShen'=>(object)[
			'if_status'=>'==17',
			'class'=> &$CI->default_controller,
			'method'=>'backFuShen',
			'html_class'=>'btn btn-danger btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'backFuShen',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'复审驳回'
		],
		//审核通过（一级审核制）
		'guoShen'=>(object)[
			'if_status'=>'==19',
			'class'=> &$CI->default_controller,
			'method'=>'guoShen',
			'html_class'=>'btn btn-success btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'guoShen',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'审核通过'
		],
		//审核驳回（一级审核制）
		'backShen'=>(object)[
			'if_status'=>'==19',
			'class'=> &$CI->default_controller,
			'method'=>'backShen',
			'html_class'=>'btn btn-danger btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'backShen',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'审核驳回'
		],
		//停用
		'stop'=>(object)[
			'if_status'=>'==20',
			'class'=> &$CI->default_controller,
			'method'=>'stop',
			'html_class'=>'btn btn-danger btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'stop',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'停用'
		],
		//申请修改
		'pleaseEdit'=>(object)[
			'if_status'=>'==20',
			'class'=> &$CI->default_controller,
			'method'=>'pleaseEdit',
			'html_class'=>'btn btn-danger btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'pleaseEdit',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'申请修改'
		],
		//批准修改申请
		'yesEdit'=>(object)[
			'if_status'=>'==23',
			'class'=> &$CI->default_controller,
			'method'=>'yesEdit',
			'html_class'=>'btn btn-success btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'yesEdit',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'批准修改申请'
		],
		//驳回修改申请
		'noEdit'=>(object)[
			'if_status'=>'==23',
			'class'=> &$CI->default_controller,
			'method'=>'noEdit',
			'html_class'=>'btn btn-danger btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'noEdit',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'驳回修改申请'
		],
		//启用
		'start'=>(object)[
			'if_status'=>'==40',
			'class'=> &$CI->default_controller,
			'method'=>'start',
			'html_class'=>'btn btn-danger btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'start',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'启用'
		],
		//流程信息
		'history'=>(object)[
			'if_status'=>'>-1',
			'class'=> &$CI->default_controller,
			'method'=>'history',
			'html_class'=>'btn btn-primary btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'history',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'流程信息'
		],
		//手动提醒
		'remind'=>(object)[
			'if_status'=>'>0',
			'class'=> &$CI->default_controller,
			'method'=>'remind',
			'html_class'=>'btn btn-primary btn-xs p310',
			'href'=>'javascript:void(0)',
			'click_fun'=>'remind',
			'click_parame'=> [&$CI->id_field],
			'btn_str'=>'提醒'
		],
	];

/*
* 新建对象时，新建一条初始状态
*/
if ( ! function_exists('addStatus')){
	function addStatus($obj_type,$obj_id,$obj_status_info=''){
		$CI = &get_instance();
		$tmp = $CI->statusArr[1];
		if($obj_status_info) $tmp .= "：$obj_status_info";

		$data = [
			'role_id' => @$_SESSION['fms_userrole'],
            'role_name' => @$_SESSION['fms_role_name'],
            'admin_id' => @$_SESSION['fms_id'],
            'admin_name' => @$_SESSION['fms_username'],

            'status_edit_time' => date('Y-m-d H:i:s'),

            'obj_type' => $obj_type,
            'obj_id' => $obj_id,
            'obj_status' => 1,
            'obj_status_info' => $tmp
        ];

        $CI->load->model("PublicStatus_model", "PS");
        $bool = $CI->PS->addStatus($data);
        if($bool){
        	$CI->load->helper('workLog');
        	$content = $CI->statusAction[1].getStatusObjType($obj_type);
        	addStatusWorkLog($obj_type,$obj_id,$content);
        }
        return $bool;
	}
}

/*
* 改变对象状态
*/
if ( ! function_exists('editStatus')){
	function editStatus($obj_type,$obj_id,$obj_status,$obj_status_info='',$old_status=''){
		$CI = &get_instance();
		$tmp = $CI->statusArr[$obj_status];
		if($obj_status_info) $tmp .= "：$obj_status_info";

		$data = [
			'role_id' => @$_SESSION['fms_userrole'],
            'role_name' => @$_SESSION['fms_role_name'],
            'admin_id' => @$_SESSION['fms_id'],
            'admin_name' => @$_SESSION['fms_username'],

            'status_edit_time' => date('Y-m-d H:i:s'),

            'obj_type' => $obj_type,
            'obj_id' => $obj_id,
            'obj_status' => $obj_status,
            'obj_status_info' => $tmp
        ];

        if($old_status)
        	$where = 'obj_status '.$old_status;

        $CI->load->model("PublicStatus_model", "PS");
        return $CI->PS->editStatus($data,$where);
	}
}

/*
* 报审对象 （一级or二级）
*/
if ( ! function_exists('baoShenStatus')){
	function baoShenStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$CI = &get_instance();
		if($CI->shen_he_type==1){
			//一级审核
			$status = 19;
		}else{
			//二级审核
			$status = 15;
		}
		$bool = editStatus($obj_type,$obj_id,$status,$obj_status_info,'<10');
		if($bool){
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->baoShen->btn_str);
			if($for_admins){
				//取obj_type中文名.操作 组成日志内容
				$content = getStatusObjType($obj_type).$CI->sbtn_option->baoShen->btn_str;
				addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
			}
		}
		return $bool;
	}
}

/*
* 初审通过 （二级审核）
*/
if ( ! function_exists('guoChuShenStatus')){
	function guoChuShenStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,17,$obj_status_info,'=15');
		if($bool){
			$CI = &get_instance();
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->guoChuShen->btn_str);
			if($for_admins){
				//取obj_type中文名.操作 组成日志内容
				$content = getStatusObjType($obj_type).$CI->sbtn_option->guoChuShen->btn_str;
				addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
			}
		}
		return $bool;
	}
}

/*
* 初审驳回 （二级审核）
*/
if ( ! function_exists('backChuShenStatus')){
	function backChuShenStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,5,$obj_status_info,'=15');
		if($bool){
			$CI = &get_instance();
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->backChuShen->btn_str);
			//寻找之前节点人
			$admin_arr = getStatusUser($obj_type,$obj_id,[15],1);
			//$admin_arr = array_column($admin_arr, 'admin_id');
			if(!$for_admins) $for_admins = [];
			foreach ($admin_arr as $key => $val) {
				if(!in_array($val['admin_id'], $for_admins)) $for_admins[]=$val['admin_id'];
			}
			//取obj_type中文名.操作 组成日志内容
			$content = getStatusObjType($obj_type).$CI->sbtn_option->backChuShen->btn_str;
			addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
		}
		return $bool;
	}
}

/*
* 复审通过 （二级审核）
*/
if ( ! function_exists('guoFuShenStatus')){
	function guoFuShenStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,20,$obj_status_info,'=17');
		if($bool){
			$CI = &get_instance();
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->guoFuShen->btn_str);
			if($for_admins){
				//取obj_type中文名.操作 组成日志内容
				$content = getStatusObjType($obj_type).$CI->sbtn_option->guoFuShen->btn_str;
				addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
			}
		}
		return $bool;
	}
}

/*
* 复审驳回 （二级审核）
*/
if ( ! function_exists('backFuShenStatus')){
	function backFuShenStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,7,$obj_status_info,'=17');
		if($bool){
			$CI = &get_instance();
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->backFuShen->btn_str);

			//寻找之前节点人
			$admin_arr = getStatusUser($obj_type,$obj_id,[15,17],2);
			//$admin_arr = array_column($admin_arr, 'admin_id');
			if(!$for_admins) $for_admins = [];
			foreach ($admin_arr as $key => $val) {
				if(!in_array($val['admin_id'], $for_admins)) $for_admins[]=$val['admin_id'];
			}
			//取obj_type中文名.操作 组成日志内容
			$content = getStatusObjType($obj_type).$CI->sbtn_option->backFuShen->btn_str;
			addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
		}
		return $bool;
	}
}

/*
* 审核通过 （一级审核）
*/
if ( ! function_exists('guoShenStatus')){
	function guoShenStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,20,$obj_status_info,'=19');
		if($bool){
			$CI = &get_instance();
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->guoShen->btn_str);
			if($for_admins){
				//取obj_type中文名.操作 组成日志内容
				$content = getStatusObjType($obj_type).$CI->sbtn_option->guoShen->btn_str;
				addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
			}
		}
		return $bool;
	}
}

/*
* 审核驳回 （一级审核）
*/
if ( ! function_exists('backShenStatus')){
	function backShenStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,9,$obj_status_info,'=19');
		if($bool){
			$CI = &get_instance();
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->backShen->btn_str);

			//寻找之前节点人
			$admin_arr = getStatusUser($obj_type,$obj_id,[19],1);
			//$admin_arr = array_column($admin_arr, 'admin_id');
			if(!$for_admins) $for_admins = [];
			foreach ($admin_arr as $key => $val) {
				if(!in_array($val['admin_id'], $for_admins)) $for_admins[]=$val['admin_id'];
			}
			//取obj_type中文名.操作 组成日志内容
			$content = getStatusObjType($obj_type).$CI->sbtn_option->backShen->btn_str;
			addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
		}
		return $bool;
	}
}

/*
* 停用 （一级or二级）
*/
if ( ! function_exists('stopStatus')){
	function stopStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,40,$obj_status_info,'=20');
		if($bool){
			if($for_admins){
				$CI = &get_instance();
				$CI->load->helper('workLog');
				//取obj_type中文名.操作 组成日志内容
				$content = getStatusObjType($obj_type).$CI->sbtn_option->stop->btn_str;
				addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
			}
		}
		return $bool;
	}
}

/*
* 启用 （一级or二级）
*/
if ( ! function_exists('startStatus')){
	function startStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$CI = &get_instance();
		if($CI->shen_he_type==1){
			//一级审核
			$status = 19;
		}else{
			//二级审核
			$status = 15;
		}
		$bool = editStatus($obj_type,$obj_id,$status,$obj_status_info,'=40');
		if($bool){
			if($for_admins){
				$CI->load->helper('workLog');
				//取obj_type中文名.操作 组成日志内容
				$content = getStatusObjType($obj_type).$CI->sbtn_option->start->btn_str;
				addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
			}
		}
		return $bool;
	}
}

/*
* 请求修改 （一级or二级）
*/
if ( ! function_exists('pleaseEditStatus')){
	function pleaseEditStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,23,$obj_status_info,'=20');
		if($bool){
			if($for_admins){
				$CI = &get_instance();
				$CI->load->helper('workLog');
				//取obj_type中文名.操作 组成日志内容
				$content = getStatusObjType($obj_type).$CI->sbtn_option->pleaseEdit->btn_str;
				addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
			}
		}
		return $bool;
	}
}

/*
* 批准修改 （一级or二级）
*/
if ( ! function_exists('yesEditStatus')){
	function yesEditStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,3,$obj_status_info,'=23');
		if($bool){
			$CI = &get_instance();
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->yesEdit->btn_str);

			//寻找之前节点人
			$admin_arr = getStatusUser($obj_type,$obj_id,[23],1);
			//$admin_arr = array_column($admin_arr, 'admin_id');
			if(!$for_admins) $for_admins = [];
			foreach ($admin_arr as $key => $val) {
				if(!in_array($val['admin_id'], $for_admins)) $for_admins[]=$val['admin_id'];
			}
			//取obj_type中文名.操作 组成日志内容
			$content = getStatusObjType($obj_type).$CI->sbtn_option->yesEdit->btn_str;
			addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
		}
		return $bool;
	}
}

/*
* 驳回修改 （一级or二级）
*/
if ( ! function_exists('noEditStatus')){
	function noEditStatus($obj_type,$obj_id,$obj_status_info='',$for_admins=[]){
		$bool = editStatus($obj_type,$obj_id,20,$obj_status_info,'=23');
		if($bool){
			$CI = &get_instance();
			$CI->load->helper('workLog');
			//完成当前用户该条工作日志
			completeStatusWorkLog($obj_type,$obj_id,0,$CI->sbtn_option->noEdit->btn_str);

			//寻找之前节点人
			$admin_arr = getStatusUser($obj_type,$obj_id,[23],1);
			//$admin_arr = array_column($admin_arr, 'admin_id');
			if(!$for_admins) $for_admins = [];
			foreach ($admin_arr as $key => $val) {
				if(!in_array($val['admin_id'], $for_admins)) $for_admins[]=$val['admin_id'];
			}
			//取obj_type中文名.操作 组成日志内容
			$content = getStatusObjType($obj_type).$CI->sbtn_option->noEdit->btn_str;
			addStatusWorkLog($obj_type,$obj_id,$content,$for_admins);
		}
		return $bool;
	}
}

/*
* 获取指定对象的当前状态
*/
if ( ! function_exists('getOneStatus')){
	function getOneStatus($obj_type,$obj_id){
		$CI =& get_instance();
        $CI->load->model("PublicStatus_model", "PS");
        return $CI->PS->getOneStatus($obj_type,$obj_id);
	}
}

/*
* 判断指定对象的当前状态是否可编辑
*/
if ( ! function_exists('checkOneEditStatus')){
	function checkOneEditStatus($obj_type,$obj_id,$obj_status=null){
		if($obj_status===null){
			$arr = getOneStatus($obj_type,$obj_id);
		}else{
			$arr['obj_status'] = $obj_status;
		}
		
        if ($arr['obj_status']<10){
        	return true;
        }else{
        	return false;
        }
	}
}

/*
* 判断指定对象的当前状态是否可审批
*/
if ( ! function_exists('checkOnePiStatus')){
	function checkOnePiStatus($obj_type,$obj_id,$obj_status=null){
		if($obj_status===null){
			$arr = getOneStatus($obj_type,$obj_id);
		}else{
			$arr['obj_status'] = $obj_status;
		}
        if ($arr['obj_status']>=10 && $arr['obj_status']<20){
        	return true;
        }else{
        	return false;
        }
	}
}

/*
* 判断指定对象的当前状态是否有效
*/
if ( ! function_exists('checkOneOkStatus')){
	function checkOneOkStatus($obj_type,$obj_id,$obj_status=null){
		if($obj_status===null){
			$arr = getOneStatus($obj_type,$obj_id);
		}else{
			$arr['obj_status'] = $obj_status;
		}
        if ($arr['obj_status']>=20 && $arr['obj_status']<30){
        	return true;
        }else{
        	return false;
        }
	}
}

/*
* 获取指定对象的所有历史状态
*/
if ( ! function_exists('historyStatus')){
	function historyStatus($obj_type,$obj_id){
		$CI =& get_instance();
        $CI->load->model("PublicStatus_model", "PS");
        return $CI->PS->getOneHistoryStatus($obj_type,$obj_id);
	}
}

/*
* 手动提醒
*/
if ( ! function_exists('remindStatus')) {
	function remindStatus($obj_type,$obj_id,$remind_admins,$remind_info=''){
		$CI = &get_instance();
		$CI->load->helper('workLog');
		//取obj_type中文名.操作 组成日志内容
		$status = getOneStatus($obj_type,$obj_id);
		if($remind_info==''){
			$content = getStatusObjType($obj_type).$status['obj_status_info'];
		}else{
			$content = getStatusObjType($obj_type).$CI->statusArr[$status['obj_status']].'：'.$remind_info;
		}
		return addStatusWorkLog($obj_type,$obj_id,$content,$remind_admins);
	}
}

/*
* 关联状态组成model语句
*/
if ( ! function_exists('joinStatus')){
	function joinStatus($obj_type,$join_field_name){
        $CI =& get_instance();
        $CI->db->join(
        	"fms_status {$obj_type}_status",
        	"{$obj_type}_status.obj_id = {$join_field_name} and {$obj_type}_status.obj_type = '{$obj_type}'"
        );
	}
}

/*
* 添加数据状态颜色
*/
if ( ! function_exists('showStatusColor')){
	function showStatusColor($rows){
        $CI =& get_instance();
        foreach ($rows as $k => $v) {
        	$rows[$k]['obj_status_color'] =
        		@$CI->statusColor[$v['obj_status']];
        }
        return $rows;
	}
}

/*
* 输出状态及其颜色供select  js组装
*/
// if ( ! function_exists('getStatusForSelect')){
// 	function getStatusForSelect(){
//         $CI =& get_instance();
//         foreach ($CI->statusArr as $k => $v) {
//         	$rs[$k]['obj_id']= $k;
//         	$rs[$k]['obj_status'] = $v;
//         }
//         $arr = [['obj_id'=>'','obj_status'=>'全部']];
//         return array_merge($arr,$rs);
// 	}
// }

/*
* 显示改状态按钮
*/
if ( ! function_exists('showStatusBtn')){
	function showStatusBtn($is_detail=false){
		$CI = &get_instance();
		$CI->load->helper('checkrolepower');
		$html = '';
		if($is_detail) $html = "var detail={};\n";
		$if_detail = '';
		
		//状态按钮
		foreach ($CI->sbtn_option as $k => $v) {
			if($v->class && $v->method && checkRolePower($v->class,$v->method)){

				$str = join("+','+",$v->click_parame);
				$click = '';

				if($is_detail){
					//获取当前角色对一方法拥有的参数权限
					$arr = getRolePowerDetails($v->class,$v->method);
					$arr = json_encode($arr);

					$html .= "detail.{$v->method} = JSON.parse('$arr');\n";
					// $html.= "console.log(detail.{$v->method});";
					$if_detail = " && $.inArray(String($CI->detail_field),detail.{$v->method})>=0 ";
					if($v->click_fun) $click = "onclick=\"{$v->click_fun}('+{$str}+',['+ detail.{$v->method} +'],\'{$v->if_status}\')\" ";
				}else{
					if($v->click_fun) $click = "onclick=\"{$v->click_fun}('+{$str}+')\" ";
				}

				$html .= "if({$CI->status_field}{$v->if_status}{$if_detail}) html += '<a class=\"{$v->html_class}\" href=\"{$v->href}\" {$click}>{$v->btn_str} </a>'+'&nbsp;&nbsp';\n";
				//$html.='console.log($.inArray(String(row.pool_sample_id),detail.baoShen));';
			}
        }
        return $html;
	}
}

/*
* 流程信息按钮
*/
if ( ! function_exists('showHistoryHtml')){
	function showHistoryHtml(){
		$CI =& get_instance();
		$url = $CI->sbtn_option->history->method;
		$statusAction = json_encode($CI->statusAction);
		echo <<<historyHtml
		<!-- 流程信息 -->
	    <div id="status-dlg" style="width:700px;height:400px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="status-dlg-buttons" data-options="modal:true">
	        <table  id="status_tt" class="easyui-datagrid" style="width:100%;height:350px"
	                    data-options="
	                        rownumbers: true,
	                        method: 'get',
	                        toolbar: '#toolbar',
	                        lines: true,
	                        fit: true,
	                        fitColumns: false,
	                        border: false,
	                        columns:status_col_data,
	                        pagination:false,
	                        onSortColum: function (sort,order) {
	                            $('#status_tt').datagrid('reload', {
	                                sort: sort,
	                                order: order
	                        　　});
	                        },
	                        rowStyler:function(index,row){
	                            return 'background-color:'+statusColor[row.obj_status];
	                        },
	                        onBeforeSelect:function(){
	                            return false;
	                        },
	                        remoteSort:false,
	                        ">
	        </table>
	        <script type="text/javascript">
	            var status_col_data = [[
	                {field: 'admin_name', title: '操作用户', width: 100, align:'center', 'sortable':true},
	                {field: 'role_name', title: '用户角色', width: 100,  align:'center', 'sortable':true},
	                {field: 'obj_status', title: '操作', width: 100,  align:'center', 'sortable':true,
	                    formatter: function(value, row, index) {
	                        return statusAction[value];
	                    }
	                },
	                {field: 'obj_status_info', title: '附加信息', width: 150,  align:'center', 'sortable':true,
	                    formatter: function(value, row, index) {
	                        if (row.obj_status==20) value = '<span class="icon-ok" style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>' + value;
	                        if (row.obj_status==40) value = '<span class="icon-cancel" style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>' + value;
	                        return value;
	                    }
	                },
	                {field: 'status_edit_time', title: '操作时间', width: 200, align:'center', 'sortable':true},
	            ]];

	            function history(id){
	                $('#status-dlg').dialog('open').dialog('setTitle','流程信息');
	                $('#status_tt').datagrid({url:'$url?obj_id='+id+'&status_type='+status_type});
	                $('#status_tt').load();
	            }
	            //状态前操作
	            var statusAction = JSON.parse(' $statusAction ');
	        </script>
	    </div>
historyHtml;
	}
}

/*
* 流程信息按钮
*/
if ( ! function_exists('showRemindHtml')){
	function showRemindHtml(){
		$CI =& get_instance();
		$url = $CI->sbtn_option->remind->method;
		echo <<<RemindHtml
	<div id="remind-dlg" style="width:400px;height:320px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="status-dlg-buttons" data-options="modal:true">
        <form id="remindForm" method="post" novalidate>
            <div><input type="hidden" name="obj_id" id="obj_id"></div>
            <div class="fitem">
                <label>提醒对象:</label>
                <input name="remind_admins[]" id="remind_admins" class="easyui-combobox" required="true" style="width:346px"
                    data-options="
                        url: '../PublicMethod/getAdmin',
                        valueField: 'id',
                        textField: 'username',
                        multiple:true,
                        panelHeight:'auto',
                        groupField:'department',
                    "
                >
            </div>
            <div class="fitem">
                <label id="info_label">提醒内容:</label>
                <input name="remind_info" id="remind_info" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,240]" data-options="prompt:'默认提醒内容：为该数据流转的流程附加信息',multiline:true" novalidate="true">
            </div>
            <div id="remind-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doRemind()" style="width:90px">提醒</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#remind-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
        <script type="text/javascript">
            //显示提醒框
            function remind(obj_id){
                $('#remind-dlg').dialog('open').dialog('setTitle','流程提醒');
                $('#remind-dlg #remindForm #obj_id').val(obj_id);
                // $('#remind_tt').datagrid({url:'history?obj_id='+jg_id});
                // $('#remind_tt').load();
            }

            //执行提醒
            function doRemind(){
                $('#remindForm').form('submit', {
                    url: '$url',
                    onSubmit: function() {
                        return $(this).form('enableValidation').form('validate');
                    },
                    dataType: 'json',
                    success: function(result) {
                        var result = eval("(" + result + ")");
                        // console.log(result);
                        if (result.ret == false) {
                            $.messager.show({
                                title: '提示',
                                msg: result.info
                            });
                        } else {
                            $('#remind-dlg').dialog('close');
                            $.messager.show({
                                title: '提示',
                                msg: result.info
                            });
                        }
                    }
                });
            }
        </script>
    </div>
RemindHtml;
	}
}

/*
* 查找最近指定状态的操作人员
*/
if ( ! function_exists('getStatusUser')) {
	function getStatusUser($obj_type,$obj_id,$obj_status_in=[],$limit=0){
		$CI = &get_instance();
		$CI->load->model("PublicStatus_model", "PS");

		$where = [
		    'obj_type' => $obj_type,
            'obj_id' => $obj_id,
        ];

        return $CI->PS->getStatusUser($where,$obj_status_in,$limit);
	}
}

/*
* 获取状态obj_type的中文名称
*/
if ( ! function_exists('getStatusObjType')) {
	function getStatusObjType($obj_type){
		$CI = &get_instance();
		$CI->load->model("PublicStatus_model", "PS");
        return $CI->PS->getStatusObjType($obj_type);
	}
}
