const { src, dest } = require('gulp')
const sass = require('gulp-sass')
const sassGlob = require('gulp-sass-glob')
const autoprefixer = require('gulp-autoprefixer')
const combineMq = require('gulp-combine-mq')
const cleanCSS = require('gulp-clean-css')
const sourcemaps = require('gulp-sourcemaps')
const toaster = require('./toaster')
const flatten = require('gulp-flatten')
const gulpif = require('gulp-if')

const isDevelopment = process.env.NODE_ENV !== 'production'

// styles
function buildStyles (cb) {
  return src(['src/styles/main.sass', 'src/styles/slider.sass', '!src/pages/header/header.sass', '!src/pages/footer/footer.sass', 'src/pages/*/*.sass'])
    .pipe(sassGlob())
    .pipe(toaster('Sass', cb))
    .pipe(gulpif(isDevelopment, sourcemaps.init()))
    .pipe(sass())
    .pipe(autoprefixer('last 4 version', '>= ie 11'))
    .pipe(gulpif(!isDevelopment, combineMq({ beautify: false })))
    .pipe(gulpif(!isDevelopment, cleanCSS({ level: 2, rebase: false })))
    .pipe(gulpif(isDevelopment, sourcemaps.write('./')))
    .pipe(flatten())
    .pipe(dest('dist/css'))
}

module.exports = { buildStyles }
