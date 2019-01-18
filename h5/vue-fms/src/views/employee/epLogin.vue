<template>
	<div>
		<div class="ep-top">员工登陆</div>
		<div class="input">
			<ul>姓名<input type="text" placeholder="请输入员工姓名或Id" v-model="name"><img src="./i/id@2x.png" alt=""></ul>
			<ul>密码<input type="password" placeholder="请输入密码"  v-model="pw"><img src="./i/锁@2x.png" alt=""></ul>
		</div>
		<div class="submit">
			<div class="btn flex-c-m" @click="login">提 交</div>
			<div class="forgot">忘记密码?</div>
		</div>
		<div class="ep-bottom"></div>
	</div>
</template>

<script>
import api from './api'
import utils from '../../utils'
export default {

  name: 'epLogin',

  data () {
    return {
    	name: 'aaa',
    	pw: ''
    }
  },
  created() {
  	this.setCookie()
  },
  methods: {
  	login() {
  		console.log(this.name, this.pw)
  	 api.frontUserLogin({
  	   login_name: this.name, // 'K01'
  	   pwd: this.pw, // '123456'
  	  }).then(res => {
  	    if (res.body.code == -1) {
  	      alert('登录失败')
  	    } else if (res.body.code == 0) {
  	    	alert('登录成功')
  	    	this.setCookie()
  	    }
  	  })
  	},
  	setCookie() {
  		utils.setCookie('login_name', this.name, 1)
  	}
  }
}
</script>

<style lang="scss" scoped>
.ep-top{
	width: 100%;
	height: rem(400);
	background: url('./i/top.png') no-repeat;
	background-size: 100% auto;
	text-align: center;
	color: #fff;
	font-size: rem(44);
	line-height: rem(200);
}
.ep-bottom{
	height: rem(298);
	width: 100%;
	bottom: 0;
	position: absolute;
	background: url('./i/bottom.png') no-repeat;
	background-size: 100% auto;
}
.input{
	margin: rem(200) rem(70);
	color: #070707;
	line-height: rem(40);
	ul{
		margin-top: rem(60);
		font-size: rem(30);
		border-bottom: 1px solid #d1d2d4;
	}
	input{
		margin: 0 rem(30);
	}
	img{
		height: rem(36);
		width: auto;
		right: rem(75);
		position: absolute;
	}
}
.submit{
	margin: 0 rem(130);
	font-size: rem(26);
	.btn{
		color: #fff;
		background: -prefix-linear-gradient(left, #1f6ac7, #38b4fd);
		background: linear-gradient(to right, #1f6ac7, #38b4fd);
		height: rem(80);
		line-height: rem(80);
		font-size: rem(38);
	}
	.forgot{
		text-align: center;
		margin-top: rem(20);
	}
}
</style>
