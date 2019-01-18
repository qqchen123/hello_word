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
        	<td class="tlabel"><?=iconv('gb2312','utf-8','��������')?></td>
            <td>
            	<input class="col-sm-5" type="text" name="corpname" id="corpname" value="">

            </td>
            <td class="tlabel"></td>
            <td>
                
            </td>
            
            
        </tr>
        

        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="queryqiye"><?=iconv('gb2312','utf-8','��ѯ');?></button>
				
				</td>
        </tr>
        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table id="acclist">
            <thead>
            <tr>
                <th data-options="field:'dddbh',width:10"><?=iconv('gb2312','utf-8','�������');?></th>
                <th data-options="field:'qiye',width:10"><?=iconv('gb2312','utf-8','�ſ���ҵ');?></th>
                <th data-options="field:'sp',width:10"><?=iconv('gb2312','utf-8','��Ʒ');?></th>
                <th data-options="field:'qudao',width:10"><?=iconv('gb2312','utf-8','����');?></th>
                <th data-options="field:'money',width:10"><?=iconv('gb2312','utf-8','���');?></th>
                <th data-options="field:'spyt',width:10"><?=iconv('gb2312','utf-8','��;');?></th>
                <th data-options="field:'nll',width:10"><?=iconv('gb2312','utf-8','������');?></th>
                <th data-options="field:'jsf',width:10"><?=iconv('gb2312','utf-8','������');?></th>
                <th data-options="field:'fwf',width:10"><?=iconv('gb2312','utf-8','�����');?></th>
                <th data-options="field:'csyg',width:10"><?=iconv('gb2312','utf-8','����Ա��');?></th>
                <th data-options="field:'op',width:10"><?=iconv('gb2312','utf-8','����');?></th>
                
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