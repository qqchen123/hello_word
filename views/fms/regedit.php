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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/regeditdo')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        <input type="hidden" name="fuserid" value="<?=$fuserid ?>" />
		<input type="hidden" name="idnumfile" value="<?=$idnumfile ?>" />
        <tr>
        	<td class="tlabel">姓名</td>
            <td>
                <input type="text" name="name" id="name" value="<?=$name ?>" class="col-sm-6">
            </td>
            
            <td class="tlabel">身份证号</td>
            <td>
                <input type="text" name="idnumber" id="idnumber" value="<?=$idnumber ?>" readonly=readonly class="col-sm-6">
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
                    <option value="00" <?php if($utype == '00'){echo "selected";}?>>企业</option>
                    <option value="01" <?php if($utype == '01'){echo "selected";}?>>个人</option>
                </select>
            </td>
            <td class="tlabel">性别</td>
            <td>
                <select name="sex" id="sex" class="col-sm-6">
                    <option value="01" <?php if($sex == '01'){echo "selected";}?>>男</option>
                    <option value="00" <?php if($sex == '00'){echo "selected";}?>>女</option>
                </select>
            </td>
            
           
        </tr>
        <tr>
        	
            
            
            <td class="tlabel">身份证有效期</td>
            <td>
                <input type="text" name="idnumvalid" id="idnumvalid" value="<?=$idnumvalid ?>" class="easyui-datebox">
            </td>
			<td class="tlabel">户口所在地</td>
            <td>
                <input type="text" name="Hkadr" id="Hkadr" value="<?=$Hkadr?>" readonly=readonly class="col-sm-6">
            </td>
        </tr>
       
        <tr id="companynameaddress" <?php if($utype == '01'){echo "style='display:none;'";}?>>
        	
            <td class="tlabel">地址</td>
            <td>
               <input type="text" name="idnumaddress" id="idnumaddress" value="<?=$idnumaddress ?>" class="col-sm-6">
            </td>
            <td class="tlabel">公司名称</td>
            <td>
                <input type="text" name="companyname" id="companyname" value="<?=$companyname ?>" class="col-sm-6">
            </td>
            
        </tr>
       <tr>
        	
            
            <td class="tlabel">身份证正面</td>
            <td>
               <div class="row provec"> <img src="http://<?=$_SERVER['HTTP_HOST']?>/upload/<?=$idnumimgu?>"/> </div>
            </td>
			<td class="tlabel">身份证反面</td>
            <td>
                <div class="row provec"> <img src="http://<?=$_SERVER['HTTP_HOST']?>/upload/<?=$idnumimgd?>"/> </div>
            </td>
        </tr>
        <tr>
        	
            
            <td class="tlabel">上传身份证正面</td>
            <td>
                <input type="file" name="idnumimgu"  id="idnumimgu" class="col-sm-6">
            </td>
			<td class="tlabel">上传身份证反面</td>
            <td>
                <input type="file" name="idnumimgd" id="idnumimgd" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep3">修改信息</button>
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
$("#utype").change(function(){
	var utype = $(this).val();
	if(utype == '01'){
		$("#companynameaddress").hide();
	}
	if(utype == '00'){
		$("#companynameaddress").show();
	}
});	
    
    
   </script>
</body>
</html>