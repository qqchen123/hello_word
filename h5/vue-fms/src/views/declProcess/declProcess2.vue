<style lang="scss" src="../css/tite.scss" scoped></style>
<style lang="scss" src="../css/bk.scss" scoped></style>
<style lang="scss" src="../css/ygbg.scss" scoped></style>
<style lang="scss" src="../css/div.scss" scoped></style>
<style lang="scss" src="../css/lable.scss" scoped></style>
<style lang="scss" src="../css/buttonys.scss" scoped></style>
<style lang="scss" src="../css/daohangt.scss" scoped></style>
<style lang="scss" src="../css/tanchuan.scss" scoped></style>
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
				<div class="login_name">
					<!-- <label class="font_name1">小区名\路名</label> -->
				    <input type="text" placeholder="小区名\路名" class="village" v-bind:readonly="isReadOnly" v-model="rolename">
				    <span @click="shous">{{sbtn}}</span>
				    <!-- <img src="./i/e.png" class="info_img"> -->
				    <div class="info_xt1"></div>
				    <input type="text" placeholder="房屋面积" class="village" v-model="area">
				    <div class="info_xt1"></div>
				</div>
				<div class="dialog-wrap" v-if="isShow">
					<div class="dialog-cover"  v-if="isShow" @click="closeMyself"></div>
					<transition name="drop">
					    <div class="dialog-content"  v-if="isShow">
					      <div class="fangz">
					      	   <p class="dialog-close" @click="closeMyself">x</p>
					           <slot>房子信息</slot>
					           <!-- <div v-html="flist"></div> -->
					           <!-- Jeanne：不用拼接网页 -->
					          <div v-if="addressIsShow" class="address" v-for="item in mockAddress" :key="item.index" @click="singleAddInfo(item)">
					        	{{ item.name }} <span>选择</span>
					          </div>
					      </div>
					      <div class="areabox" v-if="areaIsShow">
					      	<div class="address-detail">{{addressDetail}}</div>
						      面积:<input class="area" type="text" v-model="area">平方米
						      <button @click="setArea">确定</button>
						  </div>
					    </div>
					</transition>
				</div>
			</div>
			<div class="searchCt"></div>
			<div class="info_dd">
				<div class="myorder1">
					<div class="info_myorder1">
						<span class="scfc">上传房产证编码页</span>
					</div>
				</div>
					<div class="info_text sdffe">
						<input type="file" class="input_css" v-on:change="houseChange($event)" name="housepage">
					</div>
				</div>
				<div class="myorder1">
					<div class="info_myorder1">
					    <span class="scfc">上传房产证内容页</span>
				    </div>
					<div class="info_text">
						<input type="file" v-on:change="houseChange($event)" name="housepaget">
					</div>
				</div>
				<div class="myorder1">
					<div class="info_myorder1">
					   <span class="scfc">上传房产证附</span>
				    </div>
					<div class="info_text">
						<input type="file" v-on:change="houseChange($event)" name="housepagef">
					</div>
				</div>
			 <div class="login_tj">
				<div class="butjianbian2" @click="dpsubmit">
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

  name: 'declProcess2',

  data () {
    return {
        rolename: '',
        formData: new FormData(),
        isShow: false,
        employee: utils.getCookie('login_name'),
        mockAddress: '',
        addressDetail: '',
        area: '',
        areai: '',
        addressIsShow: true,
        areaIsShow: false,
        cname: '',
        bname: '',
        hname: '',
        isReadOnly: false,
        sbtn: '搜索',
        sbtnType: 1,
        id: ''
      }
    },
    created() {
    	this.id = this.$route.fullPath.split("?")[1].split("=")[1];
    	console.log(this.id)
    },
    methods: {
      check () {
    		if (!utils.getCookie('login_name')) {
	    		alert('请重新登录');
	    		window.location.href = '#/userlogin'
	    	}
    	},
    	houseChange (e) {
            this.deviceArray = [];
            let deviceFile = e.target.files;
            if (this.formData.has(e.target.name)) {
            	this.formData.set(e.target.name, deviceFile[0])
            } else {
            	this.formData.append(e.target.name, deviceFile[0])
            }
          },
        dpsubmit () {
        	var tmp = this.formData
	        tmp.append('address', this.addressDetail)
	        tmp.append('area', this.area)
	        tmp.append('employee', utils.getCookie('login_name'))
	        tmp.append('id', this.id)
	        if (this.rolename == '') {
    			 	 alert('请输入小区名称或路名');
    				return false;
    			}
	        if (!this.formData.has("housepage")) {
           	       alert("请上传房产证编码页");
           	       return false;
              }
           if (!this.formData.has("housepaget")) {
           	       alert("请上传房产证内容页");
           	       return false;
              }
           if (!this.formData.has("housepagef")) {
           	       alert("请上传房产附征");
           	       return false;
              }
	        api.subHouseProperty(
			     tmp
			).then(res => {
				console.log('asdfsdf1212');
				if (res.body.code == -1) {
				console.log(res.body)
				alert('提交失败')
				} else if (res.body.code == 0) {
					console.log(res.body)
					window.location.href = "#/declProcess3" + "?id=" + res.body.data.id
			    }
			})
    	},
      shouh (e) {
    		console.log(e);
    		api.subCinfo({
  	        	id: e.target.text
  	        }).then(res => {
				console.log(res.body)
				this.mockAddress = res.body;
			});
    	},
    	shous () {
    		if (this.sbtnType == 1) {
    			 if (this.rolename == '') {
    			 	 alert('请输入小区名称或路名');
    				return false;
    			   }
    			console.log(this.rolename);
	    		api.subCinfo({
	  	        	address: this.rolename
	  	        }).then(res => {
					console.log(res.body)
					this.mockAddress = res.body;
				});
				this.isShow = true;
				this.addressIsShow = true;
				this.areaIsShow = false;
    		} else {
    			this.sbtnType = 1;
    			this.rolename = '';
    			this.sbtn = '搜索';
    			this.isReadOnly = false;
    		}
    	},
    	closeMyself () {
            this.isShow = false;
            this.rolename = this.addressDetail
    		this.isReadOnly = true;
    		this.sbtn = '重新搜索'
    		this.sbtnType = 0
    	},
    	singleAddInfo(item) {
    		// item就是单个地址的信息
    		console.log('function singleAddInfo - item:', item)
    		if (item.type == 'building') {
    			this.bname = item.name;
    			api.selectBinfo({
	  	        	buildingId: item.id,
	  	        	communityId: item.communityId,
	  	        	defid: item.defid
	  	        }).then(res => {
					console.log(res.body)
					this.mockAddress = res.body;
				});
    		} else if (item.type == 'communityId') {
    			this.cname = item.name;
    			api.selectCinfo({
	  	        	id: item.id
	  	        }).then(res => {
					console.log(res.body)
					this.mockAddress = res.body;
				});
    		} else if (item.type == 'house') {
    			this.hname = item.name;
    			// 输入面积
    			this.addressIsShow = false;
    			this.areaIsShow = true;
    			console.log(1)
    			console.log(item)
    			this.addressDetail = this.cname + ' ' + this.bname + ' ' + this.hname;
    		} else {
    			console.log(0)
    			console.log(item)
    		}
    	},
    	setArea() {
    		console.log(this.area)
    		this.closeMyself()
    	}
    }
}
</script>

<style lang="scss" scoped>
.content {
  margin-top: rem(-55);
}
.infos {
 margin-top: rem(20);
 height: rem(70);
 margin-left: 10%;
}
.info_sc {
	width: rem(130);
    border: rem(1) red solid;
}
.scfc {
	display: block;
	padding-top: rem(30);
	width: rem(120);
	margin-left: rem(30);
	color: white;
}
.login_tj {
	padding-top: rem(30);
}
.village {
	width: rem(540);
}
.sdffe {
	display: block;
	margin-top: rem(-80);
}
.input_css {
	display: inline-block;
	margin-left: rem(250);
	width: rem(450);
	height: rem(80);
}
.searchCt {
	display: none;
	width: rem(600);
	height: rem(500);
	border: rem(1) solid #38b4fe;
	z-index: 999;
	margin-left: rem(75);
}
  #shouh {
  	display: none;
  }
  .area {
  	width: rem(440);
  	border:rem(1) solid #CCC;
  }
</style>
