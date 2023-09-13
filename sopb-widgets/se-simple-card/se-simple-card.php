<?php

/*
Widget Name: SE Simple Card Widget
Description: A simple content card with image, text, button and color.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class Se_Simple_Card_Widget extends SiteOrigin_Widget {

  function __construct() {
    parent::__construct(
        'se-simple-card-widget',
        __('SE Simple Card', 'se-sopb-widgets'),
        array(
            'description' => __('A simple content card with image, text, button and color.', 'se-sopb-widgets'),
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

    wp_enqueue_style('se-simple-card', SPB_URL.'sopb-widgets/se-simple-card/styles/se-simple-card.css', '', SPB_VER);

    do_action('se_simple_card_frontend_enqueue');

  }

  function modify_form($form) {
    return
      array(
        'image_section' => array(
            'type' => 'section',
            'label' => __( 'Image Section' , 'se-sopb-widgets' ),
            'hide' => true,
            'fields' => array(
                'image' => array(
                    'type' => 'media',
                    'label' => __( 'Select an image', 'se-sopb-widgets' ),
                    'choose' => __( 'Select an image', 'se-sopb-widgets' ),
                    'update' => __( 'Set image', 'se-sopb-widgets' ),
                    'library' => 'image',
                    'fallback' => true
                ),
                'image_size' => array(
                    'type' => 'image-size',
                    'label' => __( 'Image size', 'se-sopb-widgets' )
                ),
            )
        ),
        'text_section_1' => array(
            'type' => 'section',
            'label' => __( 'Text Section 1' , 'se-sopb-widgets' ),
            'hide' => true,
            'fields' => array(
                'h_' => array(
                    'type' => 'select',
                    'label' => __( 'Text Type', 'se-sopb-widgets' ),
                    'options' => array(
                      'p' => 'Paragraph',
                      'h1' => 'H1',
                      'h2' => 'H2',
                      'h3' => 'H3',
                      'h4' => 'H4',
                    ),
                ),
                'text' => array(
                    'type' => 'text',
                    'label' => __( 'Write here', 'se-sopb-widgets' )
                ),
                'text_align' => array(
                    'type' => 'select',
                    'label' => __('Text Alignment', 'se-sopb-widgets'),
                    'default' => 'text-left',
                    'options' => array(
                        'text-left' => __( 'Left', 'se-sopb-widgets' ),
                        'text-center' => __( 'Center', 'se-sopb-widgets' ),
                        'text-right' => __( 'Right', 'se-sopb-widgets' ),
                    )
                ),
            )
        ),
        'text_section_2' => array(
            'type' => 'section',
            'label' => __( 'Text Section 2' , 'se-sopb-widgets' ),
            'hide' => true,
            'fields' => array(
                'h_' => array(
                    'type' => 'select',
                    'label' => __( 'Text Type', 'se-sopb-widgets' ),
                    'options' => array(
                      'p' => 'Paragraph',
                      'h1' => 'H1',
                      'h2' => 'H2',
                      'h3' => 'H3',
                      'h4' => 'H4',
                    ),
                ),
                'text' => array(
                    'type' => 'text',
                    'label' => __( 'Write here', 'se-sopb-widgets' )
                ),
                'text_align' => array(
                    'type' => 'select',
                    'label' => __('Text Alignment', 'se-sopb-widgets'),
                    'default' => 'text-left',
                    'options' => array(
                        'text-left' => __( 'Left', 'se-sopb-widgets' ),
                        'text-center' => __( 'Center', 'se-sopb-widgets' ),
                        'text-right' => __( 'Right', 'se-sopb-widgets' ),
                    )
                ),
            )
        ),
        'button_section' => array(
            'type' => 'section',
            'label' => __( 'Link/Popup Section' , 'se-sopb-widgets' ),
            'hide' => true,
            'fields' => array(
                'action' => array(
                  'type'    => 'radio',
                  'default' => 'url',
                  'label'   => __( 'Button opens:', 'se-sopb-widgets' ),
                  'state_emitter' => array(
                      'callback' => 'select',
                      'args' => array( 'action' )
                  ),
                  'options' => array(
                      'url' => __( 'A link to another web page', 'se-sopb-widgets' ),
                      'popup'      => __( 'A popup', 'se-sopb-widgets' ),
                  )
                ),
                'url' => array(
                    'type' => 'link',
                    'label' => __('Button URL', 'se-sopb-widgets'),
                    'description' => __('Select content or paste URL', 'se-sopb-widgets'),
                    'state_handler' => array(
                        'action[url]' => array('show'),
                        'action[popup]' => array('hide'),
                    ),
                ),
                'button' => array(
                    'type' => 'text',
                    'label' => __('Button text', 'se-sopb-widgets'),
                    'default' => '',
                    'state_handler' => array(
                        'action[url]' => array('show'),
                        'action[popup]' => array('hide'),
                    ),
                ),
                'button_align' => array(
                    'type' => 'select',
                    'label' => __('Button Alignment', 'se-sopb-widgets'),
                    'default' => 'text-left',
                    'options' => array(
                        'text-left' => __( 'Left', 'se-sopb-widgets' ),
                        'text-center' => __( 'Center', 'se-sopb-widgets' ),
                        'text-right' => __( 'Right', 'se-sopb-widgets' ),
                    ),
                    'state_handler' => array(
                        'action[url]' => array('show'),
                        'action[popup]' => array('hide'),
                    ),
                ),
                'popup' => array(
                    'type' => 'widget',
                    'label' => __('Popup Settings', 'se-sopb-widgets'),
                    'class' => 'SE_Popup_Widget',
                    'state_handler' => array(
                        'action[url]' => array('hide'),
                        'action[popup]' => array('show'),
                    ),
                ),
            )
        ),
        'color' => array(
            'type' => 'color',
            'label' => __('Set the card background color', 'se-sopb-widgets' ),
        ),
        'order' => array(
            'type' => 'order',
            'label' => __( 'Element Order', 'se-sopb-widgets' ),
            'options' => array(
                'image_section' => __( 'Image', 'se-sopb-widgets' ),
                'text_section_1' => __( 'Text 1', 'se-sopb-widgets' ),
                'text_section_2' => __( 'Text 2', 'se-sopb-widgets' ),
                'button_section' => __( 'Button', 'se-sopb-widgets' ),
            ),
            'default' => array( 'image_section', 'text_section_1', 'text_section_2', 'button_section' ),
        ),
      );
  }

  function get_template_variables($instance, $args) {

    if ( empty( $instance ) ) return array();

    return array(
      'order' => $instance['order'],
      'color' => $instance['color'],
      'url' => isset($instance['button_section']['url']) ? $this->process_link($instance['button_section']['url']) : ''
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

siteorigin_widget_register('se-simple-card-widget', __FILE__, 'Se_Simple_Card_Widget');
