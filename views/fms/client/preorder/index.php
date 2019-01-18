<?php tpl("admin_applying") ?>
<style type="text/css">
	.inputline {
		margin-bottom: 10px;
	}
	#start_sub:hover {
		cursor: pointer;
	}
</style>
<div>
	<div class="inputline">
		<label>姓名</label>
		<input id="name" type="text" name="name">
		<span>*</span>
	</div>
	<div class="inputline">
		<label>身份证</label>
		<input id="idnumber" type="text" name="idnumber">
		<span>*</span>
	</div>
	<div class="inputline">
		<label>业务员编号</label>
		<input id="code" type="text" name="code">
		<span>*</span>
	</div>
	<div class="inputline">
		<span style="border:1px solid #CCC;padding: 5px;" id="start_sub">开始报单</span>
		<input style="display: none;" type="submit" name="submit" value="开始报单">
	</div>
</div>

<script type="text/javascript">
    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
	$('#start_sub').click(function(){
		var name = $('#name').val();
		var idnumber = $('#idnumber').val();
		var code = $('#code').val();
		if (name.length && idnumber.length && code.length) {
			$.ajax({ 
	            type : "post", 
	            url : AJAXBASEURL + '/client/ClientPreOrder/create_pre_order', 
	            data : {name: name, idnumber:idnumber, code:code},
	            success : function(res){ 
	                console.log(JSON.parse(res));
	                var response = JSON.parse(res);
	                if (response['code'] == 0) {
	                	//返回数据有效 开始提取里面的内容
	                	name = response['data']['name'];
	                	idnumber = response['data']['idnumber'];
	                	fuserid = response['data']['fuserid'];
	                	var operator = response['data']['operator'];
	                	var order = response['data']['order'];
	                	//跳转到下一页
	                	window.location.href = AJAXBASEURL + '/client/ClientPreOrder/showcode?order=' + order + '&name=' + name + '&idnumber=' + idnumber + '&fuserid=' + fuserid + '&operator=' + operator + '&code=' + code;
	                } else {
	                	//返回失败 显示提示信息
	                	alert(response['msg']);
	                }
	            } 
	        });
		} else {
			alert('内容未填写完整');
		}
	});
</script>
