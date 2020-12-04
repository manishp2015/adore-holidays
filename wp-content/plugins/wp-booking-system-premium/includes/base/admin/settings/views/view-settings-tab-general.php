<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>


<?php

	/**
	 * Hook to add extra fields at the top of the General Tab
	 *
	 * @param array $settings
	 *
	 */
	do_action( 'wpbs_submenu_page_settings_tab_general_top', $settings );

?>


<!-- Booking History Color -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">

	<label class="wpbs-settings-field-label"><?php echo __( 'Booking History Color', 'wp-booking-system' ); ?></label>

	<div class="wpbs-settings-field-inner">
		<input name="wpbs_settings[booking_history_color]" type="text" class="wpbs-colorpicker" value="<?php echo ( ! empty( $settings['booking_history_color'] ) ? esc_attr( $settings['booking_history_color'] ) : '' ); ?>" />
	</div>
	
</div>

<!-- Booking Selection Hover -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">

	<label class="wpbs-settings-field-label"><?php echo __( 'Booking Hover Color', 'wp-booking-system' ); ?> <?php echo wpbs_get_output_tooltip(__('The color of the calendar dates when hovering over them.', 'wp-booking-system')) ?></label>

	<div class="wpbs-settings-field-inner">
		<input name="wpbs_settings[booking_selection_hover_color]" type="text" class="wpbs-colorpicker" value="<?php echo ( ! empty( $settings['booking_selection_hover_color'] ) ? esc_attr( $settings['booking_selection_hover_color'] ) : '' ); ?>" />
	</div>
	
</div>

<!-- Booking Selection Hover -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline">

	<label class="wpbs-settings-field-label"><?php echo __( 'Booking Selection Color', 'wp-booking-system' ); ?> <?php echo wpbs_get_output_tooltip(__('The color of the calendar dates after they have been selected.', 'wp-booking-system')) ?></label>

	<div class="wpbs-settings-field-inner">
		<input name="wpbs_settings[booking_selection_selected_color]" type="text" class="wpbs-colorpicker" value="<?php echo ( ! empty( $settings['booking_selection_selected_color'] ) ? esc_attr( $settings['booking_selection_selected_color'] ) : '' ); ?>" />
	</div>
	
</div>


<!-- Calendar Back-end Start Day -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-small">

	<label class="wpbs-settings-field-label"><?php echo __( 'Backend Start Day', 'wp-booking-system' ); ?></label>

	<div class="wpbs-settings-field-inner">
		<select name="wpbs_settings[backend_start_day]">
			<?php 

				$weekday = wpbs_get_translated_weekdays();

				foreach( $weekday as $key => $day_name ) {

					// Weekdays keys start at 1, not 0
					$key++;

					echo '<option value="' . esc_attr( $key ) . '" ' . ( ! empty( $settings['backend_start_day'] ) ? selected( $settings['backend_start_day'], $key, false ) : '' ) . '>' . $day_name . '</option>';

				}

			?>
		</select>
	</div>
	
</div>


<!-- iCal Refresh Times -->
<div id="wpbs-settings-field-ical-refresh-times-wrapper" class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-medium">

	<label class="wpbs-settings-field-label"><?php echo __( 'iCal Refresh Times', 'wp-booking-system' ); ?></label>

	<div class="wpbs-settings-field-inner">
		<select name="wpbs_settings[ical_refresh_times]">
			<?php $selected = ( ! empty( $settings['ical_refresh_times'] ) ? $settings['ical_refresh_times'] : 'hourly' ); ?>
			<option value="page_load" <?php selected( $selected, 'page_load' ); ?>><?php echo __( 'On each page load', 'wp-booking-system' ); ?></option>
			<option value="hourly" <?php selected( $selected, 'hourly' ); ?>><?php echo __( 'Hourly', 'wp-booking-system' ); ?></option>
			<option value="daily" <?php selected( $selected, 'daily' ); ?>><?php echo __( 'Daily', 'wp-booking-system' ); ?></option>
			<option value="custom" <?php selected( $selected, 'custom' ); ?>><?php echo __( 'Custom', 'wp-booking-system' ); ?></option>
		</select>
	</div>

</div>

<!-- Custom iCal Refresh Times -->
<div id="wpbs-settings-field-ical-custom-refresh-time-wrapper" class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-medium">

	<label class="wpbs-settings-field-label"><?php echo __( 'iCal Custom Refresh Time', 'wp-booking-system' ); ?></label>

	<div class="wpbs-settings-field-inner">

		<input name="wpbs_settings[ical_custom_refresh_time]" type="number" value="<?php echo ( ! empty( $settings['ical_custom_refresh_time'] ) ? esc_attr( $settings['ical_custom_refresh_time'] ) : '' ); ?>" />

		<select name="wpbs_settings[ical_custom_refresh_time_unit]">
			<?php $selected = ( ! empty( $settings['ical_custom_refresh_time_unit'] ) ? $settings['ical_custom_refresh_time_unit'] : 'page_load' ); ?>
			<option value="minutes" <?php selected( $selected, 'minutes' ); ?>><?php echo __( 'Minutes', 'wp-booking-system' ); ?></option>
			<option value="hours" <?php selected( $selected, 'hours' ); ?>><?php echo __( 'Hours', 'wp-booking-system' ); ?></option>
		</select>

	</div>

</div>

<?php

	/**
	 * Hook to add extra fields at the bottom of the General Tab
	 *
	 * @param array $settings
	 *
	 */
	do_action( 'wpbs_submenu_page_settings_tab_general_bottom', $settings );

?>

<!-- Submit button -->
<input type="submit" class="button-primary" value="<?php echo __( 'Save Settings', 'wp-booking-system' ); ?>" />