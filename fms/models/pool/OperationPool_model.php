<?php 

/**
 * @desc 各业务model 操作证据池统一方法
 * @other 第一版 直接放一起 
 */
class OperationPool_model extends Admin_model
{
	/**
	 * @name 证据池类型 默认用户证据池
	 */
	public $type = 'user';

	public $type_user = 'user';

	public $db_pool_sample = 'fms_pool_sample';

	/**
	 * @name 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pool_model', 'pm');
	}

	/**
	 * @name 通过名字换取 sample id
	 * @return array
	 */
	public function get_sample_id_by_pool_key($pool_key)
	{
		return $this->db->select('id')
		->where('pool_key', $pool_key)
		->where('is_del', 0)
		->get($this->db_pool_sample)->result_array();
	}

	/**
	 * @name 从证据池获取证据
	 * @param int $id 关系ID
	 * @param array $pool_keys 证据池样本ID
	 * @param string $type 证据池类型
	 * @return array
	 */
	public function get_data($id, $pool_keys, $type = 'user')
	{
		$ret = [];
		//先通过ID去找到数据
		$values = array_values($pool_keys);
        //从pool中获取数据
        if ($this->type_user == $type) {
        	$this->load->model('Pool_model', 'pm');
	        $pool_tmp = $this->pm->getpoolinfo_l($id, $values);
	        if (!isset($pool_tmp['code'])) {
	        	$ret['id'] = $id;
	        	foreach ($pool_tmp as $value) {
	        		foreach ($pool_keys as $key => $item) {
	        			if ($value['pool_sample_id'] == $pool_keys[$key]) {
	        				$ret[$key] = [
	        					'val' => $value['pool_val'],
	        					'status' => $value['obj_status'],
	        					'type' => $value['obj_type'],
	        					'id' => $value['obj_id'],
	        					'edit_date' => $value['edit_date']
	        				];
	        			}
	        		}
	        	}
	        }
        }
        return $ret;
	}

	/**
	 * @name 向证据池写入证据
	 * @param string $id 关系ID
	 * @param array $data 即将写入的数据
	 * @param array $pool_keys 证据池样本ID
	 * @param string $type 证据池类型
	 * @return bool
	 */
	public function record_data($id, $data, $pool_keys, $type = 'user')
	{
		$flag = 1;
		//先通过ID去找到数据
		$values = array_values($pool_keys);
        //从pool中获取数据
        $this->load->model('Pool_model', 'pm');
        $pool_tmp = $this->pm->getpoolinfo_l($id, $values);
        // Slog::log($pool_tmp);
        // Slog::log($pool_keys);
        if (isset($pool_tmp['code'])) {
        	$flag = -1;
        } else {
        	foreach ($data as $key => $value) {
    			if (!empty($pool_tmp)) {
        			$uflag = 0;
        			if (isset($pool_keys[$key]) && !empty($pool_keys[$key])) {
	        			foreach ($pool_tmp as $item) {
	        				if ($pool_keys[$key] == $item['pool_sample_id']) {
	        					if ($item['pool_val'] != $value) {
	        						if (in_array($item['obj_status'], [1,3,5,7,9])) {
	        							//修改记录
			   							$ret = $this->pm->edit_poolinfo(['pool_val' => $value], $item['pool_id']);
			   							if (!$ret) {
						        			Slog::log('修改失败');
						        			$flag = 0;
						        			return $flag;
						        		}
	        						}
	        					}
	        					$uflag = 1;
	        					break;
	        				}
				        }
				        if (!$uflag && !empty($value)) {
				        	//创建记录
			        		$ret = $this->pm->add_pool_info([
			        			'user_id' => $id,
			        			'pool_sample_id' => $pool_keys[$key],
			        			'pool_val' => $value
			        		]);
			        		if (!$ret) {
			        			$flag = 0;
			        		}
				        }
			        }
        		} else {
        			//创建记录
        			if (isset($pool_keys[$key]) && !empty($value)) {
        				$ret = $this->pm->add_pool_info([
		        			'user_id' => $id,
		        			'pool_sample_id' => $pool_keys[$key],
		        			'pool_val' => $value
		        		]);
		        		if (!$ret) {
		        			$flag = 0;
		        		}
        			}
        		}
	        }
	    }
	    return $flag;
	}

}