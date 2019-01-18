/**
 * 浏览器的cookie操作
 * @param  {[key]}    [传key]
 * @return {[value]}  [获取key的值]
 */
const getCookie = (key) => {
  let arr = new RegExp('(^| )' + key + '=([^;]*)(;|$)');
  let reg = new RegExp('(^| )' + key + '=([^;]*)(;|$)');
  if ((arr = document.cookie.match(reg))) {
    return decodeURIComponent(arr[2]);
  } else {
    return null;
  }
}
const setCookie = (key, value, days) => {
  // 设置cookie过期事件,默认是30天
  var expire = new Date();
  days = days || 30;
  expire.setTime(expire.getTime() + (+days) * 24 * 60 * 60 * 1000);
  console.log(days, expire)
  document.cookie = key + "=" + encodeURIComponent(value) + ";expires=" + expire.toGMTString() + ";domain=" + location.hostname;
};
const deleteCookie = (key) => {
  var expire = new Date();
  expire.setTime(expire.getTime() - 1);
  var cval = getCookie(key);
  if (cval != null) {
    // 把toGMTString改成了toUTCString，两者等价。但是ECMAScript推荐使用toUTCString方法。toGMTString的存在仅仅是
    // 为了向下兼容
    document.cookie = key + "=" + cval + ";expires=" + expire.toUTCString();
  }
}
const setCookieMinutes = (key, value, minutes) => {
  // 设置cookie过期事件,默认是30分钟
  var expire = new Date();
  minutes = minutes || 30;
  expire.setTime(expire.getTime() + (+minutes) * 60 * 1000);
  console.log(minutes, expire)
  document.cookie = key + "=" + encodeURIComponent(value) + ";expires=" + expire.toGMTString() + ";domain=" + location.hostname;
};
export default {
  getCookie,
  setCookie,
  deleteCookie,
  setCookieMinutes
}
