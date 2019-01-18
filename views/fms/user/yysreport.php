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
        <div id="dd" class="easyui-dialog" title="运营商报告" style="width:1600px;height:700px;top: 10px;"data-options="iconCls:'icon-info',modal:true,closed:true">
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
    //[key=>array]
    globalData['tmp'] = [];
    var apiKey = "dd0171ba1b5f4b03a49375f4194b2b7e";
    var $userid = "xiaoyougaotou_test";
    var $token = "d74ea104c9274d40aaf873eb972cfa87";

</script>
<script type="text/javascript" src="/assets/apps/user/yysreportquery.js?s=<?= time()?>"></script>
<script type="text/javascript" src="/assets/apps/user/query.js?s=<?= time()?>"></script>
<script type="text/javascript">
    //numjucks 加载页面
    $("#listselect").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/select_basic', 
            {
                selectConfig: [['手机号', 'mobile', 'find-mobile']],
                canSelect: <?= checkRolePower('Qiye', 'yyslist') ?>,
                expendBtn: [['查看原始报告', '' , 'getoriginal',1],['拉取报告', 'https://api.51datakey.com/h5/importV3/index.html#/carrier?apiKey=dd0171ba1b5f4b03a49375f4194b2b7e&userId=xiaoyougaotou_test&backUrl='+AJAXBASEURL+'Qiye/tojd&taskId', 'get', 1]] 
            }
        )
    );
    
</script>

<script type="text/javascript">

//#################################################
    //点击查询
    $("#query").click(function(){
        var mobile = $("#find-mobile").val();
        if(mobile == false){ mobile = 'err';}
        $.ajax({ 
            type : "post", 
            url : AJAXBASEURL + 'Qiye/yyslist', 
            data : {key: mobile},
            success : function(res){ 
                // console.log(JSON.parse(res));
                $("#dd").html('');
                res = JSON.parse(res);
                if (0 == res.code) {
                    res = res.data;
                    console.log(res.data.cell_behavior[0]);
                    $("#dd").prepend(
                        nunjucks.render(
                            AJAXBASEURL + tplPath + 'v001/view_box_basic', 
                            {boxwidth:'width:1600px;', data: res}
                        )
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

    $('.getoriginal').click(function(){
        var mobile = $("#find-mobile").val();
        if(mobile == false){ mobile = 'err';}
        $.ajax({ 
            type : "post", 
            url : AJAXBASEURL + 'Qiye/getyysoriginalreport', 
            data : {key: mobile},
            success : function(res){ 
                // console.log(JSON.parse(res));
                $("#dd").html('');
                res = JSON.parse(res);
                if (0 == res.code) {
                    res = res.data;
                    console.log(res.original_data);
                    $("#dd").prepend(
                        nunjucks.render(
                            AJAXBASEURL + tplPath + 'v001/view_yys_original_box', 
                            {boxwidth:'width:1600px;', data: res.original_data}
                        )
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

    //获取运营商报告
    function record_report() {
        $.ajax({ 
            type : "post", 
            url : AJAXBASEURL + 'Qiye/getyysreport', 
            data : {mobile: mobile},
            success : function(res){ 
                console.log(res);
            } 
        });
    } 

</script>

<?= tpl('admin_foot') ?>
</body>
</html>