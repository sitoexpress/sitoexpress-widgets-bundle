# SitoExpress Widgets Bundle Changelog
This changelog will still be used to keep track of changes, as the github repo won't necessarily follow our internal release history.
* Current release: 1.0

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
