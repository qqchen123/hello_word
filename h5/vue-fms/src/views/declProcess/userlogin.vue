<style lang="scss" src="../css/buttonys.scss" scoped></style>
<style lang="scss" src="../css/lable.scss" scoped></style>
<style lang="scss" src="../css/div.scss" scoped></style>
<template>
	<div>
		<div class="bgtp">
			<div class="login_info">
				<div class="login_name">
					<label class="font_name">姓名</label>
				    <input placeholder="请输入员工姓名或Id" v-model="name">
				    <img src="./i/a.png" class="info_img">
				    <div class="info_xt2"></div>
				</div>
				<div class="login_pwd">
					<label class="font_name">密码</label>
				    <input type="password" placeholder="请输入密码" v-model="pwds">
				    <img src="./i/b.png" class="info_img">
				    <div class="info_xt2"></div>
				 </div>
			</div>
			<div class="login_tj">
				<div class="butjianbian2" @click="login">
					<div class="divcssc">提交</div>
				</div>
				<div class="fget">忘记密码?</div>
			</div>
		</div>
	</div>
</template>

<script>
import api from './api'
import utils from '../../utils'
export default {

  name: 'userlogin',

  data () {
    return {
    	name: 'K01',
    	pwds: '123456'
      }
    },
   created() {
  	this.setCookie()
   },
    methods: {
      login() {
  		console.log(this.name)
  	    api.declUlogin({
  	        login_name: this.name, // 'K01'
  	         pwd: this.pwds, // '123456'
  	     }).then(res => {
  	     	console.log('asdfsdf1212');
  	        if (res.body.code == -1) {
  	        	console.log(res.body)
  	            alert('登录失败')
  	        } else if (res.body.code == 0) {
  	    	   console.log(res.body)
  	    	   this.$router.push({
  	      	   path: "/userpag"
  	         })
  	    	   this.setCookie()
  	       }
  	     })
  	  },
  	setCookie() {
  		utils.setCookieMinutes('login_name', this.name, 30)
  	  }
   }
}
</script>

<style lang="scss" scoped>
.info_img {
	width: rem(49);
	height: rem(51);
}
.bgtp {
   height: rem(1334);
   background: url(i/1.jpg) no-repeat fixed top;
   background-size:100% 100%;
   -moz-background-size:100% 100%;
}
.login_info {
	height: rem(900);
	width: rem(750);
	border: rem(1) white solid;
}
.login_name {
	display: block;
	margin-top: rem(530);
    margin-left: 15%;
}
.login_pwd {
	margin-top: rem(90);
	margin-left: 15%;
}
.fget {
	margin-top: rem(15);
	margin-left: 40%;
	font-weight: bold;
}
</style>
