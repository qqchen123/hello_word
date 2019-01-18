<?php //tpl("admin_header") ?>
<body>
<!-- <link rel="stylesheet" href="/assets/lib/js/bootstrapdatatable/css/dataTables.bootstrap.min.css"> -->
<!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
<link rel="stylesheet" type="text/css" href="/assets/lib/css/bootstrap.min.css">
<link rel="stylesheet" id="themecss" type="text/css" href="/assets/lib/jquery-easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/assets/lib/jquery-easyui/themes/bootstrap/easyui.css">
<link rel="stylesheet" href="/assets/lib/css/bootstrap-grid.min.css">
<link rel="stylesheet" href="/assets/lib/css/zoomify.min.css">
<!--<link rel="stylesheet" href="/assets/lib/css/style.css">-->
<link rel="stylesheet" href="/assets/layui/layui.css">
<!-- <title></title> -->
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="/assets/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/assets/lib/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/assets/layui/layui.js"></script>
<style>


</style>

<body class="easyui-layout">
<div data-options="region:'north',title:'',split:true" style="height:100px; background: #cccccc;">
    <div class="row" style=" margin: 20 0 0 60;">
        <div class="form-inline">
            银信开户时间
            <input id="start_date" type="text" style="height: 34px;">
            -
            <input id="end_date" type="text" style="height: 34px;">
<!--            <input class="easyui-textbox" data-options="prompt:'渠道编号！'" type="text" id="fuserid" name="locknum1"-->
<!--                   style="height: 34px;" value="">-->
            <input id="fuserid" data-options="prompt:'渠道编号！'" style="height: 34px;" name="dept" value="">
            <input class="easyui-textbox" data-options="prompt:'请输入客户姓名！'" type="text" id="name" name="locknum2"
                   style="height: 34px;" value="">
            <input class="easyui-textbox" data-options="prompt:'请输入银信编号！'" type="text" id="yx_account" name="locknum3"
                   style="height: 34px;" value="">
            <input class="easyui-textbox" data-options="prompt:'请输入手机！'" type="text" id="reg_phone" name="locknum4"
                   style="height: 34px;" value="">

            <input type="button" class="form-control btn btn-info" onclick="search()" value="确定">
            <input type="button" class="form-control btn btn-info" onclick="show_all()" value="显示全部">
<!--            <input type="button" class="form-control btn btn-info" onclick="add_yx_user()" value="新增银信用户">-->
        </div>
    </div>
</div>
<div data-options="region:'center',title:''" style="padding:5px;background:#eee;">
    <!--    客户信息管理汇总-->
    <table class=" easyui-datagrid" id="phone_list" title="客户信息管理汇总" style="width:100%;height:500px;"
           data-options="
                    url:'get_yx_account_mysql',
                    idField: 'id',
                    rownumbers: true,
                    method:'post',
                    lines: true,
                    border: false,
                    columns:col_data,
                    pagination: true,
                    sortName:'yx_account',
                    sortOrder:'asc',
                    remoteSort:true,
        ">
    </table>
    <div>
        <span>备注：</span>
        <p><span>1.</span><span>密码修改成功后，状态会直接置为有效，点击《在线详情》使用新的密码。实际密码是否正确，第二天会更新。</span></p>
    </div>
    <div id="borrow_agree_detail_win" class="easyui-window" title="修改登录密码" style="width:260px;height:140px"
         data-options="modal:true,closed:true">
        <div style="margin-bottom: 20px;">
            <input type="hidden" name="username" id="changepwd_username" value="">
        </div>
        <div style="text-align: center;margin-bottom: 10px;">
            <span>新密码：</span><input type="password" id="changepwd_pwd" name="pwd" value="">
        </div>
        <div style="text-align: center;">
            <span class="btn" onclick="changepwd()">提交</span><span class="btn" onclick="closechangepwd()">取消</span>
        </div>
    </div>

    <div id="iframebox"></div>
    <span style="display: none;" id="loginstatus">登录中</span>

</div>
</body>

