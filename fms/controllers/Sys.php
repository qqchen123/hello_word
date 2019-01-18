<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 权限管理
 * @filename Sys.php
 * @author xixiaojun
 * @version 1.0.0
 * @date 2018-04-20
 */
class Sys extends CI_Controller {
    function __construct(){
        parent::__construct();
        // $this->load->driver('cache', array('adapter' => 'redis'));
        // $this->load->library('session');
        // $this->load->helper(['form','url']);
        // $this->output->enable_profiler(TRUE);
    }
// 控制器管理 开始================================
    /**
     * 输出class_mothod列表
     */
    // public function list_class_method(){
    //     // $this->load->view('sys/sys_header');
    //     $this->load->view('sys/list_class_method');
    // }

    /**
     * 获取所有控制器方法 废弃代码
     */
    public function get_class_method_tree2(){
    	// if (!$this->input->is_ajax_request()) exit();
    	$this->load->model("Sys_model",'sys');
    	$arr = $this->sys->get_class_method([],['detail'=>'']);
    	$tree = [];

        //组class
    	foreach ($arr as $key => $val) {
    		if ($val['parent_id']==0){
                // $val['state'] = 'closed';
    			$tree[$val['class']]=$val;
    			unset($arr[$key]);
    		}
    	}

    	ksort($tree);

        //组method
    	foreach ($arr as $key=>$val) {
            if($val['detail']!=='') continue;
            // $val['state'] = 'closed';
            unset($arr[$key]);

            //组detail
            foreach ($arr as $k=>$v) {
                if ($v['detail']!=='' && $v['parent_id'] == $val['id']) {
                    $val['children'][]=$v;
                    unset($arr[$key]);
                }
            }

    		if (@$tree[$val['class']]!=null) {
    			$tree[$val['class']]['children'][]=$val;
    		}else{
                $tree[] = $val;
            }
    	}

    	echo json_encode(array_values($tree));
    }

    /**
     * 获取所有控制器 or 指定方法 or 指定参数 解决全部加载数据量大js卡死
     */
    public function get_class_method_tree(){
        $this->load->model("Sys_model",'sys');

        $id = $this->input->get('id');

        //获取指定id的方法或参数
        if(isset($id)){
            $arr = $this->sys->get_class_method([],['parent_id'=>$id]);
            if($arr){
                $ids = array_column($arr,'id');
                $son_num = array_column($this->sys->get_son_num($ids),'num','parent_id');
            }

        //获取所有class
        }else{
            $arr = $this->sys->get_class_method([],['method'=>'']);
        }

        foreach ($arr as $key => $val) {
            if ($val['method']=='' || @$son_num[$val['id']]>0){
                $arr[$key]['state'] = 'closed';
            }
        }

        echo json_encode($arr);
    }

    public function get_tree_for_select(){
        // if (!$this->input->is_ajax_request()) exit();
        $this->load->model("Sys_model",'sys');
        $arr = $this->sys->get_class_method(['id','name as text','parent_id'],['detail'=>'']);
        // $tree = $this->list_to_tree($arr);
        $tree = [];
        $refer = [];
        foreach ($arr as $key => $val) {
            $refer[$val['id']] =& $arr[$key];
        }

        foreach ($arr as $key => $val) {
            if ($val['parent_id']==0) {
                $tree[] =& $arr[$key];
            }else{
                if (isset($refer[$val['parent_id']])) {
                    $refer[$val['parent_id']]['children'][] =& $arr[$key];
                }
            }
        }
        echo json_encode($tree);
    }

