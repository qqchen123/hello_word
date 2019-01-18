<?php
/**
 * 代扣API接口-DEMO
 * 本实例证书在CER文件夹，配制文件在Config/init.php
 * 实例仅供学习宝付<代扣API接口>接口使用，仅供参考。商户可根据本实例写自已的代码
 * @author：宝付（大圣）
 * @date  :2017-09-08
 *
 */

class Init {
	public $path = '';
	public $pathcer = '';

	function __construct()
	{
		$this->LoadFile();
	}

	public function LoadFile()
	{
		//====================配置商户的宝付接口授权参数============================
//		$this->path = dirname(__FILE__).'/../';
		$this->path = substr(dirname(__FILE__),0,strlen(dirname(__FILE__))-7);
//		print_r($this->path);die;
		$this->pathcer = $this->path."/CER/";    //证书路径

		require_once($this->path."/Function/BFRSA.php");
		require_once($this->path."/Function/SdkXML.php");
		require_once($this->path."/Function/Log.php");
		require_once($this->path."/Function/Tools.php");
		require_once($this->path."/Function/HttpClient.php");

		Log::LogWirte("=================安全服务验证=====================");
	}

	//====================配置商户的宝付接口授权参数==============

	/**
	 *
	 */
	public $version = '';
	public $member_id = '';
	public $terminal_id = '';
	public $data_type = '';
	public $txn_type = '';
	public $private_key_password = '';
	public $pfxfilename = '';
	public $cerfilename = '';
	public $request_url = '';

	public function LoadConfig()
	{
		$this->version = "4.0.0.0";//版本号
		$this->member_id = "100000276";    //商户号
		$this->terminal_id = "100000990";    //终端号
		$this->data_type = "xml";//加密报文的数据类型（xml/json）
		$this->txn_type = "0431";//交易类型
//		$this->private_key_password = "100000749_272769";    //商户私钥证书密

		$this->private_key_password = "123456";    //商户私钥证书密码
		$this->pfxfilename = $this->pathcer."bfkey_100000276@@100000990.pfx";  //注意证书路径是否存在
		$this->cerfilename = $this->pathcer."bfkey_100000276@@100000990.cer";//注意证书路径是否存在

		$this->request_url = "https://vgw.baofoo.com/cutpayment/api/backTransRequest";  //测试环境请求地址
		//$request_url = "https://public.baofoo.com/livesplatform/api/backTransRequest";  //正试环境请求地址

		if ( ! file_exists($this->pfxfilename))
		{
			die("私钥证书不存在！<br>");
		}
		if ( ! file_exists($this->cerfilename))
		{
			die("公钥证书不存在！<br>");
		}
	}

	public function GetParams()
	{
		return [
			'version' => $this->version,
			'member_id' => $this->member_id,
			'terminal_id' => $this->terminal_id,
			'data_type' => $this->data_type,
			'txn_type' => $this->txn_type,
			'private_key_password' => $this->private_key_password,
			'pfxfilename' => $this->pfxfilename,
			'cerfilename' => $this->cerfilename,
			'request_url' => $this->request_url,
		];
	}
}


