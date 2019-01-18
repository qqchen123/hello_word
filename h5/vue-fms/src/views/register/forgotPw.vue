<template>
	<div class="background page-content">
		<nav>
		  <div class="navigation flex-space flex-c-m">
		    <div class="nav-back flex-c-m">
		    	<img src="../../assets/nav_back.png">
		    </div>
		    <div class="nav-title">找回密码</div>
		    <div class="nav-right"></div>
		  </div>
		</nav>
		<div class="info">
			<div class="info-input">
				<input type="text" name="" placeholder="请输入手机号" v-model="phone" maxlength="11">
			</div>
			<div class="info-input">
				<input type="password" name="" placeholder="请设置6至20位数字或字符的密码" v-model="pw">
			</div>
			<div class="info-input">
				<input type="password" name="" placeholder="请确认密码" v-model="pwComfirm">
			</div>
		</div>
		<div class="btn flex-c">
			<div class="btn-item flex-c-m" @click="forgotpwd">提 交</div>
		</div>
	</div>
</template>

<script>
import api from './api'
export default {

 name: 'forgotPw',

  data () {
    return {
    	phone: '',
    	pw: '',
    	pwComfirm: ''
    }
  },
  methods: {
  	confirmLetter () {
  	  console.log('use confirmLetter function')
  	},
  forgotpwd () {
  	  api.forgotpwd({
  	   login_name: '18717816183', // this.name
  	   pwd: '12345', // this.idCard
  	  }).then(res => {
  	    if (res.body.code != 0) {
  	      // 提示组件
  	     alert(res.body.msg)
  	    } else if (res.body.code == 0) {
  	    	console.log(res.body)
  	      alert('设置成功')
  	      this.$router.push({
  	      	path: "/login"
  	      })
  	    }
  	  })
  	}
  }
}
</script>

<style lang="scss" scoped>
.background{
  height: 100%;
  background: url(i/backg2.jpg) no-repeat fixed top;
  background-size:100% 100%;
  -moz-background-size:100% 100%;
}
.navigation {
	margin-top:rem(20);
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
	.info-input{
		margin-top: rem(40);
		background-color: white;
		height: rem(55);
		line-height: rem(55);
		border-radius: rem(15);
		border: 1px solid #bdbdbd;
		input{
			padding: 0 rem(15);
		}
	}
	.forgot-pw{
		right: rem(150);
		position: absolute;
		margin-top: rem(25);
		font-size: rem(20);
		color: #b4b3ba;
	}
}
.btn{
	margin: 0 rem(150);
	height: rem(100);
	color: white;
	text-align: center;
	.btn-item{
		background-color: #a39fb5;
		height: rem(60);
		width: rem(222);
		border-radius: rem(15);
		box-shadow: rem(2) rem(4) #c0bbd2;
	}
}
</style>
