<body>
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


</style>

<body class="easyui-layout">
<div data-options="region:'north',title:'',split:true" style="height:150px; background: #ffffff;" >
    <div class="row" style=" margin: 20 0 0 60;">
        <div class="form-inline">

            <input type="button" class="btn btn-warning" class="form-control" onclick="window.history.go(-1)" value="返回">
        </div>

    </div>
    <table class=" easyui-datagrid" id="phone_list" title="客户信息管理" style="width:100%;height:95px; "
           data-options="
                    url:'get_one_yx_account_mysql',
                    idField: 'id',
                    rownumbers: true,
                    method:'post',
                    lines: true,
                    border: false,
                    columns:col_data,
                    queryParams: {
                        account: '<?php echo $account ?>',
                    },
                    rowStyler: function(index,row){
                         return 'font-size: 40px;';
                    }
        ">
    </table>
</div>
<div data-options="region:'center',title:'客户详情信息管理'" style="padding:5px;background:#FFFFFF;">
<!--    <div data-options="region:'east',title:'East',split:true" style="width:100px;"></div>-->
        <div style="padding:10px 60px 20px 60px">
<!--            <table cellpadding="5">-->
<!--                <tr>-->
<!--                    <td>登陆用户名:</td>-->
<!--                    <td><input class="easyui-textbox" type="text" name="name" data-options="required:true"></input></td>-->
<!--                    <td>专属客服:</td>-->
<!--                    <td><input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input></td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>存管专户是否开通:</td>-->
<!--                    <td><input class="easyui-textbox" type="text" name="subject" data-options="required:true"></input></td>-->
<!--                    <td>存管专户账号:</td>-->
<!--                    <td><input class="easyui-textbox" type="text" name="subject" data-options="required:true"></input></td>-->
<!---->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>是否实名认证:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                    <td>认证人姓名:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>身份证号:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                    <td>是否绑定手机号:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>绑定手机:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                    <td>和用户名是否一致:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>是否绑定邮箱:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                    <td>上次登录时间:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>回款短信:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                    <td>还款短信:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>可用余额:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                    <td>冻结金额:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>可提现金额:</td>-->
<!--                    <td>-->
<!--                        <input class="easyui-textbox" type="text" name="email" data-options="required:true,validType:'email'"></input>-->
<!--                    </td>-->
<!--                </tr>-->
<!--            </table>-->
            <fieldset>
                <legend>客户详情信息</legend>
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="ds_host">登陆用户名：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_host" type="text" placeholder="" value="<?= !empty($about['account']) ? $about['account'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_name">专属客服：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="" value="<?= !empty($about['ex_service']) ? $about['ex_service'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_host">存管专户是否开通：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_host" type="text" placeholder="" value="<?= !empty($about['have_sp_dep']) ? $about['have_sp_dep'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_name">存管专户账号：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="" value="<?= !empty($about['sp_dep_account']) ? $about['sp_dep_account'] : ''?>"/>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend></legend>
                <div class="form-group">
                    <div class="form-group">
                        <label class="col-sm-1 control-label" for="ds_name">是否实名认证：</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="ds_name" type="text" placeholder="" value="<?= !empty($about['is_real_name']) ? $about['is_real_name'] : ''?>"/>
                        </div>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_host">认证人姓名：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_host" type="text" placeholder="" value="<?= !empty($about['real_name']) ? $about['real_name'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_name">身份证号：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="<?= !empty($about['idnumber']) ? $about['idnumber'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_host">是否绑定手机号：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_host" type="text" placeholder="" value="<?= !empty($about['bind_phone']) ? $about['bind_phone'] : ''?>"/>
                    </div>

                </div>
            </fieldset>
            <fieldset>
                <legend></legend>
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="ds_name">绑定手机：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="" value="<?= !empty($about['binding_phone']) ? $about['binding_phone'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_host">和用户名是否一致：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_host" type="text" placeholder="<?=  $about['yz'] ?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_name">是否绑定邮箱：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="" value="<?= !empty($about['is_bind_email']) ? $about['is_bind_email'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_host">上次登录时间：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_host" type="text" placeholder="" value="<?= !empty($about['lutime']) ? $about['lutime'] : ''?>"/>
                    </div>

                </div>
            </fieldset>
            <fieldset>
                <legend></legend>
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="ds_name">回款短信：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="" value="<?= !empty($about['rec_pay_message']) ? $about['rec_pay_message'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_host">还款短信：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_host" type="text" placeholder="" value="<?= !empty($about['repay_message']) ? $about['repay_message'] : ''?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_name">可用余额：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="<?=  $about['acctBal']?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_host">冻结金额：</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_host" type="text" placeholder="<?= $about['frozBl']?>"/>
                    </div>

                </div>
            </fieldset>
            <fieldset>
                <legend></legend>
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="ds_name">总余额</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="<?=  $about['acctAmount']?>"/>
                    </div>
                    <label class="col-sm-1 control-label" for="ds_name">是否风险测评</label>
                    <div class="col-sm-2">
                        <input class="form-control" id="ds_name" type="text" placeholder="<?=  $about['if_assessment']?>"/>
                    </div>
                </div>

            </fieldset>
        </div>
</div>
</body>

<script>

    //客户信息管理汇总
    var col_data = [[
        {field: 'fuserid',title:'我司ID',align:'center',halign:'center'},
        {field: 'channel',title:'渠道ID',align:'center',halign:'center'},
        {field: 'yx_account',title:'银信ID',align:'center',halign:'center'},
        {field: 'reg_phone',title:'银信绑定手机',align:'center'},
        {field: 'ctime',title:'银信开户时间',align:'center',halign:'center'},
        {field: 'idnumber',title:'身份证号码',align:'center',halign:'center'},
        {field: 'name',title:'客户姓名',align:'center',halign:'center'},
    ]];
    $('#agreement_detail').datagrid({
        url:'',
        width:'100%',
        columns:[[
            {field:' ',title:'借款合同编号',width:100},
            {field:' ',title:'协议签署时间',width:100},
            {field:' ',title:'甲方',width:100,align:'right'},
            {field:' ',title:'证件编号',width:100,align:'right'},
            {field:' ',title:'借款本金总额',width:100,align:'right'},
            {field:' ',title:'借款本金大写',width:100,align:'right'},
            {field:' ',title:'借款年利率',width:100,align:'right'},
            {field:' ',title:'借款期限（满标起）',width:100,align:'right'},
            {field:' ',title:'借款用途',width:100,align:'right'},
            {field:' ',title:'银行开户名',width:100,align:'right'},
            {field:' ',title:'银行账号',width:100,align:'right'},
            {field:' ',title:'开户行',width:100,align:'right'},
        ]]
    });
    $('#finish_detail').datagrid({
        url:'',
        width:'100%',
        columns:[[
            {field:' ',title:'期数',width:100},
            {field:' ',title:'还款日',width:100},
            {field:' ',title:'还款利息',width:100,align:'right'},
            {field:' ',title:'还款本金',width:100,align:'right'},
            {field:' ',title:'还款总本息',width:100,align:'right'},
            {field:' ',title:'状态',width:100,align:'right'},
        ]]
    });

    $('.datagrid-cell').css('font-size','40px');
</script>

</body>