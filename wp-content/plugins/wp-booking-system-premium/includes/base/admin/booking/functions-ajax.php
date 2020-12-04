<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function wpbs_action_ajax_open_booking_details()
{

    // Nonce
    check_ajax_referer('wpbs_open_booking_details', 'wpbs_token');

    if (!isset($_POST['id'])) {
        return false;
    }

    $booking_id = absint($_POST['id']);

    // Get booking
    $booking = wpbs_get_booking($booking_id);

    if (is_null($booking)) {
        return;
    }

    // If booking is unread, make it read
    if ($booking->get('is_read') == 0) {
        $booking_data = array(
            'is_read' => 1,
        );
        wpbs_update_booking($booking_id, $booking_data);
    }

    // Get modal content
    $booking_display = new WPBS_Booking_Details_Outputter($booking);
    $booking_display->display();

    wp_die();

}
add_action('wp_ajax_wpbs_open_booking_details', 'wpbs_action_ajax_open_booking_details');

function wpbs_action_ajax_booking_email_customer()
{
    // Nonce
    check_ajax_referer('wpbs_booking_email_customer', 'wpbs_token');

    if (!isset($_POST['id'])) {
        return false;
    }

    $booking_id = absint($_POST['id']);

    // Get booking
    $booking = wpbs_get_booking($booking_id);

    if (is_null($booking)) {
        return;
    }
    parse_str($_POST['form_data'], $_POST['form_data']);

    $email_form_data = $_POST['form_data'];

    $language = wpbs_get_booking_meta($booking_id, 'submitted_language', true);

    // Parse some form tags
    $email_tags = new WPBS_Email_Tags(wpbs_get_form($booking->get('form_id')), wpbs_get_calendar($booking->get('calendar_id')), $booking_id, $booking->get('fields'), $language, strtotime($booking->get('start_date')), strtotime($booking->get('end_date')));

    $email_form_data['booking_email_customer_message'] = $email_tags->parse(nl2br($email_form_data['booking_email_customer_message']));
    $email_form_data['booking_email_customer_subject'] = $email_tags->parse($email_form_data['booking_email_customer_subject']);

    // Send the email
    $mailer = new WPBS_Booking_Mailer($booking, $email_form_data);
    $mailer->prepare('customer');
    $mailer->send();

    echo __('Email successfully sent.', 'wp-booking-system');

    wp_die();

}
add_action('wp_ajax_wpbs_booking_email_customer', 'wpbs_action_ajax_booking_email_customer');

/**
 * Edit Booking Details
 *
 */
function wpbs_action_ajax_wpbs_edit_booking_details()
{

    // Verify nonce
    if (empty($_POST['token']) || !wp_verify_nonce($_POST['token'], 'wpbs_edit_booking')) {
        return;
    }

    parse_str($_POST['form_data'], $data);

    if (!isset($data['booking_id'])) {
        return false;
    }

    $booking_id = absint($data['booking_id']);

    // Get booking
    $booking = wpbs_get_booking($booking_id);

    if (is_null($booking)) {
        return;
    }

    // Update Booking Details (Form Fields)
    if ($_POST['type'] == 'booking_details') {

        $fields = $booking->get('fields');

        foreach ($fields as &$field) {
            if (isset($data['wpbs-edit-booking-field-' . $field['id']]) && !empty(isset($data['wpbs-edit-booking-field-' . $field['id']]))) {
                if (wpbs_form_field_is_product($field['type'])) {
                    list($price, $value) = explode('|', $field['user_value']);
                    $field['user_value'] = $price . '|' . $data['wpbs-edit-booking-field-' . $field['id']];
                } else {
                    $field['user_value'] = esc_attr($data['wpbs-edit-booking-field-' . $field['id']]);
                }

            }
        }

        $booking_data = array('fields' => $fields);

    }

    // Update Booking Data (Dates)
    if ($_POST['type'] == 'booking_data') {

        $start_date = DateTime::createFromFormat('Y-m-d', $data['wpbs-edit-booking-field-start_date']);
        $end_date = DateTime::createFromFormat('Y-m-d', $data['wpbs-edit-booking-field-end_date']);

        if(!$start_date || !$end_date || $start_date > $end_date){
            echo json_encode(array(
                wpbs_date_i18n(get_option('date_format'), strtotime($booking->get('start_date'))), 
                wpbs_date_i18n(get_option('date_format'), strtotime($booking->get('end_date')))
            ));
            wp_die();
        }

        $booking_data = array(
            'start_date' => wpbs_date_i18n('Y-m-d 00:00:00', $start_date->getTimestamp()),
            'end_date' => wpbs_date_i18n('Y-m-d 00:00:00', $end_date->getTimestamp()),
        );

        echo json_encode(array(
            wpbs_date_i18n(get_option('date_format'), strtotime($booking_data['start_date'])), 
            wpbs_date_i18n(get_option('date_format'), strtotime($booking_data['end_date']))
        ));
        
    }
    wpbs_update_booking($booking_id, $booking_data);

    wp_die();

}
add_action('wp_ajax_wpbs_edit_booking_details', 'wpbs_action_ajax_wpbs_edit_booking_details');


