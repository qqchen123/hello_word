<?

/**
 * @desc 客户 身份证、手机卡、银行卡录入 报审等页面操作
 */
class House extends Admin_Controller
{
	/**
     * @name 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->basicloadhelper();
        $this->load->service('business/YunFang_service', 'yunfang_service');
    }

    /**
     * @name 房估估页面
     */
    public function index()
    {
    	$this->showpage('fms/house/index', []);
    }

    /**
     * @name 房估估查询列表
     */
    public function list()
    {
        $this->showpage('fms/house/list', []);
    }

    /**
     * @name 房估估查询
     */
    public function find()
    {
    	$address = $this->input->post('address', true);
    	$data = $this->yunfang_service->yunfangAction(['comName' => $address, 'cityCode' => '310100']);
        
    	if (!empty($data['data']) && !empty($data['data'][0]) && !empty($data['data'][0]['data'])) {
    		$tmp = [];
    		foreach ($data['data'][0]['data'] as $key =>$value) {
    			$tmp[] = array_merge(
    				$value, 
    				[
    					'op' => '<span class="dn op" id="op'. $key .'" data-id="'. $value['communityId'] .'" data-name="'. $value['address'] .'">'. $value['communityId'] .'</span>'
	    			]
	    		);
    		}
    		echo json_encode($tmp, JSON_UNESCAPED_UNICODE);
    	} else {
            Slog::log('find接口异常请检查');
            Slog::log($data);
    		echo json_encode([], JSON_UNESCAPED_UNICODE);
    	}
    	exit;
    }

    /**
     * @name 楼房查询
     */
    public function building_find()
    {
    	$communityId = $this->input->post('communityId', true);
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
	    			$tmp[] = array_merge($value, ['defid' => $buildingId]);
	    		}
	    		echo json_encode($tmp, JSON_UNESCAPED_UNICODE);
	    	} else {
	    		echo json_encode([], JSON_UNESCAPED_UNICODE);
	    	}
    	} else {
            Slog::log('building接口异常请检查');
            Slog::log($data);
    		echo json_encode([], JSON_UNESCAPED_UNICODE);
    	}
    	exit;
    }

    /**
     * @name 房间号查询
     */
    public function unit_find()
    {
    	$communityId = $this->input->post('communityId', true);
    	$buildingId = $this->input->post('buildingId', true);
    	$defId = $this->input->post('defid', true);
    	$data = $this->yunfang_service->getBuildingUnitDoor([
	    	'cityCode' => '310100',
	    	'communityId' => $communityId,
	    	'buildingId' => $defId,
	    	'unitId' => $buildingId
    	]);
    	if (!empty($data['data']) && !empty($data['data'][0]) && !empty($data['data'][0]['list'])) {
    		echo json_encode($data['data'][0]['list'], JSON_UNESCAPED_UNICODE);
    	} else {
            Slog::log('unit接口异常请检查');
            Slog::log($data);
    		echo json_encode([], JSON_UNESCAPED_UNICODE);
    	}
    	exit;
    }

    /**
     * @name 获取价格
     */
    public function getprice()
    {
    	$communityId = $this->input->post('communityId', true);
    	$buildingId = $this->input->post('buildingId', true);
    	$houseId = $this->input->post('houseId', true);
    	$buildingArea = $this->input->post('buildingArea', true);

    	$data = $this->yunfang_service->enquiryPrice([
    		'cityCode' => '310100',
	        'communityId' => $communityId,
	        'houseType' => '住宅',
	        'enquiryTime' => date('Y-m-d',time()),
	        'buildingArea' => strval($buildingArea),
	        'buildingId' => $buildingId,
	        'houseId' => $houseId,
    	]);
    	if (!empty($data['data']) && !empty($data['data'][0])) {
    		echo json_encode($data['data'][0], JSON_UNESCAPED_UNICODE);
    	} else {
            Slog::log('获取价格接口异常请检查');
            Slog::log($data);
    		echo json_encode([], JSON_UNESCAPED_UNICODE);
    	}
    	exit;
    }

    /**
     * @name 获取小区信息
     */
    public function getvillageinfo()
    {
    	$communityId = $this->input->post('communityId', true);
    	$data = $this->yunfang_service->queryBaseInfo([
    		'cityCode' => '310100',
	        'communityId' => $communityId,
    	]);
    	if (!empty($data['data']) && !empty($data['data'][0])) {
    		echo json_encode($data['data'][0], JSON_UNESCAPED_UNICODE);
    	} else {
            Slog::log('获取小区信息接口异常请检查');
            Slog::log($data);
    		echo json_encode([], JSON_UNESCAPED_UNICODE);
    	}
    	exit;
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
/*####基础end############################################*/
}

?>
