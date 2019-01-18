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
    #search{
        margin: 0 auto;
    }
    .examples { margin-top: 40px; }
    .examples .row { margin-bottom: 20px; }
    .examples .col-md-3 { text-align: center; margin-bottom: 20px; }
    .example img{width: 100%;}

    #id {
        margin: 2px 2px 0 2px;
        border-radius: 5px;
        width: 99%;
        height: 250px;
        padding-left: 20px;
        border: solid #cccccc 1px;
        position: relative;
    }
    #id .ids{
        float: left;
    }
    #id .ids_img1{
        clear: both;
        position: relative;
        top: 30px;
        left: 30px;
    }
    #id .ids_img2{
        position: relative;
        top: -12px;
        left: 50px;
    }
    #house{
        margin: 2px 2px 0 2px;
        border-radius: 5px;
        width: 99%;
        height: 250px;
        padding-left: 20px;
        border: solid #cccccc 1px;
        position: relative;
    }
    #house .house1{
        position: relative;
        top: 10px;
        left: 0px;
    }
    #house .house2{
        position: relative;
        top: -11px;
        left: 55px;
    }
    #house .house3{
        position: relative;
        top: -33px;
        left: 108px;
    }
    #money{
        margin: 2px 2px 0 2px;
        border-radius: 5px;
        width: 99%;
        height: 79px;
        /*padding-left: 20px;*/
        border: solid #cccccc 1px;
        position: relative;
    }

    #money .money1{
        position: relative;
        left: 26px;
    }
    #money .money2{
        position: relative;
        top: -52px;
        left: 278px;
    }
    #money .money3{
        position: relative;
        top: -104px;
        left: 539px;
    }
    #butsres{
        margin: 2px 2px 0 2px;
        border-radius: 5px;
        width: 99%;
        height: 79px;
        /*padding-left: 20px;*/
        border: solid #cccccc 1px;
        position: relative;
    }
    #butsres .but{
        position: relative;
        top: 27px;
        left: 347px;
    }

</style>

<body class="easyui-layout">
    <div data-options="region:'north',title:'报单搜索',split:true" style="height:100px; background: #cccccc;" >
        <div class="input-group col-md-3" style="margin: 0 auto; margin-top: 17px;">
            <input type="text" id="search_info" class="form-control"placeholder="请输入 客户名 或 身份证 ！" / >
            <span class="input-group-btn">
               <button class="btn btn-info btn-search" onclick="searchinfo()">查找</button>
<!--               <button class="btn btn-info btn-search" style="margin-left:3px">添加</button>-->
            </span>
        </div>
    </div>
    <div data-options="region:'center',title:'报单列表'" style="padding:5px;background:#eee;">
        <table id="dg">
        </table>
    </div>
    <div id="dec_detail" class="easyui-dialog" title="详情" style="width:75%;height:600px;" data-options="resizable:true,modal:true,closed:true">
        <div id="id">
            <div class="ids" style="margin-top:10px">
                <label>客户姓名:</label>
                <input class="easyui-textbox" type="text" id="user_name" style="width:180px;height:32px">
            </div>
            <div class="ids" style="margin-top:10px; margin-left: 10px;">
                <label>身份证:</label>
                <input class="easyui-textbox" id="id_num" style="width:180px;height:32px">
            </div>
            <div style="margin-top:20px;" class="ids_img1">
                <label>身份证正面:</label>
                <br>
                <div class="example col-xs-3 col-md-3">
                    <img id="front_img" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1535713658047&di=35661014c5e31a66aae526698e4aa398&imgtype=0&src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01a8bc5541e20d0000011541120aa4.jpg%401280w_1l_2o_100sh.png" alt="暂无图片" width="180" height="100">
                </div>
            </div>
            <div style="margin-top:20px;" class="ids_img2">
                <label>身份证反面:</label>
                <br>
                <div class="example col-xs-3 col-md-3">
                    <img id="back_img" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1535713658047&di=35661014c5e31a66aae526698e4aa398&imgtype=0&src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01a8bc5541e20d0000011541120aa4.jpg%401280w_1l_2o_100sh.png" alt="暂无图片" width="180" height="100">
                </div>
            </div>
        </div>

        <div id="house">
            <div style="margin-top: 20px;">
                <label for="">房产证详细地址:</label>
                <input class="easyui-textbox" id="address" style="width:300px;height:32px">
            </div>
            <div class="house1">
                <label for="">房产证编码页:</label>
                <br>
                <div class="example col-xs-3 col-md-3">
                    <img id="codepage_string" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1535713658047&di=35661014c5e31a66aae526698e4aa398&imgtype=0&src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01a8bc5541e20d0000011541120aa4.jpg%401280w_1l_2o_100sh.png" alt="暂无图片" width="180" height="100">
                </div>
            </div>
            <div class="house2">
                <label for="">房产证内容页:</label>
                <br>
                <div class="example col-xs-3 col-md-3">
                    <img id="content_string" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1535713658047&di=35661014c5e31a66aae526698e4aa398&imgtype=0&src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01a8bc5541e20d0000011541120aa4.jpg%401280w_1l_2o_100sh.png" alt="暂无图片" width="180" height="100">
                </div>
            </div>
            <div class="house3">
                <label for="">房产证附记页:</label>
                <br>
                <div class="example col-xs-3 col-md-3">
                    <img id="notes_string" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1535713658047&di=35661014c5e31a66aae526698e4aa398&imgtype=0&src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01a8bc5541e20d0000011541120aa4.jpg%401280w_1l_2o_100sh.png" alt="暂无图片" width="180" height="100">
                </div>
            </div>
        </div>
        <div id="money">
            <div style="margin-top:20px" class="money1">
                <label>需求金额:</label>
                <input class="easyui-textbox" id="moneys" style="width:180px;height:32px">
            </div>
            <div style="margin-top:20px" class="money2">
                <label>借款用途:</label>
                <input class="easyui-textbox" id="lend_fors" style="width:180px;height:32px">
            </div>
            <div style="margin-top:20px" class="money3">
                <label>还款来源:</label>
                <input class="easyui-textbox" id="back_sources" style="width:180px;height:32px">
            </div>
        </div>
