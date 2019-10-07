export function tabs () {
  const tabsWrap = document.querySelectorAll('.tabsWrapJs')

  tabsWrap.forEach(item => {
    const tabsNav = item.querySelector('.tabsNavJs')
    const tabsLinks = tabsNav.children
    const tabs = item.querySelector('.tabsJs').children

    tabsLinks[0].classList.add('active')
    tabs[0].classList.add('active')

    tabsNav.addEventListener('click', function (e) {
      const target = e.target
      for (let i = 0; i < tabsLinks.length; i++) {
        if (target !== tabsNav) {
          tabsLinks[i].classList.remove('active')
          tabs[i].classList.remove('active')
        }
        if (target === tabsLinks[i]) {
          tabsLinks[i].classList.add('active')
          tabs[i].classList.add('active')
        }
      }
    })

  })
}
