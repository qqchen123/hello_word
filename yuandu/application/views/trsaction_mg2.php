<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<style type="text/css">
.money {
	font-size: 30px;
	font-weight: bold;
	margin-top: 2%;
	margin-left: 2%;
	color: black;
}
.file_css {
	color: black;
	margin-top: 2%;
	margin-left: 2%;
	font-size: 20px;
}
.filecss {
	margin-left: 15px;
}
.query {
	display: inline-block;
 	width: 200px;
 	height: 54px;
 	background: #356aca;
 	font-size: 24px;
 	line-height: 54px;
 	text-align: center;
 	border-radius: 25px;
 	color: #ffff;
 	margin-top: 4%;
 	margin-left: 10%;
 }
 .shibai {
 	 display: none;
     color: red;
     font-size: 30px;
     margin-left: 4%;
 }
 a {
	text-decoration:none;
}
</style>
<div>
	<div class="money">余额： 240536.97</div>
	<div class="file_css">
		上传代付文件 <input type="file" class="filecss file_band">
	</div>
	<a href="javascript:void(0)" onclick="checkv()">
		<button class="query">
		  上 传
	    </button>
	    <lable class="shibai">上传失败</lable>
   </a>
</div>

<script type="text/javascript">
  function checkv() {
  	if ($('.file_band').val() == null || $('.file_band').val() == '') {
	       	   $('.shibai').show();
	       } else if($('.file_band').val() != null) {
	       	   $('.shibai').hide();
               window.location.href="http://yugui.club/index.php/welcome/test15";
	       }
	}
</script>
