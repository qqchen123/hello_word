const app = getApp()
const util = require('../../utils/util.js')
Page({
  data: {
    timesindex: 0,
    timesArray: ['请选择','法人','股东','实控人','其他'],
    objectArray: ['请选择', '清单','转单'],
    objectIndex:0,
    qingkArray: ['请选择', '一抵', '二抵'],
    qingIndex:0,
    shenqingdataArray: ['请选择','六个月','十二个月','二十四个月','三十六个月','六十个月'],
    shenqingIndex: 0,
    houseArray: ['请选择','住宅','别墅'],
    houseIndex: 0,
    codeArray: ['请选择','A1', 'A2', 'B1','B2'],
    codeIndex: 0,
    cpnameArray: ['请选择','一号', '二号', '三号'],
    cpnameIndex: 0,
    diya_mon_show: false,
    shangja_mon_show:false,
    allImgs: app.globalData.baseImg,
    items: [
      { name: 0, value: '无', checked: 'true' },
      { name: 1, value: '房抵贷' },
      { name: 2, value: '银抵贷' },
      { name: 3, value: '好享贷' },
    ],
    formData: {},
    deg_code_show:false,
  },
   //  职业类型  
  bindDateChange: function (e) {
    this.setData({
      timesindex: e.detail.value
    })
  },
   //  借款类型 
  bindPickerChange: function (e) {
    this.setData({
      objectIndex: e.detail.value
    })
    if (e.detail.value == 2) {
      this.setData({
        shangja_mon_show: true
      })
    } else {
      this.setData({
        shangja_mon_show: false
      })
    }
  },
  //抵押类型
  bindPickerqingk: function (e) {
    this.setData({
      qingIndex: e.detail.value
    })
    if (e.detail.value == 2) {
      this.setData({
        diya_mon_show: true
      })
    } else {
      this.setData({
        diya_mon_show: false
      })
    }
  },
  //申请时间
  shengqPickerqingk: function (e) {
    this.setData({
      shenqingIndex: e.detail.value
    })
  },
  //机构代码
  codePickerqingk: function (e) {
    this.setData({
      codeIndex: e.detail.value
    })
  },
  //产品名称
  cpnamePickerqingk: function (e) {
    this.setData({
      cpnameIndex: e.detail.value
    })
  },
  //房屋类型
  housePickerqingk: function (e) {
    this.setData({
      houseIndex: e.detail.value
    })
  },
  //单选按钮操作
  radioChange: function (obj) {
    if (obj.detail.value == 0){
      this.setData({
        deg_code_show:false
      })
    } else if (obj.detail.value == 1 || obj.detail.value == 2 || obj.detail.value == 3) {
        this.setData({
          deg_code_show: true
        })
      // if (this.data.cpnameIndex == 0 && this.data.codeIndex == 0) {
      //   wx.showToast({
      //     title: '选择机构、产品',
      //     icon: 'none',
      //     duration: 1000
      //   })
      //   return false
      // }
    }
  },

  formSubmit(e) {
    if (app.globalData.checkFormField){
      //申贷人姓名的填写
      if (e.detail.value.user_name.length == 0) {
          wx.showToast({
            title: '输入申贷人姓名',
            icon: 'none',
            duration: 1000
          })
        return false
      }
      //职业类型
      if (this.data.timesindex == 0) {
        wx.showToast({
          title: '选择职业类型',
          icon: 'none',
          duration: 1000
        })
        return false
      }
      //申贷金额
      var number_m = /^(0|[1-9]\d*)$/
      if (e.detail.value.get_money.length < 1) {
        wx.showToast({
          title: '请输入申贷金额',
          icon: 'none',
          duration: 1000
        })
        return false
      }
      //借款类型
      if (this.data.objectIndex == 0) {
        wx.showToast({
          title: '选择借款类型',
          icon: 'none',
          duration: 1000
        })
        return false
      //上家余额
      } else if (this.data.objectIndex == 2 && !e.detail.value.pre_yu_e) {
        wx.showToast({
          title: '请输入上家余额',
          icon: 'none',
          duration: 1000
        })
        return false
      }
      //抵押类型
      if (this.data.qingIndex == 0) {
        wx.showToast({
          title: '选择抵押类型',
          icon: 'none',
          duration: 1000
        })
        return false
      //一抵金额
      } else if (this.data.qingIndex == 2 && !e.detail.value.yidi_yue) {
        wx.showToast({
          title: '请输入一抵余额',
          icon: 'none',
          duration: 1000
        })
        return false
      }

      //申请期限
      if (this.data.shenqingIndex == 0) {
        wx.showToast({
          title: '选择申请期限',
          icon: 'none',
          duration: 1000
        })
        return false
      }
      //房产类型
      if (this.data.houseIndex == 0) {
        wx.showToast({
          title: '选择房产类型',
          icon: 'none',
          duration: 1000
        })
        return false
      }
      //产品属性
      if (e.detail.value.product_type != '0') {
        if (!e.detail.value.jg_code) {
          wx.showToast({
            title: '选择机构代码',
            icon: 'none',
            duration: 1000
          })
          return false
        }
        if (!e.detail.value.product_name) {
          wx.showToast({
            title: '选择产品名称',
            icon: 'none',
            duration: 1000
          })
          return false
        }
      }
    }

    app.globalData.baodan = e.detail.value
    wx.navigateTo({ url:'/pages/jdunion/creadID'})
  },
  onUnload:function(){
    delete app.globalData.housePriceId
    delete app.globalData.baodanT
    delete app.globalData.baodan
  }
})
