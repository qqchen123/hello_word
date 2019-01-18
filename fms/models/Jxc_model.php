<?php

class Jxc_model extends CI_Model
{
	function addcangw($cangw,$name,$type)
	{

	    $ifexists = $this->db->query('select 1 from wesing_cangw where cangw=?',array($cangw))->result_array();
        if($ifexists) return false;
		$data = array(
		    'cangw' => $cangw,
		    'name' => $name,
		    'type' => $type
		);
		
		$ret = $this->db->insert('wesing_cangw', $data);
		return $ret;
	}
	
	function querycangw($page=1,$rows=10)
	{
		//$ret = $this->db->order_by('type', 'ASC')->get('wesing_cangw')->result_array();
        $ret = $this->db->query('select * from wesing_cangw ORDER  by type ASC limit ?,?',array(($page-1)*$rows,$rows*1))->result_array();
        $ret['total'] = current($this->db->query('select count(1) as total from wesing_cangw')->result_array());
		return $ret;
	}
	
	function delcangw($cangw)
	{
		$this->db->where('cangw', $cangw);
		$ret = $this->db->delete('wesing_cangw');
		return $ret;
	}
	
	function addgys($dname,$daname,$dlxr,$dlxdh,$dlxdz)
	{

		$data = array(
		    'dname' => $dname,
		    'daname' => $daname,
		    'dlxr' => $dlxr,
		    'dlxdh' => $dlxdh,
		    'dlxdz' => $dlxdz
		);
		
		$ret = $this->db->insert('wesing_gys', $data);
		return $ret;
	}
	
	function querygys($page=1,$rows=10)
	{
		//$ret = $this->db->order_by('cdate', 'ASC')->get('wesing_gys')->result_array();
		$ret = $this->db->query('select * from wesing_gys ORDER  by cdate ASC limit ?,?',array(($page-1)*$rows,$rows*1))->result_array();
		$ret['total'] = current($this->db->query('select count(1) as total from wesing_gys')->result_array());
		return $ret;
	}
	
	function delgys($id)
	{
		$this->db->where('id', $id);
		$ret = $this->db->delete('wesing_gys');
		return $ret;
	}

	function createjxc_justify($jid,$dlx)
    {
        $ret = $this->db->query('select count(cid) as dspsl,a.dsphh,djj,dzxj from wesing_card_raw a left join wesing_sp b on a.dsphh=b.dsphh where a.dzt=\'00\' and drkdj=?',array($jid))->result_array();
        if(!$ret) return array(
            'ret'=>false,
            'msg'=>iconv('gbk','utf-8','查询失败')
        );
        $ret = current($ret);
        $ddbh = 'RK'.date('ymdHis').mt_rand(100,999);
        $insRet=$this->createjxc($ddbh,$dlx,$ret['dsphh'],$ret['dspsl'],$ret['djj'],$ret['dzxj'],'','0001',$jid);
        if($insRet == false)
            return array(
            'ret'=>false,
            'msg'=>iconv('gbk','utf-8','新增失败')
        );

        return array(
            'ret'=>true
        );
    }

