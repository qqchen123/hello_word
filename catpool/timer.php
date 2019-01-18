<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/Workerman-master/Autoloader.php';
$task = new Worker();
$task->name = '查话费';

// 每xx秒执行一次
$task->timeInterval = 2.5;
// 执行的地址
$task->url = '';


$task->onWorkerStart = function($task){
    Timer::add($task->timeInterval, function(){
        timer_task();
    });
};

// 运行worker
Worker::runAll();

// 定时任务 
function timer_task(){
	echo "task run\n";

}