<?php
use Workerman\Worker;
require_once __DIR__ . '/../Workerman/Autoloader.php';

// // worker实例1有4个进程，进程id编号将分别为0、1、2、3
// $worker1 = new Worker('http://0.0.0.0:8585');
// // 设置启动4个进程
// $worker1->count = 4;
// // 设置实例的名称
// $worker->name = 'MyWebsocketWorker';
// // 每个进程启动后打印当前进程id编号即 $worker1->id
// $worker1->onWorkerStart = function($worker1){
//     echo "worker1->id={$worker1->id}\n";
// };
// $worker1->onConnect = function($connection){
//     echo '连接'.$connection->id;
// };


// // worker实例2有两个进程，进程id编号将分别为0、1
// $worker2 = new Worker('tcp://0.0.0.0:8686');
// // 设置启动2个进程
// $worker2->count = 2;
// // 每个进程启动后打印当前进程id编号即 $worker2->id
// $worker2->onWorkerStart = function($worker2){
//     echo "worker2->id={$worker2->id}\n";
// };

// // 运行worker
// Worker::runAll();


// 创建一个Worker监听20000端口，不使用任何应用层协议
$tcp_worker = new Worker("tcp://0.0.0.0:20000");

// 启动4个进程对外提供服务
$tcp_worker->count = 4;

// 当客户端发来数据时
$tcp_worker->onMessage = function($connection, $data){
	echo "proxy server ip : " . $connection->getRemoteip() . PHP_EOL;
	echo "get data : " . $data . PHP_EOL;
    // 向客户端发送hello $data
    $connection->send('hello client');
};

// 运行worker
Worker::runAll();