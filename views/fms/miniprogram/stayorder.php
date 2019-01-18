<html>
<?php tpl("admin_applying") ?>
<body>
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">

<link rel="stylesheet" href="/assets/styles/miniprogram/ordermanage.css">
<script src="/assets/apps/miniprogram/ordermanage.js"></script>
<style type="text/css">

</style>
<div style="padding-left: 20px;padding-top: 20px;">
	<div>
		导航
	</div>
	<div style="width: 80%;">
		<div>
			<div>
				<div class="search-input">
					<input type="text" id="condition-input" name="condition" placeholder="请输入查询内容">
					<span id="search-btn"><img src="/assets/images/miniprogram/scan.png"></span>
					<div class="clear"></div>
				</div>
			</div>
			<div class="type-btn-box">
				<div class="type-btn" data-type="on">在办</div>
				<div class="type-btn" data-type="wait">待办</div>
				<div class="type-btn" data-type="done">已办</div>
				<div class="clear"></div>
			</div>
		</div>
		<div id="search-box">
			<input type="hidden" id="condition" value="">
			<input type="hidden" id="type" value="wait">
			<input type="hidden" id="page" value="1">
			<input type="hidden" id="size" value="10">
		</div>
		<div>
			<div id="list-box"></div>
			<div style="border: 1px solid #CCC;padding:15px;margin-top: 20px;">
				<span>备注：</span>
				<div><span class="remark-span-title">订单编号:</span>每条订单的唯一标识</div>
				<div><span class="remark-span-title">申请时间:</span>报单提交时间</div>
				<div><span class="remark-span-title">申贷人:</span>报单中的申贷人姓名</div>
				<div><span class="remark-span-title">申贷金额:</span>报单中的借款数额</div>
				<div><span class="remark-span-title">系统评估金额:</span>提交报单时通过房估估评出的金额</div>
				<div><span class="remark-span-title">单价:</span>系统评估之后给出的每平米价格</div>
				<div><span class="remark-span-title">总价:</span>系统评估之后给出的整栋房子的估价</div>
				<div><span class="remark-span-title">预估可贷成数:</span>提交报单时通过房估估评出的金额</div>
				<div><span class="remark-span-title">评估可贷金额:</span>系统评估之后的总价乘以可贷成数，为房屋可以贷款的大致价格</div>
				<div><span class="remark-span-title">可贷机构:</span>初步判断，用户材料符合标准的机构</div>
				<div><span class="remark-span-title">最高可贷:</span>机构通过用户提交的资料初步判断的最高可贷金额</div>
			</div>
		</div>
	</div>
	<div id="viewbox" class="easyui-dialog" title="订单信息" style="width:1600px;height:700px;top: 10px;padding-left: 30px;padding-top: 30px;font-size: 16px;"data-options="iconCls:'icon-info',modal:true,closed:true">
		<div>订单信息</div>
		<div id="part1">
			<div>
				<span>订单编号:</span><span id="part1-id"></span>
				<span>部门:</span><span id="part1-department"></span>
				<span>业务员:</span><span id="part1-sales"></span>
			</div>
		</div>
		<div id="part2">
			<div class="view-type-title">申贷信息</div>
			<div id="part2-applyinfo"></div>
		</div>
		<div id="part3">
			<div class="view-type-title">房本信息</div>
			<div id="part3-houseinfo"></div>
		</div>
		<div id="part4">
			<div class="view-type-title">证件信息</div>
			<div>
				<div style="margin-bottom: 10px;">身份证：</div>
				<div id="idnumberbox"></div>
			</div>
			<div>
				<div style="margin-bottom: 10px;">房产证：</div>
				<div id="housebox"></div>
			</div>
		</div>
        </div>
	<div id="editbox" class="easyui-dialog" title="订单信息" style="width:1600px;height:740px;top: 10px;"data-options="iconCls:'icon-info',modal:true,closed:true">
		<div id="baodaninfo">
			<div style="width: 100%;background-color: #E6E6E6;height: 30px;line-height: 30px;font-weight: 600;">订单信息</div>
			<form id="baodanform">
				<label>订单编号：</label><input type="text" class="easyui-textbox" name="bd_id" disabled="true" style="border:none;">
				<label>业务员：</label><input type="text" class="easyui-textbox" name="sales" disabled="true" style="border:none;">
				<label>申请时间：</label><input type="text" class="easyui-textbox" name="c_time" disabled="true" style="border:none;">
			</form>
		</div>
		<div id="applyinfo">
			<div style="width: 100%;background-color: #E6E6E6;height: 30px;line-height: 30px;font-weight: 600;">申贷信息</div>
			<form id="applyform">
				<div>
				<label>申贷人：</label><input type="text" class="easyui-textbox" name="user_name" disabled="true" style="border:none;">
				<label>职业类型：</label><input type="text" class="easyui-textbox" name="zhiye_type" disabled="true" style="border:none;">
				<label>申贷金额：</label><input type="text" class="easyui-textbox" name="get_money" disabled="true" style="border:none;">
				<label>借款期限：</label><input type="text" class="easyui-textbox" name="get_money_term" disabled="true" style="border:none;">
				</div>
				<label>产品属性：</label><input type="text" class="easyui-textbox" name="product_type" disabled="true" style="border:none;">
				<label>机构代码：</label><input type="text" class="easyui-textbox" name="jg_code" disabled="true" style="border:none;">
				<label>产品名称：</label><input type="text" class="easyui-textbox" name="product_name" disabled="true" style="border:none;">
			</form>
		</div>
		<div id="sysassessinfo">
			<div style="width: 100%;background-color: #E6E6E6;height: 30px;line-height: 30px;font-weight: 600;">系统评估信息</div>
			<form id="sysassessform">
				<div><label>房屋坐落：</label><input type="text" class="easyui-textbox" name="houseName" disabled="true" style="border:none;"></div>
				<label>竣工日期：</label><input type="text" class="easyui-textbox" name="finish_date" disabled="true" style="border:none;">
				<label>面积：</label><input type="text" class="easyui-textbox" name="house_area" disabled="true" style="border:none;">
				<label>评估单价：</label><input type="text" class="easyui-textbox" name="diYaDanJia" disabled="true" style="border:none;">
				<label>评估总价：</label><input type="text" class="easyui-textbox" name="diYaZongJia" disabled="true" style="border:none;">
			</form>
		</div>
		<div id="picinfo">
			<div style="width: 100%;background-color: #E6E6E6;height: 30px;line-height: 30px;font-weight: 600;">
				图片信息：
				<span>
					<input type="radio" name="pic" class="pictypeselect" value="1" >身份证 <input type="radio" name="pic" class="pictypeselect" value="2">房产证
				</span>
			</div>
			<div style="float: left;">
				<div style="height: 400px;width: 600px;margin: 2px 20px 10px 20px;border:1px #CCC solid;text-align: center;">
					<img id="bigpic" style="width: 540px;height: 390px;margin:auto;" src="../PublicMethod/getImg?name=mini_upload_tmp/a.png">
				</div>
				<div style="height: 120px;width: 600px;border:1px #CCC solid;margin: 0 20px;">
					<span class="pic-select-left"><</span>
					<span id="imgs-box" style="display:block;margin-top:10px;float: left;"></span>
					<span class="pic-select-right">></span>
					<div style="clear: both;"></div>
				</div>
			</div>
			<div style="float: left;">
				<div style="height: 480px;width: 900px;margin: 0 0 10px 0;border:1px #FFF solid;" id="write">
					<div id="idnumberpagebox" class="dn">
						<div style="padding-left: 60px;margin-bottom: 10px;">
							<div style="display: inline-block;margin-right: 20px;">
								<input type="radio" value="1" name="idnumberpagetype" class="idnumberpagetype" >
								身份证正面
							</div>
							<div style="display: inline-block;">
								<input type="radio" value="2" name="idnumberpagetype" class="idnumberpagetype" checked="checked">
								身份证反面
							</div>
						</div>
						<div id="idnumberform">
							<form id="idnumber1form" class="dn">
								<div style="padding-left: 60px;margin-bottom: 10px;">
									证件材料：
									<div style="display: inline-block;margin-right: 20px;">
										<input type="radio" value="照片件" name="idpic2type" class="pic" checked="checked">照片件
									</div>
									<div style="display: inline-block;">
										<input type="radio" value="复印件" name="idpic2type" class="copy">复印件
									</div>
								</div>
								<div>
									<span class="idnumberform-title">签发机关：</span>
									<input type="text" name="qfjg" id="qfjg" class="input-text">
								</div>
								<div style="margin-bottom: 5px;">
									<span class="idnumberform-title">有效期起始时间：</span>
									<input class="easyui-datebox" data-options="onSelect:onSelect" name="able_start" id="able_start"></input>
								</div>
								<div style="margin-bottom: 5px;">
									<span class="idnumberform-title">有效期：</span>
									<select name="able_type">
										<option value="5">5</option>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="长期">长期</option>
									</select>
								</div>
								<div style="margin-bottom: 5px;">
									<span class="idnumberform-title">有效期结束时间：</span>
									<input type="text" name="able_end" id="able_end" readonly="readonly">
								</div>
								<div>
									<span class="idnumberform-title">有效期剩余时间：</span>
									<span id="remaining_time"></span>
								</div>
							</form>
							<form id="idnumber2form" class="dn">
								<div style="padding-left: 60px;margin-bottom: 10px;">
									证件材料：
									<input type="radio" name="pic1_type" class="easyui-validatebox" checked="checked" value="照片件"><label>照片件</label>
									<input type="radio" name="pic1_type" class="easyui-validatebox"  value="复印件"><label>复印件</label>
								</div>
								<div>
									<span class="idnumberform-title">姓名：</span>
									<input type="text" name="name" class="easyui-textbox">
								</div>
								<div>
									<span class="idnumberform-title">身份证号：</span>
									<input type="text" name="idnumber" class="easyui-textbox" data-options="events:{keyup:function(e){if (e.keyCode == 13) {get_idnumber_info($(this).val());}}}">
								</div>
								<div>
									<span class="idnumberform-title">身份证地址：</span>
									<input type="text" name="idnumber_address" class="easyui-textbox">
								</div>
								<div>
									<span class="idnumberform-title">性别：</span>
									<input type="text" name="sex" class="easyui-textbox">
								</div>
								<div>
									<span class="idnumberform-title">生日：</span>
									<input type="text" name="birth" class="easyui-textbox">
								</div>
								<div>
									<span class="idnumberform-title">出生地：</span>
									<input type="text" name="area" class="easyui-textbox" style="width: 240px;">
								</div>
								<div>
									<span class="idnumberform-title">年龄：</span>
									<input type="text" name="age" class="easyui-textbox" style="width: 40px;">
								</div>
								<div class="dn">
									<span class="idnumberform-title">备注:</span>
									<textarea name="remark2" style="resize:none" cols="40" rows="5"></textarea>
								</div>
							</form>
						</div>
					</div>
					<div id="housepagebox" class="dn">
						<div style="padding-left: 60px;">
							<div style="display: inline-block;margin-right: 50px;">
								<input type="radio" name="ishousenewold" data-val="1" checked="checked">新产证 
							</div>
							<div style="display: inline-block;">
								<input type="radio" name="ishousenewold" data-val="2">旧产证
							</div>
						</div>
						<div style="padding-left: 60px;margin-bottom: 10px;">
							证件材料：
							<div style="display: inline-block;margin-right: 20px;">
								<input type="radio" value="照片件" name="housepic1type" class="pic" checked="checked">照片件
							</div>
							<div style="display: inline-block;">
								<input type="radio" value="复印件" name="housepic1type" class="copy">复印件
							</div>
						</div>
						<div style="padding-left: 50px;" id="housepage_box">
							房产证第
							<span style="display: inline-block;">
								<div class="page-btn" data-page="1">1</div>
								<div class="page-btn" data-page="2">2</div>
								<div class="page-btn" data-page="3">3</div>
								<div class="page-btn" data-page="4">4</div>
								<div class="page-btn" data-page="5">5</div>
								<div class="clear"></div>
							</span>
							页
						</div>
						<div style="border: 1px solid #CCC;border-width: 0 0 1px 0;margin-bottom: 10px;"></div>
						<div id="houseform">
							<form id="housenew1form" class="dn">
								<div style="margin-bottom: 5px;">
									<span class="houseform-title">登记日:</span>
									<input id="recorddate" type="datetime" name="recorddate" class="input-text">
								</div>
							</form>
							<form id="houseold1form" class="dn">
								<div>
									<span class="idnumberform-title">产证编号：</span>
									<input type="text" name="housecode" class="easyui-textbox">
								</div>
								<div style="margin-bottom: 5px;">
									<span class="houseform-title">登记日:</span>
									<input type="datetime" name="recorddate" class="input-text">
								</div>
								<div style="margin-bottom: 5px;">
									<span class="houseform-title">所在区县:</span>
									<select name="houselocalname">
										<option value="静安区">静安区</option>
									</select>
								</div>
							</form>
							<form id="houseold2form" class="dn">
								<div style="width: 48%;float: left;">
									<div>
										<span class="idnumberform-title">房屋坐落：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">权利人：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>土地状况</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">权利类型：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">权利性质：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">用途：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div>
										<span class="idnumberform-title">宗地号：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">宗地(丘)面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">平方米
									</div>
									<div>
										<span class="idnumberform-title">使用权面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">独用面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">分摊面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">使用期限:</span>
										<input id="recorddate" type="datetime" name="recorddate" class="input-text">
										至
										<input id="recorddate" type="datetime" name="recorddate" class="input-text">
									</div>
								</div>
								<div style="width: 48%;float: left;">
									<div>房屋状况</div>
									<div>
										<span class="idnumberform-title">幢号：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">室号或部位：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">建筑面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">平方米
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">建筑类型：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">用途：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div>
										<span class="idnumberform-title">总层数：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">竣工日期:</span>
										<input id="recorddate" type="datetime" name="recorddate" class="input-text">
									</div>
								</div>
								<div class="clear"></div>
							</form>
							<form id="housenew2form" class="dn">
								<div style="width: 48%;float: left;">
									<div>
										<span class="idnumberform-title">房屋坐落：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">权利人：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">共有情况：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div>
										<span class="idnumberform-title">不动产单元号：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">权利类型：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">权利性质：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">用途：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div>面积</div>
									<div>
										<span class="idnumberform-title">宗地面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">平方米
									</div>
									<div>
										<span class="idnumberform-title">建筑面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">平方米
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">使用期限:</span>
										<input id="recorddate" type="datetime" name="recorddate" class="input-text">
									</div>
								</div>
								<div style="width: 48%;float: left;">
									<div>土地状况</div>
									<div>
										<span class="idnumberform-title">地号：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">使用权面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">独用面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>
										<span class="idnumberform-title">分摊面积：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div>房屋状况</div>
									<div>
										<span class="idnumberform-title">室号部位：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">类型：</span>
										<select name="houselocalname">
											<option value=""></option>
										</select>
									</div>
									<div>
										<span class="idnumberform-title">总层数：</span>
										<input id="forminput-housename" type="text" name="housename" class="easyui-textbox">
									</div>
									<div style="margin-bottom: 5px;">
										<span class="houseform-title">竣工日期:</span>
										<input id="recorddate" type="datetime" name="recorddate" class="input-text">
									</div>
								</div>
							</form>
							<form id="house3form" class="dn">
								<label>备注：</label>
								<textarea rows="6" cols="60" name="remark3" style="resize:none"></textarea>
							</form>
							<form id="house4form" class="dn">
								<label>备注：</label>
								<textarea rows="6" cols="60" name="remark4" style="resize:none"></textarea>
							</form>
							<form id="house5form" class="dn">
								<label>备注：</label>
								<textarea rows="6" cols="60" name="remark5" style="resize:none"></textarea>
							</form>
						</div>
					</div>
				</div>
				<div style="width:400px;height:80px;border:1px #FFF solid;margin: 0 20px;">
					<span class="edit-save-btn press-button">保存</span>
					<span onclick="editT()" class="edit-pic-btn press-button">编辑图片</span>
					<span class="edit-turnback-btn press-button">回退</span>
				</div>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
	<div id="bigpicbox" class="easyui-dialog" title="图片" style="width:1600px;height:700px;top: 10px;"data-options="iconCls:'icon-info',modal:true,closed:true">
	</div>
