<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta name="renderer" content="webkit">
    <meta charset="utf-8" />
    <title>管理系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <base target="_self">
    <link href="/assets/lib/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/lib/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/assets/lib/css/ace.min.css" />
    <link rel="stylesheet" href="/assets/styles/local.css" />
    <link rel="stylesheet" href="/assets/lib/js/layer/skin/default/layer.css" />
    <script src="/assets/seajs.js"></script>
    <script src="/assets/seajsConfig.js"></script>
    <script>
        var PAGE_VAR = {
            SITE_URL:'<?php echo site_url('/')?>',
            BASE_URL:'<?php echo base_url('/')?>',
        }
        String.prototype.trimSpace = function(){
            return this.replace(/\s/g,'');
        };
    </script>
</head>
<body>
<?php
/*<div class="navbar navbar-default" id="navbar" style="border-radius: 0">
    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="javascript:void(0)" class="navbar-brand">
                <small>
                    <i class="icon-leaf"></i>
                    管理系统
                </small>
            </a><!-- /.brand -->
        </div><!-- /.navbar-header -->

        <div class="navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <span class="user-info">
                            <small>欢迎光临,</small>
                            <?php echo $admin_uname?>
                        </span>
                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
<!--                        <li>-->
<!--                            <a _href="--><?php //echo site_url('usr-setting') ?><!--">-->
<!--                                <i class="icon-cog"></i>-->
<!--                                设置-->
<!--                            </a>-->
<!--                        </li>-->
<!---->
<!--                        <li>-->
<!--                            <a href="--><?php //echo site_url('usr/profile') ?><!--">-->
<!--                                <i class="icon-user"></i>-->
<!--                                个人资料-->
<!--                            </a>-->
<!--                        </li>-->
<!---->
<!--                        <li class="divider"></li>-->

                        <li>
                            <a href="<?php echo site_url('Auth/logout')?>">
                                <i class="icon-off"></i>
                                退出
                            </a>
                        </li>
                    </ul>
                </li>
            </ul><!-- /.ace-nav -->
        </div><!-- /.navbar-header -->
    </div><!-- /.container -->
</div>
*/
?>
<div class="main-container" id="main-container">
    <div class="main-container-inner">
        <a class="menu-toggler" id="menu-toggler" href="#">
            <span class="menu-text"></span>
        </a>
        <div class="sidebar" id="sidebar">
            <!--     边栏菜单       -->
            <ul class="nav nav-list">
                <?php
                if (is_array($my_menu)) {
                	//var_dump($my_menu);
                    menushow($my_menu, '控制台');
                }
                else
                {
                	menushow($menu, '控制台');
                }
                ?>
                <li>
                    <a href="<?php echo site_url('Auth/logout')?>" class="ace_tabs" style="cursor: pointer"><i class="icon-off"></i><span class="menu-text">退出</span></a>
                </li>
            </ul>
            <!-- 边栏菜单 -->

            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon-double-angle-left" data-icon1="icon-double-angle-left"
                   data-icon2="icon-double-angle-right"></i>
            </div>

        </div>
        <div class="main-content" id="page-Container">
            <ul class="nav nav-tabs padding-12 tab-color-blue background-f9" id="navTabs">

            </ul>

            <div class="tab-content" id="navTabContent" style="padding: 0;">

            </div>
            <!--            <div class="main-container-inner">-->
            <!--                <div class="main-content main-content-no-left-margin">-->
            <!--                    <div class="breadcrumbs">-->
            <!--                        <ul class="breadcrumb" id="breadcrumbs">-->
            <!--                            <li>-->
            <!--                                <i class="icon-home home-icon"></i>-->
            <!--                                <span>首页</span>-->
            <!--                            </li>-->
            <!--                            <li class="active">-->
            <?php //echo isset($breadTitle) ? $breadTitle : '详情'?><!--</li>-->
            <!--                        </ul><!-- .breadcrumb -->
            <!--                    </div>-->
            <!--                    <iframe src="-->
            <?php //echo site_url('sys/dashboard') ?><!--" frameborder="0" id="pageContainer" name="pageContainer" scrolling="auto" style="width: 100%"></iframe>-->
            <!--                </div><!-- /.main-content -->
            <!--            </div><!-- /.main-container-inner -->
        </div><!-- /.main-content -->
    </div><!-- /.main-container-inner -->
</div><!-- /.main-container -->
<div id="dialog_target">
    <div id="dialog"></div>
</div>
<div id="dialog_target_frame" style="overflow: hidden;display: none">
    <iframe frameborder="0" name="dialog_frame" id="dialog_frame" scrolling="auto"
            style="width: 100%;height: 100%;"></iframe>
</div>
<div id="warningMsg" class="alert alert-warning"
     style="position: absolute; top: -100px; z-index: 99999; left: 25%;right: 25%;">
    <button type="button" class="close" data-dismiss="alert">
        <i class="icon-remove"></i>
    </button>
    <strong>提示!</strong>
    为了达到更好的使用体验，推荐您使用IE9及以上的浏览器，或者chrom、firefox等现代浏览器!
    <br>
</div>
<div id="msg-help-block" class="alert alert-warning"
     style="position: absolute; top: -100px; z-index: 99999; left: 25%;right: 25%;">
    <strong>提示!</strong>
    <span id="msg-help-block-content"></span>
    <br>
</div>

<style>
    body {
        /*overflow: hidden;*/
    }

    iframe::after {
        content: '.';
        line-height: 0;
        height: 0;
    }
</style>
<script>
    seajs.use('apps/mainFrm');
</script>
</body>
</html>