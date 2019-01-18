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
        	<td class="tlabel">渠道名称</td>
            <td>
            	<input class="col-sm-5" type="text" name="corpname" id="corpname" value="">

            </td>
            <td class="tlabel"></td>
            <td>
                
            </td>
            
            
        </tr>
        

        <tr>
            <td colspan="4" class="align-center">
            	
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
                <?php /*
                <th data-options="field:'name',width:30">渠道名称</th>
                <th data-options="field:'num',width:10">企业数量</th>
                <th data-options="field:'total',width:10">总额度</th>
                <th data-options="field:'remain',width:10">剩余额度</th>
                <th data-options="field:'udate',width:10">最近还款时间</th>
                <th data-options="field:'op',width:20">操作</th>
                */?>
                <th data-options="field:'q_name',width:10">渠道名称</th>
                <th data-options="field:'q_code',width:10">渠道编号</th>
                <th data-options="field:'q_company',width:25">渠道公司名称</th>
                <th data-options="field:'q_team_numbers',width:6">现有团队人数</th>
                <th data-options="field:'q_if_has_risk_team',width:10">是否有风控团队</th>
                <th data-options="field:'q_if_has_stock',width:10">是否自有存量</th>
                <th data-options="field:'ctime',width:10">创建时间</th>
                <th data-options="field:'uptime',width:10">更新时间</th>
                <th data-options="field:'op',width:8">操作</th>
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->	
<script>	
	$('#acclist').datagrid({
        url:"<?php echo site_url('qudao/qudaolist')?>",
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
        window.location.href=PAGE_VAR.SITE_URL+'Qudao/edit/'+$id;
    }

    function showpicker($id) {
        window.location.href=PAGE_VAR.SITE_URL+'Qudao/getpickers/'+$id;
    }

    function del($id){
        top.modalbox.confirm('您确认要删除此产品吗？',function(cfm){
            if(cfm){
                $.getJSON(
                    PAGE_VAR.SITE_URL+'Qudao/del/'+$id,
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