</div>

<script type="text/javascript">
	var view;

	var pageindex = 1;
	var pagemax = 10;
	var pagesize = 10;

	var picontype = 0;//编辑窗当前所处的页面类型
	var beforeeditdata = [];//编辑前的数据
	var aftereditdata = [];//编辑后的数据

	var editid = '';//编辑的id
	var edit_house_id = '';//编辑的房屋ID

	var edit_load_flag = 1;//编辑时加载数据时的标识
	var not_save_message = "您正在编辑当前图片并未保存，如果离开会丢失当前编辑信息,确定离开？";

    $('#imgs-box').on('click', '.imgselectbox', function(){
    	//判断当前的表单是否有修改
    	//有修改则提示：是否保存 是则保存之后跳转 否不提交表单数据重置跳转  没有修改则跳转
    	var turnback_flag = 0;
    	var stop_flag = 0;
    	switch ($(this).parent().index()) {
    		case 0 : //身份证表单
    			//获取当前处于显示状态的表单
    			$('.idnumberform').each(function(){
    				var flag = 1;
    				var className = $(this).attr('class');
		    		for (var y = 0; y < className.split(' ').length; y++) {
		    			if ('dn' === className.split(' ')[y]) {
		    				flag = 0;
		    			}
		    		}
		    		if (flag) {
		    			var temp;
		    			var id = $(this).attr('id');
		    			switch(id) {
		    				case 'idnumberpage1' :
		    					temp = idnumberpage1data();
		    					break;
		    				case 'idnumberpage2' :
		    					temp = idnumberpage2data();
		    					break;
		    				default : console.log('参数错误');
		    					return false;
 		    			}
 		    			if (!edit_load_flag) {
 		    				if (isEmptyObject(beforeeditdata.userinfo)) {
	 		    				for (var i in temp) {
	 		    					console.log(i);
	 		    					if (!$.inArray(i, ['idpic1type','idpic2type', 'bd_id'])) {
	 		    						if (temp[i]) {
			 		    					if (!confirm(not_save_message)) {
			 		    						stop_flag = 1;
			 		    					} else {
			 		    						stop_flag = 0;
			 		    						turnback_flag = 1; 
			 		    					}
			 		    					break;
		 		    					}
	 		    					}
	 		    				}
	 		    			} else {
	 		    				for (var i in temp) {
		 		    				if (temp.hasOwnProperty(i) && !isEmptyObject(beforeeditdata.userinfo)) {
		 		    					if (beforeeditdata.userinfo[i] != temp[i]  && null !== beforeeditdata.userinfo[i]) {
			 		    					//存在不同
			 		    					if ('area' == i && beforeeditdata.userinfo['birth_area'] == temp[i]) {
			 		    						continue;
			 		    					}
			 		    					if ('img_path' == i || 'bd_id' == i || 'type' == i) {
			 		    						continue;
			 		    					}
			 		    					if ('idpic1type' == i && null !== beforeeditdata.userinfo[i]) {
			 		    						continue;
			 		    					}
			 		    					if ('idpic2type' == i && null !== beforeeditdata.userinfo[i]) {
			 		    						continue;
			 		    					}
			 		    					if (!confirm(not_save_message + 1)) {
			 		    						stop_flag = 1;
			 		    					} else {
			 		    						stop_flag = 0;
			 		    						turnback_flag = 1; 
			 		    					}
			 		    					break;
			 		    				}
		 		    				}
		 		    			}
	 		    			}
 		    			}
		    		}
    			});
    			break;
    		case 1 : //房产证表单
				var housetemp = $('#housepagebox').children('div:eq(1)').children('div');
				for (var ii = 0; ii < housetemp.length; ii++) {
		    		var houseclassName = housetemp[ii].className;
		    		var houseflag = 1;
		    		for (var yy = 0; yy < houseclassName.split(' ').length; yy++) {
		    			if ('dn' === houseclassName.split(' ')[yy]) {
		    				houseflag = 0;
		    			}
		    		}
		    		if (houseflag) {
		    			var house_page_num = 0;
		    			switch(housetemp[ii].id){
		    				case 'housepage1':
			    				temp = housepage1data();
			    				house_page_num = 1;
			    				edit_load_flag = 0;
								break;
							case 'housepage2':
								temp = housepage2data();
								house_page_num = 2;
								edit_load_flag = 0;
								break;
							case 'housepage3':
								temp = housepage3data();
								house_page_num = 3;
								edit_load_flag = 0;
								break;
							default : console.log('参数错误');
								edit_load_flag = 1;
								break;
    					}
    					if (!edit_load_flag) {
	    					for (var i in temp) {
	 		    				if (temp.hasOwnProperty(i) && beforeeditdata.houseinfo[i] != temp[i]) {
	 		    					if ('img_path' == i || 'bd_id' == i || 'type' == i) {
	 		    						continue;
	 		    					}
	 		    					if ('remark' == i) {
	 		    						var temp_remark = JSON.parse(beforeeditdata.houseinfo[i]);
	 		    						if (temp_remark[(house_page_num-1)]) {
	 		    							if (temp_remark[(house_page_num-1)] == temp[i]) {
	 		    								continue;
	 		    							}
	 		    						} else {
	 		    							continue;
	 		    						}
	 		    					}
	 		    					console.log(beforeeditdata.houseinfo[i]);
	 		    					console.log(temp[i]);
	 		    					console.log(i);
	 		    					//存在不同
	 		    					if (!confirm(not_save_message)) {
	 		    						stop_flag = 1;
	 		    					} else {
	 		    						stop_flag = 0;
	 		    						turnback_flag = 1;
	 		    					}
	 		    					break;
	 		    				}
	 		    			}
	 		    		}
		    		}
				}
    			break;
    		default : //类型错误
    			return false;
    	}
    	
    	if (stop_flag) {
    		return false;
    	}
    	if (turnback_flag) {
    		$('.edit-turnback-btn').click();
    	}
    	//执行图片切换
    	$('.imgselectbox').each(function(){
    		$(this).removeClass('imgonselect');
    	});
    	$(this).addClass('imgonselect');
    	//切换大图
    	var img_temp = $(this).attr('src').split('/');
    	$('#bigpic').attr('data-filename', img_temp[img_temp.length-1]);
    	var dir = 'mini_upload_tmp';
    	switch ($(this).parent().index()) {
    		case 0 : //身份证表单
    			if (beforeeditdata.userinfo && !isEmptyObject(beforeeditdata.userinfo)) {
    				dir = 'preorder/' + editid + '/idnumber';
    			}
    			break;
    		case 1 : //房产证表单
    			if (beforeeditdata.houseinfo && !isEmptyObject(beforeeditdata.houseinfo)) {
    				dir = 'preorder/' + editid + '/house';
    			}
    			break;
    	}
    	$('#bigpic').attr('data-dir', dir);

    	$('#bigpic').attr('src', $(this).attr('src').replace('mini_upload_tmp', dir));

    	//如果没有绑定关系则变更重置表格
    	if (1 == $(this).parent().index()) {
    		$('.houseform').each(function(){
				$(this).addClass('dn');
				$('#housepagenum').val('');
			});
    	}
    	//用这个图片的src 扫描 houseinfo 中关系
    	if (beforeeditdata.houseinfo && beforeeditdata.houseinfo.hasOwnProperty('relation_img') && null != beforeeditdata.houseinfo.relation_img) {
    		var temp_relation_img = JSON.parse(beforeeditdata.houseinfo.relation_img);
    		if (temp_relation_img) {
	    		for (var i = 0; i < temp_relation_img.length; i++) {
		    		if (temp_relation_img[i] == $(this).attr('data-filename')) {
		    			$('#housepage'+(parseInt(i)+1)).removeClass('dn');
		    		}
		    	}
		    }
    	}

    	//用这个图片的src 扫描 userinfo 中关系
    	if (beforeeditdata.userinfo && beforeeditdata.userinfo.hasOwnProperty('relation_img') && null != beforeeditdata.userinfo.relation_img) {
    		var temp_relation_img = JSON.parse(beforeeditdata.userinfo.relation_img);
    		if (temp_relation_img) {
    			for (var i in temp_relation_img) {
		    		if (temp_relation_img[i] == $(this).attr('data-filename')) {
		    			if (2 == (parseInt(i)+1)) {
		    				$('.idnumberpage1type:eq(1)').click();
		    			} else {
		    				$('.idnumberpage2type:eq(0)').click();
		    			}	
		    		}
		    	}
    		}
    	}
    });

    //回退按钮
    $('.edit-turnback-btn').click(function(){
    	//获取未处于隐藏状态下的表单
    	var temp = $('#write').children('div');
    	for (var i = 0; i < temp.length; i++) {
    		var className = temp[i].className;
    		var flag = 1;
    		for (var y = 0; y < className.split(' ').length; y++) {
    			if ('dn' === className.split(' ')[y]) {
    				flag = 0;
    			}
    		}
    		if (flag) {
    			var array = [];
    			console.log('显示的表单id:' + temp[i].id + ' class:' + temp[i].className);
    			//不同的表单提取不同的数据 整理之后调用submitsave(data,condition) 保存数据
    			switch(temp[i].id) {
    				case 'idnumberpage1': 
    					load_idnumber1_info(beforeeditdata.userinfo);
    					break;
					case 'idnumberpage2': 
    					load_idnumber2_info(beforeeditdata.userinfo);
    					break;	
    				default: console.log('参数错误');
    					break;
    			}
    			break;
    		}
    	}
    });

    //获取大图图片的文件名
    function getbigpicfilename(){
    	var src = $('#bigpic').attr('src');
    	var src_img = src.split('/');
    	return src_img[src_img.length-1];
    }

	//查看框数据加载
	function viewbox(data){
		//加载数据到查看页面
    	var html_part1 = '';
    	var html_part2 = '';
    	var html_part3 = '';
    	var html_part4 = '';
    	html_part1 = data.baodan.bd_id;
    	admin_id = data.baodan.admin_id
    	//ajax 置换 报单人信息
    	$.ajax({
    		url:'getsalesinfo?id='+admin_id,
    		type:'get',
    		datetype:'json',
    		success: function(res){
    			console.log(JSON.parse(res));
    			res = JSON.parse(res);
    			if (res.data) {
    				$('#part1-department').html(res.data.department_name);
    				$('#part1-sales').html(res.data.username);
    			}
    		}
    	});
    	var arr = ['user_name','get_money', 'zhiye_type', 'get_money_type', 'di_ya_type'];
    	for (var key in data.baodan) {
    		if (-1 != $.inArray(key, arr)) {
    			html_part2 += '<div style="margin-bottom:5px;">';
    			var flag = 1;
    			switch(key) {
    				case 'user_name': html_part2 += '<span class="view-span-title">申贷人姓名:</span>';break;
    				case 'zhiye_type': html_part2 += '<span class="view-span-title">职业类型:</span>';break;
    				case 'get_money': html_part2 += '<span class="view-span-title">申贷金额(万元):</span>';break;
    				case 'get_money_type': html_part2 += '<span class="view-span-title">借款类型:</span>';break;
    				case 'di_ya_type': html_part2 += '<span class="view-span-title">抵押类型:</span>';break;
    				case 'house_type': html_part2 += '<span class="view-span-title">房产类型:</span>';break;
    				case 'product_type': html_part2 += '<span class="view-span-title">产品属性:</span>';break;
    				case 'jg_code': html_part2 += '<span class="view-span-title">机构:</span>';break;
    				default: 
    					flag = 0;
    				break;
    			}
    			if (flag) {
    				html_part2 += '<span>'+data.baodan[key]+'</span>';
	    			if ('二抵' == data.baodan[key] && 'di_ya_type' == key) {
	    				html_part2 += '<span style="margin-left:20px;">一抵金额:</span>'+'<span>'+data.baodan['yidi_yue']+'</span>';
	    			}
	    			html_part2 += '</div>';
    			}
    		}
    	}
    	var arr = ['diYaDanJia', 'diYaZongJia', 'fangDaiZheKou', 'ZheKouZongJia'];
    	for (var key in data.house) {
    		if (-1 != $.inArray(key, arr)) {
    			html_part2 += '<div style="margin-bottom:5px;">';
    			var flag = 1;
    			switch(key) {
    				case 'diYaDanJia': html_part2 += '<span class="view-span-title">单价:</span>';break;
    				case 'fangDaiZheKou': html_part2 += '<span class="view-span-title">预估可贷成数:</span>';break;
    				default: 
    					flag = 0;
    					break;
    			}
    			if (flag) {
    				html_part2 += '<span>'+data.house[key]+'</span>';
	    			if ('diYaDanJia' == key) {
	    				html_part2 += '<span style="margin-left:20px;">总价:</span>'+'<span>'+data.house['diYaZongJia']+'</span>';
	    			}
	    			if ('fangDaiZheKou' == key) {
	    				html_part2 += '<span style="margin-left:20px;">评估可贷金额:</span>'+'<span>'+data.house['ZheKouZongJia']+'</span>';
	    			}
	    			html_part2 += '</div>';
    			}
    		}
    	}


    	var arr = ['houseName','finish_date', 'house_area', 'gui_hua_yong_tu'];
    	for (var key in data.house) {
    		if (-1 != $.inArray(key, arr)) {
    			html_part3 += '<div style="margin-bottom:5px;">';
    			switch(key) {
    				case 'houseName': html_part3 += '<span class="view-span-title">小区地址:</span>';break;
    				case 'house_area': html_part3 += '<span class="view-span-title">建筑面积:</span>';break;
    				case 'finish_date': html_part3 += '<span class="view-span-title">竣工时间:</span>';break;
    				case 'gui_hua_yong_tu': html_part3 += '<span class="view-span-title">规划用途:</span>';break;
    				default: break;
    			}
    			html_part3 += '<span>'+data.house[key]+'</span>';
    			html_part3 += '</div>';
    		}
    	}

    	//身份证图片
    	$('#idnumberbox').html('');
    	html_part4 = '<div style="border:1px #CCC solid;padding:10px;">';
    	html_part4 += '<div style="width:50%;float:left;">';
    	for (var i = 0; i < JSON.parse(data.baodan['idNumberT']).length; i++) {
    		var temp = JSON.parse(data.baodan['idNumberT'])[i];
    		if ('false' != temp && !isEmptyObject(temp)) {
    			html_part4 += '<img style="width:200px;height:200px;" src="../PublicMethod/getImg?name=mini_upload_tmp/'+temp+'">';
    		}
    	}
    	html_part4 += "</div>";
    	//关联图片信息
    	var user_map = {
    		'name' : '姓名', 
    		'idnumber' : '身份证号', 
    		'birth_area' : '出生地', 
    		'sex' : '性别', 
    		'birth' : '生日', 
    		'qfjg' : '签发机关', 
    		'able_start' : '有效期开始时间', 
    		'able_end' : '有效期结束时间', 
    		'remark1' : '备注1', 
    		'remark2' : '备注2'
    	};
    	html_part4 += '<div style="width:50%;float:left;">';
    	if (data.hasOwnProperty('userinfo') && data.userinfo) {
    		for (var i in user_map) {
    			if (data.userinfo[i]) {
    				html_part4 += '<div>'+user_map[i]+':'+data.userinfo[i]+'</div>';
    			} else {
    				html_part4 += '<div>'+user_map[i]+':'+' '+'</div>';
    			}
				
    		}
    	}
    	html_part4 += "</div>";
    	html_part4 += '<div style="clear:both;"></div>';
    	$('#idnumberbox').html(html_part4);

    	//房产证图片
    	html_part4 = '';
    	html_part4 = '<div style="border:1px #CCC solid;padding:10px;">';
    	html_part4 += '<div style="width:50%;float:left;">';
    	$('#housebox').html('');
    	if (!isEmptyObject(data.house)) {
    		if (data.house.hasOwnProperty('img_path') && isJsonString(data.house['img_path'])) {
    			var temp = JSON.parse(data.house['img_path']);
    			for (var y in temp) {
    				html_part4 += '<img style="width:200px;height:200px;" src="../PublicMethod/getImg?name=mini_upload_tmp/'+temp[y]+'">';
    			}
	    	}
    	}
    	html_part4 += "</div>";
    	//关联图片信息
    	var house_map = {
    		'housecode' : '产证编号', 
    		'recorddate' : '登记日', 
    		'housearea' : '所在区县', 
    		'ishousenewold' : '新旧产证', 
    		'obligee' : '权利人', 
    		'address' : '房屋坐落', 
    		'housenum' : '室号或部位', 
    		'buildingarea' : '建筑面积', 
    		'buildingtype' : '建筑类型', 
    		'useby' : '用途',
    		'buildingtotalfloor' : '总层数',
    		'buildingfinishdate' : '竣工日期',
    		'remark' : '备注'
    	};
    	html_part4 += '<div style="width:50%;float:left;">';
    	if (data.hasOwnProperty('userinfo') && data.houseinfo) {
    		for (var i in house_map) {
    			if ('remark' == i) {
    				for (var y = 0; y < JSON.parse(data.houseinfo['remark']).length; y++) {
    					html_part4 += '<div>'+house_map[i]+(parseInt(y)+1)+':'+JSON.parse(data.houseinfo['remark'])[y]+'</div>';
    				}
    			} else {
    				if (data.userinfo[i]) {
	    				html_part4 += '<div>'+house_map[i]+':'+data.houseinfo[i]+'</div>';
	    			} else {
	    				html_part4 += '<div>'+house_map[i]+':'+' '+'</div>';
	    			}
    			}
    		}
    	}
    	html_part4 += "</div>";
    	html_part4 += '<div style="clear:both;"></div>';
    	$('#housebox').html(html_part4);

    	$('#part1-id').html(html_part1);
    	$('#part2-applyinfo').html(html_part2);
    	$('#part3-houseinfo').html(html_part3);
	}

	//编辑框数据加载 总方法
	function editbox(data){
		edit_load_flag = 1;
		//装载完全部表格数据 

		editid = data.baodan['bd_id'];
		//图片选择区数据加载
		//处理身份证照片
		var idnumbertemp = new Array();
		for (var i = 0; i < JSON.parse(data.baodan['idNumberT']).length; i++) {
    		var temp = JSON.parse(data.baodan['idNumberT'])[i];
    		if ('false' != temp && !isEmptyObject(temp)) {
    			idnumbertemp[idnumbertemp.length] = temp;
    		}
    	}
		var housetemp = new Array();
		if (data.house.hasOwnProperty('img_path') && isJsonString(data.house['img_path'])) {
			var temp = JSON.parse(data.house['img_path']);
			for (var y in temp) {
				housetemp[housetemp.length] = temp[y];
			}
		}
		var imgs = [idnumbertemp,housetemp];
		//处理数据的顺序
		//idnumber 
		if (data.userinfo && data.userinfo['relation_img']) {
			var result = new Array();
			var temp_idnumbertemp = idnumbertemp;
			var json_relation_img = JSON.parse(data.userinfo.relation_img);
			for (var z = 0; z < json_relation_img.length; z++) {
				for (var i = 0; i < idnumbertemp.length; i++) {
					if (json_relation_img[z] == idnumbertemp[i]) {
						result.push(idnumbertemp[i]);
						//移除
						temp_idnumbertemp.splice($.inArray(idnumbertemp[i],temp_idnumbertemp),1);
						break;
					}
				}
			}
			imgs[0] = $.merge(result, temp_idnumbertemp);
		}

		//house
		if (data.houseinfo && data.houseinfo['relation_img']) {
			var result = new Array();
			var temp_housetemp = housetemp;
			var json_relation_img = JSON.parse(data.houseinfo.relation_img);
			for (var z = 0; z < json_relation_img.length; z++) {
				for (var i = 0; i < housetemp.length; i++) {
					if (json_relation_img[z] == housetemp[i]) {
						result.push(housetemp[i]);
						//移除
						temp_housetemp.splice($.inArray(housetemp[i],temp_housetemp),1);
						break;
					}
				}
			}
			imgs[1] = $.merge(result, temp_housetemp);
		}

		var imgs_box_html = '';
		var cnt = 0;
		for (var y = 0; y < imgs.length; y++) {
			if (0 == y) {
				imgs_box_html += '<div class="imgtype" id="imgtype'+(parseInt(y)+1)+'">';
			} else {
				imgs_box_html += '<div class="imgtype dn" id="imgtype'+(parseInt(y)+1)+'">';
			}
			for (var i = 0; i < imgs[y].length; i++) {
				if (4 > i) {
					imgs_box_html += '<img class="imgselectbox"';
				} else {
					imgs_box_html += '<img class="imgselectbox dn"';
				}
				imgs_box_html += ' data-filename = "'+ imgs[y][i] +'" ';
				var img_dir = 'mini_upload_tmp/';

				if (0 == y && data.userinfo && data.userinfo['relation_img']) {
					img_dir = 'preorder/' + editid + '/idnumber/';
				}
				if (1 == y && data.houseinfo && data.houseinfo['relation_img']) {
					img_dir = 'preorder/' + editid + '/house/';
				}
				imgs_box_html += ' id="img'+cnt+'" src="../PublicMethod/getImg?name='+img_dir+imgs[y][i]+'">';
				cnt++;
			}
			imgs_box_html += '</div>';
		}
		
		$('#imgs-box').html(imgs_box_html);
		var cnt = 3;
		if (cnt > 3) {
			//产证多余图片追加表单
			html_add = '<div>add</div>';//多余图片
		} else {
			html_add = '';
		}

		$('#write').append(html_add);

		//动作初始化
		$('.pictypeselect')[0].click();
		edit_load_flag = 0;
	}

	function isJsonString(str) {
        try {
            if (typeof JSON.parse(str) == "object") {
                return true;
            }
        } catch(e) {
        }
        return false;
    }

    function isEmptyObject(obj){ 
    	for (var n in obj) {
    		return false
    	} 
    	return true; 
    }



    
