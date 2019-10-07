var isntEmptyName = require('../utils/isnt_empty')

module.exports = function () {
  return {
    description: 'Create a new page',
    prompts: [{
      type: 'input',
      name: 'name',
      message: 'Page\'s name',
      validate: isntEmptyName
    }],

    actions: function () {
      var actions = []

      // pug
      actions.push({
        type: 'add',
        path: '../../src/pages/{{dashCase name}}/{{dashCase name}}.pug',
        templateFile: './page/page.pug'
      })

      // styl
      actions.push({
        type: 'add',
        path: '../../src/pages/{{dashCase name}}/{{dashCase name}}.sass',
        templateFile: './page/page.sass'
      })

      return actions
    }
  }
}
