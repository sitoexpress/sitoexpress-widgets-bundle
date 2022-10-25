<?php

/*
Widget Name: SE Post Carousel Widget
Description: Displays a post carousel.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Post_Carousel_Widget extends SiteOrigin_Widget {

  static $rendering_loop;

  static $current_loop_template;
  static $current_loop_instance;
  static $current_pagination_id;

  function __construct() {
    parent::__construct(
        'se-post-carousel-widget',
        __('SE Post Carousel Widget', 'se-sopb-widgets'),
        array(
            'description' => __('Displays a post carousel.', 'se-sopb-widgets'),
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

  /**
   * Are we currently rendering a post loop
   *
   * @return bool
   */
  static function is_rendering_loop() {
    return self::$rendering_loop;
  }

  /**
   * Which post loop is currently being rendered
   *
   * @return array
   */
  static function get_current_loop_template() {
    return self::$current_loop_template;
  }

  /**
   * Which post loop is currently being rendered
   *
   * @return array
   */
  static function get_current_loop_instance() {
    return self::$current_loop_instance;
  }

  /**
   * The pagination id used in custom format pagination links
   *
   * @return array
   */
  static function get_current_pagination_id() {
    return self::$current_pagination_id;
  }

  private static function is_legacy_widget_block_preview() {
  	return isset( $_GET['legacy-widget-preview'] ) && (
  		$_GET['legacy-widget-preview']['idBase'] == 'siteorigin-panels-postloop' ||
  		$_GET['legacy-widget-preview']['idBase'] == 'siteorigin-panels-builder'
  	);
  }

  private static function is_layout_block_preview() {
  	return isset( $_POST['action'] ) && $_POST['action'] == 'so_panels_layout_block_preview';
  }

  /**
   * Get all the existing files
   *
   * @return array
   */

  function get_loop_templates(){

  	$templates = array();

  	$template_files = array(
  		'loop*.php',
  		'*/loop*.php',
  		'content*.php',
  		'*/content*.php',
  	);

  	$template_dirs = array( get_template_directory(), get_stylesheet_directory() );
  	$template_dirs = apply_filters( 'siteorigin_panels_postloop_template_directory', $template_dirs );
  	$template_dirs = array_unique( $template_dirs );

  	foreach( $template_dirs  as $dir ){
  		foreach( $template_files as $template_file ) {
  			foreach( (array) glob($dir.'/'.$template_file) as $file ) {
  				if( file_exists( $file ) ) $templates[] = str_replace($dir.'/', '', $file);
  			}
  		}
  	}

  	$templates = array_unique( apply_filters( 'siteorigin_panels_postloop_templates', $templates ) );
  	$templates = array_filter( $templates, array($this, 'validate_template_file') );
    $template_options = array();

		if( ! empty( $templates ) ) {
			foreach( $templates as $template ) {

        // Is this template being added by a plugin?
				$filename = SE_Post_Carousel_Widget::locate_template( $template );

        // Is it a content template?
        if(!preg_match( '/\/content*/', '/' . $template)) continue;

				$headers = get_file_data( $filename, array(
					'loop_name' => 'Loop Name',
          'carousel_compat' => 'Carousel Compatible',
				) );

        // Is it carousel compatible?
        if(!$headers['carousel_compat']) continue;

        $template_options[ $template ] = esc_html( ! empty( $headers['loop_name'] ) ? $headers['loop_name'] : $template );
			}
		}

    $template_options['default'] = 'Default Template';

  	return $template_options;

  }

  /**
   * Checks if a template file is valid
   *
   * @param $filename
   *
   * @return bool
   */
  public function validate_template_file( $filename )
  {
  	return (
  		// File is a valid PHP file
  		validate_file( $filename ) == 0 &&
  		substr( $filename, -4 ) == '.php' &&

  		// And it exists
  		self::locate_template( $filename ) != ''
  	);
  }

  /**
   * Find the location of a given template. Either in the theme or in the plugin directory.
   *
   * @param $template_names
   * @param bool $load
   * @param bool $require_once
   *
   * @return string The template location.
   */
  public static function locate_template( $template_names, $load = false, $require_once = true )
  {
    $located = '';

    foreach ( (array) $template_names as $template_name ) {

      $located = locate_template($template_name, false);

      if ( ! $located && file_exists( WP_PLUGIN_DIR . '/' . $template_name ) ) {
        // Template added by a plugin
        $located = WP_PLUGIN_DIR . '/' . $template_name;
      }
    }

    if ( $load && '' != $located ) {
      load_template( $located, $require_once );
    }

    return $located;
  }

  function wrapper_class_filter( $classes, $instance ) {
    if(!isset($instance['template'])) return;
    return $classes;
  }

  function wrapper_data_filter( $data, $instance ) {
    return $data;
  }

  function enqueue_widget_scripts( $instance ) {
    wp_enqueue_script('slick-carousel','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery','sauce-script'), SPB_VER, true);
    wp_enqueue_style('slick-carousel','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', '', true);
    wp_enqueue_style('slick-custom', SPB_URL.'sopb-widgets/se-post-carousel/styles/se-carousel.css', array('slick-carousel'), SPB_VER);
    wp_add_inline_script( 'slick-carousel', '$(".so-widget-se-post-carousel-widget .se-carousel").each(function() { $(this).slick() });' );
    do_action('se_post_carousel_frontend_enqueue');
  }

  function modify_form($form) {
    $templates = $this->get_loop_templates();
    return
      array(
        'template' => array(
          'type' => 'select',
          'label' => __( 'Choose an available template', 'se-sopb-widget' ),
          'default' => 'default',
          'options' => $templates,
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
                      'min' => 100,
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
                'options' => array(
                    'type' => 'checkboxes',
                    'label' => __( 'Setup', 'se-sopb-widget' ),
                    'options' => array(
                        'arrows' => __( 'Navigation arrows', 'se-sopb-widget' ),
                        'dots' => __( 'Navigation dots', 'se-sopb-widget' ),
                        'infinite' =>  __( 'Infinite', 'se-sopb-widget' ),
                        'autoplay' =>  __( 'Autoplay', 'se-sopb-widget' ),
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
        'query' => array(
              'type' => 'posts',
              'label' => __('Post Query', 'se-sopb-widget'),
          ),
    		);
  }

  function get_template_variables($instance, $args) {
    if ( empty( $instance ) ) return array();

    return array(
      'template' => (isset($instance['template'])) ? $instance['template'] : 'default',
      'query' => $this->process_pseudo_query($instance),
      'settings' => $this->process_carousel_settings($instance),
      'classes' => $this->process_classes($instance),
    );

  }

  private function process_classes($instance) {
     $classes = (isset($instance['settings']['classes'])) ? $instance['settings']['classes'] : '';
     $classes .= (isset($instance['settings']['options']) && !in_array('arrows', $instance['settings']['options'])) ? ' no-arrows' : '';
     return $classes;
  }

  private function process_carousel_settings($instance) {
    $settings = $instance['settings'];
    $setup = array(
      'slidesToShow' => (isset($settings['slidesToShow_desktop'])) ? $settings['slidesToShow_desktop'] : 3,
      'slidesToScroll' => 1,
      'speed' => (isset($settings['speed'])) ? $settings['speed'] : 5000,
      'autoplaySpeed' => (isset($settings['autoplaySpeed'])) ? $settings['autoplaySpeed'] : 0,
      'dots' => (isset($settings['options']) && in_array('dots', $settings['options'])) ? true : false,
      'arrows' => (isset($settings['options']) && in_array('arrows', $settings['options'])) ? true : false,
      'infinite' => (isset($settings['options']) && in_array('infinite', $settings['options'])) ? true : false,
      'autoplay' => (isset($settings['options']) && in_array('autoplay', $settings['options'])) ? true : false,
      'rtl' => (isset($settings['options']) && in_array('rtl', $settings['options'])) ? true : false,
      'responsive' => array(
          array(
            'breakpoint' => (isset($settings['breakpoint'])) ? $settings['breakpoint'] : 810,
            'settings' => array(
                'slidesToShow' => (isset($settings['slidesToShow_mobile'])) ? $settings['slidesToShow_mobile'] : 1,
                'slidesToScroll' => 1
              ),
          ),
      ),
    );
    return $setup;
  }

  function get_template_name($instance) {
      return 'template';
  }

  private function process_pseudo_query($instance) {

    if(!isset($instance['query'])) return;

    $query_args = $instance;

    $query_args = siteorigin_widget_post_selector_process_query($instance['query']);
    $query_args['additional'] = empty($instance['additional']) ? array() : $instance['additional'];

    $query_args = wp_parse_args($query_args['additional'], $query_args);
		unset($query_args['additional']);

    // Exclude the current post to prevent possible infinite loop

    global $siteorigin_panels_current_post;

    if( !empty($siteorigin_panels_current_post) ){
      if( !empty( $query_args['post__not_in'] ) ){
        if( !is_array( $query_args['post__not_in'] ) ){
          $query_args['post__not_in'] = explode( ',', $query_args['post__not_in'] );
          $query_args['post__not_in'] = array_map( 'intval', $query_args['post__not_in'] );
        }
        if(is_numeric($siteorigin_panels_current_post))
        $query_args['post__not_in'][] = $siteorigin_panels_current_post;
      }
      else {
        if(is_numeric($siteorigin_panels_current_post))
        $query_args['post__not_in'] = array( $siteorigin_panels_current_post );
      }
    }

    if( !empty($query_args['post__in']) && !is_array($query_args['post__in']) ) {
      $query_args['post__in'] = explode(',', $query_args['post__in']);
      $query_args['post__in'] = array_map('intval', $query_args['post__in']);
    }

    return $query_args;

  }

}

siteorigin_widget_register('se-post-carousel-widget', __FILE__, 'SE_Post_Carousel_Widget');
