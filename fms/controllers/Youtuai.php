<?
require('/www/php_sdk-master/include.php');
use TencentYoutuyun\Youtu;
use TencentYoutuyun\Conf;

class Youtuai extends Admin_Controller
{

	private $appid='10134247';
	private $secretId='AKIDthLqDSAd1FIAC64Rs8DB3o6FDa4ZjSho';
	private $secretKey='JTwJVWQ9h4FGAtWzGOMApIajiZFqKo8H';
	private $userid='45737921';
	
    function __construct()
    {
    	Conf::setAppInfo($this->appid, $this->secretId, $this->secretKey, $this->userid,conf::API_YOUTU_END_POINT );
    }
    
    function index()
    {
    	echo 111;
    }
	
	function idcardocr()
	{
		$picurl = $this->input->get_post('picurl', true);
		//$uploadRet = YouTu::idcardocrurl($picurl, 1);
		$uploadRet = YouTu::idcardocrurl('https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1531758855982&di=da7f862f7750d07e8d9e647087cc6dfb&imgtype=0&src=http%3A%2F%2Fa4.att.hudong.com%2F77%2F02%2F01000000000000119090268590177.jpg', 0);
		var_dump($uploadRet);
	}
}

?>