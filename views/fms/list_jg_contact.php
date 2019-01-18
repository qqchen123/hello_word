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

        #contactForm label{
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
                机构状态：<select id="jg_status" class="easyui-combobox" style="width:100px;" 
                    data-options="
                        panelHeight:'auto',
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
                        url:'get_jg_name?status=<?= @$obj_status ?>',
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
                                url: 'get_contact',
                                queryParams:{
                                    like:$('#like').val(),
                                    jg_status:$('#jg_status').val(),
                                    jg_id:$('#jg').combobox('getValue')
                                }
                            });
                        },
                        onChange:function(row){
                            $('#tt').datagrid({
                                url: 'get_contact',
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
                机构联系人名称：<input class="easyui-textbox" type="text" name="like" id="like" value="">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
                </td>
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('jigou','do_contact')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addContact()" ><i class="fa fa-plus"></i>新增</a>
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
                            return 'background-color:'+statusColor[row.jg_status];
                        },
                        singleSelect:true,
                        onClickCell:function(rowIndex, field, value){
                            if(field!='jg_status_info') return;
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

    //获取联系人列表
        var col_data = [[
            {field: 'jg_name', title: '机构', width: 100, align:'center', 'sortable':true,
            },
            {field: 'jg_status_info', title: '机构状态', width: 200,  align:'center', 'sortable':true,
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
            {field: 'ct_name', title: '姓名', width: 100, align:'center', 'sortable':true},
            {field: 'ct_define', title: '对接人<br>定义', width: 100,  align:'center', 'sortable':true},
            {field: 'ct_department', title: '所属部门', width: 100,  align:'center', 'sortable':true},
            {field: 'ct_class', title: '组别', width: 100,  align:'center', 'sortable':true},
            {field: 'ct_role', title: '角色', width: 100,  align:'center', 'sortable':true},
            {field: 'ct_call', title: '手机', width: 100,  align:'center', 'sortable':true},
            {field: 'note1_val', title: '评估邮件组', width: 100,  align:'center', 'sortable':true},
            {field: 'note2_val', title: '订单邮件组', width: 100,  align:'center', 'sortable':true},


            {field: 'operate', title: '操作', width: 100, halign:'center', align:'center',
                formatter: function(value, row, index) {
                    var html = '';
                    //机构状态正常才可编辑
                    <?php if (checkRolePower('jigou','do_contact')): ?>
                        html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="editContact('+row.ct_id+','+row.jg_status+')" >编辑 </a> '+'&nbsp;&nbsp;';
                    <?php endif ?>
                    //机构状态正常才可收费标准
                    // html += '<a class="btn btn-primary btn-xs p310" href="list_jg_product_cost?product_id='+row.product_id+'" >收费标准 </a> '+'&nbsp;&nbsp;';
                    //html += ' <a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="Confirm(\'确认删除这个记录\',' + row.jigou_id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

    // //千分位
    //     function toThousands(num) {
    //         var num = (num || 0).toString(), result = '';
    //         while (num.length > 3) {
    //             result = ',' + num.slice(-3) + result;
    //             num = num.slice(0, num.length - 3);
    //         }
    //         if (num) { result = num + result; }
    //         return result;
    //     }

    //添加机构联系人
        function addContact() {
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
            $('#contact-dlg').dialog('open').dialog('setTitle', '新增机构联系人');
            $('#contactForm').form('clear');
            $('#contactForm #classBtn .l-btn-text').text('新增');

            $('#contactForm #jg_id').val(jg_id);
            $('#contactForm #jg_name').textbox('setValue',jg_name);
            // $('#contactForm #jg_name').textbox('readonly','true');
        }

    //编辑机构联系人
        function editContact(ct_id,jg_status) {
  
            if(!checkStatus(jg_status)) return false;

            $('#contact-dlg').dialog('open').dialog('setTitle', '编辑机构联系人');
            $('#contactForm').form('clear');
            $.getJSON('get_contact',{ct_id:ct_id},function(row){
                // row.margin_rate = row.margin_rate/10000;
                $('#contactForm').form('load',row);
            });
            $('#contactForm #classBtn .l-btn-text').text('提交');
        }

    //执行添加、编辑
        function doContact() {
            $('#contactForm').form('submit', {
                url: 'do_contact',
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
                        $('#contact-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#tt').datagrid('reload');
                    }
                }
            });
        }
    //检测机构状态是否有效
        function checkStatus(jg_status=jg_row.obj_status){
            // console.log(jg_status);
            // console.log(jg_row);
            //验证选中机构有效状态
            if(!(jg_status<10)){
                $.messager.show({
                    title: '提示',
                    msg: "只有当机构可编辑，才能进行此操作！"
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
    <div id="contact-dlg" style="width:360px;height:400px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="product-dlg-buttons" data-options="modal:true">

        <form id="contactForm" method="post" novalidate>
            <div><input type="hidden" name="ct_id"></div>
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
                <label>姓名:</label>
                <input name="ct_name" id="ct_name" class="easyui-textbox" required="true" validType="length[1,10]">
            </div>
            <div class="fitem">
                <label>对接人定义:</label>
                <input name="ct_define" id="ct_define" class="easyui-textbox"  validType="length[1,10]">
            </div>
            <div class="fitem">
                <label>所属部门:</label>
                <input name="ct_department" id="ct_department" class="easyui-textbox"  validType="length[1,10]">
            </div>
            <div class="fitem">
                <label>组别:</label>
                <input name="ct_class" id="ct_class" class="easyui-textbox"  validType="length[1,10]">
            </div>
            <div class="fitem">
                <label>角色:</label>
                <input name="ct_role" id="ct_role" class="easyui-textbox"  validType="length[1,10]">
            </div>
            <div class="fitem">
                <label>手机:</label>
                <input name="ct_call" id="ct_call" class="easyui-textbox"  validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>评估邮件组:</label>
                <input name="note1_val" id="note1_val" class="easyui-textbox"  validType="length[1,10]">
            </div>
            <div class="fitem">
                <label>订单邮件组:</label>
                <input name="note2_val" id="note1_val" class="easyui-textbox"  validType="length[1,10]">
            </div>
            <div id="product-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doContact()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#contact-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
</body>
</html>