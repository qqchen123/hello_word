<style lang="scss" src="../css/tojdtb.scss" scoped></style>
<template>
  <div>
    <div class="background">
        <div class="biank">
          <div class="anniu">
               <a class="shouquan" @click="tojd"><div class="info-btn">京东授权</div></a>
          </div>
          <div class="anniu">
               <a class="shouquan" @click="tguo"><div class="info-btn">跳过</div></a>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import api from '../register/api'
import url from '../overallS/qjbl'
export default {

  name: 'registion',
  data () {
    return {
    }
  },
  mounted () {
    this.prea()
  },
  methods: {
     tojd () {
      window.location.href = url.authurl + 'jingdong' + url.authkey + url.authcallbackurl + this.$route.query.fuserid;
     },
     tguo () {
      window.location.href = '#/totaoba?fuserid=' + this.$route.query.fuserid;
     },
     prea () {
       console.log(this.$route.query.taskId)
       console.log(this.$route.query.fuserid)
       api.getaccs({
         task_id: this.$route.query.taskId,
         fuserid: this.$route.query.fuserid
       })
     }
   }
 }
</script>
