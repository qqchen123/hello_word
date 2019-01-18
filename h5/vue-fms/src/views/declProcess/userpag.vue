<style lang="scss" src="../css/tite.scss" scoped></style>
<style lang="scss" src="../css/bk.scss" scoped></style>
<style lang="scss" src="../css/ygbg.scss" scoped></style>
<style lang="scss" src="../css/div.scss" scoped></style>
<style lang="scss" src="../css/daohangt.scss" scoped></style>
<template>
	<div>
		<div class="bgtp">
			<div class="tite">
				员工首页
			</div>
			<div class="userid">
				xxxxxx
			</div>
			<div class="info_tt">
			  <div class="content">
		         <div class="content-box">
		            <div class="box">
		               <span class="t1" @click="decform">报单</span>
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
			<div class="info_dd">
				<div class="myorder">
					<div class="info_myorder">我的订单</div>
					<div class="info_text">
						<div v-if="order" class="order" v-for="item in orderArray" :key="item.index" @click="singleAddInfo(item)">
						      	{{ item.name }} <span>详情</span>
				      	</div>
					</div>
				</div>
				<div class="myorder">
					<div class="info_myorder">评估结果</div>
					<div class="info_text"></div>
				</div>
				<div class="myorder">
					<div class="info_myorder">喜报</div>
					<div class="info_text"></div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import api from './api'
import utils from '../../utils'
export default {

  name: 'userpag',

  data () {
    return {
    	employee: utils.getCookie('login_name'),
    	orderArray: ''
      }
    },
    created: function () {
        setInterval(this.getOrderList, 1000);
   },
    mounted() {
       utils.getCookie('login_name')
    },
    methods: {
    	decform () {
            window.location.href = '#/declForm'
    	},
    	singleAddInfo(item) {
    		console.log(item)
    	},
    	getOrderList() {
    		api.getDeclaraList({
  	        	employee: this.employee
  	        }).then(res => {
				console.log(res.body)
				this.mockAddress = res.body;
			});
    	}
    }
}
</script>

<style lang="scss" scoped>
.info_tt {
   height: rem(190);
}
.content-box {
    margin-top: rem(-20);
}
.tl {
	color: white;
}
</style>
