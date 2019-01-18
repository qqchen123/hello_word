<?php 

 /**
  * 
  */
 class Admin_Service
 {
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

        foreach (is_loaded() as $var => $class) {
            if (isset($this->$var)) {
                $this->$var =& load_class($class);
            }
        }

        $this->load =& load_class('Loader', 'core');
        $this->load->initialize();

        //提前加载 helper
        $this->load->helper(['array', 'tools', 'slog']);
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
        $CI = & get_instance();
        return $CI->$key;
    }
 }