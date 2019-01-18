define(function(require,exports){
   // var validator=require('apps/validator').validator;

    $('#gostep3').on('click',function(e){
        e.preventDefault();
        var name=$('#name').val();
        var idnumber=$('#idnumber').val();
        var utype=$('#utype option:selected').val();
        var idnumvalid=$('#idnumvalid').val();
        var idnumaddress=$('#idnumaddress').val();
        var companyname=$('#companyname').val();
       
       

        if(!/^[\u4E00-\u9FA5A-Za-z]+$/.test(name)){
            layer.alert('姓名不能为空',{icon:2});
            return;
        }
        if(!/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(idnumber)){
            top.modalbox.alert('身份证号不能为空，只能为字母和数字的组合');
            return;
        }
		if(utype == '00'){
			if(!/^[\u4E00-\u9FA5A-Za-z]+$/.test(idnumaddress)){
				 layer.alert('公司地址格式有误',{icon:2}); 
            return false;
				top.modalbox.alert('公司地址格式有误',function(){});
				return;
			}
			if(!/^[\u4E00-\u9FA5A-Za-z]+$/.test(companyname)){
				top.modalbox.alert('格式有误',function(){});
				return;
			}
		}
		 if(getimgsize('idnumimgu')>1050000){
			top.modalbox.alert('图片不能大于1M(正面)',function(){});
			return;
		}
		if(getimgsize('idnumimgd')>1050000){
			top.modalbox.alert('图片不能大于1M(反面)',function(){});
			return;
		}
        $('form').submit();
    });
	
});

