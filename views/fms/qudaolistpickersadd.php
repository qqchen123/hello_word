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
        <div class="page-header">
            <span class="bigger-150">
                <a onclick="location.href='<?php echo site_url('Qudao/query')?>'">渠道列表</a>  /  <a onclick="location.href='<?php echo site_url('Qudao/getpickers').'/'.$qinfo['q_id']?>'">渠道【<?php echo $qinfo['q_name']?>】对接人信息表</a> / 新增对接人
            </span>
        </div><!-- /.page-header -->
        <form id='fileform' method='post' action='<?php echo site_url('Qudao/adddc_row')?>' target="uploadFrame">
            <table class="table table-bordered" style="margin: 0;padding: 0px">
                <tbody>
                <tr>
                    <td class="tlabel">机构名称</td>
                    <td>
                        <select name="dc_jgid" id="dc_jgid" class="easyui-combogrid col-sm-3" style="height: 30px;line-height: 30px"></select>
                    </td>
                    <td class="tlabel">姓名</td>
                    <td>
                        <input type="text" name="dc_name" id="dc_name" class="col-sm-6" _required _valid="isChineseORAlphar" _msg="姓名错误" maxlength="50">
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">所属部门</td>
                    <td>
                        <select name="dc_department" id="dc_department" class="col-sm-6" _required _valid="isAlphaOrNumbers" _msg="所属部门未选择">
                            <option value="">---请选择---</option>
                            <?php
                            if(isset($mdepart) && $mdepart && is_array($mdepart)):
                                foreach ($mdepart as $_key=>$_val):
                                    printf('<option value="%s">%s</option>',$_key,$_val);
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </td>
                    <td class="tlabel">对接人类别</td>
                    <td>
                        <select name="dc_qpik" id="dc_qpik" class="col-sm-6" _required _valid="isChineseORAlphar" _msg="对接人类别未选择" maxlength="100">
                            <option value="">---请选择---</option>
                            <?php
                            if(isset($pick) && $pick && is_array($pick)):
                                foreach ($pick as $_key=>$_val):
                                    printf('<option value="%s">%s</option>',$_key,$_val);
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">组别</td>
                    <td>
                        <select name="dc_group" id="dc_group" class="col-sm-6" _required _valid="isChineseORAlphar" _msg="组别未选择" maxlength="100">
                            <option value="">---请选择---</option>
                            <?php
                            if(isset($group) && $group && is_array($group)):
                                foreach ($group as $_key=>$_val):
                                    printf('<option value="%s">%s</option>',$_key,$_val);
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </td>
                    <td class="tlabel">角色</td>
                    <td>
                        <select name="dc_role" id="dc_role" class="col-sm-6" _required _valid="" _msg="请选择角色">
                            <option value="">---请选择---</option>
                            <?php
                            if(isset($mrol) && $mrol && is_array($mrol)):
                                foreach ($mrol as $_key=>$_val):
                                    printf('<option value="%s">%s</option>',$_key,$_val);
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">微信号</td>
                    <td>
                        <input type="text" name="dc_wesing" id="dc_wesing" class="col-sm-6" _required _valid="isChineseORAlphar" _msg="微信号" maxlength="50">
                    </td>
                    <td class="tlabel">电话</td>
                    <td>
                        <input type="text" name="dc_phone" id="dc_phone" class="col-sm-6" _required _valid="isPhone" maxlength="15" _msg="电话号码错误">
                    </td>
                </tr>
                <tr>
                    <td class="tlabel">公司邮箱</td>
                    <td colspan="3">
                        <input type="text" name="dc_comp_mail" id="dc_comp_mail" class="col-sm-6" _valid="isEmail" _required _msg="公司邮箱错误" maxlength="150">
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="align-center">
                        <input type="hidden" name="q_id" value="<?php echo $qinfo['q_id']?>">
                        <button class="btn btn-success ml2" id="gostep8">保存</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>


</div><!-- /.page-content -->
<iframe src="#" frameborder="0" style="display: none" name="uploadFrame"></iframe>
<script>
    seajs.use('apps/admin/channel.js?_=234234')
    $('#dc_jgid').combogrid({
        idField: 'jg_id',
        textField: 'jg_name',
        url: '../../jigou/get_jigou',
        method: 'get',
        columns: [[
            {field:'jg_id',title:'机构 ID',width:80},
            {field:'jg_name',title:'机构名称',width:120},
            {field:'status_info',title:'机构状态',width:60,align:'center'}
        ]],
        fitColumns: true
    })
    function complete($code, $msg) {
        if($code==200){
            top.modalbox.alert('成功',function () {
                window.location.reload();
            })
        }else{
            top.modalbox.alert($msg)
        }
    }
</script>
</body>
</html>