<?php

/*
Widget Name: SE WooCommerce Category Widget
Description: Select a category on a list, displays the category box with a link to the archive.
Author: Francesco Fortino
Author URI: https://sito.express
*/

class WC_Category_Widget extends SiteOrigin_Widget {

    function __construct() {
      $options = array();
      parent::__construct(
          'wc-category-widget',
          __('SE WooCommerce Category Widget', 'wc-category-widget-text-domain'),
          array(
              'description' => __('Select a category on a list, displays the category box with a link to the archive.', 'wc-category-widget-text-domain'),
          ),
          array(
          ),
          '',
          plugin_dir_path(__FILE__)
      );
    }

    function modify_form($form) {
      return
        array(
          'term_id' => array(
                'type' => 'select',
                'label' => __('Select the category you want to display', 'wc-category-widget-text-domain'),
                'options' => $this->get_product_cat_terms()
          ),
          'text_1' => array(
              'type' => 'text',
              'label' => __( 'Heading line (Above category name).', 'wc-category-widget-text-domain' )
          ),
          'text_2' => array(
              'type' => 'text',
              'label' => __( 'Subtitle line (Below category name).', 'wc-category-widget-text-domain' )
          ),
          'product_id' => array(
              'type' => 'link',
              'label' => __('Select the product you want to link.', 'wc-category-widget-text-domain'),
              'description' => __('If selected, the button will link to the product, while keeping its category layout.', 'wc-category-widget-text-domain'),
              'post_types' => array('product')
          ),
          'background' => array(
              'type' => 'color',
              'label' => __( 'Background color', 'wc-category-widget-text-domain' ),
              'default' => ''
          ),
          'hide_button' => array(
              'type' => 'checkbox',
              'label' => __( 'Hide button?', 'wc-category-widget-text-domain' ),
              'default' => false
          ),
          'show_description' => array(
              'type' => 'checkbox',
              'label' => __( 'Show Category Description? (Below category name and subtitle)', 'wc-category-widget-text-domain' ),
              'default' => false
          )
        );
    }

    public function get_product_cat_terms() {
      /*
      global $wpdb;
      $terms = $wpdb->get_results(
          "SELECT t.term_id, t.name
          FROM
              $wpdb->terms AS t
          INNER JOIN
              $wpdb->term_taxonomy ON
                  t.term_id = $wpdb->term_taxonomy.term_id
          WHERE
              $wpdb->term_taxonomy.taxonomy = 'product_cat'"
      );
      $options = array();
      foreach($terms as $term) {
        $options[$term->term_id] = $term->name;
      } */
      $options = (taxonomy_exists('product_cat')) ? $this->get_terms_hierarchical(get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => 0,
        'fields' => 'all'
      ))) : array('none' => 'WooCommerce Not Found');
      return $options;
    }

    public function get_terms_hierarchical($terms) {

      $ordered_children = array();
      $maybe_parents = array();
      $ordered = array();

      if($terms) {

        // List only terms without parent
        foreach($terms as $term) {
          if($term->parent == 0) {
            $maybe_parents[$term->term_id] = $term->name;
          }
        }

        // if there are children, count will be different
        if(count($maybe_parents) != count($terms)) {

          foreach($terms as $term) {
            if($term->parent != 0) {
              $ordered_children[$term->parent][$term->term_id] = ' - '.$term->name;
            }
          }

          foreach($maybe_parents as $mparent_id => $parent_name) {
            $ordered[$mparent_id] = $parent_name;
            if(isset($ordered_children[$mparent_id])) {
              foreach($ordered_children[$mparent_id] as $child_id => $child_name) {
                $ordered[$child_id] = $child_name;
              }
            }
          }

        } else {

          $ordered = $maybe_parents;

        }

        $terms = $ordered;

      }

      return $terms;

    }

    function get_template_name($instance) {
        return 'template';
    }

    function get_style_name($instance) {
        return '';
    }

}
siteorigin_widget_register('wc-category-widget', __FILE__, 'WC_Category_Widget');
