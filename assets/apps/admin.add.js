define(function (require) {
    $('form').on('submit',function(e){
        e.preventDefault();
        var adminname = $('#adminName').val().trimSpace();
        var adminpass = $('#adminpass').val().trimSpace();
        var confirmpass = $('#confirmpass').val().trimSpace();
        var adminNameError = '';
        var adminPassError = '';
        var adminCpasError = '';
        if(!/^[a-zA-Z0-9_&]{3,15}$/.test(adminname)){
            adminNameError = '登录名只能为3~8位的数字+字母的组合';
        }

        if(!/^[a-zA-Z0-9_&]{6,15}$/.test(adminpass)){
            adminPassError = "密码只能为6~15位的数字、字母或者_&的组合";
        }

        if(!/^[a-zA-Z0-9_&]{6,15}$/.test(confirmpass)){
            adminCpasError = "密码只能为6~15位的数字、字母或者_&的组合";
        }

        if(adminpass != confirmpass){
            adminPassError = "两次输入密码不一致";
        }

        $('#adminName').next().children().text(adminNameError);
        $('#adminpass').next().children().text(adminPassError);
        $('#confirmpass').next().children().text(adminCpasError);

        if(adminNameError || adminPassError || adminCpasError) return;

        $.post(
            PAGE_VAR.SITE_URL+'administrator/addUsr',
            $('form').serialize(),
            function (response) {
                if(response.responseCode==200){
                    return window.location.href=PAGE_VAR.SITE_URL+'administrator';
                }
                layer.msg(response.responseMsg,{icon:2});
            },'json'
        );
    });
});