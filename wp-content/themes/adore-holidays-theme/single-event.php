<?php
get_header();
pageBanner();

while(have_posts()) {
  the_post(); ?>

  <div class="body-container">

    <div class="event-metabox">
      <div class="btn-group" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-secondary"><i class="fas fa-home"></i><a class="metabox-link" href="<?php echo get_post_type_archive_link('event'); ?>"> Events Home </a></button>
        <button type="button" class="btn btn-light"><a class="btn-group"><?php the_title(); ?></a></button>
      </div>
    </div>

    <div class="main-body-content">
      <?php the_content(); ?>
    </div>

  </div>

<?php }

get_footer();

 ?>
