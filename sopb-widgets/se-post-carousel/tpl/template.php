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

$size = apply_filters('se_post_carousel_default_thumb_size', 'medium');

 // Saving post context

$context = new post_context();

query_posts($query);

if(have_posts()) { ?>

	<div class='se-image-carousel with-posts <?php echo $classes; ?>'>

		<section class="splide" aria-label="<?php echo $title; ?>" data-splide=<?php echo json_encode($settings); ?> <?php if(isset($settings['direction']) && $settings['direction'] == 'rtl') echo "dir='rtl'"; ?>>
			<div class="splide__track">
				<div class="splide__list">

<?php
  while( have_posts() ) {

    the_post(); ?>

		<div class="splide__slide" <?php if($key == 0) echo "data-splide-interval='$net_interval'"; ?>>

		<?php

    if($instance['template'] == 'default') { ?>

      <article id="post-<?php echo get_the_id(); ?>" <?php post_class(); ?> <?php if(function_exists('generate_do_microdata')) generate_do_microdata( 'article' ); ?>>

        <div class='carousel-item-inner'>

          <?php if(has_post_thumbnail()) { ?>
           <a class="thumb-a" href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
             <?php the_post_thumbnail($size); ?>
           </a>
          <?php } ?>


          <div class='entry-content'>
            <div class="entry-meta">
							<?php
							if(function_exists('bp_get_the_term_list')) {
								echo bp_get_the_term_list(get_the_id(), 'category', '<div class="terms-list">', ', ', '</div>', 1);
							} else {
								echo get_the_term_list(get_the_id(), 'category', '', ', ', '');
							} ?>
						</div>
            <h1 class='entry-title'>
              <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></a>
            </h1>
          </div>

        </div>

      </article>

    <?php } else {

      SE_Post_Carousel_Widget::locate_template($instance['template'], true, false);

    } ?>

		</div><!-- .splide__slide -->

		<?php

  } ?>

			</div>
		</div>
	</section>
</div>

<?php }

$context->restore(); ?>
