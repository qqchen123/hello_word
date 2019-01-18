<?php
// $env = 'local';
$env = 'dev';
if ('local' == $env) {
    require('d:/wamp64/www/vendor/autoload.php');
} else {
    require('/www/guzzle-master/vendor/autoload.php');
}

/**
 * Class Reptile
 * write by 陈恩杰
 */
class Reptile extends Admin_Controller{

    private $client = '';
    private $jar = '';

    /**
     * 输出界面
     */
    public function show_rep()
    {
        $this->showpage('fms/reptile');
    }
    //登陆
    public function funguguLogin(){
//        $login_info = $this->input->post();

//        if (1){
            $this->client = new GuzzleHttp\Client();
            $this->jar = new \GuzzleHttp\Cookie\CookieJar();
            $url = "http://www.fungugu.com/DengLu/doLogin_noLogin";
//            $map['pwd_login_username'] = $login_info['username'];
//            $map['pwd_login_password'] = md5($login_info['password']);
            $map['pwd_login_username'] = '18757803728';
            $map['pwd_login_password'] = md5('147258aa');
            $map['remembermeVal'] = 0;
            $retdata = $this->loginPage($url,$map);
            print_r($retdata);die;
            echo($retdata);die;
//        }
    }
    /**
     * 获取小区信息
     */
    public function getarea(){
        $xq_res  = $this->input->post();
        if ( !empty($xq_res['xiaoqu']) ){
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
            $map['q'] =  urlencode($xq_res['xiaoqu']);
            $map['limit'] = 150;
            $map['timestamp'] = time();
            $map['userInput'] = urlencode($xq_res['xiaoqu']);
            $map['cityName'] = urlencode('上海');
            $map['accurateType'] = 1;
            $map['matchType'] = 1;
            $url = "http://www.fungugu.com/addressSearch/dataGet?";
            $url .= $this->urlgetac($map);

            $retdata = $this->getPage($url);
            echo substr(substr($retdata,58),0,-47);die;
        }else{
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
//            $map['q'] =  urlencode('香阁丽');
//            $map['limit'] = 5;
//            $map['timestamp'] = time();
            $map['userInput'] = urlencode('香阁丽');
            $map['cityName'] = urlencode('上海');
            $map['accurateType'] = 1;
            $map['matchType'] = 1;
            $url = "http://www.fungugu.com/addressSearch/dataGet?";
            $url .= $this->urlgetac($map);

            $retdata = $this->getPage($url);
            echo substr(substr($retdata,58),0,-47);die;
        }
    }

