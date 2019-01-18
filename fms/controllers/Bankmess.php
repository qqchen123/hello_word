<?php

//银杏
class Bankmess extends Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Yinxin_model');
	}

	/**
	 * 新增银信用户审核 列表 界面
	 */
	public function show_search_page()
	{
		$this->showpage('fms/search_page');
	}

	/**
	 * 新增银信用户界面
	 */
	public function add_yx_user()
	{
		$this->showpage('fms/add_yx_user');
	}

	public function show_check_page()
	{
		$this->showpage('fms/check_page');
	}

	/**
	 * 新增银信用户
	 */
	public function add_yx_user_info()
	{
		$add_info = $this->input->post();
		$add_info['account'] = 'YX'.$add_info['reg_phone'];
		$add_info['pwd'] = 'yx'.substr($add_info['reg_phone'], -6);
		$m_add = $this->Yinxin_model->add_new_user_info($add_info);
		echo json_encode($m_add);
		die;
	}

	/**
	 * 用户审核列表
	 */
	public function new_yx_user_list()
	{
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$first = $rows * ($page - 1);
		$list_res = $this->Yinxin_model->get_new_yx_user_list($rows, $first);
		echo json_encode($list_res);
		die;
	}

	/**
	 * 审核新用户
	 */
	public function check_new_user_yx(){
		$postmsg = $this->input->post();
		$do_check = $this->Yinxin_model->do_check_new_user($postmsg);
		if ($do_check){
			$msg = ['code' => 1, 'msg' => ' 审核成功！'];
		}else{
			$msg = ['code' => 0, 'msg' => '审核失败！'];
		}
		echo json_encode($msg);
		exit();
	}

	/**
	 * 审核完成执行爬取数据
	 */
	public function do_rep()
	{
		$postmsg = $this->input->post();
		$this->Yinxin_model->do_rep($postmsg['account']);
	}
	/**
	 *导入Excel--添加信用户
	 */
	public function uploadify_excel()
	{
//		$config['upload_path']      = '../upload/add_user_excel/'.date('Y-m-d');//服务器路径
		$config['upload_path'] = '/home/upload/'.date('Y-m-d H:i:s');;//服务器路径
		$this->mkdir_we($config['upload_path']);
		$_SESSION['del_excel_path'] = $config['upload_path'];
		$config['allowed_types']    = '*';
		$config['max_size']         = 100000000000;
		$config['file_name']        = uniqid();
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		foreach($_FILES as $field => $file)
		{
			if($file['error'] == 0)
			{
				if ($this->upload->do_upload($field))
				{
					$datas = $this->upload->data();
//					print_r($datas);die;
					$filename = $datas['full_path'];
					$exts = substr($filename,(strrpos($filename,".")+1));
					if($exts == 'xls'){
						include_once('./models/PHPExcel/Reader/Excel5.php');
						$PHPReader=new \PHPExcel_Reader_Excel5();
					}else if($exts == 'xlsx'){
						include_once('./models/PHPExcel/Reader/Excel2007.php');
						$PHPReader=new \PHPExcel_Reader_Excel2007();
					}
//					print_r($filename);die;
					$PHPExcel=$PHPReader->load($filename);
//					print_r($PHPExcel);die;
					$currentSheet=$PHPExcel->getSheet(0);
					$allColumn=$currentSheet->getHighestColumn();
					$allRow=$currentSheet->getHighestRow();
					for($currentRow=1;$currentRow<=$allRow;$currentRow++){
						for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
							$address=$currentColumn.$currentRow;
							$data[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
						}
					}
					unset($data[1]);
					foreach ($data as $k=>$v){
						$account['account']='YX'.$v['D'];
						$account['reg_phone']=$v['D'];
						$account['binding_phone']=$v['E'];
						$account['pwd']= 'yx'.substr($v['D'], -6);
						$account['ctime']=date('Y-m-d H:i:s');
						$user['ctime']=date('Y-m-d H:i:s');
						$user['fuserid'] = $v['A'];
						$user['name'] = $v['B'];
						$user['idnumber'] = $v['C'];
						$user['yx_account'] = 'YX'.$v['D'];
						$user['channel'] = $v['F'];//import_excel_user
						$res = $this->Yinxin_model->import_excel_user($account,$user);
						if ($res){
							echo json_encode($res);die;
						}
					}
					echo json_encode(['code' => 1, 'msg' => '信账户添加成功！']);die;
				}else{
					$errors = $this->upload->display_errors();
					echo json_encode(['state'=>0,'err'=>$errors]);die;
				}
			}
		}
	}
	//创建文件目录
	public function mkdir_we($dir)
	{
		if (!file_exists($dir)){
			mkdir ($dir,0777,true);
			return 1;
		} else {
			return 1;
		}
	}

	/**
	 * 批量爬取数据
	 */
	public function check_rep()
	{
		$checkres = $this->Yinxin_model->update_check_status();
		if($checkres){
			$res_arr = ['code'=>1,'msg'=>'批量审核完成！'];
		}else{
			$res_arr = ['code'=>0,'msg'=>'批量审核失败！'];
		}
		echo json_encode($res_arr);die;
	}

	public function get_account()
	{
		$count_res = $this->db->count_all_results('fms_yx_account');
		print_r($count_res);die;
	}

}