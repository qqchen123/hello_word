<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/bootstrap-grid.min.css">
<link rel="stylesheet" href="/assets/lib/css/zoomify.min.css">
<link rel="stylesheet" href="/assets/layui/layui.css">
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/assets/layui/layui.js"></script>
<style>
	html,body{
		width:100%;
		height:100%
	}
	body{
		background: url(https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1547534719660&di=ce7ed658c8be14a8bd293b0c2587fb7a&imgtype=0&src=http%3A%2F%2Fpic163.nipic.com%2Ffile%2F20180429%2F26422107_220358106081_2.jpg) no-repeat top left;
		background-size: cover;
	}
</style>
<body class="">
	<div style="margin-top: 200px;margin-left: 200px;">
		<h1>
			<?= $look404['msg']?>
			<div id="countdown" style="color: #ff4420"></div>
		</h1>
	</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.3.0/jquery.min.js"></script>
<script src="/assets/lanrenzhijia/js/pic_tab.js"></script>
<script>
    setTimeout(funcName,5000);
    function funcName() {
        window.location.href ='mini_pinggu_order_list_page';
    }

    window.onload=function(){
        var countdown=document.getElementById("countdown");

        var time=3;//30分钟换算成1800秒

        setInterval(function(){
            time=time-1;
            var minute=parseInt(time/60);
            var second=parseInt(time%60);
            countdown.innerHTML='还剩'+second+'秒';
        },1000);
    }
</script>
</body>