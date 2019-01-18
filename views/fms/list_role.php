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

        #form label{
            font-size: 12px;
            margin-top: 5px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
        <div style="float:left;"><h3>职位管理</h3></div>
        <div class="ibox-tools rboor" style="float:right;margin-top:20px;margin-right:40px;">
            <?php if (checkRolePower('department','do_role')): ?>
                <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="add()" ><i class="fa fa-plus"></i>新增职位</a>
            <?php endif ?>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table  id="tt" class="easyui-treegrid" style="width:100%;height:350px"
                    data-options="
                        url: 'get_role_tree',
                        idField: 'role_id',
                        treeField: 'role_name',
                        rownumbers: true,
                        method: 'get',
                        toolbar: '#toolbar',
                        lines: true,
                        fit: true,
                        fitColumns: false,
                        border: false,
                        columns:col_data,
                        pagination:false,
                        ">
        </table>
    </div>
</div>
<script>
    //var statusColor = JSON.parse('<?//= $statusColor ?>');

    //获取列表
        var col_data = [[
            {field: 'role_id', title: '职位id', width: 100, align:'center', 'sortable':true},
            {field: 'department_name', title: '部门名称', width: 200, align:'center', 'sortable':true},
            {field: 'role_name', title: '职位名称', width: 300, align:'left', halign:'center',sortable:true},
            // {field: 'parent_role_name', title: '上级职位', width: 100,  align:'center', 'sortable':true},
            {field: 'role_function', title: '职位职责', width: 400,  align:'center', 'sortable':true},
            {field: 'level_range', title: '职级范围', width: 100,  align:'center', 'sortable':true},

            {field: 'operate', title: '操作', width: 150,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    //对接人
                    if(<?= checkRolePower('department','do_role') ?>) html += '<a class="btn btn-primary btn-xs p310" onclick="edit('+row.role_id+')">编辑 </a> '+'&nbsp;&nbsp;';

                    if(<?= checkRolePower('department','del') ?>) html += ' <a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确认删除这个记录\',\'del\',' + row.role_id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

    //添加
        function add() {
            $('#dlg').dialog('open').dialog('setTitle', '新增职位');
            $('#form').form('clear');
            $('#parent_role_id').combotree('reload');
            $('#department').combotree('reload');
            $('#level_range').combobox('reload');
            $('#form #classBtn .l-btn-text').text('新增');
        }

    //编辑
        function edit(role_id) {
            $('#dlg').dialog('open').dialog('setTitle', '编辑职位');
            $('#form').form('clear');
            $('#parent_role_id').combotree('reload');
            $('#department').combotree('reload');
            $('#level_range').combobox('reload');

            $.getJSON('get_role',{role_id:role_id},function(row){
                $('#form').form('load',row);
            });
            $('#form #classBtn .l-btn-text').text('提交');
        }

    //执行添加、编辑
        function doDepartment() {
            $('#form').form('submit', {
                url: 'do_role',
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
                        $('#dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#tt').treegrid('reload');
                    }
                }
            });
        }

    //删除
        function del(id){
            $.getJSON('del_role',{role_id:id},function(row){
                $.messager.show({
                        title: '提示',
                        msg: row['msg'],
                    });
                if(row['ret']){
                    $('#tt').treegrid('reload');
                    $('#parent_role_id').combotree('reload');
                }
            });
        }

    //确认框
        function myConfirm(msg,fun,id){
            $.messager.confirm("确认", msg, function(r) {
                if (r) window[fun](id); 
            });
            return false;
        }


    $('#likeBtn').on('click',function () {
        var like = $('#like ').val();
        $('#tt').datagrid('load',{like:like});
    });

   </script>

    <!-- 新增/编辑 -->
    <div id="dlg" style="width:400px;max-height:450px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="dlg-buttons" data-options="modal:true">

        <form id="form" method="post" novalidate>
            <div><input type="hidden" name="role_id"></div>
            <div class="fitem">
            <label>所属部门:</label>
                <input name="department" id="department" class="easyui-combotree" required="true"
                    data-options="
                        url: 'get_department_tree?select=select',
                        valueField: 'department_id',
                        textField: 'department_name',
                        //multiple:true,
                        //groupField:'department',
                        //panelHeight:'auto',
                        editable:false,
                       "
                 >
            </div>
            <div class="fitem">
                <label>上级职位:</label>
                <input name="parent_role_id" id="parent_role_id" class="easyui-combotree" required="true"
                    data-options="
                        url: 'get_role_tree?select=select&root=1',
                        valueField: 'role_id',
                        textField: 'role_name',
                        //multiple:true,
                        //groupField:'department',
                        //panelHeight:'auto',
                        editable:false,
                       "
                 >
            </div>
            <div class="fitem">
                <label>职位名称:</label>
                <input name="role_name" id="role_name" class="easyui-textbox" required="true" validType="length[1,20]">
            </div>
            <div class="fitem">
                <label>职位职级:</label>
                <input name="level_range" id="level_range" class="easyui-combobox"
                    data-options="
                        //url: '../PublicMethod/getAdmin',
                        valueField: 'id',
                        textField: 'username',
                        multiple:true,
                        //groupField:'department',
                        panelHeight:'auto',
                        
                       "
                 >
            </div>
            <div class="fitem">
                <label>职位职责:</label>
                <input name="role_function" id="role_function" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,255]" data-options="multiline:true" novalidate="true">
            </div>
            <div id="dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doDepartment()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

</body>
</html>