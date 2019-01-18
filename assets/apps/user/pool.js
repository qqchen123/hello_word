$.extend($.fn.validatebox.defaults.rules, {
    //手机号限制字段验证 by 奚晓俊
    mobile: {//value值为文本框中的值
        validator: function (value,params=''){
            if(params=='') return true;
            params = eval('['+params+']');
            var str = '/^(';
            $.each(params,function(k,v){
                if(k!=0) str += '|';
                str += '('+v+')';
            });
            str += ')/';
            var reg = eval(str);
            return !reg.test(value);
        },
        message: '输入的手机号码为限制号段.',
    },
    // //数字长度限制 by 奚晓俊
    // numLength: {
    //     validator: function (value,params=[]) {
    //         if(params==[]) return true;
    //         var length = value.length;
    //         if(length>=params[0] && length<=params[1]) return true;
    //     },
    //     message: '"输入数字长度必须介于{0}和{1}之间'
    // },

    //日期验证 by 奚晓俊
    date: {
        validator: function (value) {
            // console.log('日期');
            value = value.split('(',1)[0];
            return /^(?:(?!0000)[0-9]{4}([-]?)(?:(?:0?[1-9]|1[0-2])\1(?:0?[1-9]|1[0-9]|2[0-8])|(?:0?[13-9]|1[0-2])\1(?:29|30)|(?:0?[13578]|1[02])\1(?:31))|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)([-]?)0?2\2(?:29))$/i.test(value);
        },
        message: '日期格式yyyy-MM-dd或yyyy-M-d.',
    },

    //时间验证 by 奚晓俊
    time: {
        validator: function (value) {
            var tempArr = value.split(":");
            var h = tempArr[0];
            var m = tempArr[1];
            var s = tempArr[2];
            if(isNaN(s)) s = '00';
            if(h.length!=2 || m.length!=2 || s.length!=2) return false;
            var h = parseInt(h);
            var m = parseInt(m);
            var s = parseInt(s);

            return (h>=0 && h<23 && m>=0 && m<59 && s>=0 && s<59);
        },
        message: '时间格式hh:mm.',
    },

    //日期时间验证 by 奚晓俊
    datetime: {
        validator: function (value) {
            var tempArr = value.split(" ");
            var date = tempArr[0];
            var time = tempArr[1];

            date = date.split('(',1)[0];
            date = /^(?:(?!0000)[0-9]{4}([-]?)(?:(?:0?[1-9]|1[0-2])\1(?:0?[1-9]|1[0-9]|2[0-8])|(?:0?[13-9]|1[0-2])\1(?:29|30)|(?:0?[13578]|1[02])\1(?:31))|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)([-]?)0?2\2(?:29))$/i.test(date);

            var tempArr = time.split(":");
            var h = tempArr[0];
            var m = tempArr[1];
            var s = tempArr[2];
            if(isNaN(s)) s = '00';
            if(h.length!=2 || m.length!=2 || s.length!=2) return false;
            var h = parseInt(h);
            var m = parseInt(m);
            var s = parseInt(s);

            time = (h>=0 && h<23 && m>=0 && m<59 && s>=0 && s<59);
            //console.log(time);
            return (date && time);
        },
        message: '日期时间格式yyyy-MM-dd hh:mm:ss.',
    },

    //省市区地址 by 奚晓俊
    province: {
        validator: function (value) {
            // console.log('省市区地址');
            if(value.split(',').length!=3) return false;
            var bool = false;
            $.ajax({
                async : false,
                url : 'check_province',
                data : {address:value},
                success : function(data) {bool = JSON.parse(data).code}
            });
            // console.log(bool);
            return bool;
        },
        message: '省市区地址不正确.' ,
    },
});

