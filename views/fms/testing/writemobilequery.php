<?php tpl("admin_applying") ?>

	<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
    <link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
    <script type="text/javascript" src="/assets/lib/js/nunjucks.js"></script>

<style type="text/css">
	.sysouts {
		width: 100%;
		height: 50px;
        background: -webkit-linear-gradient(#0f55b5,#b0cffa,#0f55b5);  
        background: -o-linear-gradient(#0f55b5,#b0cffa,#0f55b5);
        background: -moz-linear-gradient(#0f55b5,#b0cffa,#0f55b5);
        background: -mos-linear-gradient(#0f55b5,#b0cffa,#0f55b5);
        background: linear-gradient(#0f55b5,#b0cffa,#0f55b5);
	}

</style>



<div id="submit-dlg" style="border:2px #CCDDFF solid;height:800px;padding:10px;top:50px;" class="easyui-dialog" data-options="modal:true" >
    <form id="submitForm" method="post" novalidate enctype="multipart/form-data"> 
         <div id="moblie" class="moblie_from">
         	 <div class="sysouts">
         	 	<h1 style="font-size:25px;margin-top:-10px;padding-top:12px;" align="center">机构系统开户申请表</h1>
         	 </div>
         </div>
    </form>
</div>

<script type="text/javascript">
	//元素 模板
	var tpl_array = {
	lable_tpl:'lable',
	input_text_tpl:'textinput',
	input_file_tpl:'fileinput',
	input_select_tpl:'ordinaryselect',
	button: 'button',
	};
	globalData['tpl'] = [];
	function load_tpl() {
	for (item in tpl_array) {
		$.ajax({ 
	        type : "get", 
	        url : AJAXBASEURL + tplPath + 'v001/' + tpl_array[item], 
	        async : false, 
	        success : function(data){ 
	            globalData['tpl'][item] = data;
	         } 
	     }); 
	 }
   }
	load_tpl();
	globalData['edit_box_title'] = '编辑客户信息';
	globalData['edit_box_btn'] = '提交';
	globalData['page_type'] = 'reg';

</script>

<script type="text/javascript">
     
      var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
      var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
      var globalData = [];//用于装载全局js变量
      var phpData = [];//php返回的内容
	   $("#moblie").append(
	    nunjucks.render(
	        AJAXBASEURL + tplPath + 'v001/panel', 
	        {fillin:'手机卡'}
	          )
    	 ); 
    
    
</script>


<script >
		//元素 模板
		var tpl_array = {
			lable_tpl:'lable',
			input_text_tpl:'textinput',
			input_file_tpl:'fileinput',
			input_select_tpl:'ordinaryselect',
			button: 'button',
		};
		globalData['tpl'] = [];
		function load_tpl() {
			for (item in tpl_array) {
				$.ajax({ 
			        type : "get", 
			        url : AJAXBASEURL + tplPath + 'v001/' + tpl_array[item], 
			        async : false, 
			        success : function(data){ 
			            globalData['tpl'][item] = data;
			         } 
			     });
			 }
		 }										
	load_tpl();
	
</script>

<?= tpl('admin_foot') ?>