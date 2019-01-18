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
    <?php if (empty($product_id)): ?>
    
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td>
                机构状态：<select id="jg_status" class="easyui-combobox" style="width:100px;" 
                    data-options="
                        formatter: function(row){
                            return '<span style=background-color:'+statusColor[row.obj_id]+'>'+row.obj_status+'</span>';
                        },
                        onChange:function(row){
                            $('#jg').combobox(
                                'reload','get_jg_name?obj_status='+row
                            );
                            $('#jg').combobox('clear');
                        },
                        <?php if (isset($obj_status)): ?>
                            value:<?= $obj_status ?>,
                        <?php endif ?>
                        data:statusSelectData,
                        valueField: 'obj_id',
                        textField: 'obj_status',
                    " 
                >
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;
                选定机构：<select id="jg" class="easyui-combobox" style="width:200px;" 
                    data-options="
                        url:'get_jg_name?obj_status=<?= @$obj_status ?>',
                        method:'get',
                        valueField:'jg_id',
                        textField:'jg_name',
                        groupField:'obj_status',
                        groupFormatter:function(group){
                            return '<span style=background-color:'+statusColor[group]+'>'+statusArr[group]+'：</span>';
                        },
                        panelHeight:'300',
                        <?php if (isset($jg_id)): ?>
                            value:'<?= $jg_id ?>',
                        <?php endif ?>
                        onLoadSuccess:function(){
                            $('#tt').datagrid({
                                url: 'get_product',
                                queryParams:{
                                    like:$('#like').val(),
                                    jg_status:$('#jg_status').val(),
                                    jg_id:$('#jg').combobox('getValue')
                                }
                            });
                        },
                        onChange:function(row){
                            $('#tt').datagrid({
                                url: 'get_product',
                                queryParams:{
                                    like:$('#like').val(),
                                    jg_status:$('#jg_status').val(),
                                    jg_id:row,
                                }
                            });
                        },
                        onSelect:function(row){
                            jg_row = row;
                        },
                    " 
                ></select>
                &nbsp;&nbsp;&nbsp;&nbsp;
                机构产品名称：<input class="easyui-textbox" type="text" name="like" id="like" value="">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('jigou','do_product')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addProduct()" ><i class="fa fa-plus"></i>新增</a>
                <?php endif ?>
                <a id="tb-add" href="javascript:window.parent.history.back(-1)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>返回</a>
                <!-- <a id="tb-add" href="javascript:history.go(0)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>刷新</a> -->
            </td>
        </tr>
        
        </tbody>
    </table>
    </div>
    <?php endif ?>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table  id="tt" class="easyui-datagrid" style="width:100%;height:350px"
                    data-options="
                        <?php if(!empty($product_id)) echo "url: 'get_product?product={$product_id}',"?>
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
                            return 'background-color:'+statusColor[row.obj_status];
                        },
                        singleSelect:true,  
                        onClickCell:function(rowIndex, field, value){
                            if(field!='jg_status_info'&& field!='obj_status_info') return;
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
    //状态供select
    var statusSelectData=[];
    statusSelectData[0] = {obj_id:'',obj_status:'全部'};
    $.each(statusArr,function(k,v){
        statusSelectData.push({obj_id:k,obj_status:v});
    });

    //当前选中机构
    var jg_row;

    //获取产品列表
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
            {field: 'product_name', title: '机构产品名称', width: 100, align:'center', 'sortable':true},
            {field: 'product_code', title: '机构产品编号', width: 100,  align:'center', 'sortable':true},
            {field: 'product_type', title: '机构产品类型', width: 100,  align:'center', 'sortable':true},
            {field: 'product_date', title: '产品期限', width: 100,  align:'center', 'sortable':true,formatter: function(value) {
                    return value+'天';
                }
            },
            {field: 'obj_status_info', title: '机构产品状态', width: 200,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    var str ='';
                    if(value.length>10) str='...';
                    value = value.substring(0,10)+str;

                    var myClass = '';
                    if (row.obj_status==20) myClass = 'icon-ok';
                    if (row.obj_status==40) myClass = 'icon-cancel';
                    value = '<span class="'+myClass+'" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>' + value;
                    return value;
                },
                styler:function(a,b,c){
                    return 'cursor: pointer;';
                }
            },
            {field: 'operate', title: '操作', width: 400, halign:'center', align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    <?php 
                    //基础设置
                        //id字段 默认row.id
                        $this->id_field='row.product_id';
                        $this->sbtn_option->edit->method='do_product';
                    //各后端方法+Product
                        foreach ($this->sbtn_option as $key => $val) {
                            $this->sbtn_option->$key->method .= 'Product';
                        }
                        echo showStatusBtn();
                    ?>
             
                    //收费标准
                    <?php if(checkRolePower('jigou','list_jg_product_cost')): ?>
                        html += '<a class="btn btn-primary btn-xs p310" href="list_jg_product_cost?product_id='+row.product_id+'" >收费标准 </a> '+'&nbsp;&nbsp;';
                    <?php endif ?>

                    return html;
                }
            }
        ]];

    //添加机构产品
        function addProduct() {
            var jg_id = $('#jg').combobox('getValue');
            var jg_name = $('#jg').combobox('getText');
      
            if (!jg_id) {
                $.messager.show({
                    title: '提示',
                    msg: '请选定机构！'
                });
                return false;
            }
            
            if(!checkStatus()) return false;
            no_status();
            $('#product-dlg').dialog('open').dialog('setTitle', '新增机构产品');
            $('#productForm').form('clear');
            $('#productForm #classBtn .l-btn-text').text('新增');

            $('#productForm #jg_id').val(jg_id);
            $('#productForm #jg_name').textbox('setValue',jg_name);
            // $('#productForm #jg_name').textbox('readonly','true');
        }

    //编辑机构产品
        function edit(product_id,jg_status) {
            $.getJSON('get_product',{product_id:product_id},function(row){
                if(!checkStatus(row.jg_status)) return false;
                
                no_status();
                $('#product-dlg').dialog('open').dialog('setTitle', '编辑机构产品');
                $('#productForm').form('clear');
                $('#productForm').form('load',row);
                $('#productForm #classBtn .l-btn-text').text('提交');
            });
        }

    //执行添加、编辑
        function doProduct() {
            $('#productForm').form('submit', {
                url: 'do_product',
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
                        $('#product-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#tt').datagrid('reload');
                    }
                }
            });
        }


    //报审机构
        function baoShen(product_id){
            status(product_id,'报审','报审','baoShenProduct');
        }

    //初审通过
        function guoChuShen(product_id){
            status(product_id,'初审通过','通过','guoChuShenProduct');
        }

    //初审退回
        function backChuShen(product_id){
            status(product_id,'初审驳回','驳回','backChuShenProduct');
        }

    //复审通过
        function guoFuShen(product_id){
            status(product_id,'复审通过','通过','guoFuShenProduct');
        }

    //复审退回
        function backFuShen(product_id){
            status(product_id,'复审驳回','驳回','backFuShenProduct');
        }

    //审核通过
        function guoShen(product_id){
            status(product_id,'审核通过','通过','guoShenProduct');
        }

    //审核退回
        function backShen(product_id){
            status(product_id,'审核驳回','驳回','backShenProduct');
        }

    //停用机构
        function stop(product_id){
            status(product_id,'停用机构','停用','stopProduct');
        }

    //启用机构
        function start(product_id){
            status(product_id,'启用机构','启用','startProduct');
        }

    //申请修改
        function pleaseEdit(product_id){
            status(product_id,'申请修改','申请','pleaseEditProduct');
        }

    //批准修改
        function yesEdit(product_id){
            status(product_id,'批准修改','批准','yesEditProduct');
        }

    //拒绝修改
        function noEdit(product_id){
            status(product_id,'驳回修改','驳回','noEditProduct');
        }

    //执行改状态
        function doStatus($url,title){
            $('#productForm').form('submit', {
                url: $url,
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    // var result = eval("(" + result + ")");
                    // console.log(result);
                    if (result==1) {
                        $('#product-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: title+'操作成功！'
                        });
                        $('#tt').datagrid('reload');
                    } else {
                        $.messager.show({
                            title: '提示',
                            msg: title+'操作失败！'
                        });
                    }
                }
            });
        }

    //改状态
        function status(product_id,title,button,url){

            $.getJSON('get_product',{product_id:product_id},function(row){
                if(!checkStatus(row.jg_status)) return false;

                $('#product-dlg').dialog('open').dialog('setTitle',title);
                $('#productForm').form('clear');
                $('#productForm').form('load',row);
                $('#productForm #classBtn .l-btn-text').text(button);
                $('#productForm #classBtn').attr('onclick','doStatus("'+url+'","'+title+'")');

                $('#status_info').parent('div').removeAttr('hidden');
                $('#for_admins').parent('div').removeAttr('hidden');

                $('#productForm .easyui-textbox').textbox('disable');
                $('#productForm .easyui-numberbox').numberbox({
                    disabled:true
                });
                // $('#productForm .easyui-datebox').datebox({
                //     disabled:true
                // });
                $('#status_info').textbox('enable');

                
            });
            
        }

    //改内容
        function no_status(){
            $('#status_info').parent('div').attr('hidden',true);
            $('#for_admins').parent('div').attr('hidden',true);
            $('#productForm .easyui-textbox').textbox('enable');
            $('#productForm .easyui-numberbox').numberbox({
                disabled:false
            });
            // $('#productForm .easyui-datebox').datebox({
            //     disabled:false
            // });
            $('#status_info').textbox('disable');
            $('#productForm #classBtn').attr('onclick','doProduct()');
        }

    //检测机构状态是否有效
        function checkStatus(jg_status=jg_row.obj_status){
            //验证选中机构有效状态
            if(!(jg_status>=20 && jg_status<30)){
                $.messager.show({
                    title: '提示',
                    msg: "只有当机构状态为“审核完成”，才能进行此操作！"
                });
                return false;
            }

            return true;
        }

    //确认框
        function myConfirm(msg,fun,id){
            $.messager.confirm("确认", msg, function(r) {
                if (r) window[fun](id,status); 
            });
            return false;
        }


    $('#likeBtn').on('click',function () {
        $('#tt').datagrid('load',{
            like:$('#like').val(),
            jg_id:$('#jg').combobox('getValue'),
            jg_status:$('#jg_status').val()
        });
    });


   </script>

    <!-- 新增/编辑机构 -->
    <div id="product-dlg" style="width:400px;max-height:450px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="product-dlg-buttons" data-options="modal:true">

        <form id="productForm" method="post" novalidate>
            <div><input type="hidden" name="obj_id"></div>
            <!-- <div class="fitem">
                <label>选择机构:</label>
                <select id="jg2" name="jg_id" class="easyui-combobox" style="width:158px;" required="true"
                    data-options="
                        url:'get_jg_name?status=3',
                        method:'get',
                        valueField:'jg_id',
                        textField:'jg_name',
                        panelHeight:'auto',
                    " 
                ></select>

            </div> -->
            <div class="fitem">
                <label>机构:</label>
                <input type="hidden" name="jg_id" id="jg_id">
                <input name="jg_name" id="jg_name" class="easyui-textbox" required="true" readonly="true">
            </div>
            <div class="fitem">
                <label>机构产品名称:</label>
                <input name="product_name" id="product_name" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>机构产品编号:</label>
                <input name="product_code" id="product_code" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>机构产品类型:</label>
                <input name="product_type" id="product_type" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>机构产品期限:</label>
                <input name="product_date" id="product_date" class="easyui-numberbox" required="true" validType="length[1,6]" data-options="precision:0,suffix:'天'">
            </div>
            <div class="fitem" hidden>
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
            </div>
            <div class="fitem" hidden>
                <label id="info_label">流程附加信息:</label>
                <input name="status_info" id="status_info" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,240]" data-options="multiline:true" novalidate="true">
                 <!-- required="true" validType="length[1,255]" novalidate="true" -->
            </div>
            <div id="product-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doProduct()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#product-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
    <? showHistoryHtml() ?>
    <? showRemindHtml(); ?>

</body>
</html>