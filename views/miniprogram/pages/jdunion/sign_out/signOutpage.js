// pages/jdunion/sign_out/signOutpage.js
var app = getApp()
const util = require('../../../utils/util.js')

Page({
  data: {
    allImgs: app.globalData.baseImg,
  },
  
  out:function(){
    util.request({
      url:'Auth/unbind',
      success: res => {
        // console.log(res)
        if(res.data.ret){
          // app.globalData.sessionId = null,//登录session
          app.globalData.bind = false,
          app.globalData.powers = [],
          app.globalData.session =  [],
            console.log(app.globalData)
          wx.reLaunch({
            url: '/pages/jdunion/homepage'
            // url:'/pages/jdunion/login/login'
          })
          // app.globalData.sessionId = null
        }
      }
    })
  },
  onShow:function(){
    // console.log('bind',app.globalData)
    this.setData({
      username: app.globalData.session.fms_username ? app.globalData.session.fms_username:'',
      department: app.globalData.session.fms_department_name ? app.globalData.session.fms_department_name:'',
      bind: app.globalData.bind
    })
  },
  login:function(){
    wx.navigateTo({
      url: '/pages/jdunion/login/login',
    })
  }
  
})