<?php

/*
Widget Name: SE Stamp Wheel Widget
Description: Displays text and image in a rotating stamp wheel.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Stamp_Wheel_Widget extends SiteOrigin_Widget {

  function __construct() {
    parent::__construct(
        'se-stamp-wheel-widget',
        __('SE Stamp Wheel Widget', 'se-sopb-widgets'),
        array(
            'description' => __('Displays text and image in a rotating stamp wheel.', 'se-sopb-widgets'),
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

    wp_enqueue_style('se-stamp-wheel', SPB_URL.'sopb-widgets/se-stamp-wheel/styles/se-stamp-wheel.css', SPB_VER);

    do_action('se_animated_text_frontend_enqueue');

  }

  function modify_form($form) {
    return
      array(
      'text' => array(
          'type' => 'text',
          'label' => __( 'Enter here the rotating text', 'se-sopb-widgets' )
      ),
      'image' => array(
          'type' => 'media',
          'library' => 'image',
          'label' => __('Set the image at the center of the stamp (should be square in size and transparent)', 'se-sopb-widgets' ),
      ),
      'image_size' => array(
          'type' => 'image-size',
          'label' => __( 'Image size', 'se-sopb-widgets' )
      ),
      'color' => array(
          'type' => 'color',
          'label' => __('Set the stamp text color', 'se-sopb-widgets' ),
      ),
      'background' => array(
          'type' => 'color',
          'label' => __('Set the stamp background color', 'se-sopb-widgets' ),
      ),
      'url' => array(
          'type' => 'link',
          'label' => __('Link', 'se-sopb-widgets'),
          'description' => __('If the wheel should link something, select content or paste URL', 'se-sopb-widgets')
      ),
      'font_size' => array(
        'type' => 'slider',
        'label' => __( 'Set font size', 'se-sopb-widgets' ),
        'default' => 16,
        'min' => 9,
        'max' => 21,
        'step' => 1,
        'integer' => true
      ),
      'width' => array(
        'type' => 'slider',
        'label' => __( 'Set wheel width', 'se-sopb-widgets' ),
        'default' => 300,
        'min' => 160,
        'max' => 800,
        'step' => 10,
        'integer' => true
      )
    );
  }

  function get_template_variables($instance, $args) {

    if ( empty( $instance ) ) return array();

    return array(
      'text' => $instance['text'],
      'image' => (!empty($instance['image'])) ? wp_get_attachment_image_src($instance['image'], $instance['image_size']) : '',
      'color' => $instance['color'],
      'background' => $instance['background'],
      'width' => $instance['width']."px",
      'font_size' => $instance['font_size'],
      'url' => $this->process_link($instance['url'])
    );

  }

  private function process_link($url) {
    $explode = explode(' ', $url);
    return (isset($explode[1]) && is_numeric($explode[1])) ? get_the_permalink($explode[1]) : $url;
  }

  function get_template_name($instance) {
      return 'template';
  }

}

siteorigin_widget_register('se-stamp-wheel-widget', __FILE__, 'SE_Stamp_Wheel_Widget');
