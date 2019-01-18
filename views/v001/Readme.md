#通过配置项生成页面  操作流程#
##前端流程##
	1.	引用页面头  	admin_applying

	2.	引用
		<link rel="stylesheet" href="/assets/lib/css/data-record-basic.css">
		<script type="text/javascript" src="/assets/lib/js/nunjucks.js"></script>

	3.	页面php文件中 配置参数
		<script type="text/javascript">
		    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
		    var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
		    var globalData = [];//用于装载全局js变量
		    var phpData = [];//php返回的内容
		    globalData['sample_config'] = <?= $sample_config?>;//后端php 给予的数据
		    
		    globalData['edit_user'] = '<?= $_SESSION['fms_username']?>';
		    var statusColor = JSON.parse('<?= $statusColor ?>');
		</script>

	4.	加载js文件
		//instquery  页面对应的js文件  配置文件   js文件需要提前写好 或者先创建文件之后写
		<script type="text/javascript" src="/assets/apps/user/instquery.js?s=<?= time()?>"></script>
		//公共js
		<script type="text/javascript" src="/assets/apps/user/query.js?s=<?= time()?>"></script>

	5.	加载模板  模板根据实际页面要求配置
		$("#editbox").append(
	        nunjucks.render(
	            AJAXBASEURL + tplPath + 'v001/edit_box_basic', 
	            {}
	        )
	    );
	    ...

	6.	编写页面   需要的js function

	7.	引用页面脚		admin_foot

##前端编辑结束##

##后端流程##
	1.	model 准备数据库操作方法

	2.	service 根据业务操作 调用/封装 model 方法

	3.	controller 写对应的控制器 配置文件

##后端编写结束##


#原理  机制
##页面的元素生成
###页面元素通过三部分生成：
	1.加载需要使用到的元素;
	2.通过参数(来自后端/前面js中配置的)生成页面布局和静态元素;
	3.$(document).ready()加载最后的动态数据

##方法介绍 
### load_tpl 模板加载
根据 globalData['tpl_array'](对象数组) 中配置的内容调用ajax获取模板,模板实际上就是字符串。
获取到的模板保存在变量 globalData['tpl'](对象数组) 中,供其它方法调用

### load_sample_config 元素配置初始化
处理后端返回的样本元素的配置信息,处理成统一的格式
[
	{
	    box_class: 'fitem ib ' + globalData['edit_box_element_conf'],//(元素父级的class)
	    lable_class:'w100',//(元素的lable的class)
	    lable_textbox: '姓名', //(元素的lable的内容)
	    input_class:'easyui-textbox',//(元素的class)
	    input_name:'name',//(元素的name属性的值) 
	    input_id: 'name', //(元素的id)
	    input_style: 'height:30px;',//(元素的行内样式,为强制改变元素样式提供位置)
	    input_tpl:'input_text_tpl'//(元素所要使用的模板 在globalData['tpl'](对象数组) 中的key值)
	},
	...
]

###load_edit_box_btn 加载 编辑框的按钮
定义编辑框的按钮
globalData['edit_box_config'][globalData['edit_box_config'].length] = [
	{
		btn_class: 'easyui-linkbutton w90', btn_id:'edit-submit-as', btn_title:'提交', btn_type: 'button'
	},
    {
        btn_class: 'easyui-linkbutton w90 dn', btn_id:'edit-submit', btn_title:'真提交', btn_type: 'button'
    },
	{
		btn_class: 'easyui-linkbutton w90', btn_id:'edit-cancel', btn_title:'取消', btn_type: 'button'
	},
];

###submitCheck 提交时候的确认框
编辑框按钮中的真假提交就是在这里使用

###submitEdit 提交编辑内容/报审内容/审批内容 动作
实际执行提交的位置,以及提交完成之后的动作

###$(document).ready(function() {}) 元素加载完之后的绑定动作


##配置数组介绍
###globalData(数组) 整个页面全部文件所使用到的全局变量  在页面php文件中需要先 var 创建

####globalData['tpl'] 元素模板

####globalData['tpl_array'] 元素模板名

####globalData['sample_config'] 后端返回的元素页面配置信息

####globalData['edit_box_element_conf'] 编辑框中元素的样式追加 
例如：globalData['edit_box_element_conf'] = 'w2' 一样显示2个元素
	globalData['edit_box_element_conf'] = 'w3' 一样显示3个元素

####globalData['web_config'] 页面元素布局配置
#####globalData['web_config']['element'] 页面元素顺序
[
	{
		samples: [112,32,32,35,120], //(元素ID,顺序在这里决定)
		relation: [{sample:33, target:'depository'}],//(元素与某一个class 绑定关系) 
		class: 'basic'//(这一批元素 都需要加载的class名 用于标识)
	}
]

#####globalData['web_config']['title'] 页面元素所属类的 标题

#####globalData['web_config']['nav'] 页面元素所属类对应的 导航信息
[
	{
		'title':'基础信息', //(元素导航名称)
		'relation-class': 'basic', //(该导航与某一个class 绑定)
		'id': 'nav-basic'//(导航ID)
	},
]

####globalData['edit_box_title'] 弹窗的标题

####globalData['edit_box_btn'] 编辑框提交按钮的名称

####globalData['page_type'] 页面业务类型  (这个变量很重要  长度不能超过10个字符串  大部分url请求都要用到)


##元素对照枚举
backbutton.php 			返回主页
basicbutton.php 		(基础信息 账号信息 核心信息 猫池信息)
dateinput.php 			时间框
excetiveselect.php 		特殊的下拉框 （ge：是否存管）
fileinput.php 			文件框 （buttonText属性填写 ‘请选择’）
lable.php 				字段名
textinput.php 			文本框
ordinaryselect.php 		普通的下拉框 （ge：运营商）
panel.php 				信息的布局（eg：基础信息的整个div框）
phonebutton.php 		手机卡管理
submitbutton.php 		提交框
change_info_form.php	用户注册信息修改弹框
datagrid_basic.php		列表
edit_box_basic.php 		编辑框/报审框/审核框
simple_form.php 		简洁表单
textareabasic.php 		多行文本输入框