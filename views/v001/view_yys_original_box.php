<style type="text/css">
    .sp {
        width: 33.1%;
        border:1px solid #ccc;
        display: inline-block;
        padding: 3px;
        margin-top: 2px;
        margin-bottom: 2px;
    }
    .download {
        display: inline-block;
        width: 100px;
        border: 1px #0a65e1 solid;
        height: 30px;
        line-height: 30px;
        background-color: #0a65e1;
        color: white;
        font-size: 16px;
        text-align: center;
    }
    .bgdata {
        background-color: #0a65e1;
        color: white;
        text-align:center;
        font-size: 18px;
    }
    .openorstop {
        display: block;
        width: 200px;
        height: 30px;
        border-radius:10px;
        font-size: 16px;
        background-color: #0a65e1;
        color: white;
        text-align:center;
        margin-top: 4px;
    }
    .xiangq {
        width: 33.1%;
        height: 200px;
        border:1px solid #ccc;
        display: inline-block;
        padding: 3px;
        margin-top: 2px;
        margin-bottom: 2px;
    }
    .userdata {
        background-color: #f1f1f1;
        margin-left: 2px;
        font-size: 14px;
    }
    .borderys {
        border: 1px solid #0a65e1;
    }
</style>
<script src="/assets/lib/js/wordjs/FileSaver.js"></script>
<script src="/assets/lib/js/wordjs/jquery.wordexport.js"></script>
<div class="download" id="dl">下载</div>
<div id="report-box">
    {% set line_size = 3 %}
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div>手机号：</div><div>{{data.mobile}}</div>
        <div id="report-name" style="display: none;">{{data.mobile}}</div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="report">报告数据</div>
        <div class="borderys"">
            <div class="sp">机主姓名:{{data.name}}</div>
            <div class="sp">机主身份证:{{data.idcard}}</div>
            <div class="sp">运营商标识:{{data.carrier}}</div>
            <div class="sp">本机号码归属省份:{{data.province}}</div>
            <div class="sp">入网时间:{{data.open_time}}</div>
            <div class="sp">用户星级:{{data.level}}</div>
            <div class="sp">本机号码当前套餐名称:{{data.package_name}}</div>
            <div class="sp">本机实名状态:{{data.reliability}}</div>
            <div class="sp">本机号码当前可用余额（单位: 分）:{{data.available_balance}}</div>
            <div class="sp">状态描述:{{data.message}}</div>
            <div class="sp">本机号码状态:{{data.state}}</div>
            <div class="sp">机主地址:{{data.address}}</div>
            <div class="sp">机主电子邮箱:{{data.email}}</div>
            <div class="sp">国际移动用户识别码:{{data.imsi}}</div>
            <div class="sp">本机号码当前实际余额:{{data.real_balance}}</div>
            <div class="sp">数据获取时间:{{data.last_modify_time}}</div>
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="userdata" data-key="user_basic"><b>套餐 数据</b></div>
        <div class="borderys"">
            {% for item in data.packages %}
                <div class="sp">账单起始日 ： {{item['bill_start_date']}}</div>
                <div class="sp">账单结束日 ： {{item['bill_end_date']}}</div>
                {% set content = item.items %}
                {% for value in content %}
                    <div style="border: 1px #CCC solid;margin: 5px 2px;">
                        <div class="sp">套餐项目名称 ： {{value['item']}}</div>
                        <div class="sp">套餐项目总量 ： {{value['total']}}</div>
                        <div class="sp">套餐项目已使用量 ： {{value['used']}}</div>
                        <div class="sp">套餐项目单位：语音-分; 流量-KB; 短/彩信-条 ： {{value['unit']}}</div>
                    </div>
                {% endfor %}
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="cell_phone">亲情网数据</div>
        <div class="borderys"">
            {% for item in data.families %}
                <div class="sp">亲情网编号 ： {{item['family_num']}}</div>
                {% set content = item.items %}
                {% for value in content %}
                    <div style="border: 1px #CCC solid;margin: 5px 2px;">
                        <div class="sp">亲情网手机号码 ： {{value['long_number']}}</div>
                        <div class="sp">短号 ： {{value['short_number']}}</div>
                        <div class="sp">成员类型. MASTER-家长, MEMBER-成员 ： {{value['member_type']}}</div>
                        <div class="sp">加入日期： {{value['join_date']}}</div>
                        <div class="sp">失效日期： {{value['expire_date']}}</div>
                    </div>
                {% endfor %}
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="basic_check_items">充值记录 数据</div>
        <div class="borderys">
            {% for item in data.recharges %}
            <div>
                <div class="sp">详情标识，标识唯一一条记录 ： {{item['details_id']}}</div>
                <div class="sp">充值时间 ： {{item['recharge_time']}}</div>
                <div class="sp">充值金额(单位: 分) ： {{item['amount']}}</div>
                <div class="sp">充值方式 ： {{item['type']}}</div>
            </div>
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="behavior_check">账单信息</div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        <div class="borderys">
            {% for item in data.bills %}
            <div>
                <div class="sp">账单月 ： {{item['bill_month']}}</div>
                <div class="sp">账期起始日期 ： {{item['bill_start_date']}}</div>
                <div class="sp">账期结束日期 ： {{item['bill_end_date']}}</div>
                <div class="sp">本机号码套餐及固定费 ： {{item['base_fee']}}</div>
                <div class="sp">增值业务费 ： {{item['extra_service_fee']}}</div>
                <div class="sp">语音费 ： {{item['voice_fee']}}</div>
                <div class="sp">短彩信费 ： {{item['sms_fee']}}</div>
                <div class="sp">网络流量费 ： {{item['web_fee']}}</div>
                <div class="sp">其它费用 ： {{item['extra_fee']}}</div>
                <div class="sp">本月总费用 ： {{item['total_fee']}}</div>
                <div class="sp">优惠费 ： {{item['discount']}}</div>
                <div class="sp">其它优惠 ： {{item['extra_discount']}}</div>
                <div class="sp">个人实际费用 ： {{item['actual_fee']}}</div>
                <div class="sp">本期已付费用 ： {{item['paid_fee']}}</div>
                <div class="sp">本期未付费用 ： {{item['unpaid_fee']}}</div>
                <div class="sp">本期可用积分 ： {{item['point']}}</div>
                <div class="sp">上期可用积分 ： {{item['last_point']}}</div>
                <div class="sp">本手机关联号码, 多个手机号以逗号分隔 ： {{item['related_mobiles']}}</div>
                <div class="sp">备注 ： {{item['notes']}}</div>
            </div>
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="friend_circle summary"> 语音详情 数据</div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        <div class="borderys">
            {% for item in data.calls %}
            <div>
                <div class="sp">语音详情月份 ： {{item['bill_month']}}</div>
                <div class="sp">该月详情记录总数 ： {{item['total_size']}}</div>
                {% set content = item['items'] %}
                {% for temp in content %}
                <div>
                    <div class="sp">详情唯一标识 ： {{temp['details_id']}}</div>
                    <div class="sp">通话时间 ： {{temp['time']}}</div>
                    <div class="sp">对方通话号码 ： {{temp['peer_number']}}</div>
                    <div class="sp">通话地(自己的) ： {{temp['location']}}</div>
                    <div class="sp">通话地类型 ： {{temp['location_type']}}</div>
                    <div class="sp">通话时长(单位:秒) ： {{temp['duration']}}</div>
                    <div class="sp">呼叫类型. DIAL-主叫; DIALED-被叫 ： {{temp['dial_type']}}</div>
                    <div class="sp">通话费(单位:分) ： {{temp['fee']}}</div>
                </div>
                {% endfor %}
            </div>
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="friend_circle peer_num_top_list">短信详情  数据</div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        <div class="borderys">
            {% for item in data.smses %}
            <div>
                <div class="sp">详情月份 ： {{item['bill_month']}}</div>
                <div class="sp">记录总数 ： {{item['total_size']}}</div>
                {% set content = item['items'] %}
                {% for temp in content %}
                <div>
                    <div class="sp">详情唯一标识 ： {{temp['details_id']}}</div>
                    <div class="sp">通话时间 ： {{temp['time']}}</div>
                    <div class="sp">对方通话号码 ： {{temp['peer_number']}}</div>
                    <div class="sp">通话地(自己的) ： {{temp['location']}}</div>
                    <div class="sp">SEND-发送; RECEIVE-收取 ： {{temp['send_type']}}</div>
                    <div class="sp">SMS-短信; MMS-彩信 ： {{temp['msg_type']}}</div>
                    <div class="sp">业务名称 ： {{temp['service_name']}}</div>
                    <div class="sp">通话费(单位:分) ： {{temp['fee']}}</div>
                </div>
                {% endfor %}
            </div>
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="friend_circle location_top_list">流量详情 数据</div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        <div class="borderys">
            {% for item in data.nets %}
            <div>
                <div class="sp">详情月份 ： {{item['bill_month']}}</div>
                <div class="sp">记录总数 ： {{item['total_size']}}</div>
                {% set content = item['items'] %}
                {% for temp in content %}
                <div>
                    <div class="sp">详情标识 ： {{temp['details_id']}}</div>
                    <div class="sp">流量使用时间 ： {{temp['time']}}</div>
                    <div class="sp">流量使用时长(单位:秒) ： {{temp['duration']}}</div>
                    <div class="sp">流量使用地点 ： {{temp['location']}}</div>
                    <div class="sp">流量使用量，单位:KB ： {{temp['subflow']}}</div>
                    <div class="sp">网络类型 ： {{temp['net_type']}}</div>
                    <div class="sp">业务名称 ： {{temp['service_name']}}</div>
                    <div class="sp">通话费(单位:分) ： {{temp['fee']}}</div>
                </div>
                {% endfor %}
            </div>
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="cell_behavior behavior">语音月份信息 数据</div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        <div class="borderys">
            {% for item in data.month_info %}
            <div>
                <div class="sp">用户手机号码 ： {{item['phone_no']}}</div>
                <div class="sp">有通话记录月份数 ： {{item['month_count']}}</div>
                <div class="sp">通话记录获取失败月份数  ： {{item['miss_month_count']}}</div>
                <div class="sp">无通话记录月份数 ： {{item['no_call_month']}}</div>
                <div class="sp">调用接口传入user_id ： {{item['user_id']}}</div>
                <div class="sp">通话记录月份采集结果集合 ： {{item['month_list']}}</div>
            </div>
            {% endfor %}
        </div>
    </div>
