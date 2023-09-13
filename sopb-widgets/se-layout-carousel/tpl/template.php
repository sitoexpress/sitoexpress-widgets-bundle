<?php

/**
 * @var array $instance
 * @var array $slides
 * @var array $settings
 * @var string $classes
 */

$size = apply_filters('se_image_carousel_default_thumb_size', 'medium'); ?>

<div class='se-image-carousel'>

<?php if (!empty($slides)) { ?>

	<section class="splide" aria-label="<?php echo $title; ?>" data-splide=<?php echo json_encode($settings); ?> <?php if(isset($settings['direction']) && $settings['direction'] == 'rtl') echo "dir='rtl'"; ?>>
		<div class="splide__track">
			<div class="splide__list">

	<?php foreach ($slides as $key => $slide) { ?>

						<div class="splide__slide" <?php if($key == 0) echo "data-splide-interval='$net_interval'"; ?>>
							<?php
							$builder_id = substr(md5(mt_rand(1000000, 9999999)), 0, 8 );
							$content = siteorigin_panels_render( 's-'.$builder_id, true, $slide['layout']);
							?>
							<?php echo do_shortcode($content); ?>

						</div>

	<?php } ?>

			</div>
		</div>
	</section>

<?php } ?>

</div>
