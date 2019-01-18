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
    <div id="buildinglistbox">
        <div id="submit-dlg" style="width:1200px;max-height: 700px;padding:10px;top: 10px;" class="easyui-dialog" closed="true" buttons="submit-dlg-buttons" data-options="modal:true">
            <div id="edit-box-content"></div>
        </div>
        <div id="unit-dlg" style="width:1200px;max-height: 700px;padding:10px;top: 10px;" class="easyui-dialog" closed="true" buttons="submit-dlg-buttons" data-options="modal:true">
            <div id="unit-box-content"></div>
        </div>
        <div id="form-dlg" style="width:1200px;max-height: 700px;padding:10px;top: 10px;" class="easyui-dialog" closed="true" buttons="submit-dlg-buttons" data-options="modal:true">
            <div id="form-box-content">
                <div>
                    <input type="text" readonly="readonly" style="width: 80%;display: block;" id="fulladdress" name="address" value="">
                </div>
                <span style="display: inline-block;margin-top: 10px;">
                    房屋面积: <input id="area" type="tel" name="area" value="0" placeholder="房屋面积">
                </span>
                <span id="getprice" style="display: inline-block;padding: 5px;margin: 5px;border:1px solid #ccc;">提交</span>
            </div>
        </div>
        <div id="report-dlg" style="width:1200px;max-height: 700px;padding:10px;top: 10px;" class="easyui-dialog" closed="true" buttons="submit-dlg-buttons" data-options="modal:true">
            <div id="report-box-content"></div>
        </div>
        <div id="village-dlg" style="width:1200px;max-height: 700px;padding:10px;top: 10px;" class="easyui-dialog" closed="true" buttons="submit-dlg-buttons" data-options="modal:true">
            <div id="village-box-content"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
    var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
    var globalData = [];//用于装载全局js变量
    var phpData = [];//php返回的内容
    var enum_array = {
        'buildYear':'建成时间',
        'dyPrice':'抵押评估单价',
        'maxDyPrice':'抵押评估单价上限',
        'minDyPrice':'押评估单价下限',
        'totalDyPrice':'抵押评估总价',
        'totalprice':'市场评估总价',
        'price':'市场评估单价',
        'rent':'租金',
        'roomType':'居室类型（1：一居室2：二居室）'
    };
    var enum_postfix = {
        'dyPrice':'元/㎡',
        'maxDyPrice':'元/㎡',
        'minDyPrice':'元/㎡',
        'buildYear': '年',
        'totalDyPrice':'万元',
        'totalprice':'万元',
        'price':'元/㎡',
        'rent':'',
        'roomType':''
    }
    var enum_village = {
        'address':'小区地址',
        'buildingArea':'建筑面积',
        'buildingCategory':'建筑类别',
        'communityName':'小区名称',
        'completionDate':'竣工时间',
        'developerCompany':'开发商',
        'districtName':'行政区',
        'greeningRate':'绿化率',
        'houseBuildingCount':'楼栋总数',
        'housingCount':'总户数',
        'landArea':'土地面积',
        'landProperties':'土地权属性质',
        'landUseType':'土地用途',
        'landUseYearsLimit':'土地使用年限（产权年限）',
        'managementCompany':'物业公司',
        'managementFees':'物业费',
        'parkingSpaceInfo':'车位状况',
        'sellDate':'开盘时间',
        'wayObtainLand':'土地取得方式',
        'xAmap':'高德经度',
        'xBaidu':'百度经度',
        'yAmap':'高德纬度',
        'yBaidu':'百度纬度',
    };
    var fulladdress_c = '';
    var fulladdress_b = '';
    var fulladdress_h = '';

    var select_cid = '';
    var select_bid = '';
    var select_hid = '';

    String.prototype.replaceAll = function(f,e){//吧f替换成e
        var reg = new RegExp(f,"g"); //创建正则RegExp对象   
        return this.replace(reg,e); 
    }

    globalData['listconfig'] = [
        [
            {field: 'address', title: '地址', width: 200, align:'center'},
            {field: 'similarWord', title: '相似小区名称', width: 160, align:'center'},
            {field: 'districtFullName', title: '区域', width: 160,  align:'center'},
            {field: 'residentialareaName', title: '住宅小区名称', width: 200,  align:'center'},
            {field: 'op', title: '操作', width: 160,  align:'center'},
        ]
    ];

    $("#listbox").prepend(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/datagrid_basic', 
            {datagrid_id: 'tt', target: 'find'}
        )
    );

    $("#listselect").append(
        nunjucks.render(
            AJAXBASEURL + tplPath + 'v001/select_basic', 
            {
                selectConfig: [['地址', 'find-address', 'find-address']],
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
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            html = '<a data-id="'+ id +'" data-name="'+ name +'" class="preview btn btn-primary btn-xs p310" href="javascript:void(0)">选择</a> <a data-id="'+ id +'" class="village btn btn-primary btn-xs p310" href="javascript:void(0)">小区信息</a>';
            html += html_tmp.join('&nbsp;&nbsp');
            $(this).parent().append(html);
        });
    }

    //点击查询
    $("#query").click(function(){
        var address = $("#find-address").val();
        if(address == false){ address = 'err';}
        $('#tt').datagrid('load', {address:address});
    }); 

    $('#listbox').on('click', '.preview', function(){
        var communityId = $(this).attr('data-id');
        fulladdress_c = $(this).attr('data-name');
        select_cid = communityId;
        $.post(
            './building_find',
            {communityId: communityId},
            function (response) {
                response[0].guid

                var html = '';
                response = JSON.parse(response);
                for (item in response) {
                    html += '<div style="border:1px #CCC solid;margin-bottom:10px;max-width:80%;padding:5px;"><span style="display:inline-block;width:30%;text-align:center;">' + response[item].name + '</span> <span style="display:none;">' + response[item].guid + '</span>' + '<a data-id="'+ response[item].guid +'" data-cid="' + communityId + '" data-defid="'+ response[item].defid +'" data-name="'+ response[item].name +'" class="preview2 btn btn-primary btn-xs p310" href="javascript:void(0)">选择</a>' + '</div>';
                }
                html += '</div></div>'
                $('#edit-box-content').html(html);
                $('#unit-dlg').dialog('close');
                $('#submit-dlg').dialog('open').dialog('setTitle', '楼幢信息');
            }
        );
    });

    $('#submit-dlg').on('click', '.preview2', function(){
        fulladdress_b = $(this).attr('data-name');
        select_bid = $(this).attr('data-id');
        $.post(
            './unit_find',
            {communityId: $(this).attr('data-cid'), buildingId: $(this).attr('data-id'), defid: $(this).attr('data-defid')},
            function (response) {
                var html = '';
                response = JSON.parse(response);
                console.log(response);
                for (item in response) {
                    html += '<div style="border:1px #CCC solid;margin-bottom:10px;max-width:80%;padding:5px;"><span style="display:inline-block;width:30%;text-align:center;">' + response[item].name + ' </span> <span style="display:none;">' + response[item].guid + '</span>' + '<a data-id="'+ response[item].guid +'" data-name="'+ response[item].name +'" class="preview3 btn btn-primary btn-xs p310" href="javascript:void(0)">选择</a>' + '</div>';
                }
                html += '</div></div>'
                $('#unit-box-content').html(html);
                // $('#submit-dlg').dialog('close');
                $('#unit-dlg').dialog('open').dialog('setTitle', '房间信息');
            }
        );
    });

    $('#unit-dlg').on('click', '.preview3', function(){
        fulladdress_h = $(this).attr('data-name');
        select_hid = $(this).attr('data-id');
        $('#fulladdress').val(fulladdress_c + ' ' + fulladdress_b + ' ' + fulladdress_h);
        $('#form-dlg').dialog('open').dialog('setTitle', '房屋面积');
    });

    $('#getprice').click(function() {
        var area = $('#area').val();
        $.post(
            './getprice',
            {
                'communityId': select_cid,
                'buildingArea': area,
                'buildingId': select_bid,
                'houseId': select_hid
            },
            function (response) {
                console.log(response);
                response = JSON.parse(response);
                var html = '';
                html += '<div style="border:1px #CCC solid;margin-bottom:10px;padding:5px;">';
                html += '<div style="margin-bottom:10px;"><span style="display:inline-block;width:240px;text-align:right;">地址: </span><span>'+fulladdress_c + ' ' + fulladdress_b + ' ' + fulladdress_h+'</span></div>'
                for (item in response) {
                    html += '<span style="display:inline-block;width:30%;margin-right:5px;margin-bottom:10px;height:30px;line-height:30px;"><span style="display:inline-block;width:240px;text-align:right;">' + enum_array[item] + ':</span>' + response[item] + enum_postfix[item] +'</span>'
                }
                html += '</div>';
                $('#report-box-content').html(html);
                $('#report-dlg').dialog('open').dialog('setTitle', '评估报告');
            }
        );
    });

    $('#listbox').on('click', '.village', function(){
        var communityId = $(this).attr('data-id');
        $.post(
            './getvillageinfo',
            {communityId:communityId},
            function (response) {
                console.log(response);
                response = JSON.parse(response);
                var html = '';
                html += '<div>';
                for (item in response) {
                    html += '<span style="display:inline-block;width:30%;margin-right:5px;border:1px solid #CCC;margin-bottom:5px;height:30px;line-height:30px;"><span  style="display:inline-block;width:160px;">' + enum_village[item] + ':</span><span style="display:inline-block;">';
                    if (null != response[item]) {
                        html += response[item];
                    } else {
                        html += '未知';
                    }
                    html += '</span></span>';
                }
                html += '</div>';
                $('#village-box-content').html(html);
                $('#village-dlg').dialog('open').dialog('setTitle', '小区信息');
            }
        );
    });

</script>

<?= tpl('admin_foot') ?>
</body>
</html>