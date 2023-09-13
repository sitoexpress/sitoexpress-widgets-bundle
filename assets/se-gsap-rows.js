function gsap_backgrounds() {
  gsap.registerPlugin(ScrollTrigger);

  const sections = gsap.utils.toArray(".gsap-row-bg");
  const container = document.querySelector('body');
  const cssColor = window.getComputedStyle(document.body, null).getPropertyValue('background-color');
  const originalColor = (typeof cssColor !== "undefined") ? cssColor : 'transparent';

  sections.forEach((item, index, arr) => {

      var prev = (0 == index) ? "START" : arr[index-1];
      var next = (arr.length - 1 == index) ? "END" : arr[index+1];

      ScrollTrigger.create({
        trigger: item,
        anticipatePin: 1,
        start: 'top center',
        onEnter: function () {
          var body_bg = ($(item).attr('data-gsap-row-bg')) ? $(item).attr('data-gsap-row-bg') : originalColor;
          if(body_bg) {
            gsap.to(container, {
              duration: 0.3,
              backgroundColor: body_bg,
              ease: 'none'
            })
          }
        },
        onEnterBack: function () {
          var body_bg = ($(item).attr('data-gsap-row-bg')) ? $(item).attr('data-gsap-row-bg') : originalColor;
          if(body_bg) {
            gsap.to(container, {
              duration: 0.3,
              backgroundColor: body_bg,
              ease: 'none'
            })
          }
        },
        onLeaveBack: function () {
          var body_bg = ($(prev).attr('data-gsap-row-bg')) ? $(prev).attr('data-gsap-row-bg') : originalColor;
          if(body_bg) {
            gsap.to(container, {
              duration: 0.3,
              backgroundColor: body_bg,
              ease: 'none'
            })
          }
        },
        onLeave: function () {
          if(next == 'END') {
            gsap.to(container, {
              duration: 0.3,
              backgroundColor: originalColor,
              ease: 'none'
            })
          }
        },
      })
    })
}

document.addEventListener("DOMContentLoaded", function() {
  if(typeof imagesLoaded == undefined) {
    gsap_backgrounds()
  } else {
    imagesLoaded( document.body, function( instance ) {
      gsap_backgrounds()
    })
  }
})