</div>

<script type="text/javascript">
    //words  
  $('#dl').click(function() {
       $("#report-box").wordExport('运营商报告-'+$('#report-name').text());
   }); 


   //excel
// $('#dl').click(function() {
// 	excelOut();
// });
// function excelOut(){
	
// 	//var oHtml = $("#report-box").html();
// 	var oHtml = "<table>";
// 	oHtml +="<tr><td>{{user_info_check_map['contacts_router_ratio']}}</td><td>出行数据分析 数据1</td></tr>";
// 	oHtml +="<tr><td>{{user_info_check_map['contacts_router_ratio']}}</td><td>出行数据分析 数据2</td></tr>";
// 	oHtml +="</table>";
// 	var excelHtml = "<html><head><meta charset='utf-8' /><style>  ";
// 	excelHtml += " table {border-collapse: collapse;}";
// 	excelHtml += "   th{height: 50px;font-size: 24px;font-family: '微软雅黑';font-weight: 700;}";
// 	excelHtml += "  tr th {border: 1px #cccccc solid;height: 40px;background: #efefef;}";
// 	excelHtml += "  tr td {padding: 0 40px;border: 1px #ccc solid;height: 40px;text-align: center;}";
// 	excelHtml += "  td {font-size: 20px;font-weight: 700;}";
// 	excelHtml += "</style></head><body>"+oHtml+"</body></html>";
// 	var debug = {hello: "world"};
// 	var blob = new Blob([JSON.stringify(debug, null, 2)],{type : 'application/json'});
// 	var excelBlob = new Blob([excelHtml], {type: 'application/text/xml'});
// 	var oA = document.createElement('a');
// 	// 利用URL.createObjectURL()方法为a元素生成blob URL
// 	oA.href = URL.createObjectURL(excelBlob);
// 	// 给文件命名
// 	oA.download = '运营商报告-' + $('#report-name').text() + '.xls';
// 	// 模拟点击
// 	oA.click();
// }
 

</script>
