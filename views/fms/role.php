<?php tpl("admin_header") ?>
<?
$role_name = '';
$department = '';
if(isset($role) && is_array($role))
{//var_dump($role);
	$role_name = $role[0]['role_name'];
	$department = @$role[0]['department'];
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
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','职能名称');?></td>
            <td>
                <input type="text" name="rolename" id="rolename" class="col-sm-6" value="<?=$role_name?>">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','部门');?></td>
            <td>
                <input type="text" name="department" id="department" class="col-sm-6" value="<?=$department?>">
            </td>
        </tr>
        
        
         <tr>
        	
            
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','职能权限');?></td>
            <td  colspan=3>
            	<?
            	for($i=0;$i<count($clist);$i++)
            	{
            		$checked = '';
            		if(isset($priv) && is_array($priv))
					{
            			for($j=0;$j<count($priv);$j++)
            			{
            				
            				if($clist[$i]['id']==$priv[$j]['role_menuid'])
            				{
            					$checked = 'checked';
            				}
            			}
            		}
            		?>
            		<label><input type="checkbox" name="role" id="role<?=$i?>" value="<?=$clist[$i]['id'];?>" <?=$checked?>><?=$clist[$i]['text'];?></label>&nbsp;
            		<?
            	}
            	?>
                <!--label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','职能管理');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','员工管理');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','新增企业');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','企业核查');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','企业开户审核');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','企业查询');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','企业证据池');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','新增渠道');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','渠道查询');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','新增产品');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','产品查询');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','订单录入');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','订单初审');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','订单复审');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','订单查询');?></label>&nbsp;
                -->
            </td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
            	<input type='hidden' id='role_id' value='<?=$role_id?>'>
               
				<button type="button" class="btn btn-success ml2" id="addrole"><?=iconv('gb2312','utf-8','保存');?></button>
				<?
				if($role_id !='')
            	{
            		?>
            		
            		<button type="button" class="btn btn-success ml2" id="goaddrole"><?=iconv('gb2312','utf-8','返回新增');?></button>
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
        <table id="acclist">
            <thead>
            <tr>
                <th data-options="field:'zn',width:10"><?=iconv('gb2312','utf-8','职能');?></th>
                <th data-options="field:'bumen',width:10"><?=iconv('gb2312','utf-8','部门');?></th>
                <th data-options="field:'cdate',width:10"><?=iconv('gb2312','utf-8','添加时间');?></th>
                <th data-options="field:'qx',width:30"><?=iconv('gb2312','utf-8','职能权限');?></th>
                <th data-options="field:'op',width:10"><?=iconv('gb2312','utf-8','操作');?></th>
                
                
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->	
<script>	
	$('#acclist').datagrid({
        url:"<?php echo site_url('priv/queryrole')?>",
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });
    
    function editrole(roleid)
    {
    	location.href="<?php echo site_url('priv/role?role_id="+roleid+"')?>";
    }
    
    $('#goaddrole').on('click', function () {
    	location.href="<?php echo site_url('priv/role')?>";
    });
    
    $('#addrole').on('click', function () {
    	var rolename=$.trim($("#rolename").val());
        var department=$.trim($("#department").val());
        var role=$("input:checkbox:checked").val();
        var role_id=$.trim($("#role_id").val());
        var error="";
        if(rolename==""){
            error+="<?=iconv('gb2312','utf-8',' 角色名不能为空,');?>";
        }
        if(department==""){
            error+="<?=iconv('gb2312','utf-8',' 部门不能为空,');?>";
        }
        if(role==undefined){
            error+="<?=iconv('gb2312','utf-8',' 权限不能为空.');?>";
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
              url: "<?php echo site_url('priv/addrole')?>",
              data:"rolename="+rolename+"&department="+department+"&role="+rolechk+"&role_id="+role_id,
              async:false,
              dataType: "json",
              success: function(data){
                  if(data.ret){
                    alert(data.msg);
         			location.href="<?php echo site_url('priv/role')?>";
                      
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