<?php //tpl("admin_header") ?>
<body>
<!-- <link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css"> -->
<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/bootstrap-grid.min.css">
<link rel="stylesheet" href="/assets/lib/css/zoomify.min.css">
<!--<link rel="stylesheet" href="/assets/lib/css/style.css">-->
<link rel="stylesheet" href="/assets/layui/layui.css">
<!-- <title></title> -->
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/assets/layui/layui.js"></script>
<style>
	.jkborrow{
		float: right;
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
    .sub-btn {
        margin-left: 200px;
    }
</style>

<body>

<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',title:'word模板',split:true" style="width:100%;">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'east',collapsed:false" style="width:120px">
                <button type="button" class="layui-btn" id="test2">
                    <i class="layui-icon">&#xe67c;</i>模板上传
                </button>
                <br>
                <br>
                <br>
                <button type="button" class="layui-btn" id="test1">
                    <i class="layui-icon">&#xe67c;</i>excel上传
                </button>
            </div>
            <div data-options="region:'center'">
                <table id="word"></table>
            </div>
        </div>
    </div>
</div>
<div id="word_excel_win" class="easyui-window" title="生成word" style="width:400px;height:200px"
     data-options="modal:true,closed:true">
    <form id="w_e_win" method="post">
        <br>
        <div>
            <div class="fitem">
                <label for="name">sheet:</label>
                <input id="sheet"  name="sheet">
            </div>
            <br>
            <div class="fitem">
                <label for="name">word模板:</label>
                <input id="up_word" name="word_path">
                <input type="hidden" value="excel_path" name="excel_path" id="excel_path">
            </div>
            <br>
            <br>
            <div class="sub-btn">
                <a class="btn btn-primary btn-xs p310" href="javascript:void(0)" style="width: 80px;" onclick="excel_to_word()" >生成 </a>
            </div>
        </div>
    </form>
</div>

</body>

<script>
    $('#sheet').combobox({
        url:'',
        valueField:'id',
        textField:'sheet',
        onChange:function(newValue,oldValue){
            $('#up_word').combobox('setValue','');
            let excel_path = $('#excel_path').val();
            $.ajax({
                type: "POST",
                url: "test_excel",
                data: {id:newValue,excel_path},
                dataType: "json",
                success: function(data){
                    console.log(data);
                    // $('#up_word').combobox();
                    // $('#up_word').combobox('loadData',data);
                    $('#up_word').combobox('setValue',data);
                }
            });
        }
    });
    $('#up_word').combobox({
        url:'get_combobox_word_info',
        valueField:'file_path',
        textField:'file_name',
    });
    $('#word').datagrid({
        url:'get_file_info',
        pagination:true,
        rownumbers: true,
        columns:[[
            {field:'file_name',title:'模板名称',width:150},
            {field:'file_path',title:'路径',width:500},
            {field:'c_time',title:'上传时间',width:100},
            {field:'operate',title:'操作',width:100,align:'center',
                formatter:function (value,row) {
                    var html = '';
                    let id = row.id;
                    let path = row.file_path;
                    // console.log(row);//http://120.26.89.131:60523/fms/upload/978d588c8b9a7d635df52c21492bb96d.docx
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="delword('+id+','+'\''+path+'\''+')" >删除 </a>'+'&nbsp;&nbsp';
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="download_word('+'\''+id+'\''+')" >下载 </a>'+'&nbsp;&nbsp';
                    return html;
                }
            }
        ]]
    });
    $('#excel').datagrid({
        url:'get_file_info_excel',
        pagination:true,
        columns:[[
            {field:'file_name',title:'excel名称',width:150},
            {field:'file_path',title:'路径',width:100},
            {field:'c_time',title:'上传时间',width:100},
            {field:'operate',title:'操作',width:150,align:'center',
                formatter:function (value,row) {
                    var html = '';
                    let pwd = row.pwd;
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="status()" >删除 </a>'+'&nbsp;&nbsp';
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="status()" >转换 </a>'+'&nbsp;&nbsp';
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="status()" >下载 </a>'+'&nbsp;&nbsp';
                    return html;
                }
            }
        ]]
    });
    //layui文件上传--excel
    layui.use('upload', function(){
        var upload = layui.upload;
        //执行实例
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: 'excel_uploadify_file'
            ,accept: 'file' //普通文件
            ,done: function(res){
                $("#w_e_win").form('clear');
                $('#excel_path').val(res[0].file);
                $('#sheet').combobox('loadData',res);
                $('#word_excel_win').window('open');
            }
        });
        var uploadInst = upload.render({
            elem: '#test2'
            ,url: 'uploadify_file'
            ,accept: 'file' //普通文件
            ,done: function(res){
                if (res.state==1){
                    $.messager.show({
                        title: '提示',
                        msg: res.msg
                    });
                }else{
                    $.messager.show({
                        title: '提示',
                        msg: res.msg
                    });
                }
                $("#word").datagrid("reload");
                $("#excel").datagrid("reload");
                window.location.reload();
            }
        });
    });
    function excel_to_word() {
        $('#w_e_win').form('submit', {
            url:'excel_to_word',
            onSubmit: function(){
                // do some check
                // return false to prevent submit;
            },
            success:function(data){
                // alert(data);
                console.log(data);
            }
        });
    }
    function delword(id,path) {
        $.ajax({
            type: "POST",
            url: "file_del",
            data: {id,path},
            dataType: "json",
            success: function(data){
                if (data){
                    $.messager.show({
                        title: '提示',
                        msg: '删除成功！'
                    });
                }else{
                    $.messager.show({
                        title: '提示',
                        msg: '删除失败！'
                    });
                }
                $("#word").datagrid("reload");
                $("#excel").datagrid("reload");
            }
        });
    }
    //download
    function download_word(id) {
        window.location.href = 'http://120.26.89.131:60523/fms/index.php/Tools/download_word?id='+id;
    }
</script>

</body>