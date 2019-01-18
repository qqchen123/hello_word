<?php 

/**
 * @desc 魔蝎接口
 * @ps 网银的 储蓄卡 和 信用卡 共用一个接口,全部公共接口需要加入 判断 和 兼容性 处理
 */
class Moxie_service extends Admin_service
{
    private $host = 'https://api.51datakey.com';
    private $config_file = 'moxie';
    private $apikey = '';
    private $userid = '';
    private $token = '';
    private $moxie_config = [];

    /**
     * @name 构造函数
     */
    function __construct()
    {
        parent::__construct();
        $this->load->service('user/User_service', 'user_service');
        $this->load->model('public/MoxieCallback_model', 'moxiecallback_model');
        $this->config->load($this->config_file);
        $moxie_config_tmp = $this->config->item('moxie');
        $key = $moxie_config_tmp['active'];
        $this->moxie_config = $moxie_config_tmp[$key];
        $this->apikey = $this->moxie_config['apikey'];
        $this->userid = $this->moxie_config['userid'];
        $this->token = $this->moxie_config['token'];
    }

    /**
     * @name 任务回调 
     * @moxie_name 任务创建通知(task.submit)回调接口
     * @param array $data 魔蝎请求过来的数据
     * @param array $type 业务类型 
     * @return boolean 
     */
    public function task_call_back($data, $type)
    {
        Slog::log($data);
        if (!empty($data)) {
            //更新回调数据 到数据库
            $fuserid = !empty($data['fuserid']) ? $data['fuserid'] : '';
            if (!empty($data['task_id'])) {
                $task_id = $data['task_id'];
                if (!empty($fuserid)) {
                    $user_info = $this->user_service->find_user_by_fuserid($fuserid);
                    if (!empty($user_info)) {
                        $type = preg_replace('/\_report/', '', $type);
                        //网银通知单独处理
                        Slog::log('type ' . $type);
                        if ('bank_card' == $type || 'bank_card_backend' == $type) {
                            if ('bank_card_backend' == $type) {
                                $data['login_target'] = 'DEBITCARD';
                            }
                            $type = $this->bank_card_task_call_back($data);
                            if (!$type){
                                Slog::log($type);
                                return false;
                            }
                        }
                        $this->moxiecallback_model->create_task_all($fuserid);
                        $ret = $this->moxiecallback_model->update_task(
                            $fuserid, 
                            $type, 
                            [
                                'task_id' => $data['task_id'],
                                'create_status' => 1,
                            ]
                        );
                        Slog::log($this->moxiecallback_model->db->last_query());
                        return $ret;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @name 授权登录状态
     * @moxie_name 任务登录状态通知(task)回调接口
     * @param array $data 魔蝎请求过来的数据
     * @param array $type 业务类型 
     * @return boolean
     */
    public function login_status_call_back($data, $type)
    {
        if (!empty($data)) {
            //更新回调数据 到数据库
            if (!empty($data['task_id'])) {
                $type = preg_replace('/\_report/', '', $type);
                //网银通知单独处理
                if ('bank_card' == $type) {
                    $type = $this->bank_card_by_task_id_call_back($data);
                    if (!$type){
                        return false;
                    }
                }
                if (isset($data['result'])) {
                    $login_status = !empty($data['result']) ? 1 : -1;
                }
                return $this->moxiecallback_model->update_task(
                    $data['task_id'], 
                    $type, 
                    [
                        'login_status' => $login_status,
                    ]
                );
            }
        }
        return false;
    }

    /**
     * @name 账单通知
     * @moxie_name 账单通知(allbill)回调接口
     * @param array $data 魔蝎请求过来的数据
     * @param array $type 业务类型 
     * @return boolean
     */
    public function bill_status_call_back($data, $type)
    {
        $bill_status = 0;
        if (!empty($data)) {
            //更新回调数据 到数据库
            if (!empty($data['task_id'])) {
                $type = preg_replace('/\_report/', '', $type);
                //网银通知单独处理
                if ('bank_card' == $type) {
                    $type = $this->bank_card_by_task_id_call_back($data);
                    if (!$type){
                        return false;
                    }
                }
                if (isset($data['bills'])) {
                    $bill_status = !empty($data['bills']) ? 1 : -1;
                } else if (isset($data['user_id'])) {
                    $bill_status = !empty($data['user_id']) ? 1 : -1;
                }
                return $this->moxiecallback_model->update_task(
                    $data['task_id'], 
                    $type, 
                    [
                        'bill_status' => $bill_status,
                    ]
                );
            } 
        }
        return false;
    }

    /**
     * @name 报告通知
     * @moxie_name 报告通知(report)回调接口
     * @param array $data 魔蝎请求过来的数据
     * @param array $type 业务类型 
     * @return boolean
     */
    public function report_status_call_back($data, $type)
    {
        if (!empty($data)) {
            //更新回调数据 到数据库
            if (!empty($data['task_id'])) {
                $type = preg_replace('/\_report/', '', $type);
                //网银通知单独处理
                if ('bank_card' == $type || 'bank_card_backend' == $type) {
                    $type = $this->bank_card_by_task_id_call_back($data);
                    if (!$type){
                        return false;
                    }
                }
                if (isset($data['result'])) {
                    $report_status = !empty($data['result']) ? 1 : -1;
                } else {
                    Slog::log($data);
                }
                if ($report_status) {
                    //启动对应的 报告落地服务
                    $this->call_back_record_report($data['task_id'], $type, $data);
                }
                return $this->moxiecallback_model->update_task(
                    $data['task_id'], 
                    $type, 
                    [
                        'report_status' => $report_status,
                        'report_message' => $data['message']
                    ]
                );
            }
        }
        return false;
    }

    /**
     * @name 调用对应的 报告落地服务
     * @param string $task_id
     * @param string $type
     * @return int 0:落地失败 1:落地成功
     */
    public function call_back_record_report($task_id, $type, $data)
    {
        $flag = 0;
        switch ($type) {
            case 'yys':
                $this->load->service('public/Yys_service', 'yys_service');
                if ($this->yys_service->get_report($data['mobile'], $task_id)) {
                    $this->yys_service->update_report($data['mobile'], $task_id);
                } else {
                    $this->yys_service->record_report($data['mobile'], $task_id);
                }
                $this->yys_service->record_original_report($data['mobile'], $task_id);
                break;
            case 'jd':
                $this->load->service('public/Jd_service', 'jd_service');
                $this->jd_service->record_report($task_id);
                break;
            case 'taobao':
                $this->load->service('public/Taobao_service', 'taobao_service');
                $this->taobao_service->record_report($task_id);
                break;
            case 'bank_card':
                $this->load->service('public/Bank_service', 'public_bank_service');
                $this->public_bank_service->record_report($task_id);
                break;
            case 'credit_card':
                $this->load->service('public/CreditCard_service', 'credit_card_service');
                $this->credit_card_service->record_report($task_id);
                break;
            case 'gjj':
                $this->load->service('public/Gjj_service', 'gjj_service');
                $this->gjj_service->record_report($task_id);
                break;
            default:
                Slog::log('未知的落地服务 请检查' . $type);
                break;
        }
        return $flag;
    }

    /**
     * @name 任务失败通知
     * @moxie_name 任务采集失败通知(task.fail)回调接口
     * @param array $data 魔蝎请求过来的数据
     * @param array $type 业务类型 
     * @return boolean
     */
    public function task_fail_call_back($data, $type)
    {
        if (!empty($data)) {
            //更新回调数据 到数据库
            if (!empty($data['task_id'])) {
                $type = preg_replace('/\_report/', '', $type);
                //网银通知单独处理
                if ('bank_card' == $type) {
                    $type = $this->bank_card_by_task_id_call_back($data);
                    if (!$type){
                        return false;
                    }
                }
                if (isset($data['result'])) {
                    if (true == $data['result']) {
                    } else {
                        return $this->moxiecallback_model->update_task(
                            $data['task_id'], 
                            $type, 
                            [
                                'status' => -6,
                                'task_failure_message' => $data['message']
                            ]
                        );
                    }
                }
            }
        }
        return false;
    }

    /**
     * @name 任务创建通知 银行卡类特别处理
     * @param array $data
     * @return string 
     */
    public function bank_card_task_call_back($data)
    {
        $type = '';
        if (!empty($data['login_target'])) {
            if ('DEBITCARD' == $data['login_target']) {
                //借记卡 任务
                $type = 'bank_card';
            } else if ('CREDITCARD' == $data['login_target']) {
                $type = 'credit_card';
            } else {
                Slog::log('任务创建通知异常 请检查返回内容: ' . json_encode($data));
            }
        }
        return $type;
    }

    /**
     * @name 任务登录状态通知(task)回调接口 银行卡类型特别处理
     * @other 通过 task_id 去匹配实际类型，如果没有匹配到 本次回调算作失败
     * @param array $data
     * @return string
     */
    public function bank_card_by_task_id_call_back($data)
    {
        $task_info = $this->moxiecallback_model->find_by_task_id($data['task_id']);
        return !empty($task_info['type']) ? $task_info['type'] : '';
    }

    /**
     * @name 生成签名
     * @param string $payload request body内容   消息签名算法: base64(hmacsha256(payload, secret))
     * @param string $secret  使用 HMAC 生成信息摘要时所使用的密钥
     * @return string
     */
    public function base64Hmac256($payload, $secret) {
        $s = hash_hmac('sha256', $payload, $secret, true);
        return base64_encode($s);
    }

    /**
     * @name 获取用户的全部生效中的任务状态
     * @param string $fuserid
     * @return array
     */
    public function get_all_task_status($fuserid)
    {
        $task_info = $this->moxiecallback_model->find_task_all($fuserid);
        $result = [];
        foreach ($task_info as $value) {
            if (0 != $value['create_status']) {
                //任务创建回执
                $result[$value['type']] = (1 == $value['create_status']) ? '任务创建成功' : '任务创建失败';
            }
            if (0 != $value['login_status']) {
                //登录回执
                $result[$value['type']] = (1 == $value['login_status']) ? '登录成功' : '登录失败';
            }
            if (0 != $value['bill_status']) {
                //账单回执
                $result[$value['type']] = (1 == $value['bill_status']) ? '账单获取成功' : '账单获取失败';
            }
            if (0 != $value['report_status']) {
                //账单回执
                $result[$value['type']] = (1 == $value['report_status']) ? '报告获取成功' : '报告获取失败';
            }
            if (empty($result[$value['type']])) {
                $result[$value['type']] = ["去授权", 'url', ''];
            } else {
                if (0 != preg_match('/失败/', $result[$value['type']])) {
                    $result[$value['type']] = [$result[$value['type']], 'url' ,''];
                } else {
                    $result[$value['type']] = [$result[$value['type']], '', ''];
                }
            }
        }
        //
        $array = [
            'yys',
            'jd',
            'taobao',
            'credit_card',
            'bank_card',
            'gjj'
        ];
        foreach ($array as $value) {
            if (empty($result[$value])) {
                $result[$value] = ["去授权", 'url', ''];
            }
        }
        return $result;
    }

    /**
     * @name 获取用户的某一个授权的进度
     * @param string $fuserid 用户编号
     * @param string $type 授权类型
     * @return array
     */
    public function find_task_id($fuserid, $type)
    {
        return $this->moxiecallback_model->find_task($fuserid, $type);
    }

    /**
     * @name 处理curl返回的内容
     */
    public function exec_curl_get_data($querys = '', $path)
    {
        $ret = [];

        $str = '';
        if (is_array($querys)) {
            foreach ($querys as $key => $value) {
                if (empty($str)) {
                    $str .= $key . '=' . $value;
                } else {
                    $str .= '&' . $key . '=' . $value;
                }
            }
        }

        $querys = $str;
        $method = "GET";
        $headers = array();
        array_push($headers, "Authorization:token " . $this->token);
        $bodys = "";
        if ($str) {
            $url = $this->host . $path . "?" . $querys;
        } else {
            $url = $this->host . $path;
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        //gzip解压
        curl_setopt($curl, CURLOPT_ENCODING, "gzip");

        if (1 == strpos("$".$this->host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
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