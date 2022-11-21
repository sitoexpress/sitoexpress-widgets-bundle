<?php

/**
* @var $trigger_type "image" | "button"
* @var $image
* @var $button
* @var $content
* @var $trigger_align
*/

$trigger = ($trigger_type == 'image') ? $image : $button;

if($instance['content_type'] == 'layout') {
  $builder_id = substr(md5(mt_rand(1000000, 9999999)), 0, 8 );
  $content = siteorigin_panels_render( 'w-'.$builder_id, true, $instance['advanced_content']);
}

if(function_exists('sauce_create_popup')) {

  $settings = array(
    'group' => array('se-pop-widget'),
    'trigger_align' => $trigger_align,
    'fullscreen' => $fullscreen
  );

  sauce_create_popup($content, $trigger, $settings);

} else { ?>

 <div class="se-pop-widget-wrap <?php echo $trigger_align; ?>">
   <div class="popup-parent-css">
     <div class="popup se-pop-widget-popup">
       <div class="popup-wrapper">
         <div class="popup-content">
           <div class="pp-content">
             <?php echo do_shortcode($content); ?>
           </div>
         </div>
       </div>
     </div>
   </div>
   <div class="popup-trigger">
    <?php if($trigger_type == 'image') { echo $image; } else { echo $button; } ?>
   </div>
 </div>

<?php } ?>
