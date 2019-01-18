const app = getApp()
const util = require('../../utils/util.js')
const powers = require('../../utils/powers.js')
Page({
    data: {
        objectArray: ['请选择', '住宅', '别墅'],
        towardArray: ['请选择', '东', '西', '南', '北', '东南', '东西', '西南', '东北', '南北', '西北', ],
        towardIndex: 0,
        ghIndex: 0,
        qingkArray: ['请选择', '一抵', '二抵'],
        qingIndex: 0,
        diya_mon_show: false,
        tempFile: [],
        isShow: true,
        houseName: null,
        houseId: null,
        hidden: true,
        diYaZongJia: [],
        diYaDanJia: [],
        nocancel: false,
        real_pics: {},
        date: '请选择',
        allImgs: app.globalData.baseImg,
        pics: [],
        real_pics: [],
        array: [],
        nowdate: '',
    },
    floor_bind: function(e) {
        this.setData({
            floor_num: e.detail.value
        })
        let f_num = this.data.floor_num
        let tf_num = this.data.total_floor_num
        if (f_num > tf_num) {
            wx.showToast({
                title: '总楼层不能小于所在楼层！',
                icon: 'none',
                duration: 1000,
                mask: true
            })
        }
    },
    totalfloor_bind: function(e) {
        this.setData({
            total_floor_num: e.detail.value
        })
        let f_num = this.data.floor_num
        let tf_num = e.detail.value
        if (f_num > tf_num) {
            wx.showToast({
                title: '总楼层不能小于所在楼层！',
                icon: 'none',
                duration: 1000,
                mask: true
            })
        }
    },
    bindPickerChanges(e) {
        this.setData({
            index: e.detail.value
        })
        // setDataarr();
    },
    bindDateChange(e) {
        console.log('picker发送选择改变，携带值为', e.detail.value)
        this.setData({
            date: e.detail.value
        })
    },
    formSubmit(e) {
        var _this = this;
        if (this.data.houseName < 1) {
            wx.showToast({
                title: '请输入小区名称/地址',
                icon: 'none',
                duration: 1000
            })
            return;
        }
        if(e.detail.value.floor>e.detail.value.totalfloor){
            wx.showToast({
                title: '所在楼层不能大于总楼层！！！',
                icon: 'none',
                duration: 1000
            })
            return;
        }
        if (e.detail.value.house_area.length < 1) {
            wx.showToast({
                title: '请输入建筑面积',
                icon: 'none',
                duration: 1000
            })
            return;
        }
        // wx.showLoading({
        //   title: '加载中...',
        // })
        util.request({
            url: 'PreOrder/get_house_price',
            method: 'POST',
            header: {
                'content-type': 'application/x-www-form-urlencoded',
            },
            data: Object.assign(e.detail.value, {
                houseName: this.data.houseName,
                full_house_name: this.data.full_house_name,
                houseId: this.data.houseId,
                img_path: JSON.stringify(_this.data.real_pics)
            }),
            success: res => {
                delete app.globalData.houseName
                delete app.globalData.houseId
                delete app.globalData.full_house_name
                if (res.data) {
                    //无结果显示
                    if (!res.data.ret) {} else {

                        console.log('+++');
                        console.log(res.data.data.id);
                        console.log('+++');

                        _this.setData({
                            hidden: false,
                            diYaDanJia: res.data.data.data.diYaDanJia,
                            diYaZongJia: res.data.data.data.diYaZongJia,
                            fangDaiZheKou: res.data.data.data.fangDaiZheKou * 10,
                            ZheKouZongJia: (res.data.data.data.fangDaiZheKou * res.data.data.data.diYaZongJia).toFixed(1),
                            oid: res.data.data.id,
                            // oid:res.data.data.id,
                        });
                    }
                }
            }
        })
    },
    cancel: function() {
        this.setData({
            hidden: true
        });

        wx.reLaunch({ //保留当前页面，跳转首页
            url: "/pages/jdunion/homepage"
        })
    },
    confirm: function() {
        if (powers.checkRolePowers('baodan')) {
            this.setData({
                hidden: true
            });
            app.globalData.housePriceId = this.data.oid

            wx.redirectTo({ //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
                url: "/pages/jdunion/customsform"
            })
        }
        
    },
    formReset() {
        console.log('form发生了reset事件')
    },
    //房屋名称  
    bindHouseName: function(e) {
        // console.log(e.detail.value)
        this.setData({
            houseName: e.detail.value
        })
    },
    //  点击城市组件确定事件  
    bindPickerChange: function(e) {
        console.log(e.detail.value)
        this.setData({
            ghIndex: e.detail.value
        })
    },
    //  点击房屋朝向组件确定事件  
    towardChange: function(e) {
        console.log(e.detail.value)
        this.setData({
            towardIndex: e.detail.value
        })
    },
    //抵押情况
    bindPickerqingk: function(e) {
        console.log(e.detail.value)
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
    //长按输出
    longPress: function(e) {
        var _that = this
        wx.showModal({
            title: '提示',
            content: '确定删除该图片',
            success: function(r) {
                if (r.cancel) return false
                let index = e.currentTarget.dataset.index
                _that.data.real_pics.splice(index, 1)
                _that.data.pics.splice(index, 1)
                _that.setData({
                    pics: _that.data.pics
                })
            }
        })
    },
    chooseimage: function() {
        var _this = this;
        var pics = this.data.tempFile;
        wx.chooseImage({
            count: 13, // 默认9  
            // 可以指定是原图还是压缩图，默认二者都有  
            sizeType: ['original', 'compressed'],
            // 可以指定来源是相册还是相机，默认二者都有
            sourceType: ['album', 'camera'],
            // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片   
            success: function(chooseRes) {
                util.forUploadFile({
                    ifShowToast: true,
                    url: 'preOrder/pingguT',
                    name: 'baodan[house]',
                    filePath: chooseRes.tempFilePaths,
                    success: uploadRes => {
                        let d = JSON.parse(uploadRes.data)
                        // if (d.ret) {
                        //   _this.data.pics[uploadRes.successIndex] = chooseRes.tempFilePaths[uploadRes.successIndex]
                        //   _this.setData({
                        //     pics: _this.data.pics
                        //   })

                        //   _this.data.real_pics[uploadRes.successIndex] = d.data

                        if (d.ret) {
                            _this.data.pics.push(chooseRes.tempFilePaths[uploadRes.successIndex])
                            _this.data.real_pics.push(d.data)
                            // console.log(_this.data.pics)
                            _this.setData({
                                pics: _this.data.pics,
                                real_pics: _this.data.real_pics
                            })
                        } else {

                        }
                        // console.log(_this.data.real_pics)
                    }
                })
            }
        })
    },
    //图片删除
    // clearImg: function (params) {
    //   var pics = this.data.tempFile
    //   var index = params.currentTarget.dataset.index // 图片索引
    //       pics.splice(index, 1) // 删除
    //   this.setData({
    //       pics:pics
    //   })
    // },
    //图片预览
    previewImage: function(e) {
        var current = e.target.dataset.src
        wx.previewImage({
            current: current,
            urls: this.data.pics
        })
    },
    onShow: function() {
        // console.log(app.globalData)
        if (app.globalData.houseName) {
            this.setData({
                houseName: app.globalData.houseName,
                houseId: app.globalData.houseId,
                full_house_name: app.globalData.full_house_name
            })
        }
        // console.log(this.data)
    },
    onUnload: function() {
        delete app.globalData.houseName
        delete app.globalData.houseId
        delete app.globalData.full_house_name
    },
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        let myDate = new Date();
        let fullyear = myDate.getFullYear();
        let diff = fullyear - 1900 + 1;
        var year_arr = [];
        for (let i = 0; i < diff; i++) {
            let ky = 1900 + i
            year_arr[i] = fullyear - i + 1;
        }
        year_arr[0] = '请选择';
        this.setData({
            array: year_arr
        })
    },

})