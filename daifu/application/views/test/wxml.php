<div>写xml 调试页</div>
<style type="text/css">
	.lab {
		display: inline-block;
		width: 140px;
		margin-right: 10px;
	}
	.input_box {
		margin-bottom: 5px;
	}
	.btn {
		display: inline-block;
		border:1px black solid;
		margin:2px;
		padding:1px;
	}
	.btn:hover{
		cursor: pointer;
	}
</style>
<div style="width:50%;float: left;">
	<form id="xml">
		<div class="input_box">
			<label class="lab">商户号：</label><input type="text" name="merchantId" value="JG2288607622">
			<label class="lab">商户级别：</label><input type="text" name="merLevel" value="2">
		</div>
		<div>
			<div class="input_box"><label class="lab">商户流水号：</label><input type="text" name="merchantOrderId" value="100000000000"></div>
			<div class="input_box"><label class="lab">交易时间：</label><input type="text" name="transTime" value="20160604130000"></div>
			<div class="input_box"><label class="lab">代付类型：</label><input type="text" name="agentPayTp" value="0"></div>
			<div class="input_box"><label class="lab">付款金额(单位分)：</label><input type="text" name="amount" value="10"></div>
			<div class="input_box"><label class="lab">收款账号：</label><input type="text" name="account" value="6226090000000048"></div>
			<div class="input_box"><label class="lab">收款人名称：</label><input type="text" name="accName" value="张三"></div>
			<div class="input_box"><label class="lab">账户类型：</label><input type="text" name="accType" value="1"></div>
			<div class="input_box"><label class="lab">收款行编号：</label><input type="text" name="accBankCode" value="11111111"></div>
		</div>
	</form>
	<span id="create_xml" class="btn">生成报文</span>
	<span id="encrypt_xml" class="btn">加密报文</span>
	<span id="dencrypt_xml" class="btn">解密报文</span>
	<span id="send_xml" class="btn">发送加密报文</span>
</div>
<div style="width:48%;float: right;">
	<div style="width: 90%;"><span>生成的报文：</span><div id="xml_box"></div></div>
	<div style="width: 90%;"><span>加密后的报文(私钥加密)：</span><div id="encrypt_xml_box" style="word-break:break-all"></div></div>
	<div style="width: 90%;"><span>解密后的报文(公钥解密)：</span><div id="dencrypt_xml_box" style="word-break:break-all"></div></div>
</div>
<div style="clear: both;"></div>
<script type="text/javascript">
	$('#create_xml').click(function(){
		console.log($("#xml").serialize());
		var info = decodeURIComponent($("#xml").serialize(), true);
		//分割字符串
		var temp = info.split("&");
		var str = '\<\?xml version="1.0" encoding="UTF-8"\?\><forpay application="Pay.Req">';
		for (var i = 0; i < temp.length; i++) {
			var params = temp[i].split("=");
			str += '<' + params[0] + '>' + params[1] + '</' + params[0] + '>';
		}
		str += '</forpay>';
		console.log(str);
		$('#xml_box').text(str);
	});

	$('#encrypt_xml').click(function(){
		var str = $('#xml_box').text();
		var merchantId = $('#merchantId').val();
		var merLevel = $('#merLevel').val();
		console.log(str);

		//送参数去加密
		$.ajax({
			url:"send",
			type: 'POST',
            data:{
				"content": str,
				"merchantId": merchantId,
				"merLevel": merLevel
			},
            dataType:"json",
            success:function(data){
            	console.log(1);
                console.log(data);
                $('#encrypt_xml_box').text(data.data);
            },
            error:function (jqXHR, textStatus, errorThrown) {
		        /*错误信息处理*/
		        console.log(jqXHR);
		        console.log(textStatus);
		        console.log(errorThrown);
		    }
		});
	});

	$('#dencrypt_xml').click(function(){
		console.log('解密');

		$.ajax({
			url:"indsend",
			data:{"content": $('#xml_box').text()},
			type: 'POST',
            dataType:"json",
            success:function(data){
            	console.log(1);
                console.log(data);
                $('#dencrypt_xml_box').text(data.data);
            },
            error:function (jqXHR, textStatus, errorThrown) {
		        /*错误信息处理*/
		        console.log(jqXHR);
		        console.log(textStatus);
		        console.log(errorThrown);
		    }
		});
	});

	$('#send_xml').click(function(){
		console.log('发送加密报文 本地调试');
		var str = $('#xml_box').text();
		var merchantId = $('#merchantId').val();
		var merLevel = $('#merLevel').val();

		//tsend
		$.ajax({
			url:"tsend",
			data:{
				"content": str,
				"merchantId": merchantId,
				"merLevel": merLevel
			},
			type: 'POST',
            dataType:"json",
            success:function(data){
            	console.log(1);
                console.log(data);
            },
            error:function (jqXHR, textStatus, errorThrown) {
		        /*错误信息处理*/
		        console.log(jqXHR);
		        console.log(textStatus);
		        console.log(errorThrown);
		    }
		});
	});
</script>