    /**
     * 添加、修改控制器
     */
    public function do_class(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_rules('id', '', 'integer');
        $this->form_validation->set_rules('name', '名称', 'required|max_length[30]');
    	$this->form_validation->set_rules('dir', '控制器目录', 'max_length[30]');
        $this->form_validation->set_rules('class', '控制器', 'required|max_length[30]|callback_checkUniqueCMD[class,0]',[
            'checkUniqueCMD'=>'该目录下控制器已存在，且必须唯一'
        ]);
        $this->form_validation->set_rules('is_login', '验证登录', "integer|in_list[1,2]");
        $this->form_validation->set_rules('is_sys', '程序类型', 'integer|in_list[1,2]');
        $this->form_validation->set_rules('is_show', 'ACL列表显示', 'integer|in_list[1,2]');
        $this->form_validation->set_rules('is_loged', '记录日志', 'integer|in_list[1,2]');

        if ($this->form_validation->run()){
        	$where['id'] = $this->input->post('id',TRUE);
            $data['name'] = trim($this->input->post('name',TRUE));
        	$data['dir'] = trim($this->input->post('dir',TRUE));
        	$data['class'] = ucfirst(trim($this->input->post('class',TRUE)));
        	$data['is_login'] = $this->input->post('is_login',TRUE);
        	$data['is_sys'] = $this->input->post('is_sys',TRUE);
        	$data['is_show'] = $this->input->post('is_show',TRUE);
        	$data['is_loged'] = $this->input->post('is_loged',TRUE);
        	$data['parent_id'] = 0;
        	$data['detail'] = '';
        	$data['method'] = '';
            $data['sort'] = 0;
        	$this->load->model("Sys_model",'sys');
        	if ($where['id']) {
        		$ret['ret'] = $this->sys->edit_class_method($data,$where);
        		$ret['info'] = $ret['ret']?'控制器修改成功':'控制器修改失败';
        	}else{
        		$ret['ret'] = $this->sys->add_class_method($data);
        		$ret['info'] = $ret['ret']?'控制器添加成功':'控制器添加失败';
        	}

        }else{
        	$ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
        	$ret['info'] = validation_errors();
        }
        echo json_encode($ret,true);
    }

    /**
     * 获取所有class
     */
    public function get_class(){
    	// if (!$this->input->is_ajax_request()) exit();
    	$this->load->model("Sys_model",'sys');
    	$ret = $this->sys->get_class_method([],['parent_id'=>0]);
    	echo json_encode($ret);
    }

    /**
     * 获取指定class的方法（未注册、能访问、非静态）
     */
    public function get_method_by_class(){
		$id = $this->input->get('id',TRUE);
		//数据库已有该class的方法
    	$this->load->model("Sys_model",'sys');
		$dbMethod = $this->sys->get_class_method(
			['id','dir','class','method'],['parent_id'=>$id]
		);

		//数据库无该class的方法 再去获取class名称
		if ($dbMethod===[]) {
			$ret = $this->sys->get_class_method(
				['id','dir','class','method'],['id'=>$id]
			);
			if(!$ret) exit('[]');
            $dir = $ret[0]['dir'];
			$className=$ret[0]['class'];
		}else{
            $dir = $dbMethod[0]['dir'];
			$className=$dbMethod[0]['class'];
		}

		//引入类文件
		if ($className!= __CLASS__) {
		 	(@include_once APPPATH.'controllers/'.$dir.'/'.$className.'.php') or exit('[]');
		};

		$class = new ReflectionClass($className);
		$arr = $class->getMethods();

		//过滤输出方法
		$classArr = [];
		$dbMethod = array_column($dbMethod, 'method');
		foreach ($arr as $k => $v) {
			//去除ci方法、_开头方法、数据库已有方法
			if ($v->class==$className && $v->name{0}!='_' && !in_array($v->name,$dbMethod)) {
				//保留public且非静态方法
				$method = new ReflectionMethod($className,$v->name);
				if ($method->isPublic() && !$method->isStatic()) 
                    $classArr[] = ['method'=>$v->name];
			}
		}

		echo json_encode($classArr);
    }

    //验证控制器、方法、参数唯一性
    public function checkUniqueCMD($obj,$obj_type='class,0'){
        $arr = explode(',',$obj_type);
        $obj_type = $arr[0];
        $parent_id = $arr[1];
        $id = (@$_POST['id'])?$_POST['id']:null;
        if($parent_id!='0') $parent_id = $_POST[$parent_id];
        $this->load->model("Sys_model",'sys');
        return ($this->sys->getCMDNum($obj,$obj_type,$parent_id,$id)==0);
    }

