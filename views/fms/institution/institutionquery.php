<html>
<?php //tpl("admin_header") ?>
<body>
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

    .ib {
        display: inline-block;
    }

    .w2 {
        width:48%;
    }

    .w3 {
        width:30%;
    }

    .dn {
        display: none;
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

        .fitem input {
        width: 160px;
    }

    .fitem label {
        display: inline-block;
        width: 100px;
    }

    .w100 {
        width: 100px;
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
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td class="tlabel">身份证号</td>
            <td>
                <input class="col-sm-8" type="text" name="idnumber" id="find-idnumber" value="">
            </td>
            <td class="tlabel">姓名</td>
            <td>
                <input class="col-sm-8" type="text" name="name" id="find-name" value=""> 
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
                <?php if (checkRolePower('jigou','do_jigou')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="add()" ><i class="fa fa-plus"></i>新增</a>
                <?php endif ?>
            </td>
        </tr>
        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table  id="tt" class="easyui-datagrid" style="width:100%;height:350px"
            data-options="
                url: 'institutionlist',
                rownumbers: true,
                method: 'post',
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
                    return 'background-color:'+statusColor[row.mb_status];
                }
                ">
        </table>
    </div>
</div>

    <!-- 新增/编辑机构 -->
    <div id="jigou-dlg" style="width:600px;padding:10px 20px;top:50px;" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true" shadow="false">
        <form id="jiGouForm" method="post" novalidate enctype="multipart/form-data">
            <div id="edit-box"></div>
            <div class="fitem" hidden>
                <label id="info_label">流程附加信息:</label>
                <input name="status_info" id="status_info" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,240]" data-options="multiline:true" novalidate="true">
            </div>
            <div class="fitem dn" id="remind">
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
            <div id="jigou-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doJiGou()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#jigou-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
    <!-- 流程信息 -->
    <? showHistoryHtml(); ?>

<script>
    var statusColor = JSON.parse('<?= $statusColor ?>');
    <?= $jscontroller?>
    var page_type = 'institution';
    var edit_box_title = '编辑机构账户信息';
    var edit_box_btn = '提交';
    var type_name = '机构账户';
    var role_arrry = [];
    //获取列表
    var col_data = [[
        {field: 'fuserid', title: '客户编号', width: 100, align:'center'},
        {field: 'idnumber', title: '身份证号', width: 160,  align:'center'},
        {field: 'name', title: '姓名', width: 100,  align:'center'},
        {field: 'mobile', title: '绑定手机号', width: 200,  align:'center'},
        {field: 'lutime', title: '最后录入时间', width: 160,  align:'right',halign:'center'},
        {field: 'op', title: '操作', width: 300,  align:'center',
            formatter: function(value, row, index) {
                var html = '';
                <?php 
                //基础设置
                    //id字段 默认row.id
                    $this->id_field = 'row.id';
                    //状态字段 默认row.obj_status
                    $this->status_field = 'row.obj_status';
                    //后端访问方法
                    foreach ($this->sbtn_option as $key => $value) {
                        $this->sbtn_option->$key->method = 'institution' . ucfirst($this->sbtn_option->$key->method);
                    }
                //输出状态按钮
                    echo showStatusBtn(true);
                ?>
                return html;
            }
        }
    ]];
    var row = <?= $editBox ?>;
    //加载 编辑框
    $('#edit-box').html('');
    $('#edit-box').html(row.content);

    $("#likeBtn").click(function(){
        var idnumber = $("#find-idnumber").val();
        var name = $("#find-name").val();
        if(idnumber == false){ idnumber = 'err';}
        if(name == false){ name = 'err';}
        if(name == 'err' && idnumber == 'err'){
            top.modalbox.alert('查询条件不能为空',function () {
                return;
            })
        }
        $('#tt').datagrid('load', {idnumber:idnumber, name:name});
    }); 

//##########################################
    //动作
    //新增信息
    function add() {
        //调用 数据处理 装载数据 打开新增窗口
        no_status();
        $('#jigou-dlg').dialog('open').dialog('setTitle', '增加机构账户');
        $('#jiGouForm').form('clear');
        dataDeal(re_data);
        $('#jiGouForm #classBtn .l-btn-text').text('新增');
        return;
        $.messager.prompt("增加"+type_name, "请输入增加"+type_name+"用户的身份证", function (idnumber) {
            if (idnumber) {
                console.log(idnumber);
                //检查
                $.post(
                    './institutioncheck',
                    {idnumber:idnumber},
                    function (response) {
                        console.log(response);
                        //接收 结果
                        var re_data = JSON.parse(response);
                        if (re_data['code']) {
                            //调用 数据处理 装载数据 打开新增窗口
                            no_status();
                            $('#jigou-dlg').dialog('open').dialog('setTitle', '增加银行卡');
                            $('#jiGouForm').form('clear');
                            dataDeal(re_data);
                            $('#jiGouForm #classBtn .l-btn-text').text('新增');
                        } else {
                            $.messager.alert('注意', re_data['message']);
                        }
                    }
                );
            }
        });
    }

    //编辑信息
    function edit(id, detail){
        no_status();
        $('#jigou-dlg').dialog('open').dialog('setTitle', edit_box_title);
        $('#jiGouForm').form('clear');
        $.getJSON(page_type + 'edit/' + id, function(row){
            dataDeal(row, detail);
        });
        $('#jiGouForm #classBtn .l-btn-text').text(edit_box_btn);
    }

    //报审机构
    function baoShen(id){
        status(id, '报审', '报审', page_type + 'BaoShen');
    }

    //初审通过
    function guoChuShen(id){
        status(id, '初审通过', '通过', page_type + 'GuoChuShen');
    }

    //初审退回
    function backChuShen(id){
        status(id, '初审驳回', '驳回', page_type + 'BackChuShen');
    }

    //复审通过
    function guoFuShen(id){
        status(id, '复审通过', '通过', page_type + 'GuoFuShen');
    }

    //复审退回
    function backFuShen(id){
        status(id, '复审驳回', '驳回', page_type + 'BackFuShen');
    }

    //停用机构
    function stop(id){
        status(id, '停用机构', '停用', page_type + 'Stop');
    }

    //启用机构
    function start(id){
        status(id, '启用机构', '启用', page_type + 'Start');
    }

    //申请修改
    function pleaseEdit(id){
        status(id, '申请修改', '申请', page_type + 'PleaseEdit');
    }

    //批准修改
    function yesEdit(id){
        status(id, '批准修改', '批准', page_type + 'YesEdit');
    }

    //拒绝修改
    function noEdit(id){
        status(id, '驳回修改', '驳回', page_type + 'NoEdit');
    }
//#################################################
    //显示与隐藏联动
    function select_with_show(a1 = [])
    {
        if (a1) {
            for (var i = 0; i < a1.length; i++) {
                var box_class = $('.'+a1[i]).attr('class');
                if (-1 != box_class.indexOf('dn')) {
                    $('.' + a1[i]).removeClass('dn');
                }
            }
        }
    }

    //批量多选框
    function add_checkbox_check(is_check){
        $('.checkbox').remove();
        //需要判断不属于这里的审核信息不加复选框
        //追加复选框
        if (is_check) {
            $.each(is_check, function(i, val){
                if ('undefined' != $('#' + i) && 'status_info' != i && 'undefined' != is_check.i) {
                    $('#' + i).parent().prepend("<input type='checkbox' name='check_" + i + "' class='ib checkbox' style='width: 16px;' checked=checked>");  
                    $('#' + i).parent().removeClass('pre-check');
                }
            });     
        }
    }

    //修改颜色
    function change_color(color){
        $.each(color, function(i, item){
            $('#'+i).css('background', statusColor[item]);
        });
    }

    //处理数据
    //后台接口数据预处理
    function dataDeal(row, detail = [], url = '') {
        var is_check = row.check ? row.check : [];//是否需要加输入框
        var color = row.status ? row.status : [];//各字段颜色
        var data = row.data;
        //加载颜色
        if (color.length) {
            change_color(color);
        }
        //批量复选
        if (url.indexOf(page_type + 'GuoChuShen') != -1) {
            add_checkbox_check(is_check);
        }

        //填数据到编辑框
        $('#jigou-dlg').form('load', data);
    }
    
    

    //改内容
    function no_status() {
        $('#status_info').parent('div').attr('hidden', true);
        $('#jiGouForm .easyui-textbox').textbox({
            disabled:false
        });
        $('#jiGouForm .easyui-combobox').combobox({
            disabled:false
        });
        $('#jiGouForm .easyui-datebox').datebox({
            disabled:false
        });
        $('select').removeAttr('disabled');
        $('#status_info').textbox('disable');
        if ($('#remind').attr('class').indexOf('dn')) {
            $('#remind').addClass('dn');
        }
        $('#jiGouForm #classBtn').attr('onclick', 'doJiGou()');
    }

    //改状态
    function status(id, title, button, url){
        $('#jigou-dlg').dialog('open').dialog('setTitle', title);
        $('#jigou-dlg').form('clear');
        $.getJSON(page_type + 'edit/' + id, function(row){
            dataDeal(row, url);
            $('#remind').removeClass('dn');
        });

        $('#jiGouForm #classBtn .l-btn-text').text(button);
        $('#jiGouForm #classBtn').attr('onclick', 'doStatus("'+url+'","'+title+'")');
        $('#status_info').parent('div').removeAttr('hidden');

        //修改除审核附加信息框之外的 输入框为只读
        $('#jiGouForm .easyui-textbox').textbox({
            disabled:true
        });
        $('#jiGouForm .easyui-combobox').combobox({
            disabled:true
        });
        $('#jiGouForm .easyui-datebox').datebox({
            disabled:true
        });
        $('select').attr('disabled', "disabled");
        $('#status_info').textbox('enable');
    }

    //执行添加、编辑
    function doJiGou() {
        $('#jiGouForm').form('submit', {
            url: page_type + 'editdo',
            onSubmit: function() {    
                return $(this).form('enableValidation').form('validate');
            },
            dataType: 'json',
            success: function(result) {
                var info = JSON.parse(result);
                top.modalbox.alert(info.msg);
                $('#jigou-dlg').dialog('close');
            }
        });
    }

    //执行改状态
    function doStatus($url, title){
        $('#jiGouForm').form('submit', {
            url: $url,
            onSubmit: function() {    
                return $(this).form('enableValidation').form('validate');
            },
            dataType: 'json',
            success: function(result) {
                if (result == 1) {
                    $('#jigou-dlg').dialog('close');
                    $.messager.show({
                        title: '提示',
                        msg: title + '操作成功！'
                    });
                    $('#tt').datagrid('reload');
                } else {
                    $.messager.show({
                        title: '提示',
                        msg: title + '操作失败！'
                    });
                }
            }
        });
    }

    //编辑框 显示与隐藏
    function showExpend(className) {
        var box_class = $('.'+className).attr('class');
        if ('expend' == className) {
            var obj = $('#is_deposit_tube option:selected');
        }
        if ('finance' == className) {
            var obj = $('#is_finance_receive option:selected');
        }
        if (box_class) {
            if (1 == obj.val()) {
                if (-1 != box_class.indexOf('dn')) {
                    $('.'+className).removeClass('dn');
                }
            } else {
                if (-1 == box_class.indexOf('dn')) {
                    $('.'+className).addClass('dn');
                }
            }
        }
    }
    
   </script>
</body>
</html>

