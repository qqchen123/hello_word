<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
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
a{
	text-decoration:none;
}
.lableziti {
	display: none;
	font-size: 16px;
	color: red;
	margin-left: 3%;
}
</style>

<div>
	<div class="inputpwd">请输入短信验证 <input type="text" class="pwdcss input_duanx">
		<label class="lableziti lable_duanx">输入短信验证错误</label>
	</div>
	<a href="javascript:void(0)" onclick="checkv()">
		<div class="bttn">
		  确定
	    </div>
    </a>
</div>

<script type="text/javascript">
  function checkv() {
  	if ($('.input_duanx').val() == null || $('.input_duanx').val() == '') {
	       	   $('.lable_duanx').show();
	       } else if($('.input_duanx').val() != null) {
	       	   $('.lable_duanx').hide();
               window.location.href="http://yugui.club/index.php/welcome/test14";
	       }
	}
</script>