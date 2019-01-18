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
    <div class="input-group col-md-3" style="margin: 0 auto; margin-top: 17px;">
        <input type="text" id="kh_id" class="form-control"placeholder="请输入客户ID！" / >
        <span class="input-group-btn">
            <button class="btn btn-info btn-search" onclick="make_id()">确认</button>
            <!--            <button class="btn btn-info btn-search" onclick="show_login()" >登陆</button>-->
        </span>
    </div>
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">
    <table class=" easyui-datagrid" id="phone_list" title="充值提现汇总" style="width:100%;height:500px;"
           data-options="
                    url:'get_yx_data_by_mongodb',
                    idField: 'id',
                    queryParams: {
                        m_db_name: 'account_detail',
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
        {field: '登录用户名',title:'登录用户名',width:'10%',align:'center',halign:'center'},
        {field: '属性',title:'属性',width:'10%',align:'center',halign:'center'},
        {field: '流水号',title:'流水号',width:'24%',align:'center',halign:'center'},
        {field: '时间',title:'时间',width:'24%',align:'center',halign:'center'},
        {field: '状态',title:'状态',width:'10%',align:'center',halign:'center'},
        {field: '金额',title:'金额',width:'10%',align:'center',halign:'center'},

    ]];


</script>

</body>