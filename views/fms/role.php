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
        	<td class="tlabel"><?=iconv('gb2312','utf-8','ְ������');?></td>
            <td>
                <input type="text" name="rolename" id="rolename" class="col-sm-6" value="<?=$role_name?>">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','����');?></td>
            <td>
                <input type="text" name="department" id="department" class="col-sm-6" value="<?=$department?>">
            </td>
        </tr>
        
        
         <tr>
        	
            
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','ְ��Ȩ��');?></td>
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
                <!--label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','ְ�ܹ���');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','Ա������');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������ҵ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��ҵ�˲�');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��ҵ�������');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��ҵ��ѯ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��ҵ֤�ݳ�');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��������');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������ѯ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������Ʒ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��Ʒ��ѯ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','����¼��');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��������');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��������');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������ѯ');?></label>&nbsp;
                -->
            </td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
            	<input type='hidden' id='role_id' value='<?=$role_id?>'>
               
				<button type="button" class="btn btn-success ml2" id="addrole"><?=iconv('gb2312','utf-8','����');?></button>
				<?
				if($role_id !='')
            	{
            		?>
            		
            		<button type="button" class="btn btn-success ml2" id="goaddrole"><?=iconv('gb2312','utf-8','��������');?></button>
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
                <th data-options="field:'zn',width:10"><?=iconv('gb2312','utf-8','ְ��');?></th>
                <th data-options="field:'bumen',width:10"><?=iconv('gb2312','utf-8','����');?></th>
                <th data-options="field:'cdate',width:10"><?=iconv('gb2312','utf-8','���ʱ��');?></th>
                <th data-options="field:'qx',width:30"><?=iconv('gb2312','utf-8','ְ��Ȩ��');?></th>
                <th data-options="field:'op',width:10"><?=iconv('gb2312','utf-8','����');?></th>
                
                
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
            error+="<?=iconv('gb2312','utf-8',' ��ɫ������Ϊ��,');?>";
        }
        if(department==""){
            error+="<?=iconv('gb2312','utf-8',' ���Ų���Ϊ��,');?>";
        }
        if(role==undefined){
            error+="<?=iconv('gb2312','utf-8',' Ȩ�޲���Ϊ��.');?>";
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