<?

/**
 * @desc 客户 身份证、手机卡、银行卡录入 报审等页面操作
 */
class Qiye extends Admin_Controller
{
    /**
     * @var 默认样本归属类型
     */
    public $sample_type = 'user';
    
    public $methods = [
            ['class' => 'Qiye', 'method' => 'qylist'],
            ['class' => 'Qiye', 'method' => 'mobilelist'],
            ['class' => 'Qiye', 'method' => 'banklist'],
            ['class' => 'Qiye', 'method' => 'instlist'],

            ['class' => 'Qiye', 'method' => 'editdo'],
            ['class' => 'Qiye', 'method' => 'edit'],
            ['class' => 'Qiye', 'method' => 'BaoShen'],
            ['class' => 'Qiye', 'method' => 'GuoShen'],
            ['class' => 'Qiye', 'method' => 'BackShen'],
            ['class' => 'Qiye', 'method' => 'Stop'],
            ['class' => 'Qiye', 'method' => 'Start'],
            ['class' => 'Qiye', 'method' => 'PleaseEdit'],
            ['class' => 'Qiye', 'method' => 'YesEdit'],
            ['class' => 'Qiye', 'method' => 'NoEdit'],
            ['class' => 'Qiye', 'method' => 'History'],

            ['class' => 'Qiye', 'method' => 'pre_a'],
            ['class' => 'Qiye', 'method' => 'pre_b'],

        ];

    public $whitelist = [
        'M000001',
    ];

    /**
     * @var array
     * @name 
     */
    private $controller_conf = [];

    /**
     * @name 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->basicloadhelper();

        //load 配置项
        $this->load->model('user/User_model', 'user_model');
        $this->load->model('user/Mobile_model', 'mobile_model');
        $this->load->model('user/Bank_model', 'bank_model');
        $this->load->model('user/Inst_model', 'inst_model');

        $this->controller_conf['user'] = $this->user_model->controller_conf;
        $this->controller_conf['mobile'] = $this->mobile_model->controller_conf;
        $this->controller_conf['bank'] = $this->bank_model->controller_conf;
        $this->controller_conf['inst'] = $this->inst_model->controller_conf;


        $this->load->service('public/Config_service', 'conf_basic');
        $this->load->service('user/User_service', 'user_service');

        //根据业务类型加载不同的service 组合
        $business_type = $this->uri->segment(2);

        if (in_array($business_type, ['bankcheck', 'banklist'])) {
            $this->load->service('user/Bank_service', 'bank_service');
        }

        if (in_array($business_type, ['banklist', 'instlist', 'mobilelist', 'qylist', 'yyslist', 'jdlist', 'taobaolist', 'gjjlist', 'wangyinlist', 'creaditslist', 'creditlist', 'bestsignlist'])) {
            $this->load->service('public/Html_service', 'html_service');
        }

        if (in_array($business_type, ['mobilecheck', 'mobilelist'])) {
            $this->load->service('user/Mobile_service', 'mobile_service');
        }

        if (in_array($business_type, ['instcheck', 'instlist'])) {
            $this->load->service('user/Inst_service', 'inst_service');
        }

        if (in_array($business_type, ['editdo']) && 'mobile' == $this->uri->segment(3)) {
            $this->load->service('user/Mobile_service', 'mobile_service');
        }
    }

/*####客户管理begin######################################################*/ 
    /**
    *开前检测是否已有注册信息，身份证ID唯一，要判断姓名
    */
    public function check()
    {
        $name = $this->input->post('name', true);
        $idnumber = $this->input->post('idnumber', true);
        $id = $this->input->post('id', true);
        if ($id) {
            //修改注册信息检查  检查原有的注册信息  做对比
            $ret = $this->user_service->find_user_edit($id);
            if ($ret) {
                //检查身份证
                $retdata = $this->user_service->check_user($idnumber, '');
                if (0 != $retdata['data']) {
                    $this->_response($retdata['message'], 201);
                }
            }
            $this->_response($retdata['message'], 400);
        } else {
            if($name != '' && $idnumber != ''){
                $retdata = $this->user_service->check_user($idnumber, $name);
                $this->_response($retdata['message'], 0 != $retdata['data'] ? 201 : 400);  
            }else{
                $this->load->model('channel/Channel_model', 'cm');

                $data = [
                    'qudaoinfo' => $this->cm->get_channel_list(),
                ];
                $this->showpage('fms/user/usercheck', $data); 
            }
        }
    }

    /**
     * @name 开户前检测是否已有注册信息 手机号唯一 手机号-》login_name
     */
    public function check_login_name()
    {
        $login_name = $this->input->post('login_name', true);
        if($login_name != ''){
            $retdata = $this->user_service->check_login_name($login_name);
            $this->_response($retdata['message'], 0 != $retdata['data'] ? 201 : 400);  
        }
    }

    /**
     * @name 创建用户
     * @return string id
     */
    public function createuser()
    {
        //获取入参
        $idnuminfo['channel'] =  $this->input->post('channel',true);
        $fuserid = $this->user_service->create_uid($idnuminfo['channel']);
        $ret = $this->user_service->register_user([
            'login_name' => $this->input->post('login_name',true),
            'channel' => $idnuminfo['channel'],
            'fuserid' => $fuserid,
            'cjyg' => !empty($_SESSION['fms_userid']) ? $_SESSION['fms_userid'] : '',
            'ctime' => date('Y-m-d H:i:s', time()),
        ]);
        Slog::log($ret);
        if (1 == $ret['code']) {
            echo $fuserid;
        } else {
            echo false;
        }
        exit;
    }

    /**
     * @name 修改注册用户的注册信息 仅管理员可用
     * @url Qiye/changereginfo
     * @return bool
     */
    public function changereginfo()
    {
        //权限强行判断
        if (!in_array($_SESSION['fms_userid'], $this->whitelist)) {
            exit;
        }
        $channel = $this->input->post('channel', true);
        $id = $this->input->post('id', true);
        $userinfo = $this->user_model->get_user_info_by_id($id);
        //判断是否需要重新生成用户编号
        if ($userinfo['channel'] != $channel) {
            $fuserid = $this->user_service->create_uid($channel);
            $this->user_model->db->set('fuserid', $fuserid);
            $this->user_model->db->set('channel', $channel);
        }
        $this->user_model->db->where(" id = '" . $id . "'");
        $ret = $this->user_model->db->update($this->user_model->table_name);
        $retdata['data'] = $ret;
        if ($ret) {
            $retdata['message'] = '修改成功';
        } else {
            $retdata['message'] = '修改失败';
        }
        $this->_response($retdata['message'], 0 != $retdata['data'] ? 200 : 201); 
    }

