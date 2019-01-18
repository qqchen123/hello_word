<html>
<?php tpl("admin_applying") ?>
<body>
<!-- <script type="text/javascript" src="/assets/lib/js/echarts.js"></script> -->
<script type="text/javascript" src="/assets/lib/js/echarts.min.js"></script>
    <link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
    <!-- <title></title> -->
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>

<style type="text/css">
	.user_box {

	}
	.repaying_box {
		margin-top: 20px;
		display: inline-block;
		width: 32%;
		float: left;
	}
	.finsih_box {
		margin-top: 20px;
		display: inline-block;
		width: 32%;
		float: left;
	}
	.no_loan {
		margin-top: 20px;
		display: inline-block;
		width: 32%;
		float: left;
	}
	.box_title {
		font-size: 20px;
		font-weight: 600;
	}
	.content_box {
		height: 160px;
	}
	.total_content_box {
		height: 300px;
	}
	.clear {
		clear: both;
	}
	.red {
		color: red;
	}
	.grey {
		color: #CCC;
	}
	.content_title {
		display: inline-block;
		width: 140px;
		font-weight: 600;
	}
	.remark_num {
		display: inline-block;
		width: 20px;
	}
</style>
<div style="margin:1%;padding:0 2%;border:solid #CCC 1px;">
<div style="font-size: 20px;font-weight: 600;">状态:<?php echo $flag;?></div>
<div class="user_box">
	<div style="display: inline-block;float: left;width: 30%;">
		<div>系统用户数：<span><?php echo $total; ?></span>人</div>
		<div>总账户用户数：<span><?php echo $master_total; ?></span>人</div>
		<div>用户收录完成度：<span><?php echo round($total/$master_total*100, 2); ?></span>%</div>
	</div>
	<div style="display: inline-block;float: left;">
		<div id="user_pic" style="width: 600px;height:100px;"></div>
	</div>
	<div class="clear"></div>
</div>
<div class="repaying_box">
	<div class="box_title red">在贷</div>
	<div class="content_box">
		<div><span class="content_title">在贷总人数：</span><span><?php echo $repaying_total; ?></span>人</div>
		<div><span class="content_title">在贷订单数：</span><span><?php echo $repaying_order_total; ?></span>单</div>
		<div><span class="content_title">在贷总金额：</span><span><?php echo preg_replace('/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/', ',', $total_amount) . '.00'; ?></span>元</div>
		<div><span class="content_title">在贷账户总余额：</span><span><?php echo preg_replace('/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/', ',', explode('.', $acct_amount)[0]) . '.' . explode('.', $acct_amount)[1]; ?></span>元&nbsp;&nbsp;&nbsp;&nbsp; <a class="btn btn-primary btn-xs p310" onclick="show_detail()">查看 </a></div>
	</div>
</div>
<div class="finsih_box">
	<div class="box_title">已结清</div>
	<div class="content_box">
		<div><span class="content_title">已结清总人数：</span><span><?php echo $finish_total; ?></span>人</div>
		<div><span class="content_title">已结清账户总余额：</span><span><?php echo $finish_total_acctAmount; ?></span>元</div>
	</div>
</div>
<div class="no_loan">
	<div class="box_title grey">无借款</div>
	<div class="content_box">
		<div><span class="content_title">无借款总人数：</span><span><?php echo $no_loan_total; ?></span>人</div>
		<div><span class="content_title">无借款账户总余额：</span><span><?php echo $no_loan_total_acctAmount; ?></span>元</div>
	</div>
</div>
<div class="clear"></div>
<div class="total">
	<div class="box_title">总计</div>
	<hr style="height:1px;border:none;border-top:1px solid #555555;" />
	<div class="total_content_box">
		<div style="display: inline-block;width: 30%;float: left;margin-right: 10px;">
			<div><span class="content_title">总人数：</span><span><?php echo $repaying_total + $no_loan_total + $finish_total; ?></span>人</div>
			<?php $total_acct_amount = $acct_amount + $no_loan_total_acctAmount + $finish_total_acctAmount; ?>
			<div><span class="content_title">账户总金额：</span><span><?php echo preg_replace('/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/', ',', explode('.', $total_acct_amount)[0]) . '.' . explode('.', $total_acct_amount)[1]; ?></span>元</div>
			<div><span class="content_title">在贷订单数：</span><span><?php echo $repaying_order_total; ?></span>单</div>
			<div><span class="content_title">在贷总金额：</span><span><?php echo preg_replace('/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/', ',', $total_amount) . '.00'; ?></span>元</div>
		</div>
		<div style="display: inline-block;width: 60%;float: left;">
			<div id="user_total_pic" style="width: 49%;height: 300px;display: inline-block;"></div>
			<div id="acct_amount_total_pic" style="width: 49%;height: 300px;display: inline-block;"></div>
		</div>
		<div class="clear"></div>
	</div>
