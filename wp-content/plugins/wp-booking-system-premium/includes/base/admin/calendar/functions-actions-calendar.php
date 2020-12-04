<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Validates and handles the adding of the new calendar in the database
 *
 */
function wpbs_action_add_calendar() {

	// Verify for nonce
	if( empty( $_POST['wpbs_token'] ) || ! wp_verify_nonce( $_POST['wpbs_token'], 'wpbs_add_calendar' ) )
		return;

	// Verify for calendar name
	if( empty( $_POST['calendar_name'] ) ) {

		wpbs_admin_notices()->register_notice( 'calendar_name_missing', '<p>' . __( 'Please add a name for your new calendar.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'calendar_name_missing' );

		return;

	}

	// Prepare calendar data to be inserted
	$calendar_data = array(
		'name' 		    => sanitize_text_field( $_POST['calendar_name'] ),
		'date_created'  => current_time( 'Y-m-d H:i:s' ),
		'date_modified' => current_time( 'Y-m-d H:i:s' ),
		'status'		=> 'active',
		'ical_hash'		=> wpbs_generate_hash()
	);

	// Insert calendar into the database
	$calendar_id = wpbs_insert_calendar( $calendar_data );

	// If the calendar could not be inserted show a message to the user
	if( ! $calendar_id ) {

		wpbs_admin_notices()->register_notice( 'calendar_insert_false', '<p>' . __( 'Something went wrong. Could not create the calendar. Please try again.', 'wp-booking-system' ) . '</p>', 'error' );
		wpbs_admin_notices()->display_notice( 'calendar_insert_false' );

		return;

	}

	/**
	 * Add default legend items if no legend has been selected
	 *
	 */
	if( empty( $_POST['calendar_legend'] ) ) {

		$legend_items_data = wpbs_get_default_legend_items_data();

		foreach( $legend_items_data as $legend_item_data ) {

			// Set the calendar id for the legend items data
			$legend_item_data['calendar_id'] = $calendar_id;

			// Insert legend item
			wpbs_insert_legend_item( $legend_item_data );

		}

	}


	/**
	 * Add legend items from another calendar
	 *
	 */
	if( ! empty( $_POST['calendar_legend'] ) ) {

		$copy_calendar_id 			= absint( $_POST['calendar_legend'] );
		$copy_calendar_legend_items = wpbs_get_legend_items( array( 'calendar_id' => $copy_calendar_id ) );

		if( ! empty( $copy_calendar_legend_items ) ) {

			foreach( $copy_calendar_legend_items as $legend_item ) {

				// Prepare data
				$copy_legend_item_data = $legend_item->to_array();
				$copy_legend_item_data['calendar_id'] = $calendar_id;

				// Unset the legend item id from the array
				unset( $copy_legend_item_data['id'] );

				$copy_legend_item_id   = $legend_item->get('id');

				// Insert the new legend item
				$legend_item_id = wpbs_insert_legend_item( $copy_legend_item_data );

				if( ! $legend_item_id )
					continue;

				// Get all meta from the copy calendar legend items
				$copy_legend_item_meta = wpbs_get_legend_item_meta( $copy_legend_item_id );

				if( empty( $copy_legend_item_meta ) )
					continue;

				foreach( $copy_legend_item_meta as $meta_key => $meta_values ) {

					foreach( $meta_values as $meta_value )
						wpbs_add_legend_item_meta( $legend_item_id, $meta_key, $meta_value );

				}

			}

		}

	}

	if( isset( $_POST['calendar_price'] ) ) {
		wpbs_update_calendar_meta($calendar_id, 'default_price', absint($_POST['calendar_price']));
	}

	// Redirect to the edit page of the calendar with a success message
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $calendar_id, 'wpbs_message' => 'calendar_insert_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_add_calendar', 'wpbs_action_add_calendar', 50 );


/**
 * Handles the trash calendar action, which changes the status of the calendar from active to trash
 *
 */
function wpbs_action_trash_calendar() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_trash_calendar' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	$calendar_data = array(
		'status' => 'trash'
	);

	$updated = wpbs_update_calendar( $calendar_id, $calendar_data );

	if( ! $updated )
		return;

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'calendar_status' => 'active', 'wpbs_message' => 'calendar_trash_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_trash_calendar', 'wpbs_action_trash_calendar', 50 );


