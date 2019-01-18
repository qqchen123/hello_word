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
    #jiGouForm label{
        font-size: 12px;
        margin-top: 5px;
    }

    #data input{
        width:110px;
    }
    .sms_phone{
        margin: 20px 0 0 30px;
    }
    .sms_phone label{
        font-size: 14px;
    }
    .sms_phone input{
        width: 200px;
        height: 30px;
        border-radius: 5px;
    }
    .sms_phone div{
        margin: 10px 0 0 40px;
    }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
        <table class="table table-bordered" style="margin: 0;padding: 0px">
            <tbody>
            <tr>
                <td class="tlabel">喜报</td>
                <td colspan="4" class="align-center">
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="xb_show()" ><i class="fa fa-plus"></i>发送喜报</a>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="add_phone()" ><i class="fa fa-plus"></i>添加手机</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table class=" easyui-datagrid" id="phone_list" title="发送列表" style="width:70%;height:500px;"
               data-options="
                    url:'phone',
                    idField: 'id',
                    treeField: 'phone',
                    rownumbers: true,
                    method:'get',
                    lines: true,
                    border: false,
                    columns:col_data,
                    pagination: true
        ">
        </table>
    </div>
    </div>

    <div id="win_add_phone" class="easyui-window" title="添加手机" style="width:400px;height:200px"
         data-options="modal:true,closed:true">
        <form id="sms_phone" class="sms_phone" method="post">
            <input type="hidden" id="e_id" name="id">
            <div>
                <label for="">姓名:</label>
                <input class="easyui-validatebox" type="text" id="e_username" name="username" data-options="required:true,validType:'isBlank'" />
            </div>
            <div>
                <label for="">手机:</label>
                <input class="easyui-validatebox" validtype="mobile" type="text" id="e_phone" name="phone" data-options="required:true" />
            </div>
            <div style="padding:5px;text-align:center;margin-top: 20px;">
                <a href="#" onclick="add_phone_info()" id="add_phone"  class="easyui-linkbutton" icon="icon-ok">确认添加</a>
                <a href="#" onclick="edit_phone_info()"  id="edit_phone" class="easyui-linkbutton" icon="icon-ok">确认编辑</a>
                <a href="#" class="easyui-linkbutton" onclick="javascript:$('#win_add_phone').window('close')" icon="icon-cancel">取消</a>
            </div>
        </form>
    </div>
    <div id="win_edit_xb"  class="easyui-window" title="编辑喜报" style="width:400px;height:300px"
         data-options="modal:true,closed:true">
        <form id="sms_send_xb" class="sms_phone" method="post">
            <div>
                <label for="phone">部门:</label>
                <input class="easyui-validatebox" type="text" id="branch" name="branch" data-options="required:true,validType:'isBlank'" />
            </div>
            <div>
                <label for="username">姓名:</label>
                <input class="easyui-combobox" id="username" name="user_id" data-options="
                                                                                    url: 'get_username',
                                                                                    valueField: 'id',
                                                                                    textField: 'username',
                                                                                    required:true,validType:'isBlank'
                                                                                        " />
            </div>
            <div>
                <label for="business">业务:</label>
                <input class="easyui-validatebox" type="text" id="business" name="business" data-options="required:true,validType:'isBlank'" />
            </div>
            <div>
                <label for="money">金额:</label>
                <input class="easyui-validatebox" type="text" id="money" name="money" data-options="required:true,validType:'isBlank'" />
            </div>
            <div style="padding:5px;text-align:center;margin-top: 20px;">
                <a href="#" onclick="send_xb()"  class="easyui-linkbutton" icon="icon-ok">发送</a>
                <a href="#" class="easyui-linkbutton" onclick="javascript:$('#win_edit_xb').window('close')" icon="icon-cancel">取消</a>
            </div>
        </form>
    </div>
