# SitoExpress Widgets Bundle Changelog
This changelog will still be used to keep track of changes, as the github repo won't necessarily follow our internal release history.
* Current release: 1.5

## v.1.5
* wc-category:  added check if WooCommerce is not installed
* added GSAP library support
* added row-background color change on scroll option (uses GSAP)
* added widget entrance effects on scroll (uses GSAP)
* added several widgets:
  * se-animated-images - an animated widget showing 3 stacked images that disappear/appear on click
  * se-animated-text - an animated widget showing animated text elements and a CTA button
  * se-conditional-content - display a Layout Builder content or another according to conditions (archive/singular/taxonomy)
  * se-image-carousel - an impressive and very powerful image carousel based on Splide JS
  * se-just-masonry - a simple masonry-like image gallery widget
  * se-layout-carousel - an impressive and very powerful Layout Builder carousel based on Splide JS
  * se-post-carousel - an impressive and very powerful post carousel /w template management and query builder based on Splide JS
  * se-simple-card - create a "card-like" content and order it (f.eg title, image, text, button)
  * se-stamp-wheel - an animated text wheel with a logo at the center
  * se-ticker - a word/text horizontal ticker based on Flickity (will move to Splide ASAP though)
* lots of code improvements
  * admin-style.css: now repeater fields have 3 columns which is generally better
  * new function: sewb_img($attachment_id, $size, $alt = '') - create an img HTML markup from an attachment_id
  * new function: reorder_sopb_layouts($layouts) will reorder SOPB theme-set layout in alphabetical order
  * new function: sewb_gsap_enqueue() enqueues gsap library and gsap-scrolltrigger when needed
  * new function: sewb_splide_enqueue($instance = 'null') enqueues splidejs when needed
  * se-popup: fixed textdomain references in strings
  * se-post-carousel: previously used Slick, moved code to Splide JS
  * se-video: small CSS fixes
  * wc-category: added check to prevent errors when WooCommerce is not active
  * wc-product: fixed textdomain reference in strings


## v.1.0
* carousel:   added se-carousel.js in order to avoid multiple slick() initializations because of wp_add_inline_script()
* popup:      text-align class applied to popup-wrap in hope it'll not break anything

## v.0.9
* video:    added 'simple-control' mode where only a large play button appears with no bar
* video:    video can be stopped by tapping video center
* carousel: created post carousel widget
* random:   fixed 1st random content would never show up

## v.0.8
* popup:        added fullscreen toggle for Popup
* popup:        fixed cf7 breaking block in template.php

## v.0.7
* popup:        makes use of modify_form to handle form
* popup:        added support for advanced content via Layout Builder
* wc-category:  makes use of modify_form to handle form
* wc-category:  added many customization options

## v.0.6
* popup:  makes use of sauce_create_popup in template.php with fallback

## v.0.5
* various fixes:  straight outta tag24

## v.0.3
* se-video-widget.php:  now able to correctly parse Youtube IDs as GET parameters (/v=XXXXXX) thus playing in iOS

## v.0.2
* se-video.css:         added .no_controls rules to hide controls
* se-video-widget.php:  sets clickToPlay to false if no_controls is checked
