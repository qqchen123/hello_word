<?php
require(__DIR__.'/../../../guzzle-master/vendor/autoload.php');

class PreOrder extends CI_Controller {
	private $client = '';
	private $jar = '';
	private $login_num = 1;
	private $login_num_house_price = 1;
	private $upload_dir = '/home/upload/';
	// public $upload_dir = '/upload/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Little_house_price_model');
	}

	//登陆
	private function funguguLogin($return=false)
	{
		$this->client = new GuzzleHttp\Client();
		$this->jar = new \GuzzleHttp\Cookie\CookieJar();
		$url = "http://www.fungugu.com/DengLu/doLogin_noLogin";
		$map['pwd_login_username'] = '18757803728';
		$map['pwd_login_password'] = md5('147258aa');
		$map['remembermeVal'] = 0;
		$retdata = $this->loginPage($url, $map);
		if(!$return)
			echo json_encode(['ret' => 1,'data'=>'success']);
	}

	//房屋小区搜索
	public function get_houses()
	{
		$houseKey = $this->input->post('houseKey');
		if ( ! $houseKey)
		{
			$houseKey = '香阁丽';
		}
		$this->client = new GuzzleHttp\Client();
		$this->jar = unserialize(file_get_contents($this->upload_dir.'fgg.txt'));
		$map['q'] = urlencode($houseKey);
		$map['limit'] = 150;
		$map['timestamp'] = time();
		$map['userInput'] = urlencode($houseKey);
		$map['cityName'] = urlencode('上海');
		$map['accurateType'] = 1;
		$map['matchType'] = 1;
		$url = "http://www.fungugu.com/addressSearch/dataGet?";
		$url .= $this->urlgetac($map);
		$response = $this->client->request('get', $url, ['cookies' => $this->jar])->getBody()->getContents();
		if (strlen(htmlspecialchars($response))<80 )
		{
			if ( $this->login_num <= 3){
				$this->login_num++;
				$this->funguguLogin(TRUE);
				$this->get_houses();
			}else{
				echo json_encode(['ret'=>false,'msg'=>'估价失败！']);
			}
		}else{
			$district = json_decode($response, TRUE)['data']['residentialList'];
			foreach ($district as $k => $v)
			{
				$district[$k]['xiaoquID'] = $this->getareaid($v['communityId']);
			}
			echo json_encode(['ret'=>true,'data'=>$district]);
			die;
		}
	}

	/**
	 * 获取小区ID
	 */
	private function getareaid($communityId)
	{
		$this->client = new GuzzleHttp\Client();
//		$this->jar = unserialize($_SESSION['jar_15618988191']);
		$this->jar = unserialize(file_get_contents($this->upload_dir.'fgg.txt'));
		$map['cityName'] = urlencode('上海');
		$map['guid'] = $communityId;
		$url = "http://www.fungugu.com/addressSearch/getComIdByGuid?";
		$url .= $this->urlgetac($map);
		$retdata = $this->getPage($url);

		return json_decode($retdata, TRUE)['data']['comId'];
	}

// 	//获取询价信息
// 	public function getXunJiaXinXi($return=false,$house_info=[])
// 	{
// 		if(!$return) $house_info = $this->input->post();
// 		if ( ! empty(trim($house_info['house_area'])) && $house_info['houseName']!='undefined' && $house_info['houseId']!='undefined')
// 		{
// 			$this->client = new GuzzleHttp\Client();
// //			if(@$_SESSION['jar_15618988191']){
// 			if(@unserialize(file_get_contents($this->upload_dir.'fgg.txt'))){
// //				$this->jar = unserialize($_SESSION['jar_15618988191']);
// 				$this->jar = unserialize(file_get_contents($this->upload_dir.'fgg.txt'));
// 			}
// 			else{
// 				$this->funguguLogin(true);
// 			}
// 			$url = "http://www.fungugu.com/JinRongGuZhi/getXunJiaXinXi";
// 			$map['city_name'] = '上海';
// 			$map['area'] = $house_info['house_area'];
// 			$map['filter'] = $house_info['houseName'];
// 			$map['position'] = $house_info['houseName'];
// 			$map['xiaoquID'] = $house_info['houseId'];
// 			$map['buildingNo'] = '';
// 			$map['house_type'] = isset($house_info['house_type']) ? $house_info['house_type'] : '';
// 			$map['toward'] = isset($house_info['toward']) ? $house_info['toward'] : "";
// 			$map['floor'] = isset($house_info['floor']) ? $house_info['floor'] : "";//builted_time/totalfloor
// 			$map['builted_time'] = isset($house_info['builted_time']) ? $house_info['builted_time'] : "";//builted_time/totalfloor
// 			$map['totalfloor'] = isset($house_info['totalfloor']) ? $house_info['totalfloor'] : "";//builted_time/totalfloor

