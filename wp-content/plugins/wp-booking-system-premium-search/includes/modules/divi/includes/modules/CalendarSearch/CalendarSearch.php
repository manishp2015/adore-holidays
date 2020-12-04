<?php
class WPBS_S_Divi_Module_CalendarSearch extends ET_Builder_Module
{

    public $slug = 'wpbs_s_divi_calendar_search';
    public $vb_support = 'on';

    public function init()
    {
        $this->name = esc_html__('Calendar Search', 'wp-booking-system');
    }

    public function get_fields()
    {

      
        // Languages
        $languages_dropdown = array();
        $settings = get_option('wpbs_settings', array());
        $languages = wpbs_get_languages();
        $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

        $languages_dropdown['auto'] = __('Auto (let WP choose)', 'wp-booking-system');

        foreach ($active_languages as $code) {
            $languages_dropdown[esc_attr($code)] = (!empty($languages[$code]) ? $languages[$code] : '');
        }

        return array(
            

            'title' => array(
                'label' => esc_html__('Widget Title', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
            ),

            'mark_selection' => array(
                'label' => esc_html__('Automatically Mark Selection', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'yes',
                'option_category' => 'basic_option',
                'options' => array('yes' => __('Yes', 'wp-booking-system'), 'no' => __('No', 'wp-booking-system')),
            ),

            'language' => array(
                'label' => esc_html__('Language', 'wp-booking-system'),
                'type' => 'select',
                'default' => 'auto',
                'option_category' => 'basic_option',
                'options' => $languages_dropdown,
            ),

        );
    }

    public function render($attrs, $content = null, $render_slug)
    {
        // Execute the shortcode
        return WPBS_S_Shortcodes::search_widget($this->props);
    }
}

new WPBS_S_Divi_Module_CalendarSearch;
