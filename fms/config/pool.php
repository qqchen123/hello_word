<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//证据池设置

    //样本归属类型定义
    $config['pool']['sample_types'] = [
    	[
	        'key'=>'user',
	        'text'=>'客户',
	        'table'=>'fms_pool_user',
	        'id_field'=>'user_id',
	        'init_pool_method'=>'get_user',
	        'status_type'=>'pool_user'
        ],[
        	'key'=>'house',
        	'text'=>'房屋',
        	'table'=>'fms_pool_house',
        	'id_field'=>'house_id',
        	'init_pool_method'=>'get_house',
        	'status_type'=>'pool_house'
        ],[
        	'key'=>'order',
        	'text'=>'订单',
        	'table'=>'fms_pool_order',
        	'id_field'=>'order_id',
        	'init_pool_method'=>'get_order',
        	'status_type'=>'pool_order'
        ],
    ];

    //默认样本归属类型
    $config['pool']['sample_type'] = 'user';
    // $config['pool']['sample_type'] = 'house';