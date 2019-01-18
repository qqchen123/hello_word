

// 编辑资料名称
/*function editpool() {
    $('#ffs').form('submit', {
        'url':"<?php //echo site_url() . '/pool/edit_ps'; ?>",
        'success': function (result) {
            var result = eval("(" + result + ")");
            if (result.code == 0) {
                $.messager.show({
                    title: '提示',
                    msg: result.message
                });
            } else {
                $('#win').dialog('close');
                $.messager.show({
                    title: '提示',
                    msg: result.message
                });
                $('#zjc').datagrid('reload');
            }
        }
    });
}*/
//搜索
// $('#likeBtn').on('click',function () {
//     var like = $('#like ').val();
//     $('#zjc').datagrid('load',{like:like});
// });

//样本增删改查 开始--------------
    // 编辑样本的界面的显示
    function edit(id) {
        $('#win').window('open').dialog('setTitle','编辑资料项');
        backInput();
        $('#is_json').combobox('readonly',false);
        // $('#multiline').combobox('setValue',"''");
        $.getJSON('getOneSample',{id:id},function(row){
            // console.log(row);
            $.extend( row, JSON.parse(row['data-options']));
            if(!$.isEmptyObject(row.data)) formData.data = row.data;
            if(row.type=='判断'){
                 formData.data_if = row.data;
            }else{
                 formData.data_if = [{'value':0,'text':'否'},{'value':1,'text':'是'}];
            }
            $('#win #ff').form('load',row);
        });
        $('#parent_id').combobox('readonly',true);
    }

    //添加样本的显示界面
    function show_add() {
        $('#win').window('open').dialog('setTitle','新增资料项');
        backInput();
        $('#parent_id').combobox('readonly',false);
        $('#is_json').combobox('readonly',false);
    }

    // 删除资料名称
    function del(id) {
        $.messager.confirm('Confirm','是否确定删除！',function (r) {
            if (r){
                $.getJSON('del_pool_s',{id:id},function(row){
                    if (row.code == 0) {
                        $.messager.show({
                            title: '提示',
                            msg: row.message
                        });
                    } else {
                        $.messager.show({
                            title: '提示',
                            msg: row.message
                        });
                        $('#zjc').treegrid('remove',id);
                    }
                });
            }
        });
    }

    var selectId=0;
    // 添加、编辑资料名称
    function do_sample() {
        $('#ff').form('submit', {
            'url':'do_sample',
            queryParams:{sample_type:sample_type},
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function (result) {
                var result = eval("(" + result + ")");
                if(!result.ret){
                    $.messager.show({
                        title: '提示',
                        msg: result.info
                    });
                }else{
                    $('#win').dialog('close');
                    $.messager.show({
                        title: '提示',
                        msg: result.info
                    });
                    selectId = result.ret;
                    //添加后弹出复制权限、编辑无
                    if (result.info=='资料项添加成功') {
                        $('#copyRoleDialog').dialog('open');
                        $('#copy_id').combotree('reload','getTreeSampleKey?root=1&sample_type='+sample_type);
                        $("#copyRoleDialog input[name='id']").val(result.ret);    
                    }
                    $('#zjc').treegrid('reload').treegrid({onLoadSuccess:function(){
                            $(this).treegrid('select',selectId).treegrid('expandTo',selectId);
                        }
                    });
                }
            }
        });
    }

    //提交复制样本权限
    function doCopyRole(){
        $('#copyRoleForm').form('submit', {
            'url':'copy_role',
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function (result) {
                var result = eval("(" + result + ")");
                if(!result.ret){
                    $.messager.show({
                        title: '提示',
                        msg: result.info
                    });
                }else{
                    $('#copyRoleDialog').dialog('close');
                    $.messager.show({
                        title: '提示',
                        msg: result.info
                    });
                }
            }
        });
    }
//样本增删改查 结束--------------

