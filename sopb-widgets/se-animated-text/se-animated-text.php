<?php

/*
Widget Name: SE Animated Text Widget
Description: Displays text in an animated preset.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Animated_Text_Widget extends SiteOrigin_Widget {

  function __construct() {
    parent::__construct(
        'se-animated-text-widget',
        __('SE Animated Text Widget', 'se-sopb-widgets'),
        array(
            'description' => __('Displays text in an animated preset.', 'se-sopb-widgets'),
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

    wp_enqueue_style('se-animated-text', SPB_URL.'sopb-widgets/se-animated-text/styles/se-animated-text.css', SPB_VER);
    wp_enqueue_script('se-animated-text', SPB_URL.'sopb-widgets/se-animated-text/js/se-animated-text.js', array('gsap-scrolltrigger'), SPB_VER, true);

    do_action('se_animated_text_frontend_enqueue');

  }

  function modify_form($form) {
    return
      array(
      'heading' => array(
          'type' => 'text',
          'label' => __( 'Title', 'se-sopb-widgets' )
      ),
      'h_' => array(
          'type' => 'select',
          'label' => __( 'Title Style', 'se-sopb-widgets' ),
          'options' => array(
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h3' => 'H4',
          ),
      ),
      'content' => array(
          'type' => 'tinymce',
          'label' => __( 'Content', 'se-sopb-widgets' ),
          'rows' => 10,
          'default_editor' => 'tmce'
      ),
      'url' => array(
            'type' => 'link',
            'label' => __('Button URL', 'se-sopb-widgets'),
            'description' => __('Select content or paste URL', 'se-sopb-widgets')
        ),
      'button' => array(
          'type' => 'text',
          'label' => __('Button text', 'se-sopb-widgets'),
          'default' => ''
      ),
      'button_align' => array(
        'type' => 'select',
        'label' => __('Button Alignment', 'se-sopb-widgets'),
        'default' => 'text-left',
        'options' => array(
            'text-left' => __( 'Left', 'se-sopb-widgets' ),
            'text-center' => __( 'Center', 'se-sopb-widgets' ),
            'text-right' => __( 'Right', 'se-sopb-widgets' ),
        )
      )
    );
  }

  function get_template_variables($instance, $args) {

    if ( empty( $instance ) ) return array();

    return array(
      'heading' => $instance['heading'],
      'h_' => $instance['h_'],
      'text' => $instance['content'],
      'button' => $instance['button'],
      'button_align' => $instance['button_align'],
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

siteorigin_widget_register('se-animated-text-widget', __FILE__, 'SE_Animated_Text_Widget');
