const del = require('del')

// clean dist
function cleanDistFolder () {
  return del('dist/**/*')
}

module.exports = { cleanDistFolder }
