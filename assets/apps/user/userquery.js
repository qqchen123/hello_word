String.prototype.replaceAll = function(f,e){//吧f替换成e
    var reg = new RegExp(f,"g"); //创建正则RegExp对象   
    return this.replace(reg,e); 
}

//元素 模板
globalData['tpl_array'] = {
	lable_tpl:'lable',
	input_text_tpl:'textinput',
	input_file_tpl:'fileinput',
	input_select_tpl:'ordinaryselect',
	button: 'button',
};
<<<<<<< .mine
globalData['tpl'] = [];
function load_tpl() {
	for (item in tpl_array) {
		$.ajax({ 
	        type : "get", 
	        url : AJAXBASEURL + tplPath + 'v001/' + tpl_array[item], 
	        async : false, 
	        success : function(data){ 
	            globalData['tpl'][item] = data;
	         } 
	     });
	 }
 }
load_tpl();
||||||| .r258
globalData['tpl'] = [];
function load_tpl() {
	for (item in tpl_array) {
		$.ajax({ 
	        type : "get", 
	        url : AJAXBASEURL + tplPath + 'v001/' + tpl_array[item], 
	        async : false, 
	        success : function(data){ 
	            globalData['tpl'][item] = data;
	        } 
	    });
	}
}
load_tpl();
=======
>>>>>>> .r291

globalData['edit_box_title'] = '编辑客户信息';
globalData['edit_box_btn'] = '提交';
globalData['page_type'] = 'user';
globalData['edit_box_element_conf'] = 'w3';
globalData['input_style'] = 'width:260px';

globalData['listconfig'] = [
	[
        {field: 'fuserid', title: '客户编号', width: 100, align:'center'},
        {field: 'channel', title: '渠道编号', width: 100, align:'center'},
        {field: 'idnumber', title: '身份证号', width: 160,  align:'center'},
        {field: 'name', title: '姓名', width: 100,  align:'center'},
        {field: 'cjyg', title: '创建员工', width: 100,  align:'center'},
        {field: 'ctime', title: '最后录入时间', width: 160,  align:'right',halign:'center'},
        {field: 'op', title: '操作', width: 400,  align:'center'}
    ]
];

globalData['web_config'] = {
    'element' : [
        {samples: [6,8,9,10,11,12,13,14,15,16,17,21,22,23,24], class: 'basic'},

    ],
    'title': ['基础信息'],
    'nav' : [
        {'title':'基础信息', 'relation-class': 'basic', 'id': 'nav-basic'},
    ]
};

globalData['check_box_config'] = [
	[
		{
        	box_class: 'fitem',
            lable_class:'w60', lable_textbox: '姓名', 
            input_class:'easyui-textbox',  input_name:'name', input_id: 'check-name', input_style: 'height:30px;', input_tpl:'input_text_tpl'
        },
        {
        	box_class: 'fitem',
            lable_class:'w60', lable_textbox: '身份证', 
            input_class:'easyui-textbox',  input_name:'idnumber', input_id: 'check-idnumber', input_style: 'height:30px;', input_tpl:'input_text_tpl'
        },
        {
        	box_class: 'fitem ib w2',
            lable_class:'w60', lable_textbox: '渠道名称', 
            input_class:'easyui-combobox',  input_name:'channel', input_id: 'check-channel', input_style: 'height:30px;', input_tpl:'input_select_tpl'
        },
        {
            box_class: 'fitem ib w2',
            lable_class:'w60', lable_textbox: '渠道编号', 
            input_class:'easyui-combobox',  input_name:'channel_id', input_id: 'check-channel-id', input_style: 'height:30px;', input_tpl:'input_select_tpl'
        },
	],
	[
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'check-submit', btn_title:'提交', btn_type: 'button'
		},
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'check-reset', btn_title:'重置', btn_type: 'button'
		},
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'check-cancel', btn_title:'取消', btn_type: 'button'
		},
	]
];

globalData['change_box_config'] = [
	[
		{
        	box_class: 'fitem ib w2',
            lable_class:'w60', lable_textbox: '姓名', 
            input_class:'easyui-textbox',  input_name:'name', input_id: 'change-name', input_style: 'height:30px;', input_tpl:'input_text_tpl'
        },
        {
        	box_class: 'fitem ib w2',
            lable_class:'w60', lable_textbox: '身份证', 
            input_class:'easyui-textbox',  input_name:'idnumber', input_id: 'change-idnumber', input_style: 'height:30px;', input_tpl:'input_text_tpl'
        },
        {
            box_class: 'fitem ib w2',
            lable_class:'w60', lable_textbox: '渠道名称', 
            input_class:'easyui-combobox',  input_name:'channel', input_id: 'change-channel', input_style: 'height:30px;', input_tpl:'input_select_tpl'
        },
        {
            box_class: 'fitem ib w2',
            lable_class:'w60', lable_textbox: '渠道编号', 
            input_class:'easyui-combobox',  input_name:'channel_id', input_id: 'change-channel-id', input_style: 'height:30px;', input_tpl:'input_select_tpl'
        },
	],
	[
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'change-submit', btn_title:'提交', btn_type: 'button'
		},
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'change-cancel', btn_title:'取消', btn_type: 'button'
		},
	]
];

