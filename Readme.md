#fms 后台系统

##目录结构
	assets 	-- js、css
	fms    	-- application
	| assets 
	| config --config配置文件
        |crontab 
            crontab.txt --crontab配置文件
        dbmap --部分样本的映射配置 便于代码中调用
        mongodb --mongodb配置
        moxie --魔蝎配置
        sysconfig --日志环境配置  优图环境配置
        ...
	| controllers --控制器
        |api --api接口
            Yxdata --银信数据接受接口，接口唐工爬虫推送的数据
        |client --客户端
            ClientPreOrder --报单H5接口
        |wx --微信端
        |reptiles --爬虫脚本
            Yx --银信客户账户爬虫脚本
            Yxz --银信总账户爬虫脚本
        CallBack --第三方回调入口
        Qiye --客户相关页面 包含基础信息、手机卡、银行卡、征信报告、客户前端接口等
        Sms --验证码短信接口
        Mx --魔蝎H5认证页面
        ...
	| core --可继承公共类 controller model service
	| helpers --公共函数
	| hooks --
	| models --model
		| channel --渠道model
		| PHPExcel --上传excel文件 model
		| pool --证据池公共方法 非证据池表操作
		| public --公共model 包含阿里云接口调用日志model
		| user --用户相关model
        | yx --银信相关model
		...
	| service -- service
		| public --公共类服务包含阿里云接口调用、文件上传、内容编码、html代码生成
            Aliyun_service --阿里云接口
            Array_service --数组处理
            Bank_service --客户银行卡报告落地
            Config_service --配置
            CreditCard_service --客户信用卡报告落地
            Encryption_service --加密
            Executor_service --失信人查询(开发中)
            File_service --文件上传
            Front_service --前端辅助
            Gjj_service --公积金报告落地
            Html_service --后台页面辅助
            Idnumber_service --身份证分析
            Jd_service --京东报告落地
            Moxie_service --魔蝎接口
            Sms_service --短信接口(阿里)
            Taobao_service --淘宝报告落地
            Youtu_service --优图服务
            Yys_service --运营商报告获取
            ...
        |bestsign --上上签
        |bs --上上签
        |business --业务类服务 
            RecordBasicData_service --客户基础信息录入
            Rule_service --业务规则
            ...
		| user --用户相关service
	| upload --文件上传目录
	shared 	-- 后台框架 配置信息 和 老版helper libraries models
	system3 -- 框架源码
	views	-- 页面html

##h5结构 框架 vue3
    vue-fms
        |dist --编译好的文件 发布代码直接全量更新该文件夹
        |node_modules --项目依赖的包 由npm install 直接生成  不需要维护
        |public 
        |src --源码
        .eslintrc.js --书写规范相关配置
        .postcssrc.js
        babel.config
        package-lock.json --由npm install 直接生成 不需要维护
        package.json --项目依赖包配置文件 决定需要加载的依赖
        README.md --项目相关运行命令
        vue.config.js --配置文件
        ...

