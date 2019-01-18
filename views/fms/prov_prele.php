<?php tpl("admin_header") ;print_r($info)?>
<body>
<style>
    .panel.window,.window-mask{position: fixed}
    .window-shadow{opacity: 0}
    .tlabel{text-align: right}
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
    .provec1 a {}
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
        <div region="north" data-options="border:false" style="height: 450px">
            <div class="page-header" style="padding-left: 15px">
                <span class="bigger-150">
                    关联人信息 <a class="btn btn-default btn-small" onclick="location.href='<?php echo site_url('Proof')?>'">返回</a>
                </span>
            </div><!-- /.page-header -->
            <form class="form-horizontal" role="form" id="addForm" method="post" action='<?php echo site_url('Proof/preleadd') ?>'  target="_upframe">
                <div class="col-xs-12 no-padding-right">
                    <div class="form-group col-sm-12 no-padding-right">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4 class="widget-title">添加共贷人</h4>
                            </div>
                            <table class="table table-bordered" style="margin: 0;padding: 0px">
                                <tbody>
                                    <tr>
                                        <td class="tlabel col-sm-2">真实姓名</td>
                                        <td>
                                            <input type="text" name="relname" id="relname" class="col-sm-6" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tlabel">身份证号</td>
                                        <td>
                                            <div class="col-sm-12 no-padding">
                                                <input type="text" name="idnum" id="idnum" class="col-sm-6" value=" ">
                                            </div>
                                            <div id="upId">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tlabel">上传证件</td>
                                        <td>
                                            <div class="col-sm-12 no-padding">
                                                <button type="button" class="btn btn-purple btn-sm upfile">
                                                    上传
                                                </button>
                                            </div>
                                            <div id="piccon">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tlabel">与主贷人关系</td>
                                        <td>
                                            <select name="rele" id="rele">
                                                <option>父亲</option>
                                                <option>母亲</option>
                                                <option>妻子</option>
                                                <option>丈夫</option>
                                                <option>长子</option>
                                                <option>长女</option>
                                                <option>次子</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tlabel">手机号码</td>
                                        <td>
                                            <input type="text" name="phone" id="phone" class="col-sm-6" value=" ">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tlabel">是否隐性共贷人</td>
                                        <td>
                                            <input type="checkbox" name="iftoge" id="iftoge" class="col-sm-6">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="align-center">
                                            <input type="hidden" name="fid" value="<?= $idnumber ?>"/>
                                            <button class="btn btn-success" id="gostep8">新增</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="clearfix"></div>
                        </div>
                    </div>
            <!-- /row -->
                </div>
            </form>
        </div>
    <div region="center" data-options="border:false" style="padding: 0px 15px;">
        <table id="acclist">
            <thead>
            <tr>
                <th data-options="field:'idnum',width:30">身份证号</th>
                <th data-options="field:'idnum_resov',width:30">身份证所属地区</th>
                <th data-options="field:'relname',width:10">真实姓名</th>
                <th data-options="field:'age',width:10">年龄</th>
                <th data-options="field:'sex',width:10">性别</th>
                <th data-options="field:'phone',width:10">手机号码</th>
                <th data-options="field:'iftoge',width:10">是否共贷人</th>
                <th data-options="field:'reles',width:10">与主贷人关系</th>
                <th data-options="field:'op',width:20">操作</th>
            </tr>
            </thead>
        </table>
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
                <input type="hidden" name="jsonresp" id="jsonresp" value="idrename"/>
                <input type="file" name="idnumimgd" id="idnumimgd" class="">
            </div>
        </div>
    </form>
</div>
<iframe src="#" frameborder="0" name="_upframe" width="0" height="0" style="display: none"></iframe>
<script src="/assets/apps/validator.js"></script>
<script>
    $('.upfile').on('click', function () {
        var currentFiles=$('#upId img').length;
        $('#jsonresp').val($('#idnum').val());
        if(currentFiles>=2){
            top.modalbox.alert("最多只允许上传<span style='color:red;margin: 0 5px;font-weight: bolder'>"+limits+"</span>张图片！");
            return;
        }
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
                    $('#jsonresp').val(1);
                    $('form[name=upfile]')[0].reset();
                    $('#upfile').dialog('close');
                }
            }]
        }).dialog('center');
    });
    $('#gostep8').on('click',function () {
        var relname=$('#relname').val();
        var idnum=$('#idnum').val();
        var phone=$('#phone').val();
        if($('#piccon img').length <2){
            return top.modalbox.alert('请上传身份证正反面照片');
        }
        if(!validator.isChineseORAlphar(relname)){
            return top.modalbox.alert('姓名填写错误');
        }

        if(!validator.isInt(idnum)){
            return top.modalbox.alert('身份证号填写错误');
        }

        if(!validator.isPhone(phone)){
            return top.modalbox.alert('电话号码填写错误')
        }

        $('#addForm')[0].submit();
        $(this).attr('disabled',true);
    });
    $('body').delegate('a[_href]','click',function () {
        var location=$(this).attr('_href');
        $.get(location,function (resp) {
            if(resp == 200){
                $('#'+location.split('/').pop().split('.').shift()).remove()
                if($('#piccon img').length <2){
                    $('.upfile').show();
                }
            }
        });
    })

    function submitSuccess(code,respon) {
        if(code == 200){
            top.modalbox.alert('提交成功！',function () {
                window.location.reload()
            });
        }

        top.modalbox.alert(respon,function () {
            $('#gostep8').removeAttr('disabled');
        })
    }
    function complete(code, msg) {
        var cont='';
        if(arguments.length==3) cont=arguments[2];
        if(code==200){
            $('#piccon').append($(msg))
            if($('#piccon img').length >=2){
                $('.upfile').hide();
            }
        }
    }

    function edit($id) {
        window.location.href='<?php echo site_url('Proof/edit/prele').'/'.$idnumber?>/'+$id;
    }

    function del($id) {
        $.getJSON(
            '<?php echo site_url('Proof/prov_del')?>/'+$id,
            function (resp) {
                if(resp.code == 200){
                    top.modalbox.alert('删除成功！',function () {
                        $('#acclist').datagrid('reload');
                    });
                }

                top.modalbox.alert(resp.msg)
            }
        );
    }

    $('#acclist').datagrid({
        url:"<?php echo site_url('Proof/prelelist')?>?fid=<?php echo $idnumber?>",
        fit:true,
        fitColumns:true,
        method:'get'
    });
</script>
</body>
</html>
