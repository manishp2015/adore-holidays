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

<div id="search-houses">
		<?php
		
		$field = get_field_object('bedrooms');
    $values = explode(',', $_GET['bedrooms']);
    

    $field = array("1" => "1", "2" => "2", "3" => "3");
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


  <?php
    while(have_posts()) {
      the_post(); ?>

    <div class="card-container">
      <div class="card" style="background-color: #f0ece3">
        <div class="card-body">
         <h3 class="card-title sub-heading-two"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
         </h3>
          <div class="metabox">
          <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('M-j-Y'); ?>  in <?php echo get_the_category_list(', '); ?></p>
          </div>

           <p class="card-text">
            <?php if (has_excerpt()) {
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




<script type="text/javascript">
(function($) {

		$('#search-houses').on('change', 'input[type="checkbox"]', function(){
		
		// vars
		var $ul = $(this).closest('ul'),
		    vals = [];
			
		$ul.find('input:checked').each(function(){

			vals.push( $(this).val() );
		});
		
		vals = vals.join(",");
		
		window.location.replace('<?php echo home_url('houses'); ?>?bedrooms=' + vals);
		
		console.log( vals );
		
		});

})(jQuery);

</script>






<?php
get_footer();

 ?>
