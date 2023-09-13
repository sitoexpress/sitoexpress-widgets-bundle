<?php

/*
Widget Name: SE Ticker Widget
Description: A horizontal text ticker.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class se_ticker_Widget extends SiteOrigin_Widget {

  function __construct() {
    parent::__construct(
        'se-ticker-widget',
        __('SE Ticker Widget', 'se-sopb-widgets'),
        array(
            'description' => __('A horizontal text ticker.', 'se-sopb-widgets'),
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

    wp_enqueue_style('flickity', 'https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.min.css', '', false);
    wp_enqueue_style('se-ticker', SPB_URL.'sopb-widgets/se-ticker/styles/se-ticker.css', '', SPB_VER);

    wp_enqueue_script('flickity', 'https://cdnjs.cloudflare.com/ajax/libs/flickity/3.0.0/flickity.pkgd.min.js', '', false, true);
    wp_enqueue_script('se-ticker', SPB_URL.'sopb-widgets/se-ticker/js/se-ticker.js', array('flickity'), SPB_VER, true);

    do_action('se_ticker_frontend_enqueue');

  }

  function modify_form($form) {
    return
      array(
        'text_lines' => array(
          'type' => 'textarea',
          'label' => __( 'Set ticker content here, one word/phrase per line.' , 'se-sopb-widgets' ),
          'rows' => 20
        )
      );
  }

  function get_template_variables($instance, $args) {

    if ( empty( $instance ) ) return array();

    return array(
      'lines' => $this->process_lines($instance['text_lines']),
    );

  }

  private function process_lines($textarea) {

    $lines = explode("\n", str_replace("\r", "", $textarea));
    $count = count($lines);

    // We need at least 20 items
    // because flickity behaves as a weirdo when (total elements width) < (viewport width)
    // NOTE: SplideJS handles this kind of transition withouth hacking with GSAP

    $min = 20;

    if($count < $min) {

      // Calculate number of cycles needed to match $min
      $cycle = ceil($min / $count);

      // Cycle
      for($i = 1; $i < $cycle; $i++) {
        // adding to new array to prevent double add over cycle
        $add[] = $textarea;
      }

      // Stringify array to longer textarea by adding \n where needed
      $add = implode("\n", $add);

      // Arraify our longer textarea
      $lines = explode("\n", str_replace("\r", "", $add));

    }

    return $lines;

  }

  private function process_link($url) {
    $explode = explode(' ', $url);
    return (isset($explode[1]) && is_numeric($explode[1])) ? get_the_permalink($explode[1]) : $url;
  }

  function get_template_name($instance) {
      return 'template';
  }

}

siteorigin_widget_register('se-ticker-widget', __FILE__, 'se_ticker_Widget');
