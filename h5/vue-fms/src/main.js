import Vue from 'vue'
import axios from 'axios'
import App from './App.vue'
import router from './router'
import './registerServiceWorker'
import VueResource from 'vue-resource'
import qj_ from './views/overallS/qjbl.vue'
import time_ from './views/overallS/time.vue'
Vue.prototype.qj = qj_
Vue.prototype.time = time_
Vue.prototype.$http = axios

require('@/sass/comm.scss')
require('@/sass/tobe/_function.scss')
Vue.config.productionTip = false
Vue.use(VueResource)
Vue.$SERVICE_BASE_URL = Vue.prototype.$SERVICE_BASE_URL = '/api'
// Vue.$BASE_URL = Vue.prototype.$BASE_URL = 'http://120.26.89.131:60523/fms'
Vue.$BASE_URL = Vue.prototype.$BASE_URL = 'http://h5.yuandoujinfu.com/apis/fms'
router.beforeEach(({ meta }, from, next) => {
  if (meta.title) {
    Vue.$title = Vue.prototype.$title = meta.title
    document.title = meta.title
  }
  next()
})

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
