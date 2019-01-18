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
    input {
        border-radius: 5px;
        width: 200px;
    }
</style>
<div style="padding:10px; margin: 30px 0 0 50px;">
    <form id="ff" method="post">
        <div>
            <label for="name">姓名:</label>
            <input class="easyui-validatebox" style="margin-left: 56px" type="text" name="username"
                   data-options="required:true"/>
        </div>
        <div>
            <label for="name">账号:</label>
            <input class="easyui-validatebox" style="margin-left: 56px" type="text" name="userid"
                   data-options="required:true"/>
        </div>
        <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" onclick="do_reset_passwd()"
           style="width:90px;margin-top: 10px;">提交</a>
    </form>
    <p>密码重置后新密码为：123456</p>
</div>
<script>
    function do_reset_passwd() {
        $('#ff').form('submit', {
            url: "<?php echo site_url().'/home/rest_pwd'; ?>",
            dataType: 'json',
            success: function (result) {
                var result = eval("(" + result + ")");
                if (result.ret == 0) {
                    $.messager.show({
                        title: '提示',
                        msg: result.message
                    });

                } else {
                    $.messager.show({
                        title: '提示',
                        msg: result.message
                    });
                    $('#ff').form('reset');
                }
            }
        });
    }
</script>
</body>
</html>