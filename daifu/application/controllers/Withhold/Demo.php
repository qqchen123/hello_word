<?php
require('./vendor/autoload.php');

/**
 *
 */
class Demo extends My_Controller {

	function __construct()
	{
		parent::__construct();
//		if ($_POST){
//			var_dump($_POST);die;
//		}
	}

//页面function

	/**
	 * @url /Withhold/Demo/index
	 */
	public function index()
	{
		$this->newview('test/index');
	}

	/**
	 * @url /Withhold/Demo/pay
	 */
	public function pay()
	{
		$this->newview('test/pay');
	}

	public function query()
	{
		$this->newview('test/query');
	}

//请求function

	/**
	 * @url /Withhold/Demo/action
	 */
	public function action()
	{
		$path = dirname(__FILE__);
		require_once $path.'/Config/init.php';
		$obj = new Init();
		$obj->LoadConfig();
		$params = $obj->GetParams();
		//变量重装
		$version = $params['version'];
		$data_type = $params['data_type'];
		$terminal_id = $params['terminal_id'];
		$member_id = $params['member_id'];
		$txn_type = $params['txn_type'];
		$request_url = $params['request_url'];

		//==================接收用户数据==========================
		//ob_start ();
		$pay_code = isset($_POST["pay_code"]) ? $_POST["pay_code"] : "";//银行卡编码
		$acc_no = isset($_POST["acc_no"]) ? trim($_POST["acc_no"]) : "";//银行卡卡号
		$id_card = isset($_POST["id_card"]) ? trim($_POST["id_card"]) : "";//身份证号码
		$id_holder = isset($_POST["id_holder"]) ? trim($_POST["id_holder"]) : "";//姓名
		$mobile = isset($_POST["mobile"]) ? trim($_POST["mobile"]) : "";//银行预留手机号
		$next_txn_sub_type = isset($_POST["next_txn_sub_type"]) ? trim($_POST["next_txn_sub_type"]) : "";//下一下进行的交易子类
		$trans_id = isset($_POST["trans_id"]) ? trim($_POST["trans_id"]) : "TI".$this->get_transid().$this->rand4(
			);    //商户订单号
		$txn_sub_type = isset($_POST["txn_sub_type"]) ? trim($_POST["txn_sub_type"]) : "";

		//==============固定参数================================

		$biz_type = "0000";//接入类型
		$id_card_type = "01";//证件类型固定01（身份证）
		$acc_pwd = "";//银行卡密码（传空）
		$valid_date = "";//卡有效期 （传空）
		$valid_no = "";//卡安全码（传空）
		$additional_info = "附加字段";//附加字段
		$req_reserved = "保留";//保留
		$pay_cm = "2";//1:不进行信息严格验证,2:对四要素


		//====================系统动态生成值=======================================
		$trans_serial_no = "TSN".$this->get_transid().$this->rand4();    //商户流水号
		$trade_date = $this->return_time();    //订单日期

		$data_content_parms = [
			'txn_sub_type' => $txn_sub_type,
			'biz_type' => $biz_type,
			'terminal_id' => $terminal_id,
			'member_id' => $member_id,
			'trans_serial_no' => $trans_serial_no,
			'trade_date' => $trade_date,
			'additional_info' => $additional_info,
			'req_reserved' => $req_reserved,
		];

		if ($txn_sub_type == "06")
		{ //06:交易状态查询类交易

			$orig_trans_id = isset($_POST["orig_trans_id"]) ? trim($_POST["orig_trans_id"]) : "";//订单号
			$data_content_parms["orig_trans_id"] = $orig_trans_id;

		} elseif ($txn_sub_type == "13")
		{
			$txn_amt = isset($_POST["txn_amt"]) ? trim($_POST["txn_amt"]) : "";//交易金额额
			$txn_amt *= 100;//金额以分为单位（把元转成分）
			$data_content_parms["pay_code"] = $pay_code;
			$data_content_parms["acc_no"] = $acc_no;
			$data_content_parms["id_card_type"] = $id_card_type;
			$data_content_parms["id_card"] = $id_card;
			$data_content_parms["id_holder"] = $id_holder;
			$data_content_parms["mobile"] = $mobile;
			$data_content_parms["valid_date"] = $valid_date;
			$data_content_parms["valid_no"] = $valid_no;
			$data_content_parms["trans_id"] = $trans_id;
			$data_content_parms["txn_amt"] = $txn_amt;
		} else
		{
			echo 'txn_sub_type参数缺失';
		}

		//==================转换数据类型=============================================

		if ($data_type == "json")
		{
			$Encrypted_string = str_replace("\\/", "/", json_encode($data_content_parms));//转JSON
		} else
		{
			$toxml = new SdkXML();    //实例化XML转换类
			$Encrypted_string = $toxml->toXml($data_content_parms);//转XML
		}
		Log::LogWirte("序列化结果：".$Encrypted_string);
//		print_r($params);die;
		$BFRsa = new BFRSA($params["pfxfilename"], $params["cerfilename"], $params["private_key_password"]); //实例化加密类。
//		print_r($Encrypted_string);die;
		$Encrypted = $BFRsa->encryptedByPrivateKey($Encrypted_string);    //先BASE64进行编码再RSA加密
//		print_r($Encrypted);die;
		$PostArry = [
			"version" => $version,
			"terminal_id" => $terminal_id,
			"txn_type" => $txn_type,
			"txn_sub_type" => $txn_sub_type,
			"member_id" => $member_id,
			"data_type" => $data_type,
			"data_content" => $Encrypted,
		];
//		print_r($PostArry);
//		echo '--';
//		print_r($request_url);die;
		$return = HttpClient::Post($PostArry, $request_url);  //发送请求到宝付服务器，并输出返回结果。
//		print_r($return);
		Log::LogWirte("请求返回参数：".$return);

		if (empty($return))
		{
			throw new Exception("返回为空，确认是否网络原因！");
		}
		$return_decode = $BFRsa->decryptByPublicKey($return);//解密返回的报文
//		echo 'miwen:';
//		print_r($return_decode);die;
//		Log::LogWirte("return：".$return);
//		Log::LogWirte("解密结果：".$return_decode);
		$endata_content = [];

		if ( ! empty($return_decode))
		{//解析XML、JSON
			if ($data_type == "xml")
			{
				$endata_content = SdkXML::XTA($return_decode);
			} else
			{
				$endata_content = json_decode($return_decode, TRUE);
			}

			if (is_array($endata_content) && (count($endata_content) > 0))
			{
				if (array_key_exists("resp_code", $endata_content))
				{
					if ($endata_content["resp_code"] == "0000")
					{
						$return_decode = "订单状态码：".$endata_content["resp_code"].", 商户订单号：".$endata_content["trans_id"].", 返回消息：".$endata_content["resp_msg"];
					} else
					{
						//错误或失败其他状态
						$return_decode = "订单状态码：".$endata_content["resp_code"].", 商户订单号：".$endata_content["trans_id"].", 返回消息：".$endata_content["resp_msg"];
					}
					echo $return_decode;//输出
					die();
				} else
				{
					throw new Exception("[resp_code]返回码不存在!");
				}
			}
		} else
		{
			echo "请求出错，请检查网络";
		}

		exit;
	}

