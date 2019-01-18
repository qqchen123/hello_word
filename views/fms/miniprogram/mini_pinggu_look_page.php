<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/bootstrap-grid.min.css">
<link rel="stylesheet" href="/assets/lib/css/zoomify.min.css">
<link rel="stylesheet" href="/assets/layui/layui.css">
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/assets/layui/layui.js"></script>
<style>
    .order_info {
        margin-top: 20px;
    }

    .order_info_btn {
        width: 100px;
        height: 35px;
        font-size: 16px;
        border-radius: 10px;
        background: #D7D7D7;
        color: white;
        line-height: 35px;
        text-align: center;
        margin-left: 40px;
    }

    .order_info_lable {
        margin-left: 10px;
        margin-top: 10px;
    }

    .order_info_font {
        display: inline-block;
        width: 200px;
        height: 30px;
        font-size: 14px;
        border: 0px red solid;
        margin-left: 50px;
    }

    img {
        border: 0;
    }

    .ban {
        display: inline-block;
        width: 500px;
        height: 600px;
        position: relative;
        overflow: hidden;
        /*margin: 40px auto 0 auto;*/
    }

    .ban2 {
        width: 500px;
        height: 500px;
        position: relative;
        overflow: hidden;
    }

    .ban2 ul {
        position: absolute;
        left: 0;
        top: 0;
    }

    .ban2 ul li {
        width: 500px;
        height: 500px;
    }

    .prev {
        float: left;
        cursor: pointer;
    }

    .num {
        height: 82px;
        overflow: hidden;
        width: 430px;
        position: relative;
        float: left;
    }

    .min_pic {
        padding-top: 10px;
        width: 500px;
    }

    .num ul {
        position: absolute;
        left: 0;
        top: 0;
    }

    .num ul li {
        width: 80px;
        height: 80px;
        margin-right: 5px;
        padding: 1px;
    }

    .num ul li.on {
        border: 1px solid red;
        padding: 0;
    }

    .prev_btn1 {
        width: 16px;
        text-align: center;
        height: 18px;
        margin-top: 40px;
        margin-right: 20px;
        cursor: pointer;
        float: left;
    }

    .next_btn1 {
        width: 16px;
        text-align: center;
        height: 18px;
        margin-top: 40px;
        cursor: pointer;
        float: right;
    }

    .prev1 {
        position: absolute;
        top: 220px;
        left: 20px;
        width: 28px;
        height: 51px;
        z-index: 9;
        cursor: pointer;
    }

    .next1 {
        position: absolute;
        top: 220px;
        right: 20px;
        width: 28px;
        height: 51px;
        z-index: 9;
        cursor: pointer;
    }

    .mhc {
        background: #000;
        width: 100%;
        opacity: 0.5;
        -moz-opacity: 0.5;
        filter: alpha(Opacity=50);
        position: absolute;
        left: 0;
        top: 0;
        display: none;
    }

    .pop_up {
        width: 500px;
        height: 500px;
        padding: 10px;
        background: #fff;
        position: fixed;
        -position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -255px;
        margin-top: -255px;
        display: none;
        z-index: 99;
    }

    .pop_up_xx {
        width: 40px;
        height: 40px;
        position: absolute;
        top: -40px;
        right: 0;
        cursor: pointer;
    }

    .pop_up2 {
        width: 500px;
        height: 500px;
        position: relative;
        overflow: hidden;
    }

    .pop_up2 {
        width: 500px;
        height: 500px;
        position: relative;
        overflow: hidden;
        float: left;
    }

    .pop_up2 ul {
        position: absolute;
        left: 0;
        top: 0;
    }

    .pop_up2 ul li {
        width: 500px;
        height: 500px;
        float: left;
    }

    body {
        margin: 0;
        padding: 0;
    }

    .images_info {
        width: 500px;
        height: 600px;
        border: 0px black solid;
        display: inline-block;
    }

    /*身份证右边的样式*/
    .right_cradidinfo {
        display: inline-block;
        width: 500px;
        height: 600px;
        border: 0px red solid;
        margin-left: 10px;
        vertical-align: top;
    }

    /*备注样式*/
    .magger_deal {
        width: 600px;
        height: 200px;
        border: 0px red solid;
    }

    .beizu_css {
        width: 400px;
        height: 100px;
        border: 1px solid #F0F0F0;
    }

    .deal_btn {
        margin-top: 10px;
        margin-left: 53%;
        width: 80px;
        height: 35px;
        background: #D7D7D7;
        border-radius: 10px;
        color: white;
        line-height: 35px;
        text-align: center;
    }
</style>

