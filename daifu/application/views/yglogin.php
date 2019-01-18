<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
.input_img {
    display: inline-block;
    width: 60px;
    height: 60px;
    float: left;
}
.clear {
    clear: both;
}
.yuguilogin {
   width:1920px;
   height:890px;
   background: url(/assets/daifu/image/yuguibg2.jpg)no-repeat;
}
.yuguifont {
    display:inline-block;
    width: 900px;
    border: 0px solid #b0c0db;
}
.font-z {
	margin-left: 22%;
    font-size: 48px;
    color: #b0c0db;
    font-family: '黑体';
     padding-top: 70%;
}
.yuguilogininfo {
	display: inline-block;
     width: 850px;
     border: 0px solid #b0c0db;
     float: right;
}
.bigborder {
	width: 580px;
    margin-left: 10%;
    padding-top: 12%;
    padding-bottom: 5%;
}
.userid-font {
	margin-top: 2%;
	width: 500px;
}
.house {
	 position: absolute;
	 display: inline-block;
     margin-top: -25px;
     margin-left:-10px;
}
.fout {
	position: relative;
    margin-left: 5%;
    color: white;
	font-size: 30px;
	font-family: '宋体';
	outline:medium;
	border:0px;
	background-color: #8EABE3;
    float: left;
    line-height: 60px;
}
.line {
	width: 479px;
	height: 3px;
	margin-top: 1%;
	background-color: white;
}
.userid {
	display: block;
	padding-top: 3%;
}
.yzm {
	display: inline-block;
    width: 256px;
    height:69px;
    border: 3px solid white;
}
.yzm-font {
	font-size: 30px;
	color: white;
	width: 256px;
    height:69px;
    background-color: #8EABE3;
    border: 0px;
}
.yzm-get {
	display: inline-block;
	width: 180px;
    height:60px;
    border: 3px solid white;
    margin-left: 4%;
    float: left;
}
.chex {
    position: absolute;
	width: 19px;
	height: 19px;
	border: 1px solid white;
	margin-left: -3px;
    margin-top: 3px;
}
.forgetpwd {
    margin-left: 20%;
    color: red;
}
.loginbut {
	width: 437px;
	height: 77px;
	margin-left: 15%;
	border-radius: 10px;
	background: #356aca;
	line-height: 77px;
	text-align: center;
	font-size: 30px;
	color: white;
}
.loginbut:hover {
    cursor:pointer;
}
a {
	text-decoration:none;
}
</style>
<body>
    <div class="yuguilogin">
        <div class="yuguifont">
        	<div class="font-z">
                上海钰桂信息科技有限公司
             </div>
        </div>
        <div class="yuguilogininfo">
        	<div class="bigborder">
        	    <div class="userid">
        		     <div class="userid-font">
        		          <img class="input_img" src="/assets/daifu/image/house.png" class="house"> 
        		          <input type="text" class="fout" id="b_num" value="123" placeholder="请输入商户号"/>
                          <div class="clear"></div>
        		      </div>
        		      <div class="line"></div>
        	     </div>
        	     <div class="userid">
        		     <div class="userid-font">
        		          <img class="input_img" src="/assets/daifu/image/people.png" class="house"> 
        		          <input type="text" class="fout" id="username" value="cup" placeholder="请输入登录名" />
                          <div class="clear"></div>
        		      </div>
        		      <div class="line"></div>
        	     </div>
        	     <div class="userid">
        		     <div class="userid-font">
        		          <img class="input_img" src="/assets/daifu/image/word.png" class="house"> 
        		          <input type="text" class="fout" id="password" value="cup" placeholder="请输入密码">
                          <div class="clear"></div>
        		      </div>
        		      <div class="line"></div>
        	     </div>
        	     <div class="userid">
        		     <!-- <div class="yzm">
        		          <input placeholder=" 验证码" id="verify" class="yzm-font" type="text">
        		      </div>
        		      <div class="yzm-get"></div><br><br> -->
        		      <input type="checkbox" class="chex">
                      <label style="color:white;margin-left:20px;">我已阅读并同意《xxxxxxx》</label>
                      <a class="forgetpwd">忘记密码？</a>
        	     </div>
        	</div>
            <div class="loginbut" id="login_btn" onclick="login()">登<span style="display: inline-block;width: 20px;">&nbsp;</span>录</div>
       </div>
        <!--       <a href="/index.php/welcome/test2" >-->
    </div>
</body>
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script>
    function login(){
        let b_num = $('#b_num').val();
        let username = $('#username').val();
        let password = $('#password').val();
        let verify = $('#verify').val();
        $.ajax({
            type: "POST",
            url: 'check_login_info',
            data:{b_num,username,password,verify},
            dataType: "json",
            success(data) {
                if (data.code==0) {
                    alert(data.msg);
                }else{
                    window.location.href="<?php echo site_url('/');?>Welcome/index"
                }
            }
        });
    }

</script>

