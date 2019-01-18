<?php tpl("admin_header") ?>
<body>
<link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css">
<style>
    td {
        border-top: none !important;
        vertical-align: middle !important;
    }

    .tlabel {
        text-align: right;
        background-color: #EEEEEE;
    }
    .ml2 {
        margin-right: 2em
    }
    .dn {
        display: none;
    }
</style>
<div class="page-content" id="check-div">
    <div class="page-header">
        <span class="bigger-150">
            账号检测
        </span>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <form class="form-horizontal" role="form" method="post">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 姓名</label>

                            <div class="col-sm-9">
                                <input type="text" id="name" name="name" placeholder="姓名" class="col-xs-10 col-sm-5">
                                <span class="help-inline col-xs-12 col-sm-7 text-danger">
                                    <span class="middle"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 身份证</label>

                            <div class="col-sm-9">
                                <input type="text" id="idnumber" name="idnumber" placeholder="身份证" class="col-xs-10 col-sm-5">
                                <span class="help-inline col-xs-12 col-sm-7 text-danger">
                                    <span class="idnumber"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-2">民族</label>

                            <div class="col-sm-9">
                                <input type="text" id="nation" name="nation" placeholder="民族" class="col-xs-10 col-sm-5">
                                <span class="help-inline col-xs-12 col-sm-7 text-danger">
                                    <span class="nation"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 渠道</label>
                            <div class="col-sm-9">
                                <select name="channel" id="channel" class="col-xs-10 col-sm-2">
                                    <? foreach($qudaoinfo as $value): ?>
                                        <option value="<?= $value['q_code']?>"><?= $value['q_name']?></option>
                                    <? endforeach; ?>
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7 text-danger">
                                    <span class="middle"></span>
                                </span>
                            </div>
                        </div>

                        <div class="clearfix form-actions">
                            <div class="col-md-12">
                                <button class="btn btn-info btn-sm" id="check-submit" type="submit" style="margin-right: 20px">
                                    <i class="icon-ok bigger-110"></i>
                                    提交
                                </button>
                                <button class="btn  btn-sm" type="reset" style="margin-right: 20px">
                                    <i class="icon-undo bigger-110"></i>
                                    重置
                                </button>
                                <span>
                                    <a class="btn btn-info btn-sm" id="query" href="<?php echo site_url('qiye/query')?>">
                                        <i class="bigger-110"></i>
                                        返回
                                    </a>
                                </span>
                            </div>
                        </div>
                    </form>

                </div><!-- /span -->
            </div><!-- /row -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<script type="text/javascript">
    $("#utype").change(function(){
        var utype = $(this).val();
        if (utype == '01') {
            $("#companynameaddress").hide();
        }
        if (utype == '00') {
            $("#companynameaddress").show();
        }
    });
    seajs.use('apps/admin/idnumber');

    function getimgsize(filePath){
        var idnumimgu = document.getElementById(filePath).files[0];
        return idnumimgu.size;
    }

    function complete($code, $msg) {
        if($code==200){
            var url = PAGE_VAR.SITE_URL+'Qiye/check/';
            top.modalbox.alert($msg,function () {
                window.location.href = url;
                return;
            })
        }else{
            top.modalbox.alert($msg,function(){});
            return ;
        }
    }

    $('#check-submit').on('click',function(e){
        e.preventDefault();
        var name = $('#name').val().trimSpace();
        var idnumber = $('#idnumber').val().trimSpace();
        var channel = $('#channel').val();
        var nation = $('#nation').val().trimSpace();
        var nameError = '';
        var idnumberError = '';
      
        if (!/^[\u4E00-\u9FA5A-Za-z]+$/.test(name)) {
            nameError = "姓名不正确";
        }

        if (!/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(idnumber)) {
            idnumberError = "身份证号有误"; 
        }

        $('#idnumber').next().children().text(idnumberError);
        
        $('#name').next().children().text(nameError);

        if(nameError || idnumberError ) return;
        $.post(
            PAGE_VAR.SITE_URL+'Qiye/check',
            {name:name, idnumber:idnumber},
            function (response) {
                if(response.responseCode==200){
					top.modalbox.alert('身份证号已存在，跳转到订单',function(){
						window.location.href = PAGE_VAR.SITE_URL+'Order/query';
					});
                    return ;
                }else if (response.responseCode == 201) {
					var flag = 0;
					top.modalbox.confirm('身份证号不存在，是否开户',function(){
                        //开户
                        $.post(
                            PAGE_VAR.SITE_URL+'Qiye/createuser',
                            {name:name, idnumber:idnumber, channel:channel, nation:nation},
                            function (response) {
                                console.log(response);
                                //接收 ID
                                var re_data = response;
                                if (false != re_data) {
                                    console.log('成功创建用户: ' + re_data);
                                    top.modalbox.confirm('客户创建成功，是否跳转至查询页',function(){
                                        window.location.href=PAGE_VAR.SITE_URL+'Qiye/query';
                                    });
                                } else {
                                    console.log('客户创建失败');
                                    top.modalbox.alert('客户创建失败');
                                }
                            }
                        );
					});
                    return ;
				}else if(response.responseCode==400){
					top.modalbox.alert(response.responseMsg);
                    return ;
				}
            },'json'
        );
    });
    $('#back').on('click', function(){
        window.location.href = PAGE_VAR.SITE_URL+'Qiye/check';
    });
</script>
</body>
</html>
