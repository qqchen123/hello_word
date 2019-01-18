<?php 

/**
 * @desc 银信数据接口
 */
class Yxdata extends Admin_Controller {

    private $myexcel;


	public function __construct() 
	{
		parent::__construct();
		// //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);
	}

    /**
     * @name 接收数据 将数据放入mongo yxdata库 data_temp 表中
     * @data_input post
     */
	public function send_data()
	{
        if (!empty($_POST)) {
            $type = $this->input->post('type', true);
            $data = $this->input->post('data', true);
        } else {
            $body = @file_get_contents('php://input');
            Slog::log($body);
            $body = json_decode($body);
            Slog::log($body->type);
            Slog::log(1);
            $type = !empty($body->type) ? $body->type : '';
            $data = !empty($body->data) ? $body->data : '';
        }
        if (!empty($type) && !empty($data)) {
            $this->load->library('mongo_manager', ['table_name' => 'data_temp', 'key' => 'yxdata']);
            $res = $this->mongo_manager->insert(['data' => $data, 'type' => $type, 'ctime' => date('Y-m-d H:i:s', time())]);
            if ($res) {
                $code = 0;
                $message = '数据保存成功';
            } else {
                $code = -2;
                $message = '数据保存失败';
            }
        } else {
            $code = -1;
            $message = '接收到的内容为: ' . json_encode([$type,$data]) . ' 请检查数据传输方式是否为post';
        }
        echo json_encode(['code' => $code, 'message' => $message], JSON_UNESCAPED_UNICODE);
	}

    public function testfind()
    {
        $this->load->service('business/YxDataDeal_service', 'yxdatadeal_service');
        $this->yxdatadeal_service->deal_rw_collect();
    }

/****************************银信账户基本信息************************************/

