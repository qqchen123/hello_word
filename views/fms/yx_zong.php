<?php //tpl("admin_header") ?>
<body>
    <!-- <link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css"> -->
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
    <link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
    <link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
    <!-- <title></title> -->
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
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
        .sub-btn{
            text-align: right;
        }
        #fm {
            margin: 0;
            padding: 10px 30px;
        }
        .ftitle {
            font-size: 14px;
            font-weight: bold;
            padding: 5px 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .fitem {
            margin-bottom: 5px;
        }

        .fitem label {
            display: inline-block;
            width: 140px;
        }

        .fitem input {
            width: 160px;
        }

        .radioformr {
            width: 5px;
        }

        .sub-btn {
            margin-top:15px;
        }

        #jiGouForm label{
            font-size: 12px;
            margin-top: 5px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">

<div data-options="region:'center',title:'银信总账户信息'" style="padding:5px;background:#eee;">
    <div id="filter">
        搜索🔍：<input type="text" class="easyui-textbox" name="like" id="like" prompt="银信编号、客户编号、身份证号、姓名、绑定手机号" style="width:300px;">
        <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
        <button class="btn btn-success ml2" onclick="get_out_money_excel()"><?='下载出借人excel';?></button>

    </div>
    <br>
    <div id="tt" class="easyui-tabs" style="width:100%;height:450px;">
        <div title="借款人列表"  style="overflow:auto;padding:20px;display:none;">
            <table  id="in_money" class="easyui-datagrid" style="width:100%;height:350px"
                        data-options="
                            url: 'get_in_money',
                            rownumbers: true,
                            method: 'get',
                            toolbar: '#toolbar',
                            lines: true,
                            fit: true,
                            fitColumns: false,
                            border: false,
                            columns:in_money_data,
                            pagination:true,
                            onSortColum: function (sort,order) {
                                $('#in_money').datagrid('reload', {
                                    sort: sort,
                                    order: order
                            　　});
                            },
                            ">
            </table>
        </div>
        <div title="出借人列表" style="padding:20px;display:none;">
            <table  id="out_money" class="easyui-datagrid" style="width:100%;height:350px"
                        data-options="
                            url: 'get_out_money',
                            rownumbers: true,
                            method: 'get',
                            toolbar: '#toolbar',
                            lines: true,
                            fit: true,
                            fitColumns: false,
                            border: false,
                            columns:out_money_data,
                            pagination:true,
                            onSortColum: function (sort,order) {
                                $('#out_money').datagrid('reload', {
                                    sort: sort,
                                    order: order
                            　　});
                            },
                        ">
            </table>
        </div>
    </div>
</div>

</div>
<script>
    //获取列表
        var out_money_data = [[
            {field: 'account', title: '被推荐人用户名', width: 150, align:'center', 'sortable':true},
            {field: 'recommend_tie', title: '推荐关系', width: 100,  align:'center', 'sortable':true},
            {field: 'reg_time', title: '注册时间', width: 150,  align:'center', 'sortable':true},
            {field: 'out_money', title: '被推荐人出借金额', width: 150,  align:'center', 'sortable':true},

            // {field: 'fms_user-yx_account', title: '本地客户是否存在', width: 200,  align:'center', 'sortable':true,
            //     formatter: function(value, row, index) {
            //         if(row['fms_user-yx_account']===null || row['fms_yx_account-acount']===null){
            //             return '不存在';
            //         }else{
            //             return '存在';
            //         }
            //     },
            // },

            {field: 'fms_user-fuserid', title: '客户编号', width: 150,  align:'center', 'sortable':true},
            {field: 'fms_user-idnumber', title: '客户身份证号', width: 200,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if(value===null) return;

                    if(row['tmp_detail-id_number']===null){
                        return '<span class="icon-help easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                    }else{
                        var check_arr = row['tmp_detail-id_number'].split('****');
                        // console.log(value);
                        // console.log(check_arr);
                        // console.log( value.indexOf(check_arr[0]));
                        // console.log( value.lastIndexOf(check_arr[1]));
                        if(value.indexOf(check_arr[0])==0 && value.lastIndexOf(check_arr[1])==14){
                            return '<span class="icon-ok easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                        }else{
                            return '<span class="icon-no easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value+'<br>'+row['tmp_detail-id_number'];
                        }
                    }
                },
            },
            {field: 'fms_user-name', title: '客户姓名', width: 100,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if(value===null) return;

                    if(row['tmp_detail-user_name']===null){
                        return '<span class="icon-help easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                    }else{
                        if(row['tmp_detail-user_name']==value){
                            return '<span class="icon-ok easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                        }else{
                            return '<span class="icon-no easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value+'<br>'+row['tmp_detail-user_name'];
                        }
                    }
                },
            },
            {field: 'fms_yx_account-bind_phone', title: '客户绑定手机号', width: 150,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if(value===null) return;

                    if(row['tmp_detail-call_number']===null){
                        return '<span class="icon-help easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                    }else{
                        var check_arr = row['tmp_detail-call_number'].split('****');
                        // console.log(value);
                        // console.log(check_arr);
                        // console.log( value.indexOf(check_arr[0]));
                        // console.log( value.lastIndexOf(check_arr[1]));
                        if(value.indexOf(check_arr[0])==0 && value.lastIndexOf(check_arr[1])==8){
                            return '<span class="icon-ok easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                        }else{
                            return '<span class="icon-no easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value+'<br>'+row['tmp_detail-call_number'];
                        }
                    }
                },
            },
            {field: 'operate', title: '出借详情', width: 150,  align:'center',
                formatter: function(value, row, index) {
                    html = '<a class="btn btn-primary btn-xs p310" onclick="show_detail(\'out\',\'get_out_money_detail?account='+row.account+'\')">查看 </a> '+'&nbsp;&nbsp;';
                    html += '<a class="btn btn-primary btn-xs p310" onclick="edit(\''+row.account+'\')">编辑 </a> '+'&nbsp;&nbsp;';
                    return html;
                },
            },
        ]];

        var in_money_data = [[
            {field: 'account', title: '被推荐人用户名', width: 150, align:'center', 'sortable':true},
            {field: 'recommend_tie', title: '推荐关系', width: 100,  align:'center', 'sortable':true},
            {field: 'reg_time', title: '注册时间', width: 150,  align:'center', 'sortable':true},
            {field: 'in_money', title: '被推荐人出借金额', width: 150,  align:'center', 'sortable':true},
            // {field: 'fms_user-yx_account', title: '本地客户是否存在', width: 200,  align:'center',
            //     formatter: function(value, row, index) {
            //         if(row['fms_user-yx_account']===null || row['fms_yx_account-acount']===null){
            //             return '不存在';
            //         }else{
            //             return '存在';
            //         }
            //     },
            // },

            {field: 'fms_user-fuserid', title: '客户编号', width: 150,  align:'center', 'sortable':true},
            {field: 'fms_user-idnumber', title: '客户身份证号', width: 200,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if(value===null) return;

                    if(row['tmp_detail-id_number']===null){
                        return '<span class="icon-help easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                    }else{
                        var check_arr = row['tmp_detail-id_number'].split('****');
                        // console.log(value);
                        // console.log(check_arr);
                        // console.log( value.indexOf(check_arr[0]));
                        // console.log( value.lastIndexOf(check_arr[1]));
                        if(value.indexOf(check_arr[0])==0 && value.lastIndexOf(check_arr[1])==14){
                            return '<span class="icon-ok easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                        }else{
                            return '<span class="icon-no easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value+'<br>'+row['tmp_detail-id_number'];
                        }
                    }
                },
            },
            {field: 'fms_user-name', title: '客户姓名', width: 100,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if(value===null) return;

                    if(row['tmp_detail-user_name']===null){
                        return '<span class="icon-help easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                    }else{
                        if(row['tmp_detail-user_name']==value){
                            return '<span class="icon-ok easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                        }else{
                            return '<span class="icon-no easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value+'<br>'+row['tmp_detail-user_name'];
                        }
                    }
                },
            },
            {field: 'fms_yx_account-bind_phone', title: '客户绑定手机号', width: 150,  align:'center', 'sortable':true,
                formatter: function(value, row, index) {
                    if(value===null) return;

                    if(row['tmp_detail-call_number']===null){
                        return '<span class="icon-help easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                    }else{
                        var check_arr = row['tmp_detail-call_number'].split('****');
                        // console.log(value);
                        // console.log(check_arr);
                        // console.log( value.indexOf(check_arr[0]));
                        // console.log( value.lastIndexOf(check_arr[1]));
                        if(value.indexOf(check_arr[0])==0 && value.lastIndexOf(check_arr[1])==8){
                            return '<span class="icon-ok easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value;
                        }else{
                            return '<span class="icon-no easyui-tooltip" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>'+value+'<br>'+row['tmp_detail-call_number'];
                        }
                    }
                },
            },

            {field: 'operate', title: '借款详情', width: 150,  align:'center',
                formatter: function(value, row, index) {
                    html = '<a class="btn btn-primary btn-xs p310" onclick="show_detail(\'in\',\'get_in_money_detail?account='+row.account+'\')">查看 </a> '+'&nbsp;&nbsp;';
                    html += '<a class="btn btn-primary btn-xs p310" onclick="edit(\''+row.account+'\')">编辑 </a> '+'&nbsp;&nbsp;';
                    return html;
                },
            },
        ]];

    //查看详情
        function show_detail(id,url){
            // console.log(id);
            // console.log(url);
            $('#'+id+'_detail-dlg').dialog('open');
            $('#'+id+'_money_detail').datagrid('load',url);
        }

    //编辑
        function edit(account){
            $('#edit-dlg').dialog('open');
            $('#form').form('clear');
            $('#form #account').val(account);

            $.getJSON('get_account_user_info',{account:account},function(row){
                // console.log(row);
                $('#form').form('load',row);
                // console.log($('#edit-dlg #account').val());
            });
        }

    //执行编辑
        function doEdit(){
            $('#form').form('submit', {
                url: 'do_edit',
                onSubmit: function() {    
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    var result = eval("(" + result + ")");
                    // console.log(result);
                    if (result.ret == false) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    } else {
                        $('#edit-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#in_money').datagrid('reload');
                        $('#out_money').datagrid('reload');
                    }
                }
            });
        }


    // //千分位
    //     function toThousands(num) {
    //         var num = (num || 0).toString(), result = '';
    //         while (num.length > 3) {
    //             result = ',' + num.slice(-3) + result;
    //             num = num.slice(0, num.length - 3);
    //         }
    //         if (num) { result = num + result; }
    //         return result;
    //     }

    // //确认框
    //     function myConfirm(msg,fun,id){
    //         $.messager.confirm("确认", msg, function(r) {
    //             if (r) window[fun](id); 
    //         });
    //         return false;
    //     }


    $('#likeBtn').on('click',function () {
        var like = $('#like').val();
        $('#in_money').datagrid('load',{like:like});
        $('#out_money').datagrid('load',{like:like});
    });

    /**
     * 导出出借人列表生成Excel
     */
    function get_out_money_excel() {
        window.location.href= 'get_out_money_excel';
    }

