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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/bankcardadddo')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
		<tr>
            <td class="tlabel">身份证号</td>
            <td>
                <input type="text" name="idnumber" value=""  id="idnumber" class="col-sm-6">
            </td>
			<td class="tlabel">姓名</td>
            <td>
                <input type="text" name="name" value="" readonly=readonly id="name" class="col-sm-6">
            </td>
        </tr>
		<tr>
        	<td class="tlabel">卡用途</td>
            <td>
                <select name="uktype" id="utype" class="col-sm-6">
                    <option value="00" >打款</option>
                    <option value="01" >扣款</option>
                </select>
            </td>
            
            <td class="tlabel"></td>
            <td>
                
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
            	
                <button type="submit" class="btn btn-success ml2" id="gostep3">保存</button>
				&nbsp;
				<!--<button type="submit" class="btn btn-success ml2" id="gostep8">保存并提交开户</button>-->
				</td>
        </tr>
        </tbody>
    </table>
    </form>
    </div>
    

</div><!-- /.page-content -->	
<iframe src="#" frameborder="0" style="display: none" name="uploadFrame"></iframe>
<script>
$("#idnumber").blur(function(){
	var idnumber = $(this).val();
	$.post(
            PAGE_VAR.SITE_URL+'Qiye/checkidnumber',
			 { idnumber: idnumber}, 
            function (response) {
				
                 if(response.responseCode==200){
					top.modalbox.alert('身份证号不存在，请先开户',function(){
						window.location.href = PAGE_VAR.SITE_URL+'Qiye/check';
					});
                    return ;
                }else{
					$("#name").val(response.responseMsg);
				}
            },'json'
        );
});
 seajs.use('apps/admin/bankcard')
function getimgsize(filePath){
	var idnumimgu = document.getElementById(filePath).files[0]
	return idnumimgu.size;
} 
function complete($code, $msg) {
	if($code==200){
		var url = PAGE_VAR.SITE_URL+'Qiye/bankquery/';
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