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

    .sms_phone{
        margin: 20px 0 0 30px;
    }
    .sms_phone label{
        font-size: 14px;
    }
    .sms_phone input{
        width: 200px;
        height: 30px;
        border-radius: 5px;
    }
    .sms_phone div{
        margin: 10px 0 0 40px;
    }
    #lp tr td:first-child{
        width: 70px;
    }
    #lp tr td:nth-child(2){
        /*width: 70px;*/
        font-size: 14px;
        color: #ccc;
    }
    #lp tr td:nth-child(4){
        /*width: 70px;*/
        font-size: 14px;
        color: #ccc;
    }
</style>

<body class="easyui-layout">
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #cccccc;" >
    <div class="input-group col-md-3" style="margin: 0 auto; margin-top: 17px;">
        <input type="text" id="search_info" class="form-control"placeholder="请输入小区名！" / >
        <span class="input-group-btn">
            <button class="btn btn-info btn-search" onclick="searchinfo()">查找</button>
            <button class="btn btn-info btn-search" onclick="show_login()" >登陆</button>
        </span>
    </div>
</div>
<div data-options="region:'center',title:'地址'" style="padding:5px;background:#eee;">
    <table id="dg">
    </table>
</div>
<div id="win_add_phone" class="easyui-window" title="登陆房估估" style="width:400px;height:200px"
     data-options="modal:true,closed:true">
    <form id="sms_phone" class="sms_phone" method="post">
        <input type="hidden" id="e_id" name="id">
        <div>
            <label for="">账号:</label>
            <input class="easyui-validatebox" type="text" id="e_username" name="username" data-options="required:true,validType:'isBlank'" />
        </div>
        <div>
            <label for="">密码:</label>
            <input class="easyui-validatebox" validtype="password" type="password" id="password" name="password" data-options="required:true" />
        </div>
        <div style="padding:5px;text-align:center;margin-top: 20px;">
            <a href="#" onclick="do_login()" id="add_phone"  class="easyui-linkbutton" icon="icon-ok">确认</a>
            <a href="#" class="easyui-linkbutton" onclick="javascript:$('#win_add_phone').window('close')" icon="icon-cancel">取消</a>
        </div>
    </form>
</div>

<!-- 周边小区-->
<div id="nearby" class="easyui-window" title="周边小区" style="width:400px;height:200px"
     data-options="modal:true,closed:true">
    <table id="near"></table>
</div>
<!-- 楼盘详情-->
<div id="build_detail" class="easyui-window" title="周边小区" style="width:800px;height:300px"
     data-options="modal:true,closed:true">
    <table id="lp" border="1" style="width: 700px; margin: 0 auto;">
        <tr>
            <td>小区地址</td>
            <td colspan="3" id="xq_address"></td>
        </tr>
        <tr>
            <td>小区名称</td>
            <td id="xq_name"></td>
            <td>建筑类别</td>
            <td id="b_type"></td>
        </tr>
        <tr>
            <td>开发商</td>
            <td id="kf"></td>
            <td>开盘时间</td>
            <td id="kf_time"></td>
        </tr>
        <tr>
            <td>绿化率</td>
            <td id="lh"></td>
            <td>容积率</td>
            <td id="rj"></td>
        </tr>
        <tr>
            <td>物业公司</td>
            <td id="wy_com"></td>
            <td>物业费</td>
            <td id="wy_mo"></td>
        </tr>
        <tr>
            <td>建筑面积</td>
            <td id="b_area"></td>
            <td>土地面积</td>
            <td id="td_area"></td>
        </tr>
        <tr>
            <td>车位状况</td>
            <td id="car_status"></td>
            <td>土地使用年限</td>
            <td id="td_use_time"></td>
        </tr>
        <tr>
            <td>公交</td>
            <td colspan="3" id="bus"></td>
        </tr>
        <tr>
            <td>地铁</td>
            <td colspan="3" id="subway"></td>
        </tr>
    </table>
    <div id="desc" style="width: 700px; margin: 0 auto;">

    </div>
</div>

<div id="house" class="easyui-window" title="估一估" style="width:400px;height:500px"
     data-options="modal:true,closed:true">
    <form id="reptile_gyg" class="sms_phone" method="post">
        <div>
            <label for="">建筑面积:</label>
            <input class="easyui-validatebox" type="text"  name="area" data-options="required:true,validType:'number'" />
        </div>
        <div>
            <label for="">房屋坐落:</label>
            <input  id="unltNo" type="text"  name="unltNo" style="width: 126px;"/>
            <input  id="houseNo" type="text"  name="houseNo" style="width: 70px;" />
        </div>
        <div>
            <label for="">物业类型:</label>
<!--            <input class="easyui-combobox"  type="text"  name="house_type" />-->
            <select id="cc1" class="easyui-combobox" name="house_type" style="width:200px;">
                <option value=""></option>
                <option value="住宅">住宅</option>
                <option value="住宅">别墅</option>
            </select>
        </div>
        <div>
            <label for="">房屋朝向:</label>
