<?php

class Sys_model extends CI_Model
{
	public function getMenu($parent='-1',$roleid='')
    {
    	if($roleid!='')
    	{
    		if($parent == '-1')
    		{
    			$where = "name in (select parent from fms_menu where id in(select role_menuid from fms_rolemenu where role_id = '".$roleid."')) or name ='ChPass'";
    			$ret = $this->db->order_by('sort', 'ASC')->where($where)->get_where('fms_menu', array('parent' => $parent))->result_array();
                // echo $this->db->last_query();

    		}
    		else
    		{
    			$where = "id in(select role_menuid from fms_rolemenu where role_id = '".$roleid."')";
    			$ret = $this->db->order_by('sort', 'ASC')->where($where)->get_where('fms_menu', array('parent' => $parent))->result_array();
    			//echo $this->db->last_query();exit;
    		}
    	}
    	else
    	{
    		$ret = $this->db->order_by('sort', 'ASC')->get_where('fms_menu', array('parent' => $parent))->result_array();

    	}
    	//$ret = $this->db->order_by('sort', 'ASC')->where($where)->get_where('wesing_menu', array('parent' => $parent))->result_array();
    	//var_dump($ret);exit;
    	if(is_array($ret))
    	{
    		$menu = array();
    		for($i=0;$i<count($ret);$i++)
    		{
    			$menu[$ret[$i]['name']]=array(
    				'show' => $ret[$i]['shown'],
    				'text' => $ret[$i]['text'],
    				'icon' => $ret[$i]['icon'],
    				'target' => $ret[$i]['target'],
    				'children' => $this->getMenu($ret[$i]['name'],$roleid)
    			);
    		}
    		return $menu;
    	}
    	return array();
    }

// 控制器管理 开始================================
    function get_class_method($select=[],$where=[],$details=[]){
        if($details!==[]) $this->db->where_in('detail',$details);
        return $this->db->select($select)->where($where)->order_by('sort,class')->get('sys_class_method')->result_array();
        // return $this->db->last_query();
    }

    function get_son_num($parent_ids){
        return $this->db->select(['parent_id','count(id) num'])->where_in('parent_id',$parent_ids)->group_by('parent_id')->get('sys_class_method')->result_array();
        // var_dump($this->db->last_query());
        // return $a;
    }

    function add_class_method($data){
        //插入方法
        if (!empty($data['class_id'])) {
            $sql = "
            INSERT into `sys_class_method`
            (
                `name`,`dir`,`class`,`method`,`parent_id`,`is_login`,`is_sys`,`is_show`,`is_loged`,`sort`
            )
            select
                '{$data['name']}',
                `dir`,
                `class`,
                '{$data['method']}',
                '{$data['class_id']}',
                '{$data['is_login']}',
                '{$data['is_sys']}',
                '{$data['is_show']}',
                '{$data['is_loged']}',
                max(sort)+1
            from `sys_class_method` where `id`={$data['class_id']} or `parent_id`={$data['class_id']}
            ";
            $this->db->query($sql);
            //return $this->db->last_query();

        //插入控制器
        }else{
            $this->db->insert('sys_class_method',$data);
        }

        return ($this->db->insert_id());
    }

    function getCMDNum($obj,$obj_type,$parent_id,$id){
        if($id) $this->db->where(['id !='=>$id]);
        if($obj_type=='class') $this->db->where(['dir'=>$_POST['dir']]);
        return $this->db->select('count(*) num')->get_where('sys_class_method',[
            'parent_id'=>$parent_id,
            $obj_type=>$obj,
        ])->row_array()['num'];
    }