/**
 * Add Booking Notes
 *
 */
function wpbs_action_ajax_booking_add_note()
{
    // Nonce
    check_ajax_referer('wpbs_booking_notes', 'wpbs_token');

    if (!isset($_POST['booking_id'])) {
        return false;
    }

    $booking_id = absint($_POST['booking_id']);

    $note = sanitize_textarea_field($_POST['note']);

    if (empty($note)) {
        return false;
    }

    $booking_notes = wpbs_get_booking_meta($booking_id, 'booking_notes', true);

    if (empty($booking_notes)) {
        $booking_notes = array();
    }

    $timestamp = current_time('timestamp');

    $booking_notes[] = array(
        'timestamp' => $timestamp,
        'note' => $note,
    );

    wpbs_update_booking_meta($booking_id, 'booking_notes', $booking_notes);

    echo '
    <div class="wpbs-booking-details-modal-note">
        <p>' . nl2br($note) . '</p>
        <div class="wpbs-booking-details-modal-note-footer">
            <span class="wpbs-booking-details-modal-note-date-added">
                <strong>' . __('Added on', 'wp-booking-system') . ':</strong>
                ' . date(get_option('date_format') . ' ' . get_option('time_format'), $timestamp) . '
            </span>
            <a href="#" data-booking-note="' . array_key_last($booking_notes) . '" data-booking-id="' . $booking_id . '" class="wpbs-booking-details-modal-note-remove">' . __('delete note', 'wp-booking-system') . '</a>
        </div>
    </div>
    ';

    wp_die();
}
add_action('wp_ajax_wpbs_booking_add_note', 'wpbs_action_ajax_booking_add_note');

/**
 * Delete Booking Notes
 *
 */
function wpbs_action_ajax_booking_delete_note()
{
    // Nonce
    check_ajax_referer('wpbs_booking_notes', 'wpbs_token');

    if (!isset($_POST['booking_id'])) {
        return false;
    }

    $booking_id = absint($_POST['booking_id']);

    $note_id = absint($_POST['note_id']);

    $booking_notes = wpbs_get_booking_meta($booking_id, 'booking_notes', true);

    unset($booking_notes[$note_id]);

    wpbs_update_booking_meta($booking_id, 'booking_notes', $booking_notes);

    wp_die();
}
add_action('wp_ajax_wpbs_booking_delete_note', 'wpbs_action_ajax_booking_delete_note');

/**
 * Fix html entities in line items
 */
function wpbs_format_html_string($string)
{
    // Remove quantity from labels
    $string = preg_replace('/<span class="wpbs-line-item-quantity\b[^>]*>(.*?)<\/span>/i', '', $string);
    $string = strip_tags($string);
    $string = str_replace('&times;', 'x', $string);
    return $string;
}

/**
 * Save "Hide past bookings" option
 *
 */
function wpbs_action_ajax_booking_remember_hide_past_option()
{
    // Nonce
    check_ajax_referer('wpbs_remember_hide_past_option', 'wpbs_token');

    update_option('wpbs_remember_hide_past_bookings_option', ($_POST['remember'] == 'true' ? true : false));

    wp_die();
}
add_action('wp_ajax_wpbs_booking_remember_hide_past_option', 'wpbs_action_ajax_booking_remember_hide_past_option');