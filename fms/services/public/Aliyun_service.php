<?php 

/**
 * @desc 阿里云接口
 */
class Aliyun_service extends Admin_service
{
    /**
     * @name 日志ID
     */
    public $log_id = '';

    /**
     * @name 构造函数
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('public/AliyunLog_model', 'am');
    }

    /**
     * @name 处理curl返回的内容
     */
    public function exec_curl_get($host, $querys, $appcode, $path, $type)
    {
        $ret = [];

        $str = '';
        foreach ($querys as $key => $value) {
            if (empty($str)) {
                $str .= $key . '=' . $value;
            } else {
                $str .= '&' . $key . '=' . $value;
            }
        }

        $querys = $str;
        $method = "GET";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $this->log_id = $this->am->write_log($querys, $type, $url);
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

    /**
     * @name 身份证姓名一致性检查
     * @param string $idnumber 身份证
     * @param string $name 姓名
     * @return string|boolean 
     */
    public function idnumber_check($idnumber, $name)
    {
        $result = '接口错误';
        $host = "http://aliyunverifyidcard.haoservice.com";
        $appcode = "ce7fbeb85cf94ad083fc9e4a179f32a5";
        $path = "/idcard/VerifyIdcardv2";
        $querys = [
            'cardNo' => $idnumber,
            'realName' => $name
        ];
        $ret = $this->exec_curl_get($host, $querys, $appcode, $path, '01');
        if (0 == $ret['error_code']) {
            $result = $ret['result']['isok'];
        }
        if (206501 == $ret['error_code']) {
            $result = '认证中心库中无此身份证记录';
        }
        $this->am->update_log($this->log_id, $ret, $ret['error_code'], $ret['result']);
        return $result;
    }

    /**
     * @name 银行卡四要素验证 实名认证
     * @param string $bank 银行卡号
     * @param string $mobile 银行预留手机号
     * @param string $idnumber 身份证
     * @param string $name 姓名
     * @return string|boolean
     */
    public function bank_check($bank, $mobile, $idnumber, $name)
    {
        $host = "http://lundroid.market.alicloudapi.com";
        $path = "/lianzhuo/verifi";
        $appcode = "ce7fbeb85cf94ad083fc9e4a179f32a5";
        $querys = [
            'acct_name' => $name,
            'acct_pan' => $bank,
            'cert_id' => $idnumber,
            'phone_num' => $mobile
        ];
        $ret = $this->exec_curl_get($host, $querys, $appcode, $path, '02');
        if (0 == $ret['resp']['code']) {
            $result = $ret['resp']['code'];
        } else if (96 == $ret['resp']['code']) {
            $result = '接口错误';
        } else {
            $result = $ret['resp']['desc'];
        }
        $this->am->update_log($this->log_id, $ret, $ret['resp']['code'], $ret['resp']);
        return $result;     
    }

    /**
     * @name 银行卡信息获取
     * @param string $bank 银行卡号
     * @other 返回有效内容字段映射
     *  result(银行卡基本信息)
     *  名称              类型      说明
     *  abbreviation    string  简写
     *  bankimage   string  银行LOGO
     *  bankname    string  发卡银行
     *  banknum string  起始数
     *  bankurl string  银行网址
     *  cardlength  string  银行卡号长度
     *  cardname    string  银行卡名称
     *  cardprefixlength    string  bin长度
     *  cardprefixnum   string  bin
     *  cardtype    string  银行卡类型
     *  enbankname  string  银行名称(英文)
     *  isLuhn  string  是否符合编码规范
     *  iscreditcard    string  是否是信用卡,1为借记卡,2为信用卡
     *  servicephone    string  银行电话
     *  province    string  归属地省
     *  city    string  归属地市
     *@return array|string
     */
    public function bank_info($bank)
    {
        $host = "http://api43.market.alicloudapi.com";
        $path = "/api/c43";
        $appcode = "ce7fbeb85cf94ad083fc9e4a179f32a5";
        $querys = [
            'apiversion' => '2.0.5',
            'bankcard' => $bank,
        ];
        $ret = $this->exec_curl_get($host, $querys, $appcode, $path, '03');
        if (isset($ret['error'])) {
            $ret['error_code'] = $ret['error'];
            if (403 == $ret['error']) {
                $ret['result'] = "剩余次数不足";
            } else if (400 == $ret['error']) {
                $ret['result'] = "APPCODE错误";
            } else {
                $ret['result'] = "APPCODE错误";
            }
            $result = '接口错误';
        } else {
            if (0 == $ret['error_code']) {
                if ($ret['result']['isLuhn']) {
                    $result = json_encode($ret, JSON_UNESCAPED_UNICODE);
                } else {
                    $result = '银行卡不符合编码规则';
                }
            } else {
                $result = $ret['result'] = $ret['reason'];
            }
        }
        $this->am->update_log($this->log_id, $ret, $ret['error_code'], $ret['result']);
        return $result;
    }


    
}