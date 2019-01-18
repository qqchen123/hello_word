<style type="text/css">
.midd_bk {
	margin-top: 3%;
 	width:100%;
 	height: 55%;
 	border:3px solid #385b99;
 	margin-left: 5%;
 }
 .textre {
 	margin-top: 10px;
	width:98%;
	table-layout:fixed;
	empty-cells:show; 
	border-collapse: collapse;
}
th{
	  height:55px;
      font-size: 16px;
      color: #292f3b;
      margin-top: 2%;
      padding-left: 2%;
}
.lines {
	width: 100%;
	border: 3px solid #385b99;
	height: 50px;
	border-top: 0px;
 	border-left: 0px;
}
.lefts {
	display: inline-block;
	width: 20px;
	height: 50px;
	font-size: 40px;
	color: #385b99;
}
.page {
	display: inline-block;
	width: 130px;
	height: 50px;
	border: 0px red solid;
}
.input_css {
	width: 33px;
	height: 33px;
	border: 1px solid #385b99;
	font-size: 18px;
	margin-left: 5%;
	font-weight: bold;
}
.label_css {
	font-size: 30px;
	color: #385b99;
	margin-left: 5%;
}
.danpage {
	position: absolute;
	display: inline-block;
	width: 50%;
	height: 50px;
	border: 2px solid #385b99;
	border-top: 0px;
	border-bottom:0px;
	margin-left: 2%;
	margin-top: -2px;
}
.label_css2 {
	display: inline-block;
	padding-top: 10px;
	font-size: 16px;
	margin-left: 4%;
}
.bk_button {
	margin-top: 2%;
	margin-left: 25%;
}
.button_css {
   display: inline-block;
   width: 150px;
   height: 50px;
   border: 0px solid red;
   border-radius: 25px;
   line-height: 50px;
   text-align: center;
   background: #356ACA;
   color: white;
   font-size: 16px;
   margin-top: 10px;
   margin-left: 20px;
}
</style>
<div>
	<div class="bk_button">
		<!-- button -->
		<a href="<?php echo site_url('/');?>welcome/toE1"><div class="button_css">返 回</div></a>
		<a href="<?php echo site_url('/');?>welcome/toE3"><div class="button_css">审核通过</div></a>
		<a href="<?php echo site_url('/');?>welcome/toE4"><div class="button_css">审核失效</div></a>
	</div>
		<!-- 内容显示 -->
	<div class="midd_bk">
		<table class="textre">
			<tr>
				<th>商户订单号</th>
				<th>收款人</th>
				<th>交易金额</th>
				<th>账号</th>
				<th>开户行名称</th>
				<th>个人收款账户类型</th>
				<th>用途</th>
				<th>备注</th>
			 </tr>
			 <tr>
				<th> 2018/10/16 10:00 </th>
				<th> ABCD1234 </th>
				<th>  </th>
				<th> 进账</th>
				<th> ABCD1234 </th>
				<th>  </th>
				<th> 进账</th>
				<th> 23</th>
			 </tr>
			 <tr>
				<th> 2018/10/16 10:00 </th>
				<th> ABCD1234 </th>
				<th>  </th>
				<th> 进账</th>
				<th>f34 </th>
				<th>34r</th>
				<th> efg</th>
				<th>eg </th>
			 </tr>
         </table>
	</div>
</div>
