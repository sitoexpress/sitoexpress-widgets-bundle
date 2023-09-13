<?php

/**
 * @var string $heading
 * @var string $h_
 * @var string $text
 * @var string $button
 * @var string $button_align
 * @var string $url
 */

?>
<div class="se-animated-text">
  <div class="animated-content texts">
    <div class="animated-text animated-item text a-disappear">
      <<?php echo $h_; ?>>
        <?php echo $heading; ?>
      </<?php echo $h_; ?>>
      <?php echo $text; ?>
    </div>
    <?php if(!empty($button)) { ?>
    <div class="animated-button animated-item text a-disappear <?php echo $button_align; ?>">
      <a class="button" href="<?php echo $url; ?>"><?php echo $button; ?></a>
    </div>
    <?php } ?>
  </div>
</div>
