<?php 

/**
 * @desc API 前端 员工用h5 接口
 */
class Front_employee extends Admin_Controller {

	/**
	 * @name 构造函数 同时配置请求头的内容  支持跨域请求
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->basicloadhelper();
		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Headers: content-type");

        $this->load->model('public/Declaration_model', 'declaration_model');
        $this->load->service('business/YunFang_service', 'yunfang_service');
	}

	/**
	 * @name 调试页面
	 */
	public function index()
	{
        phpinfo();
		echo 2;exit;
	}

	/**
     * @name 员工前端h5登录
     * @url Front_employee/front_user_login
     */
    public function front_user_login()
    {
    	$request = $this->get_request();
    	$upass = !empty($request['pwd']) ? $request['pwd'] : '';
    	$uname = !empty($request['login_name']) ? $request['login_name'] : '';
        if(!$uname || !$upass){
            $this->front_return(
                1,
                ['uname' => $uname, 'upass' => $upass], 
                ['msg' => '账号或者密码不能为空', 'code' => -1], 
                1
            );
        }

        //修改by奚晓俊 开始----------------
            // $this->load->model('merchant_model','mers');
            // $adminInfo = $this->mers->getByField('userid',$uname);

            //增加roel_name Merchant_model读不了？？？
            $this->load->model('WorkLog_model','wl');
            //保持用userid代替username？？？
            $adminInfo = $this->wl->getUser(['userid'=>$uname]);
            if ($adminInfo) $adminInfo = $adminInfo[0];

        //修改by奚晓俊 结束----------------
        if(!$adminInfo) {
            $this->front_return(
                1,
                ['uname' => $uname, 'upass' => $upass], 
                ['msg' => '账号或者密码错误', 'code' => -1], 
                1
            );
        }

        $adminPass = $adminInfo['usermm'];
        $salt = $adminInfo['salt'];
        //echo md5($uname.$salt.$upass);
        if(md5($uname.$salt.$upass)!=$adminPass) {
            $this->front_return(
                1,
                ['uname' => $uname, 'upass' => $upass], 
                ['msg' => '账号或者密码错误', 'code' => -1], 
                1
            );
        }

        unset($adminInfo['upass']);
        $adminInfo['uname']=$adminInfo['username'];
        foreach ($adminInfo as $_key=>$_val){
            $_SESSION['fms_'.$_key] = $_val;
        }
        $_SESSION['login_time'] = date('Y-m-d H:i:s', time());
        $this->front_return(
            1,
            ['uname' => $uname, 'upass' => $upass], 
            ['msg' => '用户登录成功', 'code' => 0], 
            1
        );
    }

    /**
     * @name 提交用户基本信息
     * @other 包含 姓名 身份证 身份证正面  身份证反面
     * @request-method POST
     * @url api/Front_employee/sub_customer
     * @return  array ['code' => int, 'msg' => string]
     */
    public function sub_customer()
    {
    	$request = $this->get_request();
    	$name = !empty($request['username']) ? $request['username'] : '';
        $idnumber = !empty($request['useridcard']) ? $request['useridcard'] : '';
    	$login_name = !empty($request['employee']) ? $request['employee'] : '';
        //创建报单ID
        $d_id = $login_name . '-' . date('ymdHis');

    	//处理上传的图片文件
    	if (!empty($request['file'])) {
    		$this->load->service('public/File_service', 'file_service');
	    	$img_src = $this->file_service->upload_img('user', $d_id, 'idnumber');
    	}
		$front_string = !empty($img_src['data']['cardp']) ? $img_src['data']['cardp'] : '';
		$back_string = !empty($img_src['data']['cardpf']) ? $img_src['data']['cardpf'] : '';

    	//传递到 报单 mongo service/model  插入记录
		$array = [
            'd_id' => $d_id,
			'name' => $name,
			'idnumber' => $idnumber,
			'front_img' => $front_string,
			'back_img' => $back_string,
            'status' => 1,
            'employee' => $login_name
		];

		$ret = $this->declaration_model->insert_declara_info($array);
        Slog::log($ret);
    	//返回结果
    	if (!empty($ret)) {
    		$code = 0;
            $data = ['id' => $d_id];
    		$msg = '提交成功';
    	} else {
    		$code = -1;
            $data = [];
    		$msg = '提交失败 失败原因';
    	}

    	$this->front_return(
    		1, 
    		[
    			'name' => $name,
    			'idnumber' => $idnumber
    		], 
    		[
    			'code' => $code,
                'data' => $data,
    			'msg' => $msg
    		], 
    		1
    	);
    }

