<?php

/*
Widget Name: SE Popup Widget
Description: Creates a popup using a button and a TinyMCE Editor
Author: Francesco Fortino
Author URI: https://sito.express
*/

class SE_Popup_Widget extends SiteOrigin_Widget {

    function __construct() {
      parent::__construct(
          'se-popup-widget',
          __('SE Popup Widget', 'se-popup-widget-text-domain'),
          array(
              'description' => __('Create a popup by defining a trigger button and setting content in a TinyMCE Editor.', 'se-popup-widget-text-domain'),
          ),
          array(
          ),
          '',
          plugin_dir_path(__FILE__)
      );
    }

    function modify_form($form) {
      return array(
        'content_type' => array(
          'type'    => 'radio',
          'default' => 'tinymce',
          'label'   => __( 'Content Type', 'se-popup-widget-text-domain' ),
          'state_emitter' => array(
              'callback' => 'select',
              'args' => array( 'content_type' )
          ),
          'options' => array(
              'tinymce' => __( 'Classic Editor', 'siteorigin-widgets' ),
              'layout'      => __( 'Layout Builder', 'siteorigin-widgets' ),
          )
        ),
        'content' => array(
              'type' => 'tinymce',
              'label' => __( 'Popup content', 'se-popup-widget-text-domain' ),
              'default' => '',
              'rows' => 10,
              'default_editor' => 'tmce',
              'state_handler' => array(
                  'content_type[tinymce]' => array('show'),
                  'content_type[layout]' => array('hide'),
              ),
          ),
          'advanced_content' => array(
              'type' => 'builder',
              'label' => __( 'Advanced popup content', 'se-popup-widget-text-domain' ),
              'state_handler' => array(
                  'content_type[tinymce]' => array('hide'),
                  'content_type[layout]' => array('show'),
              ),
          ),
          'fullscreen' => array(
              'type' => 'checkbox',
              'label' => __( 'Fullscreen popup?', 'widget-form-fields-text-domain' ),
              'default' => false
          ),
          'trigger_type'    => array(
              'type'    => 'radio',
              'default' => 'button',
              'label'   => __( 'Popup is triggered by clicking:', 'se-popup-widget-text-domain' ),
              'state_emitter' => array(
                  'callback' => 'select',
                  'args' => array( 'trigger_type' )
              ),
              'options' => array(
                  'button' => __( 'A button', 'se-popup-widget-text-domain' ),
                  'image'      => __( 'An image', 'se-popup-widget-text-domain' ),
              )
          ),
          'trigger_align' => array(
                'type' => 'select',
                'label' => __('Trigger Alignment', 'se-popup-widget-text-domain'),
                'default' => 'text-left',
                'options' => array(
                    'text-left' => __( 'Left', 'se-popup-widget-text-domain' ),
                    'text-center' => __( 'Center', 'se-popup-widget-text-domain' ),
                    'text-right' => __( 'Right', 'se-popup-widget-text-domain' ),
                )
            ),
          'image' => array(
              'type' => 'media',
              'state_handler' => array(
                  'trigger_type[image]' => array('show'),
                  'trigger_type[button]' => array('hide'),
              ),
              'label' => __( 'Select an image', 'se-popup-widget-text-domain' ),
              'choose' => __( 'Select an image', 'se-popup-widget-text-domain' ),
              'update' => __( 'Set image', 'se-popup-widget-text-domain' ),
              'library' => 'image',
              'fallback' => true
          ),
          'image_size' => array(
              'type' => 'image-size',
              'label' => __( 'Image size', 'se-popup-widget-text-domain' ),
              'state_handler' => array(
                  'trigger_type[image]' => array('show'),
                  'trigger_type[button]' => array('hide'),
              ),
          ),
          'image_width' => array(
              'type' => 'number',
              'state_handler' => array(
                  'trigger_type[image]' => array('show'),
                  'trigger_type[button]' => array('hide'),
              ),
              'label' => __( 'Set additional image width limit if needed (px)', 'se-popup-widget-text-domain' ),
              'default' => ''
          ),
          'button' => array(
              'type' => 'text',
              'state_handler' => array(
                  'trigger_type[image]' => array('hide'),
                  'trigger_type[button]' => array('show'),
              ),
              'label' => __('Button text', 'se-popup-widget-text-domain'),
              'default' => ''
          )
        );
    }

    function get_template_variables($instance, $args) {

      if(!$instance['settings']) $instance['settings'] = array();

  		$return = array(
  			'content'  => ($instance['content_type'] == 'tinymce') ? $instance['content'] : $instance['advanced_content'],
        'title'      => $instance['title'],
  			'image'        => $this->image_html($instance),
        'fullscreen'        => $instance['fullscreen'],
        'image_width' => $instance['image_width'],
        'button'   => $this->button_html($instance),
        'trigger_align'   => $instance['trigger_align'],
        'trigger_type' => $instance['trigger_type']
  		);

  		return $return;

  	}

    private function button_html($instance) {

      if(empty($instance['button'])) return;

      $html = "<a class='button'>".$instance['button']."</a>";

      return $html;

    }

    private function image_html($instance) {

      if(empty($instance['image'])) return;

      $size = (isset($instance['image_size']) && !empty($instance['image_size'])) ? $instance['image_size'] : 'medium';

      $data = wp_get_attachment_image_src($instance['image'], $instance['image_size']);

      if(isset($instance['image_width']) && !empty($instance['image_width'])) {
        // some calculations to keep proportions
        $height = ($data[1] == $data[2]) ? $instance['image_width'] : $instance['image_width']/$data[1]/$data[2];
        $html = '<img src="'.$data[0].'" width="'.$instance['image_width'].'" height="'.$height.'" />';
      } else {
        $html = '<img src="'.$data[0].'" width="'.$data[1].'" height="'.$data[2].'" />';
      }

      return $html;

    }

    function get_template_name($instance) {
        return 'template';
    }

    function get_style_name($instance) {
        return '';
    }

}

siteorigin_widget_register('se-popup-widget', __FILE__, 'SE_Popup_Widget');
