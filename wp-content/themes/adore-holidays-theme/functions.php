<?php

// Search Route for Villa Destinations
require get_theme_file_path('/destinations/search-route.php');


function holiday_files() {
  //CDN Scripts (Content Delivery Network) from Bootstrap, Google Fonts, and Fontawesome
  wp_enqueue_style('bootstrap-styles', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900');
  wp_enqueue_style('font-awesome', '//pro.fontawesome.com/releases/v5.13.1/css/all.css');
  // wp_enqueue_script('boostrap1', '//code.jquery.com/jquery-3.5.1.slim.min.js', array('jquery'), '', true);
  wp_enqueue_script('boostrap2', '//cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', array('jquery'), '', true);
  wp_enqueue_script('boostrap3', '//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), '', true);
  wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/topbutton.js', array('jquery'));

  // Storing files in Bundle Assets Folder
  if (strstr($_SERVER['SERVER_NAME'], 'adore-holidays')) {
    wp_enqueue_script('holiday-main-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
  } else {
    wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.9678b4003190d41dd438.js'), NULL, '1.0', true);
    wp_enqueue_script('holiday-main-js', get_theme_file_uri('/bundled-assets/scripts.13397a01e561d7b8efc3.js'), NULL, '1.0', true);
    wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.13397a01e561d7b8efc3.css'));
  }

    wp_localize_script('holiday-main-js', 'holidayData', array(
      'root_url' => get_site_url(),
    ));
}

add_action('wp_enqueue_scripts','holiday_files');


function holiday_features() {
  register_nav_menu('headerMenuLocation', 'Header Menu Location');   //For Creating Menu Items
  register_nav_menu('footerMenuLocation', 'Footer Menu Location');   //For Creating Footer Items
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');                              //To use resized images
  add_image_size('locationThumbnail', 128, 104, true);
  add_image_size('villaLandscape', 400, 260, true);
  add_image_size('villaPortrait', 480, 650, true);
  add_image_size('pageBanner', 1348, 250, true);
  add_image_size('villaSlideshow', 768, 512, true);
}

add_action('after_setup_theme','holiday_features');


// Ordering Files for Custom Post Types
function holiday_adjust_queries($query) {
  if (!is_admin() AND is_post_type_archive('destination') AND is_main_query()) {
    $query->set('orderby', 'category');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }
}

add_action('pre_get_posts', 'holiday_adjust_queries');


// Page Banner 

function pageBanner($args = NULL) {
  if (!$args['photo']) {
    if (get_field('page_banner_background_image') AND !is_archive() AND !is_home()) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/flower-pot.png');
    }
  }

  ?>
  <div class="page-banner-img">
    <img src="<?php echo $args['photo']; ?>" width="100%" height="auto" alt="">
  </div>

<?php 

  if (!$args['title']) {
    $args['title'] = get_the_title();
  }

  if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

 ?>
  <div class="title-container">
    <div class="event-title">
      <h1 class="event-heading"><?php echo $args['title']; ?></h1>
      <p class="event-sub-heading"><?php echo $args['subtitle']; ?></p>
    </div>
  </div>

<?php }  
//Redirect subscriber accounts out of admin and onto the homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }  
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }  
}

//Customize Login Screen
add_filter('login_headerurl','ourHeaderUrl');

  function ourHeaderUrl() {
    return esc_url(site_url('/'));
  }

add_action('login_enqueue_scripts','ourLoginCSS');

function ourLoginCSS() {
  wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.13397a01e561d7b8efc3.css'));
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900');
}



// Widget Area 

function arphabet_widgets_init() {

  register_sidebar( array(
    'name' =>__('Home right sidebar'),
    'id' => 'home_right_1',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="rounded">',
    'after_title'   => '</h2>',
) );   // Widget Area for Hostfully Booking Plugin

}
add_action( 'widgets_init', 'arphabet_widgets_init' );



//Category Dropdown
add_filter('acf/fields/taxonomy/query', 'my_acf_fields_taxonomy_query', 10, 3);
function my_acf_fields_taxonomy_query( $args, $field, $post_id ) {

    // Show 40 terms per AJAX call.
    $args['number'] = 40;

    // Order by most used.
    $args['orderby'] = 'count';
    $args['order'] = 'DESC';

    return $args;
}



/*Columns on Villa Admin Page*/
function villa_filter_post_columns($columns) {
  $columns = array(
    'cb'                   => '<input type="checkbox" />',
    'title'                => 'Title',
    'no_of_bedrooms'       => 'Bedrooms',
    'prices'               => 'Price',
    'location'             => 'Location',
    'owner'                => 'Owner Name',
    'date'                 => 'Date'  
  );
  return $columns;
}

add_filter('manage_villa_posts_columns', 'villa_filter_post_columns');


function villa_post_column ($column, $post_id) {
  if ($column === 'prices') {
    $price = get_post_meta( $post_id, 'prices', true );
    $relatedCurrency = get_field('currency');

    if( $relatedCurrency ) {
      foreach( $relatedCurrency as $currency ){

      if ( ! $price) {
       echo 'n/a';
      } else {
        echo $currency . $price . ' per week';
      }
    }
    }

  }

  if ( $column === 'location' ) { 

    $location = get_post_meta($post_id, 'location', true);
    
      if (!$location) {
        echo 'n/a';
      } else {
        echo $location;
      }   

  }

  if ( 'no_of_bedrooms' === $column ) {
    $bedrm = get_post_meta( $post_id, 'no_of_bedrooms', true);

    if (!$bedrm) {
      echo 'n/a';
    } else {      
      echo $bedrm;
    }

  }

  if ( 'owner' === $column ) {
    $owner = get_post_meta( $post_id, 'owner', true);

    if (!$owner) {
      echo 'n/a';
    } else {
      echo $owner;
    }

  }

}
add_action('manage_villa_posts_custom_column', 'villa_post_column', 10, 2);



//**********Checkbox on Destination Page******************* 

add_action('pre_get_posts', 'destination_pre_get_posts');

function destination_pre_get_posts( $query ) {

	//validate
	if( is_admin() ) {
		return;
	}

	//get original meta query
	$meta_query = $query->get('meta_query');

	//allow the url to alter the query
	if( !empty($_GET['related_country']) ) {
  
    $related_country = explode(',', $_GET['related_country']);

		//Add our meta query to the original meta queries
		$meta_query = array(
      array(
			      'key'     => 'related_country',
            'value'	  =>  $related_country,
            'compare' => 'IN'			
          ),
    );	
	}

	$query->set('meta_query', $meta_query );
	
	//always return
	return;

}

/*
* Add Table Columns on the Clients WP_List_Table
*/

function add_table_columns($columnsClient) {

    $columnsClient = array(
      'cb'                => '<input type="checkbox" />',
      'first_name'        => 'First Name',
      'last_name'         => 'Last Name',
      'email_address'     => 'Email Address',
      'phone_number'      => 'Phone Number',
      'interest_location' => 'Villa',            
      'check_in'          => 'Check In',
      'check_out'         => 'Check Out',
      'no_of_guests'      => 'No of Guests',
      'date'              => 'Date'
    );
    return $columnsClient; 
}

add_filter('manage_edit-client_columns','add_table_columns');

function output_table_columns_data($columnName, $post_id) {
  echo get_field($columnName, $post_id);

}    /*Outputs our Client custom field data, based on the column requested */  

add_action('manage_client_posts_custom_column', 'output_table_columns_data', 10, 2);

