<?= tpl('admin_applying')?>
<style type="text/css">
/*@media screen and (min-width: 600px) { 当屏幕尺寸小于600px时，应用下面的CSS样式
  .aa {
    padding-left: 30%;
  }
}
@media screen and (min-width: 1400px) { 当屏幕尺寸小于600px时，应用下面的CSS样式
  .aa {
    padding-left: 10%;
  }
}
@media screen and (min-width: 1900px) { 当屏幕尺寸小于600px时，应用下面的CSS样式
  .aa {
    padding-left: 40%;
  }
}*/
/*
#img_3png {
   	 margin-top: -370px;
   	 width: 100%;
   	 margin-left: -10px;
     position: relative;
   }
   #massger {
   	   font-size: 22px;
   	   color: white;
   	   margin-top: -528px;
   	   margin-left: 460px;
   	   filter: opacity(70%);
      position: relative;
   }
   #userlogin {
      width:271px; 
      height:250px;
      margin-top: -81px;
   	  margin-left: calc(100% - 20%);
      background: -webkit-linear-gradient(#285e90,#3470a5);  
     background: -o-linear-gradient(#285e90,#3470a5); 
     background: -moz-linear-gradient(#285e90,#3470a5); 
     background: -mos-linear-gradient(#285e90,#3470a5); 
     background: linear-gradient(#285e90,#3470a5); 
     filter: opacity(90%);
   }

   .logins1 {
   	 font-size: 20px;
   	 color: white;
   	 margin-left: 85px;
   	 margin-top: 15px;

   }
   #flatbad {
   	 width: 480px;
   	 height: 100px;
   	 margin-top:-387px;
   	 margin-left: 5px;
   	 border-radius: 90px 0 0 0px;
     position: relative;
     filter: opacity(25%);
     background-image: url('/assets/images/web/login/2.png');
     background-repeat: no-repeat;
     z-index: 990;
   }
   .spanflatbad {
        font-size: 25px;
        color: black;
        margin-top: -389px;
        margin-left: 360px;
       position: relative;
       z-index: 999;
   }*/
 
    #loginpages {
	    width: 100%;
	    background-image: url('/assets/images/web/login/bgrod1.jpg');
	    background-repeat: no-repeat;
	    background-size: 100% 100%;
	}

   #img_3png {
     margin-top: 80px;
     margin-left: 20px;
   }
   #massger {
   	font-size: 20px;
   	color: white;
   }
   #base {
   	margin-top: 50px;
   	margin-left: -45px;
   }
  .spanflatbad {
  	font-size: 25px;
   margin-top: -60px;
   margin-left: 40px;
   position: relative;
  }
    #sdfrwds {
     width: 100%;
     height: 257px;
     background:-webkit-linear-gradient(0,#386ca3,#194f88);
     filter: opacity(30%);
    }
    #dsa {
       margin-top: -295px;
       margin-left: 30%;
    }
    #userlogin {
      width:271px; 
      height:257px;
      margin-top: -260px;
      margin-left:55%;
      background: -webkit-linear-gradient(#285e90,#3470a5);  
      background: -o-linear-gradient(#285e90,#3470a5); 
      background: -moz-linear-gradient(#285e90,#3470a5); 
      background: -mos-linear-gradient(#285e90,#3470a5); 
      background: linear-gradient(#285e90,#3470a5); 
      filter: opacity(90%);
   }
   .logins1 {
   	
   	text-shadow:2px 2px 1px #000;
   	   font-size: 26px;
       color: white;
       margin-top: 30px;
       margin-left: 70px;
   }
  input {
  	margin-top: 25px;
  	margin-left: 22px;
  	width: 230px;
  	height: 31px;
    border-radius:10px;
  }
  .longsu {
  	 font-size:22px; 
  	 color: white;
  	 height: 35px;
     font-family: "宋体";
     border:0px solid;
     font-weight:normal; 
     background-color: #239fff;
     /*background: -webkit-linear-gradient(#5092c4,#386ca3);  
       background: -o-linear-gradient(#5092c4,#386ca3); 
       background: -moz-linear-gradient(#5092c4,#386ca3);
        background: -mos-linear-gradient(#5092c4,#386ca3);
        background: linear-gradient(#5092c4,#386ca3);*/
  }
  .longsu:hover {
      background: -webkit-linear-gradient(#0085d0,#0d52b1);  
      background: -o-linear-gradient(#0085d0,#0d52b1);
      background: -moz-linear-gradient(#0085d0,#0d52b1); 
      background: -mos-linear-gradient(#0085d0,#0d52b1);
      background: linear-gradient(#0085d0,#0d52b1);
    	cursor: pointer;
}
</style>

<div id="loginpages" style="height:100%;padding-top: 150px;">
	<div id="sdfrwds" ></div>
    <div id="dsa" class="aa" style="display: inline-block;float:left;"><!-- 中间 -->
    	<div style="display: inline-block;float:left;width: 800px;"><!--中间左边-->
	    	<div ><!--中间左一-->
		    	<div id="img_3png" style="display:inline-block;" >
			    	<img src="/assets/images/web/login/1.png" style="width:150px;height: 60px;"> <!--logo-->
			    </div>
			    <div id="massger" style="display: inline-block;">|&nbsp; 企业综合管理平台 </div><!--logo旁边的字-->
		    </div>
		    <div id="flatbad" ><!-- logo下面的一段字 --><!--中间左二-->
				 <img id="base" src="/assets/images/web/login/base.png">
				<h4 class="spanflatbad" style="display: inline-block; float: left;">
				提&nbsp;供&nbsp;安&nbsp;全&nbsp;保&nbsp;障&nbsp;的&nbsp;借&nbsp;贷&nbsp;平&nbsp;台
			  </h4>
			</div> 
	    </div>
	    
	    <div ><!--中间右边-->
	    	<form ><!--表单-->
				<div id="userlogin" style="display: inline-block;float:left;">
				  	<span class="logins1" style="display: inline-block;float: left;">欢&nbsp;迎&nbsp;登&nbsp;录</span><br/>
			  	     	<input type="text" placeholder="  账号" id="loginUsrname" /> <br>
			   	     	<input type="password" placeholder="  密码" id="loginUsrpass"/> <br>
			     		<input  class="longsu" type="button" value="登录" id="loginBtn">
			     	<div style="clear: both;"></div>  
				</div>
				<div style="clear: both;"></div>
			</form>
	    </div>
	    <div style="clear: both;"></div> 
    </div><!--中间底色-->
</div> 
<script src="/assets/seajs.js"></script>
<script src="/assets/seajsConfig.js"></script>
<script src="/assets/lib/js/layer/layer.js"></script>
<script>
    seajs.use('apps/login')
</script>
