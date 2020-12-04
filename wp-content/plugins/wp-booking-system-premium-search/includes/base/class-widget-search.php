<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WPBS_S_Widget_Calendar_Search extends WP_Widget {

	/**
	 * Constructor
	 *
	 */
	public function __construct() {

		$widget_ops = array( 
			'classname'   => 'wpbs_s_calendar_search',
			'description' => __( 'Insert a WP Booking System Search Widget', 'wp-booking-system-search'),
		);

		parent::__construct( 'wpbs_s_calendar_search', 'WP Booking System Search', $widget_ops );

	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 */
	public function widget( $args, $instance ) {

		// Remove the "wpbs" prefix to have a cleaner code
		$instance = ( ! empty( $instance ) && is_array( $instance ) ? $instance : array() );

		foreach( $instance as $key => $value ) {

			$instance[ str_replace( 'wpbs_', '', $key ) ] = $value;
			unset( $instance[$key] );

		}

        if( ! empty( $instance['display_calendars'] ) && $instance['display_calendars'] == 1){
            $calendars = 'all';
        }

        if( ! empty( $instance['display_calendars'] ) && $instance['display_calendars'] == 2){
            if(!empty($instance['calendars'])){
                $calendars = implode(',', $instance['calendars']);
            } else {
                $calendars = 'all';
            }
        }
		
		$args = array(
			'calendars' => $calendars,
			'language'  => ( ! empty( $instance['language'] ) ? ( $instance['language'] == 'auto' ? wpbs_get_locale() : $instance['language'] ) : 'en' ),
			'title'  => ( ! empty( $instance['title'] ) ? $instance['title'] : 'yes' ),
			'mark_selection'  => ( ! empty( $instance['mark_selection'] ) ? $instance['mark_selection'] : 'yes' ),
		);

        // Shortcode default attributes
        $default_args = wpbs_s_get_search_widget_default_args();

        // Shortcode attributes
        $args = shortcode_atts($default_args, $args);

        $search_widget_outputter = new WPBS_S_Search_Widget_Outputter($args);

        echo $search_widget_outputter->get_display();
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 *
	 */
	public function form( $instance ) {
		
		global $wpdb;
        $calendar_display       = ( ! empty( $instance['wpbs_display_calendars'] ) ? $instance['wpbs_display_calendars'] : 1 );
        $calendar_ids       = ( ! empty( $instance['wpbs_calendars'] ) ? $instance['wpbs_calendars'] : array() );
		$widget_title       = ( ! empty( $instance['wpbs_title'] ) ? $instance['wpbs_title'] : 'yes' );
		$mark_selection       = ( ! empty( $instance['wpbs_mark_selection'] ) ? $instance['wpbs_mark_selection'] : 'yes' );
        
        $calendar_language = ( ! empty( $instance['wpbs_language'] ) ? $instance['wpbs_language'] : 'en' );
        
        $calendars = wpbs_get_calendars(array('status' => 'active'));

        ?>

        <!-- Calendar -->
		<p class="wpbs-widget-display-calendars-select">
			<label for="<?php echo $this->get_field_id('wpbs_display_calendars'); ?>"><?php echo __( 'Calendars', 'wp-booking-system-search'); ?></label>

			<select name="<?php echo $this->get_field_name('wpbs_display_calendars'); ?>" id="<?php echo $this->get_field_id('wpbs_display_calendars'); ?>" class="widefat">
				<option value="1" <?php echo ( $calendar_display == 1 ? 'selected="selected"' : '' ); ?>><?php echo __('All Calendars', 'wp-booking-system-search'); ?></option>
                <option value="2" <?php echo ( $calendar_display == 2 ? 'selected="selected"' : '' ); ?>><?php echo __('Selected Calendars', 'wp-booking-system-search'); ?></option>
			</select>
		</p>
        
        <!-- Calendar -->
		<p class="wpbs-chosen-wrap <?php echo (empty($calendar_display) || $calendar_display == 1) ? 'wpbs-element-disabled' : '';?>">
			<label for="<?php echo $this->get_field_id('wpbs_calendars'); ?>"><?php echo __( 'Calendars', 'wp-booking-system-search'); ?></label>

			<select multiple="multiple" name="<?php echo $this->get_field_name('wpbs_calendars'); ?>[]" id="<?php echo $this->get_field_id('wpbs_calendars'); ?>" class="widefat wpbs-chosen">
				<?php foreach( $calendars as $calendar ):?>
					<option <?php echo ( in_array($calendar->get('id'), $calendar_ids) ? 'selected="selected"' : '' );?> value="<?php echo $calendar->get('id'); ?>"><?php echo $calendar->get('name'); ?></option>
				<?php endforeach;?>
			</select>
		</p>


		<!-- Calendar Language -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_language'); ?>"><?php echo __( 'Language', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_language'); ?>" id="<?php echo $this->get_field_id('wpbs_language'); ?>" class="widefat">
				<?php
					$settings 		  = get_option( 'wpbs_settings', array() );
					$languages 		  = wpbs_get_languages();
					$active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );
				?>

				<option value="auto"><?php echo __( 'Auto (let WP choose)', 'wp-booking-system-search');?></option>

				<?php foreach( $active_languages as $code ):?>
					<option value="<?php echo esc_attr( $code ); ?>" <?php echo ( $calendar_language == $code ? 'selected="selected"' : '' ); ?>><?php echo ( ! empty( $languages[$code] ) ? $languages[$code] : '' ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<!-- Show Widget Title -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_title'); ?>"><?php echo __( 'Widget Title', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_title'); ?>" id="<?php echo $this->get_field_id('wpbs_title'); ?>" class="widefat">
				
				<option value="yes" <?php echo ( $widget_title == 'yes' ? 'selected="selected"' : '' ); ?>><?php echo __('Yes', 'wp-booking-system-search') ?></option>
				<option value="no" <?php echo ( $widget_title == 'no' ? 'selected="selected"' : '' ); ?>><?php echo __('No', 'wp-booking-system-search') ?></option>
			</select>
		</p>

		<!-- Mark Selection -->
		<p>
			<label for="<?php echo $this->get_field_id('wpbs_mark_selection'); ?>"><?php echo __( 'Automatically Mark Selection', 'wp-booking-system-search');?></label>

			<select name="<?php echo $this->get_field_name('wpbs_mark_selection'); ?>" id="<?php echo $this->get_field_id('wpbs_mark_selection'); ?>" class="widefat">
				
				<option value="yes" <?php echo ( $mark_selection == 'yes' ? 'selected="selected"' : '' ); ?>><?php echo __('Yes', 'wp-booking-system-search') ?></option>
				<option value="no" <?php echo ( $mark_selection == 'no' ? 'selected="selected"' : '' ); ?>><?php echo __('No', 'wp-booking-system-search') ?></option>
			</select>
		</p>

        <?php

    }


	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		
		return $new_instance;

	}

}

add_action( 'widgets_init', function() {
	register_widget( 'WPBS_S_Widget_Calendar_Search' );
});