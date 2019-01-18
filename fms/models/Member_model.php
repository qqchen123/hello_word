<?php

class Member_model extends CI_Model
{
	public function getmember()
	{
		$ret = $this->db->get('wesing_member')->result_array();
    	return $ret;
	}
	
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
}
?>