    /**
     * @param $map
     * @return bool|string
     */
    public function urlgetac($map){
        $urlstr = '';
        foreach ($map as $key => $value){
            $urlstr .= $key.'='.$value.'&';
        }
        return substr($urlstr,0,-1);
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
     * 获取小区ID
     */
    public function getareaid(){
        $community_number = $this->input->post();
        if ($community_number){
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
            $map['cityName'] = urlencode('上海');
            $map['guid'] = $community_number['communityId'];
            $url = "http://www.fungugu.com/addressSearch/getComIdByGuid?";
            $url .= $this->urlgetac($map);
            $retdata = $this->getPage($url);
            echo $retdata;die;
        }else{
            $ret['code'] = 0;
            $ret['msg'] = '不能为空！';
            echo json_encode($ret,JSON_UNESCAPED_UNICODE);die;
        }
    }
    /**
     * 获取楼栋
     * @param unknown $map
     * @return unknown
     */
    public function getld(){
        $comid = $this->input->post();
//        print_r($comid);die;
        if ($comid['comId']){
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
            $map['cityName'] = urlencode('上海');
            $map['id'] = $comid['comId'];
            $map['type'] = 'xiaoqu';
            $map['source'] = '';
            $map['guid'] = $comid['communityId'];
            $url = "http://www.fungugu.com/addressSearch/getBuildingList?";
            $url .= $this->urlgetac($map);
            $retdata = $this->getPage($url);
            $ret_arr = json_decode($retdata,true)['data']['list'];
            echo json_encode($ret_arr, true);die;
        }
    }
    /**
     * 获取房间信息
     */
    public function gethome(){
        $home_res = $this->input->post();
//        print_r($home_res);die;
        if ($home_res['comId']){
//            echo 11;die;
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
            $map['cityName'] = urlencode('上海');
            $map['id'] = $home_res['comId'];
            $map['type'] = 'danyuan';
            $map['source'] = '';
            $map['guid'] = $home_res['communityId'];
            $url = "http://www.fungugu.com/addressSearch/getBuildingList?";
            $url .= $this->urlgetac($map);
            $retdata = $this->getPage($url);
            $ret_arr = json_decode($retdata,true)['data']['list'];
            echo json_encode($ret_arr, true);die;
        }
    }
    public function getXunJiaXinXi(){
        $house_info = $this->input->post();
        if (!empty(trim($house_info['area']))){
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
            $url = "http://www.fungugu.com/JinRongGuZhi/getXunJiaXinXi";
            $map['city_name']  = '上海';
            $map['area']       = $house_info['area'];
            $map['filter']     = $house_info['residentialName'];
            $map['position']   =  $house_info['residentialName'];
            $map['xiaoquID']   =  $house_info['xiaoquID'];
            $map['buildingNo'] = '';
            $map['house_type'] = $house_info['house_type'];
            $map['toward'] = $house_info['toward'];
            $map['floor'] = $house_info['floor'];//builted_time/totalfloor
            $map['builted_time'] = $house_info['builted_time'];//builted_time/totalfloor
            $map['totalfloor'] = $house_info['totalfloor'];//builted_time/totalfloor
//			print_r($map);die;
            $retdata = $this->postPage($url,$map);
            $res_arr = json_decode($retdata,true);
            echo json_encode($res_arr);die;
        }
    }

    /**
     * @param $url
     * @param $data
     * @param string $method
     * @return mixed
     */
    function postPage($url,$data,$method='post')
    {
        $response = $this->client->request($method,$url,['cookies' => $this->jar,'form_params' => $data])->getBody()->getContents();
        return $response;
    }
    public function getarea_p()
    {
        $community_number = $this->input->post();
        if ($community_number){
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
            $map['cityName'] = urlencode('上海');
            $map['guid'] = $community_number['communityId'];
            $url = "http://www.fungugu.com/addressSearch/getComIdByGuid?";
            $url .= $this->urlgetac($map);
            $retdata = $this->getPage($url);
            echo $retdata;die;
        }else{
            $ret['code'] = 0;
            $ret['msg'] = '不能为空！';
            echo json_encode($ret);die;
        }
    }
    /**
     * 获取周边小区信息
     */
    public function getNearby(){
        $house_info = $this->input->post();
        if (!empty(trim($house_info['communityId']))){
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
            $url = "http://www.fungugu.com/community_info/peripheral_community";
            $map['city_name']    = '上海';
            $map['community_ID'] = $house_info['communityId'];
            $map['radius']       = 2;

            $retdata = $this->postPage($url,$map);
            $res_arr = json_decode($retdata,true)['data'];
            echo json_encode($res_arr);die;
        }
    }

    /**
     * 获取楼盘详情
     */
    public function getBase(){
        $house_info = $this->input->post();
        if (!empty($house_info)){
            $this->client = new GuzzleHttp\Client();
            $this->jar = unserialize($_SESSION['jar_15618988191']);
            $url = "http://www.fungugu.com/JinRongGuZhi/getBaseinfo";
            $map['city']    = '上海';
//            $map['residentialAreaId'] = 21576;//36023
            $map['residentialAreaId'] = $house_info['residentialAreaId'];//36023
            $retdata = $this->postPage($url,$map);
            $res_arr = json_decode($retdata,true)['json']['0'];
            echo json_encode($res_arr);die;
        }
    }


}