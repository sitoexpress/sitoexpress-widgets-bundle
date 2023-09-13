<?php
/**
 * @var array $instance
 */

 if( !empty( $instance['title'] ) ) {
 	echo $args['before_title'] . $instance['title'] . $args['after_title'];
 }

 foreach($instance['content_repeater'] as $layout) {

   // $show will be true if this is the place
   $show = false;

   // A shit load of procedural logic just to make the job

   if($layout['archive_singular'] == 'archive') {

     // Archive logic

     /* DEBUG PURPOSE
     echo 'archive ok'.'</br>';
     echo get_the_ID().'</br>';
     echo get_option( 'page_for_posts' ).'</br>';
     echo (is_home()) ? 'coddio' : 'cocazzo'; */

     if($layout['archive_type'] == 'all' && is_archive()) {
       $show = true;
     } else
     if($layout['archive_type'] == 'post_type' && $layout['post_type_archive'] == 'post' && is_home()) {
       $show = true;
     } else
     if($layout['archive_type'] == 'post_type' && is_post_type_archive($layout['post_type_archive'])) {
       $show = true;
     } else
     if($layout['archive_type'] == 'taxonomy' && is_tax($layout['taxonomy'])) {
       $show = true;
     } else
     if($layout['archive_type'] == 'term') {

       // $layout['terms'] is something like:
       // [terms] => category:non-categorizzato,category:temporanea

       $terms = array();
       $terms_outer = explode(',', $layout['terms']);

       foreach($terms_outer as $string) {
         $terms_inner = explode(':', $string);
         $terms[$terms_inner[0]][] = $terms_inner[1];
       }

       // $terms is now
       // Array ( [category] => Array ( [0] => non-categorizzato [1] => temporanea ) )

       if($terms) {
         foreach($terms as $taxonomy => $terms_array) {
           if($taxonomy == 'category' && is_category($terms_array)) {
             $show = true;
           } else
           if($taxonomy == 'post_tag' && is_category($terms_array)) {
             $show = true;
           } else
           if(is_tax($taxonomy, $terms_array)) {
             $show = true;
           }
         }
       }

     }

   } else {

     // Singular logic

     if($layout['singular_type'] == 'generic' && is_post_type_archive($layout['post_type_singular'])) {
       $show = true;
     } else
     if($layout['singular_type'] == 'specific' && get_the_ID() == intval(str_replace('post: ', '', $layout['post_id']))) {
       $show = true;
     }

   }

   // if there's show, generate buffer render the content and break the loop
   if($show == true) {
     $builder_id = substr(md5(mt_rand(1000000, 9999999)), 0, 8 );
     $content = siteorigin_panels_render( 'cc-'.$builder_id, true, $layout['content']);
     break;
   }

 }

 if($show == false || !$content) return;

?>

<div class='se-conditional-wrapper'>
  <div class='se-conditional-content'>
    <?php echo do_shortcode($content); ?>
  </div>
</div>
