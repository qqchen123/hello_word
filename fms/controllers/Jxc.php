<?

class Jxc extends Admin_Controller
{
	public function __construct()
    {
        parent::__construct();
    }
    
    public function cangw()
    {
    	$args =array();
    	$this->showpage('yfk/cangw',$args);
    }
    
    public function querycangw()
    {
        $page = $this->input->get('page',true);
        $rows=$this->input->get('rows',true);
        if(!filter_var($page,FILTER_VALIDATE_INT)) $page=1;
        if(!filter_var($rows,FILTER_VALIDATE_INT)) $rows=1;

    	$this->load->model('jxc_model','jxc');
    	$c_list = $this->jxc->querycangw($page,$rows);
        $total = $c_list['total']['total'];
        unset($c_list['total']);

    	for($i=0;$i<count($c_list);$i++)
        {
    		$c_list[$i]["sn"] = $i+1;
        	
        	$c_list[$i]["type"] = ($c_list[$i]["type"] == "00") ? iconv('gb2312','utf-8','虚仓'):iconv('gb2312','utf-8','实仓');
        	$c_list[$i]["op"] = "<a href='#' onclick='delcangw(\"".$c_list[$i]["cangw"]."\")'>".iconv('gb2312','utf-8','删除')."</a>";
       
    	}
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $total;
        echo json_encode($reponseData);
    }

    public function listcangw()
    {
        $this->load->model('jxc_model','jxc');
        $sp_list = $this->jxc->querycangw(1,99999);
        unset($sp_list['total']);
        $str_html = "<select name=\"cangw\" id=\"cangw\" class=\"col-sm-6\">";
        $str_html .= "<option value=\"-1\">---".iconv('gb2312','utf-8','请选择')."---</option>";
        for($i=0;$i<count($sp_list);$i++)
        {
            $str_html .= "<option value=\"".$sp_list[$i]['cangw']."\">".$sp_list[$i]['name']."</option>";
        }
        $str_html .= "</select>";
        echo $str_html;
    }
    
    public function addcangw()
    {
    	$cangw = $this->input->post('cangw',true);
    	$name = $this->input->post('cname',true);
    	$type = $this->input->post('ctype',true);
    	$this->load->model('jxc_model','jxc');
    	$ret=$this->jxc->addcangw($cangw,$name,$type);
    	if($ret)
    	{
    		$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','新增成功!');
        	echo json_encode($data);exit;
    	}
    	$data['ret'] = false;
        $data['msg'] = iconv('gb2312','utf-8','新增失败!');
        echo json_encode($data);exit;
    }
    
    public function delcangw()
    {
    	$cangw = $this->input->get('cangw',true);
    	$this->load->model('jxc_model','jxc');
    	$ret=$this->jxc->delcangw($cangw);
    	if($ret)
    	{
    		$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','删除成功!');
        	echo json_encode($data);exit;
    	}
    	$data['ret'] = false;
        $data['msg'] = iconv('gb2312','utf-8','删除失败!');
        echo json_encode($data);exit;
    }
    
    public function addgys()
    {
    	$dname = $this->input->post('dname',true);
    	$daname = $this->input->post('daname',true);
    	$dlxr = $this->input->post('dlxr',true);
    	$dlxdh = $this->input->post('dlxdh',true);
    	$dlxdz = $this->input->post('dlxdz',true);
    	$this->load->model('jxc_model','jxc');
    	$ret=$this->jxc->addgys($dname,$daname,$dlxr,$dlxdh,$dlxdz);
    	if($ret)
    	{
    		$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','新增成功!');
        	echo json_encode($data);exit;
    	}
    	$data['ret'] = false;
        $data['msg'] = iconv('gb2312','utf-8','新增失败!');
        echo json_encode($data);exit;
    }
    
    public function delgys()
    {
    	$id = $this->input->get('id',true);
    	$this->load->model('jxc_model','jxc');
    	$ret=$this->jxc->delgys($id);
    	if($ret)
    	{
    		$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','删除成功!');
        	echo json_encode($data);exit;
    	}
    	$data['ret'] = false;
        $data['msg'] = iconv('gb2312','utf-8','删除失败!');
        echo json_encode($data);exit;
    }
    
    public function gys()
    {
    	$args =array();
    	$this->showpage('yfk/gys',$args);
    }
    
    public function kc()
    {
        $data =array();
        $this->load->model('produce_model','produce');
        $splb_list = $this->produce->listsplb();
        $data['splb_list'] = $splb_list;
    	$this->showpage('yfk/kc',$data);
    }
    
