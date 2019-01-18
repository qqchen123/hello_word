<?php 

/**
 * @desc 报单上传资料
 */
class ClientPreOrder extends Admin_Controller {
    /**
     * @name 构造函数
     */
	public function __construct() 
	{
        // phpinfo();exit;
		parent::__construct();
		// //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);

        $this->load->model('PreOrder_model', 'pre_order_model');
        
	}

    /**
     * @name 起始页 录入客户信息/补录资料入口
     * @url /client/ClientPreOrder/index
     */
	public function index()
	{
        $_SESSION['fms_uname'] = 'visitor';//临时放置一个内容 因为showpage里面要用
        $data = [];
        $this->showpage('fms/client/preorder/testupload', $data); 
	}

    /**
     * @name 创建报单编号
     * @url /client/ClientPreOrder/create_pre_order
     * @other post 
     * [
     *     'code' => 员工编号(stirng), //必填
     *     'channel' => 渠道(string),//必填
     *
     *     'phone' => 手机号(string),//选填
     *     'idnumber' => 身份证号(string),//选填
     *     'name' => 姓名(string),//选填
     * ]
     * @return json_string ['code' => int, 'msg' => string, 'data' => string|array]
     */
    public function create_pre_order()
    {
        if (!empty($_POST)) {
            $get_data['channel'] = $this->input->post('channel', true);
            $get_data['code'] = $this->input->post('code', true);

            $get_data['phone'] = $this->input->post('phone', true);
            $get_data['idnumber'] = $this->input->post('idnumber', true);
            $get_data['name'] = $this->input->post('name', true);
        } else {
            $body = @file_get_contents('php://input');
            Slog::log($body);
            $body = json_decode($body);
            $get_data['channel'] = !empty($body->channel) ? $body->channel : '';
            $get_data['code'] = !empty($body->code) ? $body->code : '';

            $get_data['phone'] = !empty($body->phone) ? $body->phone : '';
            $get_data['idnumber'] = !empty($body->idnumber) ? $body->idnumber : '';
            $get_data['name'] = !empty($body->name) ? $body->name : '';
        }
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Headers: content-type");
        //入参判断
        if (empty($get_data['channel']) || empty($get_data['code'])) {
            //参数不完整
            //4.整理信息返回
            $return_data = ['code' => -4, 'msg' => '参数不完整', 'data' => []];
            Slog::log('前端报单接口 入参: ' . json_encode($get_data, JSON_UNESCAPED_UNICODE) . '|| 返回: ' . json_encode($return_data));
            echo json_encode($return_data, JSON_UNESCAPED_UNICODE);exit;
        }

        $code = 0;
        $message = '';
        //为员工自动登录
        @$_SESSION['fms_userrole'] = 19;
        $_SESSION['fms_role_name'] = 'H5';
        $_SESSION['fms_id'] = $get_data['code'];
        $operator = $_SESSION['fms_username'] = trim($get_data['code']);

        if ($code == 0) {
            //2.检查用户是否有过往未完成订单，有则返回列表。没有则新建订单号
            $data = 0;
            //没有未完成的订单 生成订单号
            $pre_order = date('ymdHi') . $operator;
            //通过员工编号置换出ID
            $find_id = $this->pre_order_model->db
            ->select(['id'])->where('userid', $get_data['code'])
            ->get('wesing_merchant')->row_array();

            if (empty($find_id['id'])) {
                $return_data = ['code' => -5, 'msg' => '业务员编号错误', 'data' => $this->pre_order_model->db->last_query()];
                Slog::log('前端报单接口 入参: ' . json_encode($get_data, JSON_UNESCAPED_UNICODE) . '|| 返回: ' . json_encode($return_data));
                echo json_encode($return_data, JSON_UNESCAPED_UNICODE);exit;
            }
            // echo $pre_order;exit;
            if ($this->pre_order_model->create_record($pre_order, $get_data['channel'], $find_id['id'])) {
                $data = $pre_order;
            }
            
            if ($data === 0) {
                $code = -3;
                $message = '订单生成失败  请联系管理员';
            }
        }

        //4.整理信息返回
        $return_data = ['code' => $code, 'msg' => $message, 'data' => $data];
        Slog::log('前端报单接口 入参: ' . json_encode($get_data, JSON_UNESCAPED_UNICODE) . '|| 返回: ' . json_encode($return_data));
        echo json_encode($return_data, JSON_UNESCAPED_UNICODE);exit;
    }

