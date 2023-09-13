<?php

/**
 * @var string $color
 * @var string $background
 * @var string $text
 * @var string $image
 * @var string $url
 * @var string $width
 * @var string $font_size
 */

?>
<div class="se-stamp-wheel">
  <div class="stamp-wheel-inner" style="background-color: <?php echo $background; ?>; width: <?php echo $width; ?>; height: <?php echo $width; ?>;">
    <?php if($image) { ?>
      <div class="stamp-wheel-img">
        <?php if($url) { ?>
          <a href="<?php echo $url; ?>">
        <?php } ?>
            <img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" />
        <?php if($url) { ?>
          </a>
        <?php } ?>
      </div>
    <?php } ?>
    <svg viewBox="0 0 100 100" width="100" height="100" style="fill: <?php echo $color; ?>">
      <defs>
        <path id="circle"
          d="
            M 50, 50
            m -37, 0
            a 37,37 0 1,1 74,0
            a 37,37 0 1,1 -74,0"/>
      </defs>
      <text font-size="<?php echo $font_size; ?>">
        <textPath xlink:href="#circle">
          <?php echo $text; ?>
        </textPath>
      </text>
    </svg>
  </div>
</div>
