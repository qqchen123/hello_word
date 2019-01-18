<?php tpl("admin_applying") ?>
<body>
<link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="/assets/lib/css/data-record-basic.css">
<style type="text/css">
    .textbox-text {
        width: 260px !important; 
    }
</style>
<div class="easyui-layout" data-options="fit:true,border:false">
    <div id="listselect"></div>
    <div id="viewbox">
        <div id="dd" class="easyui-dialog" title="失信 被执行 判决文书 查询" style="width:1600px;height:700px;top: 10px;"data-options="iconCls:'icon-info',modal:true,closed:true">
        </div>
    </div>
</div><!-- /.page-content -->
<script>
    var youtu_flag = 1;
    var submit_flag = 1;
    var env = '<?= $env ?>';
    var statusColor = JSON.parse('<?= $statusColor ?>');
    <?= $jscontroller?>
    var role_arrry = [];
    var edit_able = ['fuserid', 'idnumber', 'name', 'channel'];
    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
    var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
    var globalData = [];//用于装载全局js变量
    var phpData = [];//php返回的内容
    globalData['tmp'] = [];

</script>
<script type="text/javascript" src="/assets/apps/user/yysreportquery.js?s=<?= time()?>"></script>
<script type="text/javascript" src="/assets/apps/user/query.js?s=<?= time()?>"></script>
<script type="text/javascript">
    //numjucks 加载页面
    $("#listselect").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/select_basic', 
            {
                selectConfig: [
                    ['关键字', 'keywords', 'find-keywords'],
                    ['身份证(可选)', 'idnumber', 'find-idnumber'],
                    ['类型', 'requestType', 'find-requestType', [['executor', '被执行人查询'], ['dishonest','失信查询'], ['judge','判决书查询']]],
                    ['页码', 'page', 'find-page']
                ],
                canSelect: <?= checkRolePower('Qiye', 'creaditslist') ?>,
            }
        )
    );
    
</script>

<script type="text/javascript">
//#################################################
    var dishonest_map = {'dishonestId':'记录编号', 'dishonestName':'失信人/机构名称', 'caseCode':'裁决书编号', 'cardNum':'机构编号/证件编号', 'businessEntity':'法人', 'courtName':'法庭名称', 'areaName':'裁决发生地区', 'gistId':'未履行裁决文档', 'gistUnit':'未履行裁决发出单位', 'legalDuty':'未履行职责', 'regDate':'日期', 'performance':'状态', 'disruptTypeName':'失信类型', 'publishDate':'公示时间'};

    var executor_map = {'executorId':'记录ID', 'name':'机构/人', 'caseCode':'裁决书编号', 'courtName':'法庭名称', 'execMoney':'执行金额', 'caseState':'执行状态', 'cardNum':'身份证号', 'companyCode':'机构编号', 'caseDate':'裁定时间'};

    var judge_map = {'judgeId':'判决书ID', 'companyCodePlaintiff':'原告机构编号', 'companyCodeDefendant':'被告机构编号', 'title':'裁定书名称', 'ch':'法院名称', 'num':'裁定书编号', 'content':'裁定书内容', 'date':'裁定书时间'};

    //点击查询
    $("#query").click(function(){
        var keywords = $("#find-keywords").val();
        var idnumber = $("#find-idnumber").val();
        var requestType = $("#find-requestType").val();
        var page = $("#find-page").val();
        if(keywords == false){ keywords = '';}
        if(idnumber == false){ idnumber = '';}
        if(page == false){ page = 1;}
        $.ajax({ 
            type : "post", 
            url : AJAXBASEURL + 'Qiye/creaditslist', 
            data : {keywords: keywords, idnumber:idnumber, requestType:requestType, page:page},
            success : function(res){ 
                // console.log(JSON.parse(res));
                $("#dd").html('');
                res = JSON.parse(res);
                if (0 == res.code) {
                    console.log(res.data);
                    requestType = res.data.type;
                    var str = '';
                    if (res.data.list) {
                        if (res.data.list.length > 0) {
                            for (item in res.data.list) {
                                str += '<div style="border:1px #ccc solid;margin-bottom:15px;">';
                                for (value in res.data.list[item]) {
                                    if ('dishonest' == requestType) {
                                        str += '<span style="width: 32%;border:1px solid #ccc;display: inline-block;padding: 2px;margin-bottom: 3px;">' + dishonest_map[value] + ':' + res.data.list[item][value] + '</span>';
                                    } else if ('executor' == requestType) {
                                        str += '<span style="border:1px #CCC solid;display:inline-block;padding:5px;">' + executor_map[value] + ':' + res.data.list[item][value] + '</span>';
                                    } else if ('judge' == requestType) {
                                        str += '<span style="border:1px #CCC solid;display:inline-block;padding:5px;">' + judge_map[value] + ':' + res.data.list[item][value] + '</span>';
                                    } else {
                                        str += '<span style="border:1px #CCC solid;display:inline-block;padding:5px;">' + value + ':' + res.data.list[item][value] + '</span>';
                                    }
                                }
                                str += '</div>';
                            }
                        }
                    }
                    $("#dd").prepend(
                        '<span>总数:' + res.data.total + '</span><div>详情:<div>' + str + '</div></div>'
                    );
                } else {
                    $("#dd").prepend(
                        res.msg
                    );
                }
                $('#dd').dialog('open');
            } 
        });
    });

</script>

<?= tpl('admin_foot') ?>
</body>
</html>