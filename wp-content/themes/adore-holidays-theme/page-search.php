<?php

get_header();

while(have_posts()) {
  the_post(); ?>

<?php pageBanner(array(
  'title' => 'Search Page',
  'subtitle' => "Find what you are looking for?"
)); 
?>

<div class="body-container">

  <?php
    $theParent =wp_get_post_parent_id(get_the_ID());

    if ($theParent) { ?>
      <div class="btn-group metabox" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-secondary"><i class="fas fa-home"></i><a class="metabox-link" href="<?php echo get_permalink($theParent); ?>"> Back to <?php echo get_the_title($theParent); ?></a></button>
        <button type="button" class="btn btn-light"><a class="btn-group metabox" href="#"><?php the_title(); ?></a></button>
      </div>

    <?php }

    ?>

  
      <div class="main-body-content">
        
        <?php get_search_form(); ?>
      </div>
</div>
</div>

<?php }

get_footer();

 ?>
