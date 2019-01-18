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
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        <tr>
        	<td class="tlabel"><?='产品名称'?></td>
            <td>
            	<input class="col-sm-5" type="text" name="corpname" id="corpname" value="">
            </td>
        </tr>
        

        <tr>
            <td colspan="4" class="align-center">
                <button class="btn btn-success ml2" id="queryqiye"><?='查询';?></button>
				</td>
        </tr>
        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table id="acclist">
            <thead>
            <tr>
                <th data-options="field:'sp_mc',width:30"><?='产品名称';?></th>
                <th data-options="field:'sp_code',width:10"><?='产品代码';?></th>
                <th data-options="field:'sp_usage',width:10"><?='抵押综合利率(%)';?></th>
                <th data-options="field:'sp_rate',width:10"><?='月基础利率(%)';?></th>
                <th data-options="field:'sp_fee',width:10"><?='综合管理技术费(%)';?></th>
                <th data-options="field:'sp_servfee',width:10"><?='平台预收服务费年费率(%)';?></th>
                <th data-options="field:'sp_during',width:10"><?='返点(%)';?></th>
                <th data-options="field:'op',width:20"><?='操作';?></th>
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->

<script>

    $('#acclist').datagrid({
        url:"<?php echo site_url('sp/splist')?>",
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });
    $('#queryqiye').on('click',function () {
        var spmc=$('#corpname').val();
        $('#acclist').datagrid('load',{spmc:spmc});
    })

    function edit_sp($id){
        window.location.href=PAGE_VAR.SITE_URL+'sp/edit/'+$id;
    }

    function del($id){
        top.modalbox.confirm('<?php echo '您确认要删除此产品吗？'?>',function(cfm){
            if(cfm){
                $.getJSON(
                    PAGE_VAR.SITE_URL+'sp/del/'+$id,
                    function(response){
                        if(response.responseCode ==200){
                            return $('#acclist').datagrid('reload');
                        }

                        top.modalbox.alert(response.responseMsg);
                    }
                )
            }
        })
    }
   </script>
</body>
</html>