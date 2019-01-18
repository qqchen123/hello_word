<?

class Produce extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function gen()
    {
    	$this->load->model('produce_model','produce');
    	$splb_list = $this->produce->listsplb();
    	$data['splb_list'] = $splb_list;
    	$this->showpage('yfk/gen',$data);
    }
    
    public function listsp()
    {
    	$this->load->model('produce_model','produce');
    	$dsplb = $this->input->get('dsplb',true);
    	$sp_list = $this->produce->listsp($dsplb);
    	$str_html = "<select name=\"dsphh\" id=\"dsphh\" class=\"col-sm-6\">";
        $str_html .= "<option value=\"-1\">---".iconv('gb2312','utf-8','请选择')."---</option>";
        for($i=0;$i<count($sp_list);$i++)
        {
        	$str_html .= "<option value=\"".$sp_list[$i]['dsphh']."\">".$sp_list[$i]['dspmc']."</option>";
        }
       	$str_html .= "</select>";
      	echo $str_html;
    }
    
    public function dogen()
    {
    	$dsphh = $this->input->get('dsphh',true);
    	$expdate = $this->input->get('expdate',true);
    	$scsl = $this->input->get('scsl',true);
    	$this->load->model('produce_model','produce');
        $res = $this->produce->dogen($dsphh,$expdate,$scsl);
        if($res['val']=='01')
        {
        	$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','生成成功!');
        	echo json_encode($data);exit;
        }
        
        $data['ret'] = false;
		$data['msg'] = iconv('gb2312','utf-8','生成失败!');
		echo json_encode($data);exit;
    }
    
    public function assign()
    {
    	$args =array();
    	$this->showpage('yfk/assign',$args);
    }

    public function addCard()
    {
        $rkd = $this->input->post('jid',true);
        $rkd='RK'.date('ymdHis',time()).mt_rand(10,99);
        $sphh = $this->input->post('hh',true);
        $dsl = $this->input->post('sl',true);
        if(!$rkd) return false;
        if(!$sphh) return false;
        if(!$dsl) return false;

        $this->load->model('produce_model','produce');
        $res = $this->produce->doMoveCard($rkd,$sphh,$dsl);
        if($res){
            echo json_encode(['ret'=>true,'msg'=>'']);
        }else{
            echo json_encode(['ret'=>false,'msg'=>iconv('gb2312','utf-8','分配失败')]);
        }
    }
    
    public function querycard()
    {
        $page = $this->input->get('page');
        $rows = $this->input->get('rows');
    	$this->load->model('produce_model','produce');
    	$c_list = $this->produce->querycard($page,$rows);
    	$total= $c_list['total']['total'];
    	unset($c_list['total']);
    	for($i=0;$i<count($c_list);$i++)
        {
        	$c_list[$i]["cardtype"] = "[".$c_list[$i]["dsplb"]."]".$c_list[$i]["dlbmc"];
        	$c_list[$i]["cardmz"] = "[".$c_list[$i]["dsphh"]."]".$c_list[$i]["dspmc"];
        
        	$c_list[$i]["op"] = "<a href='#' onclick='justify(\"".$c_list[$i]['drkdj']."\",\"".$c_list[$i]['dsphh']."\",\"".$c_list[$i]['dsl']."\")'>".iconv('gb2312','utf-8','分配入库')."</a>";
        }
    	
        
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $total;
        echo json_encode($reponseData);
    }
}
?>
