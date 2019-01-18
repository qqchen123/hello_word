<body>
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
</style>

<body class="easyui-layout">

<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',fit:true">
        <div id="tt" class="easyui-tabs" style="width:100%;">
            <div title="待办" style="padding:20px;display:none;">
                <div data-options="region:'north',split:true" style="height:80px">
                    <div class="row" style=" margin: 20 0 0 60;">
                        <div class="form-inline">
                            <input class="easyui-textbox" data-options="prompt:'SOUSUO'" type="text" id="name" name="locknum2"
                                   style="height: 34px;" value="">
                            <input type="button" class="form-control btn btn-info" onclick="search('borrowing')"
                                   value="确定">
                        </div>
                    </div>
                </div>
                <div data-options="region:'center'">
                    <table id="daiban"></table>
                </div>
            </div>
            <div title="在办" style="padding:20px;display:none;">
                <div data-options="region:'north',split:true" style="height:80px">
                    <div class="row" style=" margin: 20 0 0 60;">
                        <div class="form-inline">
                            <input class="easyui-textbox" data-options="prompt:'SOUSUO'" type="text" id="name" name="locknum2"
                                   style="height: 34px;" value="">
                            <input type="button" class="form-control btn btn-info" onclick="search('finish')"
                                   value="确定">
                        </div>
                    </div>
                </div>
                <div data-options="region:'center'">
                    <table id="zaiban"></table>
                </div>
            </div>
            <div title="已办" style="padding:20px;display:none;">
                <div data-options="region:'north',split:true" style="height:80px">
                    <div class="row" style=" margin: 20 0 0 60;">
                        <div class="form-inline">
                            <input class="easyui-textbox" data-options="prompt:'SOUSUO'" type="text" id="name" name="locknum2"
                                   style="height: 34px;" value="">
                            <input type="button" class="form-control btn btn-info" onclick="search('overdue')"
                                   value="确定">
                        </div>
                    </div>
                </div>
                <div data-options="region:'center'">
                    <table id="yiban"></table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script>

    /**
     * 去掉字符串左右两边的空格
     * @param x
     * @returns {*|Cropper|void|string}
     */
    function myTrim(x) {
        return x.replace(/^\s+|\s+$/gm, '');
    }

    /**
     * 代办
     */
    $('#daiban').datagrid({
        url: 'mini_pinggu_order_list_data',
        width: '100%',
        rownumbers: true,
        pagination: true,
        queryParams: {
            status: 'daiban',
        },
        columns: [[
            {field: 'order_num', title: '订单编号', align: 'center', halign: 'center', sortable: true},
            {field: 'c_date', title: '申请时间', align: 'center', halign: 'center', sortable: true},
            {field: 'user_name', title: '申贷人', align: 'center', halign: 'center', sortable: true},
            {field: 'get_money', title: '审贷金额（万元）', align: 'center', halign: 'center', sortable: true},
            {field: 'ZheKouZongJia', title: '系统评估金额（万元）', align: 'center', halign: 'center', sortable: true},
            {field: 'admin_id', title: '业务员', align: 'center', halign: 'center', sortable: true},
            {field: 'status', title: '进度', align: 'center', halign: 'center', sortable: true},
            {
                field: 'caozuo', title: '操作', width: '', align: 'center', halign: 'center',
                formatter: function (value, row) {
                    var html = '';
                        html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="db_check_look('+row.bd_id+')" >查看</a>';
                        html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="db_check_deal('+row.bd_id+')" >处理</a>';
                    return html;
                }
            },
        ]]
    });
    /**
     * 在办
     */
    $('#zaiban').datagrid({
        url: 'mini_pinggu_order_list_data',
        width: '100%',
        rownumbers: true,
        pagination: true,
        queryParams: {
            status: 'zaiban',
        },
        columns: [[
            {field: 'order_num', title: '订单编号', align: 'center', halign: 'center', sortable: true},
            {field: 'c_date', title: '申请时间', align: 'center', halign: 'center', sortable: true},
            {field: 'user_name', title: '申贷人', align: 'center', halign: 'center', sortable: true},
            {field: 'get_money', title: '审贷金额（万元）', align: 'center', halign: 'center', sortable: true},
            {field: 'ZheKouZongJia', title: '系统评估金额（万元）', align: 'center', halign: 'center', sortable: true},
            {field: 'admin_id', title: '业务员', align: 'center', halign: 'center', sortable: true},
            {field: 'status', title: '进度', align: 'center', halign: 'center', sortable: true},
            {
                field: 'caozuo', title: '操作', width: '', align: 'center', halign: 'center',
                formatter: function (value, row) {
                    var html = '';
                    html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="zb_check_look('+row.bd_id+')" >查看</a>';
                    html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="zb_check_deal('+row.bd_id+')" >处理</a>';
                    return html;
                }
            },
        ]]
    });
    /**
     * 已办
     */
    $('#yiban').datagrid({
        url: 'mini_pinggu_order_list_data',
        width: '100%',
        rownumbers: true,
        pagination: true,
        queryParams: {
            status: 'yiban',
        },
        columns: [[
            {field: 'order_num', title: '订单编号', align: 'center', halign: 'center', sortable: true},
            {field: 'c_date', title: '申请时间', align: 'center', halign: 'center', sortable: true},
            {field: 'user_name', title: '申贷人', align: 'center', halign: 'center', sortable: true},
            {field: 'get_money', title: '审贷金额（万元）', align: 'center', halign: 'center', sortable: true},
            {field: 'ZheKouZongJia', title: '系统评估金额（万元）', align: 'center', halign: 'center', sortable: true},
            {field: 'admin_id', title: '业务员', align: 'center', halign: 'center', sortable: true},
            {field: 'status', title: '进度', align: 'center', halign: 'center', sortable: true},
            {
                field: 'caozuo', title: '操作', width: '', align: 'center', halign: 'center',
                formatter: function (value, row) {
                    var html = '';
                    html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="yb_check_look('+row.bd_id+')" >查看</a>';
                    html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="yb_check_deal('+row.bd_id+')" >处理</a>';
                    return html;
                }
            },
        ]]
    });
    //搜索
    function search() {
        alert('search');
    }
    function show_all() {
        window.location.reload();
    }
    function db_check_look(data) {
        window.location.href ='mini_pinggu_look_page?bd_id='+data;
    }
    function db_check_deal(data) {
        window.location.href ='mini_pinggu_deal_page?bd_id='+data+'&judge_ld=1';
    }
</script>
</body>