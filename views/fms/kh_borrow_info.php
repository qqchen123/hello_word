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

    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true" style="height:150px">
            <div class="row" style=" margin: 20 0 0 60;">
                <div class="form-inline">
                    <input type="button" class="btn btn-warning" class="form-control" onclick="window.history.go(-1)" value="返回">
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
        <div data-options="region:'center',fit:true">
            <div id="tt" class="easyui-tabs" style="width:100%;">
                <div title="借款管理" style="padding:20px;display:none;">
                    <div data-options="region:'center'">
                        <table id="kh_borrow_product"></table>
                    </div>
                </div>
                <div title="审核管理" style="padding:20px;display:none;">
                    <!--                        审核中-->
                    <div data-options="region:'center'">
                        <table id="verify"></table>
                    </div>
                </div>
                <div title="招标管理" style="padding:20px;display:none;">
                    <!--                        招标中-->
                    <div data-options="region:'center'">
                        <table id="call_for_bids"></table>
                    </div>
                </div>
                <div title="已结清管理" style="padding:20px;display:none;">
                    <!--                        已完结-->
                    <div data-options="region:'center'">
                        <table id="finish"></table>
                    </div>
                </div>
                <div title="申请管理"  style="padding:20px;display:none;">
                    <!--                        借款申请-->
                    <div data-options="region:'center'">
                        <table id="borrow_apply"></table>
                    </div>
                </div>
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
    $('#kh_borrow_product').datagrid({
        url:'get_yx_loan_nofinish',
        width:'100%',
        queryParams: {
            account: '<?php echo $account;?>',
        },
        rownumbers: true,
        columns:[[
            {field: 'operate1',title:'借款详情',width:'10%',align:'center',halign:'center',
                formatter:function (value,row) {
                    var html = '';
                    let account = '';
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="borrowing_info('+'\''+account+'\''+')" >借款中详情 </a>'+'&nbsp;&nbsp';
                    return html;
                }
            },
            {field: 'operate2',title:'电子账单管理',width:'10%',align:'center',halign:'center',
                formatter:function (value,row) {
                    var html = '';
                    let web = row.web;
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="el_bills()" >电子账单管理 </a>'+'&nbsp;&nbsp';
                    return html;
                }
            },
            {field:'loan_title',title:'借款标题',width:100,align:'right'},
            {field:'loan_amount',title:'借款金额(元)',width:100,align:'right'},
            {field:'periods',title:'期数',width:100,align:'right'},
            {field:'next_repay_amount',title:'下期还款本息(元)',width:100,align:'right'},
            {field:'repay_type',title:'还款方式',width:100,align:'right'},
            {field:'next_repay_time',title:'下期还款日',width:100,align:'right'},
            {field:'status',title:'状态',width:100,align:'right'},
            {field:'ctime',title:'爬取时间',width:100,align:'right'},

        ]]
    });
    //审核中
        $('#verify').datagrid({
            url:'',
            width:'100%',
            rownumbers: true,
            columns:[[
                {field:'operate1',title:'审核详情',width:'10%',align:'center',
                    formatter:function (value,row) {
                        var html = '';
                        let web = row.web;
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="check_agreement('+'\''+web+'\''+')" >审核中详情 </a>'+'&nbsp;&nbsp';
                        return html;
                    }
                },
                {field:' ',title:'申请借款日期',width:100,align:'right'},
                {field:'借款标题',title:'借款标题',width:100,align:'right'},
                {field:'借款金额',title:'借款金额(元)',width:100,align:'right'},
                {field:'借款金额',title:'年化利率(%)',width:100,align:'right'},
                {field:'期数',title:'期数',width:100,align:'right'},
                {field:'还款方式',title:'还款方式',width:100,align:'right'},
                {field:'状态',title:'状态',width:100,align:'right'},

            ]]
        });
    //招标中
        $('#call_for_bids').datagrid({
            url:'',
            width:'100%',
            rownumbers: true,
            columns:[[
                {field:'operate',title:'招标详情',width:'100',align:'center',
                    formatter:function (value,row) {
                        var html = '';
                        let web = row.web;
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="check_agreement('+'\''+web+'\''+')" >招标中详情 </a>'+'&nbsp;&nbsp';
                        return html;
                    }
                },
                {field:' ',title:'招标时间',width:100},
                {field:'借款标题',title:'借款标题',width:100,align:'right'},
                {field:'借款金额',title:'借款金额(元)',width:100,align:'right'},
                {field:'借款金额',title:'年化利率(%)',width:100,align:'right'},
                {field:'期数',title:'期数',width:100,align:'right'},
                {field:' ',title:'剩余招标时间',width:100,align:'right'},
                {field:'还款方式',title:'还款方式',width:100,align:'right'},
                {field:'状态',title:'状态',width:100,align:'right'},

            ]]
        });
    //已完结
        $('#finish').datagrid({
            url:'get_finished_by_mysql',
            width:'100%',
            rownumbers: true,
            queryParams: {
                account: '<?php echo $account;?>',
            },
            pagination:true,
            columns:[[
                {field:'operate1',title:'已结清详情',width:'10%',align:'center',
                    formatter:function (value,row) {
                        var html = '';
                        var title = '\''+row.pname+'\'';
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="finish_info('+title+')" >已结清详情 </a>'+'&nbsp;&nbsp';
                        return html;
                    }
                },
                {field:'operate2',title:'电子账单管理',width:'10%',align:'center',
                    formatter:function (value,row) {
                        var html = '';
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="el_bills()" >电子账单管理 </a>'+'&nbsp;&nbsp';
                        return html;
                    }
                },
                {field:'pname',title:'借款标题',width:100,align:'right'},
                {field:'lend_money',title:'借款金额(元)',width:100,align:'right'},
                {field:'lilv',title:'年化利率(%)',width:100,align:'right'},
                {field:'qishu',title:'期数',width:100,align:'right'},
                {field:'zll',title:'借款总利息(元)',width:100,align:'right'},
                {field:'f_date',title:'结清日期',width:100,align:'right'},
                {field:'back_way',title:'还款方式',width:100,align:'right'},
                {field:'f_status',title:'状态',width:100,align:'right'},

            ]]
        });
    //借款申请
        $('#borrow_apply').datagrid({
            url:'',
            width:'100%',
            rownumbers: true,
            columns:[[
                {field:'operate',title:'申请详情',width:'10%',align:'center',
                    formatter:function (value,row) {
                        var html = '';
                        let web = row.web;
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="check_agreement('+'\''+web+'\''+')" >申请中详情 </a>'+'&nbsp;&nbsp';
                        return html;
                    }
                },
                {field:'借款金额',title:'借款金额(元)',width:100,align:'right'},
                {field:' ',title:'借款期限',width:100,align:'right'},
                {field:' ',title:'借款用途',width:100,align:'right'},
                {field:'状态',title:'状态',width:100,align:'right'},
                {field:' ',title:'申请时间',width:100,align:'right'},
                {field:' ',title:'操作',width:100,align:'right'},

            ]]
        });
    // }
    function el_bills() {
        let account = '<?php echo $account ?>';
        window.location.href= 'el_bills?account='+account;
    }
    function finish_info(title) {
        let account = '<?php echo $account ?>';
        window.location.href= 'borrowing_detail?account='+account+'&title='+title;
    }
    //borrowing_info
    function borrowing_info() {
        let account = '<?php echo $account ?>';
        window.location.href= 'finish_detail?account='+account;
    }
</script>

</body>