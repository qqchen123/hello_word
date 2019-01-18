<html>
<?php tpl("admin_applying") ?>
<body>
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/data-record-basic.css">
<script type="text/javascript" src="/assets/lib/js/nunjucks.js"></script>
<style>
    
    .pre-check {
        margin-left: 16px;
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

    .radioformr {
        width: 5px;
    }

    .sub-btn {
        margin-top:15px;
    }

    #submitForm label{
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
                <input class="col-sm-8" type="text" name="find-idnumber" id="find-idnumber" value="">
            </td>
            <td class="tlabel">姓名</td>
            <td>
                <input class="col-sm-8" type="text" name="find-name" id="find-name" value=""> 
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div region="center" data-options="border:false" style="padding: 0px 20px;">
    <table  id="tt" class="easyui-datagrid" style="width:100%;min-height:500px;max-height: 80%;"
        data-options="
            url: 'banklist',
            rownumbers: true,
            method: 'post',
            toolbar: '#toolbar',
            lines: true,
            fit: true,
            fitColumns: false,
            border: false,
            columns:globalData['listconfig'],
            pagination:true,
            onSortColum: function (sort,order) {
                $('#tt').datagrid('reload', {
                    sort: sort,
                    order: order
            　　});
            },
            rowStyler:function(index,row){
                return 'background-color:'+statusColor[row.status];
            }
            ">
    </table>
</div>

<div id="editbox" class="dn"></div>
<div id="dlg"><img id="simg" src=""></div>
<div id="youtu" class="dn">提交中</div>

<!-- 流程信息 -->
<? showHistoryHtml(); ?>

<script type="text/javascript">
    var youtu_flag = 1;
    var submit_flag = 1;
    var env = '<?= $env ?>';
    var enum_array = JSON.parse('<?= $status_enum?>');
    var session_name = '<?= $_SESSION['fms_username']?>';
    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
    var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
    var globalData = [];//用于装载全局js变量
    var phpData = [];//php返回的内容
    globalData['sample_config'] = <?= $sample_config?>;
    
    globalData['edit_user'] = '<?= $_SESSION['fms_username']?>';
    var statusColor = JSON.parse('<?= $statusColor ?>');

    globalData['tmp'] = [];


    String.prototype.replaceAll = function(f,e){//吧f替换成e
        var reg = new RegExp(f,"g"); //创建正则RegExp对象   
        return this.replace(reg,e); 
    }

</script>
<script type="text/javascript" src="/assets/apps/user/bankquery.js?s=<?= time()?>"></script>
<script type="text/javascript" src="/assets/apps/user/query.js?s=<?= time()?>"></script>
<script type="text/javascript">
    $("#editbox").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/edit_box_basic', 
            {}
        )
    );
    $('#submit-dlg').attr('style', $('#submit-dlg').attr('style')+'width:1600px;');
