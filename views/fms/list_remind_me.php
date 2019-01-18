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
            <td>
                完成状态：<select name="complete_status" id="complete_status" class="easyui-combobox" style="width:100px;"
                    data-options="
                        formatter: function(value) {
                            var myClass = '';
                            if(value.value==='0') myClass = 'icon-cancel';
                            if(value.value==='1') myClass = 'icon-ok';
       
                            value = '<span class='+myClass+' style=display:inline-block;width:11px;background-size:100%;>&nbsp;</span>' + value.text;
                            return value;
                        },
                        onChange:function(row){
                            $('#tt').datagrid({
                                queryParams:{
                                    complete_status:$(this).val(),
                                }
                            });
                        },
                    "
                >
                    <option value="">全部</option>
                    <option value="0">待完成</option>
                    <option value="1">已完成</option>
                </select>
                <!-- &nbsp;&nbsp;&nbsp;&nbsp;
                机构产品名称：<input class="easyui-textbox" type="text" name="like" id="like" value="">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn"><?//='查询';?></button> -->
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('workLog','add_me_work_log')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="add()" ><i class="fa fa-plus"></i>新增</a>
                <?php endif ?>
                <a id="tb-add" href="javascript:window.parent.history.back(-1)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>返回</a>
                <!-- <a id="tb-add" href="javascript:history.go(0)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>刷新</a> -->
            </td>
        </tr>
        
        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table  id="tt" class="easyui-datagrid" style="width:100%;height:350px"
            data-options="
                url: 'get_me_work_log?for_from=for',
                rownumbers: true,
                method: 'get',
                toolbar: '#toolbar',
                lines: true,
                fit: true,
                fitColumns: false,
                border: false,
                columns:col_data,
                singleSelect:true,
                pagination:true,
                onSortColum: function (sort,order) {
                    $('#tt').datagrid('reload', {
                        sort: sort,
                        order: order
                　　});
                },
            "
        >
        </table>
    </div>