	/**
	 * @url /Withhold/Demo/queryorder
	 */
	public function queryorder()
	{
		$path = dirname(__FILE__);
		require_once $path.'/Config/init.php';
		$obj = new Init();
		$obj->LoadConfig();
		$params = $obj->GetParams();
		//变量重装
		$version = $params['version'];
		$data_type = $params['data_type'];
		$terminal_id = $params['terminal_id'];
		$member_id = $params['member_id'];
		$txn_type = $params['txn_type'];
		$request_url = $params['request_url'];

		//==================接收用户数据==========================
		//ob_start ();
		$trans_serial_no = isset($_POST["trans_serial_no"]) ? $_POST["trans_serial_no"] : "";//商户流水号
		$orig_trans_id = isset($_POST["orig_trans_id"]) ? trim($_POST["orig_trans_id"]) : "";//原始商户订单号
		$orig_trade_date = isset($_POST["orig_trade_date"]) ? trim($_POST["orig_trade_date"]) : "";//订单日期

		//==============固定参数================================

		$txn_sub_type = "31";//交易子类
		$biz_type = "0000";//接入类型

		$data_content_parms = [
			'txn_sub_type' => $txn_sub_type,
			'biz_type' => $biz_type,
			'terminal_id' => $terminal_id,
			'member_id' => $member_id,
			'trans_serial_no' => $trans_serial_no,
			'orig_trans_id' => $orig_trans_id,
			'orig_trade_date' => $orig_trade_date,
		];

		//==================转换数据类型=============================================

		if ($data_type == "json")
		{
			$Encrypted_string = str_replace("\\/", "/", json_encode($data_content_parms));//转JSON
		} else
		{
			$toxml = new SdkXML();    //实例化XML转换类
			$Encrypted_string = $toxml->toXml($data_content_parms);//转XML
		}
		Log::LogWirte("序列化结果：".$Encrypted_string);
		$BFRsa = new BFRSA($params["pfxfilename"], $params["cerfilename"], $params["private_key_password"]); //实例化加密类。
		$Encrypted = $BFRsa->encryptedByPrivateKey($Encrypted_string);    //先BASE64进行编码再RSA加密
		$PostArry = [
			"version" => $version,
			"terminal_id" => $terminal_id,
			"txn_type" => $txn_type,
			"txn_sub_type" => $txn_sub_type,
			"member_id" => $member_id,
			"data_type" => $data_type,
			"data_content" => $Encrypted,
		];
		$return = HttpClient::Post($PostArry, $request_url);  //发送请求到宝付服务器，并输出返回结果。
		Log::LogWirte("请求返回参数：".$return);

		if (empty($return))
		{
			throw new Exception("返回为空，确认是否网络原因！");
		}
		$return_decode = $BFRsa->decryptByPublicKey($return);//解密返回的报文
		Log::LogWirte("解密结果：".$return_decode);
		$endata_content = [];
		if ( ! empty($return_decode))
		{//解析XML、JSON
			if ($data_type == "xml")
			{
				$endata_content = SdkXML::XTA($return_decode);
			} else
			{
				$endata_content = json_decode($return_decode, TRUE);
			}

			if (is_array($endata_content) && (count($endata_content) > 0))
			{
				if (array_key_exists("resp_code", $endata_content))
				{
					//提前处理部分数据
					foreach ($endata_content as $key => $value)
					{
						if (is_array($value) && empty($value))
						{
							$endata_content[$key] = '';
						}
					}

					if ($endata_content["resp_code"] == "0000")
					{
						$return_decode = "订单状态码：".$endata_content["resp_code"].", 商户订单号：".$endata_content["trans_no"].", 返回消息：".$endata_content["resp_msg"].', 交易结果：'.$endata_content['order_stat'];
					} else
					{
						Log::LogWirte("其它信息：".json_encode($endata_content, JSON_UNESCAPED_UNICODE));
						//错误或失败其他状态
						$return_decode = "订单状态码：".$endata_content["resp_code"].", 商户订单号：".$endata_content["trans_no"].", 返回消息：".$endata_content["resp_msg"].', 交易结果：'.$endata_content['order_stat'];
					}
					// echo $return_decode;//输出
					echo json_encode(['data' => $return_decode], JSON_UNESCAPED_UNICODE);
					die();
				} else
				{
					throw new Exception("[resp_code]返回码不存在!");
				}
			}
		} else
		{
			// echo "请求出错，请检查网络";
			echo json_encode(['msg' => "请求出错，请检查网络"], JSON_UNESCAPED_UNICODE);
		}
		exit;
	}

//工具函数
	public function get_transid()
	{//生成时间戳
		return strtotime(date('Y-m-d H:i:s', time()));
	}

	public function rand4()
	{//生成四位随机数
		return rand(1000, 9999);
	}

	public function return_time()
	{//生成时间
		return date('YmdHis', time());
	}

}

?>
