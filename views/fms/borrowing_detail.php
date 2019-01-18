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
    .jkborrow{
        float: right;
    }

</style>

<body class="easyui-layout">

<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:true" style="height:150px">
        <div class="row" style=" margin: 20 0 0 60;">
            <div class="form-inline">
                <input type="button" class="btn btn-warning" class="form-control" onclick="window.history.go(-1)" value="返回">
            </div>

        </div>
        <table class=" easyui-datagrid" id="phone_list" title="客户信息" style="width:100%;height:100px;"
               data-options="
                    url:'get_one_yx_account_mysql',
                    idField: 'id',
                    rownumbers: true,
                    method:'post',
                    lines: true,
                    border: false,
                    columns:col_data,
                    queryParams: {
                        account: '<?php echo $account;?>',
                    }
        ">
        </table>
    </div>
<!--    <div data-options="region:'north',split:true" style="height:80px">-->
<!--        <table class=" easyui-datagrid" id="phone_list" title="客户信息管理汇总" style="width:100%;height:500px;"-->
<!--               data-options="-->
<!--                    url:'get_yx_account_mysql',-->
<!--                    idField: 'id',-->
<!--                    rownumbers: true,-->
<!--                    method:'post',-->
<!--                    lines: true,-->
<!--                    border: false,-->
<!--                    columns:col_data,-->
<!--                    pagination: true,-->
<!--        ">-->
<!--        </table>-->
<!--    </div>-->

    <div data-options="region:'west',split:true,title:'借款合同信息'" style="width: 50%">
            <a class="btn btn-primary btn-xs p310 jkborrow" href="javascript:void(0)" onclick="borrow_agree_detail()" > 借款合同详情</a>
            <table id="borrowing"></table>
    </div>
    <div data-options="region:'east',title:'还款信息表',split:true" style="width:50%;">
                <table id="finish_detail"></table>
    </div>
    <div id="borrow_agree_detail_win" class="easyui-window" title="合同编号" style="width:500px;height:400px"
         data-options="modal:true,closed:true">
        <div style="margin:30px 0 0 50px;">
            <!-- <button type="button" class="btn btn-success" >打包下载⏬</button> -->
        </div>
        <ul style="margin-top: 30px;" class="list-group">
            <li class="list-group-item">11111111</li>
            <li class="list-group-item">22222222</li>
            <li class="list-group-item">33333333</li>
            <li class="list-group-item">44444444</li>
            <li class="list-group-item">55555555</li>
        </ul>

    </div>
</div>

</body>

<script>
    var global_data = [];
    global_data['loan_title'] = '';

    //客户信息管理汇总
    var col_data = [[
        {field: 'fuserid',title:'我司ID',align:'center',halign:'center'},
        {field: 'channel',title:'渠道ID',align:'center',halign:'center'},
        {field: 'yx_account',title:'银信ID',align:'center',halign:'center'},
        {field: 'reg_phone',title:'银信绑定手机',align:'center'},
        {field: 'ctime',title:'银信开户时间',align:'center',halign:'center'},
        {field: 'idnumber',title:'身份证号码',align:'center',halign:'center'},
        {field: 'name',title:'客户姓名',align:'center',halign:'center'},
    ]];

    //我的借款-还款中
    // function agreement_back_info() {
        $('#borrowing').datagrid({
            url:'get_repaying_data',
            width:'100%',
            columns:[[
                {field:'loan_title',title:'借款标题',width:100,align:'center'},
                {field:'loan_amount',title:'借款金额（元）',width:100,align:'center'},
                {field:'periods',title:'期数',width:100,align:'center'},
                {field:'next_repay_amount',title:'下期还款本息（元）',width:100,align:'center'},
                {field:'next_repay_time',title:'下期还款日',width:100,align:'center'},
                {field:'repay_type',title:'还款方式',width:100,align:'center'},
                {field:'status',title:'状态',width:100,align:'center'},
                {field:'contract', class: 'op', title:'操作',width:100,align:'center'},
            ]],
            queryParams: {
                account: '<?php echo $account;?>',
            },
            onLoadSuccess: function(data){
                // console.log(data);
                var html = '';
                $('td[field="contract"]').each(function(){
                    if (-1 != $(this).text().indexOf('借款合同')) {
                        var obj = JSON.parse($(this).text());
                        console.log(obj);
                        if (typeof obj == 'object' && obj ) {
                            //是json
                            console.log(JSON.parse($(this).text()));
                            if (obj.length == 2) {
                                //单一合同
                                var str = obj[0];
                                var str_array = str.split("-");
                                switch(str_array[1]) {
                                    case '单一合同': //单一合同
                                    // console.log(str_array[0]);
                                        html = '<li class="list-group-item"><span>编号:YXCH'+str_array[2]+'</span><a href="' + obj[1] + '">' + str_array[0] +'</a></li>';
                                        $(".list-group").html(html);
                                        break;
                                    case '新版'://新版多份合同
                                        html = '';
                                        for (var i = 0; i < obj[1].length; i++) {
                                            html += '<li class="list-group-item"><span>'+ obj[1][i]['bidUserName'] +'</span><span style="margin-left:10px;margin-right:10px;">'+obj[1][i]['bidAmount']+'</span><a target="_blank" href="' + obj[1][i]['previewPdfUrl'] + '" download >' + str_array[0] +'</a></li>';
                                        }
                                        $(".list-group").html(html);
                                        break;
                                    default:break;
                                }
                            } else {
                                //新版组合合同

                            }
                            $(this).html('');
                        } else {
                            //不是json
                            $(this).html(html);
                        }
                    }
                });
                $('td[field="loan_title"]').each(function(){
                    if (-1 == $(this).text().indexOf('借款标题')) {
                        console.log($(this).text());
                        var queryParams = $('#finish_detail').datagrid('options').queryParams;  
                        queryParams.loan_title = $(this).text();  
                        $('#finish_detail').datagrid('options').queryParams = queryParams;
                        $('#finish_detail').datagrid('reload');
                    }
                });
            }
        });
        $('#finish_detail').datagrid({
            url:'get_repaying_plan',
            width:'100%',
            columns:[[
                {field:'periods',title:'期数',width:100},
                {field:'repaying_time',title:'还款日期',width:100},
                {field:'should_repaying_amount',title:'应还本息',width:100,align:'right'},
                {field:'principal',title:'本金（元）',width:100,align:'right'},
                {field:'interest',title:'利息（元）',width:100,align:'right'},
                {field:'default_interest',title:'罚息',width:100,align:'right'},
                {field:'status',title:'状态',width:100,align:'right'},
            ]],
            queryParams: {
                account: '<?php echo $account;?>',
                loan_title: '',
            },
            onLoadSuccess: function(data){
                console.log(data);
            }
        });
        // $('#agreement_back_win').window('open');
    // }
    function borrow_agree_detail() {
        $('#borrow_agree_detail_win').window('open');
    }

    function showBaoquanContract(id){
        return false;
        $.ajax({
            url:"baoquan/showContract",
            data:{associateId:id},
            dataType:"json",
            success:function(data){
                if(data.success){
                    window.location.href=data.data.url; 
                }else{
                    Dialog.show(data.msg);
                }
            },
            error:function(data){
                Dialog.show(data.msg);
            }
        });
    }
</script>

</body>