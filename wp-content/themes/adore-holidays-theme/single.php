<?php

get_header();

while(have_posts()) {
  the_post(); ?>

<?php pageBanner(); ?>

<div class="body-container">

    <div class="metabox min-list">
      <div class="btn-group metabox" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-secondary"><i class="fas fa-home"></i><a class="metabox-link" href="<?php echo site_url('/blog'); ?>"> Blog Home </a></button>
        <button type="button" class="btn btn-light"><a class="btn-group metabox" href="#">Posted by <?php the_author_posts_link(); ?> on <?php the_time('M-j-Y'); ?>  in <?php echo get_the_category_list(', '); ?></a></button>

      </div>
    </div>


  <div class="main-body-content">
    <?php the_content(); ?>

  </div>
</div>

<?php }

get_footer();

 ?>
