<?php
/**
 * @var array $instance
 * @var array $panels
 * @var string $icon_open
 * @var string $icon_close
 */

 if( !empty( $instance['title'] ) ) {
 	echo $args['before_title'] . $instance['title'] . $args['after_title'];
 }

?>
<div>
  <div class='se-random-content'>
    <?php foreach($contents as $key => $content) {
          if(strpos($key, 'selected') !== false) continue; ?>
          <div class='se-choosen-content <?php echo $key; ?>'>
            <?php echo wp_kses_post($content); ?>
          </div>
    <?php } ?>
  </div>
</div>
