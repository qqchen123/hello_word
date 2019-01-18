<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
 .big_bk {
     width: 100%;
     height: 99%;
     border:3px solid #385b99;
     margin-left: 3%;
     margin-top: 1%;
 }
 .top_bk {
 	width: 100%;
 	height: 30%;
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
 	margin-left: 3%;
 }
 .query {
 	width: 20%;
 	height: 54px;
 	background: #356aca;
 	font-size: 24px;
 	line-height: 54px;
 	text-align: center;
 	border-radius: 30px;
 	color: #ffff;
 	margin-top: 2%;
 	margin-left: 27%;
 }
 .midd_bk {
 	width:100%;
 	height: 55%;
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
	padding-top: 15px;
	font-size: 16px;
	margin-left: 4%;
}
</style>

<div>
	<div class="big_bk">
		<div class="top_bk">
			<div class="left_bk">
				<div class="start_time">
				    <div class="tr_info">起始时间</div>
					<select class="tr_info2">
						<option>日期</option>
					</select>
		        </div>
			     <div class="start_time">
					<div class="tr_info">截止时间</div>
					<select class="tr_info2">
						<option>日期</option>
					</select>
					<label class="tips">间隔时间不能超过一个月</label>
			      </div>
			     <div class="query queryclick">
			     	查 询
			     </div>
			</div>
		</div>
       <div class="midd_bk">
			<table class="textre">
				<tr>
					<th>上传时间</th>
					<th>加款凭证号</th>
					<th>加款金额</th>
					<th>类型</th>
					<th>余额</th>
					<th>备注</th>
				 </tr>
				 <tr>
					<th> 2018/10/16 10:00 </th>
					<th> ABCD1234 </th>
					<th>  </th>
					<th> 进账</th>
					<th>   </th>
					<th>账户充值 </th>
				 </tr>
				 <tr>
					<th> 2018/10/16 10:00 </th>
					<th> ABCD1234 </th>
					<th>  </th>
					<th> 进账</th>
					<th>   </th>
					<th>账户充值 </th>
				 </tr>
				 <tr>
					<th> 2018/10/16 10:00 </th>
					<th> ABCD1234 </th>
					<th>  </th>
					<th> 进账</th>
					<th>   </th>
					<th>账户充值 </th>
				 </tr>
				 <tr>
					<th> 2018/10/16 10:00 </th>
					<th> ABCD1234 </th>
					<th>  </th>
					<th> 进账</th>
					<th>   </th>
					<th>账户充值 </th>
				 </tr>
				 <tr>
					<th> 2018/10/16 10:00 </th>
					<th> ABCD1234 </th>
					<th>  </th>
					<th> 进账</th>
					<th>   </th>
					<th>账户充值 </th>
				 </tr>
	         </table>
		</div>
		<div class="lines"></div>
		<div class="page">
			<label class="label_css">&lt;</label>
			<input type="text" value="  1" class="input_css">
			<label class="label_css">&gt;</label>
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
