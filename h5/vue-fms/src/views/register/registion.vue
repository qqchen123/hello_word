<style lang="scss" src="../css/input.scss" scoped></style>
<style lang="scss" src="../css/lable.scss" scoped></style>
<style lang="scss" src="../css/buttonys.scss" scoped></style>
<template>
	<div>
		<div v-if="willShow" class="background page-content">
		  <div class="navigation flex-space flex-c-m" >
		    <div class="nav-back flex-c-m">
		    	<img src="../../assets/nav_back.png">
		    </div>
		    <div class="nav-right"></div>
		  </div>
		<div class="info">
			<div>
            <label class="ztts">客户姓名</label>
				    <input type="text" placeholder="请输入真实姓名" v-model="name" class="info-input">
			</div>
			<div class="caredscc">
				<label class="ztts">身份证号</label>
				<input type="text" placeholder="请输入有效身份证号" v-model="id" class="info-input">
			</div>
			<div class="caredscc">
				<label class="ztts">手机号码</label>
				<input type="tel" placeholder="请输入手机号" v-model="phone" class="info-input">
			</div>
			<div class="vaild caredscc">
				<div class="dhjf">
					<label class="ztts">验证码</label>
				   <input type="text" placeholder="请输入验证码" v-model="yzmsr" class="vaild-input">
				</div>
				<div class="vaild-btn">
            <span class="flex-c-m" v-if="sendMsgDisabled">{{time+'秒后获取'}}</span>
             <span class="flex-c-m" v-if="!sendMsgDisabled" @click="yzmphone">获取验证码</span>
				</div>
			</div>
		</div>
		<div class="agree flex-c-m">
		   <input class="select-icon" id="cb1" type="checkbox" v-model="agree">
		  <div>
		   我已阅读并同意
		  </div>
		  <span @click="baxk">
		  《个人信息查询及使用授权书》
		  </span>
          <div>
            等
          </div>
		</div>
		<div class="btn flex-c">
			<div class="btn-item flex-c-m" @click="cAccount">提 交</div>
		</div>
	</div>
  <div v-if="!willShow">
      <div class="regs" v-if="shounone">
        <span v-if="shounone" @click="shown" class="liebiao">协议列表</span>
        <span v-if="shounone" @click="shown" class="xinxin">1.《个人信息查询及使用授权书》</span>
        <a v-if="shounone" class="fanghui fanhui2 fanghui2" @click="baxk"><span class="fanghuiziti">返回</span></a>
      </div>
   </div>
		<div class="xieyi_div" v-if="!shounone">
			<div class="xx_div">
			     <span class="xinxi">个人信息查询及使用授权书</span>
			 </div>
		  <div class="ms">
			授权人声明:本人已知悉并理解下述条款，本授权书一
			经确认即视为本人签署并对本
			人产生法律效力。<br><br>
		    </div>
		    <div class="ms">
			     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;尊敬的上海源都金融信息服务有限公司:<br>
            鉴于 本人  [ ](身份证号码:<span class="cread"> </span>)
              拟 通 过贵 司运营的【 源 都 金 服】
            平台(“源都金服 ”)向与贵司具有合作关系的银行 金融机构
            或消费金融公司、小额贷款公司等银行金融机构(以下合称
            “金融机构”)及其关联公司申请、使用该等金融机构的授信额
            度并获得相应贷款资金，为贵司对本人进行信用分析和评价
            之目的，就本人的有关个人信息查询及使用等相关事宜，本
            人特向贵司作出以下不可撤销的授权:
             <br>
           一、在本人通过源都金服 向金融机构及其关联公司提出授信额
           度及贷款申请或使用该等授信额度、获得贷款过程中及本人与该
           等金融机构信贷关系存续期间内，授权贵司使用与贵司具有合作
           关系并经本入授权的金融机构及其关联公司 向中国人 民银行金融
           信息基 础数 据库查询、保存或打印的本人的个人征信信息。
            <br>
           二、在本人通过源都金服 向金融机构及其关联公司提出授信额度
           及贷款申请或使用该等授信额度、获得贷款过程中及本人与该等金
           融机构信贷关系存续期间内，授权贵司及合作方向有关机构或单位
           (包括但不限于经国务院或其他政府有权部门]批准合法设立的其
           他征信机构;公安、住房公积金、社会保险、税务、民政等政府主
           管部门;物流、通信运营商、电子商务平台、互联网平台等第三方
           机构)查询、保存、打印和使用本人的征信信息、财产信息、联系
           方式、关系人、 资信状况、就业情况、收入情况、婚姻情况、学历
           信息、 地址信息、位置数据、通讯行为、通讯信息、互联网使用信
           息、互联网使用行为等信息。本人保证，本人不会因上述机构或单
           位向贵司提供有关信息或确认事项而向贵司、贵司合作方、上述机
           构或单 位以任何形式提出权利主张。上述本人的个人征信信息或贵
           司向有关机构或单位查询、保存或打印的本人其他个人信息，仅可用
           于以下用途:
            (一)审核本人的授信额度或贷款申请;
            (二)对已向本人发放的贷款进行贷后管理。
             <br>
           三、本人同意并授权贵司及合作方向中国人民银行金融信用信息基础数据库以及经国务院或其
           他政府有关部门批准合法设立的征信机构报送本人的信用信息，包括但不限于信贷信息及对本
           人信用状况构成负面影响的信息。
           本授权书自本人通过源都金服 首次进行授信或贷款申请时勾选相关“确认”按钮即生效,有效期
           至本人和与贵司具有合作关系的金融机构及其关联公司的所有信贷业务合作终止并无任何法律
           纠纷，且注销或终止源都金服 账户之日止。<br><br>
          </div>
          <div class="ms">
        	授权人:<span class="names"></span>授权日期:<span id="times">{{this.currentdate}}</span>
          </div>
          <a class="fanghui fh2" @click="shown"><span class="fanghuiziti">返回</span></a>
	   </div>
	</div>
