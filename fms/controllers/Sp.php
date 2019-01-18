<?

class Sp extends Admin_Controller
{
	public function __construct()
    {
        parent::__construct();
    }
    
    public function add()
	{
		if ($_SESSION['fms_userrole'] != 1)
		{
			echo "没有权限！";exit;
		}
		$data = array();
		$this->showpage('fms/spadd',$data);
	}
	
	public function query()
	{
		if ($_SESSION['fms_userrole'] != 1)
		{
			echo "没有权限！";exit;
		}
		$data = array();
		$this->showpage('fms/spquery',$data);
	}
	
	public function splist()
    {
    	$this->load->model('sp_model','spm');
        $reponseData=$this->spm->querysp();
        foreach ($reponseData['rows'] as $k=>$v){
            $reponseData['rows'][$k]['op']=sprintf("<a href='#' onclick='edit_sp(%d)'>%s</a>&nbsp;<a href='#' onclick='del(%d)'>%s</a>",
                $v['sp_id'],
                '编辑',
                $v['sp_id'],
                '删除'
            );
        }
        echo json_encode($reponseData);
    }

    public function ajax_add_rec()
    {
        $this->load->model('sp_model','spm');
        $resp=$this->spm->addsp();
        $this->_response($resp);
    }

    public function del($sp_id){
        $this->load->model('sp_model','spm');
        $resp=$this->spm->del($sp_id);
        $this->_response($resp);
    }

    public function edit($sp_id){
        if ($_SESSION['fms_userrole'] != 1)
        {
            echo "没有权限！";exit;
        }
        $this->load->model('sp_model','spm');
        $data = $this->spm->get($sp_id);
        if(!$data){
            show_error('没有匹配的记录',404,'出错了');
            exit();
        }
        $this->showpage('fms/spadd',$data);
    }
}
?>