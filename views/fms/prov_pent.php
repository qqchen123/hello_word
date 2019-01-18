<?php tpl("admin_header"); ?>
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
            房产资料 <a class="btn btn-default btn-small" onclick="location.href='<?php echo site_url('Proof')?>'">返回</a>
        </span>
    </div><!-- /.page-header -->

    <div class="row">

        <div class="col-xs-12">
            <form class="form-horizontal" id="dataForm" role="form" method="post" action='<?php echo site_url('Proof/proveupfile') ?>'
                  enctype="multipart/form-data" target="_upframe">
                <div class="form-group col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">企业五证</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        操作
                                    </span>
<!--                                    <select name="main_per_house_limit" id="main_per_house_limit" class="form-control search-query">-->
<!--                                        <option value="1">1</option>-->
<!--                                        <option value="2">2</option>-->
<!--                                        <option value="3">3</option>-->
<!--                                        <option value="4">4</option>-->
<!--                                        <option value="5">5</option>-->
<!--                                    </select>-->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="wuzheng">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_wuzheng">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^wuzheng/',$v)) {$a= pathinfo($v);
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
                            <h4 class="widget-title">企业公章</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        操作
                                    </span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="gongzhang">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_gongzhang">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^gongzhang/',$v)) {$a= pathinfo($v);
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
                            <h4 class="widget-title">公司章程</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        操作
                                    </span>
<!--                                    <select name="fin_ser_limit" id="fin_ser_limit" class="form-control search-query">-->
<!--                                        <option value="1">1</option>-->
<!--                                        <option value="2">2</option>-->
<!--                                        <option value="3">3</option>-->
<!--                                        <option value="4">4</option>-->
<!--                                        <option value="5">5</option>-->
<!--                                    </select>-->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="zhengcheng">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_zhengcheng">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^zhengcheng/',$v)) {$a= pathinfo($v);
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
                            <h4 class="widget-title">公司财务报表(近一年)</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        操作
                                    </span>
<!--                                    <select name="secondary_fin_ser_limit" id="secondary_fin_ser_limit" class="form-control search-query">-->
<!--                                        <option value="1">1</option>-->
<!--                                        <option value="2">2</option>-->
<!--                                        <option value="3">3</option>-->
<!--                                        <option value="4">4</option>-->
<!--                                        <option value="5">5</option>-->
<!--                                    </select>-->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="caibao">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_caibao">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^caibao/',$v)) {$a= pathinfo($v);
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
                            <h4 class="widget-title">公司流水(近半年)</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        操作
                                    </span>
<!--                                    <select name="secondary_fin_ser_limit" id="secondary_fin_ser_limit" class="form-control search-query">-->
<!--                                        <option value="1">1</option>-->
<!--                                        <option value="2">2</option>-->
<!--                                        <option value="3">3</option>-->
<!--                                        <option value="4">4</option>-->
<!--                                        <option value="5">5</option>-->
<!--                                    </select>-->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="liushui">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_liushui">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^liushui/',$v)) {$a= pathinfo($v);
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
                            <h4 class="widget-title">上下游合同</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        操作
                                    </span>
<!--                                    <select name="secondary_fin_ser_limit" id="secondary_fin_ser_limit" class="form-control search-query">-->
<!--                                        <option value="1">1</option>-->
<!--                                        <option value="2">2</option>-->
<!--                                        <option value="3">3</option>-->
<!--                                        <option value="4">4</option>-->
<!--                                        <option value="5">5</option>-->
<!--                                    </select>-->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="hetong">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_hetong">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^hetong/',$v)) {$a= pathinfo($v);
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
                            <h4 class="widget-title">本次借款股东会议决议</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        操作
                                    </span>
<!--                                    <select name="secondary_fin_ser_limit" id="secondary_fin_ser_limit" class="form-control search-query">-->
<!--                                        <option value="1">1</option>-->
<!--                                        <option value="2">2</option>-->
<!--                                        <option value="3">3</option>-->
<!--                                        <option value="4">4</option>-->
<!--                                        <option value="5">5</option>-->
<!--                                    </select>-->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="jiekuan">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_jiekuan">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^jiekuan/',$v)) {$a= pathinfo($v);
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
                            <h4 class="widget-title">无限连带保证书</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="input-group col-sm-4">
                                    <span class="input-group-addon">
                                        caozuo
                                    </span>
<!--                                    <select name="secondary_fin_ser_limit" id="secondary_fin_ser_limit" class="form-control search-query">-->
<!--                                        <option value="1">1</option>-->
<!--                                        <option value="2">2</option>-->
<!--                                        <option value="3">3</option>-->
<!--                                        <option value="4">4</option>-->
<!--                                        <option value="5">5</option>-->
<!--                                    </select>-->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-purple btn-sm upfile" _for="liandaibaozheng">
                                            上传
                                        </button>
                                    </span>
                                </div>
                                <hr />
                                <div id="piccon_liandaibaozheng">
                                    <?php foreach ($files as $k => $v) {
                                        if ($k > 1 && preg_match('/^liandaibaozheng/',$v)) {$a= pathinfo($v);
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
        <a class="btn btn-default btn-small" onclick="location.href='<?php echo site_url('Proof/prove')?>'">返回</a>
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
        var limits=20;
        var currentFiles=$('div[id^='+tar+']').length;

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
    $('#has_secondary_house_limit').on('change',function () {
        if(this.value=='无'){
            if($('#piccon_secondary_house img').length>0){
                $('#has_secondary_house_limit').val('有');
                return top.modalbox.alert('当前已有上传数据，不能选择此项');
            }
            $('#secondary_house_limit').attr('disabled',true);
            $('button[_for=secondary_house]').attr('disabled',true);
        }else{
            $('#secondary_house_limit').attr('disabled',false);
            $('button[_for=secondary_house]').attr('disabled',false);
        }
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
            '<?php echo site_url('Proof/prov_sval_save')?>/pfin_limit/<?php echo $idnumber?>',
            $('#dataForm').serialize(),
            function (resp) {

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
        var selected=<?php echo $limit['p_val']==null?"''":$limit['p_val']?>;
        selected= selected?JSON.parse(selected):{};
        for(var key in selected){
            $('#'+key).val(selected[key]);
            if(key == 'has_secondary_house_limit' && selected[key] == '有'){
                $('#secondary_house_limit').attr('disabled',false);
                $('button[_for=secondary_house]').attr('disabled',false);
            }
        }
    })
</script>
</body>
</html>