<!--            <input class="easyui-combobox"  type="text"  name="toward" />-->
            <select id="cc2" class="easyui-combobox" name="toward" style="width:200px;">
                <option value=""></option>
                <option value="东">东</option>
                <option value="西">西</option>
                <option value="南">南</option>
                <option value="北">北</option>
            </select>
        </div>
        <div>
            <label for="">所在楼层:</label>
            <input class="easyui-numberbox"  type="text"  name="floor" data-options="validType:'number'"/>
        </div>
        <div>
            <label for="">建成年代:</label>
            <input class="easyui-numberbox"  type="text"  name="builted_time" data-options="validType:'number'"/>
        </div>
        <div>
            <label for="">总楼层:&nbsp;&nbsp;&nbsp;</label>
            <input class="easyui-numberbox"  type="text"  name="totalfloor" data-options="validType:'number'"/>
        </div>
        <input type="hidden" id="house_number" name="house_number" value="">
        <input type="hidden" id="xiaoquID" name="xiaoquID" value="">
        <input type="hidden" name="xiaoqujunjia" value="">
<!--        <input type="hidden" name="houseNo" value="">-->
<!--        <input type="hidden" name="unitID" value="">-->
<!--        <input type="hidden" name="houseID" value="">-->
        <input type="hidden" id="residentialName" name="residentialName" value="">
        <div id="div_money" style="width: 300px;height: 80px; display: none;" >
            <div style="margin: 20px 0 0 20px;;"><span style="margin: ">单价: <span id="d_money"></span>元/m<sup>2</sup></span>|&nbsp;&nbsp;<span>总价:<span id="z_money"></span>万元</span></div>
        </div>
        <div id="f_msg" style="width: 300px;height: 80px; display: none;">

        </div>
        <div style="padding:5px;text-align:center;margin-top: 20px;">
            <a href="#" onclick="do_guyigu()" id="add_phone"  class="easyui-linkbutton" icon="icon-ok">确认</a>
            <a href="#" class="easyui-linkbutton" onclick="javascript:$('#house').window('close')" icon="icon-cancel">取消</a>
        </div>
    </form>
</div>
</body>
<script src="/assets/lib/js/zoomify.min.js"></script>

