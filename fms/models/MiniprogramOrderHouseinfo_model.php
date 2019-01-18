<?php 

/**
 * @desc
 */
class MiniprogramOrderHouseinfo_model extends Admin_Model
{
	public $table_name = 'fms_miniprogram_order_houseinfo';
	
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
			$array = [];
			$array[$data['sort']] = $data['remark'];
			$relation = [];
			$relation[$data['sort']] = $data['img_path'];
			$data['remark'] = json_encode($array, JSON_UNESCAPED_UNICODE);
			$data['relation_img'] = json_encode($relation, JSON_UNESCAPED_UNICODE);
			//加载写入的数据
			$array = [
				'housecode' => !empty($data['housecode']) ? $data['housecode'] : '',
				'housearea' => !empty($data['housearea']) ? $data['housearea'] : '',
				'recorddate' => !empty($data['recorddate']) ? $data['recorddate'] : '',//
				'ishousenewold' => $data['ishousenewold'],
				'c_time' => date('Y-m-d H:i:s', time()),
				'relation_img' => !empty($data['relation_img']) ? $data['relation_img'] : '',
				'remark' => !empty($data['remark']) ? $data['remark'] : '',
				'bd_id' => $data['bd_id'],
				'obligee' => !empty($data['obligee']) ? $data['obligee'] : '',
				'address' => !empty($data['address']) ? $data['address'] : '',
				'housenum' => !empty($data['housenum']) ? $data['housenum'] : '',
				'buildingarea' => !empty($data['buildingarea']) ? $data['buildingarea'] : '',
				'buildingtype' => !empty($data['buildingtype']) ? $data['buildingtype'] : '',
				'useby' => !empty($data['useby']) ? $data['useby'] : '',
				'buildingtotalfloor' => !empty($data['buildingtotalfloor']) ? $data['buildingtotalfloor'] : '',
				'buildingfinishdate' => !empty($data['buildingfinishdate']) ? $data['buildingfinishdate'] : '',
			];
			return $this->db->insert($this->table_name, $array);
		} else {
			//update
			$temp = $this->find_by_id($data['bd_id']);
			$temp['relation_img'] = json_decode($temp['relation_img'], true);
			$temp['relation_img'][$data['sort']] = $data['img_path'];

			$temp['remark'] = json_decode($temp['remark'], true);
			$temp['remark'][$data['sort']] = $data['remark'];
			//加载更新的数据
			$array = [];
			if (!empty($data['housecode'])) { $array['housecode'] = $data['housecode'];}
			if (!empty($data['obligee'])) { $array['obligee'] = $data['obligee'];}
			if (!empty($data['address'])) { $array['address'] = $data['address'];}
			if (!empty($data['housenum'])) { $array['housenum'] = $data['housenum'];}
			if (!empty($data['buildingarea'])) { $array['buildingarea'] = $data['buildingarea'];}
			if (!empty($data['buildingtype'])) { $array['buildingtype'] = $data['buildingtype'];}
			if (!empty($data['useby'])) { $array['useby'] = $data['useby'];}
			if (!empty($data['buildingtotalfloor'])) { $array['buildingtotalfloor'] = $data['buildingtotalfloor'];}
			if (!empty($data['buildingfinishdate'])) { $array['buildingfinishdate'] = $data['buildingfinishdate'];}
			if (isset($data['ishousenewold'])) { $array['ishousenewold'] = $data['ishousenewold'];}
			if (!empty($data['housearea'])) { $array['housearea'] = $data['housearea'];}
			if (!empty($data['recorddate'])) { $array['recorddate'] = $data['recorddate'];}
			if (!empty($data['img_path'])) { $array['relation_img'] = json_encode($temp['relation_img'], JSON_UNESCAPED_UNICODE);}
			if (!empty($data['remark'])) { $array['remark'] = json_encode($temp['remark'], JSON_UNESCAPED_UNICODE);}
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
