<?= tpl('admin_applying')?>
<style type="text/css">
 #loginpage2 {
    background:url('/assets/images/web/login/pagelogin3.jpg')no-repeat;
    width:100%;
    height:100%;
    background-size:100% 100%;
  }
.long_boby2 {
    margin-left: 30%;
    margin-top: 2%;
    width: 39%;
    height: 48%;
    border: 0px solid red;
}
.long_wryh {
   display:inline-block;
   margin-left:9%;
   width: 320px;
   height: 80px;
   border: 0px solid blue;
   font-size:36px;
   color:#333333;
   font-family:'微软雅黑';
   line-height: 80px;
   margin-top: 3%;
}
.login_bk {
    width: 90%;
    height: 50%;
    border: 0px solid blue;
    margin-top: 2%;
}
.long_infos {
   width:90%;
   height: 120px;
}
.long_info1 {
  font-size:17px;
  margin-left:10%;
  width:19%;
}
.user_in {
   margin-left:-18px;
}
.login_pwd {
   font-size:17px;
   margin-left:10%;
   width:19%;
}
.userinfo input{
   margin-top: 5%;
   width:63%;
   height:38px;
   background:#eeeeee;
   border:2px #fcf8f8 solid;
}
.logo img{
    width:15%;
    height:7%;
}
#addLineImg:mouseover{
 	  border: 1px red solid;
 }
.long_bom {
    margin-left:24%;
    margin-top:7%;
}
.long_img {
  	width:8%;
  	height:6.5%;
  	position:absolute;
 }
.long_img2 {
  	filter:opacity(8%);
  	width:8%;
  	height:6.5%;
  	position:absolute;
  	margin-left:-2px;
}
.long_btn {
  	position:absolute;
  	margin-left:2.2%;
  	margin-top:0.7%;
  	font-size:22px; 
  	color:#ffffff;
 }
.long_wjpwd {
   margin-left:35%;
   float:left;
   margin-top:4%;
   color:red;
   font-size:14px;
   text-decoration:underline;
  }
.weixins {
    margin-top: 3.5%;
 }
.weixins_a {
    display:inline-block;
    margin-left: 35%;
}
</style>

<script>
    String.prototype.trimSpace = function(){
        return this.replace(/\s/g,'');
    };
    if(window.parent!=self){
        window.parent.location.href='<?php echo site_url('Auth/login')?>'
    }
    var PAGE_VAR = {SITE_URL:'<?php echo site_url()?>'}
</script>

<div id="loginpage2" >
	<div class="logo">
		<img src="/assets/images/web/login/1.png">
	</div>
	<div class="long_boby2">
  		<div class="long_wryh long_wryh2">
  			   企业综合管理平台
  		</div>
      <div class="login_bk">
          <div class="long_infos">
             <div class="userinfo">
               <label class="long_info1">用户名：</label>
               <input type="text" id="loginUsrname" class="user_in"><br>
             </div>
             <div class="userinfo">
               <label class="login_pwd">密&nbsp;&nbsp;&nbsp;码：</label>
               <input type="password" id="loginUsrpass" class="user_in" >
            </div> 
          </div>
          <div class="long_bom">
            <div id="loginBtn">
               <img src="/assets/images/web/login/anniu.png" id="addLineImg" class="long_img" >
               <img src="/assets/images/web/login/anshagn1.png" class="long_img2" >
               <label class="long_btn">登 录</label>
               <div style="clear: both;"></div>
           </div>
           <a href="" class="long_wjpwd"> &nbsp;？忘记密码</a>
         </div>
      </div>
     <div class="weixins">
   		 <a class="weixins_a"><img class="aaimg" src="/assets/images/web/login/weixin.png" style="filter: opacity(60%);"></a>
     	 <img src="/assets/images/web/login/QQ.png" style="filter: opacity(60%);" class="qq">
     	 <img src="/assets/images/web/login/weibo.png" style="filter: opacity(60%);"  class="weibo">
    </div>
	</div>
</div>

<script type="text/javascript">
	$("#loginBtn").on('mouseover',function(){
		console.log(1);
		$("#addLineImg").attr("src","/assets/images/web/login/anniu2.png");
	});
	$("#loginBtn").on('mouseout',function(){
		console.log(2);
		$("#addLineImg").attr("src","/assets/images/web/login/anniu.png");
	});
	$('.aaimg').on('mouseover', function(){
		$('.aaimg').attr('style', 'filter:opacity(100%)');
	});
	$('.aaimg').on('mouseout', function(){
		$('.aaimg').attr('style', 'filter:opacity(60%)');
	});
    $('.qq').on('mouseover', function(){
		$('.qq').attr('style', 'filter:opacity(100%)');
	});
	$('.qq').on('mouseout', function(){
		$('.qq').attr('style', 'filter:opacity(60%)');
	});
	$('.weibo').on('mouseover', function(){
		$('.weibo').attr('style', 'filter:opacity(100%)');
	});
	$('.weibo').on('mouseout', function(){
		$('.weibo').attr('style', 'filter:opacity(60%)');
	});
</script>

<script src="/assets/seajs.js"></script>
<script src="/assets/seajsConfig.js"></script>
<script src="/assets/lib/js/layer/layer.js"></script>
<script>
    seajs.use('apps/login');
</script>