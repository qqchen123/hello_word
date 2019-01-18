<?php 

/**
 * @name 小程序房屋估值记录
 */
class MiniproHousePrice_model extends Admin_Model
{
	public $table_name = 'fms_minipro_house_price';
	
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 通过id 获取某一条记录
	 * @param $id int id
	 * @return row array
	 */
	public function find_by_id($id)
	{
		return $this->db->select([
			'id','diYaZongJia','img_path','houseName','finish_date', 'house_area', 'gui_hua_yong_tu', 'diYaDanJia', 'fangDaiZheKou', 'ZheKouZongJia'
		])
		->where('id', $id)
		->get($this->table_name)
		->row_array();
	}

	/**
	 * @name 通过id array 获取某一批记录
	 * @param $ids array ids
	 * @return array
	 */
	public function find_by_ids($ids)
	{
		if (empty($ids)) {
			return [];
		}
		
		return $this->db->select([
			'id','diYaZongJia','img_path', 'houseName','finish_date', 'house_area', 'gui_hua_yong_tu'
		])
		->where_in('id', $ids)
		->get($this->table_name)
		->result_array();
	}

	/**
	 * @name 更新报单数据
	 * @param $data array 更新的数据
	 * @param $bd_id 条件
	 * @return bool
	 */
	public function update_record($data, $house_id)
	{
		return $this->db->update($this->table_name, $data, ' id=\'' . $house_id  .'\'');
	}

	function add($data){
		$this->db->insert($this->table_name,$data);
		return $this->db->insert_id();
	}

	function edit_baodan_status($id){
		$this->db->update($this->table_name,['status'=>1],['id'=>$id]);
	}

	function del($id){
		$this->db->delete($this->table_name,['status'=>0,'id'=>$id]);
	}
}


?>
