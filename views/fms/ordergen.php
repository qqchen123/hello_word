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
	<form id='fileform' method='post' action='' enctype="multipart/form-data">
	<table class="table table-bordered" style="margin: 0;padding: 0px">
		<tbody>
        
        <tr>
        	<td class="tlabel"><?=iconv('gb2312','utf-8','�������');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6" readonly>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','�ÿ���ҵ');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                	<option value="">--<?=iconv('gb2312','utf-8','��ѡ����ҵ');?>--</option>

                </select>
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','ѡ������');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','�Ϻ�����1');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','�Ϻ�����2');?></option>
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','������;');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','������ת');?></option>
                    <option value="00"><?=iconv('gb2312','utf-8','ҵ����ת');?></option>
                </select>
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','��Ѻ��');?></td>
            <td>
                <input type="text" name="idno" id="idno" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','�ſ���');?></td>
            <td>
                <input type="text" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','ѡ���Ʒ');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','7��');?></option>
                    
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','��Ʒ������');?></td>
            <td>
                <input type="text" name="dyyzz" id="dyyzz" class="col-sm-6" readonly>
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','��Ʒ����');?></td>
            <td>
               <input type="text" name="begin" id="begin" class="col-sm-6" readonly><?=iconv('gb2312','utf-8','��');?>
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','һ���Լ�����');?></td>
            <td>
               <input type="text" name="begin" id="begin" class="col-sm-6" readonly>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','һ���Է����');?></td>
            <td>
                <input type="text" name="begin" id="begin" class="col-sm-6" readonly>
            </td>
        </tr>
         <tr>
        	
            
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','֤��ƥ��');?></td>
            <td  colspan=3>
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��ҵ��Ϣ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������Ϣ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������ż��Ϣ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��������Ϣ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������Ϣ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','��ҵ��ˮ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������ˮ');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','������Ϣ');?></label>&nbsp;
                <label><button type="button" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','����֤��');?></button></label>&nbsp;
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','����ſ�ʱ��');?></td>
            <td>
               <input type="text" name="begin" id="begin" class="easyui-datebox">
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','������ʱ��');?></td>
            <td>
               <input type="text" name="begin" id="begin" class="easyui-datebox">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','������');?></td>
            <td>
                <input type="text" name="begin" id="begin" class="col-sm-6" >&nbsp;
                <button type="button" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','�鿴�ؿ�ƻ�');?></button>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
            	
               
				<button type="submit" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','����');?></button>
				</td>
        </tr>
        </tbody>
    </table>
    </form>
    </div>
    

</div><!-- /.page-content -->	
<script>	
	
    
    
   </script>
</body>
</html>