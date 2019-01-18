<script src="/assets/daifu/js/jquery.min.js"></script>
<style type="text/css">
.inputpwd {
	margin-top: 5%;
	font-size: 24px;
	margin-left: 30%;
}
.pwdcss {
	width: 30%;
	height: 5.5%;
	border: 2px solid #356ACA;
	margin-left: 3%;
}
.bttn {
       width: 20%;
       height: 7%;
       border-radius: 25px;
       background: #356ACA;
       margin:5% auto;
       font-size: 30px;
       color: white;
       line-height: 50px;
       text-align: center;
}
.lableziti {
	font-size: 16px;
	color: red;
	margin-left: 3%;
	display: none;
}
a{
	text-decoration:none;
}
</style>

<div>
	<div class="inputpwd">请输入密码 <input type="text" class="pwdcss input_pwd">
	 <label class="lableziti">密码输入错误</label>
	</div>
	<a href="javascript:void(0)" onclick="checkv()">
		<div class="bttn">
		  确定
	    </div>
    </a>
</div>

<script type="text/javascript">
  function checkv() {
  	if ($('.input_pwd').val() == null || $('.input_pwd').val() == '') {
	       	   $('.lableziti').show();
	       } else if($('.input_pwd').val() != null) {
	       	   $('.lableziti').hide();
               window.location.href="<?php echo site_url('/');?>welcome/test13";
	       }
	  }
</script>
