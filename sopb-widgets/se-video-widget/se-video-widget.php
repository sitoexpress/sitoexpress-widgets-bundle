<?php

/*
Widget Name: SE Video Player & Background Widget
Description: Plays YT/Vimeo video or sets a Youtube video as a SOPB row background.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Video_Widget extends SiteOrigin_Widget {

    function __construct() {
      parent::__construct(
          'se-video-widget',
          __('SE Video Player & Background Widget', 'se-video-widget-text-domain'),
          array(
              'description' => __('Plays YT video or sets a Youtube video as a SOPB row background.', 'se-video-widget-text-domain'),
          ),
          array(
          ),
          $this->get_widget_form(),
          plugin_dir_path(__FILE__)
      );
    }

    function get_widget_form() {
  		return array(
  			'title'     => array(
  				'type'  => 'text',
  				'label' => __( 'Title', 'se-video-widget-text-domain' )
  			),
        'url'     => array(
  				'type'  => 'text',
  				'label' => __( 'Youtube/Vimeo Video Url', 'se-video-widget-text-domain' ),
          'description'   => __( 'Paste here the Url to the Youtube/Vimeo video.', 'se-video-widget-text-domain' )
  			),
        'cover' => array(
          'type' => 'media',
          'label' => __( 'Choose a cover for the video', 'se-video-widget-text-domain' ),
          'choose' => __( 'Choose cover', 'se-video-widget-text-domain' ),
          'update' => __( 'Set cover', 'se-video-widget-text-domain' ),
          'library' => 'image',
          'fallback' => true
        ),
        'settings' => array(
  				'type'          => 'checkboxes',
  				'label'         => __( 'Settings', 'se-video-widget-text-domain' ),
  				'options'       => array(
  					'autoplay'      => __( 'Autoplay<sup>(1)(2)</sup>', 'se-video-widget-text-domain' ),
            'muted'          => __('Muted', 'se-video-widget-text-domain' ),
            'no_controls'   => __( 'Hide controls<sup>(3)</sup>', 'se-video-widget-text-domain' ),
            'loop'          => __( 'Loop<sup>(4)</sup>', 'se-video-widget-text-domain' ),
  					'background'    => __( 'Row Background (Not yet implemented)<sup>(2)(3)(4)</sup>', 'se-video-widget-text-domain' ),
				   ),
           'description'   => __( '<strong>Notes:</strong><br/>(1) Videos will be muted if Autoplay is checked.<br/>(2) Autoplay works best on Vimeo. Youtube videos may be blocked by Firefox even if muted.<br/>(3) Player controls will be hidden automatically for background videos.<br/>(4) Background videos loop automatically and they work best using Vimeo.', 'se-video-widget-text-domain' )
         ),
      );
  	}

    function enqueue_frontend_scripts( $instance ) {
      /* here for reference only
  		$video_host = empty( $instance['host_type'] ) ? '' : $instance['host_type'];
  		if ( $video_host == 'external' ) {
  			$video_host = ! empty( $instance['video']['external_video'] ) ? $this->get_host_from_url( $instance['video']['external_video'] ) : '';
  		} */
      wp_enqueue_script('plyr','https://cdnjs.cloudflare.com/ajax/libs/plyr/3.7.2/plyr.min.js', array('jquery','sauce-script'), SPB_VER, true);
      wp_enqueue_style('plyr','https://cdnjs.cloudflare.com/ajax/libs/plyr/3.7.2/plyr.css', '', true);
      wp_enqueue_script('se-video', SPB_URL.'sopb-widgets/se-video-widget/js/se-video.js', array('plyr'), SPB_VER, true);
      wp_enqueue_style('se-video', SPB_URL.'sopb-widgets/se-video-widget/styles/se-video.css', array('plyr'), SPB_VER);
      do_action('se_video_frontend_enqueue');

  		parent::enqueue_frontend_scripts( $instance );
  	}

    function get_template_variables($instance, $args) {
  		static $player_id = 1;

      $source = $this->get_host_from_url($instance['url']);
      if(!$instance['settings']) $instance['settings'] = array();

  		$return = array(
  			'player_id'  => 'se-video-' . ( $player_id ++ ),
        'title'      => $instance['title'],
  			'url'        => $this->format_url($instance['url'], $source),
        'settings'   => $this->format_settings($instance['settings']),
        'plyrconfig' => $this->format_plyrconfig($instance, $source),
        'cover'      => $instance['cover'],
        'source'     => $source
  		);

  		return $return;
  	}

    private function format_url($url, $source) {
      if($source != 'vimeo') {
        $parsed = parse_url($url);
        if(isset($parsed['query'])) {
          parse_str($parsed['query'], $output);
          $url = "https://www.youtube-nocookie.com/embed/".$output['v'];
        } else {
          $url = "https://www.youtube-nocookie.com/embed/".basename($url);
        }
      } else {
        $url = "https://player.vimeo.com/video/".basename($url);
      }
      return $url;
    }

    private function format_plyrconfig($instance, $source) {
      $plyrconfig = array(
        'controls'    => array('play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'pip', 'airplay', 'fullscreen'),
        'autoplay'    => (in_array('autoplay', $instance['settings'])) ? true : false,
        'muted'       => (in_array('muted', $instance['settings']) || in_array('background', $instance['settings']) || in_array('autoplay', $instance['settings'])) ? true : false,
        'volume'      => (in_array('muted', $instance['settings']) || in_array('background', $instance['settings']) || in_array('autoplay', $instance['settings'])) ? '0' : '1',
        'loop'        => (in_array('loop', $instance['settings']) || in_array('background', $instance['settings'])) ? array('active' => true) : array('active' => false),
        'playsinline' => true,
        'clickToPlay' => (in_array('no_controls', $instance['settings'])) ? false : true,
        'storage'     => array('enabled' => false),
        'poster'      => (isset($instance['cover']) && !empty($instance['cover'])) ? esc_html(wp_get_attachment_image_src($instance['cover'], 'big-800')[0]) : '',
        'resetOnEnd'  => (empty($instance['settings']['autoplay']) && empty($instance['settings']['background'])) ? true : false
      );
      if($source == 'youtube') {
        $plyrconfig['youtube'] = array(
          'noCookie' => true,
          'rel' => 0,
          'showinfo' => 0,
          'iv_load_policy' => 3,
          'modestbranding' => 1,
          'controls' => (in_array('no_controls', $instance['settings'])) ? 0 : 2,
        );
      }
      if($source == 'vimeo') {
        $plyrconfig['vimeo'] = array(
          'dnt' => true,
        );
      }
      return $plyrconfig;
    }

    private function format_settings($settings) {
      $return = array();
      if(!empty($settings)){
        foreach($settings as $key => $value) {
          $return[$value] = 1;
        }
      }
      return $return;
    }

    /**
     * Get the video host from the URL - taken from SOPB Video Widget
     *
     * @param $video_url
     *
     * @return string
     */
    private function get_host_from_url( $video_url ) {
      preg_match( '/https?:\/\/(www.)?([A-Za-z0-9\-]+)\./', $video_url, $matches );

      return ( ! empty( $matches ) && count( $matches ) > 2 ) ? $matches[2] : '';
    }

    function get_template_name($instance) {
        return 'se-video-template';
    }

    function get_style_name($instance) {
        return 'se-video-widget-style';
    }

}

siteorigin_widget_register('se-video-widget', __FILE__, 'SE_Video_Widget');
