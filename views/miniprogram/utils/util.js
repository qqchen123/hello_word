// 公共请求方法 by 奚晓俊
const app = getApp()
const isJSON = str => {
  if (typeof str == 'string') {
    try {
      let obj = JSON.parse(str);
      if (typeof obj == 'object' && obj) {
        return true;
      } else {
        return false;
      }

    } catch (e) {
      //console.log('error：' + str + '!!!' + e);
      return false;
    }
  }
}

//网络请求错误处理 by 奚晓俊
const curlErr = (curlErr) => {
// console.log('succ的错误')
  let data = {}
  if (isJSON(curlErr.data)) {
    data = JSON.parse(curlErr.data)
  }else{
    data = curlErr.data
  }

  //会话过期 重新登录
  if (data.sessionid === false) {
    // console.log(data)
    console.log('会话过期 重新登录', data)
    app.globalData.bind = false
    app.globalData.powers = []
    app.globalData.session = []
    wx.showModal({
      title: '提示',
      content: data.msg ? data.msg : '请求错误',
      showCancel: false,
    })
    // wx.navigateTo({
    //   url: '/pages/jdunion/homepage',
    //   complete: function () {
    //     wx.showToast({
    //       title: data.msg,
    //       icon: 'none',
    //       duration: 1000,
    //     })
    //   }
    // })
    app.onLaunch()
    return false
  }
  //返回编码状态错误
  if (curlErr.statusCode !== 200) {
    wx.hideToast()
    if (curlErr.statusCode == 403) {
      wx.switchTab({ 
        url: '/pages/jdunion/homepage',
        complete: switchTabR => {
          //已登录
          if(app.globalData.bind){
            wx.showModal({
              title: '提示',
              content: data.msg ? data.msg : '请求错误',
              showCancel: false,
            })
          //未登录
          }else{
            wx.showModal({
              title: '提示',
              content: data.msg ? data.msg : '请求错误',
              showCancel: true,
              confirmText: '登录',
              success: showModalR => {
                console.log(showModalR)
                if (showModalR.confirm){
                  wx.navigateTo({
                    url: '/pages/jdunion/login/login',
                  })
                }
                //wx.redirectTo({ url: '/'+getCurrentPages()[getCurrentPages().length - 1].route})
                // console.log(curlErr.statusCode)
              }
            })
          }

        }
      })
    }else{
      wx.showToast({
        title: data.msg,
        icon: 'none',
        duration: 1000,
      })
    }
    return false
    //返回值错误 显示提示
  } else if (data.ret == false) {
    wx.showToast({
      title: data.msg,
      icon: 'none',
      duration: 1000
    })
    return false
  }
  return true
}

const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}

//测试模拟延迟 参数毫秒 by 奚晓俊
const sleep = delay => {
  let start = (new Date()).getTime()
  while ((new Date()).getTime() - start < delay) {
    continue
  }
}

//网络请求 参数同wx.requert by 奚晓俊
  //ifShowToast bool 是否显示等待消息(控制延迟重复提交) 默认显示 
  //ifCheckSession bool 是否检查wxSession 默认检查
  //showToastTitle string 显示等待消息文字
const request = o => {
  if(o.ifShowToast!==false)
    wx.showToast({
      title: o.showToastTitle ? o.showToastTitle:'加载中...',
      icon: 'loading',
      mask: true,
      duration: 10000
    })

  o.url = app.globalData.baseUrl + o.url
  o.header = Object.assign({'SESSIONID': app.globalData.sessionId},o.header)
  let succ = o.success ? o.success : function (){}
  let fail = o.fail ? o.fail:function(){}
  let complete = o.complete ? o.complete:function(){}

  o.success = res => {
    if (o.ifShowToast !== false) wx.hideToast()
    if(curlErr(res)) succ(res)
  }
  //o.fail = res => { fail(res) }
  o.complete = res =>{ 
    complete(res)
  }
  o.fail = res => {
    if (o.ifShowToast !== false) wx.hideToast()
    wx.showModal({
      title: '提示',
      content: '请求失败',
      showCancel: false,
      success: function (res) { }
    })
    fail(res)
  }

  if(o.ifCheckSession!==false){
    wx.checkSession({
      success() {
        wx.request(o)
      },
      fail() {
        app.globalData.bind = false
        app.globalData.powers = []
        app.globalData.session = []
        app.onLaunch({},wx.request,o) // 重新登录
      },
    })
  }else{
    wx.request(o)
  }
}

