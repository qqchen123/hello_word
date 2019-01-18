const app = getApp()
const util = require('../../utils/util.js')
// pages/jdunion/bookingOrder.js
Page({

    /**
     * 页面的初始数据
     */
    data: {
        orderlist: [],
        ybdorderlist:[],
        ypgorderlist:[],
        page: 1,
        ybdpage: 1,
        ypgpage: 1,
        allImgs: app.globalData.baseImg,
        hasMoreData: true,
        pageSize: '',
        ypgshow:false,
        ybdshow:false,  
        allshow:true,
    },
    oclick:function(e){
        app.globalData.housePriceId = e.currentTarget.id
    },
    ypg:function(){
        // console.log('ypg');
        this.setData({
            ypgshow: true,
            ybdshow: false,
            allshow: false,
        });
        var _this = this
        util.request({
            url: 'PreOrder/get_fgg_log',
            method: 'get',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: {
                page: _this.data.ypgpage,
                status:0,
                // openid:
            },
            success: res => {
                var ypgorderlist = _this.data.ypgorderlist
                if (!res.data) {
                    wx.showToast({
                        title: '没有匹配数据',
                        icon: 'success',
                        duration: 1000
                    })
                } else {
                    this.setData({
                        ypgorderlist: res.data.data,
                        ypgorderlist: ypgorderlist.concat(res.data.data),
                        ypgpage: _this.data.ypgpage + 1,
                    })
                }
                if (ypgorderlist.length == res.data.count) {
                    this.setData({
                        hasMoreData: false
                    })
                }
            }
        })
    },
    ybd:function(){
        // console.log('ybd');
        this.setData({
            ypgshow: false,
            ybdshow: true,
            allshow: false,
        });
        var _this = this
        util.request({
            url: 'PreOrder/get_fgg_log2',
            method: 'get',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: {
                page: _this.data.ybdpage,
                status:1
            },
            success: res => {
                var ybdorderlist = _this.data.ybdorderlist

                if (!res.data) {
                    wx.showToast({
                        title: '没有匹配数据',
                        icon: 'success',
                        duration: 1000
                    })
                } else {
                    this.setData({
                        ybdorderlist: res.data.data,
                        ybdorderlist: ybdorderlist.concat(res.data.data),
                        ybdpage: _this.data.ybdpage + 1,
                    })
                }
                if (ybdorderlist.length == res.data.count) {
                    this.setData({
                        hasMoreData: false
                    })
                }
            }
        })
    },
    getOrderList: function() {
        this.setData({
            ypgshow: false,
            ybdshow: false,
            allshow: true,
        });
        var _this = this
        util.request({
            url: 'PreOrder/get_fgg_log',
            method: 'get',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: {
                page: _this.data.page,
            },
            success: res => {
                // console.log(res.data);
                var orderlist = _this.data.orderlist
                if (!res.data) {
                    wx.showToast({
                        title: '没有匹配数据',
                        icon: 'success',
                        duration: 1000
                    })
                } else {
                    this.setData({
                        orderlist: res.data.data,                        
                        orderlist: orderlist.concat(res.data.data),
                        page: _this.data.page + 1,
                    })
                }
                if (orderlist.length == res.data.count){
                    this.setData({
                        hasMoreData:false
                    })
                }
                // console.log(res.data.count);
                // console.log(orderlist.length);
            }
        })
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        console.log(options);
        console.log(this.hasMoreData);
        this.getOrderList();
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
        // console.log('pd');
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {
        console.log(this.data.hasMoreData);
        if (this.data.hasMoreData && this.data.allshow){
            this.getOrderList();
            
        }
        if (this.data.hasMoreData && this.data.ybdshow) {
            this.ybd();
        }
        if (this.data.hasMoreData && this.data.ypgshow) {
            this.ypg();

        }
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {

    }
})