<div>
	<div class="logo_header">
		<div class="logo_n">
			<img src="/assets/images/web/ofnetwork/gw3.png" class="gws">
		</div>
		<div class="logo_font">
			<label class="shoue">首页</label>
			<label class="shoue">关于我们</label>
			<label class="shoue">产品中心</label>
			<label class="shoue">合作案例</label>
			<label class="shoue">联系我们</label>
		</div>
	</div>
	<div id="yem_concer" align="center">
			    <img src="/assets/images/web/ofnetwork/gw5.jpg" class="dge" style="display: block;">
			   <img src="/assets/images/web/ofnetwork/g1.jpg"  class="dge" style="display:none;">
			    <img src="/assets/images/web/ofnetwork/gw4.jpg" class="dge" style="display: none;">
			   <div id="buttons">
			     <span class="spanwe"></span>
	      	     <span class="spanwe"></span>
			     <span class="spanwe"></span>
			   </div>
	 </div>
	<div class="text_concer">
		 <div class="font_concer">
		 	<label class="jd">九盾联盟</label>
		 	<label class="df">| 风控审核 个人借款标</label>
		 </div>
		 <div class="neir1"></div>
		 <div class="neir1"></div>
		 <div class="neir1"></div>
	</div>
	<div class="gs_foother">
		<div class="company">公司介绍</div>
		<div class="company_img">
			<img src="/assets/images/web/ofnetwork/31.png" class="company_p">
			<div class="company_text"></div>
			<img src="/assets/images/web/ofnetwork/32.png" class="company_p2">
			<div class="company_text"></div>
		</div>
	</div>
	<div class="foother">
		<div class="company">媒体报道</div>
		<img src="/assets/images/web/ofnetwork/33.png" class="sdf">
		<div class="fothers">
			<div class="ourme our1">
				<label>关于我们</label><br><br>
				<label>公司简介</label><br>
				<label>管理团队</label><br>
				<label>公司新闻</label>
			</div>
			<div class="ourme our2">
				<label>业务介绍</label><br><br>
				<label>工作原理</label><br>
				<label>客户来源</label><br>
				<label>商务合作</label>
			</div>
			<div class="ourme our2">
				<label>安全保障</label><br><br>
				<label>法律法规</label><br>
				<label>安全保障</label><br>
				<label>反舞弊专线</label>
			</div>
			<div class="img_concers">
			   <img src="/assets/images/web/ofnetwork/34.png">
		    </div>
		</div>
	</div>
</div>
<style type="text/css">
.logo_header {
	display: block;
	width: 100%;
	height: 80px;
	background: #060606;
}
.logo_n {
	display: inline-block;
	width: 300px;
	height: 80px;
}
.gws {
	width: 184px;
	height: 74px;
	margin-left: 15%;
}
.logo_font {
	display: inline-block;
	width: 600px;
	height: 80px;
	color: #dbae2f;
	float: right;
}
.shoue {
	display: inline-block;
	margin-left: 35px;
	height: 80px;
	line-height: 80px;
}
.dge {
	width: 100%;
	height: 660px;
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
#yem_concer {
	width: 100%;
	height: 661px;
}
.text_concer {
	width: 100%;
	height: 400px;
	background: #EFF3FF;
	padding-top: 30px;
}
.jd {
	font-size: 18px;
	font-weight: bold;
	margin-left: 12px;
}
.df {
	margin-left: 10px;
}
.neir1 {
	display: inline-block;
	width: 26%;
	height: 270px;
	background: #FFFFFF;
	margin-top: 2%;
	margin-left: 5%;
}
.neir1:hover {
	width: 30%;
	height: 300px;
}
.gs_foother {
	width: 100%;
	height: 375px;
	background: #FFFFFF;
}
.company {
	width: 300px;
	height: 60px;
	padding-top: 30px;
	font-size: 20px;
	margin-left: 12px;
}
.company_img {
	margin-left: 5%;
}
.company_p {
	display: inline-block;
}
.company_p2 {
	display: inline-block;
	margin-left: 5%;
}
.company_text {
	display: inline-block;
	width: 30%;
	height: 213px;
	background: #EFF3FF;
	margin-left: -10px;
}
.sdf {
	width: 99.9%;
}
.foother {
	width: 100%;
	height: 300px;
	background: #FFFFFF;
}
.fothers {
	width: 100%;
	height: 300px;
	background: #313B50;
}
.ourme {
	display: inline-block;
	color: white;
	margin-top: 25px;
}
.our1 {
	margin-left: 30%;
}
.our2 {
	margin-left: 10%;
}
.img_concers {
	margin-top: 2%;
	margin-left: 23%;
}
</style>
<script type="text/javascript">
var index=1;//每张图片的下标，
window.onload=function(){
    var start=setInterval(autoPlay,1000);//开始轮播，每秒换一张图
	    $('#yem_concer').onmouseover=function(){//当鼠标光标停在图片上，则停止轮播
	       clearInterval(start);
	    }
	    $('#yem_concer').onmouseout=function(){//当鼠标光标停在图片上，则开始轮播
	        start=setInterval(autoPlay,1000);
	    }
	    var lis=document.getElementsByClassName('spanwe');//得到所有圆圈
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