<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| System log env
|--------------------------------------------------------------------------
|
| value: local dev prod
| local: use seas log
| dev:	 use CI log
| prod:	 use CI log
*/
// $config['log_env'] = 'local';
$config['log_env'] = 'dev';


/*
|--------------------------------------------------------------------------
| youtu dir config
|--------------------------------------------------------------------------
|
| value: local dev prod
| 
*/
$config['youtu_env'] = 'dev';

/*
|--------------------------------------------------------------------------
| youtu enum config
|--------------------------------------------------------------------------
|
| 
| 
*/
$config['youtu_id_number'] = [
	'sex' => 11,
	'nation' => 8,
	'birth' => 10,
	'address' => 21,
	'valid_date_begin' => 14,
	'valid_date_end' => 15,
	'valid_date_remaining_time' => 16,
	'authority' => 13,
];

/*
|--------------------------------------------------------------------------
| file_upload_type enum config
|--------------------------------------------------------------------------
|
| 
| 
*/
$config['file_upload_type'] = [
	'user',
	'house',
	'order'
]; 