    /**
     * @name 上传房产信息
     * @other 包含地址  房产证编码页 内容页 附记页
     */
    public function sub_house_property()
    {
    	$request = $this->get_request();
        $address = !empty($request['address']) ? $request['address'] : '';
    	$area = !empty($request['area']) ? $request['area'] : '';
    	$d_id = !empty($request['id']) ? $request['id'] : '';

        //房估估信息
        $communityId = !empty($request['communityId']) ? $request['communityId'] : '';
        $buildingId = !empty($request['buildingId']) ? $request['buildingId'] : '';
        $unitId = !empty($request['unitId']) ? $request['unitId'] : '';

        if ($communityId && $buildingId && $unitId) {
            $house_report = $this->getprice([
                'communityId' => $communityId,
                'buildingId' => $buildingId,
                'houseId' => $unitId,
                'buildingArea' => $area
            ]);
        } else {
            $house_report = [];
        }
        

    	//处理上传的图片文件
    	if (!empty($request['file'])) {
    		$this->load->service('public/File_service', 'file_service');
            $img_src = $this->file_service->upload_img('house', $d_id, 'house');
    	}
        $codepage_string = !empty($img_src['data']['housepage']) ? $img_src['data']['housepage'] : '';
        $content_string = !empty($img_src['data']['housepaget']) ? $img_src['data']['housepaget'] : '';
    	$notes_string = !empty($img_src['data']['housepagef']) ? $img_src['data']['housepagef'] : '';

    	//传递到 报单 mongo service/model  跟新记录
		$array = [
			'address' => $address,
			'codepage_string' => $codepage_string,
			'content_string' => $content_string,
			'notes_string' => $notes_string,
            'area' => $area,
            'house_report' => $house_report
		];

		$check = $this->declaration_model->select_declara_info(['d_id' => $d_id], ['d_id']);
		if (!empty($check)) {
			$ret = $this->declaration_model->update_declara_info(['d_id' => $d_id], $array);
		} else {
            Slog::log($check);
            Slog::log($d_id);
        }

    	//返回结果
    	if (!empty($ret)) {
    		$code = 0;
            $data = ['id' => $d_id];
    		$msg = '提交成功';
    	} else {
    		$code = -1;
            $data = [];
    		$msg = '提交失败 失败原因';
    	}

    	$this->front_return(
    		1, 
    		[
    			'address' => $address,
    			'id' => $d_id
    		], 
    		[
    			'code' => $code,
                'data' => $data,
    			'msg' => $msg
    		], 
    		1
    	);
    }

    /**
     * @name 提交报单信息
     * @other 包含需求金额 借款用途 还款途径
     */
    public function sub_declaration()
    {
    	$request = $this->get_request();
    	$amount = !empty($request['amount']) ? $request['amount'] : '';
    	$useby = !empty($request['useby']) ? $request['useby'] : '';
    	$repaymentby = !empty($request['repaymentby']) ? $request['repaymentby'] : '';
        $d_id = !empty($request['id']) ? $request['id'] : '';

    	//传递到 报单 mongo service/model  跟新记录
		$array = [
			'amount' => $amount,
			'useby' => $useby,
			'repaymentby' => $repaymentby,
		];

		$check = $this->declaration_model->select_declara_info(['d_id' => $d_id], ['d_id']);
		if (!empty($check)) {
			$ret = $this->declaration_model->update_declara_info(['d_id' => $d_id], $array);
		}

    	//返回结果
    	if (!empty($ret)) {
    		$code = 0;
            $data = ['id' => $d_id];
    		$msg = '提交成功';
    	} else {
    		$code = -1;
            $data = [];
    		$msg = '提交失败 失败原因';
    	}

    	$this->front_return(
    		1, 
    		[
    			'amount' => $amount,
				'useby' => $useby,
				'repaymentby' => $repaymentby,
    			'd_id' => $d_id
    		], 
    		[
    			'code' => $code,
                'data' => $data,
    			'msg' => $msg
    		], 
    		1
    	);
    }

