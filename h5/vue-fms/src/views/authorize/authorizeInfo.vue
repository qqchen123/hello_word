<style lang="scss" src="../css/creditEvalcss.scss" scoped></style>
<template>
  <div class="main">
    <div class="gradiant flex-c-m">
      <div class="path">
        <div>以下授权等你来完成哟！</div>
        <div class="bg">
          <div class="overall" :class="{'finish': finished === '100'}" :style="{'width': finished + '%'}">
          </div>
        </div>
        <div class="task">1/2</div>
      </div>
    </div>
    <div class="info">
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>用户基本信息</div>
          <div class="info-btn flex-c-m">已完成</div>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>手机运营商</div>
          <div class="info-btn flex-c-m"><a class="shouquan" @click="jun">去授权</a></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import url from '../overallS/qjbl'
export default {

  name: 'keepRegister',

  data () {
     return {
      finished: '50'
      }
   },
    methods: {
      jun () {
        console.log(url.authurl + 'carrier' + url.authkey + this.$BASE_URL + 'Qiye/tojd');
        window.location.href = url.authurl + 'carrier' + url.authkey + this.$BASE_URL + 'Qiye/tojd';
      }
    }
}
</script>
