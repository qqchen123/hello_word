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
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #cccccc;" >
    <div class="row" style=" margin: 20 0 0 60;">
        <div class="form-inline">
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
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">
    <table class=" easyui-datagrid" id="phone_list" title="已完结的借款" style="width:100%;height:500px;"
           data-options="
                    url:'get_yx_loan_finish',
                    idField: 'id',
                    queryParams: {
                        m_db_name: 'loan_finish',
                    },
                    rownumbers: true,
                    method:'post',
                    lines: true,
                    border: false,
                    columns:col_data,
                    pagination: true
        ">
    </table>




</div>
</body>

<script>

    //列表数据格式
    var col_data = [[
        // {field: '操作',title:'操作',width:'7%',align:'center',halign:'center'},
        // {field: '期数',title:'期数',width:'5%',align:'center',halign:'center'},
        // {field: '借款标题',title:'借款标题',width:'10%',align:'center',halign:'center'},
        // {field: '满标时间',title:'满标时间',width:'10%',align:'center',halign:'center'},
        // {field: '状态',title:'状态',width:'10%',align:'center',halign:'center'},
        // {field: '登录用户名',title:'登录用户名',width:'10%',align:'center',halign:'center'},
        // {field: '结清日期',title:'结清日期',width:'10%',align:'center',halign:'center'},
        // {field: '网址',title:'网址',width:'10%',align:'center',halign:'center'},
        // {field: '还款方式',title:'还款方式',width:'10%',align:'center',halign:'center'},
        // {field: '借款总利息(元)',title:'借款总利息(元)',width:'100',align:'center',halign:'center'},
        // {field: '年化利率(%)',title:'年化利率(%)',width:'100',align:'center',halign:'center'},
        // {field: '借款金额(元)',title:'借款金额(元)',width:'100',align:'center',halign:'center'},

        {field: '操作',title:'操作',align:'center',halign:'center'},
        {field: '期数',title:'期数',align:'center',halign:'center'},
        {field: '借款标题',title:'借款标题',align:'center',halign:'center'},
        // {field: '满标时间',title:'满标时间',align:'center',halign:'center'},
        {field: '状态',title:'状态',align:'center',halign:'center'},
        {field: '登录用户名',title:'登录用户名',align:'center',halign:'center'},
        {field: '结清日期',title:'结清日期',align:'center',halign:'center'},
        {field: '网址',title:'网址',align:'center',halign:'center'},
        {field: '还款方式',title:'还款方式',align:'center',halign:'center'},
        {field: '借款总利息',title:'借款总利息(元)',align:'center'},
        // {field: '   ',title:'  ',align:'center'},
        {field: '年化利率',title:'年化利率(%)',align:'center'},
        // {field: '   ',title:'  ',align:'center'},
        {field: '借款金额',title:'借款金额(元)',align:'center'},
    ]];//登录用户名
    function search() {
        let date_start = $('#date1').val();
        let date_end = $('#date2').val();
        let account = $('#account').val();
        $("#phone_list").datagrid("load",{
            "date_start":date_start,
            "date_end":date_end,
            "account":account,
        });
    }

    $('#date1').datebox({
        required:true,
        width:100,
    });
    $('#date2').datebox({
        required:true,
        width:100
    });

</script>

</body>