<?php
$env = 'local';
 //$env = 'dev';
if ('local' == $env) {
    require('../shared/libraries/vendor/autoload.php');
} else {
    require('/usr/share/nginx/html/shared/libraries/vendor/autoload.php');
}
class Sifa extends Admin_Controller
{

    public function showsifa()
    {
        $this->showpage('fms/sifa');
    }
    /**
     * 贝壳网
     * @param string $xiaoqu
     */
    public function searchbeikeXiaoqu($xiaoqu = ""){
        $xiaoqu = trim($this->input->get('xiaoqu'));
        if(empty($xiaoqu)){
            exit;
        }
        $xiaoquInfo = $this->db->where('name',$xiaoqu)->get('xiaoquinfo')->row_array();
       // if(true){
       /*  if(empty($xiaoquInfo)){
            $beanbun = new Beanbun\Beanbun;
            $parser = new Beanbun\Middleware\Parser;
            $beanbun->name = 'sfpm';
            $beanbun->count = 5;
            $beanbun->seed = 'https://sh.ke.com/xiaoqu/rs'.$xiaoqu;
            $beanbun->max = 20;
            $beanbun->middleware($parser);
            $beanbun->fields = [
                [
                    'name' => 'name',
                    'selector' => ['.content .leftContent li .info .title a', 'text'],
                    'repeated' => true,
                ],
                [
                    'name' => 'serachurl',
                    'selector' => ['.content .leftContent li .info .title a', 'href'],
                    'repeated' => true,
                ],
                [
                    'name' => 'price',
                    'selector' => ['.content .leftContent li .xiaoquListItemRight .xiaoquListItemPrice .totalPrice span', 'text'],
                    'repeated' => true,
                ],
                [
                    'name' => 'month',
                    'selector' => ['.content .leftContent li .xiaoquListItemRight .xiaoquListItemPrice .priceDesc', 'text'],
                    'repeated' => true,
                ]
            ];
            $beanbun->afterDownloadPage = function($beanbun) {
                $houseinfo = $beanbun->data;
                foreach ($houseinfo as $key => $value){
                    foreach ($value as $k => $v){
                        $retdata[$k][$key] = $v;
                    }
                }
                echo json_encode($retdata);
            }; 
            $beanbun->start();
        }else{ */
            $redata['searchurl']  = $xiaoquInfo['id'];
            $redata['city']  = $xiaoquInfo['city'];
            $redata['district']  = $xiaoquInfo['district'];
            $redata['name']  = $xiaoquInfo['name'];
            $redata['propertyfee']  = $xiaoquInfo['propertyfee'];
            $redata['comyear'] = $xiaoquInfo['comyear'];
            $redata['address'] = $xiaoquInfo['address'];
            $redata['price'] = $xiaoquInfo['price'];
            $redata['buildingnum'] = $xiaoquInfo['buildingnum'];
            $redata['roomnum'] = $xiaoquInfo['roomnum'];
            $this->db->where('xiaoquid',$xiaoquInfo['id']);
            $this->db->select_max('month');
            $this->db->select('price');
            $xiaoquInfo_price = $this->db->get('xiaoquprice')->row_array();
            if($xiaoquInfo_price){
                $redata['price'] = $xiaoquInfo_price['price'];
                $redata['month'] = $xiaoquInfo_price['month'];
            }
            echo json_encode(array($redata));exit;
       // }
    }
    public function beikexiaoquxq(){
        $xqid =  $this->input->post('id');
        $xqdata =  $this->db->where('id',$xqid)->get('xiaoquinfo')->row_array();
        $map['city']  = $xqdata['city'];
        $map['district']  = $xqdata['district'];
        $map['price']       = $xqdata['price'];
        $map['name']        = $xqdata['name'];
        $map['address'] = $xqdata['address'];
        $map['comyear']     = $xqdata['comyear'];
        $map['propertyfee'] = $xqdata['propertyfee'];
        $map['buildingnum'] = $xqdata['buildingnum'];
        $map['roomnum']     = $xqdata['roomnum'];
        $xiaoquInfo_price = $this->db->get('xiaoquprice')->row_array();
        if($xiaoquInfo_price){
            $redata['price'] = $xiaoquInfo_price['price'];
            $redata['month'] = $xiaoquInfo_price['month'];
        }
        $zjcjdata =  $this->db->where('xq_id',$xqid)->get('houscjinfo')->result_array();
        
        $zjcjdataTable = "<tr><td>面积（㎡）</td><td>房屋类型</td><td>所在楼层位置</td><td>梯户比例</td><td>配备电梯</td><td>挂牌价格（万）</td><td>成交价格（万）</td><td>成交周期（天）</td></tr>";
        foreach ($zjcjdata as $key => $value){
            $trtb = '';
            foreach ($value as $k => $v){
                if($k == 'xq_id'){
                    unset($v);
                }else{
                    $trtb .= "<td>".$v."</td>";
                }
            }
            $zjcjdataTable .= "<tr>".$trtb."</tr>";
            $trtb = '';
        }
       
        $map['zjcj'] = $zjcjdataTable;
        echo json_encode($map);
        /* $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'beike';
        $beanbun->count = 50;
        $beanbun->seed = $url;
        $beanbun->max = 100;
        $beanbun->middleware($parser);
        $beanbun->fields = [
            [
                'name' => 'price',
                'selector' => ['.xiaoquDescribe .xiaoquPrice .xiaoquUnitPrice', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'cankao_time',
                'selector' => ['.xiaoquPrice .xiaoquUnitPriceDesc', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'build_info',
                'selector' => ['.xiaoquInfo .xiaoquInfoItem .xiaoquInfoContent', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'xiaoqu_name',
                'selector' => ['.xiaoquDetailHeader .detailTitle', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'xiaoqu_address',
                'selector' => ['.xiaoquDetailHeader .detailDesc', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'chengjiao_url',
                'selector' => ['.frameDealList .frameDealListItem .frameDealInfo>a', 'href'],
                'repeated' => true,
            ]
            
        ];
        
        $beanbun->afterDownloadPage = function($beanbun) {
            $houseinfo = $beanbun->data;
            
            $address = explode(')',$houseinfo['xiaoqu_address'][0]);
            $map['city']  = '上海';
            $map['district']  = mb_substr($address[0],1);
            $map['price']       = isset($houseinfo['price'][0]) ? $houseinfo['price'][0] : '';
            $map['name']        = isset($houseinfo['xiaoqu_name'][0]) ? $houseinfo['xiaoqu_name'][0] : '';
            // $map['address']     = isset($houseinfo['xiaoqu_address'][0]) ? $houseinfo['xiaoqu_address'][0] : '';
            $map['address'] = $address[1];
            $map['comyear']     = isset($houseinfo['build_info'][0]) ? $houseinfo['build_info'][0] : '';
            $map['propertyfee'] = isset($houseinfo['build_info'][2]) ? $houseinfo['build_info'][2] : '';
            $map['buildingnum'] = isset($houseinfo['build_info'][5]) ? $houseinfo['build_info'][5] : '';
            $map['roomnum']     = isset($houseinfo['build_info'][6]) ? $houseinfo['build_info'][6] : ''; 
            $this->db->where('name',$map['name']);
            $this->db->where('address',$map['address']);
            $xiaoquInfos = $this->db->get('xiaoquinfo')->row_array();
            if(!$xiaoquInfos){
                $this->db->insert( 'xiaoquinfo',$map); 
            }
            echo  json_encode($map);
        };
        $beanbun->start(); */
    
    }
    public function searchshunruXiaoqu($xiaoqu = "")
    {
        $xiaoqu = trim($this->input->get('xiaoqu'));
        if(empty($xiaoqu)){
            exit;
        }
        $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'sfpm';
        $beanbun->count = 50;
        $beanbun->seed = 'http://www.shunru.net.cn/category.php?keywords_search='.$xiaoqu.'&mod=information&catid=45&cityid=2&areaid=&streetid=';
        $beanbun->max = 20;
        $beanbun->middleware($parser);
        $beanbun->fields = [
            [
                'name' => 'serachurl',
                'selector' =>['.list_box .bd ul .clearfix .p1 a', 'href'],
                'repeated' => true,
            ],

            [
                'name' => 'urlname',
                'selector' =>['.list_box .bd ul .clearfix .p1 a', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'phone',
                'selector' =>['.list_box .bd ul .clearfix .p5 span', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'qp_price',
                'selector' =>['.list_box .bd ul .clearfix .price .p1', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'market_price',
                'selector' =>['.list_box .bd ul .clearfix .price .p2', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'img',
                'selector' =>['.list_box .bd ul .clearfix .fl a img', 'src'],
                'repeated' => true,
            ],
        ];

        $beanbun->afterDownloadPage = function($beanbun) {
            $houseinfo = $beanbun->data;
            $h_count = count($houseinfo['serachurl']);
            if($h_count == 0){
                exit;
            }
            for ( $i=0; $i<$h_count; $i++){
                $arri[] = array_column($houseinfo, $i);
            }
            echo json_encode($arri);die;
        };
        $beanbun->start();
    }



    public function getshunrufangHousInfo($url = '')
    {
        $url = $this->input->post('id');
        $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'sfpm';
        $beanbun->count = 50;
        $beanbun->seed = $url;
        $beanbun->max = 100;
        $beanbun->middleware($parser);
        $beanbun->fields = [
            [
                'name' => 'name',
                'selector' => ['.info_box .d1', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'price',
                'selector' => ['.info_box .d2', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'date',
                'selector' => ['.info_box .d3', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'area',
                'selector' => ['.info_box .d4 span', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'address',
                'selector' => ['.info_box .d6', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'message',
                'selector' => ['.main_box  .clearfix .item .bd p', 'text'],
                'repeated' => true,
            ]
        ];

        $beanbun->afterDownloadPage = function($beanbun) {
            $houseinfo = $beanbun->data;
//            print_r($houseinfo);die;
            function trimall($str){
                $qian=array(" ","　","\t","\n","\r");
                return str_replace($qian, '', $str);
            }
            $map['price'] = $houseinfo['price'][0];
            $map['xq_name'] = $houseinfo['name'][0];
            $map['date'] = substr($houseinfo['date'][0],15) ;
            $map['bz_money'] = substr($houseinfo['date'][1],12) ;
            $map['louceng'] = $houseinfo['area'][0] ;
            $map['mianji'] = $houseinfo['area'][1] ;
            $map['danjia'] = $houseinfo['area'][2] ;
            $map['address'] = substr(trimall($houseinfo['address'][0]),6) ;
            $map['message'] = implode(' ',$houseinfo['message']) ;
            echo json_encode($map);die;
        };
        $beanbun->start();
    }



    public function searchpaihaofangXiaoqu()
    {
        $xiaoqu = trim($this->input->get('xiaoqu'));
        if(empty($xiaoqu)){
            exit;
        }
        $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'sfpm';
        $beanbun->count = 50;

        $beanbun->seed = 'http://www.paihaofang.net/index.php?m=content&c=house&a=lists&catid=30&kw='.$xiaoqu;
        $beanbun->max = 20;
        $beanbun->middleware($parser);
        $beanbun->fields = [
            [
                'name' => 'searchurl',
                'selector' =>['.houselist li a', 'href'],
                'repeated' => true,
            ],
            [
                'name' => 'urlname',
                'selector' =>['.houselist li a div .houselist_img_continer_title', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houselist_acreage',
                'selector' =>['.houselist li a div .houselist_acreage', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houselist_price',
                'selector' =>['.houselist li a div .houselist_price', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houselist_dateline',
                'selector' =>['.houselist li a div .houselist_dateline', 'text'],
                'repeated' => true,
            ],
        ];

        $beanbun->afterDownloadPage = function($beanbun) {
            $houseinfo = $beanbun->data;
            
            $h_count = count($houseinfo['searchurl']);
            if($h_count > 0){
                for ( $i=0; $i<$h_count; $i++){
                    $arri[] = array_column($houseinfo, $i);
                }
                echo json_encode($arri);die;
            }
            
        };
        $beanbun->start();
    }


    public function getpmpaihaofangHousInfo()
    {
//        print_r($this->input->post());die;
        $url = $this->input->post('id');
        $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'sfpm';
        $beanbun->count = 50;
        $beanbun->seed = 'http://www.paihaofang.net'.$url;
        $beanbun->max = 100;
        $beanbun->middleware($parser);
        $beanbun->fields = [
            [
                'name' => 'title',
                'selector' => ['.house_view_info .title', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'acreage',
                'selector' => ['.house_view_info .houselist_acreage', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'price',
                'selector' => ['.house_view_info .houselist_price', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'dateline',
                'selector' => ['.house_view_info .houselist_dateline', 'text'],
                'repeated' => true,
            ],

            [
                'name' => 'num',
                'selector' => ['.house_view_info .houselist_num', 'text'],
                'repeated' => true,
            ]
        ];

        $beanbun->afterDownloadPage = function($beanbun) {
            $houseinfo = $beanbun->data;
            $map['name'] = $houseinfo['title'][0];
            $map['mianji'] = $houseinfo['acreage'][0];
            $map['price'] = $houseinfo['price'][0];
            $map['date'] = $houseinfo['dateline'][0];
            $map['user'] = $houseinfo['dateline'][1];
            $map['phone'] = $houseinfo['num'][0];
            echo json_encode(($map));
        };
        $beanbun->start();
    }

    public function sftaobao($xiaoquAddress=""){
        if (!$this->input->get()){
//print_r(123123);die;
//            echo 'error';
            $xiaoquAddress = '河畔明珠';
            $xiaoquAddress = urldecode($xiaoquAddress);//河畔明珠
            $xiaoquAddress = iconv("UTF-8","GBK//IGNORE",$xiaoquAddress);
            $xiaoquAddress = urlencode($xiaoquAddress);

            $client = new GuzzleHttp\Client();
//            print_r($client);die;
            $url = 'https://sf.taobao.com/item_list.htm?q='.$xiaoquAddress.'&spm=a213w.3064813.9001.1';
            $response = $client->request('get',$url)->getBody()->getContents();
            $ustr = mb_convert_encoding($response, "UTF-8", "GBK");
            $hstr = htmlspecialchars($ustr);
            $sstr = substr($hstr,32089,10000);
            $pstr = strrpos($sstr,'supportOrgLoan');
            $endstr =  substr($sstr,1,$pstr+24);
            $hd_str = htmlspecialchars_decode($endstr);
            echo $hd_str;die;
        }else{
            $xiaoquAddress = $this->input->get('xiaoqu');
//            print_r($xiaoquAddress);die;
            $xiaoquAddress = urldecode($xiaoquAddress);//河畔明珠
            $xiaoquAddress = iconv("UTF-8","GBK//IGNORE",$xiaoquAddress);
            $xiaoquAddress = urlencode($xiaoquAddress);

            $client = new GuzzleHttp\Client();
//            print_r($client);die;
            $url = 'https://203.119.207.253:443/item_list.htm?q='.$xiaoquAddress.'&spm=a213w.3064813.9001.1';
            $response = file_get_contents($url);
            print_r($response);die;
//            $response = $client->request('get',$url)->getBody()->getContents();
//            print_r($response);die;
            $ustr = mb_convert_encoding($response, "UTF-8", "GBK");
            $hstr = htmlspecialchars($ustr);
            $sstr = substr($hstr,32089,10000);
            $pstr = strrpos($sstr,'supportOrgLoan');
            $endstr =  substr($sstr,0,$pstr+24);
            $hd_str = htmlspecialchars_decode($endstr);
            echo $hd_str;die;
        }

    }


    public function showtaobao()
    {
        $this->showpage('fms/taobao');
    }
    public function taobao_detail()
    {
        $xiaoqu = trim($this->input->get('xiaoqu'));
        $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'sfpm';
        $beanbun->count = 50;
        $beanbun->seed = 'http://sf.taobao.com/sf_item/575370769098.htm';
        $beanbun->max = 20;
        $beanbun->middleware($parser);
        $beanbun->fields = [
            [
                'name' => 'serachurl',
                'selector' =>['#J_HoverShow tr td span span', 'text'],
                'repeated' => true,
            ]
        ];
        $beanbun->afterDownloadPage = function($beanbun) {
            $houseinfo = $beanbun->data;
            print_r($houseinfo);die;
        };
        $beanbun->start();
    }
    public function UTF8toGBK(){
        
        $string = trim($this->input->get('urlstr'));
        
        $string = iconv("utf-8","gbk",$string);
        $string = urlencode($string);
        echo $string;exit;
       
    }
}