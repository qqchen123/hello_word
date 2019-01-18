<?php //tpl("admin_header") ?>
<body>
<!-- <link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css"> -->
<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/bootstrap-grid.min.css">
<link rel="stylesheet" href="/assets/lib/css/zoomify.min.css">
<!--<link rel="stylesheet" href="/assets/lib/css/style.css">-->
<link rel="stylesheet" href="/assets/layui/layui.css">
<!-- <title></title> -->
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/assets/layui/layui.js"></script>
<style>
.jkborrow{
    float: right;
}

</style>

<body class="easyui-layout">

<div class="easyui-layout" data-options="fit:true">
	<div data-options="region:'north',split:true" style="height:150px">
		<div class="row" style=" margin: 20 0 0 60;">
			<div class="form-inline">
				<input type="button" class="btn btn-warning" class="form-control" onclick="window.history.go(-1)" value="返回">
			</div>

		</div>
		<table class=" easyui-datagrid" id="phone_list" title="客户信息" style="width:100%;height:100px;"
			   data-options="
                    url:'get_one_yx_account_mysql',
                    idField: 'id',
                    rownumbers: true,
                    method:'post',
                    lines: true,
                    border: false,
                    columns:col_data,
                    queryParams: {
                        account: '<?php echo $account;?>',
                    }
        ">
		</table>
	</div>


	<div data-options="region:'west',split:true,title:'借款合同信息'" style="width: 50%">
        <a class="btn btn-primary btn-xs p310 jkborrow" href="javascript:void(0)" onclick="borrow_agree_detail()">借款合同详情</a>
        <table id="borrowing"></table>
	</div>
	<div data-options="region:'east',title:'还款信息表',split:true" style="width:50%;">
		<table id="finish_detail"></table>
	</div>
	<div id="borrow_agree_detail_win" class="easyui-window" title="合同编号" style="width:300px;height:400px"
		 data-options="modal:true,closed:true">
		<div style="margin:30px 0 0 50px;">
			<button type="button" class="btn btn-success" >打包下载⏬</button>
		</div>
		<ul style="margin-top: 30px;" class="list-group">
			<li class="list-group-item">11111111</li>
			<li class="list-group-item">22222222</li>
			<li class="list-group-item">33333333</li>
			<li class="list-group-item">44444444</li>
			<li class="list-group-item">55555555</li>
		</ul>

	</div>
</div>

</body>

<script>
    //客户信息管理汇总
    var col_data = [[
        {field: 'fuserid',title:'我司ID',align:'center',halign:'center'},
        {field: 'channel',title:'渠道ID',align:'center',halign:'center'},
        {field: 'yx_account',title:'银信ID',align:'center',halign:'center'},
        {field: 'reg_phone',title:'银信绑定手机',align:'center'},
        {field: 'ctime',title:'银信开户时间',align:'center',halign:'center'},
        {field: 'idnumber',title:'身份证号码',align:'center',halign:'center'},
        {field: 'name',title:'客户姓名',align:'center',halign:'center'},
    ]];

    //我的借款-还款中
    $('#borrowing').datagrid({
        url:'get_one_finished_by_mysql',
        width:'100%',
        queryParams: {
            account: '<?php echo $account;?>',
            title: '<?php echo $title;?>',
        },
        columns:[[
            {field:'pname',title:'借款标题',width:100,align:'center'},
            {field:'lend_money',title:'借款金额（元）',width:100,align:'center'},
            {field:'lilv',title:'年化利率（%）',width:100,align:'center'},
            {field:'qishu',title:'期数',width:100,align:'center'},
            {field:'zll',title:'借款总利息（元）',width:100,align:'center'},
            {field:'f_date',title:'结清日期',width:100,align:'center'},
            {field:'back_way',title:'还款方式',width:100,align:'center'},
            {field:'f_status',title:'状态',width:100,align:'center'},
            {field:'operate',title:'操作',width:100,align:'center'},
        ]]
    });
    $('#finish_detail').datagrid({
        url:'back_plan',
        width:'100%',
        queryParams: {
            account: '<?php echo $account;?>',
            title: '<?php echo $title;?>',
        },
        columns:[[
            {field:'qishu',title:'期数'},
            {field:'back_date',title:'还款日期'},
            {field:'b_interest',title:'应还本息',align:'center'},
            {field:'principal',title:'本金（元）',align:'center'},
            {field:'l_interest',title:'利息（元）',align:'center'},
            {field:'f_interest',title:'罚息',align:'center'},
            {field:'status',title:'状态',align:'center'},
        ]]
    });
    function borrow_agree_detail() {
        // $('#borrow_agree_detail_win').window('open');
        if ('<?php echo $contract['down_contract_url'];?>') {
            window.location.href= '<?php echo $contract['down_contract_url'];?>';
        }else{
            alert('暂无合同');
            return false;
        }
    }
</script>

</body>