(function () {
  if (typeof NodeList.prototype.forEach === 'function') {
    return false
  } else {
    NodeList.prototype.forEach = Array.prototype.forEach
  }
})()
