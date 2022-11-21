<?php

/*
Widget Name: SE Random Content Widget
Description: Displays a random header.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Random_Content_Widget extends SiteOrigin_Widget {

    function __construct() {
      parent::__construct(
          'se-random-content-widget',
          __('SE Random Content Widget', 'se-sopb-widgets'),
          array(
              'description' => __('Displays a random content.', 'se-sopb-widgets'),
          ),
          array(
          ),
          false,
          plugin_dir_path(__FILE__)
      );
    }

    function initialize() {
      add_action( 'siteorigin_widgets_enqueue_frontend_scripts_' . $this->id_base, array( $this, 'enqueue_widget_scripts' ) );
      add_filter( 'siteorigin_widgets_wrapper_classes_' . $this->id_base, array( $this, 'wrapper_class_filter' ), 10, 2 );
      add_filter( 'siteorigin_widgets_wrapper_data_' . $this->id_base, array( $this, 'wrapper_data_filter' ), 10, 2 );
    }

    function wrapper_class_filter( $classes, $instance ) {
      if ( ! empty( $instance[ 'fittext' ] ) ) {
        $classes[] = 'so-widget-fittext-wrapper';
      }
      return $classes;
    }

    function wrapper_data_filter( $data, $instance ) {
      if ( ! empty( $instance['fittext'] ) ) {
        $data['fit-text-compressor'] = $instance['fittext_compressor'];
      }
      return $data;
    }

    function enqueue_widget_scripts( $instance ) {
      if ( ! empty( $instance['fittext'] ) || $this->is_preview( $instance ) ) {
        wp_enqueue_script( 'sowb-fittext' );
      }
    }

    function get_widget_form() {
      return
      array(
        'content_repeater' => array(
              'type' => 'repeater',
              'label' => __( 'Setup your content here. The widget will display one item per time, randomly.' , 'se-sopb-widgets' ),
              'item_name'  => __( 'Content item', 'se-sopb-widgets' ),
              'fields' => array(
                'editor_1' => array(
                      'type' => 'tinymce',
                      'label' => __( 'Top content', 'se-sopb-widgets' ),
                      'rows' => 10,
                      'default_editor' => 'tmce'
                  ),
                  'editor_2' => array(
                      'type' => 'tinymce',
                      'label' => __( 'Bottom content', 'se-sopb-widgets' ),
                      'rows' => 10,
                      'default_editor' => 'tmce'
                  ),
              )
          ),
          'fittext' => array(
    				'type' => 'checkbox',
    				'label' => __( 'Use FitText', 'so-widgets-bundle' ),
    				'description' => __( 'Dynamically adjust your heading font size based on screen size.', 'so-widgets-bundle' ),
    				'default' => false,
    				'state_emitter' => array(
    					'callback' => 'conditional',
    					'args'     => array(
    						'use_fittext[show]: val',
    						'use_fittext[hide]: ! val'
    					),
    				)
    			),
    			'fittext_compressor' => array(
    				'type' => 'number',
    				'label' => __( 'FitText Compressor Strength', 'so-widgets-bundle' ),
    				'description' => __( 'The higher the value, the more your Content will be scaled down. Values above 1 are allowed.', 'so-widgets-bundle' ),
    				'default' => 0.85,
    				'step' => 0.01,
    				'state_handler' => array(
    					'use_fittext[show]' => array( 'show' ),
    					'use_fittext[hide]' => array( 'hide' ),
    				)
    			),
      );
  	}

    function get_template_variables($instance, $args) {
      if ( empty( $instance ) ) return array();

      $max = count($instance['content_repeater']);
      $rand = mt_rand(0, $max - 1);

  		return array(
  			'contents' => $instance['content_repeater'][$rand]
  		);

  	}

    function get_template_name($instance) {
        return 'template';
    }

}

siteorigin_widget_register('se-random-content-widget', __FILE__, 'SE_Random_Content_Widget');
