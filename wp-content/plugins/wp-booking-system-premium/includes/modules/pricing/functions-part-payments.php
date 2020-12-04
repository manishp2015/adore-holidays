<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if part payments are enabled
 *
 * @return bool
 *
 */
function wpbs_part_payments_enabled()
{
    $settings = get_option('wpbs_settings', array());
    if (isset($settings['payment_part_payments_enable']) && $settings['payment_part_payments_enable'] == 'on') {
        return true;
    }

    return false;
}

function wpbs_part_payments_settings_page($settings)
{

    $calendars = wpbs_get_calendars(array('status' => 'active'));
    $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
    $languages = wpbs_get_languages();

    ?>

    <h2><?php echo __('Part Payments (Deposit)', 'wp-booking-system'); ?></h2>

    <!-- Enable Part Payments -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_part_payments_enable">
            <?php echo __('Enable Part Payments', 'wp-booking-system'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <label for="payment_part_payments_enable" class="wpbs-checkbox-switch">
                <input data-target="#wpbs-part-payments-wrapper" name="wpbs_settings[payment_part_payments_enable]" type="checkbox" id="payment_part_payments_enable"  class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo (!empty($settings['payment_part_payments_enable'])) ? 'checked' : ''; ?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>

    <div id="wpbs-part-payments-wrapper" class="wpbs-payment-part-payments-wrapper wpbs-settings-wrapper <?php echo (!empty($settings['payment_part_payments_enable'])) ? 'wpbs-settings-wrapper-show' : ''; ?>">

        <div class="wpbs-part-payments-wrapper">

            <!-- Amount Type -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="payment_part_payments_amount_type">
                    <?php echo __('Deposit type', 'wp-booking-system'); ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="wpbs_settings[payment_part_payments_amount_type]" id="payment_part_payments_amount_type">
                        <option <?php selected((isset($settings['payment_part_payments_amount_type']) ? $settings['payment_part_payments_amount_type'] : ''), 'percentage')?> value="percentage"><?php echo __('Percentage', 'wp-booking-system') ?></option>
                        <option <?php selected((isset($settings['payment_part_payments_amount_type']) ? $settings['payment_part_payments_amount_type'] : ''), 'fixed_amount')?> value="fixed_amount"><?php echo __('Fixed Amount', 'wp-booking-system') ?></option>
                    </select>
                </div>
            </div>

            <!-- Amount -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="payment_part_payments_amount">
                    <?php echo __('Deposit amount', 'wp-booking-system'); ?>
                    <?php echo wpbs_get_output_tooltip(__('The amount that the customer will be charged when making the booking.', 'wp-booking-system')); ?>
                </label>

                <div class="wpbs-settings-field-inner wpbs-deposit-value-field-inner">
                    <span class="input-before">
                        <span class="before">
                            <span class="deposit-type deposit-type-fixed_amount"><?php echo wpbs_get_currency(); ?></span>
                            <span class="deposit-type deposit-type-percentage">%</span>
                        </span>
                        <input name="wpbs_settings[payment_part_payments_amount]" id="payment_part_payments_amount" type="text" value="<?php echo (!empty($settings['payment_part_payments_amount']) ? esc_attr($settings['payment_part_payments_amount']) : ''); ?>" />
                    </span>
                </div>
            </div>

            <!-- Applicable Period -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="payment_part_payments_applicable_period">
                    <?php echo __('Applicable period', 'wp-booking-system'); ?>
                    <?php echo wpbs_get_output_tooltip(__('This option allows you to charge a deposit only if the booking is X days in the future. For example, if you set the applicable period to 14 days, and someone makes a booking starting one week from today, they will be charged the full amount. If they make a booking starting from 14 or more days in the future, they will be asked to pay the deposit. Zero days means the option is disabled, and deposit will always be charged.', 'wp-booking-system')); ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <input name="wpbs_settings[payment_part_payments_applicable_period]" id="payment_part_payments_applicable_period" min="0" type="number" value="<?php echo (!empty($settings['payment_part_payments_applicable_period']) ? esc_attr($settings['payment_part_payments_applicable_period']) : '0'); ?>" /> <?php echo __('days from the current date', 'wp-booking-system') ?>

                </div>
            </div>

            <!-- Calendars -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="payment_part_payments_calendars">
                    <?php echo __('Calendars', 'wp-booking-system'); ?>
                    <?php echo wpbs_get_output_tooltip(__('Select the calendars the part payments apply to. If no calendars are selected, the part payments will be applied to all calendars.', 'wp-booking-system')) ?>
                </label>

                <div class="wpbs-settings-field-inner wpbs-chosen-wrapper">
                    <select name="wpbs_settings[payment_part_payments_calendars][]" id="payment_part_payments_calendars" class="wpbs-chosen" multiple>
                        <?php foreach ($calendars as $calendar): ?>
                            <option value="<?php echo $calendar->get('id'); ?>" <?php echo isset($settings['payment_part_payments_calendars']) && in_array($calendar->get('id'), $settings['payment_part_payments_calendars']) ? 'selected' : ''; ?>><?php echo $calendar->get('name'); ?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>

            <!-- Final Payment Method -->
            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                <label class="wpbs-settings-field-label" for="payment_part_payments_method">
                    <?php echo __('Final Payment Method', 'wp-booking-system'); ?>
                    <?php echo wpbs_get_output_tooltip(__('You can charge a deposit and receive the rest of the amount in cash, or ask for the final payment to be done online using the same method (PayPal or Stripe) as the initial payment, for which you will have some extra options below.', 'wp-booking-system')); ?>
                </label>

                <div class="wpbs-settings-field-inner">
                    <select name="wpbs_settings[payment_part_payments_method]" id="payment_part_payments_method">
                        <option <?php selected((isset($settings['payment_part_payments_method']) ? $settings['payment_part_payments_method'] : ''), 'cash')?> value="cash"><?php echo __('Cash on arrival', 'wp-booking-system') ?></option>
                        <option <?php selected((isset($settings['payment_part_payments_method']) ? $settings['payment_part_payments_method'] : ''), 'initial')?> value="initial"><?php echo __('Same as initial payment', 'wp-booking-system') ?></option>
                    </select>
                </div>
            </div>

            <div id="wpbs-final-payment-options">

                <h2><?php echo __('Final Payment Options', 'wp-booking-system'); ?></h2>

                <!-- Final Payment Page -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_part_payments_page">
                        <?php echo __('Page', 'wp-booking-system'); ?>
                        <?php echo wpbs_get_output_tooltip(__('This is the page which will hold the payment form for the final payment. You will need to add the [wpbs-final-payment] shortcode to this page.', 'wp-booking-system')); ?>
                    </label>

                    <div class="wpbs-settings-field-inner">
                        <select name="wpbs_settings[payment_part_payments_page]" id="payment_part_payments_page">
                            <option value="">-</option>
                            <?php $items = get_posts(array('post_type' => 'page', 'numberposts' => -1));foreach ($items as $item): ?>
                                <option <?php selected((isset($settings['payment_part_payments_page']) ? $settings['payment_part_payments_page'] : ''), $item->ID)?> value="<?php echo $item->ID; ?>"><?php echo $item->post_title ?></option>
                            <?php endforeach;?>
                        </select>
                        <small><?php echo __('Make sure to include the [wpbs-final-payment] shortcode on this page.', 'wp-booking-system') ?></small>
                    </div>
                </div>

                <!-- Email Notification -->
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_part_payments_email_notification">
                        <?php echo __('Email Reminder', 'wp-booking-system'); ?>
                        <?php echo wpbs_get_output_tooltip(__('Send the customer an email X days before the booking to remind him about the final payment.', 'wp-booking-system')); ?>
                    </label>

                    <div class="wpbs-settings-field-inner">
                        <div class="wpbs-page-notice notice-info wpbs-form-changed-notice">
                            <p><?php echo __('You can set up an email reminder under the <strong>Payment Reminder Notifications</strong> tab when editing a <strong>Form</strong>.', 'wp-booking-system'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="wpbs-settings-field-translation-wrapper">
                    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                        <label class="wpbs-settings-field-label" for="payment_part_payments_confirmation">
                            <?php echo __('Confirmation Message', 'wp-booking-system'); ?>
                            <?php echo wpbs_get_output_tooltip(__('The confirmation message that appears after the final payment has succeeded.', 'wp-booking-system')); ?>
                        </label>

                        <div class="wpbs-settings-field-inner">
                            <?php wp_editor((!empty($settings['payment_part_payments_confirmation']) ? esc_attr($settings['payment_part_payments_confirmation']) : __('The form was successfully submitted.', 'wp-booking-system')), 'payment_part_payments_confirmation', array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false, 'textarea_name' => 'wpbs_settings[payment_part_payments_confirmation]'))?>
                            <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
                        </div>
                    </div>
                    <?php if (wpbs_translations_active()): ?>
                    <!-- Required Field Translations -->
                    <div class="wpbs-settings-field-translations">
                        <?php foreach ($active_languages as $language): ?>
                            <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-xlarge">
                                <label class="wpbs-settings-field-label" for="payment_part_payments_confirmation_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                                <div class="wpbs-settings-field-inner">
                                    <?php wp_editor((!empty($settings['payment_part_payments_confirmation_translation_' . $language]) ? esc_attr($settings['payment_part_payments_confirmation_translation_' . $language]) : ''), 'payment_part_payments_confirmation_translation_' . $language, array('teeny' => true, 'textarea_rows' => 10, 'media_buttons' => false, 'textarea_name' => 'wpbs_settings[payment_part_payments_confirmation_translation_' . $language . ']'))?>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <?php endif;?>
                </div>

            </div>

        </div>
    </div>
    <?php
}
add_action('wpbs_submenu_page_settings_tab_payment_general_bottom', 'wpbs_part_payments_settings_page', 20, 1);

/**
 * Calculate the applicable period
 *
 * @return timestamp
 *
 */
function wpbs_part_payments_get_applicable_period()
{
    $settings = get_option('wpbs_settings', array());

    if (!isset($settings['payment_part_payments_applicable_period']) || empty($settings['payment_part_payments_applicable_period'])) {
        return 0;
    }
    return mktime(0, 0, 0, current_time('n'), current_time('j'), current_time('Y')) + ($settings['payment_part_payments_applicable_period'] * DAY_IN_SECONDS);

}

/**
 * Calculate the part payments and add it to the pricing array.
 *
 * @param array         $prices
 * @param array         $post_data
 * @param int           $calendar_id
 * @param array         $form_args
 * @param WPBS_Form     $form
 * @param array         $form_fields
 *
 * @return array
 *
 */
function wpbs_get_checkout_price_part_payments($prices, $post_data, $calendar_id, $form_args, $form, $form_fields)
{
    // Check if part payments is enabled
    if (wpbs_part_payments_enabled() == false) {
        return $prices;
    }

    // Check if Payment method is not Payment on Arrival
    if ($prices['payment_method'] == 'payment_on_arrival') {
        return $prices;
    }

    $settings = get_option('wpbs_settings', array());

    // Check if part payments is applicable to this calendar
    if (isset($settings['payment_part_payments_calendars']) && !empty($settings['payment_part_payments_calendars']) && !in_array($calendar_id, $settings['payment_part_payments_calendars'])) {
        return $prices;
    }

    // Get applicable period
    $applicable_period = wpbs_part_payments_get_applicable_period();

    // Get booking start date
    $start_date = wpbs_convert_js_to_php_timestamp($post_data['calendar']['start_date']);

    // Check if booking starts after applicable period
    if ($start_date < $applicable_period) {
        return $prices;
    }

    // Calculate first and second payments
    if (isset($settings['payment_part_payments_amount_type']) && $settings['payment_part_payments_amount_type'] == 'fixed_amount') {
        $amount = absint($settings['payment_part_payments_amount']);
        $amount = apply_filters('wpbs_pricing_item_modifier', $amount, $prices, 'deposit');

        $first_payment = $amount;
    } else {
        $amount = absint($settings['payment_part_payments_amount']) / 100;
        $first_payment = ceil($prices['total'] * $amount);
    }

    $first_payment = apply_filters('wpbs_part_payments_deposit', $first_payment, $prices, $post_data, $calendar_id, $form_args, $form, $form_fields);

    $second_payment = $prices['total'] - $first_payment;

    // If deposit is larger than total amount, don't enable part payments for this
    if ($first_payment > $prices['total']) {
        return $prices;
    }

    // Apply part payment prices
    $prices['part_payments'] = array(
        'total' => round($prices['total'], 2),
        'first_payment' => round($first_payment, 2),
        'second_payment' => round($second_payment, 2),
        'method' => $settings['payment_part_payments_method'],
    );

    return $prices;
}
add_filter('wpbs_get_checkout_price_after_total', 'wpbs_get_checkout_price_part_payments', 10, 6);

/**
 * Add Part Payments to Checkout Line Items
 *
 * @param string $line_items
 * @param WPBS_Payment $payment
 *
 */
function wpbs_checkout_pricing_table_part_payments($line_items, $payment)
{
    if (!$payment->is_part_payment()) {
        return $line_items;
    }

    if ($payment->get('gateway') == 'payment_on_arrival') {
        return $line_items;
    }

    $line_items['first_payment'] = array(
        'label' => __('Deposit', 'wp-booking-system'),
        'value' => wpbs_get_formatted_price($payment->get_total_first_payment(), $payment->get_display_currency(), true),
        'class' => 'wpbs-line-item-part-payments wpbs-first-payment',
        'type' => 'part-payment-first-payment',
    );

    $line_items['second_payment'] = array(
        'label' => __('Final Payment', 'wp-booking-system'),
        'value' => wpbs_get_formatted_price($payment->get_total_second_payment(), $payment->get_display_currency(), true),
        'class' => 'wpbs-line-item-part-payments wpbs-second-payment',
        'type' => 'part-payment-second-payment',
    );

    return $line_items;
}
add_filter('wpbs_line_items_after_total', 'wpbs_checkout_pricing_table_part_payments', 10, 2);

/**
 * Overwrite the the part payments line items in the booking details popup, adding links to mark as paid and the final payment link.
 *
 * @param array $line_items
 * @param WPBS_Payment $payment
 *
 * @return array
 *
 */
function wpbs_booking_details_order_information_part_payments($line_items, $payment)
{

    if (!$payment->is_part_payment()) {
        return $line_items;
    }

    if ($payment->get('gateway') == 'payment_on_arrival') {
        return $line_items;
    }

    $settings = get_option('wpbs_settings', array());

    $first_payment_status = wpbs_booking_details_order_information_part_payments_actions($payment, 'deposit');
    $second_payment_status = wpbs_booking_details_order_information_part_payments_actions($payment, 'final_payment');

    // Part Payments
    $line_items['first_payment'] = array(
        'label' => __('Deposit', 'wp-booking-system'),
        'value' => wpbs_get_formatted_price($payment->get_total_first_payment(), $payment->get_currency()) . '<span class="wpbs-order-information-payment-actions wpbs-order-information-part-payment-actions" data-booking-payment="deposit" data-booking-id="' . $payment->get('id') . '">' . $first_payment_status . '</span>',
    );
    $line_items['second_payment'] = array(
        'label' => __('Final Payment', 'wp-booking-system'),
        'value' => wpbs_get_formatted_price($payment->get_total_second_payment(), $payment->get_currency()) . '<span class="wpbs-order-information-payment-actions wpbs-order-information-part-payment-actions" data-booking-payment="final_payment" data-booking-id="' . $payment->get('id') . '">' . $second_payment_status . '</span>',
    );


    

    $line_items[] = array(
        'label' => __(' &#8211; Final Payment Method', 'wp-booking-system'),
        'value' => ($payment->get_final_payment_method() == 'cash' ? __('Cash on arrival', 'wp-booking-system') : __('Same as initial payment', 'wp-booking-system')),
    );

    // Final Payment Link
    if (
        $payment->is_deposit_paid() &&
        !$payment->is_final_payment_paid() &&
        $payment->get_final_payment_method() == 'initial' &&
        $payment->get('gateway') != 'bank_transfer' &&
        $payment->get('order_status') == 'completed'
    ) {
        $url = get_permalink($settings['payment_part_payments_page']) . '?wpbs-payment-id=' . $payment->get('order_id');
        $value = '<a target="_blank" href="' . $url . '">'. $url . '</a>';

        $crons = _get_cron_array();
        foreach($crons as $timestamp => $cron){
            if(isset($cron['wpbs_part_payments_payment_reminder_email'])){
                foreach($cron['wpbs_part_payments_payment_reminder_email'] as $data){
                    if($data['args'][2] == $payment->get('booking_id')){
                        $value .= '<br><small>'. sprintf(__('Payment Reminder email will be sent on %s.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format'), $timestamp)) .'</small>';
                    }
                }
            }
            
        }

        $line_items[] = array('label' => __(' &#8211; Final Payment Link', 'wp-booking-system'), 'value' => $value);
    }

    return $line_items;

}
add_filter('wpbs_booking_details_order_information', 'wpbs_booking_details_order_information_part_payments', 10, 2);

