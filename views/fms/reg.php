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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/step2')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','企业名称');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','提交渠道');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="qd01"><?=iconv('gb2312','utf-8','上海渠道01');?></option>
                    
                </select>
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','法人姓名');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传身份证');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','注册资本');?></td>
            <td>
                <input type="text" name="dlxdz" id="dlxdz" class="col-sm-6">
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','持股比例');?></td>
            <td>
                <input type="text" name="dyyzz" id="dyyzz" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','是否合资');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="01"><?=iconv('gb2312','utf-8','是');?></option>
                    <option value="00"><?=iconv('gb2312','utf-8','否');?></option>
                </select>
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','营业执照编号');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传营业执照');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','纳税人编号');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
                
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','一般纳税人');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="01"><?=iconv('gb2312','utf-8','是');?></option>
                    <option value="00"><?=iconv('gb2312','utf-8','否');?></option>
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传一般纳税人资格');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','开户行');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','上传开户许可证');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','银行账号');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep2"><?=iconv('gb2312','utf-8','下一步 企业法人信息');?></button>
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