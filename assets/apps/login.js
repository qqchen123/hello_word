/**
 * Created by Admin on 2016/5/10.
 */
define(function (require) {
    function show_box(id) {
        jQuery('.widget-box.visible').removeClass('visible');
        jQuery('#' + id).addClass('visible');
    }
    $("#loginpage2").keydown(function() {
    if (event.keyCode == "13") {//keyCode=13是回车键
        $('#loginBtn').click();
      }
   });    
    jQuery('#loginBtn').on('click', function () {
        var uname = $('#loginUsrname');
        var upass = $('#loginUsrpass');
        uname.val(uname.val().trimSpace());
        upass.val(upass.val().trimSpace());
        if(!uname.val() || !upass.val()){
            layer.msg('用户名或者密码错误',{icon:2});
            return;
        }
        jQuery.getJSON(
            PAGE_VAR.SITE_URL+'/Auth/checkPass',
            {uname:uname.val(),upass:upass.val(),_:Math.random()},
            function(data){
                if(data.responseCode==200){
                    location.href=PAGE_VAR.SITE_URL;
                    return;
                }
                layer.msg(data.responseMsg,{icon:2});
            }
        );
    });
});