//用户自定义数据类型 开始----------
    //显示保存假数据类型页面
    function addShowType(){
        $('#ff #parent_id').combobox('disable');
        $('#ff #pool_key').textbox('disable');
        if(!$('#ff').form('enableValidation').form('validate')){
            $.messager.show({
                title: '提示',
                msg: '请输入除“选择父项”和“资料名称”以外的“红色框”必填项.'
            });
            $('#ff #parent_id').combobox('enable');
            $('#ff #pool_key').textbox('enable');
            return;
        }
        $('#addShowTypeDialog').window('open');
        $('#addShowTypeForm #show_type').combobox('reload','getShowTypes?level=2');
        $('#ff #parent_id').combobox('enable');
        $('#ff #pool_key').textbox('enable');
    }

    //执行保存假数据类型
    function doShowType(check_unique=true){
        //验证数据类型名称
        if(!$('#addShowTypeForm').form('enableValidation').form('validate'))
            return false;

        //覆盖提醒
        if(check_unique){
            var show_type = $('#addShowTypeForm #show_type').combobox('getValue');
            var arr = $('#addShowTypeForm #show_type').combobox('getData');
            var is_unique = true;
            $.each(arr,function(k,v){
                if(v.show_type==show_type){
                    myConfirm('“ '+show_type+' ”已存在，确认覆盖？','doShowType',false);
                    is_unique = false;
                    return false;
                }
            });
            if(!is_unique) return false;
        }

        //提取数据
        var o = {};
        $.each($("#ff").serializeArray(),function(k,v) {
            if (o[v.name] !== undefined) {
                if (!o[v.name].push) o[v.name] = [o[v.name]];
                o[v.name].push(v.value || '');
            }else{
                o[v.name] = v.value || '';
            }
        });
        delete o.pool_key;
        delete o.parent_id;
        delete o.id;
        o.show_type = $('#addShowTypeForm #show_type').combobox('getValue');
        // console.log(o);
        //提交数据
        $('#addShowTypeForm').form('submit', {
            url:'do_show_type',
            queryParams:o,
            success: function (result) {
                var result = eval("(" + result + ")");
                if(!result.ret){
                    $.messager.show({
                        title: '提示',
                        msg: result.info
                    });
                }else{
                    $('#addShowTypeDialog').dialog('close');
                    $.messager.show({
                        title: '提示',
                        msg: result.info
                    });
                    // $('#addShowTypeForm #show_type').combobox('reload');
                    $('#ff #show_type').combobox('reload').combobox('setValue',o.show_type);
                }
            }
        });
    }

    //删除假数据类型
    function delShowType(first=true){
        if(!$('#ff #show_type').combobox('isValid')){
            $.messager.show({
                title: '提示',
                msg: '请选择要删除的“数据类型”.',
            });
            return false;
        }

        var show_type = $('#ff #show_type').combobox('getValue');
        var data = $('#ff #show_type').combobox('getData');
        var check_level = true;
        $.each(data,function(k,v){
            if(v.show_type==show_type && v.level!=='自定义类型'){
                $.messager.show({
                    title: '提示',
                    msg: '该数据类型不能删除,只有“自定义类型”可以删除.',
                });
                check_level = false;
            }
        });
        if(!check_level) return false;

        if(first){
            myConfirm('确认删除“ '+show_type+' ”数据类型','delShowType',false);
        }else{
            $.getJSON('delShowType',{show_type:show_type},function(row){
                if (row) {
                    $.messager.show({
                        title: '提示',
                        msg: '数据类型删除成功.'
                    });
                    $('#ff #show_type').combobox('reload').combobox('clear');
                    backInput(false,false);
                } else {
                    $.messager.show({
                        title: '提示',
                        msg: '数据类型删除失败.'
                    });
                }
            });
        }
    }

    //确认框
    function myConfirm(msg,fun,id){
        $.messager.confirm("确认", msg, function(r) {
            if (r) window[fun](id); 
        });
        return false;
    }
//用户自定义数据类型 结束----------