</template>

<script>
import api from './api'
import url from '../overallS/qjbl'
import times from '../overallS/time'
export default {

  name: 'registion',

  data () {
    return {
      name: '',
      id: '',
      phone: '',
      verify: '',
      yzmsr: '',
      time: 60,
      sendMsgDisabled: false,
      agree: false,
      cansub: 0,
      willShow: true,
      shounone: true,
      currentdate: ''
    }
  },
   created: function () {
    setInterval(this.shijian, 1000);
   },
   methods: {
    shijian () {
        this.currentdate = times.shijians();
       },
    baxk () {
         this.willShow = !this.willShow;
     },
     shown () {
        this.shounone = !this.shounone;
     },
    cAccount () {
  	if (!/^[\u4E00-\u9FA5A-Za-z]+$/.test(this.name)) {
        alert("姓名不正确");
        return false;
    }
    if (!/^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(this.id)) {
        alert("身份证号有误");
        return false;
    }
    if (!/^[1][3,4,5,7,8][0-9]{9}$/.test(this.phone)) {
        alert("手机号有误");
        return false;
    }
   if (!this.yzmsr) {
   	   alert('验证码有误！');
       return false;
      } else {
      	this.cansub = 1;
      }
    if (!this.cansub) {
    	return false;
    }
  if (!document.getElementById("cb1").checked) {
      alert('协议未勾选！');
      return false;
   }
  api.cAccount({
  	    name: this.name, // this.name
        idnumber: this.id, // this.idCard
        mobile: this.phone, // this.phone
       identifying: this.yzmsr
  	  }).then(res => {
  	    if (res.body.code != 0) {
  	      // 提示组件
  	     console.log(res.body)
  	     if (res.body.code == 1) {
  	     	this.$router.push({
  	      	 path: "/creditEval?fuserid=" + res.body.msg
  	       })
  	     } else {
          alert(res.body.msg)
  	     }
  	    } else if (res.body.code == 0) {
  	      window.location.href = url.authurl + 'carrier' + url.authkey + url.base + 'yysid?fuserid=' + res.body.msg + '&carrier_idcard=' + this.id + '&carrier_name=' + this.name
  	    }
  	  })
  	},
  yzmphone () {
  	   if (!this.phone) {
  	   	console.log(2);
  	   	 return false;
  	   } else {
  	   	let myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
        if (!myreg.test(this.phone)) {
            return false;
        }
  	   }
	  	api.yzmphone(this.phone)
	     let me = this;
        me.sendMsgDisabled = true;
       let interval = window.setInterval(function () {
       if ((me.time--) <= 0) {
       me.time = 60;
       me.sendMsgDisabled = false;
       window.clearInterval(interval);
          }
        }, 1000);
  	 }
   }
}
</script>

