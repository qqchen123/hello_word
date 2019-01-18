<style lang="scss" src="../css/creditEvalcss.scss" scoped></style>
<style lang="scss" src="../css/buttonys.scss" scoped></style>
<style lang="scss" src="../css/div.scss" scoped></style>
<template>
  <div class="main">
    <div class="gradiant flex-c-m">
      <div class="path">
        <div>以下授权等你来完成哟！</div>
        <div class="bg">
           <div class="overall" :style="{'width': (finished) + '%'}">
          </div>
        </div>
        <div class="task">{{this.pro}}/5</div>
      </div>
    </div>
    <div class="info">
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>
            <img src="./i/yunys3.png" class="imgs">
            手机运营商
         </div>
          <a id="a_yys" href=""><div class="info-btn flex-c-m">{{this.yys}}</div></a>
        </div>
      </div>
       <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>
            <img src="./i/jingdong3.png"  class="img_css">
          京东
          </div>
          <a id="a_jd" href=""><div class="info-btn flex-c-m">{{this.jd}}</div></a>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>
            <img src="./i/taobao3.png" class="img_css2">
           淘宝
           </div>
          <a id="a_taobao" href="" class="shouquan"><div class="info-btn flex-c-m">{{this.taobao}}</div></a>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>
            <img src="./i/xinyongk3.png" class="img_css2">
          网银
          </div>
          <a id="a_bank" href="" class="shouquan"><div class="info-btn flex-c-m">{{this.bank}}</div></a>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>
            <img src="./i/xinyongk3.png" class="img_css2">
          信用卡
          </div>
          <a id="a_credit" href="" class="shouquan"><div class="info-btn flex-c-m">{{this.credit}}</div></a>
        </div>
      </div>
    </div>
    <div class="tuichu">
      <a href="#/registion">
        <div class="butgreentc">
          <div class="divcssc">退出</div>
        </div>
      </a>
    </div>
  </div>
</template>

<script>
import api from '../register/api'
import url from '../overallS/qjbl'
export default {

  name: 'creditEval',

  data () {
    return {
     finished: 0,
     time: 10,
     bank: '',
     sendMsgDisabled: false,
     yys: '',
     jd: '',
     taobao: '',
     credit: '',
     a_credit: '',
     a_bank: '',
     a_yys: '',
     a_jd: '',
     a_taobao: '',
     res_obj: '',
     flag_yys: 0,
     flag_jd: 0,
     flag_taobao: 0,
     flag_bank_card: 0,
     flag_credit_card: 0,
     flag_gjj: 0,
     pro: ''
     }
  },
    mounted: function () {
          this.dispatch = setInterval(this.timer, 1000);
    },
    destroyed: function () {
      clearInterval(this.dispatch)
    },
    methods: {
        timer: function () {
            let bb = 1
            let str = ''
            let fuserid = this.$route.query.fuserid
            api.getstatus({
                fuid: this.$route.query.fuserid
            }).then(res => {
                console.log(res);
                if (res.body.code == 0) {
                  str = res.body.data;
                  this.res_obj = str;
                }
            }).catch(e => {
                console.log(e);
            })
            console.log(this.res_obj);
            if (this.res_obj) {
                this.yys = this.res_obj['yys'][0];
                this.jd = this.res_obj['jd'][0];
                this.taobao = this.res_obj['taobao'][0];
                this.credit = this.res_obj['credit_card'][0];
                this.bank = this.res_obj['bank_card'][0];
                if (this.res_obj['credit_card'][1] != '') {
                  // a_credit
                  this.a_credit = url.authurl + 'banklist' + url.authkey + url.base + 'creditcards?fuserid=' + fuserid;
                  document.getElementById("a_credit").href = this.a_credit;
                  this.flag_credit_card = 0;
                } else {
                  document.getElementById("a_credit").href = 'javascript:void(0)';
                  this.flag_credit_card = 1;
                }
                if (this.res_obj['bank_card'][1] != '') {
                  // a_bank
                  this.a_bank = url.authurl + 'banklist' + url.authkey + url.base + 'bankcard?fuserid=' + fuserid;
                  document.getElementById("a_bank").href = this.a_bank;
                  this.flag_bank_card = 0;
                } else {
                  document.getElementById("a_bank").href = 'javascript:void(0)';
                  this.flag_bank_card = 1;
                }
                if (this.res_obj['yys'][1] != '') {
                  // a_yys
                  this.a_yys = url.authurl + 'carrier' + url.authkey + url.base + 'yysid?fuserid=' + fuserid;
                  document.getElementById("a_yys").href = this.a_yys;
                  this.flag_yys = 0;
                } else {
                  document.getElementById("a_yys").href = 'javascript:void(0)';
                  this.flag_yys = 1;
                }
                if (this.res_obj['jd'][1] != '') {
                  // a_jd
                  this.a_jd = url.authurl + 'jingdong' + url.authkey + url.base + 'jdid?fuserid=' + fuserid;
                  document.getElementById("a_jd").href = this.a_jd;
                  this.flag_jd = 0;
                } else {
                  document.getElementById("a_jd").href = 'javascript:void(0)';
                  this.flag_jd = 1;
                }
                if (this.res_obj['taobao'][1] != '') {
                  // a_taobao
                  this.a_taobao = url.authurl + 'taobao' + url.authkey + url.base + 'taobaoid?fuserid=' + fuserid;
                  document.getElementById("a_taobao").href = this.a_taobao;
                  this.flag_taobao = 0;
                } else {
                  document.getElementById("a_taobao").href = 'javascript:void(0)';
                  this.flag_taobao = 1;
                }
                this.finished = 0;
                this.finished += this.flag_yys ? 20 : 0;
                this.finished += this.flag_taobao ? 20 : 0;
                this.finished += this.flag_jd ? 20 : 0;
                this.finished += this.flag_bank_card ? 20 : 0;
                this.finished += this.flag_credit_card ? 20 : 0;
                this.pro = (this.finished / 20);
            }
            if (bb) {
                return false
            }
            let aa = 0
            if (aa) {
                return false
            }
        }
    }
 }
</script>

<style lang="scss" scoped>
.imgs {
  width: rem(116);
  height: rem(90);
}
.img_css {
    width: rem(102);
    height: rem(70);
}
.img_css2 {
    width: rem(99);
    height: rem(76);
}
.tuichu {
  margin-top: 97%;
}
</style>
