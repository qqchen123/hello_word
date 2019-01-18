<?

class Sale extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function order()
    {
    	$this->load->model('member_model','member');
    	$c_list = $this->member->getmember();
    	$data['member_list'] = $c_list;
    	$this->load->model('produce_model','produce');
    	$splb_list = $this->produce->listsplb();
    	$data['splb_list'] = $splb_list;
    	$this->showpage('yfk/order',$data);
    }
    
    public function genorder()
    {
    	$memberid = $this->input->get('memberid',true);
    	$ddgsl = $this->input->get('ddgsl',true);
    	$dsplb = $this->input->get('dsplb',true);
    	$dsphh = $this->input->get('dsphh',true);
    	$this->load->model('sale_model','sale');
        $res = $this->sale->genorder($memberid,$ddgsl,$dsplb,$dsphh);
        if($res['code']=='01')
        {
        	$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','下单成功!');
        	echo json_encode($data);exit;
        }
        
        $data['ret'] = false;
		$data['msg'] = iconv('gb2312','utf-8','下单失败!');
		echo json_encode($data);exit;
    }
    
    public function queryorder()
    {
    	$orderid = $this->input->get('orderid',true);
    	$memberid = $this->input->get('memberid',true);
    	$this->load->model('sale_model','sale');
    	$c_list = $this->sale->queryorder($orderid,$memberid,'00');
    	for($i=0;$i<count($c_list);$i++)
        {
    	
    	
    		$c_list[$i]["userid"] = $c_list[$i]["memberid"];
        	$c_list[$i]["dsphh"] = '['.$c_list[$i]["dsphh"].']'.$c_list[$i]["dspmc"];
        	if($c_list[$i]["dddzt"] == '01')
        	{
        		$c_list[$i]["op"] = iconv('gb2312','utf-8','已审核');
        	}
        	else
        	{
        		$c_list[$i]["op"] = "<a href='#' onclick='doaudit(\"".$c_list[$i]["dddbh"]."\")'>".iconv('gb2312','utf-8','审核')."</a>";
        	}
        }
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
    
    public function queryactive()
    {
    	$orderid = $this->input->get('orderid',true);
    	$memberid = $this->input->get('memberid',true);
    	$this->load->model('sale_model','sale');
    	$c_list = $this->sale->queryorder($orderid,$memberid,'01');
    	for($i=0;$i<count($c_list);$i++)
        {
    	
    	
    		$c_list[$i]["userid"] = $c_list[$i]["memberid"];
        	$c_list[$i]["dsphh"] = '['.$c_list[$i]["dsphh"].']'.$c_list[$i]["dspmc"];
        	if($c_list[$i]["dddzt"] == '99')
        	{
        		$c_list[$i]["op"] = iconv('gb2312','utf-8','已激活');
        	}
        	else
        	{
        		$c_list[$i]["op"] = "<a href='#' onclick='doactive(\"".$c_list[$i]["dddbh"]."\")'>".iconv('gb2312','utf-8','激活')."</a>";
        	}
        }
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
    
    public function queryrefund()
    {
    	$orderid = $this->input->get('orderid',true);
    	$memberid = $this->input->get('memberid',true);
    	$this->load->model('sale_model','sale');
    	$c_list = $this->sale->queryorder2($orderid,$memberid);
    	for($i=0;$i<count($c_list);$i++)
        {
    	
    	
    		$c_list[$i]["userid"] = $c_list[$i]["memberid"];
        	$c_list[$i]["dsphh"] = '['.$c_list[$i]["dsphh"].']'.$c_list[$i]["dspmc"];
        	if($this->sale->queryrefund($orderid))
        	{
        		$c_list[$i]["op"] = iconv('gb2312','utf-8','已回退');
        	}
        	else
        	{
        		$c_list[$i]["op"] = "<a href='#' onclick='dorefund(\"".$c_list[$i]["dddbh"]."\")'>".iconv('gb2312','utf-8','回退')."</a>";
        	}
        }
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
    
    public function queryreport()
    {
    	$begin = $this->input->get('begin',true);
    	$end = $this->input->get('end',true);
    	$this->load->model('sale_model','sale');
        $c_list = $this->sale->queryreport($begin,$end);
        if(is_array($c_list))
        {
        	$reponseData['rows'] = $c_list;
    		$reponseData["total"] = count($c_list);
        	echo json_encode($reponseData);
        }else{
        	echo json_encode([
        		'rows'=>[],
				'total'=>0
			]);
		}

    }
    
    public function audit()
    {
    	$this->load->model('member_model','member');
    	$c_list = $this->member->getmember();
    	$data['member_list'] = $c_list;
    	$this->showpage('yfk/audit',$data);
    }
    
    public function doaudit()
    {
    	$orderid = $this->input->get('orderid',true);
    	$this->load->model('sale_model','sale');
        $res = $this->sale->doaudit($orderid);
        if($res['code']=='01')
        {
        	$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','审核成功!');
        	echo json_encode($data);exit;
        }
        
        $data['ret'] = false;
		$data['msg'] = iconv('gb2312','utf-8','审核失败!');
		echo json_encode($data);exit;
    }
    
    public function doactive()
    {
    	$orderid = $this->input->get('orderid',true);
    	$this->load->model('sale_model','sale');
        $res = $this->sale->doactive($orderid);
        if($res['code']=='01')
        {
        	$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','激活成功!');
        	echo json_encode($data);exit;
        }
        
        $data['ret'] = false;
		$data['msg'] = iconv('gb2312','utf-8','激活失败!');
		echo json_encode($data);exit;
    }
    
    public function active()
    {
    	$this->load->model('member_model','member');
    	$c_list = $this->member->getmember();
    	$data['member_list'] = $c_list;
    	$this->showpage('yfk/active',$data);
    }
    
    public function refund()
    {
    	$this->load->model('member_model','member');
    	$c_list = $this->member->getmember();
    	$data['member_list'] = $c_list;
    	$this->showpage('yfk/refund',$data);
    }
    
    public function dorefund()
    {
    	$orderid = $this->input->get('orderid',true);
    	$this->load->model('sale_model','sale');
        $res = $this->sale->dorefund($orderid);
        if($res['code']=='01')
        {
        	$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','回退成功!');
        	echo json_encode($data);exit;
        }
        
        $data['ret'] = false;
		$data['msg'] = iconv('gb2312','utf-8','回退失败!');
		echo json_encode($data);exit;
    }
    
    public function report()
    {
    	$args =array();
    	$this->showpage('yfk/report',$args);
    }
    
    
}
?>
