var app = getApp()
const util = require('../../utils/util.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    idNumber0: '',
    idNumber1: '',
    idNumber: {},
    allImgs: app.globalData.baseImg,
  },
  bindPickerqingk: function (e) {
    this.setData({
      index: e.detail.value
    })
  },
  chooseimage: function (e) {
    var _this = this;
    wx.chooseImage({
      count: 1, // 默认9  
      // 可以指定是原图还是压缩图，默认二者都有  
      sizeType: ['original', 'compressed'],
      // 可以指定来源是相册还是相机，默认二者都有
      sourceType: ['album', 'camera'],
      // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片   
      success: function (chooseRes) {
        // _this.setData({
        //   [e.currentTarget.id]: chooseRes.tempFilePaths[0]
        // })
        util.uploadFile({
          ifShowToast: true,
          url: 'preOrder/baodanT',
          name: "baodan["+e.currentTarget.id+"]",
          filePath: chooseRes.tempFilePaths[0],
          success: uploadRes => {
            let d = JSON.parse(uploadRes.data)
            if(d.ret){
              _this.setData({
                [e.currentTarget.id]: chooseRes.tempFilePaths[0]
              })
              // console.log(_this)
              _this.data.idNumber[e.currentTarget.id] = d.data
              // if (!app.globalData.baodanT) app.globalData.baodanT = {idNumber0:false,idNumber1:false}
              // console.log('-----------------------')
              // console.log(d.data)
              // app.globalData.baodanT[e.currentTarget.id] = d.data
              // console.log(app.globalData.baodanT)

              // console.log('-----------------------')

            }else{

            }
            // console.log(_this.data)
          }
        })
      }
    })
  },
  formSubmit(e) {
    if (app.globalData.checkFormField){
      if (this.data.idNumber0 == '' || this.data.idNumber1 == '' || !this.data.idNumber.idNumber0 || !this.data.idNumber.idNumber1){
        wx.showToast({
          title: '请上传身份证',
          icon: 'none',
          duration: 1000
        })
        return false
      }
    }
    app.globalData.baodanT = this.data.idNumber
    console.log(app.globalData.baodanT)
    
    // if (app.globalData.housePriceId) {
    //   app.globalData.baodan = Object.assign(app.globalData.baodan, app.globalData.baodanT, {housePriceId: app.globalData.housePriceId})
    //   //console.log(app.globalData.baodan)
    //   util.request({
    //     url: 'PreOrder/baodan',
    //     method: 'POST',
    //     header: {
    //       'content-type': 'application/x-www-form-urlencoded',
    //     },
    //     data: app.globalData.baodan,
    //     success: res => {
    //       if (res.data.ret) {
    //         console.log(res.data.ret)
    //         delete app.globalData.baodanT
    //         delete app.globalData.baodan
    //         delete app.globalData.housePriceId
    //         //console.log(app.globalData)
    //         wx.reLaunch({
    //           url: 'insAudits',
    //         })
    //       }
    //     }
    //   })
    // }else{
      wx.navigateTo({
        url: '/pages/jdunion/housinformation',
      })
    // }
    //console.log(app.globalData.baodanT)
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
  },

})