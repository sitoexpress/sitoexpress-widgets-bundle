<?php

$product_id = explode(' ', $instance['product_id'])[1];

 ?>
<div class='se-product-widget'>
  <?php echo do_shortcode('[products columns="1" ids="'.$product_id.'"]'); ?>
</div>
