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
    input{
        border-radius: 5px;
        width: 200px;
    }
</style>
<div  style="padding:10px; margin: 30px 0 0 50px;">
    <form id="ff" method="post">
        <div>
            <label for="name">原密码:</label>
            <input class="easyui-validatebox" style="margin-left: 56px" type="text" name="oldpassword" data-options="required:true" />
        </div>
        <div style="margin-top: 10px;">
            <label for="email">新密码:</label>
            <input class="easyui-validatebox" style="margin-left: 56px" type="text" name="newpassword" data-options="required:true,validType:['length[1,20]']" />
        </div style="margin-top: 10px;">
        <div style="margin-top: 5px;">
            <label for="email">再次输入新密码:</label>
            <input class="easyui-validatebox" type="text" name="newpassword2" data-options="required:true,validType:['length[1,20]']" />
        </div>
        <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" onclick="dochangepasswd()" style="width:90px;margin-top: 10px;">提交</a>
    </form>
</div>
<script>
    function dochangepasswd() {
        $('#ff').form('submit', {
            //url:"<?php //echo site_url() . '/changepass/change_password'; ?>//",
            url:"<?php echo site_url() . '/home/change_password'; ?>",
            dataType: 'json',
            success: function(result) {
                var result = eval("(" + result + ")");
                if (result.ret == 0) {
                    $.messager.show({
                        title: '提示',
                        msg: result.message
                    });
                } else {
                    $('#jigou-dlg').dialog('close');
                    $.messager.show({
                        title: '提示',
                        msg: result.message
                    });
                }
            }
        });
    }
</script>
</body>
</html>