<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
    <link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
    <title></title>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="/assets/lib/js/treegrid-dnd.js"></script>

    <style>
        .datagrid-row {
            height: 25px;
        }
        .sub-btn{
            text-align: right;
        }

        .tree-title {
            line-height: 25px;
        }
        body {
            height: auto;
            /*background-color: #fff;*/
            padding-top: 0px;
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
            width: 80px;
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
    </style>
</head>

<body>
    <!-- 新增、返回 -->
    <div class="col-md-12">
        <section class="content">
            <div class="box box-primary role-method" style="overflow:scroll;">
                <div class="box-header with-border">
                    <h3 class="box-title">权限管理</h3>
                    <div class="ibox-tools rboor" >
                        选定职位：
                        <select id="role" class="easyui-combotree" style="width:200px;" 
                            data-options="
                                url:'../department/get_role_tree?select=select',
                                required:true,
                                editable:true,
                                //checkbox:true,
                                lines:true,
                                //multiple:true,
                                //onCheck:function(node,checked){getRoleMethod(node, checked)},
                                onChange:function(newValue,oldValue){
                                    if(newValue<0){
                                        $.messager.show({
                                            title: '提示',
                                            msg: '不能选取部门！'
                                        });
                                        $('#role').combotree('setValue',oldValue);
                                    }else{
                                        getRoleMethod();
                                    }
                                },
                            " 
                        >
                        </select>
                        <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="$('#role').combotree('reload');" ><i class="fa fa-plus"></i>刷新职位</a>
                        <!-- <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="bindRoleMethod()" ><i class="fa fa-plus"></i>绑定权限</a> -->
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        <!-- 2018-12 角色改为职位管理 单独管理
                        <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addRole()" ><i class="fa fa-plus"></i>新建角色</a>
                        <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="editRole()" ><i class="fa fa-plus"></i>编辑角色</a>
                        <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="delRole()" ><i class="fa fa-plus"></i>删除角色</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
                        <!-- <a id="tb-add" href="javascript:window.parent.history.back(-1)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>返回</a> -->
                    <!-- </div>
                    <div class="ibox-tools rboor" style="text-align: right;"> -->
                        <!-- 搜索：
                        <select id="search" class="easyui-combotree" style="width:200px;" 
                            data-options="
                                url:'get_tree_for_select',
                                required:true,
                                editable:true,
                                lines:true,
                                options:function(v){
                                    console.log(v);
                                }
                            " 
                        >
                        </select> -->
                        <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addClass()" ><i class="fa fa-plus"></i>新增控制器</a>
                        <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addMethod()" ><i class="fa fa-plus"></i>新增方法</a>
                        <!-- <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addDetail()" ><i class="fa fa-plus"></i>新增参数</a> -->
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <!-- <a id="tb-add" href="javascript:window.parent.history.back(-1)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>返回</a> -->
                        <a id="tb-add" href="javascript:history.go(0)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>刷新</a>
                    </div>
                </div>

                <!-- <div style="margin:15px">⚠️注意：只显示选定第一个角色的权限</div> -->
                <!-- 表格 -->
                <table  id="tt" class="easyui-treegrid" style="width:100%;height:500px"
                    data-options="
                        url: 'get_class_method_tree',
                        idField: 'id',
                        treeField: 'name',
                        rownumbers: true,
                        method: 'get',
                        toolbar: '#toolbar',
                        lines: true,
                        fit: false,
                        fitColumns: true,
                        border: false,
                        columns:col_data,
                        //checkbox:true,
                        onLoadSuccess: function(row){
                            $(this).treegrid('enableDnd', row?row.id:null).treegrid('select',selectId);
                            //getRoleMethod();
                        },
                        onBeforeDrop: onBeforeDrop,
                        onBeforeExpand:function(row){
                            $(this).treegrid('collapseAll',0);
                            $(this).treegrid('expandTo',row.id);
                        },
                        animate:true,
                        ">
                </table>
            </div>
        </section>
    </div>


    <script type="text/javascript">

        function onBeforeDrop(targetRow,sourceRow,point){
            //方法同class
            if (targetRow.class != sourceRow.class) return false;
            //参数同method
            if(sourceRow.detail!=='' && (targetRow.method != sourceRow.method || targetRow.detail==='')) return false;
            // console.log('g');
            switch (point) {
                case 'top':
                    point=1;
                    break;
                case 'bottom':
                    point=2;
                    break;
                case 'append':
                    point=2;
                    break;
            }


            $.post('sort_method',{
                id:sourceRow.id,
                sort_id:targetRow.id,
                point:point
            },function(r){
                if (r) {
                    selectId = sourceRow.id;
                    $('#tt').treegrid('reload',{id:sourceRow.parent_id});
                }
            },'json');
            
            return false;
        }
        // 权限表格列数据定义
        var col_data = [[
            {field: 'name', title: '名称', width: 100, editor: 'text'},
            {field: 'dir', title: '控制器目录', width: 100,  align:'center', editor: 'text'},
            {field: 'class', title: '控制器', width: 100,  align:'center', editor: 'text'},
            {field: 'method', title: '方法', width: 100,  align:'center', editor: 'text'},
            {field: 'detail', title: '参数', width: 100,  align:'center', editor: 'text'},

            {field: 'parent_id', title: '父级', width: 100,  align:'center', hidden: true},
            {field: 'is_login', title: '登录验证', width: 50, align:'center',
                formatter: function(value, row, index) {
                    //if (row.detail!=='') return;
                    var html = '';
                    html += row.is_login == 1 ? '<span class="icon-ok"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>' : '<span class="icon-cancel"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>';
                    return html;
                }
            },
            {field: 'is_sys', title: '程序类型', width: 50,  align:'center',
                formatter: function(value, row, index) {
                    //if (row.detail!=='') return;
                    var html = '';
                    html += row.is_sys == 1 ? '<span>系统</span>' : '<span>普通</span>';
                    return html;
                }
            },
            {field: 'is_show', title: 'ACL显示', width: 50,  align:'center',
                formatter: function(value, row, index) {
                    //if (row.detail!=='') return;
                    var html = '';
                    html += row.is_show == 1 ? '<span class="icon-ok"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>' : '<span class="icon-cancel"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>';
                    return html;
                }
            },
            {field: 'is_loged', title: '记录日志', width: 50,  align:'center',
                formatter: function(value, row, index) {
                    //if (row.detail!=='') return;
                    var html = '';
                    html += row.is_loged == 1 ? '<span class="icon-ok"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>' : '<span class="icon-cancel"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>';
                    return html;
                }
            },
            {field: 'operate', title: '操作', width: 100,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    //角色授权
                    if(roleId!=undefined && row.state!=='closed'){
                        if($.inArray(parseInt(row.id),rolePower)>=0){
                            //已拥有权限
                            html += ' <a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="delRolePower(' + row.id + ')">删除权限 </a> '+'&nbsp;&nbsp;';
                        }else{
                            //没有权限
                            html += ' <a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="addRolePower(' + row.id + ')">授权 </a> '+'&nbsp;&nbsp;';
                        }
                    }
                    //根方法有新增参数按钮
                    if(row.parent_id!=0 && row.detail==='')
                        html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="addDetail('
                                +row.id+
                            ')" >新增参数 </a> '+'&nbsp;&nbsp;';
                    
                    //编辑控制器、方法
                    if(row.detail===''){
                        html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="edit('
                                +row.id+",'"
                                +row.name+"','"
                                +row.dir+"','"
                                +row.class+"',"
                                +(row.method?"'"+row.method+"'":null)+","
                                +row.parent_id+","
                                +row.is_login+","
                                +row.is_sys+","
                                +row.is_show+","
                                +row.is_loged+
                            ')" >编辑 </a> '+'&nbsp;&nbsp;';

                    //编辑参数
                    }else{
                        html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="editDetail('
                                +row.parent_id+","
                                +row.id+",'"
                                +row.name+"','"
                                +row.detail+
                            '\')" >编辑 </a> '+'&nbsp;&nbsp;';
                    }

                    html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="Confirm(\'确认删除这个记录\',' + row.id + ',' + row.parent_id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

        //获取角色权限
        var roleId;
        var roleText;
        var rolePower;
        function getRoleMethod(){
            // console.log('getRoleMethod');

            roleId = $('#role').combotree('getValue');
            roleText = $('#role').combotree('getText');
            rolePower = [];
            if(isNaN(roleId) || roleId<=0){
                $.messager.show({
                    title: '提示',
                    msg: '请选中要绑定的角色'
                });
                return ;
            }
            $.post('get_method_by_role',{role_id:roleId},function(result){
                $.each(result,function(k,v){
                    rolePower.push(parseInt(v.method_id)); 
                });
                $('#tt').treegrid('reload');
                // console.log(rolePower);
            },'json');
        }

        //授权
        function addRolePower(id){
            if(roleId==0) {
                $.messager.show({
                    title: '提示',
                    msg: '请选中要绑定的角色'
                });
                return ;
            }
            $.post('addRolePower',{role_id:roleId,method_id:id},function(result){
                    if (result.ret) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        //添加rolePower
                        rolePower.push(id);
                        $('#tt').treegrid('refresh',id);
                    } else {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                },'json');
        }

        //删除权限
        function delRolePower(id){
            if(roleId==0) {
                $.messager.show({
                    title: '提示',
                    msg: '请选中要绑定的角色'
                });
                return ;
            }
            $.post('delRolePower',{role_id:roleId,method_id:id},function(result){
                    if (result.ret) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        //删除rolePower
                        rolePower = $.grep(rolePower, function(v) {
                            return v!==id;
                        });
                        $('#tt').treegrid('refresh',id);
                    } else {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                },'json');
        }

        //添加角色页面
        function addRole() {
            $('#role-dlg').dialog('open').dialog('setTitle', '新增角色');
            $('#roleForm').form('clear');
            $('#roleForm #roleBtn .l-btn-text').text('新增');
        }

        //编辑角色页面
        function editRole() {
            var id;
            $.each( $('#role').combo('getValues') , function(i, n){
                if (n>0) {
                    id = n;
                    return false;
                }
            });

            if(id == undefined) {
                $.messager.show({
                    title: '提示',
                    msg: '请选中要编辑的角色'
                });
                return ;
            }
            $.post('get_role',{role_id:id},function(data){
                var row = data;
                $('#roleForm').form('load', row);
            },'json');
            $('#role-dlg').dialog('open').dialog('setTitle', '编辑角色');
            $('#roleForm').form('clear');
            $('#roleForm #roleBtn .l-btn-text').text('提交');
        }

        //执行角色添加、编辑
        function doRole(){
            $('#roleForm').form('submit', {
                url: 'do_role',
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    if (result.ret == false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#role-dlg').dialog('close');
                        $('#role').combotree('reload');
                        $('#role').combotree('setValue',result.id);
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                }
            });
        }

        //执行角色删除
        function delRole(){
            var num = 0;
            var ids = [];
            $.each( $('#role').combo('getValues') , function(i, n){
                if (n>0) {
                    ids.push(n);
                    num++;
                }
            });

            if(ids.length==0) {
                $.messager.show({
                    title: '提示',
                    msg: '请选中要删除的角色'
                });
                return ;
            }

            $.messager.confirm("确认",'确认删除'+num+'个角色', function(r) {
                if (r) {
                    $.post('del_role',{ids:ids},function(result){
                        if (result.ret == false) {
                            $.messager.show({
                                title: '提示',
                                msg: result.info
                            });
                        } else {
                            // $('#dlg').dialog('close');
                            $('#role').combotree('reload');
                            $('#role').combotree('setValue','');
                            $.messager.show({
                                title: '提示',
                                msg: result.info
                            });
                        }
                    },'json');
                }
            });
            return false;
        }


        var selectId=0;
        //添加控制器页面
        function addClass() {
            $('#class-dlg').dialog('open').dialog('setTitle', '新增控制器');
            $('#classForm').form('clear');
            $('#classForm #classBtn .l-btn-text').text('新增');
            var row = {
                is_show:1,
                is_login:1,
                is_sys:2,
                is_loged:1
            }
            $('#classForm').form('load', row);
        }
        //执行控制器添加、编辑
        function doClass(){
            $('#classForm').form('submit', {
                url: 'do_class',
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    if (result.ret == false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#class-dlg').dialog('close');
                        selectId = result.ret;
                        $('#tt').treegrid('reload');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });

                    }
                }
            });
        }

        //添加方法页面
        function addMethod() {
            //开窗
            $('#add-method-dlg').dialog('open').dialog('setTitle', '新增方法');
            $('#add-method-dlg #methodForm').form('clear');

            //加载class可选项
            $('#add-method-dlg #methodForm #class_id').combobox({  
                url:'get_class', 
                dataType: 'json', 
                valueField:'id',  
                textField:'class',
                onLoadSuccess:function(r){ //默认选中
                    var addClass = $('#tt').treegrid('getSelected');
                    if (addClass != undefined) {
                        if (addClass.parent_id==0) {
                            var id=addClass.id;
                        }else{
                            var id=addClass.parent_id;
                        }

                        $('#add-method-dlg #methodForm #class_id').combobox('select',id);
                    }
                },
                onSelect:function(r){
                    //加载方法可选项
                    $('#add-method-dlg #methodForm #method').combobox({
                        url:'get_method_by_class?id='+r.id, 
                        dataType: 'json', 
                        valueField:'method',  
                        textField:'method',
                        value:''
                    });
                    //选择控制器触发表单各项值为class默认值
                    $('#add-method-dlg #methodForm').form('load', r);
                }
            });
        }

        //执行方法添加
        function doAddMethod(){
            $('#add-method-dlg #methodForm').form('submit', {
                url: 'do_method',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    if (result.ret==false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#add-method-dlg').dialog('close');
                        selectId = result.ret;
                        $('#tt').treegrid('reload',{id:$('#add-method-dlg #class_id').textbox('getValue')});
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                }
            });
        }

        //编辑(控制器、方法)
        function edit(id,name,dir,className,method,parent_id,is_login,is_sys,is_show,is_loged) {
            //编辑控制器
            if(parent_id==0) {
                var row = {
                    id:id,
                    name:name,
                    dir:dir,
                    class:className,
                    method:method,
                    parent_id:parent_id,
                    is_login:is_login,
                    is_sys:is_sys,
                    is_show:is_show,
                    is_loged:is_loged
                }
                $('#class-dlg').dialog('open').dialog('setTitle', '编辑控制器');
                $('#classForm').form('clear');
                $('#classForm').form('load', row);
                $('#classForm #classBtn .l-btn-text').text('提交');

            //编辑方法
            }else{
                var row = {
                    id:id,
                    name:name,
                    class:className,
                    method:method,
                    class_id:parent_id,
                    parent_id:parent_id,
                    is_login:is_login,
                    is_sys:is_sys,
                    is_show:is_show,
                    is_loged:is_loged
                }
                // console.log(row);
                $('#edit-method-dlg').dialog('open').dialog('setTitle', '编辑方法');
                $('#edit-method-dlg #methodForm').form('clear');

                //加载class可选项
                $('#edit-method-dlg #methodForm #class_id').combobox({  
                    url:'get_class', 
                    dataType: 'json', 
                    valueField:'id',  
                    textField:'class',
                    onLoadSuccess:function(r){ //控制器默认选中
                        var addClass = $('#tt').treegrid('getSelected');
                        if (addClass != undefined) {
                            if (addClass.parent_id==0) {
                                var id=addClass.id;
                            }else{
                                var id=addClass.parent_id;
                            }

                            $('#edit-method-dlg #methodForm #class_id').combobox('select',id);
                        }
                    },
                    onSelect:function(r){
                        //加载方法可选项
                        $('#edit-method-dlg #methodForm #method').combobox({  
                            url:'get_method_by_class?id='+r.id, 
                            dataType: 'json', 
                            valueField:'method',  
                            textField:'method',
                            onLoadSuccess:function(r){ //方法默认选中
                                $('#edit-method-dlg #methodForm').form('load', row);
                            }
                        });
                    }
                });
            }
        }
        //执行方法编辑
        function doEditMethod(){
            $('#edit-method-dlg #methodForm').form('submit', {
                url: 'do_method',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    if (result.ret == false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#edit-method-dlg').dialog('close');
                        selectId = result.ret;
                        $('#tt').treegrid('reload',{id:$('#edit-method-dlg #class_id').textbox('getValue')});
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                }
            });
        }

        //添加参数页面
        function addDetail(method_id) {
            //开窗
            $('#detail-dlg').dialog('open').dialog('setTitle', '新增参数');
            $('#detail-dlg #detailForm').form('clear');
            $('#detail-dlg #detailForm #method_id').val(method_id);
        }

        //编辑参数页面
        function editDetail(method_id,id,name,detail) {
            //开窗
            $('#detail-dlg').dialog('open').dialog('setTitle', '编辑参数');
            $('#detail-dlg #detailForm').form('clear');
            $('#detail-dlg #detailForm #method_id').val(method_id);
            $('#detail-dlg #detailForm #id').val(id);
            // $('#detail-dlg #detailForm #name').val(name);
            $('#detail-dlg #detailForm #name').textbox('setValue',name);
            $('#detail-dlg #detailForm #detail').textbox('setValue',detail);
        }

        //执行添加、编辑参数
        function doDetail(){
            $('#detail-dlg #detailForm').form('submit', {
                url: 'do_detail',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    if (result.ret == false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#detail-dlg').dialog('close');
                        selectId = result.ret;
                        $('#tt').treegrid('reload',{id:$('#detail-dlg #method_id').val()});
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                }
            });
        }

        //是否删除
        function Confirm(msg,id,parent_id=0) {
            $.messager.confirm("确认", msg, function(r) {
                if (r) {
                    $.post('del_class_method',{id:id},function(result){
                        if (result.ret == false) {
                            $.messager.show({
                                title: '提示',
                                msg: result.info
                            });
                        } else {
                            // $('#dlg').dialog('close');
                            selectId = parent_id;
                            $('#tt').treegrid('remove',id);

                            $.messager.show({
                                title: '提示',
                                msg: result.info
                            });
                        }
                    },'json');
                }
            });
            return false;
        }
    </script>

    <!-- 新增/编辑角色 -->
    <div id="role-dlg" style="width:400px;height:160px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="role-dlg-buttons" data-options="modal:true">

        <form id="roleForm" method="post" novalidate>
            <div><input type="hidden" name="role_id"></div>
            <div class="fitem">
                <label>部门:</label>
                <input name="department" id="department" class="easyui-textbox" required="true" validType="length[1,20]">
            </div>
            <div class="fitem">
                <label>职能名称:</label>
                <input name="role_name" id="role_name" class="easyui-textbox" required="true" validType="length[1,20]">
            </div>
            <div id="role-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="roleBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doRole()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#role-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

    <!-- 新增/编辑控制器 -->
    <div id="class-dlg" style="width:400px;height:360px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="class-dlg-buttons" data-options="modal:true">

        <form id="classForm" method="post" novalidate>
            <div><input type="hidden" name="id"></div>
            <div class="fitem">
                <label>名称:</label>
                <input name="name" id="name" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>控制器目录:</label>
                <input name="dir" id="dir" class="easyui-textbox"  validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>控制器:</label>
                <input name="class" id="class" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div >
                <h5>该控制器方法的默认设置:</h5>
            </div>
            <div class="">
                <label>登录验证:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_login"  value="1"  checked="true" class="easyui-validatebox" required="true">开启
                    <input type="radio" name="is_login"  value="2" class="easyui-validatebox" required="true">关闭
                </div>
            </div>
            <div class="">
                <label>程序类型:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_sys" class="easyui-validatebox" value="2" validType="radio['class','is_sys']" checked="true"/>普通
                    <input type="radio" name="is_sys" class="easyui-validatebox" value="1"/>系统
                </div>
            </div>
            <div class="">
                <label>ACL列表:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_show" class="easyui-validatebox" value="1" validType="radio['class','is_show']" checked="true"/>显示
                    <input type="radio" name="is_show" class="easyui-validatebox" value="2"/>隐藏
                </div>
            </div>
            <div class="">
                <label>记录日志:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_loged" class="easyui-validatebox" value="1" validType="radio['class','is_loged']" checked="true"/>记录
                    <input type="radio" name="is_loged" class="easyui-validatebox" value="2"/>不记录
                </div>
            </div>
            <div id="class-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doClass()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#class-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

    <!-- 新增方法 -->
    <div id="add-method-dlg" style="width:400px;height:360px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="add-method-buttons" data-options="modal:true">
        <form id="methodForm" method="post" novalidate>
            <div class="fitem">
                <label>名称:</label>
                <input name="name" id="name" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>控制器:</label>
                <input name="class_id" id="class_id" class="easyui-textbox" required="true" url="get_class" valueField="id" textField="class" validType="integer">
            </div>
            <div class="fitem">
                <label>方法:</label>
                <input name="method" id="method" class="easyui-textbox" required="true" url="get_method_by_class" valueField="id" textField="method">
            </div>
            <div class="">
                <label>登录验证:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_login"  value="1"  checked="true" class="easyui-validatebox" required="true">开启
                    <input type="radio" name="is_login"  value="2" class="easyui-validatebox" required="true">关闭
                </div>
            </div>
            <div class="">
                <label>程序类型:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_sys" class="easyui-validatebox" value="2" validType="radio['class','is_sys']" checked="true"/>普通
                    <input type="radio" name="is_sys" class="easyui-validatebox" value="1"/>系统
                </div>
            </div>
            <div class="">
                <label>ACL列表:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_show" class="easyui-validatebox" value="1" validType="radio['class','is_show']" checked="true"/>显示
                    <input type="radio" name="is_show" class="easyui-validatebox" value="2"/>隐藏
                </div>
            </div>
            <div class="">
                <label>记录日志:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_loged" class="easyui-validatebox" value="1" validType="radio['class','is_loged']" checked="true"/>记录
                    <input type="radio" name="is_loged" class="easyui-validatebox" value="2"/>不记录
                </div>
            </div>
            <div id="add-method-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doAddMethod()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#add-method-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

    <!-- 编辑方法 -->
    <div id="edit-method-dlg" style="width:400px;height:360px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="edit-method-buttons" data-options="modal:true">
        <form id="methodForm" method="post" novalidate>
            <div><input type="hidden" name="id"></div>
            <div class="fitem">
                <label>名称:</label>
                <input name="name" id="name" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>控制器:</label>
                <input name="class_id" id="class_id" class="easyui-textbox" required="true" url="get_class" valueField="id" textField="class" validType="integer">
            </div>
            <div class="fitem">
                <label>方法:</label>
                <input name="method" id="method" class="easyui-textbox" required="true" url="get_method_by_class" valueField="id" textField="method">
            </div>
            <div class="">
                <label>登录验证:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_login"  value="1"  checked="true" class="easyui-validatebox" required="true">开启
                    <input type="radio" name="is_login"  value="2" class="easyui-validatebox" required="true">关闭
                </div>
            </div>
            <div class="">
                <label>程序类型:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_sys" class="easyui-validatebox" value="2" validType="radio['class','is_sys']" checked="true"/>普通
                    <input type="radio" name="is_sys" class="easyui-validatebox" value="1"/>系统
                </div>
            </div>
            <div class="">
                <label>ACL列表:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_show" class="easyui-validatebox" value="1" validType="radio['class','is_show']" checked="true"/>显示
                    <input type="radio" name="is_show" class="easyui-validatebox" value="2"/>隐藏
                </div>
            </div>
            <div class="">
                <label>记录日志:</label>
                <div style="position:relative;width:156px;left:80px;top:-18px;">
                    <input type="radio" name="is_loged" class="easyui-validatebox" value="1" validType="radio['class','is_loged']" checked="true"/>记录
                    <input type="radio" name="is_loged" class="easyui-validatebox" value="2"/>不记录
                </div>
            </div>
            <div id="edit-method-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doEditMethod()" style="width:90px">提交</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#edit-method-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

    <!-- 新增、编辑参数 -->
    <div id="detail-dlg" style="width:400px;height:160px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="detail-buttons" data-options="modal:true">
        <form id="detailForm" method="post" novalidate>
            <div><input type="hidden" name="method_id" id="method_id"></div>
            <div><input type="hidden" name="id" id="id"></div>
            <div class="fitem">
                <label>参数名称:</label>
                <input name="name" id="name" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>参数字段:</label>
                <input name="detail" id="detail" class="easyui-textbox" required="true" validType="length[1,20]">
            </div>
            <div id="detail-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doDetail()" style="width:90px">提交</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#detail-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
</body>
</html>