    /**
     * @name 获取员工订单列表
     */
    public function get_orderlist()
    {
        $request = $this->get_request();
        $employee = !empty($request['employee']) ? $request['employee'] : '';
        $list = $this->declaration_model->select_declara_info(['employee_id' => $employee], ['d_id']);
        $this->front_return(
            1, 
            [
                'employee' => $employee
            ], 
            [
                'code' => 0,
                'data' => $list,
                'msg' => '查询完成'
            ], 
            1
        );
    }

/*************************************************************/
    /**
     * @name 房估估查询
     */
    public function find()
    {
        $request = $this->get_request();
        Slog::log($request);
        if (empty($request['address'])) {
            echo false;exit;
        }
        $address = $request['address'];
        $data = $this->yunfang_service->yunfangAction(['comName' => $address, 'cityCode' => '310100']);
        if (!empty($data['data']) && !empty($data['data'][0]) && !empty($data['data'][0]['data'])) {
            $tmp = [];
            foreach ($data['data'][0]['data'] as $key =>$value) {
                $tmp[] = [
                    'name' => $value['address'],
                    'id' => $value['communityId'],
                    'type' => 'communityId'
                ];
            }
            echo json_encode($tmp, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    /**
     * @name 楼房查询
     */
    public function building_find()
    {
        $request = $this->get_request();
        $communityId = $request['id'];//communityId
        $data = $this->yunfang_service->getBuildingUnitDoor([
            'cityCode' => '310100',
            'communityId' => $communityId
        ]);
        if (!empty($data['data']) && !empty($data['data'][0]) && !empty($data['data'][0]['list'])) {
            $buildingId = $data['data'][0]['list'][0]['guid'];
            //用默认的buildingId 再请求一次
            $data = $this->yunfang_service->getBuildingUnitDoor([
                'cityCode' => '310100',
                'communityId' => $communityId,
                'buildingId' => $buildingId
            ]);
            if (!empty($data['data']) && !empty($data['data'][0]) && !empty($data['data'][0]['list'])) {
                $tmp = [];
                foreach ($data['data'][0]['list'] as $key => $value) {
                    $tmp[] = [
                        'communityId' => $communityId,
                        'id' => $value['guid'],
                        'defid' => $buildingId,
                        'name' => $value['name'],
                        'type' => 'building'
                    ];
                }
                echo json_encode($tmp, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([], JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode([], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    /**
     * @name 房间号查询
     */
    public function unit_find()
    {
        $request = $this->get_request();
        $communityId = $request['communityId'];
        $buildingId = $request['buildingId'];
        $defId = $request['defid'];
        $data = $this->yunfang_service->getBuildingUnitDoor([
            'cityCode' => '310100',
            'communityId' => $communityId,
            'buildingId' => $defId,
            'unitId' => $buildingId
        ]);
        if (!empty($data['data']) && !empty($data['data'][0]) && !empty($data['data'][0]['list'])) {
            $tmp = [];
            foreach ($data['data'][0]['list'] as $key => $value) {
                $tmp[] = [
                    'communityId' => $communityId,
                    'id' => $value['guid'],
                    'buildingId' => $buildingId,
                    'name' => $value['name'],
                    'type' => 'house'
                ];
            }
            echo json_encode($tmp, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    /**
     * @name 获取价格
     */
    public function getprice($request)
    {
        $communityId = $request['communityId'];
        $buildingId = $request['buildingId'];
        $houseId = $request['houseId'];
        $buildingArea = $request['buildingArea'];

        $data = $this->yunfang_service->enquiryPrice([
            'cityCode' => '310100',
            'communityId' => $communityId,
            'houseType' => '住宅',
            'enquiryTime' => date('Y-m-d',time()),
            'buildingArea' => strval($buildingArea),
            'buildingId' => $buildingId,
            'houseId' => $houseId,
        ]);
        Slog::log('房估估价格报告:' . $data);
        if (!empty($data['data']) && !empty($data['data'][0])) {
            return $data['data'][0];
        } else {
            return [];
        }
    }

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
	 * @name 接收内容
	 */
	public function get_request()
	{
		if (!empty($_POST)) {
			$res = $_POST;
            Slog::log($_FILES);
            if (!empty($_FILES)) {
                $res['file'] = $_FILES;
            }
        } else {
            $body = @file_get_contents('php://input');
            $tmp = json_decode($body);
            if (!empty($tmp)) {
            	$res = ArrayHelper::object_to_array($tmp);
            } else {
            	$res = [];
            }
        }
        return $res;
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
/*####基础end############################################*/
}

?>
