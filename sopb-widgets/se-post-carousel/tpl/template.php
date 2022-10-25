<?php

/**
 * @var array $instance
 * @var array $query
 * @var array $settings
 * @var string $template
 * @var string $classes
 */

 if( !empty( $instance['title'] ) ) {
 	echo $args['before_title'] . $instance['title'] . $args['after_title'];
 }

 // Saving post context

$context = new post_context();

query_posts($query);

if(have_posts()) { ?>

<div class='se-carousel <?php if($classes) echo $classes; ?>' data-slick=<?php echo json_encode($settings); ?> <?php if(isset($settings['rtl']) && $settings['rtl'] == true) echo "dir='rtl'"; ?>>

<?php
  while( have_posts() ) {
    the_post();
    SE_Post_Carousel_Widget::locate_template($instance['template'], true, false);
  }
}
?>

</div>
<?php $context->restore(); ?>
