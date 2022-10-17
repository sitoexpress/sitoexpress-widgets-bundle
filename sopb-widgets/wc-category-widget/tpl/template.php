<?php

$term = get_term($instance['term_id'], 'product_cat');

$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
$image = wp_get_attachment_image_src( $thumbnail_id, 'medium' );
$classes = apply_filters('wc_category_widget_button_classes', array('button'));

$product_id = (!empty($instance['product_id']) && strpos($instance['product_id'], 'https') === false) ? explode(' ', $instance['product_id'])[1] : $instance['product_id'];

$text_1 = (!empty($instance['text_1'])) ? $instance['text_1'] : '';
$text_2 = (!empty($instance['text_2'])) ? $instance['text_2'] : '';

$hide_button = (!empty($instance['hide_button'])) ? $instance['hide_button'] : false;
$show_description = (!empty($instance['show_description'])) ? $instance['show_description'] : false;

$background = (!empty($instance['background'])) ? $instance['background'] : false;

if(is_numeric($product_id)) {
  $link = get_the_permalink($product_id);
} else
if(!$product_id) {
  $link = get_term_link($term);
} else {
  $link = $product_id;
}

 ?>
<div class='se-category-widget' <?php if($background) { echo "style='background-color: $background'"; } ?>>
  <?php if ( $image ) { ?>
    <div class="thumbnail">
      <?php if ( $hide_button == true ) { ?>
        <a href="<?php echo $link; ?>">
      <?php } ?>
          <img src="<?php echo $image[0]; ?>" alt="<?php echo $term->name; ?>" />
      <?php if ( $hide_button == true ) { ?>
        </a>
      <?php } ?>
    </div>
  <?php } ?>
  <div class="content">
    <?php if ( $text_1 ) { ?>
      <h2><?php echo $text_1; ?></h2>
    <?php } ?>
    <h3><?php echo $term->name; ?></h3>
    <?php if ( $text_2 ) { ?>
      <h4><?php echo $text_2; ?></h4>
    <?php } ?>
    <?php if ( $show_description == true ) { ?>
      <p><?php echo $term->description; ?></p>
    <?php } ?>
  </div>
  <?php if ( $hide_button == false ) { ?>
    <div class="action"><a class="<?php echo implode(" ", $classes); ?>" href="<?php echo $link; ?>"><?php _e("Discover more", "wc-category-widget-text-domain"); ?></a></div>
  <?php } ?>
</div>
