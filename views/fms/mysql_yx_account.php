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
    <div class="input-group col-md-3" style="margin: 0 auto; margin-top: 17px;">
        <input type="text" id="word" class="form-control"placeholder="姓名、手机、身份证、客户ID、银信账户！" / >
        <span class="input-group-btn">
            <button class="btn btn-info btn-search" onclick="search()">确认</button>
            <!--            <button class="btn btn-info btn-search" onclick="show_login()" >登陆</button>-->
        </span>
    </div>
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">
    <table class=" easyui-datagrid" id="phone_list" title="银信账户信息" style="width:100%;height:500px;"
           data-options="
                    url:'get_yx_account_mysql',
                    idField: 'id',
                    queryParams: {
                        m_db_name: 'account_detail',
                    },
                    rownumbers: true,
                    method:'post',
                    lines: true,
                    border: false,
                    columns:col_data,
                    pagination: true
        ">
    </table>
    <div id="back_plan_win" class="easyui-window" title="还款计划" style="width:70%;height:70%"
         data-options="iconCls:'icon-save',modal:true,closed:true">
<!--        1、期数-->
<!--        2、还款日期-->
<!--        3、应还本息（元）-->
<!--        4本金(元)-->
<!--        5利息(元)-->
<!--        6罚息-->
<!--        7状态-->
        <table class="easyui-datagrid">
            <thead>
            <tr>
                <th data-options="field:'name'">期数</th>
                <th data-options="field:'price'">还款日期</th>
                <th data-options="field:'price'">应还本息（元）</th>
                <th data-options="field:'price'">本金(元)</th>
                <th data-options="field:'price'">利息(元)</th>
                <th data-options="field:'price'">罚息</th>
                <th data-options="field:'price'">状态</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="borrow_detail_win" class="easyui-window" title="借款详情" style="width:70%;height:70%"
         data-options="iconCls:'icon-save',modal:true,closed:true">
        <div id="back_plan" class="easyui-tabs" style="width:500px;height:250px;">
            <div title="借款信息" style="padding:20px;display:none;">
<!--                1借款合同编号-->
<!--                2甲方-->
<!--                3证件类型：-->
<!--                4证件编号-->
<!--                5借款本金总额-->
<!--                6借款本金大写-->
<!--                7借款年利率-->
<!--                8借款期限（满标起算）-->
<!--                9借款用途-->
<!--                10银行开户名-->
<!--                11银行账号-->
<!--                12开户行-->
                <table class="easyui-datagrid">
                    <thead>
                    <tr>
                        <th data-options="field:'name'">借款合同编号</th>
                        <th data-options="field:'price'">甲方</th>
                        <th data-options="field:'price'">证件类型</th>
                        <th data-options="field:'price'">证件编号</th>
                        <th data-options="field:'price'">借款本金总额</th>
                        <th data-options="field:'price'">借款本金大写</th>
                        <th data-options="field:'price'">借款年利率</th>
                        <th data-options="field:'price'">借款期限（满标起算）</th>
                        <th data-options="field:'price'">借款用途</th>
                        <th data-options="field:'price'">银行开户名</th>
                        <th data-options="field:'price'">银行账号</th>
                        <th data-options="field:'price'">开户行</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div title="出借人信息" data-options="closable:true" style="overflow:auto;padding:20px;display:none;">
<!--                1出借人-->
<!--                2证件类型-->
<!--                3证件号码-->
<!--                4金额-->
                <table class="easyui-datagrid">
                    <thead>
                    <tr>
                        <th data-options="field:'name'">出借人</th>
                        <th data-options="field:'price'">证件类型</th>
                        <th data-options="field:'price'">证件号码</th>
                        <th data-options="field:'price'">金额</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div title="还款信息表" data-options="iconCls:'icon-reload',closable:true" style="padding:20px;display:none;">
