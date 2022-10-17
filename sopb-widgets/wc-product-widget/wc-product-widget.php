<?php

/*
Widget Name: SE Product Widget
Description: Select product on a list, displays the product. Based on WC corresponding shortcode.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class WC_Product_Widget extends SiteOrigin_Widget {

    function __construct() {
      parent::__construct(
          'wc-product-widget',
          __('SE Product Widget', 'wc-product-widget-text-domain'),
          array(
              'description' => __('Select product on a list, displays the product. Based on WC corresponding shortcode.', 'wc-product-widget-text-domain'),
          ),
          array(
          ),
          array(
            'product_id' => array(
                  'type' => 'link',
                  'label' => __('Select the product you want to display', 'wc-product-widget-text-domain'),
                  'post_types' => array('product'),
                  'readonly' => true,
              ),
            ),
          plugin_dir_path(__FILE__)
      );
    }

    function get_template_name($instance) {
        return 'template';
    }

    function get_style_name($instance) {
        return '';
    }

}

siteorigin_widget_register('wc-product-widget', __FILE__, 'WC_Product_Widget');
