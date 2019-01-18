<?php tpl("admin_applying") ?>
<style type="text/css">
	#upload:hover{
		cursor: pointer;
	}
	#next-page:hover{
		cursor: pointer;
	}
</style>
<div>
	<div>
		<div>身份证正面：</div>
		<div><input type="file" name="idcardfront"></div>
	</div>
	<div>
		<div>身份证反面：</div>
		<div><input type="file" name="idcardback"></div>
	</div>
	<div>
		<span id="upload">上传</span>
		<span id="next-page">下一步</span>
	</div>
</div>
<!DOCTYPE HTML PUBLIC>
<html>
 <head>
  <meta charset="utf-8">
  <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <title>使用html5 FileReader获取图片，并异步上传到服务器(not iframe)</title>
 
  <style type="text/css">
    body{margin: 0px; background:#f2f2f0;}
    p{margin:0px;}
    .title{color:#FFFF00; background:#000000; text-align:center; font-size:24px; line-height:50px; font-weight:bold;}
    .file{position:absolute; width:100%; font-size:90px;}
    .filebtn{display:block; position:relative; height:110px; color:#FFFFFF; background:#06980e; font-size:48px; line-height:110px; text-align:center; cursor:pointer; border: 3px solid #cccccc;}
    .filebtn:hover{background:#04bc0d;}
    .showimg{margin:10px auto 10px auto; text-align:center;}
  </style>
 
  <script type="text/javascript">
  	var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
  window.onload = function(){
 
    // 选择图片
    document.getElementById('img').onchange = function(){
 
        var img = event.target.files[0];
 
        // 判断是否图片
        if(!img){
            return ;
        }
 
        // 判断图片格式
        if(!(img.type.indexOf('image')==0 && img.type && /\.(?:jpg|png|gif)$/.test(img.name)) ){
            alert('图片只能是jpg,gif,png');
            return ;
        }
 
        var reader = new FileReader();
        reader.readAsDataURL(img);
 
        reader.onload = function(e){ // reader onload start
            // ajax 上传图片
            $.post(
            	AJAXBASEURL + "/client/ClientPreOrder/uploadidcard", 
            	{
            		img: e.target.result,
            		type: 'idcardfront',
            		idnumber: $('#idnumber').val()
            	},function(ret){
                if(ret.img!=''){
                    alert('upload success');
                    $('#showimg').html('<img src="' + ret.img + '">');
                }else{
                    alert('upload fail');
                }
            },'json');
        } // reader onload end
    }
 
  }
  </script>
 
 </head>
 
 <body>
  <p class="title">使用html5 FileReader获取图片，并异步上传到服务器(not iframe)</p>
  <p><input type="file" class="file" id="img"><label class="filebtn" for="img" title="JPG,GIF,PNG">请选择图片</label></p>
  <p class="showimg" id="showimg"></p>
 </body>  
</html>
