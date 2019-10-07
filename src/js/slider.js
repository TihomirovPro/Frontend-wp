import Swiper from 'swiper/dist/js/swiper'

document.addEventListener('DOMContentLoaded', () => {

  const specialistsSlider = new Swiper('.specialists__list', {
    // loop: false,
    slidesPerView: 1,
    // spaceBetween: 30,
    navigation: {
      nextEl: '.btnNext',
      prevEl: '.btnPrev'
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'fraction'
    }
  })

  const servicesSlider = new Swiper('.services__list', {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 170,
    navigation: {
      nextEl: '.btnNext',
      prevEl: '.btnPrev'
    }
  })

})
