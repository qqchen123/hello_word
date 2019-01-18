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
            width: 100px;
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

        #productForm label{
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
                选定机构：<select id="jg" class="easyui-combobox" style="width:200px;" 
                    data-options="
                        url:'get_jg_name',
                        method:'get',
                        valueField:'jg_id',
                        textField:'jg_name',
                        groupField:'obj_status',
                        groupFormatter:function(group){
                            return '<span style=background-color:'+statusColor[group]+'>'+statusArr[group]+'：</span>';
                        },
                        panelHeight:'300',
                        onChange:function(row){
                            $('#product').combobox(
                                'reload','get_product_name?jg_id='+row
                            );
                            $('#product').combobox('clear');
                            //$('#jg').combobox({value:row});
                        },
                        onSelect:function(row){
                            jg_row = row;
                        },
                        <?php if (isset($jg_id)): ?>
                            value:'<?= $jg_id ?>',
                        <?php endif ?>
                    ">
                ></select>
                &nbsp;&nbsp;&nbsp;&nbsp;
                机构产品：<select id="product" class="easyui-combobox" style="width:200px;" 
                    data-options="
                        url:'get_product_name?jg_id=<?= @$jg_id ?>',
                        method:'get',
                        valueField:'product_id',
                        textField:'product_name',
                        groupField:'obj_status',
                        groupFormatter:function(group){
                            return '<span style=background-color:'+statusColor[group]+'>'+statusArr[group]+'：</span>';
                        },
                        panelHeight:'300',
                        <?php if (isset($product_id)): ?>
                            value:'<?= $product_id ?>',
                        <?php endif ?>
                        onLoadSuccess:function(){
                            $('#tt').datagrid({
                                url: 'get_cost',
                                queryParams:{
                                    product_id:$('#product').combobox('getValue'),
                                    jg_id:$('#jg').combobox('getValue')
                                }
                            });
                        },
                        onChange:function(row){
                            $('#tt').datagrid({
                                url: 'get_cost',
                                queryParams:{
                                    product_id:$('#product').combobox('getValue'),
                                    jg_id:$('#jg').combobox('getValue')
                                }
                            });
                        },
                        onSelect:function(row){
                            product_row = row;
                        },
                        onClickCell:function(rowIndex, field, value){
                            if(field!='obj_status_info') return;
                            $.messager.alert('状态详情', value);
                        }
                    " 
                ></select>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <!-- 名称：<input class="easyui-textbox" type="text" name="like" id="like" value="">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn"><?//='查询';?></button> -->
                </td>
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('jigou','do_cost')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addCost()" ><i class="fa fa-plus"></i>新增</a>
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
                        rowStyler:function(index,row){
                            return 'background-color:'+statusColor[row.product_status];
                        },
                        singleSelect:true,
                        onClickCell:function(rowIndex, field, value){
                            if(field!='jg_status_info'&& field!='product_status_info') return;
                            $.messager.alert('状态详情', value);
                        }
                        ">
        </table>
    </div>
</div>

