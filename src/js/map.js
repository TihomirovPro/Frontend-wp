let zoomMap = 15
var centerMap = [53.927968, 27.643066]

ymaps.ready(function () {
  const map = new ymaps.Map('map', {
    center: centerMap,
    zoom: zoomMap,
    controls: []
  })

  var myPlacemark1 = new ymaps.Placemark([53.927968, 27.643066], {}, {
    iconLayout: 'default#image',
    iconImageHref: '../wp-content/themes/medusa/img/map-marker.svg',
    iconImageSize: [30, 42]
  })
  map.geoObjects.add(myPlacemark1)
  map.behaviors.disable('scrollZoom')
})