/**
 * Get payment statuses for part payments and add the "mark as paid" buttons
 *
 * @param WPBS_Payment $payment
 * @param string $payment_type
 *
 * @return string
 *
 */
function wpbs_booking_details_order_information_part_payments_actions($payment, $payment_type)
{
    $html = '<span>';

    $details = $payment->get('details');

    $is_paid = 'is_' . $payment_type . '_paid';

    $html .= $payment->$is_paid() ? '<strong> (' . __('paid', 'wp-booking-system') . ')</strong>' : false;

    // Handle bank transfer
    if ($payment->get('gateway') == 'bank_transfer') {

        // exit if payment = final & deposit is not paid
        if ($payment_type == 'final_payment' && !$payment->is_deposit_paid()) {
            return $html;
        }

        // exit if payment = deposit & final payment is paid
        if ($payment_type == 'deposit' && $payment->is_final_payment_paid()) {
            return $html;
        }

        $html .= $payment->$is_paid()
        ? '<a class="wpbs-part-payment-change-status wpbs-payment-status-unpaid" href="#">' . __('mark as unpaid', 'wp-booking-system') . '</a>'
        : '<a class="wpbs-part-payment-change-status" href="#">' . __('mark as paid', 'wp-booking-system') . '</a>';
    }

    // Handle other payment methods
    if (
        $payment_type == 'final_payment' &&
        !in_array($payment->get('gateway'), array('payment_on_arrival', 'bank_transfer')) &&
        $payment->get_final_payment_method() == 'cash'
    ) {
        $html .= $payment->$is_paid()
        ? '<a class="wpbs-part-payment-change-status wpbs-payment-status-unpaid" href="#">' . __('mark as unpaid', 'wp-booking-system') . '</a>'
        : '<a class="wpbs-part-payment-change-status" href="#">' . __('mark as paid', 'wp-booking-system') . '</a>';
    }

    $html .= '</span>';

    return $html;

}

