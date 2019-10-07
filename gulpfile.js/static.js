const { src, dest } = require('gulp')

// static files
function copyStaticFiles () {
  return src('static/**/*')
    .pipe(dest('dist'))
}

module.exports = { copyStaticFiles }
