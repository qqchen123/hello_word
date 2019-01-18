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

        #QudaoForm label{
            font-size: 12px;
            margin-top: 5px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <?php if (empty($q_id)): ?>
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td class="tlabel">渠道名称</td>
            <td>
                <input class="col-sm-3" type="text" name="like" id="like" value="">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
                </td>
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('qudao','do_qudao')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addQudao()" ><i class="fa fa-plus"></i>新增渠道</a>
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
                        url: 'get_qudao?q=<?=$q_id?>',
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
            {field: 'q_name', title: '渠道名称', width: 100, align:'center', 'sortable':true},
            {field: 'q_code', title: '渠道编号', width: 100,  align:'center', 'sortable':true},
            {field: 'q_company', title: '渠道公司名称', width: 100,  align:'center', 'sortable':true},
            {field: 'q_level', title: '渠道等级', width: 100,  align:'center', 'sortable':true},
            // {field: 'q_picker', title: '对接人姓名', width: 100,  align:'center', 'sortable':true},
            // {field: 'q_picker_phone', title: '电话号码', width: 100,  align:'center', 'sortable':true},
            {field: 'q_picker_company', title: '公司名称', width: 100,  align:'center', 'sortable':true},
            {field: 'q_picker_company_addr', title: '公司地址', width: 100,  align:'center', 'sortable':true},
            {field: 'q_picker_company_mail', title: '唯一指定邮箱', width: 100,  align:'center', 'sortable':true},
            {field: 'q_picker_business_time', title: '经营时间', width: 100,  align:'center', 'sortable':true},
            {field: 'q_picker_business', title: '主营业务', width: 100,  align:'center', 'sortable':true},
            {field: 'q_team_numbers', title: '现有团队人数', width: 100,  align:'center', 'sortable':true},
            {field: 'q_if_has_risk_team', title: '是否有风控团队', width: 100,  align:'center', 'sortable':true},
            {field: 'q_if_has_stock', title: '是否有自有存量', width: 100,  align:'center', 'sortable':true},

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
            {field: 'operate', title: '操作', width: 300,  align:'center',
                formatter: function(value, row, index) {

                    var html = '';
                    <?php 
                    //基础设置
                        //id字段 默认row.id
                        $this->id_field='row.q_id';
                        //状态字段 默认row.obj_status
                        // $this->status_field = 'row.obj_status';
                        //默认访问控制器 设置后不用每个按钮设置
                        // $this->default_controller = 'qudao';
                    //编辑
                        //后端访问控制器 此处已经设置默认可不设置
                        // $this->sbtn_option->edit->class='qudao';
                        //后端访问方法
                        $this->sbtn_option->edit->method='do_qudao';
                    //输出状态按钮
                        echo showStatusBtn();
                    ?>
                    //对接人
                    if(<?= checkRolePower('Qudao','list_jg_contact') ?>) html += '<a class="btn btn-primary btn-xs p310" href="list_qudao_contact?q_id='+row.q_id+'">对接人 </a> '+'&nbsp;&nbsp;';

                    //html += ' <a class="btn btn-danger btn-xs p310" href="javascript:void(0)" onclick="Confirm(\'确认删除这个记录\',' + row.qudao_id + ')">删除</a> ';
                    return html;
                }
            }
        ]];

    //添加渠道
        function addQudao() {
            no_status();
            $('#qudao-dlg').dialog('open').dialog('setTitle', '新增渠道');
            $('#QudaoForm').form('clear');
            $('#QudaoForm #classBtn .l-btn-text').text('新增');
        }

    //编辑渠道
        function edit(q_id) {
            no_status();
            $('#qudao-dlg').dialog('open').dialog('setTitle', '编辑');
            $('#QudaoForm').form('clear');
            $.getJSON('get_qudao',{q_id:q_id},function(row){
                $('#QudaoForm').form('load',row);
            });
            $('#QudaoForm #classBtn .l-btn-text').text('提交');
        }

    //执行添加、编辑
        function doQudao() {
            $('#QudaoForm').form('submit', {
                url: 'do_qudao',
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
                        $('#qudao-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#tt').datagrid('reload');
                    }
                }
            });
        }

    //报审渠道
        function baoShen(q_id){
            status(q_id,'报审','报审','baoShen');
        }

    //初审通过
        function guoChuShen(q_id){
            status(q_id,'初审通过','通过','guoChuShen');
        }

    //初审退回
        function backChuShen(q_id){
            status(q_id,'初审驳回','驳回','backChuShen');
        }

    //复审通过
        function guoFuShen(q_id){
            status(q_id,'复审通过','通过','guoFuShen');
        }

    //复审退回
        function backFuShen(q_id){
            status(q_id,'复审驳回','驳回','backFuShen');
        }

    //审核通过
        function guoShen(q_id){
            status(q_id,'审核通过','通过','guoShen');
        }

    //审核退回
        function backShen(q_id){
            status(q_id,'审核驳回','驳回','backShen');
        }

    //停用渠道
        function stop(q_id){
            status(q_id,'停用渠道','停用','stop');
        }

    //启用渠道
        function start(q_id){
            status(q_id,'启用渠道','启用','start');
        }

    //申请修改
        function pleaseEdit(q_id){
            status(q_id,'申请修改','申请','pleaseEdit');
        }

    //批准修改
        function yesEdit(q_id){
            status(q_id,'批准修改','批准','yesEdit');
        }

    //拒绝修改
        function noEdit(q_id){
            status(q_id,'驳回修改','驳回','noEdit');
        }

    //执行改状态
        function doStatus($url,title){
            $('#QudaoForm').form('submit', {
                url: $url,
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    // var result = eval("(" + result + ")");
                    // console.log(result);
                    if (result==1) {
                        $('#qudao-dlg').dialog('close');
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
        function status(q_id,title,button,url){
            $('#qudao-dlg').dialog('open').dialog('setTitle',title);
            $('#QudaoForm').form('clear');
            $.getJSON('get_qudao',{q_id:q_id},function(row){
                $('#QudaoForm').form('load',row);
            });
            $('#QudaoForm #classBtn .l-btn-text').text(button);
            $('#QudaoForm #classBtn').attr('onclick','doStatus("'+url+'","'+title+'")');

            $('#status_info').parent('div').removeAttr('hidden');
            $('#for_admins').parent('div').removeAttr('hidden');

            $('#q_name,#q_code,#q_company,#q_level,#q_picker,#q_picker_phone,#q_picker_company,#q_picker_company_addr,#q_picker_company_mail,#q_picker_business_time,#q_picker_business,#q_team_numbers,#q_if_has_risk_team,#q_if_has_stock').textbox('disable');
            // $('#QudaoForm .easyui-numberbox').numberbox({
            //     disabled:true
            // });
            // $('#QudaoForm .easyui-datebox').datebox({
            //     disabled:true
            // });
            $('#status_info').textbox('enable');
        }

    //改内容
        function no_status(){
            $('#status_info').parent('div').attr('hidden',true);
            $('#for_admins').parent('div').attr('hidden',true);
            $('#jg_name,#jg_code,#jg_company').textbox('enable');
            $('#q_name,#q_code,#q_company,#q_level,#q_picker,#q_picker_phone,#q_picker_company,#q_picker_company_addr,#q_picker_company_mail,#q_picker_business_time,#q_picker_business,#q_team_numbers,#q_if_has_risk_team,#q_if_has_stock').textbox('enable');
            // $('#QudaoForm .easyui-numberbox').numberbox({
            //     disabled:false
            // });
            // $('#QudaoForm .easyui-datebox').datebox({
            //     disabled:false
            // });
            $('#status_info').textbox('disable');
            $('#QudaoForm #classBtn').attr('onclick','doQudao()');
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
    })

   </script>

    <!-- 新增/编辑渠道 -->
    <div id="qudao-dlg" style="width:400px;max-height:550px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="qudao-dlg-buttons" data-options="modal:true">

        <form id="QudaoForm" method="post" novalidate>
            <div><input type="hidden" name="obj_id"></div>
            <div class="fitem">
                <label>渠道名称:</label>
                <input name="q_name" id="q_name" class="easyui-textbox" required="true" validType="length[1,100]">
            </div>
            <div class="fitem">
                <label>渠道编号:</label>
                <input name="q_code" id="q_code" class="easyui-textbox" required="true" validType="length[1,25]">
            </div>
            <div class="fitem">
                <label>渠道公司名称:</label>
                <input name="q_company" id="q_company" class="easyui-textbox" required="true" validType="length[1,100]">
            </div>
            <div class="fitem">
                <label>渠道等级:</label>
                <select id="q_level" name="q_level" class="easyui-combobox" style="width:160px;" required="true" data-options="panelHeight:'auto',editable:false">
                    <option value="I级">I级</option>
                    <option value="II级">II级</option>
                    <option value="III级">III级</option>
                </select>
            </div>
            <!-- <div class="fitem">
                <label>对接人姓名:</label>
                <input name="q_picker" id="q_picker" class="easyui-textbox" required="true" validType="length[1,50]">
            </div>
            <div class="fitem">
                <label>电话号码:</label>
                <input name="q_picker_phone" id="q_picker_phone" class="easyui-textbox" required="true" validType="length[8,15]">
            </div> -->
            <div class="fitem">
                <label>公司名称:</label>
                <input name="q_picker_company" id="q_picker_company" class="easyui-textbox" required="true" validType="length[1,150]">
            </div>
            <div class="fitem">
                <label>公司地址:</label>
                <input name="q_picker_company_addr" id="q_picker_company_addr" class="easyui-textbox" required="true" validType="length[1,250]">
            </div>
            <div class="fitem">
                <label>唯一指定邮箱:</label>
                <input name="q_picker_company_mail" id="q_picker_company_mail" class="easyui-textbox" required="true" validType="email">
            </div>
            <div class="fitem">
                <label>经营时间:</label>
                <input name="q_picker_business_time" id="q_picker_business_time" class="easyui-textbox" required="true" validType="length[1,20]">
            </div>
            <div class="fitem">
                <label>主营业务:</label>
                <input name="q_picker_business" id="q_picker_business" class="easyui-textbox" required="true" validType="length[1,250]">
            </div>
            <div class="fitem">
                <label>现有团队人数:</label>
                <input name="q_team_numbers" id="q_team_numbers" class="easyui-numberbox" required="true" validType="length[1,5]" data-options="precision:0">
            </div>
            <div class="fitem">
                <label>是否有风控团队:</label>
                <select id="q_if_has_risk_team" name="q_if_has_risk_team" class="easyui-combobox" style="width:160px;" required="true" data-options="panelHeight:'auto',editable:false">
                    <option value="有">有</option>
                    <option value="无">无</option>
                </select>
            </div>
            <div class="fitem">
                <label>是否有自有存量:</label>
                <select id="q_if_has_stock" name="q_if_has_stock" class="easyui-combobox" style="width:160px;" required="true" data-options="panelHeight:'auto',editable:false">
                    <option value="有">有</option>
                    <option value="无">无</option>
                </select>
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
            </div>
            <div id="qudao-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doQudao()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#qudao-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

    <!-- 流程信息 -->
    <? showHistoryHtml(); ?>
    <!-- 提醒 -->
    <? showRemindHtml(); ?>
    

</body>
</html>