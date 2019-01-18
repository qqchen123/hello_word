<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <title><?php echo isset($title) ? $title : '管理后台管理系统'?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- basic styles -->
    <link href="/assets/lib/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/assets/lib/css/font-awesome.min.css"/>
    <!--[if IE 7]>
    <link rel="stylesheet" href="/assets/lib/css/font-awesome-ie7.min.css" />
    <![endif]-->
    <!-- ace styles -->
    <link rel="stylesheet" href="/assets/lib/css/ace.min.css"/>
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/assets/lib/css/ace-ie.min.css"/>
    <![endif]-->
    <link rel="stylesheet" href="/assets/lib/js/layer/skin/default/layer.css">
    <script>
        String.prototype.trimSpace = function(){
            return this.replace(/\s/g,'');
        };
        if(window.parent!=self){
            window.parent.location.href='<?php echo site_url('Auth/login')?>'
        }
        var PAGE_VAR = {SITE_URL:'<?php echo site_url()?>'}
    </script>
</head>
<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="green"></i>
                            <span class="red">蜂鸟&copy; </span>
                            <span class="white">FMS资金链　</span>
                        </h1>
                        <h4 class="blue">管理系统</h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="icon-coffee green"></i>
                                        请输入您的登陆凭证
                                    </h4>

                                    <div class="space-6"></div>

                                    <form>
                                        <fieldset>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control"
                                                                   placeholder="用户名"
                                                                   id="loginUsrname"/>
															<i class="icon-user"></i>
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control"
                                                                   placeholder="密码"
                                                                   id="loginUsrpass"/>
															<i class="icon-lock"></i>
														</span>
                                            </label>

                                            <div class="space"></div>

                                            <div class="clearfix">
                                                <button type="button"
                                                        class="width-35 pull-right btn btn-sm btn-primary"
                                                        id="loginBtn">
                                                    <i class="icon-key"></i>
                                                    登陆
                                                </button>
                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>
                                </div><!-- /widget-main -->

                                <div class="toolbar clearfix">
                                    <div>
                                        <a href="#"
                                           class="forgot-password-link">
                                            <i class=""></i>
                                        </a>
                                    </div>

                                    <div>
                                        <a href="#"
                                           class="user-signup-link">
                                            <i class=""></i>
                                        </a>
                                    </div>
                                </div>
                            </div><!-- /widget-body -->
                        </div><!-- /login-box -->
                    </div><!-- /position-relative -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</div><!-- /.main-container -->
<!-- inline scripts related to this page -->
<script src="/assets/seajs.js"></script>
<script src="/assets/seajsConfig.js"></script>
<script src="/assets/lib/js/layer/layer.js"></script>
<script>
    seajs.use('apps/login')
</script>
</body>
</html>