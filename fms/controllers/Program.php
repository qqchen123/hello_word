<?php
//require('/www/guzzle-master/vendor/autoload.php');
require('../vendor/autoload.php');

class Program extends CI_Controller {
	private $client = '';
	private $jar = '';

	public function __construct()
	{
		parent::__construct();
	}

	//登陆
	public function funguguLogin($return = FALSE)
	{
		$res = file_get_contents('./upload/fgg.txt');
		if ( ! $res || $return)
		{
			$this->client = new GuzzleHttp\Client();
			$this->jar = new \GuzzleHttp\Cookie\CookieJar();
			$url = "http://www.fungugu.com/DengLu/doLogin_noLogin";
			$map['pwd_login_username'] = '18757803728';
			$map['pwd_login_password'] = md5('147258aa');
			$map['remembermeVal'] = 0;
			$retdata = $this->loginPage($url, $map);
			if ( ! $return)
			{
				echo json_encode(['success' => 'success']);
			}

			return TRUE;
		} else
		{
			print_r($res);

			return TRUE;
		}

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
//		$this->jar = unserialize($_SESSION['jar_15618988191']); //file_get_contents($file_path)
		$this->jar = unserialize(file_get_contents('./upload/fgg.txt')); //file_get_contents($file_path)
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
		if (strlen(htmlspecialchars($response))<80)
		{
			$this->funguguLogin(TRUE);
			$this->get_houses();
		}
		$district = json_decode($response, TRUE)['data']['residentialList'];
		foreach ($district as $k => $v)
		{
			$district[$k]['xiaoquID'] = $this->getareaid($v['communityId']);
		}
		echo json_encode($district);
		die;
	}

	/**
	 * 获取小区ID
	 */
	public function getareaid($communityId)
	{
		$this->client = new GuzzleHttp\Client();
//		$this->jar = unserialize($_SESSION['jar_15618988191']);
		$this->jar = unserialize(file_get_contents('./upload/fgg.txt')); //file_get_contents($file_path)

		$map['cityName'] = urlencode('上海');
		$map['guid'] = $communityId;
		$url = "http://www.fungugu.com/addressSearch/getComIdByGuid?";
		$url .= $this->urlgetac($map);
		$retdata = $this->getPage($url);

		return json_decode($retdata, TRUE)['data']['comId'];
	}

