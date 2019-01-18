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
    <script type="text/javascript" src="/assets/apps/user/pool.js?<?=time()?>"></script>
    <script type="text/javascript" src="/assets/apps/user/sample.js?<?=time()?>"></script>
    <script type="text/javascript" src="/assets/apps/user/decision.js?<?=time()?>"></script>
    <script type="text/javascript">
        //var user_id = <?=$user_id?>;
    </script>
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

        .fitem select {
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

        #data input{
            width:110px;
        }

        #res table{
            width:100%;
            border:1px solid #000;
            text-align: center;
            border-collapse: collapse;
        }
        #res table tr td {
            font-size: 12px;
            border:1px solid #000;
            width: 20%;
            height: 20px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td class="tlabel">策略树管理</td>
            <td style="width:50%">
                <!-- <input class="col-sm-3" type="text" name="like" id="like" value="">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn">查询</button> -->
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('Pool','do_decision')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="show_add()" ><i class="fa fa-plus"></i>完善策略</a>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="add_root()" ><i class="fa fa-plus"></i>新增根策略</a>
                <?php endif ?>
                <?php if (checkRolePower('Pool','eval_decision_tree')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="eval_decision_tree()" ><i class="fa fa-plus"></i>运行策略</a>
                <?php endif ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>

        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table class="easyui-treegrid" id="zjc" title="策略树" style="width:100%;height:80%"
        data-options="
            url:'get_decision_tree?formatChildren=1',
            idField: 'tree_id',
            treeField: 'parent_return',
            rownumbers: true,
            method:'get',
            toolbar: '#toolbar',
            lines: true,
            border: false,
            //onLoadSuccess:function(row,data){
                //$(this).treegrid('collapseAll');
                //$(this).treegrid('expandTo',data[0]['children'][0]['tree_id']);        
            //},
            onBeforeExpand:function(row){
                $(this).treegrid('collapseAll',0);
                $(this).treegrid('expandTo',row.tree_id);
            },
            //fit: false,
            //fitColumns: true,
            //checkbox:true,
            //singleSelect:true,
            //collapsible:true,
            //pagination:true,
            //pageSize:10,
            //pageList:[10,20],
            //sortName:'edit_date',
            //remoteSort:true,
            //striped:true,
            columns:col_data,
        ">

        </table>
    </div>
    <script type="text/javascript">
        var col_data = [[
            {field: 'tree_id',title:'ID',width:50,align:'center'},
            {field: 'parent_id', title: '父级', width: 100,  align:'center', hidden: true},
            {field: 'parent_return',title:'父项结果',width:200,align:'left',halign:'center', editor: 'text'},
            {field: 'parent_return_action',title:'结果动作',width:150,align:'left',halign:'center',
                formatter:function(value, row, index){
                    var str = '';
                    $.each(actionData,function(k,v){
                        if(v.id==value) return str = v.text;
                    });
                    return str;
                }
            },
            {field: 'name',title:'策略名称',width:200,align:'left',halign:'center',formatter:function(value, row, index){
                if(value===null){
                    return '';
                }else{
                    return value;
                }
            }},
            {field: 'fun_info',title:'策略描述',width:300,align:'left',halign:'center'},
            {field: 'parent_return_info',title:'输出信息',width:100,align:'left',halign:'center'},
            {field: 'tree_create_date',title:'创建时间',width:150,align:'center'},
            {field: 'tree_edit_date',title:'编辑时间',width:150,align:'center'},
            {field: 'cao_zuo',title:'操作',width:200,align:'center',
                formatter:function (value, row, index) {

                    var html = '';
                    <?php if (checkRolePower('Pool','do_decision')): ?>
                    if(row.fun!=null || row.tree_id==0)
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="edit('+row.tree_id+')" >子项 </a>'+'&nbsp;&nbsp';
                    if(row.tree_id!=0){
                        html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="edit('+row.parent_id+')" >编辑 </a>'+'&nbsp;&nbsp';
                    }else{
                         html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="add_root()" >新增根策略 </a>'+'&nbsp;&nbsp';
                    }
                    
                    <?php endif ?>

                    <?php if (checkRolePower('Pool','del_decision')): ?>
                    html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="del('+row.tree_id+')" >删除 </a>';
                    <?php endif ?>
                    
                    return html;
                }
            },
        ]];

        // 编辑资料名称的界面的显示
        function edit(id) {
            is_add_root = false;
            $('#return_div').html('');
            $('#win').window('open').dialog('setTitle','编辑策略树');
            $('#win #ff').form('clear');
            $('#parent_id').combotree('setValue',id).combotree('readonly',true);
        }

        //添加资料名称的显示界面
        function show_add() {
            is_add_root = false;
            $('#return_div').html('');
            $('#win').window('open').dialog('setTitle','编辑策略树');
            $('#win #ff').form('clear');
            $('#parent_id').combotree('readonly',false);
        }

        //增加根分支标志
        var is_add_root = false;
        function add_root(){
            is_add_root = true;
            $('#return_div').html('');
            $('#win').window('open').dialog('setTitle','新增根策略');
            $('#win #ff').form('clear');
            $('#parent_id').combotree('setValue',0).combotree('readonly',true);
        }


        // 删除策略树
        function del(tree_id) {
            $.messager.confirm('提示','是否确定删除！',function (r) {
                if (r){
                    $.getJSON('del_decision_tree',{tree_id:tree_id},function(res){
                        if (res) {
                            $.messager.show({
                                title: '提示',
                                msg: '删除策略成功.',
                            });
                            $('#parent_id').combotree('reload');
                            $('#zjc').treegrid('remove',tree_id);
                        } else {
                            $.messager.show({
                                title: '提示',
                                msg: '删除策略失败.',
                            });
                        }
                    });
                }
            });
        }

        var selectId=0;
        // 添加、编辑策略
        function do_decision_tree() {
            $('#ff').form('submit', {
                'url':'do_decision_tree',
                onSubmit: function() {
                    // return true;
                    return $(this).form('enableValidation').form('validate');
                },
                success: function (result) {
                    var result = eval("(" + result + ")");
                    if(!result.ret){
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }else{
                        $('#win').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        selectId = result.ret;
                        $('#parent_id').combotree('reload');
                        $('#zjc').treegrid('reload');
                        // .treegrid({onLoadSuccess:function(){
                                // $(this).treegrid('select',selectId);/*.treegrid('expandTo',selectId);*/
                            // }
                        // });
                    }
                }
            });
        }

    //搜索
    // $('#likeBtn').on('click',function () {
    //     var like = $('#like ').val();
    //     $('#zjc').datagrid('load',{like:like});
    // });
    </script>

    <!-- 样本添加做活 选择类型选取默认样式 并展示样式  by 奚晓俊-->
    <div id="win" style="width:900px;height:550px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true,resizable:true,maximizable:true">
        <div class="easyui-layout" fit="true">
            <!-- 定义样式 -->
            <div data-options="region:'west',title:'定义策略树',border:false," style="width:50%;padding:10px 20px">
                <form id="ff" method="post" novalidate>
                    <input type="hidden" name="tree_id">
                    <div class="fitem">
                        <label>选择父项:</label>
                        <input name="parent_id" id="parent_id" class="easyui-combotree" required="true" 
                            data-options="
                                editable:true,
                                url: 'get_decision_tree?root=1&id=1&select=tree_id id,decision_id,name text,parent_return,parent_return_action',
                                valueField: 'id',
                                textField: 'text',
                                panelHeight:'auto',
                                lines:true,
                                panelHeight:400,
                                onChange:changeDecisionTree,
                                panelWidth:300,
                                onLoadSuccess:function(node,data){
                                    var t = $('#parent_id').combotree('tree');
                                    t.tree('collapseAll');
                                    t.tree('expandTo',data[0]['children'][0]['target']);
                                },
                                formatter:function(row){
                                    if(row.id!=0){
                                        if(row.text==null){
                                            row.text = '';
                                        }else{
                                            row.text = '('+row.text+')';
                                        }
                                        row.text = row.parent_return+':'+actionData2[row['parent_return_action']]+row.text;
                                    }
                                    return row.text;
                                },
                            "
                        >
                    </div>
                    <hr>
                    <div class="fitem">
                        <label>父项公式:</label>
                        <input name="fun" id="fun" type="text" class="easyui-textbox" novalidate="false" style="height:200px;" readonly="true" data-options="multiline:true">
                    </div>
                    <input type="hidden" name="name" id="name">
                    <hr>
                    <div class="fitem">
                        <h5>
                            父结果定义:
                            <a href="#" onclick="changeRes('decision_tree')"  class="easyui-linkbutton" icon="icon-redo">运行公式</a>
                        </h5>
                        <div id="return_div"></div>
                    </div>
                    <div style="padding:5px;text-align:center;">
                        <a href="#" onclick="do_decision_tree()"  class="easyui-linkbutton" icon="icon-ok">提交</a>
                        <a href="#" class="easyui-linkbutton" onclick="javascript:$('#win').dialog('close')" icon="icon-cancel">取消</a>
                    </div>
                </form>
            </div>
            <!-- 样式展示 -->
            <div data-options="region:'east',title:'策略结果展示',border:false," style="width:50%;padding:10px 20px">
                <form id="psz_test_sample" method="post" novalidate>
                <div class="fitem">
                    <label><h5>资料项id展示:</h5></label>
                    <input name="parent_id" id="parent_id" class="easyui-combotree" 
                        data-options="
                            editable:true,
                            url: 'getTreeSampleKey?root=1',
                            valueField: 'id',
                            textField: 'pool_key',
                            panelHeight:'auto',
                            lines:true,
                            panelHeight:400,
                            multiple:true,
                            onChange:getSample,
                        "
                    >
                </div>
                <input type="hidden" name="fun" id="fun">
                <div id="res">
                    <div id="show_sample"></div>
                    <div class="fitem">
                        <hr>
                        <h5>转译后公式:</h5>
                        <div id="res_fun"></div>
                        <hr>
                        <h5>策略结果:</h5>
                        <table>
                            <tr>
                                <td>策略名称</td>
                                <td>动作</td>
                                <!-- <td>评级</td> -->
                                <td>输出信息</td>
                                <td>后续策略</td>
                            </tr>
                            <tr>
                                <td id="name"></td>
                                <td id="res_action"></td>
                                <!-- <td id="res_score"></td> -->
                                <td id="res_info"></td>
                                <td id="res_decision"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                </form>
                <div id="add_return" style="display:none">
                    <div class="add_return">
                        <h5  class="return_text"></h5>
                        <div class="fitem">
                            <label>选取动作</label>
                            <input class="parent_return_action" required="true" editable="false">
                        </div>
                        <div class="fitem">
                            <label>后续策略</label>
                            <input class="decision_id" required="true" editable="false">
                        </div>
                        
                        <div class="fitem">
                            <label>动作信息</label>
                            <input class="parent_return_info" validType="length[1,250]" >
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</body>
</html>