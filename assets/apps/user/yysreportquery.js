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

globalData['page_type'] = 'yysrepost';

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
