<style lang="scss" src="./cssys/xinxin.scss"></style>
<style lang="scss" src="./cssys/wujx.scss"></style>
<style lang="scss" src="./cssys/jjjux.scss"></style>
<template>
	<div>
		<div class="bgc">
			<!-- <div class="jt">
				<div class="talkbubble"></div>
			</div> -->
			<div class="yuelbk">
				<img src="./i/xx2.png" class="xx2 xx">
				<img src="./i/xx2.png" class="xx3 xx">
				<img src="./i/xx5.png" class="xx15 xx">
			    <div class="yuel"></div>
			    <img src="./i/xx2.png" class="xx4 xx">
			    <img src="./i/xx3.png" class="xxx xx9">
				<img src="./i/xx4.png" class="xxx xx10">
			</div>
			<div>
				<img src="./i/xx1.png" class="xx1 xx"><br>
				<img src="./i/xx2.png" class="xx xx6">
			</div>
			<div class="liux">
				<img src="./i/xx3.png" class="xxx xx7">
				<img src="./i/xx4.png" class="xxx xx8">
				 <div class="star"></div>
			</div>
			<div class="liux2">
				<img src="./i/xx3.png" class="xxx x">
				<img src="./i/xx4.png" class="xxx x1">
				<div class="star"></div>
				<img src="./i/sx2.png" class="shuanx">
			</div>
			<!-- <div class="bangks">
			     <div class="heart2"></div>
			     <div class="heart"></div>
			     <div class="heart2"></div>
			     <div class="heart"></div>
			</div>
			<div class="bangkss2">
				<div class="star-five"></div>
			</div>
			<div class="bangkss">
			     <div class="heart"></div>
			     <div class="heart2"></div>
			     <div class="heart"></div>
			     <div class="heart2"></div>
			</div> -->
		</div>
	</div>
</template>

<style lang="scss" scoped>
.bgc {
	width: rem(755);
	height: rem(1336);
	background-color:black;
    background-image: radial-gradient(white, rgba(275,275,275,.2) rem(2),transparent rem(40)),radial-gradient(white, rgba(275,275,275,.15) rem(1),transparent 30px),radial-gradient(white, rgba(275,275,275,.1) rem(2),transparent rem(40)),radial-gradient(rgba(275,275,275,.4), rgba(275,275,275,.1) rem(2), transparent rem(30));
    background-size: rem(550) rem(550), rem(350) rem(350), rem(250) rem(250),rem(150) rem(150);
    background-position: 0 0, rem(40) rem(60), rem(130) rem(270), rem(70) rem(100);
    }
.bangks {
	display: inline-block;
	padding-top: rem(100);
	margin-left: rem(25);
}
.bangkss {
   padding-top: rem(100);
   display: inline-block;
   float:right;
}
.bangkss2 {
	display: inline-block;
}
.yuelbk {
	padding-top: rem(50);
}
.yuel {
	width: rem(150);
	height: rem(150);
	border-radius: 50%;
	box-shadow: rem(20) rem(20) 0 0 #FFFFE0;
	margin-left: rem(100);
	margin-top: rem(-70);
}
.star {
    display: block;
    width: rem(4);
    height: rem(4);
    border-radius: 50%;
    background: #FFF;
    top: rem(50);;
    left: rem(400);;
    position: relative;
    box-shadow: 0 0 rem(5) rem(5) rgba(300,300,300,.4);
}
.star:after {
    content: '';
    display: block;;
    border: rem(0) solid #fff;
    border-width: rem(2) rem(100) rem(4) rem(100);
    border-color: transparent transparent transparent rgba(300,300,300,.4);
}
.liux2 {
	margin-top: rem(60);
	margin-left: rem(70);
}
.xxx {
	width: rem(45);
	height: rem(35);
}
.xx {
	width: rem(30);
	height: rem(20);
}
.xx1 {
	margin-left: rem(140);
}
.xx2 {
	margin-left: rem(30);
}
.xx3 {
  margin-top: rem(30);
}
.xx4 {
	margin-top: rem(60);
	margin-left: rem(30);
}
.x {
	margin-left: rem(70);
}
.x1 {
	margin-top: rem(50);
	margin-left: rem(160);
}
.xx6 {
	margin-left: rem(200);
	margin-top: rem(-190);
}
.xx7 {
	margin-left: rem(400);
}
.xx8 {
	margin-left: rem(200);
	margin-top: rem(-140);
}
.xx9 {
	float: right;
    margin-top: rem(-90);
}
.xx10 {
	margin-left: rem(400);
	margin-top: rem(-100);
}
.xx15 {
	width: rem(50);
	height: rem(45);
	margin-left: rem(400);
}
.xx16 {
    width: rem(90);
	height: rem(65);
	margin-left: rem(300);
	margin-top: rem(40);
}
.shuanx {
	width: rem(220);
	height: rem(180);
	margin-left: rem(140);
}
</style>
