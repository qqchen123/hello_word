var app = getApp()
const util = require('../../utils/util.js')
Page({
  data: {
    gui_hua_yong_tu:'请选择',
    gui_hua_yong_tuArray: ['请选择', '住宅', '别墅'],
    toward: '请选择',
    towardArray: ['请选择', '东', '西', '南', '北', '东南', '东西', '西南', '东北', '南北', '西北',],
    finish_date: '请选择',
    // ghIndex: 0,
    // jgIndex: 0,
    // towardIndex: 0,
    diya_mon_show: false,
    pics: [],
    hidden: true,
    nocancel: true,
    //date: '请选择',
    allImgs: app.globalData.baseImg,
    // array: [],
    nowdate: '',
    // finish_date: '请选择'
  },
  // bindPickerChanges(e) {
  //   this.setData({
  //     index: e.detail.value
  //   })
  // },
  // bindDateChange(e) {
  //   this.setData({
  //     date: e.detail.value
  //   })
  // },
  cancel: function() {
    this.setData({
      hidden: true
    });

    wx.switchTab({
      url: "/pages/jdunion/homepage"
    })
  },
  confirm: function() {
    this.setData({
      hidden: true
    });
    wx.switchTab({
      url: "/pages/jdunion/homepage"
    })
  },
  //点击房屋朝向组件确定事件  
  towardChange: function (e) {
    this.setData({
      toward: this.data.towardArray[e.detail.value]
    })
  },
  //规划用途
  gui_hua_yong_tuChange: function(e) {
    console.log(this.data.array)
    this.setData({
      gui_hua_yong_tu: this.data.gui_hua_yong_tuArray[e.detail.value]
    })
  },
  //竣工日期
  finish_dateChange: function(e) {
    console.log(this.data.array)
    this.setData({
      finish_date: this.data.array[e.detail.value]
    })
  },
  //长按输出
  longPress: function(e) {
    var _that = this
    wx.showModal({
      title: '提示',
      content: '确定删除该图片',
      success: function(r) {
        if (r.cancel) return false
        let index = e.currentTarget.dataset.index
        app.globalData.baodanT.houseT.splice(index, 1)
        _that.data.pics.splice(index, 1)
        _that.setData({
          pics: _that.data.pics
        })
      }
    })
  },
  chooseimage: function() {
    var _this = this;
    wx.chooseImage({
      count: 9, // 默认9  
      // 可以指定是原图还是压缩图，默认二者都有  
      sizeType: ['original', 'compressed'],
      // 可以指定来源是相册还是相机，默认二者都有
      sourceType: ['album', 'camera'],
      // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片   
      success: function(chooseRes) {
        if (!app.globalData.baodanT) app.globalData.baodanT = {}
        if (!app.globalData.baodanT.houseT) app.globalData.baodanT.houseT = []
        util.forUploadFile({
          ifShowToast: true,
          url: 'preOrder/baodanT',
          name: 'baodan[house]',
          filePath: chooseRes.tempFilePaths,
          success: uploadRes => {
            let d = JSON.parse(uploadRes.data)
            if (d.ret) {
              _this.data.pics.push(chooseRes.tempFilePaths[uploadRes.successIndex])
              _this.setData({
                pics: _this.data.pics
              })
              app.globalData.baodanT.houseT.push(d.data)
            }
          }
        })
      }
    })
  },
  previewImage: function(e) {
    var current = e.target.dataset.src
    wx.previewImage({
      current: current,
      urls: this.data.pics
    })
  },
  formSubmit(e) {
    if (app.globalData.checkFormField) {
      if (!app.globalData.baodanT || !app.globalData.baodanT['idNumber0'] || !app.globalData.baodanT['idNumber1']) {
        wx.showToast({
          title: '请上传身份证',
          icon: 'none',
          duration: 1000
        })
        return false
      }
      if (!app.globalData.baodanT || !app.globalData.baodanT.houseT || !app.globalData.baodanT.houseT.length) {
        wx.showToast({
          title: '请上传房产证',
          icon: 'none',
          duration: 1000
        })
        return false
      }
      if (e.detail.value.floor > e.detail.value.totalfloor){
        wx.showToast({
          title: '“所在楼层”不能大于“楼层总数”',
          icon: 'none',
          duration: 1000
        })
        return false
      }
    } 
    if (app.globalData.baodanT && app.globalData.baodanT.houseT) app.globalData.baodanT.houseT = JSON.stringify(app.globalData.baodanT.houseT)
    app.globalData.baodan = Object.assign(app.globalData.baodan, app.globalData.baodanT, e.detail.value, {
      full_house_name: this.data.full_house_name,
      houseName: this.data.houseName,
      houseId: this.data.houseId,

      old_house_price_id: this.data.old_house_price_id
    })
    util.request({
      ifShowToast: true,
      url: 'PreOrder/baodan',
      method: 'POST',
      header: {
        'content-type': 'application/x-www-form-urlencoded',
      },
      data: app.globalData.baodan,
      success: res => {
        // console.log(res)
        if (res.data.ret) {
          delete app.globalData.baodanT
          delete app.globalData.baodan
          delete app.globalData.housePriceId
          delete app.globalData.houseName
          delete app.globalData.houseId
          delete app.globalData.full_house_name

          let str = ''
          if(res.data.data.hasOwnProperty('data')) str =
            'diYaDanJia=' + res.data.data.data.diYaDanJia + '&' +
            'diYaZongJia=' + res.data.data.data.diYaZongJia + '&' +
            'fangDaiZheKou=' + res.data.data.data.fangDaiZheKou;

          wx.reLaunch({
            url: 'insAudits?' + str
          })
        }
      }
    })
  },
  onLoad: function(options) {
    let myDate = new Date();
    let fullyear = myDate.getFullYear();
    let diff = fullyear - 1900 + 1;
    var year_arr = ['请选择'];
    for (let i = 1; i <= diff; i++) {
      let ky = 1900 + i
      year_arr[i] = fullyear - i + 1;
    }
    this.setData({
      array: year_arr
    })
    if (app.globalData.housePriceId) {
      util.request({
        url: 'PreOrder/get_one_fgginfo_by_oid',
        method: 'POST',
        header: {
          'content-type': 'application/x-www-form-urlencoded',
        },
        data: {
          oid: app.globalData.housePriceId
        },
        success: res => {
          // console.log(res)
          if (res.data.ret) {
            // delete app.globalData.housePriceId,
            var real_path = []
            var img_path = JSON.parse(res.data.data.img_path)
            if (img_path.length) {
              // console.log(img_path)
              Object.keys(img_path).forEach(function(key) {
                real_path[key] = app.globalData.uploadImg + img_path[key]
              });
            }
            if (!app.globalData.baodanT) app.globalData.baodanT = {}
            app.globalData.baodanT.houseT = img_path

            // if (res.data.data.gui_hua_yong_tu=='')
            // if (res.data.data.gui_hua_yong_tu)
            //   this.setData({
            //     objectIndex: this.data.objectArray.indexOf(res.data.data.gui_hua_yong_tu)
            //   })

            this.setData({
              full_house_name: res.data.data.full_house_name,
              houseName: res.data.data.houseName,
              houseId: res.data.data.houseId,

              old_house_price_id: res.data.data.id,

              toward: res.data.data.toward ? res.data.data.toward:'请选择',
              gui_hua_yong_tu: res.data.data.gui_hua_yong_tu ? res.data.data.gui_hua_yong_tu : '请选择',
              finish_date: res.data.data.finish_date ? res.data.data.finish_date : '请选择',

              floor: res.data.data.floor,
              totalfloor: res.data.data.totalfloor,
              house_area: res.data.data.house_area,
              
              pics: real_path,
            })
          }
        }
      })
    }
  },
  onShow: function(options) {
    if (app.globalData.houseName) {
      this.setData({
        full_house_name: app.globalData.full_house_name,
        houseName: app.globalData.houseName,
        houseId: app.globalData.houseId
      })
    }
  },
  onUnload: function() {
    delete app.globalData.houseName
    delete app.globalData.houseId
    delete app.globalData.full_house_name
    if (app.globalData.baodanT) delete app.globalData.baodanT.houseT
  }
})