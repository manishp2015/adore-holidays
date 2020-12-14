<?php
$start_date = '';
if(!is_null($this->start_date) && $start_date = DateTime::createFromFormat('Y-m-d', $this->start_date)){
    $start_date = date_i18n(get_option('date_format'), $start_date->format('U'));
}

$end_date = '';
if(!is_null($this->end_date) && $end_date = DateTime::createFromFormat('Y-m-d', $this->end_date)){
    $end_date = date_i18n(get_option('date_format'), $end_date->format('U'));
}
?>

<?php if($this->args['title'] == 'yes'): ?>
<h2><?php echo $this->get_search_widget_string('widget_title'); ?></h2>
<?php endif; ?>

<form action="#" method="get" class="wpbs_s-search-widget-form" autocomplete="off"> 

    <div class="wpbs_s-search-widget-field col-lg-4 col-md-4">
        <label for="wpbs_s-search-widget-datepicker-start-date-<?php echo $this->unique;?>"><?php echo $this->get_search_widget_string('start_date_label'); ?></label>
        <input type="text" value="<?php echo $start_date; ?>" id="wpbs_s-search-widget-datepicker-start-date-<?php echo $this->unique;?>" class="wpbs_s-search-widget-datepicker wpbs_s-search-widget-datepicker-start-date" name="start-date" />
        <input type="hidden" value="<?php echo (!is_null($this->start_date)) ? $this->start_date : '';?>" id="wpbs_s-search-widget-datepicker-standard-format-start-date-<?php echo $this->unique;?>" class="wpbs_s-search-widget-datepicker-standard-format-start-date" name="start-date-standard-format" />
    </div>

    <div class="wpbs_s-search-widget-field col-lg-4 col-md-4">
        <label for="wpbs_s-search-widget-datepicker-end-date-<?php echo $this->unique;?>"><?php echo $this->get_search_widget_string('end_date_label'); ?></label>
        <input type="text" value="<?php echo $end_date; ?>" id="wpbs_s-search-widget-datepicker-end-date-<?php echo $this->unique;?>" class="wpbs_s-search-widget-datepicker wpbs_s-search-widget-datepicker-end-date" name="end-date" />
        <input type="hidden" value="<?php echo (!is_null($this->end_date)) ? $this->end_date : '';?>" id="wpbs_s-search-widget-datepicker-standard-format-end-date-<?php echo $this->unique;?>" class="wpbs_s-search-widget-datepicker-standard-format-end-date" name="end-date-standard-format" />
    </div>

    <div class="wpbs_s-search-widget-field input-group-lg col-lg-3 col-md-3">
        <label for="">No of Guests</label>
        <input type="number" name="sleeps" class="form-control" placeholder="">
    </div>

    <div class="wpbs_s-search-widget-field wpbs_s-search-widget-field-submit col-lg-2 col-md-2">
        <button class="wpbs_s-search-widget-datepicker-submit"><?php echo $this->get_search_widget_string('search_button_label'); ?></button>
    </div>

</form>
