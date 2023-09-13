function gsap_widgets() {

  gsap.registerPlugin(ScrollTrigger);

  const sections = gsap.utils.toArray(".gsap-widget-entrance");
  const container = document.querySelector('body');


  ScrollTrigger.batch(".gsap-widget-entrance", {
      start: 'top 87%',
      once: true,
      batchMax: 6,
      onEnter: batch => {
        batch.forEach((item, index, array) => {
        gsap.fromTo(
          item,
          {
            [item.dataset.gsap_direction]: [item.dataset.gsap_amount]
          },
          {
            opacity: 1,
            [item.dataset.gsap_direction]: 0,
            duration: 0.5,
            delay: index * 0.2
          }
        )
      })
    }
  })

}

document.addEventListener("DOMContentLoaded", function() {
  if(typeof imagesLoaded == undefined) {
    gsap_widgets()
  } else {
    imagesLoaded( document.body, function( instance ) {
      gsap_widgets()
    })
  }
})
