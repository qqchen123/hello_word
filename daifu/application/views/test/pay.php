<title>代扣API支付类交易</title>
<style type="text/css">
    <!--
    .STYLE1 {
        font-size: 24px;
        font-weight: bold;
    }

    -->
</style>


</head>

<body style="margin:0">
<p><a href="./index">返回</a></p>
<div style="margin:0 auto; width:500px;">
    <form name="R01" id="R01" method="post" action="./action">
        <table width="500" height="394" border="0" cellpadding="1" cellspacing="1" bgcolor="#33CCFF">
            <tr>
                <td height="84" colspan="2" align="center" bgcolor="#FFFFFF"><span class="STYLE1">代扣API支付类交易-DEMO</span>
                </td>
            </tr>
            <tr>
                <td width="108" align="right" bgcolor="#FFFFFF">银行名称：</td>
                <td width="392" bgcolor="#FFFFFF"><select name="pay_code" id="pay_code">
                        <option value="ICBC" selected="selected">中国工商银行</option>
                        <option value="ABC">中国农业银行</option>
                        <option value="CCB">中国建设银行</option>
                        <option value="BOC">中国银行</option>
                        <option value="BCOM">中国交通银行</option>
                        <option value="CIB">兴业银行</option>
                        <option value="CITIC">中信银行</option>
                        <option value="CEB">中国光大银行</option>
                        <option value="PAB">平安银行</option>
                        <option value="PSBC">中国邮政储蓄银行</option>
                        <option value="SHB">上海银行</option>
                        <option value="SPDB">浦东发展银行</option>
                    </select></td>
            </tr>
            <tr>
                <td height="47" align="right" bgcolor="#FFFFFF">银行卡号：</td>
                <td bgcolor="#FFFFFF"><input name="acc_no" type="text" value="6222020111122220001" id="acc_no" size="20" maxlength="20"/></td>
            </tr>
            <tr>
                <td height="47" align="right" bgcolor="#FFFFFF">身份证号：</td>
                <td bgcolor="#FFFFFF"><input name="id_card" type="text" value="320301198502169142" id="id_card" size="18" maxlength="18"/></td>
            </tr>
            <tr>
                <td height="47" align="right" bgcolor="#FFFFFF">姓名：</td>
                <td bgcolor="#FFFFFF"><input name="id_holder" type="text" id="id_holder" value="钱宝" size="10" maxlength="10"/></td>
            </tr>
            <tr>
                <td height="47" align="right" bgcolor="#FFFFFF">手机号：</td>
                <td bgcolor="#FFFFFF"><input name="mobile" type="text" id="mobile" value="18689262767" size="11" maxlength="11"/></td>
            </tr>
            <tr>
                <td height="44" align="right" bgcolor="#FFFFFF">交易金额：</td>
                <td bgcolor="#FFFFFF"><input name="txn_amt" type="text" id="txn_amt" value="0.01" size="5"
                                             maxlength="5"/><input name="txn_sub_type" type="hidden" id="txn_sub_type"
                                                                   value="13"/>
                    （元）
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center" bgcolor="#FFFFFF">
                    <span id="sub">提交</span>
                    <input style="display: none;" type="submit" id="Submit" name="Submit" value="提交"/></td>
            </tr>

        </table>
    </form>
</div>

</body>

