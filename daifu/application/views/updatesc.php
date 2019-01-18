<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <style type="text/css">
 .right_er{
 	/*width: 747px;*/
 	width: 20%;
 	/*height: 123px;*/
 	height: 40px;
 	background: #356aca;
 	border-radius: 25px;
 	
 	margin: auto;
 	margin-top: 20%;
 }
 .yes_img {
 	position: absolute;
 	display: inline-block;
 	margin-left: 20px;
 	width: 40px;
 	height: 40px;
 }
 .pwd_sc {
 	display: inline-block;
 	font-size: 20px;
 	line-height: 40px;
 	color: #ffffff;
 	font-family: '微软雅黑';
 	margin-left: 35%;
 }
 </style>
<div>
     <a href="<?php echo site_url('/');?>welcome/test2">
     	<div class="right_er">
     	    <img src="/assets/daifu/image/yes.png" class="yes_img">
     	     <p class="pwd_sc">密码修改成功</p>
         </div>
    </a>
</div>
