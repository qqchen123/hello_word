const util = require('../../../utils/util.js')
const app = getApp()
Page({
  data: {
    allImgs: app.globalData.baseImg,
  },
  nameInput: function (e) {
    this.setData({
      name: e.detail.value
    })
  },
  passwordInput: function (e) {
    this.setData({
      password: e.detail.value
    })
  },
  formSubmit: function (e) {
    // console.log(app.globalData)
    util.request({
      url: 'Auth/bind',
      method: 'POST',
      header: {
        'content-type': 'application/x-www-form-urlencoded',
      },
      data: {
        name: this.data.name,
        password: this.data.password,
      },
      success: res => {
        // console.log(res)
        if (res.data.ret) {
          app.globalData.bind = res.data.bind
          app.globalData.powers = res.data.powers
          app.globalData.session = res.data.session
          // console.log(app.globalData)
          wx.showToast({
            title: res.data.msg,
            icon: 'success',
            duration: 1000
          })
          wx.switchTab({
            url: '../homepage'
          })
        }
      }
    })
  },
  formReset: function () {
    console.log('form发生了reset事件')
  }
})