// 			$retdata = $this->postPage($url, $map);
// //			var_dump(htmlspecialchars($retdata));die;
// //			if (strlen(htmlspecialchars($retdata))<80)
// //			{
// //				$this->funguguLogin(TRUE);
// //				$this->getXunJiaXinXi($return=true ,$house_info);
// //			}
// 			// file_put_contents('/home/upload/getXunJiaXinXi.txt',$retdata);
// var_dump($retdata);
// 			$res_arr = json_decode($retdata, TRUE);
// var_dump($res_arr);
// 			if($res_arr['success']==false){
// 				echo json_encode($res_arr, JSON_UNESCAPED_UNICODE);
// 				die;
// 			}
// 			if (!$house_info['gui_hua_yong_tu'] =='请选择'){
// 				$house_info['gui_hua_yong_tu'] = '';
// 			}
// 			if ($house_info['finish_date'] =='请选择'){
// 				$house_info['finish_date'] = '';
// 			}
// //			$house_info['finish_date'] == '请选择' ? $house_info['finish_date'] = '' : $house_info['finish_date'];
// 			$house_info['openid'] = $_SESSION['wx']['openid'];
// //			$house_info['openid'] = $_SESSION['fms_openid'];
// 			$house_info['diYaDanJia'] = $res_arr['data']['diYaDanJia'];
// 			$house_info['diYaZongJia'] = $res_arr['data']['diYaZongJia'];
// 			$house_info['fangDaiZheKou'] = $res_arr['data']['fangDaiZheKou'];
// 			$house_info['ZheKouZongJia'] = $res_arr['data']['fangDaiZheKou']*$res_arr['data']['diYaZongJia'];
// 		}
// 		if ($house_info['houseName']=='undefined'){
// 			$house_info['houseName'] = '';
// 		}
// 		if ($house_info['full_house_name']=='undefined'){
// 			$house_info['full_house_name'] = '';
// 		}
// 		if ($house_info['houseId']=='undefined'){
// 			$house_info['houseId'] = '';
// 		}
// 		$house_info['openid'] = $_SESSION['wx']['openid'];
// //		$house_info['openid'] = $_SESSION['fms_openid'];
// 		$house_info['c_date'] = date('Y-m-d H:i:s');
// 		$house_info['order_date'] = date('Ymd');
// 		$house_info['order_num'] = md5(rand(1111, 9999).time());
// 		$house_info['admin_id'] = $_SESSION['fms_id'];
// 		$this->db->insert('fms_minipro_house_price', $house_info);
// 		$res_arr['id'] = $this->db->insert_id();
// 		if($return){
// 			return $res_arr;
// 		}else{
// 			echo json_encode(['ret'=>1,'data'=>$res_arr], JSON_UNESCAPED_UNICODE);
// 		}
// 	}

	//获取评估记录
	public function get_fgg_log()
	{
		$rows = 4;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$first = $rows * ($page - 1);
		$this->where_fgg_order();
		$logres['data'] = $this->db
			->limit($rows, $first)
			->where('admin_id', $_SESSION['fms_id'])
			->order_by('id', 'desc')
			->get('fms_minipro_house_price')
			->result_array();
		// file_put_contents(
		// 	$this->upload_dir.'get_fgg_log.txt',
		// 	$_GET['status'].'+'.json_encode($this->db->last_query()).'----'.date('Y-m-d H:i:s').'\n',
		// 	FILE_APPEND
		// );
		$this->where_fgg_order();
		$logres['count'] = $this->db
			->from('fms_minipro_house_price')
			->where('admin_id', $_SESSION['fms_id'])
			->count_all_results();
		// file_put_contents(
		// 	$this->upload_dir.'get_fgg_log.txt',
		// 	$_GET['status'].'+'.json_encode($this->db->last_query()).'----'.date('Y-m-d H:i:s').'\n',
		// 	FILE_APPEND
		// );
		echo json_encode($logres, JSON_UNESCAPED_UNICODE);
	}
	//获取评估记录2--+姓名--status=1
	 public function get_fgg_log2()
	 {
	 	$rows = 4;
	 	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	 	$first = $rows * ($page - 1);
	 	if (isset($_GET['status']))
	 	{
	 		if ($_GET['status'] == 1)
	 		{
	 			$this->db->where('status', 0);
	 		}
	 		if ($_GET['status'] == 2)
	 		{
	 			$this->db->where('status', 1);
	 		}
	 	}
	 	$logres['data'] = $this->db
	 		->select('fms_baodan.openid as bd_openid,fms_baodan.user_name,fms_baodan.product_type,fms_minipro_house_price.*')
	 		->limit($rows, $first)
	 		->where('fms_baodan.admin_id', $_SESSION['fms_id'])
	 		->join('fms_baodan', 'fms_minipro_house_price.id = fms_baodan.house_price_id','left')
	 		->order_by('id', 'desc')
	 		->get('fms_minipro_house_price')
	 		->result_array();
//	 	file_put_contents(
//	 		$this->upload_dir.'get_fgg_log2.txt',
//	 		$_GET['status'].'+'.json_encode($this->db->last_query()).'----'.date('Y-m-d H:i:s').'\n\r',
//	 		FILE_APPEND
//	 	);
	 	if ( ! $logres)
	 	{
	 		echo json_encode(['ret' => 0, 'msg' => '暂无数据']);
	 		die;
	 	}
	 	if (isset($_GET['status']))
	 	{
	 		if ($_GET['status'] == 1)
	 		{
	 			$this->db->where('status', 0);
	 		}
	 		if ($_GET['status'] == 2)
	 		{
	 			$this->db->where('status', 1);
	 		}
	 	}
	 	$logres['count'] = $this->db
	 		->select('fms_baodan.openid as bd_openid,fms_baodan.user_name,fms_minipro_house_price.*')
	 		->from('fms_minipro_house_price')
	 		->where('fms_baodan.admin_id', $_SESSION['fms_id'])
	 		->join('fms_baodan', 'fms_minipro_house_price.id = fms_baodan.house_price_id','left')
	 		->order_by('id', 'desc')
	 		->count_all_results();
//	 	file_put_contents(
//	 		$this->upload_dir.'get_fgg_log2.txt',
//	 		$_GET['status'].'+'.json_encode($this->db->last_query()).'----'.date('Y-m-d H:i:s').'\n\r',
//	 		FILE_APPEND
//	 	);
	 	echo json_encode($logres, JSON_UNESCAPED_UNICODE);
	 }

	/**
	 * 订单详情
	 */
	public function get_order_detail_by_id()
	{
		$order_detail_res = $this->Little_house_price_model->get_order_detail_by_oid();
		echo json_encode(['ret'=>true,'data'=>$order_detail_res]);die;
	}
	/**
	 * @param $map
	 * @return bool|string
	 */
	private function urlgetac($map)
	{
		$urlstr = '';
		foreach ($map as $key => $value)
		{
			$urlstr .= $key.'='.$value.'&';
		}

		return substr($urlstr, 0, -1);
	}

	/**
	 * @param        $url
	 * @param string $method
	 * @return mixed
	 */
	private function getPage($url, $method = 'get')
	{
		$response = $this->client->request($method, $url, ['cookies' => $this->jar])->getBody()->getContents();

		return $response;
	}

	/**
	 * 登陆
	 * @param        $url
	 * @param        $data
	 * @param string $method
	 * @return mixed
	 */
	private function loginPage($url, $data, $method = 'post')
	{
		$response = $this->client->request($method, $url, ['cookies' => $this->jar, 'form_params' => $data])
			->getBody()
			->getContents();
		file_put_contents($this->upload_dir.'fgg.txt',serialize($this->jar));

		return $response;
	}

	/**
	 * @param        $url
	 * @param        $data
	 * @param string $method
	 * @return mixed
	 */
	private function postPage($url, $data, $method = 'post')
	{
		$response = $this->client->request($method, $url, ['cookies' => $this->jar, 'form_params' => $data])->getBody(
		)->getContents();

		return $response;
	}

	// 接收图片 by 奚晓俊
	private function upload_img(){
		// var_dump($_FILES);

		//允许接受的图片类型
		$file_type = ['jpg', 'jpeg', 'gif', 'bmp', 'png'];
		//允许图片大小
		$file_size = 10*1024*1024;
		//上传临时文件路径
		$new_path = $this->upload_dir.'mini_upload_tmp';
		//$new_path = '/upload/mini_upload_tmp';

		$k1 = array_keys($_FILES)[0];
		$k2 = array_keys($_FILES[$k1]['name'])[0];

		if(is_array($_FILES[$k1]['name'][$k2])){
			$k3 = array_keys($_FILES[$k1]['name'][$k2])[0];
			$name =& $_FILES[$k1]["name"][$k2][$k3];
			$type =& $_FILES[$k1]["type"][$k2][$k3];
			$tmp_name =& $_FILES[$k1]["tmp_name"][$k2][$k3];
			$error =& $_FILES[$k1]["error"][$k2][$k3];
			$size =& $_FILES[$k1]["size"][$k2][$k3];
		}else{
			$name =& $_FILES[$k1]["name"][$k2];
			$type =& $_FILES[$k1]["type"][$k2];
			$tmp_name =& $_FILES[$k1]["tmp_name"][$k2];
			$error =& $_FILES[$k1]["error"][$k2];
			$size =& $_FILES[$k1]["size"][$k2];
		}
		if($error!==0) exit(json_encode(['ret'=>false,'msg'=>'上传错误']));

		if(strpos($type,'image/')!==0) exit(json_encode(['ret'=>false,'msg'=>'图片格式错误']));
		if (! in_array(substr($type,6), $file_type)) exit(json_encode(['ret'=>false,'msg'=>'图片格式错误']));

		if($size>$file_size) exit(json_encode(['ret'=>false,'msg'=>'图片太大']));

		if ( ! is_uploaded_file($tmp_name) || ! move_uploaded_file($tmp_name, $new_path.'/'.$name)){
			exit(json_encode(['ret' => FALSE, 'msg' => '上传错误']));
		}
		return $name;
	}

	public function baodanT(){
		$this->load->model('Upload_manage_model','um');
		$name = $this->upload_img();
		$this->um->add_mini_upload_tmp($name,'报单');
		echo json_encode(['ret'=>true,'data'=> $name]);
		$this->del_img();
	}

	public function pingguT(){
		$this->load->model('Upload_manage_model','um');
		$name = $this->upload_img();
		$this->um->add_mini_upload_tmp($name,'评估');
		echo json_encode(['ret'=>true,'data'=> $name]);
		$this->del_img();
	}

	private function del_img(){
		ignore_user_abort();
		set_time_limit(0);
		header("Content-Length: ".ob_get_length());
		header("Connection: Close");
		ob_flush();
		flush();
		if(mt_rand(1,100)!=100) return;
		$path = $this->upload_dir.'mini_upload_tmp/';
		$time = time()-24*60*60;//1天前的文件
		// $time = time()-1*60;
		$time = date('Y-m-d H:i:s',$time);
		$this->load->model('Upload_manage_model','um');
		$del_img = $this->um->get_upload(['status'=>'临时文件','d_time <'=>$time]);
		if($del_img && $this->um->del_upload_by_time(['status'=>'临时文件','d_time <'=>$time])
		){
			//var_dump($del_img);
			foreach ($del_img as $key => $val) {
				if(file_exists($path.$val['name'])) unlink($path.$val['name']);
			}
		}
	}

	// 报单数据
	public function baodan(){
		// var_dump($_POST);
		// exit(json_encode(['ret'=>false,'msg'=>'报单失败']));
		$this->load->library('form_validation');
        // $this->form_validation->set_rules('obj_id', '', 'integer');
		if ($this->form_validation->run()) {
			$ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

		$old_house_price_id = $this->input->post('old_house_price_id',true);
		$data['user_name'] = $this->input->post('user_name',true);
		$data['zhiye_type'] = $this->input->post('zhiye_type',true);
		$data['get_money'] = $this->input->post('get_money',true);
		$data['get_money_type'] = $this->input->post('get_money_type',true);
		$data['pre_yu_e'] = $this->input->post('pre_yu_e',true);
		$data['di_ya_type'] = $this->input->post('di_ya_type',true);
		$data['yidi_yue'] = $this->input->post('yidi_yue',true);
		$data['get_money_term'] = $this->input->post('get_money_term',true);
		$data['house_type'] = $this->input->post('house_type',true);
		$data['product_type'] = $this->input->post('product_type',true);
		$data['jg_code'] = $this->input->post('jg_code',true);
		$data['product_name'] = $this->input->post('product_name',true);
		$data['openid'] = $_SESSION['wx']['openid'];

		$data['admin_id'] = $_SESSION['fms_id'];
		$idnumber_arr = [
			$this->input->post('idNumber0',true),
			$this->input->post('idNumber1',true),
		];
		$data['idnumberT'] = json_encode($idnumber_arr);

		$house['houseName'] = $this->input->post('houseName', TRUE);
		$house['full_house_name'] = $this->input->post('full_house_name', TRUE);
		$house['houseId'] = $this->input->post('houseId', TRUE);
		$house['toward'] = $this->input->post('toward', TRUE);
		$house['floor'] = $this->input->post('floor', TRUE);
		$house['totalfloor'] = $this->input->post('totalfloor', TRUE);
		$house['img_path'] = $this->input->post('houseT', TRUE);
		$house_img_arr = json_decode($house['img_path'],true);
		//$house['openid'] = $_SESSION['wx']['openid'];
		$house['house_area'] = $this->input->post('house_area', TRUE);
		$house['finish_date'] = $this->input->post('finish_date', TRUE);
		$house['gui_hua_yong_tu'] = $this->input->post('gui_hua_yong_tu', TRUE);

        $this->load->model("baodan_model",'bd');
        $this->load->model("MiniproHousePrice_model",'hp');
        $this->load->model('Upload_manage_model','um');
        $this->load->model('BaodanStatus_model','bs');

        $this->db->trans_start();
        	//插入房屋评估
            $res = $this->get_house_price_to_xxj($house);

            if($res){
            	$data['house_price_id'] = $res['id'];
            }else{
            	exit(json_encode(['ret'=>false,'msg'=>'报单失败']));
            }

			//插入报单
			$bd_id = $this->bd->add($data);

            //改变估价状态
        	$this->hp->edit_baodan_status($data['house_price_id']);
        	if($old_house_price_id && $old_house_price = $this->hp->find_by_id($old_house_price_id)) {

        		//删除旧评估数据
        		$this->hp->del($old_house_price_id);

        		//删除旧评估数据独有图
        		$old_house_price['img_path'] = json_decode($old_house_price['img_path'],true);
        		$old_house_img = array_diff($old_house_price['img_path'],$house_img_arr);
				if($old_house_img) $this->um->del_upload($old_house_img);
        	}
        	//改变临时文件状态
        	if($house_img_arr)
        		$this->um->use_upload($house_img_arr,$res['id']);
        	if($idnumber_arr)
        		$this->um->use_upload($idnumber_arr,$bd_id);

        	//插入报单数据
        	$this->bs->addStatus($bd_id);

		$this->db->trans_complete();

        if ($this->db->trans_status() && $bd_id){
        	// $r = [
        	// 	'diYaDanJia' => $res['diYaDanJia'],
        	// 	'diYaZongJia' => $res['diYaZongJia'],
        	// 	'fangDaiZheKou' => $res['fangDaiZheKou'],
        	// 	'ZheKouZongJia' => $res['ZheKouZongJia'],
        	// ];

        	//删除旧文件
        	if(@$old_house_img) {
	        	$path = $this->upload_dir.'mini_upload_tmp';
		        foreach ($old_house_img as $key => $val) {
		        	if(file_exists($path.'/'.$val)) unlink($path.'/'.$val);
		        }
		    }
            echo json_encode(['ret'=>true,'msg'=>'报单成功','data'=>$res]);
        }else{
            echo json_encode(['ret'=>false,'msg'=>'报单失败']);
        }
		//echo $this->db->last_query();
	}

	// //创建文件目录
	// public function mkdir_we($dir)
	// {
	// 	if (!file_exists($dir)){
	// 		mkdir ($dir,0777,true);
	// 		return 1;
	// 	} else {
	// 		return 1;
	// 	}
	// }

	/**
	 * 根据订单ID获取一条房屋估价信息
	 */
	public function get_one_fgginfo_by_oid()
	{
		$oid = $this->input->post('oid');
		if(empty($oid)){
			echo json_encode(['ret'=>false,'msg'=>'oid不能为空！']);die;
		}
		$ores = $this->db->where('id',$oid)->get('fms_minipro_house_price')->row_array();
		echo json_encode(['ret'=>true,'data'=>$ores]);die;
	}

	/**
	 * 房估估----房屋估价的公共方法
	 * @param string $house_area   房屋面积  ----必填
	 * @param string $houseName    小区名称  ----必填
	 * @param string $houseId      小区ID   ----必填
	 * @param string $house_type   房屋类型 （住宅，别墅）    ----选填
	 * @param string $toward	   房屋朝向 （东、西、南、北） ----选填
	 * @param string $floor        所在楼层 （数字）         ----选填
	 * @param string $builted_time 建造时间 （数字）         ----选填
	 * @param string $totalfloor   总楼层   （数字）         ----选填
	 * @return mixed
	 */
	private function getXunJiaXinXi2($house_area='',$houseName='', $houseId='',$house_type='',$toward='',$floor='',$totalfloor='',$builted_time='')
	{
		$this->client = new GuzzleHttp\Client();
		$this->jar = unserialize(file_get_contents($this->upload_dir.'fgg.txt'));
		$url = "http://www.fungugu.com/JinRongGuZhi/getXunJiaXinXi";
		$map['city_name'] = '上海';
		$map['area'] = $house_area;
		$map['filter'] = $houseName;
		$map['position'] = $houseName;
		$map['xiaoquID'] = $houseId;
		$map['buildingNo'] = '';
		$map['house_type'] = isset($house_type) ? $house_type : '';
		$map['toward'] = isset($toward) ? $toward : "";
		$map['floor'] = isset($floor) ? $floor : "";
		$map['builted_time'] = isset($builted_time) ? $builted_time : "";//builted_time/totalfloor
		$map['totalfloor'] = isset($totalfloor) ? $totalfloor : "";//builted_time/totalfloor

		$retdata = $this->postPage($url, $map);
		return $retdata;
	}

	/**
	 * 小程序-------房产评估
	 */
	public function get_house_price($house_info='')
	{
		$this->form_validation->set_rules('house_area', 'house_area', 'trim|required');
		$this->form_validation->set_rules('houseName', 'houseName', 'trim|required');
		$this->form_validation->set_rules('houseId', 'houseId', 'trim|required');
		$this->form_validation->set_message('required', '必须填写');
		if ($this->form_validation->run()==false) {
			$ret['success'] = false;
			$this->form_validation->set_error_delimiters('', '<br>');
			$ret['msg'] = validation_errors();
			echo json_encode($ret);die;
		}
		$house_info = $this->input->post();
		if(!isset($house_info['toward']) || $house_info['toward'] =='请选择'){
			$house_info['toward'] = '';
		}
		if(!isset($house_info['gui_hua_yong_tu']) ||$house_info['gui_hua_yong_tu'] =='请选择'||$house_info['gui_hua_yong_tu'] =='null'){//house_type
			$house_info['gui_hua_yong_tu'] = '';
		}
		if(!isset($house_info['floor'])){
			$house_info['floor'] = '';
		}
		if(!isset($house_info['totalfloor'])){
			$house_info['totalfloor'] = '';
		}
		if(!isset($house_info['finish_date']) || $house_info['finish_date'] =='null' || $house_info['finish_date'] =='请选择'){
			$house_info['finish_date'] = '';
		}
//		print_r($house_info);die;
		$house_price_res = $this->getXunJiaXinXi2(
			$house_info['house_area'],
			$house_info['houseName'],
			$house_info['houseId'],
			$house_info['gui_hua_yong_tu'],
			$house_info['toward'],
			$house_info['floor'],
			$house_info['totalfloor'],
			$house_info['finish_date']
			);
		$arr_getxjxx = json_decode($house_price_res,true);
		if ($arr_getxjxx['success']==false){
			echo json_encode(['ret'=>false,'msg'=>'暂无小区评估价格，请检查输入条件是否准确！']);die;
		}
		if (strlen(htmlspecialchars($house_price_res))<80)
		{
			if ( $this->login_num_house_price <= 3){
				$this->login_num_house_price++;
				$this->funguguLogin(TRUE);
				$this->get_house_price($house_info);
			}else{
				echo json_encode(['ret'=>false,'msg'=>'估价失败！']);
			}
		}
		$arr_housePrice = json_decode($house_price_res,TRUE);
		file_put_contents($this->upload_dir.'get_house_price.txt',json_encode($arr_housePrice),FILE_APPEND);
//		print_r($house_info);die;
		$this->insert_fgg_price($house_info, $arr_housePrice);
		$arr_housePrice['id'] = $this->db->insert_id();
		if($imgs = json_decode($house_info['img_path'],true)){
	        $this->load->model('Upload_manage_model','um');
			$this->um->use_upload($imgs,$arr_housePrice['id']);
		}
		echo json_encode(['ret'=>true,'data'=>$arr_housePrice], JSON_UNESCAPED_UNICODE);
		die;
	}

	/**
	 * 将fgg评估信息插入数据库
	 * @param $house_info
	 * @param $arr_housePrice
	 */
	private function insert_fgg_price($house_info, $arr_housePrice)
	{
		if ($house_info['gui_hua_yong_tu']== '请选择'){
			$house_info['gui_hua_yong_tu'] = '';
		}
		if ($house_info['finish_date']== '请选择' || $house_info['finish_date']=='null'){
			$house_info['finish_date'] = '';
		}

		if (!isset($house_info['toward']) || $house_info['toward']== '请选择'){
			$house_info['toward'] = '';
		}

		$house_info['openid'] = $_SESSION['wx']['openid'];
		if($arr_housePrice){
			$house_info['diYaDanJia'] = @$arr_housePrice['data']['diYaDanJia'];
			$house_info['diYaZongJia'] = @$arr_housePrice['data']['diYaZongJia'];
			$house_info['fangDaiZheKou'] = @$arr_housePrice['data']['fangDaiZheKou'];
			$house_info['ZheKouZongJia'] = @$arr_housePrice['data']['fangDaiZheKou'] * @$arr_housePrice['data']['diYaZongJia'];

//		$house_info['full_house_name'] = $arr_housePrice['data']['fangDaiZheKou'] * $arr_housePrice['data']['diYaZongJia'];
		}
		$house_info['c_date'] = date('Y-m-d H:i:s');
		$house_info['order_date'] = date('Ymd');
		$house_info['order_num'] = md5(rand(1111, 9999).time());
		$house_info['admin_id'] = $_SESSION['fms_id'];
		$this->db->insert('fms_minipro_house_price', $house_info);
	}
	/**
	 * 获取评估信息给xixiaojun
	 * write by chenenjie
	 */
	private function get_house_price_to_xxj($select)
	{
		$house_info = $this->input->post();
		if ( ! empty(trim($house_info['house_area'])) && $house_info['houseName']!='undefined' && $house_info['houseId']!='undefined'){
			$house_price_res = $this->getXunJiaXinXi2($select['house_area'],$select['houseName'],$select['houseId']);
			if (strlen(htmlspecialchars($house_price_res))<80)
			{
				if ( $this->login_num_house_price <= 3){
					$this->login_num_house_price++;
					$this->funguguLogin(TRUE);
					$this->get_house_price_to_xxj($select);
				}else{
					echo json_encode(['ret'=>false,'msg'=>'估价失败！']);die;
				}
			}
			$arr_housePrice = json_decode($house_price_res,TRUE);
		}else{
			$arr_housePrice = [];
		}
		$this->insert_fgg_price($select, $arr_housePrice);
		$arr_housePrice['id'] = $this->db->insert_id();
		return $arr_housePrice;
	}

	private function where_fgg_order()
	{
		if (isset($_GET['status']))
		{
			if ($_GET['status'] == 1)
			{
				$this->db->where('status', 0);
			}
			if ($_GET['status'] == 2)
			{
				$this->db->where('status', 1);
			}
		}
	}

}