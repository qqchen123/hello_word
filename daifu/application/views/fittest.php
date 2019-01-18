<body>
	<div class="top_css">
		<div class="top_css2">
			<a href="#">论坛/客服</a>
		</div>
    </div>
	<div class="logo_header">
		<div class="logo_n">
			<img src="/assets/daifu/image/gw3.png" class="gws">
		</div>
		<div class="logo_font">
			<a class="shoue pege1">首页</a>
			<a class="shoue pege2">关于我们</a>
			<a class="shoue pege2">产品中心</a>
			<a class="shoue pege2">合作案例</a>
			<a class="shoue pege2 all">联系我们</a>
		</div>
		<div class="logo_font2">
			<div class="outover">
				<img src="/assets/daifu/image/guanwalianx.PNG">
			</div>
		</div>
		<div class="logo_btn">
			<div class="login">我 要 进 件</div>
		</div>
	</div>
	<div id="yem_concer" align="center">
	    <img src="/assets/daifu/image/j1.jpg" class="dge" style="display: block;">
	   <img src="/assets/daifu/image/j2.jpg"  class="dge" style="display:none;">
	    <img src="/assets/daifu/image/j3.jpg" class="dge" style="display: none;">
	   <div id="buttons">
	     <span class="spanwe"></span>
  	     <span class="spanwe"></span>
	     <span class="spanwe"></span>
	   </div>
	</div>
	<div class="div_text">
		<div>
			<img src="/assets/daifu/image/i178.png" class="imge">
		</div>
	</div>
	<di v class="divdfk_css">
		<div class="div_do div_d1">
			<img src="/assets/daifu/image/jfan1.png">
		</div>
		<div class="div_do">
			<img src="/assets/daifu/image/fan2.png">
		</div>
		<div class="div_do">
			<img src="/assets/daifu/image/fan3.png">
		</div>
		<div class="div_d2">
			<div class="div_texts">
				媒体报道
			</div>
			<img src="/assets/daifu/image/i112.png" class="imgse">
			<div class="div_texts3"></div>
		</div>
	</div>
</body>
<script src="/assets/daifu/js/jquery.min.js"></script>
<script type="text/javascript">
var index=1;//每张图片的下标，
window.onload=function(){
    var start=setInterval(autoPlay,800);//开始轮播，每秒换一张图
	    $('#yem_concer').onmouseover=function(){
	    //当鼠标光标停在图片上，则停止轮播
	       clearInterval(start);
	    }
	    $('#yem_concer').onmouseout=function(){
	    //当鼠标光标停在图片上，则开始轮播
	        start=setInterval(autoPlay,1200);
	    }
	    var lis=document.getElementsByClassName('spanwe');
	    //得到所有圆圈
	    //当移动到圆圈，则停滞对应的图片
	    var funny = function(i){
	        lis[i].onmouseover = function(){
	            changeImg(i)
	        }
	    }
	    for(var i=0;i<lis.length;i++){
	        funny(i);
	    }
}
function autoPlay(){
    if(index > 2){
        index=0;
    }
    changeImg(index++);
}
//对应圆圈和图片同步
function changeImg(index){
    var list=document.getElementsByClassName('dge');
    var listy=document.getElementsByClassName('spanwe');
    for(i=0;i<list.length;i++){
        list[i].style.display='none';
        listy[i].style.background='#333';
    }
       list[index].style.display='block';
       listy[index].style.background='white';
  }
</script>
<script type="text/javascript">
$(document).ready(function(){
	  $(".all").mouseover(function(){
	      $(".outover").show();
	  });
	  $(".all").mouseout(function(){
	      $(".outover").hide();
	  });
});
</script>
<style type="text/css">
body {
	background: #EFF3FF;
}
.top_css {
	width: 100%;
	height: 30px;
	background: #313B50;
	border: 0px red solid;
}
.top_css2 a {
	width: 100px;
	height: 30px;
	border: 0px red solid;
    font-size: 14px;
    float: right;
    text-decoration: none;
    color: white;
    line-height: 30px;
}
.top_css2 a {
	clear: both;
}
.logo_header {
    display: block;
	width: 100%;
	height: 80px;
	background: white;
	border:0px solid blue;
}
.logo_n {
	display: inline-block;
}
.logo_font {
	display: inline-block;
	position: absolute;
	width:65%;
	height: 80px;
	border: 0px red solid;
	color: black;
}
.logo_font2 {
	position: absolute;
	width:70%;
	height: 80px;
	border: 0px red solid;
	color: black;
}
.pege1 {
	color: #33A0FC;
}
.pege2:hover {
	color: #33A0FC;
}
.shoue {
	margin-left: 11%;
	line-height: 80px;
	font-size: 18px;
}
.outover {
	position: absolute;
	display: none;
	margin-left: 85%;
}
.logo_btn {
	float: right;
	width: 280px;
	height: 70px;
	border: 0px red solid;
}
.login {
   background: #313B50;
   border:0px;
   width: 170px;
   height:40px;
   margin-left: 15px;
   margin-top: 15px;
   border-radius: 20px;
   line-height: 40px;
   text-align: center;
   color: white;
}
#yem_concer {
	margin-top: 8px;
	width: 100%;
	height: 611px;
	border: 0px solid black;
}
.dge {
	width: 100%;
	height: 604px;
}
#buttons{
  	position:absolute;
  	height:10px;
  	width:100%;
  	z-index:2;
  	left:45%;
  	margin-top: -50px;
}
#buttons span{
  	cursor:pointer;
    float:left;
    border:1px solid #fff;
    width:10px;
    height:10px;
    border-radius:10px;
    background:#333;
    margin-right:5px;
    margin-left: 40px;
}
.div_text .imge {
	width: 100%;
	height: 378px;
	border: 0px solid pink;
}
.divdfk_css {
	width: 100%;
	height: 330px;
	border: 0px solid #313B50;
	margin-top: 10px;
}
.div_d1 {
	margin-left: 1%;
}
.div_do {
	display: inline-block;
	width: 26%;
	height: 306px;
	margin-left: 5%;
	background: white;
}
.div_texts {
	font-size: 25px;
	font-weight: bold;
}
.imgse {
	width: 100%;
}
.div_do img{
	width: 100%;
	height: 306px;
}
.div_d2 {
	margin-top: 30px;
}
.div_do img:hover{
	width: 105%;
	height: 316px;
}
.div_texts3 {
	width: 100%;
	height: 100px;
	background: #313B50;
}
</style>