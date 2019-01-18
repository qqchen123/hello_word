define(function(require){
    require('boot');
    require('acextra');
    require('ace');
    require('bootbox');

    var ele = '';
    var tabCounter = 0;
    var iframeHeight = $('html').height() - $('#navbar').outerHeight() - $('#navTabs').outerHeight()-80;
    // console.log(iframeHeight);
    var timeoutHandler = null;
    function setFrame() {
        $('[id^=dom_tab_]').height(iframeHeight);
        //var domId = '';
        //if(arguments.length) {
        //    domId = arguments[0];
        //    $('#pageContainer'+domId).height(iframeHeight);
        //    return;
       // }
        //$('[id^=pageContainer]').each(function (idx,frmObj) {
        //    $(frmObj).height(iframeHeight);
        //})
    }

    $(window).resize(function () {
        iframeHeight = $('html').height() - $('#navbar').outerHeight() - $('#navTabs').outerHeight()-80;
        // console.log(iframeHeight);
        setFrame();
    });

    function closeDialog() {
        if(arguments.length&&arguments[0]==1){
            pageContainer.window._frameEvents.fire('closeDialog');
            $('#dialog').children().remove().end().html('内容载入中... ...');
            ele == '_frame' ? ($('#dialog_frame').attr('src','')&&dialog_frame.document.write('')) : '';
            return ;
        }
        $('.bootbox-close-button.close').click();
    }

    function showDialog($url,$param, $options,ifFrame) {
        $options = $options || {};
        ele = (typeof ifFrame != 'undefined')&&ifFrame ? '_frame' : '';
        try {
            bootbox.dialog({
                title:$options.title || '',
                message:ele ? $('#dialog_target_frame').clone().show().find('#dialog_frame').attr('src',$url+'?'+ $.param($param)) : $('<div></div>').text('页面加载中... ...').load($url,$param,function(){
                    var wrapper = $(this).parent();
                    setTimeout(function(){
                        wrapper[0].scrollHeight>(wrapper.height()+60) && wrapper.css('overflow','auto');
                    },500);

                }),
                width: $options.height || 533,
                height: $options.height || 300,
                animate:!ele
            });
        } catch (e) {}
    }
    //弹出模态框
    var modalbox = {
        alert:function(msg){
            if(jQuery('.modal-dialog').length){
                return;
            }
            if(arguments.length>1 && typeof arguments[1] =='function'){
                bootbox.alert(msg,arguments[1]);
            }else{
                bootbox.alert(msg);
            }
        },
        confirm:function(msg,callback){
            if(typeof arguments[1] == 'function'){
                bootbox.confirm(msg,callback);
            }
        },
        showMsg:function(msg){
            $('#msg-help-block-content').text(msg);
            $('#msg-help-block').animate({top:0,opacity:1});
            setTimeout(function(){
                $('#msg-help-block-content').text('');
                $('#msg-help-block').animate({top:-100,opacity:0});
            },3000);
        },
    };

    jQuery('#logoutBtn').on('click', function () {
        $.getJSON(
            'logout',
            function (data) {
                if(data.ret)
                    location.href = data.msg;
            }
        )
    });

    jQuery('body').delegate('.ace_tabs','click',function(e){
        //e.preventDefault();
        if($(this).is('.dropdown-toggle')) return;
        var target = $(this).attr('_href');
        var title = $(this).text();
        var domId='dom_tab_'+target;
        if($('[_tabid='+domId+']').length) {
            $('[_tabid='+domId+']').is('.active') ? $('#'+domId+" iframe")[0].contentWindow.location.reload(): $('[_tabid='+domId+'] a').click();
            return;
        };
        if(tabCounter>=12){
            modalbox.alert('请先关闭一些窗口再操作');
            return;
        }
        target = target.replace('-','/');
        var $liItem = $('<li _tabid="'+domId+'" class="active">\
                <a data-toggle="tab" href="#'+domId+'" style="padding-right: 20px">'+title+'</a>\
                <i class="icon-remove grey" data-for="'+domId+'"></i>\
            </li>')
        var $ContentItem =$('<div id="'+domId+'" class="tab-pane in active">\
                <iframe src="'+(PAGE_VAR.SITE_URL+target)+'" id="pageContainer_'+domId+'" frameborder="0" scrolling="auto" style="width: 100%;height:calc(100% - 50px);"></iframe>\
            </div>');

        $('#navTabs').children().removeClass('active').end().append($liItem);
        $('#navTabContent').children().removeClass('active').end().append($ContentItem);
        setFrame('_'+domId);
        tabCounter++;

    });

    jQuery('body').delegate('#navTabs li','dblclick',function () {
        var tabId=$(this).attr('_tabid');
        $('#'+tabId+" iframe")[0].contentWindow.location.reload()
    })
    jQuery('body').delegate('#navTabs .icon-remove','click',function () {
        var domId = $(this).data('for');
        if(!domId) return;

        var element =$('[_tabid='+domId+']');
        if(element.is('.active')){
            if(element.prev().find('a').length)
                element.prev().find('a').click();
            else
                element.next().find('a').click();
        }
        element.remove();
        $('#'+domId).remove();
        tabCounter--;
    });

    jQuery(function ($) {
        setFrame();
        $('.nav-list a:not(.dropdown-toggle)').on('click',function(){
            var parList = $(this).parents('ul.submenu').prev();
            var path = [];
            path.push('<li><i class="icon-home home-icon"></i><span>首页</span></li>');
            if(parList&&$(parList[0]).text().length){
                path.push('<li class="active"><span>'+$(parList[0]).text()+'</span></li>');
            }
            path.push('<li class="active"><span>'+$(this).text()+'</span></li>');
            $('#breadcrumbs').html(path.join(''));
        });

        //用户到NAV
        // $('.user-menu li').on('click', function () {
        //     var path = [];
        //     path.push('<li><i class="icon-home home-icon"></i><span>首页</span></li>');
        //     $(this).text().length&&path.push('<li class="active"><span>'+$(this).text()+'</span></li>');
        //     $('#breadcrumbs').html(path.join(''));
        // });

        $('#tasks input:checkbox').removeAttr('checked').on('click', function () {
            if (this.checked) $(this).closest('li').addClass('selected');
            else $(this).closest('li').removeClass('selected');
        });
        var browserVersion = navigator.userAgent.match(/MSIE (\d+)/);
        try{
            if(browserVersion[1]<9){
                $('#warningMsg').animate({top:0});
            }else{
                $('#warningMsg').remove();
            }
        }catch (e){}
    })
    window.modalbox = modalbox;
    window.showDialog = showDialog;
    window.closeDialog = closeDialog;

    //2018.7.13 新增
    function page_listening() {
        var len = $('#navTabs').children().length;
        // console.log('标签长度' + len);
        $('.nav-list').children('li:eq(0)').children('a').click();
    }
    page_listening();
});

