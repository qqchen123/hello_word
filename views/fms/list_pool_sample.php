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
    <script type="text/javascript" src="/assets/apps/user/pool.js?<?=time()?>"></script>
    <script type="text/javascript" src="/assets/apps/user/sample.js?<?=time()?>"></script>
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

        .fitem select {
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

        #data input{
            width:110px;
        }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
        <tbody>
        <tr>
            <!-- <td class="tlabel">资料项管理</td> -->
            <td style="width:80%">
                <div class="fitem">
                    <label>资料项归属类型:</label>
                    <input class="easyui-combobox" required="true" style="width:100px" 
                        data-options="
                            editable:false,
                            panelHeight:'auto',
                            valueField:'key',
                            textField:'text',
                            data: typeData,
                            onChange:function(v){
                                sample_type = v;
                                $('#zjc').treegrid('load',{sample_type:sample_type});
                            },
                            value:'<?=$sample_type?>',
                        "
                    >
                </div>
            </td>
            <td colspan="4" class="align-center">
                <?php if (checkRolePower('Pool','do_sample')): ?>
                    <a class="btn btn-primary btn-xs p310" href="javascript:void(0)"  onclick="show_add()" ><i class="fa fa-plus"></i>新增资料项</a>
                <?php endif ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>

        </tbody>
    </table>
    </div>
    <div region="center" data-options="border:false" style="padding: 0px 20px;">
        <table class="easyui-treegrid" id="zjc" title="资料项" style="width:100%;height:80%"
        data-options="
            url:'getTreeSample?formatChildren=1',
            queryParams:{sample_type:sample_type},
            idField: 'id',
            treeField: 'pool_key',
            rownumbers: true,
            method:'get',
            toolbar: '#toolbar',
            lines: true,
            border: false,
            onLoadSuccess:function(){
                $(this).treegrid('collapseAll');
            },
            onBeforeExpand:function(row){
                $(this).treegrid('collapseAll',0);
                $(this).treegrid('expandTo',row.id);
            },
            //fit: false,
            //fitColumns: true,
            //checkbox:true,
            //singleSelect:true,
            //collapsible:true,
            //pagination:true,
            //pageSize:10,
            //pageList:[10,20],
            //sortName:'edit_date',
            //remoteSort:true,
            //striped:true,
            columns:col_data,
        ">

        </table>
    </div>


    <!-- 样本添加做活 选择类型选取默认样式 并展示样式  by 奚晓俊-->
    <div id="win" style="width:900px;height:550px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="jigou-dlg-buttons" data-options="modal:true,resizable:true,maximizable:true">
        <div class="easyui-layout" fit="true">
            <!-- 定义样式 -->
            <div data-options="region:'west',title:'定义样式',border:false" style="width:50%;padding:10px 20px">
                <form id="ff" method="post" novalidate>
                    <input type="hidden" name="id">
                    <div class="fitem">
                        <label>选择父项:</label>
                        <input name="parent_id" id="parent_id" class="easyui-combotree" required="true" 
                            data-options="
                                editable:true,
                                url: 'getTreeSampleKey?root=1&sample_type='+sample_type,
                                valueField: 'id',
                                textField: 'pool_key',
                                panelHeight:'auto',
                                lines:true,
                                panelHeight:400,
                            "
                        >
                    </div>
                    <div class="fitem">
                        <label>资料名称:</label>
                        <input name="pool_key" id="pool_key" class="easyui-textbox" required="true" validType="length[1,20]" data-options="onChange:changeSample">
                    </div>
                    <div class="fitem">
                        <label>是否为数据组:</label>
                        <input name="is_json" id="is_json" class="easyui-combobox" required="true"
                            data-options="
                                editable:false,
                                panelHeight:'auto',
                                valueField:'key',
                                textField:'text',
                                data: [
                                    {key:'0',text:'否'},
                                    {key:'1',text:'是'}
                                ],
                                onChange:function(v){
                                    if(v=='1'){
                                        $('#is_select #multiple').combobox('setValue','1');
                                    }else{
                                        $('#is_select #multiple').combobox('setValue','0');
                                    };
                                    changeSample();
                                },
                                value:'0',
                            "
                        >
                    </div>
                    <div class="fitem">
                        <label>数据类型:</label>
                        <input name="show_type" id="show_type" class="easyui-combobox" validtype="length[1,20]" required="true" 
                            data-options="
                                editable:false,
                                url: 'getShowTypes',
                                valueField: 'show_type',
                                textField: 'show_type',
                                groupField: 'level',
                                groupFormatter: function(group){
                                    var color = '';
                                    switch (group) {
                                        case '基本类型':
                                            color = 'red';
                                            group += '————————';
                                            break;
                                        case '定制类型':
                                            color = '#CC00FF';
                                            group += '————————';
                                            break;
                                        case '自定义类型':
                                            color = 'blue';
                                            group += '———————';
                                            break; 
                                    }
                                    return '<span style=\'color:'+color+'\'>' + group + '</span>';
                                },
                                panelHeight:300,
                                onSelect:selectShowType,
                            "
                        >
                        <a href="#" onclick="addShowType()"  class="easyui-linkbutton" icon="icon-add"></a>
                        <a href="#" class="easyui-linkbutton" onclick="delShowType()" icon="icon-cancel"></a>
                    </div>
                    <div class="fitem" style="display:none">
                        <label>数据类型:</label>
                        <input name="type" id="type" class="easyui-combobox" required="true"
                            data-options="
                                editable:false,
                                url: 'getFiledOptions?field=type',
                                valueField: 'key',
                                textField: 'text',
                                panelHeight:'auto',
                                onSelect:selectType,
                            "
                        >
                    </div>
                    <hr>
                    <div class="fitem">
                        <label>输入框样式:</label>
                        <input name="class" id="class" class="easyui-combobox" required="true" 
                            data-options="
                                editable:false,
                                panelHeight:'auto',
                                valueField:'key',
                                textField:'text',
                                value:'easyui-textbox',
                                data: [
                                    {key:'easyui-textbox',text:'文本输入框'},
                                    {key:'easyui-numberbox',text:'数字输入框'},
                                    {key:'easyui-datebox',text:'日期输入框'},
                                    {key:'easyui-timespinner',text:'时间输入框'},
                                    {key:'easyui-datetimebox',text:'日期时间输入框'},
                                    {key:'easyui-combobox',text:'下拉选择框'},
                                    {key:'easyui-filebox',text:'文件上传框'},
                                    {key:'easyui-numberbox rate',text:'百分率输入框'},
                                    {key:'easyui-textbox derive',text:'派生值输入框'},
                                    {key:'easyui-numberbox phone-num',text:'手机号输入框'},
                                    {key:'easyui-textbox province',text:'省市区地址输入框'},
                                    {key:'easyui-filebox photo',text:'图片上传框'},
                                ],
                                onChange:selectClass,
                            "
                        >
                    </div>
                    <div class="fitem">
                        <label>是否多行输入框:</label>
                        <input name="multiline" id="multiline" class="easyui-combobox" required="true" value=""
                            data-options="
                                editable:false,
                                panelHeight:'auto',
                                valueField:'key',
                                textField:'text',
                                data:[
                                    {key:'0',text:'否'},
                                    {key:'1',text:'是'}
                                ],
                                //value:'\'\'',
                                onChange:changeSample,
                            "
                        >
                    </div>

                    <!-- 手机号类型专用设置 -->
                    <div id="is_phonenum"></div>
                    <!-- 派生值类型专用设置 -->
                    <div id="is_psz"></div>
                    <!-- 文本类型专用设置 -->
                    <div id="is_text"></div>
                    <!-- 数字类型专用设置 -->
                    <div id="is_num"></div>
                    <!-- 下拉选择框专用设置 -->
                    <div id="is_select"></div>

                    <hr>
                    <div style="padding:5px;text-align:center;">
                        <a href="#" onclick="do_sample()"  class="easyui-linkbutton" icon="icon-ok">提交</a>
                        <a href="#" class="easyui-linkbutton" onclick="javascript:$('#win').dialog('close')" icon="icon-cancel">取消</a>
                    </div>
                </form>
            </div>
            <!-- 文本类型专用设置原声页面 -->
                <div id="is_text_bak" style="display:none">
                    <hr>
                    <div class="fitem">
                        <label>最小长度:</label>
                        <input name="minLength" id="minLength" value="0" min="0" max="250">
                    </div>
                    <div class="fitem">
                        <label>最大长度:</label>
                        <input name="maxLength" id="maxLength" value="20" min="0" max="250">
                    </div>
                </div>
            <!-- 数字类型专用设置原生页面 -->
                <div id="is_num_bak" style="display:none">
                    <hr>
                    <div class="fitem">
                        <label>前缀:</label>
                        <input name="prefix" id="prefix" validType="length[1,10]">
                    </div>
                    <div class="fitem">
                        <label>后缀:</label>
                        <input name="suffix" id="suffix" validType="length[1,10]">
                    </div>
                    <div class="fitem">
                        <label>最小值:</label>
                        <input name="min" id="min">
                    </div>
                    <div class="fitem">
                        <label>最大值:</label>
                        <input name="max" id="max">
                    </div>
                    <div class="fitem">
                        <label>最小长度:</label>
                        <input name="minLength" id="minLength" value="0" min="0" max="250">
                    </div>
                    <div class="fitem">
                        <label>最大长度:</label>
                        <input name="maxLength" id="maxLength" value="20" min="0" max="250">
                    </div>
                    <div class="fitem">
                        <label>小数位数:</label>
                        <input name="precision" id="precision" min="0" max="4" required="true">
                    </div>
                    <div class="fitem">
                        <label>千分位分隔符:</label>
                        <input name="groupSeparator" id="groupSeparator" validType="length[1,1]">
                    </div>
                    <div class="fitem">
                        <label>小数点符号:</label>
                        <input name="decimalSeparator" id="decimalSeparator" required="true" validType="length[1,1]">
                    </div>
                </div>
            <!-- 下拉选择框专用设置原生页面 -->
                <div id="is_select_bak" style="display:none">
                    <hr>
                    <div class="fitem">
                        <label>下拉框多选:</label>
                        <input name="multiple" id="multiple" required="true"
                            data-options="
                                readonly:true,
                                disable:true,
                                editable:false,
                                panelHeight:'auto',
                                valueField:'key',
                                textField:'text',
                                data: [
                                    {key:'0',text:'否'},
                                    {key:'1',text:'是'},
                                ],
                                onChange:function(newValue,oldValue){
                                    changeSample(newValue,oldValue,true);
                                },
                            "
                        >
                    </div>
                    <label>下拉选择框选项:</label>
                    <div id="data">
                        <div class="fitem">
                            选项值:
                            <input name="data_value[]" class="value" required="true">
                            显示内容:
                            <input name="data_text[]" class="text" required="true">
                            <a href="#" class="easyui-linkbutton" onclick="javascript:addData();" icon="icon-add"></a>
                        </div>
                    </div>
                </div>
            <!-- 动态添加下拉选择框选项原生页面 -->
                <div id="add_data" style="display:none">
                    <div class="fitem">
                        选项值:
                        <input name="data_value[]" class="value" required="true">
                        显示内容:
                        <input name="data_text[]" class="text" required="true">
                        <a href="#" class="easyui-linkbutton" onclick="javascript:$(this).parent('.fitem').remove();changeSample('newValue','oldValue',true);" icon="icon-cancel"></a>
                    </div>
                </div>
            <!-- 派生值原生页面 -->
                <div id="is_psz_bak" style="display:none">
                    <hr>
                    <div class="fitem">
                        <label>输入公式:</label>
                        <input name="fun" id="fun" type="text" class="" required="true" validtype="length[0,200]" novalidate="true" data-options="multiline:true,prompt:'请输入公式\n示例：资料项id为1+资料项id为2\n{{1}}+{{2}}'" style="height:100px;">
                    </div>
                </div>
            <!-- 手机号类型专用设置原生页面 -->
                <div id="is_phonenum_bak" style="display:none">
                    <hr>
                    <div class="fitem">
                        <label>最小长度:</label>
                        <input name="minLength" id="minLength" value="11" min="0" max="250">
                    </div>
                    <div class="fitem">
                        <label>最大长度:</label>
                        <input name="maxLength" id="maxLength" value="11" min="0" max="250">
                    </div>
                    <div class="fitem">
                        <label>小数位数:</label>
                        <input name="precision" id="precision" min="0" max="0" required="true" value="0" readonly="true">
                    </div>
                    <div class="fitem">
                        <label>过滤号段:</label>
                        <input name="filtephonenum" id="filtephonenum" validType="length[0,30]">
                    </div>
                </div>
            <!-- 省市区原生页面 -->
                <!-- <div id="is_psz_bak" style="display:none">
                    <hr>
                    <div class="fitem">
                        <label>输入公式:</label>
                        <input name="fun" id="fun" type="text" class="" required="true" validtype="length[0,200]" novalidate="true" data-options="multiline:true,prompt:'请输入公式\n示例：资料项id为1+资料项id为2\n{{1}}+{{2}}'" style="height:100px;">
                    </div>
                </div> -->
            <!-- 样式展示 -->
            <div data-options="region:'east',title:'样式展示',border:false" style="width:50%;padding:10px 20px">
                <div id="sample_bak" style="display:none">
                    <div class="fitem">
                        <label>资料项名称:</label>
                        <input name="pool_sample" id="pool_sample" class="" required="true" validType="">
                        <a href="#"></a>
                    </div>
                </div>
                <div id="sample">
                    <div class="fitem">
                        <label>资料项名称:</label>
                        <input name="pool_sample" id="pool_sample" class="" required="true" validType="">
                    </div>
                </div>
                <!-- 派生值专用关联样本测试页面 -->
                <form id="psz_test_sample" method="post" novalidate style="display:none">
                    <hr>
                    <div class="fitem">
                        <label>资料项id展示:</label>
                        <input name="pool_sample_id[]" id="pool_sample_id" class="easyui-combotree"
                            data-options="
                                editable:true,
                                //url: 'getTreeSampleKey?root=1&sample_type='+sample_type,
                                valueField: 'id',
                                textField: 'pool_key',
                                panelHeight:'auto',
                                lines:true,
                                panelHeight:400,
                                multiple:true,
                                onChange:getSample,
                            "
                        >
                    </div>
                    <hr>
                    <input type="hidden" name="fun" id="fun">
                    <div id="res">
                        <div id="show_sample"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="copyRoleDialog" style="width:400px;height:150px;padding:10px 20px" class="easyui-dialog" closed="true" data-options="modal:true" title="给之前保存的样本复制其他样本的权限">
        <form id="copyRoleForm" method="post" novalidate>
            <input type="hidden" name="id">
            <div class="fitem">
                <label>选择相同权限的样本:</label>
                <input name="copy_id" id="copy_id" class="easyui-combotree" required="true" 
                    data-options="
                        editable:true,
                        //url: 'getTreeSampleKey?root=1&sample_type='+sample_type,
                        valueField: 'id',
                        textField: 'pool_key',
                        panelHeight:'auto',
                        lines:true,
                        panelHeight:400,
                    "
                >
            </div>
            <div style="padding:5px;text-align:center;">
                <a href="#" onclick="doCopyRole()"  class="easyui-linkbutton" icon="icon-ok">复制权限</a>
                <a href="#" class="easyui-linkbutton" onclick="javascript:$('#copyRoleDialog').dialog('close')" icon="icon-cancel">取消</a>
            </div>
        </form>
    </div>
    <div id="addShowTypeDialog" style="width:400px;height:150px;padding:10px 20px" class="easyui-dialog" closed="true" data-options="modal:true" title="保存数据类型">
        <form id="addShowTypeForm" method="post" novalidate>
            <div class="fitem">
                <label>数据类型名称:</label>
                <input name="show_type" id="show_type" class="easyui-combobox" required="true" validtype="length[1,20]"
                    data-options="
                        valueField: 'show_type',
                        textField: 'show_type',
                        groupField: 'level',
                        groupFormatter: function(group){
                            var color = '';
                            switch (group) {
                                case '基本类型':
                                    color = 'red';
                                    group += '————————';
                                    break;
                                case '定制类型':
                                    color = '#CC00FF';
                                    group += '————————';
                                    break;
                                case '自定义类型':
                                    color = 'blue';
                                    group += '———————';
                                    break; 
                            }
                            return '<span style=\'color:'+color+'\'>' + group + '</span>';
                        },
                        panelHeight:300,
                    "
                >
            </div>
            <div style="padding:5px;text-align:center;">
                <a href="#" onclick="doShowType()"  class="easyui-linkbutton" icon="icon-ok">保存</a>
                <a href="#" class="easyui-linkbutton" onclick="javascript:$('#addShowTypeDialog').dialog('close')" icon="icon-cancel">取消</a>
            </div>
        </form>
    </div>
