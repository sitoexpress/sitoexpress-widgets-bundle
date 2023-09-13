<?php

/**
 * @var array $order
 * @var string $color
 * @var string $url
 */

?>

<div class="se-simple-card" style="background: <?php echo $color; ?>">
  <?php
    foreach( $instance['order'] as $item ) {
      switch( $item ) {

          case 'image_section' :

          $image_data = wp_get_attachment_image_src( $instance[$item]['image'], $instance[$item]['image_size'] );
          $alt = get_the_title();

          if( !empty( $image_data ) ) { ?>

            <div class="card-image">
              <img alt="<?php echo $alt; ?>" src="<?php echo $image_data[0]; ?>" width="<?php echo $image_data[1]; ?>" height="<?php echo $image_data[2]; ?>" />
            </div>

          <?php
          }

          break;

          case 'text_section_1' : ?>

          <div class="card-text text-1 <?php echo $instance[$item]['text_align']; ?>">
            <<?php echo $instance[$item]['h_']; ?>><?php echo $instance[$item]['text']; ?></<?php echo $instance[$item]['h_']; ?>>
          </div>

          <?php
          break;

          case 'text_section_2' : ?>

          <div class="card-text text-2 <?php echo $instance[$item]['text_align']; ?>">
            <<?php echo $instance[$item]['h_']; ?>><?php echo $instance[$item]['text']; ?></<?php echo $instance[$item]['h_']; ?>>
          </div>

          <?php
          break;

          case 'button_section' :
            if($instance[$item]['action'] == 'url') {
              if($url) { ?>

                <div class="card-button <?php echo $instance[$item]['button_align']; ?>">
                  <a class="button" href="<?php echo $url; ?>"><?php echo $instance[$item]['button']; ?></a>
                </div>
            <?php
              }
            } elseif($instance[$item]['action'] == 'popup')  {

              $this->sub_widget('SE_Popup_Widget', $instance[$item]['popup'], $instance[$item]['popup']);

            }
          break;
      }
  } ?>
</div>
