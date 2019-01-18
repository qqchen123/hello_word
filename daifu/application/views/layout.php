<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" type="text/css" href="/assets/daifu/css/layout.css">
<link rel="stylesheet" type="text/css" href="/assets/layui/layui.css">
<script src="/assets/daifu/js/jquery.min.js"></script>
<!-- <script src="/assets/daifu/js/jquery.min.js"></script> -->
<div>
    <div>
        <div class="place">
            <div class="place_r">
                <a href="<?php echo site_url('/'); ?>login/logout" class="quit">
                    安全退出
                </a>
            </div>
            <div class="place_l">
                <img src="/assets/daifu/image/peo.png" class="yugui_img">
                <span class="yugui_fout">上海钰桂信息科技有限公司</span>
            </div>
        </div>
    </div>
    <div>
        <div>
            <div class="root">
                <div class="left">
                    <div class="left_fout">
                        <div class="ziti">用户管理
                            <img src="/assets/daifu/image/xiala.png" class="img_btn ziti_img user_im">
                            <img src="/assets/daifu/image/dianji.png" class="img_btn ziti_img user_im2" style="display: none;">
                        </div>
                        <div class="lien"></div>
                        <div class="user_info user_info2" style="display: none;">
                            <a href="<?php echo site_url('/'); ?>welcome/test3" class="user_ziti">修改密码</a><br>
                            <a href="<?php echo site_url('/'); ?>welcome/test5" class="user_ziti">短信设置</a>
                        </div>
                    </div>
                    <div class="left_fout left_fout2">
                        <div class="ziti">交易管理
                            <img src="/assets/daifu/image/xiala.png" class="img_btn ziti_img tr_img">
                            <img src="/assets/daifu/image/dianji.png" class="img_btn ziti_img tr_img2" style="display: none;">
                        </div>
                        <div class="lien"></div>
                        <div class="dfdfg_info user_big" style="display: none;">
                            <a href="<?php echo site_url('/'); ?>userexcel/test9" class="user_ziti">交易明细查询</a>
                            <br>
                            <a href="<?php echo site_url('/'); ?>userexcel/test19" class="user_ziti">交易明细查询2</a>
                            <br>
                            <a href="<?php echo site_url('/'); ?>userexcel/test10" class="user_ziti">上传代付文件</a>
                            <br>
                            <a href="<?php echo site_url('/'); ?>welcome/test18" class="user_ziti">上传代扣文件</a>
                            <br>
                            <a href="<?php echo site_url('/'); ?>welcome/toE1" class="user_ziti">交易审核</a><br>
                            <a href="<?php echo site_url('/'); ?>welcome/test17" class="user_ziti">交易预存款变动查询</a><br>
                            <a href="<?php echo site_url('/'); ?>welcome/test16" class="user_ziti">交易预存款余额查询</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="midd">
			<?php $this->load->view($p, $data); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".user_im").click(function () {
            $(".user_im2").show();
            $(".user_im").hide();
            $(".user_info2").show();
        });
        $(".user_im2").click(function () {
            $(".user_im").show();
            $(".user_im2").hide();
            $(".user_info2").hide();
        });
        $(".tr_img").click(function () {
            $(".tr_img2").show();
            $(".tr_img").hide();
            $(".dfdfg_info").show();
        });
        $(".tr_img2").click(function () {
            $(".tr_img").show();
            $(".tr_img2").hide();
            $(".dfdfg_info").hide();
        });
    });
</script>