<?php

$env = 'local2';
if ('local' == $env)
{
	require_once "./models/PHPExcel.php";
	require('../vendor/autoload.php');
} else
{
	require('/www/guzzle-master/vendor/autoload.php');
	require('/www/PHPWord-develop/vendor/autoload.php');
}

class Tools extends Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tools_model');
		$this->load->library('zip');
		$this->load->helper('download');
	}

	public function show_tool()
	{
		$this->showpage('fms/excel_word_tools');
	}

	//获取文件内容word
	public function get_file_info()
	{
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$first = $rows * ($page - 1);
		$where = ['f_type' => 1];
		$f_res = $this->Tools_model->tool_file_select($rows, $first, $where);
		echo json_encode($f_res);
		die;
	}

	//获取文件内容word
	public function get_combobox_word_info()
	{
		$f_res = $this->Tools_model->get_combobox_word_info();
		echo json_encode($f_res);
		die;
	}

	//获取文件内容excel
	public function get_file_info_excel()
	{
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$first = $rows * ($page - 1);
		$where = ['f_type' => 0];
		$f_res = $this->Tools_model->tool_file_select($rows, $first, $where);
		echo json_encode($f_res);
		die;
	}

	//删除文件
	public function file_del()
	{
		$id = $this->input->post('id');
		$path = $this->input->post('path');
		@unlink('..'.$path);//文件删除
		$res = $this->Tools_model->logic_del(['id' => $id], ['f_type' => 0, 'status' => 0]);
		echo $res;
	}

	// word文件上传
	public function uploadify_file()
	{
		$config['upload_path']      = './upload/'.date('Y-m-d H:i:s');//服务器路径
//		$config['upload_path'] = '/home/upload/'.date('Y-m-d H:i:s');;//服务器路径
		$this->mkdir_we($config['upload_path']);
		$config['allowed_types'] = '*';
		$config['max_size'] = 100000000000;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$map = [];
		foreach ($_FILES as $field => $file)
		{
			if ($file['error'] == 0)
			{
				if ($this->upload->do_upload($field))
				{
					$data = $this->upload->data();
					$strpos = stripos($data['full_path'], '/upload');//服务器
//                    $strpos = stripos($data['full_path'],'/fms');//本地
					$substr = substr($data['full_path'], $strpos);//echo $data['full_path'];die;
					$map['file_path'] = $substr;
					$map['c_time'] = date('Y-m-d', time());
					$map['file_name'] = $data['client_name'];
					if ($data['file_ext'] == '.xlsx' || $data['file_ext'] == '.xls')
					{
						$map['f_type'] = 0;
					} else
					{
						$map['f_type'] = 1;
					}
					$fures = $this->Tools_model->tool_file_insert($map);
					if ($fures)
					{
						$jsres = ['state' => 1, 'msg' => '上传成功！'];
						echo json_encode($jsres);
						die;
					} else
					{
						$jsres = ['state' => 0, 'msg' => '上传失败！'];
						echo json_encode($jsres);
						die;
					}
				} else
				{
					$errors = $this->upload->display_errors();
					echo json_encode(['state' => 0, 'msg' => $errors]);
					die;
				}
			}
		}
	}

	//word 文件下载
	public function download_word()
	{
		$file_res = $this->Tools_model->get_one_file($_GET['id']);
		$file_name = $file_res['file_name'];
		$file_dir = $file_res['file_path'];
		$file_path = $file_dir;
		$data = file_get_contents('/home'.$file_path);
		force_download($file_name,$data);
	}

	// excel文件上传
	public function excel_uploadify_file()
	{
//		$config['upload_path'] = '../upload/excel/'.date('Y-m-d H:i:s');//服务器路径
		$config['upload_path'] = '/home/upload/'.date('Y-m-d H:i:s');;//服务器路径
		$this->mkdir_we($config['upload_path']);
		$_SESSION['del_excel_path'] = $config['upload_path'];
		$config['allowed_types'] = 'xls|xlsx';
		$config['max_size'] = 100000000000;
		$config['file_name'] = uniqid();
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$map = [];
		foreach ($_FILES as $field => $file)
		{
			if ($file['error'] == 0)
			{
				if ($this->upload->do_upload($field))
				{
					$data = $this->upload->data();
					$filename = $data['full_path'];
					$exts = substr($filename, (strrpos($filename, ".") + 1));
					if ($exts == 'xls')
					{
						include_once('./models/PHPExcel/Reader/Excel5.php');
						$PHPReader = new \PHPExcel_Reader_Excel5();
					} else
					{
						if ($exts == 'xlsx')
						{
							include_once('./models/PHPExcel/Reader/Excel2007.php');
							$PHPReader = new \PHPExcel_Reader_Excel2007();
						}
					}
					//载入文件
					$PHPExcel = $PHPReader->load($filename);
					//获取Excel中的所有sheet名称
					$sheetname = $PHPExcel->getSheetNames();
					foreach ($sheetname as $k => $v)
					{
						$arr[$k]['sheet'] = $v;
						$arr[$k]['id'] = $k;
						$arr[$k]['file'] = $filename;
					}
					echo json_encode($arr);
					die;
				} else
				{
					$errors = $this->upload->display_errors();
					echo json_encode(['state' => 0, 'err' => $errors]);
					die;
				}
			}
		}

	}


	public function excel_to_word()
	{
		$sheet = $this->input->post('sheet');
		$word_path = '/home'.$this->input->post('word_path');
		$excel_path = $this->input->post('excel_path');
		$filename = $excel_path;
		$exts = substr($filename, (strrpos($filename, ".") + 1));
		if ($exts == 'xls')
		{
			include_once('./models/PHPExcel/Reader/Excel5.php');
			$PHPReader = new \PHPExcel_Reader_Excel5();
		} else
		{
			if ($exts == 'xlsx')
			{
				include_once('./models/PHPExcel/Reader/Excel2007.php');
				$PHPReader = new \PHPExcel_Reader_Excel2007();
			}
		}
		//载入文件
		$PHPExcel = $PHPReader->load($filename);
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
		$currentSheet = $PHPExcel->getSheet($sheet);
		//获取Excel中的所有sheet名称
		$sheetname = $PHPExcel->getSheetNames()[$sheet];
		//获取总列数
		$allColumn = $currentSheet->getHighestColumn();
		//获取总行数
		$allRow = $currentSheet->getHighestRow();
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for ($currentRow = 1; $currentRow <= $allRow; $currentRow++)
		{
			//从哪列开始，A表示第一列
			for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++)
			{
				//数据坐标
				$address = $currentColumn.$currentRow;
				//读取到的数据，保存到数组$arr中
				$data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
			}
		}
		foreach ($data as $k => $v)
		{
			if ($k == 1)
			{
				unset($data[$k]);
			}
			if (empty($v['A']))
			{
				unset($data[$k]);
			}
		}
		if (empty($data))
		{
			$err = ['status' => -1, 'msg' => 'excel不能为空！'];
			echo json_encode($err);
			die;
		}
		$arr = array_values($data);
		if ( ! empty($arr))
		{
			foreach ($arr[0] as $k => $v)
			{
				$excel_key_arr[] = $k;
			}
		}
		$dir_path = '/home/upload/zip/'.date('Y-m-d', time()).'/'.md5(time().rand(1111, 9999));
		$this->mkdir_we($dir_path);
		foreach ($arr as $k => $v)
		{
			$this->read_word1($v, $excel_key_arr, $word_path, $dir_path, $sheetname);
		}
		$this->del_dir($_SESSION['del_excel_path'].'/');
		unset($_SESSION['del_excel_path']);
		$this->dozip($dir_path);
	}


	public function read_word1(
		$data,
		$excel_key_arr,
		$word_path,
		$dir_path,
		$sheetname
	) {
		$filename = $word_path;
		$phpWord = new \PhpOffice\PhpWord\TemplateProcessor($filename);
		foreach ($excel_key_arr as $k => $v)
		{
			$phpWord->setValue($v, $data[$v]);
		}
		$phpWord->saveAs($dir_path.'/'.$sheetname.'.docx');
	}

	//创建文件目录

	public function mkdir_we($dir) {
		if ( ! file_exists($dir))
		{
			mkdir($dir, 0777, TRUE);

			return 1;
		} else
		{
			return 1;
		}
	}

	// 去压缩

	public function dozip($path) {
		$this->zip->read_dir($path, FALSE);//开始压缩指定路径的文件夹，清除里面的结构。
		$this->zip->download('my_backup.zip');//下载压缩后的的文件。
	}

