const path = require('path')

//
// base entries
//

const entries = {
  'js/main': './src/js/main.js',
  // 'js/slider': './src/js/slider.js',
  // 'js/map': './src/js/map.js'
}

exports.baseEntry = entries

//
// dev entries
//
const hot = 'webpack-hot-middleware/client?reload=true'
let devEntries = {}
for (const entriy in entries) {
  devEntries[entriy] = [entries[entriy], hot]
}
exports.devEntry = devEntries

//
// rules
//
exports.moduleRules = {
  rules: [{
    test: /\.(js)$/,
    exclude: /node_modules/,
    use: ['babel-loader']
  }]
}

//
// frontend output
//
exports.baseOutput = {
  path: path.join(__dirname, '../dist'),
  filename: '[name].js'
}
