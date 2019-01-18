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
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #cccccc;">
    <div class="input-group col-md-3" style="margin: 0 auto; margin-top: 17px;">
<!--        <input type="text" id="kh_id" class="form-control" placeholder="请输入客户ID！" / >-->
        <span class="input-group-btn">
<!--            <button class="btn btn-info btn-search" onclick="make_id()">确认</button>-->
            <button class="btn btn-info btn-search" onclick="gcheck()" >点击批量审核</button>
        </span>
    </div>
</div>
<div region="center" data-options="border:false,title:'银信用户审核'" style="">
    <table id="check_user_list"></table>
</div>


<div id="dd" class="easyui-dialog form-horizontal" role="form" title="新增银信用户" style="width:500px;height:500px;"
     data-options="resizable:true,modal:true,closed:true">
    <form id="ff" method="post">

        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">用户编号：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control easyui-validatebox" data-options="required:true"  id="fuserid" name="fuserid" placeholder="请输入用户编号">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">姓名：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control easyui-validatebox" data-options="required:true"  id="name" name="name" placeholder="请输入姓名">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">身份证：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control easyui-validatebox" data-options="required:true"  id="idnumber" name="idnumber" placeholder="请输入身份证">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">渠道编号：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="channel" name="channel" placeholder="请输入渠道编号">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">创建员工：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="cjyg" name="cjyg" placeholder="请输入创建员工">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">银信账号：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control easyui-validatebox" data-options="required:true"  id="yx_account" name="yx_account" placeholder="请输入银信账号">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">银信密码：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control easyui-validatebox" data-options="required:true"  id="pwd" name="pwd" placeholder="请输入银信密码">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">开户手机：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control easyui-validatebox" data-options="required:true"  id="reg_phone" name="reg_phone" placeholder="请输入开户手机">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">开户日期：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="reg_time" name="reg_time" placeholder="请输入开户日期">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">创建时间：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="ctime" name="ctime" placeholder="请输入创建时间">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-4 control-label">银信绑定手机：</label>
            <div class="col-sm-6">
                <input type="text" class="form-control easyui-validatebox" data-options="required:true"  id="binding_phone" name="binding_phone" placeholder="请输入银信绑定手机">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" onclick="add_yx_user_info()" class="btn btn-default">提交</button>
            </div>
        </div>
    </form>
</div>


</body>

<script>
    $('#check_user_list').datagrid({
        url: 'new_yx_user_list',
        idField: 'id',
        rownumbers: true,
        method: 'post',
        lines: true,
        border: false,
        pagination: true,
        columns: [[
            // {field: 'checkbox',title:'选择',align:'center',halign:'center',checkbox:true},
            {field: 'channel',title:'渠道编号',align:'center',halign:'center'},
            {field: 'name',title:'客户姓名',align:'center',halign:'center'},
            {field: 'idnumber',title:'身份证号码',align:'center',halign:'center'},
            {field: 'fuserid',title:'我司客户编号',align:'center',halign:'center'},
            {field: 'yx_account',title:'银信编号',align:'center',halign:'center'},
            {field: 'reg_phone',title:'银信绑定手机',align:'center'},
            // {field: 'reg_time',title:'银信开户时间',align:'center',halign:'center'},
            {field: 'ctime',title:'创建时间',align:'center',halign:'center'},
            {field: 'check_status',title:'状态',align:'center',halign:'center',
                formatter:function (value,row) {
                    var html = '';
                    if (row.check_status==0) {
                        html = '待审核';
                    }else if(row.check_status==1) {
                        html = '通过';
                    }else{
                        html = '拒绝';
                    }
                    return html;
                }
            },
            {field: 'operate1',title:'审核',width:'',align:'center',halign:'center',
                formatter:function (value,row) {
                    var html = '';
                    let account = row.yx_account;
                    if (row.check_status==1){
                        html='审核完成！';
                        // html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="do_rep_data('+'\''+account+'\''+')" >点击爬取数据 </a>'+'&nbsp;&nbsp';
                    }else if(row.check_status==-1){
                        html='审核完成！';
                    }else{
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="do_check('+'\''+account+'\''+',1)" >通过 </a>'+'&nbsp;&nbsp';
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="do_check('+'\''+account+'\''+',-1)" >拒绝 </a>'+'&nbsp;&nbsp';
                    }
                    return html;
                }
            },
        ]]
    });
    //批量爬取数据
    function gcheck(){
        // var rows = $("#check_user_list").datagrid("getRows");
        // let selarrs = new Array();
        // for(var i=0;i<rows.length;i++)
        // {
        //     selarrs[i] = rows[i].account;
        // }
        $.ajax({
            type: "POST",
            url: "check_rep",
            // data: {sel:selarrs},
            dataType: "json",
            success: function(data){
                // console.log(data);
                $('#check_user_list').datagrid('reload');
            }
        });

    }
    /**
     * 新增银信用户
     */
    $('#ff').form({
        url: 'add_yx_user_info',
        onSubmit: function () {
            // do some check
            // return false to prevent submit;
        },
        success: function (data) {
            // alert(data);
            console.log(data);
        }
    });
    function add_yx_user_info(){
        $('#ff').submit();
    }

    /**
     * 审核通过
     */
    function do_check(account,status) {
        $.ajax({
            type: "POST",
            url: 'check_new_user_yx',
            data: {account, check_status:status},
            dataType: "json",
            success(data){
                console.log(data);
                if (data.code == 1) {
                    $.messager.show({
                        title: '提示',
                        msg: data.msg
                    });
                    $('#check_user_list').datagrid('reload');
                } else {
                    $.messager.show({
                        title: '提示',
                        msg: data.msg
                    });
                    $('#check_user_list').datagrid('reload');
                }
            }
        });
        // setTimeout("location.reload()",5000);
        // window.location.reload();
    }
    function do_rep_data(account) {
        $.ajax({
            type: "POST",
            data: {account},
            url: 'do_rep',
            success(data){
            }
        });
        alert('开始爬取，请稍等。');
    }

</script>

</body>