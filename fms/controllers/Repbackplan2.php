<?php
$env = 'local';
//$env = 'dev';
if ('local' == $env) {
	require('../vendor/autoload.php');
} else {
	require('/www/guzzle-master/vendor/autoload.php');
}
use QL\QueryList;
/**
 * @desc 银信爬虫
 */
class Repbackplan2 extends CI_Controller
{
	private $yx_baseurl = 'https://www.yinxinsirencaihang.com/';
	private $client = '';
	private $jar = '';
	private $finish_url = 'product/proudctFinished';
	//获取用户信息
	public function get_account()
	{
		$user = $this->db->select('account,pwd')
			->get('fms_yx_account')->result_array();
		foreach ($user as $k=>$v){
			$this->yx_login($v['account'],$v['pwd']);
		}
	}

	/**
	 * 模拟登陆
	 * @param $account
	 * @param $pwd
	 */
	public function yx_login($account='YX15637822759', $pwd = 'yxt822759'){//YX18001625170 yxt625170
		$this->client = new GuzzleHttp\Client();
		$this->jar = new \GuzzleHttp\Cookie\CookieJar();
		$url = $this->yx_baseurl."doLogin";
		$map['username'] = $account;
		$map['password'] = $pwd;
		$map['vcode'] = '';
		$map['hasToken'] = true;
		$retdata = $this->loginPage($url,$map);
		$login_info = json_decode($retdata,true);
		if ($login_info['msg'] == '成功' && $login_info['success']==1){
			$this->get_finish($map['username']);
		}else{
			$this->get_user_account($account);
		}
	}
	/**
	 * 登陆
	 * @param $url
	 * @param $data
	 * @param string $method
	 * @return mixed
	 */
	function loginPage($url,$data,$method='post')
	{
		$response = $this->client->request($method,$url,['cookies' => $this->jar,'form_params' => $data])->getBody()->getContents();
		$_SESSION['jar_15618988191'] =  serialize($this->jar);
		return $response;
	}

	/**
	 * 爬取数据处理
	 * @param $username
	 */
	public function get_finish($yx_account){
		$this->client = new GuzzleHttp\Client();
		$this->jar = unserialize($_SESSION['jar_15618988191']);
		$url = $this->yx_baseurl.$this->finish_url;
		$res =  $this->getPage($url,'post');
		$pos_start = strpos($res, 'id="beenFinishedForm"');
		$sub_str = substr($res,$pos_start);
		$pos_end = strpos($sub_str, 'id="applyBorrowForm"');
		$sub_str_end = substr($sub_str,0,$pos_end);
		$lend_title_url = QueryList::Query($sub_str_end,array(
			'link' => array('.myInvestment_box_RepaymentIn_one_a1','href')
		))->data;
		if (empty($lend_title_url)){
			$this->get_user_account($yx_account);//如果没有数据
		}
		$title = QueryList::Query($sub_str_end,array(
			'title' => array('.myInvestment_box_RepaymentIn_one_a1','text')
		))->data;
		foreach ($lend_title_url as $k=>$v)
		{
			$res = $this->get_back_plan($v['link'],$title[$k]['title'],$yx_account);
			if ($res){
				continue;
			}
		}

	}

	public function get_back_plan($url,$title,$yx_account)
	{
		$this->client = new GuzzleHttp\Client();
		$this->jar = unserialize($_SESSION['jar_15618988191']);
		$res =  $this->getPage($url,'get');
		$pos_start = strpos($res, 'reimbursementTabArea"');
		$sub_str = substr($res,$pos_start);
		$pos_end = strpos($sub_str, 'bidRecordTabArea');
		$sub_str_end = substr($sub_str,0,$pos_end);
		$data1 = QueryList::Query($sub_str_end,array(
			'title' => array('td','text')
		))->data;
		$arr_chunk = array_chunk($data1,7);
		if (empty($arr_chunk)){ //判断这个标题下是否有数据--如果有就继续执行，没有返回false跳出本次循环
//			$this->get_user_account($yx_account);
			return false;
		}
		foreach ($arr_chunk as $k=>$v){
			$arr_chunks[$k]['qishu'] = $v[0]['title'];
			$arr_chunks[$k]['back_date'] = $v[1]['title'];
			$arr_chunks[$k]['b_interest'] = $v[2]['title'];
			$arr_chunks[$k]['principal'] = $v[3]['title'];
			$arr_chunks[$k]['l_interest'] = $v[4]['title'];
			$arr_chunks[$k]['f_interest'] = $v[5]['title'];
			$arr_chunks[$k]['status'] = $v[6]['title'];
			$arr_chunks[$k]['yx_account'] = $yx_account;
			$arr_chunks[$k]['title'] = $title;
			$arr_chunks[$k]['rep_time'] = date('Y-m-d');
		}
		if (empty($arr_chunks[0]['qishu'])){
			echo $yx_account;die;
		}
		$this->db->insert_batch('yx_back_plan',$arr_chunks);
	}
	/**
	 * 去掉空格、回车、换行
	 * @param $str
	 * @return mixed
	 */
	function trimall($str){
		$qian=array(" ","　","\t","\n","\r");
		return str_replace($qian, '', $str);
	}

	/**
	 * 去掉td标签
	 * @param $str
	 * @return mixed
	 */
	function trimtd($str){
		$qian=array("<td>");
		return str_replace($qian, '。', $str);
	}

	/**
	 * 去掉td标签
	 * @param $str
	 * @return mixed
	 */
	function trimtd2($str){
		$qian=array("</td>");
		return str_replace($qian, '', $str);
	}
	/**
	 * @param $url
	 * @param string $method
	 * @return mixed
	 */
	function getPage($url,$method='get')
	{
		$response = $this->client->request($method,$url,['cookies' => $this->jar])->getBody()->getContents();
		return $response;
	}

	/**
	 * 获取用户账号
	 * @param $account
	 */
	public function get_user_account($account)
	{
		$ures = $this->db->select('id')->where('account',$account)->get('fms_yx_account')->row_array();
		$usres = $this->db->select('id,account,pwd')->where('id >',$ures['id'])->get('fms_yx_account')->result_array();
		if ($usres){
			foreach ($usres as $k=>$v){
				$this->yx_login($v['account'],$v['pwd']);
			}
		}else{
			print_r(['status'=>0,'数据爬取完成！']);die;
		}
	}

	/**
	 * 根据条件----手动删除测试数据
	 */
	public function del()
	{
		$res = $this->db->where('id <','784')->delete('yx_back_plan');
		echo $this->db->last_query();
		print_r($res);die;
	}

	public function test123()
	{
		echo 'test123';
	}




}