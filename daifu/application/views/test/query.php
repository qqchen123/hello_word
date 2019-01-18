<title>代扣API支付类交易状态查询</title>
<style type="text/css">
<!--
.STYLE1 {
	font-size: 24px;
	font-weight: bold;
}
-->
</style>


</head>

<body style="margin:0">
<p><a href="./index">返回</a></p>
<div>调试例子:  商户流水号 12083820150806120216； 原始商户订单号 20150806120152； 订单日期 2016110917；</div>
<div style="margin:0 auto; width:500px;">
<form name="R01" id="R01" method="post" action="./queryorder">
<table width="500" height="394" border="0" cellpadding="1" cellspacing="1" bgcolor="#33CCFF">
  <tr>
    <td height="84" colspan="2" align="center" bgcolor="#FFFFFF"><span class="STYLE1">代扣API支付类交易查询-DEBUG</span></td>
  </tr>
  <tr>
    <td height="47" align="right" bgcolor="#FFFFFF">商户流水号：</td>
    <td bgcolor="#FFFFFF"><input name="trans_serial_no" type="text" id="trans_serial_no" size="20" maxlength="20" /></td>
  </tr>
  <tr>
    <td height="47" align="right" bgcolor="#FFFFFF">原始商户订单号：</td>
    <td bgcolor="#FFFFFF"><input name="orig_trans_id" type="text" id="orig_trans_id" size="20" maxlength="18" /></td>
  </tr>
  <tr>
    <td height="47" align="right" bgcolor="#FFFFFF">订单日期：</td>
    <td bgcolor="#FFFFFF"><input name="orig_trade_date" type="text" id="orig_trade_date" size="14" maxlength="10" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center" bgcolor="#FFFFFF"> 
    <span id="sub">查询</span>
    </tr>
	
</table>
</form>
</div>
</body>

<script type="text/javascript">
  $('#sub').click(function(){
    $.ajax({
      url:'./queryorder',
      type:'post',
      data:$('#R01').serialize(),
      dataType:"json",
      success:function(data){
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
