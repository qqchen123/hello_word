<?php
// $env = 'local';
$env = 'dev';
if ('local' == $env) {
    require('C:/www/svn-code/guzzle-master/vendor/autoload.php');
} else {
    // require('/www/guzzle-master/vendor/autoload.php');
    require('../shared/libraries/vendor/autoload.php');
}

/**
 * @desc 银信爬虫
 */
class Yx extends Admin_Controller
{
	private $client = '';
    private $cookie = '';
    
    private $username = '';
    private $pwd = '';

    private $def_username = 'YX15901889950';
    private $def_pwd = 'yxt146348';

    private $target_url = 'https://www.yinxinsirencaihang.com';

    private $task_time = '';

    private $url_map = [
    	'account_general_three' => [
    		'url' => '/account/account',
    		'name' => '账户综合数据',
    		'remark' => '账户概况-代收资产图标数据还有账户资金',
    	],
    	'query_wallet_by_user' => [
    		'url' => '/account/queryWalletByUser',
    		'name' => '账户钱包',
    		'remark' => '账户概况-账户资金(冻结金额) amount|frozenAmount|forbiddenWithdraw',
    	],
    	'query_service_info' => [
    		'url' => '/account/queryServiceInfo',
    		'name' => '',
    		'remark' => ''
    	],
    	'calendar_details' => [
    		'url' => '/myInvestmentCollect/calendarDetails',
    		'name' => '日历',
    		'remark' => '产品概况-资金日历'
    	],
    	'query_reward' => [
    		'url' => '/account/queryReward',
    		'name' => '查询报酬',
    		'remark' => ''
    	],
        'log_out' => [
            'url' => '/doLogout',
        ],
        'repaying' => [
            'url' => '/product/productRepayIng',
            'name' => '还款中',
            'remark' => '我的借款-还款中(一个人只会有一笔再借，故不用考虑分页)'
        ]
    ];

    private $currentPage = 1;
    private $totalPage = 1;

