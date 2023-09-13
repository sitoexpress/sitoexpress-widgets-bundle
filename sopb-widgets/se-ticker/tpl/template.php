<?php

/**
 * @var array $lines
 */

?>

<div class="se-ticker-widget">
    <div class="ticker-inner">
      <?php
      foreach($lines as $line) { ?>
        <div class="ticker-item">
          <p><?php echo $line; ?></p>
        </div>
      <?php } ?>
    </div>
</div>
