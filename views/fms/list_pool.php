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
    <script type="text/javascript" src="/assets/apps/user/sample.js?<?=time()?>"></script>
    <script type="text/javascript" src="/assets/apps/user/pool.js?=<?=time()?>"></script>
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
            width:380px;
            float:left;
            margin: 10px;
            /*border-left: 2px solid #999;*/
        }

        .fitem label {
            text-align: right;
            margin-right: 20px;
            display: inline-block;
            width: 100px;
        }

        .fitem input {
            width: 160px;
        }

        .fitem input[type="checkbox"] {
            width: 20px;
            /*font: 200px;*/
            /*zoom:150%;*/
        }

        .fitem .status-info{
            margin-left: 5px;
        }

        .radioformr {
            width: 5px;
        }

        hr,#bottom {
            clear: both;
        }

        .sub-btn {
            clear: left;
            margin-top:25px;
            text-align: center;
        }

        #poolForm label{
            font-size: 12px;
            margin-top: 5px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">

    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <!-- <tr>
            <td class="tlabel">资料项</td>
            <td style="width:50%"> -->
                <!-- <input class="col-sm-3" type="text" name="like" id="like" value=""> -->
                <!-- &nbsp;&nbsp;&nbsp;&nbsp; -->
                <!-- <button class="btn btn-success ml2" id="likeBtn"><?//='查询';?></button> -->
            <!-- </td>
            <td colspan="4" class="align-center">
                <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="addPoolInfo()" ><i class="fa fa-plus"></i>新增客户资料</a>
                <a class="btn btn-primary btn-xs p310" href="javascript:window.parent.history.back(-1);"><i class="fa fa-plus"></i>返回</a>

            </td>
        </tr> -->
        <tr>
            <?php switch($sample_type): case 'user': ?>
                    <td>客户编号：<?=$fuserid?></td>
                    <td>客户姓名：<?=$name?></td>
                    <td>身份证号：<?=$idnumber?></td>
                    <td>渠道编号：<?=$channel?></td>
                    <!-- <td>常用手机：<?=@$staple_mobile?></td>
                    <td>民族：<?=@$nation?></td> -->
                    <?php break;?>
                <?php case 'house': ?>
                    <td>房屋编号：<?=$house_code?></td>
                    <td>房屋地址：<?=$address?></td>
                    <?php break;?>
                <?php case 'order': ?>
                    <td>订单编号：<?=$order_code?></td>
                    <?php break;?>
            <?php endswitch;?>

            <td colspan="4" class="align-center">
                <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="add()" ><i class="fa fa-plus"></i>完善客户资料</a>
                <a class="btn btn-primary btn-xs p310" href="javascript:window.parent.history.back(-1);"><i class="fa fa-plus"></i>返回</a>
                <!-- <a href="javascript:void(0)" onclick="$('#zjc').treegrid('reload');">刷新</a> -->
            </td>
        </tr>

        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table class="easyui-treegrid" id="zjc" title="资料池数据" style="width:100%;"
            data-options="
                url:'pool',
                queryParams:{sample_type:sample_type,obj_id:obj_id},

                idField:'pool_id',
                rownumbers: true, 
                treeField:'pool_key',
                method:'get',
                //fit:true,
                fitColumns: false,
                border:false,
                columns:col_data,
                lines:true,

                //pagination:true,
               // singleSelect:true,
                //collapsible:true,
                //pageSize:10,
               // pageList:[10,20],
               // striped:true,
                rowStyler:function(row){
                    return 'background-color:'+statusColor[row.obj_status];
                },
            "
        >
        </table>
    </div>
    <script type="text/javascript">
        var statusColor = JSON.parse('<?= $statusColor ?>');
        var col_data = [[
            //{field: 'name',title:'客户名称',width:100,align:'center'},
            {field: 'pool_key',title:'资料项',width:200,align:'left'},
            {field: 'pool_val',title:'资料数据',width:200,align:'center'},
            {field: 'create_date',title:'创建时间',width:120,align:'center'},
            {field: 'edit_date',title:'编辑时间',width:120,align:'center'},
            {field: 'obj_status_info', title: '状态信息', width: 150,  align:'center',
                formatter: function(value, row, index) {
                    if(value===null) return;
                    var str ='';
                    if(value.length>10) str='...';
                    value = value.substring(0,10)+str;

                    var myClass = '';
                    if (row.obj_status==20) myClass = 'icon-ok';
                    if (row.obj_status==40) myClass = 'icon-cancel';
                    value = '<span class="'+myClass+'" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>' + value;
                    return value;
                },
                styler:function(a,b,c){
                    return 'cursor: pointer;';
                }
            },
            {field: 'operate',title:'操作',width:300,align:'center',
                formatter: function (value, row, index) {
                    var html = '';
                    <?php
                        //基础设置
                        //id字段 默认row.id
                        $this->id_field='row.pool_id';
                        //输出状态按钮
                        echo showStatusBtn(true);
                    ?>
                    return html;
                }
            }
        ]];

        var loadData = {};//已有数据
        var detailPower = [];//当前js操作具有的参数权限
        var obj_name = '<?=$id_field?>';//当前对象id字段名称
        // var <?=$id_field?> = <?=$id?>;//当前样本对象id值
        var obj_id = <?=$id?>;//当前对象id值
        var sample_type = '<?=$sample_type?>';//样本对象类型
        var status_type = '<?=$status_type?>';//状态类型

        var nowClass = 'Pool';//当前js操作访问控制器
        var nowMethod = '';//当前js操作访问方法
        var nowStatus = '';//当前js操作方法的状态条件
        var showCheckBox = true;//当前js操作是否显示多选框头
        var divId = '#input';//生成input框的外层id
        var type = 'getPoolsBySampleidObjid';//url附加参数，减少参数权限

        // 添加资料数据
        function add() {
            no_status();
            nowStatus = '<10';
            detailPower = JSON.parse('<?=json_encode(getRolePowerDetails($this->default_controller,'do_pool')) ?>');
        }

        //编辑资料数据
        function edit(pool_id,detail,if_status) {
            no_status();
            nowStatus = if_status;
            detailPower = detail;
            $('#pool_sample_id').combotree('setValue',$('#zjc').treegrid('find',pool_id).id);
        }

        //pool_id获取pools
        // function getPoolsByPoolid(url,pool_id){
        //     $.getJSON(url,{pool_id:pool_id,type:'getPoolsByPoolid'},function(row){
        //         var pool_sample_id = row[0].pool_sample_id;
        //         // $.each(row,function(k,v){
        //         //     if(v.is_json==1) v.pool_val = JSON.parse(v.pool_val);
        //         //     loadData['sample['+v.pool_sample_id+']'] = v.pool_val;
        //         // });
        //         $('#pool_sample_id').combotree('setValue',pool_sample_id);
        //     });
        // }

        /**
         * 执行新增、编辑资料池样本数据
         */
        function doPool() {
            $('#poolForm').form('submit', {
                'url':"do_pool",
'data':{sample_type:sample_type},
                'success': function (result) {
                    var result = eval("(" + result + ")");
                    if (result.ret) {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                        $('#win').dialog('close');
                        $('#zjc').treegrid('reload');
                    } else {
                        $.messager.show({
                            title: '提示',
                            msg: result.info
                        });
                    }
                }
            });
        }
        //报审机构
        function baoShen(pool_id,detail,if_status){
            status(pool_id,'报审','报审','baoShen',detail,if_status);
        }

        //初审通过
        function guoChuShen(pool_id,detail,if_status){
            status(pool_id,'初审通过','通过','guoChuShen',detail,if_status);
        }

        //初审退回
        function backChuShen(pool_id,detail,if_status){
            status(pool_id,'初审驳回','驳回','backChuShen',detail,if_status);
        }

        //复审通过
        function guoFuShen(pool_id,detail,if_status){
            status(pool_id,'复审通过','通过','guoFuShen',detail,if_status);
        }

        //复审退回
        function backFuShen(pool_id,detail,if_status){
            status(pool_id,'复审驳回','驳回','backFuShen',detail,if_status);
        }
    
        //审核通过
        function guoShen(pool_id,detail,if_status){
            status(pool_id,'审核通过','通过','guoShen',detail,if_status);
        }

        //审核退回
        function backShen(pool_id,detail,if_status){
            status(pool_id,'审核驳回','驳回','backShen',detail,if_status);
        }

        //停用机构
        function stop(pool_id,detail,if_status){
            status(pool_id,'停用机构','停用','stop',detail,if_status);
        }

        //启用机构
        function start(pool_id,detail,if_status){
            status(pool_id,'启用机构','启用','start',detail,if_status);
        }

        //申请修改
        function pleaseEdit(pool_id,detail,if_status){
            status(pool_id,'申请修改','申请','pleaseEdit',detail,if_status);
        }

        //批准修改
        function yesEdit(pool_id,detail,if_status){
            status(pool_id,'批准修改','批准','yesEdit',detail,if_status);
        }

        //拒绝修改
        function noEdit(pool_id,detail,if_status){
            status(pool_id,'驳回修改','驳回','noEdit',detail,if_status);
        }

        //确认框
        function myConfirm(msg,fun,url,title){
            $.messager.confirm("确认", msg, function(r) {
                if (r) window[fun](url,title); 
            });
            return false;
        }

        //改状态确认
        function editStatusConfirm(fun,url,title){
            var o = $('#poolForm :checkbox:checked[name^="pool_id["]')
            var msg = '确认'+title+'如下 '+o.length+' 项数据：<br><br><br>';
            o.each(function(k,v){
                msg += (k+1)+'.    '+$(v).parent().html()+'<br>';
            });
            msg = msg.replace(/checked=""/ig,"checked='checked' onclick='return false;'");
            myConfirm(msg,fun,url,title);
        }

        //执行改状态
        function doStatus(url,title){
            $('#poolForm').form('submit', {
                url: url,
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                dataType: 'json',
                success: function(result) {
                    if (result==1) {
                        $('#win').dialog('close');
                        $.messager.show({
                            title: '提示',
                            msg: title+'操作成功！'
                        });
                        $('#zjc').treegrid('reload');
                    } else {
                        $.messager.show({
                            title: '提示',
                            msg: title+'操作失败！'
                        });
                    }
                }
            });
        }

        //改状态
        function status(pool_id,title,button,url,detail,if_status){
            nowMethod = url;
            showCheckBox = true;
            readonly_all = true;
            nowStatus = if_status;
            detailPower = detail;
            $('#input').empty();

            $('#status_info').parent('div').removeAttr('hidden');
            $('#for_admins').parent('div').removeAttr('hidden');
            $('#status_info,#for_admins').textbox('enable');

            $('#obj_id').attr('disabled','disabled');
            $('#pool_sample_id').combotree('disable');

            // $('#poolForm #classBtn').attr('onclick','doStatus("'+url+'","'+title+'")');
            $('#poolForm #classBtn').attr('onclick','editStatusConfirm("doStatus","'+url+'","'+title+'")');
            $('#win').dialog('open').dialog('setTitle',title);
            $('#pool_sample_id').combotree('reload');
            $('#poolForm').form('clear').form('load',{'obj_id':obj_id,sample_type:sample_type});
            $('#poolForm #classBtn .l-btn-text').text(button);

            $('#pool_sample_id').combotree('setValue',$('#zjc').treegrid('find',pool_id).id);
        }
        //改内容
        function no_status(){
            nowMethod = 'do_pool';
            showCheckBox = false;
            readonly_all = false;
            $('#input').empty();

            $('#status_info').parent('div').attr('hidden',true);
            $('#for_admins').parent('div').attr('hidden',true);
            $('#status_info,#for_admins').textbox('disable');

            $('#obj_id').removeAttr('disabled');
            $('#pool_sample_id').combotree('enable');

            $('#poolForm #classBtn').attr('onclick','doPool()');
            $('#win').dialog('open').dialog('setTitle', '完善客户资料');
            $('#pool_sample_id').combotree('reload');
            $('#poolForm').form('clear').form('load',{'obj_id':obj_id,sample_type:sample_type});
            $('#poolForm #classBtn .l-btn-text').text('提交');

        }
        //搜索
        // $('#likeBtn').on('click',function () {
        //     var like = $('#like ').val();
        //     $('#zjc').datagrid('load',{like:like});
        // })

        // $(function () {
        //     $.ajax({
        //         url:"<?php //echo site_url().'/pool/get_xlinfo?user_id='.@$userid;?>",
        //         dataType:"json",
        //         type:"GET",
        //         success:function(data){
        //             //绑定第一个下拉框
        //             $('#pool_sample_id').combobox({
        //                 data: data,
        //                 prompt:'输入首关键字自动检索',
        //                 valueField: 'id',
        //                 textField: 'pool_key'
        //             });
        //             $('#pool_sample_id').combobox('setValue',data[0].id);
        //         },
        //         error:function(error){
        //             alert("初始化下拉控件失败");
        //         }
        //     })
        // });
    </script>

