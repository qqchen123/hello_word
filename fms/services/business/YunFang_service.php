<?php 
// $env = 'local';
$env = 'dev';
if ('dev' == $env) {
    require('/www/guzzle-master/vendor/autoload.php');
} else {
    require('/www/svn-code/guzzle-master/vendor/autoload.php');
}
class YunFang_service extends Admin_service{
    private $userKeyId = '9542538e883243c198aa82add5b98acf';
    private $userAccessKey = 'dbadd6f3640244fe83c50d790e4055d9';
    public function __construct()
    {
        parent::__construct();
       // $this->load->service('bs/HttpUtils','_http_utils');
    } 
    /**
     *10102模糊匹配 模糊匹配小区
     * cityCode 城市CODE
     * comName  小区名称模糊信息（包含拼音、首字母）
     */
    public function yunfangAction($map){
        $path = 'http://api.yunfangdata.com/general/building/areaSearch/v1';
       // $map['cityCode'] = '310100';
       // $map['comName'] = '香阁丽苑';
        $data = $this->areaSearch($map,$path);
        return $data;
    }
    /**
     * 10201小区基础信息接口
     * cityCode 城市CODE
     * communityId 小区GUID
     */
    public function queryBaseInfo($map){
        $path = 'http://api.yunfangdata.com/general/baseinfo/queryBaseInfo/v1';
        //$map['cityCode'] = '310100';
        //$map['communityId'] = 'c2f0ea60-292c-11e5-ac2c-288023a0e898';
        $data = $this->areaSearch($map,$path);
        return $data;
    }
    /**
     * 10103楼栋单元户
     * cityCode 城市CODE
     * communityId 小区GUID
     * buildingId 楼栋GUID（如果传入楼栋GUID就必须有楼栋名称）
     * unitId 单元GUID（如果传入单元GUID就必须有单元名称）
     */
    
    public function getBuildingUnitDoor($map){
        $path = 'http://api.yunfangdata.com/general/building/getBuildingUnitDoor/v1';
        //$map['cityCode'] = '310100';
       // $map['communityId'] = 'c2f0ea60-292c-11e5-ac2c-288023a0e898';
        //$map['buildingId'] = '24899f1d-e489-11e7-bdff-6c92bf2bffb1';
       // $map['unitId'] = 'f9ab877e-e495-11e7-bdff-6c92bf2bffb1';
        $data = $this->areaSearch($map,$path);
        return $data;
    }/**
    * 10301自动估值
    * cityCode             城市CODE                      必填
    * communityId          小区GUID                      必填
    * houseType            住宅类型（默认为住宅，住宅或别墅）           必填
    * enquiryTime          询价时间（默认为发起时间，YYYY-MM-dd）必填
    * buildingArea         建筑面积                                                                必填
    * buildingId           楼栋GUID                          选填
    * houseId              户号GUID                          选填
    * buildYear            建成年代（如2000,2001）                                选填
    * floor                所在层（整数，如-1、1）                           选填
    * totalFloor           总楼层（整数，如8、9）                                选填
    * toward               朝向（如东南、南北）                                      选填
    * roomType             居室（一居室：1，2居室：2，3居室：3，4居室：4，多室多厅：5)          选填
    * specialFactors       特殊因素（如复式、临街）                                                        选填
    * isDy                 是否抵押价值（默认为0，0：否，1：是）                        选填
    */
    public function enquiryPrice($map){
        $path = 'http://api.yunfangdata.com/general/price/enquiryPrice/v1';
        /* $map['cityCode'] = '310100';
        $map['communityId'] = 'c2f0ea60-292c-11e5-ac2c-288023a0e898';
        $map['houseType'] = '住宅';
        $map['enquiryTime'] = date('Y-m-d',time());
        $map['buildingArea'] = '100';
        $map['buildingId'] = 'f9ab877e-e495-11e7-bdff-6c92bf2bffb1';
        $map['houseId'] = '6f2ea2c8-e4a3-11e7-bdff-6c92bf2bffb1'; */
        /*  $map['buildYear'] = '';
         $map['floor'] = '';
         $map['totalFloor'] = '';
         $map['toward'] = '';
         $map['roomType'] = '';
         $map['specialFactors'] = '';
         $map['isDy'] = ''; */
        $data = $this->areaSearch($map,$path);
        return $data;
    }
    public function areaSearch($map,$path){
       $map['timeStamp'] = time();
       $accessSignature = $this->HashAndSignString($map);
       $map['userKeyId'] = $this->userKeyId;
       $urls = $this->urlAc($map);
       $url =  $path.$urls.'accessSignature='.$accessSignature;//var_dump($url);exit;
       $redata = $this->getPage($url);
       $redata = json_decode($redata,true);
       return $redata;
    }
    public function urlAc($map){
        ksort($map);
        $urlstr = '?';
        foreach ($map as $k => $v){
            $urlstr .= $k.'='.$v.'&';
        }
        return $urlstr;
    }
    /*
     * 生成数字签名
     * $userId 用户userKeyId
     * $userAccessKey 用户UserAccessKey
     * $time 时间戳
     * $parameters 请求参数
     * $method="Post" 请求类型 默认为:Post/Get/Put/Delete
     */
     public function HashAndSignString($parameters) {
        // 请求参数
        $arrSig = array();
        // 条件分隔符
        $strSep = '&';
        $KEY_ID = $this->userKeyId;
        $AKEY = $this->userAccessKey;
        //组装参数
        $arrSig['userKeyId'] = $KEY_ID;
        $arrSig['time'] = $parameters['timeStamp'];
        unset($parameters['timeStamp']);
        if (!empty($parameters)) {
            foreach ($parameters as $k => $v) {
                $arrSig[$k] = $v;
            }
        }
        //key字典排序
        ksort($arrSig);
        //生成规范化请求字符串
        $arrStd = array();
        foreach ($arrSig as $strKey => $strValue) {
            $arrStd[] = sprintf('%s=%s', $this->percentEncode($strKey), $this->percentEncode($strValue));
        }
        $strSig = implode('&', $arrStd);
        //生成用于计算签名的字符串
        $strSig = 'Post' . $strSep . $this-> percentEncode('/') . $strSep . $this-> percentEncode($strSig);
        //生成签名
        $strKeySecret = $AKEY . '&';
        $strSig = mb_convert_encoding($strSig, "UTF-8");
        $strSign = base64_encode(hash_hmac('sha1', $strSig, $strKeySecret, true));
        return $strSign;
    }
    /**
     * URL参数转码
     * @param
     * @return
     */
    function percentEncode($strUrl) {
        $strUrl = str_replace('+', '%20', rawurlencode($strUrl));
        $strUrl = str_replace('*', '%2A', $strUrl);
        $strUrl = str_replace('%7E', '~', $strUrl);
        return $strUrl;
    }
   
    function getPage($url,$method='get')
    {
        $client = new GuzzleHttp\Client();
        $response = $client->request($method,$url)->getBody()->getContents();
        return $response;
    }   
}