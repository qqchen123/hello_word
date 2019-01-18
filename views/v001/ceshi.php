<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
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
	<div class="logo_fother">
		<div class="log_l">
			<div class="fother_o"></div>
			<div class="fother_o"></div>
			<div class="fother_o"></div>
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
#yem_concer {
	width: 100%;
	height: 661px;
}
.logo_fother {
	width: 100%;
	height: 320px;
	background: #282E3A;
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
.log_l {
	width: 80%;
	height: 320px;
	margin-left: 23%;
}
.fother_o {
	display: inline-block;
	width: 260px;
	height: 300px;
	background: #00B7ED;
	margin-left: 3%;
	margin-top: 15px;
}
.fother_o:hover {
	width: 290px;
	height: 340px;
}
</style>
<script type="text/javascript">
var index=1;//每张图片的下标，
window.onload=function(){
    var start=setInterval(autoPlay,800);//开始轮播，每秒换一张图
	    $('#yem_concer').onmouseover=function(){//当鼠标光标停在图片上，则停止轮播
	       clearInterval(start);
	    }
	    $('#yem_concer').onmouseout=function(){//当鼠标光标停在图片上，则开始轮播
	        start=setInterval(autoPlay,800);
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