//删除文件

	public function del_dir($dir = './upload/', $type = TRUE) {
		$n = 0;
		if (is_dir($dir))
		{
			if ($dh = opendir($dir))
			{
				while (($file = readdir($dh)) !== FALSE)
				{
					if ($file == '.' or $file == '..')
					{
						continue;
					}
					if (is_file($dir.$file))
					{
						@unlink($dir.$file);
						$n++;
					}
					if (is_dir($dir.$file))
					{
						$this->del_dir($dir.$file.'/');
						if ($type)
						{
							$n++;
							rmdir($dir.$file.'/');
						}
					}
				}
			}
			closedir($dh);
		}

		return $n;
	}


	public function test_excel()
	{
//		print_r($_POST);die;
		$arr_id = $this->input->post('id');
		$excel_path = $this->input->post('excel_path');
		$pos_path = strpos($excel_path, '/upload');
		$sub_path = substr($excel_path, $pos_path);
		$filename = $excel_path;
		$exts = substr($filename, (strrpos($filename, ".") + 1));
		if ($exts == 'xls')
		{
			include_once('./models/PHPExcel/Reader/Excel5.php');
			$PHPReader = new \PHPExcel_Reader_Excel5();
		} else
		{
			if ($exts == 'xlsx')
			{
				include_once('./models/PHPExcel/Reader/Excel2007.php');
				$PHPReader = new \PHPExcel_Reader_Excel2007();
			}
		}
//		$PHPReader=new \PHPExcel_Reader_Excel2007();
		$PHPExcel = $PHPReader->load($excel_path);
		$sheet_names_arr = $PHPExcel->getSheetNames();
		$f_res = $this->Tools_model->get_combobox_word_info($sheet_names_arr[$arr_id].'.docx');
		echo json_encode($f_res);
	}


}