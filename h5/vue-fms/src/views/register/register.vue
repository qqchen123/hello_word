<template>
  <div class="background page-content">
    <div class="main">
      <div class="title">注册</div>
      <div class="box">
        <div class="joint-wrap content">
          <ul>
            <li>
              <div class="input">
                <input v-model="name" type="text" placeholder="请输入真实姓名">
              </div>
            </li>
            <li>
              <div class="input">
                <input v-model="idCard" type="tel" placeholder="请输入有效身份证号">
              </div>
            </li>
            <li>
              <div class="input">
                <input v-model="phone" type="tel" placeholder="请输入手机号">
              </div>
            </li>
            <li>
              <div class="input">
                <input v-model="pw" type="password" placeholder="请设置6至20位数字或字符的密码" maxlength="20">
              </div>
            </li>
            <li>
              <div class="input">
                <input v-model="checkedPw" type="password" maxlength="20" placeholder="请确认密码">
              </div>
            </li>
            <div class="verification flex-space flex-m">
              <div class="input">
                <input v-model="verify" type="tel" placeholder="请输入验证码">
              </div>
              <div class="btn">获取验证码</div>
            </div>
          </ul>
        </div>
        <div class="agree flex-c-m">
          <input class="select-icon" type="checkbox" v-model="agree">
          <div @click="agree = !agree">
            我已阅读并同意
          </div>
          <span @click="confirmLetter">
          用户协议
          </span>
        </div>
      </div>
    </div>
    <bl-button @click="createAccount">点击调用请求开户接口</bl-button>
    <bl-button @click="createAccount" class="margin-top-20">点击弹出弹框</bl-button>
    <!-- toast -->
    <div class="toast">
      ;aa;
    </div>
  </div>
</template>

<script>
import api from './api'
export default {

  name: 'register',

  data () {
    return {
      name: '',
      idCard: '',
      phone: '',
      pw: '',
      checkedPw: '',
      verify: '',
      agree: false
    }
  },
  created () {
  },
  methods: {
    confirmLetter () {
      console.log('use confirmLetter function')
    },
    // 请求开户接口例子
    createAccount () {
      api.createAccount({
        name: '成功', // this.name
        idnumber: '450104198602081527', // this.idCard
        mobile: '18717816183' // this.phone
      }).then(res => {
        if (res.body.code == -1) {
          // 提示组件
          this.$toast({
            message: res.body.msg,
            position: "bottom"
          })
        } else if (res.body.code == 0) {
          this.$toast({
            message: res.body.msg,
            position: "bottom"
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .background{
    background: url(i/backg2.jpg) no-repeat fixed top;
    background-size:100% 100%;
    -moz-background-size:100% 100%;
  }
  .main{
    height: rem(800);
    margin: rem(30);
  }
  .title{
    font-size: rem(34);
    font-weight:bold;
    color: #090909;
    padding: rem(25) 0;
    text-align: center;
  }
  .content{
    ul{
      width: 70%;
      margin: 0 auto;
    }
    li{
      margin-top: rem(20);
      border: 1px solid #282828;
      height: rem(56);
      line-height: rem(56);
    }
    input{
      font-size: rem(24);
      width: 100%;
    }
    .input{
      padding: 0 rem(15);
    }
    .verification{
      height: rem(56);
      margin-top: rem(20);
      .input{
        height: rem(56);
        width: 50%;
        line-height: rem(56);
        border: 1px solid #282828;
      }
      .btn{
        height: rem(56);
        line-height: rem(56);
        padding: 0 rem(15);
        background-color: #eee;
      }
    }
  }
  .agree {
    line-height: rem(40);
    color: #666666;
    text-align: center;
    padding: rem(50) 0;
    span {
      color: #3913f0;
    }
    .select-icon {
      z-index: 3;
      border-radius: rem(6);
      margin-right: rem(16);
      width: rem(28) !important;
      height: rem(28) !important;
      min-height: rem(28) !important;
      &:checked {
        background-color: #666666;
        border: none;
      }
    }
  }
  .margin-top-20{
    margin-top: rem(20);
  }
  .toast{
    height: rem(100);
    background-color: tan;
  }
</style>
