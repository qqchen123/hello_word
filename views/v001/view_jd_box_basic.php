<style type="text/css">
    .sp {
        width: 32%;
        border:1px solid #ccc;
        display: inline-block;
        padding: 2px;
        margin-bottom: 3px;
    }
    .sp2 {
        width: 48%;
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
        <div class="userdata"> 用户基本信息</div>
        {% set item = data.userinfo %}
        <div class="borderys">
            <div id="report-name" style="display: none;">{{item.real_name}}</div>
        	<div class="sp">用户昵称: {{item.nick}}</div>
	        <div class="sp">用户邮箱: {{item.email}}</div>
	        <div class="sp">用户生日: {{item.birthday}}</div>
	        <div class="sp">用户性别: {{item.gender}}</div>
	        <div class="sp">MX映射ID: {{item.mapping_id}}</div>
	        <div class="sp">用户名: {{item.user_name}}</div>
	        <div class="sp">登录名: {{item.login_name}}</div>
	        <div class="sp">真实姓名: {{item.real_name}}</div>
	        <div class="sp">京东VIP值: {{item.vip_count}}</div>
	        <div class="sp">小白信用分: {{item.xb_credit}}</div>
	        <div class="sp">手机号码: {{item.phone_number}}</div>
	        <div class="sp">京享值: {{item.jxz}}</div>
        </div>

        <div class="bgdata">用户收货地址信息</div>
        {% set item = data.deliveraddresses %}
        <div>
	        {% for content in item %}
	        <div class="borderys">
	        	<div class="sp">MX映射ID: {{content.mapping_id}}</div>
	            <div class="sp">收件人姓名: {{content.name}}</div>
	            <div class="sp">收货地址: {{content.address}}</div>
	            <div class="sp">收货地址的省份: {{content.province}}</div>
	            <div class="sp">收货地址的城市: {{content.city}}</div>
	            <div class="sp">是否默认收货地址: {{content.default}}</div>
	            <div class="sp">详细地址: {{content.full_address}}</div>
	            <div class="sp">电话号码: {{content.phone_no}}</div>
            </div>
	        {% endfor %}
    	</div>

    	<div class="userdata">用户资产信息</div>
        {% set item = data.wealth %}
        <div class="borderys">
        	<div class="sp">小金库（元）: {{item.balance}}</div>
	        <div class="sp">理财金额（元）: {{item.fund}}</div>
	        <div class="sp">MX映射ID: {{item.mapping_id}}</div>
	        <div class="sp">总资产（元）: {{item.total_money}}</div>
	        <div class="sp">钱包可用余额（元）: {{item.wallet_money}}</div>
	        <div class="sp">白条可用额度（元）: {{item.available_limit}}</div>
	        <div class="sp">白条总额度（元）: {{item.credit_limit}}</div>
	        <div class="sp">白条欠款（元）: {{item.credit_waitpay}}</div>
	        <div class="sp">白条逾期（元）: {{item.delinquency_balance}}</div>
	        <div class="sp">白条循环可用额度（元）: {{item.touravailable_limit}}</div>
	        <div class="sp">白条循环总额度（元）: {{item.tourcredit_limit}}</div>
	        <div class="sp">白条循环欠款（元）: {{item.tourcredit_waitpay}}</div>
	        <div class="sp">白条循环逾期（元）: {{item.tourdelinquency_balance}}</div>
	        <div class="sp">金条总额度（元）: {{item.jtcredit_limit}}</div>
	        <div class="sp">金条可用额度（元）: {{item.jtavailable_limit}}</div>
        </div>

        <div class="userdata"> 用户交易信息(含商品)</div>
        {% set item = data.tradedetails %}
        <div class="borderys">
        	<div class="sp">物品: {{item.balance}}</div>
        	<div class="sp">大小: {{item.size}}</div>
        	<div>交易信息</div>
        	<div>
	        	{% for content in item.tradedetails %}
	        		<div class="sp">收货地址的省份: {{content.province}}</div>
	                <div class="sp">收货地址的城市: {{content.city}}</div>
	                <div class="sp">MX映射ID: {{content.mapping_id}}</div>
	                <div class="sp">交易日期: {{content.trade_time}}</div>
	                <div class="sp">交易id: {{content.trade_id}}</div>
	                <div class="sp">订单的收件人: {{content.receiver}}</div>
	                <div class="sp">订单的收件地址: {{content.receive_address}}</div>
	                <div class="sp">订单的收件电话: {{content.receive_phone}}</div>
	                <div class="sp">交易金额: {{content.amount}}</div>
	                <div class="sp">交易状态: {{content.trade_status}}</div>
	                <div>products 订单中的商品信息</div>
	                <div>
		                {% for tmp in content.products %}
		                	<div class="sp2">商品名称: {{tmp.name}}</div>
	                        <div class="sp2">商品数量: {{tmp.quantity}}</div>
	                        <div class="sp2">MX映射ID: {{tmp.mapping_id}}</div>
	                        <div class="sp2">交易id: {{tmp.trade_id}}</div>
	                        <div class="sp2">商品id: {{tmp.product_id}}</div>
	                        <div class="sp2">商品链接: {{tmp.item_url}}</div>
	                        <div class="sp2">商品图片: {{tmp.item_pic}}</div>
	                        <div class="sp2">一级商品类型ID: {{tmp.cid_level1}}</div>
	                        <div class="sp2">二级商品类型ID: {{tmp.cid_level2}}</div>
	                        <div class="sp2">一级商品类型名称: {{tmp.cname_level1}}</div>
	                        <div class="sp2">二级商品类型名称: {{tmp.cname_level2}}</div>
		                {% endfor %}
		            </div>
	        	{% endfor %}
	        </div>
	        <div class="sp">总的件数: {{item.total_size}}</div>
        </div>

        <div class="userdata"> 用户绑定银行卡信息</div>
        <div>
        	{% for content in data.bindcards %}
        	<div class="borderys">
        		<div class="sp">MX映射ID: {{content.mapping_id}}</div>
	            <div class="sp">银行名称: {{content.bank_name}}</div>
	            <div class="sp">卡号末4位: {{content.card_num}}</div>
	            <div class="sp">卡类型: {{content.card_type}}</div>
	            <div class="sp">持卡人: {{content.card_name}}</div>
	            <div class="sp">手机号: {{content.phone_num}}</div>
            </div>
        	{% endfor %}
        </div>

        <div class="userdata"> 用户白条账单信息</div>
        <div>
        	{% for content in data.btbills %}
        	<div class="borderys">
        		<div class="sp">MX映射ID: {{content.mapping_id}}</div>
	            <div class="sp">账单Id: {{content.bill_id}}</div>
	            <div class="sp">账单金额: {{content.bill_amt}}</div>
	            <div class="sp">已支付金额: {{content.payed_amt}}</div>
	            <div class="sp">退款金额: {{content.refund_amt}}</div>
	            <div class="sp">分期金额: {{content.planed_amt}}</div>
	            <div class="sp">剩余可分期金额: {{content.rest_plan_amt}}</div>
	            <div class="sp">账单状态: {{content.status}}</div>
	            <div class="sp">账单日期: {{content.bill_date}}</div>
	            <div class="sp">最后还款时间: {{content.bill_limit_date}}</div>
	            <div class="sp">剩余还款金额: {{content.sdp_amt}}</div>
	            <div class="sp">最少还款金额: {{content.min_pay_amt}}</div>
	            <div class="sp">是否本月账单: {{content.is_cur_bill}}</div>
	            <div class="sp">是否在还款时间内: {{content.is_in_pay_day}}</div>
        	</div>
        	{% endfor %}
        </div>

        <div class="userdata"> 用户金条账单信息</div>
        <div class="borderys">
        	<div>jtbills | jd_jintiao_details</div>
        	{% for item in data.jtbills.jd_jintiao_details %}
        		<div class="sp">MX映射ID: {{item.mapping_id}}</div>
                <div class="sp">账单号: {{item.loan_id}}</div>
                <div class="sp">产品ID: {{item.product_id}}</div>
                <div class="sp">产品名称: {{item.product_name}}</div>
                <div class="sp">还款状态: {{item.status_code}}</div>
                <div class="sp">借款金额: {{item.original_amount}}</div>
                <div class="sp">借款日期: {{item.retail_date}}</div>
                <div class="sp">借款时间: {{item.retail_time}}</div>
                <div class="sp">借款期数: {{item.original_terms}}</div>
                <div class="sp">当前期数: {{item.current_term}}</div>
                <div class="sp">剩余本金: {{item.outstanding_amount}}</div>
                <div class="sp">剩余期数: {{item.outstanding_terms}}</div>
                <div class="sp">京东订单号: {{item.jd_order_no}}</div>
                <div class="sp">京东金融订单号: {{item.jd_pay_order_no}}</div>
                <div class="sp">出账订单号: {{item.cashier_order_no}}</div>
                <div class="sp">最后还款时间: {{item.current_due_date}}</div>
                <div class="sp">当前还本金: {{item.total_payment_amount}}</div>
                <div class="sp">还款总金额: {{item.pay_amount}}</div>
                <div class="sp">还款计划数: {{item.pay_plan_num}}</div>
                <div class="sp">逾期计划数: {{item.over_plan_num}}</div>
                <div class="sp">逾期产生的金额: {{item.over_amount}}</div>
                <div class="sp">退款金额: {{item.refund_amount}}</div>
                <div class="sp">贷款折扣金额: {{item.loan_amount_discount}}</div>
                <div class="sp">逾期天数: {{item.over_due_days}}</div>
                <div class="sp">最大逾期天数: {{item.max_over_due_days}}</div>
                <div class="sp">还款完成日期: {{item.finish_pay_date}}</div>
                <div class="userdata"> 金条还款计划</div>
                {% for content in item.plans %}
                	<div class="borderys">
                		<div class="sp">MX映射ID: {{content.mapping_id}}</div>
                        <div class="sp">还款计划Id: {{content.plan_id}}</div>
                        <div class="sp">交易Id: {{content.trade_id}}</div>
                        <div class="sp">贷款Id: {{content.loan_id}}</div>
                        <div class="sp">当前期数: {{content.cur_plan_num}}</div>
                        <div class="sp">还款计划期数: {{content.plan_num}}</div>
                        <div class="sp">本期金额: {{content.amount}}</div>
                        <div class="sp">本期所还本金金额: {{content.pay_amount}}</div>
                        <div class="sp">逾期金额: {{content.over_amount}}</div>
                        <div class="sp">最后还款时间: {{content.limit_pay_date}}</div>
                        <div class="sp">结清时间: {{content.finish_pay_date}}</div>
                        <div class="sp">本期状态: {{content.status}}</div>
                        <div class="sp">还款计划创建时间: {{content.plan_created_time}}</div>
                        <div class="sp">还款计划最后更新时间: {{content.plan_update_time}}</div>
                        <div class="sp">是否坏账: {{content.bad_status}}</div>
                        <div class="sp">退款状态: {{content.refund_status}}</div>
                        <div class="sp">逾期状态: {{content.over_due_status}}</div>
                        <div class="sp">逾期天数: {{content.over_due_days}}</div>
                        <div class="sp">还款状态: {{content.repayment_status}}</div>
                        <div class="sp">本次计划还款开始时间: {{content.plan_start_date}}</div>
                        <div class="sp">贷款总金额: {{content.loan_amount}}</div>
                        <div class="sp">逾期天数是否在7天内: {{content.due_in_7_days}}</div>
                        <div class="sp">当前时间是否在本次还款计划区间内: {{content.is_current_time_plan}}</div>
                        <div class="sp">产品名称: {{content.product_name}}</div>
                        <div class="sp">基础日利率: {{content.day_amount_base_rate}}</div>
                        <div class="sp">日利率: {{content.day_amount_rate}}</div>
                        <div class="sp">截止当前产生的利息: {{content.day_amount}}</div>
                        <div class="sp">利息折扣: {{content.day_amount_discount}}</div>
                        <div class="sp">利息折扣率: {{content.day_amount_discount_rate}}</div>
                        <div class="sp">应付利息: {{content.should_pay_day_amount}}</div>
                        <div class="sp">利息天数: {{content.day_count}}</div>
                        <div class="sp">已付利息: {{content.payed_day_amount}}</div>
                	</div>
                {% endfor %}
        	{% endfor %}
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#dl').click(function() {
        $("#report-box").wordExport('京东报告-'+$('#report-name').text());
    });
</script>