##碎片化html架构
	由js nunjucks 插件
	=======================================================================
	使用顺序
	一、html主体页面部分

	1.引用 js/lib/nunjucks.js

	2.引用 atom/globalData.js

	3.引用 (对应页面的js、此js多为配置文件).js

	5.html主体页面 <script></script>内添加以下内容：
	var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index/';//网站地址
	var tplPath = 'PublicMethod/getTemplate/';//调用的模板控制器
	var globalData = [];//用于装载全局js变量
	var phpData = <?=$data?>;//php返回的内容
	其他供模板调用的变量和function...
	-------------------------------------------------进行渲染的模板

	二、编写对应的js配置文件
	1.Demo文件 js/atom/demoData.js  -------适用于查询列表页  交易查询 用户查询等
	//文件说明
	!$(function () {
		---------------------------------replaceAll 绑定 后续内容需要使用
	    | String.prototype.replaceAll = function(f,e){//吧f替换成e
	    |     var reg = new RegExp(f,"g"); //创建正则RegExp对象   
	    |     return this.replace(reg,e); 
	    | }
	    ---------------------------------------------------------------

    ---------------------------------Ajax 获取select配置数据
    | fucntion ajaxFunctionName()
    | {
    |     $.ajax({
    |         ...
    |         //把结果赋值到globalData[(下标自定)]中 自定下标禁用以下: const selectData listData
    |     });
    | }
    | ajaxFunctionName();
    --------------------------------------------------------------

    //变量配置赋值
    ----------------------------------
    | globalData['const']['pageName'] = 'temple-mng';
    | globalData['const']['queryPath'] = '/api/temple/list';
    --------------------------------------------------------------

    ----------------------------------配置页面中会用到的时间
    | //获取当天时间
    | var today = new Date();
    | var oneDayTime = globalData['const']['oneDayTime'];
    | var todayString = today.toLocaleDateString();
    | todayString = todayString.replaceAll("/", "-");
	| 
    | //获取系统前一周的时间  7天
    | var oneWeekDateString = new Date(today-7*oneDayTime).toLocaleDateString();
    | oneWeekDateString = oneWeekDateString.replaceAll("/", "-");
	| 
    | //获取系统前30天的时间  30天
    | var oneMonthDateString = new Date(today-30*oneDayTime).toLocaleDateString();
    | oneMonthDateString = oneMonthDateString.replaceAll("/", "-");
	| 
    | //获取系统前90天的时间  3*30天
    | var threeMonthDateString = new Date(today-3*30*oneDayTime).toLocaleDateString();
    | threeMonthDateString = threeMonthDateString.replaceAll("/", "-");
    --------------------------------------------------------------------------------------------

    //选择器 配置

    ----------------------------------选择型input搜索框 select&input 组合的搜索器
    | //配置输入框的查询类型
    | //{name:name,options:[[name, value]...]}
    | globalData['selectData']['selectInputCondition'] = {
    |     'name': 'keyWordsType',
    |     'options': [
    |         ['value', 'name'],
    |         ['value', 'name'],
    |         ['value', 'name']
    |     ],
    | };
    |
    | //配置内容输入框 内容 
    | //{'name':input框name属性, 'placeholder':input框placeholder属性}
    | globalData['selectData']['selectInput'] = {'name':'keyWords','placeholder':''};
    ----------------------------------------------------------------------------------

    -----------------------------------时间选择器
    | //配置时间选择器(双时间) 
    | //{
    | // title:时间选择器头修饰字符, 
    | // midStyle:中间修饰字符, 
    | // startTime:startTime, 
    | // endTime:endTime, 
    | // expand:[[name, startTime, endTime]...]拓展内容 该处为 快速选择时间时间段内容
    | // }
    | globalData['selectData']['selectTimer'] = {
    |     'title': '创建时间',
    |     'midStyle': '至',
    |     'startTime': threeMonthDateString,
    |     'endTime': todayString,
    |     'expand': [
    |         ['近7天', oneWeekDateString, todayString],
    |         ['近30天', oneMonthDateString, todayString],
    |     ],
    | };
    -----------------------------------------------------------------------

    -------------------------------select选择器 所有的独立的select选择器配置都在这里配
    | //配置其他条件选择器
    | //{name:title名, options:选项}...
    | globalData['selectData']['selectCondition'] = [
    |     {
    |         'title':'选择器标题',
    |         'name':'name',
    |         'options': optionmap,//可以用全局变量或者局部变量 optionmap 这里为全局变量
    |     },
    |     {
    |         'title':'选择器标题',
    |         'name':'name',
    |         'options': optionmap,//可以用全局变量或者局部变量 optionmap 这里为全局变量
    |     }
    | ];
    ---------------------------------------------------------------------

    ---------------------------------查询按钮
    | //查询按钮
    | //{basic:原始的按钮名, expand: 拓展的按钮配置[[name, 对应执行的function]...}
    | globalData['selectData']['selectBtn'] = {
    |     'basic': '查询',
    |     'expand': [
    |         ['清空查询条件','clearQueryCondition'],
    |     ],
    | };
    ------------------------------------------------------------------------

    -------------------------------------表单的input type="hidden" 配置
    | //表单隐藏内容
    | //[name, value, elementId]
    | globalData['selectData']['hiddenBox'] = [
    |     ['input1Name', 'input1Value', 'input1Id'],
    |     ['input2Name', 'input2Value'],
    |     ['input3Name', 'input3Value', 'input3Id'],
    | ];
    -----------------------------------------------------------------------------


    //列表部分
    -----------------------------------------列表配置 
    ----title:列表的表头配置  vlaue:列表的内容配置
    ----sort：是否可排序 sort=1时 改列拥有排序功能 不带次参数默认不能排序
    ----class: title中的class是表头的class value中的class是内容的class
    ----title中的顺序与value中的相对应value中读取的field在title里的field配置
    ----evalString：eval()可执行的语句，能够将具体的数值传入到这个eval()可执行的语句中进行转移或者二次处理，再显示到页面
    | //列表配置{
    | //          'title':[{name:name,field:field,class:class,(sort:1)}...]  列表title 配置, 
    | //          'value':[{evalString:evalString,class:class}...]   列表 内容配置,对应字段的值以变量value的形式传入evalString中执行,没有evalString则直接打印
    | //      }
    | globalData['listData']['data'] = {
    |     'title': [
    |         {name: '列表名称', field: '读入的字段名称', class: '该元素样式', sort: 1}, 
    |         {name: '列表名称', field: '读入的字段名称', class: '该元素样式', sort: 1}, 
    |         {name: '列表名称', field: '读入的字段名称', class: '该元素样式'}, 
    |         {name: '列表名称', field: '读入的字段名称',class: '该元素样式'}, 
    |         {name: '列表名称', field: '读入的字段名称', class: '该元素样式', sort: 1}, 
    |         {name: '列表名称', field: '读入的字段名称', class: '该元素样式'}, 
    |         {name: '列表名称', field: '读入的字段名称', class: '该元素样式'}, 
    |         {name: '列表名称', field: '读入的字段名称', class: '该元素样式'}, 
    |         {name: '列表名称', field: '读入的字段名称'}, 
    |     ],
    |     'value': [
    |         {class: '读入的字段名称'}, 
    |         {class: '读入的字段名称'}, 
    |         {class: '读入的字段名称'}, 
    |         {class: '读入的字段名称'}, 
    |         {evalString: 'value.substring(0,10)', class: 'etime'}, 
    |         {evalString: '\'<img src="../img/icon/\'+ func(value, 2) +\'">\'+ func(value, 0) ', class: 'verify_status'}, 
    |         {evalString: '\'<img src="../img/icon/\'+ func(value, 2) +\'">\'+ func(value, 0) ', class: 'status'}, 
    |         {class: 'remark'}, 
    |         {evalString: '\'<a href="./edit?id=\'+ value +\'" >资料维护</a>\'', class: ''}, 
    |     ],
    | };
    -----------------------------------------------------------------------------------------
    
    --------------------------------------页面需要的其他js文件在此处加载 ，目的是先让配置内容加载之后再调用其他js
    | //最后加载
    | $(document).ready(function(){ 
    |     $.getScript("../js/lib/datetimepicker.min.js");  //加载js文件
    |     $('.J-click-to-sort').filter(':first').click();
    | });
    -----------------------------------------------------------------
})

2.listQueryController、selectController 列表组件和查询组件的启动顺序 
相应页面的 js 通过 numnjucks.js 引用 listQueryBasicBox.html 和 selectBasicBox.html 并传递参数，具体元素的生成在以上两个BasicBox中实现。