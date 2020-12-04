<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the reporting admin area
 *
 */
function wpbs_r_include_files_admin_reporting()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page
    if (file_exists($dir_path . 'class-submenu-page-reporting.php')) {
        include $dir_path . 'class-submenu-page-reporting.php';
    }

    // Include main class file
    if (file_exists($dir_path . 'class-reporting.php')) {
        include $dir_path . 'class-reporting.php';
    }

}
add_action('wpbs_r_include_files', 'wpbs_r_include_files_admin_reporting');

/**
 * Register the reporting admin submenu page
 *
 */
function wpbs_r_register_submenu_page_reporting($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $submenu_pages['reporting'] = array(
        'class_name' => 'WPBS_Submenu_Page_Reporting',
        'data' => array(
            'page_title' => __('Reports', 'wp-booking-system-reporting'),
            'menu_title' => __('Reports', 'wp-booking-system-reporting'),
            'capability' => apply_filters('wpbs_submenu_page_capability_reporting', 'manage_options'),
            'menu_slug' => 'wpbs-reporting',
        ),
    );

    return $submenu_pages;

}
add_filter('wpbs_register_submenu_page', 'wpbs_r_register_submenu_page_reporting', 50);
