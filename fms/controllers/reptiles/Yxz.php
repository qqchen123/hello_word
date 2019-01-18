<?php

    require_once '../shared/libraries/autoloader.php';
    require_once HELPERS.'slog_helper.php';
    require_once '../shared/libraries/Domparse.php';
    // require_once '../shared/libraries/vendor/simple_html_dom.php';


/**
 * @desc 银信总账号爬虫
 */
class Yxz extends Admin_Controller{
    private $jar;//cookie
    private $cookie;
    private $client;//链接对象
    private $timeout = 5;//超时时间

    private $Domparse;//dom对象

    private $request_method = 'POST';//访问方式
    private $login_username = '13761888138';//登录名称
    private $login_password = '258369aa';//登录密码

    private $base_uri = 'https://www.yinxinsirencaihang.com';//根网址
    private $login = '/doLogin';//登录

    private $out_money = '/referrer/myreferrer';//出借人列表
    private $out_money_detail = '/referrer/investmentDetail';//出借详情

    private $in_money = '/referrer/myreferrerBorrower';//借款人列表
    private $in_money_detail = '/borrow/borrowDetail';//借款详情


    public function __construct(){
        parent::__construct();

        $this->jar = new \GuzzleHttp\Cookie\CookieJar();
        $this->client = new GuzzleHttp\Client([
            'base_uri'=>$this->base_uri,
            'connect_timeout'=>$this->timeout,
        ]);

        $this->begin_time = time();
    }

    // public function __destruct(){
    // }

    //输出信息
    private function msg($msg,$if_exit=true){
        $msg = "\r\n<br>".date('Y-m-d H:i:s')."银信总账号爬虫信息：".$msg."<br>\r\n";
        if ($if_exit) {
            exit($msg);
        }else{
            echo $msg;
        }
    }

    //登录
    public function login(){
        $response = $this->client->request('POST',$this->login,[
            'verify'=>true,
            'cookies'=>$this->jar,
            'allow_redirects' => false,
            // 'headers'=>$this->headers,
            'form_params'=>[
                'username' => $this->login_username,
                'password' => $this->login_password,
                'vcode' => '',
                'hasToken' => 'true',
            ],
        ]);
        $r = $response->getBody()->getContents();
        if(json_decode($r,true)['success']){
            $this->msg('账号：'.$this->login_username.' 密码：'.$this->login_password.' 登录成功',false);
        }else{
            $this->msg('账号：'.$this->login_username.' 密码：'.$this->login_password.' 不正确');
        }
    }

