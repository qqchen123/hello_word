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
<link rel="stylesheet" href="/assets/layui/layui.css">
<!-- <title></title> -->
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/assets/layui/layui.js"></script>
<style>
.pic{
    float: left;
    margin-left: 5px;
    display: none;
}

</style>

<body class="easyui-layout">
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #eee;" >
    <div class="input-group col-md-3" style="margin: 0 auto; margin-top: 17px;">
        <input type="text" id="kh_id" class="form-control"placeholder="请输入客户ID！" / >
        <span class="input-group-btn">
            <button class="btn btn-info btn-search" onclick="make_id_select()">确认</button>
<!--            <button class="btn btn-info btn-search" onclick="show_login()" >登陆</button>-->
        </span>
    </div>
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">

    <div style="margin-bottom:20px">
        <div>账号:</div>
        <input class="easyui-textbox" id="fms_account" style="width:50%;height:32px">
    </div>
    <div style="margin-bottom:20px">
        <div>密码:</div>
        <input class="easyui-textbox" id="fms_pwd" style="width:50%;height:32px">
    </div>


    <form id="sms_phone" action="">
        <div style="margin-bottom:20px">
            <div>手机:</div>
                    <input class="easyui-textbox" id="kh_phone" style="width:50%;height:32px">
        </div>
    </form>

    <div class="pic">
        <img id="show_img1" src="" width="200" height="200">
        <br>
        <br>
<!--        <button type="button" class="layui-btn" id="test1">-->
<!--            <i class="layui-icon">&#xe67c;</i>上传图片1-->
<!--        </button>-->
    </div>

    <div class="pic">
        <img id="show_img2" src="" width="200" height="200">
        <br>
        <br>
<!--        <button type="button" class="layui-btn" id="test2">-->
<!--            <i class="layui-icon">&#xe67c;</i>上传图片2-->
<!--        </button>-->

    </div>

    <div class="pic">
        <img id="show_img3" src="" width="200" height="200">
        <br>
        <br>
<!--        <button type="button" class="layui-btn" id="test3">-->
<!--            <i class="layui-icon">&#xe67c;</i>上传图片3-->
<!--        </button>-->

    </div>

    <div class="pic">
        <img id="show_img4" src="" width="200" height="200">
        <br>
        <br>
<!--        <button type="button" class="layui-btn" id="test4">-->
<!--            <i class="layui-icon">&#xe67c;</i>上传图片3-->
<!--        </button>-->

    </div>
</div>

</body>

<script>
    function make_id_select() {
        let kh_id = $('#kh_id').val();
        kh_id = $.trim(kh_id);
        $.ajax({
            type: "POST",
            url: 'get_bank_select',
            data: {kh_id},
            dataType: "json",
            success(data){
                console.log(data);
                if (data.code==1){
                    $('#fms_account').textbox('setValue',data.data.account);
                    $('#fms_pwd').textbox('setValue',data.data.pwd);
                    $('#kh_phone').textbox('setValue',data.data.phone);
                    $('#show_img1').attr("src",data.data.img_path1);
                    $('#show_img2').attr("src",data.data.img_path2);
                    $('#show_img3').attr("src",data.data.img_path3);
                    $('#show_img4').attr("src",data.data.img_path4);
                    $('#fms_account').textbox('textbox').attr('readonly',true);
                    $('#fms_pwd').textbox('textbox').attr('readonly',true);
                    $('#kh_phone').textbox('textbox').attr('readonly',true);
                    $('.pic').css('display','block');
                } else{
                    $.messager.show({
                        title: '提示',
                        msg: data.message
                    });
                }
            }
        })
    }

</script>

</body>