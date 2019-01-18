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

        #customForm label{
            font-size: 12px;
            margin-top: 5px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">

    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td>
                <div class="fitem">
                    资料项归属类型:
                    <input class="easyui-combobox" required="true" style="width:100px" 
                        data-options="
                            editable:false,
                            panelHeight:'auto',
                            valueField:'key',
                            textField:'text',
                            data: typeData,
                            onChange:function(v){
                                sample_type = v;
                                $('#tt').datagrid({
                                    columns:col_data[v],
                                    queryParams:{sample_type:sample_type},
                                });
                            },
                            value:sample_type,
                        "
                    >
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    搜索:
                    <input class="easyui-textbox" type="text" name="like" id="like" value="">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    </div>

    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table id="tt" class="easyui-datagrid" style="max-height:80%;"
            data-options="
                url: 'get_init_pool_method',
                queryParams:{sample_type:sample_type},

                method:'get',
                rownumbers: true,

                fit:true,
                fitColumns: false,
                border:false,
                columns:col_data[sample_type],
                pagination:true,
                //pagePosition:'bottom',
                //singleSelect:true,
                //collapsible:true,
                //pageSize:10,
                //pageList:[10,20],
                striped:true,
        ">
        </table>
    </div>
</div>
    <script>
    var typeData = JSON.parse('<?= $sample_types ?>');
    var sample_type = '<?= $sample_type ?>';
    var col_data = {};
    //用户列表定义
        col_data.user = [[
            {field: 'fuserid', title: '客户编号', width: 200, align:'center', 'sortable':true},
            {field: 'name', title: '客户名称', width: 200,  align:'center', 'sortable':true},
            {field: 'idnumber', title: '身份证号', width: 200,  align:'center', 'sortable':true},
            {field: 'channel', title: '渠道编号', width: 200,  align:'center', 'sortable':true},
            // {field: 'cjyg', title: '创建员工', width: 100,  align:'center', 'sortable':true},
            {field: 'ctime', title: '开户时间', width: 150, align:'center', 'sortable':true},
            {field: 'operate', title: '操作', width: 150, align:'center',
                formatter: function(value, row, index) {
                    html = '';
                    html += '<a class="btn btn-primary btn-xs p310" href="list_pool?id='+row.id+'&sample_type=user">查看资料数据 </a> '+'&nbsp;&nbsp;';
                    return html;
                }
            }
        ]];

    //房屋列表定义
        col_data.house = [[
            {field: 'house_id', title: '房屋编号', width: 200, align:'center', 'sortable':true},
            {field: 'address', title: '房屋地址', width: 200,  align:'center', 'sortable':true},
            // {field: 'idnumber', title: '身份证号', width: 200,  align:'center', 'sortable':true},
            // {field: 'channel', title: '渠道编号', width: 200,  align:'center', 'sortable':true},
            // {field: 'cjyg', title: '创建员工', width: 100,  align:'center', 'sortable':true},
            {field: 'ctime', title: '开户时间', width: 150, align:'center', 'sortable':true},
            {field: 'operate', title: '操作', width: 150, align:'center',
                formatter: function(value, row, index) {
                    html = '';
                    html += '<a class="btn btn-primary btn-xs p310" href="list_pool?id='+row.id+'&sample_type=house">查看资料数据 </a> '+'&nbsp;&nbsp;';
                    return html;
                }
            }
        ]];

    //订单列表定义
        col_data.order = [[
            {field: 'order_id', title: '订单编号', width: 200, align:'center', 'sortable':true},
            // {field: 'name', title: '客户名称', width: 200,  align:'center', 'sortable':true},
            // {field: 'idnumber', title: '身份证号', width: 200,  align:'center', 'sortable':true},
            // {field: 'channel', title: '渠道编号', width: 200,  align:'center', 'sortable':true},
            // {field: 'cjyg', title: '创建员工', width: 100,  align:'center', 'sortable':true},
            {field: 'ctime', title: '开户时间', width: 150, align:'center', 'sortable':true},
            {field: 'operate', title: '操作', width: 150, align:'center',
                formatter: function(value, row, index) {
                    html = '';
                    html += '<a class="btn btn-primary btn-xs p310" href="list_pool?id='+row.id+'&sample_type=order">查看资料数据 </a> '+'&nbsp;&nbsp;';
                    return html;
                }
            }
        ]];

        $('#likeBtn').on('click',function () {
            var like = $('#like ').val();
            $('#tt').datagrid('reload',{like:like,sample_type,sample_type});
        })

   </script>

</body>
</html>