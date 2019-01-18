<style lang="scss" src="../css/tite.scss" scoped></style>
<style lang="scss" src="../css/bk.scss" scoped></style>
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
			<form id="subc">
				<div class="infos">
					<div class="login_name">
						<label class="font_name1">客户姓名</label>
					    <input type="text" v-model="username">
					    <img src="./i/d.png" class="info_img">
					    <div class="info_xt1"></div>
					</div>
					<div class="login_pwd">
						<label class="font_name1">客户身份证</label>
					    <input type="text" v-model="useridcard">
					    <img src="./i/a.png" class="info_img">
					    <div class="info_xt1"></div>
					</div>
				</div>
				<div class="info_dd">
					<div class="myorder1">
						<div class="info_myorder">身份证正面</div>
						<div class="info_text">
							<input class="input_css" type="file" v-on:change="changeFn($event)" name="cardp">
						</div>
					</div>
					<div class="myorder1">
						<div class="info_myorder">身份证反面</div>
						<div class="info_text">
							<input type="file" v-on:change="changeFn($event)" name="cardpf">
						</div>
					</div>
				</div>
				<input type="hidden" v-model="employee">
				<div class="login_tj">
					<div class="butjianbian2" @click="bsubmit">
						<div class="divcssc">提交</div>
					</div>
			    </div>
			</form>
		</div>
	</div>
</template>

<script>
import api from './api'
import utils from '../../utils'
export default {

  name: 'declForm',

  data () {
    return {
    	username: '',
        useridcard: '',
        formData: new FormData(),
        employee: utils.getCookie('login_name')
      }
    },
    created() {
    	this.check();
    },
    methods: {
    check () {
    		if (!utils.getCookie('login_name')) {
	    		alert('请重新登录');
	    		window.location.href = '#/userlogin'
	    	}
    	},
    changeFn (e) {
            this.deviceArray = [];
            let deviceFile = e.target.files;
            if (this.formData.has(e.target.name)) {
            	this.formData.set(e.target.name, deviceFile[0])
            } else {
            	this.formData.append(e.target.name, deviceFile[0])
            }
          },
    bsubmit () {
    	    var tmp = this.formData
    	        tmp.append('username', this.username)
    	        tmp.append('useridcard', this.useridcard)
    	        tmp.append('employee', utils.getCookie('login_name'))
    	    console.log(tmp);
    	    if (!/^[\u4E00-\u9FA5A-Za-z]+$/.test(this.username)) {
                      alert("姓名不正确");
                   return false;
                }
           if (!/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(this.useridcard)) {
                    alert("身份证号有误");
                   return false;
                }
           if (!this.formData.has("cardp")) {
           	       alert("请上传省份证正面");
           	       return false;
              }
           if (!this.formData.has("cardpf")) {
           	       alert("请上传省份证反面");
           	       return false;
              }
    		api.subCustomerInfo(
			     tmp
			).then(res => {
				console.log('asdfsdf1212');
				if (res.body.code == -1) {
				console.log(res.body)
				alert('登录失败')
			} else if (res.body.code == 0) {
				console.log(res.body)
				this.$router.push({
					path: "/declProcess2" + "?id=" + res.body.data.id
					// path: "/declProcess2"
				})
				this.setCookie()
			    }
			})
         }
    }
}
</script>

<style lang="scss" scoped>
.content {
  margin-top: rem(-40);
}
.infos {
 margin-top: rem(50);
 height: rem(150);
 margin-left: 10%;
}
.login_pwd {
	margin-top: rem(30);
}
.login_tj {
	margin-top: rem(70);
}
</style>
