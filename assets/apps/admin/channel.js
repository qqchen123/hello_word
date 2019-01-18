define(function(require,exports){
    var validator=require('apps/validator').validator;

    $('#gostep8').on('click',function(e){
        e.preventDefault();
        var fail=false;
        var fmsg=[];

        $('[_valid]').each(function(){
            var _required=$(this).attr('_required') != 'undefined';
            var _validtor=$(this).attr('_valid');
            var _msg = $(this).attr('_msg');
            if(_validtor && typeof validator[_validtor]=='function' && !validator[_validtor]($(this).val()) || this.tagName=='SELECT'&& _required && $(this).val()=='' ) {
                fail=true;
                fmsg.push(_msg);
            }
        });

        if(!$('[name=dc_jgid]').val()){
            fail=true;
            fmsg.push('机构ID不能为空');
        }

        if(fail){
            top.modalbox.alert(fmsg.join('<br/>'));
            return false;
        }
        $('form').submit();
    });
});