<?php 
get_header(); 
pageBanner(array(
  'title' => 'Search Results',
  'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query(false)) . '&rdquo;'
)); 

?>

<div class="body-container">

<!-- Blog Posts -->
<div class="main-body-content">
  <?php 

    if (have_posts()) {
      while(have_posts()) {
        the_post(); 
  
        get_template_part('template-parts/content', get_post_type());
    }
        echo paginate_links(); 
      
    } else {
      echo '<h6>No results match that search</h6>';
    }

    get_search_form();
    ?> 
  </div>
</div>
</div>

<?php get_footer(); ?>
