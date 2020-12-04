<?php
get_header(); 
pageBanner(array(
  'title' => get_the_archive_title(),
  'subtitle' => get_the_archive_description()
));  

?>

<div class="body-container">

<!-- Blog Posts -->
<div class="main-body-content">

<div id="search-villas">
		<?php
		
		$field = get_field_object('select_bedrooms');
    $values = explode(',', $_GET['select_bedrooms']);
    

    $field = array("3" => "3", "4" => "4", "5" => "5", "6+" => "6+");
      ?> 



      <ul>
			<?php foreach($field as $choice_value=>$choice_label): ?>
      
        <li>
        
            <input type="checkbox" value="<?php echo $choice_value; ?>" <?php if(in_array($choice_value, $values)) : ?> checked="checked"<?php endif;	?> />

				  	<?php echo $choice_label; ?></li>
			  </li>
      <?php endforeach; ?>
	  </ul>



   
    
		
		 
		
</div>     

<?php wp_reset_postdata(); ?>


<div class="col-lg-9">
 

 <?php 
   while(have_posts()){
     the_post();
 ?>       
        
       <div class="section-break"></div>

 <div class="popular-dest">
   <div class="row">
     <div class="col-lg-3">                  
       <a class="sub-heading-one" href="<?php the_permalink(); ?>"><img class="img-space" src="<?php the_post_thumbnail_url('locationThumbnail') ?>" style="width: 8rem; height: 6.5rem" alt=""></a>
     </div>
     <div class="col-lg-9">
       <h5><a class="sub-heading-one" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
       <p><?php if (has_excerpt()) {
           echo get_the_excerpt();
           } else {
           echo wp_trim_words(get_the_content(), 18);
           } ?></p>
     </div>
   </div>
 </div>

 <?php } ?>




</div>

</div>
</div>
</div>




<script type="text/javascript">
(function($) {

		$('#search-villas').on('change', 'input[type="checkbox"]', function(){
		
		// vars
		var $ul = $(this).closest('ul'),
		    vals = [];
			
		$ul.find('input:checked').each(function(){

			vals.push( $(this).val() );
		});
		
		vals = vals.join(",");
		
		window.location.replace('<?php echo home_url('villas'); ?>?bedrooms=' + vals);
		
		console.log( vals );
		
		});

})(jQuery);

</script>






<?php
get_footer();

 ?>
