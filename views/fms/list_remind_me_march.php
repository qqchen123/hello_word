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
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <td>工作进展</td>
            <td colspan="4" class="align-center">
                <?php if ($for_from=='for' && $complete_status==0 && checkRolePower('workLog','add_me_work_log_march')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="add()" ><i class="fa fa-plus"></i>新增</a>
                <?php endif ?>
                <a id="tb-add" href="javascript:window.parent.history.back(-1)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>返回</a>
                <!-- <a id="tb-add" href="javascript:history.go(0)"  class="btn btn-primary btn-xs p310"><i class="fa fa-chevron-left"></i>刷新</a> -->
            </td>
        </tr>
        
        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table  id="tt" class="easyui-datagrid" style="width:100%;height:350px"
            data-options="
                url: 'get_me_work_log_march?parent_id=<?=$parent_id?>',
                rownumbers: true,
                method: 'get',
                toolbar: '#toolbar',
                lines: true,
                fit: true,
                fitColumns: false,
                border: false,
                columns:col_data,
                singleSelect:true,
                pagination:true,
                onSortColum: function (sort,order) {
                    $('#tt').datagrid('reload', {
                        sort: sort,
                        order: order
                　　});
                },
            "
        >
        </table>
    </div>
</div>
<script>
    //获取列表
        var col_data = [[
            {field: 'c_date', title: '日期', width: 200, align:'center', 'sortable':true},
            {field: 'complete_rate', title: '预估完成率', width: 100,  align:'center',
                formatter: function(value) {
                    return value+'%';
                }
            },
            {field: 'content', title: '工作进展内容', width: 500,  align:'center'},
        ]];

    //添加
        function add() {
            $('#workLog-dlg').dialog('open').dialog('setTitle', '新增我的工作日志');
            $('#workLogForm').form('clear');
            $('#parent_id').val('<?= $parent_id?>');
            $('#workLogForm #classBtn .l-btn-text').text('新增');
        }

    //执行添加
        function doadd() {
            $('#workLogForm').form('submit', {
                url: 'add_me_work_log_march',
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
                        $('#workLog-dlg').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#tt').datagrid('reload');
                    }
                }
            });
        }

    //编辑
        // function edit(jg_id) {
        //     no_status();
        //     $('#workLog-dlg').dialog('open').dialog('setTitle', '编辑机构');
        //     $('#jiGouForm').form('clear');
        //     $.getJSON('get_jigou',{jg_id:jg_id},function(row){
        //         $('#workLogForm').form('load',row);
        //     });
        //     $('#workLogForm #classBtn .l-btn-text').text('提交');
        // }

    //确认框
        function myConfirm(msg,fun,id){
            $.messager.confirm("确认", msg, function(r) {
                if (r) window[fun](id); 
            });
            return false;
        }

   </script>

    <!-- 新增我的日志进展 -->
    <div id="workLog-dlg" style="width:400px;max-height:450px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="workLog-dlg-buttons" data-options="modal:true">

        <form id="workLogForm" method="post" novalidate>
            <input type="hidden" name="parent_id" id="parent_id">
            <div class="fitem">
                <label>预估完成率:</label>
                <input name="complete_rate" id="complete_rate" class="easyui-numberbox" required="true" style="width:346px" 
                    data-options="
                        precision:0,
                        groupSeparator:',',
                        decimalSeparator:'.',
                        suffix:'%',
                        min:0,
                        max:100
                    "
                >
            </div>
            <div class="fitem">
                <label>工作进展内容:</label>
                <input name="content" id="content" class="easyui-textbox" style="width:346px;height:140px;" validType="length[0,250]" data-options="multiline:true" novalidate="true">
            </div>
            <div id="workLog-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doadd()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#workLog-dlg').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>
</body>
</html>