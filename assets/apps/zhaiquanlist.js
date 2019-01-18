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

globalData['tpl'] = [];
function load_tpl() {
	tpl_array = globalData['tpl_array'];
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

function format_number(n){
    var b = parseInt(n).toString();
    var len = b.length;
    if (len <= 3) {return b;}
    var r = len%3;
    return r>0?b.slice(0,r)+","+b.slice(r,len).match(/\d{3}/g).join(","):b.slice(r,len).match(/\d{3}/g).join(",");
}
 


globalData['page_type'] = 'user';
globalData['input_style'] = 'width:260px';

globalData['listconfig'] = [
	[
        {field: 'id',title:'序号',width:'40px',align:'center',halign:'center',sortable:true},
        {field: 'fuserid',title:'客户编号',width:'100px',align:'center',halign:'center',sortable:true},
        {field: 'account_info',title:'账号信息',width:'100px',align:'center',halign:'center'},
        {field: 'name',title:'姓名',width:'100px',align:'center',halign:'center'},
        {field: 'idnumber',title:'身份证号',width:'200px',align:'center',halign:'center'},
        {field: 'area',title:'区县',width:'100px',align:'center',halign:'center'},
        {field: 'quanli_name',title:'权利人姓名',width:'200px',align:'center',halign:'center'},
        {field: 'address',title:'房屋坐落地址',width:'200px',align:'center',halign:'center'},
        {field: 'house_loan_amount',title:'房抵借款金额',width:'100px',align:'center',halign:'center',sortable:true,
            formatter:function (value,row) {
                var html = '';
                var house_loan_amount = row.house_loan_amount;
                if (house_loan_amount) {
                    html+= '<span style="display:inline-block;width:100%;text-align:right;">' + format_number(parseInt(house_loan_amount)) + '.00' + '</span>';
                }
                return html;
            }
        },
        {field: 'house_loan_term',title:'房地借款期限',width:'100px',align:'center',halign:'center',sortable:true,
            formatter:function (value,row) {
                var html = '';
                var house_loan_term = row.house_loan_term;
                if (house_loan_term) {
                    html+= house_loan_term + '个月';
                }
                return html;
            }
        },
        {field: 'house_loan_start',title:'抵押开始时间',width:'100px',align:'center',halign:'center',sortable:true},
        {field: 'house_loan_end',title:'抵押到期时间',width:'100px',align:'center',halign:'center',sortable:true},
        {field: 'house_loan_remaining_time',title:'抵押剩余天数',width:'100px',align:'center',halign:'center',sortable:true,
            formatter:function (value,row) {
                var html = '';
                var house_loan_remaining_time = row.house_loan_remaining_time;
                if (house_loan_remaining_time > 0) {
                    html+= Math.floor(house_loan_remaining_time/30)+'个月'+(house_loan_remaining_time%30)+'天';
                } else {
                    html+= Math.ceil(house_loan_remaining_time/30)+'个月'+(house_loan_remaining_time%30)+'天';
                }
                return html;
            }
        },
        {field: 'house_loan_quanli',title:'抵押权人',width:'160px',align:'center',halign:'center'},
        {field: 'terrace_loan_amount',title:'平台借款金额',width:'100px',align:'center',halign:'center',sortable:true,
            formatter:function (value,row) {
                var html = '';
                var terrace_loan_amount = row.terrace_loan_amount;
                if (terrace_loan_amount) {
                    html+= '<span style="display:inline-block;width:100%;text-align:right;">' + format_number(parseInt(terrace_loan_amount)) + '.00' + '</span>';
                }
                return html;
            }
        },
        {field: 'terrace_loan_term',title:'平台借款期限',width:'100px',align:'center',halign:'center',sortable:true,
            formatter:function (value,row) {
                var html = '';
                var terrace_loan_term = row.terrace_loan_term;
                if (terrace_loan_term) {
                    html+= terrace_loan_term + '个月';
                }
                return html;
            }
        },
        {field: 'terrace_loan_start',title:'平台借款开始时间',width:'100px',align:'center',halign:'center',sortable:true},
        {field: 'terrace_loan_end',title:'平台借款结束时间',width:'100px',align:'center',halign:'center',sortable:true},
        {field: 'terrace_loan_remaining_time',title:'平台借款到期天数',width:'100px',align:'center',halign:'center',sortable:true,
            formatter:function (value,row) {
                var html = '';
                var terrace_loan_remaining_time = row.terrace_loan_remaining_time;
                if (terrace_loan_remaining_time > 0) {
                    html+= Math.floor(terrace_loan_remaining_time/30)+'个月'+(terrace_loan_remaining_time%30)+'天';
                } else {
                    html+= Math.ceil(terrace_loan_remaining_time/30)+'个月'+(terrace_loan_remaining_time%30)+'天';
                }
                return html;
            }
        },
        {field: 'mispairing_time',title:'错配天数',width:'100px',align:'center',halign:'center',sortable:true,
            formatter:function (value,row) {
                var html = '';
                var house_loan_remaining_time = row.house_loan_remaining_time;
                var terrace_loan_remaining_time = row.terrace_loan_remaining_time;
                var diff_time = house_loan_remaining_time - terrace_loan_remaining_time;
                if (diff_time > 0) {
                    html+= Math.floor(diff_time/30)+'个月'+(diff_time%30)+'天';
                } else {
                    html+= Math.ceil(diff_time/30)+'个月'+(diff_time%30)+'天';
                }
                
                return html;
            }
        }
    ]
];



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