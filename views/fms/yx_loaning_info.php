<?php //tpl("admin_header") ?>
<body>
<!-- <link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css"> -->
<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/bootstrap-grid.min.css">
<link rel="stylesheet" href="/assets/lib/css/zoomify.min.css">
<!--<link rel="stylesheet" href="/assets/lib/css/style.css">-->
<link rel="stylesheet" href="/assets/layui/layui.css">
<!-- <title></title> -->
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/assets/layui/layui.js"></script>
<style>


</style>

<body class="easyui-layout">
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #cccccc;" >
    <div class="row" style=" margin: 20 0 0 60;">
        <div class="form-inline">
            &nbsp;
            &nbsp;
            &nbsp;
            结清日期
            <input type="text" class="form-control" id="date1" name="" value="">
            -
            <input type="text" id="date2" class="form-control" name="" value="">
            <input type="text" id="account" class="form-control" placeholder="请输入登陆用户名！" name="" value="">
            <input type="button" class="form-control" onclick="search()" value="确定">
        </div>
    </div>
</div>
<div data-options="region:'center',title:'银信客户在贷信息管理'" style="padding:5px;background:#eee;">
    <div id="tt" class="easyui-tabs" style="width:500px;height:250px;">
        <div title="客户借款信息汇总" style="padding:20px;display:none;">
            结清客户信息汇总
        </div>
<!--        <div title="逾期客户信息汇总" data-options="closable:true" style="overflow:auto;padding:20px;display:none;">-->
<!--            逾期客户信息汇总-->
<!--        </div>-->

    </div>
</div>
</body>

<script>




</script>

</body>