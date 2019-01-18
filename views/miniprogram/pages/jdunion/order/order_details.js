const app = getApp()
const util = require('../../../utils/util.js')
Page({
    data: {
        // 展开折叠 
        selectedFlag: [false, false],
    },
    // 展开折叠选择  
    changeToggle: function(e) {
        var index = e.currentTarget.dataset.index;
        if (this.data.selectedFlag[index]) {
            this.data.selectedFlag[index] = false;
        } else {
            this.data.selectedFlag[index] = true;
        }
        this.setData({
            selectedFlag: this.data.selectedFlag
        })
    },
    order_detail:function(){
        util.request({
            url: 'PreOrder/get_order_detail_by_id',
            method: 'get',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: {
                oid:this.data.ol_id
            },
            success: res => {
                if (!res.data) {
                    wx.showToast({
                        title: '没有匹配数据',
                        icon: 'none',
                        duration: 1000
                    })
                } else {
                    console.log(res.data.data)
                    this.setData({
                        order_detal:res.data.data
                    })
                }
            }
        })
    },
    onLoad:function(option){
        console.log(option.id);
        this.setData({
            ol_id:option.id
        })
        this.order_detail()
    }
})