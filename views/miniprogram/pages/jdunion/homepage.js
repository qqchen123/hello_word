var app = getApp()
const util = require('../../utils/util.js')
const powers = require('../../utils/powers.js')
var img = app.globalData.baseImg

Page({
  data: {
    autoplay: true,
    dotsBoll: true,
    interval: 2000,
    duration: 1000,
    current: 0,
    allImgs: app.globalData.baseImg,
    imageUrls: [
     img + 'julm3.jpg',
     img + 'julm2.jpg',
     img + 'julm1.jpg'
    ]
  },
  intervalChange: function (e) {
    //自动切换时间间隔
    this.setData({
      interval: e.detail.value
    })
  },
  durationChange: function (e) {
    //滑动动画时长
    duration: e.detail.value
  }, 
  changeIndicatorDots: function (e) {
    //是否显示小圆圈
    this.setData({
      dotsBoll: !this.data.dotsBoll
    })
  },
  swipclick: function (e) {
    //点击图片触发事件
    console.log(this.data.imageUrls[this.data.current]);
  },
  bindchange: function (e) {
    //轮播图发生改变
    this.setData({
      current: e.detail.current
    })
  },
  baodan:function(e){
    if(powers.checkRolePowers('baodan',false)) 
    wx.navigateTo({
      url: '/pages/jdunion/customsform',      
    })
  },
  pinggu:function(){
    if (powers.checkRolePowers('pinggu',false)) 
    wx.navigateTo({
      url: '/pages/jdunion/systemassess',
    })
  },

  // onLoad: function (options) {

  //   util.checkRolePowers(needPowers)
  // }
})
