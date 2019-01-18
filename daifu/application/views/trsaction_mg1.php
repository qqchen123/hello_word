<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
 .big_bk {
     width: 100%;
     height: 100%;
     border:3px solid #385b99;
     margin-left: 3%;
     margin-top: 1%;
 }
 .top_bk {
 	width: 100%;
 	height: 43%;
 	border:3px solid #385b99;
 	border-top: 0px;
 	border-left: 0px;
 }
 .left_bk {
 	display: inline-block;
 	width: 90%;
 	height: 30%;
 	border:0px solid red;
 	margin-left: 10%;
    margin-top: 1%;
 }
 .start_time {
 	display: inline-block;
 	width: 45%;
 }
 .tr_info {
 	display: inline-block;
 	font-size: 20px;
 	color: #292f3b;
    width: 110px;
 }
 .tr_info2 {
 	display: inline-block;
    width: 36%;
 	height: 50px;
 	border:2px solid #356aca;
 	font-size: 16px;
 	border-radius: 20px;
 }
 .tips {
 	color: red;
 	font-size: 14px;
 	margin-left: 2%;
 }
 .query {
 	display: inline-block;
 	width: 18%;
 	height: 54px;
 	background: #356aca;
 	font-size: 24px;
 	line-height: 54px;
 	text-align: center;
 	border-radius: 30px;
 	color: #ffff;
 	margin-left: 30%;
 	margin-top: 3%;
 }
 .midd_bk {
 	width:100%;
 	height: 42%;
 	border:3px solid #385b99;
 	border-top: 0px;
 	border-left: 0px;
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
a {
	text-decoration:none;
}
</style>

<div>
	<div class="big_bk">
		<div class="top_bk">
			<div class="left_bk">
				<div class="info_text">
					<div class="start_time">
						<p class="tr_info">日期搜索</p>
                        <input type="text" class="tr_info2">
			        </div>
			        <div class="start_time">
						<p class="tr_info">截止时间</p>
						<select class="tr_info2">
							<option>日期</option>
						</select>
						<label class="tips">间隔时间不能超过一个月</label>
			          </div>
				 </div>
				 <div class="info_text">
					<div class="start_time">
						<p class="tr_info">商户批次号</p>
						<input type="text" class="tr_info2">
			        </div>
			        <div class="start_time">
						<p class="tr_info">商户订单号</p>
						<input type="text" class="tr_info2">
			        </div>
				 </div>
				 <div class="info_text">
					<div class="start_time">
						<p class="tr_info">订单性质</p>
						<select class="tr_info2">
							<option> 代付订单</option>
						</select>
			        </div>
			        <div class="start_time">
						<p class="tr_info">订单状态</p>
						<select class="tr_info2">
							<option> 已盘回</option>
						</select>
			        </div>
				 </div>
			     	<div class="query queryclick">
			     	   查 询
			         </div>
			    <!--  <div class="query queryclick">
			     	下 载
			     </div> -->
			</div>
		</div>
       <div class="midd_bk">
			<table class="textre">
				<tr>
					<th>商户批次号</th>
<!--					<th>订单状态</th>-->
					<th>总金额</th>
					<th>总笔数</th>
<!--					<th>创建时间</th>-->
<!--					<th>审核通过时间</th>-->
					<th>明细</th>
					<th>下载</th>
				 </tr>
<!--				 <tr>-->
<!--					<th> 2018/10/16 10:00 </th>-->
<!--					<th> ABCD1234 </th>-->
<!--					<th>  </th>-->
<!--					<th> 进账</th>-->
<!--					<th>   </th>-->
<!--					<th>账户充值 </th>-->
<!--					<th> <a href="--><?php //echo site_url('/');?><!--userexcel/test19"><img src="/assets/daifu/image/mingxi.png"></a></th>-->
<!--					<th><img src="/assets/daifu/image/xiazai.png"></th>-->
<!--				 </tr>-->
				<?php foreach ($order['users'] as $k=>$v): ?>
                    <tr>
					<th><?php echo $v['business_pc_num']?></th>
					<th><?php echo $v['sum']?></th>
					<th><?php echo $v['count']?></th>
					<th> <a href="<?php echo site_url('/');?>userexcel/test19?business_pc_num=<?php echo $v['business_pc_num']?>"><img src="/assets/daifu/image/mingxi.png"></a></th>
					<th><img src="/assets/daifu/image/xiazai.png"></th>
                </tr>
				<?php endforeach; ?>
	         </table>
		</div>
		<div class="lines"></div>
			<div class="page">
<!--				<label class="label_css">&lt;</label>-->
<!--				<input type="text" value="  1" class="input_css">-->
<!--				<label class="label_css">&gt;</label>-->
				<?php echo $order['link'];?>
			</div>
	        <div class="danpage">
	        	<label class="label_css2">单页显示</label>
				<input type="text" value="  3" class="input_css inputcs">
				<label class="label_css2">共1页</label>
				<label class="label_css2">总笔数： 3笔</label>
				<label class="label_css2">总金额：</label>
	        </div>
	</div>
</div>