globalData['edit_box_config'] = [
	[
        {
        	box_class: 'fitem ib w3',
            lable_class:'w100', lable_textbox: '客户ID', 
            input_class:'easyui-textbox',  input_name:'fuserid', input_id: 'fuserid', input_style: 'height:30px;width:260px;', input_tpl:'input_text_tpl'
        },
        {
        	box_class: 'fitem ib w3',
            lable_class:'w100', lable_textbox: '渠道编号', 
            input_class:'easyui-textbox',  input_name:'channel', input_id: 'channel', input_style: 'height:30px;width:260px;', input_tpl:'input_text_tpl'
        },
        {
        	box_class: 'fitem ib w3',
            lable_class:'w100', lable_textbox: '姓名', 
            input_class:'easyui-textbox',  input_name:'name', input_id: 'name', input_style: 'height:30px;width:260px;', input_tpl:'input_text_tpl'
        },
        {
        	box_class: 'fitem ib w3',
            lable_class:'w100', lable_textbox: '身份证号', 
            input_class:'easyui-textbox',  input_name:'idnumber', input_id: 'idnumber', input_style: 'height:30px;width:260px;', input_tpl:'input_text_tpl'
        },
    ]
];
/*############################################################################*/
<<<<<<< .mine
//元素配置初始化
function load_sample_config() {
	if (undefined != globalData['sample_config']) {
		for (key in globalData['sample_config']) {
            //[key=>array]
			var value = globalData['sample_config'][key];
			var value_tpl = '';
			var value_option = [];
			switch(value['type']) {
				case '文本' : value_tpl = 'input_text_tpl';
                      break;
				case '判断' : 
					value_tpl = 'input_select_tpl'; 
					var tmp_ary = JSON.parse(value['data-options']); 
					for (var i = 0; i < tmp_ary.data.length ; i++) {
						value_option[value_option.length] = [tmp_ary.data[i]['text'], tmp_ary.data[i]['value']];
					}
				      break;
				case '图片' : value_tpl = 'input_file_tpl';
                       break;
				default : value_tpl = 'input_text_tpl';
                       break;
			}
			var tmp = [];
			tmp['box_class'] = 'fitem ib w2';
			tmp['lable_class'] = 'w100'; 
			tmp['lable_textbox'] = value['text'];
			tmp['input_class'] = value['class'];
			tmp['input_name'] = tmp['input_id'] = key;
			tmp['input_style'] = 'height:30px;';
			tmp['input_tpl'] = value_tpl;
			if (value_option) {
				tmp['input_options'] = value_option;
			}
			globalData['edit_box_config'][0][globalData['edit_box_config'][0].length] = tmp;
		}
	}
}
load_sample_config();

function load_edit_box_btn() {
	globalData['edit_box_config'][globalData['edit_box_config'].length] = [
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'edit-submit', btn_title:'提交', btn_type: 'button'
		},
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'edit-cancel', btn_title:'取消', btn_type: 'button'
		},
	];
}
load_edit_box_btn();
/*############################################################################*/
||||||| .r258
//元素配置初始化
function load_sample_config() {
	if (undefined != globalData['sample_config']) {
		for (key in globalData['sample_config']) {
			var value = globalData['sample_config'][key];
			var value_tpl = '';
			var value_option = [];
			switch(value['type']) {
				case '文本' : value_tpl = 'input_text_tpl';break;
				case '判断' : 
					value_tpl = 'input_select_tpl'; 
					var tmp_ary = JSON.parse(value['data-options']); 
					for (var i = 0; i < tmp_ary.data.length ; i++) {
						value_option[value_option.length] = [tmp_ary.data[i]['text'], tmp_ary.data[i]['value']];
					}
					break;
				case '图片' : value_tpl = 'input_file_tpl';break;
				default : value_tpl = 'input_text_tpl';break;
			}
			var tmp = [];
			tmp['box_class'] = 'fitem ib w2';
			tmp['lable_class'] = 'w100';
			tmp['lable_textbox'] = value['text'];
			tmp['input_class'] = value['class'];
			tmp['input_name'] = tmp['input_id'] = key;
			tmp['input_style'] = 'height:30px;';
			tmp['input_tpl'] = value_tpl;
			if (value_option) {
				tmp['input_options'] = value_option;
			}
			globalData['edit_box_config'][0][globalData['edit_box_config'][0].length] = tmp;
		}
	}
}
load_sample_config();

