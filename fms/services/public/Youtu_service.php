<?php
// $env = 'local';
$env = 'dev';
if ('dev' == $env) {
    require('/www/php_sdk-master/include.php');
} else {
    require('/www/svn-code/php_sdk-master/include.php');
}
use TencentYoutuyun\Youtu;
use TencentYoutuyun\Conf;
/**
 * @desc 优图服务
 */
class Youtu_service extends Admin_service
{
    /*配置信息*/
    private $appid='10134247';
    private $secretId='AKIDthLqDSAd1FIAC64Rs8DB3o6FDa4ZjSho';
    private $secretKey='JTwJVWQ9h4FGAtWzGOMApIajiZFqKo8H';
    private $userid='45737921';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
        Conf::setAppInfo($this->appid, $this->secretId, $this->secretKey, $this->userid,conf::API_YOUTU_END_POINT );
		parent::__construct();
	}

    /**
     * @name 加载配置项
     * @param $name conf_name
     * @param $type 
     * @return array
     */
    function load_config($name, $type)
    {
        $this->load->service('business/'.$type, 'conf_service');
        return $this->conf_service->$name;
    }

    /*
        session_id 相应请求的session标识符，可用于结果查询 
        name 证件姓名 
        sex 性别 
        nation 民族 
        birth 出生日期 
        address 地址 
        id 身份证号 
        frontimage OCR识别的身份证正面照片 
        valid_date 证件的有效期 
        authority 发证机关 
        backimage OCR识别的证件身份证反面照片 
        detail_errorcode 详细的错误原因 
        detail_errormsg 详细的错误原因说明 
        errorcode 返回状态值 
        errormsg 返回错误消息
    */
    public function idcardocr($dir_array = [])
    {
        if ('dev' == $this->config->item('youtu_env')) {
            $dir = '/www';
        } else {
            $dir = '/www/svn-code';
        }
        Slog::log($dir);
        foreach ($dir_array as $key => $value) {
            if ($value) {
                $uploadRet[] = YouTu::idcardocr($dir . $value, $key);//0-代表正面，1-代表反面
            } else {
                Slog::log('无' . $key);
                $uploadRet[] = [];
            }
        }
        return $uploadRet;
    }

    /**
     * @name 优图测试
     * @param $id 用户ID
     */
    public function youtu_test($id)
    {
        $this->load->service('user/User_service', 'user_service');
        $user_info = $this->user_service->find_user_edit($id);
        if (empty($user_info['sample_23']) || empty($user_info['sample_23']['val'])) {
            $user_info['sample_23']['val'] = '';
        }
        if (empty($user_info['sample_24']) || empty($user_info['sample_24']['val'])) {
            $user_info['sample_24']['val'] = '';
        }
        Slog::log('入参检查');
        Slog::log([$user_info['sample_23']['val'], $user_info['sample_24']['val']]);
        $result = $this->idcardocr([$user_info['sample_23']['val'], $user_info['sample_24']['val']]);
        Slog::log('返回内容');
        unset($result[1]['backimage']);
        unset($result[0]['frontimage']);
        Slog::log($result);
        $info = [];
        foreach ($result as $key => $value) {
            //优图识别成功
            //提取内容
            if (0 == $key) {
                if (isset($value['errorcode']) && 0 === $value['errorcode']) {
                    $info[0] = [
                        'name' => !empty($value['name']) ? $value['name'] : '姓名获取失败',
                        'sex' => !empty($value['sex']) ? $value['sex'] : '性别获取失败',
                        'nation' => !empty($value['nation']) ? $value['nation'] : '民族获取失败',
                        'birth' => !empty($value['birth']) ? $value['birth'] : '生日获取失败',
                        'address' => !empty($value['address']) ? $value['address'] : '地址获取失败',
                        'id' => !empty($value['id']) ? $value['id'] : '身份证获取失败',
                    ];
                } else {
                    $info[0] = [];
                }
            } else {
                if (isset($value['errorcode']) && 0 === $value['errorcode']) {
                    $info[1] = [
                        'valid_date' => !empty($value['valid_date']) ? $value['valid_date'] : '证件的有效期获取失败',
                        'authority' => !empty($value['authority']) ? $value['authority'] : '发证机关获取失败',
                    ];
                } else {
                    $info[1] = [];
                }
            }
        }
        $result = array_merge($info[0], $info[1]);

        // var_dump($result);

        //处理返回的数据
        //处理生日
        $result['birth'] = preg_replace('/\//', '-', $result['birth']);
        $result['valid_date_tmp'] = explode('-', $result['valid_date']);
        $result['valid_date_begin'] = preg_replace('/\./', '-', $result['valid_date_tmp'][0]);
        $result['valid_date_end'] = preg_replace('/\./', '-', $result['valid_date_tmp'][1]);
        $result['valid_date_remaining_time'] = ceil((strtotime($result['valid_date_end']) - strtotime("now")) / 86400);

        //格式整理
        // echo '格式整理完成<br/>';
        // var_dump($result);
        return $result;
    }
    

    public function do_youtu($user_info)
    {

        Slog::log('入参检查');
        Slog::log([$user_info['sample_23']['val'], $user_info['sample_24']['val']]);
        $result = $this->idcardocr([$user_info['sample_23']['val'], $user_info['sample_24']['val']]);
        Slog::log('返回内容');
        if (isset($result[1]['backimage'])) {
            unset($result[1]['backimage']);
        }
        if (isset($result[0]['frontimage'])) {
            unset($result[0]['frontimage']);
        }
        Slog::log($result);
        $info = [];
        foreach ($result as $key => $value) {
            //优图识别成功
            //提取内容
            if (0 == $key) {
                if (isset($value['errorcode']) && 0 === $value['errorcode']) {
                    $info[0] = [
                        'name' => !empty($value['name']) ? $value['name'] : '姓名获取失败',
                        'sex' => !empty($value['sex']) ? $value['sex'] : '性别获取失败',
                        'nation' => !empty($value['nation']) ? $value['nation'] : '民族获取失败',
                        'birth' => !empty($value['birth']) ? $value['birth'] : '生日获取失败',
                        'address' => !empty($value['address']) ? $value['address'] : '地址获取失败',
                        'id' => !empty($value['id']) ? $value['id'] : '身份证获取失败',
                    ];
                } else {
                    $info[0] = [
                        'name' => '',
                        'sex' => '',
                        'nation' => '',
                        'birth' => '',
                        'address' => '',
                        'id' => '',
                    ];
                }
            } else {
                if (isset($value['errorcode']) && 0 === $value['errorcode']) {
                    $info[1] = [
                        'valid_date' => !empty($value['valid_date']) ? $value['valid_date'] : '证件的有效期获取失败',
                        'authority' => !empty($value['authority']) ? $value['authority'] : '发证机关获取失败',
                    ];
                } else {
                    $info[1] = [
                        'valid_date' => '',
                        'authority' => '',
                    ];
                }
            }
        }
        $result = array_merge($info[0], $info[1]);
        // Slog::log($result);
        // var_dump($result);
        $check_flag = 1;
        foreach ($result as $value) {
            if (!empty($value)) {
                $check_flag = 0;
            }
        }

        //处理返回的数据
        if (!$check_flag) {
            //处理生日
            $result['birth'] = preg_replace('/\//', '-', $result['birth']);
            $result['valid_date_tmp'] = explode('-', $result['valid_date']);
            $result['valid_date_begin'] = !empty($result['valid_date_tmp'][0]) ? preg_replace('/\./', '-', $result['valid_date_tmp'][0]) : '';
            $result['valid_date_end'] =  !empty($result['valid_date_tmp'][1]) ? preg_replace('/\./', '-', $result['valid_date_tmp'][1]) : '';
            $result['valid_date_remaining_time'] = !empty($result['valid_date_end']) ? ceil((strtotime($result['valid_date_end']) - strtotime("now")) / 86400) : 0;
        }
        //格式整理
        // echo '格式整理完成<br/>';
        // var_dump($result);
        return $result;
    }
    
}