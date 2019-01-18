<?php 

/**
 * @desc 上传文件 保存文件名与原文件名映射关系表 上传文件自动记录
 */
class UploadFileName_model extends Admin_model
{
	/**
	 * @name model 操作的表名
	 */
	public $table_name = 'fms_upload_file_name';

	/**
	 * @name 默认显示的字段
	 */
	public $show_fields = [
		'type',
		'type_id',
		'second_type',
		'file',
		'ext',
		'name'
	];

	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 写入对照关系
	 * @param string $type 大类
	 * @param string $type_id 大类ID	
	 * @param string $second_type 小类
	 * @param string $file 保存的文件名
	 * @param string $ext 文件后缀名
	 * @param string $name 上传的原文件名
	 * @return insert id
	 */
	public function insert_record($type, $type_id, $second_type, $file, $ext, $name)
	{
		$ctime = date('Y-m-d H:i:s', time());
		$data = [
			"type" => $type,
			"type_id" => $type_id,
			"ctime" => $ctime,
			"second_type" => $second_type,
			"file" => $file,
			"ext" => $ext,
			"name" => $name,
		];
		$this->db->insert($this->table_name, $data);
		if ($this->db->affected_rows() > 0) {
        	$id = $this->db->insert_id();
            return $id;
        }
        return 0;
	}

	/**
	 * @name 查询记录
	 * @param string $type 大类
	 * @param string $type_id 大类ID
	 * @param string $second_type 小类
	 * @param string $file 保存的文件名
	 * @param string $ext 文件后缀名
	 * @return array 查询到的内容
	 */
	public function find_record($type, $type_id, $second_type, $file, $ext)
	{
		return $this->db->where(" `type` = '" . $type . "' AND `type_id` = '" . $type_id . "' AND `second_type` = '" . $second_type . "' AND `file` = '" . $file . "' AND `ext` = '" . $ext . "'")
		->order_by('ctime', 'DESC')
		->get($this->table_name)
		->row_array();
	}

}