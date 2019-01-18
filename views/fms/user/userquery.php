<?php tpl("admin_applying") ?>
<body>
<link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="/assets/lib/css/data-record-basic.css">
<style type="text/css">
    .textbox-text {
        width: 260px !important; 
    }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div id="listselect"></div>
    <div id="easyuilist"></div>
    <div id="editbox"></div>
    <div id="checkbox"></div>
    <div id="seniorbox"></div>
    <div id="dlg"><img id="simg" src=""></div>
    <div id="youtu" class="dn">提交中</div>
</div><!-- /.page-content -->
	<!-- 流程信息 -->
    <? showHistoryHtml(); ?>
<script>
    var youtu_flag = 1;
    var submit_flag = 1;
    var env = '<?= $env ?>';
    var session_name = '<?= $_SESSION['fms_username']?>';
    var statusColor = JSON.parse('<?= $statusColor ?>');
    <?= $jscontroller?>
    var map = JSON.parse('<?= $map?>');
    var enum_array = JSON.parse('<?= $status_enum?>');
    var role_arrry = [];
    var edit_able = ['fuserid', 'idnumber', 'name', 'channel'];
    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
    var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
    var globalData = [];//用于装载全局js变量
    var phpData = [];//php返回的内容
    //[key=>array]
    globalData['sample_config'] = <?= json_encode($sample_config, JSON_UNESCAPED_UNICODE)?>;
    globalData['tmp'] = [];
    globalData['tmp']['channel'] = <?= json_encode($qudaoinfo, JSON_UNESCAPED_UNICODE)?>;
    globalData['tmp']['channel_id'] = JSON.parse(<?= json_encode($qudaoinfo_id, JSON_UNESCAPED_UNICODE)?>);

</script>
<script type="text/javascript" src="/assets/apps/user/userquery.js?s=<?= time()?>"></script>
<script type="text/javascript" src="/assets/apps/user/query.js?s=<?= time()?>"></script>
<script type="text/javascript">
    //补渠道列表
    var channel_array = JSON.parse('<?= $qudaoinfo?>');
    globalData['check_box_config'][0][2]['input_options'] = channel_array;
    globalData['change_box_config'][0][2]['input_options'] = channel_array;

    //numjucks 加载页面
    $('#checkbox').append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/simple_form', 
            {form_id:'check-box'}
        )
    );
    $('#seniorbox').append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/change_info_form', 
            {form_id:'change-box'}
        )
    );
    $("#listselect").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/select_basic', 
            {
                selectConfig: [['身份证号', 'idnumber', 'find-idnumber'],['姓名', 'name', 'find-name']],
                canSelect: <?= checkRolePower('Qiye', 'qylist') ?>, 
                expendBtn: [['开户', '', 'check', <?= checkRolePower('Qiye', 'createuser') ?>]]
            }
        )
    );
    $("#easyuilist").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/datagrid_basic', 
            {datagrid_id: 'tt', target: 'qylist'}
        )
    );
    $("#editbox").prepend(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/edit_box_basic', 
            {boxwidth:'width:1400px;'}
        )
    );
</script>

