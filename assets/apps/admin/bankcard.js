define(function(require,exports){
   // var validator=require('apps/validator').validator;

    $('#gostep3').on('click',function(e){
        e.preventDefault();
        var name=$('#name').val();
        var idnumber=$('#idnumber').val();
        var mobile=$('#mobile').val();
        
        var bankname=$('#bankname').val();
        var bankcardNo=$('#bankcardNo').val();
       
        if(!/^[\u4E00-\u9FA5A-Za-z]+$/.test(name)){
            layer.alert('姓名不能为空',{icon:2});
            return;
        }
        if(!/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(idnumber)){
            
			layer.alert('身份证号不能为空，只能为字母和数字的组合',{icon:2});
            return;
        }
		
		if(!/^[\u4e00-\u9fa5]+$/.test(bankname)){
			layer.alert('开户行名称错误',{icon:2}); 
			return false;
		}
		if(!/^([1-9]{1})(\d{15}|\d{18})$/.test(bankcardNo)){
			layer.alert('银行卡有误',{icon:2}); 
			return;
		}
		if(!/^1[3456789]\d{9}$/.test(mobile)){
			layer.alert('手机号有误',{icon:2}); 
			return;
		}
		
		if(getimgsize('bankcardimgu')>1050000){
			top.modalbox.alert('图片不能大于1M(正面)',function(){});
			return;
		}
		if(getimgsize('bankcardimgd')>1050000){
			top.modalbox.alert('图片不能大于1M(反面)',function(){});
			return;
		}
        $('form').submit();
    });
	
});

