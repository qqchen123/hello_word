// pages/jdunion/assresults.js
const app = getApp()
const util = require('../../utils/util.js')
Page({
    /**
     * 页面的初始数据
     */
    data: {
        selects: [],
        allImgs: app.globalData.baseImg,
        // path: '/pages/jdunion/systemassess'
    },
    // path: function(e) {
    //     this.setData({
    //         path: e.detail.value
    //     })
    // },
    houseKey: function(e) {
        this.setData({
            houseKey: e.detail.value
        })
    },
    searchtap:function(){
        // wx.showLoading({
        //     title: '加载中...',
        // })
        // return;
        //小区搜索
        util.request({
            url: 'PreOrder/get_houses',
            method: 'POST',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: {
                houseKey: this.data.houseKey
            },
            success:res=>{
                // console.log(res.data);
                // console.log(res.data.code);
                // return;
                // wx.hideLoading();
                if(res.data.ret){
                    // if(res.data.data){
                    //     wx.showToast({
                    //         title: res.data.data,
                    //         icon: 'success',
                    //         duration: 1000
                    //     })
                    // }else{
                    //     wx.showToast({
                    //         title: '没有匹配数据',
                    //         icon: 'success',
                    //         duration: 1000
                    //     })
                    // }
                    
                // }else{
                    this.setData({
                        selects: res.data.data
                    })
                }
            }
        })
    },
    back:function(e){
      // console.log(e)
      app.globalData.full_house_name = e.currentTarget.dataset.full_house_name
      app.globalData.houseId = e.currentTarget.dataset.houseid
      app.globalData.houseName = e.currentTarget.dataset.housename
      // console.log(app.globalData)
      wx.navigateBack({
        delta: 1,
      })
    },
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        //房估估登陆
        // util.request({
        //     url: 'PreOrder/funguguLogin',
        //     method: 'POST',
        //     header: {
        //         'content-type': 'application/x-www-form-urlencoded',
        //     },
        //     data: {},
        //     success: res => {
        //         console.log(res);
        //         return;
        //     }
        // })
        // this.data.back=options.back
        // if (options.back)
        //   this.setData({
        //     path: options.back
        //   })
        // console.log(this.data.path)
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {

    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function() {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {

    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {

    }
})