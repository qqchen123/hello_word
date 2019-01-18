define(function (require) {
    var validatorObj = require("apps/validator.js");
    var validator = validatorObj.validator;

    $('.validrange').on('change',function(){
        var _targetText = $(this).closest('div.col-sm-9').prev().text();

        if(!validator.validRate(this.value)){
            return layer.msg(_targetText+'设置错误',{icon:2});
        }

        this.value = validator.validRate(this.value);
    });


    $('form').on('submit',function(e){
        e.preventDefault();
        var merchant = $('#merchant').val().trimSpace();
        var address = $('#address').val().trimSpace();
        var contactor = $('#contactor').val().trimSpace();
        var mobile = $('#mobile').val().trimSpace();
        var pass = $('#pass').val();
        var confirmpass = $('#confirmpass').val();
        var commission = $('#commission').val();
        var rebate = $('#rebate').val();
        var merchantError = '';
        var addressError = '';
        var contactorError = '';
        var mobileError = '';
        var passError = '';
        var confirmpassError = '';
        var commissionError='';
        var rebateError='';

        if(!merchant || validator.isForbidden(merchant)){
            merchantError = '商户名不能为特殊字符';
        }
        if(!address || validator.isForbidden(address)){
            addressError = '商户地址不能为特殊字符';
        }
        if(!contactor || validator.isForbidden(contactor)){
            contactorError = '联系人不能为特殊字符';
        }
        if(mobile && !validator.isPhone(mobile)){
            mobileError = '联系电话错误';
        }
        if(!validator.validRate($('#commission').val())){
            commissionError='佣金比率设置错误';
        }

        if(!validator.validRate($('#rebate').val())) {
            rebateError='返利比率设置错误';
        }

        if(!/^[a-zA-Z0-9_&]{6,15}$/.test(pass)){
            passError = "密码只能为6~15位的数字、字母或者_&的组合";
        }

        if(!/^[a-zA-Z0-9_&]{6,15}$/.test(confirmpass)){
            confirmpassError = "密码只能为6~15位的数字、字母或者_&的组合";
        }

        if(pass != confirmpass){
            passError = confirmpassError = "两次输入密码不一致";
        }


        $('#merchant').next().children().text(merchantError);
        $('#address').next().children().text(addressError);
        $('#contactor').next().children().text(contactorError);
        $('#mobile').next().children().text(mobileError);
        $('#pass').next().children().text(passError);
        $('#confirmpass').next().children().text(confirmpassError);
        $('#commission').parent().next().children().text(commissionError);
        $('#rebate').parent().next().children().text(rebateError);

        if(!$('#documentid').length){
            if(merchantError || passError || confirmpassError || mobileError || commissionError || rebateError) return;
            $.post(
                PAGE_VAR.SITE_URL+'Merchant/addMers',
                $('form').serialize(),
                function (response) {
                    if(response.responseCode==200){
                        return window.location.href=PAGE_VAR.SITE_URL+'Merchant';
                    }
                    layer.msg(response.responseMsg,{icon:2});
                },'json'
            );

            return;
        }

        if(merchantError || pass && passError || confirmpass && confirmpassError || mobileError || commissionError || rebateError) return;
        $.post(
            PAGE_VAR.SITE_URL+'Merchant/save',
            $('form').serialize(),
            function (response) {
                if(response.responseCode==200){
                    return window.location.href=PAGE_VAR.SITE_URL+'Merchant';
                }
                layer.msg(response.responseMsg,{icon:2});
            },'json'
        );

        return;
    });
});