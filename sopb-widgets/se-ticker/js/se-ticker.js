$(document).ready(function() {

  const carousels = document.querySelectorAll('.ticker-inner'),
    args = {
      accessibility: true,
      resize: true,
      wrapAround: true,
      prevNextButtons: false,
      pageDots: false,
      percentPosition: true,
      setGallerySize: true,
      draggable: false
    };

  carousels.forEach((carousel) => {
    let requestId;

    if (carousel.childNodes.length > 3) {

      const mainTicker = new Flickity(carousel, args);

      // Set initial position to be 0
      mainTicker.x = 0;

      // Start the marquee animation
      play();

      // Main function that 'plays' the marquee.
      function play() {
        mainTicker.x = mainTicker.x - 0.5;
        mainTicker.settle(mainTicker.x);
        requestId = window.requestAnimationFrame(play);
      }

      // Main function to cancel the animation.
      function pause() {
        if (requestId) {
          window.cancelAnimationFrame(requestId)
          requestId = undefined;
        }
      }

      // Pause on mouse over / focusin
      // carousel.addEventListener('mouseover', pause, false);
      // carousel.addEventListener('focusin', pause, false);

      // Unpause on mouse out / focusout
      // carousel.addEventListener('mouseout', play, false);
      // carousel.addEventListener('focusout', play, false);

    }

  });

})
