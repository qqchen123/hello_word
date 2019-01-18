<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/Workerman-master/Autoloader.php';
worker::$stdoutFile = 'workerman.log';

$task = new Worker();
$task->name = '查猫池话费';

// 每xx秒执行一次
$task->timeInterval = 1;
// 执行的地址
$task->url = 'http://localhost/fms/index.php/CatPool/autoCheckAndPay';

$task->onWorkerStart = function($task){
    Timer::add($task->timeInterval, function($task){
    	$date = date('Y-m-d H:i:s');
    	echo "\r\n".$date."  充值检查开始===================================\r\n";
        timer_task($task->url);
    	echo "\r\n".$date."  充值检查结束===================================\r\n\r\n\r\n\r\n";
    },['task'=>$task]);
};

// 运行worker
Worker::runAll();

// 定时任务 
function timer_task($url){
	// 创建新的 cURL 资源
	$ch = curl_init();

	// 设置 URL 和相应的选项
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// 抓取 URL
	$res = curl_exec($ch);//返回bool

	// 关闭 cURL 资源，并且释放系统资源
	curl_close($ch);
}