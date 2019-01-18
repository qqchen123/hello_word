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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/step7')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','上传资产负债表(全年)');?></td>
            <td>
                <input type="file" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传现金流量表(全年)');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','上传利润分配表(全年)');?></td>
            <td>
                <input type="file" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传基本户资金流水(全年)');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','其他一般资金流水1');?></td>
            <td>
                <input type="file" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','其他一般资金流水2');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep7"><?=iconv('gb2312','utf-8','下一步 法人个人流水信息');?></button>
				&nbsp;
				<button type="submit" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','保存并提交开户');?></button>
				</td>
        </tr>
        </tbody>
    </table>
    </form>
    </div>
    

</div><!-- /.page-content -->	
<script>	
	
    
   </script>
</body>
</html>