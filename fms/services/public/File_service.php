<?php 

/**
 * @desc 文件|图片 service
 * @other 原方法
 *			
 *		  增强型
 *
 *		  结合页面型
 * 文件上传的同时 新的文件名与上传的文件名需要做关联 存放在 fms_upload_file_name中
 * 文件上传的层级结构： 大类名+大类ID/小类名/文件名.后缀
 *
 */
class File_service extends Admin_service
{
	/**
	 * @var array 大类
	 */
	public $type_array = [];
	
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->type_array = $this->config->item('file_upload_type');
	}

	//######################################
	/**
	 * @name 上传正反面照片
	 * @param string $no 号码 身份证号码
	 * @param string $file_dir_type 文件夹类型 idnumber mobile bank
	 * @return array 
	 */
	public function upload_img_front_and_back($no, $file_dir_type)
	{
		$ret = [];
		$this->load->service('public/Encryption_service', 'es');
		$file = $this->es->md5Hash($no);
		$dirpath = '../upload/' . $file . '/' . $file_dir_type;
		//检查是否已经有客户的文件夹
		if (!$this->mkdir_idnumber($dirpath)) {
			return [
				'code' => 0,
				'message' => '生成文件夹目录失败',
			];
		} else {
			foreach ($_FILES as $key => $value) {
				if (!empty($value['size'])) {
					Slog::log('上传图片入参:'. json_encode([$dirpath,$value['name'],$file_dir_type]));
					$refileinfo = $this->upload_file(
						$dirpath, //路径
						$key, //样本名
						$file_dir_type
					);
					if (!$refileinfo['code']) {
						return [
							'code' => 0,
							'message' => '上传失败',
						];
					} else {
						$ret['data'][$key] = preg_replace('/\.\./','',$refileinfo['data']);
					}
				}
			}
			
			//文件目录信息
			$ret['data'][$key . 'file'] = $file;
			$ret['code'] = 1;
			$ret['message'] = '正常';
			return $ret;
		}
	}

	/**
	 * @name 上传照片
	 * @param string $type 大类
	 * @param string $type_id 大类ID
	 * @param string $file_dir_type 小类
	 * @return array 
	 */
	public function upload_img($type, $type_id, $file_dir_type)
	{
		$ret = [];
		$dirpath = '../upload/' . $type . $type_id . '/' . $file_dir_type;
		//检查是否已经有客户的文件夹
		if (!$this->mkdir_idnumber($dirpath)) {
			return [
				'code' => 0,
				'message' => '生成文件夹目录失败',
			];
		} else {
			foreach ($_FILES as $key => $value) {
				if (!empty($value['size'])) {
					Slog::log('上传图片入参:'. json_encode([$dirpath, $value['name'], $file_dir_type]));
					$refileinfo = $this->upload_file(
						$dirpath, //路径
						$key, //样本名
						$file_dir_type
					);
					if (!$refileinfo['code']) {
						return [
							'code' => 0,
							'message' => '上传失败',
						];
					} else {
						$ret['data'][$key] = preg_replace('/\.\./','',$refileinfo['data']);
					}
				}
			}
			
			//文件目录信息
			$ret['data'][$key . 'file'] = $type . $type_id;
			$ret['code'] = 1;
			$ret['message'] = '正常';
			return $ret;
		}
	}

	/**
	 * @name 文件夹检查
	 * @param 路径
	 * @return bool
	 */
	public function mkdir_idnumber($path)
	{
		if (is_dir($path)){  
			return true;
		}else{
			$res=mkdir($path,0777,true);
			if ($res){
				//"mkdirok"
				return true;
			}else{
				//"mkdirerr"
				return false;
			}	
		}	
	}

	/**
	 * @name 图片上传配置参数
	 * @return array 
	 */
	public function img_config()
	{
		$config = [];
		$config['allowed_types']    = 'gif|jpg|png';//允许上传的类型
		$config['max_size']     	= 5120;//最大上传图片大小
		$config['max_width']        = 6000;//最大上传图片宽度
		$config['max_height']       = 4000;//最大上传图片高度
		return $config;
	}

	/**
	 * @name 上传文件
	 * @param $dir 路径
	 * @param $idnumfile 文件夹名
	 * @param $notice 报错时返回的提示文字
	 * @return array
	 */
	public function upload_file($dir, $idnumfile, $file_dir_type)
	{
		$refileinfo = $this->file_up($dir, $idnumfile, $file_dir_type);
		if($refileinfo == 'uperr' || $refileinfo == 'rnerr'){
			Slog::log('文件上传失败 错误码 :' . $refileinfo . '||入参: ' . json_encode([$dir, $file_dir_type, $idnumfile]));
			return [
				'code' => 0,
				'data' => '',
			];
		}else{
			return [
				'code' => 1,
				'data' => $dir . '/' . $refileinfo,
			]; 
		}
	}

	/**
	 * @name 执行上传
	 * @param string $dirpath 文件路径
	 * @param string $upfilename 上传的文件在$_File中的key名
	 * @param string $file_dir_type 小类名
	 * @param string $newfile 新的文件名 可选
	 * @param string $file_type 上传的文件类型
	 * @return string 上传结果
	 */
	public function file_up($dirpath, $upfilename, $file_dir_type, $file_type = 'img', $newfile = '')
	{
		$this->load->service('public/Encryption_service', 'es');
		switch ($file_type) {
			case 'img':
				$config = array_merge($this->img_config(), ['upload_path' => $dirpath]); //上传路径
				break;
			default:
				Slog::log('入参异常 需要检查 入参:' . json_encode([$dirpath, $upfilename, $file_dir_type, $file_type], JSON_UNESCAPED_UNICODE) );
				$config = array_merge($this->img_config(), ['upload_path' => $dirpath]); //上传路径
				break;
		}
		$this->load->library('upload', $config);
		Slog::log('上传文件名:' . $upfilename);
		if ( ! $this->upload->do_upload($upfilename)) {
			return 'uperr';
		} else {
			$imgdata = $this->upload->data();
			if (empty($newfile)) {
				$newfile = $this->es->upload_file_name($imgdata['file_name']);
			}
			if (rename($dirpath . '/' . $imgdata['file_name'], $dirpath . '/' . $newfile . $imgdata['file_ext'])) {
				//根据路径 获取大类 大类ID
				$tmp = explode("/", $dirpath);
				foreach ($this->type_array as $value) {
					if (-1 != preg_match('/' . $value . '/', $tmp[count($tmp)-2])) {
						$type = $value;
						$type_id = preg_replace('/' . $value . '/', '', $tmp[count($tmp)-2]);
						break;
					}
				}
				$this->load->model('public/UploadFileName_model', 'ufnm');
				$this->ufnm->insert_record(
					$type, 
					$type_id, 
					$file_dir_type, 
					$newfile, 
					preg_replace('/\./', '', $imgdata['file_ext']), 
					$imgdata['file_name']
				);
				return $newfile . $imgdata['file_ext'];
			} else {
				return 'rnerr';
			}
		}
	}

	/**
	 * @name 删除文件
	 */
	public function file_delete($file_name, $dir = '../upload/')
	{
		$ret = 'null';
		if (file_exists($dir . $file_name)) { 
			if (unlink($dir . $file_name)) {
				$ret = 'ok';
			} else {
				$ret = 'err';
			}
		}
		return $ret;
	}

	/**
	 * @name 图片转码成base64
	 * @param file_name string 文件名
	 * @return string 图片编码
	 */
	function base64EncodeImage($file_name) {
		$base64_image = '';
		$base64_image = 'data:' . $_FILES['file'][$file_name]['mime'] . ';base64,' . chunk_split(base64_encode(file_get_contents($_FILES['file'][$file_name])));
		return $base64_image;
	}


}