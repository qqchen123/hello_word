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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/step4')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','法人配偶姓名');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传结婚证');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','年龄');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','出生日期');?></td>
            <td>
                <input type="text" name="begin" id="begin" class="easyui-datebox">
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','性别');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="01"><?=iconv('gb2312','utf-8','男');?></option>
                    <option value="00"><?=iconv('gb2312','utf-8','女');?></option>
                </select>
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','政治面貌');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','党员');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','群众');?></option>
                    <option value="02"><?=iconv('gb2312','utf-8','民主党派');?></option>
                </select>
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','户籍所在地');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
                
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传法人配偶户口本');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','其他证照1');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传证照');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','其他证照2');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传证照');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep4"><?=iconv('gb2312','utf-8','下一步 法人不动产信息');?></button>
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