import Vue from 'vue'
import url from '../../overallS/qjbl'

export default {
  // H5开户
  createAccount: params => {
    return Vue.http.post(url.url + '/index.php/Qiye/front_end_reg', params)
  },
  login: params => {
    return Vue.http.post(url.url + '/index.php/Qiye/front_end_login', params)
  },
  forgotpwd: params => {
    return Vue.http.post(url.url + '/index.php/Qiye/front_end_set_pwd', params)
  },
  cAccount: params => {
    return Vue.http.post(url.url + '/index.php/Qiye/front_end_reg', params)
  },
  backurl: params => {
    return url.url + "/index.php/";
  },
  yzmphone: params => {
    return Vue.http.get(url.url + '/index.php/Sms/send_sms/' + params);
  },
  getstatus: params => {
    return Vue.http.post(url.url + '/index.php/Qiye/user_report_status', params)
  },
  getaccs: params => {
    return Vue.http.post(url.url + url.callbackroute + 'yys_report', params)
  },
  jdye: params => {
    return Vue.http.post(url.url + url.callbackroute + 'jd_report', params)
  },
  taobao: params => {
    return Vue.http.post(url.url + url.callbackroute + 'taobao_report', params)
  },
  creditcard: params => {
    return Vue.http.post(url.url + url.callbackroute + 'credit_card_report', params)
  },
  bankcard: params => {
    return Vue.http.post(url.url + url.callbackroute + 'bank_card_report', params)
  },
  gongjj: params => {
    return Vue.http.post(url.url + url.callbackroute + 'gjj_report', params)
  }
}
