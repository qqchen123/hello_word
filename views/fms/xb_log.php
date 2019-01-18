<?php //tpl("admin_header") ?>
<body>
<!-- <link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css"> -->
<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<!-- <title></title> -->
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<style>

</style>

<body class="easyui-layout">
    <div data-options="region:'center',title:'喜报日志列表'" style="padding:5px;background:#eee;">
        <table id="dg"></table>

    </div>
</body>
<script>
    $('#dg').datagrid({
        url:'get_xb_log',
        pagination: true,
        rownumbers: true,
        columns:[[
            {field:'username',title:'用户',width:'10%',align:'center'},
            {field:'phone',title:'手机',width:'10%',align:'center'},
            {field:'s_time',title:'发送时间',width:'10%',align:'center'},
            {field:'status',title:'发送状态',width:'10%',align:'center',formatter: function(value,row){
                    if (row.status==0){
                        return '<span style="color:red;">发送失败！</span>';
                    } else {
                        return '发送成功！';
                    }
                }
            },
            {field:'message',title:'喜报内容',width:'50%',align:'center'},
        ]]
    });
</script>
</body>