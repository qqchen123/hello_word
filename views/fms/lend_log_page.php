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
            出借方式：
            <select id="status" class="easyui-combobox" name="status" style="width:100px;">
                <option value="">全部</option>
                <option value="苹果 手动出借">苹果 手动出借</option>
                <option value="微信 手动出借">微信 手动出借</option>
                <option value="安卓 手动出借">安卓 手动出借</option>
            </select>
            &nbsp;
            &nbsp;
            &nbsp;
            出借时间
            <input type="text" class="form-control" id="date1" name="" value="">
            -
            <input type="text" id="date2" class="form-control" name="" value="">
            <input type="text" id="account" class="form-control" placeholder="请输入标题名！" name="" value="">
            <input type="button" class="form-control" onclick="search()" value="确定">
        </div>
    </div>
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">
    <table class=" easyui-datagrid" id="phone_list" title="我的借款_借出记录" style="width:100%;height:500px;"
           data-options="
                    url:'get_yx_lend_log',
                    idField: 'id',
                    queryParams: {
                        m_db_name: 'lend_log',
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
        {field: '标题名',title:'标题名',width:'10%',align:'center',halign:'center'},
        {field: '出借用户',title:'出借用户',width:'10%',align:'center',halign:'center'},
        {field: '登录用户名',title:'登录用户名',width:'10%',align:'center',halign:'center'},
        {field: '真实姓名',title:'真实姓名',width:'10%',align:'center',halign:'center'},
        {field: '出借方式',title:'出借方式',width:'10%',align:'center',halign:'center'},
        {field: '出借时间',title:'出借时间',width:'10%',align:'center',halign:'center'},
        {field: '出借金额(元)',title:'出借金额(元)',width:'10%',align:'center',halign:'center'},
    ]];
    function search() {
        let date_start = $('#date1').val();
        let date_end = $('#date2').val();
        let status = $('#status').val();
        let account = $('#account').val();
        $("#phone_list").datagrid("load",{
            "date_start":date_start,
            "date_end":date_end,
            "status":status,
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