<script type="text/javascript">
    $('#sub').click(function () {
        //检查内容
        //银行卡号
        // alert($('#acc_no').val());
        // console.log($('#acc_no').val());
        // return;

        // if (!luhnCheck($('#acc_no').val())) {
        //     console.log('银行卡检测不通过');
        //     return false;
        // }

        //身份证号
        // if (!ChinaIdChecker($('#id_card').val())) {
        //     console.log('身份证无效');
        //     return false;
        // }

        //姓名
        // if (!checkName($('#id_holder').val())) {
        //     console.log('姓名无效');
        //     return false;
        // }

        //手机号
        // if (!isPhoneNumber($('#mobile').val())) {
        //     console.log('手机号无效');
        //     return false;
        // }

        //交易金额
        // if (!isNumber($('#txn_amt').val())) {
        //     console.log('交易金额无效');
        //     return false;
        // }

        $('#Submit').click();
    });

    //银行卡号码检测
    function luhnCheck(bankno) {
        var lastNum = bankno.substr(bankno.length - 1, 1); //取出最后一位（与luhn进行比较）
        var first15Num = bankno.substr(0, bankno.length - 1); //前15或18位
        var newArr = new Array();
        for (var i = first15Num.length - 1; i > -1; i--) { //前15或18位倒序存进数组
            newArr.push(first15Num.substr(i, 1));
        }
        var arrJiShu = new Array(); //奇数位*2的积 <9
        var arrJiShu2 = new Array(); //奇数位*2的积 >9
        var arrOuShu = new Array(); //偶数位数组
        for (var j = 0; j < newArr.length; j++) {
            if ((j + 1) % 2 == 1) { //奇数位
                if (parseInt(newArr[j]) * 2 < 9) arrJiShu.push(parseInt(newArr[j]) * 2);
                else arrJiShu2.push(parseInt(newArr[j]) * 2);
            } else //偶数位
                arrOuShu.push(newArr[j]);
        }

        var jishu_child1 = new Array(); //奇数位*2 >9 的分割之后的数组个位数
        var jishu_child2 = new Array(); //奇数位*2 >9 的分割之后的数组十位数
        for (var h = 0; h < arrJiShu2.length; h++) {
            jishu_child1.push(parseInt(arrJiShu2[h]) % 10);
            jishu_child2.push(parseInt(arrJiShu2[h]) / 10);
        }

        var sumJiShu = 0; //奇数位*2 < 9 的数组之和
        var sumOuShu = 0; //偶数位数组之和
        var sumJiShuChild1 = 0; //奇数位*2 >9 的分割之后的数组个位数之和
        var sumJiShuChild2 = 0; //奇数位*2 >9 的分割之后的数组十位数之和
        var sumTotal = 0;
        for (var m = 0; m < arrJiShu.length; m++) {
            sumJiShu = sumJiShu + parseInt(arrJiShu[m]);
        }

        for (var n = 0; n < arrOuShu.length; n++) {
            sumOuShu = sumOuShu + parseInt(arrOuShu[n]);
        }

        for (var p = 0; p < jishu_child1.length; p++) {
            sumJiShuChild1 = sumJiShuChild1 + parseInt(jishu_child1[p]);
            sumJiShuChild2 = sumJiShuChild2 + parseInt(jishu_child2[p]);
        }
        //计算总和
        sumTotal = parseInt(sumJiShu) + parseInt(sumOuShu) + parseInt(sumJiShuChild1) + parseInt(sumJiShuChild2);

        //计算luhn值
        var k = parseInt(sumTotal) % 10 == 0 ? 10 : parseInt(sumTotal) % 10;
        var luhn = 10 - k;

        if (lastNum == luhn) {
            // $("#banknoInfo").html("luhn验证通过");
            return true;
        } else {
            // $("#banknoInfo").html("银行卡号必须符合luhn校验");
            return false;
        }
    }

    function ChinaIdChecker(id) {
        this.isOK = false;
        this.error = '';

        if (!id || typeof (id) != 'string' || id.length != 15 && id.length != 18
            || !id.match(/^[0-9]{15}$/) && !id.match(/^[0-9]{17}[0-9xX]$/) || "111111111111111" == id) {
            this.error = '输入不是15位或者18位有效字符串';
            return false;
        }

        var area = {
            11: "北京",
            12: "天津",
            13: "河北",
            14: "山西",
            15: "内蒙古",
            21: "辽宁",
            22: "吉林",
            23: "黑龙江",
            31: "上海",
            32: "江苏",
            33: "浙江",
            34: "安徽",
            35: "福建",
            36: "江西",
            37: "山东",
            41: "河南",
            42: "湖北",
            43: "湖南",
            44: "广东",
            45: "广西",
            46: "海南",
            50: "重庆",
            51: "四川",
            52: "贵州",
            53: "云南",
            54: "西藏",
            61: "陕西",
            62: "甘肃",
            63: "青海",
            64: "宁夏",
            65: "新疆",
            71: "台湾",
            81: "香港",
            82: "澳门",
            91: "国外"
        };

        this.areaName = area[id.substr(0, 2)];
        if (!this.areaName) {
            this.error = '前2位不是有效的行政区划代码';
            return false;
        }

        if (id.length == 15) {
            this.year = parseInt(id.substr(6, 2));
            this.month = parseInt(id.substr(8, 2));
            this.day = parseInt(id.substr(10, 2));
        } else {
            this.year = parseInt(id.substr(6, 4));
            this.month = parseInt(id.substr(10, 2));
            this.day = parseInt(id.substr(12, 2));
        }

        this.error = '出生日期不正确';
        if (this.month > 12) {
            return false;
        }
        if (this.day > 31) {
            return false;
        }
        // February can't be greater than 29 (leap year calculation comes later)
        if ((this.month == 2) && (this.day > 29)) {
            return false;
        }
        // check for months with only 30 days
        if ((this.month == 4) || (this.month == 6) || (this.month == 9) || (this.month == 11)) {
            if (this.day > 30) {
                return false;
            }
        }
        // if 2-digit year, use 50 as a pivot date
        if (this.year < 100) {
            this.year += 1900;
        }
        if (this.year > 9999) {
            return false;
        }
        // check for leap year if the month and day is Feb 29
        if ((this.month == 2) && (this.day == 29)) {
            var div4 = this.year % 4;
            var div100 = this.year % 100;
            var div400 = this.year % 400;
            // if not divisible by 4, then not a leap year so Feb 29 is invalid
            if (div4 != 0) {
                return false;
            }
            // at this point, year is divisible by 4. So if year is divisible by
            // 100 and not 400, then it's not a leap year so Feb 29 is invalid
            if ((div100 == 0) && (div400 != 0)) {
                return false;
            }
        }
        this.yearStr = '' + this.year;
        this.monthStr = (this.month < 10 ? '0' : '') + this.month;
        this.dayStr = (this.day < 10 ? '0' : '') + this.day;

        // date is valid
        var birthDay = new Date(this.year, this.month - 1, this.day);

        if (birthDay - new Date() >= 0 || birthDay - new Date(1850, 1, 1) <= 0) {
            return false;
        }

        this.error = '';
        var lastNum = id.length == '15' ? id.substr(14, 1) : id.substr(16, 1);
        this.sex = (lastNum == '1' || lastNum == '3' || lastNum == '5'
            || lastNum == '7' || lastNum == '9') ? '1' : '0';
        this.sexName = this.sex == '1' ? '男' : '女';
        if (id.length == '15') {
            this.isOK = true;
            return true;
        }

        var getLastValidationLetter = function (str) {
            var strArray = new Array(17);
            // 存储身份证的前17为数字
            var Wi = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1);
            // 表示第i位置上的加权因子
            var Y = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            // 校验码值
            var S = 0;
            // 十七位数字本体码加权求和
            var jym = 0;
            // 校验码
            for (var i = 16; i >= 0; i -= 1) {
                strArray[i] = Number(str.charAt(i));
            }

            for (var j = 16; j >= 0; j -= 1) {
                S += strArray[j] * Wi[j];
            }

            jym = S % 11;
            return Y[jym];
        };

        if (id.substr(17, 1) != getLastValidationLetter(id.substr(0, 17))) {
            this.error = '18位身份证编码最后一位校验码不正确';
            return false;
        }

        this.isOK = true;
        return true;
    }

    function checkName(name) {
        var regName = /^[\ue-\ufa]{,}$/;
        if (!regName.test(name)) {
            // alert('真实姓名填写有误');
            return false;
        }
        return true;
    }

    function isPhoneNumber(tel) {
        var reg = /^0?1[3|4|5|6|7|8][0-9]\d{8}$/;
        return reg.test(tel);
    }

    function isNumber(val) {
        var regPos = /^\d+(\.\d+)?$/; //非负浮点数
        var regNeg = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/; //负浮点数
        if (regPos.test(val) || regNeg.test(val)) {
            return true;
        } else {
            return false;
        }
    }
</script>