/*编辑AND查看的弹框*/
	//查看按钮  编辑按钮
	function view(id, target){
		$.ajax({
			url: './info?id='+id,
			type: 'get',
			datetype: 'json',
			success: function(res){
				res = JSON.parse(res);
				if (res.code != 0) {
					console.log(res.msg);
					return;
				} else {
					beforeeditdata = res.data;
					console.log('更新beforeeditdata');
					console.log(beforeeditdata);
					switch(target) {
						case 'view': viewbox(res.data);break;
						case 'edit': get_data(id);editbox(res.data);break;
						default: break;
					}
				}
			}
		});
	}

    function get_data(id) {
    	$.ajax({
			url: './info?id='+id,
			type: 'get',
			async: false,
			datetype: 'json',
			success: function(res){
				res = JSON.parse(res);
				if (res.code != 0) {
					console.log(res.msg);
					return;
				} else {
					load_baodan_info(res.data.baodan);
					load_apply_info(res.data.baodan);
					load_sysassess_info(res.data.house);
					load_idnumber1_info(res.data.userinfo);
					load_idnumber2_info(res.data.userinfo);
					load_house_new1_info(res.data.houseinfo);
					load_house_old1_info(res.data.houseinfo);
					load_house_new2_info(res.data.houseinfo);
					load_house_old2_info(res.data.houseinfo);
					load_house3_info(res.data.houseinfo);
					load_house4_info(res.data.houseinfo);
					load_house5_info(res.data.houseinfo);
				}
			}
		});
    }

    //编辑框订单信息加载
    function load_baodan_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    //ajax 置换 报单人信息
    	$.ajax({
    		url:'getsalesinfo?id='+data['admin_id'],
    		type:'get',
    		datetype:'json',
    		success: function(res){
    			res = JSON.parse(res);
    			// $('#part1-department').html(res.data.department_name);
    			if (res.data && res.data.hasOwnProperty('username')) {
    				data_array['sales'] = res.data.username;
    			}
    		}
    	});
	    $('#baodanform').form('load', data_array);
    }

    //编辑框申贷信息加载
    function load_apply_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }

    	$('#applyform').form('load', data_array);
    }

    //编辑框系统评估信息加载
    function load_sysassess_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }

    	$('#sysassessform').form('load', data_array);
    }

    //编辑框身份证正面信息加载
    function load_idnumber1_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('身份证在正面数据');
	    // $('#able_start').datebox('setValue', data.able_start);
    	$('#idnumber1form').form('load', data_array);
    }

    //编辑框身份证反面信息加载
    function load_idnumber2_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('身份证在反面数据');
    	$('#idnumber2form').form('load', data_array);
    }

    //编辑框房产证(新版)第一页信息加载
    function load_house_new1_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('房产证(新版)第一页');
    	$('#housenew1form').form('load', data_array);
    }

    //编辑框房产证(旧版)第一页信息加载
    function load_house_old1_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('房产证(新版)第一页');
    	$('#houseold1form').form('load', data_array);
    }

    //编辑框房产证(新版)第二页信息加载
    function load_house_new2_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('房产证(新版)第二页');
    	$('#housenew2form').form('load', data_array);
    }

    //编辑框房产证(旧版)第二页信息加载
    function load_house_old2_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('房产证(旧版)第二页');
    	$('#houseold2form').form('load', data_array);
    }

    //编辑框房产证第三页信息加载
    function load_house3_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('房产证第三页');
    	$('#house3form').form('load', data_array);
    }

    //编辑框房产证第四页信息加载
    function load_house4_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('房产证第四页');
    	$('#house4form').form('load', data_array);
    }

    //编辑框房产证第五页信息加载
    function load_house5_info(data) {
    	var data_array = {};
	    for (var i in data) {
	    	data_array[i] = data[i];
	    }
	    console.log('房产证第五页');
    	$('#house5form').form('load', data_array);
    }

	/**
     * type：view、edit
     */
    function open_dialog(type, id) {
    	view(id, type);//
    	//初始化部分参数
    	picontype = 0;
    	//显示对应的对话框
    	$('#'+type+'box').dialog('open');
    }
