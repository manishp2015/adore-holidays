<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Pricing Tab to Booking Details
 *
 */
function wpbs_booking_modal_add_payment_tab($tabs)
{
    if (!wpbs_is_pricing_enabled()) {
        return $tabs;
    }

    $tabs['payment-details'] = __('Payment Details', 'wp-booking-system');
    return $tabs;
}
add_action('wpbs_booking_modal_tabs', 'wpbs_booking_modal_add_payment_tab', 10, 1);

/**
 * Add Pricing Tab view
 *
 */
function wpbs_booking_modal_add_payment_view($booking, $calendar)
{
    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    include 'views/view-modal-payment-details.php';
}
add_action('wpbs_booking_modal_tab_payment-details', 'wpbs_booking_modal_add_payment_view', 10, 2);

/**
 * Default message for no payment received.
 *
 * @param WPBS_Booking
 *
 */
function wpbs_booking_modal_payment_tab_content_no_payment($booking)
{
    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    $payments = wpbs_get_payments(array('booking_id' => $booking->get('id')));

    // Check if there is an order for this booking
    if (empty($payments)) {
        echo '<h3>' . __('No payment was received for this booking.', 'wp-booking-system') . '</h3>';
    }
}
add_action('wpbs_booking_modal_tab_content_payment', 'wpbs_booking_modal_payment_tab_content_no_payment', 20, 1);