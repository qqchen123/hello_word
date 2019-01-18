<?php

/**
 *
 */
class My_Controller extends CI_Controller {
//	public    $need_login = true;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->check_login();
	}

	public function newview($page, $data = [])
	{
		$data = ['p' => $page, 'data' => $data];
		$this->load->view('layout', $data);
	}

	/**
	 * 登陆验证
	 */
	private function check_login()
	{
//		if($this->need_login){
		$session_data = $this->session->userdata('user_info');
		if ( ! $session_data)
		{
			redirect('/Login/test1', 'refresh');
		}
//		}

	}
}


class My_Service {
	/**
	 * @name 单例模式
	 * @var object
	 */
	private static $instance;


	/**
	 * @name 助手
	 * @var object
	 */
	public $load;


	public function __construct()
	{
		self::$instance =& $this;

		foreach (is_loaded() as $var => $class)
		{
			if (isset($this->$var))
			{
				$this->$var =& load_class($class);
			}
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();

		//提前加载 helper
		// $this->load->helper(['tools']);
	}

	/**
	 * @name 单例模式
	 * @static
	 * @return  object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	function __get($key)
	{
		$CI = &get_instance();

		return $CI->$key;
	}

}

?>
