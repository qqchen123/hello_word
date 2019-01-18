<style type="text/css">
    .sp {
        width: 32%;
        border:1px solid #ccc;
        display: inline-block;
        padding: 2px;
        margin-bottom: 3px;
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
    #dl:hover{
        cursor: pointer;
    }
    .bgdata {
        background-color: #0a65e1;
        color: white;
        text-align:center;
        font-size: 18px;
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
<div>
    <div class="download" id="dl">下载</div>
	{% set line_size = 3 %}
    <div style="margin:5px;padding:5px;border:1px #ccc solid;" id="report-box">
        <div class="bgdata" data-key="report">报告数据</div>
        {% set cnt = 0 %}
        <div class="userdata">用户基本信息</div>
        {% set item = data.user_basic_info %}
        <div class="box1 borderys">
            <div id="report-name" style="display: none;">{{item.real_name}}</div>
            <div class="sp">性别: {{item.gender}}</div>
            <div class="sp">证件类型(ID_CARD：身份证): {{item.certificate_type}}</div>
            <div class="sp">证件号码: {{item.certificate_number}}</div>
            <div class="sp">年龄: {{item.age}}</div>
            <div class="sp">籍贯: {{item.native_place}}</div>
            <div class="sp">邮箱: {{item.email}}</div>
            <div class="sp">家庭地址: {{item.home_address}}</div>
            <div class="sp">手机号码: {{item.mobile}}</div>
            <div class="sp">公积金地区: {{item.description}}</div>
            <div class="sp">单位名称: {{item.corporation_name}}</div>
            <div class="sp">单位类型: {{item.compay_type}}</div>
        </div>
        <div class="userdata">用户基本信息效验</div>
        {% set item = data.user_basic_info_check %}
        <div class="borderys">
            <div class="sp">身份证号码有效性: {{item.certificate_number_check}}</div>
            <div class="sp">家庭地址与公积金地区是否匹配(是/否): {{item.description_is_match_home_address}}</div>
            <div class="sp">籍贯与公积金地区是否匹配(是/否): {{item.native_place_is_match_description}}</div>
            <div class="sp">家庭地址与籍贯是否匹配(是/否): {{item.home_address_is_match_native_place}}</div>
        </div>

        <div class="userdata"> 账户基本信息</div>
        {% set item = data.fund_basic_info %}
        <div class="borderys">
            <div class="sp">账户余额(元): {{item.balance/100}}</div>
            <div class="sp">公积金缴存状态: {{item.pay_status}}</div>
            <div class="sp">开户日期: {{item.begin_date}}</div>
            <div class="sp">月缴金额(元): {{item.monthly_total_income/100}}</div>
            <div class="sp">单位月缴金额(元): {{item.monthly_corporation_income/100}}</div>
            <div class="sp">个人月缴金额(元): {{item.monthly_customer_income/100}}</div>
            <div class="sp">基数: {{item.base_number}}</div>
            <div class="sp">单位月缴比例: {{item.corporation_ratio}}</div>
            <div class="sp">个人月缴比例: {{item.customer_ratio}}</div>
            <div class="sp">最后缴费日期: {{item.last_pay_date}}</div>
            <div class="sp">最早缴费日期: {{item.earlyest_time}}</div>
            <div class="sp">近24个月内公积金缴纳公司数: {{item.corporation_name_num}}</div>
            <div class="sp">贷款金额(元): {% if item.loan_amount > 0 %}{{item.loan_amount/100}}{% else %}{{item.loan_amount}}{% endif %}</div>
            <div class="sp">贷款期数: {{item.loan_periods}}</div>
            <div class="sp">剩余贷款(元): {% if item.loan_remain_amount > 0 %}{{item.loan_remain_amount}}{% else %}{{item.loan_remain_amount}}{% endif %}</div>
        </div>

        <div class="userdata">缴纳信息</div>
        {% set item = data.payment_info %}
        <div class="borderys">
            <div class="sp">近6月缴存金额(元): {{item.income_6/100}}</div>
            <div class="sp">近12月缴存金额(元): {{item.income_12/100}}</div>
            <div class="sp">近24月缴存金额(元): {{item.income_24/100}}</div>
            <div class="sp">近24月月均缴存金额(元): {{item.income_avg_24/100}}</div>
            <div class="sp">近6月未缴月数: {{item.un_month_6}}</div>
            <div class="sp">近12月未缴月数: {{item.un_month_12}}</div>
            <div class="sp">近24月未缴月数: {{item.un_month_24}}</div>
            <div class="sp">近24月月均未缴月数: {{item.un_month_avg_24}}</div>
            <div class="sp">近6月取出金额(元): {{item.outcome_6/100}}</div>
            <div class="sp">近12月取出金额(元): {{item.outcome_12/100}}</div>
            <div class="sp">近24月取出金额(元): {{item.outcome_24/100}}</div>
            <div class="sp">近24月月均取出金额(元): {{item.outcome_avg_24/100}}</div>
            <div class="sp">近6月取出笔数: {{item.outcome_num_6}}</div>
            <div class="sp">近12月取出笔数: {{item.outcome_num_12}}</div>
            <div class="sp">近24月取出笔数: {{item.outcome_num_24}}</div>
            <div class="sp">近24月月均取出笔数: {{item.outcome_num_avg_24}}</div>
            <div class="sp">近6月月均余额(元): {{item.balance_avg_6/100}}</div>
            <div class="sp">近12月月均余额(元): {{item.balance_avg_12/100}}</div>
            <div class="sp">近24月月均余额(元): {{item.balance_avg_24/100}}</div>
            <div class="sp">近6月最大连续缴存月数: {{item.max_month_6}}</div>
            <div class="sp">近12月最大连续缴存月数: {{item.max_month_12}}</div>
            <div class="sp">近24月最大连续缴存月数: {{item.max_month_24}}</div>
            <div class="sp">近24月月均最大连续缴存月数: {{item.max_month_avg_24}}</div>
        </div>

        <div class="userdata"> 还款信息</div>
        {% set item = data.repay_info %}
        <div class="borderys">
            <div class="sp">近6月还款月数: {{item.repay_month_6}}</div>
            <div class="sp">近12月还款月数: {{item.repay_month_12}}</div>
            <div class="sp">近24月还款月数: {{item.repay_month_24}}</div>
            <div class="sp">近24月月均还款月数: {{item.repay_month_avg_24}}</div>
            <div class="sp">近6月最大连续还款月数: {{item.repay_continues_month_6}}</div>
            <div class="sp">近12月最大连续还款月数: {{item.repay_continues_month_12}}</div>
            <div class="sp">近24月最大连续还款月数: {{item.repay_continues_month_24}}</div>
            <div class="sp">近24月月均最大连续还款月数: {{item.repay_continues_month_avg_24}}</div>
            <div class="sp">近6月逾期还款合同数占比: {{item.delay_repay_ratio_6}}</div>
            <div class="sp">近12月逾期还款合同数占比: {{item.delay_repay_ratio_12}}</div>
            <div class="sp">近24月逾期还款合同数占比: {{item.delay_repay_ratio_24}}</div>
            <div class="sp">近24月月均逾期还款合同数占比: {{item.delay_repay_ratio_avg_24}}</div>
            <div class="sp">近6月还款合同数: {{item.repay_num_6}}</div>
            <div class="sp">近12月还款合同数: {{item.repay_num_12}}</div>
            <div class="sp">近24月还款合同数: {{item.repay_num_24}}</div>
            <div class="sp">近24月月均还款合同数: {{item.repay_num_avg_24}}</div>
            <div class="sp">近6月还款金额(元): {{item.repay_amount_6/100}}</div>
            <div class="sp">近12月还款金额(元): {{item.repay_amount_12/100}}</div>
            <div class="sp">近24月还款金额(元): {{item.repay_amount_24/100}}</div>
            <div class="sp">近24月月均还款金额(元): {{item.repay_amount_avg_24/100}}</div>
            <div class="sp">近6月还款本金(元): {{item.repay_capital_6/100}}</div>
            <div class="sp">近12月还款本金(元): {{item.repay_capital_12/100}}</div>
            <div class="sp">近24月还款本金(元): {{item.repay_capital_24/100}}</div>
            <div class="sp">近24月月均还款本金(元): {{item.repay_capital_avg_24/100}}</div>
            <div class="sp">近6月还款利息(元): {{item.repay_interest_6/100}}</div>
            <div class="sp">近12月还款利息(元): {{item.repay_interest_12/100}}</div>
            <div class="sp">近24月还款利息(元): {{item.repay_interest_24/100}}</div>
            <div class="sp">近24月月均还款利息(元): {{item.repay_interest_avg_24/100}}</div>
            <div class="sp">近6月还款罚息(元): {{item.repay_penalty_6/100}}</div>
            <div class="sp">近12月还款罚息(元): {{item.repay_penalty_12/100}}</div>
            <div class="sp">近24月还款罚息(元): {{item.repay_penalty_24/100}}</div>
            <div class="sp">近24月月均还款罚息(元): {{item.repay_penalty_avg_24/100}}</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#dl').click(function() {
        $("#report-box").wordExport('公积金报告-'+$('#report-name').text());
    });
</script>