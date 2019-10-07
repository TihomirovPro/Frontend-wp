var isntEmptyName = require('../utils/isnt_empty')

module.exports = function () {
  return {
    description: 'Create a new block',
    prompts: [{
      type: 'input',
      name: 'name',
      message: 'Block\'s name',
      validate: isntEmptyName
    }],

    actions: function () {
      var actions = []

      // pug
      actions.push({
        type: 'add',
        path: '../../src/blocks/{{camelCase name}}/{{camelCase name}}.pug',
        templateFile: './block/block.pug'
      })

      // styl
      actions.push({
        type: 'add',
        path: '../../src/blocks/{{camelCase name}}/{{camelCase name}}.sass',
        templateFile: './block/block.sass'
      })

      return actions
    }
  }
}
