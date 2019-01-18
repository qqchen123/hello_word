<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta name="renderer" content="webkit">
    <meta charset="utf-8" />
    <title>后台管理系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <base target="_self">
    <link href="/assets/lib/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/lib/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/assets/lib/css/ace.min.css" />
    <link rel="stylesheet" href="/assets/styles/local.css" />
    <link rel="stylesheet" href="/assets/lib/js/layer/skin/default/layer.css" />
    <link rel="stylesheet" href="/assets/lib/js/layer/mobile/need/layer.css" />
    <script src="/assets/lib/js/layer/mobile/layer.js"></script>
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
    <style>
        html,body{overflow-y: auto}
    </style>
    <link rel="stylesheet" href="/assets/lib/css/datepicker.css"/>
<link rel="stylesheet" href="/assets/lib/css/daterangepicker.css"/>
<link rel="stylesheet" href="/assets/lib/css/metro/easyui.css">
<script src="/assets/lib/js/jquery.min.js"></script>

<script src="/assets/lib/js/jquery.easyui.min.js"></script>
<script src="/assets/lib/js/easyui-lang-zh_CN.js"></script>
<script src="/assets/lib/js/bootstrap.min.js"></script>
<!--<script src="/assets/lib/js/date-time/moment.min.js"></script>-->
<!--<script src="/assets/lib/js/date-time/bootstrap-datepicker.min.js"></script>-->
<!--<script src="/assets/lib/js/date-time/daterangepicker.min.js"></script>-->
<!--<script src="/assets/lib/js/bootstrapdatatable/js/jquery.dataTables.min.js"></script>-->
<!--<script src="/assets/lib/js/bootstrapdatatable/js/dataTables.bootstrap.js"></script>-->
<!--<script src="/assets/apps/merchant/tradequery.js"></script>-->
</head>