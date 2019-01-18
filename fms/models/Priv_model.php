<?php

class Priv_model extends CI_Model
{
	public function getroles()
	{
		$ret = $this->db->query("select * from fms_menu where parent != '-1' order by parent,sort")->result_array();
		return $ret;
	}
	
	public function addrole($role_name,$department,$ary_priv)
	{
		$data = array(
			'role_name' => $role_name,
			'department' => $department
		);
        $this->db->insert('fms_role',$data);
        if($this->db->affected_rows()>0)
        {
        	$role_id = $this->db->insert_id();
        	for($i=0;$i<count($ary_priv);$i++)
        	{
        		$data_role = array(
					'role_id' => $role_id,
					'role_menuid' => $ary_priv[$i]
				);
				$this->db->insert('fms_rolemenu',$data_role);
        	}
        	return true;
    	}
    	return false;
	}
	
	public function updaterole($role_id,$role_name,$department,$ary_priv)
	{
		$this->db->set('role_name', $role_name);
		$this->db->set('department', $department);
		$this->db->where('role_id', $role_id);
		$this->db->update('fms_role');
		
		$this->db->where('role_id', $role_id);
		$this->db->delete('fms_rolemenu'); 
		for($i=0;$i<count($ary_priv);$i++)
        {
        	$data_role = array(
				'role_id' => $role_id,
				'role_menuid' => $ary_priv[$i]
			);
			$this->db->insert('fms_rolemenu',$data_role);
        }
        return true;
	}
	
	public function getrolename($role_id)
	{
		$this->db->where("role_id = '".$role_id."'");
		$ret_role = $this->db->get('fms_role')->result_array();
		if(is_array($ret_role))
		{
			$role_name = $ret_role[0]['role_name'];
			$department = @$ret_role[0]['department'];
			return $role_name."[".$department."]";
		}
		else
		{
			return '';
		}
	}
	
	public function getrolebyid($role_id)
	{
		$this->db->where("role_id = '".$role_id."'");
		$ret_role = $this->db->get('fms_role')->result_array();
		
		$this->db->where("role_id = '".$role_id."'");
		$ret_priv = $this->db->get('fms_rolemenu')->result_array();
		$data['role'] = $ret_role;
		$data['priv'] = $ret_priv;
		return $data;
	}
	
	public function countrole($rolename='',$department='')
	{
		if($rolename!='') $this->db->where("role_name = '".$rolename."'");
		if($department!='') $this->db->where("department = '".$department."'");
		
		$this->db->from('fms_role');
		return $this->db->count_all_results();
	}
	
	public function getallroles()
	{
		$ret = $this->db->get('fms_role')->result_array();
		return $ret;
	}
	
	public function queryrole($rolename='',$department='',$page,$rows)
	{
		$count = $this->countrole($rolename,$department);
		if($rolename!='') $this->db->where("role_name = '".$rolename."'");
		if($department!='') $this->db->where("department = '".$department."'");
		/* È¡µÃÃ¿Ò³¼ÇÂ¼Êý */
        $size = isset($rows) && intval($rows) > 0 ? intval($rows) : 15;
        /* ¼ÆËã×ÜÒ³Êý */
        $page_count = ceil($count / $size);
        /* È¡µÃµ±Ç°Ò³ */
        $page = isset($page) && intval($page) > 1 ? intval($page) : 0;
        if ($page > $page_count) {
            $page = $page_count;
        }
        if ($page > 1) {
            $page = ($page - 1) * $size;
        }
        $ret['data'] = $this->db->get('fms_role',$size, $page)->result_array();
		$ret['total'] = $count;
    	return $ret;
	}
	
	public function countuser()
	{
		//if($rolename!='') $this->db->where("role_name = '".$rolename."'");
		//if($department!='') $this->db->where("department = '".$department."'");
		
		$this->db->from('wesing_merchant');
		return $this->db->count_all_results();
	}
	