</script>
<script type="text/javascript">
    $('input[name="smaple_4"]').on('change', function(){
        console.log($('input[name="smaple_4"] selected'));
    });

    Array.prototype.unique = function() {
        var a = this.concat();
        for(var i=0; i < a.length; i++){
            for(var j=i+1; j < a.length; j++){
                if(a[i] === a[j]){
                    a.splice(j, 1);
                }
            }
        }
        return a;
    };

    function showBtn() {
        var row = {};
        var html = '';
        $('.op').each(function(){
            var html_tmp = [];
            var statuss = $(this).attr('data-status').split("-");
            for (var i = 0; i < statuss.length; i++) {
                row = {};
                html = '';
                row.id = $(this).attr('id').substr(2);
                row.pool_sample_id = $(this).text();
                row.obj_status = statuss[i];
                <?= $btnCode?>
                html_tmp = html_tmp.concat(html.split("&nbsp;&nbsp")).unique();
            }
            html = '<a data-id="'+ row.id +'" class="preview btn btn-primary btn-xs p310" href="javascript:void(0)">预览</a>';
            html += html_tmp.join('&nbsp;&nbsp');
            $(this).parent().append(html);
            //preview
            $(this).parent().children('.preview').on('click', function(){
                var id = $(this).attr('data-id');
                status(id, '预览', '预览', '');
            });
        });
    }

    //解除只读状态
    function ablebasic() {
        $('#submitForm #fuserid').textbox({
            disabled:false
        });
        $('#submitForm #idnumber').textbox({
            disabled:false
        });
        $('#submitForm #channel').textbox({
            disabled:false
        });
        $('#submitForm #name').textbox({
            disabled:false
        });
    }

    //锁定四个基础信息
    function readonlybasic(){
        $('#submitForm #fuserid').textbox({
            disabled:true
        });
        $('#submitForm #idnumber').textbox({
            disabled:true
        });
        $('#submitForm #channel').textbox({
            disabled:true
        });
        $('#submitForm #name').textbox({
            disabled:true
        });
    }


    //带过来的数据的背景色
    function bgcbasic(){
        var bgcgc = 'background-color: #B1A0C7 !important;color: #000 !important;';
        var tmp_style = $('#submitForm #fuserid').next().children('input:eq(0)').attr('style');
        $('#submitForm #fuserid').next().children('input:eq(0)').attr('style', tmp_style + bgcgc);

        var tmp_style = $('#submitForm #name').next().children('input:eq(0)').attr('style');
        $('#submitForm #name').next().children('input:eq(0)').attr('style', tmp_style + bgcgc);

        var tmp_style = $('#submitForm #channel').next().children('input:eq(0)').attr('style');
        $('#submitForm #channel').next().children('input:eq(0)').attr('style', tmp_style + bgcgc);

        var tmp_style = $('#submitForm #idnumber').next().children('input:eq(0)').attr('style');
        $('#submitForm #idnumber').next().children('input:eq(0)').attr('style', tmp_style + bgcgc);

    }

/*##########################################*/
    //动作
    //报审机构
    function baoShen(id){
        status(id, '报审', '报审', 'BaoShen');
    }

    //过审
    function guoShen(id){
        status(id, '过审', '通过', 'GuoShen');
    }

    //驳回
    function backShen(id){
        status(id, '驳回', '驳回', 'BackShen');
    }

    //停用机构
    function stop(id){
        status(id, '停用', '停用', 'Stop');
    }

    //启用机构
    function start(id){
        status(id, '启用', '启用', 'Start');
    }

    //申请修改
    function pleaseEdit(id){
        status(id, '申请修改', '申请', 'PleaseEdit');
    }

    //批准修改
    function yesEdit(id){
        status(id, '批准修改', '批准', 'YesEdit');
    }

    //拒绝修改
    function noEdit(id){
        status(id, '驳回修改', '驳回', 'NoEdit');
    }