<script>
    // $('#near').datagrid({
    //     url:'getarea',
    //     pagination: true,
    //     rownumbers: true,
    //     columns:[[
    //         {field:'distance',title:'距离123(米)',width:'30%',align:'center'},
    //         {field:'residentialAreaName',title:'小区',width:'30%',align:'center'},
    //         {field:'unitPrice',title:'均价',width:'30%',align:'center'},
    //     ]]
    // });buildinfo


    $('#unltNo').combobox({
        url:'',
        valueField:'id',
        textField:'name'
    });
    $('#houseNo').combobox({
        url:'',
        valueField:'id',
        textField:'name'
    });
    $('#dg').datagrid({
        url:'getarea',
        pagination: true,
        rownumbers: true,
        columns:[[
            {field:'districtName',title:'区/县',width:'10%',align:'center'},
            {field:'similarName',title:'相似小区名称',width:'15%',align:'center'},
            {field:'residentialName',title:'住宅名',width:'20%',align:'center'},
            {field:'communityId',title:'社区ID',width:'30%',align:'center'},
            {field:'caozuo',title:'操作',width:'30%',align:'center',
                formatter:function (value, row) {
                    let html = '';
                    let communityId = row.communityId;
                    let districtName = row.districtName;
                    let residentialName = row.residentialName;
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="gujia('+'\''+communityId+'\''+','+'\''+districtName+'\''+','+'\''+residentialName+'\''+')">估价</a>&nbsp;';
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="nearby('+'\''+communityId+'\''+','+'\''+districtName+'\''+','+'\''+residentialName+'\''+')">周边小区</a>&nbsp;';
                    html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="buildetail('+'\''+communityId+'\''+','+'\''+districtName+'\''+','+'\''+residentialName+'\''+')">楼盘详情</a>';
                    return html;
                }
            }
        ]]
    });

    function buildetail(communityId,districtName,residentialName) {
        $('#build_detail').window('open')
        $.ajax({
            type: "POST",
            url: 'getarea_p',
            data: {communityId,districtName,residentialName},
            dataType: "json",
            success(data){
                $.ajax({
                    type: "POST",
                    url: 'getBase',
                    data: {residentialAreaId:data.data.comId},
                    dataType: "json",
                    success(data){
                        var arr = new Array();
                        for ( let k in data.busStationList){
                            arr[k] = data.busStationList[k]['LineName'];
                        }
                        $('#bus').html(arr.join(','));
                        if (data.subwayStationList) {
                            var subarr = new Array();
                            for ( let k in data.subwayStationList){
                                subarr[k] = data.subwayStationList[k]['LineName'];
                            }
                            $('#subway').html(subarr.join(','));
                        }
                        $('#car_status').html(data.residentialareaMap.parkingspaceInfo);
                        $('#desc').html(data.residentialareaMap.description);
                        $('#xq_address').html(data.residentialareaMap.address);
                        $('#xq_name').html(data.residentialareaMap.residentialareaName);
                        $('#b_type').html(data.residentialareaMap.buildingCategory);
                        $('#kf').html();
                        $('#rj').html(data.residentialareaMap.floorAreaRatio);
                        $('#kf_time').html();
                        $('#lh').html(data.residentialareaMap.greeningRate);
                        $('#wy_com').html(data.residentialareaMap.mamageemtCompany);
                        $('#wy_mo').html();
                        $('#b_area').html(data.residentialareaMap.buildingArea+'㎡');
                        $('#td_area').html(data.residentialareaMap.landArea+'㎡');
                        // $('#td_use_time').html(data.address);
                    }
                })
            }
        });
    }


    function searchinfo(){
        let search = $('#search_info').val();
        search = $.trim(search);
        $('#dg').datagrid('load',{
            xiaoqu: search,
        });
    }
    function show_login(){
        // $('#win_add_phone').window('open');
        $.ajax({
            type: "POST",
            url: 'funguguLogin',
            // data: {,},
            dataType: "json",
            success(data){
                if(data.success==true){
                    $.messager.show({
                        title: '提示',
                        msg: data.msg
                    });
                    $('#win_add_phone').window('close');
                }else{
                    $.messager.show({
                        title: '提示',
                        msg: data.msg
                    });
                }
            }
        });
        $('#dg').datagrid('reload');
    }
    show_login();//自动登陆
    function do_login(){
        let username = $('#e_username').val();
        let password = $('#password').val();
        $.ajax({
            type: "POST",
            url: 'funguguLogin',
            data: {username,password},
            dataType: "json",
            success(data){
                if(data.success==true){
                    $.messager.show({
                        title: '提示',
                        msg: data.msg
                    });
                    $('#win_add_phone').window('close');
                }else{
                    $.messager.show({
                        title: '提示',
                        msg: data.msg
                    });
                }
            }
        });
    }
    function do_guyigu(){
        $.messager.progress();
        $('#reptile_gyg').form('submit', {
            url: 'getXunJiaXinXi',
            onSubmit: function(data){
                console.log(data)
            },
            success:function(data){
                $.messager.progress('close');
                var data = eval('(' + data + ')');
                if(data.success){
                    $('#d_money').html(data.data.diYaDanJia).css('color','red').css("font-weigh","bold");
                    $('#z_money').html(data.data.diYaZongJia).css('color','red').css("font-weigh","bold");
                    $('#div_money').show();
                }else{
                    $('#f_msg').html('暂无小区评估价格').css('color','blue').css("font-weigh","bold");
                    // $('#z_money').html(data.data.diYaZongJia).css('color','red').css("font-weigh","bold");
                    $('#f_msg').show();
                }

            }
        });
    }
    function nearby(communityId,districtName,residentialName) {
        $('#nearby').window('open');
        $.ajax({
            type: "POST",
            url: 'getareaid',
            data: {communityId,districtName,residentialName},
            dataType: "json",
            success(data){
                $('#near').datagrid({
                    url:'getNearby',
                    queryParams: {
                        communityId: data.data.comId,
                    },
                    // pagination: true,
                    // rownumbers: true,
                    columns:[[
                        {field:'distance',title:'距离（米）',width:'30%',align:'center'},
                        {field:'residentialAreaName',title:'小区',width:'30%',align:'center'},
                        {field:'unitPrice',title:'均价（元/平方米）',width:'30%',align:'center'},
                    ]]
                });
            }
        });
    }

    function gujia(communityId,districtName,residentialName){
        $('#f_msg').hide();
        $('#f_msg').html();
        $('#div_money').hide();
        $('#reptile_gyg').form('clear');
        $.ajax({
            type: "POST",
            url: 'getareaid',//获取小区ID
            data: {communityId,districtName,residentialName},
            dataType: "json",
            success(data){
                $('#xiaoquID').attr('value',data.data.comId);
                $('#unltNo').combobox({
                    url:'getld',
                    valueField:'id',
                    textField:'name',
                    queryParams: { comId: data.data.comId, communityId:communityId}
                });
                $('#houseNo').combobox({
                    url:'gethome',
                    valueField:'id',
                    textField:'name',
                    queryParams: { comId: data.data.comId, communityId:communityId}
                });
            }
        });
        $('#residentialName').val(residentialName);
        $('#house').window('open');
    }


    //图片点击放大
    $('.example img').zoomify();
</script>

</body>