//样本定样式展示联动 开始--------
    //初始设置
    var nowClass;
    var nowMethod;
    var user_id;

    //动态默认值 不能含type、class
    var formData = {
        prefix:'',
        suffix:'',
        max:null,
        min:null,
        precision:'0',
        groupSeparator:',',
        decimalSeparator:'.',
        multiple:'0',
        data:[],
        data_if:[{'value':0,'text':'否'},{'value':1,'text':'是'}],
        is_if:false
    };

    var dataOptions = {};

    //缓存动态值
    function cacheChangeValue(o){

        if(!$.isEmptyObject(o.data))
            // formData.data = o.data;
        if(!$.isEmptyObject(o.multiple))
            formData.multiple = o.multiple;
        if(!$.isEmptyObject(o.prefix))
            formData.prefix = o.prefix;
        if(!$.isEmptyObject(o.suffix))
            formData.suffix = o.suffix;
        if(!$.isEmptyObject(o.max))
            formData.max = o.max;
        if(!$.isEmptyObject(o.min))
            formData.min = o.min;
        if(!$.isEmptyObject(o.precision))
            formData.precision = o.precision;
        if(!$.isEmptyObject(o.groupSeparator))
            formData.groupSeparator = o.groupSeparator;
        if(!$.isEmptyObject(o.decimalSeparator))
            formData.decimalSeparator = o.decimalSeparator;
    }

    //还原动态input、清除表单值、父级重新加载
    function backInput(clear=true,reload_parent_id=true){
        // console.log('backInput  '+(clear?'true':'false')+'  '+(reload_parent_id?'true':'false'));
        $('#is_text').empty();
        $('#is_num').empty();
        $('#is_select').empty();
        $('#is_psz').empty();
        $('#is_phonenum').empty();
        if (clear) {
            $('#win #ff').form('clear');
        }else{
            //$('#win #ff #type').combobox('clear');
        }
        if (reload_parent_id){
            $('#parent_id').combotree('reload','getTreeSampleKey?root=1&sample_type='+sample_type);
            $('#pool_sample_id').combotree('reload','getTreeSampleKey?root=1&sample_type='+sample_type);
        }
        $('#psz_test_sample').css('display','none');
    }

    //改变展示样本
    function changeSample(newValue,oldValue,cacheValue=false){
        console.log('changeSample');
        $('#sample').empty();
        $('#sample').html($('#sample_bak').html());
        var o = {};
        dataOptions = {};

        $.each($("#ff").serializeArray(),function(k,v) {
            if (o[v.name] !== undefined) {
                if (!o[v.name].push) o[v.name] = [o[v.name]];
                o[v.name].push(v.value || '');
            }else{
                o[v.name] = v.value || '';
            }
        });

        //基本设置项 开始==============================           
            //父项
            if("parent_id" in o){}
            //资料名称
            if("pool_key" in o) $('#sample label').html(o.pool_key);
            //输入框样式
            if("class" in o) $('#sample #pool_sample').addClass(o.class);
            //是否多行输入框
            if("multiline" in o ) dataOptions.multiline = Number(o.multiline);
        //基本设置项 结束===============================

        //长度限制（文本、手机号、数字）开始===============
            //最小长度
            if("minLength" in o && o.minLength!=''){
                var minLength = Number(o.minLength);
            }else{
                var minLength = 0;
            }
            //最大长度
            if("maxLength" in o && o.maxLength!='') {
                var maxLength = Number(o.maxLength);
            }else{
                var maxLength = 250;
            }
            // if(minLength<=maxLength)
            dataOptions.validType = ['length['+minLength+','+maxLength+']'];
        //长度限制（文本、手机号、数字）结束===============

        //派生值专用 开始--------------------------------
            if(o.class=='easyui-textbox derive') {
                dataOptions.buttonIcon = 'icon-reload';
                dataOptions.fun = o.fun;
                    // onChange:function(){ getDeriveValue()},
                //dataOptions.onClickButton = getDeriveValue;
                // dataOptions.readonly = true;
            }
        //派生值专用 结束--------------------------------

        //省市区地址专用 开始--------------------------------
            if(o.class=='easyui-textbox province') 
                dataOptions.buttonIcon = 'icon-filter';
        //省市区地址专用 结束--------------------------------

        //手机号专用 开始--------------------------------
            if(o.class =='easyui-numberbox phone-num') {
                dataOptions.validType.unshift('mobile['+o.filtephonenum+']');
            }
        //手机号专用 结束--------------------------------

        //日期时间框专用 开始--------------------------------
            if(o.class =='easyui-datebox') 
                dataOptions.validType.unshift('date');
            if(o.class =='easyui-timespinner') 
                dataOptions.validType.unshift('time');
            if(o.class =='easyui-datetimebox') 
                dataOptions.validType.unshift('datetime');
        //日期时间框专用 结束--------------------------------



        //数字框专用 开始--------------------------------
            //前缀
            if("prefix" in o && o.prefix!='') dataOptions.prefix = o.prefix;
            //后缀
            if("suffix" in o && o.suffix!='') dataOptions.suffix = o.suffix;
            //最大值
            if("max" in o && o.max!='') dataOptions.max = Number(o.max);
            //最小值
            if("min" in o && o.min!='') dataOptions.min = Number(o.min);
            //小数位数
            if("precision" in o && o.precision!='') dataOptions.precision = o.precision;
            //千分位
            if("groupSeparator" in o && o.groupSeparator!='') dataOptions.groupSeparator = o.groupSeparator;
            //小数点符号
            if("decimalSeparator" in o && o.decimalSeparator!='') dataOptions.decimalSeparator = o.decimalSeparator;
        //数字框专用 结束--------------------------------

        //下拉框可选项 开始---------------------------------
            if(!$.isEmptyObject(o['data_value[]'])) {
                o.data = [];
                //var arr = [];
                if( typeof o['data_value[]']=='string') o['data_value[]'] = [o['data_value[]']];
                $.each(o['data_value[]'],function(k,v){
                    o.data[k] = {
                        value:v,
                        text:o['data_text[]'][k]
                    }
                });

                // arr = JSON.stringify(o.data).html();
                dataOptions.valueField = 'value';
                dataOptions.textField = 'text';
                dataOptions.data = o.data;

            }
        //下拉框可选项 结束---------------------------------

        if(cacheValue) cacheChangeValue(o);

        //是否为数组 放到最后取input 开始---------------------
        if("is_json" in o && o.is_json=='1'){
            if(o.class!='easyui-combobox'){
                $('#sample a').addClass("easyui-linkbutton").attr('onclick',"javascript:$(this).parent().remove();").attr('icon','icon-cancel');
                $('#sample input').attr('name',$('#sample input').attr('name')+'[]');
                var addHtml = '<div>';
                addHtml += '<label></label>  ';
                addHtml += $('#sample input').prop('outerHTML');
                addHtml += '&nbsp;';
                addHtml += $('#sample a').prop('outerHTML');
                addHtml += '</div>';

                $('#sample a').attr('icon','icon-add').attr('onclick',"$(this).parent().append('"+addHtml+"');$.parser.parse($('#sample'));");
            //$('#sample #').html(o.pool_key);
            }else{
                dataOptions.multiple = o.multiple;
            }
        }
        //是否为数组 放到最后取input 结束---------------------

        var dataOptionsStr = JSON.stringify(dataOptions);
        $('#sample #pool_sample').attr('data-options',dataOptionsStr);
        $.parser.parse($('#sample'));
        // $('#sample #pool_sample').numberbox('validType', 'length[0,1]');

        //派生值事件
        $('.derive').textbox({
            onChange:evalDeriveFun,
            onClickButton:evalDeriveFun,
        });
        // $('.derive').textbox('setValue','❌获取失败');
        clickAllDerive();

        //省市区地址事件
        $('.province').textbox({
            //onChange:showProvinceHtml,
            onClickButton:showProvinceHtml,
        });
    }

    //选择假类型 赋值其配置
    function selectShowType(row){
        // console.log('selectShowType');
        backInput(false,false);
        $('#ff #type').combobox('clear');
        var row2 = {};
        $.extend(row2,row);
        if(row2['data-options']!=='') $.extend(row2, JSON.parse(row2['data-options']));
        delete row2.show_type;

        $('#win #ff').form('load',row2);
    }

    //选择数据类型联动选取默认样式
    function selectType(param){
        // console.log('selectType');
        formData.is_if = false;
        backInput(false,false);

        $('#is_json').combobox('enable').combobox('readonly',false);
        $('#class').combobox('clear').combobox('readonly',false);
        switch (param.key) {
            case '判断':
                formData.is_if = true;
                $('#class').combobox('setValue','easyui-combobox');
                break;
            case '文本':
                $('#class').combobox('setValue','easyui-textbox');
                break;
            case '数字':
                $('#class').combobox('setValue','easyui-numberbox');
                break;
            case '日期':
                $('#class').combobox('setValue','easyui-datebox');
                break;
            case '时间':
                $('#class').combobox('setValue','easyui-timespinner');
                break;
            case '日期时间':
                $('#class').combobox('setValue','easyui-datetimebox');
                break;
            case '百分率':
                $('#class').combobox('setValue','easyui-numberbox rate');
                break;
            case '文件':
                $('#class').combobox('setValue','easyui-filebox');
                break;
            case '图片':
                $('#class').combobox('setValue','easyui-filebox photo');
                break;
            case '派生值':
                $('#class').combobox('setValue','easyui-textbox derive');
                break;
            case '手机号':
                $('#class').combobox('setValue','easyui-numberbox phone-num');
                break;
            case '省市区地址':
                $('#class').combobox('setValue','easyui-textbox province');
                break;
        }
    }

    //选择输入框样式
    function selectClass(param){
        // console.log('selectClass');
        backInput(false,false);

        $('#is_json').combobox('enable').combobox('readonly',false);
        switch (param) {
            case 'easyui-textbox':
                $('#is_text').html($('#is_text_bak').html());
                $("#is_text #maxLength").numberbox()
                .numberbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_text #minLength").numberbox()
                .numberbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                break;
            case 'easyui-datebox':
                // $('#class').combobox('readonly',true);
                break;
            case 'easyui-datetimebox':
                // $('#class').combobox('readonly',true);
                break;
            case 'easyui-timespinner':
                // $('#class').combobox('readonly',true);
                break;
            case 'easyui-numberbox':
                $('#is_num').html($('#is_num_bak').html());
                $("#is_num #prefix").textbox()
                .textbox({
                    value:formData.prefix,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #suffix").textbox()
                .textbox({
                    value:formData.suffix,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #max").numberbox()
                .numberbox({
                    value:formData.max,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #min").numberbox()
                .numberbox({
                    value:formData.min,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #maxLength").numberbox()
                .numberbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #minLength").numberbox()
                .numberbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #precision").numberspinner()
                .numberspinner({
                    value:formData.precision,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #groupSeparator").textbox()
                .textbox({
                    value:formData.groupSeparator,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #decimalSeparator").textbox()
                .textbox({
                    value:formData.decimalSeparator,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                break;
            case 'easyui-combobox':
                $('#is_select').html($('#is_select_bak').html());
                $('#is_select #multiple').combobox()
                .combobox({
                    value:formData.multiple,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $('#is_select #data input').textbox();
                setDataValue();
                $('#is_select #multiple')/*.combobox('disable')*/.combobox('setValue',$('#is_json').combobox('getValue'));
                break;
            case 'easyui-numberbox rate'://百分率输入框
                $('#is_num').html($('#is_num_bak').html());
                $("#is_num #suffix").text()
                .textbox('setValue','%')
                .textbox({
                    value:formData.prefix,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #suffix").textbox()
                .textbox({
                    value:formData.suffix,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #max").numberbox()
                .numberbox({
                    value:formData.max,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #min").numberbox()
                .numberbox({
                    value:formData.min,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #maxLength").numberbox()
                .numberbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #minLength").numberbox()
                .numberbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #precision").numberspinner()
                .numberspinner({
                    value:formData.precision,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #groupSeparator").textbox()
                .textbox({
                    value:formData.groupSeparator,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_num #decimalSeparator").textbox()
                .textbox({
                    value:formData.decimalSeparator,
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                // $('#class').combobox('readonly',true);
                break;
            //派生值输入框
            case 'easyui-textbox derive':
                $('#is_psz').html($('#is_psz_bak').html());
                $("#is_psz #fun").textbox();
                $("#is_psz #fun").textbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                // $('#multiline').combobox('setValue','0');
                $('#is_json').combobox('disable').combobox('initValue','0');
                $('#class').combobox('readonly',true);
                $('#psz_test_sample').css('display','');
                break;
            //手机号输入框
            case 'easyui-numberbox phone-num':
                $('#is_phonenum').html($('#is_phonenum_bak').html());
                $("#is_phonenum #maxLength").numberbox();
                $("#is_phonenum #maxLength").numberbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_phonenum #minLength").numberbox();
                $("#is_phonenum #minLength").numberbox({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_phonenum #precision").numberspinner();
                $("#is_phonenum #precision").numberspinner({
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $("#is_phonenum #filtephonenum").textbox();
                $("#is_phonenum #filtephonenum").textbox({
                    prompt:'多号段请用英文逗号(,)隔开！',
                    onChange:function(newValue,oldValue){
                        changeSample(newValue,oldValue,true);
                    },
                });
                $('#multiline').combobox('setValue','0');
                $('#class').combobox('readonly',true);
                break;
            //省市区地址输入框
            case 'easyui-textbox province':
                $('#is_json').combobox('disable').combobox('setValue','0');
                $('#class').combobox('readonly',true);
                break;
        }
        changeSample('newValue','oldValue'); 
    }

    //动态添加下拉框选项
    function addData(){
        $('#is_select #data').append($('#add_data').html());
        $('#data div:last input').addClass('easyui-textbox adddata').textbox().textbox({
            onChange:function(newValue,oldValue){
                changeSample(newValue,oldValue,true);
            }
        });
    }

    //赋值添加下拉框选项
    function setDataValue(){
        var arr;
        if (formData.is_if){
            arr = formData.data_if;
        }else{
            arr = formData.data;
        }

        $.each(arr,function(k,v){
            if(k!=0) addData();
            $('#data div:last .value').textbox({
                value:v.value,
            });
            $('#data div:last .text').textbox({
                value:v.text,
            });
        });
        changeSample('newValue','oldValue'); 
    }
//样本定样式展示联动 结束--------

//派生值输入样本 开始--------
    // var test_sample={};
    //按勾选的样本生成 相关样本输入框 供派生值定义公式
    function getSample(){
        //console.log('getSample');
        $('#show_sample').html('');
        var test_sample = $(this).combotree('tree').tree('getChecked');
        if (!$.isEmptyObject(test_sample)){
            var divId = '#res #show_sample';
            var ids = [];
            $.each(test_sample,function(k,v){
                ids[k] = v.id;
            });
            $.getJSON('getSamplesByIds',{ids:ids,sample_type:sample_type},function(data){
                // console.log(data);
                if(!data) return false;
                //创建input 
                buildInput(data,divId,true,true,false,false);
                //json数组格式按值数量加载input框
                buildJsonInput(divId);
                //专属事件
                buildAction(false);

                //label加{{id}}
                $.each(data,function(k,v){
                    $(divId+' #sample_'+v.id).prev().html(v.pool_key+'{{'+v.id+'}}');
                });
                //改写派生值事件
                $('.derive').textbox({
                    onChange:evalDeriveFun,
                    onClickButton:evalDeriveFun,
                });
                // $('.derive').textbox('setValue','❌获取失败');
                clickAllDerive();
            });
        }
    }

    //所有派生值点击
    function clickAllDerive(){
        $('.derive').next('span').find('.icon-reload').click();
    }

    //派生值远端传值计算
    //type 可选值 derive(派生值)、decision(策略)、decision_tree(策略树)
    function evalDeriveFun(o=null,type='decision'){
        console.log('evalDeriveFun');

        if(o===null){
            //派生值测试公式取值
            var is_derive = true;
            var o = $(this);
            var fun = JSON.parse(o.attr('data-options'))['fun'];
        }else{
            //策略、策略树测试公式取值
            var is_derive = false;
            var fun = o.fun;
        }

        var re = /{{(\d*)}}/g;
        var funSample = true;
        var sample_val;
        var label = '';
        var msgLabel = '';

        //验证公式是否存在
        if(fun=='' || fun == null){
            if(is_derive){
                o.textbox('initValue','❌未定义公式！');
                var label = o.prev('label').text();
            }

            $.messager.show({
                title: '提示',
                msg: label+'未定义公式！',
            });

            return false;
        }
       
        while(sample = re.exec(fun)) {
            // console.log(sample);
            if(sample==null) break;

            sample_val = $('#sample_'+sample[1]).val();
            if(sample_val==null || sample_val===''){
                str = $('#sample_'+sample[1]).prev('label').text();
                if(str){
                    label += str+'值为空！';
                    msgLabel += str+'值为空！<br>';
                }else{
                    label += sample[0]+'值为空！';
                    msgLabel += sample[0]+'值为空！<br>';
                }
                funSample = false;
            }
        }

        if(!funSample){
            $.messager.show({
                title: '提示',
                msg: msgLabel,
            });
            if(is_derive)
                o.textbox('initValue','❌'+label);
            return false;
        }    

        $('#psz_test_sample #fun').val(fun);
        if(!$('#psz_test_sample').form('enableValidation').form('validate')) return false;

        $.ajax({
            url:'evalDeriveFun',
            type:'post',
            dataType: 'json',
            success: function (data) {
                if(!is_derive){
                    $('#res #name').text(o.name);
                    $('#res #res_fun').text(data.fun);

                    //策略公式测试
                    if(data.ret){
                        //策略
                        if(type=='decision'){
                            $('#res #res_return').text(data.info);
                        
                        //策略树
                        }else{
                            var r = data.info;
                            var decision_id = o['return['+r+'][decision_id]'];
                            var action_id = o['return['+r+'][parent_return_action]'];
                            var res_info = o['return['+r+'][parent_return_info]'];
                            $('#res_info').text(res_info);
                            
                            $.each(actionData,function(k,v){
                                if(v.id==action_id){
                                    action_id = v.text;
                                    return;
                                }
                            });
                            $('#res_action').text(action_id);

                            $.each(decisionData,function(k,v){
                                if(v.id==decision_id){
                                    decision_id = v.text;
                                    return;
                                }
                            });
                            $('#res_decision').text(decision_id);
                        }
                    }
                }else{
                    //派生值策略测试
                    if(!data.ret){
                        var str = '';
                        var msgStr = '';
                        $.each(data.info,function(k,v){
                            str += v;
                            msgStr += v+'<br>';
                        });
                        $.messager.show({
                            title: '提示',
                            msg: msgStr,
                        });
                        o.textbox('initValue','❌'+str);
                    }else{
                        o.textbox('initValue',data.info);
                    } 
                }
            },
            data:$('#psz_test_sample').serialize()/*+'&sample_type='+sample_type*/,
            async: false,
        });

        // $('#psz_test_sample').form('submit', {
        //     'url':'evalDeriveFun',
        //     onSubmit: function() {
        //         return $(this).form('enableValidation').form('validate');
        //     },
        //     success: function (result) {
        //         var result = eval("(" + result + ")");
        //         // var oo = o;
        //         if(!result.ret){
        //             $.messager.show({
        //                 title: '提示',
        //                 msg: result.info
        //             });
        //             o.textbox('initValue','❌'+result.info);
        //         }else{
        //             o.textbox('initValue',result.info);
        //         }
        //     }
        // });
    }
//派生值输入样本 结束--------



