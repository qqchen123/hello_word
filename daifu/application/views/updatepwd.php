<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" type="text/css" href="/assets/daifu/css/updatepwd.css">
<div>
	<div class="revise_pwd">
        <img src="/assets/daifu/image/pen.png" class="pen_img">
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
		<a href="<?php echo site_url('/');?>welcome/test4">
			<div class="old_pwd">
			    <div class="loginbut">
        	     确&nbsp;&nbsp; 认
                </div>
		    </div>
		</a>
	</div>
</div>