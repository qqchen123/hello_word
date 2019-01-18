export default [{
  path: '/userlogin',
  meta: {
    title: '员工登录'
  },
  component: r => require.ensure([], () => r(require('./userlogin')), 'userlogin')
}, {
  path: '/userpag',
  meta: {
    title: '员工首页'
  },
  component: r => require.ensure([], () => r(require('./userpag')), 'userpag')
}, {
  path: '/declForm',
  meta: {
    title: '报单'
  },
  component: r => require.ensure([], () => r(require('./declForm')), 'declForm')
}, {
  path: '/declProcess2',
  meta: {
    title: '报单'
  },
  component: r => require.ensure([], () => r(require('./declProcess2')), 'declProcess2')
}, {
  path: '/declProcess3',
  meta: {
    title: '报单3'
  },
  component: r => require.ensure([], () => r(require('./declProcess3')), 'declProcess3')
}, {
  path: '/declSuccess',
  meta: {
    title: '报单成功'
  },
  component: r => require.ensure([], () => r(require('./declSuccess')), 'declSuccess')
}, {
  path: '/test',
  meta: {
    title: '调试页'
  },
  component: r => require.ensure([], () => r(require('./test')), 'test')
}]