//上传单文件 参数同wx.uploadFile（增加 ifShowToast bool值 是否显示等待弹窗）by 奚晓俊
  //ifShowToast bool 是否显示等待消息(控制延迟重复提交) 默认显示 
  //ifCheckSession bool 是否检查wxSession 默认检查
const uploadFile = o => {
  //启动上传等待中...  
  if (o.ifShowToast !== false)
    wx.showToast({
      title: '正在上传...',
      icon: 'loading',
      mask: true,
      duration: 10000
    })
  o.url = app.globalData.baseUrl + o.url
  o.header = Object.assign({ 'SESSIONID': app.globalData.sessionId, 'content-type': 'multipart/form-data' } , o.header)
  let succ = o.success ? o.success : function () { }
  let fail = o.fail ? o.fail : function () { }
  let complete = o.complete ? o.complete : function () { }

  o.success = res => {
    if (curlErr(res)) succ(res)
    //隐藏等待中
    if (o.ifShowToast !== false) wx.hideToast()
    
  }
  o.fail = res => {
    if (o.ifShowToast !== false) wx.hideToast()
    wx.showModal({
      title: '提示',
      content: '上传图片失败',
      showCancel: false,
      success: function (res) { }
    })
    fail(res)
  }
  o.complete = res => { complete(res) }
  if(o.ifCheckSession!==false){
    wx.checkSession({
      success() {
        wx.uploadFile(o)
      },
      fail() {
        app.globalData.bind = false
        app.globalData.powers = []
        app.globalData.session = []
        app.onLaunch({}, wx.uploadFile,o) // 重新登录 
      }
    })
  }else{
    wx.uploadFile(o)
  }
}

// 上传多文件 by 奚晓俊
  // 参数同wx.uploadFile（增加 ifShowToast bool值 是否显示等待弹窗）
  // 返回值增加参数 successIndex 成功上传的数字下标
  //ifShowToast bool 是否显示等待消息(控制延迟重复提交) 默认显示 
  //ifCheckSession bool 是否检查wxSession 默认检查
const forUploadFile = o => {
  //启动上传等待中...  
  if (o.ifShowToast !== false)
    wx.showToast({
      title: '正在上传...',
      icon: 'loading',
      mask: true,
      duration: 10000
    })

  if(o.ifCheckSession!==false){
    wx.checkSession({
      success() {
        forUploadFile2(o)
      },
      fail() {
        app.globalData.bind = false
        app.globalData.powers = []
        app.globalData.session = []
        app.onLaunch({}, forUploadFile2, o) // 重新登录 
      }
    })
  }else{
    forUploadFile2(o)
  }
}

let forUploadFile2 = o =>{
  let uploadImgCount = 0
  let url = o.url
  let name = o.name
  let succ = o.success ? o.success : function () { }
  let fail = o.fail ? o.fail : function () { }
  let complete = o.complete ? o.complete : function () { }
  let filePath = o.filePath
  let uploadRes = []
  let ifShowToast = o.ifShowToast
  o.ifShowToast = false
  o.ifCheckSession = false

  for (var i = 0, h = filePath.length; i < h; i++) {
    //sleep(5000)
    o.url = url
    o.filePath = filePath[i]
    o.name = name + '[' + i + ']'
    o.success = res => {
      //curlErr(res)
      res.successIndex = uploadImgCount
      uploadImgCount++
      // uploadRes[i] 

      //如果是最后一张,则隐藏等待中
      if (ifShowToast!==false && uploadImgCount == filePath.length) wx.hideToast()
      succ(res)
    }
    uploadFile(o)
  }
}

module.exports = {
  formatTime: formatTime,
  request: request,
  uploadFile: uploadFile,
  forUploadFile:forUploadFile
}