</body>
<!-- 新增/编辑 -->
    <div id="win" style="width:460px;min-width:460px;height:450px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true,resizable:true,maximizable:true">
        <form id="poolForm" method="post" enctype=”multipart/form-data” novalidate>
            <!-- <div><input type="hidden" name="obj_id"></div> -->
            <!-- <div><input type="hidden" name="pool_id"></div> -->
            <div>
            <input type="hidden" name="obj_id" id="obj_id">
            <!-- <input type="hidden" name="obj_name" id="obj_name"> -->
            <input type="hidden" name="sample_type" id="sample_type">
            </div>
            <div class="fitem">
                <label>请选取资料项:</label>
                <input name="pool_sample_id" id="pool_sample_id" class="easyui-combotree"  required="true"
                    data-options="
                        editable:true,
                        url: 'getTreeSampleKey',
                        queryParams:{sample_type:sample_type},

                        valueField: 'id',
                        textField: 'pool_key',
                        panelHeight:'auto',
                        lines:true,
                        onChange:function(sample_id){
                            getSamplesAndPools(sample_id,showCheckBox,readonly_all);
                        },
                    "
                >
            </div>
            <hr>
            <div id="input"></div>
            <hr id="bottom">
            <div class="fitem" hidden>
                <label>提醒对象:</label>
                <input name="for_admins[]" id="for_admins" class="easyui-combobox" style="width:250px"
                    data-options="
                        url: '../PublicMethod/getAdmin',
                        valueField: 'id',
                        textField: 'username',
                        multiple:true,
                        panelHeight:'auto',
                        groupField:'department',
                    "
                >
            </div>
            <div class="fitem" hidden>
                <label id="info_label">流程附加信息:</label>
                <input name="status_info" id="status_info" class="easyui-textbox" style="width:250px;height:140px;" validType="length[0,240]" data-options="multiline:true" novalidate="true">
                 <!-- required="true" validType="length[1,255]" novalidate="true" -->
            </div>
            <div id="jigou-dlg-buttons" class="sub-btn">
                <a href="javascript:void(0)" id="classBtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doPool()" style="width:90px">新增</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#win').dialog('close')" style="width:90px">取消</a>
            </div>
        </form>
    </div>

    <!-- 流程信息 -->
    <? showHistoryHtml(); ?>
    <!-- 提醒 -->
    <? showRemindHtml(); ?>


</html>