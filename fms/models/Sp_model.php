<?php

class Sp_model extends CI_Model
{
	public function querysp()
	{
	    $where = [];
        $rows=$this->input->get('rows',true);
        $page=$this->input->get('page',true);
        $sp_mc=filter_var($this->input->get('spmc',true),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dzt=$this->input->get('dzt');
        if(!filter_var($rows,FILTER_VALIDATE_INT,$rows) || $rows < 0){
            $rows = 10;
        }
        if(!filter_var($page,FILTER_VALIDATE_INT) || $page < 0){
            $page = 1;
        }
        if(!in_array($dzt,['00','01']))
            $dzt='';
        if($sp_mc){
            $where[]="sp_mc like '%".$sp_mc."%'";
        }
        if($dzt){
            $where[]="dzt='".$dzt."'";
        }else{
            $where[]="dzt!='ds'";
        }

        $this->db->limit($rows,($page-1)*$rows);
		$ret = $this->db->get('wesing_sp')->result_array();
		$ret=$this->db->query("select sp_id,sp_code,sp_mc,sp_during,sp_rate,sp_type,sp_usage,sp_fee,sp_servfee,sp_envice from wesing_sp where dzt = '00'" . ($where?' and '.implode(' and ',$where):''))->result_array();
        // echo $this->db->last_query();
		$total=current($this->db->query('select count(1) as total from wesing_sp where dzt = "00"')->result_array());
		$ret['rows'] = $ret;
		$ret['total']= $total['total'];
    	return $ret;
	}
	
	public function getsplb($dsplb)
	{
		$ret = $this->db->where('dsplb',$dsplb)->get('wesing_splb')->result_array();
    	return $ret;
	}
    
    public function addsp()
    {
        $sp_mc=$this->input->post('dname',true);
        $sp_code=$this->input->post('dcode',true);
        $sp_type=$this->input->post('dnmeni',true);
        $sp_usage=$this->input->post('buway',true);
        $sp_rate=$this->input->post('brate',true);
        $sp_fee=$this->input->post('bfee',true);
        $sp_servfee=$this->input->post('bservfee',true);
        $sp_during=$this->input->post('bduring',true);
        $sp_envice=$this->input->post('evidence',true);
        $spid=$this->input->post('spid',true);
        $ret =false;
        if(!$spid)
    	    $ret = $this->db->get_where('wesing_sp', array('sp_code' => $sp_code))->result_array();

        if($ret)
        {
            return [500,'该产品编号已存在'];
        }
        
        $data = array(
			'sp_mc' => $sp_mc,
			'sp_code' => $sp_code,
			'sp_type' => $sp_type,
			'sp_usage' => $sp_usage,
			'sp_rate' => $sp_rate,
			'sp_fee' => $sp_fee,
			'sp_servfee' => $sp_servfee,
			'sp_during' => $sp_during,
			'sp_envice' => $sp_envice,
		);
        //print_r($data);exit();
        if($spid){
            $this->db->where('sp_id',$spid*1);
            $this->db->update('wesing_sp',$data);
            return [200,''];
        }else{
            $data['dzt']='00';
            $this->db->insert('wesing_sp',$data);
            if($this->db->affected_rows()>0)
            {
                return [200,''];
            }
        }

        return [500,'处理失败'];
    }

    public function del($spid){
	    $this->db->query("update wesing_sp set dzt='ds' where sp_id=".$spid);
	    return  [200,'产品已删除'];
    }

    public function get($id){
        return current($this->db->query("select * from wesing_sp where sp_id=".(int)$id)->result_array());
    }
}