	//获取询价信息
	public function getXunJiaXinXi($return = FALSE, $house_info = [])
	{
//		echo '222222';die;
		if ( ! $return)
		{
			$house_info = $this->input->post();
		}
		if ( ! empty(
			trim(
				$house_info['house_area']
			)
			) || $house_info['houseName'] != 'undefined' || $house_info['houseId'] != 'undefined')
		{
			$this->client = new GuzzleHttp\Client();
//			if (@$_SESSION['jar_15618988191'])
			if (@file_get_contents('./upload/fgg.txt'))
			{
//				$this->jar = unserialize($_SESSION['jar_15618988191']);
				$this->jar = unserialize(file_get_contents('./upload/fgg.txt')); //file_get_contents($file_path)

			}
//			else
//			{
//				$this->funguguLogin(TRUE);
//			}
			$url = "http://www.fungugu.com/JinRongGuZhi/getXunJiaXinXi";
			$map['city_name'] = '上海';
			$map['area'] = $house_info['house_area'];
			$map['filter'] = $house_info['houseName'];
			$map['position'] = $house_info['houseName'];
			$map['xiaoquID'] = $house_info['houseId'];
			$map['buildingNo'] = '';
			$map['house_type'] = isset($house_info['house_type']) ? $house_info['house_type'] : '';
			$map['toward'] = isset($house_info['toward']) ? $house_info['toward'] : "";
			$map['floor'] = isset($house_info['floor']) ? $house_info['floor'] : "";//builted_time/totalfloor
			$map['builted_time'] = isset($house_info['builted_time']) ? $house_info['builted_time'] : "";//builted_time/totalfloor
			$map['totalfloor'] = isset($house_info['totalfloor']) ? $house_info['totalfloor'] : "";//builted_time/totalfloor

			$retdata = $this->postPage($url, $map);
//			print_r($retdata);die;
			if (strlen(htmlspecialchars($retdata))<80)
			{
				$this->funguguLogin(TRUE);
				$this->getXunJiaXinXi($return=true ,$house_info);
			}
			$res_arr = json_decode($retdata, TRUE);
//			print_r($res_arr);die;
			if ($res_arr['success'] == FALSE)
			{
				echo json_encode($res_arr, JSON_UNESCAPED_UNICODE);
				die;
			}
			if ($house_info['gui_hua_yong_tu'] == '请选择')
			{
				$house_info['gui_hua_yong_tu'] = '';
			}
			if ($house_info['di_ya'] == '请选择')
			{
				$house_info['di_ya'] = '';
			}
			$house_info['openid'] = $_SESSION['wx']['openid'];
//			$house_info['openid'] = $_SESSION['fms_openid'];
			$house_info['diYaDanJia'] = $res_arr['data']['diYaDanJia'];
			$house_info['diYaZongJia'] = $res_arr['data']['diYaZongJia'];
			$house_info['fangDaiZheKou'] = $res_arr['data']['fangDaiZheKou'];
			$house_info['ZheKouZongJia'] = $res_arr['data']['fangDaiZheKou'] * $res_arr['data']['diYaZongJia'];
		}
		if ($house_info['houseName'] == 'undefined')
		{
			$house_info['houseName'] = '';
		}
		if ($house_info['houseId'] == 'undefined')
		{
			$house_info['houseId'] = '';
		}
		$house_info['openid'] = $_SESSION['wx']['openid'];
//		$house_info['openid'] = $_SESSION['fms_openid'];
		$house_info['c_date'] = date('Y-m-d H:i:s');
		$house_info['order_date'] = date('Ymd');
		$house_info['order_num'] = md5(rand(1111, 9999).time());
		$house_info['admin_id'] = $_SESSION['fms_id'];
		$this->db->insert('fms_minipro_house_price', $house_info);
		$res_arr['id'] = $this->db->insert_id();
		if ($return)
		{
			return $res_arr;
		} else
		{
			echo json_encode($res_arr, JSON_UNESCAPED_UNICODE);
		}
	}

	//获取评估记录
	public function get_fgg_log()
	{
		$rows = 4;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$first = $rows * ($page - 1);
		if (isset($_GET['status']))
		{
			if ($_GET['status'] == 0 || $_GET['status'] == 1)
			{
				$this->db->where('status', $_GET['status']);
			}
		}
		$logres['data'] = $this->db
			->limit($rows, $first)
//			->where('openid',$_SESSION['wx']['openid'])
			->where('admin_id', $_SESSION['fms_id'])
			->order_by('id', 'desc')
			->get('fms_minipro_house_price')
			->result_array();
		if ($this->input->get('status'))
		{
			$this->db->where('status', $_GET['status']);
		}
		$logres['count'] = $this->db->count_all('fms_minipro_house_price');
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
				$this->db->where('status', $_GET['status']);
			}
		}
		$logres['data'] = $this->db
			->select('fms_baodan.openid as bd_openid,fms_baodan.user_name,fms_minipro_house_price.*')
			->limit($rows, $first)
//			->where('fms_baodan.openid',$_SESSION['wx']['openid'])
//			->where('openid',$_SESSION['fms_openid'])  //$data['admin_id'] = $_SESSION['fms_id'];
			->where('fms_baodan.admin_id', $_SESSION['fms_id'])
			->join('fms_baodan', 'fms_minipro_house_price.id = fms_baodan.house_price_id')
			->order_by('id', 'desc')
			->get('fms_minipro_house_price')
			->result_array();