<script>
    //状态数组
    var statusArr = JSON.parse('<?= json_encode($this->statusArr) ?>');
    //状态颜色
    var statusColor = JSON.parse('<?= json_encode($this->statusColor) ?>');
    //当前选中机构
    var jg_row;
    //当前选中机构产品
    var product_row;
    //获取产品收费标准列表
        var col_data = [[
            {field: 'jg_name', title: '机构名称', width: 100, align:'center', 'sortable':true,
                    styler: function(value,row,index){
                        return 'background-color:'+statusColor[row.jg_status];
                    }
            },
            {field: 'jg_status_info', title: '机构状态', width: 200, align:'center', 'sortable':true,
                styler: function(value,row,index){
                        return 'cursor: pointer;background-color:'+statusColor[row.jg_status];
                },
                formatter: function(value, row, index) {
                    var str ='';
                    if(value.length>10) str='...';
                    value = value.substring(0,10)+str;

                    var myClass = '';
                    if (row.jg_status==20) myClass = 'icon-ok';
                    if (row.jg_status==40) myClass = 'icon-cancel';
                    value = '<span class="'+myClass+'" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>' + value;
                    return value;
                },
            },
            {field: 'product_name', title: '机构产品名称', width: 100, align:'center', 'sortable':true,
            },
            {field: 'product_status_info', title: '机构产品状态', width: 200, align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    var str ='';
                    if(value.length>10) str='...';
                    value = value.substring(0,10)+str;

                    var myClass = '';
                    if (row.product_status==20) myClass = 'icon-ok';
                    if (row.product_status==40) myClass = 'icon-cancel';
                    value = '<span class="'+myClass+'" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>' + value;
                    return value;
                },
                styler:function(a,b,c){
                    return 'cursor: pointer;';
                }
            },
            {field: 'cost_type_name', title: '收费类型', width: 150, align:'center', 'sortable':true},
            {field: 'cost_rate', title: '收费费率', width: 100,  align:'right',halign:'center', 'sortable':true,
                formatter: function(value) {
                    return value/10000+'%';
                }
            },
            {field: 'is_before', title: '是否前置', width: 100,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if (value==1) {
                        return '是';
                    }else{
                        return '否';
                    }
                }
            },
            {field: 'pay_type', title: '付息方式', width: 100,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if (value==1) {
                        return '先息后本';
                    }else{
                        return '后息后本';
                    }
                }
            },
            {field: 'operate', title: '操作', width: 200, halign:'center', align:'center',
                formatter: function(value, row, index) {
                    html = '';
                    //编辑
                    <?php if (checkRolePower('jigou','do_cost')): ?>
                        html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="editCost('+row.cost_id+','+row.jg_status+','+row.product_status+')" >编辑 </a> '+'&nbsp;&nbsp;';
                    <?php endif ?>
                    //html += ' <a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="Confirm(\'确认删除这个记录\',' + row.jigou_id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

    //添加机构产品
        function addCost() {           
            var jg_id = $('#jg').combobox('getValue');
            var jg_name = $('#jg').combobox('getText');
            var product_id = $('#product').combobox('getValue');
            var product_name = $('#product').combobox('getText');
      
            if (!jg_id || !product_id) {
                $.messager.show({
                    title: '提示',
                    msg: '请选定机构和机构产品！'
                });
                return false;
            }

            if(!checkStatus()) return false;

            $('#cost-dlg').dialog('open').dialog('setTitle', '新增机构产品收费标准');
            $('#costForm').form('clear');
            $('#costForm #classBtn .l-btn-text').text('新增');

            $('#costForm #jg_id').val(jg_id);
            $('#costForm #jg_name').textbox('setValue',jg_name);

            $('#costForm #product_id').val(product_id);
            $('#costForm #product_name').textbox('setValue',product_name);
        }

    //编辑机构产品
        function editCost(cost_id,jg_status,product_status) {

            if(!checkStatus(jg_status,product_status)) return false;

            $('#cost-dlg').dialog('open').dialog('setTitle', '编辑机构产品收费标准');
            $('#costForm').form('clear');
            $.getJSON('get_cost',{cost_id:cost_id},function(row){
                row.cost_rate = row.cost_rate/10000;
                $('#costForm').form('load',row);
            });
            $('#costForm #classBtn .l-btn-text').text('提交');
        }

    //检测机构、机构产品状态是否可新建、编辑
        function checkStatus(jg_status=jg_row.obj_status,product_status=product_row.obj_status){

            //验证选中机构有效状态
            if(!(jg_status>=20 && jg_status<30)){
                $.messager.show({
                    title: '提示',
                    msg: "只有当机构状态为“审核完成”，才能进行此操作！"
                });
                return false;
            }

            //验证选中机构产品可编辑状态
            if(!(product_status<10)){
                $.messager.show({
                    title: '提示',
                    msg: "只有当机构产品可编辑时，才能进行此操作！"
                });
                return false;
            }
            return true;
        }

    //执行添加、编辑
        function doCost() {
            $('#costForm').form('submit', {
                url: 'do_cost',
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
                        $('#cost-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#tt').datagrid('reload');
                    }
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


        // $('#likeBtn').on('click',function () {
        //     $('#tt').datagrid('load',{
        //         like:$('#like').val(),
        //         jg_id:$('#jg').combobox('getValue'),
        //         jg_status:$('#jg_status').val()
        //     });
        // });


   </script>

    <!-- 新增/编辑机构 -->
    <div id="cost-dlg" style="width:360px;height:320px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="cost-dlg-buttons" data-options="modal:true">

        <form id="costForm" method="post" novalidate>
            <div><input type="hidden" name="cost_id"></div>
            <!-- <div class="fitem">
                <label>选定机构:</label>
                <select id="jg_id" name="jg_id" class="easyui-combobox" style="width:158px;" required="true"
                    data-options="
                        url:'get_jg_name?status=3',
                        method:'get',
                        valueField:'jg_id',
                        textField:'jg_name',
                        panelHeight:'auto',
                    " 
                ></select>

            </div>
            <div class="fitem">
                <label>机构产品:</label>
                <select id="product_id" name="product_id" class="easyui-combobox" style="width:158px;" required="true"
                    data-options="
                        url:'get_product_name',
                        method:'get',
                        valueField:'product_id',
                        textField:'product_name',
                        panelHeight:'auto',
                    " 
                ></select>
            </div> -->
            <div class="fitem">
                <label>机构:</label>
                <input type="hidden" name="jg_id" id="jg_id">
                <input name="jg_name" id="jg_name" class="easyui-textbox" required="true" disabled="true">
            </div>
            <div class="fitem">
                <label>机构产品:</label>
                <input type="hidden" name="product_id" id="product_id">
                <input name="product_name" id="product_name" class="easyui-textbox" required="true" disabled="true">
            </div>
            <div class="fitem">
                <label>收费类型:</label>
                <select id="cost_type" name="cost_type" class="easyui-combobox" style="width:158px;" required="true" validType="integer"
                    data-options="
                        url:'get_cost_name',
                        method:'get',
                        valueField:'zname',
                        textField:'zval',
                        panelHeight:'auto',
                        editable:false
                    " 
                ></select>
            </div>
            <div class="fitem">
                <label>收费费率:</label>
                <input name="cost_rate" id="cost_rate" class="easyui-numberbox" required="true" validType="length[1,8]" data-options="precision:4,groupSeparator:',',decimalSeparator:'.',suffix:'%'">
            </div>
            <div class="fitem">
                <label>是否前置:</label>
                <select id="is_before" name="is_before" class="easyui-combobox" style="width:158px;" required="true" validType="integer"
                    data-options="panelHeight:'auto',editable:false">
                    <option value="1">是</option> 
                    <option value="2">否</option> 
                </select>
            </div>
            <div class="fitem">
                <label>付息方式:</label>
                <select id="pay_type" name="pay_type" class="easyui-combobox" style="width:158px;" required="true" validType="integer"
                    data-options="panelHeight:'auto',editable:false">
                    <option value="1">先息后本</option> 
                    <option value="2">后息后本</option> 
                </select>
            </div>
            <div id="cost-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doCost()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#cost-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
</body>
</html>