const { src } = require('gulp')
const fs = require('fs')
const ftp = require('vinyl-ftp')
const notifier = require('node-notifier')
const util = require('gulp-util')
const toaster = require('./toaster')

// upload
function upload (cb) {
  if (fs.existsSync('./ftpConfig.js')) {
    const configFTP = require('../ftpConfig.js')
    const conn = ftp.create({
      host: configFTP.host,
      user: configFTP.user,
      password: configFTP.password,
      log: util.log
    })

    return src('dist/**/*.*')
      .pipe(toaster('Upload', cb))
      .pipe(conn.dest(configFTP.dest))
  } else {
    notifier.notify({
      title: 'No ftp config',
      message: 'Read Readme "Setup upload task" section'
    })
    cb()
  }
}

function uploadMin (cb) {
  if (fs.existsSync('./ftpConfig.js')) {
    const configFTP = require('../ftpConfig.js')
    const conn = ftp.create({
      host: configFTP.host,
      user: configFTP.user,
      password: configFTP.password,
      log: util.log
    })

    return src('dist/**/*.{css,js,html}')
      .pipe(toaster('Upload', cb))
      .pipe(conn.dest(configFTP.dest))
  } else {
    notifier.notify({
      title: 'No ftp config',
      message: 'Read Readme "Setup upload task" section'
    })
    cb()
  }
}

module.exports = { upload, uploadMin }
