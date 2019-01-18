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
        #return_div input{
            width:110px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td class="tlabel">策略管理</td>
            <td>
                <input class="col-sm-3" type="text" name="like" id="like" value="">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn">查询</button>
                </td>
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('Pool','do_decision')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="show_add()" ><i class="fa fa-plus"></i>新增策略</a>
                <?php endif ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>

        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table class="easyui-datagrid" id="tt" title="策略" style="width:100%;height:80%"
        data-options="
            url:'get_decision',
            rownumbers: true,
            method:'get',
            toolbar: '#toolbar',
            lines: true,
            border: false,
            fit: false,
            //fitColumns: true,
            //checkbox:true,
            //singleSelect:true,
            //collapsible:true,
            pagination:true,
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
            {field: 'id',title:'ID',width:50,align:'center', 'sortable':true},
            {field: 'name',title:'策略名称',width:200,align:'left',halign:'center', 'sortable':true},
            {field: 'fun_info',title:'策略描述',width:300,align:'left',halign:'center', 'sortable':true},
            {field: 'return',title:'结果信息',width:250,align:'left',halign:'center', 'sortable':true,
                formatter: function(value,row,index){
                    var o = JSON.parse(value);
                    var str = '';
                    $.each(o,function(k,v){
                        str += '值：'+k+'(信息：'+v+')<br>';
                    });
                    return str;
                }
            },
            {field: 'fun',title:'公式',width:300,align:'left',halign:'center', 'sortable':true},
            {field: 'create_date',title:'创建时间',width:150,align:'center', 'sortable':true},
            {field: 'edit_date',title:'编辑时间',width:150,align:'center', 'sortable':true},
            {field: 'cao_zuo',title:'操作',width:200,align:'center',
                formatter:function (value, row, index) {

                    var html = '';
                    <?php if (checkRolePower('Pool','do_decision')): ?>
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="edit('+row.id+')" >编辑 </a>'+'&nbsp;&nbsp';
                    <?php endif ?>

                    <?php if (checkRolePower('Pool','del_decision')): ?>
                    html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="del('+row.id+')" >删除 </a>';
                    <?php endif ?>
                    
                    return html;
                }
            },
        ]];

        // 编辑资料名称的界面的显示
        function edit(id) {
            $('#win').window('open').dialog('setTitle','编辑策略');
            $('#win #ff').form('clear');
            $('.add_return').remove();
            // $('#parent_id').combotree('reload');
            $.getJSON('get_decision',{id:id},function(row){
                var arr = JSON.parse(row.return);
                var js = 0;
                var add = $("#return_div div:last a");
                $.each(arr,function(k,v){
                    if(js>0) add.click();
                    $("#return_div div:last .return_value").textbox('setValue',k);
                    $("#return_div div:last .return_text").textbox('setValue',v);
                    js++;
                });
                $('#win #ff').form('load',row);
            });
        }

        //添加资料名称的显示界面
        function show_add() {
            $('#win').window('open').dialog('setTitle','新增策略');
            $('#win #ff').form('clear');
            $('.add_return').remove();
        }
        // 删除资料名称
        function del(id) {
            $.messager.confirm('提示','确定删除！',function (r) {
                if (r){
                    $.getJSON('del_decision',{id:id},function(res){
                        if (res) {
                            $.messager.show({
                                title: '提示',
                                msg: '删除策略成功.',
                            });
                            $('#tt').datagrid('reload');
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
        function do_decision() {
            $('#ff').form('submit', {
                'url':'do_decision',
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
                       
                        $('#tt').datagrid('reload').datagrid({onLoadSuccess:function(){
                                $(this).datagrid('select',selectId).datagrid('expandTo',selectId);
                            }
                        });
                    }
                }
            });
        }

    //搜索
    // $('#likeBtn').on('click',function () {
    //     var like = $('#like ').val();
    //     $('#tt').datagrid('load',{like:like});
    // });
    </script>

    <!-- 样本添加做活 选择类型选取默认样式 并展示样式  by 奚晓俊-->
    <div id="win" style="width:900px;height:550px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true,resizable:true,maximizable:true">
        <div class="easyui-layout" fit="true">
            <!-- 定义样式 -->
            <div data-options="region:'west',title:'定义策略',border:false," style="width:50%;padding:10px 20px">
                <form id="ff" method="post" novalidate>
                    <input type="hidden" name="id">
                    <div class="fitem">
                        <label>策略名称:</label>
                        <input name="name" id="name" class="easyui-textbox" required="true" validType="length[1,20]" data-options="onChange:changeRes">
                    </div>
                    <div class="fitem">
                        <label>
                            策略描述:
                        </label>
                        <input name="fun_info" id="fun_info" type="text" class="easyui-textbox" required="true" validtype="length[0,250]" novalidate="true" style="height:100px;" 
                            data-options="
                                multiline:true,
                                onChange:changeRes,
                                prompt:'请输入本策略公式描述',
                            ">
                    </div>
                    <hr>
                    
                    <div class="fitem">
                        <h5>结果信息（记录所有可能的结果,布尔值转1、0）:</h5>

                        <!-- <input name="return[0]" id="return" class="easyui-textbox" required="true" validType="length[1,250]" data-options="onChange:changeRes"> -->

                        <div id="return_div">
                            <div class="fitem">
                                结果值:
                                <input name="return_value[]" class="easyui-textbox return_value" required="true" validType="length[1,20]">
                                结果描述:
                                <input name="return_text[]" class="easyui-textbox return_text" required="true" validType="length[1,100]" >
                                <a href="#" class="easyui-linkbutton" onclick="javascript:addReturn();" icon="icon-add"></a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="fitem">
                        <label>
                            策略公式:<br>
                            (结果为布尔值)<br>
                            <hr>
                            <a href="#" onclick="changeRes()"  class="easyui-linkbutton" icon="icon-redo">运行公式</a>
                        </label>
                        <input name="fun" id="fun" type="text" class="easyui-textbox" required="true" validtype="length[0,20000]" novalidate="true" style="height:200px;" 
                            data-options="
                                multiline:true,
                                onChange:changeRes,
                                prompt:'请输入策略公式\n示例：资料项id为1+资料项id为2\n{{1}}+{{2}}',
                            ">
                    </div>
                    <hr>
                    <div style="padding:5px;text-align:center;">
                        <a href="#" onclick="do_decision()"  class="easyui-linkbutton" icon="icon-ok">提交</a>
                        <a href="#" class="easyui-linkbutton" onclick="javascript:$('#win').dialog('close')" icon="icon-cancel">取消</a>
                    </div>
                </form>
                <div id="add_return" style="display:none">
                    <div class="fitem">
                        结果值:
                        <input name="return_value[]" class="return_value" required="true" validType="length[1,20]" >
                        结果描述:
                        <input name="return_text[]" class="return_text" required="true" validType="length[1,100]" >
                        <a href="#" class="easyui-linkbutton" onclick="javascript:$(this).parent('.fitem').remove();" icon="icon-cancel"></a>
                    </div>
                </div>
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
                        <div id="res_return"></div>
                        <!-- <table>
                            <tr>
                                <td>策略名称</td>
                                <td>结论</td>
                                <td>评级</td>
                                <td>信息</td>
                            </tr>
                            <tr>
                                <td id="name"></td>
                                <td id="res_level"></td>
                                <td id="res_score"></td>
                                <td id="res_info"></td>
                            </tr>
                        </table> -->
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>