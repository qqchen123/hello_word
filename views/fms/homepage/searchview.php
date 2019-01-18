<html>
<?php tpl("admin_applying") ?>
<body>
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<script type="text/javascript" src="/assets/lib/js/nunjucks.js"></script>
<style type="text/css">
    #user_detail {
        font-size: 13px;
    }
    .dn {
        display: none;
    }
	.search_head {
      width:1700px;
      height:40px;
      background: -webkit-linear-gradient(#ffffff,#b4b5b6);  
      background: -o-linear-gradient(#ffffff 40%,#b4b5b6); 
      background: -moz-linear-gradient(#ffffff 50%,#b4b5b6);
      background: -mos-linear-gradient(#ffffff 50%,#b4b5b6);
      background: linear-gradient(#ffffff,#b4b5b6);

	}
    .search_midder {
      display:inline-block;
      float:left;
      margin-left: 10%;
    }

	.search_box {
	  display: inline-block;
      width:1000px;
      height:30px;
      margin-top:0.7%;
      border-radius:10px;
      background-color: #c9c9c9;
      background: -webkit-linear-gradient(#b4b5b6 ,#f3f6f8);  
      background: -o-linear-gradient(#b4b5b6,#f3f6f8); 
      background: -moz-linear-gradient(#b4b5b6,#f3f6f8);
      background: -mos-linear-gradient(#b4b5b6,#f3f6f8);
      background: linear-gradient(#b4b5b6 ,#f3f6f8);
	}
    .search_div {
     display: inline-block;
     margin-left:10%;
    }
    .search_btn {
      display: inline-block;
      width:100px;
      height:30px;
      border-radius:12px;
      background:#eeeeee;
      border-color:#d2d2d2;
      margin-top: 4%;
      font-size: 14px;
    }
     .search_btn:hover {
     	line-height: 20px;
      background: -webkit-linear-gradient(#ffffff,#b4b5b6);  
      background: -o-linear-gradient(#ffffff 50%,#b4b5b6); 
      background: -moz-linear-gradient(#ffffff 50%,#b4b5b6);
      background: -mos-linear-gradient(#ffffff 50%,#b4b5b6);
      background: linear-gradient(#ffffff ,#b4b5b6);
     }

    #worklist {
        width: 100%;
        height: 400px;
    }
    #me_remind_box {
        width: 49%;
        height: 400px;
    }
    #remind_me_box {
        width: 49%;
        height: 400px;
    }
</style>

<div class="search_head">
	<div class="search_midder" style="">
		 <input type="text" name="codition" class="search_box">
	</div>
    <div class="search_div">
    	<input type="submit" value="搜 索" class="search_btn">
    </div> 
</div>
<div id="easyuilist" class=""></div>
<div id="worklist">
    <!-- <iframe id="me_remind_box" src=""></iframe> -->
    <!-- <iframe id="remind_me_box" src=""></iframe> -->
</div>
</div>
<div id="user_detail" class="dn"></div>

<script type="text/javascript"> 
    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
    var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
    var globalData = [];
    var statusColor = JSON.parse('<?= $statusColor ?>');

    // $('#me_remind_box').attr('src', AJAXBASEURL+'/WorkLog/list_me_remind');
    // $('#remind_me_box').attr('src', AJAXBASEURL+'/WorkLog/list_remind_me');
</script>
<script type="text/javascript" src="/assets/apps/homepage.js?s=<?= time()?>"></script>
<script type="text/javascript">
    $("#easyuilist").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/datagrid_basic', 
            {datagrid_id:'tt', target:'homepage'}
        )
    );

    $('#tt').attr('style', 'width:100%;min-height:400px;border:1px red solid;');

    //查询按钮
    $('.search_btn').on('click', function(){
        $('#easyuilist').removeClass('dn');
        $('#worklist').removeClass('dn');
        $('#tt').datagrid({
            showHeader:true,
            showFooter:true,
            pagination:true, 
        });
        $('#user_detail').addClass('dn');
        console.log($('.search_box').val());
        $('#tt').datagrid('load', {condition: $('.search_box').val()});
    });
</script>
<script type="text/javascript">
    //显示按钮
    function showBtn() {
        $('td[field="id"]').each(function(){
            var html = '';
            if ($(this).children('div').text() + 0 > 0) {
                $(this).children('div').html('<button data-id="'+ $(this).children('div').text() +'" class="detail">详情</button>');
            }
        });
    }

    //展示详情
    function showdetail(id) {
        $('#easyuilist').addClass('dn');
        $('#worklist').addClass('dn');
        $("#user_detail").html('');
        console.log('显示详情');
        $("#user_detail").append(
            nunjucks.render(
                AJAXBASEURL + tplPath + 'fms/homepage/deilpage', 
                {id:id}
            )
        );
        $('#user_detail').removeClass('dn');
    }

    $('#easyuilist').on('click', '.detail', function(){
        showdetail($(this).attr('data-id'));
    });
</script>
<?= tpl('admin_foot') ?>
<script type="text/javascript">
    $('#tt').datagrid({
        showHeader:false, 
        showFooter:false,
        pagination:false,
    });

</script>