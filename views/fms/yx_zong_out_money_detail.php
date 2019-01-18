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
    td {
        border-top: none !important;
        vertical-align: middle !important;
    }

    .tlabel {
        text-align: right;
        background-color: #EEEEEE;
    }

    .ml2 {
        margin-right: 2em
    }
        .sub-btn{
            text-align: right;
        }
        #fm {
            margin: 0;
            padding: 10px 30px;
        }
        .ftitle {
            font-size: 14px;
            font-weight: bold;
            padding: 5px 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .fitem {
            margin-bottom: 5px;
        }

        .fitem label {
            display: inline-block;
            width: 140px;
        }

        .fitem input {
            width: 160px;
        }

        .radioformr {
            width: 5px;
        }

        .sub-btn {
            margin-top:15px;
        }

        #jiGouForm label{
            font-size: 12px;
            margin-top: 5px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'center',title:'银信总账户出借人详情'" style="padding:5px;background:#eee;">
        <table  id="out_money_detail" class="easyui-datagrid" style="width:100%;height:350px"
                    data-options="
                        url: 'get_out_money_detail?account=<?=$account?>',
                        rownumbers: true,
                        method: 'get',
                        toolbar: '#toolbar',
                        lines: true,
                        fit: true,
                        fitColumns: false,
                        border: false,
                        columns:out_money_data,
                        pagination:true,
                        onSortColum: function (sort,order) {
                            $('#out_money').datagrid('reload', {
                                sort: sort,
                                order: order
                        　　});
                        },
                    ">
        </table>
    </div>
</div>
<script>
    //获取列表
        var out_money_data = [[
            {field: 'account', title: '被推荐人用户名', width: 200, align:'center', 'sortable':true},
            {field: 'out_title', title: '借款标题', width: 200, align:'center', 'sortable':true},
            {field: 'user_name', title: '真实姓名', width: 200, align:'center', 'sortable':true},
            {field: 'id_number', title: '身份证号', width: 200, align:'center', 'sortable':true},
            {field: 'call_number', title: '手机号', width: 200, align:'center', 'sortable':true},
            {field: 'out_money', title: '出借金额', width: 200, align:'center', 'sortable':true},
            {field: 'out_cyc', title: '出借期限', width: 200, align:'center', 'sortable':true},
            {field: 'out_rate', title: '出借利率', width: 200, align:'center', 'sortable':true},
            {field: 'return_mode', title: '还款方式', width: 200, align:'center', 'sortable':true},
            {field: 'out_date', title: '出借时间', width: 200, align:'center', 'sortable':true},
        ]];
</script>

</body>
</html>