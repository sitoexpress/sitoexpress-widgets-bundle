<?php

/**
 * @var string $image_size
 * @var array $images
 * @var int $height
 */

$image_size = empty( $image_size ) ? 'full' : $image_size; // Account for no image size selection

?>
<div class="se-animated-images">
  <div class="animated-content images" style="height: <?php echo $height; ?>">
    <?php
    $image_count = count($images)-2;
    $image_current = 1;
    foreach($images as $image) {
      $image_data = wp_get_attachment_image_src( $image, $image_size );
      $alt = get_the_title()." - ".sprintf(__("Image %s of %s", "se-sopb-widgets"), $image_current, $image_count);

      if( !empty( $image_data ) ) { ?>

        <div class='animated-item image item-<?php echo $image_current; ?> a-disappear'>
          <img alt="<?php echo $alt; ?>" src="<?php echo $image_data[0]; ?>" width="<?php echo $image_data[1]; ?>" height="<?php echo $image_data[2]; ?>" />
        </div>

      <?php
      $image_current++;
      }
    } ?>
  </div>
</div>