<!--                1期数-->
<!--                2还款日-->
<!--                3还款利息-->
<!--                4还款本金-->
<!--                5还款总本息-->
                <table class="easyui-datagrid">
                    <thead>
                    <tr>
                        <th data-options="field:'name'">期数</th>
                        <th data-options="field:'price'">还款日</th>
                        <th data-options="field:'price'">还款利息</th>
                        <th data-options="field:'price'">还款本金</th>
                        <th data-options="field:'price'">还款总本息</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="win" class="easyui-window" title="个人银信信息" style="width:70%;height:70%"
         data-options="iconCls:'icon-save',modal:true,closed:true">
        <div id="tt" class="easyui-tabs" style="width:100%;height:100%;">
            <div title="账户概况" style="padding:20px;display:none;">
                <ul class="list-group">
<!--                    1可用余额-->
<!--                    2银信宝-->
<!--                    3冻结金额-->
<!--                    4最近一次登陆时间-->
<!--                    5个人资产-->
<!--                    6待收款金额-->
<!--                    7累计收益-->
                    <table class="easyui-datagrid">
                        <thead>
                        <tr>
                            <th data-options="field:'name'">可用余额</th>
                            <th data-options="field:'price'">银信宝</th>
                            <th data-options="field:'price'">冻结金额</th>
                            <th data-options="field:'price'">最近一次登陆时间</th>
                            <th data-options="field:'price'">个人资产</th>
                            <th data-options="field:'price'">待收款金额</th>
                            <th data-options="field:'price'">累计收益</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </ul>

            </div>
            <div title="出借管理"  style="overflow:auto;padding:20px;display:none;">
<!--                1、出借用户-->
<!--                2、出借金额(元)-->
<!--                3、出借时间-->
<!--                4、出借方式-->
                <table class="easyui-datagrid">
                    <thead>
                    <tr>
                        <th data-options="field:'name'">出借用户</th>
                        <th data-options="field:'price'">出借金额(元)</th>
                        <th data-options="field:'price'">出借时间</th>
                        <th data-options="field:'price'">出借方式</th>

                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div title="借款管理"  style="padding:20px;display:none;">
                <div id="tt1" class="easyui-tabs" style="height:auto;">
                    <div title=""  style="overflow:auto;padding:20px;">

                    </div>
                    <div title="还款中"  style="overflow:auto;padding:20px;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'code'">借款标题</th>
                                <th data-options="field:'name'">借款金额(元)</th>
                                <th data-options="field:'price'">期数</th>
                                <th data-options="field:'price'">下期还款本息(元)</th>
                                <th data-options="field:'price'">下期还款日</th>
                                <th data-options="field:'price'">还款方式</th>
                                <th data-options="field:'price'">状态</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div title="审核中"  style="overflow:auto;padding:20px;display:none;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'code'">申请借款日期</th>
                                <th data-options="field:'name'">借款标题</th>
                                <th data-options="field:'price'">借款金额(元)</th>
                                <th data-options="field:'price'">年化利率(%)</th>
                                <th data-options="field:'price'">期数</th>
                                <th data-options="field:'price'">还款方式</th>
                                <th data-options="field:'price'">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div title="招标中"  style="padding:20px;display:none;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'code'">招标时间</th>
                                <th data-options="field:'name'">借款标题</th>
                                <th data-options="field:'price'">借款金额(元)</th>
                                <th data-options="field:'price'">年化利率(%)</th>
                                <th data-options="field:'price'">期数</th>
                                <th data-options="field:'price'">剩余招标时间</th>
                                <th data-options="field:'price'">还款方式</th>
                                <th data-options="field:'price'">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div title="已完结"  style="padding:20px;display:none;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'name'">借款标题</th>
                                <th data-options="field:'price'">借款金额(元)</th>
                                <th data-options="field:'price'">年化利率(%)</th>
                                <th data-options="field:'price'">期数</th>
                                <th data-options="field:'price'">借款总利息(元)</th>
                                <th data-options="field:'price'">结清日期</th>
                                <th data-options="field:'price'">还款方式</th>
                                <th data-options="field:'price'">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div title="借款申请"  style="padding:20px;display:none;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'name'">借款人用户名</th>
                                <th data-options="field:'price'">手机号</th>
                                <th data-options="field:'price'">借款金额(元)</th>
                                <th data-options="field:'price'">借款期限</th>
                                <th data-options="field:'price'">借款用途</th>
                                <th data-options="field:'price'">状态</th>
                                <th data-options="field:'price'">申请时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div title="电子账单" style="padding:20px;display:none;">
                <div id="tt" class="easyui-tabs" style="height:auto;">
                    <div title="" style="padding:20px;display:none;">

                    </div>
                    <div title="交易记录" style="padding:20px;display:none;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'code'">流水号</th>
                                <th data-options="field:'name'">日期</th>
                                <th data-options="field:'price'">收入</th>
                                <th data-options="field:'price'">支出</th>
                                <th data-options="field:'price'">交易类型</th>
                                <th data-options="field:'price'">状态</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div title="回款记录-已收款" style="overflow:auto;padding:20px;display:none;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'code'">回款日期</th>
                                <th data-options="field:'name'">借款标题</th>
                                <th data-options="field:'price'">期数</th>
                                <th data-options="field:'price'">回款本息(元)</th>
                                <th data-options="field:'price'">回款本金(元)</th>
                                <th data-options="field:'price'">回款利息(元)</th>
                                <th data-options="field:'price'">还款方式</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div title="回款记录-待收款" style="overflow:auto;padding:20px;display:none;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'code'">回款日期</th>
                                <th data-options="field:'name'">借款标题</th>
                                <th data-options="field:'price'">期数</th>
                                <th data-options="field:'price'">回款本息(元)</th>
                                <th data-options="field:'price'">回款本金(元)</th>
                                <th data-options="field:'price'">回款利息(元)</th>
                                <th data-options="field:'price'">还款方式</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div title="出借记录" style="padding:20px;display:none;">
                        <table class="easyui-datagrid">
                            <thead>
                            <tr>
                                <th data-options="field:'code'">出借日期</th>
                                <th data-options="field:'name'">借款标题</th>
                                <th data-options="field:'price'">出借金额(元)</th>
                                <th data-options="field:'price'">年化利率(%)</th>
                                <th data-options="field:'price'">期数</th>
                                <th data-options="field:'price'">还款方式</th>
                                <th data-options="field:'price'">状态</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
