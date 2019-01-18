<?php tpl("admin_header") ?>
<body>
<link rel="stylesheet" href="/fms/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css">
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
	
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table id="acclist">
            <thead>
            <tr>
                <th data-options="field:'fuserid',width:10">客户编号</th>
                <th data-options="field:'name',width:8">姓名</th>
				<th data-options="field:'idnumber',width:20">身份证号</th>
                
                <th data-options="field:'utype',width:10">分类</th>
                <!--<th data-options="field:'companyname',width:15">企业名称</th>-->
				<th data-options="field:'idnumimgu',width:12">身份证正面</th>
				<th data-options="field:'idnumimgd',width:12">身份证反面</th>
				 <th data-options="field:'comment',width:30">审核意见</th>
                <th data-options="field:'lryg',width:10">录入员工</th>
                <th data-options="field:'cdate',width:10">录入时间</th>
                <th data-options="field:'op',width:20">操作</th>
                
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->	
<script>	
	$('#acclist').datagrid({
        url:"<?php echo site_url('qiye/checklist')?>",
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });
    
    
   </script>
</body>
</html>