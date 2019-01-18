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
	<form id='fileform' method='post' action="<?php echo site_url('qiye/idnumber')?>" enctype="multipart/form-data" target="uploadFrame">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
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
        	<td class="tlabel">出生日期</td>
            <td>
                <input type="text" name="birthdate" value="<?=$birthdate?>" readonly=readonly id="birthdate" class="col-sm-6">
            </td>
            
            <td class="tlabel">年龄</td>
            <td>
                <input type="text" name="age" value="<?=$age?>" readonly=readonly id="age" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
        	<td class="tlabel">类别</td>
            <td>
                <select name="utype" id="utype" class="col-sm-6">
                    <option value="00">企业</option>
                    <option value="01">个人</option>
                </select>
            </td>
            <td class="tlabel">渠道</td>
            <td>
                <select name="qudao" id="qudao" class="col-sm-6">
				<?foreach($qudaoinfo as $k => $v){
					//var_dump($k,$v);exit;
					?>
                    <option value="<?=$v['q_code']?>"><?=$v['q_name']?></option>
				<?}?>
                    
                </select>
            </td>
        </tr>
       
        <tr id="companynameaddress">
        	
            <td class="tlabel">地址</td>
            <td>
               <input type="text" name="idnumaddress" id="idnumaddress" placeholder="个人填写身份证地址" class="col-sm-6">
            </td>
             <td class="tlabel">公司名称</td>
            <td>
                <input type="text" name="companyname" id="companyname"  class="col-sm-6">
            </td>
        </tr>
       <tr>
        	<td class="tlabel">身份证有效期</td>
            <td>
                <input type="text" name="idnumvalid" id="idnumvalid" class="easyui-datebox">
            </td>
            <td class="tlabel">性别</td>
            <td>
                <select name="sex" id="sex" class="col-sm-6">
                    <option value="01">男</option>
                    <option value="00">女</option>
                </select>
            </td>
        </tr>
        <tr>
		<tr>
            <td class="tlabel">户口所在地</td>
            <td>
                <input type="text" name="Hkadr" id="Hkadr" value="<?=$Hkadr?>" readonly=readonly class="col-sm-6">
            </td>
			<td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
            <td class="tlabel">上传身份证正面</td>
            <td>
                <input type="file" name="idnumimgu" id="idnumimgu" class="col-sm-6">
            </td>
			<td class="tlabel">上传身份证反面</td>
            <td>
                <input type="file" name="idnumimgd" id="idnumimgd" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
                <button type="submit" class="btn btn-success ml2" id="gostep3">提交</button>
				&nbsp;
			</td>
        </tr>
        </tbody>
    </table>
    </form>
</div>

</div><!-- /.page-content -->
<iframe src="#" frameborder="0" style="display: none" name="uploadFrame"></iframe>
<script>	
$("#utype").change(function(){
	var utype = $(this).val();
	if(utype == '01'){
		$("#companynameaddress").hide();
	}
	if(utype == '00'){
		$("#companynameaddress").show();
	}
});
seajs.use('apps/admin/idnumber')

function getimgsize(filePath){
	var idnumimgu = document.getElementById(filePath).files[0]
	return idnumimgu.size;
}
function complete($code, $msg) {
	if($code==200){
		var url = PAGE_VAR.SITE_URL+'Qiye/check/';
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