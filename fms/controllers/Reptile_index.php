<?php
//set_time_limit(0);
//$env = 'local';
//$env = 'dev';
//if ('local' == $env) {
require_once('d:/wamp64/www/vendor/autoload.php');
//} else {
 //   require('/www/guzzle-master/vendor/autoload.php');
//}

/**
 * Class Reptile
 * write by 陈恩杰
 */
class Reptile_index extends Admin_Controller
{
    public function set_url()
    {
        $num = $this->uri->segment(3);
        $qu = $this->uri->segment(4);
       // var_dump($num);exit;
       // $dq_arr = ['pudong','minhang','baoshan','xuhui','putuo','yangpu','changning','songjiang','jiading','huangpu','jingan','zhabei','hongkou','chongming'];
      //  $num = 10;
       // $countJ = count($dq_arr);
       // for ($j=0;$j<$countJ;$j++){
            for ($i = $num-1;$i<=$num;$i++){

               // $cs = $dq_arr[$j].'/'.$qu.'/pg'.$i;
                $cs = $qu.'/pg'.$i;
                echo $cs;exit;
                echo '<br>';
               $this->url_test($cs);
            }
       // }
    }
    public function url_test($cs = '')
    {
        $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'beike';
        $beanbun->count = 5;
        if ($cs){
            $beanbun->seed = 'https://sh.ke.com/xiaoqu/'.$cs;
        }else{
            $beanbun->seed = 'https://sh.ke.com/xiaoqu';
        }
        $beanbun->max = 100;
        $beanbun->middleware($parser);
        $beanbun->fields = [
            [
                'name' => 'url',
                'selector' => ['.info .title a', 'href'],
                'repeated' => true,
            ]
        ];
       
        $beanbun->afterDownloadPage = function($beanbun) {
            $houseinfo = $beanbun->data;
            foreach ($houseinfo['url'] as $k=>$v){
                $this->testbeanbun222($v);
            }
        };
        $beanbun->start();
    }
    public function testbeanbun222($url = '')
    {
        $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'beike';
        $beanbun->count = 5;
        if ($url){
            $beanbun->seed = $url;
        }else{
            $beanbun->seed = 'https://sh.ke.com/xiaoqu/5011000017980/';
        }
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
            $map['price']       = isset($houseinfo['price'][0]) ? $houseinfo['price'][0] : '';
            $map['name']        = isset($houseinfo['xiaoqu_name'][0]) ? $houseinfo['xiaoqu_name'][0] : '';
            $map['address']     = isset($houseinfo['xiaoqu_address'][0]) ? $houseinfo['xiaoqu_address'][0] : '';
            $map['comyear']     = isset($houseinfo['build_info'][0]) ? $houseinfo['build_info'][0] : '';
            $map['propertyfee'] = isset($houseinfo['build_info'][2]) ? $houseinfo['build_info'][2] : '';
            $map['buildingnum'] = isset($houseinfo['build_info'][5]) ? $houseinfo['build_info'][5] : '';
            $map['roomnum']     = isset($houseinfo['build_info'][6]) ? $houseinfo['build_info'][6] : '';
            $this->db->insert( 'xiaoquinfo',$map);
            foreach ($houseinfo['chengjiao_url'] as $k=>$v){
                $this->testbeanbun9999($v);
            }
        };
        $beanbun->start();
    }
    public function testbeanbun9999($urls)
    {
        $beanbun = new Beanbun\Beanbun;
        $parser = new Beanbun\Middleware\Parser;
        $beanbun->name = 'beike';
        $beanbun->count = 5;
        $beanbun->seed = $urls;
        $beanbun->max = 100;
        $beanbun->middleware($parser);
        $beanbun->fields = [
            [
                'name' => 'houinfo1',
                'selector' => ['.house-title .wrapper span', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houinfo2',
                'selector' => ['.house-title .wrapper .index_h1', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houinfo3',
                'selector' => ['.info .price .dealTotalPrice i', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houinfo4',
                'selector' => ['.info .price  b', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houinfo5',
                'selector' => ['.info .msg span label', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houinfo6',
                'selector' => ['.houseContentBox .content ul li span', 'text'],
                'repeated' => true,
            ],
            [
                'name' => 'houinfo7',
                'selector' => ['.houseContentBox .content ul li', 'text'],
                'repeated' => true,
            ],
        ];

        $beanbun->afterDownloadPage = function($beanbun) {
            $houseinfo = $beanbun->data;
//            print_r($beanbun->data);die;
            foreach ($houseinfo['houinfo7'] as $k =>$v){
                $houseinfo['houinfo7'][$k] = str_replace($houseinfo['houinfo6'][$k],'',$v);
            }
            $houseinfo['houinfo1'] = explode(' ',$houseinfo['houinfo1'][0]);
            $houseinfo['houinfo2'] = explode(' ',$houseinfo['houinfo2'][0]);

            $b_id = $this->db->where('name',$houseinfo['houinfo2'][0])->get('xiaoquinfo')->row_array();

            $map['xq_id']    = isset($b_id['id']) ? $b_id['id'] : '';
            $map['cjprice']  = isset($houseinfo['houinfo3'][0]) ? $houseinfo['houinfo3'][0] : '';
            $map['cjzq']     = isset($houseinfo['houinfo5'][1]) ? $houseinfo['houinfo5'][1] : '';
            $map['gpprice']  = isset($houseinfo['houinfo5'][0]) ? $houseinfo['houinfo5'][0] : '';
            $map['is_dt']    = isset($houseinfo['houinfo7'][11]) ? $houseinfo['houinfo7'][11] : '';
            $map['ltbl']     = isset($houseinfo['houinfo7'][11]) ? $houseinfo['houinfo7'][11] : '';
            $map['floor']    = isset($houseinfo['houinfo7'][1]) ? $houseinfo['houinfo7'][1] : '';
            $map['area']     = isset($houseinfo['houinfo7'][2]) ? $houseinfo['houinfo7'][2] : '';
            $map['roomtype'] = isset($houseinfo['houinfo7'][0]) ? $houseinfo['houinfo7'][0] : '';

            $this->db->insert('houscjinfo',$map);
        };
        $beanbun->start();
    }



}