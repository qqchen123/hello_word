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
            <!--            状态：-->
            <!--            <select id="status" class="easyui-combobox" name="status" style="width:100px;">-->
            <!--                <option value="">全部</option>-->
            <!--                <option value="已垫付">已垫付</option>-->
            <!--                <option value="带还款">带还款</option>-->
            <!--                <option value="已还款">已还款</option>-->
            <!--            </select>-->
            &nbsp;
            &nbsp;
            &nbsp;
            出借时间
            <input type="text" class="form-control" id="date1" name="" value="">
            -
            <input type="text" id="date2" class="form-control" name="" value="">
            <input type="text" id="account" class="form-control" placeholder="请输入姓名、被推荐人用户名！" name="" value="">
            <input type="button" class="form-control" onclick="search()" value="确定">
        </div>
    </div>
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">
    <table class=" easyui-datagrid" id="phone_list" title="借款人详情" style="width:100%;height:500px;"
           data-options="
                    url:'get_yx_borrower_detail',
                    idField: 'id',
                    queryParams: {
                        m_db_name: 'borrower_detail',
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
        {field: '真实姓名',title:'真实姓名',width:'8%',align:'center',halign:'center'},
        {field: '手机号',title:'手机号',width:'10%',align:'center',halign:'center'},
        {field: '身份证号',title:'身份证号',width:'10%',align:'center',halign:'center'},
        {field: '借款金额',title:'借款金额',width:'10%',align:'center',halign:'center'},
        {field: '借款利率',title:'借款利率',width:'8%',align:'center',halign:'center'},
        {field: '属性',title:'属性',width:'5%',align:'center',halign:'center'},
        {field: '借款时间',title:'借款时间',width:'10%',align:'center',halign:'center'},
        {field: '借款期限',title:'借款期限',width:'8%',align:'center',halign:'center'},
        {field: '放款时间',title:'放款时间',width:'10%',align:'center',halign:'center'},
        {field: '被推荐人用户名',title:'被推荐人用户名',width:'10%',align:'center',halign:'center'},
        {field: '还款方式',title:'还款方式',width:'10%',align:'center',halign:'center'},
        {field: 'work',title:'操作',align:'center',halign:'center',
            formatter:function (value, row) {
                var html = '';
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="show_edit_sms()" >客户借款详情 </a>'+'&nbsp;&nbsp';
                html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="del_sms()" >客户付息管理 </a>';
                return html;
            }
        },

    ]];
    function search() {
        let date_start = $('#date1').val();
        let date_end = $('#date2').val();
        let status = $('#status').val();
        let account = $('#account').val();
        $("#phone_list").datagrid("load",{
            "date_start":date_start,
            "date_end":date_end,
            // "status":status,
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