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
        	<td class="tlabel"><?=iconv('gb2312','utf-8','渠道名称')?></td>
            <td>
            	<input class="col-sm-5" type="text" name="corpname" id="corpname" value="">

            </td>
            <td class="tlabel"></td>
            <td>
                
            </td>
            
            
        </tr>
        

        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="queryqiye"><?=iconv('gb2312','utf-8','查询');?></button>
				
				</td>
        </tr>
        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table id="acclist">
            <thead>
            <tr>
                <th data-options="field:'dddbh',width:10"><?=iconv('gb2312','utf-8','订单编号');?></th>
                <th data-options="field:'qiye',width:10"><?=iconv('gb2312','utf-8','放款企业');?></th>
                <th data-options="field:'sp',width:10"><?=iconv('gb2312','utf-8','产品');?></th>
                <th data-options="field:'qudao',width:10"><?=iconv('gb2312','utf-8','渠道');?></th>
                <th data-options="field:'money',width:10"><?=iconv('gb2312','utf-8','金额');?></th>
                <th data-options="field:'spyt',width:10"><?=iconv('gb2312','utf-8','用途');?></th>
                <th data-options="field:'nll',width:10"><?=iconv('gb2312','utf-8','年利率');?></th>
                <th data-options="field:'jsf',width:10"><?=iconv('gb2312','utf-8','技术费');?></th>
                <th data-options="field:'fwf',width:10"><?=iconv('gb2312','utf-8','服务费');?></th>
                <th data-options="field:'csyg',width:10"><?=iconv('gb2312','utf-8','初审员工');?></th>
                <th data-options="field:'op',width:10"><?=iconv('gb2312','utf-8','操作');?></th>
                
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->	
<script>	
	$('#acclist').datagrid({
        url:"<?php echo site_url('order/auditlist')?>",
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });
    
    
   </script>
</body>
</html>