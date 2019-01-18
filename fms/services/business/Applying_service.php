<?php 

/**
 * @desc 报审service
 */
class Applying_service extends Admin_service
{
    public $register_service = [];

    public $register_model = [];

    public $register_public_function = [];

    public $apply_conf = [];
	
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
        $this->load->helper(['publicstatus']);
        //加载配置项
        //获取数据

        $this->apply_conf['baoShenStatus'] = [1,3,5,7,9];

        $this->apply_conf['guoShenStatus'] = [19];

        $this->apply_conf['backShenStatus'] = [19];

        $this->apply_conf['stopStatus'] = [20];

        $this->apply_conf['startStatus'] = [40];

        $this->apply_conf['pleaseEditStatus'] = [20,40];

        $this->apply_conf['yesEditStatus'] = [23];

        $this->apply_conf['noEditStatus'] = [23];
	}

    /**
     * @name 注册模块
     */
    public function register_config($key, $name, $alias = '')
    {
        if (preg_match('/\_model/', $name)) {
            if (isset($this->register_model[$key])) {
                if ($this->register_model[$key][0] != $name) {
                    return false;
                }
            } else {
                $this->register_model[$key] = [$name, $alias];
            }
        } else if(preg_match('/\_service/', $name)) {
            if (isset($this->register_service[$key])) {
                if ($this->register_service[$key][0] != $name) {
                    return false;
                }
            } else {
                $this->register_service[$key] = [$name, $alias];
            }
        } else {
            if (isset($this->register_public_function[$key])) {
                if ($this->register_public_function[$key][0] != $name) {
                    return false;
                }
            } else {
                $this->register_public_function[$key] = [$name];
            }
        }
        return true;
    }

    /**
     * @name 模块注册检查
     */
    public function register_check()
    {
        if (!empty($this->register_model) || !empty($this->register_service) || !empty($this->register_public_function)) {
            return true;
        }
        Slog::log('模块检查不通过，请检查往Applying_service中注册的模块 ' . json_encode($this->register_model) . '||' . json_encode($this->register_model) . '||' . json_encode($this->register_public_function));
        return false;
    }

    /**
     * @name 加载模块
     * @param string $type 类型
     * @param array $load_type 必须检验的内容 验证不过直接退出
     */
    public function load_register($type, $load_type = [])
    {
        if (isset($this->register_model[$type])) {
            $this->load->model($this->register_model[$type][0], $this->register_model[$type][1]);
        } else {
            Slog::log('模块 缺少 model');
            if (!empty($load_type) && in_array('model', $load_type)) {
                Slog::log('直接退出 model');
                exit;
            }
        }
        if (isset($this->register_service[$type])) {
            $this->load->service($this->register_service[$type][0], $this->register_service[$type][1]);
        } else {
            Slog::log('模块 缺少 service');
            if (!empty($load_type) && in_array('service', $load_type)) {
                Slog::log('直接退出 '.$type.' service '.json_encode($this->register_service));
                exit;
            }
        }
        if (isset($this->register_public_function[$type])) {
            $this->load->helper($this->register_public_function[$type][0]);
        }
    }

    /**
     * @name 编辑重置全部状态
     * @param int $id 用户ID
     * @param string $apply_type 数据类型 user bank mobile ...
     * @param array $change_data 将要修改的数据
     * @return bool 
     */
    public function edit_data($id, $apply_type, $change_data)
    {
        $result = false;
        if (!$this->register_check()) {
            Slog::log('模块检查失败');
            return $result;
        }
        $this->load_register($apply_type, ['service']);
        
        //获取全部数据
        $sevice = $this->register_service[$apply_type][1];
        $data = $this->$sevice->get_all_data($id);
        Slog::log('原数据');
        Slog::log($data);
        //进行数据对比
        $flag = 0;
        $match_array = [];
        foreach ($data[0] as $key => $value) {
            if (is_array($value)) {
                $match_array[] = $value;
            }
        }

        $change_data_count = 0;
        foreach ($change_data as $key => $value) {
            if (-1 != preg_match('/sample_/', $key)) {
                $change_data_count ++;
            }
        }
        
        if ($change_data_count != count($match_array)) {
            $flag = 1;
        } else {
            foreach ($match_array as $value) {
                if ($value['val'] != $change_data[$key]) {
                    Slog::log('数据不一致 ' . $value['val'] . '||' . $change_data[$key]);
                    $flag = 1;
                    break;
                }
            }
        }
        if ($flag) {
            //提取需要报审的id
            $id_array = [];
            //证据池数据报审
            foreach ($data[0] as $value) {
                if (is_array($value)) {
                    if (isset($value['status']) && 1 != $value['status']) {
                        $ret = editStatus($value['type'], $value['id'], 1, '编辑重置', '>0');
                        if (empty($ret)) {
                            $result = -1;
                            return $result;
                        } else {
                            $result = 1;
                        }
                    }
                }
            }
            //基础数据报审
            $result = editStatus($apply_type, $id, 1, '编辑重置', '>0');
        } else {
            Slog::log('没有数据需要修改');
            $result = 0;
        }
        return $result;
    }

    /**
     * @name 统一操作
     * @param string $fn 方法名
     * @param string|array $old_status 当前状态
     * @param string $id uid
     * @param string $apply_type 报审类型
     * @param string $status_info 报审附加信息
     * @param array $for_admins 通知到谁
     * @return bool 
     */
    public function apply_cmd($fn, $old_status, $id, $apply_type, $pass_array = [], $status_info = '', $for_admins = [])
    {
        $result = false;
        Slog::log('统一操作');
        if (!$this->register_check()) {
            Slog::log('模块检查失败');
            return $result;
        }
        $this->load_register($apply_type, ['service']);
        
        //获取全部数据
        $sevice = $this->register_service[$apply_type][1];
        // $data = $this->$sevice->get_all_data($id);
        //提取需要报审的id
        $id_array = [];
        Slog::log($pass_array);
        //证据池数据报审
        foreach ($pass_array as $value) {
            if (is_array($value) && isset($value['status'])) {
                if (is_array($old_status)) {
                    if (isset($value['status']) && in_array($value['status'], $old_status)) {
                        $ret = $fn($value['type'], $value['id'], $status_info, $for_admins);
                    }
                } else {
                    if (isset($value['status']) && $old_status == $value['status']) {
                        $ret = $fn($value['type'], $value['id'], $status_info, $for_admins);
                    }
                }
                if (empty($ret)) {
                    $result = -1;
                    Slog::log('状态调整失败');
                    Slog::log('入参: '.json_encode([$fn,$value['type'], $value['id'], $status_info, $for_admins]));
                    Slog::log($value);
                    return $result;
                } else {
                    $result = 1;
                }
            }
        }
        return $result;
    }

}