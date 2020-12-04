<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Settings page option to enable pricing when no payment Add-on is active.
 *
 */
function wpbs_submenu_page_settings_tab_general_enable_pricing($settings)
{

    if (defined('WPBS_ENABLE_PRICING') && WPBS_ENABLE_PRICING === true) {
        return false;
    }

    ?>
    <!-- Enable Pricing -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="enable_pricing">
            <?php echo __('Enable Pricing', 'wp-booking-system'); ?>
            <?php echo wpbs_get_output_tooltip(__('Enabling this option will allow you to add prices to calendar dates, add a price calculator to the form and enable the "Payment on Arrival" and "Bank Transfer" payment methods.', 'wp-booking-system')); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <label for="enable_pricing" class="wpbs-checkbox-switch">
                <input  name="wpbs_settings[enable_pricing]" type="checkbox" id="enable_pricing" class="regular-text wpbs-settings-toggle " <?php echo (!empty($settings['enable_pricing'])) ? 'checked' : ''; ?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>
    <?php

}
add_action('wpbs_submenu_page_settings_tab_general_bottom', 'wpbs_submenu_page_settings_tab_general_enable_pricing');

/**
 * Add Price Header to Calendar Editor
 *
 */
function wpbs_calendar_editor_columns_header_price($output)
{
    if (!wpbs_is_pricing_enabled()) {
        return $output;
    }

    $output .= '<div class="wpbs-calendar-date-price-header">' . __('Price', 'wp-booking-system') . '</div>';

    return $output;
}
add_filter('wpbs_calendar_editor_columns_header_before_description', 'wpbs_calendar_editor_columns_header_price');

/**
 * Add Price Field Input to Calendar Editor
 *
 */
function wpbs_calendar_editor_columns_price($output, $year, $month, $day, $event, $data, $default_price)
{

    if (!wpbs_is_pricing_enabled()) {
        return $output;
    }

    $value = '';

    if (!is_null($data) && isset($data['price'])) {
        $value = $data['price'];
    } elseif (!is_null($event)) {
        $value = $event->get('price');
    }

    $output .= '<div class="wpbs-calendar-date-price">';

    $output .= '<span class="dashicons dashicons-tag"></span>';
    $output .= '<input type="number" min="0" value="' . esc_attr($value) . '" placeholder="' . $default_price . '" data-name="price" data-year="' . esc_attr($year) . '" data-month="' . esc_attr($month) . '" data-day="' . esc_attr($day) . '" />';

    $output .= '</div>';

    return $output;

}
add_filter('wpbs_calendar_editor_columns_before_description', 'wpbs_calendar_editor_columns_price', 10, 7);

/**
 * Add custom field types
 *
 */
function wpbs_add_pricing_field_types_options($options)
{

    if (!wpbs_is_pricing_enabled()) {
        return $options;
    }

    $options['options_pricing'] = array('key' => 'options_pricing', 'label' => __('Options', 'wp-booking-system'), 'translatable' => true);
    $options['pricing'] = array('key' => 'pricing', 'label' => __('Price', 'wp-booking-system'), 'translatable' => false);
    $options['line_label'] = array('key' => 'line_label', 'label' => __('Custom "Total" Label', 'wp-booking-system'), 'translatable' => true, 'default_value' => '%%');
    $options['pricing_type'] = array('key' => 'pricing_type', 'label' => __('Price Calculation', 'wp-booking-system'), 'translatable' => false, 'options' => array('per_day' => __('Per Day - Multiply by the number of booked days', 'wp-booking-system'), 'per_booking' => __('Per Booking - Only add once per booking', 'wp-booking-system')));
    $options['multiplication'] = array('key' => 'multiplication', 'label' => __('Multiplication', 'wp-booking-system'), 'translatable' => false);
    $options['date_range'] = array('key' => 'date_range', 'label' => __('Applicable Period', 'wp-booking-system'), 'translatable' => false);
    $options['date_range_type'] = array('key' => 'date_range_type', 'label' => __('Period Type', 'wp-booking-system'), 'translatable' => false, 'options' => array('once' => __('Fixed Date - Only between the selected dates', 'wp-booking-system'), 'yearly' => __('Recurring - Between the selected dates, but each year', 'wp-booking-system')));

    return $options;
}
add_filter('wpbs_form_available_field_types_options', 'wpbs_add_pricing_field_types_options', 10, 1);