$.fn.datebox.defaults.formatter = function (date){
    // console.log('formate');
    // console.log(date);
    date = new Date(date);
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    var day = date.getDay(); 
    // var hour = date.getHours(); 
    // var minutes = date.getMinutes(); 
    // var second = date.getSeconds(); 
    var week = ['日','一','二','三','四','五','六'];
    m<10?m='0'+m:m;
    d<10?d='0'+d:d;
    // hour<10?hour='0'+hour:hour;
    // minutes<10?minutes='0'+minutes:minutes;
    // second<10?second='0'+second:second;
    return y+'-'+m+'-'+d+'(周'+week[day]+')';
}

var obj_id;
var nowClass;
var nowMethod;

//获取派生值 by 奚晓俊
function getDeriveValue(){
    console.log('getDeriveValue');
    if(obj_id==undefined) alert("请在页面代码定义池值对象ID 'obj_id'值");
    if(nowClass==undefined) alert("请在页面代码定义当前访问类 'nowClass'值");
    if(nowMethod==undefined) alert("请在页面代码定义当前访问方法 'nowMethod'值");
    var o = $(this);
    var id = o.attr('id').substr(7,10);
    var label = o.textbox('options').label;
    if(label==null)
        label = o.parent('div').children('label').html();

    $.post('../PublicMethod/getDeriveValue',{obj_id:obj_id,id:id,class:nowClass,method:nowMethod},function(data){
        // console.log(data['info'].join(','));
        if(data.ret){
            o.textbox('initValue',data.info);
        }else{
            var str = '';
            var msgStr = '';
            $.each(data.info,function(k,v){
                str += v;
                msgStr += v+'<br>';
            });
            o.textbox('initValue','❌'+str);
            $.messager.show({
                title: '提示',
                msg: '❌获取“'+label+'”失败<br>'+msgStr,
            });
        }
    },'json');
}

