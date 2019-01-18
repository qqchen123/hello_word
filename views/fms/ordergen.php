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
        	<td class="tlabel"><?=iconv('gb2312','utf-8','订单编号');?></td>
            <td>
                <input type="text" name="dname" id="dname" class="col-sm-6" readonly>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','用款企业');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                	<option value="">--<?=iconv('gb2312','utf-8','请选择企业');?>--</option>

                </select>
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','选择渠道');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','上海渠道1');?></option>
                    <option value="01"><?=iconv('gb2312','utf-8','上海渠道2');?></option>
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','贷款用途');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','短期周转');?></option>
                    <option value="00"><?=iconv('gb2312','utf-8','业务周转');?></option>
                </select>
            </td>
        </tr>
        
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','抵押物');?></td>
            <td>
                <input type="text" name="idno" id="idno" class="col-sm-6">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','放款金额');?></td>
            <td>
                <input type="text" name="idno" id="idno" class="col-sm-6">
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','选择产品');?></td>
            <td>
                <select name="bumenid" id="bumenid" class="col-sm-6">
                    <option value="00"><?=iconv('gb2312','utf-8','7天');?></option>
                    
                </select>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','产品年利率');?></td>
            <td>
                <input type="text" name="dyyzz" id="dyyzz" class="col-sm-6" readonly>
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','产品期限');?></td>
            <td>
               <input type="text" name="begin" id="begin" class="col-sm-6" readonly><?=iconv('gb2312','utf-8','天');?>
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','一次性技术费');?></td>
            <td>
               <input type="text" name="begin" id="begin" class="col-sm-6" readonly>
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','一次性服务费');?></td>
            <td>
                <input type="text" name="begin" id="begin" class="col-sm-6" readonly>
            </td>
        </tr>
         <tr>
        	
            
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','证据匹配');?></td>
            <td  colspan=3>
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','企业信息');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','法人信息');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','法人配偶信息');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','不动产信息');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','汽车信息');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','企业流水');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','个人流水');?></label>&nbsp;
                <label><input type="checkbox" name="dyyzz" id="dyyzz" ><?=iconv('gb2312','utf-8','其他信息');?></label>&nbsp;
                <label><button type="button" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','补足证据');?></button></label>&nbsp;
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','最晚放款时间');?></td>
            <td>
               <input type="text" name="begin" id="begin" class="easyui-datebox">
            </td>
            
            <td class="tlabel"></td>
            <td>
                
            </td>
        </tr>
        <tr>
        	
            <td class="tlabel"><?=iconv('gb2312','utf-8','最晚还款时间');?></td>
            <td>
               <input type="text" name="begin" id="begin" class="easyui-datebox">
            </td>
            
            <td class="tlabel"><?=iconv('gb2312','utf-8','还款金额');?></td>
            <td>
                <input type="text" name="begin" id="begin" class="col-sm-6" >&nbsp;
                <button type="button" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','查看回款计划');?></button>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="align-center">
            	
               
				<button type="submit" class="btn btn-success ml2" id="gostep8"><?=iconv('gb2312','utf-8','保存');?></button>
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