	public function queryuser($page,$rows)
	{
		$count = $this->countuser();
		/* È¡µÃÃ¿Ò³¼ÇÂ¼Êý */
        $size = isset($rows) && intval($rows) > 0 ? intval($rows) : 15;
        /* ¼ÆËã×ÜÒ³Êý */
        $page_count = ceil($count / $size);
        /* È¡µÃµ±Ç°Ò³ */
        $page = isset($page) && intval($page) > 1 ? intval($page) : 0;
        if ($page > $page_count) {
            $page = $page_count;
        }
        if ($page > 1) {
            $page = ($page - 1) * $size;
        }
        $ret['data'] = $this->db->get('wesing_merchant',$size, $page)->result_array();
		$ret['total'] = $count;
    	return $ret;
	}
	
	public function updateuser($userid,$username,$userrole,$dzt,$idno,$mobile,$email,$rzdate)
	{
		$res['val'] = false;
		$data = array(			
			'username' => $username,
			'dzt' => $dzt,			
			'idno' => $idno,
			'mobile' => $mobile,
			'email' => $email,
			'rzdate' => $rzdate,			
			'userrole' => $userrole
		);
		$this->db->where('userid', $userid);
		$ret = $this->db->update('wesing_merchant', $data);
		if($ret>0)
        {
        	$res['val'] = true;
            $res['code'] = '01';//'success';
            return $res;
        }

        $res['code'] = '-2';//'Ê§°Ü';
        return $res;
	}
	
    public function addNew($userid,$username,$userrole,$dzt,$idno,$mobile,$email,$rzdate)
    {
  		$res['val'] = false;
    	$ret = $this->db->get_where('wesing_merchant', array('userid' => $userid))->result_array();
        
        if($ret)
        {
            $res['code'] = '-1';//'ÓÃ»§ÒÑ´æÔÚ';
            return $res;
        }

        $salt = substr(str_shuffle(uniqid(time())),3,6);
		$data = array(
			'userid' => $userid,
			'username' => $username,
			'dzt' => $dzt,
			'usermm' => md5($userid.$salt.'123456'),
			'idno' => $idno,
			'mobile' => $mobile,
			'email' => $email,
			'rzdate' => $rzdate,
			'salt' => $salt,
			'userrole' => $userrole,
			'wesing' => 1
		);
        $this->db->insert('wesing_merchant',$data);
        if($this->db->affected_rows()>0)
        {
        	$res['val'] = true;
            $res['code'] = '01';//'success';
            return $res;
        }

        $res['code'] = '-2';//'²åÈëÊ§°Ü';
        return $res;
    }
    
    public function getuserbyid($userid)
    {
    	$ret = $this->db->get_where('wesing_merchant', array('userid' => $userid))->result_array();
    	return $ret;
    }
    
    public function delrole($roleid)
    {
    	$res['val'] = false;
		$this->db->where('role_id', $roleid);
		$this->db->delete('wesing_role');
		if($this->db->affected_rows()>0)
        {
			$this->db->where('w_type', 'role');
			$this->db->where('type_val', $roleid);
			$this->db->delete('wesing_zamk');
			if($this->db->affected_rows()>0)
        	{
        		$res['val'] = true;
            	$res['code'] = '01';//'success';
            	return $res;
        	}
        }

        $res['code'] = '-2';//'¸üÐÂÊ§°Ü';
        return $res;
    }
    
   	public function checkpriv($role_id,$target)
   	{
   		$ret_role = $this->db->query("select * from fms_menu a,fms_rolemenu b where a.id = b.role_menuid and b.role_id='".$role_id."' and target='".$target."'")->result_array();
   		if(count($ret_role)>0) return true;
   		else return false;
   	}
    
    public function getpriv($role_id)
    {
    	$ret_role = $this->db->query("select a.text from fms_menu a,fms_rolemenu b where a.id = b.role_menuid and b.role_id='".$role_id."'")->result_array();
    	//var_dump($ret_role);exit;
    	$ret_role_str = "";
    	for($j=0;$j<count($ret_role);$j++)
    	{
    		$ret_role_str .= $ret_role[$j]['text'].",&nbsp;";
    	}
    		
    	return $ret_role_str;
    }
    
   
}
?>
