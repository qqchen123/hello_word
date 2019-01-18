<template>
  <div class="main">
    <div class="gradiant flex-c-m">
      <div class="path">
        <div>以下授权等你来完成哟！</div>
        <div class="bg">
          <div class="overall" :class="{'finish': finished === '100'}" :style="{'width': finished + '%'}">
          </div>
        </div>
        <div class="task">0/5</div>
      </div>
    </div>
    <div class="info">
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>手机运营商</div>
          <div class="info-btn flex-c-m">查看</div>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>手机运营商</div>
          <div class="info-btn flex-c-m">去授权</div>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>手机运营商</div>
          <div class="info-btn flex-c-m">去授权</div>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>手机运营商</div>
          <div class="info-btn flex-c-m">去授权</div>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>手机运营商</div>
          <div class="info-btn flex-c-m">去授权</div>
        </div>
      </div>
      <div class="info-box">
        <div class="info-item flex-m flex-space">
          <div>手机运营商</div>
          <div class="info-btn flex-c-m">去授权</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import api from './api'
export default {

  name: 'keepRegister',

  data () {
    return {
      finished: '80'
    }
  },
  created () {
  },
  methods: {
    confirmLetter () {
      console.log('use confirmLetter function')
    }
  }
}
</script>

<style lang="scss" scoped>
  .main{
    background-color: #f7f4f4;
    .gradiant{
      width: 100%;
      height: rem(220);
      background: -prefix-linear-gradient(left, #00a3ea, #00ebcd);
      background: linear-gradient(to right, #00a3ea, #00ebcd);
      .path{
        width: rem(565);
        height: rem(100);
        padding: 0 rem(20);
        color: #fff;
        font-size: rem(16);
        font-weight: bold;
      }
      .bg{
        margin: rem(15) 0;
        width: 100%;
        height: rem(18);
        background-color: #fff;
        border-radius: rem(15);
      }
      .overall{
        border-top-left-radius: rem(15);
        border-bottom-left-radius: rem(15);
        // width: 80%;
        height: rem(18);
        background-image: -webkit-linear-gradient(left, #018675, #045f87);
      }
      .finish{
        width: 100%;
        border-radius: rem(15);
        background-image: -webkit-linear-gradient(left, #018675, #045f87);
      }
      .task{
        float: right;
      }
    }
    .info{
      top: rem(170);
      position: absolute;
      padding: 0 rem(52);
      width: 100%;
      font-size: rem(24);
      color: #090909;
      .info-box{
        margin-top: rem(20);
      }
      .info-item{
        height: rem(130);
        background-color: #fff;
        border-radius: rem(10);
        padding: 0 rem(30);
      }
      .info-btn{
        margin-top: rem(20);
        background-color: #1bcfc6;
        width: rem(160);
        height: rem(56);
        border-radius: rem(20);
        color: #fff;
      }
    }
  }

</style>
