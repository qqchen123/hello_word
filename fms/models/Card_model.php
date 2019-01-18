<?php

class Card_model extends CI_Model
{
	function querycard($dsplb,$dsphh,$expdate,$dddbh,$cid)
	{
		if($dsplb != '-1' && $dsplb != '') $this->db->where("dsphh in(select dsphh from wesing_sp where dsplb='".$dsplb."')");
		if($dsphh != '-1' && $dsphh != '') $this->db->where("dsphh = '".$dsphh."'");
		if($expdate != '') $this->db->where("expdate = '".$expdate."'");
		if($dddbh != '') $this->db->where("dddbh = '".$dddbh."'");
		if($cid != '') $this->db->where("cid = '".$cid."'");
		$ret = $this->db->get('wesing_dig_card')->result_array();
		//echo $this->db->last_query();
		return $ret;
	}
	
	function queryfreeze($begin,$end)
	{
		$sql = "select min(cid) as kah1,max(cid) as kah2 from wesing_dig_card where cid between '".$begin."' and '".$end."' and ddjzt='01'";
		$ret = $this->db->query($sql)->result_array();
		//echo $this->db->last_query();
    	return $ret;
	}
	
	function freezecard($begin,$end)
	{
		$this->db->set("ddjzt", '01');
		$this->db->where("cid between '".$begin."' and '".$end."'");
		$this->db->where("ddjzt = '00'");
		$ret = $this->db->update('wesing_dig_card');
		return $ret;
	}
	
	function unfreezecard($begin,$end)
	{
		$this->db->set("ddjzt", '00');
		$this->db->where("cid between '".$begin."' and '".$end."'");
		$this->db->where("ddjzt = '01'");
		$ret = $this->db->update('wesing_dig_card');
		return $ret;
	}
	
	function addboard($title,$content)
	{
		$data = array(
		    'title' => $title,
		    'content' => $content
		);
		
		$ret = $this->db->insert('wesing_board', $data);
		return $ret;
	}
	
	function delboard($id)
	{
		$this->db->where('id', $id);
		$ret = $this->db->delete('wesing_board');
		return $ret;
	}
	
	function queryboard()
	{
		$ret = $this->db->order_by('cdate', 'DESC')->get('wesing_board')->result_array();
		
		return $ret;
	}
	
	function getboard($id)
	{
		$this->db->where("id = '".$id."'");
		$ret = $this->db->get('wesing_board')->result_array();
		return $ret;
	}
}
?>