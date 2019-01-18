<?php tpl("admin_applying") ?>
<style type="text/css">
	#next-page:hover{
		cursor: pointer;
	}
	.show-code-title {
		display: inline-block;
		width: 100px;
		height: 30px;
		line-height: 30px;
	}
	.show-code-content {
		display: inline-block;
		width: 100px;
		height: 30px;
		line-height: 30px;
	}
</style>
<div>
	<div>
		<div class="show-code-title">客户姓名:</div>
		<div class="show-code-content"><?= $name?></div>
	</div>
	<div>
		<div class="show-code-title">客户身份证号:</div>
		<div class="show-code-content"><?= $idnumber?></div>
	</div>
	<div>
		<div class="show-code-title">业务员编号:</div>
		<div class="show-code-content"><?= $code?></div>
	</div>
	<div>
		<div class="show-code-title">业务员姓名:</div>
		<div class="show-code-content"><?= $operator?></div>
	</div>
	<div>
		<div class="show-code-title">客户编号:</div>
		<div class="show-code-content"><?= $fuserid?></div>
	</div>
	<div>
		<div class="show-code-title">报单编号:</div>
		<div class="show-code-content"><?= $order?></div>
	</div>
	<div>
		<span id="next-page">下一步</span>
	</div>
</div>
<script type="text/javascript">
	var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
	$('#next-page').click(function(){
		window.location.href = AJAXBASEURL + '/client/ClientPreOrder/uploadidcard?order=<?= $order?>&name=<?= $name?>&idnumber=<?= $idnumber?>&fuserid=<?= $fuserid?>&operator=<?= $operator?>&code=<?= $code?>';
	});

</script>
