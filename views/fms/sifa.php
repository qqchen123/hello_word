<?php //tpl("admin_header") ?>
<body>
<!-- <link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css"> -->
<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/bootstrap-grid.min.css">
<link rel="stylesheet" href="/assets/lib/css/zoomify.min.css">
<!--<link rel="stylesheet" href="/assets/lib/css/style.css">-->
<!-- <title></title> -->
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<style>
    .bd1{
        widows: 100%;

    }
    .bd1 span {
        margin-left: 10px;
        font-size: 25px;
        font-weight: bold;
    }

</style>

<body class="easyui-layout">
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #cccccc;" >
    <div class="input-group col-md-3" style="margin: 0 auto; margin-top: 17px;">
        <input type="text" id="search_info" class="form-control"placeholder="请输入小区名！" value='河畔明珠'/ >
        <span class="input-group-btn">
            <button class="btn btn-info btn-search" onclick="searchinfo()">顺如</button>
            <button class="btn btn-info btn-search" onclick="searchsifa()">拍好房</button>
            <button class="btn btn-info btn-search" onclick="searchbeike()">贝壳网</button>
        </span>
    </div>
</div>
<div data-options="region:'center',title:'地址'" style="padding:5px;background:#eee;">
    <table id="dg">
    </table>
    <table id="df">
    </table>
    <table id="db">
    </table>
</div>
<div id="house_detail" class="easyui-window" title="顺如网房屋详情" style="width:600px;height:600px"
     data-options="modal:true,closed:true">
    <div class="bd1"> <span>小区名：</span> <i id="xq_name"></i></div>
    <div class="bd1"><span>地址：</span><i id="address"></i> </div>
    <div class="bd1"> <span>单价：</span><i id="danjia"></i> </div>
    <div class="bd1"> <span>楼层：</span><i id="lc"></i> </div>
    <div class="bd1"> <span>面积：</span><i id="mianji"></i> </div>
    <div class="bd1"> <span>保证金：</span><i id="bz_money"></i> </div>
    <div class="bd1"><span>价格详情：</span> <i id="jg_detail"></i></div>
    <div class="bd1"> <span>拍卖时间：</span> <i id="pm_time"></i></div>
    <div class="bd1"> <span>小区详情：</span><i id="xq_detail"> </i> </div>
</div>
<div id="house_detail1" class="easyui-window" title="拍好房房屋详情" style="width:600px;height:400px"
     data-options="modal:true,closed:true">
    <div class="bd1"><span>地址：</span><i id="address1"></i> </div>
    <div class="bd1"> <span>面积：</span><i id="mianji1"></i> </div>
    <div class="bd1"><span>价格：</span> <i id="jg_detail1"></i></div>
    <div class="bd1"> <span>拍卖时间：</span> <i id="pm_time1"></i></div>
    <div class="bd1"> <span>联系人：</span><i id="lc1"></i> </div>
    <div class="bd1"> <span>手机：</span><i id="bz_money1"></i> </div>
</div>
<div id="house_detailbk" class="easyui-window" title="贝壳网小区详情" style="width:900px;height:600px"
     data-options="modal:true,closed:true">
    <div class="bd1"><span>城市：</span><i id="city"></i> </div>
    <div class="bd1"><span>地区：</span><i id="district"></i> </div>
    <div class="bd1"><span>名称：</span><i id="name"></i> </div>
    <div class="bd1"><span>地址：</span><i id="addressbk"></i> </div>
    <div class="bd1"> <span>最近估价：</span><i id="price"></i> </div>
    <div class="bd1"><span>建成时间：</span> <i id="comyear"></i></div>
    <div class="bd1"> <span>物业费：</span> <i id="propertyfee"></i></div>
    <div class="bd1"> <span>楼栋总数：</span><i id="buildingnum"></i> </div>
    <div class="bd1"> <span>房屋总数：</span><i id="roomnum"></i> </div>
    <div class="bd1"> <span>最近成交：</span><table id="zjcj" border="1"></table> </div>
</div>
</body>
<script src="/assets/lib/js/zoomify.min.js"></script>

