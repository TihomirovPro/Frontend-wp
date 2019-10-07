const browserSync = require('browser-sync')
const webpack = require('webpack')
const webpackDevMiddleware = require('webpack-dev-middleware')
const webpackHotMiddleware = require('webpack-hot-middleware')
const webpackConfig = require('../builder/webpack.config.dev')
const webpackBundler = webpack(webpackConfig)

function runServer (cb) {
  browserSync.init({
    server: { baseDir: 'dist' },
    middleware: [
      webpackDevMiddleware(webpackBundler),
      webpackHotMiddleware(webpackBundler)
    ],
    open: false,
    logFileChanges: false,
    notify: false,
    online: true,
    files: [
      'dist/css/*.css',
      'dist/js/*.js',
      'dist/*.html'
    ]
  })
  cb()
}

// reload server
function reloadServer (cb) {
  browserSync.reload()
  cb()
}

module.exports = {
  runServer,
  reloadServer
}
