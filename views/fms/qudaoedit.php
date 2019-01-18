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
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
        <?php if(!$qinfo):?>
            <div class="page-header">
                <span class="bigger-150">
                    <a onclick="location.href='<?php echo site_url('Qudao/query')?>'">渠道列表</a>  /  无记录
                </span>
            </div><!-- /.page-header -->
            <?php exit;endif;?>
        <form id='fileform' method='post' action='<?php echo site_url('Qudao/saverec')?>' target="uploadFrame">
            <table class="table table-bordered" style="margin: 0;padding: 0px">
                <tbody>
                <tr>
                    <td class="tlabel">渠道名称</td>
                    <td>
                        <input type="text" name="q_name" id="q_name" class="col-sm-6" _required _valid="isChineseORAlphar" _msg="渠道名称错误" maxlength="100">
                    </td>
                    <td class="tlabel">渠道编号</td>
                    <td>
                        <input type="text" name="q_code" id="q_code" class="col-sm-6" readonly _required _valid="isAlphaOrNumbers" _msg="渠道编号错误" maxlength="25">
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">渠道公司名称</td>
                    <td>
                        <input type="text" name="q_company" id="q_company" class="col-sm-6" _required _valid="isChineseORAlphar" _msg="渠道公司名称错误" maxlength="100">
                    </td>
                    <td class="tlabel">渠道等级</td>
                    <td>
                        <select name="q_level" id="q_level" class="col-sm-6" _valid="" _msg="请选择渠道等级">
                            <option value="">---请选择---</option>
                            <option >I级</option>
                            <option >II级</option>
                            <option >III级</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">对接人姓名</td>
                    <td>
                        <input type="text" name="q_picker" id="q_picker" class="col-sm-6" _required _valid="isChineseORAlphar" _msg="对接人姓名错误" maxlength="50">
                    </td>
                    <td class="tlabel">电话</td>
                    <td>
                        <input type="text" name="q_picker_phone" id="q_picker_phone" class="col-sm-6" _required _valid="isPhone" maxlength="15" _msg="电话号码错误">
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">公司名字</td>
                    <td>
                        <input type="text" name="q_picker_company" id="q_picker_company" class="col-sm-6" _valid="isChineseORAlphar" _required _msg="公司名字错误" maxlength="150">
                    </td>
                    <td class="tlabel">公司地址</td>
                    <td>
                        <input type="text" name="q_picker_company_addr" id="q_picker_company_addr" class="col-sm-6"  _valid="isChineseORAlphar" _required _msg="公司地址错误" maxlength="250">
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">唯一指定邮箱号</td>
                    <td>
                        <input type="text" name="q_picker_company_mail" id="q_picker_company_mail" class="col-sm-6" _valid="isEmail" _required _msg="唯一指定邮箱错误" maxlength="150">
                    </td>
                    <td class="tlabel">经营时间</td>
                    <td>
                        <input type="text" name="q_picker_business_time" id="q_picker_business_time" class="col-sm-6" _valid="isAlphaOrNumbers" _required _msg="经营时间错误" maxlength="20">
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">主营业务</td>
                    <td>
                        <input type="text" name="q_picker_business" id="q_picker_business" class="col-sm-6" _valid="isChineseORAlphar" _required _msg="主营业务错误" maxlength="250">
                    </td>
                    <td class="tlabel">现有团队人数</td>
                    <td>
                        <input type="text" name="q_team_numbers" id="q_team_numbers" class="col-sm-6" _valid="isInt" _required _msg="现有团队人数错误" maxlength="5">
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">是否有风控团队</td>
                    <td>
                        <select name="q_if_has_risk_team" id="q_if_has_risk_team" class="col-sm-6">
                            <option >无</option>
                            <option >有</option>
                        </select>
                    </td>
                    <td class="tlabel">是否有自有存量</td>
                    <td>
                        <select name="q_if_has_stock" id="q_if_has_stock" class="col-sm-6">
                            <option >无</option>
                            <option >有</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="align-center">
                        <input type="hidden" name="q_id">
                        <button class="btn btn-success ml2" id="gostep8">保存</button>
                        <button class="btn btn-success ml2" onclick="location.href='<?php echo site_url('Qudao/query')?>'">返回</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</div><!-- /.page-content -->
<iframe src="#" frameborder="0" style="display: none" name="uploadFrame"></iframe>
<script>
    seajs.use('apps/admin/channel.js?_=234234');
    $(function () {
        var vals=<?php echo json_encode($qinfo)?>;
        for(var idx in vals){
            $('[name='+idx+']').val(vals[idx]);
            console.log(idx,vals[idx])
        }
    });
    function complete($code, $msg) {
        if($code==200){
            top.modalbox.alert('成功',function () {
                window.location.href='<?php echo site_url('Qudao/query')?>';
            })
        }else{
            top.modalbox.alert($msg)
        }
    }
</script>
</body>
</html>