/**
 * Make strings translatable - add default strings for part payments
 *
 * @param array $strings
 *
 * @return array
 *
 */
function wpbs_part_payments_payment_default_strings($strings)
{
    $strings['amount_billed'] = __('Amount Billed', 'wp-booking-system');
    $strings['part_payments_deposit'] = __('Deposit', 'wp-booking-system');
    $strings['part_payments_final_payment_cash'] = __('Final Payment (on arrival)', 'wp-booking-system');
    $strings['part_payments_final_payment_initial'] = __('Final Payment (online, before arrival)', 'wp-booking-system');

    return $strings;
}
add_filter('wpbs_payment_default_strings', 'wpbs_part_payments_payment_default_strings');

/**
 * Make strings translatable - add form fields strings for part payments
 *
 * @param array $strings
 *
 * @return array
 *
 */
function wpbs_part_payments_payment_default_strings_labels($strings)
{
    $strings['amount_billed'] = array(
        'label' => __('Amount Billed Label', 'wp-booking-system'),
        'tooltip' => __("The label for the Amount Billed in the payment form.", 'wp-booking-system'),
    );

    $strings['part_payments_deposit'] = array(
        'label' => __('Deposit Label', 'wp-booking-system'),
        'tooltip' => __("Appears in the Pricing Table", 'wp-booking-system'),
    );

    $strings['part_payments_final_payment_cash'] = array(
        'label' => __('Final Payment (on arrival) Label', 'wp-booking-system'),
        'tooltip' => __("Appears in the Pricing Table when the Final Payment method is set to 'Cash on Arrival'", 'wp-booking-system'),
    );

    $strings['part_payments_final_payment_initial'] = array(
        'label' => __('Final Payment (online) Label', 'wp-booking-system'),
        'tooltip' => __("Appears in the Pricing Table when the Final Payment method is set to 'Same as initial payment'", 'wp-booking-system'),
    );

    return $strings;
}
add_filter('wpbs_payment_default_strings_labels', 'wpbs_part_payments_payment_default_strings_labels');

