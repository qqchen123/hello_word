//app.js by 奚晓俊
App({
  onLaunch: function (e,myFun,myO) {
    // 展示本地存储能力
    //var logs = wx.getStorageSync('logs') || []
    //logs.unshift(Date.now())
    //wx.setStorageSync('logs', logs)

    // 登录
    wx.login({
      success: res => {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        if (res.code) {
          //销毁sessionid
          this.globalData.sessionId = null
          wx.showToast({
            title: '加载中...',
            icon: 'loading',
            mask: true,
            duration: 10000
          })
          //发起网络请求
          wx.request({
            url: this.globalData.baseUrl + 'Auth/is_bind',
            data: { code: res.code },
            success: r => {
              //console.log(r);

              //保存sessionid
              this.globalData.sessionId = r.header.SESSIONID;
              //保存bind
              this.globalData.bind = r.data.bind;
              //保存powers
              this.globalData.powers = r.data.powers;
              //保存session
              this.globalData.session = r.data.session;
              // console.log(this.globalData.bind,this.globalData)
              // if (!r.data.bind) {
              //   //未绑定 跳转登录页
              //   wx.navigateTo({
              //     url: '/pages/jdunion/login/login'
              //   })
              // }
              // console.log(e,myFun,myO,typeof(myFun),typeof(myO))
              if(myFun!=undefined && typeof(myFun)=='function') myFun(myO)
              
            },
            complete: res => {
              wx.hideToast()
            }
          })
        } else {
          console.log('登录失败！' + res.errMsg)
        }
      }
    })

    // 获取用户信息
    wx.getSetting({
      success: res => {
        //console.log('获取用户信息')
        // console.log(1,res)
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          wx.getUserInfo({
            success: res => {
              // console.log(2,res)
              // 可以将 res 发送给后台解码出 unionId
              this.globalData.userInfo = res.userInfo

              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }
            }
          })
        }
        // console.log(3,this.globalData.userInfo)
      }
    })
  },
  globalData: {
    userInfo: null,
    sessionId: null,//登录session
    bind: false,//是否登录
    powers: [],//具备权限
    session: [],//session信息
    checkFormField: false,//验证表单元素

    //测试
    // baseUrl: 'http://localhost/fms/index.php/miniprogram/',//基础地址
    // base: 'http://localhost',//域名
    // uploadImg: 'http://localhost/fms/index.php/miniprogram/Auth/getImg?name=mini_upload_tmp/',//获取图片
    // baseImg: 'http://localhost/fms/index.php/miniprogram/Auth/getImg?name=mini/',//获取图片

    //线上
    //baseUrl: 'http://120.26.89.131:60523/fms/index.php/miniprogram/',//基础地址
    //base: 'http://120.26.89.131:60523/',//域名

    //正式https
    baseUrl: 'https://fms.yuandoujinfu.com/index.php/miniprogram/',//基础地址
    base: 'https://fms.yuandoujinfu.com',//域名
    uploadImg: 'https://fms.yuandoujinfu.com/index.php/miniprogram/Auth/getImg?name=mini_upload_tmp/',//获取图片
    baseImg: 'https://fms.yuandoujinfu.com/index.php/miniprogram/Auth/getImg?name=mini/',//获取图片
    
    
  }
})