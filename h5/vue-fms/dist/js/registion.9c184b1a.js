(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["registion"],{"182c":function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAlCAYAAABCr8kFAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTM4IDc5LjE1OTgyNCwgMjAxNi8wOS8xNC0wMTowOTowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QzVGQjQwODc4RUU2MTFFOEFCRkFCRkRFOUYzRUY0ODEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QzVGQjQwODg4RUU2MTFFOEFCRkFCRkRFOUYzRUY0ODEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpDNUZCNDA4NThFRTYxMUU4QUJGQUJGREU5RjNFRjQ4MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpDNUZCNDA4NjhFRTYxMUU4QUJGQUJGREU5RjNFRjQ4MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PuUtE3gAAAHSSURBVHjarNZHSwNRFAVgnaW9YNeNvSFW7IJiQVHXuvMvCG6TRXb+DsGFuLZgw44FLNhd2wtY154L98HlMSbxjhcOYQ7Jl8djMnmRPp8vwuMkI1PIWCAQOHD+AVtAOujV7/dXegGTGKsS+LgWTLQwmgNkWAMmMFYtukOkC3v46iixGhfshS7+AsYzViu6I8aeTeF4wI5tLFyQsHmkTnQnSCfyZL/ZCQObQ+pFd8r33ZPbB5wQ2CzSYGHtv2HBwDhkBmkU3VmwlQUDYxlrEt05Y4+hNtz5BWsW3QVjD+HcDhKMYazFBbsP92Y1YLQLdsnY3V9+SgRGMdYq+isNZsBppE1014zdah5DBG5b3Qhyo31IOtZPimYCyfECDiFLostFVpBsLfiNDCLLos/Toua2IXSAETP5/CXZGtCg/ciq6AoYzdKABu1D1lzQTA0o0XXRFTKaoQFpvpBeCy3iPc7QgAallW5YKK00XQPSfDK6KbpiRtM0IM0Ho1uiK2E0VQPSvPOeSrSU9hQHpFQNaNA+62FC6DLQFA1I88Yr3RFdmUQ1hyVCe5Bd0ZUji4Rqj3OEdiN7oqtAJr0cOAntQvb5mv6vR70eiQ1Kx5VOHJxOfgQYAAIneTubnsJfAAAAAElFTkSuQmCC"},"539a":function(t,e,n){"use strict";var a=n("5e20"),i=n.n(a);i.a},"5c75":function(t,e,n){},"5e20":function(t,e,n){},6153:function(t,e,n){},"7f7f":function(t,e,n){var a=n("86cc").f,i=Function.prototype,s=/^\s*function ([^ (]*)/,c="name";c in i||n("9e1e")&&a(i,c,{configurable:!0,get:function(){try{return(""+this).match(s)[1]}catch(t){return""}}})},"80ce":function(t,e,n){"use strict";var a=n("2b0e"),i=n("705f");e["a"]={createAccount:function(t){return a["a"].http.post(i["a"].url+"/index.php/Qiye/front_end_reg",t)},login:function(t){return a["a"].http.post(i["a"].url+"/index.php/Qiye/front_end_login",t)},forgotpwd:function(t){return a["a"].http.post(i["a"].url+"/index.php/Qiye/front_end_set_pwd",t)},cAccount:function(t){return a["a"].http.post(i["a"].url+"/index.php/Qiye/front_end_reg",t)},backurl:function(t){return i["a"].url+"/index.php/"},yzmphone:function(t){return a["a"].http.get(i["a"].url+"/index.php/Sms/send_sms/"+t)},getstatus:function(t){return a["a"].http.post(i["a"].url+"/index.php/Qiye/user_report_status",t)},getaccs:function(t){return a["a"].http.post(i["a"].url+i["a"].callbackroute+"yys_report",t)},jdye:function(t){return a["a"].http.post(i["a"].url+i["a"].callbackroute+"jd_report",t)},taobao:function(t){return a["a"].http.post(i["a"].url+i["a"].callbackroute+"taobao_report",t)},creditcard:function(t){return a["a"].http.post(i["a"].url+i["a"].callbackroute+"credit_card_report",t)},bankcard:function(t){return a["a"].http.post(i["a"].url+i["a"].callbackroute+"bank_card_report",t)},gongjj:function(t){return a["a"].http.post(i["a"].url+i["a"].callbackroute+"gjj_report",t)}}},8708:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[t.willShow?n("div",{staticClass:"background page-content"},[t._m(0),n("div",{staticClass:"info"},[n("div",[n("label",{staticClass:"ztts"},[t._v("客户姓名")]),n("input",{directives:[{name:"model",rawName:"v-model",value:t.name,expression:"name"}],staticClass:"info-input",attrs:{type:"text",placeholder:"请输入真实姓名"},domProps:{value:t.name},on:{input:function(e){e.target.composing||(t.name=e.target.value)}}})]),n("div",{staticClass:"caredscc"},[n("label",{staticClass:"ztts"},[t._v("身份证号")]),n("input",{directives:[{name:"model",rawName:"v-model",value:t.id,expression:"id"}],staticClass:"info-input",attrs:{type:"text",placeholder:"请输入有效身份证号"},domProps:{value:t.id},on:{input:function(e){e.target.composing||(t.id=e.target.value)}}})]),n("div",{staticClass:"caredscc"},[n("label",{staticClass:"ztts"},[t._v("手机号码")]),n("input",{directives:[{name:"model",rawName:"v-model",value:t.phone,expression:"phone"}],staticClass:"info-input",attrs:{type:"tel",placeholder:"请输入手机号"},domProps:{value:t.phone},on:{input:function(e){e.target.composing||(t.phone=e.target.value)}}})]),n("div",{staticClass:"vaild caredscc"},[n("div",{staticClass:"dhjf"},[n("label",{staticClass:"ztts"},[t._v("验证码")]),n("input",{directives:[{name:"model",rawName:"v-model",value:t.yzmsr,expression:"yzmsr"}],staticClass:"vaild-input",attrs:{type:"text",placeholder:"请输入验证码"},domProps:{value:t.yzmsr},on:{input:function(e){e.target.composing||(t.yzmsr=e.target.value)}}})]),n("div",{staticClass:"vaild-btn"},[t.sendMsgDisabled?n("span",{staticClass:"flex-c-m"},[t._v(t._s(t.time+"秒后获取"))]):t._e(),t.sendMsgDisabled?t._e():n("span",{staticClass:"flex-c-m",on:{click:t.yzmphone}},[t._v("获取验证码")])])])]),n("div",{staticClass:"agree flex-c-m"},[n("input",{directives:[{name:"model",rawName:"v-model",value:t.agree,expression:"agree"}],staticClass:"select-icon",attrs:{id:"cb1",type:"checkbox"},domProps:{checked:Array.isArray(t.agree)?t._i(t.agree,null)>-1:t.agree},on:{change:function(e){var n=t.agree,a=e.target,i=!!a.checked;if(Array.isArray(n)){var s=null,c=t._i(n,s);a.checked?c<0&&(t.agree=n.concat([s])):c>-1&&(t.agree=n.slice(0,c).concat(n.slice(c+1)))}else t.agree=i}}}),n("div",[t._v("\n\t\t   我已阅读并同意\n\t\t  ")]),n("span",{on:{click:t.baxk}},[t._v("\n\t\t  《个人信息查询及使用授权书》\n\t\t  ")]),n("div",[t._v("\n            等\n          ")])]),n("div",{staticClass:"btn flex-c"},[n("div",{staticClass:"btn-item flex-c-m",on:{click:t.cAccount}},[t._v("提 交")])])]):t._e(),t.willShow?t._e():n("div",[t.shounone?n("div",{staticClass:"regs"},[t.shounone?n("span",{staticClass:"liebiao",on:{click:t.shown}},[t._v("协议列表")]):t._e(),t.shounone?n("span",{staticClass:"xinxin",on:{click:t.shown}},[t._v("1.《个人信息查询及使用授权书》")]):t._e(),t.shounone?n("a",{staticClass:"fanghui fanhui2 fanghui2",on:{click:t.baxk}},[n("span",{staticClass:"fanghuiziti"},[t._v("返回")])]):t._e()]):t._e()]),t.shounone?t._e():n("div",{staticClass:"xieyi_div"},[t._m(1),t._m(2),t._m(3),n("div",{staticClass:"ms"},[t._v("\n        \t授权人:"),n("span",{staticClass:"names"}),t._v("授权日期:"),n("span",{attrs:{id:"times"}},[t._v(t._s(this.currentdate))])]),n("a",{staticClass:"fanghui fh2",on:{click:t.shown}},[n("span",{staticClass:"fanghuiziti"},[t._v("返回")])])])])},i=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"navigation flex-space flex-c-m"},[a("div",{staticClass:"nav-back flex-c-m"},[a("img",{attrs:{src:n("182c")}})]),a("div",{staticClass:"nav-right"})])},function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"xx_div"},[n("span",{staticClass:"xinxi"},[t._v("个人信息查询及使用授权书")])])},function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"ms"},[t._v("\n\t\t\t授权人声明:本人已知悉并理解下述条款，本授权书一\n\t\t\t经确认即视为本人签署并对本\n\t\t\t人产生法律效力。"),n("br"),n("br")])},function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"ms"},[t._v("\n\t\t\t          尊敬的上海源都金融信息服务有限公司:"),n("br"),t._v("\n            鉴于 本人  [ ](身份证号码:"),n("span",{staticClass:"cread"}),t._v(")\n              拟 通 过贵 司运营的【 源 都 金 服】\n            平台(“源都金服 ”)向与贵司具有合作关系的银行 金融机构\n            或消费金融公司、小额贷款公司等银行金融机构(以下合称\n            “金融机构”)及其关联公司申请、使用该等金融机构的授信额\n            度并获得相应贷款资金，为贵司对本人进行信用分析和评价\n            之目的，就本人的有关个人信息查询及使用等相关事宜，本\n            人特向贵司作出以下不可撤销的授权:\n             "),n("br"),t._v("\n           一、在本人通过源都金服 向金融机构及其关联公司提出授信额\n           度及贷款申请或使用该等授信额度、获得贷款过程中及本人与该\n           等金融机构信贷关系存续期间内，授权贵司使用与贵司具有合作\n           关系并经本入授权的金融机构及其关联公司 向中国人 民银行金融\n           信息基 础数 据库查询、保存或打印的本人的个人征信信息。\n            "),n("br"),t._v("\n           二、在本人通过源都金服 向金融机构及其关联公司提出授信额度\n           及贷款申请或使用该等授信额度、获得贷款过程中及本人与该等金\n           融机构信贷关系存续期间内，授权贵司及合作方向有关机构或单位\n           (包括但不限于经国务院或其他政府有权部门]批准合法设立的其\n           他征信机构;公安、住房公积金、社会保险、税务、民政等政府主\n           管部门;物流、通信运营商、电子商务平台、互联网平台等第三方\n           机构)查询、保存、打印和使用本人的征信信息、财产信息、联系\n           方式、关系人、 资信状况、就业情况、收入情况、婚姻情况、学历\n           信息、 地址信息、位置数据、通讯行为、通讯信息、互联网使用信\n           息、互联网使用行为等信息。本人保证，本人不会因上述机构或单\n           位向贵司提供有关信息或确认事项而向贵司、贵司合作方、上述机\n           构或单 位以任何形式提出权利主张。上述本人的个人征信信息或贵\n           司向有关机构或单位查询、保存或打印的本人其他个人信息，仅可用\n           于以下用途:\n            (一)审核本人的授信额度或贷款申请;\n            (二)对已向本人发放的贷款进行贷后管理。\n             "),n("br"),t._v("\n           三、本人同意并授权贵司及合作方向中国人民银行金融信用信息基础数据库以及经国务院或其\n           他政府有关部门批准合法设立的征信机构报送本人的信用信息，包括但不限于信贷信息及对本\n           人信用状况构成负面影响的信息。\n           本授权书自本人通过源都金服 首次进行授信或贷款申请时勾选相关“确认”按钮即生效,有效期\n           至本人和与贵司具有合作关系的金融机构及其关联公司的所有信贷业务合作终止并无任何法律\n           纠纷，且注销或终止源都金服 账户之日止。"),n("br"),n("br")])}],s=(n("7f7f"),n("cadf"),n("551c"),n("80ce")),c=n("705f"),r=n("cef8"),o={name:"registion",data:function(){return{name:"",id:"",phone:"",verify:"",yzmsr:"",time:60,sendMsgDisabled:!1,agree:!1,cansub:0,willShow:!0,shounone:!0,currentdate:""}},created:function(){setInterval(this.shijian,1e3)},methods:{shijian:function(){this.currentdate=r["a"].shijians()},baxk:function(){this.willShow=!this.willShow},shown:function(){this.shounone=!this.shounone},cAccount:function(){var t=this;return/^[\u4E00-\u9FA5A-Za-z]+$/.test(this.name)?/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(this.id)?/^[1][3,4,5,7,8][0-9]{9}$/.test(this.phone)?this.yzmsr?(this.cansub=1,!!this.cansub&&(document.getElementById("cb1").checked?void s["a"].cAccount({name:this.name,idnumber:this.id,mobile:this.phone,identifying:this.yzmsr}).then(function(e){0!=e.body.code?(console.log(e.body),1==e.body.code?t.$router.push({path:"/creditEval?fuserid="+e.body.msg}):window.location.href=c["a"].authurl+"carrier"+c["a"].authkey+c["a"].base+"yysid?fuserid="+e.body.msg+"&carrier_idcard="+t.id+"&carrier_name="+t.name):0==e.body.code&&(window.location.href=c["a"].authurl+"carrier"+c["a"].authkey+c["a"].base+"yysid?fuserid="+e.body.msg+"&carrier_idcard="+t.id+"&carrier_name="+t.name)}):(alert("协议未勾选！"),!1))):(alert("验证码有误！"),!1):(alert("手机号有误"),!1):(alert("身份证号有误"),!1):(alert("姓名不正确"),!1)},yzmphone:function(){if(!this.phone)return console.log(2),!1;var t=/^[1][3,4,5,7,8][0-9]{9}$/;if(!t.test(this.phone))return!1;s["a"].yzmphone(this.phone);var e=this;e.sendMsgDisabled=!0;var n=window.setInterval(function(){e.time--<=0&&(e.time=60,e.sendMsgDisabled=!1,window.clearInterval(n))},1e3)}}},l=o,u=(n("c034"),n("539a"),n("8b6e"),n("e986"),n("2877")),d=Object(u["a"])(l,a,i,!1,null,"1c74cf08",null);e["default"]=d.exports},"8b6e":function(t,e,n){"use strict";var a=n("5c75"),i=n.n(a);i.a},"8c40":function(t,e,n){},c034:function(t,e,n){"use strict";var a=n("8c40"),i=n.n(a);i.a},e986:function(t,e,n){"use strict";var a=n("6153"),i=n.n(a);i.a}}]);
//# sourceMappingURL=registion.9c184b1a.js.map