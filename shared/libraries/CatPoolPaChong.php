<?php
require_once 'autoloader.php';
require_once HELPERS.'slog_helper.php';
require_once 'Domparse.php';
// require_once 'simple_html_dom.php';

class CatPoolPaChong{

    private $jar;//cookie
    private $client;//链接对象
    private $timeout = 5;//超时时间

    private $Domparse;//dom对象

    private $request_method = 'POST';//访问方式
    private $login_name = 'admin';//登录名称
    private $login_password = 'admins';//登录密码

    private $proxy_uri = 'http://localhost:20000';//反代云端服务器网址
    private $base_uri = 'http://172.16.10.200/default/zh_CN/';//根网址
    private $status_uri = 'status.html?type=gsm';//状态网页
    private $send_sms_uri = 'sms_info.html?code=utf8&type=sms';//发送短信接口
    private $send_sms_status_uri = 'send_sms_status.xml?line=&ajaxcachebust=';//短信发送状态网址
    private $sms_uri = 'tools.html?type=sms_inbox&code=utf8';//短信收件箱网页

    public function __construct(){
        // $this->jar = new \GuzzleHttp\Cookie\CookieJar();
        $this->client = new GuzzleHttp\Client([
            'base_uri'=>$this->proxy_uri,
            'connect_timeout'=>$this->timeout,
        ]);
    }

    //建立链接
    private function _get_client($uri,$form_params=[]){
        $res = $this->client->request($this->request_method,$uri,[
            // 'auth' => [$this->login_name,$this->login_password],
            'form_params' => $form_params,
            //'allow_redirects' => false,
        ]);
        // Slog::log('猫池爬虫：'.$uri);
        // Slog::log($form_params);
        if($res->getStatusCode()==200){
            return $res;
        }else{
            Slog::log($res->getReasonPhrase());
            exit($res->getReasonPhrase());
        }
    }

    //生成Domparse对象 $html = string or url
    private function _get_domparse($html=''){
        $this->Domparse = new Domparse();
        $this->Domparse->initDom($html);
        return;
    }

    //gb2312转utf8
    private function _get_utf8_from_gb($value){
        $value_1 = $value;
        $value_2 = @iconv( "gb2312", "utf-8//IGNORE",$value_1);
        $value_3 = @iconv( "utf-8", "gb2312//IGNORE",$value_2);
        if   (strlen($value_1)==strlen($value_3)){
            return $value_2;
        }else{
            return $value_1;
        }
    }

    //获取猫池各卡状态
    public function get_cat_pool_status(){
        $html = $this->_get_client('',['url'=>$this->base_uri.$this->status_uri])->getBody();
        $html = $this->_get_utf8_from_gb($html);

        $this->_get_domparse($html);
        //[线路]、[SIM]、[注册]、[信号]、[GPRS注册]、[GPRS附着]、[运营商]、[基站模式]、[BCCH]、[位置区编码]
            $status_table_dom = $this->Domparse->find('#gsm_info table',1);

            //标题
            $title_arr = [];
            foreach ($status_table_dom->find('tr',0)->find('td') as $td) {
                $title_arr[] = $td->plaintext;
            }

            //数据
            $status_arr = [];
            $arr = [];
            foreach ($status_table_dom->find('tr') as $tr) {
                if($tr == $status_table_dom->find('tr',0)) continue;
                $num = 0;
                foreach ($tr->find('td') as $td) {
                    $arr[$title_arr[$num]] = trim($td->plaintext,'&nbsp;');
                    $num++;
                }
                $status_arr[$arr['线路']] = $arr;
                $arr = [];
            }
        
        // [线路]、[模块]、[模块版本]、[SIM号码]、[IMEI]、[IMSI]、[ICCID]
            $detail_table_dom = $this->Domparse->find('#gsm_detail table',0);

            //标题
            $title_arr = [];
            foreach ($detail_table_dom->find('tr',0)->find('td') as $td) {
                $title_arr[] = $td->plaintext;
            }

            //数据
            $detail_arr = [];
            $arr = [];
            foreach ($detail_table_dom->find('tr') as $tr) {
                if($tr == $detail_table_dom->find('tr',0)) continue;
                $num = 0;
                foreach ($tr->find('td') as $td) {
                    $arr[$title_arr[$num]] = trim($td->plaintext,'&nbsp;');
                    $num++;
                }
                $status_arr[$arr['线路']] += $arr;
                $arr = [];
            }
            return $status_arr;
    }

    //获取猫池各卡状体详情
    // [线路]、[模块]、[模块版本]、[SIM号码]、[IMEI]、[IMSI]、[ICCID]
    // public function get_cat_pool_detail(){
    //     $html = $this->_get_client($this->status_uri)->getBody();
    //     $html = $this->_get_utf8_from_gb($html);

    //     $this->_get_domparse($html);
    //     //状态表dom
    //     $detail_table_dom = $this->Domparse->find('#gsm_detail table',0);

    //     //标题
    //     $title_arr = [];
    //     foreach ($detail_table_dom->find('tr',0)->find('td') as $td) {
    //         $title_arr[] = $td->plaintext;
    //     }

