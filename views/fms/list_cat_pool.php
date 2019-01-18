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
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td class="tlabel">猫池管理</td>
            <td>
                <input class="easyui-textbox" type="text" name="like" id="like" prompt="客户编号、姓名、手机">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
                </td>
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('CatPool','update_status_and_data')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="update_status_and_data()" ><i class="fa fa-plus"></i>更新状态</a>
                <?php endif ?>
                <?php if (checkRolePower('CatPool','update_money')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="myConfirm('查询余额需要耗时1分钟左右，是否继续？','update_money')" ><i class="fa fa-plus"></i>查询余额</a>
                <?php endif ?>
                <?php if (checkRolePower('CatPool','update_tc_money')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="myConfirm('查询套餐价格需要耗时1分钟左右，是否继续？','update_tc_money')" ><i class="fa fa-plus"></i>查询套餐价格</a>
                <?php endif ?>
                <!-- <a id="tb-add" href="javascript:window.parent.history.back(-1)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>返回</a>
                <a id="tb-add" href="javascript:history.go(0)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>刷新</a> -->
            </td>
        </tr>
        
        </tbody>
    </table>
    </div>

    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table  id="tt" class="easyui-datagrid" style="width:100%;height:350px"
                    data-options="
                        url: 'get_cat_pool',
                        rownumbers: true,
                        method: 'get',
                        toolbar: '#toolbar',
                        lines: true,
                        fit: true,
                        fitColumns: false,
                        border: false,
                        columns:col_data,
                        pagination:true,
                        onSortColum: function (sort,order) {
                            $('#tt').datagrid('reload', {
                                sort: sort,
                                order: order
                        　　});
                        },
                        singleSelect:true,
                        ">
        </table>
    </div>
</div>
<script>
    var sim_options = JSON.parse('<?= $sim_options ?>');

    //获取列表
        var col_data = [[
            {field: 'cp_id', title: '猫池id', width: 50, align:'center', 'sortable':true},
            {field: 'fuserid', title: '客户编号', width: 100,  align:'center', 'sortable':true},
            {field: 'name', title: '客户姓名', width: 100,  align:'center', 'sortable':true},
            {field: 'line_id', title: '猫池线路', width: 100,  align:'center', 'sortable':true},
            //{field: 'goip_id', title: '猫池数据库id', width: 100,  align:'center', 'sortable':true},
            {field: 'carrier', title: '运营商', width: 100,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if(sim_options[value]){
                        return sim_options[value]['name'];
                    }else{
                        return value;
                    }
                },
            },
            {field: 'status', title: '状态', width: 100,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    var myClass = '';
                    if (row.status=='有效') myClass = 'icon-ok';
                    if (row.status=='无效') myClass = 'icon-cancel';
                    value = '<span class="'+myClass+'" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>' + value;
                    return value;
                },
            },
            {field: 'sim_num', title: '手机号码', width: 150,  align:'center', 'sortable':true},
            {field: 'money', title: '余额', width: 80, align:'center', halign:'center', 'sortable':true,},
            {field: 'get_money_time', title: '获取余额时间', width: 150, align:'center', halign:'center', 'sortable':true,formatter: function(value, row, index) {;
                    if(value!='0000-00-00 00:00:00') 
                    return value;
                }
            },
            {field: 'tc_money', title: '套餐价格', width: 80, align:'center', halign:'center', 'sortable':true,},
            {field: 'get_tc_money_time', title: '获取套餐价格时间', width: 150, align:'center', halign:'center', 'sortable':true,
            },
            {field: 'imei', title: 'imei', width: 150, align:'center', halign:'center', 'sortable':true},
            {field: 'imsi', title: 'imsi', width: 150, align:'center', halign:'center', 'sortable':true},
            {field: 'iccid', title: 'iccid', width: 150, align:'center', halign:'center', 'sortable':true},

            {field: 'sim_type', title: '电话卡<br>类型', width: 80, align:'center', halign:'center', 'sortable':true},
            {field: 'if_get', title: '是否移交', width: 80, align:'center', halign:'center', 'sortable':true},
            {field: 'from_man', title: '移交人', width: 80, align:'center', halign:'center', 'sortable':true},
            {field: 'from_date', title: '申请日期', width: 80, align:'center', halign:'center', 'sortable':true},
            {field: 'note', title: '备注', width: 150, align:'center', halign:'center', 'sortable':true},
            {field: 'operate', title: '操作', width: 300,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    //编辑
                    if(<?= checkRolePower('CatPool','edit') ?>) html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="edit('+row.cp_id+')" >编辑 </a> '+'&nbsp;&nbsp;';
                    //查看短信
                    if(<?= checkRolePower('CatPool','list_sms') ?>) html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="list_sms(\''+row.iccid+'\')" >查看短信 </a> '+'&nbsp;&nbsp;';
                    //充值
                    if(<?= checkRolePower('CatPool','pay_money') ?>) html += '<a class="btn btn-primary btn-xs p310" href="pay_money?cp_id='+row.cp_id+'">充值 </a> '+'&nbsp;&nbsp;';
                    //充值记录
                    if(<?= checkRolePower('CatPool','get_pay_data') ?>) html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="get_pay_data('+row.cp_id+')" >话费流水 </a> '+'&nbsp;&nbsp;';
                    //开启自动充值
                    if(<?= checkRolePower('CatPool','auto_pay') ?>){
                        if (row.auto_pay=='已开启自动充值'){
                            var c = 'btn-success';
                        }else{
                            var c = 'btn-danger';
                        }
                        html += '<a class="btn btn-xs '+c+' p310" href="javascript:void(0)" onclick="auto_pay('+row.cp_id+
                        ')" >'+row.auto_pay+' </a> '+'&nbsp;&nbsp;';
                    }
                    return html;
                }
            }
        ]];

    //获取猫池实时状态和数据，并更新数据库
        function update_status_and_data(){
            $.getJSON('update_status_and_data',{},function(data){
                //console.log(data);
                $.messager.show({
                    title: '提示',
                    msg: data.info
                });
                if (data.ret == true) {
                    $('#tt').datagrid('reload');
                }
            });
        }

    //实时查询余额
        function update_money(){
            open_jh();

            $.get('update_money',{},function(data){
                $('#jh-dlg').dialog('close');
                var data = eval("(" + data + ")");
                $.messager.show({
                    title: '提示',
                    msg: data.info
                });
                if (data.ret) $('#tt').datagrid('reload');
                
            });
        }

    //查询套餐价格
        function update_tc_money(){
            open_jh();

            $.get('update_tc_money',{},function(data){
                $('#jh-dlg').dialog('close');
                var data = eval("(" + data + ")");
                $.messager.show({
                    title: '提示',
                    msg: data.info
                });
                if (data.ret) $('#tt').datagrid('reload');
                
            });
        }

    //显示菊花
        function open_jh(){
            $('#jh-dlg').dialog('open');
            var o = $('#jh-dlg').parent();
            o.hide();
            o.next().hide();
            var z = o.nextAll('.window-mask').first();
            z.html('<div style="margin:10% auto;width:100;"><img id="loading" style="width:100px;" src="/assets/images/loading.gif"></div>');
        }

    //编辑
        function edit(cp_id) {
            //no_status();
            $('#edit-dlg').dialog('open').dialog('setTitle', '编辑猫池');
            $('#form').form('clear');
            $.getJSON('get_cat_pool_by_cpid',{cp_id:cp_id},function(row){
                if(row.sim_num){
                    $('#sim_num').textbox({disabled:true}).parent().hide();
                }else{
                    $('#sim_num').textbox({disabled:false}).parent().show();
                }
                $('#form').form('load',row);
            });
        }

    //执行编辑
        function do_edit() {
            // alert(1);
            $('#form').form('submit', {
                url: 'edit',
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    if (result) {
                        $.messager.show({
                            title: '提示',
                            msg: '编辑成功'
                        });
                        $('#edit-dlg').dialog('close');
                        $('#tt').datagrid('reload');
                    } else {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                }
            });
        }

    //查看短信
        function list_sms(iccid){
            $('#list_sms-dlg').dialog('open').dialog('setTitle', '短信信息');
            $('#list_sms_tt').datagrid({url: 'list_sms?iccid='+iccid});
        }

    //确认框
        function myConfirm(msg,fun,id){
            $.messager.confirm("确认", msg, function(r) {
                if (r) window[fun](id); 
            });
            return false;
        }

    //猫池搜索
        $('#likeBtn').on('click',function () {
            var like = $('#like ').val();
            $('#tt').datagrid('load',{like:like});
        });

    //短信搜索
        function sms_like(){
            var like = $('#sms_like').val();
            $('#list_sms_tt').datagrid('reload',{like:like});
        }

    //开关自动充值
        function auto_pay(cp_id){
            $.getJSON('auto_pay',{cp_id:cp_id},function(row){
                if(row){
                    $.messager.show({
                        title: '提示',
                        msg: '操作成功'
                    });
                    $('#tt').datagrid('reload');
                }else{
                    $.messager.show({
                        title: '提示',
                        msg: '操作失败'
                    });
                }
            });
        }

    //话费流水
    function get_pay_data(cp_id){
        $('#pay_data-dlg').dialog('open').dialog('setTitle', '编辑猫池');
        $('#pay_tt').datagrid({url: 'get_pay_data?cp_id='+cp_id});
        
    }

   </script>

    <div id="jh-dlg" style="width:0px;max-height:0px;text-align:center;" class="easyui-dialog" closed="true" buttons="edit-dlg-buttons" data-options="modal:true"></div>

    <!-- 话费流水 -->
     <div id="pay_data-dlg" style="width:900px;height:550px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true,resizable:true,maximizable:true">
        <table  id="pay_tt" class="easyui-datagrid" style="width:100%;height:350px"
                    data-options="
                        rownumbers: true,
                        method: 'get',
                        toolbar: '#toolbar',
                        lines: true,
                        fit: true,
                        fitColumns: false,
                        border: false,
                        columns:pay_data,
                        pagination:true,
                        onSortColum: function (sort,order) {
                            $('#tt').datagrid('reload', {
                                sort: sort,
                                order: order
                        　　});
                        },
                        singleSelect:true,
                        ">
        </table>
        <script type="text/javascript">
            var pay_data = [[
                {field: 'cp_id', title: '猫池id', width: 50, align:'center', 'sortable':true},
                {field: 'money', title: '余额', width: 100, align:'center', 'sortable':true},
                {field: 'get_money_time', title: '获取余额时间', width: 150, align:'center', 'sortable':true},
                {field: 'pay_money', title: '充值金额', width: 100, align:'center', 'sortable':true},
                {field: 'pay_money_time', title: '充值时间', width: 150, align:'center', 'sortable':true},
            ]];
        </script>
    </div>

    <!-- 查看短信 -->
     <div id="list_sms-dlg" style="width:900px;height:600px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true,resizable:true,maximizable:true">

        <div style="padding-bottom:20px">
            过滤:
            <input name="sms_like" id="sms_like" class="easyui-textbox" prompt="短信内容、来自手机号">
            <button class="btn btn-success ml2" onclick="sms_like()">查询</button>
        </div>

        <table  id="list_sms_tt" class="easyui-datagrid" style="width:100%;height:450px"
                    data-options="
                        rownumbers: true,
                        method: 'get',
                        toolbar: '#toolbar',
                        lines: true,
                        //fit: true,
                        fitColumns: true,
                        border: false,
                        columns: sms,
                        pagination:true,
                        onSortColum: function (sort,order) {
                            $('#tt').datagrid('reload', {
                                sort: sort,
                                order: order
                        　　});
                        },
                        singleSelect:true,
                        nowrap:false,
                        ">
        </table>
        <script type="text/javascript">
            var sms = [[
                {field: 'srcnum', title: '来自', width: 100, align:'center', 'sortable':true},
                {field: 'time', title: '时间', width: 150, align:'center', 'sortable':true},
                {field: 'msg', title: '短信内容', width: 500, align:'left', 'sortable':true},
            ]];
        </script>
    </div>

    <!-- 新增/编辑 -->
    <div id="edit-dlg" style="width:400px;max-height:450px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="edit-dlg-buttons" data-options="modal:true">

        <form id="form" method="post" novalidate>
            <div><input type="hidden" name="cp_id"></div>
            <div class="fitem">
                <label>手机号码:</label>
                <input name="sim_num" id="sim_num" class="easyui-textbox" required="true" validType="length[11,15]">
            </div>
            <div class="fitem">
                <label>客户名称:</label>
                <input name="user_id" id="user_id" class="easyui-combobox" required="true"
                    data-options="
                        url: '../PublicMethod/get_user_name_for_select',
                        valueField: 'id',
                        textField: 'name',
                        //multiple:true,
                        panelHeight:'auto',
                        groupField:'fuserid',
                        groupFormatter:function(group){
                            return group;
                        }
                    ">
            </div>
            <div class="fitem">
                <label>电话卡类型:</label>
                <input name="sim_type" id="sim_type" class="easyui-combobox" required="true" validType="radio['本地','外地']"
                    data-options="
                        data:[{i:'本地',v:'本地'},{i:'外地',v:'外地'}],
                        valueField: 'i',
                        textField: 'v',
                        panelHeight:'auto',
                    "
                >
            </div>
            <div class="fitem">
                <label>是否移交:</label>
                <input name="if_get" id="if_get" class="easyui-combobox" required="true" validType="radio['是','否']"
                    data-options="
                        data:[{i:'是',v:'是'},{i:'否',v:'否'}],
                        valueField: 'i',
                        textField: 'v',
                        panelHeight:'auto',
                    "
                >
            </div>
            <div class="fitem">
                <label>申请日期:</label>
                <input name="from_date" id="from_date" class="easyui-datebox" required="true">
            </div>
            <div class="fitem">
                <label>移交人:</label>
                <input name="from_man" id="from_man" class="easyui-textbox" required="true" validType="length[1,10]">
            </div>

            <div class="fitem">
                <label>备注:</label>
                <input name="note" id="note" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,250]" data-options="multiline:true" novalidate="true">
            </div>
            <div id="edit-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="do_edit()" style="width:90px">编辑</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#edit-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
</body>
</html>