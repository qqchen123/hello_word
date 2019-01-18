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
            <input type="button" class="form-control" onclick="window.history.go(-1)" value="返回🔙">

            状态：
            <select id="status" class="easyui-combobox" name="status" style="width:100px;">
                <option value="">全部</option>
                <option value="苹果 手动出借">苹果 手动出借</option>
                <option value="微信 手动出借">微信 手动出借</option>
                <option value="安卓 手动出借">安卓 手动出借</option>
            </select>
            <input type="text" id="account" class="form-control" placeholder="请输入客户编号！" name="" value="">
            <input type="text" id="account" class="form-control" placeholder="请输入银信账户！" name="" value="">
            <input type="button" class="form-control" onclick="search()" value="确定">

        </div>
    </div>
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">
    <table id="kh_borrow_product"></table>

    <!--投资人-->
    <div id="investor_win" class="easyui-window" title="投资人" style="width:600px;height:400px"
         data-options="modal:true,closed:true">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',split:true" style="height:80px">
                <div class="row" style=" margin: 20 0 0 60;">
                    <div class="form-inline">
                        出借方式：
                        <select id="status" class="easyui-combobox" name="status" style="width:100px;">
                            <option value="">全部</option>
                            <option value="苹果 手动出借">苹果 手动出借</option>
                            <option value="微信 手动出借">微信 手动出借</option>
                            <option value="安卓 手动出借">安卓 手动出借</option>
                        </select>
                        出借时间
                        <input type="text" class="form-control" id="date_start_investor" name="" value="">
                        -
                        <input type="text" id="date_end_investor" class="form-control" name="" value="">
                        <!--                        <input type="text" id="account" class="form-control" placeholder="请输入借款标题！" name="" value="">-->
                        <input type="button" class="form-control" onclick="search()" value="确定">
                    </div>
                </div>
            </div>
            <div data-options="region:'center'">
                <table id="investor"></table>
            </div>
        </div>
    </div>

    <!--    还款计划-->
    <div id="back_plan_win" class="easyui-window" title="还款计划" style="width:600px;height:400px"
         data-options="modal:true,closed:true">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',split:true" style="height:80px">
                <div class="row" style=" margin: 20 0 0 60;">
                    <div class="form-inline">
                        还款日期
                        <input type="text" class="form-control" id="date_start_back_plan" name="" value="">
                        -
                        <input type="text" id="date_end_back_plan" class="form-control" name="" value="">
                        <!--                        <input type="text" id="account" class="form-control" placeholder="请输入借款标题！" name="" value="">-->
                        <input type="button" class="form-control" onclick="search()" value="确定">
                    </div>
                </div>
            </div>
            <div data-options="region:'center'">
                <table id="back_plan"></table>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    $('#kh_borrow_product').datagrid({
        url:'get_all_loan',
        width:'100%',
        // queryParams: {
        //     account: account,
        // },
        frozenColumns:[[
            {field:'operate',title:'操作',align:'left'},
        ]],
        columns:[[
            {field:'真实姓名',title:'借款人',width:100},
            {field:'登录用户名',title:'银信账户',width:100},
            {field:'借款标题',title:'借款标题',width:100},
            {field:'借款金额',title:'借款金额(元)',width:100,align:'right'},
            {field:'年化利率',title:'年化利率(%)',width:100,align:'right'},
            {field:'期数',title:'期数',width:100,align:'right'},
            {field:'还款方式',title:'还款方式',width:100,align:'right'},
            {field:'状态',title:'状态',width:100,align:'right'},
            {field:' ',title:'下期还款本息(元)',width:100,align:'right'},
            {field:' ',title:'下期还款日',width:100,align:'right'},
            {field:' ',title:'是否客户已还',width:100,align:'right'},
            {field:' ',title:'是否渠道已还',width:100,align:'right'},
            {field:' ',title:'是否我司已还',width:100,align:'right'},
            {field:' ',title:'满标日期',width:100,align:'right'},
            {field:' ',title:'结清日期',width:100,align:'right'},
            {field: 'operate',title:'操作',width:'20%',align:'center',halign:'center',
                formatter:function (value,row) {
                    var html = '';
                    let account = row.account;
                    let borrow_title = row.borrow_title;
                    let agreement = row.agreement;
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="back_plan('+'\''+account+'\''+','+'\''+borrow_title+'\''+')" >还款计划 </a>'+'&nbsp;&nbsp';
                    // html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="collect('+'\''+account+'\''+')" >募集 </a>'+'&nbsp;&nbsp';
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="investor('+'\''+account+'\''+','+'\''+borrow_title+'\''+')" >投资人 </a>'+'&nbsp;&nbsp';
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="check_agreement('+'\''+agreement+'\''+')" >查看合同 </a>'+'&nbsp;&nbsp';
                    return html;
                }
            },
        ]]
    });
    //还款计划
    function back_plan(account,borrow_title) {
        // alert(account);
        $('#back_plan').datagrid({
            url:'get_yx_repayment_plan',
            width:'100%',
            queryParams: {
                account: account,
                borrow_title: borrow_title,
            },
            columns:[[
                {field:'期数',title:'期数',width:100},
                {field:'还款日期',title:'还款日期',width:100},
                {field:'应还本息',title:'应还本息(元)',width:100,align:'right'},
                {field:'本金',title:'本金(元)',width:100,align:'right'},
                {field:'利息',title:'利息(元)',width:100,align:'right'},
                {field:'罚息',title:'罚息',width:100,align:'right'},
                {field:' ',title:'阶段',width:100,align:'right'},
                {field:' ',title:'内容',width:100,align:'right'},
                {field:' ',title:'类型',width:100,align:'right'},
                {field:'状态',title:'状态',width:'20%',align:'center',halign:'center'},
            ]]
        });
        $('#back_plan_win').window('open');
    }

    function investor(account,borrow_title) {
        // alert('investor')
        $('#investor').datagrid({
            url:'get_yx_lend_log',
            width:'100%',
            queryParams: {
                account: account,
                borrow_title: borrow_title,
            },
            columns:[[
                {field:'出借用户',title:'出借用户',width:100},
                {field:'出借金额',title:'出借金额(元)',width:100},
                {field:'出借时间',title:'出借时间',width:100,align:'right'},
                {field:'出借方式',title:'出借方式',width:100,align:'right'},
            ]]
        });
        $('#investor_win').window('open');
    }

    //查看合同
    function check_agreement(agreement) {
        window.location.href=agreement;
    }
</script>

</body>