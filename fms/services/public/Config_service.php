<?php

/**
 * @desc 配置服务
 */
class Config_service extends Admin_service
{
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

    /**
     * @name 加载配置项
     * @param $name conf_name
     * @param $type 
     * @return array
     */
    function load_config($name, $type)
    {
        $this->load->service('business/'.$type, 'conf_service');
        return $this->conf_service->$name;
    }

    
}