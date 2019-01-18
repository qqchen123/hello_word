<?php

class Admin_Model extends CI_Model
{
    /**
     * @name 单例模式
     * @var object
     */
    private static $instance;

    public $table_name;

    /**
     * @name 助手
     * @var object
     */
    private $load;

    /**
     * @name db
     * @var object
     */
    private $db;

    /**
     * @name 默认显示的字段
     */
    private $show_fields = ['id'];

    /**
     * @name 受保护的字段
     */
    private $protect_fields = [];

    /**
     * @name construct
     */
    public function __construct()
    {
        parent::__construct();
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
     * @name 获取最后一次插入的ID  低频操作数据库下可用 目前可用
     */
    public function getLastInsertId()
    {
        return $this->db->insert_id();
    }

    /**
     * @name 关联公共状态
     * @param string $type_name 关联的公共状态类型
     * @param string $join_key 用于关联的key
     */
    public function join_public_status($type_name, $join_key)
    {
        $this->load->helper('publicstatus');
        joinStatus($type_name, $join_key);
    }

    /**
     * @name 绑定记录到公共状态
     * @param string $type_name 关联的公共状态类型
     * @param string $record_id 记录的ID
     * @return bool
     */
    public function add_public_status($type_name, $record_id)
    {
        $this->load->helper('publicstatus');
        return addStatus($type_name, $record_id);
    }
        
    /**
     * @name select 的内容
     * @param array $data [原名, [别名 => 原名] ...] 可以是两种写法混合
     * @param int $public_status 是否有联表查询
     */
    public function ret_select(array $data = [], $public_status, $type = '')
    {
        $str = '';
        $prefix = !empty($public_status) ? $this->table_name . '.' : '';
        // SLog::log('table: ' . $this->table_name);
        if (empty($data)) {
            $data = $this->show_fields;
        }
        foreach ($data as $value) {
            if (is_array($value)) {
                $tmp = array_keys($value);
                if (in_array($value[$key], $this->protect_fields)) {
                    continue;
                }
                if (empty($str)) {
                    $str = $prefix . $value[$key] . ' as ' . $tmp[0]; 
                } else {
                    $str .= ', ' . $prefix . $value[$key] . ' as ' . $tmp[0];
                }
            } else {
                if (in_array($value, $this->protect_fields)) {
                    continue;
                }
                if (empty($str)) {
                    $str = $prefix . $value;
                } else {
                    $str .= ', ' . $prefix . $value;
                }
            }
        }
        //公共状态的字段
        if ($public_status && !empty($type)) {
            $str .= ', ' . $type . '_status.* '; 
        }
        if (preg_match('/;/', $str)) {
            $str = '';
        }
        return $str;
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

    /**
     * @name 获取表名
     */
    public function get_table_name()
    {
        return $this->table_name;
    }

    

}