<body class="easyui-layout">
<div class="easyui-layout" data-options="fit:true">
    <!--    <div data-options="region:'west',title:'订单管理',split:true" style="width:100px;">-->
    <!--    </div>-->
    <div data-options="region:'center',title:'首页'" style="padding:5px;background:#eee;">
        <div class="easyui-layout" data-options="fit:true">

            <div data-options="region:'center'">
                <div>
                    <!-- 订单信息 -->
                    <div class="order_info">
                        <div class="order_info_btn">订单信息</div>
                        <!-- 内容 -->
                        <div class="order_info_lable">
                            <label class="order_info_font">订单编号：<span style="color: #A8A8A8"><span
                                            style="color: #A8A8A8"><?= isset($look['order']['order_num']) ? $look['order']['order_num'] : ''; ?></span>
                            </label>
                            <label class="order_info_font">业务员：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['admin_id']) ? $look['order']['admin_id'] : ''; ?></span></label>
                            <label class="order_info_font">申请日期：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['c_date']) ? $look['order']['c_date'] : ''; ?></span></label>
                        </div>
                    </div>
                    <!-- 申贷信息 -->
                    <div class="order_info">
                        <div class="order_info_btn">申贷信息</div>
                        <!-- 内容 -->
                        <div class="order_info_lable">
                            <label class="order_info_font">申贷人信名：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['user_name']) ? $look['order']['user_name'] : ''; ?></span></label>
                            <label class="order_info_font">职业类型：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['zhiye_type']) ? $look['order']['zhiye_type'] : ''; ?></span></label>
                            <label class="order_info_font">申贷金额：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['get_money']) ? $look['order']['get_money'] : ''; ?></span></label>
                            <label class="order_info_font">借款期限：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['get_money_term']) ? $look['order']['get_money_term'] : ''; ?></span></label>
                            <label class="order_info_font">借款类型：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['get_money_type']) ? $look['order']['get_money_type'] : ''; ?></span></label>
                            <label class="order_info_font">上家余额：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['pre_yu_e']) ? $look['order']['pre_yu_e'] : ''; ?></span></label>
                            <label class="order_info_font">抵押类型：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['di_ya_type']) ? $look['order']['di_ya_type'] : ''; ?></span></label>
                            <label class="order_info_font">一抵金额：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['yidi_yue']) ? $look['order']['yidi_yue'] : ''; ?></span></label>
                            <label class="order_info_font">产品属性：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['product_type']) ? $look['order']['product_type'] : ''; ?></span></label>
                            <label class="order_info_font">机构代码：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['jg_code']) ? $look['order']['jg_code'] : ''; ?></span></label>
                            <label class="order_info_font">产品名称：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['product_name']) ? $look['order']['product_name'] : ''; ?></span></label>
                        </div>
                    </div>
                    <!-- 房本信息 -->
                    <div class="order_info">
                        <div class="order_info_btn">房本信息</div>
                        <!-- 内容 -->
                        <div class="order_info_lable">
                            <label class="order_info_font">建筑面积：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['house_area']) ? $look['order']['house_area'] : ''; ?></span></label>
                            <label class="order_info_font">房屋朝向：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['toward']) ? $look['order']['toward'] : ''; ?></span></label>
                            <label class="order_info_font">系统评估价格：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['ZheKouZongJia']) ? $look['order']['ZheKouZongJia'] : ''; ?></span></label>
                            <label class="order_info_font">机构初评价格：<?php //echo $look['order'][''];?></label><br>
                            <label class="order_info_font">房屋坐落：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['full_house_name']) ? $look['order']['full_house_name'] : ''; ?></span></label>
                            <label class="order_info_font">竣工日期：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['finish_date']) ? $look['order']['finish_date'] : ''; ?></span></label>
                            <label class="order_info_font">规划用途：<span
                                        style="color: #A8A8A8"><?= isset($look['order']['gui_hua_yong_tu']) ? $look['order']['gui_hua_yong_tu'] : ''; ?></span></label>
                        </div>
                    </div>
                </div>

                <div id="tt" class="easyui-tabs" style="width:100%;">

                    <div title="身份证" style="padding:20px;display:none;">
                        <!-- 图片信息 -->
                        <div class="images_info">
                            <div class="ban" id="demo1">
                                <div class="ban2" id="ban_pic1">
                                    <div class="prev1" id="prev1">
                                        <img src="/assets/lanrenzhijia/images/index_tab_l.png" width="28" height="51"
                                             alt=""/>
                                    </div>
                                    <div class="next1" id="next1">
                                        <img src="/assets/lanrenzhijia/images/index_tab_r.png" width="28" height="51"
                                             alt=""/>
                                    </div>
                                    <ul>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                    </ul>
                                </div>
                                <div class="min_pic">
                                    <div class="prev_btn1" id="prev_btn1"><img
                                                src="/assets/lanrenzhijia/images/feel3.png"
                                                width="9" height="18" alt=""/></div>
                                    <div class="num clearfix" id="ban_num1">
                                        <ul>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                        </ul>
                                    </div>
                                    <div class="next_btn1" id="next_btn1"><img
                                                src="/assets/lanrenzhijia/images/feel4.png"
                                                width="9" height="18" alt=""/></div>
                                </div>
                            </div>
                            <div class="mhc"></div>
                            <div class="pop_up" id="demo2">
                                <div class="pop_up_xx">
                                    <img src="/assets/lanrenzhijia/images/chacha3.png" width="40" height="40" alt=""/>
                                </div>
                                <div class="pop_up2" id="ban_pic2">
                                    <div class="prev1" id="prev2">
                                        <img src="/assets/lanrenzhijia/images/index_tab_l.png"
                                             width="28" height="51" alt=""/></div>
                                    <div class="next1" id="next2">
                                        <img src="/assets/lanrenzhijia/images/index_tab_r.png" width="28" height="51"
                                             alt=""/></div>
                                    <ul>
                                        <li><a href="javascript:;">
                                                <img src="/assets/lanrenzhijia/images/b1.jpg" width="500"
                                                     height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- 身份证信息 -->
                        <div class="right_cradidinfo">
                            <h3>身份证正面</h3><br><br>
                            <label>姓名：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['name']) ? $look['user']['name'] : ''; ?></span></label><br><br>
                            <label>身份证：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['idnumber']) ? $look['user']['idnumber'] : ''; ?></span></label><br><br>
                            <label>年龄：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth']) ? $look['user']['birth'] : ''; ?></span></label><br><br>
                            <label>性别：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['sex']) ? $look['user']['sex'] : ''; ?></span></label><br><br>
                            <label>出生日期：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth']) ? $look['user']['birth'] : ''; ?></span></label><br><br>
                            <label>出生地址：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth_area']) ? $look['user']['birth_area'] : ''; ?></span></label><br><br>
                            <label>年龄判断：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth']) ? $look['user']['birth'] : ''; ?></span></label><br><br>
                            <label>住址：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth_area']) ? $look['user']['birth_area'] : ''; ?></span></label><br><br>
                            <h3>身份证反面</h3><br><br>
                            <label>产证编号：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth_area']) ? $look['user']['birth_area'] : ''; ?></span></label><br><br>
                            <label>登记日期：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth_area']) ? $look['user']['birth_area'] : ''; ?></span></label><br><br>
                        </div>

                    </div>

                    <!-- 房产证 -->
                    <div title="房产证" style="padding:20px;display:none;">
                        <div class="images_info">
                            <!--                        房产证-->
                            <div class="ban" id="2demo1">
                                <div class="ban2" id="2ban_pic1">
                                    <div class="prev1" id="2prev1">
                                        <img src="/assets/lanrenzhijia/images/index_tab_l.png" width="28" height="51"
                                             alt=""/>
                                    </div>
                                    <div class="next1" id="2next1">
                                        <img src="/assets/lanrenzhijia/images/index_tab_r.png" width="28" height="51"
                                             alt=""/>
                                    </div>
                                    <ul>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                    </ul>
                                </div>
                                <div class="min_pic">
                                    <div class="prev_btn1" id="2prev_btn1"><img
                                                src="/assets/lanrenzhijia/images/feel3.png"
                                                width="9" height="18" alt=""/></div>
                                    <div class="num clearfix" id="2ban_num1">
                                        <ul>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                            <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                            width="80" height="80" alt=""/></a></li>
                                        </ul>
                                    </div>
                                    <div class="next_btn1" id="2next_btn1"><img
                                                src="/assets/lanrenzhijia/images/feel4.png"
                                                width="9" height="18" alt=""/></div>
                                </div>
                            </div>

                            <div class="mhc"></div>

                            <div class="pop_up" id="2demo2">
                                <div class="pop_up_xx">
                                    <img src="/assets/lanrenzhijia/images/chacha3.png" width="40" height="40" alt=""/>
                                </div>
                                <div class="pop_up2" id="2ban_pic2">
                                    <div class="prev1" id="2prev2">
                                        <img src="/assets/lanrenzhijia/images/index_tab_l.png"
                                             width="28" height="51" alt=""/></div>
                                    <div class="next1" id="2next2">
                                        <img src="/assets/lanrenzhijia/images/index_tab_r.png" width="28" height="51"
                                             alt=""/></div>
                                    <ul>
                                        <li><a href="javascript:;">
                                                <img src="/assets/lanrenzhijia/images/b1.jpg" width="500"
                                                     height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b1.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b2.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b3.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b4.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                        <li><a href="javascript:;"><img src="/assets/lanrenzhijia/images/b5.jpg"
                                                                        width="500"
                                                                        height="500" alt=""/></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="right_cradidinfo">
                            <h3>房产证第一页</h3><br><br>
                            <label>照片件：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['name']) ? $look['user']['name'] : ''; ?></span></label><br><br>
                            <label>旧证件：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['idnumber']) ? $look['user']['idnumber'] : ''; ?></span></label><br><br>
                            <label>产证编号：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth']) ? $look['user']['birth'] : ''; ?></span></label><br><br>
                            <label>登记日：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['sex']) ? $look['user']['sex'] : ''; ?></span></label><br><br>
                            <h3>机构估价</h3><br><br>
                            <label>机构估价：<span
                                        style="color: #A8A8A8"><?= isset($look['user']['birth_area']) ? $look['user']['birth_area'] : ''; ?></span></label><br><br>
                        </div>

                    </div>

                </div>
                <div style="display: <?= isset($look['look']) ? 'none' : ''; ?>;">
                    <div id="p"  class="easyui-panel" title="审批意见"
                         style="width:100%;height:300px;padding:10px;background:#fafafa;" data-options="">
                        <!-- 备注处理 -->
                        <div class="magger_deal" style=" margin-left: 50px; ">
                            <label>备注</label><br>
                            <!--                        <input type="texterea" class="beizu_css" id="beizhu">-->
                            <textarea class="beizu_css" id="beizhu"></textarea>
                            <br>
                            <br>
                            <div class="btn-group" data-toggle="buttons" style="padding-left: 250px;">
                                <button type="button" class="btn btn-primary" onclick="deal(40)">处理</button>
                                <button type="button" class="btn btn-danger" onclick="deal(24)">退回</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.3.0/jquery.min.js"></script>
