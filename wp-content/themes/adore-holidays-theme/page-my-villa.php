<?php

if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url('/')));
    exit;
}

get_header();

while(have_posts()) {
  the_post(); ?>

<?php pageBanner(); ?>

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
         Custom code here
      </div>
</div>



<?php }

get_footer();

 ?>
