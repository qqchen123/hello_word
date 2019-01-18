<?php 
class YunFang extends Admin_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->service('business/YunFang_service','yunfang');
    }
    /**
     *10102模糊匹配 模糊匹配小区
     * cityCode 城市CODE
     * comName  小区名称模糊信息（包含拼音、首字母）
     */
    public function yunfangAction(){
        $path = 'http://api.yunfangdata.com/general/building/areaSearch/v1';
        $map['cityCode'] = '310100';
        $map['comName'] = '香阁丽苑';
        
        $data = $this->yunfang->areaSearch($map,$path);
        var_dump($data);exit;
    }
    /**
     * 10201小区基础信息接口
     * cityCode 城市CODE
     * communityId 小区GUID
     */
    public function queryBaseInfo(){
        $path = 'http://api.yunfangdata.com/general/baseinfo/queryBaseInfo/v1';
        $map['cityCode'] = '310100';
        $map['communityId'] = 'c2f0ea60-292c-11e5-ac2c-288023a0e898';
        $data = $this->yunfang->areaSearch($map,$path);
        var_dump($data);exit;
    }
    /**
     * 10103楼栋单元户
     * cityCode 城市CODE
     * communityId 小区GUID
     * buildingId 楼栋GUID（如果传入楼栋GUID就必须有楼栋名称）
     * unitId 单元GUID（如果传入单元GUID就必须有单元名称）
     */
    
    public function getBuildingUnitDoor(){
        $path = 'http://api.yunfangdata.com/general/building/getBuildingUnitDoor/v1';
        $map['cityCode'] = '310100';
        $map['communityId'] = 'c2f0ea60-292c-11e5-ac2c-288023a0e898';
        $map['buildingId'] = 'f9acbe86-e495-11e7-bdff-6c92bf2bffb1';
        $map['unitId'] = '446d2723-5d76-4a6c-8b90-861df6dabb50';
        
        $data = $this->yunfang->areaSearch($map,$path);
        var_dump($data);exit;
    }
    /**
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
    public function enquiryPrice(){
        $path = 'http://api.yunfangdata.com/general/price/enquiryPrice/v1';
        $map['cityCode'] = '310100';
        $map['communityId'] = 'c2f0ea60-292c-11e5-ac2c-288023a0e898';
        $map['houseType'] = '';
        $map['enquiryTime'] = '';
        $map['buildingArea'] = '';
        $map['buildingId'] = 'f9acbe86-e495-11e7-bdff-6c92bf2bffb1';
        $map['houseId'] = '6f0cd16e-e4a3-11e7-bdff-6c92bf2bffb1';
        $map['buildYear'] = '';
        $map['floor'] = '';
        $map['totalFloor'] = '';
        $map['toward'] = '';
        $map['roomType'] = '';
        $map['specialFactors'] = '';
        $map['isDy'] = '';
        $data = $this->yunfang->areaSearch($map,$path);
        var_dump($data);exit;
    }
    
}    