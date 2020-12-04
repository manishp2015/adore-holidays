<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Booking_Details_Outputter
{

    /**
     * The calendar
     *
     * @access protected
     * @var    WPBS_Calendar
     *
     */
    protected $calendar_id;

    /**
     * The booking
     *
     * @access protected
     * @var    WPBS_Booking
     *
     */
    protected $booking;

    /**
     * Tabs
     *
     * @access protected
     * @var    array
     *
     */
    protected $tabs;

    /**
     * Plugin Settings
     *
     * @access protected
     * @var    array
     *
     */
    protected $plugin_settings;

    /**
     * Constructor
     *
     * @param WPBS_Booking $booking
     *
     */
    public function __construct($booking)
    {
        /**
         * Get Booking
         *
         */
        $this->booking = $booking;

        /**
         * Get Calendar
         *
         */
        $this->calendar = wpbs_get_calendar($this->booking->get('calendar_id'));

        /**
         * Set plugin settings
         *
         */
        $this->plugin_settings = get_option('wpbs_settings', array());

        /**
         * Set default tabs
         *
         */
        $this->tabs = array(
            'manage-booking' => __('Manage Booking', 'wp-booking-system'),
            'booking-details' => __('Booking Details', 'wp-booking-system'),
            'email-customer' => __('Email Customer', 'wp-booking-system'),
            'email-logs' => __('Email Logs', 'wp-booking-system'),
        );

        $this->tabs = apply_filters('wpbs_booking_modal_tabs', $this->tabs, $this->booking);

        $this->check_tabs();

        

    }

    /**
     * Displays the modal HTML
     *
     */
    public function display()
    {
        include 'views/view-modal.php';
    }

    /**
     * Show or hide tabs depending on the stastus of the booking
     *
     */
    protected function check_tabs()
    {
        if ($this->booking->get('status') == 'trash') {
            unset($this->tabs['email-customer']);
        }

        if ($this->get_email_addresses() === false) {
            unset($this->tabs['email-customer']);
            unset($this->tabs['email-logs']);
        }

        if(!isset($this->plugin_settings['email_logs']) || $this->plugin_settings['email_logs'] != 'on'){
            unset($this->tabs['email-logs']);
        }

    }

    /**
     * Get the active tab depending on the stastus of the booking
     *
     * @return string
     *
     */
    protected function get_active_tab()
    {
        if ($this->booking->get('status') == 'accepted') {
            return 'booking-details';
        }
        return $this->active_tab = 'manage-booking';
    }

    /**
     * Get the button label depending on the stastus of the booking
     *
     * @return string
     *
     */
    protected function get_manage_booking_button_label()
    {
        if ($this->booking->get('status') == 'pending') {
            return __('Accept Booking', 'wp-booking-system');
        } else if ($this->booking->get('status') == 'trash') {
            return __('Restore Booking', 'wp-booking-system');
        }
        return __('Update Booking', 'wp-booking-system');
    }

    /**
     * Get email heading depending on the stastus of the booking
     *
     * @return string
     *
     */
    protected function get_email_customer_heading()
    {
        if ($this->booking->get('status') == 'pending') {
            return __('Send an email to the customer when accepting the booking', 'wp-booking-system');
        }
        return __('Send an email to the customer when updating the booking', 'wp-booking-system');
    }

    /**
     * Get booking data
     *
     * @return array
     *
     */
    protected function get_booking_data()
    {
        $data = array();

        $data[] = array(
            'editable' => false,
            'name' => 'booking_id',
            'label' => __('Booking ID', 'wp-booking-system'),
            'value' => '#' . $this->booking->get('id'),
        );

        $data[] = array(
            'editable' => true,
            'time' => strtotime($this->booking->get('start_date')),
            'name' => 'start_date',
            'label' => __('Start Date', 'wp-booking-system'),
            'value' => wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('start_date'))),
        );

        $data[] = array(
            'editable' => true,
            'time' => strtotime($this->booking->get('end_date')),
            'name' => 'end_date',
            'label' => __('End Date', 'wp-booking-system'),
            'value' => wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('end_date'))),
        );

        $data[] = array(
            'editable' => false,
            'name' => 'booked_on',
            'label' => __('Booked on', 'wp-booking-system'),
            'value' => wpbs_date_i18n(get_option('date_format'), strtotime($this->booking->get('date_created'))),
        );

        if (wpbs_translations_active()) {

            $languages = wpbs_get_languages();

            $data[] = array(
                'editable' => false,
                'name' => 'language',
                'label' => __('Language', 'wp-booking-system'),
                'value' => $languages[wpbs_get_booking_meta($this->booking->get('id'), 'submitted_language', true)],
            );
        }

        $crons = _get_cron_array();

        foreach ($crons as $timestamp => $cron) {
            if (isset($cron['wpbs_er_reminder_email'])) {
                foreach ($cron['wpbs_er_reminder_email'] as $job) {
                    if ($job['args'][2] == $this->booking->get('id')) {
                        $data[] = array(
                            'editable' => false,
                            'name' => 'language',
                            'label' => __('Email Reminder', 'wp-booking-system'),
                            'value' => sprintf(__('Scheduled to be sent sent on %s.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format'), $timestamp))
                            . ($this->booking->get('status') != 'accepted' ? '<br><small><em>' . __('Email will be sent only if the booking is Accepted.', 'wp-booking-system') . '</em></small>' : ''),
                        );

                    }
                }
            }

            if (isset($cron['wpbs_er_follow_up_email'])) {
                foreach ($cron['wpbs_er_follow_up_email'] as $job) {
                    if ($job['args'][2] == $this->booking->get('id')) {
                        $data[] = array(
                            'editable' => false,
                            'name' => 'language',
                            'label' => __('Follow up Email', 'wp-booking-system'),
                            'value' => sprintf(__('Scheduled to be sent sent on %s.', 'wp-booking-system'), wpbs_date_i18n(get_option('date_format'), $timestamp))
                            . ($this->booking->get('status') != 'accepted' ? '<br><em><small>' . __('Email will be sent only if the booking is Accepted.', 'wp-booking-system') . '</small></em>' : ''),
                        );

                    }
                }
            }

        }

        return $data;
    }

    /**
     * Get form data
     *
     * @return array
     *
     */
    protected function get_form_data()
    {

        $data = array();

        foreach ($this->booking->get('fields') as $field) {

            if (in_array($field['type'], wpbs_get_excluded_fields(array('hidden')))) {
                continue;
            }

            // Get value
            $value = (isset($field['user_value'])) ? $field['user_value'] : '';

            // Handle Pricing options differently
            if (wpbs_form_field_is_product($field['type'])) {
                $value = wpbs_get_form_field_product_values($field);
            }

            $value = wpbs_get_field_display_user_value($value);

            if ($field['type'] == 'textarea') {
                $value = nl2br($value);
            }

            if ($field['type'] == 'payment_method') {
                $value = isset(wpbs_get_payment_methods()[$value]) ? wpbs_get_payment_methods()[$value] : '';
            }

            $data[] = array(
                'field' => $field,
                'editable' => (in_array($field['type'], array('consent', 'payment_method', 'coupon')) ? false : true),
                'label' => $this->get_translated_label($field),
                'value' => $value,
            );
        }

        return $data;

    }

    /**
     * Get notes
     *
     * @return array
     *
     */
    protected function get_notes()
    {
        return wpbs_get_booking_meta($this->booking->get('id'), 'booking_notes', true);
    }

    /**
     * Helper function to get label translations
     *
     * @param array $field
     *
     * @return string
     *
     */
    protected function get_translated_label($field)
    {
        $language = wpbs_get_locale();

        if (isset($field['values'][$language]['label']) && !empty($field['values'][$language]['label'])) {
            return $field['values'][$language]['label'];
        }

        return $field['values']['default']['label'];
    }

    /**
     * Get the calendar edirot
     *
     * @return string
     *
     */
    protected function calendar_editor()
    {

        $output = '';

        // Set start date
        $start_date = new DateTime();
        $start_date->setTimestamp(strtotime($this->booking->get('start_date')));

        // Set end date
        $end_date = new DateTime();
        $end_date->setTimestamp(strtotime($this->booking->get('end_date')));
        $end_date->modify('+1 day');

        // Set loop interval
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start_date, $interval, $end_date);

        $months = array();

        // Loop through dates
        foreach ($period as $date) {
            // Set the first day of the month
            if (!isset($months[$date->format('n')]['start'])) {
                $months[$date->format('n')]['start'] = $date->getTimestamp();
            }

            // Set the last day of the month
            $months[$date->format('n')]['end'] = $date->getTimestamp();
        }

        // Output Calendar Editor
        foreach ($months as $month => $days) {
            $month_object = DateTime::createFromFormat('!m', $month);
            $output .= '<h3>' . wpbs_date_i18n('F', $month_object->getTimestamp()) . '</h3>';

            $calendar_args = array(
                'current_year' => date('Y', $days['start']),
                'current_month' => date('n', $days['start']),
                'booking_view' => true,
                'booking_start_date' => $days['start'],
                'booking_end_date' => $days['end'],
            );
            $calendar_editor_outputter = new WPBS_Calendar_Editor_Outputter($this->calendar, $calendar_args);
            $output .= $calendar_editor_outputter->get_display();
        }

        return $output;
    }

    /**
     * Get calendar legends as <option> tags
     *
     * @return string
     *
     */
    protected function get_legends_as_options()
    {

        $legend_items = wpbs_get_legend_items(array('calendar_id' => $this->calendar->get('id')));

        $output = '';
        foreach ($legend_items as $legend_item) {

            $output .= '<option value="' . esc_attr($legend_item->get('id')) . '">' . $legend_item->get('name') . '</option>';

        }

        return $output;
    }

    /**
     * Get the email addresses submitted in the form
     *
     * @return array
     *
     */
    protected function get_email_addresses()
    {

        $emails = array();

        foreach ($this->booking->get('fields') as $field) {
            if ($field['type'] != 'email') {
                continue;
            }

            if (empty($field['user_value'])) {
                continue;
            }

            $emails[] = $field['user_value'];
        }

        if (empty($emails)) {
            return false;
        }

        return $emails;

    }

    /**
     * Get the email addresses submitted in the form as <option> tags
     *
     * @return string
     *
     */
    protected function get_email_addresses_as_options()
    {

        $emails = $this->get_email_addresses();

        $output = '';

        if (!empty($emails)) {
            foreach ($emails as $email) {
                $output .= '<option value="' . $email . '">' . $email . '</option>';
            }
        }

        return $output;

    }

}
