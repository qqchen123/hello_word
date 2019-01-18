    $('input[name=daterange],input[name=shorange]').daterangepicker({
        format:'YYYY-MM-DD',
        ranges: {
            '最近3天': [moment().subtract(2,'days'), moment()],
            '最近30天': [moment().subtract(32, 'days'), moment().subtract(2,'days')],
            '30天以前': [moment().subtract(1, 'year'), moment().subtract(32, 'days')]
        }
    });

    $('#example').DataTable({"language": {
        "lengthMenu": "每页显示 _MENU_ 条记录",
        "zeroRecords": "无记录",
        "info": "_PAGE_ / _PAGES_",
        "sInfo": "当前 _START_ 至 _END_ 条记录/共 _TOTAL_ ",
        "infoEmpty": "无记录",
        "infoFiltered": "(filtered from _MAX_ total records)",
        "sLoadingRecords":"加载中... ...",
        "sProcessing":"处理中... ...",
        "sSearch":"搜索",
        "oPaginate":{
            "sFirst":"首页",
            "sLast":"尾页",
            "sNext":"下一页",
            "sPrevious":"上一页"
        }
    }});

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