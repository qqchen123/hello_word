<style lang="scss" src="../css/tite.scss" scoped></style>
<style lang="scss" src="../css/ygbg.scss" scoped></style>
<style lang="scss" src="../css/div.scss" scoped></style>
<style lang="scss" src="../css/lable.scss" scoped></style>
<style lang="scss" src="../css/buttonys.scss" scoped></style>
<style lang="scss" src="../css/daohangt.scss" scoped></style>
<template>
	<div>
		<div class="bgtp">
			<div class="tite">
				报单
			</div>
			<div class="userid">
				{{this.employee}}
			</div>
			<div class="info_tt">
			   <div class="content">
		         <div class="content-box">
		            <div class="box">
		              <span class="t1">报单</span>
		            </div>
		            <div class="box">
		                <span class="arrow"></span>
		                <span class="arrow2"></span>
		                <span class="t1">评估</span>
		            </div>
		            <div class="box">
		                <span class="arrow"></span>
		                <span class="arrow2"></span>
		                <span class="t1">进件</span>
		            </div>
		            <div class="box">
		                <span class="arrow"></span>
		                <span class="arrow2"></span>
		                <span class="t1">面审</span>
		            </div>
		            <div class="box">
		                <span class="arrow"></span>
		                <span class="arrow2"></span>
		                <span class="t1">审核</span>
		            </div>
		            <div id="arrow3" class="info_box">
		                <span class="arrow"></span>
		                <span class="arrow2"></span>
		                <span class="t1">放款</span>
		            </div>
		            <span class="arrow"></span>
	                <span class="arrow2"></span>
                  </div>
			   </div>
			</div>
			<div class="infos">
				<div class="login_name login_pwd">
					<label class="font_name1">需求金额</label>
				    <input type="text" v-model="amount">
				    <img src="./i/g.png" class="info_img">
				    <div class="info_xt1"></div>
				</div>
				<div class="login_pwd">
					<label class="font_name1">借款用途</label>
				    <input type="text" v-model="useby">
				    <img src="./i/f.png" class="info_img">
				    <div class="info_xt1"></div>
				</div>
				<div class="login_pwd">
					<label class="font_name1">还款来源</label>
				    <input type="text" v-model="repaymentby">
				    <img src="./i/f.png" class="info_img">
				    <div class="info_xt1"></div>
				</div>
			</div>
			<div class="login_tj">
				<div class="butjianbian2" @click="dssubmit">
					<div class="divcssc">提交</div>
				</div>
		    </div>
		</div>
	</div>
</template>

<script>
import api from './api'
import utils from '../../utils'
export default {

  name: 'declProcess3',

  data () {
    return {
    	amount: '',
    	useby: '',
    	repaymentby: '',
    	id: '',
    	employee: utils.getCookie('login_name'),
      }
    },
    created() {
    	this.id = this.$route.fullPath.split("?")[1].split("=")[1];
    	console.log(this.id)
    },
   check () {
    		if (!utils.getCookie('login_name')) {
	    		alert('请重新登录');
	    		window.location.href = '#/userlogin'
	    	}
    	},
    methods: {
    	dssubmit () {
    		if (!/^[0-9.]+$/.test(this.amount)) {
    			 alert("请输入正确的金额");
    				return false;
    			}
    		if (this.useby == '') {
    			 alert("请输入借款用途")
    				return false;
    			}
    		if (this.repaymentby == '') {
    			 alert("请输入还款来源")
    				return false;
    			}
    		api.subDeclaration({
    			amount: this.amount,
    			useby: this.useby,
    			repaymentby: this.repaymentby,
    			id: this.id,
    			employee: this.employee
    		}).then(res => {
				console.log('asdfsdf1212');
				if (res.body.code == -1) {
				console.log(res.body)
				alert('提交失败')
				} else if (res.body.code == 0) {
					console.log(res.body)
					window.location.href = '#/declSuccess' + "?id=" + res.body.data.id
			    }
			})
    	}
    }
}
</script>

<style lang="scss" scoped>

.infos {
 height: rem(400);
 margin-left: 10%;
}
.login_pwd {
	margin-top: rem(50);
}
</style>
