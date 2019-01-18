<?

class Mx extends CI_Controller
{
	private $apiKey = "dd0171ba1b5f4b03a49375f4194b2b7e";
	private $userid = "xiaoyougaotou_test";
	private $token = "d74ea104c9274d40aaf873eb972cfa87";
	
    function mx_recv()
    {
    	header("HTTP/1.1 201 Created");
    	var_dump($_REQUEST);
    }
	
	//运营商
	function mx_yys()
	{
	 	$url = "https://api.51datakey.com/h5/importV3/index.html#/carrier?apiKey=".$this->apiKey."&userId=".$this->userid."&backUrl=".urlencode("http://47.94.133.229/fms/index.php/Mx/mx_recv");
	 	redirect($url);
	}
	
	//运营商结果查询
	function mx_yys_query()
	{
		$taskid="6ff35db0-69fe-11e8-90f8-00163e0f4b67";
		$mobile = "13761790334";
		$url = "https://api.51datakey.com/carrier/v3/mobiles/".$mobile."/mxreport?taskid=".$taskid;
		$ret = $this->get_data($url);
		$ret = json_decode($ret,true);
		//echo "111<pre>";var_dump($ret);echo "</pre>";
		echo $this->array2table($ret);  
	}
	
	//公积金
	function mx_gjj()
	{
		$url = "https://api.51datakey.com/h5/importV3/index.html#/fundlist?apiKey=".$this->apiKey."&userId=".$this->userid."&backUrl=".urlencode("http://47.94.133.229/fms/index.php/Mx/mx_recv");
		redirect($url);
	}
	
	//公积金结果查询	
	function mx_gjj_query()
	{
		$taskid="42262880-6a13-11e8-b01a-00163e002b50";
		$url = "https://api.51datakey.com/fund/v2/funds/".$taskid;
		$ret = $this->get_data($url);
		echo $ret;
	}
	
	//社保
	function mx_sb()
	{
		$url = "https://api.51datakey.com/h5/importV3/index.html#/securityList?apiKey=".$this->apiKey."&userId=".$this->userid."&backUrl=".urlencode("http://47.94.133.229/fms/index.php/Mx/mx_recv");
		redirect($url);
	}
	
	//网银
	function mx_wy()
	{
		$url = "https://api.51datakey.com/h5/importV3/index.html#/banklist?apiKey=".$this->apiKey."&userId=".$this->userid."&backUrl=".urlencode("http://47.94.133.229/fms/index.php/Mx/mx_recv");
		redirect($url);
	}
	
	//网银结果查询
	function mx_wy_query()
	{
		$taskid="d8673f70-6a16-11e8-b35b-00163e0cf9f8";
		$url = "https://api.51datakey.com/bank/v3/cards?task_id=".$taskid;
		$ret = $this->get_data($url);
		$ret = json_decode($ret,true);
		//echo "<pre>";var_dump($ret);echo "</pre>";
		
		echo $this->array2table($ret);
		
	}
	
	function array2table2($array)
	{
		
		if(is_array($array))
		{
		
			foreach($array as $k => $v) 
			{
							
				if(is_array($v))
				{
					$this->array2table2($v);
				}
				else
				{
					echo "<tr>";
					echo "<td>".$k."</td><td>".$v."</td>";
					echo "</tr>";
				}
			}
		}
		
	}
	
	function array2table($array)
	{
		
		if(is_array($array))
		{
			echo "<table border=1>";
			foreach($array as $k => $v) 
			{
							
				if(is_array($v))
				{
					$this->array2table2($v);
				}
				else
				{
					echo "<tr>";
					echo "<td>".$k."</td><td>".$v."</td>";
					echo "</tr>";
				}
				
			}
			echo "</table>";
		}
		
	}
	
	 function get_data($url){

     	$headers[] = 'Authorization:token '.$this->token;

      	//初始化
    	$curl = curl_init();
    	//设置抓取的url
    	curl_setopt($curl, CURLOPT_URL, $url);
    	//设置头文件的信息作为数据流输出
    	curl_setopt($curl, CURLOPT_HEADER, 0);
    	//设置获取的信息以文件流的形式返回，而不是直接输出。
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($curl, CURLOPT_ENCODING, "gzip");
    	//执行命令
    	$data = curl_exec($curl);
    	//关闭URL请求
    	curl_close($curl);
    	
    	//显示获得的数据
    	return $data;
  }
}

?>