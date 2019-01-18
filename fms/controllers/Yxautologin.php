<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2018/10/30
 * Time: 9:59 AM
 */

$env = 'local';
//$env = 'dev';
if ('local' == $env) {
    require('../shared/libraries/vendor/autoload.php');
} else {
    require('/www/guzzle-master/vendor/autoload.php');
}

//银信
class Yxautologin extends Admin_Controller
{
    private $client = '';
    private $jar = '';
    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Yxmongo_model');
    }

    public function login()
    {
        $this->client = new GuzzleHttp\Client();
        $this->jar = new \GuzzleHttp\Cookie\CookieJar();
        $url = "https://www.yinxinsirencaihang.com/doLogin";
//            $map['pwd_login_username'] = $login_info['username'];
//            $map['pwd_login_password'] = md5($login_info['password']);
        $map['username'] = 'YX15901889950';
        $map['password'] = 'yxt146348';
        $map['vcode'] = '';
        $map['hasToke'] = true;
        $retdata = $this->loginPage($url,$map);
        print_r($retdata);die;
        echo($retdata);die;
    }

    function loginPage($url,$data,$method='post')
    {
        $response = $this->client->request($method,$url,['cookies' => $this->jar,'form_params' => $data])->getBody()->getContents();
        $_SESSION['jar_15618988191'] =  serialize($this->jar);
        return $response;
    }

    public function get_index()
    {
        $this->client = new GuzzleHttp\Client();
        $this->jar = unserialize($_SESSION['jar_15618988191']);
        $url = 'https://www.yinxinsirencaihang.com/account/queryAccount';
        $retdata = $this->getPage($url);
        print_r($retdata);die;
    }
    function getPage($url,$method='get')
    {
        $response = $this->client->request($method,$url,['cookies' => $this->jar])->getBody()->getContents();
        return $response;
    }
    public function account()
    {
        $this->client = new GuzzleHttp\Client();
        $this->jar = unserialize($_SESSION['jar_15618988191']);
        $url = 'https://www.yinxinsirencaihang.com/account/account';
        $retdata = $this->getPage($url);
        print_r($retdata);die;
    }







}