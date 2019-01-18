export default [{
  path: '/login',
  meta: {
    title: '登录'
  },
  component: r => require.ensure([], () => r(require('./login')), 'login')
}, {
  path: '/forgotPw',
  meta: {
    title: '忘记密码'
  },
  component: r => require.ensure([], () => r(require('./forgotPw')), 'forgotPw')
}, {
  path: '/getReport',
  meta: {
    title: '获取运营商报告'
  },
  component: r => require.ensure([], () => r(require('./getReport')), 'getReport')
}, {
  path: '/registion',
  meta: {
    title: '授权'
  },
  component: r => require.ensure([], () => r(require('./registion')), 'registion')
}, {
  path: '/vaildCode',
  meta: {
    title: '注册'
  },
  component: r => require.ensure([], () => r(require('./vaildCode')), 'vaildCode')
}, {
  path: '/register',
  meta: {
    title: '注册'
  },
  component: r => require.ensure([], () => r(require('./register')), 'register')
}, {
  path: '/xieyi',
  meta: {
    title: '协议页面'
  },
  component: r => require.ensure([], () => r(require('./xieyi')), 'xieyi')
}, {
  path: '/registiontwo',
  meta: {
    title: '注册页面2'
  },
  component: r => require.ensure([], () => r(require('./registiontwo')), 'registiontwo')
}]
