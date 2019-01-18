<?php

/**
 * @desc 数组处理
 */
class Array_service extends Admin_service
{
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

    /**
     * @name 对象转数组
     * @param object $object
     * @return array 
     */
	function object_to_array($object) 
    {
        $array = [];
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        }
        else {
            $array = $object;
        }
        return $array;
    }
}