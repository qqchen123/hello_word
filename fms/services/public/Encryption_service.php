<?php 

/**
 * @desc 加密service
 */
class Encryption_service extends Admin_service
{
	
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name MD5
	 */
	public function md5Hash($str)
	{
		return hash('md5', $str);
	}

	/**
	 * @name 上传文件 文件名生成
	 */
	public function upload_file_name($file_name) 
	{
		return hash('md5', $file_name) . date('YmdHis', time());
	}

}