</body>
<script type="text/javascript">
    $.extend($.fn.validatebox.defaults.rules, {
        //移动手机号码验证
        mobile: {//value值为文本框中的值
            validator: function (value) {
                var reg = /^1[3|4|5|6|7|8|9]\d{9}$/;
                return reg.test(value);
            },
            message: '输入手机号码格式不准确.'
        },
        isBlank: {
            validator: function (value, param) { return $.trim(value) != '' },
            message: '不能为空，全空格也不行'
        }
    });

    //列表数据格式
    var col_data = [[
        // {field: 'id',title:'ID',width:0,align:'center'},
        {field: 'username',title:'用户',width:'15%',align:'center',halign:'center'},
        {field: 'phone',title:'手机',width:'17%',align:'center',halign:'center'},
        {field: 'create_time',title:'创建时间',width:'24%',align:'center',halign:'center'},
        {field: 'xb_count',title:'喜报总数',width:'10%',align:'center',halign:'center',
            formatter:function (value,row) {
                if (row.xb_count){
                    return row.xb_count;
                } else{
                    return 0;
                }
            }
        },
        {field: 'status',title:'状态',width:'10%',align:'center',halign:'center',
            formatter:function (value,row) {
                if (row.status==1){
                    return '正常';
                } else{
                    return '禁用';
                }
            }
        },
        {field: 'opera',title:'操作',width:'25%',align:'center',halign:'center',formatter:function (value, row) {
                var html = '';
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="show_edit_sms('+row.id+')" >编辑 </a>'+'&nbsp;&nbsp';
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="toggle_sms('+row.id+','+row.status+')" >';
                html+=  row.status =='1'?'禁用':'启用';
                html+= '</a>'+'&nbsp;&nbsp';
                html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="del_sms('+row.id+')" >删除 </a>';
                return html;
            }
        },
    ]];
    $('#phone_list').datagrid({
        rowStyler:function(index,row){
            if (row.status ==0){
                return 'background-color:pink;color:#fff';
            }else{
                return 'font-weight:bold;';
            }
        }
    });
    function add_phone() {
        $('#edit_phone').hide();
        $('#add_phone').show();
        $('#win_add_phone').window('open');

        $('#sms_phone').form('clear');
    }
    function edit_phone_info() {
        $('#sms_phone').form('submit', {
            url: 'edit_phone_info',
            onSubmit: function(){
            },
            success: function(result){
                if (result == 0) {
                    $.messager.show({
                        title: '提示',
                        msg: '修改失败'
                    });
                } else {
                    $('#win_add_phone').dialog('close');
                    $.messager.show({
                        title: '提示',
                        msg: '修改成功'
                    });
                    $('#phone_list').datagrid('reload');
                }
            }
        });
    }
    function show_edit_sms(id) {
        $('#add_phone').hide();
        $('#edit_phone').show();
        $.ajax({
            type: "POST",
            url: "get_one_info",
            data: {id:id},
            dataType: "json",
            success: function(data){
                $('#e_username').val(data.username);
                $('#e_phone').val(data.phone);
                $('#e_id').val(data.id);
            }
        });
        $('#win_add_phone').window('open').dialog('setTitle','编辑');
    }
    function add_phone_info() {
        $('#sms_phone').form('submit', {
            url: 'add_phone',
            onSubmit: function(param){
                return $("#sms_phone").form('validate');
            },
            success: function(result){
                var result = eval("(" + result + ")");
                if (result.code == 0) {
                    $.messager.show({
                        title: '提示',
                        msg: result.message
                    });
                } else {
                    $('#win_add_phone').dialog('close');
                    $.messager.show({
                        title: '提示',
                        msg: result.message
                    });
                    $('#phone_list').datagrid('reload');
                }
            }
        });
    }
    function xb_show() {
        $('#win_edit_xb').window('open');
        $('#username').combobox({
            url:'get_username',
            valueField:'id',
            textField:'username'
        });
        $('#win_edit_xb').form('clear');
    }

    function send_xb() {
        $.messager.progress();
        $('#sms_send_xb').form('submit', {
            url: 'send_xb_message',
            onSubmit(){
                return $("#sms_send_xb").form('validate');
            },
            success(result){
                var result = eval("(" + result + ")");
                if (result.code == 0) {
                    $.messager.show({
                        title: '提示',
                        msg: result.message
                    });
                    $.messager.progress('close');
                } else {
                    $.messager.show({
                        title: '提示',
                        msg: result.message
                    });
                    $('#win_edit_xb').dialog('close');
                    $('#phone_list').datagrid('reload');
                    $.messager.progress('close');
                }
            }
        });
    }
    function del_sms(id) {
        $.getJSON('delete_sms',{id:id},function(row){
            if (row==1) {
                $.messager.show({
                    title: '提示',
                    msg: '删除成功!'
                });
                $('#phone_list').datagrid('reload');
            } else {
                $.messager.show({
                    title: '提示',
                    msg: '删除失败！'
                });
            }
        });
    }
    function toggle_sms(id,status) {
        $.ajax({
            type: "POST",
            url: "toggle_sms",
            data: {id:id,status:status},
            dataType: "json",
            success: function(data){
                if (data==1) {
                    $.messager.show({
                        title: '提示',
                        msg: '操作成功!'
                    });
                    $('#phone_list').datagrid('reload');
                } else {
                    $.messager.show({
                        title: '提示',
                        msg: '操作失败！'
                    });
                }
            }
        });
    }
</script>
</html>