    //建立链接
    private function _get_client($uri,$form_params=[],$try_num=1){

        $arr =(array)$this->jar;
        if(array_shift($arr) === []) $this->login();
        $res = $this->client->request($this->request_method,$uri,[
            'cookies' => $this->jar,
            'form_params' => $form_params,
            'allow_redirects' => false,
            'headers' => [
                'Content-Encoding' => 'gzip'
            ]
        ]);
        if($res->getStatusCode()==200){
            return $res;
        }elseif ($res->getStatusCode()==701 || $try_num<3) {
            $this->login();
            return $this->_get_client($uri,$form_params,++$try_num);
        }else{
            $this->msg($try_num.' 次尝试获取 '.$uri.' 数据失败',false);
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
    // private function _get_utf8_from_gb($value){
    //     $value_1 = $value;
    //     $value_2 = @iconv( "gb2312", "utf-8//IGNORE",$value_1);
    //     $value_3 = @iconv( "utf-8", "gb2312//IGNORE",$value_2);
    //     if   (strlen($value_1)==strlen($value_3)){
    //         return $value_2;
    //     }else{
    //         return $value_1;
    //     }
    // }

    private function get_use_time(){
        $times = (time()-$this->begin_time);
        $result = '00:00:00';
        if ($times>0) {
                $hour = str_pad(floor($times/3600),2,'0',0);
                $minute = str_pad(floor(($times-3600 * $hour)/60),2,'0',0);
                $second = str_pad(floor((($times-3600 * $hour) - 60 * $minute) % 60),2,'0',0);
                $result = $hour.':'.$minute.':'.$second;
        }
        return $result;
    }
    
    //获取出借列表数据 开始-----------------
        public function get_out_money($find_account=null){
            echo "\n\r<br>获取出借列表数据 开始 ".date('Y-m-d H:i:s')."===========================================";
            
            //查找页数
            $html = $this->_get_client($this->out_money)->getBody();
            $this->_get_domparse($html);
            $now_page = $this->Domparse->find('pager',0)->current;
            $max_page = $this->Domparse->find('pager',0)->size;

            //查找内容
            $res = [];

            for ($i=$now_page; $i<=$max_page; $i++) {
                $this->msg('开始爬第'.$i.'页',false);
                $html = $this->_get_client($this->out_money,[
                    'currentPage' => $i,
                ])->getBody();
                $res += $this->parse_out_money($html,$find_account);
            }

            echo "\n\r<br>获取出借列表数据 结束 ".date('Y-m-d H:i:s')."  耗时".$this->get_use_time()."===========================================";
        }

        private function parse_out_money($html,$find_account){
            $map = [
                'account',//被推荐人用户名
                'recommend_tie',//推荐关系
                'reg_time',//注册时间
                'out_money',//被推荐人出借金额
                'userId',//银信内部客户编号（估计）
            ];
            $this->load->model('yx/YxOutMoney_model', 'yom');

            $arr = [];
            $this->_get_domparse($html);
            foreach ($this->Domparse->find('#referrer_tabForm',0)->find('ul') as $key=>$ul) {
                foreach ($ul->find('li') as $k=>$li) {
                    $content = trim($li->innertext);
                    if($k==0) $account = $content;
                    if($k!=4){
                        $arr[$account][$map[$k]] = $content;
                    }else{
                        $userId = $li->find('a',0)->id;
                        $arr[$account][$map[$k]] = $userId;
                        $this->get_out_money_detail($userId,$account);
                    }
                }

                //写入数据库
                if($arr[$account]){
                    $this->msg('出借数据',false);
                    var_export($arr[$account]);
                    $this->yom->replace_data($arr[$account]);
                }

                //判断是否寻找的find_account
                if($find_account!==null && $account==$find_account){
                    $this->msg('成功找到：'.$find_account,false);
                    exit("\n\r<br>获取出借列表数据 结束 ".date('Y-m-d H:i:s')."  耗时".$this->get_use_time()."===========================================");
                }
            }
            return $arr;
        }

        private function get_out_money_detail($userId,$account){
            $map = [
                'out_title',//借款标题
                'user_name',//真实姓名
                'id_number',//身份证号
                'call_number',//手机号
                'out_money',//出借金额
                'out_cyc',//出借期限
                'out_rate',//出借利率
                'return_mode',//还款方式
                'out_date',//出借时间
                'account',//银信编号
                'out_title_url'//借款标题网址
            ];

            $this->load->model('yx/YxOutMoneyDetail_model', 'yomd');

            $html = $this->_get_client($this->out_money_detail,[
                'userId' => $userId,
            ])->getBody();
            $this->_get_domparse($html);
            $arr = [];
            foreach ($this->Domparse->find('#myReferrerDetailsForm',0)->find('ul') as $key=>$ul){
                foreach ($ul->find('li') as $k=>$li) {
                    if($k==0){
                        $o = $li->find('a',0);
                        $arr[$key]['out_title_url'] = $o->href;
                        $arr[$key]['out_title'] = trim($o->plaintext);
                        $arr[$key]['account'] = $account;
                    }else{
                        $arr[$key][$map[$k]] = str_replace(["\t"],[],trim($li->plaintext));
                    }
                }

                //写入数据库
                if($arr[$key]){
                    $this->msg('出借详情数据',false);
                    var_export($arr[$key]);
                    $this->yomd->replace_data($arr[$key]);
                }
            }
        }
    //获取出借列表数据 结束-----------------

    
    //获取借款列表数据 开始-----------------
        public function get_in_money($find_account=null){
            echo "\n\r<br>获取出借列表数据 开始 ".date('Y-m-d H:i:s')."===========================================";

            //查找页数
            $html = $this->_get_client($this->in_money)->getBody();
            $this->_get_domparse($html);
            $now_page = $this->Domparse->find('pager',0)->current;
            $max_page = $this->Domparse->find('pager',0)->size;

            //查找内容
            $res = [];
            for ($i=$now_page; $i<=$max_page; $i++) {
                $this->msg('开始爬第'.$i.'页',false);
                $html = $this->_get_client($this->in_money,[
                    'currentPage' => $i,
                ])->getBody();
                $res += $this->parse_in_money($html,$find_account);
            }

            echo "\n\r<br>获取出借列表数据 结束 ".date('Y-m-d H:i:s')."  耗时".$this->get_use_time()."===========================================";
        }

        private function parse_in_money($html,$find_account){
            $map = [
                'account',//被推荐人用户名
                'recommend_tie',//推荐关系
                'reg_time',//注册时间
                'in_money',//被推荐人出借金额
                'userId',//银信内部客户编号（估计）
            ];
            $this->load->model('yx/YxInMoney_model', 'yim');

            $arr = [];
            $this->_get_domparse($html);
            foreach ($this->Domparse->find('#referrer_tabForm',0)->find('ul') as $key=>$ul) {
                foreach ($ul->find('li') as $k=>$li) {
                    $content = trim($li->innertext);
                    if($k==0) $account = $content;   
                    if($k!=4){
                        $arr[$account][$map[$k]] = $content;
                    }else{
                        $userId = $li->find('a',0)->id;
                        $arr[$account][$map[$k]] = $userId;
                        $this->get_in_money_detail($userId,$account);
                    }
                }

                //写入数据库
                if($arr[$account]){
                    $this->msg('借款数据',false);
                    var_export($arr[$account]);
                    $this->yim->replace_data($arr[$account]);
                }

                //判断是否寻找的find_account
                if($find_account!==null && $account==$find_account){
                    $this->msg('成功找到：'.$find_account,false);
                    exit("\n\r<br>获取出借列表数据 结束 ".date('Y-m-d H:i:s')."  耗时".$this->get_use_time()."===========================================");
                }
            }
            return $arr;
        }

        private function get_in_money_detail($userId,$account){
            $map = [
                'in_title',//借款标题
                'user_name',//真实姓名
                'id_number',//身份证号
                'call_number',//手机号
                'in_money',//出借金额
                'in_cyc',//出借期限
                'in_rate',//出借利率
                'return_mode',//还款方式
                'in_date',//出借时间
                'loan_date',//放款时间
                'account',//银信编号
                'in_title_url'//借款标题网址
            ];

            $this->load->model('yx/YxInMoneyDetail_model', 'yimd');

            $html = $this->_get_client($this->in_money_detail,[
                'userId' => $userId,
            ])->getBody();
            $this->_get_domparse($html);
            $arr = [];
            foreach ($this->Domparse->find('#myReferrerDetailsForm',0)->find('ul') as $key=>$ul){
                $arr[$key]['account'] = $account;
                foreach ($ul->find('li') as $k=>$li) {
                    $arr[$key][$map[$k]] = str_replace(["\t"],[],trim($li->plaintext));
                }

                //写入数据库
                if($arr[$key]){
                    $this->msg('借款详情数据',false);
                    var_export($arr[$key]);
                    $this->yimd->replace_data($arr[$key]);
                }
            }
        }
    //获取借款列表数据 结束-----------------


    
}