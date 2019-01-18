<style lang='scss' src="../css/realEvals.scss"></style>
<template>
  <div class="big_div">
     <div style="margin-top:rem(20);">
         <label style="display:block;margin-left:40%;height:rem(80)">房产评估</label>
        </div>
      <div class="query_div">
          <div class="city_switching">
                <img src="./i/dingwei.gif" class="dingweiimg">
                <span class="citycss">上海</span>
                <span class="citycss">  城市切换</span>
            </div>
            <div class="quer_buton">
                <div>
                  <input type="text" placeholder="  请输入房屋的坐落 / 小区名称" class="input_css">
                  <button class="quer_anniu">查 询</button>
                    </div>
               </div>
         </div>
         <div class="evaluation_div">
              <div class="mimgcheng">
                  <img src="./i/xiaoqu3.gif" class="imgcss">
                  <span class="mingchegn">小区名称：</span>
                  <input type="text" name="" class="inputcss"><span style="color:red;"> *</span>
              </div>
              <div class="mimgcheng">
                 <img src="./i/mianji3.gif" class="imgcss">
                  <span class="mingchegn">房屋面积：</span>
                  <input type="text" name="" class="inputcss"><span style="color:red;"> *</span>
              </div>
              <div class="mimgcheng">
                 <img src="./i/zuoluo3.gif" class="imgcss">
                  <span class="mingchegn">房屋坐落：</span>
                  <select class="selectcss inputs selectt">
                      <option></option>
                  </select><select class="selectcss inputs selectt2">
                      <option></option>
                  </select>
                  <span style="color:red;"> *</span>
              </div>
              <div class="mimgcheng">
                  <img src="./i/yongtu3.gif" class="imgcss">
                  <span class="mingchegn">房屋用途：</span>
                  <select class="inputcss selectcss">
                      <option> </option>
                  </select>
                  <span style="color:red;"> *</span>
              </div>
              <div class="mimgcheng">
                  <img src="./i/zhaoxian3.gif" class="imgcss">
                  <span class="mingchegn">房屋朝向：</span>
                  <input type="text" name="" class="inputcss">
              </div>
              <div class="mimgcheng">
                 <img src="./i/louceng3.gif" class="imgcss">
                  <span class="mingchegn">所在楼层：</span>
                  <input type="text" name="" class="inputcss">
                  <i class="tishi">层</i>
              </div>
              <div class="mimgcheng">
                 <img src="./i/zongluoceng3.gif" class="imgcss">
                  <span class="mingchegn">总楼层：</span>
                  <input type="text" name="" class="inputcss">
                  <i class="tishi">层</i>
              </div>
              <div class="mimgcheng">
                <img src="./i/niandai3.gif" class="imgcss">
                  <span class="mingchegn">建房年代：</span>
                  <input type="text" name="" class="inputcss">
              </div>
              <div class="mimgcheng">
                  <img src="./i/xingzhi3.gif" class="imgcss">
                  <span class="mingchegn">房屋性质：</span>
                  <input type="text" name="" class="inputcss">
              </div>
              <div class="mimgcheng">
                  <img src="./i/fangling3.gif" class="imgcss">
                  <span class="mingchegn">房龄：</span>
                  <input type="text" name="" class="inputcss">
              </div>
              <div class="mimgcheng">
                 <img src="./i/zhenghao3.gif" class="imgcss">
                  <span class="mingchegn">房产证号：</span>
                  <input type="text" name="" class="inputcss">
              </div>
              <div class="mimgcheng">
                 <img src="./i/yingsu3.gif" class="imgcss">
                  <span class="mingchegn">特殊因素：</span>
                  <select class="inputcss selectcss">
                      <option> 请选择</option>
                  </select>
              </div>
              <div class="sumbies">
                   <span class="pingfan">提交评房</span>
              </div>
         </div>
    </div>
</template>

<script>
// import api from './api'
export default {

  name: 'realEval',

  data () {
    return {
    }
  }
}
</script>

<style lang="scss" scoped>
.selectcss {
   appearance:none;
   -moz-appearance:none;
   -webkit-appearance:none;
   background:url('./i/selectimg.png') no-repeat right center white;
   color:#e6e3e3;
   font-size:rem(18);
   }
</style>
