<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
.place {
	width: 100%;
	height: 100px;
	background: #385b99;
}
.place_r {
	display: inline-block;
	width: 235px;
	height: 100px;
	background-color: #0c2679;
}
.place_l {
	display: inline-block;
	float: right;
	width: 450px;
	height: 100px;
	border: 0px red solid;
}
.yugui_img {
	position: absolute;
	display: inline-block;
    margin-top: 30px;
}
.yugui_fout {
	position: relative;
	display: inline-block;
	font-size: 24px;
	color: #b0c0db;
	line-height: 100px;
	margin-left: 18%;
}
.quit {
	color: #ffffff;
	text-decoration:none;
	line-height: 100px;
	margin-left: 30%;
	font-size: 24px;
	font-family: '微软雅黑';
}
.left {
	display: inline-block;
	float: left;
	width: 18%;
	width: 235px;
	height: 90%;
	background: #356aca;
}
.left_fout {
	margin-top: 30%;
	margin-left: 40px;
}
.left_fout2 {
	margin-top: 60%;
}
.ziti {
	position: relative;
	margin-left: 5%;
	color: #0c2679;
	font-size: 20px;
	font-family: '微软雅黑';
	font-weight: bold;
}
.ziti_img {
    margin-left: 20%;
}
.lien {
	width: 80%;
	height: 3px;
	margin-top: 3%;
	background-color: white;
}
.midd {
	display: inline-block;
	width: calc(100% - 366px);
	height: 780px;
	border: 0px red solid;
}
.drop-down {
	margin-left: 20%;
}
.user_info {
	width: 73%;
	height: 100px;
	background: #869dc5;
	margin-left: 3%;
}
.user_big {
	width: 73%;
	background: #869dc5;
	margin-left: 3%;
	height: 240px;
}
.user_ziti {
	display: inline-block;
	text-decoration:none;
    color: #385b99;
    font-size: 14px;
    font-family:'微软雅黑';
    margin-top: 7%;
    margin-left:5%;
}
a:hover {
	color: #0c2679;
}

</style>
<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<body>
<div>
	<div>
		<div class="place">
       	     <div class="place_r">
       	     	<a href="" class="quit">
       	     		安全退出
       	     	</a>
       	     </div>
       	     <div class="place_l">
       	     	   <img src="/public/image/peo.png" class="yugui_img">
       	     	   <span class="yugui_fout">上海钰桂信息科技有限公司</span>
       	     </div>
       </div>
	</div>
	<div>
		<div>
			<div class="root">
	          <div class="left">
	      	    <div class="left_fout">
	      	    	<div class="ziti">用户管理 
	      	    		<img src="/public/image/xiala.png" class="ziti_img user_im">
	      	    		<img src="/public/image/dianji.png" class="ziti_img user_im2" style="display: none;">
	      	    	</div>
	      	    	<div class="lien"></div>
	      	    	<div class="user_info user_info2" style="display: none;">
	      	    		 <a href="http://yugui.club/index.php/welcome/test3" class="user_ziti" >修改密码</a><br>
	      	    		 <a href="http://yugui.club/index.php/welcome/test5" class="user_ziti">短信设置</a>
	      	    	</div>
	      	    </div>
	      	    <div class="left_fout left_fout2">
	      	    	<div class="ziti">交易管理
                       <img src="/public/image/xiala.png" class="ziti_img tr_img">
                       <img src="/public/image/dianji.png" class="ziti_img tr_img2" style="display: none;">
	      	    	</div>
	      	    	<div class="lien"></div>
	      	    	<div class="dfdfg_info user_big" style="display: none;">
	      	    		 <a href="http://yugui.club/index.php/welcome/test9" class="user_ziti">交易明细查询</a>
	      	    		 <br>
	      	    		 <a href="http://yugui.club/index.php/welcome/test10" class="user_ziti">上传代付文件</a>
	      	    		 <br>
	      	    		 <a href="http://yugui.club/index.php/welcome/test18" class="user_ziti">上传代扣文件</a>
	      	    		 <br>
	      	    		 <a href="http://yugui.club/index.php/welcome/test17" class="user_ziti">交易预存款变动查询</a><br>
	      	    		 <a href="http://yugui.club/index.php/welcome/test16" class="user_ziti">交易预存款余额查询</a>
	      	    	</div>
	      	    </div>
	      </div>
      </div>
		</div>
		<div class="midd">
	        <?php $this->load->view($p); ?>
		</div>
	</div>
</div>
</body>

<script type="text/javascript">
	$(document).ready(function(){
	    $(".user_im").click(function(){
	  	     $(".user_im2").show();
	         $(".user_im").hide();
             $(".user_info2").show();
	      });
	    $(".user_im2").click(function(){
	  	     $(".user_im").show();
	         $(".user_im2").hide();
             $(".user_info2").hide();
	      });
	    $(".tr_img").click(function(){
	  	     $(".tr_img2").show();
	         $(".tr_img").hide();
             $(".dfdfg_info").show();
	      });
	    $(".tr_img2").click(function(){
	  	     $(".tr_img").show();
	         $(".tr_img2").hide();
             $(".dfdfg_info").hide();
	      });
	 });
</script>