</script>
    <!-- 出借详情 -->
    <div id="out_detail-dlg" style="width:90%;height:600px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="out_detail-dlg-buttons" title='银信总账户出借人详情' data-options="modal:true">
        <table  id="out_money_detail" class="easyui-datagrid" style="width:100%;height:350px"
                    data-options="
                        rownumbers: true,
                        method: 'get',
                        toolbar: '#toolbar',
                        lines: true,
                        fit: true,
                        fitColumns: false,
                        border: false,
                        columns:out_money_detail_data,
                        pagination:true,
                        onSortColum: function (sort,order) {
                            $('#out_money_detail').datagrid('reload', {
                                sort: sort,
                                order: order
                        　　});
                        },
                    ">
        </table>
        <script>
            var out_money_detail_data = [[
                {field: 'account', title: '被推荐人用户名', width: 200, align:'center', 'sortable':true},
                {field: 'out_title', title: '借款标题', width: 200, align:'center', 'sortable':true},
                {field: 'user_name', title: '真实姓名', width: 200, align:'center', 'sortable':true},
                {field: 'id_number', title: '身份证号', width: 200, align:'center', 'sortable':true},
                {field: 'call_number', title: '手机号', width: 200, align:'center', 'sortable':true},
                {field: 'out_money', title: '出借金额', width: 200, align:'center', 'sortable':true},
                {field: 'out_cyc', title: '出借期限', width: 200, align:'center', 'sortable':true},
                {field: 'out_rate', title: '出借利率', width: 200, align:'center', 'sortable':true},
                {field: 'return_mode', title: '还款方式', width: 200, align:'center', 'sortable':true},
                {field: 'out_date', title: '出借时间', width: 200, align:'center', 'sortable':true},
                {field: 'expire_time', title: '到期时间', width: 200,  align:'center', 'sortable':true},
            ]];
        </script>
    </div>

    <!-- 借款详情 -->
    <div id="in_detail-dlg" style="width:90%;height:600px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="in_detail-dlg-buttons" title='银信总账户借款人详情' data-options="modal:true">
        <table  id="in_money_detail" class="easyui-datagrid" style="width:100%;height:350px"
                data-options="
                    rownumbers: true,
                    method: 'get',
                    toolbar: '#toolbar',
                    lines: true,
                    fit: true,
                    fitColumns: false,
                    border: false,
                    columns:in_money_detail_data,
                    pagination:true,
                    onSortColum: function (sort,order) {
                        $('#in_money_detail').datagrid('reload', {
                            sort: sort,
                            order: order
                    　　});
                    },
                ">
        </table>
        <script>
            //获取列表
            var in_money_detail_data = [[
                {field: 'account', title: '被推荐人用户名', width: 200, align:'center', 'sortable':true},
                {field: 'in_title', title: '借款标题', width: 200, align:'center', 'sortable':true},
                {field: 'user_name', title: '真实姓名', width: 200, align:'center', 'sortable':true},
                {field: 'id_number', title: '身份证号', width: 200, align:'center', 'sortable':true},
                {field: 'call_number', title: '手机号', width: 200, align:'center', 'sortable':true},
                {field: 'in_money', title: '出借金额', width: 200, align:'center', 'sortable':true},
                {field: 'in_cyc', title: '出借期限', width: 200, align:'center', 'sortable':true},
                {field: 'in_rate', title: '出借利率', width: 200, align:'center', 'sortable':true},
                {field: 'return_mode', title: '还款方式', width: 200, align:'center', 'sortable':true},
                {field: 'in_date', title: '出借时间', width: 200, align:'center', 'sortable':true},
                {field: 'loan_date', title: '放款时间', width: 200, align:'center', 'sortable':true},
                {field: 'expire_time', title: '到期时间', width: 200,  align:'center', 'sortable':true},
            ]];
        </script>
    </div>

    <!-- 编辑 -->
    <div id="edit-dlg" style="width:400px;max-height:450px;padding:10px 20px" class="easyui-dialog" title="编辑客户" closed="true" buttons="edit-dlg-buttons" data-options="modal:true">
        <form id="form" method="post" novalidate>
            <div><input type="hidden" name="account" id="account"></div>
            <div class="fitem">
                <label>客户编号:</label>
                <input name="fuserid" id="fuserid" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>客户姓名:</label>
                <input name="name" id="name" class="easyui-textbox" required="true" validType="length[1,30]">
            </div>
            <div class="fitem">
                <label>客户身份证号:</label>
                <input name="idnumber" id="idnumber" class="easyui-textbox" required="true" validType="length[1,18]">
            </div>
            <div class="fitem">
                <label>绑定手机号:</label>
                <input name="binding_phone" id="binding_phone" class="easyui-numberbox" validType="length[11,11]">
            </div>
            <div id="edit-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doEdit()" style="width:90px">编辑</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#edit-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
</body>
</html>