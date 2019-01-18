<?

class Card extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function query()
    {
    	$this->load->model('produce_model','produce');
    	$splb_list = $this->produce->listsplb();
    	$data['splb_list'] = $splb_list;
    	$this->showpage('yfk/query',$data);
    }
    
    public function doquery()
    {
    	$dsplb = $this->input->get('cardtype',true);
    	$dsphh = $this->input->get('dsphh',true);
    	$expdate = $this->input->get('expdate',true);
    	$dddbh = $this->input->get('orderid',true);
    	$cid = $this->input->get('kah',true);
    	$this->load->model('card_model','card');
        $c_list = $this->card->querycard($dsplb,$dsphh,$expdate,$dddbh,$cid);
        for($i=0;$i<count($c_list);$i++)
        {
    	
        	$c_list[$i]["kah"] = $c_list[$i]["cid"];
        	$c_list[$i]["passwd"] = $c_list[$i]["cpasswd"];
        	
        	if($c_list[$i]["dzt"]=='00') $c_list[$i]["dddzt"] = iconv('gb2312','utf-8','未出售');
        	if($c_list[$i]["dzt"]=='02') $c_list[$i]["dddzt"] = iconv('gb2312','utf-8','已出售');
        	if($c_list[$i]["dzt"]=='01') $c_list[$i]["dddzt"] = iconv('gb2312','utf-8','已激活');
        	if($c_list[$i]["dzt"]=='DD') $c_list[$i]["dddzt"] = iconv('gb2312','utf-8','收款审核中');
        	if($c_list[$i]["ddjzt"]=='01') $c_list[$i]["dddzt"] .= ",".iconv('gb2312','utf-8','已冻结');
    	}
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
    
    public function queryfroze()
    {
    	$begin = $this->input->get('begin',true);
    	$end = $this->input->get('end',true);
    	$this->load->model('card_model','card');
    	$c_list = $this->card->queryfreeze($begin,$end);
    	for($i=0;$i<count($c_list);$i++)
        {
    		$c_list[0]["sn"] = $i+1;
        
        	$c_list[0]["op"] = "<a href='#' onclick='dounfroze(\"".$c_list[$i]["kah1"]."\",\"".$c_list[$i]["kah2"]."\")'>".iconv('gb2312','utf-8','解冻')."</a>";
    	}
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
    
    public function froze()
    {
    	$args =array();
    	$this->showpage('yfk/froze',$args);
    }
    
    public function dofroze()
    {
    	$begin = $this->input->get('begin',true);
    	$end = $this->input->get('end',true);
    	$this->load->model('card_model','card');
    	$ret=$this->card->freezecard($begin,$end);
    	if($ret)
    	{
    		$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','冻结成功!');
        	echo json_encode($data);exit;
    	}
    	$data['ret'] = false;
        $data['msg'] = iconv('gb2312','utf-8','冻结失败!');
        echo json_encode($data);exit;
    }
    
    public function unfroze()
    {
    	$args =array();
    	$this->showpage('yfk/unfroze',$args);
    }
    
    public function dounfroze()
    {
    	$begin = $this->input->get('begin',true);
    	$end = $this->input->get('end',true);
    	$this->load->model('card_model','card');
    	$ret=$this->card->unfreezecard($begin,$end);
    	if($ret)
    	{
    		$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','解冻成功!');
        	echo json_encode($data);exit;
    	}
    	$data['ret'] = false;
        $data['msg'] = iconv('gb2312','utf-8','解冻失败!');
        echo json_encode($data);exit;
    }
    
    public function addboard()
    {
    	$title = $this->input->post('title',true);
    	$content = $this->input->post('content',true);
    	$this->load->model('card_model','card');
    	$ret=$this->card->addboard($title,$content);
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
    
    public function delboard()
    {
    	$id = $this->input->get('id',true);
    	$this->load->model('card_model','card');
    	$ret=$this->card->delboard($id);
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
    
    public function getpass()
    {
    	$args =array();
    	$this->showpage('yfk/getpass',$args);
    }
    
    public function board()
    {
    	$args =array();
    	$this->showpage('yfk/board',$args);
    }
    
    public function queryboard()
    {
    	$this->load->model('card_model','card');
    	$c_list = $this->card->queryboard();
    	for($i=0;$i<count($c_list);$i++)
        {
    		$c_list[$i]["sn"] = $i+1;
    		$c_list[$i]["title"] = "<a href='#' onclick='getboard(\"".$c_list[$i]["id"]."\")'>".$c_list[$i]["title"]."</a>";
			$c_list[$i]["op"] = "<a href='#' onclick='delboard(\"".$c_list[$i]["id"]."\")'>".iconv('gb2312','utf-8','删除')."</a>";
    	}
        
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
    
    public function getboard()
    {
    	$id = $this->input->get('id',true);
    	$this->load->model('card_model','card');
    	$c_list = $this->card->getboard($id);
    	if(is_array($c_list) && count($c_list)>0)
    	{
    		$data['ret'] = true;
        	$data['title'] = $c_list[0]["title"];
        	$data['content'] = $c_list[0]["content"];
        	echo json_encode($data);exit;
    	}
    	$data['ret'] = false;
        $data['msg'] = iconv('gb2312','utf-8','获取失败!');
        echo json_encode($data);exit;
    }
}
?>
