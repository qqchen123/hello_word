<?

class Member extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    	$args =array();
    	$this->showpage('yfk/member',$args);
    }
    
	public function queryuser()
    {
    	$this->load->model('member_model','member');
    	$c_list = $this->member->getmember();
    	for($i=0;$i<count($c_list);$i++)
        {
        	$zm_name = $this->member->getZamk('idtype',$c_list[$i]["idtype"]);
        	if($c_list[$i]["dzt"] == 1)
        	{
        		$c_list[$i]["op"] = "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','Ω˚”√≥…π¶!')."\")'>".iconv('gb2312','utf-8','Ω˚”√')."</a>";
        	}
        	else
        	{
        		$c_list[$i]["op"] = "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','∆Ù”√≥…π¶!')."\")'>".iconv('gb2312','utf-8','∆Ù”√')."</a>";
        	}
        	$c_list[$i]["usercard"] = "[".$zm_name[0]['type_name']."]".$c_list[$i]["idno"];
        }
    	
        
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
    
    public function querymember()
    {
    	$username = $this->input->post('username',true);
    	$gender = $this->input->post('gender',true);
    	$idtype = $this->input->post('idtype',true);
    	$idno = $this->input->post('idno',true);
    	$mobile = $this->input->post('mobile',true);
    	$addr = $this->input->post('addr',true);
    	$this->load->model('member_model','member');
    	$c_list = $this->member->querymember($username,$gender,$idtype,$idno,$mobile,$addr);
    	for($i=0;$i<count($c_list);$i++)
        {
        	$zm_name = $this->member->getZamk('idtype',$c_list[$i]["idtype"]);
        	if($c_list[$i]["dzt"] == 1)
        	{
        		$c_list[$i]["op"] = "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','Ω˚”√≥…π¶!')."\")'>".iconv('gb2312','utf-8','Ω˚”√')."</a>";
        	}
        	else
        	{
        		$c_list[$i]["op"] = "<a href='#' onclick='alert(\"".iconv('gb2312','utf-8','∆Ù”√≥…π¶!')."\")'>".iconv('gb2312','utf-8','∆Ù”√')."</a>";
        	}
        	$c_list[$i]["usercard"] = "[".$zm_name[0]['type_name']."]".$c_list[$i]["idno"];
        }
    	
        
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
    
    public function regmember()
    {
    	$username = $this->input->post('username',true);
    	$gender = $this->input->post('gender',true);
    	$idtype = $this->input->post('idtype',true);
    	$idno = $this->input->post('idno',true);
    	$mobile = $this->input->post('mobile',true);
    	$addr = $this->input->post('addr',true);
    	$this->load->model('member_model','member');
        $res = $this->member->regmember($username,$gender,$idtype,$idno,$mobile,$addr);
        if($res['val']=='01')
        {
        	$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','◊¢≤·Ì≥…π¶!');
        	echo json_encode($data);exit;
        }
        
        $data['ret'] = false;
		$data['msg'] = iconv('gb2312','utf-8','◊¢≤·Ì ß∞‹!');
		echo json_encode($data);exit;
    }
}
?>
