import Vue from 'vue'
import Router from 'vue-router'
import NotFound from '@/views/NotFound'

Vue.use(Router)

const baseRoute = [
  {
    path: '/',
    redirect: '/registion'
  }
]

const notFoundRoute = [
  {
    path: '*',
    component: NotFound
  }
]
export default new Router({
  // mode: 'history',
  base: '/',
  routes: (r => {
    // let route = r.keys().map(key => r(key).default)
    let route = []
    r.keys().forEach(key => {
      let arr = r(key).default
      if (Array.isArray(arr)) {
        for (let tempData of arr) {
          route.push(tempData)
        }
      } else {
        route.push(arr)
      }
    })
    if (process.env.NODE_ENV !== 'production') {
      let routeDatas = [...baseRoute, ...route, ...notFoundRoute]
      let tempArr = []
      for (let item of routeDatas) {
        tempArr.push({
          path: item.path,
          title: (item.meta && item.meta.title) ? item.meta.title : '无title'
        })
      }
      console.log('/* 路由表 */')
      console.table(tempArr)
    }
    return [...baseRoute, ...route, ...notFoundRoute]
  })(require.context('../views', true, /^\.\/((?!\/)[\s\S])+\/route\.js$/))
})
