<?php

// $env = 'local';
$env = 'dev';
if ('dev' == $env) {
    require('../aliyun-dysms-php-sdk-lite/include.php');
} else {
    require('/www/svn-code/aliyun-dysms-php-sdk-lite/include.php');
}

use demo\SignatureHelper;

/**
 * @desc 短信服务
 */
class Sms_service extends Admin_service
{

    public function send_identifying_code($mobile, $code)
    {
        $ret = $this->sendSms($mobile, $code);
        Slog::log($ret);
        if (!empty($ret['content']->Code)) {
            if ('OK' == $ret['content']->Code) {
                return $ret['send_code'];
            }
        }
        Slog::log('验证码发送异常');
        return '验证码发送异常';
    }

    /**
     * 发送短信
     */
    public function sendSms($mobile, $code) {

        $params = array ();

        // *** 需用户填写部分 ***

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = "bXZ05GhJt1zCQPH7";
        $accessKeySecret = "Ttkh85f2RNKvNvz63lbfT9SLAvyfHD";

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $mobile;

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = "九盾科技";

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = "SMS_142110405";

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "code" => $code,
        );

        // fixme 可选: 设置发送短信流水号
        $params['OutId'] = "123456";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        $params['SmsUpExtendCode'] = "1234567";


        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
            // fixme 选填: 启用https
            // ,true
        );

        return ['content' => $content, 'send_code' => $code];
    }
    //发送喜报--write by 陈恩杰--2018 8 21 11：30
    public function send_xb($mobile,$xb_res)
    {
        $ret = $this->xb_sendSms($mobile,$xb_res);
        if (!empty($ret['content']->Code)) {
            if ('OK' == $ret['content']->Code) {
                return $ret['send_code'];
            }
        }
        return '喜报发送异常';
    }
    /**
     * 发送喜报--write by 陈恩杰--2018 8 21， 11：30
     */
    public function xb_sendSms($mobile, $msg = '') {

        $params = array ();

        // *** 需用户填写部分 ***

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = "bXZ05GhJt1zCQPH7";
        $accessKeySecret = "Ttkh85f2RNKvNvz63lbfT9SLAvyfHD";

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $mobile;

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = "九盾科技";

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = "SMS_142620724";

//        $code = $this->randStr();
        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "code1" => $msg['branch'],
            "code2" => $msg['username'],
            "code3" => $msg['business'],
            "code4" => $msg['money'],
        );

        // fixme 可选: 设置发送短信流水号
        $params['OutId'] = "123456";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        $params['SmsUpExtendCode'] = "1234567";


        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        // fixme 选填: 启用https
        // ,true
        );

        return ['content' => $content, 'send_code' => $msg];
    }

    public function randStr($len=6, $format='NUMBER') { 
        switch($format) { 
            case 'ALL':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; break;
            case 'CHAR':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~'; break;
            case 'NUMBER':
                $chars='0123456789'; break;
            default :
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; 
            break;
        }
        mt_srand((double)microtime()*1000000*getmypid()); 
        $password="";
        while(strlen($password)<$len)
        $password.=substr($chars,(mt_rand()%strlen($chars)),1);
        return $password;
    }

}