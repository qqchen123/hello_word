<?php tpl("admin_applying") ?>

<style type="text/css">
   #serchbox {
     width: 1400px;
     height: 100px;
     background-color:#f8f8f8;
    }
	span {
      font-family: '宋体';
      font-size: 22px;
      margin-left: 20px;
	}
	input ,select {
		width: 140px;
		height: 30px;
		border-radius:10px;
		margin-left: 50px;
	}
	select {
     appearance:none;
	-moz-appearance:none;
	-webkit-appearance:none;
	background: url('/assets/images/web/icons/1.png') right 2px 
	 top 1px no-repeat ;
	}

	.cardid {
		width: 135px;
		height:30px;
	}
    div {
     margin-left: 37px;
    }
    .names5 {
    	margin-left: 14px;
    }
    .names6 {
    	margin-left: 84px;

    }
    .dsf {
         margin-left: 20px;
    }
    .infos {
    	margin-top: 15px;
    	margin-left: 8px;
    }
    .search {
     width:88px;
     height:30px;
     font-size:20px;
     font-weight:normal;
     border: 1px #555657 solid;
     background: -webkit-linear-gradient(#686a6b,#e5e8eb);  
     background: -o-linear-gradient(#686a6b,#e5e8eb);  
     background: -moz-linear-gradient(#686a6b,#e5e8eb);
     background: -mos-linear-gradient(#686a6b,#e5e8eb);
     background: linear-gradient(#686a6b,#e5e8eb);
    }
    .blursearch {
     width:92px;
     height:30px;
     font-size:16px;
     font-weight:normal;
     border: 1px #555657 solid;
     background: -webkit-linear-gradient(#686a6b,#e5e8eb);  
     background: -o-linear-gradient(#686a6b,#e5e8eb);  
     background: -moz-linear-gradient(#686a6b,#e5e8eb);
     background: -mos-linear-gradient(#686a6b,#e5e8eb);
     background: linear-gradient(#686a6b,#e5e8eb);
    }
</style>

<div id="serchbox" class="serch">
	 <div class="infos" style="display: inline-block;">
	 	<div class="names" style="display: inline-block;margin-left: 7px;">
	 	<span>姓名</span>
	 	<input type="text" name=" "/> 
	    </div>
	 <div class="names2" style="display: inline-block;">
	 	<span>客户编号</span>
	 	<input type="text" name=" "/>
	 </div>
	 <div class="names3" style="display: inline-block;margin-left: 50px;">
	 	<span>渠道编号</span>
	 	<select name=" " >
	 		<option> </option>
	 	</select>
	 </div>
	   <div class="names4" style="display: inline-block;">
	 	<span>身份证编号</span>
	 	<input type="text" name=" " class="cardid" style="width: 190px;" />
	    </div>
	 </div>
	  <div class="phoneno" style="display: inline-block;margin-top: 10px;margin-left: 10px;">
	  	<div class="names5" style="display: inline-block;margin-left:2px;">
	 	<span>手机号</span>
	 	 <div style="display: inline-block;width: 132px;height: 31px; ">
	 		<input type="text" name=" " style="margin-left: -7px;" />
	 	 </div>
	 </div>
	 <div class="names6" style="display: inline-block;margin-left: 40px;">
	 	<span>时间</span>
	 	<div style="display: inline-block;margin-left: 40px;">
	 	<input type="date" name=" "/><span>至</span>
	 	<input type="date" name=" " class="dsf" />
	 	</div>
	 </div>
	 <div class="names7" style="display:inline-block;">
	 	<input type="button" value="搜索"  class="search" style="margin-left:-3px;">
	 	<input type="button" value="模糊搜索" id="blursearch" class="blursearch" style="margin-left:20px;">
	 </div>
    </div>
</div>
<div style="background-color:#f8f8f8;width:1400px;height: 50px;display:none;" id="shsear" >
	<div style="width: 1300px;margin-left: 60px;">
		<div style="display: inline-block;width:700px;height: 40px;margin-left: 150px;margin-top: 7px;">
		<input style="width:632px;height:35px;" type="text" name=" " class="searchform" />
	</div>
	<div style="display:inline-block;margin-left:40px;">
		<input type="button" value="搜索"   class="search" style="margin-left:10px;">
	 	<input type="button" value="精确搜索" id="searchclik" class="blursearch" style="margin-left:20px;">
	  </div>
	</div>
</div >

<script type="text/javascript">
	$(document).ready(function(){
	  $("#blursearch").click(function(){
	  	$("#shsear").show();
	     $("#serchbox").hide();
	       });
		    $("#searchclik").click(function(){
		  	$("#shsear").hide()
	        $("#serchbox").show();
	      });
	   });
</script>
<?= tpl('admin_foot') ?>