define(function(require){
    require("lib/js/bootstrap.min.js");
    require("lib/js/date-time/moment.min.js");
    require("lib/js/jquery.easyui.min.js");
    require("lib/js/easyui-lang-zh_CN.js");
    require("lib/js/date-time/bootstrap-datepicker.min.js");
    require("lib/js/date-time/daterangepicker.min.js");
    $('input[name=daterange],input[name=shorange]').daterangepicker({
        format:'YYYY-MM-DD',
        ranges: {
            '最近3天': [moment().subtract(2,'days'), moment()],
            '最近30天': [moment().subtract(32, 'days'), moment().subtract(2,'days')],
            '30天以前': [moment().subtract(1, 'year'), moment().subtract(32, 'days')]
        }
    });

    $('#olist').datagrid({
        url:'/admin/index.php/order/getlist',
        fit:true,
        fitColumns:true,
        method:'get',
        pagination:true
    });

    $('form').on('submit', function () {
        return false;
    });
    $('#query').on('click', function () {
        var a = $('form').serialize();
        var b = a.split('&');
        var param = {};
        var t = [];
        for (var i in b){
            t = b[i].split('=');
            param[t[0]] = t[1];
            t = [];
        }
        $('#olist').datagrid({
            queryParams:param
        })
    });
    $('#export').on('click', function () {
        window.location.href = '/admin/index.php/Order/download_xls?'+$('form').serialize();
    });
});