
<html>
<?php tpl("admin_applying") ?>
<body>
<script type="text/javascript" src="/assets/lib/js/echarts.min.js"></script>
<style type="text/css">
	.date_td {
		display: inline-block;
		width: 140px;
	}
	.name_td {
		display: inline-block;
		width: 80px;
	}
	.channel_td {
		display: none;
	}
	.account_td {
		display: inline-block;
		width: 140px;
	}
	.idnumber_td {
		display: inline-block;
		width: 160px;
	}
	.loan_title_td {
		display: none;
		width: 140px;
	}
	.reaying_amount_th {
		display: inline-block;
		width: 100px;
	}

	.reaying_amount_td {
		text-align: right;
		display: inline-block;
		width: 100px;
	}
	.principal_td {
		text-align: right;
		display: inline-block;
		width: 100px;
	}
	.interest_td {
		text-align: right;
		display: inline-block;
		width: 100px;
	}

	.status_td {
		display: inline-block;
		width: 120px;
		padding-left: 10px;
	}

	.label_box {
		display: inline-block;
		width: 80px;
		border: 1px #CCC solid;
		margin: 2px;
		padding: 2px;
		text-align: center;
	}
	.datelebal_box {
		display: inline-block;
		width: 80px;
		border: 1px #CCC solid;
		margin: 2px;
		padding: 2px;
		text-align: center;
	}
	.create_excal_box {
		display: inline-block;
		width: 80px;
		border: 1px #CCC solid;
		margin: 2px;
		padding: 2px;
		text-align: center;
	}
	.content_box {
		display: inline-block;
		width: 80px;
		border: 1px #CCC solid;
		margin: 2px;
		padding: 2px;
		text-align: center;
	}
	.status_box {
		display: inline-block;
		width: 80px;
		border: 1px #CCC solid;
		margin: 2px;
		padding: 2px;
		text-align: center;
	}
	.exportlebal_box {
		display: inline-block;
		width: 80px;
		border: 1px #CCC solid;
		margin: 2px;
		padding: 2px;
		text-align: center;
	}
	.channellebal_box {
		display: inline-block;
		width: 80px;
		border: 1px #CCC solid;
		margin: 2px;
		padding: 2px;
		text-align: center;
	}
	.label_box:hover {
		cursor: pointer;
	}
	.datelebal_box:hover {
		cursor: pointer;
	}
	.create_excal_box:hover {
		cursor: pointer;
	}
	.exportlebal_box:hover {
		cursor: pointer;
	}
	.channellebal_box:hover {
		cursor: pointer;
	}
	.content_box：hover {
		cursor: pointer;
	}
	.status_box：hover {
		cursor: pointer;
	}
	.collect_tr1 {
		border:1px solid #CCC;
	}
	.collect_tr2 {
		background-color: #CCC;
	}
	.collect_div {
		display: inline-block;
		width: 100px;
		height: 20px;
		line-height: 20px;
	}
	.collect_date_div {
		display: inline-block;
		width: 140px;
		height: 20px;
		line-height: 20px;
	}
	.collect_div_amount {
		display: inline-block;
		width: 100px;
		height: 20px;
		line-height: 20px;
		text-align: right;
	}
	.label_on_select {
		color: #CCC;
		border-style: dashed;
	}
	.dn {
		display: none;
	}
	#excel-btn {
		display: inline-block;
		margin-left: 150px;
	}
	.tr_div:hover {
		background-color: #CCC;
	}