/**
 * Add Custom Product Field to Form Builder
 *
 */
function wpbs_form_available_field_types_custom_product($fields)
{

    if (!wpbs_is_pricing_enabled()) {
        return $fields;
    }

    $fields['product_field'] = array(
        'type' => 'product_field',
        'group' => 'pricing',
        'supports' => array(
            'primary' => array('label', 'pricing', 'pricing_type', 'multiplication'),
            'secondary' => array('date_range', 'date_range_type'),
        ),
        'values' => array(),
    );

    $fields['product_dropdown'] = array(
        'type' => 'product_dropdown',
        'group' => 'pricing',
        'supports' => array(
            'primary' => array('label', 'required', 'options_pricing', 'pricing_type'),
            'secondary' => array('multiplication', 'line_label', 'description', 'placeholder', 'class', 'hide_label'),
        ),
        'values' => array(),
    );

    $fields['product_checkbox'] = array(
        'type' => 'product_checkbox',
        'group' => 'pricing',
        'supports' => array(
            'primary' => array('label', 'required', 'options_pricing', 'pricing_type'),
            'secondary' => array('multiplication', 'line_label', 'description', 'class', 'hide_label'),
        ),
        'values' => array(),
    );

    $fields['product_radio'] = array(
        'type' => 'product_radio',
        'group' => 'pricing',
        'supports' => array(
            'primary' => array('label', 'required', 'options_pricing', 'pricing_type'),
            'secondary' => array('multiplication', 'line_label', 'description', 'class', 'hide_label'),
        ),
        'values' => array(),
    );

    return $fields;
}
add_filter('wpbs_form_available_field_types', 'wpbs_form_available_field_types_custom_product', 5, 1);

/**
 * Add Total Field to Form Builder
 *
 */
function wpbs_form_available_field_types_total_field($fields)
{

    if (!wpbs_is_pricing_enabled()) {
        return $fields;
    }

    $fields['total'] = array(
        'type' => 'total',
        'group' => 'pricing',
        'supports' => array(
            'primary' => array('label'),
            'secondary' => array('description', 'class', 'hide_label'),
        ),
        'values' => array(),
    );

    return $fields;
}
add_filter('wpbs_form_available_field_types', 'wpbs_form_available_field_types_total_field', 15, 1);

/**
 * Add Payment Method to Form Builder
 *
 */
function wpbs_form_available_field_types_payment_method($fields)
{

    if (!wpbs_is_pricing_enabled()) {
        return $fields;
    }

    $fields['payment_method'] = array(
        'type' => 'payment_method',
        'group' => 'pricing',
        'supports' => array(
            'primary' => array('label'),
            'secondary' => array('description', 'class', 'hide_label'),
        ),
        'values' => array(),
    );

    return $fields;
}
add_filter('wpbs_form_available_field_types', 'wpbs_form_available_field_types_payment_method', 15, 1);

/**
 * Get the default currency
 *
 */
function wpbs_get_currency()
{

    $currency = 'USD';

    $settings = get_option('wpbs_settings');

    if (isset($settings['payment_currency']) && !empty($settings['payment_currency'])) {
        $currency = $settings['payment_currency'];
    }

    return apply_filters('wpbs_currency', $currency);

}

/**
 * List of all currencies
 *
 */
function wpbs_get_currencies()
{

    $currencies = array(
        'AUD' => 'Australian dollar',
        'BRL' => 'Brazilian real',
        'CAD' => 'Canadian dollar',
        'CZK' => 'Czech koruna',
        'DKK' => 'Danish krone',
        'EUR' => 'Euro',
        'HKD' => 'Hong Kong dollar',
        'HUF' => 'Hungarian forint',
        'INR' => 'Indian rupee',
        'ILS' => 'Israeli new shekel',
        'JPY' => 'Japanese yen',
        'MYR' => 'Malaysian ringgit',
        'MXN' => 'Mexican peso',
        'NZD' => 'New Zealand dollar',
        'NOK' => 'Norwegian krone',
        'PHP' => 'Philippine peso',
        'PLN' => 'Polish złoty',
        'GBP' => 'Pound sterling',
        'RUB' => 'Russian ruble',
        'SGD' => 'Singapore dollar',
        'SEK' => 'Swedish krona',
        'CHF' => 'Swiss franc',
        'THB' => 'Thai baht',
        'USD' => 'United States dollar',
    );

    $currencies = apply_filters('wpbs_currencies', $currencies);

    ksort($currencies);

    return $currencies;

}

