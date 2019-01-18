<?php
class Tools_model extends CI_Model {
	private $file_tab = 'tools';

	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
	}
	//插入文件信息
	public function tool_file_insert($data)
	{
		return $this->db->insert($this->file_tab,$data);
	}
	//获取文件信息
	public function tool_file_select($page ='' ,$first = '', $where='')
	{
		if ($where){
			$this->db->where($where);
		}
		if ($page!=='' && $first !== ''){
			$this->db->limit($page,$first);
		}
		$res['rows'] = $this->db->where('status',1)->get($this->file_tab)->result_array();
		$res['total'] = $this->db->where('status',1)->count_all_results($this->file_tab);
		return $res;
	}
	//逻辑删除文件信息
	public function logic_del($id = '',$data ='')
	{
		return $delres = $this->db->where($id)->update($this->file_tab,$data);
	}

	public function get_combobox_word_info($name='')
	{
		if ($name){
			$res = $this->db->where('file_name',$name)->where('f_type',1)->where('status',1)->get($this->file_tab)->row_array();
			return $res;
		}
		$res = $this->db->where('f_type',1)->where('status',1)->get($this->file_tab)->result_array();
		return $res;
	}
	/**
	 * 获取一条数据
	 */
	public function get_one_file($id)
	{
		$file = $this->db->where('id',$id)->get($this->file_tab)->row_array();
		return $file;
	}

}