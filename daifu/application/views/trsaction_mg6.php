<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <style type="text/css">
 .right_er{
 	width: 50%;
 	height: 15%;
 	background: #356aca;
 	border-radius: 25px;
 	margin-top: 20%;
 	margin-left: 20%;
 }
 .yes_img {
 	position: absolute;
 	display: inline-block;
 	margin-top: 10px;
 	margin-left: 4%;
 }
 .pwd_sc {
 	display: inline-block;
 	font-size: 48px;
 	color: #ffffff;
 	font-family: '微软雅黑';
 	margin-top: 20px;
 	margin-left: 35%;
 }
 </style>
<div>
     <a href="<?php echo site_url('/');?>welcome/test2">
     	<div class="right_er">
     	    <img src="/assets/daifu/image/yes.png" class="yes_img">
     	     <p class="pwd_sc">上传成功</p>
         </div>
    </a>
</div>