/**
 * List of all the available payment methods
 *
 */
function wpbs_get_payment_methods()
{
    $payment_methods = array(
        'payment_on_arrival' => 'Payment on Arrival',
        'bank_transfer' => 'Bank Transfer',
    );

    $payment_methods = apply_filters('wpbs_payment_methods', $payment_methods);

    return $payment_methods;
}

/**
 * List of all the active payment methods
 *
 */
function wpbs_get_active_payment_methods()
{
    $payment_methods = wpbs_get_payment_methods();

    $active_payment_methods = array();

    foreach ($payment_methods as $payment_method_slug => $payment_method_name) {
        if (apply_filters('wpbs_form_outputter_payment_method_enabled_' . $payment_method_slug, false) === true) {
            $active_payment_methods[] = $payment_method_slug;
        }
    }

    return $active_payment_methods;
}

/**
 * Add the Pricing meta box to the calendar editor page.
 *
 * @param $calendar
 *
 */
function wpbs_calendar_editor_price_metabox($calendar)
{

    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    ?>
    <!-- Pricing -->
    <div class="postbox">

        <h3 class="hndle"><?php echo __('Pricing', 'wp-booking-system'); ?></h3>

        <div class="inside">
            <p>
                <label for="wpbs-default-price"><?php echo __('Default Price', 'wp-booking-system'); ?></label>
                <span class="input-before">
                    <span class="before"><?php echo wpbs_get_currency(); ?></span>
                    <input id="wpbs-default-price" name="default_price" min="0" type="number" value="<?php echo wpbs_get_calendar_meta($calendar->get('id'), 'default_price', true); ?>" />
                </span>

            </p>
        </div>

        <div class="wpbs-plugin-card-bottom plugin-card-bottom">
            <a href="#" class="button button-secondary wpbs-save-calendar"><span class="dashicons dashicons-tag"></span> <?php echo __('Set Default Price', 'wp-booking-system'); ?></a>
        </div>

    </div><!-- / Pricing -->
    <?php
}
add_action('wpbs_view_edit_calendar_sidebar_before', 'wpbs_calendar_editor_price_metabox');

/**
 * Save the default price in the database
 *
 * @param $calendar
 *
 */
function wpbs_save_calendar_default_price($post_data)
{

    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    if (!isset($post_data['form_data']['default_price'])) {
        return false;
    }

    $calendar_id = absint($post_data['form_data']['calendar_id']);

    wpbs_update_calendar_meta($calendar_id, 'default_price', $post_data['form_data']['default_price']);
}
add_action('wpbs_save_calendar_data', 'wpbs_save_calendar_default_price');

/**
 * Add the price field to the bulk editor
 *
 */
function wpbs_bulk_editor_add_pricing()
{

    if (!wpbs_is_pricing_enabled()) {
        return false;
    }

    ?>
    <!-- Price -->
    <p>
        <label for="wpbs-bulk-edit-availability-price"><?php echo __('Price', 'wp-booking-system'); ?></label>
        <input id="wpbs-bulk-edit-availability-price" type="number" min="0" />
    </p>
    <?php

}
add_action('wpbs_view_edit_calendar_bulk_editor_before', 'wpbs_bulk_editor_add_pricing');

/**
 * Ajax request to calculate the form price before submitting the form
 *
 */
