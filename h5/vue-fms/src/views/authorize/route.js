export default [{
  path: '/authorize',
  meta: {
    title: '魔蝎授权'
  },
  component: r => require.ensure([], () => r(require('./authorize')), 'authorize')
}, {
  path: '/authorizeInfo',
  meta: {
    title: '基本信息'
  },
  component: r => require.ensure([], () => r(require('./authorizeInfo')), 'authorizeInfo')
}, {
  path: '/creditEval',
  meta: {
    title: '信息页面'
  },
  component: r => require.ensure([], () => r(require('./creditEval')), 'creditEval')
}, {
  path: '/realEval',
  meta: {
    title: '房产评估'
  },
  component: r => require.ensure([], () => r(require('./realEval')), 'realEval')
 }, {
  path: '/tojd',
  meta: {
    title: '京东授权'
  },
  component: r => require.ensure([], () => r(require('./tojd')), 'tojd')
}, {
  path: '/totaoba',
  meta: {
    title: '淘宝授权'
  },
  component: r => require.ensure([], () => r(require('./totaoba')), 'totaoba')
}, {
  path: '/gongjij',
  meta: {
    title: '公积金'
  },
  component: r => require.ensure([], () => r(require('./gongjij')), 'gongjij')
}, {
  path: '/bankcard',
  meta: {
    title: '信用卡'
  },
  component: r => require.ensure([], () => r(require('./bankcard')), 'bankcard')
}, {
  path: '/creditcards',
  meta: {
    title: ''
  },
  component: r => require.ensure([], () => r(require('./creditcards')), 'creditcards')
}, {
  path: '/taobaoid',
  meta: {
    title: ''
  },
  component: r => require.ensure([], () => r(require('./taobaoid')), 'taobaoid')
}, {
  path: '/yysid',
  meta: {
    title: ''
  },
  component: r => require.ensure([], () => r(require('./yysid')), 'yysid')
}, {
  path: '/jdid',
  meta: {
    title: ''
  },
  component: r => require.ensure([], () => r(require('./jdid')), 'jdid')
}, {
  path: '/selectnext',
  meta: {
    title: ''
  },
  component: r => require.ensure([], () => r(require('./selectnext')), 'selectnext')
}]