/**
 * Final Payment Shortcode
 *
 */
function wpbs_final_payment_shortcode($atts, $content = null)
{

    // Get Payment ID
    if (!isset($_GET['wpbs-payment-id']) || empty($_GET['wpbs-payment-id'])) {
        return __('Inexistent Payment ID.', 'wp-booking-system');
    }

    $order_id = sanitize_text_field($_GET['wpbs-payment-id']);

    // Get Order
    $payments = wpbs_get_payments(array('order_id' => $order_id));

    if (empty($payments)) {
        return __('Invalid Payment ID.', 'wp-booking-system');
    }

    $payment = array_shift($payments);

    $details = $payment->get('details');

    // Exit early if we need to display a payment confirmation if the order was already updated (eg. GoPay or WC)
    $output = '';
    $output = apply_filters('wpbs_final_payment_output', $output);
    if (!empty($output)) {
        return $output;
    }

    if (!$payment->is_part_payment()) {
        return __('Invalid Order ID.', 'wp-booking-system');
    }

    // Check if it's a valid order that requires second payment
    if ($payment->is_final_payment_paid()) {
        return __('Invalid Payment ID.', 'wp-booking-system');
    }

    // Check second payment method
    if ($payment->get_final_payment_method() == 'cash') {
        return false;
    }

    // Get the language the form was submitted in
    $language = wpbs_get_booking_meta($payment->get('booking_id'), 'submitted_language', true);

    /**
     * Prepare Output
     *
     */

    $output .= '<div class="wpbs-main-wrapper">';
    $output .= '<div class="wpbs-payment-confirmation wpbs-final-payment-confirmation">';

    $output .= '<form id="wpbs-final-payment-form">';
    $output .= '<input type="hidden" name="order_id" value="' . $payment->get('id') . '">';
    $output .= '<input type="hidden" name="language" value="' . $language . '">';
    $output .= wp_nonce_field('wpbs_final_payment_ajax', 'wpbs_token', true, false);
    $output .= '</form>';

    $output .= '<div class="wpbs-payment-confirmation-inner wpbs-' . $payment->get('gateway') . '-payment-confirmation-inner">';
    $output .= $payment->get_pricing_table($language);
    $output .= apply_filters('wpbs_final_payment_' . $payment->get('gateway'), '', $payment, $language);
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
add_shortcode('wpbs-final-payment', 'wpbs_final_payment_shortcode');

/**
 * AJAX Callback function for saving the final payment
 *
 */
function wpbs_save_final_payment()
{
    parse_str($_POST['post_data'], $post_data);

    // Nonce
    if (!wp_verify_nonce($post_data['wpbs_token'], 'wpbs_final_payment_ajax')) {
        return false;
    }

    // Get Payment ID
    if (!isset($post_data['order_id']) || empty($post_data['order_id'])) {
        return false;
    }

    $order_id = absint($post_data['order_id']);

    // Get Order
    $payment = wpbs_get_payment($order_id);

    if (empty($payment)) {
        return false;
    }

    $settings = get_option('wpbs_settings', array());

    do_action('wpbs_save_final_payment_' . $payment->get('gateway'), $post_data, $payment);

    $confirmation_message = (!empty($settings['payment_part_payments_confirmation_translation_' . $post_data['language']])) ? $settings['payment_part_payments_confirmation_translation_' . $post_data['language']] : (!empty($settings['payment_part_payments_confirmation']) ? $settings['payment_part_payments_confirmation'] : __('The form was successfully submitted.', 'wp-booking-system'));

    echo apply_filters('the_content', $confirmation_message);

    wp_die();
}

add_action('wp_ajax_nopriv_wpbs_save_final_payment', 'wpbs_save_final_payment');
add_action('wp_ajax_wpbs_save_final_payment', 'wpbs_save_final_payment');

/**
 * Add Payment Reminder tab to form editor page
 *
 * @param array $tabs
 *
 * @return array
 *
 */
function wpbs_submenu_page_edit_form_tabs_payment_reminder($tabs)
{

    $settings = get_option('wpbs_settings', array());

    if (!isset($settings['payment_part_payments_method']) || $settings['payment_part_payments_method'] != 'initial') {
        return $tabs;
    }

    $tabs['email-notifications']['payment_reminder'] = __('Payment Reminder Notification', 'wp-booking-system');

    return $tabs;
}
add_filter('wpbs_submenu_page_edit_form_sub_tabs', 'wpbs_submenu_page_edit_form_tabs_payment_reminder', 10, 1);

/**
 * Add Payment Reminder tab content to form editor page
 *
 */
function wpbs_submenu_page_edit_form_tab_payment_reminder()
{
    include 'form/views/view-edit-form-tab-payment-reminder.php';
}
add_action('wpbs_submenu_page_edit_form_tabs_email_notifications_payment_reminder', 'wpbs_submenu_page_edit_form_tab_payment_reminder');

/**
 * Add {Final Payment Link} email tag
 *
 * @param string $output
 *
 * @return string
 *
 */
function wpbs_email_tags_final_payment_link($tags)
{
    $settings = get_option('wpbs_settings', array());

    if (!isset($settings['payment_part_payments_method']) || $settings['payment_part_payments_method'] != 'initial') {
        return $tags;
    }

    $tags['payment']['outstanding-amount'] = 'Outstanding Amount';
    $tags['payment']['final-payment-link'] = 'Final Payment Link';

    return $tags;

}
add_filter('wpbs_email_tags', 'wpbs_email_tags_final_payment_link', 40, 1);

/**
 * Schedule email
 *
 * @param WPBS_Form     $form
 * @param WPBS_Calendar $calendar
 * @param int           $booking_id
 * @param array         $form_fields
 * @param string        $language
 * @param timestamp     $start_date
 * @param timestamp     $end_date
 *
 */
function wpbs_part_payments_submit_form_emails($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date)
{
    $settings = get_option('wpbs_settings', array());

    if (!isset($settings['payment_part_payments_method'])) {
        return false;
    }

    if ($settings['payment_part_payments_method'] != 'initial') {
        return false;
    }

    if (wpbs_get_form_meta($form->get('id'), 'payment_notification_enable', true) != 'on') {
        return false;
    }

    $payment = wpbs_get_payment_by_booking_id($booking_id);
    if (empty($payment)) {
        return false;
    }

    $details = $payment->get('details');

    // If payment has a deposit
    if (!$payment->is_deposit_paid() && $payment->get('gateway') != 'bank_transfer') {
        return false;
    }

    // When to send?
    $days_before = wpbs_get_form_meta($form->get('id'), 'payment_notification_when_to_send', true) * DAY_IN_SECONDS;
    $when_to_send = $start_date - $days_before;

    // Schedule email
    wp_schedule_single_event($when_to_send, 'wpbs_part_payments_payment_reminder_email', array($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date));
}
add_action('wpbs_submit_form_emails', 'wpbs_part_payments_submit_form_emails', 10, 7);

/**
 * Callback function for setting the email reminder schedule.
 *
 */
function wpbs_part_payments_payment_reminder_email($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date)
{

    $booking = wpbs_get_booking($booking_id);

    if (is_null($booking)) {
        return false;
    }

    if ($booking->get('status') == 'trash') {
        return false;
    }

    $email = new WPBS_Form_Mailer($form, $calendar, $booking_id, $form_fields, $language, $start_date, $end_date);
    $email->prepare('payment');
    $email->send();
}
add_action('wpbs_part_payments_payment_reminder_email', 'wpbs_part_payments_payment_reminder_email', 10, 7);

/**
 * Ajax Callback for changing the payment status on part payments
 *
 */
function wpbs_action_ajax_booking_part_payment_change_status()
{
    // Nonce
    check_ajax_referer('wpbs_change_payment_status', 'wpbs_token');

    if (!isset($_POST['id'])) {
        return false;
    }

    $payment_id = absint($_POST['id']);

    if (!isset($_POST['payment_type'])) {
        return false;
    }

    $payment_type = $_POST['payment_type'];

    // Get payment
    $payment = wpbs_get_payment($payment_id);

    if (is_null($payment)) {
        return;
    }

    $details = $payment->get('details');

    $details['part_payments'][$payment_type] = (isset($details['part_payments'][$payment_type]) && $details['part_payments'][$payment_type] == true) ? false : true;

    wpbs_update_payment($payment_id, array(
        'details' => $details,
    ));

    wp_die();

}
add_action('wp_ajax_wpbs_booking_part_payment_change_status', 'wpbs_action_ajax_booking_part_payment_change_status');

/**
 * Ajax Callback for updating the HTML for the payment status on part payments
 *
 */
function wpbs_action_ajax_booking_part_payment_update_status()
{
    // Nonce
    check_ajax_referer('wpbs_change_payment_status', 'wpbs_token');

    if (!isset($_POST['id'])) {
        return false;
    }

    $payment_id = absint($_POST['id']);

    if (!isset($_POST['payment_type'])) {
        return false;
    }

    $payment_type = $_POST['payment_type'];

    $payment = wpbs_get_payment($payment_id);

    echo wpbs_booking_details_order_information_part_payments_actions($payment, $payment_type);

    wp_die();
}
add_action('wp_ajax_wpbs_booking_part_payment_update_status', 'wpbs_action_ajax_booking_part_payment_update_status');