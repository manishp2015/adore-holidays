<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPBS_S_Search_Widget_Outputter
{

    /**
     * The shortcode attributes
     *
     */
    private $args;

    /**
     * The start date
     *
     */
    private $start_date;

    /**
     * The end date
     *
     */
    private $end_date;

    /**
     * Variable that holds the errors
     *
     */
    private $has_error = false;

    /**
     * The error message
     *
     */
    private $error;

    /**
     * A unique string
     *
     * @access protected
     * @var    array
     *
     */
    protected $unique;

    /**
     * Constructor
     *
     */
    public function __construct($args, $start_date = null, $end_date = null)
    {

        /**
         * Set the attributes
         *
         */
        $this->args = $args;

        /**
         * Set the start date
         */
        if ($start_date === null && isset($_GET['wpbs-search-start-date'])) {
            $this->start_date = sanitize_text_field($_GET['wpbs-search-start-date']);
        } else {
            $this->start_date = $start_date;
        }

        /**
         * Set the end date
         *
         */
        if ($end_date === null && isset($_GET['wpbs-search-end-date'])) {
            $this->end_date = sanitize_text_field($_GET['wpbs-search-end-date']);
        } else {
            $this->end_date = $end_date;
        }

        /**
         * Set the unique string to prevent conflicts if the same form is embedded twice on the same page
         *
         */
        $this->unique = hash('crc32', microtime(), false);

        /**
         * Check for errors
         *
         */
        $this->check_errors();

    }

    /**
     * Constructs and returns the HTML for the Search Widget
     *
     * @return string
     *
     */
    public function get_display()
    {
        // Add the shortcode attributes
        $search_widget_html_data = '';
        foreach ($this->args as $att => $val) {
            $search_widget_html_data .= 'data-' . $att . '="' . esc_attr($val) . '" ';
        }

        $output = '<div class="wpbs_s-search-widget" ' . $search_widget_html_data . '>';

        // Get the form
        $output .= '<div class="wpbs_s-search-widget-form-wrap">';
        $output .= $this->get_display_search_form();
        $output .= '</div>';

        // Get the errors
        $output .= $this->get_display_error();

        // Get the results
        $output .= '<div class="wpbs_s-search-widget-results-wrap">';
        $output .= $this->get_search_results();
        $output .= '</div>';

        $output .= $this->get_widget_styles();

        $output .= '<div class="wpbs-search-container-loaded" data-just-loaded="1"></div>';

        $output .= '</div>';

        return $output;
    }

    /**
     * Constructs the HTML for the form errors
     *
     * @return string
     *
     */
    private function get_display_error()
    {

        // Check if we have errors
        if ($this->has_error === false) {
            return false;
        }

        // If we do, return them
        return '<div class="wpbs_s-search-widget-error-field">' . $this->error . '</div>';
    }

    /**
     * Constructs the HTML for the search form
     *
     * @return string
     *
     */
    private function get_display_search_form()
    {
        ob_start();
        include 'views/view-search-form.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Constructs the HTML for the 'no results found' message
     *
     * @return string
     *
     */
    private function get_display_no_results()
    {
        return '<p class="wpbs_s-search-widget-no-results">' . $this->get_search_widget_string('no_results') . '</p>';
    }

    /**
     * Constructs the HTML for the search results
     *
     * @return string
     *
     */
    private function get_search_results()
    {
        // Check if the form was submitted
        if ($this->is_form_submitted() === false) {
            return false;
        }

        // Check if there are errors
        if ($this->has_error === true) {
            return false;
        }

        // Get search data
        $available_calendars = $this->get_search_results_calendar_data();

        // Search results title
        $output = '<h2>' . $this->get_search_widget_string('results_title') . '</h2>';

        // Check if there are results
        if (empty($available_calendars)) {
            $output .= $this->get_display_no_results();
            return $output;
        }

        // Display results
        foreach ($available_calendars as $calendar) {
            $output .= $this->get_display_search_result($calendar);
        }

        return $output;
    }

    /**
     * Constructs the HTML for each search result row
     *
     * @param array $data
     * @return string
     *
     */
    private function get_display_search_result($data)
    {

        $data['button_label'] = $this->get_search_widget_string('view_button_label');

        $output = '<div class="wpbs_s-search-widget-result">';

        if (empty($data['link'])) {
            $output .= '<h3>' . $data['calendar_name'] . '</h3>';
        } else {
            $output .= '<h3><a class="wpbs_s-search-widget-result-link" href="' . $data['link'] . '" title="' . $data['calendar_name'] . '">' . $data['calendar_name'] . '</a></h3>';
            $output .= '<a class="wpbs_s-search-widget-result-button" href="' . $data['link'] . '">' . $data['button_label'] . '</a>';
        }

        $output .= '</div>';

        $output = apply_filters('wpbs_search_resuts_html', $output, $data);

        return $output;
    }

    /**
     * Checks if the form was submitted
     *
     * @return bool
     *
     */
    private function is_form_submitted()
    {
        if ($this->start_date === null || $this->end_date === null) {
            return false;
        }
        return true;
    }

    /**
     * Does the actual search for available dates in calendars
     *
     */
    private function get_search_results_calendar_data()
    {
        // Format start date
        $start_datetime = DateTime::createFromFormat('Y-m-d', $this->start_date);
        $start_date = $start_datetime->format('Ymd');

        // Format end date
        $end_datetime = DateTime::createFromFormat('Y-m-d', $this->end_date);
        $end_date = $end_datetime->format('Ymd');

        // Empty array with calendars
        $available_calendars = [];

        if (empty($this->args['calendars']) || $this->args['calendars'] == 'all') {
            // All calendars
            $calendars = wpbs_get_calendars(array('status' => 'active', 'orderby' => 'name', 'order' => 'asc'));
        } else {
            // Specific calendars
            $calendar_ids = array_filter(array_map('trim', explode(',', $this->args['calendars'])));
            $args = array(
                'include' => $calendar_ids,
                'orderby' => 'FIELD( id, ' . implode(',', $calendar_ids) . ')',
                'order' => '',
            );

            $calendars = wpbs_get_calendars($args);
        }

        // Loop through calendars
        foreach ($calendars as $calendar) {

            // Assume calendar dates are available
            $is_available = true;

            // Get non bookable legend items
            $non_bookable_legend_items = array();
            $legend_items = wpbs_get_legend_items(array('calendar_id' => $calendar->get('id')));

            $changeover_start = $changeover_end = false;

            foreach ($legend_items as $legend_item) {
                if ($legend_item->get('is_bookable') == 0) {
                    $non_bookable_legend_items[] = $legend_item->get('id');
                }

                if ($legend_item->get('auto_pending') == 'changeover_start') {
                    $changeover_start = $legend_item->get('id');
                }

                if ($legend_item->get('auto_pending') == 'changeover_end') {
                    $changeover_end = $legend_item->get('id');
                }

            }

            $changeover_start_found = $changeover_end_found = false;

            // Loop through events
            $calendar_events = wpbs_get_events(array('calendar_id' => $calendar->get('id'))); // Calendar Events
            $ical_events = wpbs_get_ical_feeds_as_events($calendar->get('id'), array()); // iCalendar Events

            $events = array_merge($calendar_events, $ical_events);

            $sorted_events = array();
            foreach ($events as $event) {
                $sorted_events[$event->get('date_year') . str_pad($event->get('date_month'), 2, '0', STR_PAD_LEFT) . str_pad($event->get('date_day'), 2, '0', STR_PAD_LEFT)] = $event->get('legend_item_id');
            }

            ksort($sorted_events);

            foreach ($sorted_events as $event_date => $event_legent_item_id) {

                // If event date is outside search range, continue;
                if ($event_date < $start_date || $event_date > $end_date) {
                    continue;
                }

                // Check if the event found is not bookable
                if (in_array($event_legent_item_id, $non_bookable_legend_items)) {
                    $is_available = false;
                    break;
                }

                // Check for changeovers. The rule is that if a start changeover exists in an array, we shouln't an end changeover

                // We found a starting changeover date
                if ($event_legent_item_id == $changeover_start) {
                    $changeover_start_found = true;
                }

                // Now if we find an ending changeover date and a starting changeover date was previously found, we mark the date as not available.
                if ($event_legent_item_id == $changeover_end && $changeover_start_found === true) {
                    $is_available = false;
                    break;
                }

            }

            if ($is_available === true) {

                $name = $calendar->get_name($this->args['language']);

                // Get calendar Link
                $calendar_link_type = wpbs_get_calendar_meta($calendar->get('id'), 'calendar_link_type', true);
                $calendar_link_post_id = WPBS_Calendar_Overview_Outputter::get_calendar_link_post_id($calendar->get('id'), $this->args['language']);
                $calendar_link = WPBS_Calendar_Overview_Outputter::get_calendar_link($calendar->get('id'), $this->args['language']);

                if (isset($calendar_link) && !empty($calendar_link) && $calendar_link_type == 'internal' && $this->args['mark_selection'] == 'yes') {
                    $calendar_link = add_query_arg(array(
                        'wpbs-start-year' => $start_datetime->format('Y'),
                        'wpbs-start-month' => $start_datetime->format('n'),
                        'wpbs-selection-start' => $start_datetime->format('Y-m-d'),
                        'wpbs-selection-end' => $end_datetime->format('Y-m-d'),
                    ), $calendar_link);
                }
                $available_calendars[] = array('calendar_name' => $name, 'link' => $calendar_link, 'post_id' => $calendar_link_post_id);
            }
        }

        return $available_calendars;
    }

    /**
     * Check for form errors
     *
     */
    private function check_errors()
    {

        // If form wasn't submitted, there is nothing to check
        if ($this->is_form_submitted() === false) {
            return false;
        }

        // Check if a starting day was entered
        if (empty($this->start_date)) {
            $this->has_error = true;
            $this->error = $this->get_search_widget_string('no_start_date');
            return;
        }

        // Check if an ending day was entered
        if (empty($this->end_date)) {
            $this->has_error = true;
            $this->error = $this->get_search_widget_string('no_end_date');
            return;
        }

        // Check if the starting day is valid
        if (DateTime::createFromFormat('Y-m-d', $this->start_date) === false) {
            $this->has_error = true;
            $this->error = $this->get_search_widget_string('invalid_start_date');
            return;
        }

        // Check if the ending day is valid
        if (DateTime::createFromFormat('Y-m-d', $this->end_date) === false) {
            $this->has_error = true;
            $this->error = $this->get_search_widget_string('invalid_end_date');
            return;
        }

    }

    /**
     * Helper function to get custom or translated strings
     *
     */
    private function get_search_widget_string($key)
    {

        $settings = get_option('wpbs_settings', array());

        // Check for translation
        if (!empty($settings['search_addon'][$key . '_translation_' . $this->args['language']])) {
            return $settings['search_addon'][$key . '_translation_' . $this->args['language']];
        }

        // Check for default
        if (!empty($settings['search_addon'][$key])) {
            return $settings['search_addon'][$key];
        }

        return wpbs_s_search_widget_default_strings()[$key];
    }

    /**
     * Generates the styles for the form colors
     *
     * @return string
     *
     */
    protected function get_widget_styles()
    {

        $settings = get_option('wpbs_settings', array());

        if ($settings['form_styling'] == 'theme') {
            return '';
        }

        $colors = array(
            'button_background_color' => '#aaaaaa',
            'button_background_hover_color' => '#7f7f7f',
            'button_text_color' => '#ffffff',
            'button_text_hover_color' => '#ffffff',
        );

        foreach ($colors as $color_key => $color) {
            if (isset($settings[$color_key]) && !empty($settings[$color_key])) {
                $colors[$color_key] = $settings[$color_key];
            }
        }

        $output = '<style>';

        // Button
        $output .= '.wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field button.wpbs_s-search-widget-datepicker-submit, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field input[type="submit"], .wpbs_s-search-widget .wpbs_s-search-widget-results-wrap .wpbs_s-search-widget-result .wpbs_s-search-widget-result-button {background-color: ' . $colors['button_background_color'] . ' !important; color: ' . $colors['button_text_color'] . ' !important; }';

        // Button Hover
        $output .= '.wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field button.wpbs_s-search-widget-datepicker-submit:hover, .wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field input[type="submit"]:hover , .wpbs_s-search-widget .wpbs_s-search-widget-results-wrap .wpbs_s-search-widget-result .wpbs_s-search-widget-result-button:hover {background-color: ' . $colors['button_background_hover_color'] . ' !important;  color: ' . $colors['button_text_hover_color'] . ' !important; }';

        // Datepicker Selection
        $output .= '.ui-datepicker.wpbs-datepicker td.ui-datepicker-current-day {background-color: ' . $colors['button_background_color'] . ' !important; }';
        $output .= '.ui-datepicker.wpbs-datepicker td.ui-datepicker-current-day a { color: ' . $colors['button_text_color'] . ' !important; }';

        // Datepicker Hover
        $output .= '.ui-datepicker.wpbs-datepicker td .ui-state-default.ui-state-hover {background-color: ' . $colors['button_background_hover_color'] . ' !important;  color: ' . $colors['button_text_hover_color'] . ' !important; }';

        $output .= '</style>';

        return $output;
    }

}
