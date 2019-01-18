<script src="/assets/daifu/js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/daifu/css/trsaction_mg2.css">
<link rel="stylesheet" href="/assets/layui/layui.css">
<script type="text/javascript" src="/assets/layui/layui.js"></script>

<div>
	<div class="money">余额： 240536.97</div>
	<div class="file_css">
		上传代付文件
<!--        <input type="file" class="filecss file_band">-->
        <button type="button" class="layui-btn" id="test1">
            <i class="layui-icon">&#xe67c;</i>excel上传
        </button>
	</div>
	<a href="javascript:void(0)" onclick="checkv()">
		<button class="query">
            上 传
        </button>

	    <lable class="shibai">上传失败</lable>
   </a>
</div>

<script type="text/javascript">
  function checkv() {
  	if ($('.file_band').val() == null || $('.file_band').val() == '') {
	       	   $('.shibai').show();
	       } else if($('.file_band').val() != null) {
	       	   $('.shibai').hide();
               window.location.href="<?php echo site_url('/');?>welcome/test15";
	       }
	}
  //layui文件上传--excel
  layui.use('upload', function() {
      var upload = layui.upload;
      //执行实例
      var uploadInst = upload.render({
          elem: '#test1'
          , url: 'excel_uploadify_file'
          , accept: 'file' //普通文件
          , done: function (res) {
              if (res.code==1){
                  alert(res.message);
              }
          }
      });
  });
</script>
