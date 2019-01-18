<?php

class Produce_model extends CI_Model
{
	public function listsplb()
    {
    	$ret = $this->db->get('wesing_splb')->result_array();
    	return $ret;
    }
    
    public function listsp($dsplb)
    {
    	$ret = $this->db->where('dsplb',$dsplb)->get('wesing_sp')->result_array();
    	return $ret;
    }
    
	public function dogen($dsphh,$expdate,$scsl)
	{
		//echo $scsl;
        //$ident=$dsphh.'00'.$expdate;
        $spinfo = current($this->db->query("select djj,dzxj from wesing_sp where dsphh=?",array($dsphh))->result_array());
        if(!$spinfo){
            $res['val'] = false;
            $res['code'] = '-1';//'fail';
            return $res;
        }

        for($i=0;$i < $scsl;$i++)
		{
			$query = $this->db->query("call wesing_next_lsh()");
			$ret_lsh = $query->result_array();
			//var_dump($ret_lsh);
			$passwd = $this->genpasswd();
			//var_dump($passwd);exit;
			$data = array(
				'cid' => $dsphh.str_pad($ret_lsh[0]['dlsh'], 8, "0", STR_PAD_LEFT),
				'cpasswd' => $passwd['raw'],
				'checksum' => $passwd['checksum'],
				'expdate' => $expdate,
				'dsphh' => $dsphh,
				'dzt' => '00', //Î´ÖÆ¿¨
                'drkdj'=>''
			);
			$this->db->close();
			$this->load->database();
			$this->db->insert('wesing_card_raw',$data);

			if($this->db->affected_rows()==0){
                $res['val'] = false;
                $res['code'] = '-1';//'success';
                return $res;
            }
		}

		$res['val'] = true;
        $res['code'] = '01';//'success';
        return $res;
	}

	public function doMoveCard($rkd,$dsphh,$dsl)
    {
        $ret=current($this->db->query("call wesing_add_card(?,?,?)",array($rkd,$dsl,$dsphh))->result_array());
        return $ret['err'];
    }
	
	private function genpasswd()
	{
		 $str = uniqid();

		$ary_s = array('a','b','c','d','e','f');
		$ary_m = array('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5,'f'=>6);
		
		for($i=0; $i < strlen($str); $i++)
		{
			$in = substr($str,$i,1);
			if(in_array($in,$ary_s))
			{
				$out[$i] = $ary_m[$in];
			}
			else
			{
				$out[$i] = $in;
			}
			
		}
		$e = $check = $e1 = '';
		for($i=0; $i < count($out); $i++)
		{
			if($i%2==0) $e = $e.$out[$i];
			else $e = $out[$i].$e;
			$check += $out[$i];
		}
		//echo "-".$e;
		//echo "-".str_pad($check, 3, "0", STR_PAD_LEFT);
		
		$check = str_pad($check, 3, "0", STR_PAD_LEFT);
		
		for($i=0; $i < strlen($e); $i++)
		{
			$o = substr($e,$i,1);
			if($i==3) $o.=substr($check,0,1);
			if($i==5) $o.=substr($check,1,1);
			if($i==7) $o.=substr($check,2,1);
			$e1 .= $o; 
		}

		$passwd['raw'] = $e1;
		$passwd['checksum'] = $check;
		return $passwd;
	}
	
	private function checkpasswd($passwd)
	{
		for($i=0; $i < strlen($passwd); $i++)
		{
			$o = substr($passwd,$i,1);
			if($i==4) $c.= $o;
			elseif($i==7) $c.= $o;
			elseif($i==10) $c.= $o;
			else $sum += $o;
		}
		if((int)$c == $sum) return true;
		else return false;
	}
	
	public function querycard($page,$rows)
	{
		$ret = $this->db->query("select c.dsplb,c.dlbmc,b.dsphh,b.dspmc,a.expdate,count(*) as dsl,drkdj from wesing_card_raw a,wesing_sp b,wesing_splb c where a.dsphh=b.dsphh and b.dsplb=c.dsplb and a.dzt='00' group by c.dsplb,c.dlbmc,b.dsphh,b.dspmc,drkdj,a.expdate limit ?,?",array(($page-1)*$rows,$rows * 1))->result_array();
		$amnt = $this->db->query("select count(1) as total from (select count(1) as total from wesing_card_raw a,wesing_sp b,wesing_splb c where a.dsphh=b.dsphh and b.dsplb=c.dsplb and a.dzt='00' group by c.dsplb,c.dlbmc,b.dsphh,b.dspmc,a.expdate) tmp")->result_array();
		$ret['total'] = current($amnt);
		return $ret;
	}
}
?>