    /**
     * @name 用户查询页-页面
     */
    public function qylist()
    {
        $pool_sample_id = 2;
        if (!empty($_POST)) {
            $fuserid = $this->input->post('fuserid',true);
            $login_name = $this->input->post('login_name',true);
            $page = $this->input->post('page',true);
            $rows = $this->input->post('rows',true);
            if($fuserid == 'err'){$fuserid = '';}
            if($login_name == 'err'){$login_name = '';}else{$login_name = urldecode($login_name);}
            $retfinddata = $this->user_service->find_user($login_name, $fuserid, $page, $rows);
            if (isset($retfinddata['fuserid'])) {
                $retfinddata_tmp = [];
                $retfinddata_tmp[] = $retfinddata;
                $retfinddata = $retfinddata_tmp;
            }
            if (isset($retfinddata['data'])) {
                $total = $retfinddata['total'];
                $retfinddata = $retfinddata['data'];
            }
            if(!isset($retfinddata[0]['fuserid'])){
                Slog::log($retfinddata);
                exit;
            } else {
                // Slog::log($retfinddata[0]);
                foreach($retfinddata as $k=>$v){
                    $c_list[$k]["id"] = $v['id'];
                    $c_list[$k]["fuserid"] = $v['fuserid'];
                    $c_list[$k]["name"] = $v['name'];
                    $c_list[$k]["idnumber"] = $v['idnumber'];
                    $c_list[$k]["channel"] = $v['channel'];
                    // $c_list[$k]["cjyg"] = $v['username'];
                    $c_list[$k]["cjyg"] = $v['cjyg'];
                    $c_list[$k]["ctime"] = $v['ctime'];
                    $c_list[$k]['pool_sample_id'] = $pool_sample_id;
                    if (isset($v['obj_status']) && !empty($v['status'])) {
                        //输出状态按钮
                        $c_list[$k]["op"] = '<span class="dn op" id="op'. $v['id'] .'" data-status='. implode('-', $v['status']) .'>'. $c_list[$k]['pool_sample_id'] .'</span>';
                    } else {
                        $c_list[$k]["op"] = '';
                    }
                }
            }
            $reponseData = [];
            $reponseData['rows'] = $c_list;
            $reponseData["total"] = !empty($total) ? $total : count($c_list);
            echo json_encode($reponseData);
        } else {
            $this->load->model('channel/Channel_model', 'cm');
            $this->list_page_conf();

            foreach ($this->cm->get_channel_list() as $value) {
                $qudaoinfo[] = array_values($value);
            }
            if (in_array($_SESSION['fms_userid'], $this->whitelist)) {
                $senior = 1;
            } else {
                $senior = 0;
            }
            // //合并 内容
            // foreach ($qudaoinfo as $key => $value) {
            //  $qudaoinfo[$key][0] = implode(',', $value); 
            // }

            $qudaoinfo_id = [];
            foreach ($qudaoinfo as $key => $value) {
                $qudaoinfo_id[] = [$value[1], $value[1]]; 
            }
            // Slog::log($qudaoinfo_id);
            //输出状态按钮
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'senior' => $senior,
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'qudaoinfo' => json_encode($qudaoinfo, JSON_UNESCAPED_UNICODE),
                'qudaoinfo_id' => json_encode($qudaoinfo_id, JSON_UNESCAPED_UNICODE),
                'map' => json_encode($this->user_model->get_pool_array(), JSON_UNESCAPED_UNICODE),
                'sample_config' => $this->user_model->get_sample_config(),
                'status_enum' => json_encode($this->conf_basic->load_config('apply_conf', 'Applying_service'), JSON_UNESCAPED_UNICODE),
                'env' => $this->config->item('youtu_env'),
            ];
            // Slog::log($this->conf_basic->load_config('apply_conf', 'Applying_service'));
            $this->showpage('fms/user/userquery', $data);
        }
    }

    /**
     * @name 获取户口地址
     */
    public function getHkadr()
    {
        $idnumber  = $this->input->post('idnumber',true);
        $ret = $this->user_service->get_id_number_info($idnumber);
        echo json_encode([
            'Hkadr' => $ret['area'],
            'birthdate' => substr($idnumber,6,8),
            'sex' => $ret['sex'],
            'age' => $ret['age'],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
/*####客户管理end######################################################*/

/*####银行卡管理begin######################################################*/
    /**
     * @name 银行卡 点击新增时 提示进行身份证检查 检查该身份证号时候能新增银行卡
     * @type ajax ['code'=>code, 'message'=>message, 'data'=>data]
     */
    public function bankcheck()
    {
        $ret = $this->bank_service->add_check($this->input->post('idnumber', true));
        if (is_array($ret)) {
            $response = [
                'code' => 1,
                'message' => 'SUCCESS',
                'data' => $ret,
            ];
        } else {
            $response = [
                'code' => 0,
                'message' => $ret,
                'data' => [],
            ];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name 银行卡查询页
     */
    public function banklist()
    {
        $pool_sample_id = 26;
        if (!empty($_POST)) {
            $idnumber = $this->input->post('idnumber',true);
            $name = $this->input->post('name',true);
            $page = $this->input->post('page',true);
            $rows = $this->input->post('rows',true);
            if($idnumber == 'err'){$idnumber = '';}
            if($name == 'err'){$name = '';}else{$name = urldecode($name);}
            $this->load->helper( array('array', 'tools', 'slog') );
            $userinfodata = $this->bank_service->bank_card_list($idnumber, $name, '', $page, $rows);
            Slog::log($userinfodata);
            if (isset($userinfodata['data'])) {
                $total = $userinfodata['total'];
                $userinfodata = $userinfodata['data']; 
                $c_list = [];
            }
            foreach($userinfodata as $k => $v){
                // slog::log($v);
                $c_list[$k]['pool_sample_id'] = $pool_sample_id;
                $c_list[$k]['id'] = $v['id'];
                $c_list[$k]["fuserid"] = $v['fuserid'];
                $c_list[$k]["idnumber"] = $v['idnumber'];
                $c_list[$k]["channel"] = $v['channel'];
                $c_list[$k]["name"] = $v['name'];
                // $c_list[$k]["cjyg"] = $v['username'];
                $c_list[$k]["cjyg"] = $v['cjyg'];
                $c_list[$k]["ctime"] = $v['ctime'];
                if (!empty($v['status'])) {
                    $c_list[$k]["op"] = '<span class="dn op" id="op'. $v['id'] .'" data-status='. implode('-', $v['status']) .'>'. $c_list[$k]['pool_sample_id'] .'</span>';
                } else {
                    if (checkRolePower('Qiye', 'editdo')) {
                        $c_list[$k]["op"] = '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="add(\''.$v['fuserid'].'\',\''.$v['idnumber'].'\',\''.$v['name'].'\',\''.$v["channel"].'\')" ><i class="fa fa-plus"></i>新增</a>';
                    }
                }
            }
            $reponseData['rows'] = $c_list;
            $reponseData["total"] = !empty($total) ? $total : count($c_list);
            echo json_encode($reponseData);
        } else {
            $this->list_page_conf();
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'sample_config' => json_encode($this->bank_model->get_sample_config(), JSON_UNESCAPED_UNICODE),
                'status_enum' => json_encode($this->conf_basic->load_config('apply_conf', 'Applying_service'), JSON_UNESCAPED_UNICODE),
                'env' => $this->config->item('youtu_env'),
            ];
            // Slog::log(is_array($data['sample_config']));
            $this->showpage('fms/user/bankquery', $data); 
        }
    }

/*####银行卡管理end############################################*/

/*####手机卡管理begin############################################*/
    /**
     * @name 手机卡 点击新增时 提示进行身份证检查 检查该身份证号时候能新增银行卡
     * @type ajax ['code'=>code, 'message'=>message, 'data'=>data]
     */
    public function mobilecheck()
    {
        $ret = $this->mobile_service->add_check($this->input->post('idnumber', true));
        if (is_array($ret)) {
            $response = [
                'code' => 1,
                'message' => 'SUCCESS',
                'data' => $ret,
            ];
        } else {
            $response = [
                'code' => 0,
                'message' => $ret,
                'data' => [],
            ];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name 手机卡列表 
     * @other 客户管理-手机卡-查询按钮方法
     */
    public function mobilelist()
    {
        $pool_sample_id = 94;
        if (!empty($_POST)) {
            $idnumber = $this->input->post('idnumber',true);
            $name = $this->input->post('name',true);
            $page = $this->input->post('page',true);
            $rows = $this->input->post('rows',true);
            if($name == 'err'){$name = '';}else{$name = urldecode($name);}
            if($idnumber == 'err'){$idnumber = '';}else{$idnumber = urldecode($idnumber);}
            $userinfodata = $this->mobile_service->mobile_card_list($idnumber, $name, '', $page, $rows);
            // Slog::log($userinfodata);
            if (isset($userinfodata['data'])) {
                $total = $userinfodata['total'];
                $userinfodata = $userinfodata['data']; 
                $c_list = [];
            }
            foreach($userinfodata as $k => $v){
                // slog::log($v);
                $c_list[$k]['pool_sample_id'] = $pool_sample_id;
                $c_list[$k]['id'] = $v['id'];
                $c_list[$k]["fuserid"] = $v['fuserid'];
                $c_list[$k]["idnumber"] = $v['idnumber'];
                $c_list[$k]["channel"] = $v['channel'];
                $c_list[$k]["name"] = $v['name'];
                // $c_list[$k]["cjyg"] = $v['username'];
                $c_list[$k]["cjyg"] = $v['cjyg'];
                $c_list[$k]["ctime"] = $v['ctime'];
                if (!empty($v['status'])) {
                    $c_list[$k]["op"] = '<span class="dn op" id="op'. $v['id'] .'" data-status='. implode('-', $v['status']) .'>'. $c_list[$k]['pool_sample_id'] .'</span>';
                } else {
                    if (checkRolePower('Qiye', 'editdo')) {
                        $c_list[$k]["op"] = '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="add(\''.$v['fuserid'].'\',\''.$v['idnumber'].'\',\''.$v['name'].'\',\''.$v["channel"].'\')" ><i class="fa fa-plus"></i>新增</a>';
                    }
                }
            }
            $reponseData['rows'] = $c_list;
            $reponseData["total"] = !empty($total) ? $total : count($c_list);
            echo json_encode($reponseData);
        } else {
            $this->list_page_conf();
            if (in_array($_SESSION['fms_userid'], $this->whitelist)) {
                $senior = 1;
            } else {
                $senior = 0;
            }
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'typemap' => json_encode([
                    ['01', '联系号'],
                    ['02', '还款卡绑定号']
                ]),
                'senior' => $senior,
                'btnCode' => showStatusBtn(true),
                'jscontroller' => $this->html_service->no_backspace(),
                'sample_config' => json_encode($this->mobile_model->get_sample_config(), JSON_UNESCAPED_UNICODE),
                'env' => $this->config->item('youtu_env'),
                'status_enum' => json_encode($this->conf_basic->load_config('apply_conf', 'Applying_service'), JSON_UNESCAPED_UNICODE),
            ];
            Slog::log(is_array($data['sample_config']));
            $this->showpage('fms/mobile/mobilequery', $data);
        }
    }

/*####手机卡管理end############################################*/

/*####用户机构账户管理begin############################################*/
    /**
     * @name 机构账户 点击新增时 提示进行身份证检查 检查该身份证号时候能新增银行卡
     * @type ajax ['code'=>code, 'message'=>message, 'data'=>data]
     */
    public function instcheck()
    {
        $ret = $this->inst_service->add_check($this->input->post('idnumber', true));
        if (is_array($ret)) {
            $response = [
                'code' => 1,
                'message' => 'SUCCESS',
                'data' => $ret,
            ];
        } else {
            $response = [
                'code' => 0,
                'message' => $ret,
                'data' => [],
            ];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name 机构列表 
     * @other 客户管理-机构账户查询-页面
     */
    public function instlist()
    {
        $pool_sample_id = 154;
        if (!empty($_POST)) {
            $idnumber = $_POST['idnumber'];
            $name = $_POST['name'];
            $page = $this->input->post('page',true);
            $rows = $this->input->post('rows',true);
            if($name == 'err'){$name = '';}else{$name = urldecode($name);}
            if($idnumber == 'err'){$idnumber = '';}else{$idnumber = urldecode($idnumber);}
            $userinfodata = $this->inst_service->institution_list($idnumber, $name, '', $page, $rows);
            if (isset($userinfodata['data'])) {
                Slog::log($userinfodata['data']);
                $total = $userinfodata['total'];
                $userinfodata = $userinfodata['data']; 
                $c_list = [];
            }
            foreach($userinfodata as $k => $v) {
                // slog::log($v);
                $c_list[$k]['pool_sample_id'] = $pool_sample_id;
                $c_list[$k]['id'] = $v['id'];
                $c_list[$k]["fuserid"] = $v['fuserid'];
                $c_list[$k]["idnumber"] = $v['idnumber'];
                $c_list[$k]["channel"] = $v['channel'];
                $c_list[$k]["name"] = $v['name'];
                // $c_list[$k]["cjyg"] = $v['username'];
                $c_list[$k]["cjyg"] = $v['cjyg'];
                $c_list[$k]["ctime"] = $v['ctime'];
                if (!empty($v['status'])) {
                    $c_list[$k]["op"] = '<span class="dn op" id="op'. $v['id'] .'" data-status='. implode('-', $v['status']) .'>'. $c_list[$k]['pool_sample_id'] .'</span>';
                } else {
                    if (checkRolePower('Qiye', 'editdo')) {
                        $c_list[$k]["op"] = '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="add(\''.$v['fuserid'].'\',\''.$v['idnumber'].'\',\''.$v['name'].'\',\''.$v["channel"].'\')" ><i class="fa fa-plus"></i>新增</a>';
                    }
                }
            }
            $reponseData['rows'] = $c_list;
            $reponseData["total"] = !empty($total) ? $total : count($c_list);
            echo json_encode($reponseData);
        } else {
            $this->list_page_conf();
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'sample_config' => json_encode($this->inst_model->get_sample_config(), JSON_UNESCAPED_UNICODE),
                'status_enum' => json_encode($this->conf_basic->load_config('apply_conf', 'Applying_service'), JSON_UNESCAPED_UNICODE),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/instquery', $data);
        }
    }

/*####用户机构账户管理end############################################*/

/*####魔蝎授权begin###############################################*/
    /**
     * @name 魔蝎授权页面 
     */
    public function moxieauth()
    {
        $data = [];
        $this->showpage('fms/user/moxieauth', $data);
    }

/*####魔蝎授权end###############################################*/

/*####运营商报告begin###############################################*/
    /**
     * @name 用户运营商报告查询
     */
    public function yyslist()
    {
        if (!empty($_POST)) {
            $tmp = $this->input->post('key',true);
            if (strlen($tmp) > 10) {
                $mobile = $tmp;
                $user_id = '';
            } else {
                $user_id = $tmp;
                $mobile = '';
            }
            $this->load->service('public/Yys_service', 'yys_service');
            $result = $this->yys_service->get_report($mobile, $user_id);
            if (empty($result)) {
                $this->load->service('public/Yys_service', 'yys_service');
                if ($this->yys_service->record_report($mobile)) {
                    $result = $this->yys_service->get_report($mobile);
                } else {
                    $str = '用户运营商报告拉取失败 需要用户授权 手机号 ' . $mobile;
                    echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $result], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/yysreport', $data);
        }
    }

    /**
     * @name 运营商报告拉取
     */
    public function getyysreport()
    {
        $tmp = $this->input->post('key',true);
        if (strlen($tmp) > 10) {
            $mobile = $tmp;
            $user_id = '';
        } else {
            $user_id = $tmp;
            $mobile = '';
        }
        Slog::log([$mobile, $user_id]);
        $this->load->service('public/Yys_service', 'yys_service');
        echo json_encode($this->yys_service->get_report($mobile, $user_id), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name 运营商报告拉取
     */
    public function getyysoriginalreport()
    {
        $tmp = $this->input->post('key',true);
        if (strlen($tmp) > 10) {
            $mobile = $tmp;
            $user_id = '';
        } else {
            $user_id = $tmp;
            $mobile = '';
        }
        $this->load->service('public/Yys_service', 'yys_service');
        echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $this->yys_service->get_original_report($mobile, $user_id)], JSON_UNESCAPED_UNICODE);
        exit;
    }

/*####运营商报告end###############################################*/

/*####京东begin###############################################*/
    /**
     * @name 用户京东报告查询
     */
    public function jdlist()
    {
        if (!empty($_POST)) {
            $condition = trim($this->input->post('condition',true));
            //判断是 身份证还是fuserid 还是 姓名
            Slog::log(preg_match('/^[A-Za-z0-9]+$/', $condition));
            Slog::log('asdasdasd');
            if (18 == strlen($condition)) {
                //身份证 换
                $ret = $this->user_service->user_list($idnumber);
                $fuserid = $ret['fuserid'];
            } else if ((10 == strlen($condition)) && (preg_match('/^[A-Za-z0-9]{10}$/', $condition))) {
                //fuserid  直接
                $fuserid = $condition;
            }
            $this->load->service('public/Moxie_service', 'moxie_service');
            $task_info = $this->moxie_service->find_task_id($fuserid, 'jd');
            Slog::log('jd task_id: ' . $task_info['task_id']);
            if (!empty($task_info['task_id'])) {
                $task_id = $task_info['task_id'];
                $this->load->service('public/Jd_service', 'jd_service');
                $result = $this->jd_service->get_report($task_id);
                if (empty($result)) {
                    if ($this->jd_service->record_report($task_id)) {
                        $result = $this->jd_service->get_report($task_id);
                    } else {
                        $str = '用户京东报告拉取失败 需要用户授权 任务ID ' . $task_id;
                        echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }
            } else {
                $str = '用户京东报告已失效或未授权 需要用户授权';
                echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $result], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/jdreport', $data);
        }
    }

/*####京东end###############################################*/

/*####淘宝begin###############################################*/
    /**
     * @name 用户淘宝报告查询
     */
    public function taobaolist()
    {
        if (!empty($_POST)) {
            $condition = trim($this->input->post('condition',true));
            //判断是 身份证还是fuserid 还是 姓名
            Slog::log(preg_match('/^[A-Za-z0-9]+$/', $condition));
            Slog::log('asdasdasd');
            if (18 == strlen($condition)) {
                //身份证 换
                $ret = $this->user_service->user_list($idnumber);
                $fuserid = $ret['fuserid'];
            } else if ((10 == strlen($condition)) && (preg_match('/^[A-Za-z0-9]{10}$/', $condition))) {
                //fuserid  直接
                $fuserid = $condition;
            }
            $this->load->service('public/Moxie_service', 'moxie_service');
            $task_info = $this->moxie_service->find_task_id($fuserid, 'taobao');
            Slog::log('taobao task_id: ' . $task_info['task_id']);
            if (!empty($task_info['task_id'])) {
                $task_id = $task_info['task_id'];
                $this->load->service('public/Taobao_service', 'taobao_service');
                $result = $this->taobao_service->get_report($task_id);
                if (empty($result)) {
                    if ($this->taobao_service->record_report($task_id)) {
                        $result = $this->taobao_service->get_report($task_id);
                    } else {
                        $str = '用户淘宝报告拉取失败 需要用户授权 任务ID ' . $task_id;
                        echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }
            } else {
                $str = '用户淘宝报告已失效或未授权 需要用户授权';
                echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $result], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/taobaoreport', $data);
        }
    }

/*####淘宝end###############################################*/

/*####公积金begin###############################################*/
    /**
     * @name 用户公积金报告查询
     */
    public function gjjlist()
    {
        if (!empty($_POST)) {
            $condition = trim($this->input->post('condition',true));
            //判断是 身份证还是fuserid 还是 姓名
            // Slog::log(preg_match('/^[A-Za-z0-9]+$/', $condition));
            // Slog::log('asdasdasd');
            // if (18 == strlen($condition)) {
            //     //身份证 换
            //     $ret = $this->user_service->user_list($idnumber);
            //     $fuserid = $ret['fuserid'];
            // } else if ((10 == strlen($condition)) && (preg_match('/^[A-Za-z0-9]{10}$/', $condition))) {
            //     //fuserid  直接
            //     $fuserid = $condition;
            // }

            //目前只支持用身份证查询
            $this->load->service('public/Gjj_service', 'gjj_service');
            $result = $this->gjj_service->get_report($condition);
            if (!empty($result)) {
                echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $result], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $str = '用户公积金报告已失效或未授权 需要用户授权';
                echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                exit;
            }
            // $this->load->service('public/Moxie_service', 'moxie_service');
            // $task_info = $this->moxie_service->find_task_id($fuserid, 'taobao');
            Slog::log('taobao task_id: ' . $task_info['task_id']);
            if (!empty($task_info['task_id'])) {
                $task_id = $task_info['task_id'];
                $this->load->service('public/Gjj_service', 'gjj_service');
                $result = $this->gjj_service->get_report($task_id);
                if (empty($result)) {
                    if ($this->gjj_service->record_report($task_id)) {
                        $result = $this->gjj_service->get_report($task_id);
                    } else {
                        $str = '用户公积金报告拉取失败 需要用户授权 任务ID ' . $task_id;
                        echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }
            } else {
                $str = '用户公积金报告已失效或未授权 需要用户授权';
                echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $result], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/gjjreport', $data);
        }
    }

/*####公积金end###############################################*/

/*####网银begin###############################################*/
    /**
     * @name 用户网银报告查询
     */
    public function wangyinlist()
    {
        if (!empty($_POST)) {
            $condition = trim($this->input->post('condition',true));
            //判断是 身份证还是fuserid 还是 姓名
            Slog::log(preg_match('/^[A-Za-z0-9]+$/', $condition));
            Slog::log('asdasdasd');
            if (18 == strlen($condition)) {
                //身份证 换
                $ret = $this->user_service->user_list($idnumber);
                $fuserid = $ret['fuserid'];
            } else if ((10 >= strlen($condition)) && (preg_match('/^[A-Za-z0-9]{6,10}$/', $condition))) {
                //fuserid  直接
                $fuserid = $condition;
            }
            $this->load->service('public/Moxie_service', 'moxie_service');
            $task_info = $this->moxie_service->find_task_id($fuserid, 'bank_card');
            Slog::log('bank task_id: ' . $task_info['task_id']);
            if (!empty($task_info['task_id'])) {
                $task_id = $task_info['task_id'];
                $this->load->service('public/Bank_service', 'public_bank_service');
                $result = $this->public_bank_service->get_report($task_id);
                if (empty($result)) {
                    if ($this->public_bank_service->record_report($task_id)) {
                        $result = $this->public_bank_service->get_report($task_id);
                    } else {
                        $str = '用户网银报告拉取失败 需要用户授权 任务ID ' . $task_id;
                        echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }
            } else {
                $str = '用户网银报告已失效或未授权 需要用户授权';
                echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                exit;
            }
            if (empty($task_id)) {
                $task_id = '';
            }
            echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $result, 'task_id' => $task_id], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/wangyinreport', $data);
        }
    }

    /**
     * @name 用户网银账单查询
     */
    public function wangyinbills()
    {
        if (!empty($_POST)) {
            $task_id = trim($this->input->post('task_id',true));
            $this->load->service('public/BankBills_service', 'bankbills_service');
            $ret = $this->bankbills_service->get_all_bills_report($task_id);
            echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $ret, 'task_id' => $task_id], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * @name 用户网银信用卡报告查询
     */
    public function creditlist()
    {
        if (!empty($_POST)) {
            $condition = trim($this->input->post('condition',true));
            //判断是 身份证还是fuserid 还是 姓名
            Slog::log(preg_match('/^[A-Za-z0-9]+$/', $condition));
            Slog::log('asdasdasd');
            if (18 == strlen($condition)) {
                //身份证 换
                $ret = $this->user_service->user_list($idnumber);
                $fuserid = $ret['fuserid'];
            } else if ((10 == strlen($condition)) && (preg_match('/^[A-Za-z0-9]{10}$/', $condition))) {
                //fuserid  直接
                $fuserid = $condition;
            }
            $this->load->service('public/Moxie_service', 'moxie_service');
            $task_info = $this->moxie_service->find_task_id($fuserid, 'credit_card');
            Slog::log('credit_card task_id: ' . $task_info['task_id']);
            if (!empty($task_info['task_id'])) {
                $task_id = $task_info['task_id'];
                $this->load->service('public/CreditCard_service', 'public_credit_service');
                $result = $this->public_credit_service->get_report($task_id);
                if (empty($result)) {
                    if ($this->public_credit_service->record_report($task_id)) {
                        $result = $this->public_credit_service->get_report($task_id);
                    } else {
                        $str = '用户网银信用卡报告拉取失败 需要用户授权 任务ID ' . $task_id;
                        echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }
            } else {
                $str = '用户网银信用卡报告已失效或未授权 需要用户授权';
                echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            echo json_encode(['code' => 0, 'msg' => '信用卡报告获取成功', 'data' => $result], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/creditcardreport', $data);
        }
    }

/*####网银end###############################################*/

/*####上上签合同begin###############################################*/
    public function bestsignlist()
    {
        if (!empty($_POST)) {
            $fuserid = trim($this->input->post('fuserid',true));
            $this->load->model('public/BestsignLog_model', 'bslm');
            $result = $this->bslm->find_log($fuserid);
            foreach ($result as $key => $value) {
                $result[$key]['op'] = '<span class="dn op" id="op'. $key .'" data-src='. $value['filename'] .'>'. $value['filename'] .'</span>';
            }
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/bestsign', $data);
        }
    }

    /**
     * @name 获取合同
     */
    public function getcontract()
    {
        $file = trim($this->input->get('src',true));
        if (!empty($file)) {
            $this->showpage('fms/tools/pdf', ['src' => $file]);
        }
    }

/*####上上签合同end###############################################*/

/*####失信 被执行 裁判文书begin###############################################*/

    /**
     * @name 失信 被执行 裁判文书 查询页
     */
    public function creaditslist()
    {
        if (!empty($_POST)) {
            $this->load->library('Creadits');
            $obj = new Creadits();

            $idnumber = $this->input->post('idnumber',true);
            $keywords = $this->input->post('keywords', true);
            $page = $this->input->post('page', true);
            $requestType = $this->input->post('requestType', true);

            $obj->setParam($keywords, $idnumber, $page);
            $result = $obj->queryInfo($requestType);

            if (0 == $result['code']) {
                //查询成功
                Slog::log($result);
            } else {
                //查询失败
                Slog::log($result);
                if (preg_match('/403/', $result['msg'])) {
                    $result['msg'] = '查询失败 查询次数不足';
                } else {
                    $result['msg'] = '查询失败 其它错误 错误内容 ' . $result['msg'];
                }
                
            }
            echo json_encode($result);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/creaditsreport', $data);
        }
    }

    /**
     * @name 被执行人查询
     */
    public function executorreport()
    {
        if (!empty($_POST)) {
            $this->load->library('Creadits');
            $obj = new Creadits();

            $idnumber = $this->input->post('idnumber',true);
            $keywords = $this->input->post('keywords', true);
            $page = $this->input->post('page', true);
            $requestType = 'executor';

            //先去查询mongo  mongo有直接调Mongo报告 没有再走接口  走接口之后要落地到mongo里  key用keywords+idnumber+time
            $sample_id = $this->config->item('executor_sample');
            $this->load->model('pool/OperationPool_model','opm');
            //用身份证差id
            $fuserinfo = $this->user_service->find_user($idnumber);
            $value = $this->opm->get_data($fuserinfo[0]['id'], [$sample_id]);
            Slog::log($value);
            $this->load->service('public/Executor_service', 'executor_service');
            if (!empty($value)) {
                //去mongo 拉报告
                $result = $this->executor_service->get_report($value);
            } 

            if (!empty($result)) {
                //调接口拉报告
                if ($this->executor_service->record_report($keywords, $idnumber, $page)) {
                    $result = $this->executor_service->get_report($mobile);
                } else {
                    $str = '用户被执行人报告拉取失败 需要用户授权 手机号 ' . $mobile;
                    echo json_encode(['code' => -1, 'msg' => $str, 'data' => []], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            echo json_encode(['code' => 0, 'msg' => '报告获取成功', 'data' => $this->yys_service->get_report($mobile)], JSON_UNESCAPED_UNICODE);

            $obj->setParam($keywords, $idnumber, $page);
            $result = $obj->queryInfo($requestType);

            if (0 == $result['code']) {
                //查询成功
                Slog::log($result);
            } else {
                //查询失败
                Slog::log($result);
                if (preg_match('/403/', $result['msg'])) {
                    $result['msg'] = '查询失败 查询次数不足';
                } else {
                    $result['msg'] = '查询失败 其它错误 错误内容 ' . $result['msg'];
                }
                
            }
            echo json_encode($result);
            exit;
        } else {
            $data = [
                'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
                'env' => $this->config->item('youtu_env'),
            ];
            $this->showpage('fms/user/creaditsreport', $data);
        }
    }

/*####失信 被执行 裁判文书end###############################################*/

/*####H5控制器begin########################################################*/
    /**
     * @name 前端用户注册 [post|json]
     * @url Qiye/front_end_reg
     * @param ['name' => string[2,15],'idnumber' =>'string[18]','mobile' => 'big int[11]']
     * @other 
     *      --(废除)客户常用手机号，不需要验证唯一性 (2018.07.24)
     *      提交的手机号作为登录账号，需要验证唯一性 (2018.07.30)
     * @return ['msg' => string, 'code' => int]
     */
    public function front_end_reg()
    {
        if (!empty($_POST)) {
            $name = $this->input->post('name', true);
            $idnumber = $this->input->post('idnumber', true);
            $mobile = $this->input->post('mobile', true);
            $identifying = $this->input->post('identifying', true);
        } else {
            $body = @file_get_contents('php://input');
            Slog::log($body);
            $body = json_decode($body);
            $name = !empty($body->name) ? $body->name : '';
            $idnumber = !empty($body->idnumber) ? $body->idnumber : '';
            $mobile = !empty($body->mobile) ? $body->mobile : '';
            $identifying = !empty($body->identifying) ? $body->identifying : '';
        }
        
        if (empty($name) || empty($idnumber) || empty($mobile) || empty($identifying)) {
            $msg = '参数不完整';
            $code = -1;
        } else {
            //验证码检查
            $this->load->model('public/SmsLog_model', 'smslog_model');
            $code_info = $this->smslog_model->find_log($mobile);
            Slog::log($code_info);
            if (empty($code_info)) {
                Slog::log('验证码已过期 或未发验证码');
                $msg = '验证码错误';
                $code = -1;
            } else {
                if ($code_info['code'] != $identifying) {
                    Slog::log('验证码错误');
                    $msg = '验证码错误';
                    $code = -1;
                } else {
                    $channel = 'H001';//H5注册
                    $retdata = $this->user_service->check_user($idnumber, $name);
                    $login_name_check = $this->user_service->check_login_name($mobile);
                    if (0 == $login_name_check) {
                        //账户已存在
                        $msg = '手机号已注册';
                        $code = -1;
                    } else {
                        Slog::log($retdata);
                        if (0 != $retdata['data']) {
                            @$_SESSION['fms_userrole'] = 19;
                            $_SESSION['fms_role_name'] = 'H5';
                            $_SESSION['fms_id'] = 14;
                            $_SESSION['fms_username'] = 'H5';
                            //获取入参
                            $fuserid = $this->user_service->create_uid($channel);
                            Slog::log('新的用户ID：' . $fuserid);
                            $ret = $this->user_service->register_user([
                                'name' => $name,
                                'idnumber' => $idnumber,
                                'channel' => $channel,
                                'fuserid' => $fuserid,
                                'login_name' => $mobile,
                                'cjyg' => 'H5',
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'mobile' => $mobile
                            ]);
                            Slog::log('写入数据库结束');
                            Slog::log($ret);
                            if (1 == $ret['code']) {
                                //创建任务
                                $this->load->model('public/MoxieCallback_model', 'moxiecallback_model');
                                $this->moxiecallback_model->create_task_all($fuserid);
                                $this->sign_a_contract($fuserid, $idnumber, $name, '面签', '个人信息查询及使用授权书');
                                $this->sign_a_contract($fuserid, $idnumber, $name, '面签', '数据查询授权书');
                                // echo $fuserid;
                                $msg = $fuserid;
                                $code = 0;
                            } else {
                                $msg = '开户失败';
                                $code = -1;
                                // echo false;
                            }
                        } else {
                            //用户已存在  获取用户fuserid
                            $ret = $this->user_service->find_user($idnumber, $name);
                            if (!empty($ret['data']) && !empty($ret['data'][0])) {
                                Slog::log('fuserid: ' . $ret['data'][0]['fuserid']);
                                //检查用户是否运营商认证  没有运营商认证则强制跳转去运营商认证
                                $this->load->service('public/Moxie_service', 'moxie_service');
                                $result = $this->moxie_service->find_task_id($ret['data'][0]['fuserid'], 'yys');
                                if (!empty($result['task_id'])) {
                                    $code = 1;
                                } else {
                                    $code = 0;
                                }
                                $msg = $ret['data'][0]['fuserid'];
                            } else {
                                $msg = '用户已存在 系统异常 请联系管理员';
                                $code = -1;
                            }
                        }
                    }
                }
            }
        }
        
        Slog::log('前端开户接口 入参: ' . json_encode([[$name,$idnumber,$mobile], JSON_UNESCAPED_UNICODE]) . '|| 返回: ' . json_encode([$msg, $code]));
        echo json_encode([
            'msg' => $msg,
            'code' => $code
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name H5用户登录 [post|json]
     * @url Qiye/front_end_login
     * @param ['login_name' => string, 'pwd' => string]
     * @other login_name 登录账户     pwd 登录密码
     * @return ['msg' => string, 'code' => int]
     */
    public function front_end_login()
    {
        if (!empty($_POST)) {
            $pwd = $this->input->post('pwd', true);
            $login_name = $this->input->post('login_name', true);
        } else {
            $body = @file_get_contents('php://input');
            Slog::log($body);
            $body = json_decode($body);
            $pwd = !empty($body->pwd) ? $body->pwd : '';
            $login_name = !empty($body->login_name) ? $body->login_name : '';
        }
        if (empty($pwd) || empty($login_name)) {
            $msg = '参数不完整';
            $code = -3;
        } else {
            $ret = $this->user_service->front_end_login($login_name, $pwd);
            $msg = $ret['msg'];
            $code = $ret['code'];
        }
        Slog::log('前端登录接口 入参: ' . json_encode([$login_name, $pwd], JSON_UNESCAPED_UNICODE) . '|| 返回: ' . json_encode([$msg, $code], JSON_UNESCAPED_UNICODE));
        echo json_encode([
            'msg' => $msg,
            'code' => $code
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name 前端设置用户密码 [post|json]
     * @url Qiye/front_end_set_pwd
     * @param ['login_name' => string, 'pwd' => string]
     */
    public function front_end_set_pwd()
    {
        if (!empty($_POST)) {
            $pwd = $this->input->post('pwd', true);
            $login_name = $this->input->post('login_name', true);
        } else {
            $body = @file_get_contents('php://input');
            Slog::log($body);
            $body = json_decode($body);
            $pwd = !empty($body->pwd) ? $body->pwd : '';
            $login_name = !empty($body->login_name) ? $body->login_name : '';
        }

        if (empty($pwd) || empty($login_name)) {
            $msg = '参数不完整';
            $code = -4;
        } else {
            $ret = $this->user_service->front_end_set_pwd($login_name, $pwd);
            $msg = $ret['msg'];
            $code = $ret['code'];
        }

        Slog::log('前端登录接口 入参: ' . json_encode([$login_name, $pwd], JSON_UNESCAPED_UNICODE) . '|| 返回: ' . json_encode([$msg, $code], JSON_UNESCAPED_UNICODE));
        echo json_encode([
            'msg' => $msg,
            'code' => $code
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name 检查用户的报告获取状态
     * @url Qiye/user_report_status
     */
    public function user_report_status()
    {
        $fuid = '';
        if (!empty($_POST)) {
            $fuid = $this->input->post('fuid', true);
        } else {
            $body = @file_get_contents('php://input');
            // Slog::log($body);
            $body = json_decode($body);
            $fuid = !empty($body->fuid) ? $body->fuid : '';
        }
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET");
        $this->load->service('public/Moxie_service', 'moxie_service');
        if (!empty($fuid)) {
            $result = $this->moxie_service->get_all_task_status($fuid);
            //去查这个用户的 授权状态
            $this->front_return(
                1,
                ['fuserid' => $fuid], 
                ['data' => $result, 'code' => 0], 
                1
            );
        } else {
            $this->front_return(
                1,
                ['fuserid' => $fuid], 
                ['data' => '缺少用户编号', 'code' => -1], 
                1
            );
        }
    }
    
    /**
     * @name 返回给前端
     * @param int $type 调用类型
     * @param array $front_input 入参
     * @param array $pre_return 回参
     * @param int $flag 标识
     */
    public function front_return($type, $front_input, $pre_return, $flag = 0)
    {
        $this->load->service('public/Front_service', 'front_service');
        switch($type) {
            case 0: $this->front_service->front_basic_return($front_input, $pre_return);
                break;
            case 1: $this->front_service->front_basic_plus($front_input, $pre_return, $flag);
                break;
        }
    }

    /**
     * @name 签上上签
     */
    public function sign_a_contract($fuserid, $idnumber, $name, $type = '面签', $contract = '个人信息查询及使用授权书')
    {
        //上上签
        $this->load->service('public/Encryption_service', 'es');
        $file = $this->es->md5Hash($idnumber);
        $dirpath = '../upload/' . $file . '/bestsign';
        //检查是否已经有客户的文件夹
        $this->load->service('public/File_service', 'fs');
        if (!$this->fs->mkdir_idnumber($dirpath)) {
            Slog::log('生成文件夹目录失败');
        }
        $file_name = $dirpath.'/'.md5($idnumber.time()).".pdf";
        $map = [
            'account' => $idnumber,
            'name' => $name,
            'path' => $file_name,
            'userType' => '1',
            'taskId' => false,
            'identity' => $idnumber,
            'description' => $contract,
            'title' => $contract,
        ];
        switch($contract) {
            case '个人信息查询及使用授权书' :
                $map = array_merge(
                    $map, 
                    ['grxxxs' => [
                            'name' => $name,
                            'idno' => $idnumber,
                            'conname' => '上海源都金服信息服务有限公司',
                            'appname1' => '源都金服',
                            'appname2' => '源都金服',
                            'appname3' => '源都金服',
                            'appname4' => '源都金服',
                            'appname5' => '源都金服',
                            'signname' => $name,
                        ]
                    ]
                );break;
            case '数据查询授权书' :
                $map = array_merge(
                    $map, 
                    ['sjcxsqs' => [
                            'name' => $name,
                            'idno' => $idnumber,
                            'signname' => $name,
                        ]
                    ]
                );break;
        }
        
        $this->load->model('BestSign_model', 'bsm');
        $result = $this->bsm->bsAction($map);
        //result ['证书ID']
        if (0 == $result['code']) {
            $this->load->model('public/BestsignLog_model', 'bslm');
            $ret_log = $this->bslm->write_log($fuserid, $result['data'], $file_name, $type, $contract);
            if (!$ret_log) {
                Slog::log('日志写入失败');
            } else {
                Slog::log('日志写入成功');
            }
        }
    }

/*####H5控制器end########################################################*/

/*####预留控制器begin########################################################*/
    public function pre_b()
    {
        echo 'pre_b';
    }

    public function find()
    {
        $_POST['key'] = '18717816183';
        $this->getyysoriginalreport();
    }

    public function test()
    {
        $idnumber = '450104199402081516';
        $name = '梁俸嘉';
        $type = 'test';
        $contract = '测试合同';
        $fuserid = 'H001000013';
        //上上签
        $this->load->service('public/Encryption_service', 'es');
        $file = $this->es->md5Hash($idnumber);
        $dirpath = '/www/svn-code/upload/' . $file . '/bestsign';
        //检查是否已经有客户的文件夹
        $this->load->service('public/File_service', 'fs');
        Slog::log('文件夹检查 ：' . $this->fs->mkdir_idnumber($dirpath));
        if (!$this->fs->mkdir_idnumber($dirpath)) {
            Slog::log('生成文件夹目录失败');
        }
        $file_name = $dirpath.'/'.md5($idnumber.time()).".pdf";
        $map = [
            'account' => $idnumber,
            'name' => $name,
            'path' => $file_name,
            'userType' => '1',
            'taskId' => false,
            'identity' => $idnumber,
            'description' => '天上人间走一回,不回不切是上家',
            'title' => '天上人间个人信息查询',
            'grxxxs' => [
                //
                'name' => '班念山',
                'idno' => '412326198711254870',
                'conname' => '上海源都金服信息服务有限公司',
                'appname1' => '源都金服',
                'appname2' => '源都金服',
                'appname3' => '源都金服',
                'appname4' => '源都金服',
                'appname5' => '源都金服',
                'signname' => '班@大能',
            ]
        ];
        $this->load->model('BestSign_model', 'bsm');
        $result = $this->bsm->bsAction($map);
        Slog::log('上上签');
        Slog::log($result);
        //result ['证书ID']
        if (0 == $result['code']) {
            $this->load->model('public/BestsignLog_model', 'bslm');
            $ret_log = $this->bslm->write_log($fuserid, $result['data'], $file_name, $type, $contract);
            if (!$ret_log) {
                Slog::log('日志写入失败');
            }
        }
    }

    public function spdf()
    {
        $this->showpage('fms/tools/pdf', []);
    }


/*####预留控制器end########################################################*/

/*####基础begin############################################*/
    /**
     * @name 加载基本的helper
     */
    private function basicloadhelper()
    {
        //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);
    }

    /**
     * @name 列表页面配置信息
     */
    private function list_page_conf()
    {
        //基础设置
        //id字段 默认row.id
        $this->id_field = 'row.id';
        //状态字段 默认row.obj_status
        $this->status_field = 'row.obj_status';

        $this->sbtn_option->edit->if_status = '>0';

        $this->shen_he_type = 2;
        //后端访问方法
        foreach ($this->sbtn_option as $key => $value) {
            $this->sbtn_option->$key->method = ucfirst($this->sbtn_option->$key->method);
        }
    }

    /** 
     * @name 增加记录
     * @return array
     */
    public function add()
    {
        $business_type = $this->uri->segment(3);
        //业务类型 单业务类型有效
        $data = $this->controller_conf[$business_type]['add']['data'];
        // Slog::log($this->controller_conf[$business_type]['add']['service']);
        $service = $this->controller_conf[$business_type]['add']['service']['service_as'];
        $fn = $this->controller_conf[$business_type]['add']['fn'];
        //接收入参
        $this->load->service('business/RecordBasicData_service', 'rbds');
        $ret = $this->rbds->update_data($data);
        $info = $ret['code'] ? $ret['data'] : [];
        Slog::log(['更新用户信息 控制器 提交给service的数据', $info]);
        if ($ret['data']) {
            //提交  //更新数据
            $this->load->service($data['business_service'][0], $data['business_service'][1]);
            $ret = $this->$service->$fn($info);
            Slog::log($ret);
            if ((-1 == $ret['code']) || (100 == $ret['code'])) {
                if (100 == $ret['code']) {
                    $ret['code'] = 0;
                }
                Slog::log('上传图片 入参检查:' . json_encode([$business_type, $ret['data']], JSON_UNESCAPED_UNICODE));
                echo json_encode(['code' => $ret['code'], 'msg' => $ret['msg'], 'url' => '', 'data' => $ret['data']], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        Slog::log('修改数据返回结果');
        echo json_encode(['code' => isset($ret['code']->code) ? $ret['code']->code : $ret['code'], 'msg' => $ret['msg'], 'url' => site_url($data['finish_url']), 'data' => !empty($ret['data']) ? $ret['data'] : []]);
        exit;
    }

    /**
     * @name 编辑
     * @return html code
     */
    public function edit()
    {
        $result = [];
        $result_color = [];
        $result_check = [];
        $result_edit = [];
        //load service 
        $business_type = $this->uri->segment(4);
        $id = $this->uri->segment(3);
        $service_conf = $this->controller_conf[$business_type][__FUNCTION__]['service'];
        $this->load->service($service_conf['service_name'], 'type_service');
        $role_array = [
            'edit' => getRolePowerDetails('Qiye','edit'),
            'editdo' => getRolePowerDetails('Qiye','editdo'),
            'BaoShen' => getRolePowerDetails('Qiye','BaoShen'),
            'GuoShen' => getRolePowerDetails('Qiye','GuoShen'),
            'BackShen' => getRolePowerDetails('Qiye','BackShen'),
            'Stop' => getRolePowerDetails('Qiye','Stop'),
            'Start' => getRolePowerDetails('Qiye','Start'),
            'PleaseEdit' => getRolePowerDetails('Qiye','PleaseEdit'),
            'YesEdit' => getRolePowerDetails('Qiye','YesEdit'),
            'NoEdit' => getRolePowerDetails('Qiye','NoEdit'),
        ];
        //调model获取数据
        if (!empty($id)) {
            $info = $this->type_service->edit(ucfirst($id));
            $result = $info;
            if (!empty($info)) {
                foreach ($info as $key => $value) {
                    if (isset($info[$key]['val'])) {
                        $result[$key] = $value['val'];
                    }
                    if (isset($info[$key]['status'])) {
                        $result_color[$key] = $value['status'];
                    }
                    if (isset($info[$key]['is_check'])) {
                        $result_check[$key] = $value['is_check'];
                    }
                }
                $result['business_type'] = $business_type;
            } else {
                $result = [];
            }
        } else {
            $result = [];
        }
        foreach ($role_array as $key => $item) {
            foreach ($item as $value) {
                $result_role[$key]['sample_'.$value] = 1;
            }
        }
        echo json_encode([
            'data' => $result, 
            'status'=> $result_color, 
            'check' => $result_check, 
            'edit' => !empty($result_role['edit']) ? $result_role['edit'] : [],
            'editdo' => !empty($result_role['editdo']) ? $result_role['editdo'] : [],
            'BaoShen' => !empty($result_role['BaoShen']) ? $result_role['BaoShen'] : [],
            'GuoShen' => !empty($result_role['GuoShen']) ? $result_role['GuoShen'] : [],
            'BackShen' => !empty($result_role['BackShen']) ? $result_role['BackShen'] : [],
            'Stop' => !empty($result_role['Stop']) ? $result_role['Stop'] : [],
            'Start' => !empty($result_role['Start']) ? $result_role['Start'] : [],
            'PleaseEdit' => !empty($result_role['PleaseEdit']) ? $result_role['PleaseEdit'] : [],
            'YesEdit' => !empty($result_role['YesEdit']) ? $result_role['YesEdit'] : [],
            'NoEdit' => !empty($result_role['NoEdit']) ? $result_role['NoEdit'] : [],
        ]);
    }

    /**
     * @name 获得当前用户的权限
     */
    public function getrole()
    {
        $role_array = [
            'edit' => getRolePowerDetails('Qiye','edit'),
            'editdo' => getRolePowerDetails('Qiye','editdo'),
            'BaoShen' => getRolePowerDetails('Qiye','BaoShen'),
            'GuoShen' => getRolePowerDetails('Qiye','GuoShen'),
            'BackShen' => getRolePowerDetails('Qiye','BackShen'),
            'Stop' => getRolePowerDetails('Qiye','Stop'),
            'Start' => getRolePowerDetails('Qiye','Start'),
            'PleaseEdit' => getRolePowerDetails('Qiye','PleaseEdit'),
            'YesEdit' => getRolePowerDetails('Qiye','YesEdit'),
            'NoEdit' => getRolePowerDetails('Qiye','NoEdit'),
        ];

        foreach ($role_array as $key => $item) {
            foreach ($item as $value) {
                $result_role[$key]['sample_'.$value] = 1;
            }
        }

        echo json_encode([
            'edit' => !empty($result_role['edit']) ? $result_role['edit'] : [],
            'editdo' => !empty($result_role['editdo']) ? $result_role['editdo'] : [],
            'BaoShen' => !empty($result_role['BaoShen']) ? $result_role['BaoShen'] : [],
            'GuoShen' => !empty($result_role['GuoShen']) ? $result_role['GuoShen'] : [],
            'BackShen' => !empty($result_role['BackShen']) ? $result_role['BackShen'] : [],
            'Stop' => !empty($result_role['Stop']) ? $result_role['Stop'] : [],
            'Start' => !empty($result_role['Start']) ? $result_role['Start'] : [],
            'PleaseEdit' => !empty($result_role['PleaseEdit']) ? $result_role['PleaseEdit'] : [],
            'YesEdit' => !empty($result_role['YesEdit']) ? $result_role['YesEdit'] : [],
            'NoEdit' => !empty($result_role['NoEdit']) ? $result_role['NoEdit'] : [],
        ]);
    }

    /**
     * @name 修改数据
     */
    public function editdo()
    {
        $business_type = $this->uri->segment(3);
        //业务类型 单业务类型有效
        $data = $this->controller_conf[$business_type]['editdo']['data'];
        // Slog::log($this->controller_conf[$business_type]['editdo']['service']);
        $service = $this->controller_conf[$business_type]['editdo']['service']['service_as'];
        $fn = $this->controller_conf[$business_type]['editdo']['fn'];
        //接收入参
        $this->load->service('business/RecordBasicData_service', 'rbds');
        $ret = $this->rbds->update_data($data);
        $info = $ret['code'] ? $ret['data'] : [];
        Slog::log(['更新用户信息 控制器 提交给service的数据', $info]);
        if ($ret['data']) {
            //提交  //更新数据
            $this->load->service($data['business_service'][0], $data['business_service'][1]);
            $ret = $this->$service->$fn($info);
            Slog::log($ret);
            if ((-1 == $ret['code']) || (100 == $ret['code'])) {
                if (100 == $ret['code']) {
                    $ret['code'] = 0;
                }
                Slog::log('上传图片 入参检查:' . json_encode([$business_type, $ret['data']], JSON_UNESCAPED_UNICODE));
                echo json_encode(['code' => $ret['code'], 'msg' => $ret['msg'], 'url' => '', 'data' => $ret['data']], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        Slog::log('修改数据返回结果');
        echo json_encode(['code' => isset($ret['code']->code) ? $ret['code']->code : $ret['code'], 'msg' => $ret['msg'], 'url' => site_url($data['finish_url']), 'data' => !empty($ret['data']) ? $ret['data'] : []]);
        exit;
    }

    /**
     * @name 改变状态
     * @other 
     * @param $fun 
     * @param $obj_type
     * @return json string
     */
    private function do_status($fun, $obj_type) 
    {
        $this->load->library('form_validation');
        //改状态
        $this->form_validation->set_rules('status_info', '', '');
        $this->form_validation->set_rules('id', '', 'integer|required');
        if ((false == $this->form_validation->run())) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
            return json_encode($ret);
        }
        $obj_id = $this->input->post('id', true);
        $status_info = $this->input->post('status_info', true);
        return ($fun($obj_type, $obj_id, $status_info));
    }

    /**
     * @name 报审流程 全量操作
     */
    public function apply_cmd_all($fn, $old_status, $type_name, $type)
    {
        $fuserid = $this->input->post('fuserid', true);
        $status_info = $this->input->post('status_info', true);
        $check_array = [];
        //调用初审通过
        $this->load->service('business/Applying_service', 'as');
        $this->as->register_config(strtolower($type), 'user/'.$type.'_service', 'service');
        //用通过的key名字 和 手机卡id去换实际的id
        $this->load->model('user/'.$type.'_model', $type);
        $tmp = $key_array = $this->$type->expand_fields;
        $userinfo = $this->user_service->find_user_by_fuserid($fuserid);
        if (isset($userinfo['id'])) {
            $id = $userinfo['id'];
            //准备好的id  这里调一个pool_model
            $ret = '';
            if (!empty($tmp)) {
                $this->load->model('pool_model', 'pm');
                $tmp = $this->pm->getpoolinfo_l($userinfo['id'], $tmp);
                $pool = [];
                foreach ($tmp as $value) {
                    $pool[] = ['type' => $value['obj_type'], 'id' => $value['obj_id'], 'status' => isset($value['status']) ? $value['status'] : $value['obj_status']];
                }
                $ret = $this->as->apply_cmd($fn, $old_status, $id, strtolower($type), $pool, $status_info, []);
            }   

            //基础记录通过
            if (isset($check_array['mobileNo']) && 'on' == $check_array['mobileNo']) {
                $ret = $this->as->apply_cmd($fn, $old_status, $id, strtolower($type), [['type'=>'mobile','id'=>$id]], $status_info, []);
            }
            if ($ret) {
                $msg = $type_name . '修改操作成功';
                $code = 0;
            } else {
                $msg = $type_name . '修改操作失败';
                $code = -1;
            }
        } else {
            $msg = '用户不存在';
            $code = -2;
        }
        echo json_encode(['msg' => $msg, 'code' => $code]);
        exit;
    }

    /**
     * @name 报审流程 部分操作
     */
    public function apply_cmd_part($fn, $old_status, $type_name, $type)
    {
        $fuserid = $this->input->post('fuserid', true);
        $status_info = $this->input->post('status_info', true);
        $check_array = [];
        //整理勾选的内容
        foreach ($_POST as $key => $value) {
            if (preg_match('/check_/', $key) && 'on' == $value) {
                $check_array[preg_replace('/check_/', '', $key)] = $value;
            }
        }
        // Slog::log($check_array);
        //调用初审通过
        $this->load->service('business/Applying_service', 'as');
        $this->as->register_config(strtolower($type), 'user/'.$type.'_service', 'service');
        //用通过的key名字 和 手机卡id去换实际的id
        $this->load->model('user/'.$type.'_model', $type);
        $key_array = $this->$type->expand_fields;
        $tmp = [];
        foreach ($check_array as $key => $value) {
            if (isset($key_array[$key])) {
                $tmp[] = $key_array[$key];
            }
        }
        $userinfo = $this->user_service->find_user_by_fuserid($fuserid);
        // Slog::log($userinfo);
        if (isset($userinfo[0]['id']) || isset($userinfo['id'])) {
            if (!empty($userinfo['id'])) {
                $userinfo_tmp = [];
                $userinfo_tmp[0] = $userinfo;
                $userinfo = $userinfo_tmp;
            }
            $id = $userinfo[0]['id'];
            //准备好的id
            //这里调一个pool_model
            $ret = '';
            if (!empty($tmp)) {
                $this->load->model('pool_model', 'pm');
                $tmp = $this->pm->getpoolinfo_l($userinfo[0]['id'], $tmp);
                $pool = [];
                foreach ($tmp as $value) {
                    $pool[] = ['type' => $value['obj_type'], 'id' => $value['obj_id'], 'status' => $value['obj_status']];
                }
                $ret = $this->as->apply_cmd($fn, $old_status, $id, strtolower($type), $pool, $status_info, []);
            }   
            if ($ret) {
                $msg = $type_name . '操作成功';
                $code = 0;
            } else {
                $msg = $type_name . '操作失败';
                $code = -1;
            }
        } else {
            $msg = '用户不存在';
            $code = -2;
        }
        echo json_encode(['msg' => $msg, 'code' => $code]);
        exit;
    }

    /**
     * @name 报审
     */
    public function BaoShen()
    {
        $conf = $this->conf_basic->load_config('apply_conf', 'Applying_service');
        $this->apply_cmd_part('baoShenStatus', $conf['baoShenStatus'], '报审', ucfirst($this->uri->segment(3)));
    }

    /**
     * @name 过审
     */
    public function GuoShen() 
    {
        $conf = $this->conf_basic->load_config('apply_conf', 'Applying_service');
        $this->apply_cmd_part('guoShenStatus', $conf['guoShenStatus'], '过审', ucfirst($this->uri->segment(3)));
    }

    /**
     * @name 驳回
     */
    public function BackShen() 
    {
        $conf = $this->conf_basic->load_config('apply_conf', 'Applying_service');
        $this->apply_cmd_part('backShenStatus', $conf['backShenStatus'], '驳回', ucfirst($this->uri->segment(3)));
    }

    /**
     * @name 停用
     */
    public function Stop()
    {
        $conf = $this->conf_basic->load_config('apply_conf', 'Applying_service');
        $this->apply_cmd_part('stopStatus', $conf['stopStatus'], '停用', ucfirst($this->uri->segment(3)));
    }

    /**
     * @name 启用
     */
    public function Start()
    {
        $conf = $this->conf_basic->load_config('apply_conf', 'Applying_service');
        $this->apply_cmd_part('startStatus', $conf['startStatus'], '启用', ucfirst($this->uri->segment(3)));
    }

    /**
     * @name 申请修改
     */
    public function PleaseEdit()
    {
        $conf = $this->conf_basic->load_config('apply_conf', 'Applying_service');
        $this->apply_cmd_part('pleaseEditStatus', $conf['pleaseEditStatus'], '申请修改', ucfirst($this->uri->segment(3)));
    }

    /**
     * @name 批准修改
     */
    public function YesEdit()
    {
        $conf = $this->conf_basic->load_config('apply_conf', 'Applying_service');
        $this->apply_cmd_part('yesEditStatus', $conf['yesEditStatus'], '批准修改', ucfirst($this->uri->segment(3)));
    }

    /**
     * @name 驳回修改
     */
    public function NoEdit()
    {
        $conf = $this->conf_basic->load_config('apply_conf', 'Applying_service');
        $this->apply_cmd_part('noEditStatus', $conf['noEditStatus'], '驳回修改', ucfirst($this->uri->segment(3)));
    }

    /**
     * @name 显示流程信息
     */
    public function History()
    {
        $id = $this->input->get('obj_id',true);
        $tmp = explode('/', $_SERVER['HTTP_REFERER']);
        // Slog::log($tmp);
        $type = preg_replace('/list/', '', $tmp[count($tmp)-1]);
        if ('qy' == $type) {
            $type = 'user';
        }
        Slog::log('入参: '.json_encode([$type, $id]));
        $arr = historyStatus($type, $id);
        echo json_encode($arr);
    }
/*####基础end############################################*/

/*####其它begin############################################*/
    public function provedelete(){
        if ($_SESSION['fms_userrole'] != 1)
        {
            echo iconv('gb2312','utf-8',"没有权限！");exit;
        }
        $this->load->model('qiye_model','qiye');
        $idnumfile = $this->uri->segment(3);
        $type = $this->uri->segment(4);
        $filename = $this->uri->segment(5);
        $jsonrespose=$this->input->get('jsp',true);
        $dirpath = "../upload/";
        $resp = function (){
            echo '200';
        };
        if(unlink($dirpath.$idnumfile.'/'.$type.'/'.$filename)){
            $jsonrespose ? redirect('/qiye/provedit/'.$idnumfile.'/'.$type, 'refresh'): $resp();
        }else{
            echo "err";
        }

    }

    public function provedit()
    {
        $this->load->model('qiye_model','qiye');
        $idnumfile = $this->uri->segment(3);
        $type = $this->uri->segment(4);
        $dirpath = "../upload/";
        if(!is_dir($dirpath.$idnumfile.'/'.$type)){
            $this->qiye->mkdir_idnumber($dirpath.$idnumfile.'/'.$type);
        }
        $filearr = scandir($dirpath.$idnumfile.'/'.$type);
        //var_dump($_SERVER);exit;
        $data['fwqip'] = $_SERVER['HTTP_HOST'];
        $data['filearr'] = $filearr;
        $data['idnumfile'] = $idnumfile;
        $data['type'] = $type;
        $this->showpage('fms/provedit',$data);
    }

    public function proveupfile(){
        $this->load->model('qiye_model','qiye');
        $type = $this->input->post('type',true);
        $idnumfile = $this->input->post('idnumfile',true);
        $responseJson=$this->input->post('jsonresp',true);
        $newfile = ($responseJson?(preg_match('/\d+/',$responseJson) ? hash('SHA1',trim($responseJson)).'_' : $responseJson."_"):$type).rand(1000,9999);
        $dirpath = "../upload/";
        if(!is_dir($dirpath.$idnumfile.'/'.$type)){
            $this->qiye->mkdir_idnumber($dirpath.$idnumfile.'/'.$type);
        }
        $refileinfo = $this->qiye->fileup($dirpath.$idnumfile.'/'.$type,'idnumimgd',$newfile);

        if($responseJson){
            if($refileinfo == 'uperr' || $refileinfo == 'rnerr'){
                printf("<script>parent.complete('%s','%s')</script>",500,'上传失败');
                exit();
            }
            $str=$this->load->view('fms/filecontent',['f'=>base_url().'../upload/'.$idnumfile.'/'.$type.'/'.$refileinfo,'id'=>$idnumfile,'t'=>$type],true);
            printf("<script>parent.complete('%s',%s,'%s')</script>",200,json_encode($str),$responseJson);
            exit();
        }

        if($refileinfo == 'uperr' || $refileinfo == 'rnerr'){
            echo '图片上传失败';exit;
        }
        redirect('/qiye/provedit/'.$idnumfile.'/'.$type, 'refresh');
    }

    public function regsh(){
        if ($_SESSION['fms_userrole'] != 1)
        {
            echo iconv('gb2312','utf-8',"没有权限！");exit;
        }
        $this->load->model('qiye_model','qiye');
        $step = $this->uri->segment(3);
        $status = $this->uri->segment(4);
        $fuserid = $this->uri->segment(5);
        $data['step'] = $step;
        $data['status'] = $status;
        $data['fuserid'] = $fuserid ;
        $this->showpage('fms/regsh',$data);
    }

    public function regshdo(){
        $this->load->model('qiye_model','qiye');
        $step = $this->input->post('step',true);
        $data['status'] = $this->input->post('status',true);
        $data['fuserid'] = $this->input->post('fuserid',true);
        $data['comment'] = $this->input->post('comment',true);
        $data['comment'] = $this->input->post('comment',true);
        $data['lryg'] =  $_SESSION['fms_userid'];
        $data['cdate'] =  date('Y-m-d H:i:s',time());
        $retdata = $this->qiye->updateQiyereg($data);
        if($step == 'one'){
            redirect('/qiye/regaudit/', 'refresh');
        }else{
            redirect('/qiye/audit/', 'refresh');
        }
        
    }
    
    public function checklist()
    {
        $this->load->model('qiye_model','qiye');
        $fuseridinfo = $this->qiye->getuserinfodata('01');
        if(!isset($fuseridinfo[0]['fuserid'])){
            exit;
        }
        foreach($fuseridinfo as $k=>$v){
            $c_list[$k]["fuserid"] = $v['fuserid'];
            $c_list[$k]["name"] = $v['name'];
            $c_list[$k]["idnumber"] = $v['idnumber'];
            $c_list[$k]["companyname"] = $v['companyname'];
            if($v['utype'] == '00'){
                $c_list[$k]["utype"] = '企业';
            }else{
                $c_list[$k]["utype"] = '个人';
            }
            $c_list[$k]["comment"] = $v['comment'];
            $c_list[$k]["idnumimgu"] = "<img style='width:100px;height:60px' src='http://".$_SERVER['HTTP_HOST'].'/upload/'.$v['idnumimgu']."' />";
            $c_list[$k]["idnumimgd"] = "<img style='width:100px;height:60px' src='http://".$_SERVER['HTTP_HOST'].'/upload/'.$v['idnumimgd']."' />";
            
            $c_list[$k]["lryg"] = $v['lryg'];
            $c_list[$k]["cdate"] = $v['cdate'];
            $c_list[$k]["op"] = "<a href='".site_url('qiye/regedit/'.$v['fuserid'])."'>编辑</a>&nbsp;
            <a href='".site_url('qiye/regsh/two/02/'.$v['fuserid'])."'>审核通过</a>&nbsp;
            <a href='".site_url('qiye/regsh/two/00/'.$v['fuserid'])."'>审核不通过</a>&nbsp;
            <a href='#'>删除</a>";
        }
        
        $reponseData['rows'] = $c_list;
        $reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }

    public function provelist()
    {
        if ($_SESSION['fms_userrole'] != 1)
        {
            echo iconv('gb2312','utf-8',"没有权限！");exit;
        }
        $this->load->model('qiye_model','qiye');
        $idcard=$this->input->get('idcard',true);
        $uname=$this->input->get('uname',true);
        $userinfodata = $this->qiye->findshinfo($idcard,$uname);
        $dirpath = "../upload/";

        foreach($userinfodata as $k=>$v){
            $c_list[$k]["name"] = $v['companyname'];
            $c_list[$k]["idnumfile"] = $v['idnumfile'];
            if(is_dir($dirpath.$v['idnumfile'].'/ptype')){
                $c_list[$k]["p_type"] = $this->_buildLink([$v['idnumfile'],'ptype'],count(scandir($dirpath.$v['idnumfile'].'/ptype'))-2);
            }else{ 
                $c_list[$k]["p_type"] = $this->_buildLink([$v['idnumfile'],'ptype'],0);
            }
            if(is_dir($dirpath.$v['idnumfile'].'/prele')){
                $c_list[$k]["p_rele"] = $this->_buildLink([$v['idnumfile'],'prele'],count(scandir($dirpath.$v['idnumfile'].'/prele'))-2);
            }else{
                $c_list[$k]["p_rele"] = $this->_buildLink([$v['idnumfile'],'prele'],0);
            }
            if(is_dir($dirpath.$v['idnumfile'].'/pfin')){
                $c_list[$k]["p_finance"] = $this->_buildLink([$v['idnumfile'],'pfin'],count(scandir($dirpath.$v['idnumfile'].'/pfin'))-2);
            }else{
                $c_list[$k]["p_finance"] = $this->_buildLink([$v['idnumfile'],'pfin'],0);
            }
            if(is_dir($dirpath.$v['idnumfile'].'/pent')){
                $c_list[$k]["p_ent"] = $this->_buildLink([$v['idnumfile'],'pent'],count(scandir($dirpath.$v['idnumfile'].'/pent'))-2);
            }else{
                $c_list[$k]["p_ent"] = $this->_buildLink([$v['idnumfile'],'pent'],0);
            }
            $c_list[$k]["p_act"] = sprintf("<a href='%s'>打包下载</a>",site_url('qiye/compress').'/'.$v['idnumfile']);
        }
        $reponseData['rows'] = $c_list;
        $reponseData["total"] = count($c_list);
        echo json_encode($reponseData);
    }
}
?>