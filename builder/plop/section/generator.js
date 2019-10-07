var isntEmptyName = require('../utils/isnt_empty')

module.exports = function () {
  return {
    description: 'Create a new page section',
    prompts: [
      {
        type: 'input',
        name: 'page',
        message: 'page\'s name',
        validate: isntEmptyName
      },
      {
        type: 'input',
        name: 'name',
        message: 'Section\'s name',
        validate: isntEmptyName
      }
    ],

    actions: function () {
      var actions = []

      // pug
      actions.push({
        type: 'add',
        path: '../../src/pages/{{camelCase page}}/{{camelCase name}}/{{camelCase name}}.pug',
        templateFile: './section/section.pug'
      })

      // sass
      actions.push({
        type: 'add',
        path: '../../src/pages/{{camelCase page}}/{{camelCase name}}/{{camelCase name}}.sass',
        templateFile: './section/section.sass'
      })

      return actions
    }
  }
}
