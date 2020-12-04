<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the Search Widget files
 *
 */
function wpbs_s_include_files_search_widget()
{

    // Get legend dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include the shortcodes class
    if (file_exists($dir_path . 'class-search-widget-outputter.php')) {
        include $dir_path . 'class-search-widget-outputter.php';
    }

    // Include the ajax functions
    if (file_exists($dir_path . 'functions-ajax.php')) {
        include $dir_path . 'functions-ajax.php';
    }

}
add_action('wpbs_s_include_files', 'wpbs_s_include_files_search_widget');

/**
 * Default Search Widget strings
 *
 */
function wpbs_s_search_widget_default_strings()
{
    return array(
        'widget_title' => __('Search', 'wp-booking-system-search'),
        'start_date_label' => __('Start Date', 'wp-booking-system-search'),
        'end_date_label' => __('End Date', 'wp-booking-system-search'),
        'search_button_label' => __("Search", 'wp-booking-system-search'),
        'no_start_date' => __("Please select a starting date.", 'wp-booking-system-search'),
        'no_end_date' => __("Please select an ending date.", 'wp-booking-system-search'),
        'invalid_start_date' => __("Invalid start date.", 'wp-booking-system-search'),
        'invalid_end_date' => __("Invalid end date", 'wp-booking-system-search'),
        'results_title' => __("Search Results", 'wp-booking-system-search'),
        'no_results' => __("No available dates were found.", 'wp-booking-system-search'),
        'view_button_label' => __("View", 'wp-booking-system-search'),
    );
}

/**
 * Returns the default arguments for the calendar outputter
 *
 * @return array
 *
 */
function wpbs_s_get_search_widget_default_args()
{
    $args = array(
        'calendars' => 'all',
        'language' => 'auto',
        'title' => 'yes',
        'mark_selection' => 'yes'
    );

    return $args;
}
