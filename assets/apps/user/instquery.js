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

globalData['edit_box_title'] = '机构开户申请表';
globalData['edit_box_btn'] = '提交';
globalData['page_type'] = 'inst';

//layout conf    w2  w3
globalData['edit_box_element_conf'] = 'w3';

globalData['listconfig'] = [
	[
        {field: 'fuserid', title: '客户编号', width: 100, align:'center'},
        {field: 'channel', title: '渠道编号', width: 100, align:'center'},
        {field: 'idnumber', title: '身份证号', width: 160,  align:'center'},
        {field: 'name', title: '姓名', width: 100,  align:'center'},
        {field: 'op', title: '操作', width: 400,  align:'center'},
        {field: 'total', title: '状态', width: 100,  align:'center'}
    ]
];
globalData['web_config'] = {
        'element' : [
            {samples: [], class: 'basic'},
            {samples: [156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173], class: 'depository instbank'},
            {samples: [175,176,177,178,179,180,181,182,183], class: 'instbankb'}
           
        ],
        'title': ['基础信息','A机构系统开户', 'B机构系统开户'],
        'nav' : [
            {'title':'基础信息', 'relation-class': 'basic', 'id': 'nav-basic'},
            {'title':'A机构系统开户', 'relation-class': 'instbank', 'id': 'nav-instbank'},
            {'title':'B机构系统开户', 'relation-class': 'instbankb', 'id': 'nav-instbankb'}   
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
  