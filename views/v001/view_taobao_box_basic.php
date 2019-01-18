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
        {% set item = data.userinfo %}
        <div class="box1 borderys">
            <div id="report-name" style="display: none;">{{item.real_name}}</div>
            <div class="sp">性别（0-保密；1-男；2-女）: {{item.gender}}</div>
            <div class="sp">生日: {{item.birthday}}</div>
            <div class="sp">星座: {{item.constellation}}</div>
            <div class="sp">居住地: {{item.address}}</div>
            <div class="sp">家乡: {{item.hometown}}</div>
            <div class="sp">身份是否认证: {{item.authentication}}</div>
            <div class="sp">MX映射ID: {{item.mapping_id}}</div>
            <div class="sp">淘宝昵称: {{item.nick}}</div>
            <div class="sp">真实姓名: {{item.real_name}}</div>
            <div class="sp">电话号码: {{item.phone_number}}</div>
            <div class="sp">绑定的邮箱: {{item.email}}</div>
            <div class="sp">vip等级: {{item.vip_level}}</div>
            <div class="sp">成长值: {{item.vip_count}}</div>
            <div class="sp">绑定的微博账号: {{item.weibo_account}}</div>
            <div class="sp">绑定的微博昵称: {{item.weibo_nick}}</div>
            <div class="sp">淘宝头像图片: {{item.pic}}</div>
            <div class="sp">绑定的支付宝账号: {{item.alipay_account}}</div>
            <div class="sp">天猫等级: {{item.tmall_level}}</div>
            <div class="sp">最早一笔订单交易时间: {{item.first_ordertime}}</div>
            <div class="sp">用户在淘宝中的用户ID: {{item.taobao_userid}}</div>
            <div class="sp">淘气值: {{item.tao_score}}</div>
            <div class="sp">是否认证: {{item.account_auth}}</div>
            <div class="sp">居住地区域编码: {{item.address_code}}</div>
            <div class="sp">家乡区域编码: {{item.hometown_code}}</div>
            <div class="sp">安全等级: {{item.security_level}}</div>
            <div class="sp">是否设置登录密码: {{item.login_password}}</div>
            <div class="sp">是否设置密保问题: {{item.pwd_protect}}</div>
            <div class="sp">是否绑定手机号码: {{item.phone_bind}}</div>
        </div>
        <div class="userdata">用户的淘宝对应支付宝资产</div>
        {% set item = data.alipaywealth %}
        <div class="borderys">
            <div class="sp">MX映射ID: {{item.mapping_id}}</div>
            <div class="sp">账户余额（分）: {{item.balance}}</div>
            <div class="sp">余额宝历史累计收益（分）: {{item.total_profit}}</div>
            <div class="sp">余额宝金额（分）: {{item.total_quotient}}</div>
            <div class="sp">花呗当前可用额度（分）: {{item.huabei_creditamount}}</div>
            <div class="sp">花呗授信额度（分）: {{item.huabei_totalcreditamount}}</div>
        </div>

        <div class="userdata"> 淘宝收货地址</div>
        {% set item = data.deliveraddress %}
        {% for content in item %}
        <div class="borderys">
            <div class="sp">姓名: {{content.name}}</div>
            <div class="sp">地址: {{content.address}}</div>
            <div class="sp">省份: {{content.province}}</div>
            <div class="sp">城市: {{content.city}}</div>
            <div class="sp">是否默认收货地址: {{content.default}}</div>
            <div class="sp">MX映射ID: {{content.mapping_id}}</div>
            <div class="sp">详细地址: {{content.full_address}}</div>
            <div class="sp">邮编: {{content.zip_code}}</div>
            <div class="sp">电话号码: {{content.phone_no}}</div>
        </div>
        {% endfor %}

        <div class="userdata">订单的收货地址</div>
        {% set item = data.recentdeliveraddress %}
        {% for content in item %}
        <div class="borderys">
            <div class="sp">省份: {{content.province}}</div>
            <div class="sp">城市: {{content.city}}</div>
            <div class="sp">订单id: {{content.trade_id}}</div>
            <div class="sp">订单时间: {{content.trade_createtime}}</div>
            <div class="sp">订单费用: {{content.actual_fee}}</div>
            <div class="sp">收货地址中的姓名: {{content.deliver_name}}</div>
            <div class="sp">收货地址中的手机号码: {{content.deliver_mobilephone}}</div>
            <div class="sp">详细地址: {{content.deliver_address}}</div>
            <div class="sp">邮编: {{content.deliver_postcode}}</div>
        </div>
        {% endfor %}

        <div class="userdata"> 淘宝订单信息</div>
        {% set item = data.tradedetails %}
        {% for content in item %}
        <div class="borderys">
            <div class="sp">MX映射ID: {{content.mapping_id}}</div>
            <div class="sp">订单id: {{content.trade_id}}</div>
            <div class="sp">交易状态: {{content.trade_status}}</div>
            <div class="sp">交易时间: {{content.trade_createtime}}</div>
            <div class="sp">订单金额: {{content.actual_fee}}</div>
            <div class="sp">卖家id: {{content.seller_id}}</div>
            <div class="sp">卖家昵称: {{content.seller_nick}}</div>
            <div class="sp">店铺名称: {{content.seller_shopname}}</div>
            <div class="sp">交易状态中文: {{content.trade_text}}</div>
            <div>
                <div class="userdata">商品信息</div>
                {% for tmp in content.sub_orders %}
                    <div class="sp">商品数量: {{tmp.quantity}}</div>
                    <div class="sp">MX映射ID: {{tmp.mapping_id}}</div>
                    <div class="sp">订单id: {{tmp.trade_id}}</div>
                    <div class="sp">商品id: {{tmp.item_id}}</div>
                    <div class="sp">商品链接: {{tmp.item_url}}</div>
                    <div class="sp">商品图片: {{tmp.item_pic}}</div>
                    <div class="sp">商品名称: {{tmp.item_name}}</div>
                    <div class="sp">商品原价: {{item.original}}</div>
                    <div class="sp">商品真实交易价格: {{item.real_total}}</div>
                    <div class="sp">一级目录的id: {{item.cid_level1}}</div>
                    <div class="sp">二级目录的id: {{item.cid_level2}}</div>
                    <div class="sp">一级目录的名称: {{item.cname_level1}}</div>
                    <div class="sp">二级目录的名: {{item.cname_level2}}</div>
                {% endfor %}
            </div>
        </div>
        {% endfor %}
    </div>
</div>

<script type="text/javascript">
    $('#dl').click(function() {
        $("#report-box").wordExport('淘宝报告-'+$('#report-name').text());
    });
</script>