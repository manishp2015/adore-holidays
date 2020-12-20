<?php

get_header(); ?>


<?php while(have_posts()) {
      the_post(); ?>

  <div class="page-banner-img">
    <img class="page-banner-pic" src="<?php $pageBannerImage = get_field('page_banner_background_image'); echo $pageBannerImage['sizes']['pageBanner'] ?>" width="100%" height="auto" alt="">
  </div>

  <div class="event-container">

    <div class="event-title text-block">
      <h1 class="event-heading"><?php the_title(); ?></h1>
      <p class="event-sub-heading"><?php the_field('page_banner_subtitle'); ?></p>

      <div class="event-metabox">
        <div class="btn-group metabox" role="group" aria-label="Basic example">
          <button type="button" class="btn btn-secondary"><i class="fas fa-home"></i><a class="metabox-link" href="<?php echo get_post_type_archive_link('destination'); ?>"> All Destinations </a></button>

          <button type="button" class="btn btn-light"><a class="btn-group metabox"><?php the_title(); ?></a></button>

        </div>
      </div>

    </div>

  <div class="main-body-content">

            <!-- Stylesheet for Lightbox -->
            <?php 
              if(get_field('slider')) {  ?>
	            <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri().'/css/lightbox.css'; ?>">
	            <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri().'/js/lightbox.min.js'; ?>"></script>
            <?php } ?>


        <div class="row">    <!-- row stars here-->
          
          <div class="col-lg-8">
            <!-- Bootstrap Slider is located in Template Parts Folder under Parts-Slider.php -->
            <?php get_template_part('template-parts/page', 'slider'); ?>             
          </div>
        

          <div class="col-lg-4 feature-section">
            <!-- Hostfully Widget from Custom Fields -->
            <?php // the_field('hostfully'); ?> 



      <!-- Basic Villa Details --> 

      <h4 class="sub-2">Basic Details</h4> 

      <div class="section-break"> </div>

      <div class="elements-room card-body">
      
      <?php
      $relatedBedrooms = get_field('no_of_bedrooms');
      echo '<i class="fas fa-bed feature"> </i>';
      echo (" " . $relatedBedrooms);
      ?>
      
      
      <?php
      $relatedBathrooms = get_field('no_of_bathrooms');
      echo '<i class="fas fa-bath feature-space"> </i>';
      echo (" " . $relatedBathrooms);
      ?>
            
      <?php
      $relatedSleeps = get_field('sleeps');
      echo '<i class="fas fa-users feature-space"> </i>';
      echo (" " . $relatedSleeps);
      ?>
      <hr>

      <div>
      <?php
      $relatedMinimumStay = get_field('minimum_stay');
      echo (" " . $relatedMinimumStay) . " "; echo '<i>Nights Minimum</i>';
      ?>
      </div>      

      </div>

      <div class="section-break"> </div> 
             
      <div class="section-break"> </div>          
            
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
                  


          </div>

        </div>               <!-- row ends here-->
  </div>   
  



    <!-- Villa Description   -->
    <div class="row">
    <div class="col-lg-12">

    <div class="section-break"> </div>
    <?php the_content(); ?>

    <!-- Comment Section   -->                  
    <?php comments_template( '', true ); ?>
    <div class="line-spacing"> </div>
         
        
    <!-- Related Destination -->
          <?php
          $relatedDestinations = get_field('related_destinations');

          if ($relatedDestinations) {
            echo '<h6 class="">Related Destination(s)</h6>';
            echo '<ul class="custom-link custom-unordered-list">';
            foreach($relatedDestinations as $destination) {   ?>
              <li><a href="<?php echo get_the_permalink($destination); ?>"><?php echo get_the_title($destination); ?></a></li>

            <?php  }
            echo '</ul>';
            }

            ?>

  </div>
  
         
    
  </div> 
  </div>          
  </div>

  


<?php }

get_footer();

 ?>