    function edit_class_method($data,$where){
        //编辑方法
        if (!empty($data['class_id'])) {
            $sql = "UPDATE
                `sys_class_method` t1,
                (select dir,class from `sys_class_method` where id={$data['class_id']}) t2
            set
                `name`='{$data['name']}',
                `t1`.`dir`=`t2`.`dir`,
                `t1`.`class`=`t2`.`class`,
                `method`='{$data['method']}',
                `parent_id`={$data['class_id']},
                `is_login`={$data['is_login']},
                `is_sys`={$data['is_sys']},
                `is_show`={$data['is_show']},
                `is_loged`={$data['is_loged']}
            where `id`={$where['id']}";
            $this->db->query($sql);
            $num = $this->db->affected_rows();
            if ($num<1) return false;

            //联动改参数
            $sql = "UPDATE
                `sys_class_method` t1,
                (select dir,class from `sys_class_method` where id={$data['class_id']}) t2
            set
                `t1`.`dir`=`t2`.`dir`,
                `t1`.`class`=`t2`.`class`,
                `method`='{$data['method']}',
                `is_login`={$data['is_login']},
                `is_sys`={$data['is_sys']},
                `is_show`={$data['is_show']},
                `is_loged`={$data['is_loged']}
            where `parent_id`={$where['id']}";
            $this->db->query($sql);
            $num += $this->db->affected_rows();
            if ($num>=1){
                return $where['id'];
            }else{
                return false;
            }

        //编辑控制器
        }else{
            //控制器
            $this->db->update('sys_class_method',$data,$where);
            $num = $this->db->affected_rows();
            if($num<1) return false;
            //方法
            $this->db->update('sys_class_method',['dir'=>$data['dir'],'class'=>$data['class']],['parent_id'=>$where['id']]);
            $num += $this->db->affected_rows();
            //参数
            $sql = "UPDATE `sys_class_method`
                set `dir`='{$data['dir']}',`class`='{$data['class']}'
                where `parent_id` in (
                    select * from (
                        select id from `sys_class_method` where parent_id={$where['id']}
                    ) as tmp
                )";
            $this->db->query($sql);
            $num += $this->db->affected_rows();
            if ($num>=1){
                return $where['id'];
            }else{
                return false;
            }
        }
    }

