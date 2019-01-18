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


</style>

<body class="easyui-layout">

<!--电子账单-->


<div data-options="region:'north',title:'',split:true" style="height:150px; background: #cccccc;" >
    <div class="row" style=" margin: 20 0 0 60;">
        <div class="form-inline">
            <input type="button" class="btn btn-warning" class="form-control" onclick="window.history.go(-1)" value="返回">

            &nbsp;
            &nbsp;
            &nbsp;
            结清日期
            <input type="text" class="form-control" id="date1" name="" value="">
            -
            <input type="text" id="date2" class="form-control" name="" value="">
            <input type="text" id="account" class="form-control" placeholder="请输入登陆用户名！" name="" value="">
            <input type="button" class="form-control" onclick="search()" value="确定">
        </div>

    </div>
    <table class=" easyui-datagrid" id="phone_list" title="客户信息" style="width:100%;height:100px;"
           data-options="
                    url:'get_one_yx_account',
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
<div data-options="region:'center',title:'电子账单'" style="padding:5px;background:#eee;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'">
            <table id="el_bills_tab"></table>
        </div>
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
    //电子账单--el_bills_tab
        $('#el_bills_tab').datagrid({
            url:'get_el_bill_data',
            width:'100%',
            pagination:true,
            rownumbers:true,
            queryParams: {
                account: '<?php echo $account;?>',
            },
            columns:[[
                {field:'serial_number',title:'流水号',width:100},
                {field:'date',title:'日期',width:100},
                {field:'income',title:'收入',width:100,align:'right'},
                {field:'pay',title:'支出',width:100,align:'right'},
                {field:'type',title:'交易类型',width:100,align:'right'},
                {field:'status',title:'状态',width:100,align:'right'},
            ]]
        });

</script>

</body>