<style lang="scss" scoped>
.fanhui2 {
  display: block;
  margin-top: rem(50);
}
.fanghuiziti {
  color: white;
  font-size: rem(35);
}
.shouqus {
  margin-left: 45%;
  font-size: rem(45);
}
.liebiao {
     display: block;
     padding-top: rem(20);
     font-size: rem(36);
     margin-left: 40%;
     font-weight: bold;
}
.xinxin {
    display: block;
    height: rem(150);
    color: #3913f0;
    margin-left: rem(60);
    margin-top: rem(25);
}
.caredscc {
    padding-top:rem(30);
}
.cread {
	width: rem(100);
}
.names {
	display: inline-block;
	width: rem(60);
}
#times {
	padding-left: rem(5);
}
#yinc {
	display: none;
}
.dhjf {
	display: inline-block;
}
.regs {
   height: rem(1337);
   background: url(i/reg.jpg) no-repeat fixed top;
   background-size:100% 100%;
   -moz-background-size:100% 100%;
}
.background {
   height: 100%;
   background: url(i/reg.jpg) no-repeat fixed top;
   background-size:100% 100%;
   -moz-background-size:100% 100%;
}
.navigation {
	margin-top:rem(-120);
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
	margin-top: rem(180);
	width: 100%;
	.info-input{
		 input{
			padding: 0 rem(15);
			width: 100%;
		}
		.vaild{
			width: rem(50);
		}
	}
	.vaild{
		margin-top: rem(10);
		height: rem(70);
		line-height: rem(70);
		.vaild-input{
			margin-top: rem(20);
		    background-color: white;
		    height: rem(70);
		    width:rem(240);
		    line-height: rem(70);
		    border-radius: rem(15);
		    border: rem(1) solid #bdbdbd;
		    font-size:rem(30);
		    margin-left: rem(10);
			input{
				width: 100%;
			}
		}
		.vaild-btn{
			width: rem(160);
			border-radius: rem(15);
			background-color: #31c7f0;
			color: white;
			font-size: rem(20);
			margin-left: rem(30);
			display: inline-block;
		}
	}
}
.btn{
	margin: 0 rem(150);
	height: rem(100);
	color: white;
	text-align: center;
	.btn-item{
		background-color: #31c7f0;
		height: rem(70);
		width: rem(450);
		border-radius: rem(15);
		box-shadow: rem(2) rem(4) #c0bbd2;
		margin-top:rem(70);
    font-size: rem(35);
	}
}
.agree {
    line-height: rem(40);
    color: #666666;
    text-align: center;
    padding: rem(50) 0;
    margin-top: rem(40);
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
     -webkit-appearance: checkbox;
      &:checked {
        background-color: #666666;
        border: none;
      }
    }
  }
  .xieyi_div {
   height: rem(2370);
   background-size:100% 100%;
   -moz-background-size:100% 100%;
 }
 .xx_div {
 	width: 100%;
 	height: rem(100);
 }
 .xx_div .xinxi {
   	 display: block;
   	 margin-top: rem(110);
     margin-left: rem(180);
    font-size: rem(30);
  }
  .ms {
  	padding:0 rem(30);
  }
  .fanghui {
  	  display: block;
  	  width: rem(550);
  	  height:rem(70);
	    border-radius: rem(15);
	    background-color: #31c7f0;
	    font-size: rem(20);
	    margin-left: rem(30);
	    margin:0 auto;
	    text-align: center;
	    line-height:rem(60);
  }
  .fanghui2 {
    margin-top: rem(550);
  }
  .fh2 {
    margin-top: rem(120);
  }
</style>
