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
                        m_db_name: 'borrower_detail_copy',
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
        {field: '借款利率',title:'借款利率',width:'10%',align:'center',halign:'center'},
        {field: '属性',title:'属性',width:'10%',align:'center',halign:'center'},
        {field: '借款时间',title:'借款时间',width:'24%',align:'center',halign:'center'},
        {field: '借款期限',title:'借款期限',width:'24%',align:'center',halign:'center'},
        {field: '借款金额',title:'借款金额',width:'10%',align:'center',halign:'center'},
        {field: '手机号',title:'手机号',width:'10%',align:'center',halign:'center'},
        {field: '放款时间',title:'放款时间',width:'10%',align:'center',halign:'center'},
        {field: '真实姓名',title:'真实姓名',width:'10%',align:'center',halign:'center'},
        {field: '被推荐人用户名',title:'被推荐人用户名',width:'10%',align:'center',halign:'center'},
        {field: '身份证号',title:'身份证号',width:'10%',align:'center',halign:'center'},
        {field: '还款方式',title:'还款方式',width:'10%',align:'center',halign:'center'},

    ]];


</script>

</body>