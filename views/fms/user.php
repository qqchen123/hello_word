<?php tpl("admin_header") ?>
<?
$username = '';
$userid = '';
$userrole = '';
$dzt = '';
$mobile = '';
$idno = '';
$email = '';
$rzdate = '';
if(isset($user) && is_array($user))
{
	$username = $user[0]['username'];
	$userid = $user[0]['userid'];
	$userrole = $user[0]['userrole'];
	$dzt = $user[0]['dzt'];
	$mobile = $user[0]['mobile'];
	$idno = $user[0]['idno'];
	$email = $user[0]['email'];
	$rzdate = $user[0]['rzdate'];
}
?>
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
<div class="easyui-layout" data-options="fit:true,border:false" style="max-height: 700px;">
	<div region="north" data-options="border:false" style="padding: 8px 20px;">
	<form id='fileform' method='post' action='' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px;">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','员工姓名');?></td>
            <td>
                <input type="text" name="username" id="username" class="col-sm-6" value="<?=$username?>">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','工号');?></td>
            <td>
            	<?
            	if($userid != '')
            	{
            		?>
            		<input type="text" name="userid" id="userid" class="col-sm-6" value="<?=$userid?>" readonly>
            		<?
            	}
            	else
            	{
            		?>
            		<input type="text" name="userid" id="userid" class="col-sm-6">
            		<?
            	} 
            	?>
                
            </td>
        </tr>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','职能权限');?></td>
            <td>
                <select name="userrole" id="userrole" class="col-sm-6">
                    <option value="">--<?=iconv('gb2312','utf-8','请选择');?>--</option>
                    <?
                    for($i=0;$i<count($clist);$i++)
                    {
                    	if($userrole == $clist[$i]['role_id'])
                    	{
                    		?>
                    		<option value="<?=$clist[$i]['role_id']?>" selected><?=$clist[$i]['role_name']?>[<?=$clist[$i]['department']?>]</option>
                    		<?
                    	}
                    	else
                    	{
                    		?>
                    		<option value="<?=$clist[$i]['role_id']?>"><?=$clist[$i]['role_name']?>[<?=$clist[$i]['department']?>]</option>
                    		<?
                    	}
                    	
                    }
                    ?>
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','状态');?></td>
            <td>
            	<?
            	$active = "";
            	$deactive = "";
            	if($dzt=='01') $active = "selected";
            	if($dzt=='00') $deactive = "selected";
            	?>
                <select name="dzt" id="dzt" class="col-sm-6">
                	<option value="01" <?=$active?>><?=iconv('gb2312','utf-8','启用');?></option>
                	<option value="00" <?=$deactive?>><?=iconv('gb2312','utf-8','禁用');?></option>
                </select>
            </td>
        </tr>
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','身份证');?></td>
            <td>
                <input type="text" name="idno" id="idno" class="col-sm-6" value="<?=$idno?>">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','手机号');?></td>
            <td>
                <input type="text" name="mobile" id="mobile" class="col-sm-6" value="<?=$mobile?>">
            </td>
        </tr>
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','邮箱');?></td>
            <td>
                <input type="text" name="email" id="email" class="col-sm-6" value="<?=$email?>">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','入职时间');?></td>
            <td>
                <input type="text" name="rzdate" id="rzdate" class="easyui-datebox" value="<?=$rzdate?>">
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
            	<button type="button" class="btn btn-success ml2" id="adduser"><?=iconv('gb2312','utf-8','保存');?></button>
            	<?
            	if($userid !='')
            	{
            		?>
            		<input type='hidden' id='update' value='01'>
            		<button type="button" class="btn btn-success ml2" id="goadduser"><?=iconv('gb2312','utf-8','返回新增');?></button>
            		<?
            	}
            	else
            	{
            		?>
            		<input type='hidden' id='update' value='00'>
            		<?
            	}
            	?>
               
				
				</td>
        </tr>
        </tbody>
    </table>
    </form>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table id="userlist">
            <thead>
            <tr>
                <th data-options="field:'username',width:10"><?=iconv('gb2312','utf-8','姓名');?></th>
                <th data-options="field:'userid',width:10"><?=iconv('gb2312','utf-8','工号');?></th>
                <th data-options="field:'userrole',width:10"><?=iconv('gb2312','utf-8','职能');?></th>
                <th data-options="field:'mobile',width:10"><?=iconv('gb2312','utf-8','手机');?></th>
                <th data-options="field:'rzdate',width:10"><?=iconv('gb2312','utf-8','入职时间');?></th>
                <th data-options="field:'dzt',width:10"><?=iconv('gb2312','utf-8','状态');?></th>
                <th data-options="field:'op',width:10"><?=iconv('gb2312','utf-8','操作');?></th>
                
                
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->	
<script>	
	$('#userlist').datagrid({
        url:"<?php echo site_url('priv/userlist')?>",
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });
    
    function edituser(userid)
    {
    	location.href="<?php echo site_url('priv/user?userid="+userid+"')?>";
    }
    
    $('#goadduser').on('click', function () {
    	location.href="<?php echo site_url('priv/user')?>";
    });
    
    $('#adduser').on('click', function () {
    	var username=$.trim($("#username").val());
        var userid=$.trim($("#userid").val());
        var userrole=$.trim($("#userrole").val());
        var dzt=$.trim($("#dzt").val());
        var idno=$.trim($("#idno").val());
        var mobile=$.trim($("#mobile").val());
        var email=$.trim($("#email").val());
        var rzdate=$.trim($("#rzdate").datebox('getValue'));
        var update=$.trim($("#update").val());
        var error="";
        if(username==""){
            error+="<?=iconv('gb2312','utf-8',' 姓名不能为空,');?>";
        }
        if(userid==""){
            error+="<?=iconv('gb2312','utf-8',' 工号不能为空,');?>";
        }
        if(userrole==''){
            error+="<?=iconv('gb2312','utf-8',' 职能不能为空.');?>";
        }
        if(error){
            alert(error);
            error="";
        }else{
        	var rolechk = [];
            $("input:checkbox:checked").each(function(){
            	rolechk.push($(this).val()); 
            });
        	$.ajax({
              type: "post",
              url: "<?php echo site_url('priv/adduser')?>",
              data:"username="+username+"&userid="+userid+"&userrole="+userrole+"&idno="+idno+"&dzt="+dzt+"&mobile="+mobile+"&email="+email+"&rzdate="+rzdate+"&update="+update,
              async:false,
              dataType: "json",
              success: function(data){
                  if(data.ret){
                    alert(data.msg);
         			location.href="<?php echo site_url('priv/user')?>";
                      
                  }else{
                      alert(data.msg);
                  }
              }
          	});
      	}
    });
   </script>
</body>
</html>