//获取省市区地址 write by 陈恩杰 | 修改嵌入 by 奚晓俊 开始===========
var province_obj;
// var addressArr = [];
function showProvinceHtml(newValue,oldValue){
    if(newValue==='') return false;
    $('#provinceHtml').dialog('open');
    province_obj = $(this);
    if(oldValue===undefined){
        var address = $(this).textbox('getValue');
    }else{
        var address = oldValue;
        $(this).textbox('initValue',oldValue);
    }

    address = address.split(",");
    // console.log(address);

    $.each(address,function(k,v){
        //不是区县
        if(k!=2){
            if(v=='香港特别行政区'||v=='澳门特别行政区'){
                address[k] = v.substring(0,v.length-5);
            }else{
                address[k] = v.substring(0,v.length-1);
            }
        }
    });
    // console.log(address);
    $('#provinceHtml').dialog('open');
    $('#provinceHtml #province').combobox('setValue', address[0]);
    $('#provinceHtml #province1').combobox('setValue', address[1]);
    $('#provinceHtml #province2').combobox('setValue', address[2]);
}
$(function () {
    var provinceHtml = 
    '<div><div id="provinceHtml" class="easyui-dialog" closed="true" style="width:500px;height:250px;padding:10px 30px;" title="选择省市区地址" buttons="#dlg-buttons">\n' +
        '    <form id="provinceForm" method="post" style="width:400px;height:80px; margin:50px 0 0 50px;">\n' +
        '        <table>\n' +
        '<td>省:</td>'+
        '            <td>\n' +
        '                <td><input id="province" editable="true" class="easyui-combobox" data-options="' +
        'valueField:\'region_name\',' +
        'textField:\'region_name\',' +
        'url:\'../PublicMethod/get_city\',' +
        'onSelect:function(param){' +
        '$(\'#province1\').combobox(\'readonly\',false).combobox(\'reload\', \'../PublicMethod/get_city?c_id=\'+param.region_id);},' +
        'onUnselect:function(param){$(\'#province1,#province2\').combobox(\'clear\').combobox(\'readonly\',true).combobox(\'loadData\',\'[]\');}' +
        '" style="width:80px;"></td>\n' +
        '<td> &nbsp&nbsp市:</td>'+
        '                <td><input id="province1" class="easyui-combobox" data-options="' +
        'valueField:\'region_name\',' +
        'textField:\'region_name\',' +
        'onSelect:function(param){' +
        '$(\'#province2\').combobox(\'readonly\',false).combobox(\'reload\', \'../PublicMethod/get_city?c_id=\'+param.region_id);},' +

        'onUnselect:function(param){$(\'#province2\').combobox(\'readonly\',true).combobox(\'clear\').combobox(\'readonly\',true).combobox(\'loadData\',\'[]\');}' +

        '" style="width:80px;"></td>\n' +
        '<td> &nbsp&nbsp区/县:</td>'+
        '                <td><input id="province2" class="easyui-combobox" data-options="' +
        'valueField:\'region_name\',' +
        'textField:\'region_name\',' +
        '" style="width:80px;"></td>\n' +
        '            </tr>\n' +
        '        </table>\n' +
        '    </form>\n' +
        '<div style="padding:5px;text-align:center;">\n'+
        '<a href="#" class="easyui-linkbutton" data-options="iconCls:\'icon-ok\'" onclick="get_province()">输入</a>\n'+
        '<a href="#" class="easyui-linkbutton" onclick="javascript:$(\'#provinceHtml\').dialog(\'close\')" icon="icon-cancel">取消</a>\n'+
        '</div>\n'+
    '</div></div>';
    $('body').append(provinceHtml);
    $.parser.parse($('#provinceHtml').parent());
});
function get_province(){
    var province = $('#province').combobox('getText');
    var province1 = $('#province1').combobox('getText');
    var province2 = $('#province2').combobox('getText');

    if (province.length==0){
        $.messager.show({
            title: '提示',
            msg: '省 不能为空！',
        });return;
    }
    if (province1.length==0){
        $.messager.show({
            title: '提示',
            msg: '市 不能为空！',
        });return;
    }
    res_cp = check_pname(province,'province');
    if (!res_cp){
        $.messager.show({
            title: '提示',
            msg: '省地址格式不正确，请重新选择！',
        });return;
    }
    res_cp1 = check_pname(province1,'province1');
    if (!res_cp1){
        $.messager.show({
            title: '提示',
            msg: '市地址格式不正确，请重新选择！',
        });return;
    }
    res_cp2 = check_pname(province2,'province2');
    if (res_cp2 !== null || $res_cp2 !== undefined || $res_cp2 !== '') {
        if (!res_cp2){
            $.messager.show({
                title: '提示',
                msg: '区县地址格式不正确，请重新选择！',
            });return;
        }
    }
    var str = '';
    if(province=='北京'||province=='天津'||province=='重庆'||province=='上海'){
        str = province+'市,'+province1+'市,'+province2;
    }else if(province=='香港'||province=='澳门'){
        str = province+'特别行政区,'+province1+'市,'+province2;
    }else{
        str = province+'省,'+province1+'市,'+province2;
    }
    province_obj.textbox('initValue',str);
    $('#provinceHtml').dialog('close');
}
function check_pname(gtp,pro) {
    var p_data = $('#'+pro).combobox('getData');
    for (var i=0;i<p_data.length;i++)
    {
        if (p_data[i].region_name == gtp){
            return true;
        }
    }
}
//获取省市区地址 write by 陈恩杰 | 修改嵌入 by 奚晓俊 结束===========

