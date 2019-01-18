<template>
  <div class="background page-content">
    <nav>
      <div class="navigation flex-space flex-c-m">
        <div class="nav-back flex-c-m">
          <img src="../../assets/nav_back.png">
        </div>
        <div class="nav-title">注册登录</div>
        <div class="nav-right"></div>
      </div>
    </nav>
    <div class="info">
      已发送验证码至150 0185 4886
      <input type="tel" name="" v-modal="code" maxlength="4">
      <div class="vaild-code-input flex-c-m">
        <div class="code-item">{{code.substring(0, 1)}}</div>
        <div class="code-item">{{code.substring(1, 2)}}</div>
        <div class="code-item">{{code.substring(2, 3)}}</div>
        <div class="code-item">{{code.substring(3, 4)}}</div>
      </div>
    </div>
  </div>
</template>

<script>
// import api from './api'
export default {

  name: 'vaildCode',

  data () {
    return {
      code: ''
    }
  },
  methods: {
  }
}
</script>

<style lang="scss" scoped>
.background{
  height: 100%;
  background: url(i/bg.png) no-repeat fixed top;
  background-size:100% 100%;
  -moz-background-size:100% 100%;
}
.header{
  height: rem(135);
  .header-t{
    height: rem(135);
    left: 20%;
    right:20%;
    width: 80%;
    background-color: tan;
    text-align: center;
    position: fixed;
  }
}
.info{
  margin: rem(150) rem(140);
  text-align: center;
  font-size: rem(24);
  .vaild-code-input{
    margin-top: rem(200);
    .code-item{
      margin: rem(10);
      height: rem(50);
      line-height: rem(50);
      width: rem(50);
      border-bottom: 1px solid #999;
    }
  }
}
input{
  background-color: tan;
  height: rem(50);
  position: absolute;
  top: rem(400);
}
</style>
