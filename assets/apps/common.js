define(function (require) {
    $('.editAction').on('click',function(){
        var baseTar = $(this).closest('div').data('info').base;
        var documentId = $(this).attr('_tagid');
        window.location.href=PAGE_VAR.SITE_URL+baseTar+'/edit/'+documentId;
    });

    $('.deleteAction').on('click',function(){
        var baseTar = $(this).closest('div').data('info').base;
        var documentId = $(this).attr('_tagid');
        layer.confirm('您确定要删除该记录吗？',function(){
            $.getJSON(
                PAGE_VAR.SITE_URL+baseTar+'/delete/'+documentId,
                function (response) {
                    if(response.responseCode==200){
                        return location.reload();
                    }

                    layer.msg(response.responseMsg,{icon:2})
                }
            )
        });
    });
});