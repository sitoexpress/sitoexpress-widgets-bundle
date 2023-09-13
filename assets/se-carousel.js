var sewb_sliders_elms
var sewb_sliders = []

document.addEventListener( 'DOMContentLoaded', function() {

  sewb_sliders_elms = document.getElementsByClassName( 'splide' );

  for ( var i = 0; i < sewb_sliders_elms.length; i++ ) {

    sewb_sliders[i] = new Splide( sewb_sliders_elms[ i ] ).mount( window.splide.Extensions );

  }

});
