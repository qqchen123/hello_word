<?php
/**
 * Description of HttpClient
 *
 * @author dasheng@baofu.com
 */
class HttpClient {
    public static function Post($PostArry,$Req_Url){
        try {
            $postData = $PostArry;		 
            $postDataString = http_build_query($postData);//格式化参数
            $curl = curl_init(); // 启动一个CURL会话
            curl_setopt($curl, CURLOPT_URL, $Req_Url); // 要访问的地址
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //对认证证书来源的检查
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); //从证书中检查SSL加密算法是否存在
            curl_setopt($curl, CURLOPT_SSLVERSION, 6); //CURL_SSLVERSION_DEFAULT (0), CURL_SSLVERSION_TLSv1 (1), CURL_SSLVERSION_SSLv2 (2), CURL_SSLVERSION_SSLv3 (3), CURL_SSLVERSION_TLSv1_0 (4), CURL_SSLVERSION_TLSv1_1 (5)， CURL_SSLVERSION_TLSv1_2 (6) 中的其中一个。	
            curl_setopt($curl, CURLOPT_POST, true); //发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postDataString); //Post提交的数据包
            curl_setopt($curl, CURLOPT_TIMEOUT, 60); //设置超时限制防止死循环返回
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

            $tmpInfo = curl_exec($curl); // 执行操作
            if (curl_errno($curl)) {
                $tmpInfo = curl_error($curl);//捕抓异常
            }
            curl_close($curl); //关闭CURL会话
            return $tmpInfo; //返回数据
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }	
    }
}
