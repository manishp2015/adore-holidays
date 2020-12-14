<?php
get_header();
pageBanner(array(
  'title' => 'All Destinations',
  'subtitle' => 'Choose your Destination'
));
?>

<div class="body-container">

  <!-- Blog Posts -->
      <div class="main-body-content">
     
        <div class="row">                                   <!-- row starts -->

          <div class="col-lg-3 col-md-3">  
            <div id="search-destinations">           
              <?php		
		          $field = get_field_object('related_country');
              $values = explode(',', $_GET['related_country']);
    
              $field = array(
                "australia"   => "Australia", 
                "france"      => "France", 
                "greece"      => "Greece",
                "indonesia"   => "Indonesia",
                "italy"       => "Italy",
                "new_zealand" => "New_Zealand",
                "portugal"    => "Portugal",
                "spain"       => "Spain",
                "thailand"    => "Thailand"
              );
              ?> 

              <ul class="custom-unordered-list custom-link">
			          <?php foreach($field as $choice_value=>$choice_label): ?>
                  <li>       
                    <input type="checkbox" value="<?php echo $choice_value; ?>" <?php if(in_array($choice_value, $values)) : ?> checked="checked"<?php endif;	?> />
				  	        <?php echo $choice_label; ?>
			            </li>
                  <?php endforeach; ?>
	            </ul>  
            </div>
          </div>

          <div class="col-lg-9">        
            <?php
            // Current page number
            $paged = max( 1, get_query_var( 'paged' ) );

            $per_page     = 15; // posts per page
            // // $offset_start = 9;  // initial offset
            // // $offset       = $paged ? ( $paged - 1 ) * $per_page + $offset_start : $offset_start;      

            $holidayDestination = new WP_Query(array(
               'posts_per_archive_page' => $per_page,
              // 'offset' => $offset,
               'post_type'=> 'destination',
              
            ));  
            
              while($holidayDestination->have_posts()){
                $holidayDestination->the_post();

            ?>

            <div class="section-break"></div>

            <div class="popular-dest">
              <div class="row">                              <!-- row starts -->
                <div class="col-lg-3">                  
                  <a class="sub-heading-one" href="<?php the_permalink(); ?>"><img class="img-space" src="<?php the_post_thumbnail_url('locationThumbnail') ?>" style="width: 8rem; height: 6.5rem" alt=""></a>
                </div>
                <div class="col-lg-9">
                  <h5><a class="sub-heading-one" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                  <p class="desc-spacing"><?php if (has_excerpt()) {
                      echo get_the_excerpt();
                      } else {
                      echo wp_trim_words(get_the_content(), 18);
                      } ?></p>
                </div>
              </div>                                        <!-- row ends -->
            </div>
            
            <?php } wp_reset_postdata(); ?>
            
            <!-- pagination -->
            <!-- <nav aria-label="...">         
              <ul class="pagination justify-content-center"> 

                   <?php echo paginate_links( array(
                    'total' => $holidayDestination->max_num_pages,
                   )); ?> 

                  </ul>
              </nav> -->
          </div>
          <div>
                
          </div>
          

        </div>              <!-- row ends -->

      </div>
</div>

  

<script type="text/javascript">
(function($) {

		$('#search-destinations').on('change', 'input[type="checkbox"]', function(){
		
		// vars
		var $ul = $(this).closest('ul'),
		    vals = [];
			
		$ul.find('input:checked').each(function(){

			vals.push( $(this).val() );
		});
		
		vals = vals.join(",");
		
		window.location.replace('<?php echo home_url('destinations'); ?>?related_country=' + vals);
		
		console.log( vals );
		
		});

})(jQuery);

</script>

<?php
get_footer();

 ?>
