export default [{
  path: '/button',
  meta: {
    title: '按钮样式测试'
  },
  component: r => require.ensure([], () => r(require('./button')), 'button')
}, {
  path: '/input',
  meta: {
    title: '输入框'
  },
  component: r => require.ensure([], () => r(require('./input')), 'input')
}, {
  path: '/div',
  meta: {
    title: 'divmb'
  },
  component: r => require.ensure([], () => r(require('./div')), 'div')
}, {
  path: '/mytest',
  meta: {
    title: 'mytest'
  },
  component: r => require.ensure([], () => r(require('./mytest')), 'mytest')
}]
