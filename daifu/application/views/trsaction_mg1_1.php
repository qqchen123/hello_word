<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .big_bk {
        width: 100%;
        height: 100%;
        border: 3px solid #385b99;
        margin-left: 3%;
        margin-top: 1%;
    }

    .top_bk {
        width: 100%;
        height: 43%;
        border: 3px solid #385b99;
        border-top: 0px;
        border-left: 0px;
    }

    .left_bk {
        display: inline-block;
        width: 90%;
        height: 30%;
        border: 0px solid red;
        margin-left: 10%;
        margin-top: 1%;
    }

    .start_time {
        display: inline-block;
        width: 45%;
    }

    .tr_info {
        display: inline-block;
        font-size: 20px;
        color: #292f3b;
        width: 110px;
    }

    .tr_info2 {
        display: inline-block;
        width: 36%;
        height: 50px;
        border: 2px solid #356aca;
        font-size: 16px;
        border-radius: 20px;
    }

    .tips {
        color: red;
        font-size: 14px;
        margin-left: 2%;
    }

    .query {
        display: inline-block;
        width: 18%;
        height: 54px;
        background: #356aca;
        font-size: 24px;
        line-height: 54px;
        text-align: center;
        border-radius: 30px;
        color: #ffff;
        margin-left: 16%;
        margin-top: 1%;
    }

    .midd_bk {
        width: 100%;
        height: 42%;
        border: 3px solid #385b99;
        border-top: 0px;
        border-left: 0px;
    }

    .textre {
        margin-top: 10px;
        width: 98%;
        table-layout: fixed;
        empty-cells: show;
        border-collapse: collapse;
    }

    th {
        height: 55px;
        font-size: 16px;
        color: #292f3b;
        margin-top: 2%;
        padding-left: 2%;
    }

    .lines {
        width: 100%;
        border: 3px solid #385b99;
        height: 50px;
        border-top: 0px;
        border-left: 0px;
    }

    .page {
        display: inline-block;
        width: 130px;
        height: 50px;
        border: 0px red solid;
    }

    .input_css {
        width: 33px;
        height: 33px;
        border: 1px solid #385b99;
        font-size: 18px;
        margin-left: 5%;
        font-weight: bold;
    }

    .label_css {
        font-size: 30px;
        color: #385b99;
        margin-left: 5%;
    }

    .danpage {
        position: absolute;
        display: inline-block;
        width: 50%;
        height: 50px;
        border: 2px solid #385b99;
        border-top: 0px;
        border-bottom: 0px;
        margin-left: 2%;
        margin-top: -2px;
    }

    .label_css2 {
        display: inline-block;
        padding-top: 15px;
        font-size: 16px;
        margin-left: 4%;
    }

    a {
        text-decoration: none;
    }
</style>
<link rel="stylesheet" href="/assets/layui/layui.css">
<script type="text/javascript" src="/assets/layui/layui.js"></script>


<div>
    <div class="big_bk">
        <div class="top_bk">
            <form action="" method="post">
                <div class="left_bk">
                    <div class="info_text">
                        <div class="start_time">
                            <p class="tr_info">日期范围</p>
                            <input type="text" id="test5" name="date" value="" class="tr_info2">
                        </div>
                    </div>
                    <div class="info_text">
                        <div class="start_time">
                            <p class="tr_info">商户批次号</p>
                            <input type="text" value="" name="business_pc_num" class="tr_info2">
                        </div>
                        <div class="start_time">
                            <p class="tr_info">商户订单号</p>
                            <input type="text" value="" name="bussiness_account" class="tr_info2">
                        </div>
                    </div>
                    <div class="info_text">
                        <div class="start_time">
                            <p class="tr_info">订单性质</p>
                            <select name="order_xingzhi" class="tr_info2">
                                <option> 代付订单</option>
                            </select>
                        </div>
                        <div class="start_time">
                            <p class="tr_info">订单状态</p>
                            <select name="status" class="tr_info2">
                                <option> 已盘回</option>
                            </select>
                        </div>
                    </div>
                    <input type="submit" value="查 询" class="query queryclick">
                    <input type="reset" value="返回" class="query queryclick">
                </div>
            </form>
        </div>
        <div class="midd_bk">
            <table class="textre" id="textre">
                <tr>
                    <th>商户批次号</th>
                    <th>商户订单号</th>
                    <th>收款人姓名</th>
                    <th>交易金额</th>
                    <th>账号</th>
                    <th>手续费</th>
                    <th>交易状态</th>
                    <th>最后更新时间</th>
                    <th>失败原因</th>
                </tr>
                <!--                --><?php //print_r($order);die;?>
				<?php foreach ($order['users'] as $k => $v): ?>
                    <tr>
                        <th><?php echo $v['business_pc_num']; ?></th>
                        <th><?php echo $v['bussiness_account']; ?></th>
                        <th><?php echo $v['username']; ?></th>
                        <th><?php echo $v['trade_money']; ?></th>
                        <th><?php echo $v['account']; ?></th>
                        <th><?php echo ''; ?></th>
                        <th><?php echo $v['status']; ?></th>
                        <th><?php echo $v['update_date']; ?></th>
                        <th><?php echo ''; ?></th>
                    </tr>
				<?php endforeach; ?>
            </table>
        </div>
        <div class="lines"></div>
        <div class="page">
            <!--			<label class="label_css">&lt;</label>-->
            <!--			<input type="text" value="  1" class="input_css">-->
            <!--			<label class="label_css">&gt;</label>-->
			<?php echo $order['link']; ?>

        </div>
        <div class="danpage">
            <label class="label_css2">单页显示</label>
            <input type="text" value="  3" class="input_css inputcs">
            <label class="label_css2">共1页</label>
            <label class="label_css2">总笔数： 3笔</label>
            <label class="label_css2">总金额：</label>
        </div>
    </div>
</div>
<script>
    layui.use('laydate', function () {
        var laydate = layui.laydate;
        laydate.render({
            elem: '#test5'
            , range: true
        });
    });
</script>