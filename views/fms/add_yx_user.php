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


</style>

<body class="easyui-layout">

<div region="center" data-options="border:false,title:'银信用户新增'" style="">
    <div style="width: 50%; padding-left: 30px;">
        <form id="ff" method="post">

            <div class="form-group">
                <label for="firstname" class="col-sm-3 control-label">用户编号：</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control easyui-validatebox" data-options="required:true" id="fuserid"
                           name="fuserid" placeholder="请输入用户编号">
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-3 control-label">姓名：</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control easyui-validatebox" data-options="required:true" id="name"
                           name="name" placeholder="请输入姓名">
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-3 control-label">身份证：</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control easyui-validatebox" data-options="required:true"
                           id="idnumber" name="idnumber" placeholder="请输入身份证">
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-3 control-label">银信开户手机：</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control easyui-validatebox" data-options="required:true"
                           id="reg_phone" name="reg_phone" placeholder="请输入开户手机">
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-3 control-label">银信绑定手机：</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control easyui-validatebox" data-options="required:true"
                           id="binding_phone" name="binding_phone" placeholder="请输入银信绑定手机">
                </div>
            </div>
            <div class="form-group">
                <label for="firstname" class="col-sm-3 control-label">渠道编号：</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="channel" name="channel" placeholder="请输入渠道编号">
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                <label for="firstname" class="col-sm-3 control-label">创建员工：</label>-->
<!--                <div class="col-sm-7">-->
<!--                    <input type="text" class="form-control" id="cjyg" name="cjyg" placeholder="请输入创建员工">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <label for="firstname" class="col-sm-3 control-label">开户日期：</label>-->
<!--                <div class="col-sm-7">-->
<!--                    <input type="text" class="form-control" id="reg_time" style="width:100%; height: 34px" name="reg_time" placeholder="请输入开户日期">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <label for="firstname" class="col-sm-3 control-label">创建时间：</label>-->
<!--                <div class="col-sm-7">-->
<!--                    <input type="text" class="form-control" id="ctime" style="width:100%; height: 34px" name="ctime" placeholder="请输入创建时间">-->
<!--                </div>-->
<!--            </div>-->

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" onclick="add_yx_user_info()" style="margin-top: 50px;" class="btn btn-default">提交</button>
                    <button type="button" onclick="add_yx_user_info_reset()" style="margin-top: 50px; margin-left: 50px;" class="btn btn-default">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div data-options="region:'north',title:'上传Excel导入用户！'" style="height:100px;">
    <button type="button" style="margin-top: 20px; margin-left: 20px;" id="test" class="btn btn-success active">导入Excel</button>
    <button type="button" style="margin-top: 20px; margin-left: 20px;" onclick="download_excel()" class="btn btn-success active">模板下载</button>
</div>



<!--<div id="dd" class="easyui-dialog form-horizontal" role="form" title="新增银信用户" style="width:500px;height:500px;"-->
<!--	 data-options="resizable:true,modal:true,closed:true">-->
<!--	-->
<!--</div>-->


</body>

<script>
    /**
     * 新增银信用户
     */
    $('#ff').form({
        url: 'add_yx_user_info',
        onSubmit: function () {
            // do some check
            // return false to prevent submit;
        },
        success: function (data) {
            data = JSON.parse(data);
            if (data.code == 0 || data.code == 2) {
                $.messager.show({
                    title: '提示',
                    msg: data.msg
                });
            } else {
                $.messager.show({
                    title: '提示',
                    msg: data.msg
                });
                $('#ff').form('clear');
            }
        }
    });

    function add_yx_user_info() {
        $('#ff').submit();
    }

    /**
     * 重置
     */
    function add_yx_user_info_reset() {
        $('#ff').form('clear');
    }

    /**
     * 日期
     */
    $('#reg_time').datebox({
        required: true
    });
    $('#ctime').datebox({
        required: true
    });
    //layui文件上传--excel--新增用户
    layui.use('upload', function(){
        var upload = layui.upload;
        //执行实例
        var uploadInst = upload.render({
            elem: '#test'
            ,url: 'uploadify_excel'
            ,accept: 'file' //普通文件
            ,done: function(res){
                $.messager.show({
                    title: '提示',
                    msg: res.msg
                });
            }
        });
    });
    //download
    function download_excel() {
        window.location.href = 'http://120.26.89.131:60523/fms/index.php/Tools/download_word?id='+11;
    }
</script>

</body>