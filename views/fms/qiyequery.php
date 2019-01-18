<?php tpl("admin_header") ?>
<body>
<link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css">

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

    .ib {
        display: inline-block;
    }

    .w2 {
        width:40%;
    }

    .w3 {
        width:36%;
    }

    .dn {
        display: none;
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
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
	<div region="north" data-options="border:false" style="padding: 8px 20px;">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        <tr>
        	<td class="tlabel">手机号</td>
            <td>
            	<input class="col-sm-8" type="text" name="login_name" id="find-login-name" value="">
            </td>
            <td class="tlabel">客户编号</td>
            <td>
                <input class="col-sm-8" type="text" name="fuserid" id="find-fuserid" value=""> 
            </td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('Qiye', 'qylist')): ?>
                    <button type="submit" class="btn btn-success ml2" id="queryqiye">查询</button>
                <?php endif;?>
                <a class="btn btn-success ml2" href="<?php echo site_url('qiye/check')?>">开户</a>
			</td>
        </tr>
        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table  id="tt" class="easyui-datagrid" style="width:100%;height:350px"
            data-options="
                url: 'qylist',
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
</div><!-- /.page-content -->

<!-- 新增/编辑机构 -->
    <div id="jigou-dlg" style="width:700px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true">
        <form id="jiGouForm" method="post" novalidate enctype="multipart/form-data"> 
            <div id="edit-box"></div>
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
<script>
    var statusColor = JSON.parse('<?= $statusColor ?>');
    <?= $jscontroller?>
    var map = JSON.parse('<?= $map?>');
    var page_type = 'reg';
    var edit_box_title = '编辑客户信息';
    var edit_box_btn = '提交';
    var role_arrry = [];
    var edit_able = ['fuserid', 'idnumber', 'name', 'channel', 'nation'];

    //配置列表加载内容
    var col_data = [[
        {field: 'fuserid', title: '客户编号', width: 100, align:'center'},
        {field: 'channel', title: '渠道编号', width: 100, align:'center'},
        {field: 'idnumber', title: '身份证号', width: 160,  align:'center'},
        {field: 'name', title: '姓名', width: 100,  align:'center'},
        {field: 'status', title: '状态', width: 100,  align:'center'},
        {field: 'ctime', title: '最后录入时间', width: 160,  align:'right',halign:'center'},
        {field: 'op', title: '操作', width: 300,  align:'center',
            formatter: function(value, row, index) {
                console.log(row);
                var html = '';
                <?php 
                //基础设置
                //id字段 默认row.id
                $this->id_field = 'row.id';
                //状态字段 默认row.obj_status
                $this->status_field = 'row.obj_status';
                //后端访问方法
                foreach ($this->sbtn_option as $key => $value) {
                    $this->sbtn_option->$key->method = 'reg' . ucfirst($this->sbtn_option->$key->method);
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

    $("#queryqiye").click(function(){
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
    function dataDeal(row, detail, url = '') {
        var is_check = row.check ? row.check : [];//是否需要加输入框
        var color = row.status ? row.status : [];//各字段颜色
        var data = row.data;
        //如果有detail 则需要筛选一下 上面的三个数组
        console.log(map);
        $.each(map, function(index, value){
            if (!$.inArray(value, detail)) {
                
            }
        });
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
    
    //编辑客户信息
    function edit(id, detail){
        no_status();
        $('#jigou-dlg').dialog('open').dialog('setTitle', edit_box_title);
        $('#jiGouForm').form('clear');
        $.getJSON(page_type + 'edit/' + id, function(row){
            dataDeal(row, detail);
        });
        //关闭fuserid idnumber name channel nation 的编辑权限
        for (var i = 0; i < edit_able.length; i++) {
            $('#'+edit_able[i]).attr('readonly', 'readonly');
        }
        $('#jiGouForm #classBtn .l-btn-text').text(edit_box_btn);
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
        //启用fuserid idnumber name channel nation 的编辑权限
        for (var i = 0; i < edit_able.length; i++) {
            $('#'+edit_able[i]).attr('readonly', '');
        }
        $('#jiGouForm #classBtn').attr('onclick', 'doJiGou()');
    }

    //改状态
    function status(id, title, button, url){
        $('#jigou-dlg').dialog('open').dialog('setTitle', title);
        $('#jigou-dlg').form('clear');
        $.getJSON(page_type + 'edit/' + id, function(row){
            dataDeal(row, [], url);
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

</script>
</body>
</html>