    public function querygys()
    {
        $page = $this->input->get('page',true);
        $rows=$this->input->get('rows',true);
        if(!filter_var($page,FILTER_VALIDATE_INT)) $page=1;
        if(!filter_var($rows,FILTER_VALIDATE_INT)) $rows=1;
    	$this->load->model('jxc_model','jxc');
    	$c_list = $this->jxc->querygys($page,$rows);
    	$total = $c_list['total']['total'];
    	unset($c_list['total']);
    	for($i=0;$i<count($c_list);$i++)
        {
    		$c_list[$i]["sn"] = $i+1;
        	$c_list[$i]["op"] = "<a href='#' onclick='delgys(\"".$c_list[$i]["id"]."\")'>".iconv('gb2312','utf-8','删除')."</a>";
    	}
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $total;
        echo json_encode($reponseData);
    }
    
    public function querykc()
    {
    	//$c_list[0]["dsphh"] = 'C0001';
        //$c_list[0]["dspmc"] = iconv('gb2312','utf-8','促销卡50元');
        //$c_list[0]["dsplb"] = iconv('gb2312','utf-8','促销卡');
        //$c_list[0]["ztsl"] = '1';
        //$c_list[0]["kcsl"] = '2';

        //$page= $this->input->post('page',true);
        //$rows= $this->input->post('rows',true);
        $dlx = $this->input->post('dlx',true);
        $dsphh = $this->input->post('dsphh',true);
        $dspmc = $this->input->post('dspmc',true);
        $this->load->model('jxc_model','jxc');
        $c_list = $this->jxc->querykc(1,99999,$dlx,$dsphh,$dspmc);
        $total = $c_list['total']['total'];
        unset($c_list['total']);
//        $respData=[];
//        $formatted=[];
//        $i=0;
//        if(is_array($c_list)) foreach ($c_list as $_key=>$_val){
//            $dsphh = $_val['dsphh'];
//            if(isset($respData[$dsphh])){
//                $formatted[$respData[$dsphh]]['ztsl']+=$_val['ztsl'];
//                $formatted[$respData[$dsphh]]['kcsl']+=$_val['kcsl'];
//                unset($c_list[$_key]);
//            }else{
//                $formatted[$i]=$c_list[$_key];
//                $respData[$dsphh] = $i++;
//            }
//        }
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $total;
        echo json_encode($reponseData);
    }
    
    public function queryrkd()
    {
        $page= $this->input->get('page',true);
        $rows= $this->input->get('rows',true);
        $dlx= $this->input->get('dlx',true);
        $this->load->model('jxc_model','jxc');
        $c_list = $this->jxc->queryrkd($page,$rows,$dlx);
        $total=$c_list['total']['total'];
        unset($c_list['total']);
        foreach ($c_list as $k=>$_cItem){
            $c_list[$k]["op"]=$_cItem['ddzt']=='01' ?iconv('gbk','utf-8','已审核') : "<a href='#' onclick=\"audit('".trim($_cItem['drkdj'])."',this)\">".iconv('gb2312','utf-8','审核')."</a>";
        }

    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $total;
        echo json_encode($reponseData);
    }
    
    public function queryxsd()
    {
//    	$c_list[0]["dxsdj"] = 'XS20171007001';
//        $c_list[0]["dsphh"] = '[12345]'.iconv('gb2312','utf-8','促销卡100元');
//        $c_list[0]["dspsl"] = 1;
//        $c_list[0]["dlsj"] = '100';
//        $c_list[0]["cangw"] = '[00001]'.iconv('gb2312','utf-8','卡库仓');
//        $c_list[0]["op"] = "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','审核成功!')."\")'>".iconv('gb2312','utf-8','审核')."</a>";

        $page= $this->input->get('page',true);
        $rows= $this->input->get('rows',true);
        $this->load->model('jxc_model','jxc');
        $c_list = $this->jxc->queryxsd($page,$rows);
        $total=$c_list['total']['total'];
        unset($c_list['total']);
        foreach ($c_list as $k=>$_cItem){
            $c_list[$k]["op"]=$_cItem['ddzt']=='01' ?iconv('gbk','utf-8','已审核') : "<a href='#' onclick=\"audit('".trim($_cItem['dxsdj'])."',this)\">".iconv('gb2312','utf-8','审核')."</a>";
        }

        $reponseData['rows'] = $c_list;
        $reponseData["total"] = $total;

    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = 1;
        echo json_encode($reponseData);
    }
    
    public function querythd()
    {
//    	$c_list[0]["dthdj"] = 'TH20171007001';
//        $c_list[0]["dsphh"] = '[12345]'.iconv('gb2312','utf-8','促销卡100元');
//        $c_list[0]["dspsl"] = 1;
//        $c_list[0]["dlsj"] = '100';
//        $c_list[0]["cangw"] = '[00001]'.iconv('gb2312','utf-8','卡库仓');
//        $c_list[0]["op"] = "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','审核成功!')."\")'>".iconv('gb2312','utf-8','审核')."</a>";

        $page= $this->input->get('page',true);
        $rows= $this->input->get('rows',true);
        $this->load->model('jxc_model','jxc');
        $c_list = $this->jxc->querytgd($page,$rows);
        $total = $c_list['total']['total'];
        foreach ($c_list as $_key=>$_val){
            $c_list[$_key]['op'] = $_val['dddzt'] ==99 ? 'iconv(\'gb2312\',\'utf-8\',\'审核成功!\')' : "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','审核成功!')."\")'>".iconv('gb2312','utf-8','审核')."</a>";
        }
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = 1;
        echo json_encode($reponseData);
    }
    
