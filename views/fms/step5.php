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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/step6')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','法人车辆品牌');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传汽车产证');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        
        
        
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="button" class="btn btn-success ml2" id="gostep3"><?=iconv('gb2312','utf-8','保存并继续添加汽车产权信息');?></button>
				
				</td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep6"><?=iconv('gb2312','utf-8','下一步 企业流水信息');?></button>
				&nbsp;
				<button type="submit" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','保存并提交开户');?></button>
				</td>
        </tr>
        </tbody>
    </table>
    </form>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table id="acclist">
            <thead>
            <tr>
                <th data-options="field:'sn',width:10"><?=iconv('gb2312','utf-8','序号');?></th>
                <th data-options="field:'addr',width:30"><?=iconv('gb2312','utf-8','车辆品牌');?></th>
                <th data-options="field:'type',width:30"><?=iconv('gb2312','utf-8','车辆产权');?></th>
                
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->	
<script>	
	$('#acclist').datagrid({
        url:"<?php echo site_url('qiye/bdclist?corpid="+$.trim($("#corpid").val())+"')?>",
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });
    
    
   </script>
</body>
</html>