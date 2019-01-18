<?

class Priv extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function role()
    {
    	$this->load->model('priv_model','priv');
        if (!$this->priv->checkpriv($_SESSION['fms_userrole'],'Priv-role'))
		{
			echo iconv('gb2312','utf-8',"没有权限！");exit;
		}
		$role_id = $this->input->get_post('role_id',true);
		
		if($role_id!='')
		{
			$role = $this->priv->getrolebyid($role_id);
			$data['role'] = $role['role'];
			$data['priv'] = $role['priv'];
			
		}
		$data['role_id'] =$role_id;
		$clist = $this->priv->getroles();
		$data['clist'] = $clist;
		$this->showpage('fms/role',$data);
    }
    
	public function addrole()
    {
    	$this->load->model('priv_model','priv');
    	if (!$this->priv->checkpriv($_SESSION['fms_userrole'],'Priv-role'))
		{
			echo iconv('gb2312','utf-8',"没有权限！");exit;
		}
    	$role_id = $this->input->get_post('role_id',true);
    	$rolename = $this->input->get_post('rolename',true);
		$department = $this->input->get_post('department',true);
		$role = $this->input->get_post('role',true);
		$role_ary = explode(",", $role);
		//var_dump($role_ary);exit;
		
		if($role_id != '')
		{
			$ret = $this->priv->updaterole($role_id,$rolename,$department,$role_ary);
		}
		else
		{
			$ret = $this->priv->addrole($rolename,$department,$role_ary);
		}
		if($ret)
		{
			$data['ret'] = true;
			$data['msg'] = iconv('gb2312','utf-8','保存成功!');
        	echo json_encode($data);exit;
		}
		$data['ret'] = false;
		$data['msg'] = iconv('gb2312','utf-8','保存失败!');
        echo json_encode($data);exit;
    }
    
    public function queryrole()
    {
    	$this->load->model('priv_model','priv');
    	if (!$this->priv->checkpriv($_SESSION['fms_userrole'],'Priv-role'))
		{
			echo iconv('gb2312','utf-8',"没有权限！");exit;
		}
    	$page = $this->input->get_post('page',true);
    	$rows = $this->input->get_post('rows',true);
    	$rolename = $this->input->get_post('rolename',true);
		$department = $this->input->get_post('department',true);
    	
    	$c_list_raw = $this->priv->queryrole($rolename,$department,$page,$rows);
		$c_list = $c_list_raw['data'];
		for($i=0;$i<count($c_list);$i++)
        {
        	$c_list[$i]["zn"] = $c_list[$i]["role_name"];
    		$c_list[$i]["bumen"] = @$c_list[$i]["department"];
    		$role_id = $c_list[$i]["role_id"];

    		$c_list[$i]["qx"] = $this->priv->getpriv($role_id);
    		$c_list[$i]["op"] = "<a href='#' onclick='editrole(".$role_id.");'>".iconv('gb2312','utf-8','编辑')."</a>&nbsp;<a href='#'>".iconv('gb2312','utf-8','删除')."</a>";
        }
    	
        
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $c_list_raw['total'];;
        echo json_encode($reponseData);
    }
    
    public function userlist()
    {
    	$this->load->model('priv_model','priv');
    	if (!$this->priv->checkpriv($_SESSION['fms_userrole'],'Priv-user'))
		{
			echo iconv('gb2312','utf-8',"没有权限！");exit;
		}
    	$page = $this->input->get_post('page',true);
    	$rows = $this->input->get_post('rows',true);
    	
    	$c_list_raw = $this->priv->queryuser($page,$rows);
		$c_list = $c_list_raw['data'];
		for($i=0;$i<count($c_list);$i++)
        {
        	if($c_list[$i]["dzt"]=='01') $c_list[$i]["dzt"]=iconv('gb2312','utf-8','已启用');
        	else $c_list[$i]["dzt"]=iconv('gb2312','utf-8','已禁用');
        	$userid = $c_list[$i]["userid"]; 
        	$role_id = $c_list[$i]["userrole"];    		
    		$c_list[$i]["userrole"] = $this->priv->getrolename($role_id);
    		$c_list[$i]["op"] = "<a href='#' onclick=\"edituser('".trim($userid)."');\">".iconv('gb2312','utf-8','编辑')."</a>";
        }
    	
        
    	$reponseData['rows'] = $c_list;
    	$reponseData["total"] = $c_list_raw['total'];;
        echo json_encode($reponseData);
    }
    
    public function user()
    {
    	$this->load->model('priv_model','priv');
    	if (!$this->priv->checkpriv($_SESSION['fms_userrole'],'Priv-user'))
		{
			echo iconv('gb2312','utf-8',"没有权限！");exit;
		}
    	$userid = $this->input->get_post('userid',true);
		
		if($userid!='')
		{
			$user = $this->priv->getuserbyid($userid);
			$data['user'] = $user;

		}
    	
    	$clist = $this->priv->getallroles();
		$data['clist'] = $clist;
    	$data['userid'] =$userid;
		$this->showpage('fms/user',$data);
    }
	
	public function adduser()
    {
    	$this->load->model('priv_model','priv');
    	if (!$this->priv->checkpriv($_SESSION['fms_userrole'],'Priv-user'))
		{
			echo iconv('gb2312','utf-8',"没有权限！");exit;
		}
    	$userid = $this->input->get_post('userid',true);
        $username = $this->input->get_post('username',true);
        $userrole = $this->input->get_post('userrole',true);
        $dzt = $this->input->get_post('dzt',true);
        $idno = $this->input->get_post('idno',true);
        $mobile = $this->input->get_post('mobile',true);
        $email = $this->input->get_post('email',true);
        $rzdate = $this->input->get_post('rzdate',true);
        $update = $this->input->get_post('update',true);
        
        
        if($update=='01')
        {
        	$res = $this->priv->updateuser($userid,$username,$userrole,$dzt,$idno,$mobile,$email,$rzdate);
        }
        else
        {
        	$res = $this->priv->addNew($userid,$username,$userrole,$dzt,$idno,$mobile,$email,$rzdate);
    	}
        //var_dump($res);
        if($res['val']=='01')
        {
        	$data['ret'] = true;
        	$data['msg'] = iconv('gb2312','utf-8','保存成功!');
        	echo json_encode($data);exit;
        }
        
        $data['ret'] = false;
		$data['msg'] = iconv('gb2312','utf-8','保存失败!');
		echo json_encode($data);exit;
    }
}
?>
