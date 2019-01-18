<?php

class Sale_model extends CI_Model
{
	public function genorder($memberid,$ddgsl,$dsplb,$dsphh)
	{
		$query = $this->db->query("call wesing_get_lsh()");
		$ret_lsh = $query->result_array();
		$orderid = "C".date("ymd").str_pad($ret_lsh[0]['dlsh'], 4, "0", STR_PAD_LEFT).mt_rand(10,99);
		$this->db->close();
		$this->load->database();
		$sql = "call wesing_ord_gen('".$orderid."','".$dsphh."',".$ddgsl.",'".$memberid."','".$orderid."')";
		$query = $this->db->query($sql);
		$ret_ord = $query->result_array();
		if($ret_ord[0]['err']==1)
		{
			$res['val'] = true;
        	$res['code'] = '01';//'success';
        	return $res;
		}
		$res['val'] = false;
        $res['code'] = '-1';//'fail';
        return $res;
	}
	
	public function dorefund($org_orderid)
	{
		$this->db->where("dddbh = '".$org_orderid."'");
		$ret = $this->db->get('wesing_ord_lsz')->result_array();
		if(is_array($ret)&&count($ret)>0)
		{
			$ddgsl = $ret[0]['ddgsl']*(-1);
			//$dsplb = $ret[0]['dsplb'];
			$dsphh = $ret[0]['dsphh'];
			$memberid = $ret[0]['memberid'];
			$query = $this->db->query("call wesing_get_lsh()");
			$ret_lsh = $query->result_array();
			$orderid = "TG".date("ymd").str_pad($ret_lsh[0]['dlsh'], 4, "0", STR_PAD_LEFT).mt_rand(10,99);
			$this->db->close();
			$this->load->database();
			$sql = "call wesing_ord_gen('".$orderid."','".$dsphh."',".$ddgsl.",'".$memberid."','".$org_orderid."')";
			$query = $this->db->query($sql);
			$ret_ord = $query->result_array();
			if($ret_ord[0]['err']==1)
			{
				$this->db->close();
				$this->load->database();
				$this->db->set('dddzt', '99');
				$this->db->where('dddbh', $orderid);
				$this->db->where('dddzt', '00');
				$this->db->update('wesing_ord_lsz');
				if($this->db->affected_rows()>0)
        		{ 
					$res['val'] = true;
        			$res['code'] = '01';//'success';
        			return $res;
        		}
			}
		}
		$res['code'] = '-2';//'更新失败';
        return $res;
	}
	
	public function queryrefund($orderid)
	{
		$this->db->where("outorderid = '".$orderid."'");
		$this->db->where("ddgsl <0");
		$ret = $this->db->get('wesing_ord_lsz')->result_array();
		if(is_array($ret) && count($ret)>0)
		{
			return true;
		}
		return false;
	}
	
	public function queryorder($orderid='',$memberid='',$dddzt='')
	{
		if($orderid!='') $this->db->where("dddbh = '".$orderid."'");
		if($dddzt!='') $this->db->where("dddzt = '".$dddzt."'");
		if($memberid!='-1') $this->db->where("memberid = '".$memberid."'");
		$ret = $this->db->get('wesing_ord_lsz')->result_array();
		//echo $this->db->last_query();
    	return $ret;
	}
	
	public function queryorder2($orderid='',$memberid='')
	{
		if($orderid!='') $this->db->where("dddbh = '".$orderid."'");
		
		if($memberid!='-1') $this->db->where("memberid = '".$memberid."'");
		$this->db->where("dddzt = '99'");
		$this->db->where("ddgsl > 0");
		$this->db->where("dddbh not in (select outorderid from wesing_ord_lsz where dddzt='99' and ddgsl<0)");
		$ret = $this->db->get('wesing_ord_lsz')->result_array();
		//echo $this->db->last_query();
    	return $ret;
	}
	
	public function doaudit($orderid)
	{
		$res['val'] = false;
        $sql = "call wesing_add_zflsz('".$orderid."','".$orderid."')";
		$ret = $this->db->query($sql)->result_array();

        if($ret[0]['err'] == 1)
        {

            $res['val'] = true;
            $res['code'] = '01';//'success';
            return $res;
        }
        $res['code'] = '-2';//'更新失败';
        return $res;
	}
	
	public function doactive($orderid)
	{
		$res['val'] = false;
		$sql = "call wesing_card_active('".$orderid."')";
		$ret = $this->db->query($sql)->result_array();
		if($ret[0]['err'] == 1)
        {
        	$res['val'] = true;
            $res['code'] = '01';//'success';
            return $res;
        }

        $res['code'] = '-2';//'更新失败';
        return $res;
	}
	
	public function queryreport($start,$end)
	{
		if($start=='' || $end=='')
		{
			return '-3';//'日期不能为空';
		}
		if($end < $start)
		{
			return '-4';//'起始日期不能大于结束日期';
		}
		$sql = "SELECT DATE_FORMAT( a.dthrq,  '%Y%m%d' ) as cdate,a.dsphh,a.dspmc as cardmz,c.dlbmc as cardtype, SUM( a.ddgsl ) as ddgsl, SUM( a.ddgje ) as ddgje FROM  wesing_ord_lsz a,wesing_sp b, wesing_splb c WHERE DATE_FORMAT( a.dthrq,  '%Y%m%d' ) >  '20171120' and a.dsphh=b.dsphh and b.dsplb=c.dsplb GROUP BY DATE_FORMAT( a.dthrq,  '%Y%m%d' ) , a.dsphh,a.dspmc,c.dlbmc";
		$ret = $this->db->query($sql)->result_array();
    	return $ret;
	}
}
?>