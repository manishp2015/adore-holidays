<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax callback when the search form is submitted
 * 
 */
function wpbs_s_ajax_search_calendars()
{
    check_ajax_referer('wpbs_s_search_form', 'wpbs_s_token');
    // Check args
    foreach ($_POST['args'] as $key => $val) {
        if (in_array($key, array_keys(wpbs_s_get_search_widget_default_args()))) {
            $search_widget_args[$key] = sanitize_text_field($val);
        }
    }

    $search_widget_outputter = new WPBS_S_Search_Widget_Outputter($search_widget_args, sanitize_text_field($_POST['start_date']), sanitize_text_field($_POST['end_date']));
    
    echo $search_widget_outputter->get_display();

    exit;
}

add_action('wp_ajax_wpbs_s_search_calendars', 'wpbs_s_ajax_search_calendars');
add_action('wp_ajax_nopriv_wpbs_s_search_calendars', 'wpbs_s_ajax_search_calendars');