<script src="/assets/lanrenzhijia/js/pic_tab.js"></script>
<script type="text/javascript">
    jq('#demo1').banqh({
        box: "#demo1",//总框架
        pic: "#ban_pic1",//大图框架
        pnum: "#ban_num1",//小图框架
        prev_btn: "#prev_btn1",//小图左箭头
        next_btn: "#next_btn1",//小图右箭头
        pop_prev: "#prev2",//弹出框左箭头
        pop_next: "#next2",//弹出框右箭头
        prev: "#prev1",//大图左箭头
        next: "#next1",//大图右箭头
        pop_div: "#demo2",//弹出框框架
        pop_pic: "#ban_pic2",//弹出框图片框架
        pop_xx: ".pop_up_xx",//关闭弹出框按钮
        mhc: ".mhc",//朦灰层
        autoplay: true,//是否自动播放
        interTime: 5000,//图片自动切换间隔
        delayTime: 400,//切换一张图片时间
        pop_delayTime: 400,//弹出框切换一张图片时间
        order: 0,//当前显示的图片（从0开始）
        picdire: true,//大图滚动方向（true为水平方向滚动）
        mindire: true,//小图滚动方向（true为水平方向滚动）
        min_picnum: 3,//小图显示数量
        pop_up: true//大图是否有弹出框
    })
    jq('#2demo1').banqh({
        box: "#2demo1",//总框架
        pic: "#2ban_pic1",//大图框架
        pnum: "#2ban_num1",//小图框架
        prev_btn: "#2prev_btn1",//小图左箭头
        next_btn: "#2next_btn1",//小图右箭头
        pop_prev: "#2prev2",//弹出框左箭头
        pop_next: "#2next2",//弹出框右箭头
        prev: "#2prev1",//大图左箭头
        next: "#2next1",//大图右箭头
        pop_div: "#2demo2",//弹出框框架
        pop_pic: "#2ban_pic2",//弹出框图片框架
        pop_xx: ".pop_up_xx",//关闭弹出框按钮
        mhc: ".mhc",//朦灰层
        autoplay: true,//是否自动播放
        interTime: 5000,//图片自动切换间隔
        delayTime: 400,//切换一张图片时间
        pop_delayTime: 400,//弹出框切换一张图片时间
        order: 0,//当前显示的图片（从0开始）
        picdire: true,//大图滚动方向（true为水平方向滚动）
        mindire: true,//小图滚动方向（true为水平方向滚动）
        min_picnum: 3,//小图显示数量
        pop_up: true//大图是否有弹出框
    });

    function deal(status) {

        let bz = $('#beizhu').val();
        let user_id = <?php echo $_SESSION['fms_id'];?>;
        let bd_id = <?php echo $look['order']['bd_id'];?>;
        $.ajax({
            type: "POST",
            url: 'mini_pinggu_deal',
            data: {
                note: bz,
                pg_admin_id: user_id,
                bd_id: bd_id,
                status: status,
            },
            dataType: "json",
            success(data) {
                if (data.code == 1) {
                    $.messager.show({
                        title: '提示',
                        msg: data.msg
                    });
                } else {
                    $.messager.show({
                        title: '提示',
                        msg: data.msg
                    });
                }
            }
        });
    }
</script>
</body>