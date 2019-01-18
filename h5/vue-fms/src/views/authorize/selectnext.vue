<style lang="scss" src="../css/tojdtb.scss" scoped></style>
<style type="text/css">
    .anniu {
        margin-top: 1rem !important;
    }
    .tc {
        text-align: center;
    }
    .biank {
        border-width: 0px !important;
    }
    .info-btn {
       height: 1.3rem !important;
       line-height: 1.0rem !important;
       font-size: 0.5rem !important;
       width: 100% !important;
    }
    .biank {
      margin-top: 2.4rem !important;
    }
    .anniu {
      height: 1.0rem !important;
      width: 80% !important;
      margin-left: 10% !important;
    }
</style>
<template>
  <div>
    <div class="background">
        <div class="biank">
        <div class="tc">5秒后跳转至京东授权</div>
          <div class="anniu">
               <a class="shouquan" @click="tojd"><div class="info-btn">京东授权</div></a>
          </div>
          <div class="anniu">
               <a class="shouquan" @click="totaobao"><div class="info-btn">淘宝授权</div></a>
          </div>
          <div class="anniu">
               <a class="shouquan" @click="tguo"><div class="info-btn">跳过</div></a>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import jssa from '../overallS/qjbl'

export default {

    name: 'selectnext',

    data () {
        return {
            token: jssa.token,
            t: 5
        }
    },
    created: function () {
        this.dispatch = setInterval(this.countDown, 1000);
    },
    methods: {
        tojd () {
            window.location.href = jssa.authurl + 'jingdong' + jssa.authkey + jssa.authcallbackurl + this.$route.query.fuserid;
        },
        totaobao () {
            window.location.href = jssa.authurl + 'taobao' + jssa.authkey + jssa.authcallbackurl + this.$route.query.fuserid;
        },
        tguo () {
            this.$router.push({
                path: "/creditEval?fuserid=" + this.$route.query.fuserid
            })
        },
        countDown () {
            if (this.t < 0) {
                return false;
            }
            console.log(1);
            if (this.t <= 0) {
                location.href = jssa.authurl + 'jingdong' + jssa.authkey + jssa.authcallbackurl + this.$route.query.fuserid;
            }
            this.t--;
        }
    },
    destroyed: function () {
      clearInterval(this.dispatch)
    },
}
</script>
