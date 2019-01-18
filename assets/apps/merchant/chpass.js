define(function (require) {
    $('form').on('submit',function(e){
        e.preventDefault();
        var opass = $('#opass').val().trimSpace();
        var adminpass = $('#adminpass').val().trimSpace();
        var confirmpass = $('#confirmpass').val().trimSpace();
        var opassError = '';
        var adminPassError = '';
        var adminCpasError = '';

        if(!/^[a-zA-Z0-9_&]{6,15}$/.test(opass)){
            opassError = "密码只能为6~15位的数字、字母或者_&的组合";
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

        $('#adminpass').next().children().text(adminPassError);
        $('#confirmpass').next().children().text(adminCpasError);
        $('#opass').next().children().text(opassError);

        if(adminCpasError || adminCpasError || opassError) return;

        $.post(
            PAGE_VAR.SITE_URL+'Home/savepass',
            $('form').serialize(),
            function (response) {
                if(response.responseCode==200){
                    layer.msg("密码已修改",{icon:1},function () {
                        location.reload()
                    });
                    return ;
                }
                layer.msg(response.responseMsg,{icon:2});
            },'json'
        );
    });
});