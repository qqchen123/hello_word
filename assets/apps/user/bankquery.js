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

globalData['edit_box_title'] = '编辑银行卡信息';
globalData['edit_box_btn'] = '提交';
globalData['page_type'] = 'bank';

//layout conf    w2  w3
globalData['edit_box_element_conf'] = 'w4';

globalData['listconfig'] = [
	[
        {field: 'fuserid', title: '客户编号', width: 100, align:'center'},
        {field: 'channel', title: '渠道编号', width: 100, align:'center'},
        {field: 'idnumber', title: '身份证号', width: 160,  align:'center'},
        {field: 'name', title: '姓名', width: 100,  align:'center'},
        {field: 'ctime', title: '开户时间', width: 160,  align:'center'},
        {field: 'op', title: '操作', width: 400,  align:'center'},
        {field: 'total', title: '状态', width: 100,  align:'center'}
    ]
];
globalData['web_config'] = {
        'element' : [
            {samples: [34,35], relation: [{sample:34, target:'depository'}], class: 'basic'},
            {samples: [37,38,40,39,41,43,42,44,45,46,47,48,49,50,51,52,53,54], class: 'dn info'},
            {samples: [57,58,59,60,61,62,63,64,66,67,68,69,71,72,73,74,75,76,77], class: 'depository dn core'},
            {samples: [[80,81,82,83],[85,86,87,88],[90,91,92,93]], class: 'depository dn secret'}
        ],
        'title': ['基础信息', '账户信息', '核心信息', '机密信息'],
        'nav' : [
            {'title':'基础信息', 'relation-class': 'basic', 'id': 'nav-basic'},
            {'title':'账户信息', 'relation-class': 'info', 'id': 'nav-info'},
            {'title':'核心信息', 'relation-class': 'core', 'id': 'nav-core'},
            {'title':'机密信息', 'relation-class': 'secret', 'id': 'nav-secret'},
        ]
    };
    
globalData['edit_box_config'] = [
	[
        {
            box_class: 'fitem ib ' + globalData['edit_box_element_conf'],
            lable_class:'w100', lable_textbox: '姓名', 
            input_class:'easyui-textbox',  input_name:'name', input_id: 'name', input_style: 'height:30px;', input_tpl:'input_text_tpl'
        },
        {
        	box_class: 'fitem ib ' + globalData['edit_box_element_conf'],
            lable_class:'w100', lable_textbox: '渠道编号', 
            input_class:'easyui-textbox',  input_name:'channel', input_id: 'channel', input_style: 'height:30px;', input_tpl:'input_text_tpl'
        },
        {
            box_class: 'fitem ib ' + globalData['edit_box_element_conf'],
            lable_class:'w100', lable_textbox: '客户ID', 
            input_class:'easyui-textbox',  input_name:'fuserid', input_id: 'fuserid', input_style: 'height:30px;', input_tpl:'input_text_tpl'
        },
        {
        	box_class: 'fitem ib ' + globalData['edit_box_element_conf'],
            lable_class:'w100', lable_textbox: '身份证号', 
            input_class:'easyui-textbox',  input_name:'idnumber', input_id: 'idnumber', input_style: 'height:30px;', input_tpl:'input_text_tpl'
        },
    ]
];
  