</body>

<script>

    //列表数据格式
    var col_data = [[
        {field: 'user_name',title:'客户姓名',width:'10%',align:'center',halign:'center'},
        // {field: 'reg_phone',title:'客户手机',width:'10%',align:'center',halign:'center'},
        {field: 'idnumber',title:'身份证号',width:'15%',align:'center',halign:'center'},
        {field: 'fuserid',title:'我司ID',width:'10%',align:'center',halign:'center'},
        {field: ' ',title:'渠道编号',width:'10%',align:'center',halign:'center'},
        {field: 'binding_phone',title:'银信绑定手机',width:'10%',align:'center',halign:'center'},
        {field: 'account',title:'银信账户',width:'10%',align:'center',halign:'center'},
        {field: 'lutime',title:'开户时间',width:'15%',align:'center',halign:'center'},
        {field: 'operate',title:'操作',width:'15%',align:'center',halign:'center',
            formatter:function (value,row) {
                var html = '';
                let account = row.account;
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="kh_borrow_msg('+'\''+account+'\''+')" >客户借款信息 </a>'+'&nbsp;&nbsp';
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="borrow_detail('+'\''+account+'\''+')" >借款详情 </a>'+'&nbsp;&nbsp';
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="back_plan('+'\''+account+'\''+')" >还款计划 </a>'+'&nbsp;&nbsp';
                return html;
            }
        },

    ]];

    var globalData = [];
    globalData['loan_no_finish'] = [[
        {field: '真实姓名',title:'真实姓名',width:'10%',align:'center',halign:'center'},
        {field: '登录用户名',title:'登录用户名',width:'10%',align:'center',halign:'center'},
        {field: '属性',title:'属性',width:'10%',align:'center',halign:'center'},
        {field: '流水号',title:'流水号',width:'24%',align:'center',halign:'center'},
        {field: '时间',title:'时间',width:'24%',align:'center',halign:'center'},
        {field: '状态',title:'状态',width:'10%',align:'center',halign:'center'},
        {field: '金额',title:'金额',width:'10%',align:'center',halign:'center'},
    ]];
    //根据关键字搜索
    function search() {
        let like = $('#word').val();
        $("#phone_list").datagrid("load",{
            "like":like
        });
    }

    function kh_borrow_msg(account) {
        $('#win').window('open');
    }

    $('#loan_no_finish').datagrid({
        url:'get_yx_loan_nofinish',
        // idField: 'id',
        rownumbers: true,
        method:'post',
        lines: true,
        border: false,
        pagination: true,
        columns:[[
            {field: '真实姓名',title:'真实姓名',width:'10%',align:'center',halign:'center'},
            {field: '登录用户名',title:'登录用户名',width:'10%',align:'center',halign:'center'},
            {field: '属性',title:'属性',width:'10%',align:'center',halign:'center'},
            {field: '流水号',title:'流水号',width:'24%',align:'center',halign:'center'},
            {field: '时间',title:'时间',width:'24%',align:'center',halign:'center'},
            {field: '状态',title:'状态',width:'10%',align:'center',halign:'center'},
            {field: '金额',title:'金额',width:'10%',align:'center',halign:'center'},
        ]],
        onLoadSuccess: function(data) {
            console.log('loan_no_finish');
            console.log(data);
            console.log('');
        }
    });
    $('#loan_no_finish2').datagrid({
        url:'get_yx_loan_nofinish',
        // idField: 'id',
        rownumbers: true,
        method:'post',
        lines: true,
        border: false,
        pagination: true,
        columns:[[
            {field: '真实姓名',title:'真实姓名',width:'10%',align:'center',halign:'center'},
            {field: '登录用户名',title:'登录用户名',width:'10%',align:'center',halign:'center'},
            {field: '属性',title:'属性',width:'10%',align:'center',halign:'center'},
            {field: '流水号',title:'流水号',width:'24%',align:'center',halign:'center'},
            {field: '时间',title:'时间',width:'24%',align:'center',halign:'center'},
            {field: '状态',title:'状态',width:'10%',align:'center',halign:'center'},
            {field: '金额',title:'金额',width:'10%',align:'center',halign:'center'},
        ]],
        onLoadSuccess: function(data) {
            console.log('loan_no_finish');
            console.log(data);
            console.log('');
        }
    });
    $('#loan_finish').datagrid({
        url:'get_yx_loan_finish',
        // idField: 'id',
        rownumbers: true,
        method:'post',
        lines: true,
        border: false,
        pagination: true,
        columns:[[
            {field: '操作',title:'操作',align:'center',halign:'center'},
            {field: '期数',title:'期数',align:'center',halign:'center'},
            {field: '借款标题',title:'借款标题',align:'center',halign:'center'},
            {field: '状态',title:'状态',align:'center',halign:'center'},
            {field: '登录用户名',title:'登录用户名',align:'center',halign:'center'},
            {field: '结清日期',title:'结清日期',align:'center',halign:'center'},
            {field: '网址',title:'网址',align:'center',halign:'center'},
            {field: '还款方式',title:'还款方式',align:'center',halign:'center'},
            {field: '借款总利息',title:'借款总利息(元)',align:'center'},
            {field: '年化利率',title:'年化利率(%)',align:'center'},
            {field: '借款金额',title:'借款金额(元)',align:'center'},
        ]],
        onLoadSuccess: function(data) {
            console.log('loan_no_finish');
            console.log(data);
            console.log('');
        }
    });

    function borrow_detail() {
        $('#borrow_detail_win').window('open');
    }
    function back_plan() {
        $('#back_plan_win').window('open');
    }
</script>

</body>