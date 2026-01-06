(function() {
  function detectTouchDevice() {
    var isTouch = 
      ('ontouchstart' in window) ||
      (navigator.maxTouchPoints > 0) ||
      (navigator.msMaxTouchPoints > 0);

    if (isTouch) {
      document.documentElement.classList.add('is-touch');
    }
  }

  // Run the function when the DOM is fully loaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', detectTouchDevice);
  } else {
    // DOM already loaded, run the function immediately
    detectTouchDevice();
  }
})();