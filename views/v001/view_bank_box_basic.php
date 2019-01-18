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
    <div class="download" id="bills" data-taskid="{{task_id}}">账单数据</div>
	{% set line_size = 3 %}
    <div style="margin:5px;padding:5px;border:1px #ccc solid;" id="report-box">
        <div class="bgdata" data-key="report">报告数据</div>
        {% set cnt = 0 %}
        <div class="userdata">用户基本信息</div>
        {% set item = data.debitcard.user_basic_info %}
        <div class="borderys">
            <div id="report-name" style="display: none;">{{item.name}}</div>
        	<div class="sp">姓名 ： {{item.name}}</div>
        	<div class="sp">性别 ： {{item.gender}}</div>
        	<div class="sp">证件类型 ： {{item.certify_type}}</div>
        	<div class="sp">证件号码 ： {{item.certify_no}}</div>
        	<div class="sp">手机号 ： {{item.mobile}}</div>
        	<div class="sp">家庭地址s ： {{item.address}}</div>
        	<div class="sp">邮箱 ： {{item.email}}</div>
        	<div class="sp">活跃银行卡数 ： {{item.active_card_num}}</div>
        	<div class="sp">活跃银行数 ： {{item.bank_num}}</div>
        	<div class="sp">当前工作单位名称 ： {{item.last_company_name}}</div>
        	<div class="sp">近1年工作单位数量 ： {{item.company_num_1y}}</div>
        	<div class="sp">近1年收入（元） ： {{item.income_amt_1y}}</div>
        	<div class="sp">近1年工资收入（元） ： {{item.salary_income_1y}}</div>
        	<div class="sp">近1年贷款收入（元） ： {{item.loan_in_1y}}</div>
        	<div class="sp">近1年支出（元） ： {{item.expense_1y}}</div>
        	<div class="sp">近1年消费支出（元） ： {{item.consumption_expense_1y}}</div>
        	<div class="sp">近1年还贷支出（元） ： {{item.loan_out_1y}}</div>
        </div>

        <div class="userdata">账户摘要</div>
        {% set item = data.debitcard.account_summary %}
        <div class="borderys">
        	<div class="sp">借记卡数 ： {{item.debitcard_num}}</div>
        	<div class="sp">借记卡余额（元） ： {{item.debitcard_balance}}</div>
        </div>

        <div class="userdata">借记卡摘要</div>
        {% set item = data.debitcard.debitcard_summary %}
        <div class="borderys">
        	<div class="sp">近1月消费金额（元） ： {{item.debitcard_shopping_amount_1m}}</div>
        	<div class="sp">近12月消费金额（元） ： {{item.debitcard_consume_amount_12m}}</div>
        	<div class="sp">近1月消费笔数 ： {{item.debitcard_shopping_num_1m}}</div>
        	<div class="sp">近12月消费笔数 ： {{item.debitcard_consume_count_12m}}</div>
        	<div class="sp">近1月取现金额（元） ： {{item.debitcard_cash_withdrawal_amount_1m}}</div>
        	<div class="sp">近12月取现金额（元） ： {{item.debitcard_cash_withdrawal_amount_12m}}</div>
        	<div class="sp">近1月取现笔数 ： {{item.debitcard_cash_withdrawal_num_1m}}</div>
        	<div class="sp">近12月取现笔数 ： {{item.debitcard_cash_withdrawal_count_12m}}</div>
        	<div class="sp">近1月定期记录数 ： {{item.debitcard_fixed_deposit_num_1m}}</div>
        	<div class="sp">近12月定期记录数 ： {{item.debitcard_fixed_deposit_num_12m}}</div>
        	<div class="sp">近1月定期最大金额（元） ： {{item.debitcard_lagest_fixed_deposit_amount_1m}}</div>
        	<div class="sp">近12月定期最大金额（元） ： {{item.debitcard_lagest_fixed_deposit_amount_12m}}</div>
        	<div class="sp">近1月其他消费金额（元） ： {{item.debitcard_other_consumption_amount_1m}}</div>
        	<div class="sp">近12月其他消费金额（元） ： {{item.debitcard_other_consumption_amount_12m}}</div>
        	<div class="sp">近1月其他消费笔数 ： {{item.debitcard_other_consumption_num_1m}}</div>
        	<div class="sp">近12月其他消费笔数 ： {{item.debitcard_other_consumption_num_12m}}</div>
        </div>

        <div class="userdata">借记卡</div>
        <div class="borderys">
        {% set item = data.debitcard.debitcard_detailses %}
        	<div class="sp">近3月收入金额（元）: {{item.debitcard_income_3}}</div>
            <div class="sp">近6月收入金额（元）: {{item.debitCard_Income_6}}</div>
            <div class="sp">近12月收入金额（元）: {{item.debitCard_Income_12}}</div>
            <div class="sp">近6月月均收入金额（元）: {{item.debitcard_income_6_avg}}</div>
            <div class="sp">近12月月均收入金额（元）: {{item.debitcard_income_12_avg}}</div>
            <div class="sp">近3月工资收入金额（元）: {{item.salary_income_amount_3m}}</div>
            <div class="sp">近6月工资收入金额（元）: {{item.salary_income_amount_6m}}</div>
            <div class="sp">近12月工资收入金额（元）: {{item.salary_income_amount_12m}}</div>
            <div class="sp">近6月月均工资收入金额（元）: {{item.salary_income_amount_average_6m}}</div>
            <div class="sp">近12月月均工资收入金额（元）: {{item.salary_income_amount_average_12m}}</div>
            <div class="sp">近3月有工资收入月数: {{item.salary_income_month_num_3}}</div>
            <div class="sp">近6月有工资收入月数: {{item.salary_income_month_num_6}}</div>
            <div class="sp">近12月有工资收入月数: {{item.salary_income_month_num_12}}</div>
            <div class="sp">近3月贷款收入金额: {{item.loan_in_amount_3}}</div>
            <div class="sp">近6月贷款收入金额: {{item.loan_in_amount_6}}</div>
            <div class="sp">近12月贷款收入金额: {{item.loan_in_amount_12}}</div>
            <div class="sp">近6月月均贷款收入金额: {{item.loan_in_amount_avg_6}}</div>
            <div class="sp">近12月月均贷款收入金额: {{item.loan_in_amount_avg_12}}</div>
            <div class="sp">近3月有贷款收入月数: {{item.loan_in_month_num_3}}</div>
            <div class="sp">近6月有贷款收入月数: {{item.loan_in_month_num_6}}</div>
            <div class="sp">近12月有贷款收入月数: {{item.loan_in_month_num_12}}</div>
            <div class="sp">近3月支出金额（元）: {{item.debitcard_outcome_3}}</div>
            <div class="sp">近6月支出金额（元）: {{item.debitcard_outcome_6}}</div>
            <div class="sp">近12月支出金额（元）: {{item.debitcard_outcome_12}}</div>
            <div class="sp">近6月月均支出金额（元）: {{item.debitcard_outcome_6_avg}}</div>
            <div class="sp">近12月月均支出金额（元）: {{item.debitcard_outcome_12_avg}}</div>
            <div class="sp">近3月贷款还款金额（元）: {{item.repany_loan_amount_3}}</div>
            <div class="sp">近6月贷款还款金额（元）: {{item.repany_loan_amount_6}}</div>
            <div class="sp">近12月贷款还款金额（元）: {{item.repany_loan_amount_12}}</div>
            <div class="sp">近6月月均贷款还款金额（元）: {{item.repany_loan_amount_avg_6}}</div>
            <div class="sp">近12月月均贷款还款金额（元）: {{item.repany_loan_amount_avg_12}}</div>
            <div class="sp">近3月有贷款还款月数: {{item.repany_loan_mon_num_3}}</div>
            <div class="sp">近6月有贷款还款月数: {{item.repany_loan_mon_num_6}}</div>
            <div class="sp">近12月有贷款还款月数: {{item.repany_loan_mon_num_12}}</div>
            <div class="sp">近3月无收入的月数/有流水的月数: {{item.debitcard_in_num_trans_num_ratio_3}}</div>
            <div class="sp">近6月无收入的月数/有流水的月数: {{item.debitcard_in_num_trans_num_ratio_6}}</div>
            <div class="sp">近12月无收入的月数/有流水的月数: {{item.debitcard_in_num_trans_num_ratio_12}}</div>
            <div class="sp">近3月最大余额（元）: {{item.debitcard_max_balance_3}}</div>
            <div class="sp">近6月最大余额（元）: {{item.debitcard_max_balance_6}}</div>
            <div class="sp">近12月最大余额（元）: {{item.debitcard_max_balance_12}}</div>
            <div class="sp">近3月最近余额（元）: {{item.debitcard_recently_balance_3}}</div>
            <div class="sp">近6月最近余额（元）: {{item.debitcard_recently_balance_6}}</div>
            <div class="sp">近12月最近余额（元）: {{item.debitcard_recently_balance_12}}</div>
            <div class="sp">近3月消费金额（元）: {{item.debitcard_consume_amount_3}}</div>
            <div class="sp">近6月消费金额（元）: {{item.debitcard_consume_amount_6}}</div>
            <div class="sp">近12月消费金额（元）: {{item.debitcard_consume_amount_12}}</div>
            <div class="sp">近6月月均消费金额（元）: {{item.debitcard_consume_amount_6_avg}}</div>
            <div class="sp">近12月月均消费金额（元）: {{item.debitcard_consume_amount_12_avg}}</div>
            <div class="sp">近3月消费笔数: {{item.debitcard_consume_count_3}}</div>
            <div class="sp">近6月消费笔数: {{item.debitcard_consume_count_6}}</div>
            <div class="sp">近12月消费笔数: {{item.debitcard_consume_count_12}}</div>
            <div class="sp">近6月月均消费笔数: {{item.debitcard_consume_count_6_avg}}</div>
            <div class="sp">近12月月均消费笔数: {{item.debitcard_consume_count_12_avg}}</div>
            <div class="sp">近3月最大连续消费月数: {{item.debitcard_max_continue_consume_month_3}}</div>
            <div class="sp">近6月最大连续消费月数: {{item.debitcard_max_continue_consume_month_6}}</div>
            <div class="sp">近12月最大连续消费月数: {{item.debitcard_max_continue_consume_month_12}}</div>
            <div class="sp">近3月取现金额（元）: {{item.debitcard_withdraw_amount_3}}</div>
            <div class="sp">近6月取现金额（元）: {{item.debitcard_withdraw_amount_6}}</div>
            <div class="sp">近12月取现金额（元）: {{item.debitcard_withdraw_amount_12}}</div>
            <div class="sp">近6月月均取现金额（元）: {{item.debitcard_withdraw_amount_6_avg}}</div>
            <div class="sp">近12月月均取现金额（元）: {{item.debitcard_withdraw_amount_12_avg}}</div>
            <div class="sp">近3月取现笔数: {{item.debitcard_withdraw_count_3}}</div>
            <div class="sp">近6月取现笔数: {{item.debitcard_withdraw_count_6}}</div>
            <div class="sp">近12月取现笔数: {{item.debitcard_withdraw_count_12}}</div>
            <div class="sp">近6月月均取现笔数: {{item.debitcard_withdraw_count_6_avg}}</div>
            <div class="sp">近12月月均取现笔数: {{item.debitcard_withdraw_count_12_avg}}</div>
            <div class="sp">近3月其他费用金额（元）: {{item.other_fee_3}}</div>
            <div class="sp">近6月其他费用金额（元）: {{item.other_fee_6}}</div>
            <div class="sp">近12月其他费用金额（元）: {{item.other_fee_12}}</div>
            <div class="sp">近6月月均其他费用金额（元）: {{item.other_fee_avg_6}}</div>
            <div class="sp">近12月月均其他费用金额（元）: {{item.other_fee_avg_12}}</div>
            <div class="sp">近3月定期最近一次金额（元）: {{item.regular_savings_recent_3}}</div>
            <div class="sp">近6月定期最近一次金额（元）: {{item.regular_savings_recent_6}}</div>
            <div class="sp">近12月定期最近一次金额（元）: {{item.regular_savings_recent_12}}</div>
            <div class="sp">近3月定期最大金额（元）: {{item.regular_savings_max_3}}</div>
            <div class="sp">近6月定期最大金额（元）: {{item.regular_savings_max_6}}</div>
            <div class="sp">近12月定期最大金额（元）: {{item.regular_savings_max_12}}</div>
            <div class="sp">近3月未到期定期存款金额: {{item.undue_fixed_deposit_amount_3}}</div>
            <div class="sp">近6月未到期定期存款金额: {{item.undue_fixed_deposit_amount_6}}</div>
            <div class="sp">近12月未到期定期存款金额: {{item.undue_fixed_deposit_amount_12}}</div>
            <div class="sp">近6月月均未到期定期存款金额: {{item.undue_fixed_deposit_amount_avg_6}}</div>
            <div class="sp">近12月月均未到期定期存款金额: {{item.undue_fixed_deposit_amount_avg_12}}</div>
            <div class="sp">近3月定期存款金额: {{item.fixed_deposit_amount_3}}</div>
            <div class="sp">近6月定期存款金额: {{item.fixed_deposit_amount_6}}</div>
            <div class="sp">近12月定期存款金额: {{item.fixed_deposit_amount_12}}</div>
            <div class="sp">近6月月均定期存款金额: {{item.fixed_deposit_amount_avg_6}}</div>
            <div class="sp">近12月月均定期存款金额: {{item.fixed_deposit_amount_avg_12}}</div>
          </div>

    <div class="userdata">借记卡未到期定期详情</div>
    {% set content = data.debitcard.debitcard_undue_regular_basis_list %}
    {% for item in content%}
    <div class="borderys">
    	<div class="sp">卡号 ： {{item.cardId}}</div>
    	<div class="sp">金额 ： {{item.balance}}</div>
    	<div class="sp">到期日期 ： {{item.duedate}}</div>
    	<div class="sp">期数 ： {{item.period}}</div>
    </div>
    {% endfor %}

    <div class="userdata">工作单位详情</div>
    {% set content = data.debitcard.work_details_list %}
    {% for item in content%}
    <div class="borderys">
    	<div class="sp">单位名称 ： {{item.company_name}}</div>
    	<div class="sp">首次工资收入时间 ： {{item.first_salary_time}}</div>
    	<div class="sp">最近工资收入时间 ： {{item.last_salary_time}}</div>
    	<div class="sp">连续有工资收入月数 ： {{item.continuous_salary_mon_num}}</div>
    </div>
    {% endfor %}