<!--        <div id="comment">-->
<!--            <div style="width: 50%;">-->
<!--                <a href="#" class="list-group-item active">-->
<!--                    评论-->
<!--                </a>-->
<!--                <a href="#" class="list-group-item"> 24*7 支持<span class="glyphicon glyphicon-comment pull-right" aria-hidden="true"></span></a>-->
<!--                <a href="#" class="list-group-item">免费 Window 空间托管<span class="glyphicon glyphicon-comment pull-right" aria-hidden="true"></span></a>-->
<!--                <a href="#" class="list-group-item">图像的数量<span class="glyphicon glyphicon-comment pull-right" aria-hidden="true"></span></a>-->
<!--                <a href="#" class="list-group-item">每年更新成本<span class="glyphicon glyphicon-comment pull-right" aria-hidden="true"></span></a>-->
<!--                <textarea class="form-control" rows="3"></textarea>-->
<!--                <button type="button" class="btn btn-info">提交</button>-->
<!--            </div>-->
<!--        </div>-->
        <div id="butsres">
<!--            <div style="margin: 0 auto;">-->
                <a href="#" class="easyui-linkbutton but" icon="icon-ok" onclick="javascript:$('#dec_detail').dialog('close')">确定</a>
<!--                <a href="#" class="easyui-linkbutton but" icon="icon-cancel" onclick="javascript:$('#dec_detail').dialog('close')" >关闭</a>-->
<!--            </div>-->
        </div>
    </div>

</body>
<script src="/assets/lib/js/zoomify.min.js"></script>

<script>
    $('#dg').datagrid({
        url:'get_info',
        pagination: true,
        rownumbers: true,
        columns:[[
            {field:'name',title:'客户姓名',width:'10%',align:'center'},
            {field:'idnumber',title:'身份证',width:'15%',align:'center'},
            {field:'address',title:'房产详细地址',width:'20%',align:'center'},
            {field:'amount',title:'需求金额',width:'10%',align:'center'},
            {field:'useby',title:'借款用途',width:'10%',align:'center'},
            {field:'repaymentby',title:'还款来源',width:'10%',align:'center'},
            {field:'opera',title:'操作',width:'10%',align:'center',
                formatter:function (value, row) {
                    let html = '';
                    let str = row.d_id;
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="check('+'\''+str+'\''+')">查看</a>';
                    return html;
                }
            }
        ]]
    });
    function check(id) {
        $.ajax({
            type: "POST",
            url: "get_one_de_info",
            data: {id:id},
            dataType: "json",
            success(data){
                $('#user_name').textbox('setValue', data.name).textbox('textbox').attr('readonly',true).css('background','#ffb3b3').css('font-weight','bold');
                $('#id_num').textbox('setValue', data.idnumber).textbox('textbox').attr('readonly',true).css('background','#ffb3b3').css('font-weight','bold');
                $('#address').textbox('setValue', data.address).textbox('textbox').attr('readonly',true).css('background','#ffb3b3').css('font-weight','bold');
                $('#moneys').textbox('setValue', data.amount).textbox('textbox').attr('readonly',true).css('background','#ffb3b3').css('font-weight','bold');
                $('#lend_fors').textbox('setValue', data.useby).textbox('textbox').attr('readonly',true).css('background','#ffb3b3').css('font-weight','bold');
                $('#back_sources').textbox('setValue', data.repaymentby).textbox('textbox').attr('readonly',true).css('background','#ffb3b3').css('font-weight','bold');
                $('#front_img').attr('src', data.front_img);
                $('#back_img').attr('src', data.back_img);
                $('#codepage_string').attr('src', data.codepage_string);
                $('#content_string').attr('src', data.content_string);
                $('#notes_string').attr('src', data.notes_string);
            }
        });
        $('#dec_detail').dialog('open');
    }
    function searchinfo(){
        let search = $('#search_info').val();
        search = $.trim(search);
        $('#dg').datagrid('load',{
            user_name: search,
        });
    }

    $('.example img').zoomify();//图片点击放大
</script>

</body>