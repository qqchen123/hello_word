export default [{
  path: '/epLogin',
  meta: {
    title: '员工'
  },
  component: r => require.ensure([], () => r(require('./epLogin')), 'epLogin')
}]
