<?php tpl("admin_header"); ?>
<body>
<link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css">
<style>
    td {
        border-top: none !important;
        vertical-align: middle !important;
    }
    a{cursor: pointer}
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
                <a onclick="location.href='<?php echo site_url('Qudao/query')?>'">渠道列表</a>  /  渠道【<?php echo $qinfo['q_name']?>】对接人信息表
            </span>
        </div><!-- /.page-header -->
        <table class="table table-bordered" style="margin: 0;padding: 0px">
            <tbody>
            <tr>
                <td class="tlabel">对接人姓名</td>
                <td>
                    <input class="col-sm-5" type="text" name="pickername" id="pickername" value="" _valid="" _msg="对接人错误" _required>
                </td>
                <?php
                /*
                ?><td class="tlabel"></td>
                <td>
                </td>*/?>
            </tr>
            <tr>
                <td colspan="4" class="align-center">
                    <button type="button" class="btn btn-success ml2" id="adddc">新增</button>
                    <button type="submit" class="btn btn-success ml2" id="queryqiye">查询</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table id="acclist">
            <thead>
            <tr>
                <th data-options="field:'jg_name',width:10">机构</th>
                <th data-options="field:'dc_name',width:10">姓名</th>
                <th data-options="field:'dc_qpik',width:10">对接人定义</th>
                <th data-options="field:'dc_department',width:6">所属部门</th>
                <th data-options="field:'dc_group',width:10">组别</th>
                <th data-options="field:'dc_role',width:10">角色</th>
                <?php
                    /*<th data-options="field:'dc_wesing',width:10">微信号</th>*/
                ?>
                <th data-options="field:'dc_phone',width:10">手机</th>
                <th data-options="field:'dc_comp_mail',width:10">公司邮箱</th>
                <th data-options="field:'op',width:8">操作</th>
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->
<script>
    $('#acclist').datagrid({
        url:"<?php echo site_url('qudao/getpickerslist')?>",
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });

    $('#queryqiye').on('click',function () {
        var dcname=$('#pickername').val();
        $('#acclist').datagrid('load',{dcname:dcname});
    });

    $('#adddc').on('click',function () {
        window.location.href=PAGE_VAR.SITE_URL+'Qudao/adddc/<?php echo $qinfo['q_id']?>';
    });

    function edit_dc($id){
        window.location.href=PAGE_VAR.SITE_URL+'Qudao/edit_dc/'+$id;
    }

    function del($id){
        top.modalbox.confirm('您确认要删除此联系人吗？',function(cfm){
            if(cfm){
                $.getJSON(
                    PAGE_VAR.SITE_URL+'Qudao/del_dc/'+$id,
                    function(response){
                        if(response.responseCode == 200){
                            return $('#acclist').datagrid('reload');
                        }
                        $.messager.alert('错误',response.responseMsg)
                    }
                )
            }
        })
    }
</script>
</body>
</html>