    /**
     * 添加、修改方法
     */
    public function do_method(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_rules('id', '', 'integer');
    	$this->form_validation->set_rules('name', '名称', 'required|max_length[30]');
        $this->form_validation->set_rules('class_id', '控制器', 'required|integer');
        $this->form_validation->set_rules('method', '方法', 'required|max_length[30]|callback_checkUniqueCMD[method,class_id]',[
            'checkUniqueCMD'=>'该方法已存在，且必须唯一'
        ]);
        $this->form_validation->set_rules('is_login', '验证登录', "integer|in_list[1,2]");
        $this->form_validation->set_rules('is_sys', '程序类型', 'integer|in_list[1,2]');
        $this->form_validation->set_rules('is_show', 'ACL列表显示', 'integer|in_list[1,2]');
        $this->form_validation->set_rules('is_loged', '记录日志', 'integer|in_list[1,2]');

        if ($this->form_validation->run()){
        	$where['id'] = $this->input->post('id',TRUE);
        	$data['name'] = trim($this->input->post('name',TRUE));
        	$data['class_id'] = $this->input->post('class_id',TRUE);
        	$data['method'] = trim($this->input->post('method',TRUE));
        	$data['is_login'] = $this->input->post('is_login',TRUE);
        	$data['is_sys'] = $this->input->post('is_sys',TRUE);
        	$data['is_show'] = $this->input->post('is_show',TRUE);
        	$data['is_loged'] = $this->input->post('is_loged',TRUE);
        	$data['detail'] = '';
        	$data['parent_id'] = 0;
        	$this->load->model("Sys_model",'sys');
        	if ($where['id']) {
        		$ret['ret'] = $this->sys->edit_class_method($data,$where);
        		$ret['info'] = $ret['ret']?'方法修改成功':'方法修改失败';
        	}else{
        		$ret['ret'] = $this->sys->add_class_method($data);
        		$ret['info'] = $ret['ret']?'方法添加成功':'方法添加失败';
        	}

        }else{
        	$ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
        	$ret['info'] = validation_errors();
        }
        echo json_encode($ret);
    }

	/**
     * 删除控制器、方法
     */
    public function del_class_method(){
    	$this->load->library('form_validation');
    	$this->form_validation->set_rules('id', '', 'is_natural_no_zero|required');
    	if (!$this->form_validation->run()) exit('');
        $id = $this->input->post('id',TRUE);
        $this->load->model("Sys_model",'sys');
        $del_num = $this->sys->del_class_method($id);
        // $ret['ret'] = ($del_num>0)?true:false;
        $ret['ret'] = $del_num;
        $ret['info'] = $ret['ret']?"成功删除{$del_num}条记录":"删除失败";
        echo json_encode($ret);
    }

    /**
     * 排序方法
     */
    public function sort_method(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', '', 'integer|required');
        $this->form_validation->set_rules('sort_id', '', 'integer|required');
        $this->form_validation->set_rules('point', '', 'integer|required|in_list[1,2]');

        if (!$this->form_validation->run()) exit('');
        $id = $this->input->post('id',TRUE);
        $sort_id = $this->input->post('sort_id',TRUE);
        $point = $this->input->post('point',TRUE);

        $this->load->model("Sys_model",'sys');
        $r = $this->sys->sort_method($id,$sort_id,$point);
        echo json_encode($r);
    }

    /**
     * 添加、修改参数
     */
    public function do_detail(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', '', 'integer');
        $this->form_validation->set_rules('method_id', '', 'integer|required');
        $this->form_validation->set_rules('name', '参数名称', 'required|max_length[30]');
        $this->form_validation->set_rules('detail', '参数字段', 'required|max_length[20]|callback_checkUniqueCMD[detail,method_id]',[
            'checkUniqueCMD'=>'该参数已存在，且必须唯一'
        ]);

        if ($this->form_validation->run()){
            $id = $this->input->post('id',TRUE);
            $method_id = $this->input->post('method_id',TRUE);
            $name = $this->input->post('name',TRUE);
            $detail = $this->input->post('detail',TRUE);
            
            $this->load->model("Sys_model",'sys');
            //编辑
            if($id){
                $ret['ret'] = $this->sys->edit_detail($id,$name,$detail);
                $ret['info'] = ($ret['ret'])?'参数修改成功！':'参数修改失败！';

            //新增
            }else{
                $ret['ret'] = $this->sys->add_detail(['id'=>$method_id],$name,$detail);
                $ret['info'] = ($ret['ret'])?'参数添加成功！':'参数添加失败！';
            }

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }
        echo json_encode($ret);
    }


// 控制器管理 结束================================

// 角色管理 开始================================
    /**
     * 输出角色方法列表
     */
    public function list_role_method(){
        $this->load->view('sys/list_role_method');
    }

