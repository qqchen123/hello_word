<template>
	<div class="background page-content">
		<nav>
		  <div class="navigation flex-space flex-c-m">
		    <div class="nav-back flex-c-m">
		    	<img src="../../assets/nav_back.png">
		    </div>
		    <div class="nav-title">获取运营商报告</div>
		    <div class="nav-right"></div>
		  </div>
		</nav>
		<div class="info">
			<div class="info-input">
				<input type="password" name="" placeholder="请输入服务密码" v-model="pw">
			</div>
			<div class="vaild flex-space">
				<div class="vaild-input">
					<input type="text" name="" placeholder="请输入验证码">
				</div>
				<div class="vaild-btn flex-c">
					<div class="flex-c-m">获取验证码</div>
				</div>
			</div>
		</div>
		<div class="btn flex-c">
			<div class="btn-item flex-c-m">提 交</div>
		</div>
	</div>
</template>

<script>
export default {

  name: 'login',

  data () {
    return {
    	pw: '',
    	vaild: ''
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
		.vaild{
			width: rem(50);
		}
	}
	.vaild{
		margin-top: rem(40);
		// background-color: tan;
		height: rem(55);
		line-height: rem(55);
		.vaild-input{
			width: 55%;
			background-color: white;
			border-radius: rem(15);
			border: 1px solid #bdbdbd;
			input{
				width: 100%;
				padding: 0 rem(15);
			}
		}
		.vaild-btn{
			width: 30%;
			border-radius: rem(15);
			background-color: #a39fb5;
			color: white;
			font-size: rem(20);
		}
	}
}
.btn{
	margin: 0 rem(150);
	height: rem(100);
	color: white;
	// text-align: center;
	.btn-item{
		background-color: #a39fb5;
		height: rem(60);
		width: rem(222);
		border-radius: rem(15);
		box-shadow: rem(2) rem(4) #c0bbd2;
	}
}
</style>
