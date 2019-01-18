define(function(require,exports){
    var validator = {
        isMoney:function(valueToValidate){
            //两位小数的正实数
            return /^[0-9]+(.[0-9]{2,4})?$/.test(valueToValidate);
        },
        isInt:function(valueToValidate){
            return /^\d+$/.test(valueToValidate);
        },
        isPass:function(valueToValidate){
            //以字母开头，长度在6-18之间，只能包含字符、数字和下划线
            return /^[a-zA-Z]\w{5,17}$/.test(valueToValidate);
        },
        isEmail:function(valueToValidate){
            //Email地址
            return /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(valueToValidate);
        },
        isMainPage:function(valueToValidate){
            //不带参数的url
            return /^http:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/\?%&=]*)?$/.test(valueToValidate)
        },
        isPhone:function(valueToValidate){
            //验证电话号码，固话以及手机号码
            return /^(\(\d{3,4}\)|\d{3,4}-)?\d{7,8}$/.test(valueToValidate) || /^\d{11}$/.test(valueToValidate);
        },
        isAlphaOrNumbers:function (value) {
            return /^[a-zA-Z0-9]+$/.test(value);
        },
        isForbidden:function(valueToValidate){
            return /<(\S*?)[^>]*>.*?<\/\1>|<.*?\/>/.test(valueToValidate);
        },
        isChineseORAlphar:function(valueToValidate){
            return /^([\u0391-\uFFE5])|\w+$/.test(valueToValidate);
        },
        isDate:function(date){
            var sepretor = '-';
            if(arguments.length==2 && arguments[1]!='-') sepretor = arguments[1];


        },
        isValidRate:function($val){
            var _valArr = $val.split('.');
            if(!$val || _valArr.length>2 || isNaN(parseFloat($val))){
                return;
            }
            _valArr[0] = parseInt(_valArr[0]);
            if(_valArr[0]>100 || _valArr[0]<0){
                return;
            }
            if(_valArr.length==2 && _valArr[1].length>3){
                _valArr[1]=_valArr[1].substr(0,3);
            }
            return _valArr.join('.');
        },
        isCreaditCode:function(validCode){
            if(validCode.length<18) return false;
            //规则码
            var $coefficient = [1,3,9,27,19,26,16,17,20,29,25,13,8,24,10,30,28];
            var $alphaMap = {65:10,66:11,67:12,68:13,69:14,70:15,71:16,72:17,74:18,75:19,76:20,77:21,78:22,80:23,81:24,82:25,84:26,85:27,87:28,88:29,89:30};
            var $codeMap  = {10:65,11:66,12:67,13:68,14:69,15:70,16:71,17:72,18:74,19:75,20:76,21:77,22:78,23:80,24:81,25:82,26:84,27:85,28:87,29:88,30:89};
            var $sum=0;
            var $asc,$coeffi,vcode;
            var $codeArr = validCode.split('');
            var $Vcode = $codeArr.pop();//校验码
            for(var i = 0 ,arrlen = $codeArr.length ; i<arrlen ; i++){
                $asc = $codeArr[i].charCodeAt(0);
                $coeffi = 0;
                if($asc>47 && $asc<58){
                    $coeffi=$codeArr[i] * $coefficient[i];
                }else if ($asc>64 && typeof $alphaMap[$asc]!='undefined'){
                    $coeffi=$alphaMap[$asc] * $coefficient[i];
                }else{
                    $sum = 9999999999999;
                    break;
                }
                $sum+=$coeffi;
            }
            vcode = 31 - $sum%31;
            vcode = vcode< 10 ? vcode : String.fromCharCode($codeMap[vcode]);
            return $Vcode == vcode;
        }
    }
    exports.validator = validator;
    window.validator = validator;
});