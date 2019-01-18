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
<style>


</style>

<body class="easyui-layout">
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #cccccc;" >
<!--    <div class="input-group col-md-3" style="">-->
<!--        <input type="text" id="kh_id" class="form-control"placeholder="请输入客户ID！" / >-->
<!--        <input type="text" id="date1" class="form-control"placeholder="date1" / >-->
<!--        <input type="text" id="date2" class="form-control"placeholder="date2" / >-->
<!--        <span class="input-group-btn">-->
<!--            <button class="btn btn-info btn-search" onclick="search()">确认</button>-->
<!--            <!--            <button class="btn btn-info btn-search" onclick="show_login()" >登陆</button>-->
<!--        </span>-->
<!--    </div>-->
        <div class="row" style=" margin: 20 0 0 60;">
            <div class="form-inline">
                状态：
                <select id="status" class="easyui-combobox" name="status" style="width:100px;">
                    <option value="">全部</option>
                    <option value="处理中">处理中</option>
                    <option value="成功">成功</option>
                    <option value="失败">失败</option>
                    <option value="等待到账">等待到账</option>
                    <option value="未知">未知</option>
                    <option value="后台处理中">后台处理中</option>
                </select>
                属性：
                <select id="property" class="easyui-combobox" name="property" style="width:100px;">
                    <option value="">全部</option>
                    <option value="充值">充值</option>
                    <option value="提现">提现</option>
                </select>
                &nbsp;
                &nbsp;
                &nbsp;

                日期
                <input type="text" class="form-control" id="date1" name="" value="">
                -
                <input type="text" id="date2" class="form-control" name="" value="">
                <input type="text" id="account" class="form-control" placeholder="请输入登陆用户名！" name="" value="">
                <input type="button" class="form-control" onclick="search()" value="确定">
            </div>
        </div>
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">
    <table class=" easyui-datagrid" id="phone_list" title="充值提现汇总" style="width:100%;height:500px;"
           data-options="
                    url:'get_yx_rw_collect',
                    idField: 'id',
                    queryParams: {
                        m_db_name: 'rw_collect',
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
<script type="text/javascript" src="/assets/layui/layui.js"></script>

<script>

    //列表数据格式
    var col_data = [[
        {field: '真实姓名',title:'真实姓名',width:'10%',align:'center',halign:'center'},
        // {field: '身份证号',title:'登录用户名',width:'10%',align:'center',halign:'center'},
        // {field: '手机号',title:'登录用户名',width:'10%',align:'center',halign:'center'},
        {field: '登录用户名',title:'登录用户名',width:'10%',align:'center',halign:'center'},
        {field: '属性',title:'属性',width:'10%',align:'center',halign:'center'},
        {field: '流水号',title:'流水号',width:'24%',align:'center',halign:'center'},
        {field: '时间',title:'时间',width:'24%',align:'center',halign:'center'},
        {field: '状态',title:'状态',width:'10%',align:'center',halign:'center'},
        {field: '金额',title:'金额',width:'10%',align:'center',halign:'center'},

    ]];

    function search() {
        let date_start = $('#date1').val();
        let date_end = $('#date2').val();
        let status = $('#status').val();
        let account = $('#account').val();
        let property = $('#property').val();
        $("#phone_list").datagrid("load",{
            "date_start":date_start,
            "date_end":date_end,
            "status":status,
            "account":account,
            "property":property,
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