</div>
<script>
    //获取列表
        var col_data = [[
            {field: 'obj_type', title: '种类', width: 200, align:'center', 'sortable':true,
                formatter: function(value,row) {
                    if(row.wl_type==1){
                        return row.obj_name+'-流程信息';
                    }else{
                        return '手动提醒';
                    }
                }
            },
            {field: 'wl_content', title: '工作内容', width: 200,  align:'center', 'sortable':true},
            {field: 'from_uname', title: '来源', width: 150,  align:'center', 'sortable':true},
            {field: 'create_date', title: '开始时间', width: 150,  align:'center', 'sortable':true},
            {field: 'plan_date', title: '计划完成时间', width: 150,  align:'center', 'sortable':true},
            {field: 'complete_date', title: '实际完成时间', width: 150,  align:'center', 'sortable':true},
            {field: 'complete_status', title: '完成状态', width: 100,  align:'center', 'sortable':true,
                formatter: function(value) {
                    var myClass = '';
                    if(value==0) {
                        myClass = 'icon-cancel';
                        value = '待完成';
                    }else{
                        myClass = 'icon-ok';
                        value = '已完成';
                    }
                    
                    value = '<span class="'+myClass+'" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>' + value;
                    return value;
                }
            },
            {field: 'note', title: '完成备注', width: 100,  align:'center','sortable':true},
            {field: 'operate', title: '操作', width: 300,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    //查看流程
                    if(row.obj_url!==null){
                        html += '<a class="btn btn-primary btn-xs p310" href="../'+row.obj_url+row.obj_id+'" >查看流程 </a> '+'&nbsp;&nbsp;'; 
                    }else{
                        //手动完成
                        if(row.complete_status==0 && <?= checkRolePower('workLog','complete_me_work_log') ?>) html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="complete('+row.wl_id+')" >完成工作 </a> '+'&nbsp;&nbsp;';
                    }
                    //编辑进展
                    if(<?= checkRolePower('workLog','list_remind_me_march') ?>) html += '<a class="btn btn-primary btn-xs p310" href="list_remind_me_march?parent_id='+row.wl_id+'&complete_status='+row.complete_status+'&for_from=for" >工作进展 </a> '+'&nbsp;&nbsp;';

                    
                    //html += ' <a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="Confirm(\'确认删除这个记录\',' + row.jigou_id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

    //添加
        function add() {
            $('#workLog-dlg').dialog('open').dialog('setTitle', '新增我的工作');
            $('#workLogForm').form('clear');
            $('#workLogForm #classBtn .l-btn-text').text('新增');
        }

    //执行添加
        function doAdd() {
            $('#workLogForm').form('submit', {
                url: 'add_me_work_log',
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    // console.log(result);
                    if (result.ret == false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#workLog-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#tt').datagrid('reload');
                    }
                }
            });
        }

    //手动完成
        function complete(wl_id) {
            $('#completeWorkLog-dlg').dialog('open').dialog('setTitle', '完成我的工作');
            $('#complereWorkLogForm').form('clear');
            $('#wl_id').val(wl_id);
            $('#completeWorkLogForm #classBtn .l-btn-text').text('完成');
        }

    //执行手动完成
        function doComplete() {
            $('#completeWorkLogForm').form('submit', {
                url: 'complete_me_work_log',
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    // console.log(result);
                    if (result.ret == false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#completeWorkLog-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#tt').datagrid('reload');
                    }
                }
            });
        }

    //编辑
        // function edit(jg_id) {
        //     no_status();
        //     $('#jigou-dlg').dialog('open').dialog('setTitle', '编辑机构');
        //     $('#jiGouForm').form('clear');
        //     $.getJSON('get_jigou',{jg_id:jg_id},function(row){
        //         row.margin_rate = row.margin_rate/10000;
        //         $('#jiGouForm').form('load',row);
        //     });
        //     $('#jiGouForm #classBtn .l-btn-text').text('提交');
        // }

    //确认框
        function myConfirm(msg,fun,id){
            $.messager.confirm("确认", msg, function(r) {
                if (r) window[fun](id); 
            });
            return false;
        }

   </script>

    <!-- 新增我的工作日志 -->
    <div id="workLog-dlg" style="width:400px;max-height:450px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="workLog-dlg-buttons" data-options="modal:true">

        <form id="workLogForm" method="post" novalidate>
            <!-- <div class="fitem" hidden>
                <label>提醒对象:</label>
                <input name="for_admins[]" id="for_admins" class="easyui-combobox" style="width:346px" 
                    data-options="
                        url: '../PublicMethod/getAdmin',
                        valueField: 'id',
                        textField: 'username',
                        multiple:true,
                        panelHeight:'auto',
                        groupField:'department',
                    "
                >
            </div> -->
            <div class="fitem">
                <label>计划完成时间:</label>
                <input name="plan_date" id="plan_date" class="easyui-datetimebox" style="width:346px">
            </div>
            <div class="fitem">
                <label>工作内容:</label>
                <input name="wl_content" id="wl_content" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,250]" data-options="multiline:true" novalidate="true" required="true">
            </div>
            <div id="workLog-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doAdd()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#workLog-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

    <!-- 完成我的工作日志 -->
    <div id="completeWorkLog-dlg" style="width:400px;max-height:450px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="completeWorkLog-dlg-buttons" data-options="modal:true">

        <form id="completeWorkLogForm" method="post" novalidate>
            <!-- <div class="fitem" hidden>
                <label>提醒对象:</label>
                <input name="for_admins[]" id="for_admins" class="easyui-combobox" style="width:346px" 
                    data-options="
                        url: '../PublicMethod/getAdmin',
                        valueField: 'id',
                        textField: 'username',
                        multiple:true,
                        panelHeight:'auto',
                        groupField:'department',
                    "
                >
            </div> -->
            <input type="hidden" name="wl_id" id="wl_id">
            <!-- <div class="fitem">
                <label>计划完成时间:</label>
                <input name="plan_date" id="plan_date" class="easyui-datetimebox" style="width:346px">
            </div> -->
            <div class="fitem">
                <label>完成备注:</label>
                <input name="note" id="note" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,250]" data-options="multiline:true" novalidate="true" required="true">
            </div>
            <div id="completeWorkLog-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doComplete()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#completeWorkLog-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
</body>
</html>