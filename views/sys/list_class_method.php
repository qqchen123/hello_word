<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
    <link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
    <title></title>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/plugins/jquery.treegrid.js"></script>

    <style>
        .datagrid-row {
            height: 25px;
        }
        .sub-btn{
            text-align: right;
        }
        /*.tree-file {
            width: 19px;
            height: 24px;
        }*/

        /*.tree-folder {
        	width: 24px;
            height: 24px;
        	background-image:url('<?//=STATIC_URL?>/sys/js/jstree/themes/default/40px.png');
        	background-position: -27px -28px;
        	background-size: 80px;
        }*/
        /*.tree-folder-open {
        	width: 24px;
            height: 24px;
        	background-image:url('<?//=STATIC_URL?>/sys/js/jstree/themes/default/40px.png');
        	background-position: -27px -28px;
        	background-size: 80px;
        }*/
        /*.tree-file {
        	background-image:url('<?//=STATIC_URL?>/sys/js/jstree/themes/default/40px.png');
        	background-position: -4px -108px;
        	background-size: 80px;
        }*/
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
    </style>
</head>

<body>
    <!-- 新增、返回 -->
    <div class="col-md-12">
        <section class="content">
            <div class="box box-primary" style="overflow:scroll;">
                <div class="box-header with-border">
                    <h3 class="box-title">控制器&方法</h3>
                    <div class="ibox-tools rboor" style="text-align: right;">
                        <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addClass()" ><i class="fa fa-plus"></i>新增控制器</a>
                        <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addMethod()" ><i class="fa fa-plus"></i>新增方法</a>
                        <a id="tb-add" href="javascript:window.parent.history.back(-1)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>返回</a>
                    </div>
                </div>
                <!-- 表格 -->
                <table  id="tt" class="easyui-treegrid" style="width:100%;height:350px"
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
                        ">
                </table>
            </div>
        </section>
    </div>


    <script type="text/javascript">
        // 表格列数据定义
        var col_data = [[
            {field: 'name', title: '名称', width: 100, editor: 'text'},
            {field: 'class', title: '控制器', width: 100,  align:'center', editor: 'text'},
            {field: 'method', title: '方法', width: 100,  align:'center', editor: 'text'},
            {field: 'parent_id', title: '父级', width: 100,  align:'center', hidden: true},
            {field: 'is_login', title: '登录验证', width: 50, align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    html += row.is_login == 1 ? '<span class="icon-ok"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>' : '<span class="icon-cancel"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>';
                    return html;
                }
            },
            {field: 'is_sys', title: '程序类型', width: 50,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    html += row.is_sys == 1 ? '<span>系统</span>' : '<span>普通</span>';
                    return html;
                }
            },
            {field: 'is_show', title: 'ACL显示', width: 50,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    html += row.is_show == 1 ? '<span class="icon-ok"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>' : '<span class="icon-cancel"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>';
                    return html;
                }
            },
            {field: 'is_loged', title: '记录日志', width: 50,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    html += row.is_loged == 1 ? '<span class="icon-ok"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>' : '<span class="icon-cancel"style="display: inline-block;width: 11px;background-size: 100%">&nbsp;</span>';
                    return html;
                }
            },
            {field: 'operate', title: '操作', width: 100,  align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="edit('
                            +row.id+",'"
                            +row.name+"','"
                            +row.class+"',"
                            +(row.method?"'"+row.method+"'":null)+","
                            +row.parent_id+","
                            +row.is_login+","
                            +row.is_sys+","
                            +row.is_show+","
                            +row.is_loged+
                        ')" >编辑 </a> '+'&nbsp;&nbsp;';
                    html += ' <a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="Confirm(\'确认删除这个记录\',' + row.id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

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
                    if (result.ret == false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#add-method-dlg').dialog('close');
                        $('#tt').treegrid('reload');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                }
            });
        }

        //编辑(控制器、方法)
        function edit(id,name,className,method,parent_id,is_login,is_sys,is_show,is_loged) {
            //编辑控制器
            if(parent_id==0) {
                var row = {
                    id:id,
                    name:name,
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
                        $('#tt').treegrid('reload');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                }
            });
        }

        //是否删除
        function Confirm(msg, id) {
            $.messager.confirm("确认", msg, function(r) {
                if (r) {
                    $.post('del_class_method',{id:id},function(result){
                        if (result.ret == false) {
                            $.messager.show({
                                title: '提示',
                                msg: result.info
                            });
                        } else {
                            $('#dlg').dialog('close');
                            $('#tt').treegrid('reload');
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

    <!-- 新增/编辑控制器 -->
    <div id="class-dlg" style="width:400px;height:360px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="class-dlg-buttons" data-options="modal:true">

        <form id="classForm" method="post" novalidate>
            <div><input type="hidden" name="id"></div>
            <div class="fitem">
                <label>名称:</label>
                <input name="name" id="name" class="easyui-textbox" required="true" validType="length[1,30]">
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
</body>
</html>