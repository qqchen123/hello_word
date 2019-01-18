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
        {% set item = data.creditcard.user_basic_info %}
        <div class="userdata"> 用户基本信息</div>
        <div class="borderys">
            <div id="report-name" style="display: none;">{{item.name}}</div>
            <div class="sp">姓名: {{item.name}}</div>
            <div class="sp">邮箱: {{item.email}}</div>
            <div class="sp">活跃卡数: {{item.active_card_num}}</div>
            <div class="sp">银行数: {{item.bank_num}}</div>
            <div class="sp">最初账单日: {{item.bill_start_date}}</div>
            <div class="sp">最早一期账单距今月份数（MOB）: {{item.bill_start_date_month}}</div>
            <div class="sp">客户族群标志: {{item.pvcu_customer_group_tag}}</div>
            <div class="sp">客户套现标志: {{item.pvcu_cashouts_tag}}</div>
            <div class="sp">账单最新认证时间: {{item.latest_certification_time}}</div>
        </div>
        <div class="userdata">授信情况</div>
        {% set item = data.creditcard.account_summary %}
        <div class="borderys">
        	<div class="sp">总信用额（元）: {{item.creditcard_limit}}</div>
            <div class="sp">总可用信用额（元): {{item.total_can_use_consume_limit_1}}</div>
            <div class="sp">可用消费信用额（元): {{item.creditcard_balance}}</div>
            <div class="sp">提现信用额（元）: {{item.creditcard_cash_limit}}</div>
            <div class="sp">可用提现信用额（元）: {{item.creditcard_cash_balance}}</div>
            <div class="sp">消费信用额（元）: {{item.consume_credit_limit}}</div>
            <div class="sp">单一银行最高授信额度（元）: {{item.single_bank_max_limit}}</div>
            <div class="sp">单一银行最低授信额度（元）: {{item.single_bank_min_limit}}</div>
        </div>
        <div class="userdata"> 交易行为</div>
        {% set item = data.creditcard.credit_card_summary %}
        <div class="borderys">
        	<div class="sp">当期总透支余额（元）: {{item.total_new_balance_1}}</div>
            <div class="sp">上期应还总额（元）: {{item.total_last_balance_1}}</div>
            <div class="sp">当期最低还款总额（元）: {{item.total_min_payment_1}}</div>
            <div class="sp">当期新发生消费总金额（元）: {{item.total_consume_amount_1}}</div>
            <div class="sp">当期新发生消费总笔数: {{item.total_consume_num_1}}</div>
            <div class="sp">上期未还总额（元）: {{item.last_unrepay_amount}}</div>
            <div class="sp">当期新发生消费平均金额（元）: {{item.cur_consume_avg_amount}}</div>
            <div class="sp">当期新发生提现总金额（元）: {{item.cur_cash_amount}}</div>
            <div class="sp">当期新发生提现总笔数: {{item.cur_cash_num}}</div>
            <div class="sp">当期新发生提现平均金额（元）: {{item.cur_cash_avg_amount}}</div>
        </div>
        <div class="userdata">还款</div>
        {% set item = data.creditcard.repayment_summary %}
        <div class="borderys">
        	<div class="sp">还款金额（元）: {{item.repay_amount_1}}</div>
            <div class="sp">还款笔数: {{item.repay_num_1}}</div>
            <div class="sp">还款率: {{item.repay_ratio_1}}</div>
        </div>
        <div class="userdata">逾期信息</div>
        {% set item = data.creditcard.overdue_information %}
        <div class="borderys">
            <div class="sp">逾期标志: {{item.delay_tag_1}}</div>
            <div class="sp">逾期状态: {{item.delay_status_1}}</div>
            <div class="sp">未还金额（元）: {{item.delay_amount_1}}</div>
            <div class="sp">未还金额占比: {{item.delay_amount_per_1}}</div>
            <div class="sp">逾期账单数量: {{item.delay_bill_num_1}}</div>
        </div>
        <div class="userdata">利费信息</div>
        {% set item = data.creditcard.interest_information %}
        <div class="borderys">
            <div class="sp">总利息（元）: {{item.delay_interest_1}}</div>
            <div class="sp">延滞金（元）: {{item.overdue_amount_1}}</div>
            <div class="sp">超额金（元）: {{item.overdue_pay_1}}</div>
            <div class="sp">其他费用（元）: {{item.other_fee_1}}</div>
            <div class="sp">收入: {{item.income_amt_1}}</div>
        </div>
        <div class="userdata">销售金额</div>
        {% set item = data.creditcard.sales_amount %}
        <div class="borderys">
            <div class="sp">近3月销售金额大于0的月份占比: {{item.ratio_sell_month_3}}</div>
            <div class="sp">近6月销售金额大于0的月份占比: {{item.ratio_sell_month_6}}</div>
            <div class="sp">近12月销售金额大于0的月份占比: {{item.ratio_sell_month_12}}</div>
            <div class="sp">近3月月均消费金额（元）: {{item.avg_consume_amount_3}}</div>
            <div class="sp">近6月月均消费金额（元）: {{item.avg_consume_amount_6}}</div>
            <div class="sp">近12月月均消费金额（元）: {{item.avg_consume_amount_12}}</div>
            <div class="sp">近3月最高消费金额（元）: {{item.creditcard_max_balance_3}}</div>
            <div class="sp">近6月最高消费金额（元）: {{item.creditcard_max_balance_6}}</div>
            <div class="sp">近12月最高消费金额（元）: {{item.creditcard_max_balance_12}}</div>
            <div class="sp">近3月最低消费金额（元）: {{item.creditcard_min_balance_3}}</div>
            <div class="sp">近6月最低消费金额（元）: {{item.creditcard_min_balance_6}}</div>
            <div class="sp">近12月最低消费金额（元）: {{item.creditcard_min_balance_12}}</div>
            <div class="sp">近3月提现次数: {{item.withdraw_num_cc_3}}</div>
            <div class="sp">近6月提现次数: {{item.withdraw_num_cc_6}}</div>
            <div class="sp">近12月提现次数: {{item.withdraw_num_cc_12}}</div>
            <div class="sp">近3月提现金额（元）: {{item.withdraw_amount_3}}</div>
            <div class="sp">近6月提现金额（元）: {{item.withdraw_amount_6}}</div>
            <div class="sp">近12月提现金额（元）: {{item.withdraw_amount_12}}</div>
            <div class="sp">近3月有提现月数占比: {{item.has_withdrawal_percentage_3m}}</div>
            <div class="sp">近6月有提现月数占比: {{item.has_withdrawal_percentage_6m}}</div>
            <div class="sp">近12月有提现月数占比: {{item.has_withdrawal_percentage_12m}}</div>
            <div class="sp">近3月月均提现金额（元）: {{item.avg_cash_amount_3}}</div>
            <div class="sp">近6月月均提现金额（元）: {{item.avg_cash_amount_6}}</div>
            <div class="sp">近12月月均提现金额（元）: {{item.avg_cash_amount_12}}</div>
            <div class="sp">近3月最近一次提现距今的月数: {{item.last_withdrawal_month_num_3}}</div>
            <div class="sp">近6月最近一次提现距今的月数: {{item.last_withdrawal_month_num_6}}</div>
            <div class="sp">近12月最近一次提现距今的月数: {{item.last_withdrawal_month_num_12}}</div>
            <div class="sp">近3月首次销售金额（元）: {{item.earlyest_sell_amount_3}}</div>
            <div class="sp">近6月首次销售金额（元）: {{item.earlyest_sell_amount_6}}</div>
            <div class="sp">近12月首次销售金额（元）: {{item.earlyest_sell_amount_12}}</div>
            <div class="sp">近3月首次消费金额（元）: {{item.earlyest_consume_amount_3}}</div>
            <div class="sp">近6月首次消费金额（元）: {{item.earlyest_consume_amount_6}}</div>
            <div class="sp">近12月首次消费金额（元）: {{item.earlyest_consume_amount_12}}</div>
            <div class="sp">近3月单期最大销售笔数: {{item.max_sell_num_3}}</div>
            <div class="sp">近6月单期最大销售笔数: {{item.max_sell_num_6}}</div>
            <div class="sp">近12月单期最大销售笔数: {{item.max_sell_num_12}}</div>
            <div class="sp">近3月有销售的月数: {{item.has_sell_month_num_3}}</div>
            <div class="sp">近6月有销售的月数: {{item.has_sell_month_num_6}}</div>
            <div class="sp">近12月有销售的月数: {{item.has_sell_month_num_12}}</div>
            <div class="sp">近3月月均消费笔数: {{item.avg_consume_num_3}}</div>
            <div class="sp">近6月月均消费笔数: {{item.avg_consume_num_6}}</div>
            <div class="sp">近12月月均消费笔数: {{item.avg_consume_num_12}}</div>
        </div>
        <div class="userdata">余额</div>
        {% set item = data.creditcard.balance %}
        <div class="borderys">
        	<div class="sp">近1月零售余额/近3月均零售余额（元）: {{item.retail_balance_1_per_3}}</div>
            <div class="sp">近1月零售余额/近6月均零售余额（元）: {{item.retail_balance_1_per_6}}</div>
            <div class="sp">近1月零售余额/近12月均零售余额（元）: {{item.retail_balance_1_per_12}}</div>
            <div class="sp">近3月均销售占近3月均余额比率: {{item.avg_amount_per_avg_balance_3}}</div>
            <div class="sp">近6月均销售占近3月均余额比率: {{item.avg_amount_per_avg_balance_6}}</div>
            <div class="sp">近12月均销售占近3月均余额比率: {{item.avg_amount_per_avg_balance_12}}</div>
            <div class="sp">近3月最高余额距今的月数: {{item.max_balance_month_num_3}}</div>
            <div class="sp">近6月最高余额距今的月数: {{item.max_balance_month_num_6}}</div>
            <div class="sp">近12月最高余额距今的月数: {{item.max_balance_month_num_12}}</div>
        </div>
        <div class="userdata">还款</div>
        {% set item = data.creditcard.repayment %}
        <div class="borderys">
        	<div class="sp">近3月平均还款金额（元）: {{item.avg_pay_amount_3}}</div>
            <div class="sp">近6月平均还款金额（元）: {{item.avg_pay_amount_6}}</div>
            <div class="sp">近12月平均还款金额（元）: {{item.avg_pay_amount_12}}</div>
            <div class="sp">近3月平均还款率: {{item.repay_ratio_avg_3}}</div>
            <div class="sp">近6月平均还款率: {{item.repay_ratio_avg_6}}</div>
            <div class="sp">近12月平均还款率: {{item.repay_ratio_avg_12}}</div>
            <div class="sp">近3月最近一次产生还款金额距今的月数: {{item.last_repay_now_num_3}}</div>
            <div class="sp">近6月最近一次产生还款金额距今的月数: {{item.last_repay_now_num_6}}</div>
            <div class="sp">近12月最近一次产生还款金额距今的月数: {{item.last_repay_now_num_12}}</div>
            <div class="sp">近3月有MINPAY且不足全额的月数: {{item.minpay_mons_3}}</div>
            <div class="sp">近6月有MINPAY且不足全额的月数: {{item.minpay_mons_6}}</div>
            <div class="sp">近12月有MINPAY且不足全额的月数: {{item.minpay_mons_12}}</div>
        </div>
        <div class="userdata">额度</div>
        {% set item = data.creditcard.quota %}
        <div class="borderys">
        	<div class="sp">近3月平均余额占额度比例: {{item.average_balance_per_quota_nearly_3m}}</div>
            <div class="sp">近6月平均余额占额度比例: {{item.average_balance_per_quota_nearly_6m}}</div>
            <div class="sp">近12月平均余额占额度比例: {{item.average_balance_per_quota_nearly_12m}}</div>
            <div class="sp">近3月销售金额占额度比: {{item.propertion_of_sales_amount_quota_3}}</div>
            <div class="sp">近6月销售金额占额度比: {{item.propertion_of_sales_amount_quota_6}}</div>
            <div class="sp">近12月销售金额占额度比: {{item.propertion_of_sales_amount_quota_12}}</div>
            <div class="sp">近3月中最大销售金额占额度比: {{item.propertion_of_max_sales_amount_in_quota_3}}</div>
            <div class="sp">近6月中最大销售金额占额度比: {{item.propertion_of_max_sales_amount_in_quota_6}}</div>
            <div class="sp">近12月中最大销售金额占额度比: {{item.propertion_of_max_sales_amount_in_quota_12}}</div>
            <div class="sp">近3月中最小销售金额占额度比: {{item.propertion_of_min_sales_amount_in_quota_3}}</div>
            <div class="sp">近6月中最小销售金额占额度比: {{item.propertion_of_min_sales_amount_in_quota_6}}</div>
            <div class="sp">近12月中最小销售金额占额度比: {{item.propertion_of_min_sales_amount_in_quota_12}}</div>
            <div class="sp">近3月最大额度使用率: {{item.useage_of_max_quota_3}}</div>
            <div class="sp">近6月最大额度使用率: {{item.useage_of_max_quota_6}}</div>
            <div class="sp">近12月最大额度使用率: {{item.useage_of_max_quota_12}}</div>
            <div class="sp">近3月最近一次额度使用率>0的值: {{item.last_useage_more_than_0_nearly_3}}</div>
            <div class="sp">近6月最近一次额度使用率>0的值: {{item.last_useage_more_than_0_nearly_6}}</div>
            <div class="sp">近12月最近一次额度使用率>0的值: {{item.last_useage_more_than_0_nearly_12}}</div>
            <div class="sp">近3月平均（消费+提现）金额/信用额度（元）: {{item.avg_propertion_of_consume_withdrawal_in_quota_3}}</div>
            <div class="sp">近6月平均（消费+提现）金额/信用额度（元）: {{item.avg_propertion_of_consume_withdrawal_in_quota_6}}</div>
            <div class="sp">近12月平均（消费+提现）金额/信用额度（元）: {{item.avg_propertion_of_consume_withdrawal_in_quota_12}}</div>
            <div class="sp">近3月中销售金额占额度比大于0.5的月数: {{item.propertion_of_sales_amnt_in_quota_more_0_5_num_3}}</div>
            <div class="sp">近6月中销售金额占额度比大于0.5的月数: {{item.propertion_of_sales_amnt_in_quota_more_0_5_num_6}}</div>
            <div class="sp">近12月中销售金额占额度比大于0.5的月数: {{item.propertion_of_sales_amnt_in_quota_more_0_5_num_12}}</div>
            <div class="sp">近3月中销售金额占额度比大于0.9的月数: {{item.propertion_of_sales_amnt_in_quota_more_0_9_num_3}}</div>
            <div class="sp">近6月中销售金额占额度比大于0.9的月数: {{item.propertion_of_sales_amnt_in_quota_more_0_9_num_6}}</div>
            <div class="sp">近12月中销售金额占额度比大于0.9的月数: {{item.propertion_of_sales_amnt_in_quota_more_0_9_num_12}}</div>
            <div class="sp">最近一期额度使用率与近3月最大比: {{item.max_propertion_of_last_useage_3}}</div>
            <div class="sp">最近一期额度使用率与近6月最大比: {{item.max_propertion_of_last_useage_6}}</div>
            <div class="sp">最近一期额度使用率与近12月最大比: {{item.max_propertion_of_last_useage_12}}</div>
            <div class="sp">近3月平均额度（元）: {{item.avg_quota_3}}</div>
            <div class="sp">近6月平均额度（元）: {{item.avg_quota_6}}</div>
            <div class="sp">近12月平均额度（元）: {{item.avg_quota_12}}</div>
            <div class="sp">近3月最高额度（元）: {{item.max_quota_3}}</div>
            <div class="sp">近6月最高额度（元）: {{item.max_quota_6}}</div>
            <div class="sp">近12月最高额度（元）: {{item.max_quota_12}}</div>
            <div class="sp">近3月最低额度（元）: {{item.min_quota_3}}</div>
            <div class="sp">近6月最低额度（元）: {{item.min_quota_6}}</div>
            <div class="sp">近12月最低额度（元）: {{item.min_quota_12}}</div>
            <div class="sp">近3月最小额度使用率: {{item.useage_of_min_quota_3}}</div>
            <div class="sp">近6月最小额度使用率: {{item.useage_of_min_quota_6}}</div>
            <div class="sp">近12月最小额度使用率: {{item.useage_of_min_quota_12}}</div>
            <div class="sp">近3月平均额度使用率: {{item.average_useage_rate_of_quota_nearly_3m}}</div>
            <div class="sp">近6月平均额度使用率: {{item.average_useage_rate_of_quota_nearly_6m}}</div>
            <div class="sp">近12月平均额度使用率: {{item.average_useage_rate_of_quota_nearly_12m}}</div>
        </div>
        <div class="userdata">利息</div>
        {% set item = data.creditcard.interest %}
        <div class="borderys">
        	<div class="sp">近3月有利息的月份数: {{item.interest_mons_3}}</div>
            <div class="sp">近6月有利息的月份数: {{item.interest_mons_6}}</div>
            <div class="sp">近12月有利息的月份数: {{item.interest_mons_12}}</div>
            <div class="sp">近3月有利息月数占比: {{item.interest_mon_ratio_3}}</div>
            <div class="sp">近6月有利息月数占比: {{item.interest_mon_ratio_6}}</div>
            <div class="sp">近12月有利息月数占比: {{item.interest_mon_ratio_12}}</div>
        </div>
        <div class="userdata">收入</div>
        {% set item = data.creditcard.income %}
        <div class="borderys">
        	<div class="sp">近3月平均收入（元）: {{item.credit_incom_avg_3}}</div>
            <div class="sp">近6月平均收入（元）: {{item.income_avg_3_div_6}}</div>
            <div class="sp">近12月平均收入（元）: {{item.income_avg_6_div_12}}</div>
            <div class="sp">近3个月平均收入与近6个月平均比: {{item.min_income_now_mons_3}}</div>
            <div class="sp">近6个月平均收入与近12个月平均比: {{item.min_income_now_mons_6}}</div>
            <div class="sp">近3月最低收入距今月份数: {{item.min_income_now_mons_12}}</div>
            <div class="sp">近6月最低收入距今月份数: {{item.credit_incom_avg_6}}</div>
            <div class="sp">近12月最低收入距今月份数: {{item.credit_incom_avg_12}}</div>
        </div>
        <div class="userdata"> 超额</div>
        {% set item = data.creditcard.overrun %}
        <div class="borderys">
        	<div class="sp">近3月超额的月份数: {{item.beyond_quota_month_num_3m}}</div>
            <div class="sp">近6月超额的月份数: {{item.beyond_quota_month_num_6m}}</div>
            <div class="sp">近12月超额的月份数: {{item.beyond_quota_month_num_12m}}</div>
            <div class="sp">近3月最高超额费（元）: {{item.beyond_quota_max_amount_3m}}</div>
            <div class="sp">近6月最高超额费（元）: {{item.beyond_quota_max_amount_6m}}</div>
            <div class="sp">近12月最高超额费（元）: {{item.beyond_quota_max_amount_12m}}</div>
        </div>
        <div class="userdata">逾期</div>
        {% set item = data.creditcard.overdue_creditcard %}
        <div class="borderys">
        	<div class="sp">近3月非延滞期数: {{item.non_delayed_periods_num_3}}</div>
            <div class="sp">近6月非延滞期数: {{item.non_delayed_periods_num_6}}</div>
            <div class="sp">近12月非延滞期数: {{item.non_delayed_periods_num_12}}</div>
            <div class="sp">近3月延滞期数: {{item.delayed_periods_num_3}}</div>
            <div class="sp">近6月延滞期数: {{item.delayed_periods_num_6}}</div>
            <div class="sp">近12月延滞期数: {{item.delayed_periods_num_12}}</div>
            <div class="sp">近3月延滞金银行机构数: {{item.delayed_bank_num_3}}</div>
            <div class="sp">近6月延滞金银行机构数: {{item.delayed_bank_num_6}}</div>
            <div class="sp">近12月延滞金银行机构数: {{item.delayed_bank_num_12}}</div>
            <div class="sp">近3月延滞金卡片数: {{item.delayed_card_num_3}}</div>
            <div class="sp">近6月延滞金卡片数: {{item.delayed_card_num_6}}</div>
            <div class="sp">近12月延滞金卡片数: {{item.delayed_card_num_12}}</div>
            <div class="sp">近3月首次延滞金额（元）: {{item.delayed_amnt_first_3}}</div>
            <div class="sp">近6月首次延滞金额（元）: {{item.delayed_amnt_first_6}}</div>
            <div class="sp">近12月首次延滞金额（元）: {{item.delayed_amnt_first_12}}</div>
            <div class="sp">近3月最高（超额金+延滞金）金额（元）: {{item.max_amnt_of_beyond_delayed_3}}</div>
            <div class="sp">近6月最高（超额金+延滞金）金额（元）: {{item.max_amnt_of_beyond_delayed_6}}</div>
            <div class="sp">近12月最高（超额金+延滞金）金额（元）: {{item.max_amnt_of_beyond_delayed_12}}</div>
            <div class="sp">近3月最高延滞金额（元）: {{item.max_beyond_amnt_3}}</div>
            <div class="sp">近6月最高延滞金额（元）: {{item.max_beyond_amnt_6}}</div>
            <div class="sp">近12月最高延滞金额（元）: {{item.max_beyond_amnt_12}}</div>
            <div class="sp">近3月最高延滞状态: {{item.highest_delayed_3}}</div>
            <div class="sp">近6月最高延滞状态: {{item.highest_delayed_6}}</div>
            <div class="sp">近12月最高延滞状态: {{item.highest_delayed_12}}</div>
            <div class="sp">近3月最近产生延滞金额距今的月份数: {{item.last_delayed_mon_num_3}}</div>
            <div class="sp">近6月最近产生延滞金额距今的月份数: {{item.last_delayed_mon_num_6}}</div>
            <div class="sp">近12月最近产生延滞金额距今的月份数: {{item.last_delayed_mon_num_12}}</div>
            <div class="sp">近3月逾期期数为1的月份数: {{item.case_delayed_period_equals_one_mon_num_3}}</div>
            <div class="sp">近6月逾期期数为1的月份数: {{item.case_delayed_period_equals_one_mon_num_6}}</div>
            <div class="sp">近12月逾期期数为1的月份数: {{item.case_delayed_period_equals_one_mon_num_12}}</div>
        </div>
        <div class="userdata">固定属性</div>
        {% set item = data.creditcard.other_attribute %}
        <div class="borderys">
        	<div class="sp">近3月有账单的月份数: {{item.have_bill_month_num_nearly_3}}</div>
            <div class="sp">近6月有账单的月份数: {{item.have_bill_month_num_nearly_6}}</div>
            <div class="sp">近12月有账单的月份数: {{item.have_bill_month_num_nearly_12}}</div>
            <div class="sp">近3月连续账单的最大月份数: {{item.longest_month_of_continuous_bill_nearly_3}}</div>
            <div class="sp">近6月连续账单的最大月份数: {{item.longest_month_of_continuous_bill_nearly_6}}</div>
            <div class="sp">近12月连续账单的最大月份数: {{item.longest_month_of_continuous_bill_nearly_12}}</div>
            <div class="sp">近3月无账单月数: {{item.none_bill_month_num_nearly_3}}</div>
            <div class="sp">近6月无账单月数: {{item.none_bill_month_num_nearly_6}}</div>
            <div class="sp">近12月无账单月数: {{item.none_bill_month_num_nearly_12}}</div>
            <div class="sp">近3月无账单月数/可统计的月数: {{item.none_bill_month_num_per_all_3}}</div>
            <div class="sp">近6月无账单月数/可统计的月数: {{item.none_bill_month_num_per_all_6}}</div>
            <div class="sp">近12月无账单月数/可统计的月数: {{item.none_bill_month_num_per_all_12}}</div>
            <div class="sp">近3月单卡最长连续账单月数: {{item.longest_num_of_continuous_bill_nearly_3}}</div>
            <div class="sp">近6月单卡最长连续账单月数: {{item.longest_num_of_continuous_bill_nearly_6}}</div>
            <div class="sp">近12月单卡最长连续账单月数: {{item.longest_num_of_continuous_bill_nearly_12}}</div>
            <div class="sp">近3月单卡最大账单断开月数: {{item.single_card_disconnect_month_num_3}}</div>
            <div class="sp">近6月单卡最大账单断开月数: {{item.single_card_disconnect_month_num_6}}</div>
            <div class="sp">近12月单卡最大账单断开月数: {{item.single_card_disconnect_month_num_12}}</div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#dl').click(function() {
        $("#report-box").wordExport('信用卡报告-'+$('#report-name').text());
    });
</script>

