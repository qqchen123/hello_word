define(function(require,exports){
    var validator=require('apps/validator').validator;
    $('button').on('click',function (e) {
        e.preventDefault();
    })
    $('#gostep8').on('click',function(){
        var dname=$('#dname').val();
        var dcode=$('#dcode').val();
        var dnmenid=$('#dumenid').val();
        var buway=$('#buway').val();
        var brate=$('#drate').val();
        var bfee=$('#bfee').val();
        var bservfee=$('#bservfee').val();
        var bduring=$('#bduring').val();
        var evidence=[];
        var spid=$('#spid').val();
        console.log(buway);
        console.log($('#buway').val());
        $('input[name=evidence]:checked').each(function () {
            evidence.push(this.value);
        });
        evidence=evidence.join('|');

        if(!validator.isChineseORAlphar(dname)){
            top.modalbox.alert('产品名称不能为空');
            return;
        }
        if(!validator.isAlphaOrNumbers(dcode)){
            top.modalbox.alert('产品编号不能为空，只能为字母和数字的组合');
            return;
        }
        if(!validator.isValidRate(brate)){
            top.modalbox.alert('产品年利率错误');
            return;
        }
        if (!validator.isValidRate(bfee)){
            top.modalbox.alert('技术费用输入错误');
            return;
        }
        if(!validator.isValidRate(bservfee)){
            top.modalbox.alert('服务费用输入错误');
            return;
        }
        if (!validator.isValidRate(bduring)){
            top.modalbox.alert('产品期限输入错误');
            return;
        }
        $.post(
            PAGE_VAR.SITE_URL+'sp/ajax_add_rec',
            {
                dname:dname,
                dcode:dcode,
                dnmeni:dnmenid,
                buway:buway,
                brate:brate,
                bfee:bfee,
                bservfee:bservfee,
                bduring:bduring,
                evidence:evidence,
                spid:spid
            },
            function (response) {
                if(response.responseCode!=200) {
                    top.modalbox.alert(response.responseMsg);
                }else {
                    top.modalbox.alert('成功',function () {
                        if($('#spid').length){
                            window.location.href=PAGE_VAR.SITE_URL+'sp/query'
                        }else
                            window.location.reload();
                    });
                }
            },'JSON'
        );
    });
});