</style>
<div><span class="label_box" id="repaying_plan">还款计划</span><span class="label_box" id="repay_progress">还款进度</span><span class="label_box" id="repaying_statis">还款统计</span></div>
<div id="repay_priv">
	<span style="font-size: 20px;font-weight: 600;">还款计划(待还和垫付版)</span>
	<div id="content-filter">
		内容选择：
		<span class="content_box" id="filter-principal">本金</span>
		<span class="content_box" id="filter-interest">利息</span>
		<span class="content_box" id="filter-contentall">全部</span>
	</div>
	<div id="status-filter">
		状态选择：
		<span class="status_box" id="filter-waitrepay">待还</span>
		<span class="status_box" id="filter-replacerepay">垫付</span>
		<span class="status_box" id="filter-repayall">全部</span>
	</div>
	<div id="date-filter">
		时间区间选择：
		<span class="datelebal_box" id="filter-day">日</span>
		<span class="datelebal_box" id="filter-week">本周</span>
		<span class="datelebal_box" id="filter-nextweek">下周</span>
		<span class="datelebal_box" id="filter-month">月</span>
		<span class="datelebal_box" id="filter-all">全部</span>
		<span id="excel-btn"><span class="create_excal_box" id="export">导出</span></span>
	</div>
	<div id="channel-filter">渠道选择：<span id="channel-box"></span></div>
	<!-- <div style="color:red;font-size:30px;">数据整理中 目前数据为测试数据</div> -->
	<div id="repay_plan_page" style="padding: 1%;">
		<div style="margin-bottom: 20px;" id="repay_plan_day" class="repay_plan_box">
			<div>今日还款计划</div>
			<div style="border:#CCC solid 1px;padding: 5px;">
				<div id="today_title_box"></div>
				<hr style="margin:5px 0;" />
				<div id="today_list"></div>
			</div>
			<div>
				<div>汇总</div> 
				<div id="today_collect_title_box"></div>
				<div id="today_collect"></div>
			</div>
		</div>
		<div style="margin-bottom: 20px;" id="repay_plan_week" class="repay_plan_box">
			<?php
				function get_week(){
					$cnt = 0;
					//获取本周周二
					$time = time();
					$now=date("w",$time);
					$now=$now==0?7:$now;
					$this_Tuesday = date('Y-m-d',$time-($now-2)*86400);
					//周二所在的月
					$month = date('m', strtotime($this_Tuesday));
					for ($temp_Tuesday = $this_Tuesday; date('m', strtotime($temp_Tuesday)) == date('m', strtotime($this_Tuesday)); ) { 
						$cnt++;
						$temp_Tuesday = date('Y-m-d',strtotime($temp_Tuesday)-86400*7);
					}
					
			        return $month . '月 第' . $cnt . '周 ';
			    }
			?>
			<div><?php echo get_week();?> 本周还款计划 <?php echo ' ' . date('Y-m-d', $week_start) . ' 至 ' . date('Y-m-d', $week_end);?></div>
			<div style="border:#CCC solid 1px;padding: 5px;">
				<div id="week_title_box"></div>
				<hr style="margin:5px 0;" />
				<div id="week_list"></div>
			</div>

			<div>
				<div>汇总</div> 
				<div id="week_collect_title_box"></div>
				<div id="week_collect"></div>
			</div>
		</div>

		<div style="margin-bottom: 20px;" id="repay_plan_nextweek" class="repay_plan_box">
			<?php
				function get_nextweek(){
					$cnt = 0;
					//获取本周周二
					$time = time();
					$now=date("w",$time);
					$now=$now==0?7:$now;
					$next_Tuesday = date('Y-m-d',$time-($now-2)*86400+86400*7);
					//周二所在的月
					$month = date('m', strtotime($next_Tuesday));
					for ($temp_Tuesday = $next_Tuesday; date('m', strtotime($temp_Tuesday)) == date('m', strtotime($next_Tuesday)); ) { 
						$cnt++;
						$temp_Tuesday = date('Y-m-d',strtotime($temp_Tuesday)-86400*7);
					}
					
			        return $month . '月 第' . $cnt . '周 ';
			    }
			?>
			<div><?php echo get_nextweek();?> 周还款计划 <?php echo ' ' . date('Y-m-d', $nextweek_start) . ' 至 ' . date('Y-m-d', $nextweek_end);?><span style="display: inline-block;margin-left: 10px;font-size: 10px;">(注：跨月数据存在暂不能排序的情况待修复。)</span></div>
			<div style="border:#CCC solid 1px;padding: 5px;">
				<div id="nextweek_title_box"></div>
				<hr style="margin:5px 0;" />
				<div id="nextweek_list"></div>
			</div>

			<div>
				<div>汇总</div> 
				<div id="nextweek_collect_title_box"></div>
				<div id="nextweek_collect"></div>
			</div>
		</div>


		<div style="margin-bottom: 20px;" id="repay_plan_month" class="repay_plan_box">
			<div>本月还款计划 <?php echo ' ' . date('Y-m-d', $month_start) . ' 至 ' . date('Y-m-d', $month_end);?></div>
			<div style="border:#CCC solid 1px;padding: 5px;">
				<div id="month_title_box"></div>
				<hr style="margin:5px 0;" />
				<div id="month_list"></div>
			</div>

			<div>
				<div>汇总</div> 
				<div id="month_collect_title_box"></div>
				<div id="month_collect"></div>
			</div>
		</div>
	</div>
</div>
<div id="repay_progress_box">
	<span style="font-size: 20px;font-weight: 600;">还款进度</span>
	<div style="padding: 1%;">
		<div style="margin-bottom: 20px;">
			<div id="progress-status-filter">
				状态选择：
				<span id="progress-status-box"></span>
			</div>
			<div id="progress-date-filter">
				时间区间选择：
				<span class="datelebal_box" id="filter-progress-day">日</span>
				<span class="datelebal_box" id="filter-progress-week">本周</span>
				<span class="datelebal_box" id="filter-progress-month">月</span>
				<span class="datelebal_box" id="filter-progress-all">全部</span>
				<span id="excel-btn"><span class="create_excal_box" id="progress-export">导出</span></span>
			</div>
			<div id="progress-channel-filter">渠道选择：<span id="progress-channel-box"></span></div>
			<div style="border:#CCC solid 1px;padding: 5px;">
				<div>
					<span class="date_td">计划还款日</span><span class="name_td">姓名</span><span class="name_td">客户编号</span><span class="idnumber_td">身份证号</span><span class="reaying_amount_th">还款金额(元)</span><span class="status_td">状态</span>
				</div>
				<!-- <span class="channel_td">渠道</span><span class="loan_title_td">借款标题</span><span class="account_td">银信账号</span> -->
				<hr style="margin:5px 0;" />
				<div id="repay_list"></div>
			</div>
		</div>
	</div>
</div>

<div id="repay_statis">
	<span style="font-size: 20px;font-weight: 600;">还款统计</span>
	<div style="padding: 1%;">
	<div id="repay_statis_list"></div>
	<div>
		<div id="repay_statis_amount_pic" style="width: 600px;height: 400px;display: inline-block;"></div>
		<div id="repay_statis_orders_pic" style="width: 600px;height: 400px;display: inline-block;"></div>
	</div>
	<div>
		<!-- <div>每周还款明细</div> -->
		<div id="repay_detail_week"></div>
	</div>
	</div>