/*##########################################*/

    //显示与隐藏联动
    function select_with_show(a1 = []) {
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
    function add_checkbox_check(row, record_status, role_type) {
        var is_check = row.check;
        var is_status = row.status;
        var role = row[role_type];
        var data = row.data;
        $('.checkbox').remove();
        //需要判断不属于这里的审核信息不加复选框
        //追加复选框
        if (is_check) {
            $.each(is_check, function(i, val){
                if (undefined == $('#' + i) || (-1 == $.inArray(parseInt(is_status[i]), record_status))) {//|| !data[i]
                } else if ('undefined' != $('#' + i) && 'status_info' != i && 'undefined' != is_check.i) {
                    console.log(role_type);
                    console.log(role);
                    if ('status_info' != i && 'undefined' != is_check.i && (undefined != role[i])) {
                        $('#' + i).parent().append("<input type='checkbox' name='check_" + i + "' class='ib checkbox' style='width: 16px;' checked=checked>");  
                        $('#' + i).parent().removeClass('pre-check');
                    }
                }
            });     
        }
    }


    //修改颜色
    function change_color(color) {
        var tmp_style = '';
        var color_map = [
            [15, 17, 19],
            [4, 5, 7, 9],
            [20],
            [40]
        ];
        var add_map = [
            'background-color:#f7de8f !important;',//黄
            'background-color:#de2531 !important;',//红
            'background-color:#8fd850 !important;',//绿
            'background-color:#fad2da !important;'//淡红
        ];
        $.each(color, function(i, item){
            tmp_style = $('#submitForm #'+i).next().children('input:eq(0)').attr('style');
            if (undefined != tmp_style) {
                tmp_style = tmp_style.replaceAll(add_map[0], '');
                tmp_style = tmp_style.replaceAll(add_map[1], '');
                tmp_style = tmp_style.replaceAll(add_map[2], '');
                for (var z = 0; z < color_map.length; z++) {
                    flag = 0;
                    for (var j = 0; j < color_map[z].length; j++) {
                        if (color_map[z][j] == item) {
                            flag = 1;
                            break;
                        }
                    }
                    if (flag) {
                        if (2 == z) {
                        }
                        $('#submitForm #'+i).next().children('input:eq(0)').attr('style', tmp_style + add_map[z]);
                    }
                }
            }
        });
    }

    //处理数据
    //后台接口数据预处理
    function dataDeal(row, url = '') {
        $('.checkbox').remove();
        ablebasic();
        var is_check = row.check ? row.check : [];//是否需要加输入框
        var is_status = row.status ? row.status : [];//状态
        var color = row.status ? row.status : [];//各字段颜色
        var data = row.data;
        console.log(is_status);
        
        //批量复选
        if (url.indexOf('BaoShen') != -1) {
            add_checkbox_check(row, enum_array['baoShenStatus'], 'BaoShen');
        }
        if (url.indexOf('GuoShen') != -1) {
            add_checkbox_check(row, enum_array['guoShenStatus'], 'GuoShen');
        }
        if (url.indexOf('BackShen') != -1) {
            add_checkbox_check(row, enum_array['backShenStatus'], 'BackShen');
        }
        if (url.indexOf('Stop') != -1) {
            add_checkbox_check(row, enum_array['stopStatus'], 'Stop');
        }
        if (url.indexOf('Start') != -1) {
            add_checkbox_check(row, enum_array['startStatus'], 'Start');
        }
        if (url.indexOf('PleaseEdit') != -1) {
            add_checkbox_check(row, enum_array['pleaseEditStatus'], 'PleaseEdit');
        }
        if (url.indexOf('YesEdit') != -1) {
            add_checkbox_check(row, enum_array['yesEditStatus'], 'YesEdit');
        }
        if (url.indexOf('NoEdit') != -1) {
            add_checkbox_check(row, enum_array['noEditStatus'], 'NoEdit');
        }

        //显示与隐藏相关联动
        var a1 = [];
        if (data.is_deposit_tube) {
            a1[a1.length] = 'expend';
        }
        select_with_show(a1);
        //填数据到编辑框
        $('#submit-dlg').form('load', data);

        $('.img-btn').each(function(){
            $(this).text('');
            $(this).attr('data-src', '');
        });

        //图片数据 需要手动放在后面的按钮上
        for (item in data) {
            if (undefined != $('input[name="'+item+'"]').parent().next('span') && 'img-btn' == $('input[name="'+item+'"]').parent().next('span').attr('class') ) {
                $('input[name="'+item+'"]').parent().next('span').text('');
                $('input[name="'+item+'"]').parent().next('span').attr('data-src', '');
            }
            var reg = new RegExp('[.png][.jpg]','g');
            if (reg.test(data[item])) {
                var file = $('input[name="'+item+'"]');
                if (file.outerHTML) {
                    file.outerHTML = file.outerHTML;
                } else { // FF(包括3.5)
                    file.value = "";
                }

                $('input[name="'+item+'"]').parent().next('span').attr('data-src', data[item]);
                $('input[name="'+item+'"]').parent().next('span').text('查看');
            }
        }

        readonlybasic();
        // bgcbasic();
        //disable input
        disableinput(is_status);

        //是否可见
        var edit_array = row.edit ? row.edit : [];
        console.log('可见');
        console.log(edit_array);
        if (edit_array) {
            for (var j = 0; j < globalData['web_config']['element'].length; j++) {
                var page_role = globalData['web_config']['element'][j]['samples'];
                for (var i = 0; i < page_role.length; i++) {
                    if ('object' == typeof(page_role[i])) {
                        for (var z = 0; z < page_role[i].length; z++) {
                            if (undefined == edit_array['sample_'+page_role[i][z]]) {
                                // console.log('dn ' + page_role[i]);
                                $('#'+'sample_'+page_role[i][z]).parent().addClass('dn');
                            } else {
                                $('#'+'sample_'+page_role[i][z]).parent().removeClass('dn');
                            }
                        }
                    } else {
                        if (undefined == edit_array['sample_'+page_role[i]]) {
                            // console.log('dn ' + page_role[i]);
                            $('#'+'sample_'+page_role[i]).parent().addClass('dn');
                        } else {
                            $('#'+'sample_'+page_role[i]).parent().removeClass('dn');
                        }
                    }
                }
            }
        }

        //是否可编辑
        var editdo_array = row.editdo ? row.editdo : [];
        console.log(editdo_array);
        if (editdo_array) {
            for (var i = 0; i < page_role.length; i++) {
                if ('object' == typeof(page_role[i])) {
                    for (var z = 0; z < page_role[i].length; z++) {
                        if (undefined == editdo_array['sample_'+page_role[i][z]]) {
                            // console.log('dn ' + page_role[i]);
                            $('#'+'sample_'+page_role[i][z]).next('span').children('input').attr('readonly', 'readonly');
                        } else {
                            $('#'+'sample_'+page_role[i][z]).next('span').children('input').attr('readonly', false);
                        }
                    }
                } else {
                    if (undefined == editdo_array['sample_'+page_role[i]]) {
                        // console.log('dn ' + page_role[i]);
                        $('#'+'sample_'+page_role[i]).next('span').children('input').attr('readonly', 'readonly');
                    } else {
                        $('#'+'sample_'+page_role[i]).next('span').children('input').attr('readonly', false);
                    }
                }
            }
        }

        //临时处理
        $('.easyui-combobox').each(function() {
            if ('depository' == $(this).attr('data-action')) {
                if (('' == $(this).combobox('getValue')) || (0 == $(this).combobox('getValue'))) {
                    $(this).combobox('select', 0);
                    $('.depository').addClass('dnlogic');
                    return false;
                }
            }
        });

        //加载颜色
        if (color) {
            change_color(color);
        }
    }

    //是已经通过的 内容只读
    function disableinput(arr) {
        for (key in arr) {
            if (arr[key] > 15) {
                var class_name = $('#submitForm #'+key).next().attr('class');
                if (undefined != class_name) {
                    if (-1 != class_name.indexOf('combobox')) {
                        $('#'+key).combobox({
                            disabled:true
                        });
                    } else if (-1 != class_name.indexOf('numberbox')) {
                        $('#'+key).numberbox({
                            disabled:true
                        });
                    } else if (-1 != class_name.indexOf('textbox')) {
                        console.log($('#'+key));
                        console.log(arr[key]);
                        $('#'+key).textbox({
                            disabled:true
                        });
                    }
                }
            }
        }
    }

    //检查身份证
    function checkidnumber(){
        var idnumber = $("#idnumber").val();
        $.post(
            PAGE_VAR.SITE_URL+'Qiye/checkidnumber',
             { idnumber: idnumber}, 
            function (response) {
                if(response.responseCode == 200){
                    top.modalbox.alert('身份证号不存在，请先开户',function(){
                        window.location.href = PAGE_VAR.SITE_URL+'Qiye/check';
                    });
                    return ;
                }else{
                    $("#name").val(response.responseMsg);
                }
            },'json'
        );
    };

    //改内容
    function no_status() {
        $('#status_info').parent('div').attr('hidden', true);
        $('#submitForm .easyui-textbox').textbox({
            disabled:false
        });
        $('#submitForm .easyui-combobox').combobox({
            disabled:false
        });
        $('#submitForm .easyui-datebox').datebox({
            disabled:false
        });
        $('#submitForm .easyui-numberbox').numberbox({
            disabled:false
        });
        $('#submitForm .easyui-filebox').filebox({
            disabled:false
        });
        $('select').removeAttr('disabled');
        $('#status_info').textbox('disable');
        $('#submitForm #classBtn').attr('onclick', 'doJiGou()');
    }

    //改状态
    function status(id, title, button, url) {
        $('#submit-dlg').dialog('open').dialog('setTitle', title);
        $('#submit-dlg').form('clear');
        $('#edit-sub').attr('data-url', url + '/' + globalData['page_type']);
        $('#edit-sub').attr('data-msg', title);
        $.getJSON('edit/' + id + '/' + globalData['page_type'], function(row){
            dataDeal(row, url);
        });

        $('#submitForm #classBtn .l-btn-text').text(button);
        $('#submitForm #classBtn').attr('onclick', 'doStatus("'+url+'","'+title+'")');
        $('#status_info').parent('div').removeAttr('hidden');

        //修改除审核附加信息框之外的 输入框为只读
        $('#submitForm .easyui-textbox').textbox({
            disabled:true
        });
        $('#submitForm .easyui-combobox').combobox({
            disabled:true
        });
        $('#submitForm .easyui-datebox').datebox({
            disabled:true
        });
        $('#submitForm .easyui-numberbox').numberbox({
            disabled:true
        });
        // //easyui-filebox
        $('#submitForm .easyui-filebox').filebox({
            disabled:true
        });
        $('select').attr('disabled', "disabled");
        $('#status_info').textbox('enable');
    }

//########################################################

    function edacg(dsf){
        var str = '';
        str += '<div style="width:850px;margin: 0 auto;">';
        $.each(dsf, function(index, content){
            str += '<div style="display:inline-block;padding-right:130px;">';
            str += nunjucks.renderString(
                globalData['tpl']['lable_tpl'], 
                {textbox:content.textbox}
            );
            str += nunjucks.renderString(
                globalData['tpl']['input_text_tpl'], 
                {name:content.name,id:content.id}
            );
            str += '</div>';
        });
        str +='</div></div>';
        return str;
    }

    //新增信息
    function add(fuserid, idnumber, name, channel) {
        //调用 数据处理 装载数据 打开新增窗口
        no_status();
        $('#submit-dlg').dialog('open').dialog('setTitle', '增加银行卡');
        $('#submitForm').form('clear');
        $('#submitForm #classBtn .l-btn-text').text('新增');
        $('#edit-sub').attr('data-url', 'add/bank');
        $('#edit-sub').attr('data-msg', '新增');
        var row = {};
        row.data = {
            'fuserid' : fuserid,
            'idnumber' : idnumber,
            'name' : name,
            'channel' : channel,
        };
        $.ajax({ 
            type : "get", 
            url : AJAXBASEURL + 'Qiye/getrole', 
            async : false, 
            success : function(data){ 
                data = JSON.parse(data);
                for (item in data) {
                    row[item] = data[item];
                }
            } 
        });
        // console.log(row);
        dataDeal(row, '');

        //临时处理
        // $('.easyui-combobox').each(function() {
        //     if ('depository' == $(this).attr('data-action')) {
        //         $(this).combobox('select', 0);
        //         $('.depository').addClass('dn');
        //         return false;
        //     }
        // });        
    }

    //查询按钮
    $("#likeBtn").click(function(){
        var idnumber = $("#find-idnumber").val();
        var name = $("#find-name").val();
        if(idnumber == false){ idnumber = 'err';}
        if(name == false){ name = 'err';}
        $('#tt').datagrid({"onLoadSuccess":function(data){
            showBtn();
        }}).datagrid('load', {idnumber:idnumber, name:name});
    }); 

    //查看图片
    $('.img-btn').on('click', function(){
        console.log($(this).attr('data-src'));
        //弹窗显示图片
        if ('dev' == env) {
            var src = '..' + $(this).attr('data-src')
        } else {
            var src = $(this).attr('data-src');
        }
        download(src);
    });

    function download(img){
        var simg =  PAGE_VAR.BASE_URL + '../' + img;
        $('#dlg').dialog({
            title: '预览',
            width: 800,
            height:450,
            resizable:true,
            closed: false,
            cache: false,
            modal: true
        });
        $("#simg").attr("src",simg);
    }
    
    //编辑客户信息
    function edit(id, detail) {
        console.log(detail);
        no_status();
        $('#submit-dlg').dialog('open').dialog('setTitle', globalData['edit_box_title']);
        $('#submitForm').form('clear');
        $('#edit-sub').attr('data-url', 'editdo/' + globalData['page_type']);
        $('#edit-sub').attr('data-msg', '');
        $.getJSON('edit/' + id + '/' + globalData['page_type'], function(row){
            dataDeal(row);
        });
        $('#submitForm #classBtn .l-btn-text').text(globalData['edit_box_btn']);
    }

    $('#submit-dlg').removeClass('dn');
</script>
<?= tpl('admin_foot') ?>
</body>
</html>