</body>
<script type="text/javascript">
    var nowClass = '<?= $this->router->class?>';
    var nowMethod = 'do_pool';
    var typeData = JSON.parse('<?= $sample_types ?>');
    var sample_type = '<?= $sample_type ?>';

    //列表数据格式
    var col_data = [[
        {field: 'id',title:'ID',width:100,align:'center'},
        {field: 'pool_key',title:'资料名称',width:300,align:'left',halign:'center'},
        {field: 'is_json',title:'是否数据组',width:80,align:'center',formatter:function (value, row, index) {
                if(value==1){
                    myClass = 'icon-ok';
                }else{
                    myClass = 'icon-cancel';
                }
                return '<span class="'+myClass+'" style="display: inline-block;width: 11px;background-size: 100%;">&nbsp;</span>';
            }
        },
        {field: 'type',title:'数据类型',width:80,align:'center'},
        {field: 'class',title:'输入框样式',width:80,align:'center',formatter:function (value, row, index) {
                var arr = {
                    'easyui-textbox':'文本输入框',
                    'easyui-numberbox':'数字输入框',
                    'easyui-datebox':'日期输入框',
                    'easyui-timespinner':'时间输入框',
                    'easyui-datetimebox':'日期时间输入框',
                    'easyui-combobox':'下拉选择框',
                    'easyui-filebox':'文件上传框',
                    'easyui-textbox derive':'派生值输入框',
                    'easyui-numberbox phone-num':'手机号输入框',
                    'easyui-filebox photo':'图片上传框',
                    'easyui-numberbox rate':'百分率输入框',
                    'easyui-textbox province':'省市区地址输入框',
                };
                return arr[value];
            }
        },
        {field: 'create_date',title:'创建时间',width:150,align:'center',sortable:true},
        {field: 'edit_date',title:'编辑时间',width:200,align:'center',sortable:true},
        {field: 'cao_zuo',title:'操作',width:200,align:'center',sortable:true,
            formatter:function (value, row, index) {
                var html = '';
                <?php if (checkRolePower('Pool','do_sample')): ?>
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="edit('+row.id+')" >编辑 </a>'+'&nbsp;&nbsp';
                <?php endif ?>

                <?php if (checkRolePower('Pool','del_pool_s')): ?>
                html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="del('+row.id+')" >删除 </a>';
                <?php endif ?>
                
                return html;
            }
        },
    ]];
</script>
</html>