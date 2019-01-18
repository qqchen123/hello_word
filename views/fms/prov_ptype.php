<?php tpl("admin_header") ?>
<body>
<style>
    .panel.window,.window-mask{position: fixed}
    .window-shadow{opacity: 0}
    a{cursor: pointer}
    .proverow {
        border: 1px solid #d5d5d5;
        width: 200px;
        height: 300px;
        margin-top: 10px;
        margin-left: 10px
    }

    .provec {
        border-bottom: 1px solid #d5d5d5;
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
            <form class="form-horizontal" id="dataForm" role="form" method="post" action='<?php echo site_url('Proof/proveupfile') ?>'
                  enctype="multipart/form-data" target="_upframe">
                <div class="form-group col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">身份证</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4" style="margin-bottom: 10px">
                                    <span class="input-group-addon">身份证</span>
                                    <input type="text" class="col-sm-12" disabled value="<?php echo isset($uinfo['idnumber']) ? $uinfo['idnumber']:"" ?>">
                                </div>
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        文件数
                                    </span>
                                    <select name="idcard_limit" id="idcard_limit" class="form-control search-query">
                                        <option value="2">2</option>
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="idcard">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_idcard">
                                <?php foreach ($files as $k => $v) {
                                    if ($k > 1 && preg_match('/^idcard_/',$v)) {$a= pathinfo($v);
                                        ?>
                                        <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                            <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                            </div>
                                            <div class="row provec1">
                                                <a _href="<?php echo site_url('Proof/provedelete/' . $idnumber . '/' . $type . '/' . $v) ?>">删除</a>
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
                <div class="form-group col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">户口本</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        文件数
                                    </span>
                                    <select name="persons_limit" id="persons_limit" class="form-control search-query">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="persons">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_persons">
                                <?php foreach ($files as $k => $v) {
                                    if ($k > 1 && preg_match('/^persons_/',$v)) {$a= pathinfo($v);
                                        ?>
                                        <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                            <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                            </div>
                                            <div class="row provec1">
                                                <a _href="<?php echo site_url('Proof/provedelete/' . $idnumber . '/' . $type . '/' . $v) ?>">删除</a>
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
                <div class="form-group col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">结婚证明</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        文件数
                                    </span>
                                    <select name="wedding_limit" id="wedding_limit" class="form-control search-query">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="wedding">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_wedding">
                                <?php foreach ($files as $k => $v) {
                                    if ($k > 1 && preg_match('/^wedding_/',$v)) {$a= pathinfo($v);
                                        ?>
                                        <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                            <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                            </div>
                                            <div class="row provec1">
                                                <a _href="<?php echo site_url('Proof/provedelete/' . $idnumber . '/' . $type . '/' . $v) ?>">删除</a>
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
                <div class="form-group col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">银行流水</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        文件数
                                    </span>
                                    <select name="bank_limit" id="bank_limit" class="form-control search-query">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="bank">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_bank">
                                <?php foreach ($files as $k => $v) {
                                    if ($k > 1 && preg_match('/^bank_/',$v)) {$a= pathinfo($v);
                                        ?>
                                        <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                            <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                            </div>
                                            <div class="row provec1">
                                                <a _href="<?php echo site_url('Proof/provedelete/' . $idnumber . '/' . $type . '/' . $v) ?>">删除</a>
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
                <div class="form-group col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">征信报告</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        文件数
                                    </span>
                                    <select name="credit_limit" id="credit_limit" class="form-control search-query">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="credit">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_credit">
                                <?php foreach ($files as $k => $v) {
                                    if ($k > 1 && preg_match('/^credit_/',$v)) {$a= pathinfo($v);
                                        ?>
                                        <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                            <div class="row provec"><img
                                                        src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                            </div>
                                            <div class="row provec1">
                                                <a _href="<?php echo site_url('Proof/provedelete/' . $idnumber . '/' . $type . '/' . $v) ?>">删除</a>
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
                <div class="form-group col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">法人签字照片</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        文件数
                                    </span>
                                    <select name="signature_limit" id="signature_limit" class="form-control search-query">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="signature">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_signature">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^signature_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                            src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                                <div class="row provec1">
                                                    <a _href="<?php echo site_url('Proof/provedelete/' . $idnumber . '/' . $type . '/' . $v) ?>">删除</a>
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
                <div class="form-group col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">法人实名认证手机号截图</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        文件数
                                    </span>
                                    <select name="relvalid_limit" id="relvalid_limit" class="form-control search-query">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="relvalid">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_relvalid">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^relvalid_/',$v)) {$a= pathinfo($v);
                                            ?>
                                            <div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
                                                <div class="row provec"><img
                                                            src="<?php echo base_url() . '../upload/' . $idnumber . '/' . $type . '/' . $v; ?>"/>
                                                </div>
                                                <div class="row provec1">
                                                    <a _href="<?php echo site_url('Proof/provedelete/' . $idnumber . '/' . $type . '/' . $v) ?>">删除</a>
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
            </form>
        </div><!-- /span -->
        <!-- /row -->
    </div>
    <div>
        <a class="btn btn-default btn-small" onclick="location.href='<?php echo site_url('Proof')?>'">返回</a>
    </div>
</div>
<div id="upfile" class="easyui-dialog" data-options="closed:true">
    <form class="form-horizontal" name="upfile" role="form" method="post"
          action='<?php echo site_url('Proof/proveupfile') ?>' enctype="multipart/form-data" target="_upframe">
        <div class="form-group col-sm-11">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-2">上传文件</label>
            <div class="col-sm-9">
                <input type="hidden" name="idnumfile" value="<?= $idnumber ?>"/>
                <input type="hidden" name="type" value="<?= $type ?>"/>
                <input type="hidden" name="jsonresp" value="1"/>
                <input type="file" name="idnumimgd" id="idnumimgd" class="">
            </div>
        </div>
    </form>
</div>
<iframe src="#" frameborder="0" name="_upframe" width="0" height="0" style="display: none"></iframe>
<script>
    $('.upfile').on('click', function () {
        var tar=$(this).attr('_for');
        var limits=$('#'+tar+'_limit option:selected').val();
        var currentFiles=$('div[id^='+tar+']').length;
        if(!parseInt(limits) || isNaN(parseInt(limits))){
            top.modalbox.alert("所需文件数设置有误！");
            return;
        }

        if(currentFiles>=limits){
            top.modalbox.alert("最多只允许上传<span style='color:red;margin: 0 5px;font-weight: bolder'>"+limits+"</span>张图片！");
            return;
        }
        $('input[name=jsonresp]').val(tar);
        $('#upfile').dialog({
            title: '文件上传',
            width: 400,
            height: 180,
            closed: false,
            cache: false,
            modal: true,
            center:true,
            buttons: [{
                text: '提交',
                handler: function () {
                    $('form[name=upfile]').submit();
                    $('form[name=upfile]')[0].reset();
                    $('#upfile').dialog('close');
                }
            }]
        }).dialog('center');
    });
    $('body').delegate('a[_href]','click',function () {
        var location=$(this).attr('_href');
        $.get(location,function (resp) {
            if(resp == 200){
                $('#'+location.split('/').pop().split('.').shift()).remove()
            }
        });
    });

    $('[id$=_limit]').on('change',function () {
        var sval= $(this).val();
        $.getJSON(
            '<?php echo site_url('Proof/prov_sval_save')?>/ptype_limit/<?php echo $idnumber?>',
            $('#dataForm').serialize(),
            function (resp) {
                if(resp.code==500)
                    return top.modalbox.alert(resp.msg)
            }
        );
    })

    function complete(code, msg) {
        var cont='';
        if(arguments.length==3) cont=arguments[2];
        if(code==200)
            $('#piccon_'+cont).append($(msg))
    }
    $(function () {
//        $('[id^=piccon_]').each(function (idx, val) {
//            var imgNum=$(this).find('img').length;
//            $('#'+val.id.split('_').pop()+'_limit').val(imgNum);
//        });

        var selected=<?php echo $limit['p_val']==null?"''":$limit['p_val']?>;
        selected= selected?JSON.parse(selected):{};
        for(var key in selected){
            $('#'+key).val(selected[key]);
        }
    })
</script>
</body>
</html>
