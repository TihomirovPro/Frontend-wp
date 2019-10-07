const baseEntry = require('./webpack.config.base').baseEntry
const moduleRules = require('./webpack.config.base').moduleRules
const baseOutput = require('./webpack.config.base').baseOutput

module.exports = {
  mode: 'production',
  stats: 'minimal',
  entry: baseEntry,
  module: moduleRules,
  output: baseOutput
}