</div>
<script type="text/javascript">
	var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
	var globalData = [];

	//时间变量
	var today = '<?= mb_substr($today, 0, 10);?>';
	var week_start = '<?= date('Y-m-d', $week_start);?>';
	var week_end= '<?= date('Y-m-d', $week_end);?>';
	var month_start = '<?= date('Y-m-d', $month_start);?>';
	var month_end = '<?= date('Y-m-d', $month_end);?>';
	var progress_start = month_start;
	var progress_end = month_end;

	//筛选器配置
	var date_filter = 'all';//默认全部显示
	var channel_filter = 'all';//默认全部显示
	var content_filter = 'contentall';//默认全部显示
	var status_filter = 'repayall';//默认全部显示
	var channel_array = [];

	var progress_date_filter = 'all';//默认全部显示
	var progress_channel_filter = 'all';//默认全部显示
	var progress_status_filter = 'all';//默认全部显示

	var repay_plan_priv = [];
	var repay_plan_wait = [];
	var repay_plan_overdue = [];
	var repay_plan_progress = [];
	var repay_plan_statis = [];

	var progress_status_array = [];

	//金额数字加逗号
	function toThousands(numString) {
		var result = '', counter = 0;
		var temp = numString.split('.');
    	num = temp[0];
     	for (var i = num.length - 1; i >= 0; i--) {
        	counter++;
        	result = num.charAt(i) + result;
        	if (!(counter % 3) && i != 0) { result = ',' + result; }
    	}
    	temp[0] = result;
    	return temp.join('.');
 	}

 	//制保留2位小数，如：2，会在2后面补上00.即2.00  
    function toDecimal2(x) {  
        var f = parseFloat(x);  
        if (isNaN(f)) {  
            return false;  
        }  
        var f = Math.round(x*100)/100;  
        var s = f.toString();  
        var rs = s.indexOf('.');  
        if (rs < 0) {  
            rs = s.length;  
            s += '.';  
        }  
        while (s.length <= rs + 2) {  
            s += '0';  
        }  
        return s;  
    }

    /**
     * 生成列表的html
     * type_key：today、week、nextweek、month
     * filter：day、week、nextweek、month
     * total_tile：日、周、下周、月
     */
    function creted_list_html(data, type_key, filter, total_tile) {
    	//填数据到页面
        var html = '';
        var collect = '';
        var collect_array = [];
        var collect_total_array = [];
        collect_total_array['cnt'] = 0;
        collect_total_array['principal'] = 0;
        collect_total_array['interest'] = 0;
        collect_total_array['total'] = 0;

        if (filter != date_filter && 'all' != date_filter) {
	        $('#'+type_key+'_list').html(html);
	        $('#'+type_key+'_collect').html(collect);
        }

        for (var i = 0; i < data[type_key].length; i++) {
        	var tempi = data[type_key][i];
        	if (tempi['channel'] == channel_filter || channel_filter == 'all') {
        		if (-1 != tempi['status'].indexOf(status_filter) || 'repayall' == status_filter) {
        			html += '<div class="tr_div">';
	            	html += '<span class="date_td">'+ tempi['repay_date'] +'</span>';
	            	html += '<span class="name_td">'+ tempi['name'] +'</span>';
	            	// html += '<span class="channel_td">'+ tempi['channel'] +'</span>';
	            	html += '<span class="name_td">'+ tempi['fuserid'] +'</span>';
	            	// html += '<span class="account_td">'+ tempi['account'] +'</span>';
	            	html += '<span class="idnumber_td">'+ tempi['idnumber'] +'</span>';
	            	// html += '<span class="loan_title_td">'+ tempi['loan_title'] +'</span>';
	            	html += '<span class="reaying_amount_td">'+ toThousands(toDecimal2(tempi['repay_amount'])) +'</span>';
	            	if (!globalData.hasOwnProperty(type_key)) {
	            		globalData[type_key] = [];
	            	}
	            	var globalData_length = globalData[type_key].length;
	            	globalData[type_key][globalData_length] = [
		            	tempi['repay_date'],
		            	tempi['name'],
		            	// tempi['channel'],
		            	tempi['fuserid'],
		            	// tempi['account'],
		            	tempi['idnumber'],
		            	// tempi['loan_title'],
		            	toThousands(toDecimal2(tempi['repay_amount']))
	            	];
	            	var value_length = globalData[type_key][globalData_length].length;
	            	if ('principal' == content_filter || 'contentall' == content_filter) {
	            		html += '<span class="principal_td">'+ toThousands(toDecimal2(tempi['principal'])) +'</span>';
	            		globalData[type_key][globalData_length][value_length] = toThousands(toDecimal2(tempi['principal']));
	            		value_length++;
	            	}
	            	if ('interest' == content_filter || 'contentall' == content_filter) {
	            		html += '<span class="interest_td">'+ toThousands(toDecimal2(tempi['interest'])) +'</span>';
	            		globalData[type_key][globalData_length][value_length] = toThousands(toDecimal2(tempi['interest']));
	            		value_length++;
	            	}
	            	globalData[type_key][globalData_length][value_length] = tempi['status'];
	            	html += '<span class="status_td">'+ tempi['status'] +'</span>';
	            	html += '</div>';
	            	
	            	//汇总内容
	            	if (!collect_array.hasOwnProperty(tempi['repay_date']+'-'+tempi['channel'])) {
	            		collect_array[tempi['repay_date']+'-'+tempi['channel']] = [];
	            		collect_array[tempi['repay_date']+'-'+tempi['channel']]['date'] = '';
	            		collect_array[tempi['repay_date']+'-'+tempi['channel']]['channel'] = '';
	            		collect_array[tempi['repay_date']+'-'+tempi['channel']]['cnt'] = 0;
	            		collect_array[tempi['repay_date']+'-'+tempi['channel']]['principal'] = 0;
	            		collect_array[tempi['repay_date']+'-'+tempi['channel']]['interest'] = 0;
	            		collect_array[tempi['repay_date']+'-'+tempi['channel']]['total'] = 0;
	            	}
	            	collect_array[tempi['repay_date']+'-'+tempi['channel']]['date'] = tempi['repay_date'];
	            	collect_array[tempi['repay_date']+'-'+tempi['channel']]['cnt']++;
	            	collect_array[tempi['repay_date']+'-'+tempi['channel']]['channel'] = tempi['channel'];
	            	collect_array[tempi['repay_date']+'-'+tempi['channel']]['principal'] += tempi['principal']*100;
	            	collect_array[tempi['repay_date']+'-'+tempi['channel']]['interest'] += tempi['interest']*100;
	            	collect_array[tempi['repay_date']+'-'+tempi['channel']]['total'] += tempi['repay_amount']*100;

	            	collect_total_array['cnt']++;
		            collect_total_array['principal'] += tempi['principal']*100;
		            collect_total_array['interest'] += tempi['interest']*100;
		            collect_total_array['total'] += tempi['repay_amount']*100;
        		}
        	}
        }
        //创建汇总html
        var collect_cnt = 1;
        if (!globalData.hasOwnProperty(type_key + 'total')) {
    		globalData[type_key + 'total'] = [];
    	}
        collect += '<div class="collect_tr1"><div class="collect_div">'+total_tile+'统计合计</div><div class="collect_date_div">'+total_tile+'</div><div class="collect_div" id="day_cnt">'+collect_total_array['cnt']+'</div><div class="collect_div" id="day_channel_">'+'合计'+'</div><div class="collect_div_amount">'+ toThousands(toDecimal2(collect_total_array['interest']/100)) +'</div><div class="collect_div_amount">'+ toThousands(toDecimal2(collect_total_array['principal']/100)) +'</div><div class="collect_div_amount">'+toThousands(toDecimal2(collect_total_array['total']/100)) +'</div></div>';
        globalData[type_key + 'total'][globalData[type_key + 'total'].length] = [
        	total_tile+'统计合计',
        	total_tile,
        	collect_total_array['cnt'],
        	'合计',
        	toThousands(toDecimal2(collect_total_array['interest']/100)),
        	toThousands(toDecimal2(collect_total_array['principal']/100)),
        	toThousands(toDecimal2(collect_total_array['total']/100))
    	];
        collect += '<hr style="margin:0;padding:0;margin-bottom:2px;" />';
        for(var key in collect_array) {
        	collect_cnt++;
        	collect += '<div class="';
        	if (collect_cnt%2) {
        		collect += 'collect_tr1';
        	} else {
        		collect += 'collect_tr2';
        	}
    		collect += '"><div class="collect_div">'+total_tile+'统计</div><div class="collect_date_div">'+collect_array[key]['date']+'</div><div class="collect_div" id="day_cnt">'+collect_array[key]['cnt']+'</div><div class="collect_div" id="day_channel_">'+collect_array[key]['channel']+'</div><div class="collect_div_amount">'+ toThousands(toDecimal2(collect_array[key]['interest']/100)) +'</div><div class="collect_div_amount">'+ toThousands(toDecimal2(collect_array[key]['principal']/100)) +'</div><div class="collect_div_amount">'+toThousands(toDecimal2(collect_array[key]['total']/100)) +'</div></div>';
    		
        	globalData[type_key+'total'][globalData[type_key+'total'].length] = [
            	total_tile+'统计',
            	collect_array[key]['date'],
            	collect_array[key]['cnt'],
            	collect_array[key]['channel'],
            	toThousands(toDecimal2(collect_array[key]['interest']/100)),
            	toThousands(toDecimal2(collect_array[key]['principal']/100)),
            	toThousands(toDecimal2(collect_array[key]['total']/100))
        	];
    	}	
        $('#'+type_key+'_list').html('');
        $('#'+type_key+'_list').html(html);
        $('#'+type_key+'_collect').html('');
        $('#'+type_key+'_collect').html(collect);
    } 

    //ajax 加载还款计划数据
	function load_repay_plan_priv() {
		$.ajax({ 
	        type : "get", 
	        url : AJAXBASEURL + 'YxStatis/RepayingPlanPriv', 
	        async : false,
	        success : function(data){ 
	            var data = JSON.parse(data);
	            repay_plan_priv = data;
	            channel_array = data.channel;//更新渠道数据
	        	//重新生成渠道元素
	        	console.log(channel_array);
	        	$('#channel-box').html('');
	        	var temp_option = '<select id="channel_select">';
	        	for (var i = 0; i < channel_array.length; i++) {
	        		temp_option += '<option value='+channel_array[i] + '>'+channel_array[i]+'</option>';
	        	}
	        	temp_option += '<option value="all">全部</option>';
	        	temp_option += '</select>';
	        	$('#channel-box').append(temp_option);
	        	$("#channel_select").val(channel_filter);
	        	globalData = [];
	            //填数据到页面
	            //day
	            creted_list_html(data, 'today', 'day', '日');

	            //week
	            creted_list_html(data, 'week', 'week', '周');

	            //nextweek
	            creted_list_html(data, 'nextweek', 'nextweek', '下周');

	            //month
	            creted_list_html(data, 'month', 'month', '月');
	        } 
	    });
    }	

    //ajax加载还款进度数据
    function load_repay_plan_progress(){
    	$.ajax({ 
	        type : "get", 
	        url : AJAXBASEURL + 'YxStatis/RepayingPlanProgress', 
	        async : false,
	        success : function(data){ 
	            var data = JSON.parse(data);
	            console.log(data);
	            repay_plan_progress = data;
	            var channel_array = data.channel;//更新渠道数据
	            progress_status_array = data.status;//更新渠道数据
	            delete(data.channel);
	            delete(data.status);
	            console.log(progress_status_array);
	            //渠道选项生成
	            $('#progress-channel-box').html('');
	        	var temp_option = '<select id="progress-channel_select">';
	        	for (var i = 0; i < channel_array.length; i++) {
	        		temp_option += '<option value='+channel_array[i] + '>'+channel_array[i]+'</option>';
	        	}
	        	temp_option += '<option value="all">全部</option>';
	        	temp_option += '</select>';
	        	$('#progress-channel-box').append(temp_option);
	        	$("#progress-channel_select").val(progress_channel_filter);

	        	//状态选择生成
	        	$('#progress-status-box').html('');
	        	var temp_spans = '';
	        	for (var i = 0; i < progress_status_array.length; i++) {
	        		temp_spans += '<span class="status_box ';
	        		if (progress_status_filter == progress_status_array[i]) {
	        			temp_spans += 'label_on_select';
	        		}
	        		temp_spans += '" data-status="'+progress_status_array[i]+'">';
	        		temp_spans += progress_status_array[i];
	        		temp_spans += '</span>';
	        	}
				temp_spans += '<span class="status_box ';
				if (progress_status_filter == 'all') {
        			temp_spans += 'label_on_select';
        		}
				temp_spans += '" data-status="all">全部</span>';
				$('#progress-status-box').html(temp_spans);
				globalData = [];
	            var html = '';
	            for (var y in data) {
	            	if ('all' == progress_status_filter || progress_status_filter == y) {
	            		for (var i = 0; i < data[y].length; i++) {
		            		if (progress_channel_filter == data[y][i]['channel'] || 'all' == progress_channel_filter) {
		            			if (Date.parse(progress_start) <= Date.parse(data[y][i]['repay_date']) && Date.parse(progress_end) >= Date.parse(data[y][i]['repay_date'])) {
		            				html += '<div>';
					            	html += '<span class="date_td">'+ data[y][i]['repay_date'] +'</span>';
					            	html += '<span class="name_td">'+ data[y][i]['name'] +'</span>';
					            	// html += '<span class="name_td">'+ data[y][i]['channel'] +'</span>';
					            	html += '<span class="name_td">'+ data[y][i]['fuserid'] +'</span>';
					            	// html += '<span class="account_td">'+ data[y][i]['account'] +'</span>';
					            	html += '<span class="idnumber_td">'+ data[y][i]['idnumber'] +'</span>';
					            	// html += '<span class="loan_title_td">'+ data[y][i]['loan_title'] +'</span>';
					            	html += '<span class="reaying_amount_td">'+ toThousands(toDecimal2((data[y][i]['repay_amount']/100).toFixed(2))) +'</span>';
					            	html += '<span class="status_td">'+ data[y][i]['status'] +'</span>';
					            	html += '</div>';

					            	var progress_key = progress_status_filter;
					            	if (!globalData.hasOwnProperty(progress_key)) {
					            		globalData[progress_key] = [];
					            	}
					            	globalData[progress_key][globalData[progress_key].length] = [
					            		data[y][i]['repay_date'],
					            		data[y][i]['name'],
					            		data[y][i]['fuserid'],
					            		data[y][i]['idnumber'],
					            		toThousands(toDecimal2((data[y][i]['repay_amount']/100).toFixed(2))),
					            		data[y][i]['status']
					            	];
		            			}
		            		}
		            	}
	            	}
	            }
	            $('#repay_list').html('');
	            $('#repay_list').html(html);
	        }
	    });
    }

    //加载还款计划统计
    function load_repay_plan_static(){
    	if (repay_plan_priv.length <= 0 ) {
    		load_repay_plan_priv();
    	}
    	if (repay_plan_progress.length <= 0 ) {
	    	load_repay_plan_progress();
	    }
	    //统计数据
	    var html = '';
	    console.log(repay_plan_progress);
	    var data = repay_plan_progress;
	    var deal_data = [];
	    for (var i = 0; i < progress_status_array.length; i++) {
	    	deal_data[progress_status_array[i]] = [];
	    	deal_data[progress_status_array[i]]['笔数'] = 0;
	    	deal_data[progress_status_array[i]]['金额'] = 0;
	    }
	    console.log('初始化数组');
	    console.log(deal_data);
	    deal_week_data = {
	    	1:{
		    	'已还款':{'笔数':0,'金额':0},
		    	'已垫付':{'笔数':0,'金额':0},
		    	'垫付后已还':{'笔数':0,'金额':0},
		    	'借付':{'笔数':0,'金额':0},
		    	'待还款':{'笔数':0,'金额':0}
		    }
			,2:{
				'已还款':{'笔数':0,'金额':0},
		    	'已垫付':{'笔数':0,'金额':0},
		    	'垫付后已还':{'笔数':0,'金额':0},
		    	'借付':{'笔数':0,'金额':0},
		    	'待还款':{'笔数':0,'金额':0}
			},3:{
				'已还款':{'笔数':0,'金额':0},
		    	'已垫付':{'笔数':0,'金额':0},
		    	'垫付后已还':{'笔数':0,'金额':0},
		    	'借付':{'笔数':0,'金额':0},
		    	'待还款':{'笔数':0,'金额':0}
			},4:{
				'已还款':{'笔数':0,'金额':0},
		    	'已垫付':{'笔数':0,'金额':0},
		    	'垫付后已还':{'笔数':0,'金额':0},
		    	'借付':{'笔数':0,'金额':0},
		    	'待还款':{'笔数':0,'金额':0}
			},5:{
				'已还款':{'笔数':0,'金额':0},
		    	'已垫付':{'笔数':0,'金额':0},
		    	'垫付后已还':{'笔数':0,'金额':0},
		    	'借付':{'笔数':0,'金额':0},
		    	'待还款':{'笔数':0,'金额':0}
			}
		};
		var data_amount_array = [];
		var data_total_array = [];
		console.log(data);
        for (var y in data) {
        	html += y + ':' + data[y].length + ' ';
        	var amount_cnt = 0;
        	for (var i = 0; i < data[y].length; i++) {
    			amount_cnt = Number(amount_cnt)+Number(data[y][i]['repay_amount']);
        	}
        	html += '金额:' + (amount_cnt/100).toFixed(2) + ' <br/>';
        	console.log(y);
        	console.log(data[y]);
        	deal_data[y]['笔数'] = data[y].length;
        	deal_data[y]['金额'] = (amount_cnt/100).toFixed(2);
        }

        for (var y in deal_data) {
        	data_amount_array[data_amount_array.length] = {value:deal_data[y]['金额'], name:y};
        	data_total_array[data_total_array.length] = {value:deal_data[y]['笔数'], name:y};
        }

        var myChart = echarts.init(document.getElementById('repay_statis_amount_pic'));
        var myChart1 = echarts.init(document.getElementById('repay_statis_orders_pic'));

        var option = {
		    title : {
		        text: '还款金额分布',
		        subtext: '系统已收录账户',
		        x:'center'
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} 元({d}%)"
		    },
		    legend: {
		        orient: 'vertical',
		        left: 'left',
		        data: progress_status_array
		    },
		    series : [
		        {
		            name: '金额',
		            type: 'pie',
		            radius : '55%',
		            center: ['50%', '60%'],
		            data:data_amount_array,
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                }
		            }
		        }
		    ]
		};

		var option1 = {
		    title : {
		        text: '还款笔数分布',
		        subtext: '系统已收录账户',
		        x:'center'
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} 笔({d}%)"
		    },
		    legend: {
		        orient: 'vertical',
		        left: 'left',
		        data: progress_status_array
		    },
		    series : [
		        {
		            name: '笔数',
		            type: 'pie',
		            radius : '55%',
		            center: ['50%', '60%'],
		            data:data_total_array,
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                }
		            }
		        }
		    ]
		};
		myChart.setOption(option);
		myChart1.setOption(option1);

		//每周明细
        console.log(deal_week_data);
        $('#repay_statis_list').html('');
        $('#repay_statis_list').html(html);
    }

    function select_repay_plan_box(type){
    	if ('all' != type) {
    		$('.repay_plan_box').each(function(){
	    		$(this).addClass('dn');
	    	});
	    	$('#repay_plan_' + type).removeClass('dn');
    	} else {
    		$('.repay_plan_box').each(function(){
	    		$(this).removeClass('dn');
	    	});
    	}
    }

	//按键切换
    $('#repaying_plan').click(function(){
    	$('.label_box').each(function() {
    		$(this).removeClass('label_on_select');
    	});
    	if (repay_plan_priv.length <= 0 ) {
    		load_repay_plan_priv();
    	}
    	
    	$(this).addClass('label_on_select');
    	$('#repay_progress_box').addClass('dn');
    	$('#repay_statis').addClass('dn');
    	$('#repay_priv').removeClass('dn');
    });

    $('#repay_progress').click(function(){
    	$('.label_box').each(function() {
    		$(this).removeClass('label_on_select');
    	});
    	$(this).addClass('label_on_select');
    	if (repay_plan_progress.length <= 0 ) {
	    	load_repay_plan_progress();
	    }
    	$('#repay_priv').addClass('dn');
    	$('#repay_statis').addClass('dn');
    	$('#repay_progress_box').removeClass('dn');
    });

    $('#repaying_statis').click(function(){
    	$('.label_box').each(function() {
    		$(this).removeClass('label_on_select');
    	});
    	load_repay_plan_static();
    	$(this).addClass('label_on_select');
    	$('#repay_priv').addClass('dn');
    	$('#repay_progress_box').addClass('dn');
    	$('#repay_statis').removeClass('dn');
    });

    //内容筛选器
    $('#content-filter').on('click', '.content_box', function(){
    	console.log($(this).attr('id'));
    	switch($(this).attr('id')) {
    		case 'filter-principal' : 
    			if ('principal' != content_filter) {
    				content_filter = 'principal';
    			} else {
    				content_filter = '';
    			}
    			break;
			case 'filter-interest' : 
    			if ('interest' != content_filter) {
    				content_filter = 'interest';
    			} else {
    				content_filter = '';
    			}
    			break;
			case 'filter-contentall' : 
    			if ('contentall' != content_filter) {
    				content_filter = 'contentall';
    			} else {
    				content_filter = '';
    			}
    			break;
    	}
    	$('.content_box').each(function(){
			$(this).removeClass('label_on_select')
		});
    	if ('' != content_filter) {
			$(this).addClass('label_on_select');
    	}
    	load_title();
    	load_repay_plan_priv();
    });

    //状态筛选器
    $('#status-filter').on('click', '.status_box', function(){
    	console.log($(this).attr('id'));
    	switch($(this).attr('id')) {
    		case 'filter-waitrepay' : 
    			if ('waitrepay' != content_filter) {
    				status_filter = '待还';
    			}
    			break;
			case 'filter-replacerepay' : 
    			if ('interest' != content_filter) {
    				status_filter = '已垫付';
    			}
    			break;
			case 'filter-repayall' : 
    			if ('repayall' != content_filter) {
    				status_filter = 'repayall';
    			}
    			break;
    	}
    	$('.status_box').each(function(){
			$(this).removeClass('label_on_select')
		});
		$(this).addClass('label_on_select');
    	load_repay_plan_priv();
    });

    //时间区间筛选器
    $('#filter-week').click(function(){
    	$('.datelebal_box').each(function() {
    		$(this).removeClass('label_on_select');
    	});
    	date_filter = 'week';
    	select_repay_plan_box(date_filter);
    	load_repay_plan_priv();

    	$(this).addClass('label_on_select');
    });

    $('#filter-nextweek').click(function(){
    	$('.datelebal_box').each(function() {
    		$(this).removeClass('label_on_select');
    	});
    	date_filter = 'nextweek';
    	select_repay_plan_box(date_filter);
    	load_repay_plan_priv();

    	$(this).addClass('label_on_select');
    });

    $('#filter-month').click(function(){
    	$('.datelebal_box').each(function() {
    		$(this).removeClass('label_on_select');
    	});
    	date_filter = 'month';
    	select_repay_plan_box(date_filter);
    	load_repay_plan_priv();
    	$(this).addClass('label_on_select');
    });

	$('#filter-day').click(function(){
		$('.datelebal_box').each(function() {
    		$(this).removeClass('label_on_select');
    	});
    	date_filter = 'day';
    	select_repay_plan_box(date_filter);
    	load_repay_plan_priv();
    	$(this).addClass('label_on_select');
    });

	$('#filter-all').click(function(){
		$('.datelebal_box').each(function() {
    		$(this).removeClass('label_on_select');
    	});
    	date_filter = 'all';
    	select_repay_plan_box(date_filter);
    	load_repay_plan_priv();
    	$(this).addClass('label_on_select');
    });

    //渠道下拉
    $('#channel-box').on('change', '#channel_select', function(){
    	var target = $('#channel_select').val();
    	channel_filter = target;
    	load_repay_plan_priv();
    });

    //还款进度-渠道下拉
    $('#progress-channel-box').on('change', '#progress-channel_select', function(){
    	var target = $('#progress-channel_select').val();
    	progress_channel_filter = target;
    	load_repay_plan_progress();
    });

    //还款进度-状态筛选器
    $('#progress-status-box').on('click', '.status_box', function(){
    	if (-1 != $(this).attr('class').indexOf('label_on_select')) {
    		return false;
    	}

    	$('#progress-status-box').children('span').each(function(){
    		$(this).removeClass('label_on_select');
    	});
    	$(this).addClass('label_on_select');
    	progress_status_filter = $(this).attr('data-status');
    	load_repay_plan_progress();
    });

    //还款进度时间选择器
    $('#progress-date-filter').on('click', '.datelebal_box', function(){
    	var id = $(this).attr('id');
    	switch(id) {
    		case 'filter-progress-day' :
    			progress_date_filter = 'day';
    			progress_start = today;
    			progress_end = today;
    			break;
			case 'filter-progress-week' :
    			progress_date_filter = 'week';
    			progress_start = week_start;
    			progress_end = week_end;
    			break;
			case 'filter-progress-month' :
    			progress_date_filter = 'month';
    			progress_start = month_start;
    			progress_end = month_end;
    			break;
			case 'filter-progress-all' :
    			progress_date_filter = 'all';
    			progress_start = month_start;
    			progress_end = month_end;
    			break;
    	}
    	$('#progress-date-filter').children('span').each(function(){
    		$(this).removeClass('label_on_select');
    	});
    	$(this).addClass('label_on_select');
    	console.log(progress_date_filter);
    	load_repay_plan_progress();
    });

	//加载各类列表表头
    function load_title() {
	    var detail_title_html = '<span class="date_td">计划还款日</span><span class="name_td">姓名</span><span class="name_td">客户编号</span><span class="idnumber_td">身份证号</span><span class="reaying_amount_th">还款金额(元)</span>';
	    //<span class="channel_td">渠道</span><span class="loan_title_td">借款标题</span><span class="account_td">银信账号</span>
	    switch (content_filter) {
	    	case 'principal' :
	    		detail_title_html += '<span class="principal_td">本金(元)</span>';
	    		break;
    		case 'interest' :
	    		detail_title_html += '<span class="interest_td">利息(元)</span>';
	    		break;
    		case 'contentall' :
	    		detail_title_html += '<span class="principal_td">本金(元)</span><span class="interest_td">利息(元)</span>';
	    		break;
	    }
	    detail_title_html += '<span class="status_td">状态</span>';

	    var statis_title_html = '<div class="collect_div">统计类型</div><div class="collect_date_div">日期</div><div class="collect_div">渠道人数统计</div><div class="collect_div">渠道编号</div><div class="collect_div_amount">利息总金额</div><div class="collect_div_amount">本金总金额</div><div class="collect_div_amount">还款汇总金额</div>';

	    $('#today_title_box').html(detail_title_html);
	    $('#week_title_box').html(detail_title_html);
	    $('#nextweek_title_box').html(detail_title_html);
	    $('#month_title_box').html(detail_title_html);

	    $('#today_collect_title_box').html(statis_title_html);
	    $('#week_collect_title_box').html(statis_title_html);
	    $('#nextweek_collect_title_box').html(statis_title_html);
	    $('#month_collect_title_box').html(statis_title_html);
	}

	load_title();
    $('#repaying_plan').click();
    // load_repay_plan_priv();
    $('#filter-all').addClass('label_on_select');
    $('#filter-repayall').addClass('label_on_select');
    $('#filter-contentall').addClass('label_on_select');
    $('#filter-progress-all').addClass('label_on_select');
