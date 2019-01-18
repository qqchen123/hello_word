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

        #jiGouForm label{
            font-size: 12px;
            margin-top: 5px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <?php if (empty($jg_id)): ?>
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td class="tlabel">机构名称</td>
            <td>
                <input class="col-sm-3" type="text" name="like" id="like" value="">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
                </td>
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('jigou','do_jigou')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addJiGou()" ><i class="fa fa-plus"></i>新增机构</a>
                <?php endif ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <!-- <a id="tb-add" href="javascript:window.parent.history.back(-1)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>返回</a>
                <a id="tb-add" href="javascript:history.go(0)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>刷新</a> -->
            </td>
        </tr>
        
        </tbody>
    </table>
    </div>
    <?php endif ?>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table  id="tt" class="easyui-datagrid" style="width:100%;height:350px"
                    data-options="
                        url: 'get_jigou?jg=<?=$jg_id?>',
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
                        onClickCell:function(rowIndex, field, value){
                            if(field!='obj_status_info') return;
                            $.messager.alert('状态详情', value);
                        }
                        ">
        </table>
    </div>
</div>
<script>
    var statusColor = JSON.parse('<?= $statusColor ?>');

    //获取列表
        var col_data = [[
            {field: 'jg_name', title: '机构名称', width: 100, align:'center', 'sortable':true},
            {field: 'jg_code', title: '机构代码', width: 100,  align:'center', 'sortable':true},
            {field: 'proxy_name', title: '机构代理名称', width: 100,  align:'center', 'sortable':true},
            {field: 'proxy_area', title: '代理区域', width: 100,  align:'center', 'sortable':true},
            {field: 'proxy_level', title: '代理级别', width: 100,  align:'center', 'sortable':true},
            {field: 'jg_company', title: '机构公司<br>名称', width: 100,  align:'center', 'sortable':true},
            {field: 'jg_signing_begin', title: '签约<br>起始日期', width: 100,  align:'center', 'sortable':true},
            {field: 'jg_signing_end', title: '签约<br>结束日期', width: 100,  align:'center', 'sortable':true},
            {field: 'jg_signing_years', title: '签约年限', width: 100,  align:'center', 'sortable':true},
            {field: 'jg_credit_money', title: '授信额度', width: 100, align:'right', halign:'center', 'sortable':true,
                formatter: function(value) {
                    if(value!==null)
                        return '¥'+toThousands(value);
                }
            },
            // {field: 'add_credit_money', title: '增加<br>授信额度', width: 100, align:'right', halign:'center', 'sortable':true,
            //     formatter: function(value) {
            //         return '¥'+toThousands(value);
            //     }
            // },
            // {field: 'margin_rate', title: '机构保证金<br>费率', width: 100,  align:'right',halign:'center', 'sortable':true,
            //     formatter: function(value) {
            //         return value/10000+'%';
            //     }
            // },
            // {field: 'payable_margin_money', title: '机构应付<br>保证金金额', width: 100, align:'right', halign:'center', 'sortable':true,
            //     formatter: function(value) {
            //         return '¥'+toThousands(value);
            //     }
            // },
            // {field: 'paid_margin_money', title: '机构已付<br>保证金金额', width: 100, align:'right', halign:'center', 'sortable':true,
            //     formatter: function(value) {
            //         return '¥'+toThousands(value);
            //     }
            // },
            {field: 'obj_status_info', title: '状态信息', width: 150,  align:'center', 'sortable':true,
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
            {field: 'operate', title: '操作', width: 400,  align:'center',
                formatter: function(value, row, index) {

                    var html = '';
                    <?php 
                    //基础设置
                        //id字段 默认row.id
                        $this->id_field='row.jg_id';
                        //状态字段 默认row.obj_status
                        // $this->status_field = 'row.obj_status';
                        //默认访问控制器 设置后不用每个按钮设置
                        // $this->default_controller = 'jigou';
                    //编辑
                        //后端访问控制器 此处已经设置默认可不设置
                        // $this->sbtn_option->edit->class='jigou';
                        //后端访问方法
                        // $this->sbtn_option->edit->method='do_jigou';
                    //报审
                        // $this->sbtn_option->baoShen->method='bao_shen';
                    //初审通过
                        // $this->sbtn_option->guoChuShen->method='guo_shu_shen';
                    //初审驳回
                        // $this->sbtn_option->backChuShen->method='back_chu_shen';
                    //复审通过
                        // $this->sbtn_option->guoFuShen->method='guo_fu_shen';
                    //复审驳回
                        // $this->sbtn_option->backFuShen->method='back_fu_shen';
                    //停用
                        // $this->sbtn_option->stop->method='stop';
                    //申请修改
                        // $this->sbtn_option->pleaseEdit->method='please_edit';
                    //批准修改申请
                        // $this->sbtn_option->yesEdit->method='yes_edit';
                    //驳回修改申请
                        // $this->sbtn_option->noEdit->method='no_edit';
                    //启用
                        // $this->sbtn_option->start->method='start';
                    //输出状态按钮
                        echo showStatusBtn();
                    ?>
                    // console.log('$this->id_field:'+<?//= $this->id_field ?>);
                    // console.log('$this->status_field:'+<?//= $this->status_field ?>);
                    // console.log('$this->default_controller:'+'<?//= $this->default_controller ?>');
                    // console.log('$this->id_field:'+'<?//= $this->id_field ?>');
                    //编辑、报审
                        // if (row.obj_status<10){
                        //     //编辑
                        //     if(<?//= checkRolePower('jigou','do_jigou') ?>) html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="editJiGou('+row.jg_id+
                        //         ')" >编辑 </a> '+'&nbsp;&nbsp;';
                        //     //报审
                        //     if (<?//= checkRolePower('jigou','bao_shen') ?>) html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="baoShen('+row.jg_id+')" >报审 </a> '+'&nbsp;&nbsp;';
                        // }

                    //初审
                        // if (row.obj_status==15) {
                        //     //通过
                        //     if(<?//= checkRolePower('jigou','guo_chu_shen') ?>) html += '<a class="btn btn-success btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定初审通过该机构！\',\'guoChuShen\','+row.jg_id+
                        //     ')" >初审通过 </a> '+'&nbsp;&nbsp;';
                        //     //驳回
                        //     if(<?//= checkRolePower('jigou','back_chu_shen') ?>) html += '<a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定初审驳回该机构！\',\'backChuShen\','+row.jg_id+
                        //     ')" >初审驳回 </a> '+'&nbsp;&nbsp;';
                        // }

                    //复审
                        // if (row.obj_status==17) {
                        //     //通过
                        //     if(<?//= checkRolePower('jigou','guo_fu_shen') ?>) html += '<a class="btn btn-success btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定复审通过该机构！\',\'guoFuShen\','+row.jg_id+
                        //     ')" >复审通过 </a> '+'&nbsp;&nbsp;';
                        //     //驳回
                        //     if(<?//= checkRolePower('jigou','back_fu_shen') ?>) html += '<a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定复审驳回该机构！\',\'backFuShen\','+row.jg_id+
                        //     ')" >复审驳回 </a> '+'&nbsp;&nbsp;';
                        // }
                    //停用、申请修改
                        // if (row.obj_status==20){
                        //     //停用
                        //     if(<?//= checkRolePower('jigou','stop') ?>) html += '<a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定停用该机构！\',\'stop\','+row.jg_id+')" >停用 </a> '+'&nbsp;&nbsp;';
                        //     //申请修改
                        //     if(<?//= checkRolePower('jigou','please_edit') ?>) html += '<a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定申请修改该机构！\',\'pleaseEdit\','+row.jg_id+')" >申请修改 </a> '+'&nbsp;&nbsp;';
                        // }
                    //批准修改、驳回修改
                        // if (row.obj_status==23){
                        //     //批准
                        //     if(<?//= checkRolePower('jigou','yes_edit') ?>) html += '<a class="btn btn-success btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定批准修改该机构！\',\'yesEdit\','+row.jg_id+')" >批准修改申请 </a> '+'&nbsp;&nbsp;';
                        //     //驳回
                        //     if(<?//= checkRolePower('jigou','no_edit') ?>) html += '<a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定驳回修改该机构！\',\'noEdit\','+row.jg_id+')" >驳回修改申请 </a> '+'&nbsp;&nbsp;';
                        // }
                    //启用
                        // if (row.obj_status==40 && <?//= checkRolePower('jigou','start') ?>) html += '<a class="btn btn-success btn-xs p310" href="javascript:void(0)" onclick="myConfirm(\'确定重新启用该机构，启用后需要重新审核！\',\'start\','+row.jg_id+
                        //     ')" >启用 </a> '+'&nbsp;&nbsp;';

                    //产品
                    if(<?= checkRolePower('jigou','list_jg_product') ?>) html += '<a class="btn btn-primary btn-xs p310" href="list_jg_product?jg_id='+row.jg_id+'">产品 </a> '+'&nbsp;&nbsp;';

                    //对接人
                    if(<?= checkRolePower('jigou','list_jg_contact') ?>) html += '<a class="btn btn-primary btn-xs p310" href="list_jg_contact?jg_id='+row.jg_id+'">对接人 </a> '+'&nbsp;&nbsp;';

                    //html += ' <a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="Confirm(\'确认删除这个记录\',' + row.jigou_id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

    //千分位
        function toThousands(num) {
            var num = (num || 0).toString(), result = '';
            while (num.length > 3) {
                result = ',' + num.slice(-3) + result;
                num = num.slice(0, num.length - 3);
            }
            if (num) { result = num + result; }
            return result;
        }

    //添加机构
        function addJiGou() {
            no_status();
            $('#jigou-dlg').dialog('open').dialog('setTitle', '新增机构');
            $('#jiGouForm').form('clear');
            $('#jiGouForm #classBtn .l-btn-text').text('新增');
        }

    //编辑机构
        function edit(jg_id) {
            no_status();
            $('#jigou-dlg').dialog('open').dialog('setTitle', '编辑机构');
            $('#jiGouForm').form('clear');
            $.getJSON('get_jigou',{jg_id:jg_id},function(row){
                row.margin_rate = row.margin_rate/10000;
                $('#jiGouForm').form('load',row);
            });
            $('#jiGouForm #classBtn .l-btn-text').text('提交');
        }

    //执行添加、编辑
        function doJiGou() {
            $('#jiGouForm').form('submit', {
                url: 'do_jigou',
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
                        $('#jigou-dlg').dialog('close');
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
        function baoShen(jg_id){
            status(jg_id,'报审','报审','baoShen');
        }

    //初审通过
        function guoChuShen(jg_id){
            status(jg_id,'初审通过','通过','guoChuShen');
        }

    //初审退回
        function backChuShen(jg_id){
            status(jg_id,'初审驳回','驳回','backChuShen');
        }

    //复审通过
        function guoFuShen(jg_id){
            status(jg_id,'复审通过','通过','guoFuShen');
        }

    //复审退回
        function backFuShen(jg_id){
            status(jg_id,'复审驳回','驳回','backFuShen');
        }

    //审核通过
        function guoShen(jg_id){
            status(jg_id,'审核通过','通过','guoShen');
        }

    //审核退回
        function backShen(jg_id){
            status(jg_id,'审核驳回','驳回','backShen');
        }

    //停用机构
        function stop(jg_id){
            status(jg_id,'停用机构','停用','stop');
        }

    //启用机构
        function start(jg_id){
            status(jg_id,'启用机构','启用','start');
        }

    //申请修改
        function pleaseEdit(jg_id){
            status(jg_id,'申请修改','申请','pleaseEdit');
        }

    //批准修改
        function yesEdit(jg_id){
            status(jg_id,'批准修改','批准','yesEdit');
        }

    //拒绝修改
        function noEdit(jg_id){
            status(jg_id,'驳回修改','驳回','noEdit');
        }

    //执行改状态
        function doStatus($url,title){
            $('#jiGouForm').form('submit', {
                url: $url,
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    // var result = eval("(" + result + ")");
                    // console.log(result);
                    if (result==1) {
                        $('#jigou-dlg').dialog('close');
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
        function status(jg_id,title,button,url){
            $('#jigou-dlg').dialog('open').dialog('setTitle',title);
            $('#jiGouForm').form('clear');
            $.getJSON('get_jigou',{jg_id:jg_id},function(row){
                row.margin_rate = row.margin_rate/10000;
                $('#jiGouForm').form('load',row);
            });
            $('#jiGouForm #classBtn .l-btn-text').text(button);
            $('#jiGouForm #classBtn').attr('onclick','doStatus("'+url+'","'+title+'")');

            $('#status_info').parent('div').removeAttr('hidden');
            $('#for_admins').parent('div').removeAttr('hidden');

            $('#jg_name,#jg_code,#jg_company').textbox('disable');
            $('#jiGouForm .easyui-numberbox').numberbox({
                disabled:true
            });
            $('#jiGouForm .easyui-datebox').datebox({
                disabled:true
            });
            $('#status_info').textbox('enable');
        }

    //改内容
        function no_status(){
            $('#status_info').parent('div').attr('hidden',true);
            $('#for_admins').parent('div').attr('hidden',true);
            $('#jg_name,#jg_code,#jg_company').textbox('enable');
            $('#jiGouForm .easyui-numberbox').numberbox({
                disabled:false
            });
            $('#jiGouForm .easyui-datebox').datebox({
                disabled:false
            });
            $('#status_info').textbox('disable');
            $('#jiGouForm #classBtn').attr('onclick','doJiGou()');
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

    <!-- 新增/编辑机构 -->
    <div id="jigou-dlg" style="width:400px;max-height:450px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true">

        <form id="jiGouForm" method="post" novalidate>
            <div><input type="hidden" name="obj_id"></div>
            <div class="fitem">
                <label>机构名称:</label>
                <input name="jg_name" id="jg_name" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>机构代码:</label>
                <input name="jg_code" id="jg_code" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>机构代理名称:</label>
                <input name="proxy_name" id="proxy_name" class="easyui-textbox" required="true" validType="length[1,20]">
            </div>
            <div class="fitem">
                <label>机构代理区域:</label>
                <input name="proxy_area" id="proxy_area" class="easyui-textbox" required="true" validType="length[1,20]">
            </div>
            <div class="fitem">
                <label>机构代理级别:</label>
                <input name="proxy_level" id="proxy_level" class="easyui-combobox" required="true" data-options="
                    valueField: 'value',
                    textField: 'value',
                    data:[
                        {value:'一级代理'},
                        {value:'二级代理'},
                        {value:'三级代理'},
                        {value:'四级代理'},
                        {value:'五级代理'},
                    ],
                ">
            </div>

            <div class="fitem">
                <label>机构公司名称:</label>
                <input name="jg_company" id="jg_company" class="easyui-textbox" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>签约日期:</label>
                <div>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开始日期:</label>
                    <input name="jg_signing_begin" id="jg_signing_begin" class="easyui-datebox" validType="">
                </div>
                <div>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;结束日期:</label>
                <input name="jg_signing_end" id="jg_signing_end" class="easyui-datebox" validType="">
                </div>
            </div>
            <div class="fitem">
                <label>签约年限:</label>
                <input name="jg_signing_years" id="jg_signing_years" class="easyui-textbox" validType="length[1,20]">
            </div>
            <div class="fitem">
                <label>授信额度:</label>
                <input name="jg_credit_money" id="jg_credit_money" class="easyui-numberbox" validType="length[1,13]" data-options="precision:0,groupSeparator:',',decimalSeparator:'.',prefix:'¥'">
            </div>
            <!-- <div class="fitem">
                <label>增加授信额度:</label>
                <input name="add_credit_money" id="add_credit_money" class="easyui-numberbox" validType="length[1,13]" data-options="precision:0,groupSeparator:',',decimalSeparator:'.',prefix:'¥'">
            </div>
            <div class="fitem">
                <label>机构保证金费率:</label>
                <input name="margin_rate" id="margin_rate" class="easyui-numberbox" validType="length[1,8]" data-options="precision:4,groupSeparator:',',decimalSeparator:'.',suffix:'%'">
            </div>
            <div class="fitem">
                <label>机构应付保证金金额:</label>
                <input name="payable_margin_money" id="payable_margin_money" class="easyui-numberbox"  validType="length[1,13]" data-options="precision:0,groupSeparator:',',decimalSeparator:'.',prefix:'¥'">
            </div>
            <div class="fitem">
                <label>机构已付保证金金额:</label>
                <input name="paid_margin_money" id="paid_margin_money" class="easyui-numberbox" validType="length[1,13]" data-options="precision:0,groupSeparator:',',decimalSeparator:'.',prefix:'¥'">
            </div> -->
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
            </div>
            <div id="jigou-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doJiGou()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#jigou-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

    <!-- 流程信息 -->
    <? showHistoryHtml(); ?>
    <!-- 提醒 -->
    <? showRemindHtml(); ?>
    

</body>
</html>