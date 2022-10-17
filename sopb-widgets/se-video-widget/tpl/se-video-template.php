<?php

/**
 * @var $player_id
 * @var $instance
 * @var $title
 * @var $url
 * @var $settings
 * @var $plyrconfig
 * @var $source "vimeo" | "youtube"
 * @var $ocover
 */

$poster = (wp_get_attachment_image_src($instance['cover'], 'big-800')) ? wp_get_attachment_image_src($instance['cover'], 'big-800')[0] : '';
$classes = (empty($instance['settings']['autoplay']) && empty($instance['settings']['background'])) ? "simple-plyr ".$source : implode(" ", $instance['settings'])." ".$source;
$url = ($source == 'vimeo') ? $url.'?dnt=1' : $url;

?>

<div class="se-video-parent <?php echo $classes; ?>">
  <?php if($title) echo "<h3 class='widget-title se-video-title'>".$title."</h3>"; ?>
  <div class="se-video">
    <div class="plyr__video-embed se-plyr" allow="autoplay" data-plyr-config='<?php echo json_encode($plyrconfig); ?>' data-poster="<?php echo $poster; ?>">
      <!--IUB-COOKIE-BLOCK-SKIP-START-->
      <iframe
        src="<?php echo $url; ?>"
        allowfullscreen
        allowtransparency
        ></iframe>
      <!--IUB-COOKIE-BLOCK-SKIP-END-->
    </div>
  </div>
</div>

<?php

if(defined('SE_VIDEO_DEBUG')) {
?>
<pre>
  <?php print_r($url); ?><br />
  <?php print_r($source); ?><br />
  <?php print_r($cover); ?><br />
  <?php print_r($plyrconfig); ?><br />
  <?php print_r($settings); ?><br />
</pre>
<?php } ?>
