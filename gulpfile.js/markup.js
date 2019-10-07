const { src, dest } = require('gulp')
const pug = require('gulp-pug')
const flatten = require('gulp-flatten')
const rename = require('gulp-rename')
const toaster = require('./toaster')

// markup
function buildMarkup (cb) {
  return src('src/pages/*/*.pug')
    .pipe(toaster('Pug', cb))
    .pipe(pug())
    .pipe(flatten())
    .pipe(rename(function (path) {
      path.extname = '.php'
    }))
    .pipe(dest('dist'))
}

module.exports = { buildMarkup }
