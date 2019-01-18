<style type="text/css">
	.head_divstyle {
		width: 100%;
		height: 80px;
		background: #2b3a4b;
	}
    .head_user {
      width: 600px;
      height: 25px;
      background: -webkit-linear-gradient(#516a86,#1c3149);
      background: -o-linear-gradient(#516a86,#1c3149);  
      background: -moz-linear-gradient(#516a86,#1c3149);
      background: -mos-linear-gradient(#516a86,#1c3149);
      background: linear-gradient(#516a86,#1c3149);
      border-radius:40px;
      margin-top:-37px;
      margin-left: 300px;
   }
     .logo {
       margin-top:17px;
     }
    #username {
	    font-size:12px;
	    color:white;
	    height:20px;
	    display:block;
	    margin-left: 20px;
            }
    #logtime {
      color:white;
      height:20px;
      display:block;
      font-size:12px;
      margin-left: 50px;
    }
     #system {
      color: white; 
      text-decoration:none;
      font-size: 12px;
      margin-left: 50px;
     }
   .head_quit {
     margin-left:calc(100% - 130px);
   	 margin-top:-25px;
    }
 #btn {
      width: 100px;
      height: 25px;
      background: #15304e;
      border-radius: 15px;
      color: white;

 }

 #btn:hover{
 	    color:#094daa;
 	    background: -webkit-linear-gradient(#ffffff,#b4b0b0);
      background: -o-linear-gradient(#ffffff,#b4b0b0);  
      background: -moz-linear-gradient(#ffffff,#b4b0b0);
      background: -mos-linear-gradient(#ffffff,#b4b0b0);
      background: linear-gradient(#ffffff,#b4b0b0);
 	    cursor: pointer;
 	
 }
 .logotp {
      display: inline-block;
      float: left;
 }
 .dateuser {
      display: inline-block;
      height: 32px;
      float: left;
      line-height: 32px;
      margin-top: -4px;
 }
 .daterq {
     display: inline-block;
     float: left;
     line-height: 32px;
     height: 32px;
     color:white;
     margin-left: 8px;
      margin-top: -4px;
 }
</style>
  <div class="head_divstyle">
  	 <div class="head_div"> 
          <img class="logo" src="/assets/images/web/head/logo.png" />
          <div class="head_user"> 
              <span class="logotp"><img style="margin-left: 30px;margin-top:-5px;" src="/assets/images/web/head/icon-admin.png" /> </span>
              <span class="dateuser" id="username">当前用户 ： <?= $name?> </span> 
              <span class="dateuser" id="logtime">登录时间： <span id="logtime_content" ><?= $logintime?></span></span>
              <span class="daterq" id="week"></span>
              <a class="dateuser" href=""  id="system">系统设置 </a>
              <div style="clear: both;"></div>
          </div>
          <div class="head_quit">
          	<input style="line-height: 20px;" type="button" value="退出" id="btn" onclick="loginout()" />
          </div>
  	 </div>

  </div>   
<script type="text/javascript">
    function loginout() {
      window.location.href = "index.php/Auth/logout";
    }

</script> 

<script type="text/javascript">  
   var login_date = document.getElementById("logtime_content").innerText;
   var date = login_date;    //此处也可以写成 17/07/2014 一样识别    也可以写成 07-17-2014  但需要正则转换 
   var day = new Date(Date.parse(date));   //需要正则转换的则 此处为 ： var day = new Date(Date.parse(date.replace(/-/g, '/')));
   var today = new Array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
   var week = today[day.getDay()];
   document.getElementById("week").innerText = week;
</script>