/**
 * Handles the restore calendar action, which changes the status of the calendar from trash to active
 *
 */
function wpbs_action_restore_calendar() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_restore_calendar' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	$calendar_data = array(
		'status' => 'active'
	);

	$updated = wpbs_update_calendar( $calendar_id, $calendar_data );

	if( ! $updated )
		return;

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'calendar_status' => 'trash', 'wpbs_message' => 'calendar_restore_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_restore_calendar', 'wpbs_action_restore_calendar', 50 );


/**
 * Handles the delete calendar action, which removes all calendar data, legend items and events data
 * associated with the calendar
 *
 */
function wpbs_action_delete_calendar() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_delete_calendar' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	/**
	 * Delete the calendar
	 *
	 */
	$deleted = wpbs_delete_calendar( $calendar_id );

	if( ! $deleted )
		return;
	
	/**
	 * Delete calendar meta
	 *
	 */
	$calendar_meta = wpbs_get_calendar_meta( $calendar_id );

	if( ! empty( $calendar_meta ) ) {

		foreach( $calendar_meta as $key => $value ) {

			wpbs_delete_calendar_meta( $calendar_id, $key );

		}

	}


	/**
	 * Delete legend items
	 *
	 */
	$legend_items = wpbs_get_legend_items( array( 'calendar_id' => $calendar_id ) );

	foreach( $legend_items as $legend_item ) {

		wpbs_delete_legend_item( $legend_item->get('id') );

	}

	/**
	 * Delete legend items meta
	 *
	 */
	foreach( $legend_items as $legend_item ) {

		$legend_item_meta = wpbs_get_legend_item_meta( $legend_item->get('id') );

		if( ! empty( $legend_item_meta ) ) {

			foreach( $legend_item_meta as $key => $value ) {

				wpbs_delete_legend_item_meta( $legend_item->get('id'), $key );

			}

		}

	}

	/**
	 * Delete events
	 *
	 */
	$events = wpbs_get_events( array( 'calendar_id' => $calendar_id ) );

	foreach( $events as $event ) {

		wpbs_delete_event( $event->get('id') );

	}

	/**
	 * Delete events meta
	 *
	 */
	foreach( $events as $event ) {

		$event_meta = wpbs_get_legend_item_meta( $event->get('id') );

		if( ! empty( $event_meta ) ) {

			foreach( $event_meta as $key => $value ) {

				wpbs_delete_event_meta( $event->get('id'), $key );

			}

		}

	}

	/**
	 * Delete bookings
	 *
	 */
	
	$bookings = wpbs_get_bookings( array( 'calendar_id' => $calendar_id ) );

	foreach( $bookings as $booking ) {
		
		wpbs_delete_booking( $booking->get('id') );

		$bookings_meta = wpbs_get_booking_meta( $booking->get('id') );

		if( ! empty( $bookings_meta ) ) {

			foreach( $bookings_meta as $key => $value ) {

				wpbs_delete_booking_meta( $booking->get('id'), $key );

			}

		}
	}

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'calendar_status' => 'trash', 'wpbs_message' => 'calendar_delete_success' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_delete_calendar', 'wpbs_action_delete_calendar', 50 );

/**
 * Handles enabling iCalendar import URLs
 *
 */
function wpbs_action_enable_icalendar_links() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_enable_icalendar_links' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	wpbs_update_calendar_meta($calendar_id, 'disable_icalendar_links', false);

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'subpage' => 'ical-import-export', 'calendar_id' => $calendar_id ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_enable_icalendar_links', 'wpbs_action_enable_icalendar_links', 50 );

/**
 * Handles disabling iCalendar import URLs
 *
 */
function wpbs_action_disable_icalendar_links() {

	// Verify for nonce
	if( empty( $_GET['wpbs_token'] ) || ! wp_verify_nonce( $_GET['wpbs_token'], 'wpbs_disable_icalendar_links' ) )
		return;

	if( empty( $_GET['calendar_id'] ) )
		return;

	$calendar_id = absint( $_GET['calendar_id'] );

	wpbs_update_calendar_meta($calendar_id, 'disable_icalendar_links', true);

	// Redirect to the current page
	wp_redirect( add_query_arg( array( 'page' => 'wpbs-calendars', 'subpage' => 'ical-import-export', 'calendar_id' => $calendar_id ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpbs_action_disable_icalendar_links', 'wpbs_action_disable_icalendar_links', 50 );