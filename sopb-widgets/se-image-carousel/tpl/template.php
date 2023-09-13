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

								<?php if($slide['url']) {

												$target = ($slide["url_target"]) ? '_blank' : '_self'; ?>

												<a href="<?php echo sow_esc_url($slide['url']); ?>" target="<?php echo esc_attr($target); ?>" rel="noopener noreferrer">

													<?php echo sewb_img($slide['image'], $slide['image_size'], get_the_title()." - Slide"); ?>

												</a>

								<?php } else { ?>

												<?php echo sewb_img($slide['image'], $slide['image_size'], get_the_title()." - Slide"); ?>

								<?php } ?>

								<?php if($slide['text']) { ?>

									<div class="slide-text">

										<?php if($slide['url']) {

												$target = ($slide["url_target"]) ? '_blank' : '_self'; ?>

												<a href="<?php echo sow_esc_url($slide['url']); ?>" target="<?php echo esc_attr($target); ?>" rel="noopener noreferrer">

										<?php } ?>

											<?php echo $slide['text']; ?>

										<?php if($slide['url']) { ?>
												</a>
										<?php } ?>

										</div>

								<?php } ?>

						</div>

	<?php } ?>

			</div>
		</div>
	</section>

<?php } ?>

</div>
