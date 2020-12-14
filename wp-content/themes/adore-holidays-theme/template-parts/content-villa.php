<div class="villa-container" style="background-color: #f0ece3">
        <div class="row">
          <div class="col-lg-5 col-md-5 col-sm-12">
            <a href="<?php the_permalink(); ?>">
              <img class="image-corners" src="<?php the_post_thumbnail_url('villaLandscape') ?>" width="100%" height="auto"/>
            </a>
          </div>
          <div class="col-lg-5">
            <h3 class="min-list"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <hr>

      <div class="row">
      <div class="elements-room card-body col-lg-3 col-md-3">
      <?php
      $relatedBedrooms = get_field('no_of_bedrooms');
      echo '<i class="fas fa-bed"> </i>';
      echo (" " . $relatedBedrooms);
      ?>
      </div>

      <div class="elements-room card-body col-lg-3 col-md-3">
      <?php
      $relatedBathrooms = get_field('no_of_bathrooms');
      echo '<i class="fas fa-bath"> </i>';
      echo (" " . $relatedBathrooms);
      ?>
      </div>

      <div class="elements-room card-body col-lg-3 col-md-3">
      <?php
      $relatedSleeps = get_field('sleeps');
      echo '<i class="fas fa-users"> </i>';
      echo (" " . $relatedSleeps);
      ?>
      </div>

      <div class="elements-room card-body col-lg-3 col-md-3">
      <?php
      $relatedMinimumStay = get_field('minimum_stay');
      echo '<i class="fas fa-moon"> Min</i>'; echo (" " . $relatedMinimumStay) . " "; 
      ?>
      </div>

    </div>
    
            <p><?php if (has_excerpt()) {
                    echo get_the_excerpt();
                  } else {
                    echo wp_trim_words(get_the_content(), 25);
                  } ?></p>
          </div>
          <div class="min-list col-lg-2">
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