//按样本格式生成input框 并加载已有值 加载专属事件 by 奚晓俊 开始=========
    //本地页面声明如下变量
        //var obj_id = <?=@$obj_id?>;//当前用户id
        //var nowClass = 'Pool';//当前js操作访问控制器
        //var nowMethod = '';//当前js操作访问方法
        //var divId='#input';//生成input框的外层id
    
    //本地页面改写如下变量
        //当前js操作方法的状态条件
            var nowStatus = '';
        //当前js操作具有的参数权限
            var detailPower = [];
        //已有样本值
            var loadData = {};
        //生产样本input基础样式 可在本地文件gai xie
            var inputHtml = '<div class="fitem"><label></label><input>&nbsp;<a href="#"></a><span class="status-info"></span></div>';

    //不同类型专属过滤值
    function filter(row){
        $.each(row,function(k,v){
            loadData['sample['+v.pool_sample_id+']'] = v.pool_val;

            //百分率类型
            if(v.class=='easyui-numberbox reate' ||v.type=='百分率')
                loadData['sample['+v.pool_sample_id+']'] *=100;
            //json类型
            if(v.is_json==1 && v.pool_val!==null){
                v.pool_val = JSON.parse(v.pool_val);
                //下拉框多选
                if(v.class=='easyui-combobox'){
                    loadData['sample['+v.pool_sample_id+'][]'] = v.pool_val.join(',');
                //其他json
                }else{
                    loadData['sample['+v.pool_sample_id+']'] = v.pool_val;
                }
            }
        });
    }

    /*
    * 创建input框
    *   row:加载的样本格式数据 
    *   divId:生成input框的外层id
    *   fore_end_check:是否开启前端验证 默认true
    *   show_fun:显示派生值公式（show_status_info显示状态（文字）时不显示）
    *   readonly_all:所有input只读
    *   if_status:row是否有状态 默认true
    *       show_status_info:是否显示状态（文字）默认true
    *       show_status_color:是否显示状态（颜色）默认true
    *       showCheckBox:符合当前js操作的是否显示多选框头 默认true
    *       readonly:不符合当前js操作的input是否只读 默认true
    */
    function buildInput(row,divId,fore_end_check=true,show_fun=false,readonly_all=true,if_status=true,show_status_info=true,show_status_color=true,showCheckBox=true,readonly=true){
        var label = divId+' div:last label';
        var input = divId+' div:last input:last';
        var a = divId+' div:last  a';
        var status_span = divId+' div:last .status-info';
        if(!if_status){
            show_status_info=false;
            show_status_color=false;
            showCheckBox=false;
            readonly=false;
        }

        $.each(row,function(k,v){
            //有当前js操作权限的 生成input
            if ($.inArray(v.id,detailPower)) {
                //创建基础div、label、input
                var name = 'sample'+'['+v.id+']';
                var id = 'sample_'+v.id;
                var data_options_arr = null;

                if(v.is_json==='1' && v.class!='easyui-combobox'){
                    name += '[0]';
                    var json_id = 0;
                }
                if(v.is_json==='1' && v.class=='easyui-combobox'){
                    name += '[]';
                }

                if(fore_end_check){
                    data_options_arr = JSON.parse(v['data-options']);
                    if (v['fore_end_check'])
                        v['fore_end_check'] = JSON.parse(v['fore_end_check']);

                    v['data-options'] = $.extend(data_options_arr,v['fore_end_check']);
                    v['data-options'] = JSON.stringify(v['data-options']);
                }

                $(divId).append(inputHtml);
                $(label).append(v.pool_key+':');

                // console.log('v.obj_status'+nowStatus);
                // console.log(eval('v.obj_status'+nowStatus));

                //判断状态显示复选框或只读
                // console.log(if_status);
                // console.log(v.obj_status);
                // console.log(v.obj_status+nowStatus);
                // console.log(eval('v.obj_status'+nowStatus));
                if(if_status && eval('v.obj_status'+nowStatus)){
                    //复选框头
                    if(showCheckBox && v.obj_status!==null)
                        $(label).prepend('<input type="checkbox" name="'+'pool_id'+'['+v.pool_id+']'+'" id="'+'check_'+v.id+'" checked>');

                }else{
                    //不符合当前js操作状态的只读
                    if(!readonly_all && readonly)
                        $(input).attr('disabled','disabled');
                        // $(input).attr('readonly','readonly');
                }
                //全只读
                if(readonly_all) $(input).attr('disabled','disabled');
                // if(readonly_all) $(input).attr('readonly','readonly');

                $(input)
                    .addClass(v.class)
                    .attr('name',name)
                    .attr('id',id)
                    // .attr('required',true)
                    .attr('data-options',v['data-options']);

                if(show_status_info){
                    $(status_span).html(v.obj_status_info?v.obj_status_info:'未录入');
                }else if(show_fun){
                    if(data_options_arr == null){
                        data_options_arr = JSON.parse(v['data-options']);
                    }
                    $(status_span).html(data_options_arr['fun']);
                }

                if(show_status_color)
                    $(status_span).parent().css('background-color',statusColor[v.obj_status]);

                //is_json数组
                if(v.is_json=='1' && v.class!='easyui-combobox'){
                    $(input).attr('json_id',json_id);
                    $(a).addClass("easyui-linkbutton").attr('onclick',"javascript:$(this).parent().remove();").attr('icon','icon-cancel');

                    var addHtml = '<div>';
                    addHtml += '<label></label>';
                    addHtml += $(input).prop('outerHTML');
                    addHtml += '&nbsp;';
                    addHtml += $(a).prop('outerHTML');
                    addHtml += '</div>';

                    
                    var clickHtml = "var obj = $(this).parent().children('div:last').children('input:first');";
                    clickHtml += "var json_id = obj.attr('json_id');";
                    clickHtml += "if(json_id==undefined) json_id=0;";
                    clickHtml += "json_id = parseInt(json_id)+1;";
                    clickHtml += "var name = 'sample["+v.id+"]['+json_id+']';";
                    clickHtml += "$(this).parent().append('"+addHtml+"');";
                    clickHtml += "var obj = $(this).parent().children('div:last').children('input:first');";
                    clickHtml += "obj.attr('name',name).attr('json_id',json_id);";
                    clickHtml += "$.parser.parse($('"+divId+"'));";

                    $(a).attr('icon','icon-add').attr('onclick',clickHtml);
                }
            }
        });
        $.parser.parse($(divId));
    }

    //json数组格式按值数量加载input框
    function buildJsonInput(divId){
        $.each(loadData,function(k,v){
            if($.isArray(v)){
                $.each(v,function(key,val){
                    loadData[k+'['+key+']'] = val;
                });
                var obj = $(divId+" div input[name='"+k+"[0]']").parent().next();
                for (var i = v.length - 1; i > 0; i--) {
                    obj.click();
                }
            }
        });
    }

    //不同class的专属事件 加载值后执行
    function buildAction(deriveAction=true){
        //派生值事件
        if(deriveAction){
            $('.derive').textbox({
                onChange:getDeriveValue,
                onClickButton:getDeriveValue,
            });
            // $('.derive').textbox('setValue','❌获取失败');
            clickAllDerive();
        }
        //省市区地址事件
        $('.province').textbox({
            // onChange:showProvinceHtml,
            onClickButton:showProvinceHtml,
        });
    }

    //打包按sample_id取子孙样本格式和已有数据
    function getSamplesAndPools(sample_id,showCheckBox,readonly_all){
        if (sample_id==='') return;
        $(divId).empty();

        //获取指定样本的所有子样本数据(含样本、数据、状态)
        url = '../'+nowClass+'/'+nowMethod;
        $.getJSON(url,{sample_id:sample_id,obj_id:obj_id,type:type,sample_type:sample_type},function(row){
            //不同类型专属过滤值
            filter(row);
            //创建input 
            buildInput(row,divId,true,false,readonly_all,true,true,true,showCheckBox,true);
            //json数组格式按值数量加载input框
            buildJsonInput(divId);
            //加载值
            $('#poolForm').form('load',loadData);
            //专属事件
            buildAction();
        });
    }
//按样本格式生成input框 并加载已有值 加载专属事件 by 奚晓俊 结束=========














