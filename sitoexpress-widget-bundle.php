<?php

/*

Plugin Name: SE SiteOrigin Widgets Bundle
Description: Adds some Custom Widgets to SOPB
Author: Sito.Express
Author URI: https://sito.express
Version: 1.5
License: GPL v3
Text Domain: se-sopb-widget

*/

define('SPB_VER', 'v.1.5');
define('SPB_DIR', plugin_dir_path(__FILE__));
define('SPB_URL', plugin_dir_url(__FILE__));

/*
* Extending Siteorigin Widget Bundle
*/

function bp_sopb_widget_bundle($folders){
    $folders[] = SPB_DIR.'sopb-widgets/'; // important: Slash on end string is required
    return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'bp_sopb_widget_bundle');

/*
* Enqueuing Admin Style
*/

function bp_sopb_admin_style( $hook ) {
    if ( 'edit.php' != $hook && 'post.php' != $hook) {
        return;
    }
    wp_enqueue_style( 'se-sopb', SPB_URL.'assets/admin-style.css');
}
add_action( 'admin_enqueue_scripts', 'bp_sopb_admin_style' );

/*
* Adding Functionalities
*/

function se_sopb_widget_extended() {

  /* GSAP Row Background Color Change */
  add_filter( 'siteorigin_panels_row_style_fields', 'gsap_row_bg_field' );
  add_filter( 'siteorigin_panels_row_style_attributes', 'gsap_row_bg_data', 10, 2 );

  /* GSAP Widget Entrance Animation */
  add_filter( 'siteorigin_panels_widget_style_fields', 'gsap_widget_entrance_field' );
  add_filter( 'siteorigin_panels_widget_style_attributes', 'gsap_widget_entrance_data', 10, 2 );

}
add_action('plugins_loaded', 'se_sopb_widget_extended');

/*
* Check if Page has parent and report it in Post Search
*/

add_filter('siteorigin_widgets_search_posts_results', 'so_widget_add_page_parent');
function so_widget_add_page_parent($results) {
  foreach($results as &$result) {
    $parents = get_post_ancestors($result['value']);
    if(!empty($parents)) {
      $result['label'] = $result['label']." (".get_the_title($parents[0]).")";
    }
  }
  return $results;
}

/*
* GSAP Row Background Color Change
*/

function gsap_row_bg_field( $fields ) {
    $fields['gsap_row_bg'] = array(
        'name' => __( 'Animated background color', 'se-sopb-widget' ),
        'type' => 'color',
        'group' => 'design',
        'description' => __( 'If enabled, the body background will fade to the selected color when row is visible', 'se-sopb-widget' ),
        'priority' => 5,
    );
    return $fields;
}

function gsap_row_bg_data( $attributes, $args ) {

    if ( ! empty( $args['gsap_row_bg'] ) ) {

        array_push( $attributes['class'], 'gsap-row-bg' );
        $attributes['data-gsap-row-bg'] = $args['gsap_row_bg'];

        sewb_gsap_enqueue();
        wp_enqueue_script('gsap-row-init', SPB_URL.'assets/se-gsap-rows.js', array('gsap-scrolltrigger'), SPB_VER, true);

    }

    return $attributes;

}

/*
* GSAP Widget Entrance Effect
*/

function gsap_widget_entrance_field( $fields ) {
    $fields['gsap_widget_entrance'] = array(
        'name' => __( 'Animated entrance', 'se-sopb-widget' ),
        'type' => 'select',
        'group' => 'design',
        'description' => __( 'If enabled, the widget elements will enter the screen with the selected effects', 'se-sopb-widget' ),
        'options' => array(
          'disabled' => 'None',
          'fade-in_y_top' => 'Fade in - Top',
          'fade-in_y_bottom' => 'Fade in - Bottom',
          'fade-in_x_left' => 'Fade in - Left',
          'fade-in_x_right' => 'Fade in - Right'
        ),
        'priority' => 5,
    );
    return $fields;
}

function gsap_widget_entrance_data( $attributes, $args ) {

    if ( isset($args['gsap_widget_entrance']) && !empty( $args['gsap_widget_entrance'] ) && $args['gsap_widget_entrance'] != 'disabled' ) {

        array_push( $attributes['class'], 'gsap-widget-entrance' );

        $classes = explode('_', $args['gsap_widget_entrance']);

        foreach($classes as $class) {
          array_push( $attributes['class'], $class);
        }

        $attributes['data-gsap_effect'] = $classes[0];
        $attributes['data-gsap_direction'] = $classes[1];
        $attributes['data-gsap_amount'] = ($classes[2] == 'bottom' || $classes[2] == 'right') ? '50' : '-50';

        sewb_gsap_enqueue();
        wp_enqueue_script('gsap-widgets-init', SPB_URL.'assets/se-gsap-widgets.js', array('gsap-scrolltrigger'), SPB_VER, true);
        wp_enqueue_style('gsap-widgets-init', SPB_URL.'assets/se-gsap-widgets.css', '', SPB_VER, false);

    }

    return $attributes;

}

/*
* Create Image tag from attachment ID, Image Size & Title
*/

function sewb_img($id, $size, $alt = '') {

  $image_data = wp_get_attachment_image_src( $id, $size );
  $alt = ($alt) ? $alt : get_the_title();
  $srcset = wp_get_attachment_image_srcset($id, $size);

  if( !empty( $image_data ) ) {

    return "<img alt='".$alt."' src='".$image_data[0]."' srcset='".$srcset."' width='".$image_data[1]."' height='".$image_data[2]."' />";

  }
}

/*
* Sort SOPB Prebuilt Layouts by Alphabetical ID
*/

add_filter( 'siteorigin_panels_prebuilt_layouts', 'reorder_sopb_layouts', 20, 1);
function reorder_sopb_layouts($layouts) {

    // Sort layouts alphabetically.
    usort( $layouts, function( $a, $b ) {
        return strcmp( $a['id'], $b['id'] );
    } );

    // It seems SiteOrigin messes with numbers
    $reordered = array();
    foreach($layouts as $data) {
      $reordered[$data['id']] = $data;
    }

    return $reordered;
};

/*
* Enqueue GSAP & ScrollTrigger
*/

function sewb_gsap_enqueue() {
  wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js', '', false, true);
  wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/ScrollTrigger.min.js', array('gsap'), false, true);
}

/*
* Enqueue Splide
*/

function sewb_splide_enqueue($instance = null) {
  wp_enqueue_script('splide','https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.4/js/splide.min.js', array('jquery','sauce-script'), false, true);
  wp_enqueue_script('splide-autoscroll', 'https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-auto-scroll@0.5.3/dist/js/splide-extension-auto-scroll.min.js', array('splide'), false, true);
  wp_enqueue_script('splide-intersection', 'https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-intersection@0.2.0/dist/js/splide-extension-intersection.min.js', array('splide'), false, true);
  wp_enqueue_style('splide','https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.4/css/splide.min.css', '', true);
  wp_enqueue_style('splide-custom', SPB_URL.'assets/se-carousel.css', array('splide'), SPB_VER);
  wp_enqueue_script('splide-init', SPB_URL.'assets/se-carousel.js', array('splide', 'splide-autoscroll'), SPB_VER, true);

  do_action('se_image_carousel_frontend_enqueue', $instance);
}
