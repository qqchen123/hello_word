//权限模块控制 by 奚晓俊
const app = getApp()
const powers = {
  allPowers : [
    ['PreOrder', 'get_houses'],//评估、报单
    ['PreOrder', 'pingguT'],//评估
    ['PreOrder', 'get_house_price'],//评估
    ['PreOrder', 'baodanT'],//报单
    ['PreOrder', 'get_one_fgginfo_by_oid'],//报单
    ['PreOrder', 'baodan'],//报单
    ['PreOrder', 'get_fgg_log'],//订单
    ['PreOrder', 'get_fgg_log2'],//订单
    ['PreOrder', 'get_order_detail_by_id'],//订单
  ],
  baodan : [
    ['PreOrder', 'get_houses'],//评估、报单
    ['PreOrder', 'baodanT'],//报单
    ['PreOrder', 'get_one_fgginfo_by_oid'],//报单
    ['PreOrder', 'baodan'],//报单
  ],
  pinggu : [
    ['PreOrder', 'get_houses'],//评估、报单
    ['PreOrder', 'pingguT'],//评估
    ['PreOrder', 'get_house_price'],//评估
  ],
  dingdan : [
    ['PreOrder', 'get_fgg_log'],//订单
    ['PreOrder', 'get_fgg_log2'],//订单
    ['PreOrder', 'get_order_detail_by_id'],//订单
  ],
}

// 判断是否具备全部权限 by 奚晓俊
  // ifGoHome 没有权限是否返回首页 默认返回
const checkRolePowers = (name,ifGoHome) => {
  let needPowers = powers[name].concat()
  // console.log(needPowers)
  // console.log(app.globalData.powers)
  let lessPowers = []
  for (let k1 in needPowers) {
    for (let v2 of app.globalData.powers) {
      if (needPowers[k1].sort().toString() === v2.sort().toString()) {
        delete needPowers[k1]
        break
      }
    }
    if (needPowers[k1] != undefined) lessPowers.push(needPowers[k1])
  }

  if (lessPowers.length != 0) {
    console.log('缺失权限', lessPowers)
    wx.showToast({
      title: '权限不足',
      icon: 'none',
      duration: 1000,
      complete: ()=>{
        if(ifGoHome !== false){
          wx.switchTab({
            url: '/pages/jdunion/homepage',
            complete: ()=>{
              if (!app.globalData.bind) {
                wx.navigateTo({
                  url: '/pages/jdunion/login/login'
                })
              }
            }
          })
        }else{
          if(!app.globalData.bind) {
            wx.navigateTo({
              url: '/pages/jdunion/login/login'
            })
          }
        }
      }
    })
    return false
  } else {
    return true
  }
}

module.exports = {
  checkRolePowers: checkRolePowers,
}