    function del_class_method($id){
        //增加为3层 控制器、方法、参数
        $where = "SELECT `id` FROM `sys_class_method` WHERE `id` = {$id} or `parent_id` = {$id}";
        $where2 = "SELECT `id` FROM `sys_class_method` WHERE `parent_id` in ({$where})";
        $sql = "DELETE FROM `sys_role_method` WHERE method_id IN ($where) or method_id IN ($where2)";
        $this->db->query($sql);
        // return $this->db->last_query();

        // $this->db->where("id=$id or parent_id=$id")->or_where_in('parent_id',$where)->delete('sys_class_method');

        $sql = "DELETE FROM `sys_class_method` WHERE `id`={$id} or `parent_id`={$id} or parent_id IN (select * from ($where) as tmp)";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function sort_method($id,$sort_id,$point){
        //目标位置
        $sort = $this->db->select(['id','sort','class','method','detail'])->get_where('sys_class_method',['id'=>$sort_id],1)->row_array();
        //原来位置
        $arr = $this->db->select(['id','sort','class','method','detail'])->get_where('sys_class_method',['id'=>$id],1)->row_array();

        //参数类排序
        if($arr['detail']!==''){
            if($sort['class']!==$arr['class'] || $sort['method']!==$arr['method'] || $sort['detail']==='' || $arr['detail']==='') return 0;
            $typeWhere = ['method'=>$arr['method'],'detail !='=>''];

        //方法类排序
        }else{
            if($sort['class']!==$arr['class']) return 0;
            $typeWhere = ['detail'=>''];
        }

        $tmp_sort = $sort['sort'];

        //上移
        if($arr['sort']>$sort['sort']){
            $this->db->set('sort', 'sort+1', false);
            //top
            if ($point==1) {
                $where = "`sort` >= {$sort['sort']} and `sort` < {$arr['sort']} and `class` = '{$sort['class']}'";
            //bottom
            }else{
                $tmp_sort++;
                $where = "`sort` > {$sort['sort']} and `sort` < {$arr['sort']} and `class` = '{$sort['class']}'";
            }

        //下移
        }else{
            $this->db->set('sort', 'sort-1', false);
            //top
            if ($point==1) {
                $tmp_sort--;
                $where = "`sort` < {$sort['sort']} and `sort` > {$arr['sort']} and `class` = '{$sort['class']}'";
            //bottom
            }else{
                $where = "`sort` <= {$sort['sort']} and `sort` > {$arr['sort']} and `class` = '{$sort['class']}'";
            }
        }

        $this->db->where($where)->where($typeWhere);
        $this->db->update('sys_class_method');
        //return $this->db->last_query();
        if($this->db->affected_rows()>0) {
            $this->db->update('sys_class_method',['sort'=>$tmp_sort],['id'=>$id]);
            return $this->db->affected_rows();
        }else{
            return 0;
        }

    }

    //新增参数
    function add_detail($where,$name,$detail){
        $data = $this->db->select([
            'id','dir','class','method','is_login','is_sys','is_show','is_loged'
        ])->get_where('sys_class_method',$where,1)->row_array();
        // return $this->db->last_query();
        if(!$data) return ;
        $data['parent_id'] = $data['id'];
        $data['name'] = $name;
        $data['detail'] = $detail;

        $sql = "
            INSERT into `sys_class_method`
            (
                `name`,`dir`,`class`,`method`,`detail`,`parent_id`,`is_login`,`is_sys`,`is_show`,`is_loged`,`sort`
            )
            select
                '{$data['name']}',
                '{$data['dir']}',
                '{$data['class']}',
                '{$data['method']}',
                '{$data['detail']}',
                '{$data['parent_id']}',
                '{$data['is_login']}',
                '{$data['is_sys']}',
                '{$data['is_show']}',
                '{$data['is_loged']}',
                max(sort)+1
            from `sys_class_method` where `parent_id`={$data['parent_id']}
            ";
        $this->db->query($sql);
        //return $this->db->last_query();

        // $this->db->insert('sys_class_method',$data);
        return $this->db->insert_id();
    }

    //添加池子样本 同步添加权限参数
    function pool_sample_add_detail($name,$detail,$methods){
        $this->db->trans_start();
        foreach ($methods as $key => $val) {
            $val['detail']='';
            $bool = $this->add_detail($val,$name,$detail);
        }
        $this->db->trans_complete();

        if($this->db->trans_status()){
            return true;
        }else{
            return false;
        }
    }

    //编辑池子样本 同步编辑权限参数
    function pool_sample_edit_detail($name,$detail,$methods){
        foreach ($methods as $key => $val) {
            $this->db->or_where("(`dir`='{$val['dir']}' and `class`='{$val['class']}' and `method`='{$val['method']}' and `detail`={$detail})");
        }
        return $this->db->update('sys_class_method',['name'=>$name]);
    }

    //假删除池子样本 同步删除权限参数
    function pool_sample_del_detail($name,$detail){
        $ids = $this->db->select('id')->get_where('sys_class_method',['name'=>$name,'detail'=>$detail])->result_array();
        if($ids==[]) return false;
        $ids = array_column($ids, 'id');

        $this->db->trans_start();
            $this->db->where_in('id',$ids)->delete('sys_class_method');
            $this->db->where_in('method_id',$ids)->delete('sys_role_method');
        $this->db->trans_complete();

        if($this->db->trans_status()){
            return true;
        }else{
            return false;
        }
    }


    //编辑参数
    function edit_detail($id,$name,$detail){
        $where['id'] = $id;
        $data['name'] = $name;
        $data['detail'] = $detail;

        $this->db->update('sys_class_method',$data,$where);
        if($this->db->affected_rows()){
            return $where['id'];
        }else{
            return false;
        }
    }
// 控制器管理 结束================================

// 角色管理 开始==================================
    function get_role($where=[]){
        return $this->db->where($where)->get('fms_role')->result_array();
    }

    function add_role($data){
        $this->db->insert('fms_role',$data);
        return $this->db->insert_id();
    }

    function edit_role($data,$where){
        $this->db->update('fms_role',$data,$where);
        return ($this->db->affected_rows()==1);
    }

    function del_role($ids){
        //删除角色
        $this->db->where_in('role_id',$ids)->delete([
            //'fms_rolemenu',//菜单权限表
            'sys_role_method',//角色权限表
            'fms_role'//角色表
        ]);
        return $this->db->affected_rows();
    }

    function bind_role_method($role_ids,$method_ids){
        //删除角色旧权限
        $this->db->where_in('role_id',$role_ids)->delete([
            //'fms_rolemenu',//菜单权限表
            'sys_role_method'//角色权限表
        ]);

        //  插入新权限
        foreach ($role_ids as $role_id) {
            foreach ($method_ids as $method_id) {
                $data[] = [
                    'role_id' => $role_id,
                    'method_id' => $method_id
                ];
            }
        }
        $this->db->insert_batch('sys_role_method',$data);
        //暂不管菜单角色
        return $this->db->affected_rows();
    }

    function add_role_power($data){
        $sql = "REPLACE into `sys_role_method` values ({$data['role_id']},{$data['method_id']})";
        return $this->db->query($sql);
    }

    function del_role_power($where,$where_in=[]){
        if($where_in!==[]) $this->db->where_in($where_in[0],$where_in[1]);
        return $this->db->delete('sys_role_method',$where);
        // var_dump($this->db->last_query());
    }

    function get_method_by_role($role_id,$method_id=null){
        if($method_id!==null) $this->db->where(['method_id' => $method_id]);
        return $this->db->select('method_id')
            ->where(['role_id' => $role_id])
            ->get('sys_role_method')
            ->result_array();
    }

    //获取根方法
    function get_method_by_classmethod($dir,$class,$method){
        return $this->db
            ->select('id,is_login,is_loged')
            ->where(['dir'=>$dir,'class' => $class,'method' => $method,'detail'=>''])
            ->get('sys_class_method')
            ->row_array();
    }

    function get_role_power($dir,$class,$method,$role_id){
        return $this->db
            ->select("count(*) num")
            ->where(['dir' => $dir,'class' => $class,'method' => $method,'role_id' => $role_id])
            ->join('sys_role_method','id=method_id')
            ->get('sys_class_method')
            ->row_array()['num'];
    }

    function get_role_detail_power($dir,$class,$method,$role_id='admin_role'){
        if($role_id!=='admin_role')
            $this->db
                ->join('sys_role_method','id=method_id')
                ->where_in('role_id',$role_id);
        $detailArr =  $this->db
            ->select("detail")
            ->where(['dir' => $dir,'class' => $class,'method' => $method])
            ->get('sys_class_method')
            ->result_array();
        return array_column($detailArr, 'detail');
    }

    function get_role_mini_powers($role_ids=[]){
        if(!in_array(1, $role_ids)){
            $this->db
                ->where_in('role_id',$role_ids)
                ->where(['dir' => 'miniprogram'])
                ->or_where(['is_login'=>2])
                ->join('sys_role_method','id=method_id','left');
        }
        return $this->db
            ->select("class '0',method '1'")
            ->where(['dir' => 'miniprogram'])
            ->get('sys_class_method')
            ->result_array();
    }
// 角色管理 结束==================================

// 访问log 开始====================
    function add_visit_log($log_table,$data){
        if (!$this->db->table_exists($log_table)){
            //表不存在创建表
            $sql = "
                CREATE TABLE IF NOT EXISTS `$log_table` (
                  `user_ip` varchar(15) NOT NULL COMMENT '用户IP',
                  `role_id` int(11) NOT NULL COMMENT '用户角色',
                  `role_name` varchar(20) NOT NULL COMMENT '角色名称',
                  `user_id` int(11) NOT NULL COMMENT '用户id',
                  `user_name` varchar(30) NOT NULL COMMENT '用户名称',
                  `method_id` int(11) NOT NULL,
                  `dir` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器目录',
                  `class` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器',
                  `method` varchar(30) NOT NULL DEFAULT '' COMMENT '方法',
                  `request_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT '请求方式 get:1,post:2',
                  `query_string` varchar(255) NOT NULL DEFAULT '' COMMENT '请求参数',
                  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '访问时间'
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='访问日志表_XXXX_XX(4位年_2位月)'
            ";
            $this->db->query($sql);
        }

        // $this->db->insert($log_table,$data);
        $sql = "
            INSERT delayed into `{$log_table}` (`user_ip`, `role_id`, `role_name`, `user_id`, `user_name`, `method_id`, `dir`, `class`, `method`, `request_method`, `query_string`) VALUES (
                '{$data['user_ip']}',
                '{$data['role_id']}',
                '{$data['role_name']}',
                '{$data['user_id']}',
                '{$data['user_name']}',
                '{$data['method_id']}',
                '{$data['dir']}',
                '{$data['class']}',
                '{$data['method']}',
                '{$data['request_method']}',
                '{$data['query_string']}'
            )
        ";
        $this->db->query($sql);
        // echo $this->db->last_query();
    }
// 访问log 结束====================

//样本添加编辑时 复制权限 开始==============
    //指定样本id获取方法表和绑定权限表 by 奚晓俊
    function get_methodandrole_by_detail($copy_id){
        return $this->db
            ->select('sys_class_method.*,sys_role_method.role_id')
            ->join('sys_role_method','method_id=sys_class_method.id')
            ->where("`detail`={$copy_id}")
            ->get('sys_class_method')
            ->result_array();
    }

    //获取detail=id循环插入授权表 by 奚晓俊
    function insert_roles($id,$row){
        $sql = "INSERT into `sys_role_method` select {$row['role_id']},id from `sys_class_method` where `dir`='{$row['dir']}' and `class`='{$row['class']}' and `method`='{$row['method']}' and `detail`='{$id}'";
        return $this->db->query($sql);
    }
//样本添加编辑时 复制权限 结束==============

}
?>