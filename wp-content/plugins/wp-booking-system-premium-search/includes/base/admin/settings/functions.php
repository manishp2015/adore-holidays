<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds a new tab to the Settings page of the plugin
 *
 * @param array $tabs
 *
 * @return $tabs
 *
 */
function wpbs_submenu_page_settings_tabs_search_addon( $tabs ) {

	$tabs['search_addon'] = __( 'Search Widget', 'wp-booking-system-search');

	return $tabs;

}
add_filter( 'wpbs_submenu_page_settings_tabs', 'wpbs_submenu_page_settings_tabs_search_addon', 40 );


/**
 * Adds the HTML for the Search Add-on Setting tab
 *
 */
function wpbs_submenu_page_settings_tab_search_addon() {

	include 'views/view-search_addon.php';

}
add_action( 'wpbs_submenu_page_settings_tab_search_addon', 'wpbs_submenu_page_settings_tab_search_addon' );