function wpbs_ajax_calculate_pricing()
{

    // Get Form ID
    $form_id = absint(!empty($_POST['form']['id']) ? $_POST['form']['id'] : 0);
    $form = wpbs_get_form($form_id);

    // Get Calendar ID
    $calendar_id = absint(!empty($_POST['calendar']['id']) ? $_POST['calendar']['id'] : 0);
    $calendar = wpbs_get_calendar($calendar_id);

    // Set form data without validating
    $form_validator = new WPBS_Form_Validator($form, $calendar, $_POST['post_data'], $_POST['form']['language']);
    $form_validator->sanitize_fields();
    $form_fields = $form_validator->get_form_fields();

    // Set the form arguments
    $form_args = array(
        'minimum_days' => (int) $_POST['form']['minimum_days'],
        'maximum_days' => (int) $_POST['form']['maximum_days'],
        'booking_start_day' => (int) $_POST['form']['booking_start_day'],
        'booking_end_day' => (int) $_POST['form']['booking_end_day'],
        'selection_type' => $_POST['form']['selection_type'],
        'selection_style' => $_POST['form']['selection_style'],
        'auto_pending' => $_POST['form']['auto_pending'],
        'show_date_selection' => (int) $_POST['form']['show_date_selection'],
        'language' => ($_POST['form']['language'] == 'auto' ? wpbs_get_locale() : $_POST['form']['language']),
    );

    // Calculate prices
    $payment = new WPBS_Payment;
    $payment->calculate_prices($_POST, $form, $form_args, $form_fields);

    // Output pricing table
    echo $payment->get_pricing_table($form_args['language']);

    wp_die();
}

add_action('wp_ajax_nopriv_wpbs_calculate_pricing', 'wpbs_ajax_calculate_pricing');
add_action('wp_ajax_wpbs_calculate_pricing', 'wpbs_ajax_calculate_pricing');

/**
 * Check if post data exists and matches form values
 *
 * @param array $form_fields
 *
 * @return bool
 *
 */
function wpbs_validate_payment_form_consistency($form_fields)
{

    foreach ($form_fields as $form_field) {
        if (wpbs_form_field_is_product($form_field['type']) && !empty($form_field['user_value'])) {

            // Test price on product field
            if ($form_field['type'] == 'product_field') {

                if(isset($form_field['values']['default']['pricing']) && empty($form_field['values']['default']['pricing'])) {
                    $form_field['values']['default']['pricing'] = 0;
                }

                if(explode('|', $form_field['user_value'])[0] != $form_field['values']['default']['pricing']){
                    return false;
                }

            }

            // Test price on selectable product fields
            $options = (!is_array($form_field['user_value'])) ? array($form_field['user_value']) : $form_field['user_value'];

            foreach ($options as $option) {

                if (empty($option)) {
                    continue;
                }

                // Check if option exists in form field.
                if (_wpbs_recursive_array_search($option, $form_field) === false) {
                    return false;
                }
            }
        }
    }
    return true;
}

/**
 * Helper function to format the price
 *
 * @param int $price
 * @param string $currency
 *
 * @return string
 */
function wpbs_get_formatted_price($price, $currency, $markup = false)
{

    $settings = get_option('wpbs_settings');

    $decimal_separator = '.';
    $thousands_separator = ',';

    if(isset($settings['price_format']) && $settings['price_format'] == 2){
        $decimal_separator = ',';
        $thousands_separator = '.';
    }

    $price = apply_filters('wpbs_price_format', number_format($price, 2, $decimal_separator, $thousands_separator), $price, $currency);

    if ($markup) {
        return '<span class="wpbs-price">' . $price . ' ' . '<span class="wpbs-price-currency">' . $currency . '</span></span>';
    }

    return $price . ' ' . $currency;
}

/**
 * Helper function that checks if a form field is a product form field.
 *
 */
function wpbs_form_field_is_product($field_type)
{
    return in_array($field_type, array('product_checkbox', 'product_radio', 'product_dropdown', 'product_field'));
}

/**
 * Helper function to get the value of a product form field.
 *
 * @param array $field
 *
 * @return array
 *
 */
function wpbs_get_form_field_product_values($field)
{

    if (empty($field['user_value'])) {
        return false;
    }

    $values = array();

    $options = (!is_array($field['user_value'])) ? array($field['user_value']) : $field['user_value'];

    foreach ($options as $option) {
        if (is_null($option)) {
            continue;
        }

        // Explode and get key value pairs
        $option = explode('|', $option);
        $values[] = $option[1];
    }
    return $values;
}

/**
 * Output the payment confirmation view
 *
 * @param array     $form_outputter
 * @param WPBS_Payment     $payment
 * @param string    $payment_gateway
 * @param string    $custom_output
 *
 * @return string
 */
