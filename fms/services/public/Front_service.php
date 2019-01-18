<?php

/**
 * @desc front 前端辅助 service
 */
class Front_service extends Admin_service
{
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

    /**
     * @name 前端基础返回
     * @param array $front_input 前端入参数组
     * @param array $pre_return 返回值数组
     */
    public function front_basic_return($front_input, $pre_return)
    {
        Slog::log('前端开户接口 入参: ' . json_encode($front_input, JSON_UNESCAPED_UNICODE) . '|| 返回: ' . json_encode($pre_return, JSON_UNESCAPED_UNICODE));
        echo json_encode($pre_return, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name 返回给前端 高级模式 允许执行exit中断
     * @param array $front_input 前端入参数组
     * @param array $pre_return 返回值数组
     * @param int $flag 是否需要停止
     */
    public function front_basic_plus($front_input, $pre_return, $flag = 0)
    {
        Slog::log('前端开户接口 入参: ' . json_encode($front_input, JSON_UNESCAPED_UNICODE) . '|| 返回: ' . json_encode($pre_return, JSON_UNESCAPED_UNICODE));
        echo json_encode($pre_return, JSON_UNESCAPED_UNICODE);
        if ($flag) {
            exit;
        }
    }
}