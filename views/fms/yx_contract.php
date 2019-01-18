<html>
<?php tpl("admin_applying") ?>
<body>
银信合同页面
<div>
	<div>
		<input type="text" id="username" name="username" placeholder="请输入银信账户" value="">
	</div>
	<div>
		<input type="text" id="loan_title" name="loan_title" placeholder="请输入借款标题" value="">
	</div>
	<span id="find_contract">查询</span>
</div>
<div>
	<div>查询结果</div>
	<div></div>
</div>
<script type="text/javascript">
	$('#find_contract').click(function() {
		var username = $('#username').val();
		var loan_title = $('#loan_title').val();
		if ("" == username || "" == loan_title) {
			alert('内容输入不全');
			return false;
		}
		if (-1 == username.indexOf('YX') || 13 != username.length) {
			alert('账户格式错误');
			return false;
		}
		//前往查询
		$.ajax({
            type: "POST",
            url: 'FindContract',
            data: {account:username, loan_title:loan_title},
            dataType: "json",
            success(data){
                console.log(data);
                if (0 == data.code) {
                    ret = 0;
                }
                if (0 == ret) {
                    alert('查询成功');
                } else {
                    alert('查询失败请重试');
                }
            }
        });
		console.log(1);
	});
</script>