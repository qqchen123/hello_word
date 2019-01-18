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
        <div style="float:left;"><h3>员工管理</h3></div>
        <div class="ibox-tools rboor" style="float:right;margin-top:20px;margin-right:40px;">
            <?php if (checkRolePower('department','do_role')): ?>
                <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="add()" ><i class="fa fa-plus"></i>新增员工</a>
            <?php endif ?>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <!-- <div style="padding:0 20px;"> -->
            <input class="easyui-textbox" type="text" name="like" id="like" style="width:500px;" prompt="员工编号、员工名称、主所属部门、主要职位、次要职位、身份证、手机、邮箱">
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
            <br><br>
        <!-- </div> -->
        <table id="tt" class="easyui-datagrid"
                    data-options="
                        url: 'get_wesing_merchant',
                        rownumbers: true,
                        method: 'get',
                        //toolbar: '#toolbar',
                        //lines: true,
                        //fit: true,
                        fitColumns: false,
                        border: false,
                        columns:col_data,
                        pagination:true,
                        ">
        </table>
    </div>
</div>
<script>
    //获取列表
        var col_data = [[
            {field: 'id', title: '员工id', width: 50, align:'center', 'sortable':true},
            {field: 'userid', title: '员工编号', width: 100, align:'center', 'sortable':true},
            {field: 'username', title: '员工名称', width: 100, align:'center', 'sortable':true},
            {field: 'dzt', title: '员工状态', width: 100, align:'center', halign:'center',sortable:true,
                formatter:function(value){
                    if(value==01){
                        return '已启用';
                    }else{
                        return '已禁用';
                    }
                }
            },
            {field: 'department_name', title: '主所属部门', width: 100,  align:'center', 'sortable':true},
            {field: 'main_role_name', title: '主要职位', width: 100,  align:'center', 'sortable':true},
            {field: 'less_role_name', title: '次要职位', width: 200,  align:'center', 'sortable':true},
            {field: 'idno', title: '身份证号', width: 150,  align:'center', 'sortable':true},
            {field: 'mobile', title: '手机', width: 100,  align:'center', 'sortable':true},
            {field: 'email', title: '邮箱', width: 200,  align:'center', 'sortable':true},
            {field: 'rzdate', title: '入职时间', width: 100,  align:'center', 'sortable':true},
            {field: 'cdate', title: '创建时间', width: 150,  align:'center', 'sortable':true},
            {field: 'udate', title: '编辑时间', width: 150,  align:'center', 'sortable':true},
            {field: 'operate', title: '操作', width: 150,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    //对接人
                    if(<?= checkRolePower('department','do_wesing_merchant') ?>) html += '<a class="btn btn-primary btn-xs p310" onclick="edit('+row.id+')">编辑 </a> '+'&nbsp;&nbsp;';

                    if(<?= checkRolePower('department','del_wesing_merchant') ?>) html += ' <a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确认删除这个记录\',\'del\',' + row.id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

    //添加
        function add() {
            $('#dlg').dialog('open').dialog('setTitle', '新增员工');
            $('#form').form('clear');
            $('#departmentid').combotree('reload');
            $('#userrole').combotree('reload');
            $('#less_role_id').combotree('reload');
            $('#level_range').combobox('reload');

            $('#form #classBtn .l-btn-text').text('新增');
        }

    //编辑
        function edit(id) {
            $('#dlg').dialog('open').dialog('setTitle', '编辑员工');
            $('#form').form('clear');
            $('#departmentid').combotree('reload');
            $('#userrole').combotree('reload');
            $('#less_role_id').combotree('reload');
            $('#level_range').combobox('reload');
            $.getJSON('get_wesing_merchant_by_id',{id:id},function(row){
                $('#form').form('load',row);
            });
            $('#form #classBtn .l-btn-text').text('提交');
        }

    //执行添加、编辑
        function doWesingMerchant() {
            $('#form').form('submit', {
                url: 'do_wesing_merchant',
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
                        $('#tt').datagrid('reload');
                        // $('#parent_role_id').combotree('reload');
                    }
                }
            });
        }

    //删除
        function del(id){
            $.getJSON('del_wesing_merchant',{id:id},function(row){
                $.messager.show({
                        title: '提示',
                        msg: row['msg'],
                    });
                if(row['ret']){
                    $('#tt').datagrid('reload');
                    // $('#parent_role_id').combotree('reload');
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
            <div><input type="hidden" name="id"></div>
            <div class="fitem">
                <label>状态:</label>
                <input name="dzt" id="dzt" class="easyui-combobox" required="true" 
                    data-options="
                        data:[{id:'01',text:'启用'},{id:'00',text:'禁用'}],
                        valueField: 'id',
                        textField: 'text',
                        panelHeight:'auto',
                    "
                 >
            </div>
            <div class="fitem">
            <label>主所属部门:</label>
                <input name="departmentid" id="departmentid" class="easyui-combotree" required="true"
                    data-options="
                        url: 'get_department_tree?select=select',
                        valueField: 'department_id',
                        textField: 'department_name',
                        //multiple:true,
                        //groupField:'department',
                        //panelHeight:'200',
                        editable:false,
                       "
                 >
            </div>
            <div class="fitem">
                <label>主要职位:</label>
                <input name="userrole" id="userrole" class="easyui-combotree" required="true"
                    data-options="
                        url: 'get_role_tree?select=select',
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
                <label>次要职位:</label>
                <input name="less_role_id[]" id="less_role_id" class="easyui-combotree"
                    data-options="
                        url: 'get_role_tree?select=select',
                        valueField: 'role_id',
                        textField: 'role_name',
                        multiple:true,
                        //groupField:'department',
                        //panelHeight:'200',
                        editable:false,
                        cascadeCheck:false,//取消勾选属性
                        onCheck:function(node, checked){
                            var childList = $(this).tree('getChildren',node.target);
                            if(childList.length>0){
                                var checkedTrue = function(){
                                    childList.map(function(currentValue){
                                        $('div[node-id=\''+currentValue.id+'\']').find('.tree-checkbox').removeClass('tree-checkbox0').addClass('tree-checkbox1');
                                    })
                                };
                                var checkedFalse = function(){
                                    $.each(childList,function(index,currentValue){  
                                        $('div[node-id=\''+currentValue.id+'\']').find('.tree-checkbox').removeClass('tree-checkbox1').addClass('tree-checkbox0');
                                    })
                                };
                                var checkChangeProperties = checked==true ? checkedTrue() : checkedFalse();
                            }
                        },
                    "
                 >
            </div>
            <div class="fitem">
                <label>员工编号:</label>
                <input name="userid" id="userid" class="easyui-textbox" required="true" validType="length[1,10]">
            </div>
            <div class="fitem">
                <label>员工名称:</label>
                <input name="username" id="username" class="easyui-textbox" required="true" validType="length[1,50]">
            </div>
            <div class="fitem">
                <label>身份证:</label>
                <input name="idno" id="idno" class="easyui-textbox" validType="length[1,18]">
            </div>
            <div class="fitem">
                <label>手机号:</label>
                <input name="mobile" id="mobile" class="easyui-textbox" validType="length[1,11]">
            </div>
            <div class="fitem">
                <label>邮件:</label>
                <input name="email" id="email" class="easyui-textbox" validType="length[1,50]">
            </div>
            <div class="fitem">
                <label>入职日期:</label>
                <input name="rzdate" id="rzdate" class="easyui-datebox" required="true">
            </div>
            <div class="fitem">
                <label>职位职级:</label>
                <input name="level_range" id="level_range" class="easyui-combobox"
                    data-options="
                        //url: '../PublicMethod/getAdmin',
                        valueField: 'id',
                        textField: 'username',
                        //multiple:true,
                        //groupField:'department',
                        panelHeight:'auto',
                    "
                >
            </div>

            <!-- <div class="fitem">
                <label>职位职责:</label>
                <input name="role_function" id="role_function" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,255]" data-options="multiline:true" novalidate="true">
            </div> -->
            <div id="dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doWesingMerchant()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
</body>
</html>