</div>

<script type="text/javascript">
    $('#dl').click(function() {
        $("#report-box").wordExport('储蓄卡报告-'+$('#report-name').text());
    });

    $('#bills').click(function() {
        var task_id = $('#bills').attr('data-taskid');
        if (task_id.length > 0) {
            //ajax 提取账单数据
            $.ajax({
                type : "post", 
                url : AJAXBASEURL + 'Qiye/wangyinbills', 
                data : {task_id: task_id},
                datatype: 'json',
                success : function(res){ 
                    res = JSON.parse(res);
                    console.log(res.data);
                    $('#bills_box').html('');
                    var str = '';
                    var temp = '';
                    for (var i = 0; i < res.data.length; i++) {
                        console.log(res.data[i].data[0].shopping_sheets);
                        temp = res.data[i].data[0].shopping_sheets;
                        if (temp.length > 0) {
                            //获取月份
                            var month = temp[0].trans_date.substring(0,7);
                            str += '<div style="margin-bottom:5px;"><div>' + month + '</div>';
                        }
                        for (var y = 0; y < temp.length; y++) {
                            str += '<div>';
                            //生成页面
                            str += '<div class="sp">排序 : ' + temp[y].order_index + '</div>';
                            str += '<div class="sp">消费金额 : ' + temp[y].amount_money + '</div>';
                            str += '<div class="sp">消费类型 : ' + temp[y].category + '</div>';
                            str += '<div class="sp">交易时间 : ' + temp[y].trans_date + '</div>';
                            // str += '<div class="sp">记账日期 : ' + temp[y].post_date + '</div>';
                            str += '<div class="sp">备注 : ' + temp[y].remark + '</div>';
                            str += '</div>';
                        }
                        str += '</div>';
                    }

                    $("#bills_box").prepend(str);

                    $('#bills_box').dialog('open');
                } 
            });
            return true;
        }
        return false;
    });
</script>