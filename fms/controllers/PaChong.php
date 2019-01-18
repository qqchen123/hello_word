<?php
// $env = 'local';
$env = 'dev';
if ('dev' == $env) {
    require('d:/wamp64/www/guzzle-master/vendor/autoload.php');
} else {
    require('/www/svn-code/guzzle-master/vendor/autoload.php');
}
class PaChong extends Admin_Controller{
    private $client = '';
    private $jar = '';
    public function __construct(){
        parent::__construct();
         //session_start();
    }
    
    public function fungugu(){
        
        $this->funguguLogin();
        //$this->getarea();
       // $this->getareaid();
       //$this->getld();
        //$this->gethome();
        $this->getXunJiaXinXi();
       
    }
    public function funguguLogin(){
        $this->client = new GuzzleHttp\Client();
        $this->jar = new \GuzzleHttp\Cookie\CookieJar();
       // $this->jar = new \GuzzleHttp\Cookie\CookieJarInterface();
        $url = "http://www.fungugu.com/DengLu/doLogin_noLogin";
        $map['pwd_login_username'] = '上海钰桂';
        $map['pwd_login_password'] = md5('888888');
        $map['remembermeVal'] = 0;
        //$map = json_encode($map);//var_dump($map);exit;
        $retdata = $this->loginPage($url,$map);
        
        //$_SESSION['client_15618988191'] = $this->client;
        var_dump($retdata);
        
    }
    /**
     * 获取小区信息
     */
    public function getarea(){
       // $this->client = unserialize($_SESSION['client_15618988191']);
        $this->client = new GuzzleHttp\Client();
        $this->jar = unserialize($_SESSION['jar_15618988191']);
        $map['q'] =  urlencode('香阁丽');
        $map['limit'] = 150;
        $map['timestamp'] = time();
        $map['userInput'] = urlencode('香阁丽');
        $map['cityName'] = urlencode('上海');
        $map['accurateType'] = 1;
        $map['matchType'] = 1;
        $url = "http://www.fungugu.com/addressSearch/dataGet?";
        $url .= $this->urlgetac($map);
       
        $retdata = $this->getPage($url);
        var_dump($retdata);
       
    }
    /**
     * 获取小区ID
     */
    public function getareaid(){
        $this->client = new GuzzleHttp\Client();
        $this->jar = unserialize($_SESSION['jar_15618988191']);
        var_dump($this->jar);
        $url = "http://www.fungugu.com/addressSearch/getComIdByGuid?cityName=%E4%B8%8A%E6%B5%B7&guid=c2e1a9a5-292c-11e5-ac2c-288023a0e898";
        $retdata = $this->getPage($url);
        var_dump($retdata);
    }
    /**
     * 获取楼栋
     * @param unknown $map
     * @return unknown
     */
    public function getld(){
        $this->client = new GuzzleHttp\Client();
        $this->jar = unserialize($_SESSION['jar_15618988191']);
        $url = "http://www.fungugu.com/addressSearch/getBuildingList?cityName=%E4%B8%8A%E6%B5%B7&id=19576&type=xiaoqu&source=&guid=c2f0ea60-292c-11e5-ac2c-288023a0e898";
       // $map['city_name'] = '上海';
       // $map['community_name'] = '香阁丽苑';
       // $map['district_name'] = '杨浦区';
        $retdata = $this->getPage($url);
        var_dump($retdata);
    }
    /**
     * 获取房间信息
     */
    public function gethome(){
        $this->client = new GuzzleHttp\Client();
        $this->jar = unserialize($_SESSION['jar_15618988191']);
        $url = "http://www.fungugu.com/addressSearch/getBuildingList?cityName=%E4%B8%8A%E6%B5%B7&id=272719&type=danyuan&source=&guid=c2f0ea60-292c-11e5-ac2c-288023a0e898";
        $retdata = $this->getPage($url);
        var_dump($retdata);
    }
    public function getXunJiaXinXi(){
        $this->client = new GuzzleHttp\Client();
        $this->jar = unserialize($_SESSION['jar_15618988191']);
        $url = "http://www.fungugu.com/JinRongGuZhi/getXunJiaXinXi";
        $map['city_name'] = '上海';
        $map['pianqu'] = '黄兴公园';
        $map['house_type'] = '住宅';
        $map['area'] = '90';
        $map['filter'] = '香阁丽苑';
        $map['residentialName'] = '香阁丽苑';
        //$map['builted_time'] = '';
        //$map['floor'] = '';
        //$map['totalfloor'] = '';
       // $map['toward'] = '';
        //$map['special_factors'] = '';
        $map['house_number'] =  '2288073';
        $map['position'] =  '香阁丽苑';
        //$map['others'] = '';
        $map['xingzhengqu'] =  '杨浦区';
        $map['xiaoquID'] =  '19576';
        $map['xiaoqujunjia'] =  '77856';
        $map['address'] =  '双阳北路395弄55号601';
        $map['buildingNo'] = '';
        $map['unitNo'] =  '双阳北路395弄55号';
        $map['houseNo'] =  '601';
        $map['unitID'] =  '272718';
        $map['houseID'] =  '2288073';
        $map['point_x'] =  '121.530716';
        $map['point_y'] =  '31.304493';
        $map['retrievalMethod'] =  '普通检索';
        $map['originalInputItem'] =  '香阁';
        $map['locatedOriginalInputItem'] =  '双阳北路395弄55号601';
        //$map['buildingSource'] =  '';
        $retdata = $this->postPage($url,$map);
        var_dump($retdata);
    }
    public function urlgetac($map){
        $urlstr = '';
         foreach ($map as $key => $value){
             $urlstr .= $key.'='.$value.'&';
         }
         return substr($urlstr,0,-1);
     }
     function getPage($url,$method='get')
     {
         
         $response = $this->client->request($method,$url,['cookies' => $this->jar])->getBody()->getContents();
        // $_SESSION['jar_15618988191'] =  serialize($this->jar);
         return $response;
     } 
     function postPage($url,$data,$method='post')
     {
         
         
         // $response = $client->request($method,$url)->getBody()->getContents();
         //$response = $client->post();
         //$response = $client->request($method,$url, ['cookies' => $jar,'form_params' => $data])->getHeaders();
         $response = $this->client->request($method,$url,['cookies' => $this->jar,'form_params' => $data])->getBody()->getContents();
         //
         return $response;
     } 
    function loginPage($url,$data,$method='post')
    {
       
        
       // $response = $client->request($method,$url)->getBody()->getContents();
       //$response = $client->post();
        //$response = $client->request($method,$url, ['cookies' => $jar,'form_params' => $data])->getHeaders();
        $response = $this->client->request($method,$url,['cookies' => $this->jar,'form_params' => $data])->getBody()->getContents();
       // $_SESSION['client_15618988191'] =  serialize($this->client);
        $_SESSION['jar_15618988191'] =  serialize($this->jar);
        return $response;
    } 
    
}