    /**
     * 获取角色
     */ 
    public function get_role(){
        // $this->load->model("Priv_model",'Priv');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_id', '', 'integer');
        if (!$this->form_validation->run()) exit('');

        $role_id = $this->input->post('role_id',TRUE);
        $this->load->model("Sys_model",'Sys');
        //取指定id
        if ($role_id) {
            $role = $this->Sys->get_role(['role_id'=>$role_id])[0];

        //取全部
        }else{
            $arr = $this->Sys->get_role();
            foreach ($arr as $val) {
                if (!isset($role[$val['department']])) {
                    // 部门
                    $role[$val['department']] = [
                        'id' => '-'.$val['role_id'],
                        'text' => $val['department'],
                    ];
                }
                $role[$val['department']]['children'][] = [
                    'id' => $val['role_id'],
                    'text' => $val['role_name'],
                    // 'department' => $val['department']
                ];
            }
            $role = array_values($role);
        }
        
        echo json_encode($role);
    }

    /**
     * 新建／编辑角色
     */ 
    public function do_role(){
        // $this->load->model("Priv_model",'Priv');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_id', '', 'integer');
        $this->form_validation->set_rules('role_name', '职能名称', 'required|max_length[20]');
        $this->form_validation->set_rules('department', '部门', 'required|max_length[20]');
        if ($this->form_validation->run()) {
            $where['role_id'] = $this->input->post('role_id',TRUE);
            $data['role_name'] = $this->input->post('role_name',TRUE);
            $data['department'] = $this->input->post('department',TRUE);
            $this->load->model("Sys_model",'Sys');
            //编辑
            if ($where['role_id']) {
                $ret['ret'] = $this->Sys->edit_role($data,$where);
                $ret['id'] = $where['role_id'];
                $ret['info'] = $ret['ret']?'角色修改成功':'角色修改失败';
            //新建
            }else{
                $ret['id'] = $this->Sys->add_role($data);
                $ret['ret'] = ($ret['id']>0);
                $ret['info'] = $ret['ret']?'角色添加成功':'角色添加失败';
            }
        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }
        
        echo json_encode($ret);
    }

    /**
     * 删除角色
     */ 
    public function del_role(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ids[]', '角色', 'required|integer');
        if (!$this->form_validation->run()) exit('');
        $ids = $this->input->post('ids',TRUE);
        $this->load->model("Sys_model",'Sys');
        $del_num = $this->Sys->del_role($ids);

        $ret['ret'] = ($del_num>0)?true:false;
        $ret['info'] = $ret['ret']?"成功删除{$del_num}个角色":"删除失败";

        echo json_encode($ret);
    }

    /**
     * 绑定角色权限 废弃批量绑定
     */ 
    public function bind_role_method(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_ids[]', '角色', 'required|integer');
        $this->form_validation->set_rules('method_ids[]', '权限', 'required|integer');
        if ($this->form_validation->run()) {
            $role_ids = $this->input->post('role_ids',TRUE);
            $method_ids = $this->input->post('method_ids',TRUE);
            $this->load->model("Sys_model",'Sys');
            $ret['ret'] = $this->Sys->bind_role_method($role_ids,$method_ids);
            $ret['info'] = $ret['ret']?'角色授权成功':'角色授权失败';

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }
        
        echo json_encode($ret);
    }

    public function addRolePower(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_id', '角色', 'required|integer');
        $this->form_validation->set_rules('method_id', '权限', 'required|integer');
        if ($this->form_validation->run()) {
            $data['role_id'] = $this->input->post('role_id',TRUE);
            $data['method_id'] = $this->input->post('method_id',TRUE);
            $this->load->model("Sys_model",'Sys');
            $ret['ret'] = $this->Sys->add_role_power($data);
            $ret['info'] = $ret['ret']?'授权成功':'授权失败';

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }
        
        echo json_encode($ret);
    }

    public function delRolePower(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_id', '角色', 'required|integer');
        $this->form_validation->set_rules('method_id', '权限', 'required|integer');
        if ($this->form_validation->run()) {
            $data['role_id'] = $this->input->post('role_id',TRUE);
            $data['method_id'] = $this->input->post('method_id',TRUE);
            $this->load->model("Sys_model",'Sys');
            $ret['ret'] = $this->Sys->del_role_power($data);
            $ret['info'] = $ret['ret']?'删除权限成功':'删除权限失败';

        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }
        
        echo json_encode($ret);
    }

    /**
     * 绑定角色权限
     */ 
    public function get_method_by_role(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_id', '角色', 'required|integer');
        if (!$this->form_validation->run()) exit('');
        $role_id = $this->input->post('role_id',TRUE);
        $this->load->model("Sys_model",'Sys');
        $data = $this->Sys->get_method_by_role($role_id);

        echo json_encode($data);
    }

// 角色管理 结束================================


}