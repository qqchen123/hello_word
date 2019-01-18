<html>
<?php tpl("admin_applying") ?>
<body>
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/data-record-basic.css">
<script type="text/javascript" src="/assets/lib/js/nunjucks.js"></script>
<style>
    td {
        border-top: none !important;
        vertical-align: middle !important;
    }
    .pre-check {
        margin-left: 16px;
    }
    .sub-btn{
        text-align: right;
    }
    #fm {
        margin: 0;
        padding: 10px 30px;
    }
    .ftitle {
        font-size: 14px;
        font-weight: bold;
        padding: 5px 0;
        margin-bottom: 10px;
        border-bottom: 1px solid #ccc;
    }

    .radioformr {
        width: 5px;
    }

    .sub-btn {
        margin-top:15px;
    }

    #submitForm label{
        font-size: 12px;
        margin-top: 5px;
    }

    #getprice:hover{
        cursor: pointer;
    }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div id="listselect"></div>
    <div id="listbox"></div>
    <div id="reportbox">
        <div id="report-dlg" style="width:1200px;max-height: 700px;padding:10px;top: 10px;" class="easyui-dialog" closed="true" buttons="submit-dlg-buttons" data-options="modal:true">
            <div id="report-box-content"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
    var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
    var globalData = [];//用于装载全局js变量
    var phpData = [];//php返回的内容

    String.prototype.replaceAll = function(f,e){//吧f替换成e
        var reg = new RegExp(f,"g"); //创建正则RegExp对象   
        return this.replace(reg,e); 
    }

    globalData['listconfig'] = [
        [
            {field: 'contractId', title: '证书ID', width: 200, align:'center'},
            {field: 'type', title: '类型', width: 160, align:'center'},
            {field: 'contract', title: '合同名字', width: 160,  align:'center'},
            {field: 'fuserid', title: '用户编号', width: 200,  align:'center'},
            {field: 'ctime', title: '创建时间', width: 200,  align:'center'},
            {field: 'op', title: '操作', width: 160,  align:'center'},
        ]
    ];

    $("#listbox").prepend(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/datagrid_basic', 
            {datagrid_id: 'tt', target: 'bestsignlist'}
        )
    );

    $("#listselect").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/select_basic', 
            {
                selectConfig: [['用户编号', 'find-fuserid', 'find-fuserid']],
                canSelect: 1,
            }
        )
    );

    //显示按钮
    function showBtn() {
        var row = {};
        var html = '';
        $('.op').each(function(){
            var html_tmp = [];
            var src = $(this).attr('data-src');
            html = '<a data-src="'+ src +'" class="preview btn btn-primary btn-xs p310" href="javascript:void(0)">查看</a>';
            html += html_tmp.join('&nbsp;&nbsp');
            $(this).parent().append(html);
        });
    }

    $('#listbox').on('click', '.preview', function(){
        var src = $(this).attr('data-src');
        window.open("./getcontract?src="+src);
    });

    //点击查询
    $("#query").click(function(){
        var fuserid = $("#find-fuserid").val();
        if(fuserid == false){ fuserid = 'err';}
        $('#tt').datagrid('load', {fuserid:fuserid});
    }); 

</script>

<?= tpl('admin_foot') ?>
</body>
</html>