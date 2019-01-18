<?php tpl("admin_header") ?>
<body>
<div class="page-content">
    <div class="page-header">
        <h1 class="bigger-150">
            出错了
        </h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <p>
                            <?php echo $msg?>
                        </p>
                        <?php echo anchor($return,'返回')?>
                    </div><!-- /.table-responsive -->
                </div><!-- /span -->
            </div><!-- /row -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
</body>
</html>
