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

    /**Custom Query for Search**/
    if($_GET['s'] && !empty($_GET['s']))
      {
        $text = $_GET['s']; 
      }

    /**Custom Query for No of Bedrooms**/  
    if($_GET['no_of_bedrooms'] && !empty($_GET['no_of_bedrooms']))
      {
        $relatedBedrooms = $_GET['no_of_bedrooms']; 
      }

    $args = array(
      'post_type' => 'villa',
      'posts_per_page' => -1,
      'meta_key' => 'no_of_bedrooms',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
      's' => $text,
      'meta_query' => array(
        array(
        'key' => 'no_of_bedrooms',
        'value' => $relatedBedrooms,
        'type' => 'NUMERIC',
        'compare' => '>='
        )
      ) 
    ); 

    $relatedVillas = new WP_Query($args); 

    if ($relatedVillas->have_posts()) {
      while($relatedVillas->have_posts()) {
        $relatedVillas->the_post(); 
  
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