    /**
     * @name 接收上传的图片 以及参数
     * @url /client/ClientPreOrder/getuploadpic
     * @param FILES 图片
     * @param type string 
     * @param no string|int
     * @param preorder string
     * @return json string
     */
    public function getuploadpic()
    {
        $pictype = $this->input->post('type', true);
        $filetype = $this->input->post('filetype', true);
        $preorder = $this->input->post('preorder', true);
        $fileno = $this->input->post('fileno', true);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Headers: content-type");
        //检查参数是否完整 不完整不需要接收上传的文件
        if (empty($pictype) || empty($preorder) || empty($filetype)) {
            echo json_encode([
                    'status' => 3,
                    'content' => "参数不完整！",
                ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        if (empty($fileno)) {
            $fileno = 1;
        }
        $cnt = 0;
        $pic_path_array = [];
        $picname = $_FILES['uploadfile']['name']; 
        $picsize = $_FILES['uploadfile']['size']; 
        if ($picname != "") { 
            if ($picsize > 2014000) { //限制上传大小 
                echo json_encode([
                    'status' => 0,
                    'content' => "图片大小不能超过2M",
                ], JSON_UNESCAPED_UNICODE);
                exit; 
            } 
            $type = strstr($picname, '.'); //限制上传格式 
            if ($type != ".gif" && $type != ".jpg" && $type != ".png") {
                echo json_encode([
                    'status' => 2,
                    'content' => '图片格式不对！' . $picname,
                ], JSON_UNESCAPED_UNICODE);
                exit; 
            }
            $rand = rand(100, 999); 
            $pics = uniqid() . $type; //命名图片名称 
            //上传路径 
            //用preorder直换id
            $ret = $this->pre_order_model->find_record_by_preorder($preorder);
            if (isset($ret['pre_order_id'])) {
                $id = $ret['pre_order_id'];
            }
            $path = "../upload/preorder/" . $id;
            $this->load->service('public/File_service', 'file_service');
            $this->file_service->mkdir_idnumber($path);
            $path .= '/' . $pictype . '/' . $fileno;
            $this->load->service('public/Os_service', 'os');
            if (strstr($this->os->getOS(), 'Windows')) {
                $path = iconv('utf-8', 'gbk', $path);
            }
            $this->file_service->mkdir_idnumber($path);
            $pic_path = $path . $pics; 
            move_uploaded_file($_FILES['uploadfile']['tmp_name'], $pic_path); 
        } 
        $size = round($picsize/1024,2); //转换成kb 
        $pic_path_array[] = $pic_path;
        //将路径信息写入到对应的json里
        $ret = $this->pre_order_model->find_record_by_preorder($preorder);
        $temp = json_decode($ret['content'], true);

        if (isset($temp[$pictype])) {
            if (!isset($temp[$pictype][$fileno]['type'])) {
                $temp[$pictype][$fileno]['type'] = [];
            }
            if (count($temp[$pictype][$fileno]['src']) != count($temp[$pictype][$fileno]['type'])) {
                //如果之前的图片没有设置type 则为之前的补为空字符出
                for ($y = 0; $y < (count($temp[$pictype][$fileno]['src']) - count($temp[$pictype][$fileno]['type'])); $y++) {
                    $temp[$pictype][$fileno]['type'][] = '其它';
                }
            }
            $temp[$pictype][$fileno]['src'][] = $pic_path;
            $temp[$pictype][$fileno]['type'][] = $filetype;
            $content = json_encode($temp, JSON_UNESCAPED_UNICODE);
            Slog::log($temp);
            $result = $this->pre_order_model->upload_pic_src_info($content, $preorder);
            if (!$result) {
                echo json_encode([
                    'status' => 5,
                    'content' => '上传成功 数据更新异常！',
                ], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $cnt++;
            }
        }
        echo json_encode([
            'status' => 1,
            'name' => $picname,
            'url' => $pic_path_array,
            'size' => $size,
            'content' => '上传成功'. $cnt .'张',
        ], JSON_UNESCAPED_UNICODE);
        exit;  
    }

    /**
     * @name 获取用户报单上传材料情况
     * @url /client/ClientPreOrder/getuploadcount
     */
    public function getuploadcount()
    {
        $preorder = $this->input->post('preorder', true);
        if (empty($preorder)) {
            echo json_encode([
                'status' => 3,
                'content' => '参数不完整！',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        //将路径信息写入到对应的json里
        $ret = $this->pre_order_model->find_record_by_preorder($preorder);
        $temp = json_decode($ret['content'], true);
        $cnt_array = [];
        foreach ($temp as $key => $value) {
            $cnt_array[$key] = !empty($value['src']) ? count($value['src']) : 0;
        }
        echo json_encode($cnt_array, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @name 显示编码页 用于展示客户边码 报单编码 业务员信息 如果信息确认无误就可以开始报单
     * @url /client/ClientPreOrder/showcode
     */
    public function showcode()
    {
        $_SESSION['fms_uname'] = 'visitor';//临时放置一个内容 因为showpage里面要用
        $get_data = $_GET;
        $data = [
            'order' => $get_data['order'],
            'name' => $get_data['name'],
            'idnumber' => $get_data['idnumber'],
            'fuserid' => $get_data['fuserid'],
            'operator' => $get_data['operator'],
            'code' => $get_data['code'],
        ];
        $this->showpage('fms/client/preorder/showcode', $data); 
    }

    /**
     * @name 上传身份证 用于上传身份证正反面
     * @url /client/ClientPreOrder/uploadidcard
     * @param post 
     * [
     *     'order' => 报单编号(string), 
     *     'idcardfront' => 身份证正面(file), 
     *     'idcardback' => 身份证反面(file)
     * ]
     * @return json_string ['code' => int, 'msg' => string, 'data' => string|array]
     */
    public function uploadidcard()
    {
        $_SESSION['fms_uname'] = 'visitor';//临时放置一个内容 因为showpage里面要用
        $img = isset($_POST['img']) ? $_POST['img'] : '';
        if ($img) {
            $_FILES['img'] = $img;
            //获取更多的修饰参数
            //类型
            $type = isset($_POST['type']) ? $_POST['type'] : '';

            //用户身份证
            $idnumber = isset($_POST['idnumber']) ? $_POST['idnumber'] : '';

            //按照之前的存放规则放置文件
            //构造出文件路径
            $this->load->service('public/Encryption_service', 'es');
            $file = $this->es->md5Hash($idnumber);
            $dirpath = '../upload/' . $file . '/preorder/' . $type;
            //检查路径是否存在 不存在则创建
            $this->load->service('public/File_service', 'fs');
            if ($this->fs->mkdir_idnumber($dirpath)) {
                // 获取图片
                list($type, $data) = explode(',', $img);
                 
                // 判断类型
                if (strstr($type, 'image/jpeg') != '') {
                    $ext = '.jpg';
                } elseif (strstr($type, 'image/gif') != '') {
                    $ext = '.gif';
                } elseif (strstr($type, 'image/png') != '') {
                    $ext = '.png';
                }
                 
                // 生成的文件名
                $photo = time() . $ext;
                 
                // 生成文件
                file_put_contents('\'' . $dirpath . '/' . $photo . '\'', base64_decode($data), true);
                 
                // 返回
                header('content-type:application/json;charset=utf-8');
                $ret = ['img' => $photo];

                echo json_encode($ret);exit;
            } else {
                Slog::log('目录创建失败');
                echo json_encode('目录创建失败');exit;
                //上传出错  需要返回非标准内容 供前端识别
            }
        } else {
            $data = [];
            $this->showpage('fms/client/preorder/uploadidcard', $data);
        }
    }

    /**
     * @name 上传房产证 用于上传房产证 那三页
     * @url /client/ClientPreOrder/uploadhouse
     */
    public function uploadhouse()
    {
        $_SESSION['fms_uname'] = 'visitor';//临时放置一个内容 因为showpage里面要用
        $data = [];
        $this->showpage('fms/client/preorder/uploadhouse', $data); 
    }

    /**
     * @name 上传结婚证 用于上传结婚证 三页/一页
     * @url /client/ClientPreOrder/uploadmc
     */
    public function uploadmc()
    {
        $data = [];
        $this->showpage('fms/client/preorder/uploadmc', $data); 
    }

    /**
     * @name 上传个人征信报告 用于上传个人征信报告 页数不确定
     * @url /client/ClientPreOrder/uploadcredit
     */
    public function uploadcredit()
    {
        $data = [];
        $this->showpage('fms/client/preorder/uploadcreit', $data); 
    }

    /**
     * @name 上传户口本 用于上传户口本 页数不确定
     * @url /client/ClientPreOrder/uploadhousehold
     */
    public function uploadhousehold()
    {
        $data = [];
        $this->showpage('fms/client/preorder/uploadhousehold', $data); 
    }

    /**
     * @name 上传最后展示各项的统计信息
     * @url /client/ClientPreOrder/showstats
     */
    public function showstats()
    {
        $data = [];
        $this->showpage('fms/client/preorder/showstats', $data);
    }
}

?>

