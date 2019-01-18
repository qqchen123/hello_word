
/*############################################################################*/
globalData['tpl'] = [];
function load_tpl() {
	for (item in globalData['tpl_array']) {
		$.ajax({ 
	        type : "get", 
	        url : AJAXBASEURL + tplPath + 'v001/' + globalData['tpl_array'][item], 
	        async : false, 
	        success : function(data){ 
	            globalData['tpl'][item] = data;
	        } 
	    });
	}
}
load_tpl();

//元素配置初始化
function load_sample_config() {
    var tmp_array = [];
	if (undefined != globalData['sample_config']) {
		for (key in globalData['sample_config']) {
			var value = globalData['sample_config'][key];
			var value_tpl = '';
			var value_option = [];
			switch(value['type']) {
				case '文本' : 
                    if ('easyui-combobox' == value['class']) {
                        value_tpl = 'input_select_tpl'; 
                        value_option = [];
                        var tmp_ary = JSON.parse(value['data-options']); 
                        for (var i = 0; i < tmp_ary.data.length ; i++) {
                            value_option[value_option.length] = [tmp_ary.data[i]['text'], tmp_ary.data[i]['value']];
                        }
                    } else {
                        value_tpl = 'input_text_tpl';break;
                    }
				case '判断' : 
					value_tpl = 'input_select_tpl'; 
					var tmp_ary = JSON.parse(value['data-options']); 
                    value_option = [];
					for (var i = 0; i < tmp_ary.data.length ; i++) {
						value_option[value_option.length] = [tmp_ary.data[i]['text'], tmp_ary.data[i]['value']];
					}
					break;
				case '图片' : value_tpl = 'input_file_tpl';break;
				default : value_tpl = 'input_text_tpl';break;
			}
			var tmp = [];
			tmp['box_class'] = 'fitem ib ' + globalData['edit_box_element_conf'];
			tmp['lable_class'] = 'w100';
			tmp['lable_textbox'] = value['text'];
			tmp['input_class'] = value['class'];
			tmp['input_name'] = tmp['input_id'] = key;
			tmp['input_style'] = 'height:30px;' + globalData['input_style'];
			tmp['input_tpl'] = value_tpl;
			if (value_option) {
				tmp['input_options'] = value_option;
			}
            tmp_array[tmp_array.length] = tmp;
		}
	}
    if ((undefined != globalData['web_config']) && globalData['web_config']['element']) {
        for (var i = 0; i < globalData['web_config']['element'].length; i++) {
            if (undefined == globalData['edit_box_config'][i]) {
                globalData['edit_box_config'][i] = [];
            }
            var samples = globalData['web_config']['element'][i]['samples'];
            var relation = globalData['web_config']['element'][i]['relation'];
            for (var j = 0; j < samples.length; j++) {
                if ('object' == typeof(samples[j])) {
                    for (var w = 0; w < samples[j].length; w++) {
                        for (var z = 0; z < tmp_array.length; z++) {
                            if ('sample_'+samples[j][w] == tmp_array[z]['input_id']) {
                                if (undefined != relation) {
                                    for (var y = 0; y < relation.length; y++) {
                                        if (samples[j][w] == relation[y]['sample']) {
                                            tmp_array[z]['action'] = relation[y]['target'];
                                        }
                                    }
                                }
                                if (undefined != globalData['web_config']['element'][i]['class']) {
                                    tmp_array[z]['box_class'] += ' ' + globalData['web_config']['element'][i]['class'];
                                }
                                if (0 == w || (w+1) == samples[j].length) {
                                    tmp_array[z]['border_class'] = '1';
                                } else {
                                    tmp_array[z]['border_class'] = '2';
                                }
                                globalData['edit_box_config'][i][globalData['edit_box_config'][i].length] = tmp_array[z];
                            }
                        }
                    }
                } else {
                    for (var z = 0; z < tmp_array.length; z++) {
                        if ('sample_'+samples[j] == tmp_array[z]['input_id']) {
                            if (undefined != relation) {
                                for (var y = 0; y < relation.length; y++) {
                                    if (samples[j] == relation[y]['sample']) {
                                        tmp_array[z]['action'] = relation[y]['target'];
                                        break;
                                    }
                                }
                            }
                            if (undefined != globalData['web_config']['element'][i]['class']) {
                                tmp_array[z]['box_class'] += ' ' + globalData['web_config']['element'][i]['class'];
                            }
                            globalData['edit_box_config'][i][globalData['edit_box_config'][i].length] = tmp_array[z];
                        }
                    }
                }
            }
        }
    }
}
load_sample_config();

//加载 编辑框的按钮
function load_edit_box_btn() {
	globalData['edit_box_config'][globalData['edit_box_config'].length] = [
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'edit-sub-as', btn_title:'提交', btn_type: 'button'
		},
        {
            btn_class: 'easyui-linkbutton w90 dn', btn_id:'edit-sub', btn_title:'真提交', btn_type: 'button'
        },
		{
			btn_class: 'easyui-linkbutton w90', btn_id:'edit-cancel', btn_title:'取消', btn_type: 'button'
		},
	];
}
load_edit_box_btn();

