<?php 
/** Custom Post Meta
* http://docs.layerswp.com/how-to-add-custom-fields-to-posts-and-pages/
**/
/*
* Add Meta Box
* http://docs.layerswp.com/how-to-add-custom-fields-to-posts-and-pages/#custom-fields-in-the-editor
*/
function layers_child_add_meta_box() {
  $screens = array('post', 'page');
  foreach ( $screens as $screen ) {
	  add_meta_box(
		'layers_child_meta_sectionid',
		__( 'Orbirental Lead Widget', 'layerswp' ),
		'layers_child_meta_box_callback',
		$screen,
			'normal',
			'high'
	   );
  	}
}
add_action( 'add_meta_boxes', 'layers_child_add_meta_box' );

/*
* Create Meta Callback - Prints the box content.
* @param WP_Post $post The object for the current post/page.
*/
function layers_child_meta_box_callback( $post ) {
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'layers_child_meta_box', 'layers_child_meta_box_nonce' );
	
	/*
	* Use get_post_meta() to retrieve an existing value
	* from the database and use the value for the form.
	*/
	$property_uid = get_post_meta( $post->ID, 'property_uid', true );

	echo '<form action="../process.php" method="post" name="myForm">
	Property UID <input id="uid" type="text" name="property_uid" value="' . $property_uid .'"/>
	<input type="submit" value="Submit" /></form>';
}

/*
* Save the Meta (we are only saving our photo credit and photo url fields in this example)
* http://docs.layerswp.com/how-to-add-custom-fields-to-posts-and-pages/#saving-meta-data
*/
function layers_child_save_meta_box_data( $post_id ) {
	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'layers_child_meta_box_nonce' ] ) && wp_verify_nonce( $_POST[ 'layers_child_meta_box' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	
	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}
	
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'property_uid' ] ) ) {
		update_post_meta( $post_id, 'property_uid', sanitize_text_field( $_POST[ 'property_uid' ] ) );
	}

}
add_action( 'save_post', 'layers_child_save_meta_box_data' );

function wptuts_scripts_basic()
{
    wp_register_script( 'custom-script', "https://www.orbirental.com/assets/js/pikaday.js" );
    wp_register_script( 'custom-script2', "https://www.orbirental.com/assets/js/leadCaptureWidget_2.0.js" );
	
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'custom-script' );
    wp_enqueue_script( 'custom-script2' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_basic' );