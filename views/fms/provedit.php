<?php tpl("admin_header") ?>
<body>
<style>
	.proverow{
		border:1px solid #d5d5d5;
		width:200px;
		height:300px;
		margin-top: 10px;
		margin-left: 10px
	}
	.provec{
		border-bottom:1px solid #d5d5d5;
		width:200px;
		height:260px;
		text-align: center;
		line-height: 260px;
	}
	.provec img{
		width:auto;
		height:auto;
		max-width:100%;
		max-height:100%;
		margin-top: -4px;
	}
	.provec1{
		height:40px;
		text-align: center;
		line-height: 40px;
	}
	.provec1 a{
		
	}
</style>

<div class="page-content">
    <div class="page-header">
        <span class="bigger-150">
            图片管理
        </span>
    </div><!-- /.page-header -->

    <div class="row">
	 
                <div class="col-xs-12">
                    <form class="form-horizontal" role="form" method="post" action='<?php echo site_url('qiye/proveupfile')?>' enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-2">上传文件</label>

                            <div class="col-sm-9">
								<input type="hidden" name="idnumfile" value="<?=$idnumfile?>"/>
								<input type="hidden" name="type" value="<?=$type?>"/>
                                <input type="file" name="idnumimgd" id="idnumimgd" class="col-sm-4">
                                <span class="help-inline col-xs-12 col-sm-7 text-danger">
                                                <span class="middle"></span>
                                            </span>
											 <button class="btn btn-info btn-sm" type="submit" style="margin-right: 20px">
                                    <i class="icon-ok bigger-110"></i>
                                    提交
                                </button>
                            </div>
                        </div>
                        <div class="clearfix">
                            
                        </div>
                    </form>
                </div><!-- /span -->
           <!-- /row -->
	<?php foreach($filearr as $k=>$v){
		if($k>1){
		?>
        <div class="col-xs-12 proverow">
            <div class="row provec" ><img src="<?php echo 'http://'.$fwqip.'/upload/'.$idnumfile.'/'.$type.'/'.$v;?>"/></div>
			<div class="row provec1" >
				<a href="<?php echo site_url('qiye/provedelete/'.$idnumfile.'/'.$type.'/'.$v)?>">删除</a>
            </div>
        </div>
		<?php }}?>
    </div>
</div>
<script>

$.ajax({    
     url : "http://localhost:8080/STS/rest/user",    
     type : "POST",    
     data : $( '#postForm').serialize(),    
     success : function(data) {    
          $( '#serverResponse').html(data);    
     },    
     error : function(data) {    
          $( '#serverResponse').html(data.status + " : " + data.statusText + " : " + data.responseText);    
     }    
});  
</script>
</body>
</html>