    /**
     * @url /api/yxdata/getdata
     */
    public function getData()
    {
        $this->load->model('user/User_model', 'user_model');
        $ret = $this->user_model->db->query('SELECT a.fuserid as fuserid,account,reg_phone,reg_time,user_name,a.idnumber as idnumber,binding_phone,pwd,channel FROM fms_yx_account a,fms_user b WHERE account = yx_account ORDER BY a.id DESC  LIMIT 100 OFFSET 300')->result_array();
        // echo json_encode($ret, JSON_UNESCAPED_UNICODE);
        return json_encode($ret, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @url /api/yxdata/testpost
     */
    public function testpost()
    {
        $path = 'http://120.26.89.131:60523/fms/index.php/dbms/deal_post_data';
        $data = json_decode($this->getData(), true);
        foreach ($data as $value) {
            $value = [
                'db_name' => 'yxtest',
                'table_name' => '账号信息表_主表',
                'key' => [
                    '银信账号' => $value['account'],
                ],
                'data' => [
                    '银信账号' => $value['account'],
                    '银信注册时间' => $value['reg_time'],
                    '银信开户手机号' => $value['reg_phone'],
                    '银信绑定手机号' => $value['binding_phone'],
                    '首次申请手机号' => preg_replace('/YX/', '', $value['account']),
                    '客户ID' => $value['fuserid'],
                    '渠道编号' => $value['channel'],
                    '客户姓名' => $value['user_name'],
                    '身份证号' => $value['idnumber'],
                    '判断的银信账号' => $value['account'],
                    '跟踪的银信账号' => $value['account'],
                    '所属银信账号' => $value['account'],
                ],
                'repid' => 1,
            ];
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            var_dump($value);
            $ret = $this->curl_post(['data' => $value], $path);
            var_dump($ret);
            exit;
        }
        
    }

    public function sendData()
    {
        $data = json_decode($this->getData(), true);
        // http://139.224.113.2/middle/index.php/middle/index
        $path = 'http://139.224.113.2/middle/index.php/middle/update_data_db';
        $query_path = 'http://139.224.113.2/middle/index.php/middle/index_db';
        $cnt = 0;
        $forcnt = 0;
        foreach ($data as $value) {
            $temp = [
                $value['account'],
                $value['reg_time'],
                $value['reg_phone'],
                $value['binding_phone'],
                preg_replace('/YX/', '', $value['account']),
                $value['fuserid'],
                $value['channel'],
                $value['user_name'],
                $value['idnumber'],
                $value['account'],
                $value['account'],
                $value['account'],
            ];
            $sql = 'SELECT F105 FROM tabDIYTable5 WHERE F105 = \'' . $value['account'] . '\'';
            $ret = $this->curl_post(['sql' => $sql, 'db' => 'yxtest'], $query_path);
            if (empty($ret)) {
                $sql = 'INSERT INTO tabDIYTable5 (F105,F106,F107,F108,F109,F121,F123,F124,F125,F131,F134,F139,ID,iOrd) VALUES (\''. implode('\',\'', $temp) .'\',(SELECT MAX(ID)+1 as ID FROM tabDIYTable5), 1)';
            } else {
                $sql = 'UPDATE tabDIYTable5 SET F106 = \''. $value['reg_time'] .'\', F107 = \''. $value['reg_phone'] .'\' ,F108 = \''. $value['binding_phone'] .'\', F109 = \''. preg_replace('/YX/', '', $value['account']) .'\',F121 = \''. $value['fuserid'] .'\',F123 = \''. $value['channel'] .'\',F124 = \''. $value['user_name'] .'\',F125 = \''. $value['idnumber'] .'\',F131 = \''. $value['account'] .'\',F134 = \''. $value['account'] .'\',F139 = \''. $value['account'] .'\' WHERE F105 = \''.$value['account'].'\'';
            }
            $ret = $this->curl_post(['sql' => $sql, 'db' => 'yxtest'], $path);
            $cnt += $ret;
            $forcnt ++;
        }
        echo '成功： ' . $cnt . '条;总共操作: ' . $forcnt . '次';
        exit;
    }


/***************************借款人信息******************************************/
    /**
     * @url /api/yxdata/getblist
     * @other borrower_investor_list
     */
    public function getblist()
    {
        $this->load->library('mongo_manager', ['table_name' => 'data_temp', 'key' => 'yxdata']);
        $ret = $this->mongo_manager->select(['data','type','ctime'])
        ->where(['type' => 'borrower_investor_list', 'ctime' => '2018-10-20 22:53:28'])
        // ->order_by(['ctime'=>1])
        ->find();
        // echo json_encode($ret, JSON_UNESCAPED_UNICODE);exit;
        return json_encode($ret, JSON_UNESCAPED_UNICODE);
    }
    
    public function sendblist()
    {
        $data = json_decode($this->getblist(), true);
        // http://139.224.113.2/middle/index.php/middle/index
        $path = 'http://139.224.113.2/middle/index.php/middle/update_data_db';
        $query_path = 'http://139.224.113.2/middle/index.php/middle/index_db';
        $cnt = 0;
        $forcnt = 0;
        foreach ($data as $item) {
            foreach ($item['data'] as $value) {
                $temp = [
                    $value['登录用户名'],
                    $value['属性'],
                    $value['被推荐人用户名'],
                    $value['推荐关系'],
                    $value['注册时间'],
                    $value['被推荐人金额'],
                    $value['详情'],
                ];
                $sql = 'SELECT F171 FROM tabDIYTable9 WHERE F171 = \'' . $value['被推荐人用户名'] . '\'';
                $ret = $this->curl_post(['sql' => $sql, 'db' => 'yxtest'], $query_path);
                if (empty($ret)) {
                    $sql = 'INSERT INTO tabDIYTable9 (F169,F170,F171,F172,F173,F174,F175,ID,iOrd,RepID,bIsFinish) VALUES (\''. implode('\',\'', $temp) .'\',(SELECT MAX(ID)+1 as ID FROM tabDIYTable9), 1,9,1)';
                } else {
                    $sql = 'UPDATE tabDIYTable9 SET F169 = \''. $value['登录用户名'] .'\' ,F170 = \''. $value['属性'] .'\', F172 = \''. $value['推荐关系'] .'\',F173 = \''. $value['注册时间'] .'\',F174 = \''. $value['被推荐人金额'] .'\',F175 = \''. $value['详情'] .'\' WHERE F171 = \''.$value['被推荐人用户名'].'\'';
                }
                // echo $sql;
                $ret = $this->curl_post(['sql' => $sql, 'db' => 'yxtest'], $path);
                $cnt += $ret;
                $forcnt ++;
            }
        }
        echo '成功： ' . $cnt . '条;总共操作: ' . $forcnt . '次';
        exit;
    }

/***********************借款人详情 borrower_detail*******************************************/
    /**
     * @url /api/yxdata/getbdetail
     * @other borrower_detail
     */
    public function getbdetail()
    {
        $this->load->library('mongo_manager', ['table_name' => 'data_temp', 'key' => 'yxdata']);
        $ret = $this->mongo_manager->select(['data','type','ctime'])
        ->where(['type' => 'borrower_detail', 'ctime' => '2018-10-20 22:54:31'])
        // ->order_by(['ctime'=>1])
        ->find();
        // echo json_encode($ret, JSON_UNESCAPED_UNICODE);exit;
        return json_encode($ret, JSON_UNESCAPED_UNICODE);
    }
    
    public function sendbdetail()
    {
        $data = json_decode($this->getblist(), true);
        // http://139.224.113.2/middle/index.php/middle/index
        $path = 'http://139.224.113.2/middle/index.php/middle/update_data_db';
        $query_path = 'http://139.224.113.2/middle/index.php/middle/index_db';
        $cnt = 0;
        $forcnt = 0;
        foreach ($data as $item) {
            foreach ($item['data'] as $value) {
                $temp = [
                    $value['被推荐人用户名'],
                    $value['属性'],
                    $value['借款标题'],
                    $value['真实姓名'],
                    $value['身份证号'],
                    $value['手机号'],
                    $value['借款金额'],
                    $value['借款期限'],
                    $value['借款利率'],
                    $value['还款方式'],
                    $value['借款时间'],
                    $value['放款时间'],
                ];
                $sql = 'SELECT F171 FROM tabDIYTable9 WHERE F171 = \'' . $value['被推荐人用户名'] . '\'';
                $ret = $this->curl_post(['sql' => $sql, 'db' => 'yxtest'], $query_path);
                if (empty($ret)) {
                    $sql = 'INSERT INTO tabDIYTable9 (F169,F170,F171,F172,F173,F174,F175,ID,iOrd,RepID,bIsFinish) VALUES (\''. implode('\',\'', $temp) .'\',(SELECT MAX(ID)+1 as ID FROM tabDIYTable9), 1,9,1)';
                } else {
                    $sql = 'UPDATE tabDIYTable9 SET F169 = \''. $value['登录用户名'] .'\' ,F170 = \''. $value['属性'] .'\', F172 = \''. $value['推荐关系'] .'\',F173 = \''. $value['注册时间'] .'\',F174 = \''. $value['被推荐人金额'] .'\',F175 = \''. $value['详情'] .'\' WHERE F171 = \''.$value['被推荐人用户名'].'\'';
                }
                // echo $sql;
                $ret = $this->curl_post(['sql' => $sql, 'db' => 'yxtest'], $path);
                $cnt += $ret;
                $forcnt ++;
            }
        }
        echo '成功： ' . $cnt . '条;总共操作: ' . $forcnt . '次';
        exit;
    }

    /**
     * @name 手动部分用户的信息
     */
    public function update_userinfo()
    {
        
    }

/************************推送数据********************************/

    private $pack_fuserinfo = [
        'repid' => 206,
        'db_name' => 'judoo1',
        'table_name' => '客户基础资料_主表',
        'key' => ['客户编号' => 'fuserid'],
        'data' => [
            '客户编号' => 'fuserid',
            '姓名' => 'name',
            '身份证号' => 'idnumber',
            '渠道编号' => 'channel',
            // '渠道名称' => '',
            // '常用手机号' => $item['login_name'],
            // '民族' => '',
            // '年龄' => '',
            // '出身年月日' => '',
            // '性别' => '',
            // '出生地' => '',
            // '公安机关' => '',
            // '有效期开始时间' => '',
            // '有效期结束时间' => '',
            // '有效期剩余时间' => '',
            // '身份证是否有效' => '',
            // '身份证详细地址' => '',
            // '客户经理' => '',
            // '身份证正面' => '',
            // '身份证反面' => '',
        ]
    ];

    private $pack_rw_collect = [
        'repid' => 5,
        'db_name' => 'yxtest',
        'table_name' => '充值提现汇总_主表',
        'key' => ['流水号' => '流水号'],
        'data' => [
            '流水号' => '流水号',
            '登录用户名' => '登录用户名',
            '属性' => '属性',
            '时间' => '时间',
            '金额' => '金额',
            '状态' => '状态',
        ]
    ];

    private $pack_loan_nofinish = [
        'repid' => 7,
        'db_name' => 'yxtest',
        'table_name' => '借款信息未完结_主表',
        'key' => ['借款标题' => '借款标题'],
        'data' => [
            '借款标题' => '借款标题',
            '登录用户名' => '登录用户名',
            '网址' => '网址',
            '满标时间' => '满标时间',
            '借款金额(元)' => '借款金额(元)',
            '状态' => '状态',
            '期数' => '期数',
            '下期还款本息(元)' => '下期还款本息(元)',
            '下期还款日' => '下期还款日',
            '还款方式' => '还款方式',
            '操作' => '操作',
        ]
    ];

    private $pack_loan_finish = [
        'repid' => 11,
        'db_name' => 'yxtest',
        'table_name' => '借款信息_已完结_主表',
        'key' => ['借款标题' => '借款标题'],
        'data' => [
            '登录用户名' => '登录用户名',
            '状态' => '状态',
            '网址' => '网址',
            '满标时间' => '满标时间',
            '借款标题' => '借款标题',
            '借款金额(元)' => '借款金额(元)',
            '年化利率(%)' => '年化利率(%)',
            '期数' => '期数',
            '借款总利息(元)' => '借款总利息(元)',
            '结清日期' => '结清日期',
            '还款方式' => '还款方式',
            '操作' => '操作',
        ]
    ];

    private $pack_lend_log = [
        'repid' => 13,
        'db_name' => 'yxtest',
        'table_name' => '出借记录_主表',
        'key' => ['标题名' => '标题名','序号' => '序号'],
        'data' => [
            '登录用户名' => '登录用户名',
            '标题名' => '标题名',
            '序号' => '序号',
            '出借用户' => '出借用户',
            '出借金额(元)' => '出借金额(元)',
            '出借时间' => '出借时间',
            '出借方式' => '出借方式',
        ]
    ];

    private $pack_repayment_plan = [
        'repid' => 16,
        'db_name' => 'yxtest',
        'table_name' => '还款计划_主表',
        'key' => ['标题名' => '标题名','期数' => '期数'],
        'data' => [
            '登录用户名' => '登录用户名',
            '标题名' => '标题名',
            '期数' => '期数',
            '还款日期' => '还款日期',
            '应还本息(元)' => '应还本息(元)',
            '本金(元)' => '本金(元)',
            '利息(元)' => '利息(元)',
            '罚息' => '罚息',
            '状态' => '状态',
        ]
    ];

    private $pack_borrower_detail = [
        'repid' => 18,
        'db_name' => 'yxtest',
        'table_name' => '借款人详情_主表',
        'key' => ['借款标题' => '借款标题','被推荐人用户名' => '被推荐人用户名'],
        'data' => [
            '被推荐人用户名' => '被推荐人用户名',
            '属性' => '属性',
            '借款标题' => '借款标题',
            '真实姓名' => '真实姓名',
            '身份证号' => '身份证号',
            '手机号' => '手机号',
            '借款金额' => '借款金额',
            '借款期限' => '借款期限',
            '借款利率' => '借款利率',
            '还款方式' => '还款方式',
            '借款时间' => '借款时间',
            '放款时间' => '放款时间',
        ]
    ];

    private $pack_investor = [
        'repid' => 20,
        'db_name' => 'yxtest',
        'table_name' => '投资人详情_主表',
        'key' => ['借款标题' => '借款标题','被推荐人用户名' => '被推荐人用户名'],
        'data' => [
            '被推荐人用户名' => '被推荐人用户名',
            '属性' => '属性',
            '借款标题' => '借款标题',
            '真实姓名' => '真实姓名',
            '身份证号' => '身份证号',
            '手机号' => '手机号',
            '出借金额' => '出借金额',
            '出借期限' => '出借期限',
            '出借利率' => '出借利率',
            '还款方式' => '还款方式',
            '出借时间' => '出借时间',
        ]
    ];

    /**
     * @url /api/yxdata/getfuserinfo?p={page}
     * @name 客户基本信息
     */
    public function getfuserinfo($p = '')
    {
        if (!empty($p)) {
            $condition = $p;
        } else {
            $condition = !empty($_GET['p']) ? $_GET['p'] : 1;
        }
        $this->load->model('user/User_model', 'user_model');
        $ret = $this->user_model->find_info_by_userinfo('','','',$condition,100);
        $temp = [];
        foreach ($ret['data'] as $value) {
            $temp[] = [
                'fuserid' => $value['fuserid'],
                'name' => $value['name'],
                'idnumber' => $value['idnumber'],
                'channel' => $value['channel'],
            ];
        }
        return json_encode($temp, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @url /api/yxdata/getyxmongodata?t={Ymd}&n={type_name}
     * @param n rwcollect|loan_nofinish|loan_finish|loan_log|repayment_plan|borrower_detail
     * @name 获取银信mongo数据(爬虫数据)
     */
    public function getyxmongodata($d = '',$n = '')
    {
        if (!empty($d)) {
            $condition = $d;
        } else {
            $condition = !empty($_GET['t']) ? $_GET['t'] : date('Ymd');
        }
        if (!empty($n)) {
            $type_name = $n;
        } else {
            $type_name = !empty($_GET['n']) ? $_GET['n'] : date('Ymd');
        }
        //修正一个名字映射时候的bug
        if ('repayment_plan' == $type_name) {
            $type_name = 'repayment plan';
        }
        $y = substr($condition,0,4);
        $m = substr($condition,4,2);
        $d = substr($condition,6,2);
        $date_start = $y . '-' . $m . '-' . $d . ' 00:00:00';
        $date_end = date('Y-m-d 00:00:00', strtotime($date_start)+86400);
        $this->load->library('mongo_manager', ['table_name' => 'data_temp', 'key' => 'yxdata']);
        $ret = $this->mongo_manager->select(['data','type','ctime'])
        ->where(['type' => $type_name])
        ->where_gte("ctime", $date_start)
        ->where_lte("ctime", $date_end)
        ->order_by(['ctime','DESC'])
        ->find_one();
        if (!empty($ret['data'])) {
            // echo json_encode($ret['data'], JSON_UNESCAPED_UNICODE);exit;
            return json_encode($ret['data'], JSON_UNESCAPED_UNICODE);
        } else {
            // echo json_encode([], JSON_UNESCAPED_UNICODE);exit;
            return json_encode([], JSON_UNESCAPED_UNICODE); 
        }
    }

    /**
     * @url /api/yxdata/postdata?t={type}&d={Ymd}&n={type_name}
     * @param type fuserinfo|yxmongodata
     * @param Ymd 时间格式 20181020
     * @param type_name 当type = yxmongodata 时有效
     *         rwcollect|loan_nofinish|loan_finish|lend_log|repayment_plan|borrower_detail
     */
    public function postdata()
    {
        $condition = $_GET['t'];
        $d = !empty($_GET['d']) ? $_GET['d'] : '';
        $n = !empty($_GET['n']) ? $_GET['n'] : '';
        $path = 'http://120.26.89.131:60523/fms/index.php/dbms/deal_post_data';
        $data = json_decode($this->{'get' . $condition}($d, $n), true);
        $cnt = 0;
        $forcnt = 0;
        foreach ($data as $item) {
            if ($n && 'yxmongodata' == $condition) {
                $value = $this->{'pack_' . $n};
            } else {
                $value = $this->{'pack_' . $condition};
            }

            $temp = [];
            foreach ($value['key'] as $key => $content) {
                $temp[$key] = str_replace(array("\r\n", "\r", "\n", "\t", " ", "\\t"), "", $item[$content]);
            }
            $value['key'] = $temp;
            $temp = [];
            foreach ($value['data'] as $key => $content) {
                $temp[$key] = !empty($item[$content]) ? str_replace(array("\r\n", "\r", "\n", "\t", " ", "\\t"), "", $item[$content]) : '';
            }

            $value['data'] =  $temp;
            $temp = [];
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            var_dump($value);
            $ret = $this->curl_post(['data' => $value], $path);
            var_dump($ret);
            $cnt += $ret;
            $forcnt ++;  
            // exit;
        }
        echo '成功： ' . $cnt . '条;总共操作: ' . $forcnt . '次。结束时间：' . date('Y-m-d H:i:s');
        exit;
    }

/***********************基础方法*******************************************/
    public function curl_post($data = [], $path)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $path);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = $data;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $response = curl_exec($curl);
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
            $ret = json_decode($body, JSON_UNESCAPED_UNICODE);
        } else {
            
            $ret = ['error' => curl_getinfo($curl, CURLINFO_HTTP_CODE)];
        }
        return $ret;
    }
    

}

?>

