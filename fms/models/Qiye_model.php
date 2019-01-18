<?php

class Qiye_model extends CI_Model
{
	public function findshinfo($idnumber,$name = '')
	{
		
		if($name != '') $this->db->where("name like '%".$name."%'");
		if($idnumber != '') $this->db->where("idnumber like '%".$idnumber."%'");
		$ret = $this->db->get('fms_qiyeinfo')->result_array();
    	return $ret; 
	}

	function updateQiyebankCardinfo($bankcardinfo)
	{
		//$this->db->set("userid", $bankcardinfo['userid']);
		//$this->db->set("idnumber", $bankcardinfo['idnumber']);
		//$this->db->set("bankname", $bankcardinfo['bankname']);
		//$this->db->set("bankcardNo", $bankcardinfo['bankcardNo']);
		$this->db->set("mobile", $bankcardinfo['mobile']);
		//$this->db->set("bankcardvalid", $bankcardinfo['bankcardvalid']);
		//$this->db->set("bankcardimgu", $bankcardinfo['bankcardimgu']);
		//$this->db->set("bankcardimgd", $bankcardinfo['bankcardimgd']);
		
		$this->db->where("idnumber = '".$bankcardinfo['idnumber']."'");
		$ret = $this->db->update('fms_qiyeinfo');
		return $ret;
	}
	function editQiyereg($info)
	{
		
		$this->db->set("name", $info['name']);
		$this->db->set("sex", $info['sex']);
		$this->db->set("idnumvalid", $info['idnumvalid']);
		$this->db->set("idnumaddress", $info['idnumaddress']);
		$this->db->set("utype", $info['utype']);
		$this->db->set("companyname", $info['companyname']);
		//$this->db->set("mobile", $info['mobile']);
		$this->db->set("lryg", $info['lryg']);
		$this->db->set("cdate", $info['cdate']);
		$this->db->set("status", $info['status']);
		if(isset($info['idnumimgu'])){
			$this->db->set("idnumimgu", $info['idnumimgu']);
		}
		if(isset($info['idnumimgd'])){
			$this->db->set("idnumimgd", $info['idnumimgd']);
		}
		
		
		$this->db->where("fuserid = '".$info['fuserid']."'");
		$ret = $this->db->update('fms_qiyeinfo');
		return $ret;
	}
	function updateQiyereg($bankcardinfo)
	{
		
		$this->db->set("status", $bankcardinfo['status']);
		$this->db->set("comment", $bankcardinfo['comment']);
		$this->db->set("lryg", $bankcardinfo['lryg']);
		$this->db->set("cdate", $bankcardinfo['cdate']);
		$this->db->where("fuserid = '".$bankcardinfo['fuserid']."'");
		$ret = $this->db->update('fms_qiyeinfo');
		return $ret;
	}
	
	public function bankcarddefault($idnumber,$uktype){
		$this->db->set("is_default", '01');
		$this->db->where("idnumber = '".$idnumber."' and uktype = '".$uktype."'");
		$ret = $this->db->update('fms_bank');
		return $ret;
	}

	public function mkdir_idnumber($path)
	{
		if (is_dir($path)){  
			return 'is_dir';
		}else{
			$res=mkdir($path,0777,true);
			if ($res){
				return "mkdirok";
			}else{
				return "mkdirerr";
			}	
		}	
	}

	public function getuserinfodata($status)
	{
		if($status != '') $this->db->where("status = '".$status."'");
		$ret = $this->db->get('fms_qiyeinfo')->result_array();
    	return $ret;
	}
	public function getuserinfo($status)
	{
		if($status != '') $this->db->where("fuserid = '".$status."'");
		$ret = $this->db->get('fms_qiyeinfo')->result_array();
    	return $ret;
	}
	//-----------------------------------------------------------------------------------------------------------------
	public function getZamk($name,$val)
    {
    	$ret = $this->db->get_where('wesing_zamk', array('w_type' => $name, 'type_val' => $val))->result_array();
    	return $ret;
    }
    
