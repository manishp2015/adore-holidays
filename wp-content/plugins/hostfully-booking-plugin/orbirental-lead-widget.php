<?php
/*
  Plugin Name: Hostfully Booking Plugin for Wordpress 
  Plugin URI: https://www.hostfully.com/
  Description: Add the Hostfully Booking Plugin for Wordpress
  Version: 1.0
  Author: Hostfully Inc
  License: GPLv2+
  Text Domain: orbirental-lead-widget
*/

define( 'ORBIRENTAL__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( ORBIRENTAL__PLUGIN_DIR . 'includes/meta.php' );

function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}

function load_admin_scripts( ) {
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('myplugin-script', plugins_url('js/script.js', __FILE__), array('wp-color-picker'), false, true );
}

add_action( 'widgets_init', 'wpb_load_widget' );
// méthode standard
add_action( 'widgets_init', 'load_admin_scripts');


// Creating the widget 
class wpb_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
		
		// Base ID of your widget
		'wpb_widget', 
		
		// Widget name will appear in UI
		__('Orbirental Lead Widget', 'wpb_widget_domain'), 
		
		// Widget description
		array( 'description' => __( 'Orbirental lead capture widget', 'wpb_widget_domain' ), ) 
		);
	}
	
	// Creating widget front-end
	public function widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$widgetColor = apply_filters( 'widget_color', $instance['widgetColor'] );
		$textColor = apply_filters( 'widget_text_color', $instance['textColor'] );
		$buttonBackgroundColor = apply_filters( 'widget_button_background_color', $instance['buttonBackgroundColor'] );
		$showPriceDetails = apply_filters( 'widget_showPriceDetails', $instance['showPriceDetails']) == 'true' ? 'true' : 'false';
		$showPrice = apply_filters( 'widget_showPrice', $instance['showPrice']) == 'true' ? 'true' : 'false';
		$showMinStay = apply_filters( 'widget_showMinStay', $instance['showMinStay']) == 'true' ? 'true' : 'false';
		$showAvailability = apply_filters( 'widget_showAvailability', $instance['showAvailability']) == 'true' ? 'true' : 'false';
		$showPhoneField = apply_filters( 'widget_showPhoneField', $instance['showPhoneField']) == 'true' ? 'true' : 'false';
		$showNotesField = apply_filters( 'widget_showNotesField', $instance['showNotesField']) == 'true' ? 'true' : 'false';

		$fields = array();

		if ($showPhoneField == 'true')
			array_push($fields, "\"phone\"");
		if ($showNotesField == 'true')
			array_push($fields, "\"notes\"");

		$fieldsString = '[' . join(',', $fields) . ']';

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		// This is where you run the code and display the output
		$property_uid = get_post_meta( get_the_ID(), 'property_uid', true );

		if (!isset( $property_uid ) || $property_uid.trim() == "") {
			return;
		}
		
		/*echo '$textColor : ' . $textColor;
		echo 'showPrice : ' . $showPrice;
		echo 'showPriceDetails : ' . $showPriceDetails;
		echo 'property_uid : ' . $property_uid;
		echo 'showMinStay : ' . $showMinStay;
		echo 'showAvailability : ' . $showAvailability;
		echo 'fields array : ' . $fieldsString;*/
		
		echo __( '<div id="leadWidget"></div>', 'wpb_widget_domain' );
		echo '<script>var widget = new Widget("leadWidget", "'. $property_uid .'", {"type":"agency","fields":'. $fieldsString .',"showAvailability":'. $showAvailability .',"lang":"US","minStay":'. $showMinStay .',"price":'. $showPrice .',"cc":false,"emailClient":false,"saveCookie":true,"showDynamicMinStay":true,"backgroundColor":"' . $widgetColor .'","buttonSubmit":{"backgroundColor":"' . $buttonBackgroundColor . '"},"showPriceDetailsLink":'. $showPriceDetails .',"showGetQuoteLink":false,"labelColor":"' . $textColor .'","showTotalWithoutSD":true,"redirectURL":false});</script>';
		echo $args['after_widget'];
	}
			
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Lead widget', 'wpb_widget_domain' );
		}

		if ( isset( $instance[ 'showPriceDetails' ] ) ) {

			if ($instance[ 'showPriceDetails' ] == 'true') {

				$showPriceDetailsLinkValue = 'checked="checked"';
			} else {
				$showPriceDetailsLinkValue = '';				
			}
		} else {
			$showPriceDetailsLinkValue = 'checked="checked"';				
		}

		if ( isset( $instance[ 'showPrice' ] ) ) {

			if ($instance[ 'showPrice' ] == 'true') {

				$showPriceValue = 'checked="checked"';
			} else {
				$showPriceValue = '';				
			}
		} else {
			$showPriceValue = 'checked="checked"';				
		}

		if ( isset( $instance[ 'showMinStay' ] ) ) {

			if ($instance[ 'showMinStay' ] == 'true') {

				$showMinStayValue = 'checked="checked"';
			} else {
				$showMinStayValue = '';				
			}
		} else {
			$showMinStayValue = 'checked="checked"';				
		}

		if ( isset( $instance[ 'showAvailability' ] ) ) {

			if ($instance[ 'showAvailability' ] == 'true') {

				$showAvailabilityValue = 'checked="checked"';
			} else {
				$showAvailabilityValue = '';				
			}
		} else {
			$showAvailabilityValue = 'checked="checked"';				
		}

		if ( isset( $instance[ 'showPhoneField' ] ) ) {

			if ($instance[ 'showPhoneField' ] == 'true') {

				$showPhoneFieldValue = 'checked="checked"';
			} else {
				$showPhoneFieldValue = '';				
			}
		} else {
			$showPhoneFieldValue = 'c';				
		}

		if ( isset( $instance[ 'showNotesField' ] ) ) {

			if ($instance[ 'showNotesField' ] == 'true') {

				$showNotesFieldValue = 'checked="checked"';
			} else {
				$showNotesFieldValue = '';				
			}
		} else {
			$showNotesFieldValue = '';				
		}

		$widgetColor = isset( $instance[ 'widgetColor' ]) ? $instance['widgetColor'] : '#ffffff'; 
		$buttonBackgroundColor = isset( $instance[ 'buttonBackgroundColor' ]) ? $instance['buttonBackgroundColor'] : '#F8981B'; 
		$textColor = isset( $instance[ 'textColor' ]) ? $instance['textColor'] : '#F8981B'; 		
		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p><b>Fields :</b></p>
		<div style="display:flex">
			<div style="width:40%" class="checkbox"> 
				<label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'showPhoneField' ); ?>" name="<?php echo $this->get_field_name( 'showPhoneField' ); ?>" type="checkbox" <?php echo $showPhoneFieldValue ?> /> Phone
				</label> 
			</div>
			<div style="width:40%" class="checkbox"> 
				<label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'showNotesField' ); ?>" name="<?php echo $this->get_field_name( 'showNotesField' ); ?>" type="checkbox" <?php echo $showNotesFieldValue ?> /> Notes
				</label> 
			</div>
		</div>	

		<p><b>Options :</b></p>		
		
		<p>
		<div class="checkbox"> 
			<label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'showPriceDetails' ); ?>" name="<?php echo $this->get_field_name( 'showPriceDetails' ); ?>" type="checkbox" <?php echo $showPriceDetailsLinkValue ?> /> Show price details
			</label> 
		</div>
		</p>

		<p>
		<div class="checkbox"> 
			<label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'showPrice' ); ?>" name="<?php echo $this->get_field_name( 'showPrice' ); ?>" type="checkbox" <?php echo $showPriceValue ?> /> Show dynamic pricing
			</label> 
		</div>
		</p>

		<p>
		<div class="checkbox"> 
			<label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'showMinStay' ); ?>" name="<?php echo $this->get_field_name( 'showMinStay' ); ?>" type="checkbox" <?php echo $showMinStayValue ?> /> Show minimum stay
			</label> 
		</div>
		</p>

		<p>
		<div class="checkbox"> 
			<label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'showAvailability' ); ?>" name="<?php echo $this->get_field_name( 'showAvailability' ); ?>" type="checkbox" <?php echo $showAvailabilityValue ?> /> Prevent inquiries for already booked dates
			</label> 
		</div>
		</p>

		<p><b>Colors :</b></p>		

		<p>
		<label for="<?php echo $this->get_field_id( 'widgetColor' ); ?>"><?php _e( 'Background color:' ); ?></label> 
		<input class="widefat color-field" id="<?php echo $this->get_field_id( 'widgetColor' ); ?>" name="<?php echo $this->get_field_name( 'widgetColor' ); ?>" type="text" data-palette="<?php echo esc_attr( $widgetColor ); ?>" value="<?php echo esc_attr( $widgetColor ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'textColor' ); ?>"><?php _e( 'Text color:' ); ?></label> 
		<input class="widefat color-field" id="<?php echo $this->get_field_id( 'textColor' ); ?>" name="<?php echo $this->get_field_name( 'textColor' ); ?>" type="text" data-palette="<?php echo esc_attr( $textColor ); ?>" value="<?php echo esc_attr( $textColor ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'buttonBackgroundColor' ); ?>"><?php _e( 'Button background color:' ); ?></label> 
		<input class="widefat color-field" id="<?php echo $this->get_field_id( 'buttonBackgroundColortextColor' ); ?>" name="<?php echo $this->get_field_name( 'buttonBackgroundColor' ); ?>" type="text" data-palette="<?php echo esc_attr( $buttonBackgroundColor ); ?>" value="<?php echo esc_attr( $buttonBackgroundColor ); ?>" />
		</p>

		
		<script>
			$(document).ready(function () {
				$('.color-field').wpColorPicker({
					change: function () {

						var $form = $(this).closest('form');
						$($form).change();
					}
				});
		  	});
		</script>
		<?php
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['widgetColor'] = ( ! empty( $new_instance['widgetColor'] ) ) ? $new_instance['widgetColor']  : '#ffffff';	
		$instance['textColor'] = ( ! empty( $new_instance['textColor'] ) ) ? $new_instance['textColor']  : '#000000';
		$instance['buttonBackgroundColor'] = ( ! empty( $new_instance['buttonBackgroundColor'] ) ) ? $new_instance['buttonBackgroundColor']  : '#FFFFFF';
		$instance['showPriceDetails'] = !empty( $new_instance['showPriceDetails']) ? 'true' : 'false';
		$instance['showPrice'] = !empty( $new_instance['showPrice']) ? 'true' : 'false';
		$instance['showMinStay'] = !empty( $new_instance['showMinStay']) ? 'true' : 'false';
		$instance['showAvailability'] = !empty( $new_instance['showAvailability']) ? 'true' : 'false';
		$instance['showPhoneField'] = !empty( $new_instance['showPhoneField']) ? 'true' : 'false';
		$instance['showNotesField'] = !empty( $new_instance['showNotesField']) ? 'true' : 'false';
		
		return $instance;
	}
} // Class wpb_widget ends here
?>