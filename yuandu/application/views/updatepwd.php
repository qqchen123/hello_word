<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
.info {
     margin-left: 5%;
     margin-top: 1%;
     width: 95%;
     height: 80%;
     border: 2px #356ACA solid;
     color: #495058;
     font-size: 24px;
   }
.old_pwd {
     margin-top:3.5%;
     margin-left: 20%;
   }
.input_css {
	width: 50%;
	height: 11.5%;
	border: 2px solid #356ACA;
	border-radius: 15px;
	margin-left: 5%;
	font-size: 20px;
   }
.loginbut {
	width: 49%;
	height: 12%;
	margin-left: 7%;
	border-radius: 20px;
	background: #356aca;
	line-height: 75px;
	text-align: center;
	font-size: 40px;
	color: white;
}
.pen_img {
	display: inline-block;
	margin-left: 7%;
}
.update_p {
	display: inline-block;
	color: #0c2679;
   	 font-size: 24px;
   	 margin-left: 1%;
   	 font-weight: bold;
}
a {
	text-decoration:none;
}
</style>
<div>
	<div class="revise_pwd">
	        <img src="/public/image/pen.png" class="pen_img">
	        <p class="update_p">修改密码</p>
    </div>
	<div class="info">
		<div class="old_pwd">
			原密码 <input type="text" placeholder="   请输入原密码" class="input_css">
		</div>
		<div class="old_pwd">
			新密码 <input type="text" placeholder="   请输入8-20位数字与英文组成的密码" class="input_css">
		</div>
		<div class="old_pwd">
			新密码 <input type="text" placeholder="   请输入新密码" class="input_css">
		</div>
		<a href="http://yugui.club/index.php/welcome/test4">
			<div class="old_pwd">
			    <div class="loginbut">
        	     确&nbsp;&nbsp; 认
                </div>
		    </div>
		</a>
	</div>
</div>