</div>

<div style="border:solid #CCC 1px;margin-top: 10px;padding: 20px;">
	<span>备注：</span>
	<div><span class="remark_num">1.</span>在贷总金额：在贷中的借款总金额，所借金额，不计算利息。</div>
	<div><span class="remark_num">2.</span>在贷人数：在贷中的客户数。</div>
	<div><span class="remark_num">3.</span>在贷账户总余额：贷中的客户的账户总余额。</div>
	<div><span class="remark_num">4.</span>在贷账户总冻结金额：在贷客户的账户总冻结额。</div>
	<div><span class="remark_num">5.</span>系统用户数：目前银信客户账户客户表中已收录的银信客户账户。</div>
	<div><span class="remark_num">6.</span>总账户用户数：总账户中出借人列表总数。</div>
	<div><span class="remark_num">7.</span>用户收录完成度：系统用户数/总账户用户数*100。</div>
	<div><span class="remark_num">8.</span>已结清客户数：拥有结清借款且没有在借的客户数。</div>
	<div><span class="remark_num">9.</span>无借款总人数：没有借款的客户数(包含借款被拒)。</div>
	<div><span class="remark_num">10.</span>无借款账户总余额：没有借款的客户(包含借款被拒)账户总余额。</div>
	<div><span class="remark_num">11.</span>已结清账户总余额：拥有结清借款且没有在借的客户账户总余额。</div>

</div>
</div>

<!-- 详情列表 开始 by 奚晓俊 -->
    <!-- 详情列表 -->
    <div id="detail-dlg" style="width:60%;min-height:200px;padding:10px 20px" class="easyui-dialog" closed="true" buttons="detail-dlg-buttons" title='在贷余额详情' data-options="modal:true">
    <div id="filter">
        搜索🔍：<input type="text" class="easyui-textbox" name="like" id="like" prompt="银信编号、客户编号、身份证号、姓名、绑定手机号" style="width:300px;">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        总余额区间💰：
        <input type="text" class="easyui-numberbox" name="min" id="min" prompt="最小金额" precision="2">--------
        <input type="text" class="easyui-numberbox" name="max" id="max" prompt="最大金额" precision="2">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button class="btn btn-success ml2" id="likeBtn"><?='查询';?></button>
    </div>
    <br>
        <table id="detail" class="easyui-datagrid" style="width:60%;height:250px"
                data-options="
                    rownumbers: true,
                    method: 'get',
                    toolbar: '#toolbar',
                    lines: true,
                    fit: true,
                    fitColumns: false,
                    border: false,
                    columns:detail_data,
                    pagination:true,
                    onSortColum: function (sort,order) {
                        $('#in_money_detail').datagrid('reload', {
                            sort: sort,
                            order: order
                    　　});
                    },
                ">
        </table>
        <script>

	        //查看详情
	        function show_detail(){
	            $('#detail-dlg').dialog('open');
	            $('#detail').datagrid('load','get_repaying_detail?min=&max=');
	        }

            //获取列表
            var detail_data = [[
                {field: 'maccount', title: '银信账号', width: 150, align:'center', 'sortable':true},
                {field: 'fuserid', title: '客户编号', width: 100, align:'center', 'sortable':true},
                {field: 'name', title: '客户姓名', width: 100, align:'center', 'sortable':true},
                {field: 'idnumber', title: '身份证号', width: 150, align:'center', 'sortable':true},
                {field: 'binding_phone', title: '绑定手机号', width: 150, align:'center', 'sortable':true},
                {field: 'acctBal', title: '可用余额', width: 100, align:'center', 'sortable':true,
                	formatter: function (value, row) {
                		if(value!==null)
                			return (parseFloat(value).toFixed(2) + '').replace(/\d{1,3}(?=(\d{3})+(\.\d*)?$)/g, '$&,');
                	}
				},
             	{field: 'acctAmount', title: '总余额', width: 100, align:'center', 'sortable':true,
					formatter: function (value, row) {
                		if(value!==null)
                			return (parseFloat(value).toFixed(2) + '').replace(/\d{1,3}(?=(\d{3})+(\.\d*)?$)/g, '$&,');
                	}
             	},
                // {field: 'frozBl', title: '冻结金额', width: 100, align:'center', 'sortable':true},
                {field: 'operate4', title: '在线详情', width: 100, align: 'center', halign: 'center',
		            formatter: function (value, row) {
		                var html = '';
		                let account = row.maccount;
		                let pwd = row.pwd;
		                html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="autologin(' + '\'' + account + '\'' + ',' + '\'' + pwd + '\'' + ')" >在线详情 </a>' + '&nbsp;&nbsp';
		                return html;
		            }
		        },
            ]];

            $('#likeBtn').on('click',function () {
		        var like = $('#like').val();
		        var min = $('#min').val();
		        var max = $('#max').val();
		        $('#detail').datagrid('load',{like:like,min:min,max:max});
		    });

            //在线详情
		    function autologin(account, pwd) {
		        const url = 'https://www.yinxinsirencaihang.com/doLogin';
		        const data = {
					username:account,
					password:pwd,
					vcode:'',
					hasToke:'true',
				}
				const re_call = function(iframe){
					console.log($(iframe));
					window.open('https://www.yinxinsirencaihang.com/account/queryAccount');
				}
				cross_post_submit(url,data,re_call);
		    }

		    /*
			* url 		post提交地址
			* data 		post提交数据
			* re_call	回调函数
		    */
		    function cross_post_submit(url,data,re_call){
		        const requestPost = ({url,data,re_call}) => {
					// 首先创建一个用来发送数据的iframe.
					const iframe = document.createElement('iframe');
					iframe.name = 'iframePost';
					iframe.style.display = 'none';
					document.body.appendChild(iframe);
					const form = document.createElement('form');
					const node = document.createElement('input');
					// 注册iframe的load事件处理程序,如果你需要在响应返回时执行一些操作的话.
					iframe.addEventListener('load', function () {
						re_call(iframe);
					});

					form.action = url;
					// 在指定的iframe中执行form
					form.target = iframe.name;
					form.method = 'post';
					for (let name in data) {
						node.name = name;
						node.value = data[name].toString();
						form.appendChild(node.cloneNode());
					}
					// 表单元素需要添加到主文档中.
					form.style.display = 'none';
					document.body.appendChild(form);
					form.submit();

					// 表单提交后,就可以删除这个表单,不影响下次的数据发送.
					document.body.removeChild(form)
				}

				// 使用方式
				requestPost({
					url: url,
					data: data,
					re_call:re_call,
				});
		    }

        </script>
    </div>