    public function querymember($username='',$gender='',$idtype='',$idno='',$mobile='',$addr='')
	{
		if($username!='') $this->db->where("username = '".$username."'");
		if($gender!='0') $this->db->where("gender = '".$gender."'");
		if($idtype!='0') $this->db->where("idtype = '".$idtype."'");
		if($idno!='') $this->db->where("idno = '".$idno."'");
		if($mobile!='') $this->db->where("mobile = '".$mobile."'");
		if($addr!='') $this->db->where("addr like '%".$addr."%'");
		$ret = $this->db->get('wesing_member')->result_array();
    	return $ret;
	}
    
    public function regmember($username,$gender,$idtype,$idno,$mobile,$addr)
    {
    	$res['val'] = false;
    	$ret = $this->db->get_where('wesing_member', array('mobile' => $mobile))->result_array();
        
        if($ret)
        {
            $res['code'] = '-1';//'用户已存在';
            return $res;
        }
        
        $data = array(
			'memberid' => uniqid(),
			'username' => $username,
			'gender' => $gender,
			'idtype' => $idtype,
			'idno' => $idno,
			'mobile' => $mobile,
			'addr' => $addr
		);
        $this->db->insert('wesing_member',$data);
        if($this->db->affected_rows()>0)
        {
        	$res['val'] = true;
            $res['code'] = '01';//'success';
            return $res;
        }

        $res['code'] = '-2';//'插入失败';
        return $res;
    }

    public function provs_get_id($id)
    {
        return $this->db->query("select * from fms_provs where p_id=?",array($id))->result_array();
    }

    public function getLimit($id,$type)
    {
        return $this->db->query("select * from fms_provs where p_fid=? and p_ftype=?",array($id,$type.'_limit'))->result_array();
    }

    public function provs_get($id,$type)
    {
        return $this->db->query("select * from fms_provs where p_fid=? and p_ftype=?",array($id,$type))->result_array();
    }

    public function saveSval($type,$fid,$data)
    {

        $rec['p_val']=json_encode($data);
        if(current($this->db->query("select 1 from fms_provs where p_fid=? and p_ftype=?",array($fid,$type))->result_array())===false) {
            $rec['p_fid']=$fid;
            $rec['p_ftype']=$type;
            $this->db->insert('fms_provs', $rec);
        }else{
            $this->db->update('fms_provs',$rec,array('p_fid'=>$fid,'p_ftype'=>$type));
        }
        return $this->db->affected_rows()>0?['code'=>200,'msg'=>''] : ['code'=>500,'msg'=>'处理失败'];
    }

    public function preleadd($fid,$type,$data,$keys)
    {
        if(isset($data['id'])){
            $id=$data['id'];
            unset($data['id']);
            $rec['p_fid']=$fid;
            $rec['p_ftype']=$type;
            $rec['p_val']=json_encode($data);
            $this->db->where('p_id = '.$id);
            $this->db->update('fms_provs',$rec);
            return ['code'=>200,'msg'=>''];
        }
        $ifexits = false;
        foreach (explode(',',$keys) as $_val){
            $rest = $this->db->query("select json_extract(p_val,'$.".$_val."') from fms_provs where json_extract(p_val,'$.".$_val."') like '%".$data[$_val]."%'")->result_array();
            echo $this->db->last_query();
            if($rest){
                $ifexits=true;
                break;
            }
        }

        if($ifexits)
            return ['code'=>500,'msg'=>'记录重复'];

        $rec['p_fid']=$fid;
        $rec['p_ftype']=$type;
        $rec['p_val']=json_encode($data);
        $this->db->insert('fms_provs',$rec);
        return ['code'=>200,'msg'=>''];
    }

    public function prov_del($id)
    {
        $id = (int)$id;
        $ret = current($this->db->query('select * from fms_provs where p_id=?',[$id])->result_array());
        if(!$ret)
            return ['code'=>200,'msg'=>''];
        $info = json_decode($ret['p_val'],true);
        foreach(glob('../upload/'.$ret['p_fid'].'/prele/'.hash('SHA1',$info['idnum']).'*') as $v)
            unlink($v);
        $this->db->query('delete from fms_provs where p_id='.$id);
        return ['code'=>200,'msg'=>''];
    }
}
?>