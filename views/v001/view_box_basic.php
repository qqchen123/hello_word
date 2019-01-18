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
        {% set report_map = {'task_id':'编号', 'data_type':'报告类型', 'source_name_zh':'数据来源', 'data_gain_time':'数据获取时间', 'update_time':'报告时间', 'version':'报告版本'} %}
        {% set cnt = 0 %}
        <div class="borderys"">
            {% for item in data.data.report %}
                {% set cnt = cnt + 1 %}
                {% if 'source_name' != item['key'] %}
                    <div class="sp">{{report_map[item['key']]}} ： {{item['value']}}</div>
                {% endif %}
                {% if 0 == cnt%line_size %} {% endif %}
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="userdata" data-key="user_basic"><b>用户基础 数据</b></div>
        {% set user_baisc_map = {'name':'姓名', 'id_card':'身份证号', 'gender':'性别', 'age':'年龄', 'constellation':'星座', 'native_place':'籍贯', 'province':'省份', 'city':'城市', 'region':'地区'} %}
        {% set cnt = 0 %}
        <div class="borderys"">
            {% for item in data.data.user_basic %}
                {% set cnt = cnt + 1 %}
                <div class="sp">{{user_baisc_map[item['key']]}} ： {{item['value']}}</div>
                {% if 0 == cnt%line_size %} {% endif %}
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="cell_phone">手机号数据</div>
        {% set cell_phone_map = {'mobile':'手机号', 'carrier_name':'认证姓名', 'carrier_idcard':'认证身份证号', 'reg_time':'入网时间', 'in_time':'网龄', 'email':'电子邮箱', 'address':'地址', 'reliability':'实名认证情况', 'phone_attribution':'电话归属地', 'package_name':'套餐名称', 'live_address':'活跃地区', 'available_balance':'可用余额(分)', 'bill_certification_day':'账单日期'} %}
        {% set cnt = 0 %}
        <div class="borderys"">
            {% for item in data.data.cell_phone %}
                {% set cnt = cnt + 1 %}
                <div class="sp">{{cell_phone_map[item['key']]}} ： {{item['value']}}</div>
                {% if 0 == cnt%line_size %} {% endif %}
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="basic_check_items">信息校验 检查项 数据</div>
        {% set basic_check_items_map = {'idcard_check':'身份证号码有效性', 'email_check':'邮箱有效性', 'address_check':'通讯地址有效性', 'call_data_check':'通话记录完整性', 'idcard_match':'身份证号码是否与运营商数据匹配', 'name_match':'姓名是否与运营商数据匹配', 'is_name_and_idcard_in_court_black':'申请人姓名+身份证号码是否出现在法院黑名单', 'is_name_and_idcard_in_finance_black':'申请人姓名+身份证号码是否出现在金融机构黑名单', 
        'is_name_and_mobile_in_finance_black':'申请人姓名+手机号码是否出现在金融机构黑名单', 'mobile_silence_3m':'手机号3个月内号码沉默度(满分10分)', 'mobile_silence_6m':'手机号6个月内号码沉默度(满分10分)', 'arrearage_risk_3m':'3个月内欠费风险度(满分10分)', 'arrearage_risk_6m':'6个月内欠费风险度(满分10分)', 'binding_risk':'亲情网风险度(满分10分)'} %}
        {% set cnt = 0 %}
        <div class="borderys"">
            {% for item in data.data.basic_check_items %}
                {% set cnt = cnt + 1 %}
                <div class="sp">{{basic_check_items_map[item['check_item']]}} ： {{item['result']}}</div>
                {% if 0 == cnt%line_size %} <br/> {% endif %}
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="behavior_check"> 社交分析摘要数据</div>
        {% set cnt = 0 %}
        <div class="borderys">
          <!--   {% set array = [{'check_point':'phone_used_time', 'class':'sp'},{'check_point':'regular_circle', 'class':'sp'},{'check_point':'phone_power_off', 'class':'sp'},{'check_point':'contact_each_other', 'class':'sp'},{'check_point':'contact_macao', 'class':'sp'}] %}  -->

             {% for temp in array %}
                {% for item in data.data.behavior_check %}
                    {% if temp['check_point'] == item['check_point'] %}
                        <div class="{{temp['class']}}" data-key="{{item['check_point']}}">{{item['check_point_cn']}} ： {{item['result']}} [详细： {{item['evidence']}}  分数：{{item['score']}} ]</div>
                    {% endif %}
                {% endfor %}
            {% endfor %} 

            {% for item in data.data.behavior_check %}
                {% set cnt = cnt + 1 %}
                {% if item['check_point_cn'] == '手机静默情况' %}
                    <div class="sp" data-key="{{item['check_point']}}">{{item['check_point_cn']}} ： {{item['result']}} [详细： {{item['evidence']}}  分数：{{item['score']}} ]</div>
                {% endif %}
                <div class="sp" data-key="{{item['check_point']}}">{{item['check_point_cn']}} ： {{item['result']}} [详细： {{item['evidence']}}  分数：{{item['score']}} ]</div>
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="friend_circle summary"> 
        朋友圈总结 数据
        </div>
        {% set friend_circle_summary_map = {'friend_num_3m':'3个月内朋友圈大小', 'good_friend_num_3m':'3个月内朋友圈亲密度', 'friend_city_center_3m':'3个月内朋友圈中心地', 'is_city_match_friend_city_center_3m':'3个月内朋友圈是否在本地','inter_peer_num_3m':'3个月内互通电话的号码数目', 'friend_num_6m':'6个月内朋友圈大小', 'good_friend_num_6m':'6个月内朋友圈亲密度', 'friend_city_center_6m':'6个月内朋友圈中心地', 'is_city_match_friend_city_center_6m':'6个月内朋友圈是否在本地', 'inter_peer_num_6m':'6个月内互通电话的号码数目'} %}
        {% set cnt = 0 %}
        <div class="borderys"">
            {% for item in data.data.friend_circle.summary %}
                {% set cnt = cnt + 1 %}
                <div class="sp">{{friend_circle_summary_map[item['key']]}} ： {{item['value']}}</div>
                {% if 0 == cnt%line_size %} <br/> {% endif %}
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="friend_circle peer_num_top_list">
             朋友圈 peer_num_top_list  数据
        </div>
        {% set top_item_map = {'peer_number':'号码', 'peer_num_loc':'归属地', 'group_name':'号码类型', 'company_name':'号码标识', 'call_cnt':'通话次数', 'call_time':'通话时间(秒)', 'dial_cnt':'主叫次数', 'dial_time':'主叫时长(秒)', 'dialed_cnt':'被叫次数', 'dialed_time':'被叫时长(秒)'} %}
        {% set cnt = 0 %}
        {% for item in data.data.friend_circle.peer_num_top_list %}
            <div style="border:1px black solid;margin-bottom: 10px;">
                {% set cnt = cnt + 1 %}
                <div class="userdata">
                    子项：{% if 'peer_num_top3_3m' == item['key'] %} 联系人Top3 (近3月通话次数降序) {% elseif 'peer_num_top3_6m' == item['key'] %} 联系人Top3 (近6月通话次数降序) {% else %} {{item['key']}} {% endif %}
                </div>
                {% set cnt_top_item = 0 %}
                {% for content in item['top_item'] %}
                    <div class="borderys">
                        {% set cnt_top_item = cnt_top_item + 1 %}
                        <div class="sp">{{top_item_map['peer_number']}} ： {{content['peer_number']}}</div>
                        <div class="sp">{{top_item_map['peer_num_loc']}} ： {{content['peer_num_loc']}}</div>
                        <div class="sp">{{top_item_map['group_name']}} ： {{content['group_name']}}</div>
                        <div class="sp">{{top_item_map['company_name']}} ： {{content['company_name']}}</div>
                        <div class="sp">{{top_item_map['call_cnt']}} ： {{content['call_cnt']}}</div>
                        <div class="sp">{{top_item_map['call_time']}} ： {{content['call_time']}}</div>
                        <div class="sp">{{top_item_map['dial_cnt']}} ： {{content['dial_cnt']}}</div>
                        <div class="sp">{{top_item_map['dialed_cnt']}} ： {{content['dialed_cnt']}}</div>
                        <div class="sp">{{top_item_map['dial_time']}} ： {{content['dial_time']}}</div>
                        <div class="sp">{{top_item_map['dialed_time']}} ： {{content['dialed_time']}}</div>
                        {% if 0 == cnt_top_item%line_size %} {% endif %}
                    </div>
                {% endfor %}
            </div>
            {% if 0 == cnt%line_size %} <br/> {% endif %}
        {% endfor %}
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="friend_circle location_top_list">
             朋友圈 通话地区 数据
        </div>
        {% set location_top_list_map = {'location':'地址', 'peer_number_cnt':'号码数量','call_cnt':'通话次数', 'call_time':'通话时间(秒)', 'dial_cnt':'主叫次数', 'dial_time':'主叫时长(秒)', 'dialed_cnt':'被叫次数', 'dialed_time':'被叫时长(秒)'} %}
        {% set cnt = 0 %}
        {% for item in data.data.friend_circle.location_top_list %}
            <div style="border:1px #0a65e1 solid;margin-bottom: 10px;">
                {% set cnt = cnt + 1 %}
                <div class="userdata">子项：{% if 'location_top3_3m' == item['key'] %}联系人号码归属地Top3 (近3月通话次数降序){% elseif 'location_top3_6m' == item['key'] %}联系人号码归属地Top3 (近6月通话次数降序) {% else %} {{item['key']}} {% endif %}</div>
                {% set cnt_top_item = 0 %}
                {% for content in item['top_item'] %}
                    <div class="borderys">
                        {% set cnt_top_item = cnt_top_item + 1 %}
                        <div class="sp">{{location_top_list_map['location']}} ： {{content['location']}}</div>
                        <div class="sp">{{location_top_list_map['peer_number_cnt']}} ： {{content['peer_number_cnt']}}</div>
                        <div class="sp">{{location_top_list_map['call_cnt']}} ： {{content['call_cnt']}}</div>
                        <div class="sp">{{location_top_list_map['call_time']}} ： {{content['call_time']}}</div>
                        <div class="sp">{{location_top_list_map['dial_cnt']}} ： {{content['dial_cnt']}}</div>
                        <div class="sp">{{location_top_list_map['dialed_cnt']}} ： {{content['dialed_cnt']}}</div>
                        <div class="sp">{{location_top_list_map['dial_time']}} ： {{content['dial_time']}}</div>
                        <div class="sp">{{location_top_list_map['dialed_time']}} ： {{content['dialed_time']}}</div>
                        {% if 0 == cnt_top_item%line_size %} <br/> {% endif %}
                    </div>
                {% endfor %}
            </div>
            {% if 0 == cnt%line_size %} <br/> {% endif %}
        {% endfor %}
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="cell_behavior behavior">
        运营商消费数据 数据
        </div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        <div>
            <div>手机号:{{data.data.cell_behavior[0].phone_num}}行为数据：</div>
            {% set cell_behavio_behavior_map = {'call_cnt':'通话次数', 'call_time':'通话时间(秒)', 'dial_cnt':'主叫次数', 'dial_time':'主叫时长(秒)', 'dialed_cnt':'被叫次数', 'dialed_time':'被叫时长(秒)', 'sms_cnt':'短信数量', 'cell_phone_num':'号码', 'net_flow':'流量使用', 'total_amount':'话费消费(分)', 'cell_mth':'月份', 'cell_loc':'归属地', 'cell_operator_zh':'运营商', 'rechange_cnt':'充值次数', 'rechange_amount':'充值总额(分)'} %}
            {% for item in data.data.cell_behavior[0].behavior %}
                <div class="borderys"margin-bottom: 10px;" >
                    <div class="sp">{{cell_behavio_behavior_map['sms_cnt']}} ： {{item['sms_cnt']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['cell_phone_num']}} ： {{item['cell_phone_num']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['net_flow']}} ： {{item['net_flow']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['total_amount']}} ： {{item['total_amount']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['cell_mth']}} ： {{item['cell_mth']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['cell_loc']}} ： {{item['cell_loc']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['cell_operator_zh']}} ： {{item['cell_operator_zh']}}</div>
                    <!-- <div class="sp">{{cell_behavio_behavior_map['cell_operator']}} ： {{item['cell_operator']}}</div> -->
                    <div class="sp">{{cell_behavio_behavior_map['call_cnt']}} ： {{item['call_cnt']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['call_time']}} ： {{item['call_time']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['dial_cnt']}} ： {{item['dial_cnt']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['dial_time']}} ： {{item['dial_time']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['dialed_cnt']}} ： {{item['dialed_cnt']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['dialed_time']}} ： {{item['dialed_time']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['rechange_cnt']}} ： {{item['rechange_cnt']}}</div>
                    <div class="sp">{{cell_behavio_behavior_map['rechange_amount']}} ： {{item['rechange_amount']}}</div>
                </div>
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-ke="call_contact_detail">
            通话记录 数据
        </div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        {% set call_contact_detail_map = {'city':'归属地', 'peer_num':'号码', 'p_relation':'联系人关系', 'group_name':'号码类型', 'company_name':'号码标识', 'call_cnt_1w':'近一周通话次数', 'call_cnt_1m':'近一月通话次数', 'call_cnt_3m':'近三月通话次数', 'call_cnt_6m':'近六月通话次数', 'call_time_3m':'近三月通话时长(秒)', 'call_time_6m':'近六月通话时长(秒)', 'dial_cnt_3m':'近三月主叫次数', 'dial_cnt_6m':'近六月主叫次数', 'dial_time_3m':'近3个月主叫时长(秒)', 'dial_time_6m':'近6个月主叫时长', 'dialed_cnt_3m':'近3个月被叫次数', 'dialed_cnt_6m':'近6个月被叫次数', 'dialed_time_3m':'近3个月被叫时长(秒)', 'dialed_time_6m':'近6个月被叫时长(秒)', 'call_cnt_morning_3m':'近三月早上（5:30-11:30）通话次数', 'call_cnt_morning_6m':'近六月早上（5:30-11:30）通话次数', 'call_cnt_noon_3m':'近三月中午（11:30-13:30）通话次数', 'call_cnt_noon_6m':'近六月中午（11:30-13:30）通话次数', 'call_cnt_afternoon_3m':'近三月下午（13:30-17:30）通话次数', 'call_cnt_afternoon_6m':'近六月下午（13:30-17:30）通话次数', 'call_cnt_evening_3m':'近三月晚上（17:30-23:30）通话次数', 'call_cnt_evening_6m':'近六月晚上（17:30-23:30）通话次数', 'call_cnt_night_3m':'近三月凌晨（23:30-5:30）通话次数', 'call_cnt_night_6m':'近六月凌晨（23:30-5:30）通话次数', 'call_cnt_weekday_3m':'近三月工作日通话次数', 'call_cnt_weekday_6m':'近六月工作日通话次数', 'call_cnt_weekend_3m':'近三月周末通话次数', 'call_cnt_weekend_6m':'近六月周末通话次数', 'call_cnt_holiday_3m':'近三月节假日通话次数', 'call_cnt_holiday_6m':'近六月节假日通话次数', 'call_if_whole_day_3m':'近三月是否有全天联系', 'call_if_whole_day_6m':'近六月是否有全天联系', 'trans_start':'第一次通话时间', 'trans_end':'最后一次通话时间'} %}
        <div>
            {% for item in data.data.call_contact_detail %}
                <div style="border:1px solid #0a65e1;margin-bottom: 5px;">
                    <div class="sp">{{call_contact_detail_map['city']}} ： {{item['city']}}</div>
                    <div class="sp">{{call_contact_detail_map['p_relation']}} ： {{item['p_relation']}}</div>
                    <div class="sp">{{call_contact_detail_map['peer_num']}} ： {{item['peer_num']}}</div>
                    <div class="sp">{{call_contact_detail_map['group_name']}} ： {{item['group_name']}}</div>
                    <div class="sp">{{call_contact_detail_map['company_name']}} ： {{item['company_name']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_1w']}} ： {{item['call_cnt_1w']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_1m']}} ： {{item['call_cnt_1m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_3m']}} ： {{item['call_cnt_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_6m']}} ： {{item['call_cnt_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_time_3m']}} ： {{item['call_time_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_time_6m']}} ： {{item['call_time_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['dial_cnt_3m']}} ： {{item['dial_cnt_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['dial_cnt_6m']}} ： {{item['dial_cnt_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['dial_time_3m']}} ： {{item['dial_time_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['dial_time_6m']}} ： {{item['dial_time_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['dialed_cnt_3m']}} ： {{item['dialed_cnt_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['dialed_cnt_6m']}} ： {{item['dialed_cnt_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['dialed_time_3m']}} ： {{item['dialed_time_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['dialed_time_6m']}} ： {{item['dialed_time_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_morning_3m']}} ： {{item['call_cnt_morning_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_morning_6m']}} ： {{item['call_cnt_morning_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_noon_3m']}} ： {{item['call_cnt_noon_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_noon_6m']}} ： {{item['call_cnt_noon_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_afternoon_3m']}} ： {{item['call_cnt_afternoon_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_afternoon_6m']}} ： {{item['call_cnt_afternoon_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_evening_3m']}} ： {{item['call_cnt_evening_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_evening_6m']}} ： {{item['call_cnt_evening_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_night_3m']}} ： {{item['call_cnt_night_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_night_6m']}} ： {{item['call_cnt_night_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_weekday_3m']}} ： {{item['call_cnt_weekday_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_weekday_6m']}} ： {{item['call_cnt_weekday_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_weekend_3m']}} ： {{item['call_cnt_weekend_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_weekend_6m']}} ： {{item['call_cnt_weekend_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_holiday_3m']}} ： {{item['call_cnt_holiday_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_cnt_holiday_6m']}} ： {{item['call_cnt_holiday_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_if_whole_day_3m']}} ： {{item['call_if_whole_day_3m']}}</div>
                    <div class="sp">{{call_contact_detail_map['call_if_whole_day_6m']}} ： {{item['call_if_whole_day_6m']}}</div>
                    <div class="sp">{{call_contact_detail_map['trans_start']}} ： {{item['trans_start']}}</div>
                    <div class="sp">{{call_contact_detail_map['trans_end']}} ： {{item['trans_end']}}</div>
                </div>
            {% endfor %}
        </div>
    </div>
    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="sms_contact_detail"> 
            短信 数据
        </div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        {% set sms_contact_detail_map = {'peer_num':'号码', 'sms_cnt_1w':'1周内短信次数', 'sms_cnt_1m':'1个月内短信次数', 'sms_cnt_3m':'3个月内短信次数', 'sms_cnt_6m':'6个月内短信次数', 'send_cnt_3m':'3个月内发短信次数', 'send_cnt_6m':'6个月内发短信次数', 'receive_cnt_3m':'3个月内收短信次数', 'receive_cnt_6m':'6个月内收短信次数'} %}
        <div>
            {% for item in data.data.sms_contact_detail %}
                <div style="border:1px solid #0a65e1;margin-bottom: 15px;">
                    <div class="sp">{{sms_contact_detail_map['peer_num']}} ： {{item['peer_num']}}</div>
                    <div class="sp">{{sms_contact_detail_map['sms_cnt_1w']}} ： {{item['sms_cnt_1w']}}</div>
                    <div class="sp">{{sms_contact_detail_map['sms_cnt_1m']}} ： {{item['sms_cnt_1m']}}</div>
                    <div class="sp">{{sms_contact_detail_map['sms_cnt_3m']}} ： {{item['sms_cnt_3m']}}</div>
                    <div class="sp">{{sms_contact_detail_map['sms_cnt_6m']}} ： {{item['sms_cnt_6m']}}</div>
                    <div class="sp">{{sms_contact_detail_map['send_cnt_3m']}} ： {{item['send_cnt_3m']}}</div>
                    <div class="sp">{{sms_contact_detail_map['send_cnt_6m']}} ： {{item['send_cnt_6m']}}</div>
                    <div class="sp">{{sms_contact_detail_map['receive_cnt_3m']}} ： {{item['receive_cnt_3m']}}</div>
                    <div class="sp">{{sms_contact_detail_map['receive_cnt_6m']}} ： {{item['receive_cnt_6m']}}</div>
                </div>
            {% endfor %}
        </div>
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="contact_region">
        contact_region 数据
        </div>
        <div onclick="if('dn' != $(this).parent().children(':eq(2)').attr('class')) {$(this).parent().children(':eq(2)').addClass('dn');}else{$(this).parent().children(':eq(2)').removeClass('dn');}" class="openorstop">展开|收起</div>
        {% set cnt = 0 %}
        {% set region_list_map = {'region_loc':'地址', 'region_uniq_num_cnt':'通话号码数', 'region_call_cnt':'通话次数', 'region_call_time':'通话时长(秒)', 'region_dial_cnt':'主叫次数', 'region_dial_time':'主叫时长(秒)', 'region_dialed_cnt':'被叫次数', 'region_dialed_time':'被叫时常(秒)', 'region_avg_dial_time':'平均主叫时长(秒)', 'region_avg_dialed_time':'平均被叫时长(秒)', 'region_dial_cnt_pct':'主叫次数比重', 'region_dial_time_pct':'主叫时长比重', 'region_dialed_cnt_pct':'被叫次数比重', 'region_dialed_time_pct':'被叫时长比重'} %}
        <div>
            {% for item in data.data.contact_region %}
                <div style="border: 1px solid #ccc;margin-bottom: 20px;">
                <div data-key="{{item['key']}}" class="userdata">子项：{{item['desc']}}</div>
                {% for content in item['region_list'] %}
                    <div style="border: 1px solid #0a65e1;margin-bottom: 10px;">
                        <div class="sp">{{region_list_map['region_loc']}} ： {{content['region_loc']}}</div>
                        <div class="sp">{{region_list_map['region_uniq_num_cnt']}} ： {{content['region_uniq_num_cnt']}}</div>
                        <div class="sp">{{region_list_map['region_call_cnt']}} ： {{content['region_call_cnt']}}</div>
                        <div class="sp">{{region_list_map['region_call_time']}} ： {{content['region_call_time']}}</div>
                        <div class="sp">{{region_list_map['region_dial_cnt']}} ： {{content['region_dial_cnt']}}</div>
                        <div class="sp">{{region_list_map['region_dial_time']}}： {{content['region_dial_time']}}</div>
                        <div class="sp">{{region_list_map['region_dialed_cnt']}} ： {{content['region_dialed_cnt']}}</div>
                        <div class="sp">{{region_list_map['region_dialed_time']}} ： {{content['region_dialed_time']}}</div>
                        <div class="sp">{{region_list_map['region_avg_dial_time']}} ： {{content['region_avg_dial_time']}}</div>
                        <div class="sp">{{region_list_map['region_avg_dialed_time']}} ： {{content['region_avg_dialed_time']}}</div>
                        <div class="sp">{{region_list_map['region_dial_cnt_pct']}} ： {{content['region_dial_cnt_pct']}}</div>
                        <div class="sp">{{region_list_map['region_dial_time_pct']}} ： {{content['region_dial_time_pct']}}</div>
                        <div class="sp">{{region_list_map['region_dialed_cnt_pct']}} ： {{content['region_dialed_cnt_pct']}}</div>
                        <div class="sp">{{region_list_map['region_dialed_time_pct']}} ： {{content['region_dialed_time_pct']}}</div>
                    </div>
                {% endfor %}
                </div>
            {% endfor %}
        </div>
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="call_risk_analysis">
             风险统计 数据
        </div>
            {% set call_risk_analysis_map = {'analysis_item':'分析项目', 'analysis_desc':'分析描述', 'call_cnt_1m':'近1月通话次数', 'call_cnt_3m':'近3月通话次数', 'call_cnt_6m':'近6月通话次数', 'avg_call_cnt_3m':'近3个月平均通话次数', 'avg_call_cnt_6m':'近6个月平均通话次数', 'call_time_1m':'近1月通话时长(秒)', 'call_time_3m':'近3月通话时长(秒)', 'call_time_6m':'近6月通话时长(秒)', 'avg_call_time_3m':'近3月平均通话时长(秒)', 'avg_call_time_6m':'近6月平均通话时长(秒)', 'call_dial_cnt_1m':'近1月主叫次数', 'call_dial_cnt_3m':'近3月主叫次数', 'call_dial_cnt_6m':'近6月主叫次数', 'avg_call_dial_cnt_3m':'近3月平均主叫次数', 'avg_call_dial_cnt_6m':'近6月平均主叫次数', 'call_dial_time_1m':'近1月主叫时长(秒)', 'call_dial_time_3m':'近3月主叫时长(秒)', 'call_dial_time_6m':'近6月主叫时长(秒)', 'avg_call_dial_time_3m':'近3月平均主叫时长(秒)', 'avg_call_dial_time_6m':'近6月平均主叫时长(秒)', 'call_dialed_cnt_1m':'近1月被叫次数', 'call_dialed_cnt_3m':'近3月被叫次数', 'call_dialed_cnt_6m':'近6月被叫次数', 'avg_call_dialed_cnt_3m':'近3月平均被叫次数', 'avg_call_dialed_cnt_6m':'近6月平均被叫次数', 'call_dialed_time_1m':'近1月被叫时长(秒)', 'call_dialed_time_3m':'近3月被叫时长(秒)', 'call_dialed_time_6m':'近6月被叫时长(秒)', 'avg_call_dialed_time_3m':'近3月平均被叫时长(秒)', 'avg_call_dialed_time_6m':'近6月平均被叫时长(秒)'} %}
        <div>
            {% for item in data.data.call_risk_analysis %}
                <div style="border: 1px solid #CCC;margin-bottom: 5px;">
                    <div class="userdata">
                        <div class="sp">{{call_risk_analysis_map['analysis_item']}} : {{item['analysis_item']}}
                        </div>
                        <div class="sp">{{call_risk_analysis_map['analysis_desc']}} : {{item['analysis_desc']}}
                        </div>
                    </div>
                    <div class="borderys">
                    {% set content = item['analysis_point'] %}
                        <div class="sp">{{call_risk_analysis_map['call_cnt_1m']}} : {{content['call_cnt_1m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['call_cnt_3m']}} : {{content['call_cnt_3m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['call_cnt_6m']}} : {{content['call_cnt_6m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['avg_call_cnt_3m']}} : {{content['avg_call_cnt_3m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['avg_call_cnt_6m']}} : {{content['avg_call_cnt_6m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['call_time_1m']}} : {{content['call_time_1m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['call_time_3m']}} : {{content['call_time_3m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['call_time_6m']}} : {{content['call_time_6m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['avg_call_time_3m']}} : {{content['avg_call_time_3m']}}</div>
                        <div class="sp">{{call_risk_analysis_map['avg_call_time_6m']}} : {{content['avg_call_time_6m']}}</div>
                        <div class="borderys">
                            <div class="sp">子项： call_analysis_dial_point</div>
                            <div class="sp">{{call_risk_analysis_map['call_dial_cnt_1m']}} : {{content['call_analysis_dial_point']['call_dial_cnt_1m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dial_cnt_3m']}} : {{content['call_analysis_dial_point']['call_dial_cnt_3m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dial_cnt_6m']}} : {{content['call_analysis_dial_point']['call_dial_cnt_6m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['avg_call_dial_cnt_3m']}} : {{content['call_analysis_dial_point']['avg_call_dial_cnt_3m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['avg_call_dial_cnt_6m']}} : {{content['call_analysis_dial_point']['avg_call_dial_cnt_6m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dial_time_1m']}} : {{content['call_analysis_dial_point']['call_dial_time_1m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dial_time_3m']}} : {{content['call_analysis_dial_point']['call_dial_time_3m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dial_time_6m']}} : {{content['call_analysis_dial_point']['call_dial_time_6m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['avg_call_dial_time_3m']}} : {{content['call_analysis_dial_point']['avg_call_dial_time_3m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['avg_call_dial_time_6m']}} : {{content['call_analysis_dial_point']['avg_call_dial_time_6m']}}</div>
                        </div>
                        <div class="borderys">
                            <div class="sp">子项： call_analysis_dialed_point</div>
                            <div class="sp">{{call_risk_analysis_map['call_dialed_cnt_1m']}} : {{content['call_dialed_cnt_1m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dialed_cnt_3m']}} : {{content['call_dialed_cnt_3m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dialed_cnt_6m']}} : {{content['call_dialed_cnt_6m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['avg_call_dialed_cnt_3m']}} : {{content['avg_call_dialed_cnt_3m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['avg_call_dialed_cnt_6m']}} : {{content['avg_call_dialed_cnt_6m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dialed_time_1m']}} : {{content['call_dialed_time_1m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dialed_time_3m']}} : {{content['call_dialed_time_3m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['call_dialed_time_6m']}} : {{content['call_dialed_time_6m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['avg_call_dialed_time_3m']}} : {{content['avg_call_dialed_time_3m']}}</div>
                            <div class="sp">{{call_risk_analysis_map['avg_call_dialed_time_6m']}} : {{content['avg_call_dialed_time_6m']}}</div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="main_service">
            常用服务 数据
        </div>
        {% set main_service_map = {'service_num':'服务号码', 'group_name':'服务类型', 'company_name':'公司名称', 'total_service_cnt':'总服务次数', 'interact_mth':' 联系月份', 'interact_cnt':'通话次数', 'interact_time':'通话时长', 'dial_cnt':'主叫次数', 'dial_time':'主叫时长（秒）', 'dialed_time':'被叫时长（秒）', 'dialed_cnt':'被叫次数'} %}
        <div>
            {% for item in data.data.main_service %}
                <div style="border:1px black solid;margin-bottom: 5px;">
                    <div class="sp">{{main_service_map['service_num']}} : {{item['service_num']}}</div>
                    <div class="sp">{{main_service_map['group_name']}} : {{item['group_name']}}</div>
                    <div class="sp">{{main_service_map['company_name']}} : {{item['company_name']}}</div>
                    <div class="sp">{{main_service_map['total_service_cnt']}} : {{item['total_service_cnt']}}</div>
                    <div class="borderys">
                        {% set content = item['service_details'] %}
                        <div class="sp">{{main_service_map['interact_mth']}} : {{content['interact_mth']}}</div>
                        <div class="sp">{{main_service_map['interact_cnt']}} : {{content['interact_cnt']}}</div>
                        <div class="sp">{{main_service_map['interact_time']}} : {{content['interact_time']}}</div>
                        <div class="sp">{{main_service_map['dial_cnt']}} : {{content['dial_cnt']}}</div>
                        <div class="sp">{{main_service_map['dialed_cnt']}} : {{content['dialed_cnt']}}</div>
                        <div class="sp">{{main_service_map['dial_time']}} : {{content['dial_time']}}</div>
                        <div class="sp">{{main_service_map['dialed_time']}} : {{content['dialed_time']}}</div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="call_service_analysis">
             常用服务 数据
        </div>
        {% set call_service_analysis_map = {'call_cnt_1m':'近1月通话次数', 'call_cnt_3m':'近3月通话次数', 'call_cnt_6m':'近6月通话次数', 'avg_call_cnt_3m':'近3月平均', 'avg_call_cnt_6m':'近6月平均', 'call_time_1m':'近1月通话时间', 'call_time_3m':'近3月通话时间', 'call_time_6m':'近6月通话时间', 'avg_call_time_3m':'近3月通话时长(秒)', 'avg_call_time_6m':'近6月通话时长(秒)', 'call_dial_cnt_1m':'近1月主叫次数', 'call_dial_cnt_3m':'近2月主叫次数', 'call_dial_cnt_6m':'近6月主叫次数', 'avg_call_dial_cnt_3m':'近3月平均主叫次数', 'avg_call_dial_cnt_6m':'近6月平均主叫次数', 'call_dial_time_1m':'近1月主叫时长(秒)', 'call_dial_time_3m':'近3月主叫时长(秒)', 'call_dial_time_6m':'近6月主叫时长(秒)', 'avg_call_dial_time_3m':'近3月平均主叫时长(秒)', 'avg_call_dial_time_6m':'近6月平均主叫时长(秒)', 'call_dialed_cnt_1m':'近1月被叫次数', 'call_dialed_cnt_3m':'近2月被叫次数', 'call_dialed_cnt_6m':'近6月被叫次数', 'avg_call_dialed_cnt_3m':'近3月平均被叫次数', 'avg_call_dialed_cnt_6m':'近6月平均被叫次数', 'call_dialed_time_1m':'近1月被叫时长(秒)', 'call_dialed_time_3m':'近3月被叫时长(秒)', 'call_dialed_time_6m':'近6月被叫时长(秒)', 'avg_call_dialed_time_3m':'近3月平均被叫时长(秒)', 'avg_call_dialed_time_6m':'近6月平均被叫时长(秒)'} %}
        {% for item in data.data.call_service_analysis %}
            <div style="margin-bottom: 10px;border:1px #0a65e1 solid;">
                <!-- <div>| analysis_item : {{item['analysis_item']}} |</div> -->
                <div class="userdata">{{item['analysis_desc']}}</div>
                {% set content = item['analysis_point'] %}
                <div class="borderys">
                    <div class="sp">{{call_service_analysis_map['call_cnt_1m']}} : {{content['call_cnt_1m']}}
                    </div>
                    <div class="sp">{{call_service_analysis_map['call_cnt_3m']}} : {{content['call_cnt_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_cnt_6m']}} : {{content['call_cnt_6m']}}
                    </div>
                    <div class="sp">{{call_service_analysis_map['avg_call_cnt_3m']}} : {{content['avg_call_cnt_3m']}}
                    </div>
                    <div class="sp">{{call_service_analysis_map['avg_call_cnt_6m']}} : {{content['avg_call_cnt_6m']}}
                    </div>
                    <div class="sp">{{call_service_analysis_map['call_time_1m']}} : {{content['call_time_1m']}}
                    </div>
                    <div class="sp">{{call_service_analysis_map['call_time_3m']}} : {{content['call_time_3m']}}
                    </div>
                    <div class="sp">{{call_service_analysis_map['call_time_6m']}} : {{content['call_time_6m']}}
                    </div>
                    <div class="sp">{{call_service_analysis_map['avg_call_time_3m']}} : {{content['avg_call_time_3m']}}
                    </div>
                    <div class="sp">{{call_service_analysis_map['avg_call_time_6m']}} : {{content['avg_call_time_6m']}}
                    </div>
                </div>
                {% set content = item['analysis_point']['call_analysis_dial_point'] %}
                <div class="borderys">
                    <div class="sp">{{call_service_analysis_map['call_dial_cnt_1m']}} : {{content['call_dial_cnt_1m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dial_cnt_3m']}} : {{content['call_dial_cnt_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dial_cnt_6m']}} : {{content['call_dial_cnt_6m']}}</div>
                    <div class="sp">{{call_service_analysis_map['avg_call_dial_cnt_3m']}} : {{content['avg_call_dial_cnt_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['avg_call_dial_cnt_6m']}} : {{content['avg_call_dial_cnt_6m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dial_time_1m']}} : {{content['call_dial_time_1m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dial_time_3m']}} : {{content['call_dial_time_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dial_time_6m']}} : {{content['call_dial_time_6m']}}</div>
                    <div class="sp">{{call_service_analysis_map['avg_call_dial_time_3m']}} : {{content['avg_call_dial_time_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['avg_call_dial_time_6m']}} : {{content['avg_call_dial_time_6m']}}</div>
                </div>
                {% set content = item['analysis_point']['call_analysis_dialed_point'] %}
                <div class="borderys">
                    <div class="sp">{{call_service_analysis_map['call_dialed_cnt_1m']}} : {{content['call_dialed_cnt_1m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dialed_cnt_3m']}} : {{content['call_dialed_cnt_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dialed_cnt_6m']}} : {{content['call_dialed_cnt_6m']}}</div>
                    <div class="sp">{{call_service_analysis_map['avg_call_dialed_cnt_3m']}} : {{content['avg_call_dialed_cnt_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['avg_call_dialed_cnt_6m']}} : {{content['avg_call_dialed_cnt_6m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dialed_time_1m']}} : {{content['call_dialed_time_1m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dialed_time_3m']}} : {{content['call_dialed_time_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['call_dialed_time_6m']}} : {{content['call_dialed_time_6m']}}</div>
                    <div class="sp">{{call_service_analysis_map['avg_call_dialed_time_3m']}} : {{content['avg_call_dialed_time_3m']}}</div>
                    <div class="sp">{{call_service_analysis_map['avg_call_dialed_time_6m']}} : {{content['avg_call_dialed_time_6m']}}</div>
                </div>
            </div>
        {% endfor %}
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="active_degree">
               活跃识别 数据
        </div>
        {% set active_degree_map = {'item_1m':'近1月', 'item_3m':'近3月', 'item_6m':' 近6月', 'avg_item_3m':'近3月平均', 'avg_item_6m':'近6月平均'} %}
        {% for item in data.data.active_degree %}
            <div style="margin-bottom: 10px;border:1px #0a65e1 solid;">
                <!-- <div>| app_point : {{item['app_point']}} |</div> -->
                <div class="sp">{{item['app_point_zh']}}</div>
                {% set content = item['item'] %}
                <div class="sp">{{active_degree_map['item_1m']}} : {{content['item_1m']}}</div>
                <div class="sp">{{active_degree_map['item_3m']}} : {{content['item_3m']}}</div>
                <div class="sp">{{active_degree_map['item_6m']}} : {{content['item_6m']}}</div>
                <div class="sp">{{active_degree_map['avg_item_3m']}} : {{content['avg_item_3m']}}</div>
                <div class="sp">{{active_degree_map['avg_item_6m']}} : {{content['avg_item_6m']}}</div>
            </div>
        {% endfor %}
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="consumption_detail">消费统计 数据:</div>
        {% set consumption_detail_map = {'item_1m':'近1月', 'item_3m':'近3月', 'item_6m':' 近6月', 'avg_item_3m':'近3月平均', 'avg_item_6m':'近6月平均'} %}
        {% for item in data.data.consumption_detail %}
            <div style="margin-bottom: 10px;border:1px #0a65e1 solid;">
                <!-- <div>| app_point : {{item['app_point']}} |</div> -->
                <div class="sp">{{item['app_point_zh']}}</div>
                {% set content = item['item'] %}
                <div class="sp">{{consumption_detail_map['item_1m']}} : {{content['item_1m']}}</div>
                <div class="sp">{{consumption_detail_map['item_3m']}} : {{content['item_3m']}}</div>
                <div class="sp">{{consumption_detail_map['item_6m']}} : {{content['item_6m']}}</div>
                <div class="sp">{{consumption_detail_map['avg_item_3m']}} : {{content['avg_item_3m']}}</div>
                <div class="sp">{{consumption_detail_map['avg_item_6m']}} : {{content['avg_item_6m']}}</div>
            </div>
        {% endfor %}
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="call_time_detail">
             通话时间详细统计 数据
        </div>
        {% set call_time_detail_map = {'item_1m':'近1月', 'item_3m':'近3月', 'item_6m':' 近6月', 'avg_item_3m':'近3月平均', 'avg_item_6m':'近6月平均'} %}
        {% for item in data.data.call_time_detail %}
            <div style="margin-bottom: 10px;border:1px #0a65e1 solid;">
                <!-- <div>| app_point : {{item['app_point']}} |</div> -->
                <div class="sp">{{item['app_point_zh']}}</div>
                {% set content = item['item'] %}
                <div class="sp">{{call_time_detail_map['item_1m']}} : {{content['item_1m']}}</div>
                <div class="sp">{{call_time_detail_map['item_3m']}} : {{content['item_3m']}}</div>
                <div class="sp">{{call_time_detail_map['item_6m']}} : {{content['item_6m']}}</div>
                <div class="sp">{{call_time_detail_map['avg_item_3m']}} : {{content['avg_item_3m']}}</div>
                <div class="sp">{{call_time_detail_map['avg_item_6m']}} : {{content['avg_item_6m']}}</div>
            </div>
        {% endfor %}
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" date-key="call_family_detail">
               稳定性 数据
       </div>
        {% set call_family_detail_map = {'item_1m':'近1月', 'item_3m':'近3月', 'item_6m':' 近6月', 'avg_item_3m':'近3月平均', 'avg_item_6m':'近6月平均'} %}
        {% for item in data.data.call_family_detail %}
            <div style="margin-bottom: 10px;border:1px #0a65e1 solid;">
                <!-- <div>| app_point : {{item['app_point']}} |</div> -->
                <div class="sp">{{item['app_point_zh']}}</div>
                {% set content = item['item'] %}
                <div class="sp">{{call_family_detail_map['item_1m']}} : {{content['item_1m']}}</div>
                <div class="sp">{{call_family_detail_map['item_3m']}} : {{content['item_3m']}}</div>
                <div class="sp">{{call_family_detail_map['item_6m']}} : {{content['item_6m']}}</div>
                <div class="sp">{{call_family_detail_map['avg_item_3m']}} : {{content['avg_item_3m']}}</div>
                <div class="sp">{{call_family_detail_map['avg_item_6m']}} : {{content['avg_item_6m']}}</div>
            </div>
        {% endfor %}
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="call_duration_detail">
             通话时段 数据
        </div>
        {% set call_duration_detail_map = {'desc':'分类', 'time_step_zh':'通话时段', 'total_cnt':' 通话次数', 'uniq_num_cnt':'通话号码数', 'total_time':'通话时长(秒)', 'dial_cnt':'主叫次数', 'dialed_cnt':'被叫次数', 'dial_time':'主叫时长(秒)', 'dialed_time':'被叫时长(秒)', 'latest_call_time':'最后一次通话时间', 'farthest_call_time':'第一次通话时间'} %}
        {% for item in data.data.call_duration_detail %}
            <div style="border:1px solid #0a65e1;margin-bottom: ">
                <!-- <div>| key : {{item['key']}} |</div> -->
                 <div class="userdata">
                   <div class="sp">
                      {{call_duration_detail_map['desc']}} : {{item['desc']}}
                   </div>
                </div>
                {% for content in item['duration_list'] %}
                <div class="borderys">
                    <!-- <div>| time_step : {{content['time_step']}} |</div> -->
                    <div class="sp">{{call_duration_detail_map['time_step_zh']}} : {{content['time_step_zh']}}</div>
                    <div class="sp">{{call_duration_detail_map['total_cnt']}} : {{content['item']['total_cnt']}}</div>
                    <div class="sp">{{call_duration_detail_map['uniq_num_cnt']}} : {{content['item']['uniq_num_cnt']}}</div>
                    <div class="sp">{{call_duration_detail_map['total_time']}} : {{content['item']['total_time']}}</div>
                    <div class="sp">{{call_duration_detail_map['dial_cnt']}} : {{content['item']['dial_cnt']}}</div>
                    <div class="sp">{{call_duration_detail_map['dialed_cnt']}} : {{content['item']['dialed_cnt']}}</div>
                    <div class="sp">{{call_duration_detail_map['dial_time']}} : {{content['item']['dial_time']}}</div>
                    <div class="sp">{{call_duration_detail_map['dialed_time']}} : {{content['item']['dialed_time']}}</div>
                    <div class="sp">{{call_duration_detail_map['latest_call_time']}} : {{content['item']['latest_call_time']}}</div>
                    <div class="sp">{{call_duration_detail_map['farthest_call_time']}} : {{content['item']['farthest_call_time']}}</div>
                </div>
                {% endfor %}
            </div>
        {% endfor %}
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="roam_analysis">
             漫游分析 数据
        </div>
        {% set roam_analysis_map = {'roam_location':'漫游城市', 'roam_day_cnt_3m':'近3月漫游天数', 'roam_day_cnt_6m':'近6月漫游天数', 'continue_roam_cnt_3m':'近3月漫游连续天数', 'continue_roam_cnt_6m':'近6月漫游连续天数', 'max_roam_day_cnt_3m':'近3月最大漫游天数', 'max_roam_day_cnt_6m':'近6月最大漫游天数'} %}
        <div>
            {% for item in data.data.roam_analysis %}
                <div style="border:1px #0a65e1 solid;margin-bottom: 5px;">
                    <div class="sp">{{roam_analysis_map['roam_location']}} : {{item['roam_location']}}</div>
                    <div class="sp">{{roam_analysis_map['roam_day_cnt_3m']}} : {{item['roam_day_cnt_3m']}}</div>
                    <div class="sp">{{roam_analysis_map['roam_day_cnt_6m']}} : {{item['roam_day_cnt_6m']}}</div>
                    <div class="sp">{{roam_analysis_map['continue_roam_cnt_3m']}} : {{item['continue_roam_cnt_3m']}}</div>
                    <div class="sp">{{roam_analysis_map['continue_roam_cnt_6m']}} : {{item['continue_roam_cnt_6m']}}</div>
                    <div class="sp">{{roam_analysis_map['max_roam_day_cnt_3m']}} : {{item['max_roam_day_cnt_3m']}}</div>
                    <div class="sp">{{roam_analysis_map['max_roam_day_cnt_6m']}} : {{item['max_roam_day_cnt_6m']}}</div>
                </div>
            {% endfor %}
        </div>
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="roam_detail">
            漫游详情统计(近6月漫游日期降序) 数据
        </div>
        {% for item in data.data.roam_detail %}
            <div>
                <div class="sp">漫游日期 : {{item['roam_day']}}</div>
                <div class="sp">漫游城市 : {{item['roam_location']}}</div>
            </div>
        {% endfor %}
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" date-key="user_info_check">
              用户信息监测 数据
        </div>
        {% set user_info_check_map = {'searched_org_cnt':'查询过该用户的相关企业数量(姓名+身份证+电话号码)', 'register_org_cnt':'电话号码注册过的相关企业数量', 'phone_gray_score':'用户号码联系黑中介分数(分数范围0-100，参考分为40，分数越低关系越紧密)', 'contacts_class1_blacklist_cnt':'直接联系人中黑名单人数', 'contacts_class2_blacklist_cnt':'间接联系人中黑名单人数', 'contacts_class1_cnt':'直接联系人人数', 'contacts_router_cnt':'引起间接黑名单人数', 'contacts_router_ratio':'直接联系人中引起间接黑名单占比'} %}
        {% for item in data.data.user_info_check %}
            <div class="borderys">
                <div class="userdata">用户查询信息</div>
                {% set content = item['check_search_info'] %}
                <div class="sp">
                   {{user_info_check_map['searched_org_cnt']}} : {{content['searched_org_cnt']}}
                 </div>
                <div class="sp">{{user_info_check_map['register_org_cnt']}} : {{content['register_org_cnt']}}
                </div>
                {% set content = item['check_black_info'] %}
                <div class="userdata">用户黑名单信息</div>
                <div class="sp">{{user_info_check_map['phone_gray_score']}} : {{content['phone_gray_score']}}</div>
                <div class="sp">{{user_info_check_map['contacts_class1_blacklist_cnt']}} : {{content['contacts_class1_blacklist_cnt']}}</div>
                <div class="sp">{{user_info_check_map['contacts_class2_blacklist_cnt']}} : {{content['contacts_class2_blacklist_cnt']}}</div>
                <div class="sp">{{user_info_check_map['contacts_class1_cnt']}} : {{content['contacts_class1_cnt']}}</div>
                <div class="sp">{{user_info_check_map['contacts_router_cnt']}} : {{content['contacts_router_cnt']}}</div>
                <div class="sp">{{user_info_check_map['contacts_router_ratio']}} : {{content['contacts_router_ratio']}}</div>
            </div>
        {% endfor %}
    </div>

    <div style="margin:5px;padding:5px;border:1px #ccc solid;">
        <div class="bgdata" data-key="trip_info">
             出行数据分析 数据
        </div>
        {% for item in data.data.trip_info %}
            <div class="borderys">
                <div class="sp">目的地 : {{item['trip_dest']}}</div>
                <div class="sp">出发时间 : {{item['trip_start_time']}}</div>
                <div class="sp">结束时间 : {{item['trip_end_time']}}</div>
                <div class="sp">出发地 : {{item['trip_leave']}}</div>
                <div class="sp">类型 : {{item['trip_type']}}</div>
            </div>
        {% endfor %}
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