//		print_r($this->db->last_query());die;
		if ( ! $logres)
		{
			echo json_encode(['code' => 0, 'msg' => '暂无数据']);
			die;
		}
		if ($this->input->get('status'))
		{
			$this->db->where('status', $_GET['status']);
		}
		$logres['count'] = $this->db->count_all('fms_minipro_house_price');
		echo json_encode($logres, JSON_UNESCAPED_UNICODE);
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
//		$_SESSION['jar_15618988191'] = serialize($this->jar);
		file_put_contents('./upload/fgg.txt', serialize($this->jar));

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
	public function baodanT()
	{
		// var_dump($_FILES);
		$k1 = array_keys($_FILES)[0];
		$k2 = array_keys($_FILES[$k1]['name'])[0];

		if (is_array($_FILES[$k1]['name'][$k2]))
		{
			$k3 = array_keys($_FILES[$k1]['name'][$k2])[0];
			$name =& $_FILES[$k1]["name"][$k2][$k3];
			$type =& $_FILES[$k1]["type"][$k2][$k3];
			$tmp_name =& $_FILES[$k1]["tmp_name"][$k2][$k3];
			$error =& $_FILES[$k1]["error"][$k2][$k3];
			$size =& $_FILES[$k1]["size"][$k2][$k3];
		} else
		{
			$name =& $_FILES[$k1]["name"][$k2];
			$type =& $_FILES[$k1]["type"][$k2];
			$tmp_name =& $_FILES[$k1]["tmp_name"][$k2];
			$error =& $_FILES[$k1]["error"][$k2];
			$size =& $_FILES[$k1]["size"][$k2];
		}
		if ($error !== 0)
		{
			exit(json_encode(['ret' => FALSE, 'msg' => '上传错误']));
		}
		// if($type!=='image/jpeg') exit(json_encode(['ret'=>false,'msg'=>'图片格式错误']));
		// if($size>10*1024*1024) exit(json_encode(['ret'=>false,'msg'=>'图片太大']));

		$new_path = '/home/upload/mini_upload_tmp';
		if ( ! is_uploaded_file($tmp_name) || ! move_uploaded_file($tmp_name, $new_path.'/'.$name))
		{
			exit(json_encode(['ret' => FALSE, 'msg' => '上传错误']));
		}

		// if(is_array($_FILES[$k1]['name'][$k2])){
		// 	$_SESSION['wx']['mini_upload_file_tmp'][$k1][$k2][$k3] = $new_path.'/'.$name;
		// }else{
		// 	$_SESSION['wx']['mini_upload_file_tmp'][$k1][$k2] = $new_path.'/'.$name;
		// }

		// echo json_encode(['ret'=>true,'data'=> 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/upload/mini_upload_tmp/'.$name]);
		echo json_encode(['ret' => TRUE, 'data' => $name]);
	}

	// 报单数据
	public function baodan()
	{
		// var_dump($_POST);
		$this->load->library('form_validation');
		// $this->form_validation->set_rules('obj_id', '', 'integer');
		if ($this->form_validation->run())
		{
			$ret['ret'] = FALSE;
			$this->form_validation->set_error_delimiters('', '<br>');
			$ret['info'] = validation_errors();
		}

		$old_house_price_id = $this->input->post('old_house_price_id', TRUE);
		$data['user_name'] = $this->input->post('user_name', TRUE);
		$data['zhiye_type'] = $this->input->post('zhiye_type', TRUE);
		$data['get_money'] = $this->input->post('get_money', TRUE);
		$data['get_money_type'] = $this->input->post('get_money_type', TRUE);
		$data['pre_yu_e'] = $this->input->post('pre_yu_e', TRUE);
		$data['di_ya_type'] = $this->input->post('di_ya_type', TRUE);
		$data['yidi_yue'] = $this->input->post('yidi_yue', TRUE);
		$data['get_money_term'] = $this->input->post('get_money_term', TRUE);
		$data['house_type'] = $this->input->post('house_type', TRUE);
		$data['product_type'] = $this->input->post('product_type', TRUE);
		$data['jg_code'] = $this->input->post('jg_code', TRUE);
		$data['product_name'] = $this->input->post('product_name', TRUE);
		$data['openid'] = $_SESSION['wx']['openid'];//
//		$data['openid'] = $_SESSION['fms_openid'];//

		$data['admin_id'] = $_SESSION['fms_id'];
		$data['idnumberT'] = json_encode(
			[
				$this->input->post('idNumber0', TRUE),
				$this->input->post('idNumber1', TRUE),
			]
		);

		$house['houseName'] = $this->input->post('houseName', TRUE);
		$house['full_house_name'] = $this->input->post('full_house_name', TRUE);
		$house['houseId'] = $this->input->post('houseId', TRUE);
		$house['img_path'] = $this->input->post('houseT', TRUE);
		//$house['openid'] = $_SESSION['wx']['openid'];
		$house['house_area'] = $this->input->post('house_area', TRUE);
		$house['finish_date'] = $this->input->post('finish_date', TRUE);
		$house['gui_hua_yong_tu'] = $this->input->post('gui_hua_yong_tu', TRUE);
		$house['di_ya'] = $this->input->post('di_ya', TRUE);
		$house['yidi_yue'] = $this->input->post('yidi_yue', TRUE);

		$this->load->model("baodan_model", 'bd');
		$this->load->model("MiniproHousePrice_model", 'hp');

		$this->db->trans_start();
		//插入房屋评估
		$res = $this->getXunJiaXinXi(TRUE, $house);
		if ($res)
		{
			$data['house_price_id'] = $res['id'];
		} else
		{
			exit(json_encode(['ret' => FALSE, 'msg' => '报单失败']));
		}

		//插入报单
		$bd_id = $this->bd->add($data);

		//改变估价状态
		$this->hp->edit_baodan_status($data['house_price_id']);
		if ($old_house_price_id)
		{
			$this->hp->del($old_house_price_id);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() && $bd_id)
		{
			// $r = [
			// 	'diYaDanJia' => $res['diYaDanJia'],
			// 	'diYaZongJia' => $res['diYaZongJia'],
			// 	'fangDaiZheKou' => $res['fangDaiZheKou'],
			// 	'ZheKouZongJia' => $res['ZheKouZongJia'],
			// ];
			echo json_encode(['ret' => TRUE, 'msg' => '报单成功', 'data' => $res]);
		} else
		{
			echo json_encode(['ret' => FALSE, 'msg' => '报单失败']);
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
		$oid = $this->input->post('oid') ? $this->input->post('oid') : 5;
		$ores = $this->db->where('id', $oid)->get('fms_minipro_house_price')->row_array();
		echo json_encode(['code' => 1, 'data' => $ores]);
		die;
		$oid = $this->input->post('oid') ? $this->input->post('oid') : 5;
		$ores = $this->db->where('id', $oid)->get('fms_minipro_house_price')->row_array();
//		$this->getXunJiaXinXi2($ores['house_area'],$ores['houseName'],$ores['houseId']);
		echo json_encode(['code' => 1, 'data' => $ores]);
		die;
	}

//	public function getXunJiaXinXi2($house_area='',$houseName='', $houseId='',$house_type='',$toward='',$floor='',$builted_time='',$totalfloor='')
//	{
//		$this->client = new GuzzleHttp\Client();
//		$this->jar = unserialize($_SESSION['jar_15618988191']);
//		$url = "http://www.fungugu.com/JinRongGuZhi/getXunJiaXinXi";
//		$map['city_name'] = '上海';
//		$map['area'] = $house_area;
//		$map['filter'] = $houseName;
//		$map['position'] = $houseName;
//		$map['xiaoquID'] = $houseId;
//		$map['buildingNo'] = '';
//		$map['house_type'] = isset($house_type) ? $house_type : '';
//		$map['toward'] = isset($toward) ? $toward : "";
//		$map['floor'] = isset($floor) ? $floor : "";
//		$map['builted_time'] = isset($builted_time) ? $builted_time : "";//builted_time/totalfloor
//		$map['totalfloor'] = isset($totalfloor) ? $totalfloor : "";//builted_time/totalfloor
//
//		$retdata = $this->postPage($url, $map);
//		$res_arr = json_decode($retdata, TRUE);
//		print_r($res_arr);
//		die;
//	}


}