<script type="text/javascript">

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

    //显示按钮
    function showBtn() {
        var row = {};
        var html = '';
        var senior = <?= $senior?>;
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
            if (senior) {
                html += '<a data-id="'+ row.id +'" class="senior btn btn-primary btn-xs p310" href="javascript:void(0)">高级</a>';
            }
            $(this).parent().append(html);
            if (senior) {
                $(this).parent().children('.senior').on('click', function(){
                    var idnumber = $(this).parent().parent().parent().children('td[field="idnumber"]').children('div').text();
                    var name = $(this).parent().parent().parent().children('td[field="name"]').children('div').text();
                    change(row.id, idnumber, name);
                });
            }
            //preview
            $(this).parent().children('.preview').on('click', function(){
                var id = $(this).attr('data-id');
                status(id, '预览', '预览', '');
            });


        });
    }

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

    //打开 编辑客户注册信息菜单
    function change(id, idnumber, name) {
        $('#change-box').dialog('open').dialog('setTitle', '修改注册信息');
        $('#change-id').val(id);
        $('#change-name').textbox('setValue', name);
        $('#change-name').textbox({
            disabled: true
        });
        $('#change-idnumber').textbox('setValue', idnumber);
        $('#change-idnumber').textbox({
            disabled: true
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

    //批量多选框
    function add_checkbox_check(row, record_status, role_type){
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
                } else {
                    // console.log(role_type);
                    // console.log(role);
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

        if (undefined != data['sample_22']) {
            if ('' == data['sample_22']) {
                data['sample_22'] = session_name;
            }
        } else {
            data['sample_22'] = session_name;
        }

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
        console.log(edit_array);
        if (edit_array) {
            var page_role = globalData['web_config']['element'][0]['samples'];
            for (var i = 0; i < page_role.length; i++) {
                if (undefined == edit_array['sample_'+page_role[i]]) {
                    // console.log('dn ' + page_role[i]);
                    $('#'+'sample_'+page_role[i]).parent().addClass('dn');
                } else {
                    $('#'+'sample_'+page_role[i]).parent().removeClass('dn');
                }
            }
        }

        //是否可编辑
        var editdo_array = row.editdo ? row.editdo : [];
        console.log(editdo_array);
        if (editdo_array) {
            for (var i = 0; i < page_role.length; i++) {
                if (undefined == editdo_array['sample_'+page_role[i]]) {
                    // console.log('dn ' + page_role[i]);
                    $('#'+'sample_'+page_role[i]).next('span').children('input').attr('readonly', 'readonly');
                } else {
                    $('#'+'sample_'+page_role[i]).next('span').children('input').attr('readonly', false);
                }
            }
        }

        //加载颜色
        if (color) {
            change_color(color);
        }
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

    //是已经通过的 内容只读
    function disableinput(arr) {
        for (key in arr) {
            if (arr[key] > 15) {
                var class_name = $('#submitForm #'+key).next().attr('class');
                if (-1 != class_name.indexOf('combobox')) {
                    $('#'+key).combobox({
                        disabled:true
                    });
                } else if (-1 != class_name.indexOf('numberbox')) {
                    $('#'+key).numberbox({
                        disabled:true
                    });
                } else if (-1 != class_name.indexOf('textbox')) {
                    $('#'+key).textbox({
                        disabled:true
                    });
                } else if (-1 != class_name.indexOf('datebox')) {
                    $('#'+key).datebox({
                        disabled:true
                    });
                }
            }
        }
    }

    //改内容
    function no_status() {
        $('#submitForm .easyui-textbox').textbox({
            disabled:false
        });
        $('#submitForm .easyui-combobox').combobox({
            disabled:false
        });
        $('#submitForm .easyui-datebox').datebox({
            disabled:false
        });
        $('#submitForm .easyui-filebox').filebox({
            disabled:false
        });
        $('#submitForm select').combobox({
            disabled:false
        });
        $('select').removeAttr('disabled');
        if (-1 == $('#status_info').attr('class').indexOf('dn')) {
            // $('#status_info').addClass('dn');
        }
        //启用fuserid idnumber name channel nation 的编辑权限
        for (var i = 0; i < edit_able.length; i++) {
            $('#'+edit_able[i]).attr('readonly', '');
        }
        $('#submitForm #classBtn').attr('onclick', 'doJiGou()');
    }

    //编辑客户信息
    function edit(id, detail){
        no_status();
        $('#edit-sub').attr('data-url', 'editdo/' + globalData['page_type']);
        $('#submit-dlg').dialog('open').dialog('setTitle', globalData['edit_box_title']);
        $('#submitForm').form('clear');
        $.getJSON('edit/' + id + '/' + globalData['page_type'], function(row){
            dataDeal(row, detail);
        });
        //关闭fuserid idnumber name channel nation 的编辑权限
        for (var i = 0; i < edit_able.length; i++) {
            $('#'+edit_able[i]).attr('readonly', 'readonly');
        }
        $('#submitForm #classBtn .l-btn-text').text(globalData['edit_box_btn']);
    }

    //改状态
    function status(id, title, button, url){
        $('#submit-dlg').dialog('open').dialog('setTitle', title);
        $('#submit-dlg').form('clear');
        $.getJSON('edit/' + id + '/' + globalData['page_type'], function(row){
            dataDeal(row, url);
        });
        $('#submitForm #classBtn .l-btn-text').text(button);
        $('#status_info').parent('div').removeClass('dn');

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
        $('#submitForm .easyui-filebox').filebox({
            disabled:true
        });
        $('#submitForm select').combobox({
            disabled:true
        });
        $('select').attr('disabled', "disabled");
        if (url) {
            $('#edit-sub').attr('data-url', url + '/' + globalData['page_type']);
            $('#status_info').textbox('enable');
        } else {
            $('#status_info').parent('div').addClass('dn');
        }
    }

    //新增信息
    function add(fuserid, idnumber, name, channel) {
        //调用 数据处理 装载数据 打开新增窗口
        no_status();
        $('#submit-dlg').dialog('open').dialog('setTitle', '增加用户信息');
        $('#submitForm').form('clear');
        $('#submitForm #classBtn .l-btn-text').text('新增');
        $('#edit-sub').attr('data-url', 'add/user');
        $('#edit-sub').attr('data-msg', '新增');
        var row = {};
        row.data = {
            'fuserid' : fuserid,
            'idnumber' : idnumber,
            'name' : name,
            'channel' : channel,
        };
        dataDeal(row, '');
    }

//##########################################
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
        status(id, '停用机构', '停用', 'Stop');
    }

    //启用机构
    function start(id){
        status(id, '启用机构', '启用', 'Start');
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
//#################################################
    //点击查询
    $("#query").click(function(){
        var idnumber = $("#find-idnumber").val();
        var name = $("#find-name").val();
        if(idnumber == false){ idnumber = 'err';}
        if(name == false){ name = 'err';}
        $('#tt').datagrid('load', {idnumber:idnumber, name:name});
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

</script>

<?= tpl('admin_foot') ?>
</body>
</html>