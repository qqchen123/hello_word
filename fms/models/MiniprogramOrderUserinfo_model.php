<?php 

/**
 * @desc 小程序报单客户信息
 */
class MiniprogramOrderUserinfo_model extends Admin_Model
{
	public $table_name = 'fms_miniprogram_order_userinfo';
	
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 保存数据
	 * @param $data array
	 * @return bool save result
	 */
	public function record_data($data)
	{
		if (empty($data['bd_id'])) {
			return false;
		}
		if (empty($this->find_by_id($data['bd_id']))) {
			//insert
			$relation = [];
			$relation[$data['sort']] = $data['img_path'];
			$data['relation_img'] = json_encode($relation, JSON_UNESCAPED_UNICODE);
			//加载写入的数据
			$array = [
				'name' => !empty($data['name']) ? $data['name'] : '',
				'idnumber' => !empty($data['idnumber']) ? $data['idnumber'] : '',
				'qfjg' => !empty($data['qfjg']) ? $data['qfjg'] : '',
				'able_start' => !empty($data['able_start']) ? $data['able_start'] : '',
				'able_end' => !empty($data['able_end']) ? $data['able_end'] : '',
				'birth_area' => !empty($data['birth_area']) ? $data['birth_area'] : '',
				'sex' => !empty($data['sex']) ? $data['sex'] : '',
				'birth' => !empty($data['birth']) ? $data['birth'] : '',
				'c_time' => date('Y-m-d H:i:s', time()),
				'remark1' => !empty($data['remark1']) ? $data['remark1'] : '',
				'remark2' => !empty($data['remark2']) ? $data['remark2'] : '',
				'bd_id' => $data['bd_id'],
				'relation_img' => !empty($data['relation_img']) ? $data['relation_img'] : '',
			];
			return $this->db->insert($this->table_name, $array);
		} else {
			//update
			$temp = $this->find_by_id($data['bd_id']);
			$temp['relation_img'] = json_decode($temp['relation_img'], true);
			$temp['relation_img'][$data['sort']] = $data['img_path'];
			//加载更新的数据
			$array = [];
			if (!empty($data['name'])) { $array['name'] = $data['name'];}
			if (!empty($data['idnumber'])) { $array['idnumber'] = $data['idnumber'];}
			if (!empty($data['qfjg'])) { $array['qfjg'] = $data['qfjg'];}
			if (!empty($data['able_start'])) { $array['able_start'] = $data['able_start'];}
			if (!empty($data['able_end'])) { $array['able_end'] = $data['able_end'];}
			if (!empty($data['birth_area'])) { $array['birth_area'] = $data['birth_area'];}
			if (!empty($data['sex'])) { $array['sex'] = $data['sex'];}
			if (!empty($data['birth'])) { $array['birth'] = $data['birth'];}
			if (!empty($data['remark1'])) { $array['remark1'] = $data['remark1'];}
			if (!empty($data['remark2'])) { $array['remark2'] = $data['remark2'];}
			if (!empty($data['img_path'])) { $array['relation_img'] = json_encode($temp['relation_img'], JSON_UNESCAPED_UNICODE);}
			if (empty($array)) {
				return false;
			}
			return $this->db->update($this->table_name, $array, " bd_id = '" . $data['bd_id'] . "'");
		}
		return false;
	}

	/**
	 * @name 获取某一个订单的用户信息
	 * @param $id 
	 */
	public function find_by_id($bd_id)
	{
		return $this->db->where('bd_id', $bd_id)
		->get($this->table_name)
		->row_array();
	}


}

?>
