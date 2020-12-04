<?php
get_header(); 
pageBanner(array(
  'title' => 'All Events',
  'subtitle' => 'See what is going on in our world.'
));

?>


<div class="body-container">


<!-- Event Posts -->
<div class="main-body-content">
  <?php
    while(have_posts()) {
      the_post(); ?>

      <div class="card-container">
        <div class="card" style="background-color: #f0ece3">
          <div class="card-body">
          <h3 class="card-title sub-heading-two"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

            <p class="card-text para-space"><?php if (has_excerpt()) {
              echo get_the_excerpt();
            } else {
              echo wp_trim_words(get_the_content(), 18);
            } ?></p>

            <a class="btn btn-secondary btn-sm" href="<?php the_permalink(); ?>">Continue Reading &raquo;</a>
          </div>
        </div>
      </div>

    <?php }
      echo paginate_links();
    ?>

</div>

</div>
</div>

<?php
get_footer();

 ?>
