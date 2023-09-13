<?php

/*
Widget Name: SE Image Carousel Widget
Description: Displays an Image carousel.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Image_Carousel_Widget extends SiteOrigin_Widget {

  function __construct() {
    parent::__construct(
        'se-image-carousel-widget',
        __('SE Image Carousel Widget', 'se-sopb-widgets'),
        array(
            'description' => __('Displays an image carousel.', 'se-sopb-widgets'),
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
    sewb_splide_enqueue($instance);
  }

  function modify_form($form) {
    return
      array(
        'title' => array(
            'type' => 'text',
            'label' => __( 'Enter here a title for the slider (needed, but not shown)', 'se-sopb-widgets' )
        ),
        'slides' => array(
          'type' => 'repeater',
          'label' => __( 'Slides' , 'se-sopb-widget' ),
          'item_name'  => __( 'Slide', 'se-sopb-widget' ),
          'fields' => array(
            'image' => array(
                'type' => 'media',
                'library' => 'image',
                'label' => __('Set the image for the slide', 'se-sopb-widgets' ),
            ),
            'image_size' => array(
                'type' => 'image-size',
                'label' => __( 'Image size', 'se-sopb-widgets' )
            ),
            'text' => array(
                'type' => 'tinymce',
                'label' => __('Text below image', 'se-sopb-widgets'),
                'media_buttons' => false,
                'rows' => 5,
                'description' => __('Leave empty if not needed', 'se-sopb-widgets'),
                'default' => '',
                'button_filters' => array(
                  'mce_buttons' => array( $this, 'filter_mce_buttons' ),
                  'mce_buttons_2' => array( $this, 'filter_mce_buttons' )
              ),
            ),
            'url' => array(
                'type' => 'link',
                'label' => __('Link', 'se-sopb-widgets'),
                'description' => __('If the image should link to something, select content or paste URL', 'se-sopb-widgets')
            ),
            'url_target' => array(
                'type' => 'checkbox',
                'label' => __( 'URL opens in new window?', 'se-sopb-widgets' ),
                'default' => false
            ),
          ),
        ),
        'settings' => array(
            'type' => 'section',
            'label' => __( 'Carousel Settings.' , 'se-sopb-widget' ),
            'hide' => true,
            'fields' => array(
              'slidesToShow_desktop' => array(
                    'type' => 'slider',
                    'label' => __( 'Slides to show (desktop)', 'se-sopb-widget' ),
                    'default' => 3,
                    'min' => 1,
                    'max' => 6,
                    'step' => 1,
                    'integer' => true
                ),
                'slidesToShow_mobile' => array(
                      'type' => 'slider',
                      'label' => __( 'Slides to show (tablet/mobile)', 'se-sopb-widget' ),
                      'default' => 1,
                      'min' => 1,
                      'max' => 6,
                      'step' => 1,
                      'integer' => true
                ),
                'breakpoint' => array(
                      'type' => 'slider',
                      'label' => __( 'Mobile breakpoint', 'se-sopb-widget' ),
                      'default' => 810,
                      'min' => 320,
                      'max' => 1080,
                      'step' => 10,
                      'integer' => true
                ),
                'speed' => array(
                      'type' => 'slider',
                      'label' => __( 'Carousel speed', 'se-sopb-widget' ),
                      'default' => 5000,
                      'min' => 0,
                      'max' => 15000,
                      'step' => 100,
                      'integer' => true
                ),
                'autoplaySpeed' => array(
                      'type' => 'slider',
                      'label' => __( 'Autoplay interval time', 'se-sopb-widget' ),
                      'description' => __('It\'s the pause between a slide change. Used only if autoplay is enabled.', 'se-sopb-widget'),
                      'default' => 2000,
                      'min' => 0,
                      'max' => 15000,
                      'step' => 100,
                      'integer' => true
                ),
                'autoscrollSpeed' => array(
                      'type' => 'slider',
                      'label' => __( 'Autoscroll Speed', 'se-sopb-widget' ),
                      'description' => __('Sets autoscroll speed, if enabled in Setup.', 'se-sopb-widget'),
                      'default' => 10,
                      'min' => 5,
                      'max' => 20,
                      'step' => 1,
                      'integer' => true
                ),
                'options' => array(
                    'type' => 'checkboxes',
                    'label' => __( 'Setup', 'se-sopb-widget' ),
                    'options' => array(
                        'arrows' => __( 'Navigation arrows', 'se-sopb-widget' ),
                        'dots' => __( 'Navigation dots', 'se-sopb-widget' ),
                        'rewind' =>  __( 'Rewind', 'se-sopb-widget' ),
                        'infinite' =>  __( 'Infinite (disables Rewind)', 'se-sopb-widget' ),
                        'autoplay' =>  __( 'Autoplay', 'se-sopb-widget' ),
                        'autoscroll' => __( 'Autoscroll (disables Autoplay)', 'se-sopb-widget' ),
                        'focus_center' => __( 'Focus Center', 'se-sopb-widget' ),
                        'dont_pauseOnHover' => __( 'Do not pause on Hover', 'se-sopb-widget' ),
                        'dont_pauseOnFocus' => __( 'Do not pause on Click/Focus', 'se-sopb-widget' ),
                        'rtl' => __( 'Inverse direction (RTL)', 'se-sopb-widget' )
                    ),
                ),
                'classes' => array(
                  'type' => 'text',
                  'label' => __( 'Set additional carousel wrapper classes (space separated)', 'se-sopb-widget' ),
                  'description' => __( 'Added to the actual carousel.', 'se-sopb-widget' ),
                ),
            ),
        ),
    		);
  }

  public function filter_mce_buttons( $buttons ) {
    //Add style selector to the beginning of the toolbar
    $buttons = array_diff($buttons, array("link", "strikethrough", "wp_more", "bullist", "numlist", "blockquote", "hr", "openlink", "unlink", "indent", "outdent"));
    return $buttons;
	 }

  function get_template_variables($instance, $args) {
    if ( empty( $instance ) ) return array();

    return array(
      'slides' => $instance['slides'],
      'net_interval' => $instance['settings']['autoplaySpeed'],
      'settings' => $this->process_carousel_settings($instance),
      'classes' => $this->process_classes($instance),
    );

  }

  private function process_classes($instance) {
     $classes = (isset($instance['settings']['classes'])) ? $instance['settings']['classes'] : '';
     $classes .= (isset($instance['settings']['options']) && !in_array('arrows', $instance['settings']['options'])) ? ' no-arrows' : '';
     $classes .= (isset($instance['template']) && $instance['template'] == 'default') ? ' carousel-default' : '';
     return $classes;
  }

  private function process_carousel_settings($instance) {
    $settings = $instance['settings'];

    $breakpoint = (isset($settings['breakpoint'])) ? $settings['breakpoint'] : 810;
    $rewind = (isset($settings['options']) && in_array('rewind', $settings['options']) && !in_array('infinite', $settings['options'])) ? true : false;

    $setup = array(
      'type' => (isset($settings['options']) && in_array('infinite', $settings['options'])) ? 'loop' : 'slide',
      'rewind' => $rewind,
      'rewindByDrag' => $rewind,
      'perPage' => (isset($settings['slidesToShow_desktop'])) ? $settings['slidesToShow_desktop'] : 3,
      'perMove' => 1,
      'easing' => 'ease',
      'pauseOnHover' => (isset($settings['options']) && in_array('dont_pauseOnFocus', $settings['options'])) ? false : true,
      'pauseOnFocus' => (isset($settings['options']) && in_array('dont_pauseOnFocus', $settings['options'])) ? false : true,
      'speed' => (isset($settings['speed'])) ? $settings['speed'] : 5000,
      'trimSpace' => (isset($settings['options']) && in_array('focus_center', $settings['options'])) ? false : true,
      'focus' => (isset($settings['options']) && in_array('focus_center', $settings['options'])) ? 'center' : 0,
      'direction' => (isset($settings['options']) && in_array('rtl', $settings['options'])) ? 'rtl' : 'ltr',
      'pagination' => (isset($settings['options']) && in_array('dots', $settings['options'])) ? true : false,
      'arrows' => (isset($settings['options']) && in_array('arrows', $settings['options'])) ? true : false,
      'breakpoints' => array(
        $breakpoint => array(
            'perPage' => (isset($settings['slidesToShow_mobile'])) ? $settings['slidesToShow_mobile'] : 1,
            'perMove' => 1
          ),
      ),
    );

    $autoscroll = (isset($settings['options']) && in_array('autoscroll', $settings['options'])) ? true : false;
    $autoplay = (isset($settings['options']) && in_array('autoplay', $settings['options'])) ? true : false;

    if($autoscroll) {
      $setup['drag'] = 'free';
      $setup['autoScroll'] = array(
        'speed' => (isset($settings['autoscrollSpeed'])) ? $settings['autoscrollSpeed']/10 : 1,
        'autoStart' => false,
        'rewind' => $rewind,
        'pauseOnHover' => (isset($settings['options']) && in_array('dont_pauseOnFocus', $settings['options'])) ? false : true,
        'pauseOnFocus' => (isset($settings['options']) && in_array('dont_pauseOnFocus', $settings['options'])) ? false : true,
      );
      $setup['intersection']['inView']['autoScroll'] = true;
      $setup['intersection']['outView']['autoScroll'] = false;
    } else {
      $setup['autoScroll'] = false;
    }

    if(!$autoscroll && $autoplay) {

      $interval = (isset($settings['autoplaySpeed'])) ? $settings['speed'] + $settings['autoplaySpeed'] : 0;
      $setup['interval'] = $interval;
      $setup['autoplay'] = $autoplay;

    }

    return $setup;
  }

  function get_template_name($instance) {
      return 'template';
  }

}

siteorigin_widget_register('se-image-carousel-widget', __FILE__, 'SE_Image_Carousel_Widget');
