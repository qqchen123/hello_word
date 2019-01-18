<!-- 引用css 方法1 -->
<!-- <style lang="scss" src="./css/_login.scss" scoped></style> -->
<template>
	<div class="background page-content">
		<nav>
		  <div class="navigation flex-space flex-c-m">
		    <div class="nav-back flex-c-m">
		    	<img src="../../assets/nav_back.png">
		    </div>
		    <div class="nav-title">登录</div>
		    <div class="nav-right"></div>
		  </div>
		</nav>
		<div class="info">
			<div class="info-input">
				<input type="text" name="" placeholder="请输入手机号" v-model="phone" maxlength="11">
			</div>
			<div class="info-input">
				<input type="password" name="" placeholder="请输入密码" v-model="pw">
			</div>
			<a class="forgot-pw" href="#/forgotPw">忘记密码？</a>
		</div>
		<div class="btn flex-space">
			<div class="btn-item flex-c-m" @click="login">登 录</div>
			<div class="btn-item flex-c-m"><a href="#/registion">注 册</a></div>
		</div>
	</div>
</template>

<script>
import api from './api'
// import './css/_login.scss' // 引用css 方法2
require('./css/_login.scss') // 引用css 方法3
export default {

  name: 'login',

  data () {
    return {
    	phone: '',
    	pw: ''
    }
  },
  methods: {
  	confirmLetter () {
  	  console.log('use confirmLetter function')
  	},
  	// 请求开户接口例子
  	login () {
  	  api.login({
  	   login_name: '18717816183', // this.name
  	   pwd: '123456', // this.idCard
  	  }).then(res => {
  	    if (res.body.code == -1) {
  	      alert('登录失败')
  	    } else if (res.body.code == 0) {
  	    	alert('登录成功')
  	      this.$router.push({
  	      path: "/creditEval"
  	      })
  	    }
  	  })
  	}
  }
}
</script>
