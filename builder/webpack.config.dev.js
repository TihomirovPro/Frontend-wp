const webpack = require('webpack')
const WriteFilePlugin = require('write-file-webpack-plugin')
const devEntry = require('./webpack.config.base').devEntry
const moduleRules = require('./webpack.config.base').moduleRules
const baseOutput = require('./webpack.config.base').baseOutput

module.exports = {
  mode: 'development',
  stats: 'minimal',
  entry: devEntry,
  module: moduleRules,
  output: baseOutput,

  plugins: [
    new webpack.HotModuleReplacementPlugin(),
    new WriteFilePlugin()
  ]
}
