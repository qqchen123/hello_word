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
    }s
</style>

<body class="easyui-layout">
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #cccccc;" >
    <div class="input-group col-md-3" style="margin: 0 auto; margin-top: 17px;">
        <input type="text" id="search_info" class="form-control"placeholder="请输入小区名！" value='河畔明珠' / >
        <span class="input-group-btn">
            <button class="btn btn-info btn-search" onclick="searchinfo()">查找</button>
                        <button class="btn btn-info btn-search" onclick="get_taobao_html()" >taobao</button>
        </span>
    </div>
</div>
<!-- <div data-options="region:'center',title:'地址'" style="padding:5px;background:#eee;">
    <table id="dg">
    </table>
</div>

<div id="house_detail1" class="easyui-window" title="房屋详情" style="width:400px;height:200px"
     data-options="modal:true,closed:true">
    <div class="bd1"><span>地址：</span><i id="address1"></i> </div>
    <div class="bd1"> <span>面积：</span><i id="mianji1"></i> </div>
    <div class="bd1"><span>价格：</span> <i id="jg_detail1"></i></div>
    <div class="bd1"> <span>拍卖时间：</span> <i id="pm_time1"></i></div>
    <div class="bd1"> <span>联系人：</span><i id="lc1"></i> </div>
    <div class="bd1"> <span>手机：</span><i id="bz_money1"></i> </div>
</div> -->
<div id="dsftb"><iframe src="" id="sftb" style="height:1000px;width:1800px;">234</iframe></div>

</body>
<script src="/assets/lib/js/zoomify.min.js"></script>

<script>
    $('#dg').datagrid({
        url:'sftaobao',
        method:'get',
        // pagination: true,
        // rownumbers: true,
        columns:[[
            {field:'title',title:'房屋名称',width:'25%',align:'center'},
            {field:'currentPrice',title:'当前价',width:'10%',align:'center'},
            {field:'consultPrice',title:'评估价',width:'7%',align:'center'},
            {field:'status',title:'状态',width:'20%',align:'center',
                formatter:function (value, row) {
                    if (row.status=='doing'){
                        return '<span style="color:red">正在进行</span>';
                    }else{
                        return '已结束';
                    }
                }
            },
            {field:'end',title:'预计结束时间',width:'7%',align:'center',
                formatter:function (value, row) {
                    let timess = row.end.toString();
                    let jqtime = timess.substr(0,10);
                    return formatDateTime(jqtime);
                }
            },
            {field:'qwe',title:'操作',width:'10%',align:'center',
                formatter:function (value,row) {
                    // console.log(row);
                    let html = '';
                    let param = '\''+row.itemUrl+'\'';
                    html+= '<a class="btn btn-primary btn-xs p310" href=" " onclick="caozuo('+param+')" >详情</ a>'+'&nbsp;&nbsp';
                    return html;
                }
            },
        ]]
    });
    function searchinfo(){
        let search = $('#search_info').val();
        search = $.trim(search);
        $('#dg').datagrid('load',{
            xiaoqu: search,
        });
    }
    function formatDateTime(timeStamp) {
        var date = new Date();
        date.setTime(timeStamp * 1000);
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        m = m < 10 ? ('0' + m) : m;
        var d = date.getDate();
        d = d < 10 ? ('0' + d) : d;
        var h = date.getHours();
        h = h < 10 ? ('0' + h) : h;
        var minute = date.getMinutes();
        var second = date.getSeconds();
        minute = minute < 10 ? ('0' + minute) : minute;
        second = second < 10 ? ('0' + second) : second;
        return y + '-' + m + '-' + d+' '+h+':'+minute+':'+second;
    }
    function caozuo(param) {
        param = param.slice(2);
        // console.log(param);return;
        $.ajax({
            type: "POST",
            url: "taobao_detail",
            data: {id:param},
            dataType: "json",
            success: function(data){
                console.log(data);
            }
        });
        $('#house_detail1').window('open');
    }
    
     function get_taobao_html() {
        // alert($('#sftb').html());
    	let search = $('#search_info').val();
        search = $.trim(search);
       // search = encodeURI(search);
        var urls ="https://sf.taobao.com/item_list.htm?q=";
        //search = gb2utf8(search);
        $.get("UTF8toGBK", {urlstr: search},function(data){
            urls  += data;
            urls +="&spm=a213w.3064813.9001.1";
           // $('#sftb').attr('src',urls);
            $.get("https://sf.taobao.com/item_list.htm", {q: data,spm:'a213w.3064813.9001.1'},function(datas){
               
               alert(datas);
                console.log(datas);
          	}); 
      	}); 
        
         /* setTimeout(function(){
        	var htmlstr = $('#sftb').contents().find('#sf-item-list-data');
        	console.log(htmlstr);
        },5000);  */
        
        
        
       
       
    } 
    
</script>

</body>