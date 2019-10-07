module.exports = function (plop) {
  plop.setGenerator('block', require('./block/generator.js')())
  plop.setGenerator('page', require('./page/generator.js')())
  plop.setGenerator('section', require('./section/generator.js')())
}
