<?php tpl("admin_header") ?>
<body>
<script src=""></script>
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
	<form id='fileform' method='post' action='' enctype="multipart/form-data" class="form-inline">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?='产品名称';?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6" value="<?php echo isset($sp_mc)?$sp_mc:''?>">
            </td>
            
            <td class="tlabel"><?='产品代码';?></td>
            <td>
                <input type="text" name="dcode" id="dcode" class="col-sm-6" value="<?php echo isset($sp_code)?$sp_code:''?>">
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?= '产品类型';?></td>
            <td>
                <select name="dumenid" id="dumenid" class="col-sm-6">
                    <option value="00" <?php echo isset($sp_type) && $sp_type=='00' ? 'selected':''?>><?= '皆易贷';?></option>
                </select>
            </td>
            
            <td class="tlabel"><?='综合抵押率';?></td>
            <td>
                <div class="col-sm-6 input-group">
                    <input type="text" name="buway" id="buway" class="form-control" value="<?php echo isset($sp_usage)?$sp_usage
                    :''?>"><span class="input-group-addon">%</span>
                </div>
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?='月基础利率';?></td>
            <td>
                <div class="col-sm-6 input-group">
                    <input type="text" name="drate" id="drate" class="form-control" value="<?php echo isset($sp_rate)?$sp_rate
                    :''?>"><span class="input-group-addon">%</span>
                </div>
            </td>
            <td class="tlabel"><?='综合管理技术费';?></td>
            <td>
                <div class="col-sm-6 input-group">
                    <input type="text" name="bfee" id="bfee" class="form-control" value="<?php echo isset($sp_fee)?$sp_fee
                    :''?>"><span class="input-group-addon">%</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="tlabel"><?='平台预收服务费年费率';?></td>
            <td>
                <div class="col-sm-6 input-group">
                    <input type="text" name="bservfee" id="bservfee" class="form-control" value="<?php echo isset($sp_servfee)?$sp_servfee
                    :''?>"><span class="input-group-addon">%</span>
                </div>
            </td>
            <td class="tlabel"><?='返点';?></td>
            <td>
                <div class="col-sm-6 input-group">
                    <input type="text" name="bduring" id="bduring" class="form-control" value="<?php echo isset($sp_during)?$sp_during:''?>">
                    <span class="input-group-addon">%</span>
                </div>
            </td>
        </tr>
            <td colspan="4" class="align-center">
                <?php echo isset($sp_id)?'<input type="hidden" value="'.$sp_id.'" name="spid" id="spid"/>':''?>
                <?php if(isset($sp_id)):?>
                <button class="btn btn-primary" onclick="history.go(-1)" ><?php echo '返回';?></button>
				<?php endif?>
                <button class="btn btn-success" id="gostep8"><?='保存';?></button>
				</td>
        </tr>
        </tbody>
    </table>
    </form>
    </div>
    

</div><!-- /.page-content -->	
<script>
    seajs.use('apps/admin/sp.js')
</script>
</body>
</html>