/*############################################################################*/
//元素加载完之后  绑定事件
$(document).ready(function() { 
	//关闭编辑框
	$('#edit-cancel').on('click', function(){
		$('#submit-dlg').dialog('close');
	});

	//
	$('#edit-sub').on('click', function(){
		submitEdit();
	});
    $('#edit-sub-as').on('click', function(){
        submitCheck();
    });

    $('#editbox').remove('dn');

    $('#likeBtn').click();

    $('#edit-box-content').children().each(function(){
    	$(this).children('.fitem').each(function(){
    		$(this).on('click', function(){
    			//点亮导航
    			for (var i = 0; i < globalData['web_config']['nav'].length; i++) {
    			 	if (-1 != $(this).attr('class').indexOf(globalData['web_config']['nav'][i]['relation-class'])) {
    			 		$('.span-nav').removeClass('color-c');
    			 		$('.span-nav').removeClass('color-b');
    			 		$('.span-nav').addClass('color-c');
    			 		$('#'+globalData['web_config']['nav'][i]['id']).removeClass('color-c');
    			 		$('#'+globalData['web_config']['nav'][i]['id']).addClass('color-b');
    			 	}
    			}
    		});
    	});
    });

    //绑定联动动作
    $('.easyui-combobox').each(function() {
        if (undefined != $(this).attr('data-action') && '' != $(this).attr('data-action')) {
            var id = $(this).attr('id');
            var target = $(this).attr('data-action');
            $('#' + id).combobox({
                onChange: function (newValue, oldValue) {
                    console.log(newValue);
                    if (1 != newValue && 0 != newValue) {
                        $(this).combobox('select', 0);
                    }
                    showExpend(target, newValue);
                }
            });
        }
    });
}); 

function showExpend(target, newValue) {
    if (1 == newValue) {
        $('.' + target).removeClass('dnlogic');
    } else {
        console.log('当前状态 : ' + newValue);
        console.log('dn target: ' + target);
        $('.' + target).removeClass('dnlogic');
        $('.' + target).addClass('dnlogic');
    }
}

//提交确认框
function submitCheck() {
    var msg = $('#edit-sub').attr('data-msg');
    if (msg) {
        $.messager.confirm('','是否' + msg, function(r){
            if (r) {
                $('#edit-sub').click();
            }
        });
    } else {
        $('#edit-sub').click();
    }
}

//提交编辑内容/报审内容/审批内容
function submitEdit() {
	$('#submitForm input[name="fuserid"]').textbox({
		disabled:false
	});

	//从按钮中获取请求地址
	var url = $('#edit-sub').attr('data-url');

    $('#submitForm').form('submit', {
        url:  url,
        onSubmit: function() { 
            return true;
        },
        dataType: 'json',
        onSubmit: function() {
            if (0 == submit_flag) {
                return false;
            }
            submit_flag = 0;
            console.log(url);
            console.log(youtu_flag);
            //检查图片
            var front_src = $('#sample_23').parent().children('.img-btn').attr('src');
            var back_src = $('#sample_24').parent().children('.img-btn').attr('src');
            if (front_src && back_src) {
                youtu_flag = 1;
            }
            console.log('editdo/user' == url);
            if ('editdo/user' == url && youtu_flag) {
                youtu_flag = 1;
                //开启遮挡
                $('#youtu').removeClass('dn');
                $('#youtu').dialog({
                    title: '提交中',
                    width: 200,
                    height:150,
                    resizable:true,
                    closed: false,
                    cache: false,
                    modal: true
                });
            }
            return true;
        },
        success: function(result) {
            var info = JSON.parse(result);
            
            if (0 == info.code) {
                submit_flag = 1;
                var data_empty = 0;
                if (('string' == typeof(info.data)) && (info.data.length > 0)) {
                    data_empty = 1;
                }
                if (data_empty && youtu_flag) {
                    $('#youtu').addClass('dn');
                    $('#youtu').dialog({
                        closed: true,
                    });
                    $('#submit-dlg').form('clear');
                    row = JSON.parse(info.data);
                    eval('dataDeal(row, "")');
                    youtu_flag = 0;
                } else {
                    top.modalbox.alert(info.msg);
                    $('#youtu').addClass('dn');
                    $('#youtu').dialog({
                        closed: true,
                    });
                    $('#submit-dlg').dialog('close');
                    //模拟查询 
                    $('#find-idnumber').val($('#submitForm input[name="idnumber"]').val());
                    $('#likeBtn').click();
                    $('#query').click();
                }
            } else {
                top.modalbox.alert(info.msg);
                $('#youtu').addClass('dn');
                $('#youtu').dialog({
                    closed: true,
                });
                submit_flag = 1;
            }
            return ;
        }
    });
}