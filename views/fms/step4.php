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
	<form id='fileform' method='post' action='<?php echo site_url('qiye/step5')?>' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','���˲�������ַ');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','�ϴ�����֤');?></td>
            <td>
                <input type="file" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','����������');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','סլ');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','����');?></option>
                    <option value="02"><?=iconv('gb2312','utf-8','��ס����');?></option>
                    <option value="03"><?=iconv('gb2312','utf-8','�칫¥');?></option>
                    <option value="04"><?=iconv('gb2312','utf-8','�ڵ�');?></option>
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','����������');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','����');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','��ҵ');?></option>
                    
                </select>
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','���޵�Ѻ');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','��');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','��');?></option>
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','�Ƿ����');?></td>
            <td>
            	<select name="bumenid" id="bumenid" class="col-sm-6">
                <option value="00"><?=iconv('gb2312','utf-8','�巿');?></option>
                <option value="01"><?=iconv('gb2312','utf-8','һ��');?></option>
                <option value="02"><?=iconv('gb2312','utf-8','����');?></option>
                </select>
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','��ֵ');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','��Ѻ���');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','��Ѻ����');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6">
            </td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','����');?></td>
            <td>
                <input type="text" name="dlxr" id="dlxr" class="col-sm-6">
                
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','�Ƿ����˶�ͯ��');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','��');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','��');?></option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="button" class="btn btn-success ml2" id="gostep3"><?=iconv('gb2312','utf-8','���沢������Ӳ�������Ϣ');?></button>
				
				</td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
            	
                <button type="submit" class="btn btn-success ml2" id="gostep5"><?=iconv('gb2312','utf-8','��һ�� ����������Ȩ��Ϣ');?></button>
				&nbsp;
				<button type="submit" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','���沢�ύ����');?></button>
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
                <th data-options="field:'sn',width:5"><?=iconv('gb2312','utf-8','���');?></th>
                <th data-options="field:'addr',width:50"><?=iconv('gb2312','utf-8','��ַ');?></th>
                <th data-options="field:'type',width:10"><?=iconv('gb2312','utf-8','����');?></th>
                <th data-options="field:'guishu',width:10"><?=iconv('gb2312','utf-8','����');?></th>
                <th data-options="field:'sfdy',width:10"><?=iconv('gb2312','utf-8','���޵�Ѻ');?></th>
                <th data-options="field:'sfrd',width:10"><?=iconv('gb2312','utf-8','�Ƿ����');?></th>
                <th data-options="field:'guzhi',width:20"><?=iconv('gb2312','utf-8','��ֵ');?></th>
                <th data-options="field:'dyje',width:20"><?=iconv('gb2312','utf-8','��Ѻ���');?></th>
                <th data-options="field:'dycs',width:20"><?=iconv('gb2312','utf-8','��Ѻ����');?></th>
            </tr>
            </thead>
        </table>
    </div>

</div><!-- /.page-content -->	
<script>	
	$('#acclist').datagrid({
        url:"<?php echo site_url('qiye/bdclist?corpid="+$.trim($("#corpid").val())+"')?>",
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });
    
    
   </script>
</body>
</html>