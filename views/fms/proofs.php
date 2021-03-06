<?php tpl('admin_header')?>
<body>
<script src="/assets/lib/js/kindeditor/kindeditor-min.js"></script>
<script src="/assets/lib/js/kindeditor/lang/zh_CN.js"></script>
<link rel="stylesheet" href="/assets/lib/js/kindeditor/themes/default/default.css">
<link rel="stylesheet" href="/assets/lib/css/kindeditorreset.css">
<p><input type="text" id="url1" value="" /> <input type="button" id="image1" value="选择图片" />（网络图片 + 本地上传）</p>
<p><input type="text" id="url2" value="" /> <input type="button" id="image2" value="选择图片" />（网络图片）</p>
<p><input type="text" id="url3" value="" /> <input type="button" id="image3" value="选择图片" />（本地上传）</p>
<script>
    KindEditor.ready(function(K) {
        var editor = K.editor({
            allowFileManager : true
        });
        K('#image1').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    imageUrl : K('#url1').val(),
                    clickFn : function(url, title, width, height, border, align) {
                        K('#url1').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
        K('#image2').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    showLocal : false,
                    imageUrl : K('#url2').val(),
                    clickFn : function(url, title, width, height, border, align) {
                        K('#url2').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
        K('#image3').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    showRemote : false,
                    imageUrl : K('#url3').val(),
                    clickFn : function(url, title, width, height, border, align) {
                        K('#url3').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
    });
</script>
</body>
</html>