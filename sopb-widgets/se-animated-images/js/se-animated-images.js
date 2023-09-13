function gsap_images() {

  gsap.registerPlugin(ScrollTrigger);

  const sections = gsap.utils.toArray(".se-animated-images");

  sections.forEach((item, index, arr) => {

      ScrollTrigger.create({
        trigger: item,
        anticipatePin: 1,
        start: 'top 50%',
        onEnter: function () {
          if($('.image', item).hasClass('a-disappear')) {
            $('.image', item).toggleClass('a-disappear a-appear animated')
          }
        },
      })

      let images = item.querySelectorAll(".image")

      images.forEach((image, index, arr) => {

        image.addEventListener("click", function(event) {

          if(image.classList.contains('a-disappear')) return

          let all
          let hidden

          image.classList.toggle('a-disappear')
          image.classList.toggle('a-appear')

          all = images.length
          hidden = item.querySelectorAll(".a-disappear").length

          if(all == hidden) {
            setTimeout(function() {
              window.requestAnimationFrame(function() {
                images.forEach((image, index, arr) => {
                  image.classList.toggle('a-disappear')
                  image.classList.toggle('a-appear')
                })
              })
            }, 1000)
          }

        })

    })

  })

}

document.addEventListener("DOMContentLoaded", function() {

  gsap_images()

})
