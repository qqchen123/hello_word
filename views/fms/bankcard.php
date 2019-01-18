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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/bankcard')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        <input type="hidden" name="idnumber" value="<?php echo $idnumber;?>"/>
		<input type="hidden" name="idnumfile" value="<?php echo $idnumfile;?>"/>
		<tr>
        	<td class="tlabel">姓名</td>
            <td>
                <input type="text" name="name" value="<?=$name?>" readonly=readonly id="name" class="col-sm-6">
            </td>
            
            <td class="tlabel">身份证号</td>
            <td>
                <input type="text" name="idnumber" value="<?=$idnumber?>" readonly=readonly id="idnumber" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	<td class="tlabel">开户行</td>
            <td>
                 <input type="text" name="bankname" id="bankname" class="col-sm-6">
            </td>
            
            <td class="tlabel">银行卡号</td>
            <td>
                <input type="text" name="bankcardNo" id="bankcardNo" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	<td class="tlabel">手机号</td>
            <td>
                <input type="text" name="mobile" id="mobile" class="col-sm-6">
            </td>
            <td class="tlabel"></td>
            <td>
            </td>
        </tr>
       
        <tr>
            <td class="tlabel">银行卡正面</td>
            <td>
                <input type="file" name="bankcardimgu" id="bankcardimgu" class="col-sm-6">
            </td>
			<td class="tlabel">银行卡反面</td>
            <td>
                <input type="file" name="bankcardimgd" id="bankcardimgd" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep3">下一步 手机号信息</button>
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
	return idnumimgu.size;
}
function complete($code, $msg) {
	if($code==200){
		var url = PAGE_VAR.SITE_URL+'Qiye/mobilecheck/';
		url += "<?=$idnumber?>"+'/'+"<?=$name?>"; 
		top.modalbox.alert($msg,function () {
			window.location.href = url;
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