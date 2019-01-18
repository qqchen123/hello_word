<?php
require('../fms/models/PHPExcel.php');///usr/share/nginx/html/fms/models
defined('BASEPATH') OR exit('No direct script access allowed');

class Userexcel extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->need_login = TRUE;//控制是否需要登录
		$this->load->model('Order_model');
	}

	//交易管理（上传代付文件）
	public function test10()
	{
		$this->newview('trsaction_mg2');
	}

	/**
	 * 解析Excel
	 * @throws PHPExcel_Exception
	 * @throws PHPExcel_Reader_Exception
	 */
	public function do_excel($file_pn)
	{
		if ( ! $file_pn)
		{
			$filename = './yugui.xls';
		} else
		{
			$filename = $file_pn;
		}
		include_once('../fms/models/PHPExcel/Reader/Excel5.php');
		$PHPReader = new \PHPExcel_Reader_Excel5();
		$PHPExcel = $PHPReader->load($filename);
		$currentSheet = $PHPExcel->getSheet(0);
		$allColumn = $currentSheet->getHighestColumn();
		$allRow = $currentSheet->getHighestRow();
		for ($currentRow = 1; $currentRow <= $allRow; $currentRow++)
		{
			for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++)
			{
				$address = $currentColumn.$currentRow;
				$data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
			}
		}
		foreach ($data as $k => $v)
		{
			if ($k == 0 || $k == 1 || $k == 2 || $k == 3 || $k == 4 || $k == 5)
			{
				unset($data[$k]);
			}
		}

		$data = array_values($data);
		$bussiness_num = $data[0]['B'];//商户批次号
		foreach ($data as $k => $v)
		{
			if ($k == 0 || $k == 1 || $k == 2)
			{
				unset($data[$k]);
			}
		}
		$data = array_values($data);
		foreach ($data as $k => $v)
		{
			$map[$k]['bussiness_account'] = $v['A'];
			$map[$k]['username'] = $v['B'];
			$map[$k]['account'] = $v['C'];
			$map[$k]['account_type'] = $v['D'];
			$map[$k]['trade_money'] = $v['E'];
			$map[$k]['account_num'] = $v['F'];
			$map[$k]['bank_name'] = $v['G'];
			$map[$k]['use'] = $v['H'];
			$map[$k]['remarks'] = $v['I'];
			$map[$k]['up_date'] = date('Y-m-d H:i:s');
			$map[$k]['business_pc_num'] = $bussiness_num;
		}
		$mres = $this->Order_model->insert_excel_info($map);
		if ($mres)
		{
			echo json_encode(['code' => '1', 'message' => '上传成功！']);
		} else
		{
			echo json_encode(['code' => '0', 'message' => '导入信息失败！']);
		}
	}

	// excel文件上传
	public function excel_uploadify_file()
	{
		$config['upload_path'] = '../upload/yuguiExcel/'.date('Y-m-d H:i:s');//服务器路径
		$this->mkdir_we($config['upload_path']);
		$_SESSION['del_excel_path'] = $config['upload_path'];
		$config['allowed_types'] = 'xls|xlsx';
		$config['max_size'] = 100000000000;
		$config['file_name'] = uniqid();
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		foreach ($_FILES as $field => $file)
		{
			if ($file['error'] == 0)
			{
				if ($this->upload->do_upload($field))
				{
					$data = $this->upload->data();
					$filename = $data['full_path'];
					$f_res = $this->do_excel($filename);
				} else
				{
					$errors = $this->upload->display_errors();
					echo json_encode(['state' => 0, 'err' => $errors]);
					die;
				}
			}
		}
	}

	//创建文件目录
	public function mkdir_we($dir)
	{
		if ( ! file_exists($dir))
		{
			mkdir($dir, 0777, TRUE);

			return 1;
		} else
		{
			return 1;
		}
	}

	/**
	 * 获取一个批次下的汇总信息--交易管理(交易明细查询)
	 */

	public function test9()
	{
//		echo '99';die;
		$this->load->library('pagination');//加载分页库
		$this->load->helper('url');
		$count = $this->Order_model->allnums('daifu_order_b_pc_num');
		$config['base_url'] = site_url('Userexcel/test9');
		$config['total_rows'] = $count;
		$config['per_page'] = '2';
		$config['first_link'] = '首页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['last_link'] = '末页';
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		$offset = intval($this->uri->segment(3));
		$data['users'] = $this->Order_model->get_sum_info($offset, $config['per_page'], 'daifu_order_info');
		$data['link'] = $this->pagination->create_links();
		// $this->load->view('trsaction_mg1_1', ['order' => $data], TRUE);
		$this->newview('trsaction_mg1', ['order' => $data]);
	}

	public function get_order_info()
	{
		$res = $this->Order_model->select_order();
		echo json_encode($res);
		die;
	}

	//交易管理（查询详情）
	public function test19()
	{
		$get_business_pc_num = $this->input->get();
		if ( ! $get_business_pc_num)
		{
			$get_business_pc_num['business_pc_num'] = '';
		}
		$this->load->library('pagination');//加载分页库
		$this->load->helper('url');
		$count = $this->Order_model->allnums('daifu_order_info');
		$config['base_url'] = site_url('Userexcel/test19');
		$config['total_rows'] = $count;
		$config['per_page'] = '5';
		$config['first_link'] = '首页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['last_link'] = '末页';
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		$offset = intval($this->uri->segment(3));
		$data['users'] = $this->Order_model->select_order(
			$offset,
			$config['per_page'],
			'daifu_order_info',
			$get_business_pc_num['business_pc_num']
		);
		$data['link'] = $this->pagination->create_links();
		// $this->load->view('trsaction_mg1_1', ['order' => $data], TRUE);
		$this->newview('trsaction_mg1_1', ['order' => $data]);
	}

	public function get_sum_num()
	{
		$sumres = $this->Order_model->get_sum_num();

		print_r($sumres);
		die;
	}

	public function get_sum_info()
	{
		$sumres = $this->Order_model->get_sum_info();

		print_r($sumres);
		die;
	}

	/**
	 * 验证码
	 */
	public function verify2()
	{
		$this->load->helper('captcha');
		$_SESSION['codeall'] = rand(1000, 10000);
		$code = $_SESSION['codeall'];
		$vals = [
			'word' => $_SESSION['codeall'],
			'img_path' => '../upload/',
			'img_url' => base_url().'/uploads/',
			//'font_path' => './path/to/fonts/texb.ttf',
			'img_width' => '80',
			'img_height' => 30,
			'expiration' => 7200,
			'word_length' => 4,
			'font_size' => 30,
			'img_id' => 'Imageid',
			'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
		];
		$cap = create_captcha($vals);
		echo $cap['image'];
	}

	public function check_vefify()
	{
		$code = $this->input->get('captcha');
		if ( ! empty($code) && $code == $_SESSION['codeall']) // 用户输入的验证码，根据逻辑，自行处理吧，大概就是这么个意思。
		{
			$data['success_info'] = '验证成功';
			session_destroy();
		}
	}

//	public function f_detail2()
//	{
////		print_r($_GET);
//		print_r($_POST);
//	}

}