	function createjxc($dddbh,$ddlx,$dsphh,$dspsl,$djj,$dsj,$dgysbh,$dcangw='0001',$jid='')
	{
        $this->db->where('dddbh',$dddbh);
        $rows=$this->db->get('wesing_cangk_lsz')->result_array();
        $dzt='00';
        if($rows) return false;

		if($ddlx == '01')//入库
		{
			$dsjcangw = 'RKAAA';
			$dxjcangw = $dcangw;
		}
		if($ddlx == '02')//退供
		{
			$dsjcangw = $dcangw;
			$dxjcangw = 'RKAAA';
		}
		if($ddlx == '03')//销售
		{
			$dsjcangw = $dcangw;
			$dxjcangw = 'XSAAA';
		}
		if($ddlx == '04')//退货
		{
			$dsjcangw = 'XSAAA';
			$dxjcangw = $dcangw;
		}
		$this->db->trans_begin();
        /*1.插入cangk_lsz*/
		$data = array(
		    'dddbh' => $dddbh,
		    'ddlx' => $ddlx,
		    'ddzt' => $dzt,
		    'cdate' => date("Y-m-d H:i:s"),
		    'dsphh' => $dsphh,
		    'dsl' => 0,
		    'dspsl' => $dspsl,
		    'djj' => $djj,
		    'dsj' => $dsj,
		    'dgysbh' => $dgysbh,
		    'dsjcangw' => $dsjcangw,
		    'dxjcangw' => $dxjcangw
		);
		$this->db->insert('wesing_cangk_lsz', $data);

        /*2.插入cangk_sptz*/
		$data = array(
		    'dddbh' => $dddbh,
		    'ddlx' => $ddlx,
		    'ddzt' => $dzt,
		    'cdate' => date("Y-m-d H:i:s"),
		    'dsphh' => $dsphh,
		    'dsl' => 0,
		    'dspsl' => $dspsl,
		    'djj' => $djj,
		    'dsj' => $dsj,
		    'dgysbh' => $dgysbh,
		    'dsjcangw' => '',
		    'dcangw' => $dsjcangw,
		    'dxjcangw' => $dxjcangw,
		    'dgjid' => 0
		);
		
		$ret = $this->db->insert('wesing_cangk_sptz', $data);
		
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}
	}
	
	function checkjxc($dddbh,$ddlx)
	{
		$this->db->where('dddbh', $dddbh);
		$ret = $this->db->get('wesing_cangk_lsz')->result_array();
		if(count($ret)>0)
		{
			$dcangw = $ret[0]['dsjcangw'];
			$dspsl = $ret[0]['dspsl'];
		}
		else
		{
			return false;
		}

		$this->db->trans_begin();
		
		$this->db->set('ddzt', '01');
		$this->db->where('ddzt', '00');
		$this->db->where('dddbh', $dddbh);
		$this->db->update('wesing_cangk_lsz');
		if($this->db->affected_rows()<=0)
        {
        	$this->db->trans_rollback();
		    return false;
        }
		if($ddlx == '01')//入库
		{
			
		}
		if($ddlx == '02' || $ddlx == '03')//02退供,03销售
		{
			$this->db->where('dcangw', $dcangw);
			$this->db->where('ddzt', '01');
			$this->db->where('dspsl >', '0');
			$this->db->order_by('cdate', 'ASC');
			$ret_02 = $this->db->get('wesing_cangk_sptz')->result_array();
			$dspsl_z = $dspsl;
			for($i=0;$i<count($ret_02);$i++)
			{
				$dspsl_temp2 = $ret_02[$i]['dspsl'];
				if($dspsl_temp2 < $dspsl_z)
				{
					$this->db->set('dspsl', 0);
					$this->db->where('id', $ret_02[$i]['id']);
					$this->db->update('wesing_cangk_sptz');
					$dspsl_z -= $dspsl_temp2;
				}
				else
				{
					$this->db->set('dspsl', $dspsl_temp2-$dspsl_z);
					$this->db->where('id', $ret_02[$i]['id']);
					$this->db->update('wesing_cangk_sptz');
					$dspsl_z = 0;
				}
			}
			if($dspsl_z>0)//退供、销售超过入库上限
			{
				$this->db->trans_rollback();
			    return false;
			}
		}
		
		if($ddlx == '04')//退货
		{
			$this->db->where('dcangw', $dcangw);
			$this->db->where('ddzt', '01');
			$this->db->where('dspsl =', '0');
			$this->db->order_by('cdate', 'DESC');
			$ret_04 = $this->db->get('wesing_cangk_sptz')->result_array();
			$dspsl_z = $dspsl;
			for($i=0;$i<count($ret_04);$i++)
			{
				$dddbh_01 = $ret_04[$i]['dddbh'];
				$this->db->where('dddbh', $dddbh_01);
				$ret_01 = $this->db->get('wesing_cangk_lsz')->result_array();
				$dspsl_01 = $ret_01[$i]['dspsl'];
				if($dspsl_01 > $dspsl_z)
				{
					
					$dspsl_z = 0;
				}
				else
				{
					
					$dspsl_z -= $dspsl_01;
				}
			}
			if($dspsl_z>0)//退货超过入库上限
			{
				$this->db->trans_rollback();
			    return false;
			}
		}
		
		$this->db->set('ddzt', '01');
		$this->db->set('dsjcangw', 'dcangw', false);
		$this->db->set('dcangw', 'dxjcangw', false);
		$this->db->set('dxjcangw', '');
		$this->db->where('ddzt', '00');
		$this->db->where('dddbh', $dddbh);
		$this->db->update('wesing_cangk_sptz');
		if($this->db->affected_rows()<=0)
        {
        	$this->db->trans_rollback();
		    return false;
        }
		
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}
	}

    public function queryrkd($page,$rows)
    {
        $ret=$this->db->query('select dddbh as drkdj,concat("[",wcl.dsphh,"]",dspmc) as dsphh,dspsl,wcl.djj,wcl.ddzt,cw.name as cangw from wesing_cangk_lsz wcl left JOIN wesing_sp sp ON wcl.dsphh=sp.dsphh LEFT JOIN wesing_cangw as cw on wcl.dsjcangw=cw.cangw where sp.dzt="01" and ddlx="01" order by cdate desc limit ?,?',array(($page-1)*$rows,$rows*1))->result_array();
        $ret['total'] = current($this->db->query('select count(1) as total from wesing_cangk_lsz where ddlx="01"')->result_array());

        return $ret;
    }

    public function queryxsd($page,$rows)
    {
        $ret=$this->db->query('select dddbh as dxsdj,concat("[",wcl.dsphh,"]",dspmc) as dsphh,dspsl,wcl.dsj * wcl.dspsl as dlsj,wcl.ddzt,cw.name as cangw from wesing_cangk_lsz wcl left JOIN wesing_sp sp ON wcl.dsphh=sp.dsphh LEFT JOIN wesing_cangw as cw on wcl.dsjcangw=cw.cangw where sp.dzt="01" and ddlx="03" order by cdate desc limit ?,?',array(($page-1)*$rows,$rows*1))->result_array();
        $ret['total'] = current($this->db->query('select count(1) as total from wesing_cangk_lsz where ddlx="03"')->result_array());

        return $ret;
    }

    public function querykc($page,$rows,$dlx,$dsphh,$dspmc)
    {
        $whereCond=[];
        if($dspmc) $whereCond[]=" b.dspmc like '%".$dspmc."%' ";
        if($dsphh&& $dsphh!=-1) $whereCond[]=" a.dsphh='".$dsphh."' ";
        if($dlx&&$dlx!=-1) $whereCond[]=" c.dsplb='".$dlx."' ";
        //$sql="select a.dsphh,dspmc,dlbmc,sum(if(a.dzt in('00','01'),1,0)) as ztsl,sum(if(a.dzt='02',1,0)) as kcsl ";
        $sqlCount="select count(a.dsphh) as total ";
        //$cond = "from wesing_card_raw a,wesing_sp b, wesing_splb c ";
        //$cond.="where a.dsphh=b.dsphh and b.dsplb=c.dsplb and a.dzt in ('00','01','02') ";

        $sql = "select a.dsphh,dspmc,dlbmc as dsplb,sum(if(a.ddzt ='00',if(a.ddlx='03',-1*a.dspsl,a.dspsl),0)) as ztsl,sum(if(a.ddzt='01',if(a.ddlx='03',-1 * a.dspsl,1 * a.dspsl),0)) as kcsl ";
        $cond=" from wesing_cangk_sptz a,wesing_sp b, wesing_splb c ";
        $cond.=" where a.dsphh=b.dsphh and b.dsplb=c.dsplb ";
        if($whereCond) $cond.= ' and '.implode($whereCond,' and ');
        $cond.='group by a.dsphh limit '.($page-1)*$rows.','.$rows;
        $ret=$this->db->query($sql.$cond)->result_array();
        $rets=$this->db->query($sqlCount.$cond)->result_array();
        $ret['total']=current($rets);
        //print_r($ret);
        return $ret;
    }

    public function querytgd($page,$rows)
    {
        /**
         $c_list[0]["dthdj"] = 'TH20171007001';
        $c_list[0]["dsphh"] = '[12345]'.iconv('gb2312','utf-8','促销卡100元');
        $c_list[0]["dspsl"] = 1;
        $c_list[0]["dlsj"] = '100';
        $c_list[0]["cangw"] = '[00001]'.iconv('gb2312','utf-8','卡库仓');
        $c_list[0]["op"] = "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','审核成功!')."\")'>".iconv('gb2312','utf-8','审核')."</a>";
         */
        $ret = $this->db->query(sprintf("select a.dddbh as dtgdj,a.dsphh,ddgsl*-1 as dspsl,a.djj,concat('[',dsjcangw,']',b.name) as cangw,dddzt from wesing_ord_lsz a, wesing_cangw b,wesing_cangk_lsz c where a.dsphh=c.dsphh and c.ddlx='01' and c.dsjcangw=b.cangw and a.dddbh like 'TG%%' limit %d,%d",($page-1)*$rows,$rows))->result_array();
        $total = $this->db->query('select count(1) as total from wesing_ord_lsz where dddbh like "TG%"')->result_array();
        $ret['total'] = current($total);
        return $ret;
    }

    public function getjxc($page,$rows,$djh,$ddlx,$ddzt,$begin,$end)
    {
        $whereCond=[];
        if($djh) $whereCond[]=" dddbh='".$djh."'";
        if($ddlx && $ddlx!=-1) $whereCond[]=" b.ddlx='".$ddlx."'";
        if($ddzt && $ddzt!=-1) $whereCond[]=" b.ddzt='".$ddzt."'";
        if($begin && $end && strtotime($begin) < strtotime($end)) $whereCond[]=" and b.cdate between '".$begin." 00:00:00' and '".$end." 23:59:59'";
        //$sql="select b.dddbh,dspmc,a.dsphh,count(cid) as dspsl,sum(dsj) as dje,dsjcangw,d.name,b.ddlx ";
        $sqlCount="select count(1) as total ";
        //$cond="from wesing_card_raw a left join wesing_cangk_lsz b on a.drkdj=b.dddbh ";
        //$cond.="left join wesing_sp c on a.dsphh=c.dsphh ";
        //$cond.="left join wesing_cangw d on b.dsjcangw=d.cangw ";
        //$cond.="where a.dzt!='00' and c.dzt='01'";

        $sql = "select dddbh,dspmc,b.dsphh,dspsl,dspsl*dsj as dje,dsjcangw,d.name,b.ddlx";
        $cond=" from wesing_cangk_lsz b left join wesing_sp c on b.dsphh=c.dsphh left join wesing_cangw d on b.dsjcangw=d.cangw where c.dzt='01'";

        if($whereCond) $cond.= ' and '. implode($whereCond,' and ');
        $total = $this->db->query($sqlCount.$cond)->result_array();
        $cond.=" order by cdate desc limit ".($page-1)*$rows.','.$rows;
        $ret = $this->db->query($sql.$cond)->result_array();
        $ret['total'] = current($total);
        //echo $sql.$cond;
        return $ret;
    }

    public function auditrkd($djh)
    {
        $this->db->trans_begin();
        $this->db->query('update wesing_cangk_lsz set ddzt="01" where ddzt="00" and dddbh=?',array($djh));
        $this->db->query('update wesing_cangk_sptz set ddzt="01" where ddzt="00" and dddbh=?',array($djh));
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }
}
?>