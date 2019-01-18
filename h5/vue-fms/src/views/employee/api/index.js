import Vue from 'vue'

export default {
  frontUserLogin: params => {
    return Vue.http.post(Vue.$BASE_URL + '/index.php/Qiye/front_user_login', params)
  }
}
