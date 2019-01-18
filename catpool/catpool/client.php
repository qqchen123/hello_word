<?php
/*猫池连接中间件（本地服务端）
* 主服务（excel服务）地址端口: 服务器ip:20000
* 子服务（猫池服务 ）地址端口:   服务器ip:20001
* 
* 
* 作者：奚晓俊
* 日期：2018-10-10 
*/
// header("Content-Type: text/html;charset=utf-8");
header("Content-Type: text/html;charset=GBK");
use \Workerman\Worker;
use \Workerman\Connection\AsyncTcpConnection;
use \Workerman\Lib\Timer;

require_once __DIR__ . '/../Workerman/Autoloader.php';

// 云服务器中间件地址
// define('PROXY_ADDRESS','ws://120.26.89.131:20001');
define('PROXY_ADDRESS','ws://127.0.0.1:20001');

// 心跳间隔55秒
define('HEARTBEAT_TIME', 55);

$worker = new Worker();

$worker->count = 1;

$worker->name = '猫池连接中间件（本地服务端）';

// 进程启动后异步连接云服务器中间件
$worker->onWorkerStart = function($worker){
    logo_info('启动'.$worker->name.' '.PROXY_ADDRESS);

    // 连接云服务器
    $connection_to_proxy = new AsyncTcpConnection(PROXY_ADDRESS);

    // 当连接建立成功时，发送tcp请求数据
    $connection_to_proxy->onConnect = 'proxy_connect';
    //
    $connection_to_proxy->onMessage = 'proxy_message';

    $connection_to_proxy->onClose = 'proxy_close';

    $connection_to_proxy->onError = 'proxy_error';
    
    $connection_to_proxy->connect();
};

// 运行worker
Worker::runAll();

// 日志格式辅助
function logo_info($info){
    // logo分割符
    $logo_sign = '============';
    echo PHP_EOL.$logo_sign.$info.$logo_sign.PHP_EOL;
}

// 心跳
function time_heart($connection){
    Timer::add(1, function()use($connection){
        $time_now = time();
        // 记录最后接收消息的时间为空则创建
        if (empty($connection->lastMessageOrSendTime)) {
            $connection->lastMessageOrSendTime = $time_now;
        }
        // 上次通讯时间间隔大于心跳间隔，发送心跳
        else if ($time_now - $connection->lastMessageOrSendTime > HEARTBEAT_TIME) {
            $connection->send('time_heart');
            $connection->lastMessageOrSendTime = $time_now;
        } 
    });
}

// 连接中间件（云服务器）
function proxy_connect($connection){
    logo_info('云端连接 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
    time_heart($connection);
}

// 连接中间件（云服务器）
function proxy_close($connection){
    logo_info('云端断开 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());

    // 如果连接断开，则在1秒后重连
    $connection->reConnect(1);
}

// 接受中间件（云服务器）数据
function proxy_message($connection,$data){
    // 记录最后接收消息的时间
    $connection->lastMessageOrSendTime = time();

    logo_info('收到云端数据 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
    echo '数据：'.$data.PHP_EOL;
    $data = json_decode($data,true);

    if($data['url']){
        //猫池密码
        $header = [
            'http'=>['header' => "Authorization: Basic YWRtaW46YWRtaW5z"],
        ];

        //post 数据
        if($data['form_data']!==null){
            // var_dump($data['form_data']);
            $header['http']['method'] = 'POST';
            $header['http']['header'] .="\r\nContent-type: application/x-www-form-urlencoded";
            $header['http']['content'] = http_build_query($data['form_data']);
        }

        $context = stream_context_create($header);

        //识别编码格式
        $url_content = @file_get_contents($data['url'],0,$context);
        // if($data['url']=='http://172.16.10.200/default/zh_CN/sms_info.html?code=utf8&type=sms') var_dump($http_response_header);
        // $encode = mb_detect_encoding($url_content, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        $url_content = phpcharset($url_content,'UTF-8');

        //发送云服务器
        if($url_content)
            proxy_send($connection,json_encode([
                'url_header' => $http_response_header,
                'url_content' => $url_content,
                'u_id' => $data['u_id'],
            ]));
    }
}

function proxy_error($connection, $code, $msg){
    logo_info('本地端错误 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
    echo "error $code $msg".PHP_EOL;
}

function proxy_send($connection,$data){
    logo_info('发送云端数据 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
    // echo '数据：'.$data.PHP_EOL;
    $connection->send($data);
    // 记录最后接收消息的时间
    $connection->lastMessageOrSendTime = time();
}

function phpcharset($data, $to) {
    if(is_array($data)) {
        foreach($data as $key => $val) {
            $data[$key] = phpcharset($val, $to);
        }
    }else{
        $encode_array = array('ASCII', 'UTF-8', 'GBK', 'GB2312', 'BIG5');
        echo $encoded = mb_detect_encoding($data, $encode_array);
        $to = strtoupper($to);
        if($encoded != $to) 
            $data = mb_convert_encoding($data, $to, $encoded);
        // $data = mb_convert_encoding($data, $to);
    }
    return $data;
}