<script>
    //客户信息管理汇总
    var col_data = [[
        {field: 'channel', title: '渠道编号', align: 'center', halign: 'center',sortable:true},
        {field: 'name', title: '客户姓名', align: 'center', halign: 'center',sortable:true},
        {field: 'idnumber', title: '身份证号码', align: 'center', halign: 'center',sortable:true},
        {field: 'fuserid', title: '我司客户编号', align: 'center', halign: 'center',sortable:true},
        {field: 'yx_account', title: '银信编号', align: 'center', halign: 'center',sortable:true},
        {field: 'reg_phone', title: '银信绑定手机', align: 'center',sortable:true},
        {field: 'reg_time', title: '银信开户时间', align: 'center', halign: 'center',sortable:true},
        {
            field: 'operate1', title: '信息管理', width: '8%', align: 'center', halign: 'center',
            formatter: function (value, row) {
                var html = '';
                let account = row.yx_account;
                html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="kh_detail_info(' + '\'' + account + '\'' + ')" >信息管理 </a>' + '&nbsp;&nbsp';
                return html;
            }
        },
        {
            field: 'operate2', title: '借款管理', width: '8%', align: 'center', halign: 'center',
            formatter: function (value, row) {
                var html = '';
                let account = row.yx_account;
                html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="kh_borrow_info(' + '\'' + account + '\'' + ')" >借款管理 </a>' + '&nbsp;&nbsp';
                return html;
            }
        },
        // {field: 'operate3',title:'出借管理',width:'8%',align:'center',halign:'center',
        //     formatter:function (value,row) {
        //         var html = '';
        //         let account = row.yx_account;
        //         html+= '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="" >出借管理 </a>'+'&nbsp;&nbsp';
        //         return html;
        //     }
        // },
        {
            field: 'operate4', title: '在线详情', width: '8%', align: 'center', halign: 'center',
            formatter: function (value, row) {
                var html = '';
                let account = row.yx_account;
                let pwd = row.pwd;
                html += '<a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="autologin(' + '\'' + account + '\'' + ',' + '\'' + pwd + '\'' + ')" >在线详情 </a>' + '&nbsp;&nbsp';
                return html;
            }
        },
        {
            field: 'operate5', title: '状态', width: '5%', align: 'center', halign: 'center',
            formatter: function (value, row) {
                return row.status;
            }
        },
        {
            field: 'pwd_effective', title: '密码是否有效', width: '8%', align: 'center', halign: 'center',
            formatter: function (value, row) {
                var html = '';
                if (row.pwd_effective == 1) {
                    html = '<span>有效</span>';
                } else {
                    html = '<span>无效</span><a class="btn btn-primary btn-xs p310" href="javascript:void(0)" onclick="openchangepwd(' + '\'' + row.yx_account + '\'' + ')" >修改密码</a>';
                }
                return html;
            }
        },
    ]];

    //客户借款信息
    function kh_borrow_info(account) {
        window.location.href = 'kh_borrow_info?account=' + account;
    }


    //客户信息详情管理 -kh_detail_info_datagrid
    function kh_detail_info(account) {
        window.location.href = 'kh_detail_info?account=' + account;
    }

    var interval = '';

    function autologin(account, pwd) {
        $("#iframebox").html('<iframe id="test" style="display: none;"></iframe>');
        var str = '<form id="ff1" name="fileForm" method="post" action="https://www.yinxinsirencaihang.com/doLogin"><input  type="text" name="username" value=' + '\'' + account + '\'' + ' /><input  type="pwd" name="password" value=' + '\'' + pwd + '\'' + ' /><input  type="hidden" name="vcode" value="" /><input  type="hidden" name="hasToke" value="true" /><input type="submit"  value="登录"></form>';
        // if (confirm('是否访问')) {
        $("#test").contents().find('body').html(str);
        $("#test").contents().find('form').submit();
        $('#loginstatus').text('开始登录');
        // } else {
        //     $("#iframebox").html('');
        //     return false;
        // }
        interval = setInterval(function () {
            aa();
        }, 1 * 1000);
    }

    $('#loginstatus').click(function () {
        if ('登录成功' == $('#loginstatus').text()) {
            $("#iframebox").html('');
            window.open('https://www.yinxinsirencaihang.com/account/queryAccount');
        }
    });

    function aa() {
        console.log(1);
        if ('开始登录' == $('#loginstatus').text()) {
            clearTimeout(interval);
            setTimeout($('#loginstatus').text('登录成功'), 5 * 1000);
            setTimeout($('#loginstatus').click(), 5 * 1000);
        }
    }

    /**
     * 去掉字符串左右两边的空格
     * @param x
     * @returns {*|Cropper|void|string}
     */
    function myTrim(x) {
        return x.replace(/^\s+|\s+$/gm, '');
    }

    //搜索
    function search() {
        let channel = myTrim($('#fuserid').val());
        let name = myTrim($('#name').val());
        let reg_phone = myTrim($('#reg_phone').val());
        let yx_account = myTrim($('#yx_account').val());
        let start_date = myTrim($('#start_date').val());
        let end_date = myTrim($('#end_date').val());
        $('#phone_list').datagrid('load', {
            channel,
            name,
            reg_phone,
            yx_account,
            start_date,
            end_date
        });
    }

    //打开修改密码对话框
    function openchangepwd(account) {
        console.log(account);
        $('#changepwd_username').val(account);
        $('#borrow_agree_detail_win').window('open');

    }

    //关闭修改密码对话框
    function closechangepwd() {
        $('#borrow_agree_detail_win').window('close');
    }

    //修改密码
    function changepwd() {
        var account = $('#changepwd_username').val();
        var pwd = $('#changepwd_pwd').val();
        if (0 == pwd.length) {
            alert('密码输入错误');
            return false;
        }
        if (-1 == pwd.indexOf("yxt")) {
            alert('密码输入不合法');
            return false;
        }
        console.log('修改密码');
        var ret = -1;
        //ajax 请求
        $.ajax({
            type: "POST",
            url: 'change_pwd',
            data: {account: account, pwd: pwd},
            dataType: "json",
            success(data) {
                console.log(data);
                if (0 == data.code) {
                    ret = 0;
                }
                if (0 == ret) {
                    alert('修改成功');
                    $('#phone_list').datagrid('reload');
                    closechangepwd();
                } else {
                    alert('修改失败请重试');
                }
            }
        });
    }

    function show_all() {
        window.location.reload();
    }

    $('#start_date').datebox({
        required: true
    });
    $('#end_date').datebox({
        required: true
    });
    /**
     *获取所有channel
     */
    $('#fuserid').combobox({
        url:'get_channel_data',
        valueField:'channel',
        textField:'channel'
    });
</script>

</body>