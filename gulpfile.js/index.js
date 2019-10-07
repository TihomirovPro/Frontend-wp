const { series, parallel, watch } = require('gulp')
const { runServer, reloadServer } = require('./server')
const { cleanDistFolder } = require('./clean')
const { copyStaticFiles } = require('./static')
const { buildMarkup } = require('./markup')
const { buildStyles } = require('./styles')
const { optimizeImages } = require('./images')
const { upload, uploadMin } = require('./upload')

// watchers
function runWatchers (cb) {
  // pug
  watch([
    'src/siteConfig.pug',
    'src/pages/**/*.pug',
    'src/layout/*.pug',
    'src/blocks/**/*.pug'
  ], series(buildMarkup, reloadServer))

  // styles
  watch([
    'src/blocks/**/*.sass',
    'src/styles/**/*.sass',
    'src/pages/**/*.sass'
  ], series(buildStyles))

  // static
  watch([
    'static/**/*'
  ], series(copyStaticFiles))

  // images
  watch([
    'images/*.+(jpg|png|gif|svg)',
    'src/pages/**/*.+(jpg|png|gif|svg)'
  ], series(optimizeImages))
  cb()
}

// development task
exports.default = series(
  cleanDistFolder,
  parallel(copyStaticFiles),
  parallel(buildStyles, buildMarkup, runServer, optimizeImages),
  runWatchers
)

// build task
exports.build = series(
  cleanDistFolder,
  parallel(copyStaticFiles),
  parallel(buildStyles, buildMarkup, optimizeImages)
)

// upload task
exports.upload = upload
exports.uploadMin = uploadMin

// clean task
exports.clean = cleanDistFolder
