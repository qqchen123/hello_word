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
    <div id="easyuilist"></div>
</div><!-- /.page-content -->
	<!-- 流程信息 -->
    <? showHistoryHtml(); ?>
<script>
    var youtu_flag = 1;
    var submit_flag = 1;
    var session_name = '<?= $_SESSION['fms_username']?>';
    <?= $jscontroller?>
    var role_arrry = [];
    var statusColor = JSON.parse('<?= $statusColor ?>');
    var edit_able = ['fuserid', 'idnumber', 'name', 'channel'];
    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
    var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
    var globalData = [];//用于装载全局js变量
    var phpData = [];//php返回的内容
    //[key=>array]
    globalData['tmp'] = [];

</script>
<script type="text/javascript" src="/assets/apps/zhaiquanlist.js?s=<?= time()?>"></script>
<script type="text/javascript">
    $("#listselect").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/select_basic', 
            {
                selectConfig: [['身份证号', 'idnumber', 'find-idnumber'],['姓名', 'name', 'find-name']],
                canSelect: <?= checkRolePower('Zhaiquanlist', 'list') ?>, 
            }
        )
    );
    $("#easyuilist").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/datagrid_basic', 
            {datagrid_id: 'tt', target: 'getlist'}
        )
    );
</script>

<script type="text/javascript">

    Array.prototype.unique = function() {
        var a = this.concat();
        for(var i=0; i < a.length; i++){
            for(var j=i+1; j < a.length; j++){
                if(a[i] === a[j]){
                    a.splice(j, 1);
                }
            }
        }
        return a;
    };

    //显示按钮
    function showBtn(){
    	return false;
    }

//#################################################
    //点击查询
    $("#query").click(function(){
        var idnumber = $("#find-idnumber").val();
        var name = $("#find-name").val();
        if(idnumber == false){ idnumber = 'err';}
        if(name == false){ name = 'err';}
        $('#tt').datagrid('load', {idnumber:idnumber, name:name});
    }); 

</script>

<?= tpl('admin_foot') ?>
</body>
</html>