    private $lastcurrentPage = 1;

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->basicloadhelper();
        $this->client = new GuzzleHttp\Client();
        $type = $this->uri->segment(3);
        if (!in_array($type, ['getqyerybill', 'getinformationpage', 'getrepaying', 'getalldata'])) {
            //非脚本操作 需要使用手动登录 手动操作代码 登录默认账号
            if (empty($_SESSION['yxcookie'])) {
                $this->test();
            } else {
                $this->client = new GuzzleHttp\Client();
                $this->cookie = unserialize($_SESSION['yxcookie']);
            }
        }
	}

    private function set_task_time()
    {
        $this->task_time = date('YmdH', time());
    }

	/**
     * @name 加载基本的helper
     */
    private function basicloadhelper()
    {
        //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);
    }

    /**
     * @name 获取指定账号的电子账单或者全部账号的电子账单
     * @url /reptiles/yx/getqyerybill
     */
    public function getquerybill($cmd = '', $account = '')
    {
        $config = $_GET;
        if (!empty($account) || !empty($cmd)) {
            $config = [
                'account' => $account,
                'cmd' => $cmd,
            ];
        }
        var_dump($config);
        $this->set_task_time();
        $this->load->model('yx/YxAccount_model', 'yxaccount');
        if (!empty($config['account'])) {
            $ret = $this->yxaccount->find_pwd($config['account']);
            if (!empty($ret)) {
                $this->set_account_info($config['account'], $ret);
                echo '开始收集账号: ' . $this->username . ' 的电子账单';
                $this->to_login();
                $this->querybill();
            }
        } else {
            if (!empty($config['cmd']) && 'all' == $config['cmd']) {
                $account_array = $this->yxaccount->find_all_account();
                echo count($account_array);
                foreach ($account_array as $value) {
                    $ret = $this->yxaccount->find_pwd($value['account']);
                    echo $value['account'] . "\r\n";
                    if (!empty($ret)) {
                        $this->set_account_info(trim($value['account']), $ret);
                        $this->to_login();
                        echo '开始收集账号: ' . $this->username . ' 的电子账单';
                        $this->querybill();
                        echo '账号: ' . $this->username . ' 的电子账单收集完成<br/>' . "\r\n";
                        $this->to_logout();
                    }
                    sleep(rand(5, 10));
                    echo '延时降速';
                }
            } else {
                echo '目前关闭 批量操作';
                exit;
            }
            
        }
        echo '操作结束 ' . date('Y-m-d H:i:s') . '<br/>';
        echo "\r\n";exit;
    }

    /**
     * @name 获取指定账号的账户概况或者全部账号的账户概况
     * @url /reptiles/yx/getinformationpage
     */
    public function getinformationpage($cmd = '', $account = '') 
    {
        $config = $_GET;
        if (!empty($account) || !empty($cmd)) {
            $config = [
                'account' => $account,
                'cmd' => $cmd,
            ];
        }
        var_dump($config);
        $this->set_task_time();
        $this->load->model('yx/YxAccount_model', 'yxaccount');
        if (!empty($config['account'])) {
            $ret = $this->yxaccount->find_pwd($config['account']);
            if (!empty($ret)) {
                $this->set_account_info($config['account'], $ret);
                echo '开始收集账号: ' . $this->username . ' 的账号概况';
                $this->to_login();
                $this->informationpage();
                echo '账号: ' . $this->username . ' 的账号概况收集完成<br/>' . "\r\n";
            }
        } else {
            if (!empty($config['cmd']) && 'all' == $config['cmd']) {
                $account_array = $this->yxaccount->find_all_account();
                echo count($account_array);
                foreach ($account_array as $value) {
                    $ret = $this->yxaccount->find_pwd($value['account']);
                    echo $value['account'] . "\r\n";
                    if (!empty($ret)) {
                        $this->set_account_info(trim($value['account']), $ret);
                        $this->to_login();
                        echo '开始收集账号: ' . $this->username . ' 的账号概况';
                        $this->informationpage();
                        echo '账号: ' . $this->username . ' 的账号概况收集完成<br/>' . "\r\n";
                        $this->to_logout();
                    }
                    sleep(rand(5, 10));
                    echo '延时降速';
                }
            } else {
                echo '目前关闭 批量操作';
                exit;
            }
            
        }
        echo '操作结束 ' . date('Y-m-d H:i:s') . '<br/>';
        echo "\r\n";exit;
    }

    /**
     * @name 获取指定账号的还款中信息或者全部账号的还款中信息
     * @url /reptiles/yx/getrepaying
     */
    public function getrepaying($cmd = '', $account = '')
    {
        $config = $_GET;
        if (!empty($account) || !empty($cmd)) {
            $config = [
                'account' => $account,
                'cmd' => $cmd,
            ];
        }
        var_dump($config);
        $this->set_task_time();
        $this->load->model('yx/YxAccount_model', 'yxaccount');
        if (!empty($config['account'])) {
            $ret = $this->yxaccount->find_pwd($config['account']);
            if (!empty($ret)) {
                $this->set_account_info($config['account'], $ret);
                echo '开始收集账号: ' . $this->username . ' 的还款中信息';
                $this->to_login();
                $this->repaying();
                echo '账号: ' . $this->username . ' 的还款中信息收集完成<br/>' . "\r\n";
            }
        } else {
            if (!empty($config['cmd']) && 'all' == $config['cmd']) {
                $account_array = $this->yxaccount->find_all_account();
                echo count($account_array);
                foreach ($account_array as $value) {
                    $ret = $this->yxaccount->find_pwd($value['account']);
                    echo $value['account'] . "\r\n";
                    if (!empty($ret)) {
                        $this->set_account_info(trim($value['account']), $ret);
                        $this->to_login();
                        echo '开始收集账号: ' . $this->username . ' 的还款中信息';
                        $this->repaying();
                        echo '账号: ' . $this->username . ' 的还款中信息收集完成<br/>' . "\r\n";
                        $this->to_logout();
                    }
                    sleep(rand(1, 5));
                    echo '延时降速';
                }
            } else {
                echo '目前关闭 批量操作';
                exit;
            }
            
        }
        echo '操作结束 ' . date('Y-m-d H:i:s') . '<br/>';
        echo "\r\n";exit;
    }

    /**
     * @name 获取指定账号的全部信息或者全部账号的全部信息
     * @other 这里定义的信息只有 账号概况 电子账单 还款中信息
     * @url /reptiles/yx/getalldata
     */
    public function getalldata($cmd = '', $account = '')
    {
        $config = $_GET;
        if (!empty($account) || !empty($cmd)) {
            $config = [
                'account' => $account,
                'cmd' => $cmd,
            ];
        }
        var_dump($config);
        $this->set_task_time();
        $this->load->model('yx/YxAccount_model', 'yxaccount');
        if (!empty($config['account'])) {
            $ret = $this->yxaccount->find_pwd($config['account']);
            if (!empty($ret)) {
                $this->set_account_info($config['account'], $ret);
                $this->to_login();
                echo '开始收集账号: ' . $this->username . ' 的信息';
                $this->informationpage();
                $this->querybill();
                $this->repaying();
                echo '账号: ' . $this->username . ' 的信息收集完成<br/>' . "\r\n";
            }
        } else {
            if (!empty($config['cmd']) && 'all' == $config['cmd']) {
                $account_array = $this->yxaccount->find_all_account();
                echo count($account_array);
                foreach ($account_array as $value) {
                    $ret = $this->yxaccount->find_pwd($value['account']);
                    echo $value['account'] . "\r\n";
                    if (!empty($ret)) {
                        $this->set_account_info(trim($value['account']), $ret);
                        $this->to_login();
                        echo '开始收集账号: ' . $this->username . ' 的信息';
                        $this->repaying();
                        $this->informationpage();
                        $this->querybill();
                        echo '账号: ' . $this->username . ' 的信息收集完成<br/>' . "\r\n";
                        $this->to_logout();
                    }
                    sleep(rand(1, 5));
                    // echo '延时降速';
                }
            } else {
                echo '目前关闭 批量操作';
                exit;
            }
            
        }
        echo '操作结束 ' . date('Y-m-d H:i:s') . '<br/>';
        echo "\r\n";exit;
    }

    /**
     * @name 设置银信账号密码
     */
    public function set_account_info($account, $pwd)
    {
        $this->username = $account;
        $this->pwd = $pwd;
    }

    /**
     * @name test
     * @url /reptiles/yx/test
     */
    public function test()
    {
        $this->client = new GuzzleHttp\Client();
        $this->cookie = new \GuzzleHttp\Cookie\CookieJar();
        $url = "https://www.yinxinsirencaihang.com/doLogin";
    	$map = [];
        if (empty($this->username)) {
            $this->username = $this->def_username;
        }
        if (empty($this->pwd)) {
            $this->pwd = $this->def_pwd;
        }
        
        $map['username'] = $this->username;
        $map['password'] = $this->pwd;
        $map['vcode'] = "";
        $map['hasToke'] = "true";
        $retdata = $this->loginPage($url, $map);
        return $retdata;
    }

    /**
     * @name test
     */
    public function to_login()
    {
        if (!empty($this->username) && !empty($this->pwd)) {
            $this->client = new GuzzleHttp\Client();
            $this->cookie = new \GuzzleHttp\Cookie\CookieJar();
            $url = "https://www.yinxinsirencaihang.com/doLogin";
            $map = [];
            $map['username'] = $this->username;
            $map['password'] = $this->pwd;
            $map['vcode'] = "";
            $map['hasToke'] = "true";
            $retdata = $this->loginPage($url, $map);
            return $retdata;
        } else {
            echo '缺少账号密码 人物停止!!!';exit;
        }
    }


    public function to_logout()
    {
        unset($_SESSION['yxcookie']);
        $url = $this->target_url . $this->url_map['log_out']['url'];
        try {
            $response = $this->getPageInfo($url, [], 'get');
        } catch(Exception $e) {
            echo '银信退出失败, 增加延时';
            sleep(10);
        }
        echo "退出";
    }

    /**
     * 登陆
     * @param $url
     * @param $data
     * @param string $method
     * @return mixed
     */
    function loginPage($url, $data, $method = 'post')
    {
        $response = $this->client->request($method,$url,['cookies' => $this->cookie,'form_params' => $data])->getBody()->getContents();
        $_SESSION['yxcookie'] =  serialize($this->cookie);
        return $response;
    }

    function index()
    {
        $url = 'https://www.yinxinsirencaihang.com/account/queryAccount';
        $response = $this->getPageInfo($url, [], 'get');
        echo $response;
    }

    function account()
    {
    	$url = 'https://www.yinxinsirencaihang.com/account/account';
    	$response = $this->getPageInfo($url, [], 'get');
        echo $response;
    }

    function queryServiceInfo()
    {
    	$url = 'https://www.yinxinsirencaihang.com/account/queryServiceInfo';
    	$response = $this->getPageInfo($url, [], 'get');
        echo $response;
    }

    /**
     * @name 
     * @url /reptiles/yx/queryWalletByUser
     */
    function queryWalletByUser()
    {
    	$url = $this->target_url . '/account/queryWalletByUser';
    	$response = $this->getPageInfo($url, [], 'get');
        echo $response;
    }

    function calendarDetails()
    {
    	$url = 'https://www.yinxinsirencaihang.com/myInvestmentCollect/calendarDetails';
    	$response = $this->getPageInfo($url, [], 'get');
        echo $response;
    }

    function queryReward()
    {
    	$url = 'https://www.yinxinsirencaihang.com/account/queryReward';
    	$response = $this->getPageInfo($url, [], 'get');
        echo $response;
    }

    /**
     * @name 查询电子账单
     */
    function querybill()
    {
        $this->load->model('yx/YxBill_model', 'yxbill');
        $task_time = $this->task_time;
        if ($this->yxbill->record_check($this->username, $task_time)) {
            echo '账号:' . $this->username . '  ' . $task_time . ' 该时段数据已更新 不需要再更新' . "\r\n";
            return false;
        }
        $url = $this->target_url . '/bill/queryBill';
        $cnt = 0;
        for($this->currentPage; $this->currentPage <= $this->totalPage;) {
            $cnt++;
            if ($cnt > 100) {
                echo 'cp: ' . $this->currentPage; 
                echo ' cnt: ' . $cnt;
                echo 'querybill';
                exit;
            }
            $data = [
                'searchType' => 1,
                'subSearchType' => '',
                'startTime' => '',
                'endTime' => '',
                'timeSearchIdx' => '',
                'flowType' => '',
                'state' => '',
                'currentPage' => $this->currentPage,
            ];
            $response = $this->getPageInfo(
                $url, 
                [
                    'cookies' => $this->cookie,
                    'form_params' => $data,
                    'headers' => [
                        'Content-Encoding' => 'gzip'
                    ]
                ], 
                'post'
            );
            $check = $this->checklogin($response, [
                'url' => $url, 
                'data' => [
                    'cookies' => $this->cookie,
                    'form_params' => $data,
                    'headers' => [
                        'Content-Encoding' => 'gzip'
                    ]
                ], 
                'method' => 'post'
            ]);
            if (!$check) {
                return false;
            } elseif (true !== $check) {
               $response = $check; 
            }
            //过滤文档
            $response = preg_replace(['/ /','/\t/','/\n/','/\r/'], '', $response);
            //处理文档
            $first_pos = stripos($response, 'transaction_box', 500);
            if ($first_pos > 0 ) {
                $response = preg_replace('/.*transaction_box/', '', $response);
                $response = preg_replace('/model\/bill.*/', '', $response);
                //分割内容
                //标题区
                $title_temp = preg_replace('/transaction_ul.*/', '', $response);
                $title_temp = preg_replace('/.*<ul>/', '', $title_temp);
                $title_temp = preg_replace('/<\/ul>.*/', '', $title_temp);
                $title_temp = explode('</li>', $title_temp);
                $temp = [];
                foreach ($title_temp as $key => $value) {
                    if (!empty($value)) {
                        $temp[] = preg_replace('/.*>/', '', $value);
                    }
                }
                // echo "<textarea style='width:600px;height:200px;'>".json_encode($temp, JSON_UNESCAPED_UNICODE)."</textarea>";
                $title_temp = $temp;
                //列表内容
                $ul_temp = preg_replace('/<pagerform.*/', '', $response);
                $ul_temp = preg_replace('/<\/ul>/', '', $ul_temp);
                $ul_temp = explode('<ulclass="transaction_ul">', $ul_temp);
                $temp = [];

                foreach ($ul_temp as $key => $value) {
                    if (!empty($value) && preg_match('/title/', $value)) {
                        $temp_item = [];
                        foreach (explode('</li>', $value) as $key => $item) {
                            if (!empty($item)) {
                                $temp_item[] = preg_replace('/.*>/', '', $item);
                            }
                        }
                        $temp[] = $temp_item;
                    }
                }
                // echo "<textarea style='width:600px;height:200px;'>".json_encode($temp, JSON_UNESCAPED_UNICODE)."</textarea>";
                $ul_temp = $temp;
                //页码数
                $page_temp = preg_replace('/.*billForm/', '', $response);
                $page_temp = preg_replace('/\/.*/', '', $page_temp);
                if (strpos($page_temp, '"') == 0) {
                    $page_temp = mb_substr($page_temp, 1);
                }
                $page_temp = explode('"', $page_temp);
                $temp = [];
                foreach ($page_temp as $key => $value) {
                    if (!empty($value)) {
                        $temp[] = preg_replace('/\=/', '', $value); 
                    }
                }
                //找数据
                foreach ($temp as $key => $value) {
                    if ('current' == $value) {
                        if ($this->currentPage != 1 && $this->currentPage == $this->lastcurrentPage) {
                            echo "翻页异常 " . $this->lastcurrentPage . '  ' . $this->currentPage;
                            $check = $this->checklogin($response);
                            if (!$check) {
                                return false;
                            } else {
                                echo "登录态正常";
                                echo "\r\n";
                            }
                            exit;
                        }
                        //当前页数
                        if (!empty($temp[$key+1]) &&intval($temp[$key+1]) > 0) {
                            $this->currentPage = $page = $temp[$key+1];
                        }
                    }
                    if ($this->totalPage == 1 && 'size' == $value && !empty($temp[$key+1])) {
                        //总页数
                        if (intval($temp[$key+1]) > 0) {
                            $this->totalPage = $temp[$key+1];
                        }
                    }
                }
                // echo "<textarea style='width:600px;height:200px;'>".'当前页：'.$this->currentPage . ' 总页数:'.$this->totalPage."</textarea>";
                if ($this->currentPage <= $this->totalPage) {
                    $this->lastcurrentPage = $this->currentPage;
                    $this->currentPage++;
                    echo '翻页';
                }   

                //数据组装
                $new_ul_temp = [];
                foreach ($ul_temp as $value) {
                    $new_ul_temp[] = array_combine($title_temp, $value);
                }
                // var_dump($new_ul_temp);exit;
                //数据落库
                $succ = 0;
                $err = 0;
                foreach ($new_ul_temp as $value) {
                    $ret = $this->yxbill->record_data($value, $this->username, $task_time);
                    if ($ret) {
                        $succ++;
                    } else {
                        $err++;
                    }
                }
                // echo "成功: " . $succ . ' 失败: ' . $err . ' 页码： ' . $this->currentPage ."\r\n";
            } else{
                echo '解析失败 页面有变化 请上报';
            }
        }
        $this->init_page_params();
        return true;
    }

    /**
     * @name 账号设置
     */
    public function informationpage() 
    {
        $type_map = [
            '专属客服',
            '存管专户',
            '实名认证',
            '绑定手机',
            '绑定邮箱',
            '用户名',
            '登陆密码',
            '回款短信',
            '还款短信',
        ];
        $deal_rule = [
            '专属客服' => ['专属客服', 3],
            '存管专户是否开通' => ['存管专户', 3],
            '存管专户账号' => ['存管专户', 2],
            '是否实名认证' => ['实名认证', 3],
            '认证人姓名' => ['实名认证', 2],
            '是否绑定手机号' => ['绑定手机', 4],
            '绑定手机' => ['绑定手机', 3],
            '是否绑定邮箱' => ['绑定邮箱', 3],
            '上次登录时间' => ['登陆密码', 1],
            '回款短信' => ['回款短信', 3],
            '还款短信' => ['还款短信', 3],
        ];
        $url = $this->target_url . '/account/informationPage';
        $response = $this->getPageInfo(
            $url, 
            [
                'cookies' => $this->cookie,
            ], 
            'get'
        );
        $check = $this->checklogin($response, [
            'url' => $url, 
            'data' => [
                'cookies' => $this->cookie,
            ], 
            'method' => 'get'
        ]);
        if (!$check) {
            return false;
        } elseif (true !== $check) {
            $response = $check;
        }
        //过滤文档
        $response = preg_replace(['/ /', '/\t/','/\n/','/\r/'], '', $response);
        //处理文档
        $first_pos = stripos($response, 'article_box_right', 500);
        if ($first_pos > 0 ) {
            $response = preg_replace('/.*rechargePage/', '', $response);
            $response = preg_replace('/script.*/', '', $response);
            $response = preg_replace('/<\/a>/', '', $response);
            $response = explode('</div>', $response);
            // echo "<textarea style='width:600px;height:200px;'>".json_encode($response, JSON_UNESCAPED_UNICODE)."</textarea>";exit;
            foreach ($response as $key => $value) {
                $response[$key] = preg_replace('/.*>/', '', $value);
            }
            //分割数据
            $header = [
                $response[0],
                $response[1],
            ];

            $content = [];
            $content_temp = [];
            for ($i = 2; $i < count($response); $i++) {
                if ((in_array($response[$i], $type_map) && !empty($content_temp)) || $i+1 == count($response)) {
                    $content[] = $content_temp;
                    $content_temp = [];
                }
                $content_temp[] = $response[$i];
            }

            //提取数据
            $deal_array = [];
            foreach ($deal_rule as $key => $value) {
                foreach ($content as $item) {
                    if ($value[0] == $item[0]) {
                        $deal_array[$key] = !empty($item[$value[1]]) ? $item[$value[1]] : '';
                        if (strstr($deal_array[$key], '上次登录时间')) {
                            $deal_array[$key] = trim(preg_replace('/上次登录时间[^0-9]*/', '', $deal_array[$key]));
                            $deal_array[$key] = preg_replace('/\&nbsp\;/', '', $deal_array[$key]);
                            $deal_array[$key] = preg_replace('/ /', '', $deal_array[$key]);
                            $deal_array[$key] = trim($deal_array[$key]);
                            $deal_array[$key] = mb_substr($deal_array[$key], 0, 10) . ' ' .mb_substr($deal_array[$key], 10);
                        }
                    }
                }
            }
            // echo "<textarea style='width:600px;height:200px;'>".json_encode($deal_array, JSON_UNESCAPED_UNICODE)."</textarea>";
            //数据落库
            if (!empty($deal_array) && !empty($this->username)) {
                $this->load->model('yx/YxAccountAbout_model', 'yxaccountabout');
                $ret = $this->yxaccountabout->record_data($deal_array, $this->username);
                if ($ret === false) {
                    echo '更新/插入失败';
                }
            } else {
                echo '参数组合失败: ' . json_encode([$data, $account], JSON_UNESCAPED_UNICODE);
            }
            // exit;
        }
    }

    /**
     * @name 我的借款-还款中
     */
    public function repaying()
    {
        $this->load->model('yx/YxRepaying_model', 'yxrepaying');
        $task_time = $this->task_time;
        if ($this->yxrepaying->record_check($this->username, $task_time)) {
            echo '账号:' . $this->username . '  ' . $task_time . ' 该时段数据已更新 不需要再更新' . "\r\n";
            return false;
        }
        // $this->test();
        $url = $this->target_url . $this->url_map['repaying']['url'];
        $response = $this->getPageInfo(
            $url, 
            [
                'cookies' => $this->cookie,
            ], 
            'get'
        );
        $check = $this->checklogin($response, [
            'url' => $url, 
            'data' => [
                'cookies' => $this->cookie,
            ], 
            'method' => 'get'
        ]);
        if (!$check) {
            return false;
        } elseif (true !== $check) {
            $response = $check;
        }
        //过滤文档
        $response = preg_replace(['/ /','/\t/','/\n/','/\r/'], '', $response);
        //处理文档
        $first_pos = stripos($response, 'myBorrow_tabArea', 500);
        if ($first_pos > 0 ) {
            $response = preg_replace('/myBorrow_tabArea.*/', '', $response);
            $response = preg_replace('/.*myInvestment_tabbox/', '', $response);
            $response = preg_replace('/.*token/', '', $response);
            $response = preg_replace('/\<\/li\>.*/', '', $response);
            $response = preg_replace('/.*myInvestment_box_RepaymentIn_one_table\">/', '', $response);
            $response = preg_replace('/inPaymentForm.*/', '', $response);
            //分割标题
            $title_temp = preg_replace('/<\/tbody>.*/', '', $response);
            $title_temp = explode('</th>', $title_temp);
            $temp = [];
            foreach ($title_temp as $key => $value) {
                if (!empty($value)) {
                    if (!empty(preg_replace('/.*>/', '', $value))) {
                        $temp[] = preg_replace('/.*>/', '', $value);
                    }
                }
            }
            // echo "<textarea style='width:600px;height:200px;'>".json_encode($temp, JSON_UNESCAPED_UNICODE)."</textarea>";
            $title_temp = $temp;

            //列表内容
            $ul_temp = preg_replace('/.*inPayment/', '', $response);
            $ul_temp = explode('</table>', $ul_temp);
            foreach ($ul_temp as $key => $value) {
                if (!strstr($value, 'myInvestment_box_RepaymentIn_one_tableproductRepayIng_ic')) {
                    unset($ul_temp[$key]);
                } else {
                    $value = preg_replace('/.*myInvestment_box_RepaymentIn_one_tableproductRepayIng_ic\"/', '', $value);
                    $value = preg_replace('/<\/tbody>.*/', '', $value);
                    $value = preg_replace('/<\/tr>/', '', $value);
                    $value = preg_replace('/<tr>/', '', $value);
                    $value = preg_replace('/<\/a>/', '', $value);
                    $value = explode('</td>', $value);
                    $ul_temp[$key] = $value;
                }
            }
            $temp = [];
            foreach ($ul_temp as $key => $item) {
                foreach ($item as $value) {
                    if (!empty($value)) {
                        if (null != strstr($value, '借款合同')) {
                            //借款合同
                            $value = preg_replace('/<p.*\/p>/', '', $value);
                            if (null != strstr($value, 'showContract')) {
                                //单一 合同
                                $value = preg_replace('/<\/div>.*/', '', $value);
                                $value = preg_replace('/<.*<a/', '', $value);
                                $value = preg_replace('/.*id\=\"/', '', $value);
                                $value = preg_replace('/\".*/', '', $value);
                                
                                //获取合同内容
                                $response = $this->getPageInfo(
                                    $this->target_url . '/pdf/readHtml?productId=' . $value . '&bidId=' . $value . '&reserve1=1', 
                                    [
                                        'cookies' => $this->cookie,
                                        'headers' => [
                                            'Content-Encoding' => 'gzip'
                                        ]
                                    ], 
                                    'get'
                                );
                                $response = $this->deal_single_contract($response);
                                $value = '借款合同-单一合同-' . $value;
                                $value = json_encode([$value, $response], JSON_UNESCAPED_UNICODE);
                            } else if (null != strstr($value, 'pactShow')) {
                                $value = preg_replace('/<\/div>.*/', '', $value);
                                $value = preg_replace('/<.*<a/', '', $value);
                                $value = preg_replace('/.*pactShow\(\'/', '', $value);
                                $value = preg_replace('/\'\)\;\"class.*/', '', $value);
                                
                                //ajax 请求
                                $ajax_url = $this->target_url . '/contractInfo/getAgreementListByProductId';                       
                                $request = $this->client->request('post',$ajax_url,[
                                        'cookies' => $this->cookie,
                                        'form_params' => [
                                            'productId' => $value,
                                        ],
                                        'headers' => [
                                            'Content-Encoding' => 'gzip',
                                        ]
                                    ]);
                                $response = $request->getBody()->getContents();
                                if (!is_array($response)) {
                                    if (json_decode($response)) {
                                        $response = json_decode($response, true);
                                    }
                                }
                                if (!empty($response['code']) && $response['code'] != '000000') {
                                    //暂无合同数据
                                    $detail = '暂无合同数据';
                                } else { 
                                    $detail = !empty($response['data']) ? $response['data'] : $response;

                                }
                                $value = '借款合同-新版-' . $value;
                                $value = json_encode([$value, $detail], JSON_UNESCAPED_UNICODE);
                            } else {
                                echo '其它版本的合同暂不支持解析。银信账号: ' . $this->username;
                            }
                            $temp[] = $value;
                        } elseif (null != strstr($value, 'title')) {
                            //获取订单详情  落地地址到另一张表里
                            //调用其它方法做二次爬取
                            //获取href
                            $href_temp = preg_replace('/title.*/', '', $value);
                            $href_temp = preg_replace('/.*href=\"/', '', $href_temp);
                            $href_temp = preg_replace('/\".*/', '', $href_temp);
                            $this->deal_project_detail($href_temp);
                            //正常处理内容
                            $temp[] = preg_replace('/.*>/', '', $value);
                        } else {
                            $temp[] = preg_replace('/.*>/', '', $value);
                        }
                    }
                }
                $ul_temp[$key] = $temp;
                $temp = [];
            }
            
            // echo "<textarea style='width:600px;height:200px;'>".json_encode($ul_temp, JSON_UNESCAPED_UNICODE)."</textarea>";exit;
            //数据组装
            $new_ul_temp = [];
            if (!empty($title_temp) && !empty($ul_temp)) {
                foreach ($ul_temp as $key => $value) {
                    if (count($title_temp) == count($value)) {
                        $new_ul_temp[$key] = array_combine($title_temp, $value);
                        //key='操作'  改名 key='合同信息'
                        if (!empty($new_ul_temp[$key]['操作'])) {
                            $new_ul_temp[$key]['合同信息'] = $new_ul_temp[$key]['操作'];
                            unset($new_ul_temp[$key]['操作']); 
                        }
                        //key='借款金额(元)'  改名 key='借款金额'
                        if (!empty($new_ul_temp[$key]['借款金额(元)'])) {
                            $new_ul_temp[$key]['借款金额'] = $new_ul_temp[$key]['借款金额(元)'];
                            unset($new_ul_temp[$key]['借款金额(元)']); 
                        }
                        //key='操作'  改名 key='合同信息'
                        if (!empty($new_ul_temp[$key]['下期还款本息(元)'])) {
                            $new_ul_temp[$key]['下期还款本息'] = $new_ul_temp[$key]['下期还款本息(元)'];
                            unset($new_ul_temp[$key]['下期还款本息(元)']); 
                        }
                        //处理数据格式  处理掉金额的逗号和点
                        foreach ($new_ul_temp[$key] as $value_key => $item) {
                            if ('合同信息' != $value_key) {
                                $item = preg_replace('/\,/', '', $item);
                                // echo $item . ' | ';
                                $new_ul_temp[$key][$value_key] = preg_replace('/\./', '', $item);
                            }
                        }
                    }
                }
            }
            
            if(!empty($new_ul_temp)) {
                foreach ($new_ul_temp as $value) {
                    $ret = $this->yxrepaying->record_data($value, $this->username, $task_time);
                    if ($ret) {
                        echo '成功';
                    } else {
                        echo '失败';
                    }
                }
            }
            // echo "<textarea style='width:600px;height:200px;'>" . $response . "</textarea>";
        } else {
            echo 'end';
        }
        // exit;
    }

    /**
     * @name 处理单一合同页面
     * @other 只提取单一合同下载地址  适用于一笔借款一份合同的情况
     * @param string $contract_html 单一合同html页面
     * @return string 单一合同下载地址
     */
    public function deal_single_contract($contract_html)
    {
        $html = preg_replace(['/ /','/\t/','/\n/','/\r/'], '', $contract_html);
        $html = preg_replace('/cWithUser.*/', '', $html);
        $html = preg_replace('/.*id=\"template\"/', '', $html);

        $response = $html;
        //处理文档
        $first = stripos($response, '>下载<');
        if ($first > 0) {
            //获取下载地址
            $response = preg_replace('/\>下载\<.*/', '', $response);
            $header = strripos($response,'href="');
            $response = mb_substr($response, $header);
            $response = preg_replace('/href\=\"/', '', $response);
            $response = preg_replace('/\".*/', '', $response);
        }
        return $response;
    }

    /**
     * @name 处理订单详情
     * @param $href 订单详情地址
     */
    public function deal_project_detail($href)
    {
        $response = $this->getPageInfo($href,[],'get');
        //过滤文档
        $response = preg_replace(['/ /','/\t/','/\n/','/\r/'], '', $response);

        //处理文档
        $response = preg_replace('/.*particulars_box_left_title\">/', '', $response);
        $response = preg_replace('/class\=\"footer\".*/', '', $response);
        $response = preg_replace('/id\=\"detailEgg\".*/', '', $response);

        //缓存文档
        $document = $response;
        
        //定位数据点
        $response = $document;
        $response = trim($response);
        //借款标题
        $loan_title = mb_substr($response, 0, stripos($response, '<'));
        $loan_title = preg_replace('/\<.*/', '', $loan_title);
        //订单详情 左上区域开始
        //订单标题
        $this->deal_project_detail_part_one($document, $loan_title);
        //订单详情 右上区域结束
        
        $this->deal_project_detail_repaying_plan($document, $loan_title, [$href,[],'post']);
        
        $this->deal_project_detail_loan_log($document, $loan_title, [$href,[],'post']);
        
        $this->deal_project_detail_trans_log($document, $loan_title, [$href,[],'post']);
    }

    /**
     * @name 处理订单详细的上部分区域
     * @param $document html string
     */
    public function deal_project_detail_part_one($document, $loan_title)
    {
        //缓存结果
        $result = [];
        $response = $document;
        //参考年化收益
        $response = preg_replace('/.*参考年化收益\(\%\)/', '', $response);
        $response = preg_replace('/\<\/li\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        if (intval($response) <= 0 && 0 !== $response && '0' !== $response) {
            $response = '未知';
        }
        $result['参考年化收益'] = $response;
        //获得 参考年化收益 $response

        //借款金额
        $response = $document;
        $response = preg_replace('/.*借款金额/', '', $response);
        $response = preg_replace('/\<\/li\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        //处理金额标签
        $response = preg_replace('/\¥/', '', $response);
        $response = preg_replace('/\,/', '', $response);
        $response = preg_replace('/\./', '', $response);
        if (intval($response) <= 0 && 0 !== $response && '0' !== $response) {
            $response = '未知';
        }
        $result['借款金额'] = $response;
        //获得 借款金额 $response

        //借款期限(月)
        $response = $document;
        $response = preg_replace('/.*借款期限\(月\)/', '', $response);
        $response = preg_replace('/\<\/li\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        if (intval($response) <= 0 && 0 !== $response && '0' !== $response) {
            $response = '未知';
        }
        $result['借款期限'] = $response;
        //获得 借款期限 $response

        //进度
        $response = $document;
        $response = preg_replace('/.*进度/', '', $response);
        $response = preg_replace('/\<\/p\>.*/', '', $response);
        $response = preg_replace('/\%/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        if (intval($response) <= 0 && 0 !== $response && '0' !== $response) {
            $response = '未知';
        }
        $result['进度'] = $response;
        //获得 进度 $response

        //最小出借金额
        $response = $document;
        $response = preg_replace('/.*最小出借金额/', '', $response);
        $response = preg_replace('/\<\/font\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        //处理金额标签
        $response = preg_replace('/\,/', '', $response);
        $response = preg_replace('/\./', '', $response);
        if (intval($response) <= 0 && 0 !== $response && '0' !== $response) {
            $response = '未知';
        }
        $result['最小出借金额'] = $response;
        //获得 最小出借金额 $response

        //发布时间
        $response = $document;
        $response = preg_replace('/.*发布时间/', '', $response);
        $response = preg_replace('/\<\/p\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        //补空格
        $response = mb_substr($response, 0, 10) . ' ' . mb_substr($response, 10);
        if (strtotime($response) <= strtotime('2010-01-01')) {
            $response = '未知';
        } 
        $result['发布时间'] = $response;
        //获得 发布时间 $response

        //最大出借金额
        $response = $document;
        $response = preg_replace('/.*最大出借金额/', '', $response);
        $response = preg_replace('/\<\/font\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        //处理金额标签
        $response = preg_replace('/\,/', '', $response);
        $response = preg_replace('/\./', '', $response);
        if (intval($response) <= 0 && 0 !== $response && '0' !== $response) {
            $response = '未知';
        }
        $result['最大出借金额'] = $response;
        //获得 最大出借金额 $response

        //出借人服务费率
        $response = $document;
        $response = preg_replace('/.*出借人服务费率/', '', $response);
        $response = preg_replace('/\<\/p\>.*/', '', $response);
        $response = preg_replace('/\%/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        if (intval($response) <= 0 && 0 !== $response && '0' !== $response) {
            $response = '未知';
        }
        $result['出借人服务费率'] = $response;
        //获得 出借人服务费率 $response

        //还款方式
        $response = $document;
        $response = preg_replace('/.*还款方式/', '', $response);
        $response = preg_replace('/\<\/p\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        $result['还款方式'] = $response;
        //获得 还款方式 $response

        //计息方式
        $response = $document;
        $response = preg_replace('/.*计息方式/', '', $response);
        $response = preg_replace('/\<\/p\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        $result['计息方式'] = $response;
        //获得 计息方式 $response

        //剩余时间
        $response = $document;
        $response = preg_replace('/.*剩余时间\<\/label\>/', '', $response);
        $response = preg_replace('/\<\/label\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        $result['剩余时间'] = $response;
        //获得 剩余时间 $response
        //订单详情 左上区域结束

        //订单详情 右上区域开始
        //实际招标金额
        $document = preg_replace('/.*projectdetail_box_right_center\">/', '', $document);
        $response = $document;
        $response = preg_replace('/.*实际招标金额\：/', '', $response);
        $response = preg_replace('/\<\/font\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        //处理金额标签
        $response = preg_replace('/\¥/', '', $response);
        $response = preg_replace('/\,/', '', $response);
        $response = preg_replace('/\./', '', $response);
        if (intval($response) <= 0 && 0 !== $response && '0' !== $response) {
            $response = '未知';
        }
        $result['实际招标金额'] = $response;
        //获得 实际招标金额 $response

        //满标时间
        $response = $document;
        $response = preg_replace('/.*class\=\"projectdetail_tou_p\"\>满标时间\：/', '', $response);
        $response = preg_replace('/\<\/p\>.*/', '', $response);
        $response = trim(strip_tags($response));//去除html标签
        //补空格
        $response = mb_substr($response, 0, 10) . ' ' . mb_substr($response, 10);
        // echo $response;
        if (strtotime($response) <= strtotime('2010-01-01')) {
            $response = '未知';
        } 
        $result['满标时间'] = $response;
        //获得 满标时间 $response
        //数据落库
        $this->load->model('yx/YxRepayingOrderDetail_model', 'yxrepayingorderdetail');
        $this->yxrepayingorderdetail->record_data($result, $this->username, $loan_title);
    }

    /**
     * @name 处理订单详情的下部分区域的还款计划
     */
    public function deal_project_detail_repaying_plan($document, $loan_title, $request_array)
    {
        $cnt = 0;
        for($this->currentPage; $this->currentPage <= $this->totalPage;) {
            $cnt++;
            if ($cnt > 100) {
                echo 'cp: ' . $this->currentPage; 
                echo ' cnt: ' . $cnt;
                echo 'deal_project_detail_repaying_plan';
                exit;
            }
            //第一次进来 html string 是截取好的 就不用去请求页面了
            if (1 != $this->currentPage) {
                $response = $this->getPageInfo(
                    $request_array[0], 
                    [
                        'cookies' => $this->cookie,
                        'form_params' => [
                            'currentPage' => $this->currentPage,
                        ],
                        'headers' => [
                            'Content-Encoding' => 'gzip'
                        ]
                    ], 
                    $request_array[2]
                );
                //过滤文档
                $response = preg_replace(['/ /','/\t/','/\n/','/\r/'], '', $response);
                $document = $response;
            }
            //订单详情 左下区域还款计划开始
            $document = preg_replace('/.*reimbursementTabArea\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '<tr>');
            $response = mb_substr($response, $temp_pos);
            $response = preg_replace('/\<\/tr\>.*/', '', $response);
            $title_temp = explode('</th>', $response);
            $temp = [];
            foreach ($title_temp as $key => $value) {
                if (!empty($value)) {
                    $temp[] = preg_replace('/.*>/', '', $value);
                }
            }
            $title_temp = $temp;

            //列表内容
            $document = preg_replace('/.*particulars_box_menu_yy\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '<tr>');
            $temp_pos_1 = stripos($response, '</tbody>');
            $response = mb_substr($response, $temp_pos, ($temp_pos_1 - $temp_pos));
            $tr_array = explode('</tr>', $response);
            $temp = [];
            //获取位置信息
            $pos_arr = [];
            foreach ($title_temp as $key => $value) {
                if (in_array($value, ['应还本息(元)', '本金(元)', '利息(元)'])) {
                    $pos_arr[] = $key;
                }
            }
            foreach ($tr_array as $key => $value) {
                if (count(explode('</td>', $value)) > 1) {
                    $temp[$key] = [];
                    foreach (explode('</td>', $value) as $td_key => $item) {
                        if ('' != preg_replace('/.*\>/', '', $item)) {
                            $item = preg_replace('/\,/', '', $item);
                            $item = preg_replace('/.*\>/', '', $item);
                            //对于整数要做特殊处理
                            if (in_array($td_key, $pos_arr)) {
                                if ($item > 0 && !stripos($item, '.')) {
                                    $item = $item * 100;
                                }
                            }
                            $item = preg_replace('/\./', '', $item);
                            $temp[$key][] = $item;
                        }
                    }
                }
            }
            $data = [];
            foreach ($temp as $value) {
                $data[] = $this->deal_array_combine($title_temp, $value, [
                    '应还本息(元)' => '应还本息',
                    '本金(元)' => '本金',
                    '利息(元)' => '利息',
                ]);
            }
            
            //数据落库
            $this->load->model('yx/YxRepayingPlan_model', 'yxrepayingplan');
            foreach ($data as $value) {
                $this->yxrepayingplan->record_data($value, $this->username, $loan_title);
            }
            
            //页码数
            $document = preg_replace('/.*formId\=\"reimbursementTabForm\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '/>');
            $response = mb_substr($response, 0, $temp_pos);
            $this->deal_page_position($response, '还款计划');
        }
        $this->init_page_params();
        
    }

    /**
     * @name 处理订单详情的下部分区域的出借记录
     */
    public function deal_project_detail_loan_log($document, $loan_title, $request_array)
    {
        $cnt = 0;
        for($this->currentPage; $this->currentPage <= $this->totalPage;) {
            $cnt++;
            if ($cnt > 100) {
                echo 'cp: ' . $this->currentPage; 
                echo ' cnt: ' . $cnt;exit;
            }
            //第一次进来 html string 是截取好的 就不用去请求页面了
            if (1 != $this->currentPage) {
                $response = $this->getPageInfo(
                    $request_array[0], 
                    [
                        'cookies' => $this->cookie,
                        'form_params' => [
                            'currentPage' => $this->currentPage,
                        ],
                        'headers' => [
                            'Content-Encoding' => 'gzip'
                        ]
                    ], 
                    $request_array[2]
                );
                //过滤文档
                $response = preg_replace(['/ /','/\t/','/\n/','/\r/'], '', $response);
                $document = $response;
            }

            //订单详情 左下区域出借记录 开始
            $document = preg_replace('/.*bidRecordTabArea\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '<tr>');
            $response = mb_substr($response, $temp_pos);
            $response = preg_replace('/\<\/tr\>.*/', '', $response);
            $title_temp = explode('</th>', $response);
            $temp = [];
            foreach ($title_temp as $key => $value) {
                if (!empty($value)) {
                    $temp[] = preg_replace('/.*>/', '', $value);
                }
            }
            $title_temp = $temp;

            //列表内容
            $document = preg_replace('/.*particulars_box_menu_five\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '<tr>');
            $temp_pos_1 = stripos($response, '</tbody>');
            $response = mb_substr($response, $temp_pos, ($temp_pos_1 - $temp_pos));
            $tr_array = explode('</tr>', $response);
            $temp = [];
            foreach ($tr_array as $key => $value) {
                if (count(explode('</td>', $value)) > 1) {
                    $temp[$key] = [];
                    foreach (explode('</td>', $value) as $item) {
                        if ('' != preg_replace('/.*\>/', '', $item)) {
                            $item = preg_replace('/\,/', '', $item);
                            $item = preg_replace('/\./', '', $item);
                            if (strstr($item, '-') && strstr($item, ':')) {
                                $date_string = preg_replace('/.*\>/', '', $item);
                                //补空格
                                $date_string = mb_substr($date_string, 0, 10) . ' ' . mb_substr($date_string, 10);
                                $temp[$key][] = $date_string;
                            } else {
                                $temp[$key][] = preg_replace('/.*\>/', '', $item);
                            }
                        }
                    }
                }
            }

            $data = [];
            foreach ($temp as $value) {
                $data[] = $this->deal_array_combine($title_temp, $value, [
                    '出借金额(元)' => '出借金额',
                ]);
            }

            //数据落库
            $this->load->model('yx/YxRepayingLoanLog_model', 'yxrepayingloanlog');
            foreach ($data as $value) {
                $this->yxrepayingloanlog->record_data($value, $this->username, $loan_title);
            }

            //页码数
            $document = preg_replace('/.*formId\=\"bidRecordTabForm\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '/>');
            $response = mb_substr($response, 0, $temp_pos);
            
            $this->deal_page_position($response, '出借记录');
            echo $this->currentPage . ' ';
            echo $this->totalPage . ' ';
        }
        $this->init_page_params();
    }

    /**
     * @name 处理订单详情的下部分区域的转让记录
     */
    public function deal_project_detail_trans_log($document, $loan_title, $request_array)
    {
        $cnt = 0;
        for($this->currentPage; $this->currentPage <= $this->totalPage;) {
            $cnt++;
            if ($cnt > 100) {
                echo 'cp: ' . $this->currentPage; 
                echo ' cnt: ' . $cnt;exit;
            }
            //第一次进来 html string 是截取好的 就不用去请求页面了
            if (1 != $this->currentPage) {
                $response = $this->getPageInfo(
                    $request_array[0], 
                    [
                        'cookies' => $this->cookie,
                        'form_params' => [
                            'currentPage' => $this->currentPage,
                        ],
                        'headers' => [
                            'Content-Encoding' => 'gzip'
                        ]
                    ], 
                    $request_array[2]
                );
                //过滤文档
                $response = preg_replace(['/ /','/\t/','/\n/','/\r/'], '', $response);
                $document = $response;
            }
            //订单详情 左下区域转让记录 开始
            $document = preg_replace('/.*transTabArea\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '<tr>');
            $response = mb_substr($response, $temp_pos);
            $response = preg_replace('/\<\/tr\>.*/', '', $response);
            $title_temp = explode('</th>', $response);
            $temp = [];
            foreach ($title_temp as $key => $value) {
                if (!empty($value)) {
                    $temp[] = preg_replace('/.*>/', '', $value);
                }
            }
            $title_temp = $temp;

            //列表内容
            $document = preg_replace('/.*particulars_box_menu_five_assignment\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '<tr>');
            $temp_pos_1 = stripos($response, '</tbody>');
            $response = mb_substr($response, $temp_pos, ($temp_pos_1 - $temp_pos));
            $tr_array = explode('</tr>', $response);
            $temp = [];
            foreach ($tr_array as $key => $value) {
                if (count(explode('</td>', $value)) > 1) {
                    $temp[$key] = [];
                    foreach (explode('</td>', $value) as $item) {
                        if ('' != preg_replace('/.*\>/', '', $item)) {
                            $item = preg_replace('/\,/', '', $item);
                            $item = preg_replace('/\./', '', $item);
                            if (strstr($item, '-') && strstr($item, ':')) {
                                $date_string = preg_replace('/.*\>/', '', $item);
                                //补空格
                                $date_string = mb_substr($date_string, 0, 10) . ' ' . mb_substr($date_string, 10);
                                $temp[$key][] = $date_string;
                            } else {
                                $temp[$key][] = preg_replace('/.*\>/', '', $item);
                            }
                        }
                    }
                }
            }

            $data = [];
            foreach ($temp as $value) {
                $data[] = $this->deal_array_combine($title_temp, $value, [
                    '交易金额(元)' => '交易金额',
                ]);
            }

            //数据落库
            $this->load->model('yx/YxRepayingTransLog_model', 'yxrepayingtranslog');
            foreach ($data as $value) {
                $this->yxrepayingtranslog->record_data($value, $this->username, $loan_title);
            }

            //页码数
            $document = preg_replace('/.*formId\=\"transTabForm\"/', '', $document);
            $response = $document;
            $temp_pos = stripos($response, '/>');
            $response = mb_substr($response, 0, $temp_pos);
            $this->deal_page_position($response, '转让记录');
        }
        $this->init_page_params();
    }

    /**
     * @param $url
     * @param $data
     * @param string $method
     * @return mixed
     */
    function getPageInfo($url,$data = [],$method='post')
    {
        $response = $this->client->request($method,$url,$data)->getBody()->getContents();
        return $response;
    }

    /**
     * @name 获取所在页码数判断是否需要翻页
     * @param string $page_html 截获到的页码信息html
     */
    public function deal_page_position($page_html, $type)
    {
        $page_temp = $page_html;
        $page_temp = explode('"', $page_temp);
        $temp = [];
        foreach ($page_temp as $key => $value) {
            if (!empty($value)) {
                $temp[] = preg_replace('/\=/', '', $value); 
            }
        }

        //找数据
        foreach ($temp as $key => $value) {
            if ('current' == $value) {
                //当前页数
                if (!empty($temp[$key+1]) &&intval($temp[$key+1]) > 0) {
                    if ($this->currentPage != $temp[$key+1]) {
                        echo $type . "翻页异常 " . $this->lastcurrentPage . '  ' . $this->currentPage;
                        exit;
                    }
                    $this->currentPage = $temp[$key+1];
                }
            }
            if ($this->totalPage == 1 && 'size' == $value && !empty($temp[$key+1])) {
                //总页数
                if (intval($temp[$key+1]) > 0) {
                    $this->totalPage = $temp[$key+1];
                }
            }
        }
        if ($this->currentPage <= $this->totalPage) {
            $this->lastcurrentPage = $this->currentPage;
            $this->currentPage++;
            echo $type . '翻页';
        } 
    }

    /**
     * @name 初始化两个页面参数
     */
    public function init_page_params()
    {
        $this->currentPage = 1;
        $this->totalPage = 1;
    }

    /**
     * @name 合并列表数据
     */
    public function deal_array_combine($title_temp, $ul_temp, $rename_keys = [])
    {
        //key改名
        foreach ($title_temp as $key => $value) {
            if (isset($rename_keys[$value])) {
                $title_temp[$key] = $rename_keys[$value];
            }
        }
        //数据组装
        $new_ul_temp = [];
        if (!empty($title_temp) && !empty($ul_temp) && count($title_temp) == count($ul_temp)) {
            $new_ul_temp = array_combine($title_temp, $ul_temp);
        }
        return $new_ul_temp;
    }

    /**
     * @name 检查登录态
     * @param $content 获得的页面内容
     * @param $request_array 页面的请求参数
     * @return true|新的页面内容
     */
    function checklogin($content, $request_array = [])
    {
        if (empty($this->username)) {
            $this->to_login();
        }
        if (!preg_match('/'.$this->username.'/', $content)) {
            echo '登录失败 1次';
            unset($_SESSION['yxcookie']);
            sleep(3);
            $one = $this->to_login();
            if (!preg_match('/成功/', $one)) {
                unset($_SESSION['yxcookie']);
                sleep(3);
                $second = $this->to_login();
                if (!preg_match('/成功/', $one)) {
                    echo '登录多次失败 失败账号：' . $this->username . '失败原因：账号密码错误，或者是登录过于频繁，清稍后再试或手动启动。如果手动启动失败说明是账号密码的问题';
                    $this->load->model('yx/YxAccount_model', 'yxaccount_model');
                    $this->yxaccount_model->mark_pwd_error($this->username);
                    return false;
                }
            }
        }
        if ($request_array) {
            //重新请求
            echo '重新请求';
            return $this->getPageInfo($request_array['url'],$request_array['data'],$request_array['method']);
        }
        return true;
    }



    // 奚晓俊 开始====================
        /**
         * @name 获取指定账号的可用余额、总余额、银信宝、冻结金额
         * @url /account/account
         * $cmd '' or 'all' 批量
         * $account 银信账号
         */
        public function getAccountHaveMoney($cmd = '', $account = ''){
            $config = $_GET;

            if (!empty($account) || !empty($cmd)) {
                $config = [
                    'account' => $account,
                    'cmd' => $cmd,
                ];
            }
            // var_dump($config);

            $task_time = date('Y-m-d H:i:s');

            $this->load->model('yx/YxAccount_model', 'yxaccount');
            if (!empty($config['account'])) {
                $ret = $this->yxaccount->find_pwd($config['account']);
                if (!empty($ret)) {
                    $this->set_account_info($config['account'], $ret);
                    echo '开始收集账号: ' . $this->username . ' 的余额';
                    $this->to_login();
                    $this->parseAccountHaveMoney($task_time,$config['account']);
                }
            } else {
                if (!empty($config['cmd']) && 'all' == $config['cmd']) {
                    $account_array = $this->yxaccount->find_all_account();
                    echo count($account_array);
                    foreach ($account_array as $value) {
                        $ret = $this->yxaccount->find_pwd($value['account']);
                        echo $value['account'] . "\r\n";
                        if (!empty($ret)) {
                            $this->set_account_info(trim($value['account']), $ret);
                            $this->to_login();
                            echo '开始收集账号: ' . $this->username . ' 的余额';
                            $this->parseAccountHaveMoney($task_time,$value['account']);
                            echo '账号: ' . $this->username . ' 的余额收集完成<br/>' . "\r\n";
                            $this->to_logout();
                        }
                        sleep(rand(5, 10));
                        echo '延时降速';
                    }
                } else {
                    echo '目前关闭 批量操作';
                    exit;
                }
            }
            echo '操作结束 ' . date('Y-m-d H:i:s') . '<br/>';
            echo "\r\n";exit;
        }

        //解析余额
        private function parseAccountHaveMoney($task_time,$account){
            $url = $this->target_url . '/account/account';
            $response = $this->getPageInfo(
                $url, 
                [
                    'cookies' => $this->cookie,
                    //'form_params' => $data,
                    'headers' => [
                        'Content-Encoding' => 'gzip'
                    ]
                ], 
                'post'
            );
            $arr = json_decode($response,true);
            $data = [
                'account' => &$account,
                'create_time' => date('Y-m-d H:i:s'),
                'acctBal' => &$arr['account']['acctBal'],
                'acctAmount' => &$arr['account']['acctAmount'],
                'frozBl' => &$arr['account']['frozBl'],
                'task_time' => &$task_time,
            ];

            $this->load->model('yx/YxAccountHaveMoney_model', 'yahm');
            $r = $this->yahm->replace_data($data);
        }

        /**
         * @name 获取指定账号是否问卷已做
         * @url /risk/assessment
         * $cmd '' or 'all' 批量
         * $account 银信账号
         */
        public function getAccountAssessment($cmd = '', $account = ''){
            $config = $_GET;

            if (!empty($account) || !empty($cmd)) {
                $config = [
                    'account' => $account,
                    'cmd' => $cmd,
                ];
            }
            // var_dump($config);

            $task_time = date('Y-m-d H:i:s');

            $this->load->model('yx/YxAccount_model', 'yxaccount');
            if (!empty($config['account'])) {
                $ret = $this->yxaccount->find_pwd($config['account']);
                if (!empty($ret)) {
                    $this->set_account_info($config['account'], $ret);
                    echo '开始收集账号: ' . $this->username . ' 是否已做问卷';
                    $this->to_login();
                    $this->parseAccountAssessment($task_time,$config['account']);
                }
            } else {
                if (!empty($config['cmd']) && 'all' == $config['cmd']) {
                    $account_array = $this->yxaccount->find_all_account();
                    echo count($account_array);
                    foreach ($account_array as $value) {
                        $ret = $this->yxaccount->find_pwd($value['account']);
                        echo $value['account'] . "\r\n";
                        if (!empty($ret)) {
                            $this->set_account_info(trim($value['account']), $ret);
                            $this->to_login();
                            echo '开始收集账号: ' . $this->username . ' 是否已做问卷';
                            $this->parseAccountAssessment($task_time,$value['account']);
                            echo '账号: ' . $this->username . ' 的是否已做问卷收集完成<br/>' . "\r\n";
                            $this->to_logout();
                        }
                        sleep(rand(5, 10));
                        echo '延时降速';
                    }
                } else {
                    echo '目前关闭 批量操作';
                    exit;
                }
            }
            echo '操作结束 ' . date('Y-m-d H:i:s') . '<br/>';
            echo "\r\n";exit;
        }

        //解析问卷
        private function parseAccountAssessment($task_time,$account){
            $url = $this->target_url . '/risk/assessment';
            $response = $this->getPageInfo(
                $url, 
                [
                    'cookies' => $this->cookie,
                    //'form_params' => $data,
                    'headers' => [
                        'Content-Encoding' => 'gzip'
                    ]
                ], 
                'post'
            );

            if(mb_strpos($response,'测评结果')!==false){
                $if_assessment = 1;//已做评测
            }else{
                $if_assessment = 0;//未做评测
            }

            $data = [
                'if_assessment' => &$if_assessment,
                'task_time' => &$task_time,
            ];

            $where = ['account' => &$account];

            $this->load->model('yx/YxAccountHaveMoney_model','yahm');
            $r = $this->yahm->update_if_assessment($data,$where);
        }
    // 奚晓俊 结束====================

}

?>

