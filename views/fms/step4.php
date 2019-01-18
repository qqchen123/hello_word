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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/step5')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','法人不动产地址');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传房产证');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','不动产类型');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','住宅');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','厂房');?></option>
                    <option value="02"><?=iconv('gb2312','utf-8','商住两用');?></option>
                    <option value="03"><?=iconv('gb2312','utf-8','办公楼');?></option>
                    <option value="04"><?=iconv('gb2312','utf-8','宗地');?></option>
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','不动产归属');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','法人');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','企业');?></option>
                    
                </select>
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','有无抵押');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','有');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','无');?></option>
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','是否二抵');?></td>
            <td>
            	<select name="bumenid" id="bumenid" class="col-sm-6">
                <option value="00"><?=iconv('gb2312','utf-8','清房');?></option>
                <option value="01"><?=iconv('gb2312','utf-8','一抵');?></option>
                <option value="02"><?=iconv('gb2312','utf-8','二抵');?></option>
                </select>
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','估值');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','抵押金额');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','抵押成数');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','房龄');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
                
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','是否老人儿童房');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','否');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','是');?></option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="button" class="btn btn-success ml2" id="gostep3"><?=iconv('gb2312','utf-8','保存并继续添加不动产信息');?></button>
				
				</td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep5"><?=iconv('gb2312','utf-8','下一步 法人汽车产权信息');?></button>
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
                <th data-options="field:'sn',width:5"><?=iconv('gb2312','utf-8','序号');?></th>
                <th data-options="field:'addr',width:50"><?=iconv('gb2312','utf-8','地址');?></th>
                <th data-options="field:'type',width:10"><?=iconv('gb2312','utf-8','类型');?></th>
                <th data-options="field:'guishu',width:10"><?=iconv('gb2312','utf-8','归属');?></th>
                <th data-options="field:'sfdy',width:10"><?=iconv('gb2312','utf-8','有无抵押');?></th>
                <th data-options="field:'sfrd',width:10"><?=iconv('gb2312','utf-8','是否二抵');?></th>
                <th data-options="field:'guzhi',width:20"><?=iconv('gb2312','utf-8','估值');?></th>
                <th data-options="field:'dyje',width:20"><?=iconv('gb2312','utf-8','抵押金额');?></th>
                <th data-options="field:'dycs',width:20"><?=iconv('gb2312','utf-8','抵押成数');?></th>
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