<script>
$('#dg').datagrid({
    url:'searchshunruXiaoqu',
    method:'get',
    // pagination: true,
    // rownumbers: true,
    columns:[[
        {field:'1',title:'名称',width:'10%',align:'center'},
        {field:'2',title:'电话',width:'10%',align:'center'},
        {field:'3',title:'起拍价',width:'7%',align:'center'},
        {field:'4',title:'市场价',width:'20%',align:'center'},
        {field:'caozuo',title:'操作',width:'20%',align:'center',
            formatter:function (value,row) {
                let html = '';
                let param = '\''+row[0]+'\'';
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="caozuo1('+param+')" >详情</a>'+'&nbsp;&nbsp';
                return html;
            }
        }
    ]]
});
$('#df').datagrid({
    url:'searchpaihaofangXiaoqu',
    method:'get',
    // pagination: true,
    // rownumbers: true,
    columns:[[
        {field:'1',title:'名称',width:'30%',align:'center'},
        {field:'4',title:'拍卖时间',width:'10%',align:'center'},
        // {field:'2',title:'联系人',width:'5%',align:'center'},
        // {field:'3',title:'手机',width:'5%',align:'center'},
        {field:'2',title:'面积',width:'10%',align:'center'},
        {field:'3',title:'参考价格',width:'10%',align:'center'},
        {field:'3',title:'手机',width:'5%',align:'center'},
        {field:'qwe',title:'操作',width:'10%',align:'center',
            formatter:function (value,row) {
                let html = '';
                let param = '\''+row[0]+'\'';
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="caozuo('+param+')" >详情</a>'+'&nbsp;&nbsp';
                return html;
            }
        },
    ]]
});
$('#db').datagrid({
    url:'searchbeikeXiaoqu',
    method:'get',
    // pagination: true,
    // rownumbers: true,
    columns:[[
    	{field:'city',title:'城市',width:'10%',align:'center'},
    	{field:'district',title:'地区',width:'10%',align:'center'},
        {field:'name',title:'名称',width:'10%',align:'center'},
        {field:'comyear',title:'建成时间',width:'5%',align:'center'},
        {field:'roomnum',title:'房屋总数',width:'5%',align:'center'},
        {field:'buildingnum',title:'楼栋总数',width:'5%',align:'center'},
        {field:'address',title:'地址',width:'10%',align:'center'},
        {field:'propertyfee',title:'物业费',width:'5%',align:'center'},
        
        {field:'price',title:'参考价格',width:'5%',align:'center'},
        {field:'month',title:'参参月份',width:'10%',align:'center'},
        {field:'qwe',title:'操作',width:'10%',align:'center',
            formatter:function (value,row) {
                console.log(row);
                let html = '';
                let param = row['searchurl'];
                html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="caozuobk('+param+')" >详情</a>'+'&nbsp;&nbsp';
                return html;
            }
        },
    ]]
});
function caozuobk(param) {
    $.ajax({
        type: "POST",
        url: "beikexiaoquxq",
        data: {id:param},
        dataType: "json",
        success: function(data){
            $('#name').html(data.name);
            $('#addressbk').html(data.address);
            $('#district').html(data.district);
            $('#comyear').html(data.comyear);
            $('#propertyfee').html(data.propertyfee);
            $('#buildingnum').html(data.buildingnum);
            $('#roomnum').html(data.roomnum);
            $('#price').html(data.price);
            $('#city').html(data.city);
            $('#zjcj').html(data.zjcj);
          

        }
    });
    $('#house_detailbk').window('open');
}
    function caozuo1(param) {
        $.ajax({
            type: "POST",
            url: "getshunrufangHousInfo",
            data: {id:param},
            dataType: "json",
            success: function(data){
                $('#xq_name').html(data.xq_name);
                $('#address').html(data.address);
                $('#danjia').html(data.danjia);
                $('#lc').html(data.louceng);
                $('#mianji').html(data.mianji);
                $('#bz_money').html(data.bz_money);
                $('#jg_detail').html(data.price);
                $('#pm_time').html(data.date);
                $('#xq_detail').html(data.message);
            }
        });
        $('#house_detail').window('open');
    }
    function caozuo(param) {
        $.ajax({
            type: "POST",
            url: "getpmpaihaofangHousInfo",
            data: {id:param},
            dataType: "json",
            success: function(data){
                console.log(data);
                $('#address1').html(data.name);
                $('#mianji1').html(data.mianji);
                $('#jg_detail1').html(data.price);
                $('#pm_time1').html(data.date);
                $('#lc1').html(data.user);
                $('#bz_money1').html(data.phone);
            }
        });
        $('#house_detail1').window('open');
    }
    function searchinfo(){
        let search = $('#search_info').val();
        search = $.trim(search);
        $('#dg').datagrid('load',{
        	xiaoqu: search,
        });
    }
    function searchsifa(){
        let search = $('#search_info').val();
        search = $.trim(search);
        $('#df').datagrid('load',{
       	 	xiaoqu: search,
        });
        
    }
    function searchbeike(){
        let search = $('#search_info').val();
        search = $.trim(search);
        $('#db').datagrid('load',{
            xiaoqu: search,
        });
    }
</script>

</body>