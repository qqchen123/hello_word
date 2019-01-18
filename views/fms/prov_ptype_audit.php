<?php tpl("admin_header") ?>
<body>
<style>
    .panel.window,.window-mask{position: fixed}
    .window-shadow{opacity: 0}
    a{cursor: pointer}
    .headtitle{padding-left: 15px}
    .sep{padding:0 5px}
    .proverow {
        border: 1px solid #d5d5d5;
        width: 200px;
        margin-top: 10px;
        margin-left: 10px
    }

    .provec {
        width: 200px;
        height: 260px;
        text-align: center;
        line-height: 260px;
    }

    .provec img {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        margin-top: -4px;
    }

    .provec1 {
        height: 40px;
        text-align: center;
        line-height: 40px;
    }
    .input-group select{height: 34px}
    .provec1 a {

    }
</style>

<div class="page-content">
    <div class="page-header">
        <span class="bigger-150">
            法人/自然人资料 <a class="btn btn-default btn-small" onclick="location.href='<?php echo site_url('Proof')?>'">返回</a>
        </span>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
                <div class="no-padding col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">身份证</h4>
                            <span class="headtitle">号码：<?php echo isset($uinfo['idnumber']) ? $uinfo['idnumber']:"" ?></span>
                            <span class="sep">|</span>
                            <span>设定文件数：</span>
                            <span id="idcard_limit"></span>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="piccon_idcard">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^idcard_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="no-padding col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">户口本</h4>
                            <span class="headtitle">设定文件数:</span>
                            <span id="persons_limit"></span>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="piccon_persons">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^persons_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="no-padding col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">结婚证明</h4>
                            <span class="headtitle">设定文件数:</span>
                            <span id="wedding_limit"></span>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="piccon_wedding">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^wedding_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="no-padding col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">银行流水</h4>
                            <span class="headtitle">设定文件数:</span>
                            <span id="bank_limit"></span>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="piccon_bank">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^bank_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="no-padding col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">征信报告</h4>
                            <span class="headtitle">设定文件数:</span>
                            <span id="credit_limit"></span>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="piccon_credit">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^credit_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="no-padding col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">法人签字照片</h4>
                            <span class="headtitle">设定文件数:</span>
                            <span id="signature_limit"></span>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="piccon_signature">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^signature_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="no-padding col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">法人实名认证手机号截图</h4>
                            <span class="headtitle">设定文件数:</span>
                            <span id="relvalid_limit"></span>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="piccon_relvalid">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^relvalid_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
        </div><!-- /span -->
    </div>
    <hr />
    <div class="col-xs-12">

    </div><!-- /span -->
    <div>
        <a class="btn btn-default btn-small" onclick="location.href='<?php echo site_url('Proof')?>'">返回</a>
    </div>
</div>
<script>
    function complete(code, msg) {
        var cont='';
        if(arguments.length==3) cont=arguments[2];
        if(code==200)
            $('#piccon_'+cont).append($(msg))
    }
    $(function () {
        var selected=<?php echo $limit['p_val']==null?"''":$limit['p_val']?>;
        selected= selected?JSON.parse(selected):{};
        for(var key in selected){
            $('#'+key).text(selected[key]);
        }
    })
</script>
</body>
</html>