<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_Form_Handler
{
    /**
     * The $_POST data when submitting the form
     *
     * @access protected
     * @var    array
     *
     */
    protected $post_data;

    /**
     * The WPBS_Form
     *
     * @access protected
     * @var    WPBS_Form
     *
     */
    protected $form;

    /**
     * The form id
     *
     * @access protected
     * @var    int
     *
     */
    protected $form_id;

    /**
     * The form arguments
     *
     * @access protected
     * @var    array
     *
     */
    protected $form_args;

    /**
     * The form fields, validated and sanitized
     *
     * @access protected
     * @var    array
     *
     */
    protected $form_fields;

    /**
     * The calendar ID
     *
     * @access protected
     * @var    int
     *
     */
    protected $calendar_id;

    /**
     * The WPBS_Calendar
     *
     * @access protected
     * @var    WPBS_Calendar
     *
     */
    protected $calendar;

    /**
     * The language the form was submitted in
     *
     * @access protected
     * @var    string
     *
     */
    protected $language;

    /**
     * The booking start date
     *
     * @access protected
     * @var    timestamp
     *
     */
    protected $start_date;

    /**
     * The booking end date
     *
     * @access protected
     * @var    timestamp
     *
     */
    protected $end_date;

    /**
     * The booking ID
     *
     * @access protected
     * @var    int
     *
     */
    protected $booking_id;

    /**
     * The response
     *
     * @access protected
     * @var    array
     *
     */
    protected $response = array('success' => true);

    /**
     * Constructor
     *
     * @param array         $post_data
     * @param int           $form_id
     * @param array         $form_args
     * @param array         $form_fields
     * @param int           $calendar_id
     *
     *
     */
    public function __construct($post_data, $form_id, $form_args, $form_fields, $calendar_id)
    {
        //Set the post data
        $this->post_data = $post_data;

        //Set the form and form id
        $this->form_id = $form_id;
        $this->form = wpbs_get_form($this->form_id);

        //Set the form args
        $this->form_args = $form_args;

        //Set the form fields
        $this->form_fields = $form_fields;

        //Set the calendar and calendar id
        $this->calendar_id = $calendar_id;
        $this->calendar = wpbs_get_calendar($this->calendar_id);

        //Set the language
        $this->language = $this->form_args['language'];

        //Set the booking start and end dates
        $this->start_date = wpbs_convert_js_to_php_timestamp($this->post_data['calendar']['start_date']);
        $this->end_date = wpbs_convert_js_to_php_timestamp($this->post_data['calendar']['end_date']);

        /**
         * Process Data
         */

        // Create the Booking
        $this->create_booking();

        // Crete the events if auto_pending is enabled
        $this->create_events();

        // Hook used to save additional data
        do_action('wpbs_submit_form_after', $this->booking_id, $this->post_data, $this->form, $this->form_args, $this->form_fields);

        // Send admin and user notifications
        $this->send_email_notifications();

        // Get the form notification and tracking scripts
        $this->get_confirmation();
    }

    /**
     * Prepare calendar data to be inserted
     *
     */
    protected function create_booking()
    {

        $booking_data = array(
            'calendar_id' => absint($this->calendar_id),
            'form_id' => absint($this->form_id),
            'start_date' => wpbs_date_i18n('Y-m-d 00:00:00', $this->start_date),
            'end_date' => wpbs_date_i18n('Y-m-d 00:00:00', $this->end_date),
            'fields' => $this->form_fields,
            'status' => 'pending',
            'is_read' => '0',
            'date_created' => current_time('Y-m-d H:i:s'),
            'date_modified' => current_time('Y-m-d H:i:s'),
            'invoice_hash' => wpbs_generate_hash(),
        );

        // Insert booking into the database
        $this->booking_id = wpbs_insert_booking($booking_data);

        $this->response['booking_id'] = $this->booking_id;

        // Save the language the form was submitted in
        wpbs_update_booking_meta($this->booking_id, 'submitted_language', $this->language);

    }

    /**
     * Add the events to the database if auto_pending is activated
     *
     */
    protected function create_events()
    {

        // Only create events if auto_pending is set to true
        if ($this->post_data['form']['auto_pending'] != 1) {
            return false;
        }

        $selection_style = $this->post_data['form']['selection_style'];

        $events_data = array();

        // Get the "Auto Pending" legend.
        $legend_items_booked = wpbs_get_legend_items(array('calendar_id' => $this->calendar_id, 'auto_pending' => 'booked'));

        if (empty($legend_items_booked)) {
            return false;
        }

        $legend_item_booked = array_shift($legend_items_booked);

        // Set the start and end dates for the loop
        $events_begin = new DateTime();
        $events_begin->setTimestamp($this->start_date);

        $events_end = new DateTime();
        $events_end->setTimestamp($this->end_date);
        $events_end->modify('+1 day'); // Add +1 day to correct the interval

        // Loop through booking dates and create events
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($events_begin, $interval, $events_end);

        foreach ($period as $event_date) {
            $events_data[] = array(
                'calendar_id' => $this->calendar_id,
                'booking_id' => $this->booking_id,
                'legend_item_id' => $legend_item_booked->get('id'),
                'date_year' => $event_date->format('Y'),
                'date_month' => $event_date->format('m'),
                'date_day' => $event_date->format('d'),
            );
        }

        $legend_item_changeover_start = $legend_item_changeover_end = false;

        // If split day selection was used, set the start and end date as changeovers
        if ($selection_style == 'split') {
            // Get split legends
            $legend_item_changeover_start = wpbs_get_legend_items(array('calendar_id' => $this->calendar_id, 'auto_pending' => 'changeover_start'))[0];
            $legend_item_changeover_end = wpbs_get_legend_items(array('calendar_id' => $this->calendar_id, 'auto_pending' => 'changeover_end'))[0];

            // Replace with default if they don't exist.
            if (is_null($legend_item_changeover_start)) {
                $legend_item_changeover_start = $legend_item_booked;
            }

            if (is_null($legend_item_changeover_end)) {
                $legend_item_changeover_end = $legend_item_booked;
            }

            reset($events_data);
            $start_day = current($events_data);
            $end_day = end($events_data);

            // Check Starting Day availability
            $start_day_event = wpbs_get_events(array('calendar_id' => $this->calendar_id, 'date_day' => $start_day['date_day'], 'date_month' => $start_day['date_month'], 'date_year' => $start_day['date_year']));
            if (!empty($start_day_event)) {
                $start_day_event = array_shift($start_day_event);
            }

            if (empty($start_day_event) || $start_day_event->get('legend_item_id') != $legend_item_changeover_end->get('id')) {
                // If date is empty, set the changeover legend.
                $events_data[0]['legend_item_id'] = $legend_item_changeover_start->get('id');
            }

            // Check Ending Day availability
            $end_day_event = wpbs_get_events(array('calendar_id' => $this->calendar_id, 'date_day' => $end_day['date_day'], 'date_month' => $end_day['date_month'], 'date_year' => $end_day['date_year']));
            if (!empty($end_day_event)) {
                $end_day_event = array_shift($end_day_event);
            }

            if (empty($end_day_event) || $end_day_event->get('legend_item_id') != $legend_item_changeover_start->get('id')) {
                // If date is empty, set the changeover legend.
                $events_data[count($events_data) - 1]['legend_item_id'] = $legend_item_changeover_end->get('id');
            }
        }

        $events_data = apply_filters('wpbs_form_handler_auto_pending_events', $events_data, $selection_style, $this->calendar_id, $legend_item_booked, $legend_item_changeover_start, $legend_item_changeover_end, $this->post_data, $this->form_fields);

        // Auto-fill event description from Form Settings
        $autofill_fields = array('description', 'tooltip');

        foreach ($autofill_fields as $autofill_field) {
            if (wpbs_get_form_meta($this->form_id, 'autofill_event_' . $autofill_field, true)) {

                // Load email tags parser
                $email_tags = new WPBS_Email_Tags($this->form, $this->calendar, $this->booking_id, $this->form_fields, $this->language, $this->start_date, $this->end_date);

                // Parse the string
                $auto_event_value = $email_tags->parse(wpbs_get_form_meta($this->form_id, 'autofill_event_' . $autofill_field, true));

                // Add description to events
                for ($i = 0; $i < count($events_data); $i++) {
                    $event = wpbs_get_events(array('calendar_id' => $this->calendar_id, 'date_day' => $events_data[$i]['date_day'], 'date_month' => $events_data[$i]['date_month'], 'date_year' => $events_data[$i]['date_year']));
                    if (!empty($event[0]) && !empty($event[0]->get($autofill_field))) {
                        $events_data[$i][$autofill_field] = $event[0]->get($autofill_field) . ' / ' . $auto_event_value;
                    } else {
                        $events_data[$i][$autofill_field] = $auto_event_value;
                    }
                }
            }
        }

        // Insert the events in the database
        foreach ($events_data as $event_data) {
            $event = wpbs_get_events(array('calendar_id' => $this->calendar_id, 'date_day' => $event_data['date_day'], 'date_month' => $event_data['date_month'], 'date_year' => $event_data['date_year']));

            if (!empty($event[0])) {

                wpbs_update_event($event[0]->get('id'), $event_data);
            } else {

                wpbs_insert_event($event_data);
            }

        }
    }

    /**
     * Send admin & user emails
     *
     */
    protected function send_email_notifications()
    {
        foreach (array('admin', 'user') as $notification_type) {
            if (wpbs_get_form_meta($this->form_id, $notification_type . '_notification_enable', true) == 'on') {
                $email = new WPBS_Form_Mailer($this->form, $this->calendar, $this->booking_id, $this->form_fields, $this->language, $this->start_date, $this->end_date);
                $email->prepare($notification_type);
                $email->send();
            }
        }

        do_action('wpbs_submit_form_emails', $this->form, $this->calendar, $this->booking_id, $this->form_fields, $this->language, $this->start_date, $this->end_date);
    }

    /**
     * Form Confirmation
     *
     */
    protected function get_confirmation()
    {

        $confirmation_type = wpbs_get_form_meta($this->form_id, 'form_confirmation_type', true);
        $this->response['confirmation_type'] = $confirmation_type;

        if ($confirmation_type == 'message') {

            $confirmation_message = (wpbs_get_form_meta($this->form_id, 'form_confirmation_message_translation_' . $this->language, true)) ? wpbs_get_form_meta($this->form_id, 'form_confirmation_message_translation_' . $this->language, true) : wpbs_get_form_meta($this->form_id, 'form_confirmation_message', true);

            // Load email tags parser
            $email_tags = new WPBS_Email_Tags($this->form, $this->calendar, $this->booking_id, $this->form_fields, $this->language, $this->start_date, $this->end_date);

            // Parse the string
            $confirmation_message = $email_tags->parse($confirmation_message);

            $confirmation_message = apply_filters('wpbs_submit_form_confirmation_message', $confirmation_message, $this->booking_id, $this->language);

            $this->response['confirmation_message'] = wpautop($confirmation_message);

        } elseif ($confirmation_type == 'redirect') {

            if (wpbs_get_form_meta($this->form_id, 'form_confirmation_redirect_url_translation_' . $this->language, true)) {
                $confirmation_redirect_url = wpbs_get_form_meta($this->form_id, 'form_confirmation_redirect_url_translation_' . $this->language, true);
            } else {
                $confirmation_redirect_url = wpbs_get_form_meta($this->form_id, 'form_confirmation_redirect_url', true);
            }
            
            $this->response['confirmation_redirect_url'] = $confirmation_redirect_url;
        }

        $this->tracking_script();

    }

    /**
     * Tracking Script
     *
     */
    protected function tracking_script()
    {
        $tracking_script = wpbs_get_form_meta($this->form_id, 'tracking_script', true);
        if (!empty($tracking_script)) {
            $this->response['tracking_script'] = $tracking_script;
        }
    }

    /**
     * Return the response
     *
     * @return array
     *
     */
    public function get_response()
    {
        return $this->response;
    }

}
