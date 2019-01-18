<?php
/*猫池连接中间件（云服务端）
* 主服务（excel服务）地址端口:	服务器ip:20000
* 子服务（猫池服务 ）地址端口:	服务器ip:20001
* 
* 
* 作者：奚晓俊
* 日期：2018-10-10
*/
use \Workerman\Worker;
use \Workerman\WebServer;
use \Workerman\Connection\TcpConnection;
use \Workerman\Protocols\Http;

// use \Workerman\Connection\AsyncTcpConnection;
// require_once __DIR__ . '/../Workerman/Autoloader.php';
require_once __DIR__ .'/../../upload/catpool/Workerman/Autoloader.php';

// $a = require_once __DIR__ .'/../../assets/lib/Workerman/Autoloader.php';

// 主服务（供excel服务）地址端口:	服务器ip:20000
define('EXCEL_ADDRESS','http://0.0.0.0:20000');

// 子服务（供猫池服务 ）地址端口:	服务器ip:20001
define('CAT_POOL_ADDRESS','Websocket://0.0.0.0:20001');

// 猫池规则
// $rules['from_cat_pool'] =[
// 	'visit_ip' => '',
// 	'data_key' => 'url',
// ];
// $rules['from_excel'] = [

// ];

$cat_pool_worker = null;
$excel_connect_id = null;
// 日志文件路径
// echo __DIR__ .'/../../shared/logs/catpool/catpool.log';
Worker::$logFile =  __DIR__ .'/../../shared/logs/catpool.log';

// 主服务（供excel）
$excel_worker = new Worker(EXCEL_ADDRESS);

// 需要PHP>=7 否则当执行到worker->listen()时会报Address already in use错误
$excel_worker->count = 1;

$excel_worker->name = '猫池连接中间件（云服务端）';

// excel进程启动后在当前进程新增一个猫池Worker监听
$excel_worker->onWorkerStart = 'excel_worker_start';

// 接受excel触发的消息 并获取猫池数据
$excel_worker->onMessage = 'excel_message';

$excel_worker->onConnect = 'excel_connect';
$excel_worker->onClose = 'excel_close';
$excel_worker->onError = 'excel_error';


Worker::runAll();


// 日志格式辅助
function logo_info($info){
	// logo分割符
	$logo_sign = '============';
    echo PHP_EOL.$logo_sign.$info.$logo_sign.PHP_EOL;
}

// excel服务 开始=================================================
	// excel服务启动后在当前进程新增一个http服务
	function excel_worker_start($worker){
		logo_info('启动'.$worker->name.'ip：'.$worker->socket);
		global $cat_pool_worker;
	    $cat_pool_worker = new Worker(CAT_POOL_ADDRESS);

	    // 设置端口复用，可以创建监听相同端口的Worker（需要PHP>=7.0）
	    $cat_pool_worker->reusePort = true;
	    $cat_pool_worker->onWorkerStart = 'cat_pool_start';
	    $cat_pool_worker->onMessage = 'cat_pool_message';
	    $cat_pool_worker->onConnect = 'cat_pool_connect';
	    $cat_pool_worker->onClose = 'cat_pool_close';
	    $cat_pool_worker->onError = 'cat_pool_error';

	    // 执行监听。正常监听不会报错
	    $cat_pool_worker->listen();
	};

	// excel客户端连接
	function excel_connect($connection){
		logo_info('excel端连接 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
//判断来源ip过滤===============================================
		// var_dump($connection);
	};

	// excel客户端断开
	function excel_close($connection){
		logo_info('excel端断开 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
	}

	// 发送excel数据
	function excel_send($json_str){
		$data = json_decode($json_str,true);
		if(!isset($data['u_id'])) return;
		global $excel_worker;
		$connection = $excel_worker->connections[$data['u_id']];
		logo_info('发送excel端数据 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
		// echo '数据：'.$data['url_content'].PHP_EOL;

		//添加真实header头
		// var_dump($data['url_header']);
		foreach ($data['url_header'] as $k => $v) {
			Http::header($v);
		}

		$connection->send($data['url_content']);
	}

	// 收到excel数据
	function excel_message($connection, $data){
		// ini_set('memory_limit','-1');
		logo_info('收到excel端数据 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
		echo 'POST数据：'.json_encode($_POST).PHP_EOL;
		if(isset($_POST['url'])){
			cat_pool_send(json_encode([
				'url'=>$_POST['url'],
				'form_data'=>@$_POST['form_data'],
				'u_id'=>$connection->id
			]));
		}else{
			excel_send(json_encode([
				'url_content' => '请POST传参url=访问地址',
				'u_id' => $connection->id,
			]));
		}
	};

	// excel错误
	function excel_error($connection, $code, $msg){
		logo_info('excel服务端错误 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
	    echo "error $code $msg".PHP_EOL;
	};
// excel服务 结束=================================================

// 猫池服务 开始=================================================
	// 猫池服务开启
	function cat_pool_start($worker){
		logo_info('启动'.$worker->name.'ip：'.$worker->socket);
	};

	// 猫池客户端连接
	function cat_pool_connect($connection){
		logo_info('猫池端连接 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
	};

	// 猫池客户端断开
	function cat_pool_close($connection){
		logo_info('猫池端断开 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());

	}

	// 发送猫池数据
	function cat_pool_send($str){
		global $cat_pool_worker;
		$connection = array_values($cat_pool_worker->connections)[0];
		logo_info('发送猫池端数据 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
		// echo '数据：'.$str.PHP_EOL;
		$connection->send($str);
	}

	// 收到猫池数据
	function cat_pool_message($connection, $data){
		if($data=='time_heart') return;
		logo_info('收到猫池端数据 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
		// echo '数据：'.$data.PHP_EOL;
		excel_send($data);
	};

	// 猫池错误
	function cat_pool_error($connection, $code, $msg){
		logo_info('猫池服务端错误 ip：'.$connection->getRemoteip().':'.$connection->getRemotePort());
	    echo "error $code $msg".PHP_EOL;
	};
// 猫池服务 结束=================================================