<!-- 详情列表 结束 by 奚晓俊 -->

<script type="text/javascript">
	var myChart = echarts.init(document.getElementById('user_pic'));
	var myChart1 = echarts.init(document.getElementById('user_total_pic'));
	var myChart2 = echarts.init(document.getElementById('acct_amount_total_pic'));
	var option = {
	    legend: {
	        data: ['系统用户数', '未收录数']
	    },
	    xAxis:  {
	        type: 'value'
	    },
	    yAxis: {
	        type: 'category',
	        data: ['用户']
	    },
	    series: [
	        {
	            name: '系统用户数',
	            type: 'bar',
	            stack: '总量',
	            label: {
	                normal: {
	                    show: true,
	                    position: 'insideRight'
	                }
	            },
	            data: [<?php echo round($total/$master_total*100, 2); ?>]
	        },
	        {
	            name: '未收录数',
	            type: 'bar',
	            stack: '总量',
	            label: {
	                normal: {
	                    show: true,
	                    position: 'insideRight'
	                }
	            },
	            data: [<?php echo (100 - round($total/$master_total*100, 2)); ?>]
	        }
	    ]
	};

	var option1 = {
	    title : {
	        text: '用户人数分布',
	        subtext: '系统已收录账户',
	        x:'center'
	    },
	    tooltip : {
	        trigger: 'item',
	        formatter: "{a} <br/>{b} : {c} ({d}%)"
	    },
	    legend: {
	        orient: 'vertical',
	        left: 'left',
	        data: ['在贷','已还清','未借']
	    },
	    series : [
	        {
	            name: '人数',
	            type: 'pie',
	            radius : '55%',
	            center: ['50%', '60%'],
	            data:[
	                {value:<?php echo $repaying_total;?>, name:'在贷'},
	                {value:<?php echo $finish_total;?>, name:'已还清'},
	                {value:<?php echo $no_loan_total;?>, name:'未借'},
	            ],
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

	var option2 = {
	    title : {
	        text: '账户余额分布',
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
	        data: ['在贷','已还清','未借']
	    },
	    series : [
	        {
	            name: '金额',
	            type: 'pie',
	            radius : '55%',
	            center: ['50%', '60%'],
	            data:[
	                {value:<?php echo $acct_amount;?>, name:'在贷'},
	                {value:<?php echo $finish_total_acctAmount;?>, name:'已还清'},
	                {value:<?php echo $no_loan_total_acctAmount;?>, name:'未借'},
	            ],
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
	// 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
    myChart1.setOption(option1);
    myChart2.setOption(option2);
</script>
</body>
</html>