</script>

<script type="text/javascript">
	function repayPlanExcelCreate() {
		//表头
		var detailth = "<tr><td>计划还款日</td><td>姓名</td><td>客户编号</td><td>身份证号</td><td>还款金额(元)</td>";
		//<td>渠道</td><td>银信账号</td><td>借款标题</td>
		switch (content_filter) {
	    	case 'principal' :
	    		detailth += '<td>本金(元)</td>';
	    		break;
    		case 'interest' :
	    		detailth += '<td>利息(元)</td>';
	    		break;
    		case 'contentall' :
	    		detailth += '<td>本金(元)</td><td>利息(元)</td>';
	    		break;
	    }
	    detailth += '<td>状态</td></tr>';
		var totalth = "<tr><td>统计类型</td><td>日期</td><td>渠道人数统计</td><td>渠道编号</td><td>利息总金额</td><td>本金总金额</td><td>还款汇总总金额</td></tr>";
		//var oHtml = $("#report-box").html();
		var oHtml = '';
		for (var item in globalData) {
			oHtml += "<table>";
			switch(item) {
				case 'today' : oHtml += "<tr><td>今日明细</td></tr>";break;
				case 'week' : oHtml += "<tr><td>本周明细</td><td><?php echo get_week();?></td><td>本周还款计划</td><td><?php echo ' ' . date('Y-m-d', $week_start) . '</td><td>至</td><td>' . date('Y-m-d', $week_end);?></td></tr>";break;
				case 'nextweek' : oHtml += "<tr><td>下周明细</td><td><?php echo get_nextweek();?></td><td>本周还款计划</td><td><?php echo ' ' . date('Y-m-d', $nextweek_start) . '</td><td>至</td><td>' . date('Y-m-d', $nextweek_end);?></td></tr>";break;
				case 'month' : oHtml += "<tr><td>月明细</td></tr>";break;
				case 'todaytotal' : oHtml += "<tr><td>今日统计</td></tr>";break;
				case 'weektotal' : oHtml += "<tr><td>本周统计</td></tr>";break;
				case 'nextweektotal' : oHtml += "<tr><td>下本周统计</td></tr>";break;
				case 'monthtotal' : oHtml += "<tr><td>月统计</td></tr>";break;
				default : oHtml += "<tr>"+item+"</tr>";break;
			}
			if (-1 == item.indexOf('total')) {
				oHtml += detailth;
			} else {
				oHtml += totalth;
			}
			for (var i = 0; i < globalData[item].length; i++) {
				var temp = globalData[item][i];
				oHtml +="<tr>";
				for (var y = 0; y < temp.length; y++) {
					oHtml +="<td>"+temp[y]+"</td>";
				}
			}
			oHtml +="</tr>";
			oHtml +="<tr></tr>";
			oHtml += "</table>";
		}
		return oHtml;
	}

	function repayProgressExcelCreate() {
		//表头
		var detailth = "<tr><td>计划还款日</td><td>姓名</td><td>客户编号</td><td>身份证号</td><td>还款金额(元)</td>";
	    detailth += '<td>状态</td></tr>';
		var oHtml = '';
		for (var item in globalData) {
			oHtml += "<table>";
			oHtml += detailth;
			for (var i = 0; i < globalData[item].length; i++) {
				var temp = globalData[item][i];
				oHtml +="<tr>";
				for (var y = 0; y < temp.length; y++) {
					oHtml +="<td>"+temp[y]+"</td>";
				}
			}
			oHtml +="</tr>";
			oHtml +="<tr></tr>";
			oHtml += "</table>";
		}
		return oHtml;
	}

	function excelOut(oHtml, fileName){
		var excelHtml = "<html><head><meta charset='utf-8' /><style>  ";
		excelHtml += " table {border-collapse: collapse;}";
		excelHtml += "   th{height: 50px;font-size: 24px;font-family: '微软雅黑';font-weight: 700;}";
		excelHtml += "  tr th {border: 1px #cccccc solid;height: 40px;background: #efefef;}";
		excelHtml += "  tr td {padding: 0 40px;border: 1px #ccc solid;height: 40px;text-align: right;}";
		excelHtml += "  td {font-size: 20px;font-weight: 700;}";
		excelHtml += "</style></head><body>"+oHtml+"</body></html>";
		var excelBlob = new Blob([excelHtml], {type: 'application/text/xml'});
		var oA = document.createElement('a');
		// 利用URL.createObjectURL()方法为a元素生成blob URL
		oA.href = URL.createObjectURL(excelBlob);
		// 给文件命名
		oA.download = fileName + '-' + '测试' + '.xls';
		// 模拟点击
		oA.click();
	}

	//excel
	$('#export').click(function() {
		excelOut(repayPlanExcelCreate(), '还款计划');
	});

	$('#progress-export').click(function() {
		excelOut(repayProgressExcelCreate(), '还款进度');
	});
</script>
</body>
</html>