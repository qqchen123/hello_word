module.exports = {
  root: true,
  env: {
    node: true
  },
  'extends': [
    'plugin:vue/essential',
    '@vue/standard'
  ],
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    // allow debugger during development
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    // allow paren-less arrow functions
    'arrow-parens': 0,
    // allow async-await
    'generator-star-spacing': 0,
    // standard规范不使用的在下面添加
    // 尾逗号不允许
    'comma-dangle': 0,
    // function前需要空格
    'space-before-function-paren': 0,
    // {}后面加分号
    'semi': 0,
    // 必须使用单引号
    'quotes': 0,
    // 定义字符串穿插正则
    'no-useless-escape': 0,
    // ===
    'eqeqeq': 0,
    'no-mixed-spaces-and-tabs': 0,
    'no-tabs': 0,
    'indent': 0,
    'camelcase': 0,
    "new-cap": 0,
    "one-var": 0,
    "no-cond-assign": 0,
    "no-mixed-operators": 0,
    "no-multiple-empty-lines": 0,
    "prefer-promise-reject-errors": 0
  },
  parserOptions: {
    parser: 'babel-eslint'
  }
}