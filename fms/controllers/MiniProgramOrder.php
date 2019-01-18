<?php


/**

 * @desc 小程序订单管理
 */
class MiniProgramOrder extends Admin_Controller
{
	
	public function __construct()
    {
        parent::__construct();
        $this->basicloadhelper();
    }

    /**
     * @name 加载基本的helper
     */
    private function basicloadhelper()
    {
        //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);

        //业务model
        $this->load->model('Baodan_model', 'baodan_model');
        $this->load->model('MiniproHousePrice_model', 'miniprohouserprice_model');
    }

//页面
	/**
     * @name 小程序订单管理-待处理
     * @method post
     * @param ['']
     * @url MiniProgramOrder/stay
     * @return json data | html string
     */
    public function stay()
    {
        if (!empty($_POST)) {
            //POST 数据检查 通过就是列表查询 没有通过就是页面渲染
            $condition = !empty($_POST['condition']) ? $_POST['condition'] : '';
            $type = !empty($_POST['type']) ? $_POST['type'] : '';
            $page = !empty($_POST['page']) ? $_POST['page'] : '';
            $size = !empty($_POST['size']) ? $_POST['size'] : '';
            if ($type && $page && $size) {
                switch ($type) {
                    case 'on':
                        $type = [21,23,24];
                        break;
                    case 'wait':
                        $type = [20];
                        break;
                    case 'done':
                        $type = ' `s.status` NOT BETWEEN 20 AND 29';
                        break;
                    default:
                        $type = [20];
                        break;
                }
                $this->load->model('BaodanStatus_model', 'baodanstatus_model');
                //查询
                $ret = $this->baodan_model->findlist_by_condition($condition, $type, $size, $page);
                //提取house id 信息
                $house_ids = [];
                foreach ($ret['listdata'] as $value) {
                    $house_ids[] = $value['house_price_id'];
                }

                $house = $this->miniprohouserprice_model->find_by_ids($house_ids);
                $status_array = $this->baodanstatus_model->statusArr;
                foreach ($ret['listdata'] as $key => $value) {
                   foreach ($house as $hkey => $item) {
                       if ($item['id'] == $value['house_price_id']) {
                            $ret['listdata'][$key]['diYaZongJia'] = $item['diYaZongJia'];
                       }
                       $ret['listdata'][$key]['status'] = $status_array[$value['status']];
                       $ret['listdata'][$key]['dn_status'] = $value['status'];
                   }
                }

                //从整理数据
                $data = [
                    //列表数据
                    'list' => $ret['listdata'],
                    //页面数据 页面处理需要用的数据 当前页 页面数量 页数 页面按钮等信息
                    'info' => [
                        'pageindex' => $page,
                        'pagemax' => ceil($ret['info']['total']/$size) ? ceil($ret['info']['total']/$size) : 1,
                        'pagebtnrange' => 9,
                        'pagesize' => $size,
                        'total' => $ret['info']['total']
                    ],
                    //入参数据
                    'params' => [
                        'condition' => $condition,
                        'type' => $type, 
                        'size' => $size, 
                        'page' => $page
                    ],
                ];
                //返回
                $ret = [
                    'code' => 0,
                    'msg' => '查询完成',
                    'data' => $data,
                ];
                echo json_encode($ret, JSON_UNESCAPED_UNICODE);
                exit;
            }
            $ret = [
                'code' => -1,
                'msg' => '入参错误',
                'data' => [],
            ];
            echo json_encode($ret, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $data = [];
        $this->showpage('fms/miniprogram/stayorder', $data);
    }


//动作接口
    /**
     * @name 查看按钮 报单的详细信息
     * @url MiniProgramOrder/info
     */
    public function info()
    {
        if (empty($_GET['id'])) {
            echo json_encode([
                'code' => -1,
                'msg' => '参数错误',
                'data' => []
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $this->load->model('MiniprogramOrderUserinfo_model', 'miniprogramorderuserinfo_model');
        $this->load->model('MiniprogramOrderHouseinfo_model', 'miniprogramorderhouseinfo_model');
        //获取这个报单的全部信息
        $ret_bd = $this->baodan_model->find_by_id($_GET['id']);
        $ret_house = $this->miniprohouserprice_model->find_by_id($ret_bd['house_price_id']);
        $ret_userinfo = $this->miniprogramorderuserinfo_model->find_by_id($_GET['id']);
        $ret_houseinfo = $this->miniprogramorderhouseinfo_model->find_by_id($_GET['id']);

        echo json_encode([
            'code' => 0,
            'msg' => '查询完成',
            'data' => ['baodan' => $ret_bd, 'house' => $ret_house, 'userinfo' => $ret_userinfo, 'houseinfo' => $ret_houseinfo]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @name 保存数据
     * @url MiniProgramOrder/save
     * @method post
     * @return json string
     */
    public function save()
    {
        if (empty($_POST['bd_id'])) {
            echo json_encode([
                'code' => -1,
                'msg' => '参数错误',
                'data' => []
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $data = $_POST;
        $result = [];
        //根据提交的类型不同调用不同的保存方式
        switch ($data['type']) {
            case 'idnumber1':
                $result = $this->save_idnumber_info1($data);
                break;
            case 'idnumber2':
                $result = $this->save_idnumber_info2($data);
                break;
            case 'house1':
                $result = $this->save_house_info1($data);
                break;
            case 'house2':
                $result = $this->save_house_info2($data);
                break;
            default:
                Slog::log('类型异常 ' . $data['type']);
                break;
        }
        Slog::log('保存结果');
        Slog::log($result);
        if ($result) {
            // $img_explode = explode('/', $data['img_path']);
            // $img_file_name = $img_explode[count($img_explode)-1];
            //检查图片是否需要迁移
            $this->load->service('public/File_service', 'file_service');
            //获取这个报单的全部信息
            Slog::log('type'.$data['type']);
            $ret_bd = $this->baodan_model->find_by_id($data['bd_id']);
            if ('idnumber1' == $data['type'] || 'idnumber2' == $data['type']) {
                //迁移图片  迁移该保单的全部身份证图片
                $base_path = '/home/upload/mini_upload_tmp/';
                foreach (json_decode($ret_bd['idNumberT']) as $value) {
                    $img_file_name = $value;
                    Slog::log('判断文件是否存在' . $base_path . $img_file_name);
                    if (file_exists($base_path . $img_file_name)) {
                        // Slog::log('文件存在');
                        $body_path = '/home/upload/preorder/' . $data['bd_id'];
                        $new_path =  $body_path . '/idnumber/';
                        // Slog::log('检查路径');
                        $this->file_service->mkdir_idnumber($body_path);
                        $this->file_service->mkdir_idnumber($new_path);
                        // Slog::log('移动文件');
                        copy($base_path . $img_file_name, $new_path . $img_file_name); //拷贝到新目录
                        unlink($base_path . $img_file_name); //删除旧目录下的文件
                        //调用奚老师的内容
                        $this->load->model('Upload_manage_model','um');
                        $this->um->del_upload($img_file_name);
                    }
                }
            }
            if ('house1' == $data['type'] || 'house2' == $data['type']) {
                //迁移图片
                $ret_house = $this->miniprohouserprice_model->find_by_id($ret_bd['house_price_id']);
                $base_path = '/home/upload/mini_upload_tmp/';
                foreach (json_decode($ret_house['img_path']) as $value) {
                    $img_file_name = $value;
                    Slog::log('判断文件是否存在' . $base_path . $img_file_name);
                    if (file_exists($base_path . $img_file_name)) {
                        $body_path = '/home/upload/preorder/' . $data['bd_id'];
                        $new_path =  $body_path . '/house/';
                        $this->file_service->mkdir_idnumber($body_path);
                        $this->file_service->mkdir_idnumber($new_path);
                        copy($base_path . $img_file_name, $new_path . $img_file_name); //拷贝到新目录
                        unlink($base_path . $img_file_name); //删除旧目录下的文件
                        //调用奚老师的内容
                        $this->load->model('Upload_manage_model','um');
                        $this->um->del_upload($img_file_name);
                    }
                }
            }
        }

        $data = [
            $result
        ];

        echo json_encode([
            'code' => 0,
            'msg' => '执行完成',
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @name 更新报单信息
     * @url MiniProgramOrder/savebaodan
     * @method post
     * @return json string
     */
    public function savebaodan()
    {
        if (empty($_POST['bd_id'])) {
            echo json_encode([
                'code' => -1,
                'msg' => '参数错误',
                'data' => []
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        //拆分数据
        $baodan_key_array = ['user_name', 'zhiye_type', 'get_money', 'get_money_type', 'pre_yu_e', 'di_ya_type', 'get_money_term', 'house_type', 'product_type', 'jg_code', 'product_name'];
        $house_key_array = ['house_area', 'finish_date', 'gui_hua_yong_tu', 'di_ya', 'yidi_yue', 'full_house_name', 'ZheKouZongJia'];
        //获取对应的数据
        foreach ($baodan_key_array as $value) {
            $array[$value] = !empty($_POST[$value]) ? $_POST[$value] : '';
        }
        foreach ($house_key_array as $value) {
            $house_array[$value] = !empty($_POST[$value]) ? $_POST[$value] : '';
        }
        $baodan_result = $this->baodan_model->update_record($array, $_POST['bd_id']);
        $house_result = $this->miniprohouserprice_model->update_record($house_array, $_POST['house_price_id']);

        $result = [$baodan_result, $house_result];

        $data = [
            $result
        ];

        echo json_encode([
            'code' => 0,
            'msg' => '执行完成',
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @name 获取身份证信息
     * @url MiniProgramOrder/getidnumberinfo?id={idnumber}
     * @method get
     * @param id idnumber
     * @return json string
     */
    public function getidnumberinfo()
    {
        if (empty($_GET['id']) || (15 != strlen($_GET['id']) && 18 != strlen($_GET['id']))) {
            echo json_encode([
                'code' => 0,
                'msg' => '参数错误',
                'data' => []
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $idnumber = $_GET['id'];
        //分析身份证数据信息
        $this->load->service('public/Idnumber_service', 'idnumber_service');
        $res = $this->idnumber_service->checkId($idnumber);
        $birth = $this->idnumber_service->get_birth_date($idnumber);
        $age = $this->idnumber_service->get_age_by_id($idnumber);
        $data = [
            'birth' => $birth,
            'sex' => $res['sex'],
            'area' => $res['area'],
            'age' => $age
        ];
        echo json_encode([
            'code' => 0,
            'msg' => '处理完成',
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @name 获取报单人员的信息
     */
    public function getsalesinfo()
    {
        if (empty($_GET['id'])) {
            echo json_encode([
                'code' => 0,
                'msg' => '参数错误',
                'data' => []
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $id = $_GET['id'];
        $this->load->model('Wesing_merchant_model','wesing_merchant_model');
        $info = $this->wesing_merchant_model->get_one($id);
        $res = $this->wesing_merchant_model->get_one_by_name($info['userid']);
        $data = $res;
        echo json_encode([
            'code' => 0,
            'msg' => '处理完成',
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;

    }

//回调接口
    /**
     * @name 图片编辑回调接口
     * @method post ['img'=>string,'bd_id'=>string|int]
     * @url MiniProgramOrder/callbackpicedit
     * @return json string ['code'=>int,'msg'=>string,'data'=>[]] code:(0 成功),(!=0 失败); msg:文字提示
     */
    public function callbackpicedit()
    {
        if (empty($_POST['bd_id']) || empty($_POST['img'])) {
            echo json_encode([
                'code' => -1,
                'msg' => '参数错误',
                'data' => []
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $id = $_POST['bd_id'];
        $img = $_POST['img'];
        $img = explode('/', $img);
        $img = $img[count($img)-1];
        //该图片经过了编辑 则relation_img 保存的数据需要做相应的变换
        //优先级是relation_img中的优先级最高
        $this->load->model('MiniprogramOrderUserinfo_model', 'miniprogramorderuserinfo_model');
        $this->load->model('MiniprogramOrderHouseinfo_model', 'miniprogramorderhouseinfo_model');
        $ret_bd = $this->baodan_model->find_by_id($id);
        $ret_house = $this->miniprohouserprice_model->find_by_id($ret_bd['house_price_id']);
        $ret_userinfo = $this->miniprogramorderuserinfo_model->find_by_id($id);
        $ret_houseinfo = $this->miniprogramorderhouseinfo_model->find_by_id($id);
        //检查是哪一张图片
        foreach (json_decode($ret_userinfo, true) as $value) {
            if (false !== strstr($value, $img)) {

            }
        }


        $data = [];
        echo json_encode([
            'code' => 0,
            'msg' => '处理完成',
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

//子funtion
    /** 
     * @name 保存订单审贷人第一部分身份证数据
     */
    private function save_idnumber_info2($data)
    {
        $array = [
            'name' => $data['name'],
            'idnumber' => $data['idnumber'],
            'birth' => $data['birth'],
            'sex' => $data['sex'],
            'birth_area' => $data['area'],
            'bd_id' => $data['bd_id'],
            // 'remark2' => $data['remark2'],
            'img_path' => $data['img_path'],
            'pic2_type' => $data['pic2_type'],
            // 'nation' => $data['nation'],
            'idnumber_address' => $data['idnumber_address'],
            'sort' => 1,
        ];
        $this->load->model('MiniprogramOrderUserinfo_model', 'miniprogramorderuserinfo_model');
        return $this->miniprogramorderuserinfo_model->record_data($array);
    }

    /** 
     * @name 保存订单审贷人第二部分身份证数据
     */
    private function save_idnumber_info1($data)
    {
        $array = [
            'qfjg' => $data['qfjg'],
            'able_start' => $data['able_start'],
            'able_end' => $data['able_end'],
            'bd_id' => $data['bd_id'],
            // 'remark1' => $data['remark1'],
            'img_path' => $data['img_path'],
            'pic1_type' => $data['pic1_type'],
            'able_type' => $data['able_type'],
            'sort' => 0,
        ];
        $this->load->model('MiniprogramOrderUserinfo_model', 'miniprogramorderuserinfo_model');
        return $this->miniprogramorderuserinfo_model->record_data($array);
    }

    /**
     * @name 保存房屋页面1
     */
    private function save_house_info1($data)
    {
        $array = [
            'housecode' => $data['housecode'],
            'recorddate' => $data['recorddate'],
            'housearea' => $data['housearea'],
            'bd_id' => $data['bd_id'],
            'remark' => $data['remark'],
            'img_path' => $data['img_path'],
            'sort' => 0,
            'ishousenewold' => $data['ishousenewold']
        ];
        $this->load->model('MiniprogramOrderHouseinfo_model', 'miniprogramorderhouseinfo_model');
        return $this->miniprogramorderhouseinfo_model->record_data($array);
    }

    /**
     * @name 保存房屋页面2
     */
    private function save_house_info2($data)
    {
        $array = [
            'bd_id' => $data['bd_id'],
            'obligee' => $data['obligee'],
            'address' => $data['address'],
            'housenum' => $data['housenum'],
            'buildingarea' => $data['buildingarea'],
            'buildingtype' => $data['buildingtype'],
            'useby' => $data['useby'],
            'buildingtotalfloor' => $data['buildingtotalfloor'],
            'buildingfinishdate' => $data['buildingfinishdate'],
            'remark' => $data['remark'],
            'img_path' => $data['img_path'],
            'sort' => 1,
        ];
        $this->load->model('MiniprogramOrderHouseinfo_model', 'miniprogramorderhouseinfo_model');
        return $this->miniprogramorderhouseinfo_model->record_data($array);
    }

    /**
     * @name 保存房屋页面3
     */
    private function save_house_info3($data)
    {
        $array = [
            'bd_id' => $data['bd_id'],
            'remark' => $data['remark'],
            'img_path' => $data['img_path'],
            'sort' => 2,
        ];
        $this->load->model('MiniprogramOrderHouseinfo_model', 'miniprogramorderhouseinfo_model');
        return $this->miniprogramorderhouseinfo_model->record_data($array);
    }
}

?>