    //     //数据
    //     $detail_arr = [];
    //     $arr = [];
    //     foreach ($detail_table_dom->find('tr') as $tr) {
    //         if($tr == $detail_table_dom->find('tr',0)) continue;
    //         $num = 0;
    //         foreach ($tr->find('td') as $td) {
    //             $arr[$title_arr[$num]] = $td->plaintext;
    //             $num++;
    //         }
    //         $detail_arr[$arr['线路']] = $arr;
    //         $arr = [];
    //     }
    //     return $detail_arr;
    // }

    //发送短信
    /*
    * $lines 线路数 1～16
    * $smskey 验证码 ajax获取 '5b977a14' ???实测传''无影响
    * $telnum 发送号码 
    * $smscontent 发送内容
    *
    * action = 'SMS'
    * send = '发送'
    */
    public function send_sms($lines=[],$telnum,$smscontent){
        $this->request_method = 'POST';//访问方式
        $form_params = [
            'smskey' => '',
            'telnum' => $telnum,
            'smscontent' => $smscontent,
            'action' => 'SMS',
            'send' => '发送',
        ];
        foreach ($lines as $val) {
            $form_params['line'.$val] = 1;
        }
        Slog::log('发送短信: url:'.$this->send_sms_uri.' post:'.json_encode($form_params));
        $o = $this->_get_client('',[
            'url'=>$this->base_uri.$this->send_sms_uri,
            'form_data'=>$form_params,
        ]);
        $str = $o->getBody();
        return strtotime($o->getHeader('Date')[0]);
    }

    // //发送余额查询短息 返回目标响应时间
    // // $lines = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16];
    // public function send_yecx_sms($lines=[],$telnum=10086,$smscontent='yecx'){
    //     $smskey = '';//???
    //     //$telnum = '10086';//暂时只做移动
    //     //$smscontent = 'yecx';//移动余额查询代码

    //     $response_send_time = $this->send_sms($lines,$smskey,$telnum,$smscontent);
    //     return $response_send_time;
    // }

    //返回13位时间戳
    private function get_time() { 
        list($t1, $t2) = explode(' ', microtime()); 
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000); 
    }

    /*
    *   xml字符串转数组
    *   $xml_arr=[
    *       'name'=>'send-sms-status',//状态名称
    *       '线路'=>[
    *           'smskey'=>'5b987023',
    *           'status'=>'STARTED'//执行中、'DONE'//完成
    *           'error'=>'50'//空表示执行正确、'GSM_LOGOUT'//无卡
    *       ],
    *       ......
    *   ]
    */
    private function xml_to_arr($xml=''){
        $xml_arr = [];
        $xml_obj = simplexml_load_string($xml);
        
        //$xml_arr['name'] = $xml_obj->getName();
        $xml_keys = ['smskey','status','error'];
        
        foreach ($xml_obj as $k => $v) {
            foreach ($xml_keys as $key => $val) {
                if(strpos($k,$val)!==false){
                    $xml_arr[substr($k,strlen($val))][$val] = (string)$v[0];
                    break;
                }
            }
        }
        return $xml_arr; 
    }

    //获取发送短信的xml执行状态
    public function get_send_sms_status(){
        $xml = $this->_get_client('',[
            'url'=>$this->base_uri.$this->send_sms_status_uri.$this->get_time()
        ])->getBody();
        return $this->xml_to_arr($xml);
    }

    //获取短信收件箱
    public function get_sms(){
        $html = $this->_get_client('',[
            'url'=>$this->base_uri.$this->sms_uri
        ])->getBody();
        $html = strstr($html,'sms=');
        $html_arr = explode('sms=',$html);
        unset($html_arr[0]);
        foreach ($html_arr as $k => &$v) {
            $v = strstr($v,'sms_row_insert(',true);
            $v = mb_strrchr($v,';',true);
            $v = json_decode($v,true);
            foreach ($v as $key => $val) {
                if($key===0 && $val===''){
                    unset($html_arr[$k]);
                    continue;
                }
                $v[$key] = explode(',',$val,3);
            }
        }
        return $html_arr;
    }






    //登录
    // public login(){
    //     $response = $this->client->request($method,$url,['cookies' => $this->jar,'form_params' => $data])->getBody()->getContents();
    //    // $_SESSION['client_15618988191'] =  serialize($this->client);
    //     $_SESSION['jar_15618988191'] =  serialize($this->jar);
    //     return $response;
    // }

    public function getImg(){
        $response = $this->client->request('get',"https://mcht.openepay.com/merchants/merchant/login/initLogin.do?currentTimeMillis=".time(),
            [
                'verify'=>false,
                'cookies'=>$this->jar
            ]);
        header("Content-Type:",$response->getHeaderLine("Content-Type"));
        echo $response->getBody()->getContents();
    }

    public function login($code)
    {
        $url = "https://mcht.openepay.com/merchants/merchant/login/agentAuth.do";
        $response = $this->client->request('POST',$url,[
            'verify'=>false,
            'cookies'=>$this->jar,
            'headers'=>$this->headers,
            'form_params'=>[
                'language'=>1,
                'merchantId'=>'205840170420001',
                'operatorId'=>'agentAdmin',
                'password1'=>'',
                'password'=>'agent@5678',
                'checkCode'=>$code
                ]
        ]);

        echo $response->getBody()->getContents();

    }
}