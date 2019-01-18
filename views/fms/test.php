<html>
<?php tpl("admin_applying") ?>
<body>
    <link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
    <link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
    <!-- <title></title> -->
    <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/lib/js/nunjucks.js"></script>
    
<style>
    td {
        border-top: none !important;
        vertical-align: middle !important;
    }

    .tlabel {
        text-align: right;
        background-color: #EEEEEE;
    }

    .ib {
        display: inline-block;
    }

    .w2 {
        width:48%;
    }

    .w3 {
        width:30%;
    }

    .dn {
        display: none;
    }
    .ml2 {
        margin-right: 2em
    }
        .sub-btn{
            text-align: right;
        }
        #fm {
            margin: 0;
            padding: 10px 30px;
        }
        .ftitle {
            font-size: 14px;
            font-weight: bold;
            padding: 5px 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .fitem {
            margin-bottom: 5px;
        }

        .fitem label {
            display: inline-block;
            width: 140px;
        }

        .fitem input {
            width: 160px;
        }

        .radioformr {
            width: 5px;
        }

        .sub-btn {
            margin-top:15px;
        }

        #jiGouForm label{
            font-size: 12px;
            margin-top: 5px;
        }

       .bank_div {
        margin-top:7px;
        padding-bottom: 5px;
        border:1px dashed #ddd8d8;
        width: 560px;
        height:220px;
       }
       .bank_userinfo {
       width:48%;
       float:left;
       height: 30px;
       margin-right: 10px;
       }
       #jiGouForm label {
        display: inline-block;
        font-size: 12px;
        font-weight:normal;
        width: 120px;
        padding: 6px;
       }
     input {
        width: 150px;
        height: 20px;
     }
     .select_style {
        border-radius:4px;
        width:148px;
        height:25px;
        
       }
       
</style>
<div id="box"></div>

<script>
  var AJAXBASEURL = PAGE_VAR.BASE_URL + 'index.php/';//网站地址
  var tplPath = 'PublicMethod/getTemplate/';//调用的模板版本
  var globalData = [];//用于装载全局js变量
  var phpData = [];//php返回的内容

  //查询按钮以及其他按钮
  $("#box").append(
      nunjucks.render(
        AJAXBASEURL + tplPath + 'v001/button', 
        {title: '提交', id: 'submit', class: 'btn'}
    )
  );
</script>

<?php tpl("admin_foot") ?>

</body>
</html>

