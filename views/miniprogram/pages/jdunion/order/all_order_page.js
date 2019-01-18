const app = getApp()
const util = require('../../../utils/util.js')
const powers = require('../../../utils/powers.js')
Page({
    data: {
        navbar: ['全部', '已评估', '贷款中', '还款中', '已结清'],
        currentTab: 0,
        ypgpage: 1,
        allpage: 1,
        ybdpage: 1,
        allorderlist: [],
        ypgorderlist: [],
        ybdorderlist: [],
        ypg_hasMoreData: true,
        all_hasMoreData: true,
        ybd_hasMoreData: true,
        ypg_show: false,
        all_show: false,
        ybd_show: false,
    },
    oclick: function (e) {
        if (powers.checkRolePowers('baodan')) {
            app.globalData.housePriceId = e.currentTarget.id
            wx.navigateTo({
                url: "/pages/jdunion/customsform?oid=" + e.currentTarget.id
            })
        }
    },
    navbarTap: function(e) {
        this.setData({
            currentTab: e.currentTarget.dataset.idx
        })
        if(this.data.currentTab == 0){
            this.all_order_list();
        }
        if (this.data.currentTab == 1) {
            this.pg_order_list();
        }
        if (this.data.currentTab == 2) {
            this.bd_order_list();
        }
    },
    pg_order_list: function() {
        var _this = this
        this.setData({
            ypg_show: true
        })
        util.request({
            url: 'PreOrder/get_fgg_log',
            method: 'get',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: {
                page: _this.data.ypgpage,
                status: _this.data.currentTab,
            },
            success: res => {
                let ypgorderlist = _this.data.ypgorderlist
                if (!res.data) {
                    wx.showToast({
                        title: '没有匹配数据',
                        icon: 'none',
                        duration: 1000
                    })
                } else {
                    if (_this.data.currentTab == 1) {
                        this.setData({
                            ypgorderlist: res.data.data,
                            ypgorderlist: ypgorderlist.concat(res.data.data),
                            ypgpage: _this.data.ypgpage + 1,
                        })
                    }
                }
                if (ypgorderlist.length == res.data.count) {
                    this.setData({
                        ypg_hasMoreData: false
                    })
                }
            }
        })
    },
    //报单----贷款中
    bd_order_list: function () {
        var _this = this
        this.setData({
            ybd_show: true
        })
        util.request({
            url: 'PreOrder/get_fgg_log2',
            method: 'get',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: {
                page: _this.data.ybdpage,
                status: _this.data.currentTab,
            },
            success: res => {
                let ybdorderlist = _this.data.ybdorderlist
                if (!res.data) {
                    wx.showToast({
                        title: '没有匹配数据',
                        icon: 'none',
                        duration: 1000
                    })
                } else {
                    if (_this.data.currentTab == 2) {
                        this.setData({
                            ybdorderlist: res.data.data,
                            ybdorderlist: ybdorderlist.concat(res.data.data),
                            ybdpage: _this.data.ybdpage + 1,
                        })
                    }
                }
                if (ybdorderlist.length == res.data.count) {
                    this.setData({
                        ybd_hasMoreData: false
                    })
                }
            }
        })
    },
    all_order_list: function () {
        var _this = this
        
        this.setData({
            all_show: true
        })
        
        util.request({
            url: 'PreOrder/get_fgg_log',
            method: 'get',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: {
                page: _this.data.allpage,
                status: _this.data.currentTab,
            },
            success: res => {
                let allorderlist = _this.data.allorderlist
                console.log('-----');
                console.log(allorderlist.length);
                console.log('------');
                console.log(res.data.count);
                
                if (!res.data) {
                    wx.showToast({
                        title: '没有匹配数据',
                        icon: 'none',
                        duration: 1000
                    })
                } else {
                    if (_this.data.currentTab == 0) {
                        console.log('00000');
                        this.setData({
                            allorderlist: res.data.data,
                            allorderlist: allorderlist.concat(res.data.data),
                            allpage: _this.data.allpage + 1,
                        })
                    }
                }
                if (allorderlist.length == res.data.count) {
                    this.setData({
                        all_hasMoreData: false
                    })
                }
            }
        })
    },
    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {
        if (this.data.all_hasMoreData && this.data.all_show) {
            this.all_order_list();
        } else {
            wx.showToast({
                title: '没有更多...',
                icon: 'none',
                duration: 1000
            })
        }
        if (this.data.ybd_hasMoreData && this.data.ybd_show) {
            this.bd_order_list();
        } else {
            wx.showToast({
                title: '没有更多...',
                icon: 'none',
                duration: 1000
            })
        }
        if (this.data.ypg_hasMoreData && this.data.ypg_show) {
            this.pg_order_list();
        } else {
            wx.showToast({
                title: '没有更多...',
                icon: 'none',
                duration: 1000
            })
        }
    },
    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
      if (!powers.checkRolePowers('dingdan')) return false
    },
    onLoad: function(){
      if (powers.checkRolePowers('dingdan')) this.all_order_list()
    }
})