function load_edit_box_btn() {
	globalData['edit_box_config'][globalData['edit_box_config'].length] = [
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'edit-submit', btn_title:'提交', btn_type: 'button'
		},
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'edit-cancel', btn_title:'取消', btn_type: 'button'
		},
	];
}
load_edit_box_btn();
/*############################################################################*/
=======
>>>>>>> .r291
$(document).ready(function() { 
	//开户按钮点击
	$("#listselect").on('click', '.check', function(){
	    check();
	});

	//开户 提交点击
	$('#check-submit').on('click', function(){
	    user_check();
	});

	//开户 取消
	$('#check-cancel').on('click', function(){
		$('#check-box').dialog('close');
	});

	// //修改用户注册信息 高级 点击
	// $("#edit-box-content").on('click', '.senior', function(){
	//     change();
	// });

	//修改用户注册信息 提交点击
	$('#change-submit').on('click', function(){
	    user_change();
	});

	//修改用户注册信息  取消
	$('#change-cancel').on('click', function(){
		$('#change-box').dialog('close');
	});


	//关闭编辑框
	$('#edit-cancel').on('click', function(){
		$('#submit-dlg').dialog('close');
	});

	//
	$('#edit-sub').on('click', function(){
		submitEdit();
	});

    $('#sample_23').filebox({
        onChange: function(){
            console.log('sample_23 change');
            youtu_flag = 1;
        }
    });
    $('#sample_24').filebox({
        onChange: function(){
            console.log('sample_24 change');
            youtu_flag = 1;
        }
    });

    // $('#sample_10').datebox({
    //     disabled:true,
    // });
}); 

//打开 开户菜单
function check() {
    $('#check-box').dialog('open').dialog('setTitle', '开户');
}

//开户检查
function user_check() {
    var name = $('#check-name').val().trimSpace();
    var idnumber = $('#check-idnumber').val().trimSpace();
    var channel = $('#check-channel').val();
    var nameError = '';
    var idnumberError = '';
    var channelError = '';
    if (!/^[\u4E00-\u9FA5A-Za-z]+$/.test(name)) {
        nameError = "姓名不正确";
    }

    if (!/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(idnumber)) {
        idnumberError = "身份证号有误"; 
    }
    console.log(channel);
    if (null == channel || '' == channel || undefined == channel) {
        channelError = '渠道选择有误';
    }

    $('#check-idnumber').parent().children('.fred').text(idnumberError);
    $('#check-name').parent().children('.fred').text(nameError);
    $('#check-channel').parent().children('.fred').text(channelError);
    if(nameError || idnumberError || channelError) return;
    $.post(
        AJAXBASEURL + 'Qiye/check',
        {name:name, idnumber:idnumber},
        function (response) {
            if(response.responseCode==200){
                top.modalbox.alert('身份证号已存在，跳转到订单',function(){
                    window.location.href = PAGE_VAR.SITE_URL+'Order/query';
                });
                return ;
            }else if (response.responseCode == 201) {
                var flag = 0;
                top.modalbox.confirm('身份证号不存在，是否开户',function(){
                    //开户
                    $.post(
                        AJAXBASEURL + 'Qiye/createuser',
                        {name:name, idnumber:idnumber, channel:channel},
                        function (response) {
                            //接收 ID
                            var re_data = response;
                            if (false != re_data) {
                                console.log('成功创建用户: ' + re_data);
                                top.modalbox.alert('客户开户成功');
                                $('#check-box').dialog('close');
                                //模拟查询 
                                $('#find-idnumber').val(idnumber);
                                $('#query').click();
                            } else {
                                console.log('客户创建失败');
                                top.modalbox.alert('客户创建失败');
                            }
                        }
                    );
                });
                return ;
            }else if(response.responseCode==400){
                top.modalbox.alert(response.responseMsg);
                return ;
            }
        },'json'
    );
}



