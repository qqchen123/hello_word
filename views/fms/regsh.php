<?php tpl("admin_header") ?>
<body>
<div class="page-content">
    <div class="page-header">
        <span class="bigger-150">
            注册审核
        </span>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <form class="form-horizontal" role="form" method="post" action="<?php echo site_url('qiye/regshdo')?>">
                        <div class="form-group">
							<input type="hidden" name="step" value="<?=$step ?>" />
							<input type="hidden" name="status" value="<?=$status ?>" />
							<input type="hidden" name="fuserid" value="<?=$fuserid ?>" />
							
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-2">审核意见</label>

                            <div class="col-sm-9">
                                <textarea class="col-sm-7" name="comment" ></textarea>
								
                                <span class="help-inline col-xs-12 col-sm-7 text-danger">
                                                <span class="middle"></span>
                                            </span>
                            </div>
                        </div>

                        

                        

                        <div class="clearfix form-actions">
                            <div class="col-md-12">
                                <button class="btn btn-info btn-sm" type="submit" style="margin-right: 20px">
                                    <i class="icon-ok bigger-110"></i>
                                    提交
                                </button>
                                <button class="btn  btn-sm" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    重置
                                </button>
                            </div>
                        </div>
                    </form>
                </div><!-- /span -->
            </div><!-- /row -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<script>

</script>
</body>
</html>
