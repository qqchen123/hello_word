<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
.yuguilogin {
   width:1920px;
   height:890px;
   background: url(/public/image/yuguibg2.jpg)no-repeat;
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
     height: 680px;
     border: 0px solid #b0c0db;
     float: right;
}
.bigborder {
	width: 580px;
    height:580px;
    margin-left: 10%;
    padding-top: 7%;
}
.userid-font {
	margin-top: 5%;
	width: 600px;
}
.house {
	 position: absolute;
	 display: inline-block;
     margin-top: -25px;
     margin-left:-10px;
}
.fout {
	position: relative;
    margin-left: 35%;
    color: white;
	font-size: 30px;
	font-family: '宋体';
	outline:medium;
	border:0px;
	background-color: #8EABE3;
}
.line {
	width: 579px;
	height: 3px;
	margin-top: 3%;
	background-color: white;
}
.userid {
	display: block;
	padding-top: 6%;
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
    margin-left: 12%;
    float: right;
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
    margin-left: 40%;
    color: red;
}
.loginbut {
	width: 437px;
	height: 77px;
	margin-left: 65%;
	border-radius: 10px;
	background: #356aca;
	line-height: 77px;
	text-align: center;
	font-size: 30px;
	color: white;
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
        		          <img src="/public/image/house.png" class="house"> 
        		          <input type="text" class="fout" placeholder="请输入商户号"/>
        		      </div>
        		      <div class="line"></div>
        	     </div>
        	     <div class="userid">
        		     <div class="userid-font">
        		          <img src="/public/image/people.png" class="house"> 
        		          <input type="text" class="fout" placeholder="请输入登录名" />
        		      </div>
        		      <div class="line"></div>
        	     </div>
        	     <div class="userid">
        		     <div class="userid-font">
        		          <img src="/public/image/word.png" class="house"> 
        		          <input type="text" class="fout" placeholder="请输入密码">
        		      </div>
        		      <div class="line"></div>
        	     </div>
        	     <div class="userid">
        		     <div class="yzm">
        		          <input placeholder="         验证码" class="yzm-font" type="text">
        		      </div>
        		      <div class="yzm-get"></div><br><br>
        		      <input type="checkbox" class="chex">
                      <label style="color:white;margin-left:20px;">我已阅读并同意《xxxxxxx》</label>
                      <a class="forgetpwd">忘记密码？</a>
        	     </div>
        	</div>
       </div>
       <a href="/index.php/welcome/test2" >
       	   <div class="loginbut">
        	登&nbsp;&nbsp; 录
           </div>
       </a>
    </div>
</body>