function wpbs_form_payment_confirmation_screen($form_outputter, $payment, $payment_gateway, $custom_output)
{

    $output = '<div class="wpbs-payment-confirmation wpbs-' . $payment_gateway . '-payment-confirmation wpbs-' . $payment_gateway . '-payment-confirmation-' . $form_outputter->get_unique() . '">';

    $output .= $form_outputter->get_display();

    $output .= '<h2>' . wpbs_get_payment_default_string('payment_confirmation', $form_outputter->get_language()) . '</h2>';

    $output .= '<div class="wpbs-payment-confirmation-inner wpbs-' . $payment_gateway . '-payment-confirmation-inner wpbs-' . $payment_gateway . '-payment-confirmation-inner-' . $form_outputter->get_unique() . '">';

    $output .= '<h4>' . wpbs_get_payment_default_string('your_order', $form_outputter->get_language()) . '</h4>';

    $output .= $payment->get_pricing_table($form_outputter->get_language());

    $output .= $custom_output;

    $output .= '</div>';
    $output .= '</div>';

    return $output;
}

/**
 * Add {Order Details} email tag
 *
 */
function wpbs_email_tags_pricing_tags($tags)
{

    if (!wpbs_is_pricing_enabled()) {
        return $tags;
    }

    $tags['payment']['order-details'] = 'Order Details';
    $tags['payment']['total-amount'] = 'Total Amount';

    return $tags;

}
add_filter('wpbs_email_tags', 'wpbs_email_tags_pricing_tags', 10, 1);

/**
 * Default Payment strings
 *
 * @return array
 */
function wpbs_payment_default_strings()
{
    $strings = array(
        'payment_confirmation' => __('Payment Confirmation', 'wp-booking-system'),
        'total' => __('Total', 'wp-booking-system'),
        'subtotal' => __('Subtotal', 'wp-booking-system'),
        'item' => __('Item', 'wp-booking-system'),
        'processing_payment' => __('Processing payment, please wait...', 'wp-booking-system'),
        'your_order' => __('Your Order', 'wp-booking-system'),
        'select_dates' => __('Please select a date first.', 'wp-booking-system'),
    );

    $strings = apply_filters('wpbs_payment_default_strings', $strings);

    return $strings;
}

/**
 * Get Payment Strings
 *
 * @param string $string
 * @param string $language
 *
 * @return string
 *
 */
function wpbs_get_payment_default_string($string, $language)
{
    $settings = get_option('wpbs_settings');
    if (!empty($settings['payment_strings'][$string . '_translation_' . $language])) {
        return esc_attr($settings['payment_strings'][$string . '_translation_' . $language]);
    }

    if (!empty($settings['payment_strings'][$string])) {
        return esc_attr($settings['payment_strings'][$string]);
    }

    return wpbs_payment_default_strings()[$string];

}

/**
 * Check if a booking has a total price of 0, and skip the payment screen.
 *
 */
function wpbs_check_zero_price_booking($response, $post_data, $form, $form_args, $form_fields, $calendar_id)
{
    if ($response === false) {
        return false;
    }

    $payment = new WPBS_Payment;
    $prices = $payment->calculate_prices($post_data, $form, $form_args, $form_fields);

    if ($payment->get_total() == 0) {
        return false;
    }

    return $response;

}
add_filter('wpbs_submit_form_before', 'wpbs_check_zero_price_booking', 99, 6);

/**
 * Check if a booking has a total price of 0, save it to the database as "Paid".
 *
 */
function wpbs_save_zero_price_booking($booking_id, $post_data, $form, $form_args, $form_fields)
{

    $payment = new WPBS_Payment;
    $prices = $payment->calculate_prices($post_data, $form, $form_args, $form_fields);

    if ($payment->get_total() == 0) {

        $gateway = false;

        // Check if payment method is enabled.
        foreach ($form_fields as $form_field) {
            if ($form_field['type'] == 'payment_method') {
                $gateway = $form_field['user_value'];
                break;
            }
        }

        if ($gateway === false) {
            return false;
        }

        $details['price'] = $prices;
        $details['raw'] = false;

        // Save Order
        wpbs_insert_payment(array(
            'booking_id' => $booking_id,
            'gateway' => $gateway,
            'order_id' => 'N/A',
            'order_status' => 'completed',
            'details' => $details,
            'date_created' => current_time('Y-m-d H:i:s'),
        ));
    }
}

add_action('wpbs_submit_form_after', 'wpbs_save_zero_price_booking', 99, 5);
