define(function (require) {
    $('form').on('submit',function(e){
        e.preventDefault();
        var adminpass = $('#adminpass').val().trimSpace();
        var confirmpass = $('#confirmpass').val().trimSpace();
        var adminPassError = '';
        var adminCpasError = '';

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

        $.post(
            PAGE_VAR.SITE_URL+'administrator/saveEdit',
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