/*编辑AND查看的弹框*/

/*保存提交数据*/
	//编辑保存
    $('.edit-save-btn').click(function(){
    	//获取未处于隐藏状态下的表单
    	var type = $('.pictypeselect:checked').val();
    	var subformname = '';//需要提交的表单名字
    	switch(parseInt(type)) {
    		case 1 : 
    			//身份证
    			$('#idnumberform').children('form').each(function(){
    				if (-1 == $(this).attr('class').indexOf('dn')) {
    					console.log('要提交的是'+$(this).attr('id'));
    					subformname = $(this).attr('id');
    				}
    			})
    			break;
    		case 2 : 
    			//房产证
    			$('#houseform').children('form').each(function(){
    				if (-1 == $(this).attr('class').indexOf('dn')) {
    					console.log('要提交的是'+$(this).attr('id'));
    					subformname = $(this).attr('id');
    				}
    			});
    			break;
    		default : return false;
    	}
    	var array = [];
    	switch(subformname) {
    		case 'idnumber1form' : 
    			array = idnumberpage1data();
    			break;
    		case 'idnumber2form' : 
    			array = idnumberpage2data();
    			break;
			case 'housenew1form' : 
			case 'houseold1form' : 
				array = housepage1data(subformname);
    			break;
			case 'housenew2form' : 
			case 'houseold2form' : 
				array = housepage2data(subformname);
    			break;
			case 'house3form' : 
				array = housepage3data(subformname);
    			break;
    		case 'house4form' : 
    			array = housepage4data(subformname);
    			break;
    		case 'house5form' : 
    			array = housepage5data(subformname);
    			break;
    		default : return false;
    	}
    	array['bd_id'] = editid;
		submitsave(array);
    });

    //编辑保存ajax
	function submitsave(data){
		//数据对比
		//相同不更新
		//不同则提示更新
		console.log(data);
		$.ajax({
			url: './save',
			type: 'post',
			datetype: 'json',
			data:data,
			success: function(res){
				res = JSON.parse(res);
				if (res.code != 0) {
					console.log(res.msg);
					return;
				}
				res = res.data;
				alert('保存成功');
				view(editid, '');
			}
		});
	}

	//身份证page2上传
    function idnumberpage2data(){
    	var formdata = $('#idnumber2form').serialize();
    	var formarray = serialize_to_obj(formdata);
    	formarray['type'] = 'idnumber2';
    	// formarray['remark1'] = $('#forminput-idnumberpage1-remark').val();
    	formarray['pic2_type'] = $('input[name="pic1_type"]:checked').val();
    	formarray['img_path'] = getbigpicfilename();
   		return formarray;
    }

    //身份证page1上传
    function idnumberpage1data(){
    	var formdata = $('#idnumber1form').serialize();
    	var formarray = serialize_to_obj(formdata);
    	formarray['type'] = 'idnumber1';
    	// formarray['remark2'] = $('#forminput-idnumberpage2-remark').val();
    	formarray['pic1_type'] = $('input[name="pic1_type"]:checked').val();
    	formarray['img_path'] = getbigpicfilename();
   		return formarray;
    }

    //产证(旧版新版)信息第一页
    function housepage1data(id){
    	var formdata = $('#' + id).serialize();
    	var formarray = serialize_to_obj(formdata);
    	formarray['type'] = 'house1';
    	formarray['img_path'] = getbigpicfilename();
    	formarray['ishousenewold'] = $('input[name="ishousenewold"]:checked').attr('data-val');
    	return formarray;
	}

	//产证(旧版新版)信息第二页
	function housepage2data(id){
		var formdata = $('#' + id).serialize();
    	var formarray = serialize_to_obj(formdata);
    	formarray['type'] = 'house2';
    	formarray['img_path'] = getbigpicfilename();
    	formarray['ishousenewold'] = $('input[name="ishousenewold"]:checked').attr('data-val');
    	return formarray;
	}

	//产证(旧版新版)信息第三页
	function housepage3data(id){
		var formdata = $('#' + id).serialize();
    	var formarray = serialize_to_obj(formdata);
    	formarray['type'] = 'house3';
    	formarray['img_path'] = getbigpicfilename();
    	formarray['ishousenewold'] = $('input[name="ishousenewold"]:checked').attr('data-val');
    	return formarray;
	}

	//产证(旧版新版)信息第四页
	function housepage4data(id){
		var formdata = $('#' + id).serialize();
    	var formarray = serialize_to_obj(formdata);
    	formarray['type'] = 'house4';
    	formarray['img_path'] = getbigpicfilename();
    	formarray['ishousenewold'] = $('input[name="ishousenewold"]:checked').attr('data-val');
    	return formarray;
	}

	//产证(旧版新版)信息第四页
	function housepage5data(id){
		var formdata = $('#' + id).serialize();
    	var formarray = serialize_to_obj(formdata);
    	formarray['type'] = 'house5';
    	formarray['img_path'] = getbigpicfilename();
    	formarray['ishousenewold'] = $('input[name="ishousenewold"]:checked').attr('data-val');
    	return formarray;
	}

    function serialize_to_obj(str) {
    	str = decodeURIComponent(str,true);
    	var array = str.split('&');
    	var obj = {};
    	var temp =[];
    	for (var i = 0; i < array.length; i++) {
    		temp = array[i].split('=');
    		if (temp.length > 1) {
    			obj[temp[0]] = temp[1];
    		} else {
    			obj[temp[0]] = '';
    		}
    	}
    	return obj;
    }
