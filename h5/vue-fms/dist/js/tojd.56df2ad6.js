(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["tojd"],{"4d13":function(t,n,a){"use strict";var r=a("80f7"),e=a.n(r);e.a},"80ce":function(t,n,a){"use strict";var r=a("2b0e"),e=a("705f");n["a"]={createAccount:function(t){return r["a"].http.post(e["a"].url+"/index.php/Qiye/front_end_reg",t)},login:function(t){return r["a"].http.post(e["a"].url+"/index.php/Qiye/front_end_login",t)},forgotpwd:function(t){return r["a"].http.post(e["a"].url+"/index.php/Qiye/front_end_set_pwd",t)},cAccount:function(t){return r["a"].http.post(e["a"].url+"/index.php/Qiye/front_end_reg",t)},backurl:function(t){return e["a"].url+"/index.php/"},yzmphone:function(t){return r["a"].http.get(e["a"].url+"/index.php/Sms/send_sms/"+t)},getstatus:function(t){return r["a"].http.post(e["a"].url+"/index.php/Qiye/user_report_status",t)},getaccs:function(t){return r["a"].http.post(e["a"].url+e["a"].callbackroute+"yys_report",t)},jdye:function(t){return r["a"].http.post(e["a"].url+e["a"].callbackroute+"jd_report",t)},taobao:function(t){return r["a"].http.post(e["a"].url+e["a"].callbackroute+"taobao_report",t)},creditcard:function(t){return r["a"].http.post(e["a"].url+e["a"].callbackroute+"credit_card_report",t)},bankcard:function(t){return r["a"].http.post(e["a"].url+e["a"].callbackroute+"bank_card_report",t)},gongjj:function(t){return r["a"].http.post(e["a"].url+e["a"].callbackroute+"gjj_report",t)}}},"80f7":function(t,n,a){},"9ad2":function(t,n,a){"use strict";a.r(n);var r=function(){var t=this,n=t.$createElement,a=t._self._c||n;return a("div",[a("div",{staticClass:"background"},[a("div",{staticClass:"biank"},[a("div",{staticClass:"anniu"},[a("a",{staticClass:"shouquan",on:{click:t.tojd}},[a("div",{staticClass:"info-btn"},[t._v("京东授权")])])]),a("div",{staticClass:"anniu"},[a("a",{staticClass:"shouquan",on:{click:t.tguo}},[a("div",{staticClass:"info-btn"},[t._v("跳过")])])])])])])},e=[],o=a("80ce"),u=a("705f"),i={name:"registion",data:function(){return{}},mounted:function(){this.prea()},methods:{tojd:function(){window.location.href=u["a"].authurl+"jingdong"+u["a"].authkey+u["a"].authcallbackurl+this.$route.query.fuserid},tguo:function(){window.location.href="#/totaoba?fuserid="+this.$route.query.fuserid},prea:function(){console.log(this.$route.query.taskId),console.log(this.$route.query.fuserid),o["a"].getaccs({task_id:this.$route.query.taskId,fuserid:this.$route.query.fuserid})}}},c=i,s=(a("4d13"),a("2877")),l=Object(s["a"])(c,r,e,!1,null,"568eec67",null);n["default"]=l.exports}}]);
//# sourceMappingURL=tojd.56df2ad6.js.map