<section class="col-xs-12 col-sm-5 col-md-7">
    <?php
      $images = get_field('gallery');
      $divider = 8;
      
      if( $images ): ?>

    <!-- bootstrap code here..  -->
    <div id="mini-carousel" class ="carousal slide" data-ride="carousal">
      <ul class="carousal-inner">
        <li class="item active">
      
      
      <?php 
        $total = count( $images );
        $counter = 0;
        foreach( $images as $image ):
            $counter++; ?>

            <a href="<?php echo $image['sizes']['large']; ?>" class="fancybox img-<?php echo $counter; ?>" rel="mini">
              <img class="img-responsive" src="<?php echo $image['sizes']['villaSlideshow']; ?>" alt="">
            </a>

            <?php 
            $current_position = $images->$image + 1;
        
            if ($counter % $divider == 0) : ?>

          </li> 
          <li class="item">

            <?php endif; ?>
            <?php endforeach; ?>
        
        </li> 

      </ul>  
  
        <!-- Controls -->
        <a class="left carousel-control" href="#mini-carousel" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#mini-carousel" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>         
      
  </div>
  <?php endif; ?>
            </section> 




            <p><?php the_field('field_name'); ?></p>       





            <div class="form-group mx-sm-3">
              <label class="sub-heading-two" for="">Check In</label>
              <input type="date" class="form-control" name="" value="" placeholder="Check In">
            </div>

            <div class="form-group mx-sm-3">
              <label class="sub-heading-two" for="">Check Out</label>
              <input type="date" class="form-control" name="" value="" placeholder="Check Out">
            </div>

<!-- Stylesheet for Lightbox -->
<?php 
if(get_field('image_gallery')) {  ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri().'/css/lightbox.css'; ?>">
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri().'/js/lightbox.min.js'; ?>"></script>
<?php } ?>


            <div class="row">           <!-- row stars here-->
                                  
                                  <div class="col-lg-8">
                                     <!-- Display Gallery Images as Slideshow -->
                                    <?php 
                                      $images = get_field('image_gallery');
                                      if( $images ): ?>
                                         <div class="flexslider gallery">
                                            <ul class="slides">
                                              <?php foreach( $images as $image ): ?>
                                              <li>
                                                 <a href="<?php echo ($image['url']); ?>" class="gallery-image" data-lightbox="gallery" data-title="<?php echo ($image['title']); ?>">
                                                  <img src="<?php echo esc_url($image['sizes']['villaSlideshow']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" /> 
                                                </a>
                                             </li>
                                              <?php endforeach; ?>
                                            </ul>
                                         </div>
                                      <?php endif;?> 

                                      </div>

<!-- Javascript Scripts for Flexslider -->

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/flexslider.css" type="text/css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script src="<?php bloginfo('template_url'); ?>/js/jquery.flexslider.js" type="text/javascript"></script>
<script type="text/javascript">
 // Can also be used with $(document).ready()
 $(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide"
  });
});

</script> 




<div class="col-lg-12 feature-section-amenities"> 
  <h5>Features</h5>  
  <div class="section-break"> </div>      
            <?php
            //Features and Amenities
            // Load field settings and values.
            
            $field = get_field_object('indoor_details');
            $indoorFeatures = $field['value'];

            // Display labels.
            if( $indoorFeatures ): ?>
              <h6>Indoor Features</h6>
                 <ul class="elements custom-unordered-list" style="list-style-type:none;">
              <?php foreach( $indoorFeatures as $indoorFeature ): ?>
                     <li><?php echo $field['choices'][ $indoorFeature ]; ?></li>
               <?php endforeach; ?>
                  </ul>
            <?php endif; ?>
            <?php
    //Features and Amenities
    // Load field settings and values.
    $field = get_field_object('outdoor_details');
    $outdoorFeatures = $field['value'];

    // Display labels.
    if( $outdoorFeatures ): ?>
    <h6>Outdoor Features</h6>
    <ul class="elements custom-unordered-list" style="list-style-type:none;">
        <?php foreach( $outdoorFeatures as $outdoorFeature ): ?>
            <li><?php echo $field['choices'][ $outdoorFeature ]; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

     <?php
    //Features and Amenities
    // Load field settings and values.
    $field = get_field_object('other_features');
    $otherFeatures = $field['value'];

    // Display labels.
    if( $otherFeatures ): ?>
    <h6>Other Features</h6>
    <ul class="elements custom-unordered-list" style="list-style-type:none;">
        <?php foreach( $otherFeatures as $otherFeature ): ?>
            <li><?php echo $field['choices'][ $otherFeature ]; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>  

    </div>               
    <div class="section-break"> </div>
        
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