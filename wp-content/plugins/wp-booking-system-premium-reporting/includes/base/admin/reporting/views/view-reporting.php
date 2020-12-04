<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if(isset($_GET['wpbs-reporting-start-date'])){
	$reporting_date = esc_attr($_GET['wpbs-reporting-start-date']);
	$reporting_interval = esc_attr($_GET['wpbs-reporting-interval']);
} else {
	$date = new DateTime('7 days ago'); 
	$reporting_date = $date->format('Y-m-d');
	$reporting_interval = 'day';
}

$reports = new WPBS_Reporting($reporting_date, $reporting_interval);



?>

<!-- WPBS Reporting Wrap -->
<div class="wrap wpbs-wrap wpbs-wrap-reporting">

	<!-- WPBS Reporting Date Interval -->
	<div class="wpbs-reporting-date-interval">
		<span>Show reports for </span> 
		<select name="wpbs-reporting-date-interval-selector" id="wpbs-reporting-date-interval-selector" data-url="<?php echo add_query_arg( array( 'page' => 'wpbs-reporting' ), admin_url('admin.php') );?>">

			<optgroup label="Daily">
				<option value="<?php $date = new DateTime('7 days ago'); echo $date->format('Y-m-d');?>" <?php echo selected($reporting_date, $date->format('Y-m-d')) ?> data-interval="day">Last 7 days</option>
				<option value="<?php $date = new DateTime('14 days ago'); echo $date->format('Y-m-d');?>" <?php echo selected($reporting_date, $date->format('Y-m-d')) ?> data-interval="day">Last 14 days</option>
				<option value="<?php $date = new DateTime('21 days ago'); echo $date->format('Y-m-d');?>" <?php echo selected($reporting_date, $date->format('Y-m-d')) ?> data-interval="day">Last 21 days</option>
				<option value="<?php $date = new DateTime('28 days ago'); echo $date->format('Y-m-d');?>" <?php echo selected($reporting_date, $date->format('Y-m-d')) ?> data-interval="day">Last 28 days</option>
				<option value="<?php $date = new DateTime('now'); echo $date->format('Y-m-01');?>" <?php echo selected($reporting_date, $date->format('Y-m-01')) ?> data-interval="day">Month to date</option>
			</optgroup>
			<optgroup label="Monthly">
				<option value="<?php $date = new DateTime('3 months ago'); echo $date->format('Y-m-01');?>" <?php echo selected($reporting_date, $date->format('Y-m-01')) ?> data-interval="month">Last 3 months</option>
				<option value="<?php $date = new DateTime('6 months ago'); echo $date->format('Y-m-01');?>" <?php echo selected($reporting_date, $date->format('Y-m-01')) ?> data-interval="month">Last 6 months</option>
				<option value="<?php $date = new DateTime('9 months ago'); echo $date->format('Y-m-01');?>" <?php echo selected($reporting_date, $date->format('Y-m-01')) ?> data-interval="month">Last 9 months</option>
				<option value="<?php $date = new DateTime('12 months ago'); echo $date->format('Y-m-01');?>" <?php echo selected($reporting_date, $date->format('Y-m-01')) ?> data-interval="month">Last 12 months</option>
				<option value="<?php $date = new DateTime('18 months ago'); echo $date->format('Y-m-01');?>" <?php echo selected($reporting_date, $date->format('Y-m-01')) ?> data-interval="month">Last 18 months</option>
				<option value="<?php $date = new DateTime('now'); echo $date->format('Y-01-01');?>" <?php echo selected($reporting_date, $date->format('Y-01-01')) ?> data-interval="month">Year to date</option>
			</optgroup>
			<optgroup label="Yearly">
				<option value="<?php $date = new DateTime('1 year ago'); echo $date->format('Y-01-01');?>" <?php echo selected($reporting_date, $date->format('Y-01-01')) ?> data-interval="year">Last year</option>
				<option value="<?php $date = new DateTime('2 years ago'); echo $date->format('Y-01-01');?>" <?php echo selected($reporting_date, $date->format('Y-01-01')) ?> data-interval="year">Last 2 years</option>
				<option value="<?php $date = new DateTime('3 years ago'); echo $date->format('Y-01-01');?>" <?php echo selected($reporting_date, $date->format('Y-01-01')) ?> data-interval="year">Last 3 years</option>
				<option value="<?php $date = new DateTime('5 years ago'); echo $date->format('Y-01-01');?>" <?php echo selected($reporting_date, $date->format('Y-01-01')) ?> data-interval="year">Last 5 years</option>
			</optgroup>

		</select>
	</div>


	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Reporting', 'wp-booking-system-reporting'); ?></h1>
	<hr class="wp-header-end" />

	
	<!-- Reporting Container -->
	<div class="wpbs-reporting-container">

		<!-- Reporting Row  -->
		<div class="wpbs-reporting-row">

			<!-- Chart -->
			<div class="wpbs-reporting-chart">
				
				<h2>Number of Bookings</h2>

				<!-- Chart Wrap -->
				<div class="wpbs-reporting-chart-wrap">
					<canvas class="wpbs-chart" data-tooltip="bookings" data-chart="<?php echo esc_attr(json_encode($reports->chart_data_bookings()));?>"></canvas>
				</div>

			</div>

			<!-- Chart Stats -->
			<div class="wpbs-reporting-chart-stats">
				
				<!-- Chart Stat Total -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Total</h2>
					<h3><?php echo $reports->total_bookings() ?></h3>
					<small>bookings</small>
				</div>


				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Average</h2>
					<h3><?php echo $reports->average_bookings() ?></h3>
					<small>bookings per <?php echo $reports->difference_interval() ?></small>
				</div>

			</div>

		</div>


		<!-- Reporting Row  -->
		<div class="wpbs-reporting-row">

			<!-- Chart -->
			<div class="wpbs-reporting-chart">
				
				<h2>Revenue</h2>

				<div class="wpbs-reporting-chart-wrap">
					<canvas class="wpbs-chart" data-tooltip="revenue" data-currency="<?php echo wpbs_get_currency();?>" data-chart="<?php echo esc_attr(json_encode($reports->chart_data_revenue()));?>"></canvas>
				</div>

			</div>

			<!-- Chart Stats -->
			<div class="wpbs-reporting-chart-stats">

				<!-- Chart Stat Total -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Total</h2>
					<h3><?php echo $reports->total_revenue() ?></h3>
					<small><?php echo wpbs_get_currency();?></small>
				</div>


				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Average</h2>
					<h3><?php echo $reports->average_revenue_per_interval() ?></h3>
					<small><?php echo wpbs_get_currency();?> per <?php echo $reports->difference_interval() ?></small>
				</div>

				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Average</h2>
					<h3><?php echo $reports->average_revenue_per_booking() ?></h3>
					<small><?php echo wpbs_get_currency();?> per booking</small>
				</div>

			</div>

		</div>


		<!-- Reporting Row  -->
		<div class="wpbs-reporting-row">

			<!-- Chart -->
			<div class="wpbs-reporting-chart">
				
				<h2>Number of Nights Booked</h2>

				<div class="wpbs-reporting-chart-wrap">
					<canvas class="wpbs-chart" data-tooltip="nights" data-chart="<?php echo esc_attr(json_encode($reports->chart_data_nights_booked()));?>"></canvas>
				</div>

			</div>

			<!-- Chart Stats -->
			<div class="wpbs-reporting-chart-stats">

				<!-- Chart Stat Total -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Total</h2>
					<h3><?php echo $reports->total_nights_booked() ?></h3>
					<small>nights</small>
				</div>


				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Average</h2>
					<h3><?php echo $reports->average_nights_booked_per_interval() ?></h3>
					<small>nights per <?php echo $reports->difference_interval() ?></small>
				</div>

				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Average</h2>
					<h3><?php echo $reports->average_nights_booked_per_booking() ?></h3>
					<small>nights per booking</small>
				</div>

			</div>

		</div>

		<!-- Reporting Row  -->
		<div class="wpbs-reporting-row">

			<!-- Chart -->
			<div class="wpbs-reporting-chart">
				
				<h2>Number of Days Booked</h2>

				<div class="wpbs-reporting-chart-wrap">
					<canvas class="wpbs-chart" data-tooltip="days" data-chart="<?php echo esc_attr(json_encode($reports->chart_data_days_booked()));?>"></canvas>
				</div>

			</div>

			<!-- Chart Stats -->
			<div class="wpbs-reporting-chart-stats">

				<!-- Chart Stat Total -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Total</h2>
					<h3><?php echo $reports->total_days_booked() ?></h3>
					<small>days</small>
				</div>


				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Average</h2>
					<h3><?php echo $reports->average_days_booked_per_interval() ?></h3>
					<small>days booked per <?php echo $reports->difference_interval() ?></small>
				</div>

				<!-- Chart Stat Average -->
				<div class="wpbs-reporting-chart-stat">
					<h2>Average</h2>
					<h3><?php echo $reports->average_days_booked_per_booking() ?></h3>
					<small>days per booking</small>
				</div>

			</div>

		</div>

	</div>


</div>

