try{
    seajs.config({
        base: '/assets/',
        //plugins:['shim'],
        alias: {
            "jquery":"lib/js/jquery.min.js",
            "boot":'lib/js/bootstrap.min.js',
            "easyui_zh":'lib/js/easyui-lang-zh_CN.js',
            "easyui":"lib/js/jquery.easyui.min.js",
            "editormin":"lib/js/kindeditor-min.js",
            "editor":'lib/js/kindeditor.js',
            'layer':'lib/js/layer/layer.js',
            "ace-ele":'lib/js/ace-elements.min.js',
            "ace":'lib/js/ace.min.js',
            "acextra":'lib/js/ace-extra.min',
            "bootbox":'lib/js/bootbox.min.js'
        },
        preload:['jquery','layer'],
        map: [[/^(.*\.(?:css))(.*)$/i, '$1?v='+Math.random()]]
    });
}catch(e){
    if(typeof console == 'undefined'){
        console = {
            log: function () {}
        }
    }
}