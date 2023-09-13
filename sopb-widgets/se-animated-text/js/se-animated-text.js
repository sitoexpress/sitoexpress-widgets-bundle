function gsap_text() {

  gsap.registerPlugin(ScrollTrigger);

  const sections = gsap.utils.toArray(".se-animated-text");

  sections.forEach((item, index, arr) => {

    ScrollTrigger.create({
      trigger: item,
      anticipatePin: 1,
      start: 'top 50%',
      onEnter: function () {
        if($('.text', item).hasClass('a-disappear')) {
          $('.text', item).toggleClass('a-disappear a-appear animated')
        }
      },
    })

  })

}

$(document).ready(function() {

  gsap_text()

})
