<!-- Modal Tab: Search Widget -->
<div class="wpbs-tab wpbs-modal-tab" data-tab="insert-search-widget">

    <h3><?php echo __('Insert a Calendar Search Widget', 'wp-booking-system-search'); ?></h3>
    <p><?php echo __('Create a search widget that allows the visitor to search for available dates in your calendars', 'wp-booking-system-search'); ?></p>


    <h4><?php echo __('Search Widget', 'wp-booking-system-search'); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Calendars -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-calendars"><?php echo __('Calendars', 'wp-booking-system-search'); ?></label>

            <select id="modal-add-search-widget-shortcode-calendars">
                <option value="1"><?php echo __('All Calendars', 'wp-booking-system-search'); ?></option>
                <option value="2"><?php echo __('Selected Calendars', 'wp-booking-system-search'); ?></option>
            </select>

        </div>

        <!-- Column: Selected Calendars -->
        <div class="wpbs-col-3-4 wpbs-element-disabled">

            <label for="modal-add-search-widget-shortcode-selected-calendars"><?php echo __('Select Calendars', 'wp-booking-system-search'); ?></label>

            <select id="modal-add-search-widget-shortcode-selected-calendars" multiple class="wpbs-chosen">
                <?php $calendars = wpbs_get_calendars(array('status' => 'active'));?>
                <?php
                    foreach ($calendars as $calendar) {
                        echo '<option value="' . $calendar->get('id') . '">' . $calendar->get('name') . '</option>';
                    }

                    ?>
            </select>

        </div>

    </div><!-- / Row -->

    <h4><?php echo __('Basic Options', 'wp-booking-system-search'); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">

        <!-- Column: Language -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-language"><?php echo __('Language', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-language" class="wpbs-shortcode-generator-field-search-widget" data-attribute="language">
                <option value="auto"><?php echo __('Auto (let WP choose)', 'wp-booking-system-search'); ?></option>

                <?php

                $settings = get_option('wpbs_settings', array());
                $languages = wpbs_get_languages();
                $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());

                foreach ($active_languages as $code) {

                    echo '<option value="' . esc_attr($code) . '">' . (!empty($languages[$code]) ? $languages[$code] : '') . '</option>';

                }

                ?>
            </select>
        </div>

        <!-- Column: Widget Title -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-title"><?php echo __('Widget Title', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-title" class="wpbs-shortcode-generator-field-search-widget" data-attribute="title">
                <option value="yes"><?php echo __('Yes', 'wp-booking-system-search'); ?></option>
                <option value="no"><?php echo __('No', 'wp-booking-system-search'); ?></option>
            </select>
        </div>

        <!-- Column: Widget Title -->
        <div class="wpbs-col-1-4">

            <label for="modal-add-search-widget-shortcode-title"><?php echo __('Automatically Mark Selection', 'wp-booking-system-search'); ?></label>
            <select id="modal-add-search-widget-shortcode-title" class="wpbs-shortcode-generator-field-search-widget" data-attribute="mark_selection">
                <option value="yes"><?php echo __('Yes', 'wp-booking-system-search'); ?></option>
                <option value="no"><?php echo __('No', 'wp-booking-system-search'); ?></option>
            </select>
        </div>


    </div><!-- / Row -->

    <h4><?php echo __('Strings', 'wp-booking-system-search'); ?></h4>
    <hr />

    <!-- Row -->
    <div class="wpbs-row">
        <p><?php echo sprintf(__('You can configure strings like labels and error messages from the <a target="_blank" href="%s">settings page</a>.', 'wp-booking-system-search'), esc_url(add_query_arg(array('page' => 'wpbs-settings', 'tab' => 'search_addon'), admin_url( 'admin.php' ) ) ));?></p>

    </div>

    <hr />

    <!-- Shortcode insert -->
    <a href="#" id="wpbs-insert-shortcode-search-widget" class="button button-primary"><?php echo __('Insert Search Widget', 'wp-booking-system-search'); ?></a>
    <a href="#" class="button button-secondary wpbs-modal-close"><?php echo __('Cancel', 'wp-booking-system-search'); ?></a>

</div>