//修改用户注册信息
function user_change(){
	var name = $('#change-name').val().trimSpace();
    var idnumber = $('#change-idnumber').val().trimSpace();
    var channel = $('#change-channel').val();
    var id = $('#change-id').val();
    var nameError = '';
    var idnumberError = '';
    if (!/^[\u4E00-\u9FA5A-Za-z]+$/.test(name)) {
        nameError = "姓名不正确";
    }

    if (!/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(idnumber)) {
        idnumberError = "身份证号有误"; 
    }

    $('#change-idnumber').parent().children('.fred').text(idnumberError);
    $('#change-name').parent().children('.fred').text(nameError);
    console.log(1);
    if(nameError || idnumberError ) return;
    $.post(
        AJAXBASEURL + 'Qiye/check',
        {name:name, idnumber:idnumber, id:id},
        function (response) {
            if(response.responseCode == 201){
            	//执行更改
            	$.post(
            		AJAXBASEURL + 'Qiye/changereginfo',
            		{name:name, idnumber:idnumber, channel:channel, id:id},
            		function (response) {
            			response = JSON.parse(response);
            			top.modalbox.alert(response.responseMsg);
            			//关闭修改框 
            			//用idnumber 重新搜索用户
            			$('#change-box').dialog('close');
            			//模拟查询 
                        $('#find-idnumber').val(idnumber);
                        $('#query').click();
            			return ;
            		}
        		);
            }else {
                top.modalbox.confirm('身份证信息已注册',function(){});
                return ;
            }
        },'json'
    );
}

// //提交编辑内容/报审内容/审批内容
// function submitEdit() {
// 	$('#submitForm input[name="fuserid"]').textbox({
// 		disabled:false
// 	});

// 	//从按钮中获取请求地址
// 	var url = $('#edit-sub').attr('data-url');
//     $('#submitForm').form('submit', {
//         url: globalData['page_type'] + url,
//         onSubmit: function() {    
//             return true;
//         },
//         dataType: 'json',
//         success: function(result) {
//             var info = JSON.parse(result);
//             top.modalbox.alert(info.msg);
//             $('#submit-dlg').dialog('close');
//             //模拟查询 
//             $('#find-idnumber').val($('#submitForm input[name="idnumber"]').val());
//             $('#query').click();
//             return ;
//         }
//     });
// }


$(document).ready(function() {
    $("#check-channel").combobox({  
        onChange:function(){  
            //改变渠道编号
            //select 遍历校验
            var flag = 1;
            var select_data = $('#check-channel').combobox("getData");
            for (item in select_data) {
                if (select_data[item]['value'] == $('#check-channel').combobox("getValue")) {
                    $('#check-channel-id').combobox('setValue', $('#check-channel').combobox("getValue"));
                    flag = 1;
                    break;
                } else {
                    flag = 0;
                }
            }
            if (!flag) {
                $('#check-channel').combobox('setValue', '');
                $('#check-channel-id').combobox('setValue', '');
            }
        }
    }); 
    $("#check-channel-id").combobox({  
        onChange:function(){  
            //改变渠道名称
            // console.log($('#check-channel-id').combobox("getValue"));
            //select 遍历校验
            var flag = 1;
            var select_data = $('#check-channel-id').combobox("getData");
            for (item in select_data) {
                if (select_data[item]['value'] == $('#check-channel-id').combobox("getValue")) {
                    $('#check-channel').combobox('setValue', $('#check-channel-id').combobox("getValue"));
                    flag = 1;
                    break;
                } else {
                    flag = 0;
                }
            }
            if (!flag) {
                $('#check-channel-id').combobox('setValue', '');
                $('#check-channel').combobox('setValue', '');
            }
        }
    });


    $("#change-channel").combobox({  
        onChange:function(){  
            //改变渠道编号
            //select 遍历校验
            var flag = 1;
            var select_data = $('#change-channel').combobox("getData");
            for (item in select_data) {
                if (select_data[item]['value'] == $('#change-channel').combobox("getValue")) {
                    $('#change-channel-id').combobox('setValue', $('#change-channel').combobox("getValue"));
                    flag = 1;
                    break;
                } else {
                    flag = 0;
                }
            }
            if (!flag) {
                $('#change-channel').combobox('setValue', '');
                $('#change-channel-id').combobox('setValue', '');
            }
        }
    }); 
    $("#change-channel-id").combobox({  
        onChange:function(){  
            //改变渠道名称
            // console.log($('#change-channel-id').combobox("getValue"));
            //select 遍历校验
            var flag = 1;
            var select_data = $('#change-channel-id').combobox("getData");
            for (item in select_data) {
                if (select_data[item]['value'] == $('#change-channel-id').combobox("getValue")) {
                    $('#change-channel').combobox('setValue', $('#change-channel-id').combobox("getValue"));
                    flag = 1;
                    break;
                } else {
                    flag = 0;
                }
            }
            if (!flag) {
                $('#change-channel-id').combobox('setValue', '');
                $('#change-channel').combobox('setValue', '');
            }
        }
    });
    console.log('绑定事件');
});