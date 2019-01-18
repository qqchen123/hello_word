<?php 

/**
 * @desc 业务规则service
 */
class Rule_service extends Admin_service
{
	
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

    /**
     * @name 手机号 号段拦截 是否通过检测
     * @param string $mobile_no 手机号
     * @return bool
     */
    public function mobile_check($mobile_no)
    {
        if (preg_match('/166[0-9]{8}/', $mobile_no)) {
            return false;
        }
        if (preg_match('/169[0-9]{8}/', $mobile_no)) {
            return false;
        }
        return true;
    }

}