    public function querytgd()
    {
    	$c_list[0]["dtgdj"] = 'TG20171007001';
        $c_list[0]["dsphh"] = '[12345]'.iconv('gb2312','utf-8','促销卡100元');
        $c_list[0]["dspsl"] = 1;
        $c_list[0]["djj"] = '100';
        $c_list[0]["cangw"] = '[00001]'.iconv('gb2312','utf-8','卡库仓');
        $c_list[0]["op"] = "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','审核成功!')."\")'>".iconv('gb2312','utf-8','审核')."</a>";

        $page= $this->input->get('page',true);
        $rows= $this->input->get('rows',true);
        $this->load->model('jxc_model','jxc');
        $c_list = $this->jxc->querytgd($page,$rows);
        $total = $c_list['total']['total'];
        unset($c_list['total']);
        foreach ($c_list as $_key=>$_val){
            $c_list[$_key]['op'] = $_val['dddzt'] ==99 ? iconv('gb2312','utf-8','审核成功!') : "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','审核成功!')."\")'>".iconv('gb2312','utf-8','审核')."</a>";
        }
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $total;
    	echo json_encode($reponseData);
    }
    
    public function queryjxc()
    {
        $page = $this->input->get('page');
        $rows = $this->input->get('rows');
        $djh = $this->input->get('ddbh');
        $ddlx = $this->input->get('ddlx');
        $ddzt = $this->input->get('status');
        $begin = $this->input->get('begin');
        $end = $this->input->get('end');
        $this->load->model('jxc_model','jxc');
        $c_list = $this->jxc->getjxc($page,$rows,$djh,$ddlx,$ddzt,$begin,$end);
        $total = $c_list['total']['total'];
        unset($c_list['total']);
        $keys=['01'=>'入库单','02'=>'退供单','03'=>'销售单','04'=>'退货单'];
        foreach ($c_list as $_key=>$_val){
            $c_list[$_key]['type']=iconv('gb2312','utf-8',$keys[$_val['ddlx']]);
            $c_list[$_key]['cangw']='['.$c_list[$_key]['dsjcangw'].']'.$c_list[$_key]['name'];
            unset($c_list[$_key]['ddlx']);
        }
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $total;
        echo json_encode($reponseData);
    }
    
    public function rkd()
    {
    	$args =array();
    	$this->showpage('yfk/rkd',$args);
    }

    public function addrkd($mode='')
    {
        $this->load->model('jxc_model','jxc');
        if($mode=='justify'){
            $ret=$this->jxc->createjxc_justify($this->input->post('jid'),$this->input->post('dlx'));
            echo json_encode($ret);
            return;
        }
        $rkd['dddbh']=$this->input->post('rkdno');
        $rkd['cdate']=date('Y-m-d H:i:s',time());
        $rkd['dczy'] = $_SESSION['merchant_id'];
        $rkd['dsphh']=$this->input->post('sphh');
        $rkd['dje']=$this->input->post('spjj');
        $rkd['dsj']=$this->input->post('spsj');
        $rkd['dsl']=$this->input->post('spsl');
        $rkd['dcangw']=$this->input->post('rkcw');
        $rkd['dlx']=$this->input->post('dlx');
        
        $ret=$this->jxc->createjxc($rkd['dddbh'],$rkd['dlx'],$rkd['dsphh'],$rkd['dsl'],$rkd['dje']||0,$rkd['dsj']||0,'000',$rkd['dcangw']);
        if($ret)
        {
            $data['ret'] = true;
            $data['msg'] = iconv('gb2312','utf-8','新增成功!');
            echo json_encode($data);exit;
        }
        $data['ret'] = false;
        $data['msg'] = iconv('gb2312','utf-8','新增失败!');
        echo json_encode($data);
    }

    public function audit()
    {
        $djh = $this->input->post('djh',true);
        $this->load->model('jxc_model','jxc');
        $ret=$this->jxc->auditrkd($djh);
        echo json_encode([
            'ret'=>$ret
        ]);
    }

    public function xsd()
    {
    	$args =array();
    	$this->showpage('yfk/xsd',$args);
    }
    
    public function thd()
    {
    	$args =array();
    	$this->showpage('yfk/thd',$args);
    }
    
    public function tgd()
    {
    	$args =array();
    	$this->showpage('yfk/tgd',$args);
    }
    
    public function cx()
    {
    	$args =array();
    	$this->showpage('yfk/cx',$args);
    }
}
?>