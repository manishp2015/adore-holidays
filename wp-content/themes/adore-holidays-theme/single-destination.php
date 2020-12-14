<?php

get_header();

while(have_posts()) {
  the_post(); ?>

<?php pageBanner() ?>

  <div class="body-container">

    <div class="event-metabox">
      <div class="btn-group metabox" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-secondary"><i class="fas fa-home"></i><a class="metabox-link" href="<?php echo get_post_type_archive_link('destination'); ?>"> All Destinations </a></button>
        <button type="button" class="btn btn-light"><a class="btn-group metabox"><?php the_title(); ?></a></button>
      </div>
    </div>

  
  <div>  
  
  <div class="section-break"></div>

  <script type="text/javascript" src="https://platform.hostfully.com/assets/widgets/searchwidget/searchwidget.js"></script>
  <script type="text/javascript" src="https://platform.hostfully.com/assets/js/pikaday.js"></script>
  <div id="searchwidget"></div>

  <!-- Left Sidebar for Search by Date -->
  <div class="row">
    <div class="col-lg-3 col-md-12">
      <?php the_content(); ?>

      <div class="line-spacing"></div>

      <!-- No of Guests Search Option -->
      <?php		
      if($_GET['sleeps'] && !empty($_GET['sleeps']))
      {
        $guestSleeps = $_GET['sleeps']; 
      }
      
      ?>

      <form action="" method="get">
        <div class="row">
          <div class="col-lg-7 col-md-3 col-sm-3">
            <input type="number" name="sleeps" class="form-control" placeholder="No of guests">
          </div>
          <div class="line-spacing"></div>
          <div class="col">
            <button type="submit" class="btn btn-secondary">Search</button>
          </div>
          <div class="col">
              <p>You searched for <?php echo $guestSleeps ?> guests</p>
          </div>
        </div>
      </form>

      <div class="line-spacing"></div>
    
    </div>


    <!-- Right Side Main Content to Showcase the Villas -->
    <div class="main-body-content col-lg-9 col-md-12">

      
      
<?php
$relatedVillas = new WP_Query(array(
  'posts_per_page' => -1,
  'post_type' => 'villa',
  'orderby' => 'meta_value_num',
  'meta_key' => 'no_of_bedrooms',
  'order' => 'ASC',
  'meta_query' => array(
    'relation' => 'AND',
    array(
      'key' => 'related_destinations',
      'compare' => 'LIKE',
      'value' => '"' . get_the_ID() . '"'
    ),
    array(
      'key' => 'sleeps',
      'value' => $guestSleeps,
      'type' => 'NUMERIC',
      'compare' => '>='
    )
  )
));

if ($relatedVillas->have_posts()) {
  echo '<h4>Popular Villas in ' . get_the_title() .'</h4>';

  while($relatedVillas->have_posts()) {
    $relatedVillas->the_post(); ?>

<div class="villa-container" style="background-color: #f0ece3">
<div class="row">
  <div class="col-lg-5 col-md-12 col-sm-12">
    <a href="<?php the_permalink(); ?>">
      <img class="image-corners" src="<?php the_post_thumbnail_url('villaLandscape') ?>" width="100%" height="auto"/>
    </a>
  </div>
  <div class="col-lg-5 col-md-8">
    <h3 class="min-list"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php
      $relatedMinimumStay = get_field('minimum_stay');
      echo (" " . $relatedMinimumStay) . " "; echo '<i> Nights Minimum</i>'; 
      ?>
    <hr>

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="elements-room card-body">  
<?php
$relatedBedrooms = get_field('no_of_bedrooms');
echo '<i class="fas fa-bed feature"> </i>';
echo (" " . $relatedBedrooms);
?>

<?php
$relatedBathrooms = get_field('no_of_bathrooms');
echo '<i class="fas fa-bath feature"> </i>';
echo (" " . $relatedBathrooms);
?>

<?php
$relatedSleeps = get_field('sleeps');
echo '<i class="fas fa-users feature"> </i>';
echo (" " . $relatedSleeps);
?>
</div>
</div>      
</div>

    <p><?php if (has_excerpt()) {
            echo get_the_excerpt();
          } else {
            echo wp_trim_words(get_the_content(), 25);
          } ?></p>
</div>
  <div class="min-list col-lg-2 col-md-4">
    <?php
      $relatedFromPrice = get_field('prices');
      $relatedCurrency = get_field('currency');
      $durations = get_field('duration');
      echo '<p class="sub-2">From Price <p>';
      
      
      if( $relatedCurrency ): ?>
        
            <?php foreach( $relatedCurrency as $currency ): ?>
              <div class="section-break"></div>
                  <h4 class="sub-2"><?php echo $currency; echo (" " . $relatedFromPrice);?></h4>
            <?php endforeach; ?>
        
        <?php endif; 

        if( $durations ): ?>
        
            <?php foreach( $durations as $duration ): ?>
                
                <h6 class="sub-2"><?php echo $duration;?></h6>
            <?php endforeach; ?>

        <?php endif;       

    ?>  
    <div class="section-break"></div>

    <div class="text-center">
      <a class="btn quote-button" style="background-color: #c7b198; color: #f0ece3"  href="<?php the_permalink(); ?>"><strong>Get Quote &raquo;</strong></a>
    </div>

  </div>
</div>
</div>

<?php  }
}

wp_reset_postdata();

?>


</div>

</div>
  
  
  </div>
              
  




</div>


<?php }

get_footer();

 ?>
