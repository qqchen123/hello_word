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
	.provec {
        
        width: 200px;
        height: 260px;
        text-align: center;
        line-height: 260px;
		margin-left: 15px;
    }

    .provec img {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        margin-top: -4px;
    }

    .provec1 {
        height: 40px;
        text-align: center;
        line-height: 40px;
    }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
	<div region="north" data-options="border:false" style="padding: 8px 20px;">
	<form id='fileform' method='post' action='<?php echo site_url('qiye/bankeditdo')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
		<input type="hidden" name="fuserid" value="<?php echo $fuserid;?>"/>
		<input type="hidden" name="bankcardNoold" value="<?php echo $bankcardNo;?>"/>
		<tr>
        	<td class="tlabel">姓名</td>
            <td>
                <input type="text" name="name" value="<?=$name?>"  id="name" readonly = readonly class="col-sm-6">
            </td>
            
            <td class="tlabel">身份证号</td>
            <td>
                <input type="text" name="idnumber" value="<?=$idnumber?>"  id="idnumber" readonly = readonly class="col-sm-6">
            </td>
        </tr>
        <tr>
        	<td class="tlabel">开户行</td>
            <td>
                <input type="text" name="bankname" id="bankname" value="<?=$bankname?>" class="col-sm-6">
            </td>
            
            <td class="tlabel">银行卡号</td>
            <td>
                <input type="text" name="bankcardNo" id="bankcardNo" value="<?=$bankcardNo?>" class="col-sm-6">
            </td>
        </tr>
		<tr>
        	<td class="tlabel">卡类别</td>
            <td>
                <select name="uktype" id="utype" class="col-sm-6">
                    <option value="00" <?php if($uktype == '00'){echo 'selected';}?>>打款</option>
                    <option value="01" <?php if($uktype == '01'){echo 'selected';}?>>扣款</option>
                </select>
            </td>
            
            <td class="tlabel">是否默认</td>
            <td>
                <select name="is_default" id="is_default" class="col-sm-6">
                    <option value="00" <?php if($is_default == '00'){echo 'selected';}?>>默认</option>
                    <option value="01" <?php if($is_default == '01'){echo 'selected';}?>>不默认</option>
                </select>
            </td>
        </tr>
        
        
        <tr>
            <td class="tlabel">手机号</td>
            <td>
                <input type="text" name="mobile" id="mobile" value="<?=$mobile?>" class="col-sm-6">
            </td>
			<td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
       
        
       
        <tr>
        	
            
            <td class="tlabel">银行卡正面</td>
            <td>
                <div class="row provec"> <img src="http://<?=$_SERVER['HTTP_HOST']?>/upload/<?=$bankcardimgu?>"/> </div>
            </td>
			<td class="tlabel">银行卡反面</td>
            <td>
                <div class="row provec"> <img src="http://<?=$_SERVER['HTTP_HOST']?>/upload/<?=$bankcardimgd?>"/> </div>
            </td>
        </tr>
		 <tr>
        	
            
            <td class="tlabel">上传银行卡正面</td>
            <td>
                <input type="file" name="bankcardimgu" id="bankcardimgu" class="col-sm-6">
            </td>
			<td class="tlabel">上传银行卡反面</td>
            <td>
                <input type="file" name="bankcardimgd" id="bankcardimgd" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep3">修改银卡号信息</button>
				&nbsp;
				<!--<button type="submit" class="btn btn-success ml2" id="gostep8">保存并提交开户</button>-->
				</td>
        </tr>
        </tbody>
    </table>
    </form>
    </div>
    

</div><!-- /.page-content -->	
<script>	
seajs.use('apps/admin/bankcard')
function getimgsize(filePath){
	var idnumimgu = document.getElementById(filePath).files[0]
	
	if(idnumimgu == undefined){
		
		return 0;
	}else{
		return idnumimgu.size;
	}
	
} 
function complete($code, $msg) {
	if($code==200){
		top.modalbox.alert($msg,function () {
			window.location.href = PAGE_VAR.SITE_URL+'Qiye/bankquery/';
			return;
		})
	}else{
		top.modalbox.alert($msg,function(){})
		return ;
	}
} 
    
    
   </script>
</body>
</html>