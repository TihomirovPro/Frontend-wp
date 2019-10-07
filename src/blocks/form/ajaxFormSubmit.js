export function ajaxFormSubmit () {
  const forms = document.querySelectorAll('.form')

  forms.forEach(item => {
    item.addEventListener('submit', function (e) {
      e.preventDefault()

      const ajaxMessage = this.parentNode.querySelector('.ajaxMessage')
      const success = ajaxMessage.querySelector('.ajaxMessage__success')
      const error = ajaxMessage.querySelector('.ajaxMessage__error')
      const closeMessage = ajaxMessage.querySelector('.ajaxMessage__btn')
      const inputs = ajaxMessage.querySelectorAll('.form__input')

      const request = new XMLHttpRequest()
      request.open('POST', '/mail.php')
      const formData = new FormData(this)
      request.send(formData)

      request.addEventListener('readystatechange', function () {
        if (request.readyState < 4) {
          // code
        } else if (request.readyState === 4 && request.status === 200) {
          item.style.display = 'none'
          ajaxMessage.classList.add('open')
          success.classList.add('open')

          inputs.forEach(function (input) {
            input.value = ''
          })
        } else {
          item.style.display = 'none'
          ajaxMessage.classList.add('open')
          error.classList.add('open')
        }
      }) // end request.addEventListener

      closeMessage.addEventListener('click', () => {
        item.style.display = ''
        ajaxMessage.classList.remove('open')
        success.classList.remove('open')
        error.classList.remove('open')
      })
    }) // end item.addEventListener
  }) // end forms.forEach
}

export function addInput () {
  const form = document.querySelector('.form')
  const addBtn = document.querySelector('.addInput')
  const input = document.querySelector('.cloneInput')

  if (addBtn) {
    addBtn.addEventListener('click', (e) => {
      e.preventDefault()
      const cloneInput = input.cloneNode(true)
      form.insertBefore(cloneInput, addBtn)
    })
  }
}
