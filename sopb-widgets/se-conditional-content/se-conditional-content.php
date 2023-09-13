<?php

/*
Widget Name: SE Conditional Content Widget
Description: Displays a content contitionally.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Conditional_Content_Widget extends SiteOrigin_Widget {

    function __construct() {
      parent::__construct(
          'se-conditional-content-widget',
          __('SE Conditional Content Widget', 'se-sopb-widgets'),
          array(
              'description' => __('Displays a content contitionally.', 'se-sopb-widgets'),
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

      // Gets taxonomy objects and extracts the 'label' field from each one.
  		$taxonomies = wp_list_pluck( get_taxonomies( array(), 'objects' ), 'label' );
      $types = wp_list_pluck( get_post_types( array('public' => true), 'objects' ), 'label' );

      return
      array(
        'content_repeater' => array(
              'type' => 'repeater',
              'label' => __( 'Setup your content here. The widget will display one item per time, conditionally.' , 'se-sopb-widgets' ),
              'item_name'  => __( 'Content item', 'se-sopb-widgets' ),
              'item_label' => array(
                  'selector'     => "[id*='archive_singular']:checked",
                  'update_event' => 'change',
                  'value_method' => 'val'
              ),
              'fields' => array(
                'content' => array(
                    'type' => 'builder',
                    'label' => __( 'Content', 'se-sopb-widgets'),
                ),
                'archive_singular'    => array(
                    'type'    => 'radio',
                    'default' => 'archive',
                    'label'   => __( 'Display content on:', 'se-sopb-widgets' ),
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array( 'archive_singular_{$repeater}' )
                    ),
                    'options' => array(
                        'archive' => __( 'Post Archives', 'se-sopb-widgets' ),
                        'singular' => __( 'Singular Posts', 'se-sopb-widgets' ),
                    )
                ),
                'archive_type' => array(
                    'type'    => 'select',
                    'default' => 'all',
                    'state_handler' => array(
                        'archive_singular_{$repeater}[archive]' => array('show'),
                        'archive_singular_{$repeater}[singular][]' => array(
                                array('val', 'select', array('all')),
                                array('trigger', 'select', array('change')),
                                array('hide')
                          )
                    ),
                    'label'   => __( 'Select archive type', 'se-sopb-widgets' ),
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array( 'archive_type_{$repeater}' )
                    ),
                    'options' => array(
                        'all'      => __( 'All Archives', 'se-sopb-widgets' ),
                        'post_type' => __( 'Post Type Archive', 'se-sopb-widgets' ),
                        'taxonomy'      => __( 'Taxonomy Archive', 'se-sopb-widgets' ),
                        'term'      => __( 'Term Archive', 'se-sopb-widgets' ),
                    )
                ),
                'singular_type' => array(
                    'type'    => 'select',
                    'default' => '',
                    'state_handler' => array(
                        'archive_singular_{$repeater}[archive]' => array('hide'),
                        'archive_singular_{$repeater}[singular]' => array('show')
                    ),
                    'label'   => __( 'Match all posts or just a specific one?', 'se-sopb-widgets' ),
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array( 'singular_type_{$repeater}' )
                    ),
                    'options' => array(
                        ''      => __( 'Select', 'se-sopb-widgets' ),
                        'generic'      => __( 'All posts', 'se-sopb-widgets' ),
                        'specific' => __( 'A specific post', 'se-sopb-widgets' ),
                    )
                ),
                'post_id' => array(
                    'type' => 'link',
                    'readonly' => true,
                    'state_handler' => array(
                        'singular_type_{$repeater}[specific]' => array('show'),
                        '_else[singular_type_{$repeater}]' => array( 'hide' ),
                        'archive_singular_{$repeater}[archive]' => array('hide')
                    ),
                    'label' => __('Select a singular entry', 'widget-form-fields-text-domain'),
                    'default' => ''
                ),
                'post_type_singular' => array(
          				'type'    => 'select',
          				'label'   => __( 'Post Type', 'so-widgets-bundle' ),
                  'state_handler' => array(
                      'singular_type_{$repeater}[generic]' => array('show'),
                      '_else[singular_type_{$repeater}]' => array( 'hide' ),
                      'archive_singular_{$repeater}[archive]' => array('hide')
                  ),
          				'options' => $types,
          			),
                'post_type_archive' => array(
          				'type'    => 'select',
          				'label'   => __( 'Post Type', 'so-widgets-bundle' ),
                  'state_handler' => array(
                      'archive_type_{$repeater}[post_type]' => array('show'),
                      '_else[archive_type_{$repeater}]' => array( 'hide' ),
                      'archive_singular_{$repeater}[singular]' => array('hide')
                  ),
          				'options' => $types,
          			),
                'taxonomy'       => array(
          				'type'    => 'select',
          				'label'   => __( 'Select a taxonomy', 'so-widgets-bundle' ),
                  'state_handler' => array(
                      'archive_type_{$repeater}[taxonomy]' => array('show'),
                      '_else[archive_type_{$repeater}]' => array( 'hide' ),
                      'archive_singular_{$repeater}[singular]' => array('hide')
                  ),
          				'options' => $taxonomies,
          			),
                'terms' => array(
          				'type' => 'autocomplete',
          				'label' => __( 'Terms', 'so-widgets-bundle' ),
                  'state_handler' => array(
                      'archive_type_{$repeater}[term]' => array('show'),
                      '_else[archive_type_{$repeater}]' => array( 'hide' ),
                      'archive_singular_{$repeater}[singular]' => array('hide')
                  ),
          				'source' => 'terms',
          				'description' => __( 'Search terms through all registered taxonomies, such as categories, tags and custom ones.', 'so-widgets-bundle' ),
          			),
              )
          ),
      );
  	}

    function get_template_variables($instance, $args) {
      if ( empty( $instance ) ) return array();

  		return array(
  			'contents' => $instance['content_repeater']
  		);

  	}

    function get_template_name($instance) {
        return 'template';
    }

}

siteorigin_widget_register('se-conditional-content-widget', __FILE__, 'SE_Conditional_Content_Widget');
