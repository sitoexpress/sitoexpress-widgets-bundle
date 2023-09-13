<?php
/**
 * @var $args array
 * @var $items array
 * @var $gallery_id string
 * @var $lightbox bool
 * @var $css string
 * @var $layouts array
 */
?>

<?php if( !empty( $instance['widget_title'] ) ) echo $args['before_title'] . $instance['widget_title'] . $args['after_title'] ?>
<style>
<?php echo $css; ?>
</style>
<div id="<?php echo $gallery_id; ?>">
	<div class="se-masonry-grid"
		 data-layouts="<?php echo esc_attr( json_encode( $layouts ) ) ?>">
		 <div class="se-grid-sizer"></div>
		<?php
		if( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				$src        = wp_get_attachment_image_src( $item['image'], 'full' );
				$src        = empty( $src ) ? '' : $src[0];
				$title      = empty( $item['title'] ) ? '' : $item['title'];
				$url        = empty( $item['url'] ) ? '' : $item['url'];
				?>
				<div class="se-grid-item">

					<?php if ( ! empty( $url ) && !$lightbox ) : ?>
						<a href="<?php echo sow_esc_url( $url ) ?>"
						<?php foreach( $item['link_attributes'] as $att => $val ) : ?>
							<?php if ( ! empty( $val ) ) : ?>
								<?php echo $att.'="' . esc_attr( $val ) . '" '; ?>
							<?php endif; ?>
						<?php endforeach; ?>>
					<?php endif; ?>

					<?php if (function_exists('jqlb_apply_lightbox') && $lightbox) : ?>
						<a href="<?php echo wp_get_attachment_image_src($item['image'], 'full')[0]; ?>" rel="lightbox[<?php echo $gallery_id; ?>]">
					<?php endif; ?>

					<?php
					if (
						! empty( $instance['title'] ) &&
						! empty( $item['title'] ) &&
						! empty( $instance['title']['display'] ) &&
						$instance['title']['position'] == 'above'
					) :
					?>
						<span class="image-title">
							<?php echo wp_kses_post( $item['title'] ) ?>
						</span>
					<?php endif; ?>

					<?php
					$loading_val = function_exists( 'wp_get_loading_attr_default' ) ? wp_get_loading_attr_default( 'the_content' ) : 'lazy';
					echo siteorigin_widgets_get_attachment_image(
						$item['image'],
						'large',
						! empty( $item['image_fallback'] ) ? $item['image_fallback'] : '',
						array(
							'title' => esc_attr( $title ),
							'class' => 'se-masonry-grid-image',
							'loading' => $loading_val,
							'rel' => 'lightbox'
						)
					);
					?>

					<?php
					if (
						! empty( $instance['title'] ) &&
						! empty( $item['title'] ) &&
						! empty( $instance['title']['display'] ) &&
						$instance['title']['position'] == 'below'
					) :
					?>
						<span class="image-title">
							<?php echo wp_kses_post( $item['title'] ) ?>
						</span>
					<?php endif; ?>

					<?php if (!empty($url) || ($lightbox && function_exists('jqlb_apply_lightbox'))) : ?>
						</a>
					<?php endif; ?>
				</div>
				<?php
			}
		}
		?>

	</div>
</div>
