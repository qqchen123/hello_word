
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>php-ajax无刷新上传(带进度条)demo</title>
<meta name="description" content="" />
<meta name="viewport" content="width=device-width , initial-scale=1.0 , user-scalable=0 , minimum-scale=1.0 , maximum-scale=1.0" />

<script type='text/javascript' src='/assets/lib/js/jquery.min.js'></script>
<script type='text/javascript' src='/assets/lib/js/jquery.form.js'></script>
<link href="/assets/lib/css/style1.css" type="text/css" rel="stylesheet"/>

</head>
<script>
    var PAGE_VAR = {
        SITE_URL:'<?php echo site_url('/')?>',
        BASE_URL:'<?php echo base_url('/')?>',
    }
    String.prototype.trimSpace = function(){
        return this.replace(/\s/g,'');
    };
</script>
<body>
<div style="width:500px;margin:10px auto; border:solid 1px #ddd; overflow:hidden; ">
  <form id='myupload' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="type" value="身份证">
    <input type="hidden" name="filetype" value="照片件">
    <input type="hidden" name="preorder" value="1810301726test001">
    <input type="file" id="uploadphoto" name="uploadfile" multiple="multiple" value="请点击上传图片"  style="display:none;" />
  </form>
  <div class="imglist"> </div>
  <p class="res"></p>
  <div class="progress">
    <div class="progress-bar progress-bar-striped" ><span class="percent">50%</span></div>
  </div>
  <a href="javascript:void(0);" onclick="uploadphoto.click()" class="uploadbtn">点击上传文件</a>
<script type="text/javascript">
$(document).ready(function(e) {
   var progress = $(".progress"); 
   var progress_bar = $(".progress-bar");
   var percent = $('.percent');
   $("#uploadphoto").change(function(){
  	 $("#myupload").ajaxSubmit({ 
       url: PAGE_VAR.SITE_URL + "/client/ClientPreOrder/getuploadpic", // 要调用的控制器方法
  		dataType:  'json', //数据格式为json 
  		beforeSend: function() { //开始上传 
  			progress.show();
  			var percentVal = '0%';
  			progress_bar.width(percentVal);
  			percent.html(percentVal);
  		}, 
  		uploadProgress: function(event, position, total, percentComplete) { 
  			var percentVal = percentComplete + '%'; //获得进度 
  			progress_bar.width(percentVal); //上传进度条宽度变宽 
  			percent.html(percentVal); //显示上传进度百分比 
  		}, 
  		success: function(data) {
			 
			if(data.status == 1){
				var src = PAGE_VAR.BASE_URL + data.url;  
				var attstr= '<img src="'+src+'">';  
				// $(".imglist").append(attstr);
				$(".res").html("上传图片"+data.name+"成功，图片大小："+data.size+"K,文件地址："+data.url);
			}else{
				$(".res").html(data.content);
			}
  			progress.hide();		
  		}, 
  		error:function(xhr){ //上传失败 
  		   alert("上传失败"); 
  		   progress.hide(); 
  		} 
  	}); 
   });

});
</script>
</body>
</html>