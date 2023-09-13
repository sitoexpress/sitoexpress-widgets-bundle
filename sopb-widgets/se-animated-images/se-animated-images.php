<?php

/*
Widget Name: SE Animated Images Widget
Description: Displays images in an animated preset.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Animated_Images_Widget extends SiteOrigin_Widget {

  function __construct() {
    parent::__construct(
        'se-animated-images-widget',
        __('SE Animated Images Widget', 'se-sopb-widgets'),
        array(
            'description' => __('Displays images in an animated preset.', 'se-sopb-widgets'),
        ),
        array(
        ),
        false,
        plugin_dir_path(__FILE__)
    );
  }

  /**
	 * Initialize this widget in whatever way we need to. Run before rendering widget or form.
	 */

  function initialize() {
    add_action( 'siteorigin_widgets_enqueue_frontend_scripts_' . $this->id_base, array( $this, 'enqueue_widget_scripts' ) );
    add_filter( 'siteorigin_widgets_wrapper_classes_' . $this->id_base, array( $this, 'wrapper_class_filter' ), 10, 2 );
    add_filter( 'siteorigin_widgets_wrapper_data_' . $this->id_base, array( $this, 'wrapper_data_filter' ), 10, 2 );
  }

  function wrapper_class_filter( $classes, $instance ) {
    return $classes;
  }

  function wrapper_data_filter( $data, $instance ) {
    return $data;
  }

  function enqueue_widget_scripts( $instance ) {

    if(is_admin()) return;

    sewb_gsap_enqueue();

    wp_enqueue_style('se-animated-images', SPB_URL.'sopb-widgets/se-animated-images/styles/se-animated-images.css', SPB_VER);
    wp_enqueue_script('se-animated-images', SPB_URL.'sopb-widgets/se-animated-images/js/se-animated-images.js', array('gsap-scrolltrigger'), SPB_VER, true);

    do_action('se_animated_images_frontend_enqueue');

  }

  function modify_form($form) {
    return
      array(
        'image_1' => array(
            'type' => 'media',
            'library' => 'image',
            'label' => __('Image 1 (On top)', 'se-sopb-widgets' ),
        ),
        'image_2' => array(
            'type' => 'media',
            'library' => 'image',
            'label' => __('Image 2 (Middle)', 'se-sopb-widgets' ),
        ),
        'image_3' => array(
            'type' => 'media',
            'library' => 'image',
            'label' => __('Image 3 (Last shown)', 'se-sopb-widgets' ),
        ),
        'image_size' => array(
            'type' => 'image-size',
            'label' => __( 'Image size', 'se-sopb-widgets' )
        ),
        'height' => array(
          'type' => 'slider',
          'label' => __( 'Set image height', 'se-sopb-widgets' ),
          'default' => 700,
          'min' => 480,
          'max' => 1080,
          'step' => 20,
          'integer' => true
        )
      );
  }

  function get_template_variables($instance, $args) {

    if ( empty( $instance ) ) return array();

    return array(
      'images' => $this->process_images($instance),
      'image_size' => $instance['image_size'],
      'height' => $instance['height']."px",
    );

  }

  private function process_link($url) {
    $explode = explode(' ', $url);
    return (isset($explode[1]) && is_numeric($explode[1])) ? get_the_permalink($explode[1]) : $url;
  }

  private function process_images($instance) {
    $images = array($instance['image_1'], $instance['image_2'], $instance['image_3']);
    return array_reverse($images);
  }

  function get_template_name($instance) {
      return 'template';
  }

}

siteorigin_widget_register('se-animated-images-widget', __FILE__, 'SE_Animated_Images_Widget');