/*保存提交数据*/

/**待分析是否可迁出的js**/

    //图片编辑
    function editT(){
    	let url = '../PreOrder/edit_jpg';
    	let dir = $('#bigpic').attr('data-dir')? $('#bigpic').attr('data-dir'):'mini_upload_tmp';
    	let file_name = $('#bigpic').attr('data-filename')? $('#bigpic').attr('data-filename'):'wx0bf5029227dc5f8f.o6zAJszVEu8K9SEh6kMJy1qPBohM.0q5dxy7WQBtJe33d9b92f63035593b827d8863d7dfcc.png';
    	url += '?dir='+dir+'&file_name='+file_name;
    	window.open(url,"_blank");
    	localStorage.setItem('resrc', 0);
    	window.addEventListener('storage', resrc);
    }

    function resrc(event){
    	//console.log(event);
    	if (event.key === 'resrc') {
			if(event.newValue=='1'){
				let s = $('#bigpic').attr('src')+'&'+Math.random();
				$('#bigpic').attr('src',s);
				$('.imgonselect').attr('src',s);
	    		localStorage.setItem('resrc', 0);
	    	}
		}
    }
/**待分析是否可迁出的js**/

/**不能迁出的js**/

    //身份证有效期选择事件
    $('select[name="able_type"]').change(function(){
    	var str_date = $('#able_start').datebox('getValue');
    	var temp = str_date.split('-');
    	if (temp[0] <= 0 || temp[0] <= 0 || temp[0] <= 0) {
    		return false;
    	}
    	var date = new Date(temp[0], temp[1], temp[2]);
    	onSelect(date);
    });

    //身份证有效期和起始时间变更时候调用的function
    function onSelect(date){
    	//获取 有效期
    	var able_time = $('select[name="able_type"]').val();
    	if ('长期' == able_time) {
    		$('#able_end').val('长期');
    		$('#remaining_time').html('长期');
    	} else {
    		var able_time = $('select[name="able_type"]').val();
    		$('#able_end').val(getAfterNYear(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate(),able_time));
    		//同时计算到期剩余时间
    		var todayDate = new Date();
    		sDate1 = Date.parse(todayDate.getFullYear()+"-"+(todayDate.getMonth()+1)+"-"+todayDate.getDate());
	        sDate2 = Date.parse(getAfterNYear(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate(),able_time));
	        dateSpan = sDate2 - sDate1;
	        var iDays = Math.floor(dateSpan / (24 * 3600 * 1000));
	        var iDays_year,iDays_month,iDays_day,conclusion;
	        if (dateSpan > 0) {
	        	iDays_year = Math.floor(iDays/365);
	        	iDays_month = Math.floor(iDays%365/30);
	        	iDays_day= Math.floor(iDays%365%30);
	        	conclusion = '剩余';
	        } else {
	        	iDays_year = Math.abs(Math.ceil(iDays/365));
	        	iDays_month = Math.abs(Math.ceil(iDays%365/30));
	        	iDays_day = Math.abs(Math.ceil(iDays%365%30));
	        	conclusion = '过期';
	        }
	        $('#remaining_time').html(conclusion + iDays_year+'年'+iDays_month+'月'+iDays_day+'日');
    	}
	}

	/**
	 * 计算N年后,YYYYMMDD
	 * startdate：为开始时间，格式YYYYMMDD
	 * nextYear：为间隔年月，如1表示一年后，2表示两年后
	 */
	function getAfterNYear(startdate,nextYear){
		 var expriedYear = parseInt(startdate.substring(0,4)) + parseInt(nextYear);
		 var expriedMonth = startdate.substring(4,6);
		 var expriedDay = startdate.substring(6);
		 //考虑二月份场景，若N年后的二月份日期大于该年的二月份的最后一天，则取该年二月份最后一天
		 if(expriedMonth == '02' || expriedMonth == 2){
			 var monthEndDate = new Date(expriedYear ,expriedMonth,0).getDate();
			 if(parseInt(expriedDay) > monthEndDate){//为月底时间
				 //取两年后的二月份最后一天
				 expriedDay = monthEndDate;
			 }
		 }
		 return expriedYear+expriedMonth+expriedDay;
	}

	//从身份证号上获取信息
   	function get_idnumber_info(idnumber){
   		if (18 == idnumber.length) {
   			$.ajax({
	   			url: 'getidnumberinfo?id=' + idnumber,
	   			type: 'get',
	   			datetype: 'json',
	   			success: function(res){
	   				console.log(res);
	   				//更新页面数据
	   				res = JSON.parse(res);
	   				res = res.data;
	   				$('#idnumber2form').form('load', {
	   					'age' : res.age,
	   					'birth' : res.birth,
	   					'sex' : res.sex,
	   					'area' : res.area,
	   				});
	   			}
	   		});
   		} else {
   			console.log('不是18位身份证');
   		}
   	};
/**不能迁出的js**/
</script>
<link rel="stylesheet" href="/assets/lib/css/datepicker.css"/>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
</body>
</html>
