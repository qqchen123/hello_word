<?php 

/**
 * @desc 籍贯表
 * @notice 
 */
class NativePlace_model extends Admin_model
{
	/**
	 * @name model 操作的表名
	 */
	public $table_name = 'fms_jiguan';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @name 获取籍贯
	 * @param $areacode
	 * @return array
	 */
	public function get_prov($placecode)
	{
		if($placecode != '') $this->db->where("placecode = '".$placecode."'");
		$ret = $this->db->get('fms_jiguan')->result_array();
    	return $ret;
	}

	//原 Qiye getprov
	public function get_prov_adr($areacode)
	{
	    return current($this->db->query("select jiguanadr from fms_jiguan where placecode=?",array($areacode))->result_array());
    }
}