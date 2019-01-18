<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
    'class' => 'MethodHook',
    'function' => 'my_init',
    'filename' => 'MethodHook.php',
    'filepath' => 'hooks'
);

// $hook['post_system'][] = array(
//     'class' => 'MethodHook',
//     'function' => 'my_end',
//     'filename' => 'MethodHook.php',
//     'filepath' => 'hooks'
// );


?>