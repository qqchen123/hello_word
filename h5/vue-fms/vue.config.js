module.exports = {
  // baseUrl: process.env.NODE_ENV === 'production' ? '/' : '/',
  pages: {
     index: {
       // page 的入口
       entry: 'src/main.js',
       // 模板来源
       template: 'public/index.html',
       // 在 dist/index.html 的输出
       filename: 'index.html'
     },
     // 当使用只有入口的字符串格式时，
     // 模板会被推导为 `public/subpage.html`
     // 并且如果找不到的话，就回退到 `public/index.html`。
     // 输出文件名会被推导为 `subpage.html`。
     // subpage: 'src/subpage/main.js'
   },
   css: {
     loaderOptions: {
       // 给 sass-loader 传递选项
       sass: {
         // @/ 是 src/ 的别名
         // 所以这里假设你有 `src/variables.scss` 这个文件
         data: `@import "@/sass/tobe/_function.scss";`
       }
     }
   },
   devServer: {
     proxy: {
       '/api': {
         target: 'http://120.26.89.131:60523/fms',
         ws: true,
         changeOrigin: true,
         pathRewrite: {
           '^/api': ''
         }
       }
     }
   }
}