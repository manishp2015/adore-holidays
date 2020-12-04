jQuery(function ($) {

    /**
     * Resizes the calendar to always have square dates
     *
     */
    function resize_calendar($calendars_wrapper) {

        /**
         * Set variables
         *
         */
        var $months_wrapper = $calendars_wrapper.find('.wpbs-calendars-wrapper');
        var $months_wrapper_width = $calendars_wrapper.find('.wpbs-calendars');
        var calendar_min_width = $calendars_wrapper.data('min_width');
        var calendar_max_width = $calendars_wrapper.data('max_width');

        var $month_inner = $calendars_wrapper.find('.wpbs-calendar-wrapper');

        /**
         * Set the calendar months min and max width from the data attributes
         *
         */
        if ($calendars_wrapper.data('min_width') > 0)
            $calendars_wrapper.find('.wpbs-calendar').css('min-width', calendar_min_width);

        if ($calendars_wrapper.data('max_width') > 0)
            $calendars_wrapper.find('.wpbs-calendar').css('max-width', calendar_max_width)


        /**
         * Set the column count
         *
         */
        var column_count = 0;

        if ($months_wrapper_width.width() < calendar_min_width * 2)
            column_count = 1;

        else if ($months_wrapper_width.width() < calendar_min_width * 3)
            column_count = 2;

        else if ($months_wrapper_width.width() < calendar_min_width * 4)
            column_count = 3;

        else if ($months_wrapper_width.width() < calendar_min_width * 6)
            column_count = 4;

        else
            column_count = 6;


        // Adjust for when there are fewer months in a calendar than columns
        if ($calendars_wrapper.find('.wpbs-calendar').length <= column_count)
            column_count = $calendars_wrapper.find('.wpbs-calendar').length;

        // Set column count
        $calendars_wrapper.attr('data-columns', column_count);


        /**
         * Set the max-width of the calendars container that has a side legend
         *
         */
        if ($months_wrapper.hasClass('wpbs-legend-position-side')) {

            $months_wrapper.css('max-width', 'none');
            $months_wrapper.css('max-width', $calendars_wrapper.find('.wpbs-calendar').first().outerWidth(true) * column_count);

        }


        /**
         * Handle the height of each date
         *
         */
        var td_width = $calendars_wrapper.find('td').first().width();

        $calendars_wrapper.find('td .wpbs-date-inner, td .wpbs-week-number').css('height', Math.ceil(td_width) + 1 + 'px');
        $calendars_wrapper.find('td .wpbs-date-inner, td .wpbs-week-number').css('line-height', Math.ceil(td_width) + 1 + 'px');

        var th_height = $calendars_wrapper.find('th').css('height', 'auto').first().height();
        $calendars_wrapper.find('th').css('height', Math.ceil(th_height) + 1 + 'px');

        /**
         * Set calendar month height
         *
         */
        var calendar_month_height = 0;

        $month_inner.css('min-height', '1px');

        $month_inner.each(function () {

            if ($(this).height() >= calendar_month_height)
                calendar_month_height = $(this).height();

        });

        $month_inner.css('min-height', Math.ceil(calendar_month_height) + 'px');

        /**
         * Show the calendars
         *
         */
        $calendars_wrapper.css('visibility', 'visible');

    }

    /**
     * Helper function to add leading zeros to numbers.
     * 
     */
    function wpbs_pad(num, size) {
        var s = "00" + num;
        return s.substr(s.length - size);
    }


    /**
     * Resizes the calendar overview to overflow
     *
     */
    // Add min-height to calendar overview headings
    function resize_calendar_overview($calendar_container) {
        $calendar_container.find(".wpbs-overview-row .wpbs-overview-row-header").each(function () {
            $(this).parent().find('.wpbs-calendar-wrapper .wpbs-date').css('min-height', $(this).find('.wpbs-overview-row-header-inner').outerHeight(true)).css('line-height', $(this).find('.wpbs-overview-row-header-inner').outerHeight(true) + 'px');
        });
    }

    /**
     * Refreshed the output of the calendar with the given data
     *
     */
    function refresh_calendar($calendar_container, current_year, current_month) {

        var $calendar_container = $calendar_container;

        if ($calendar_container.hasClass('wpbs-is-loading'))
            return false;

        /**
         * Prepare the calendar data
         *
         */
        var data = $calendar_container.data();

        data['action'] = 'wpbs_refresh_calendar';
        data['current_year'] = current_year;
        data['current_month'] = current_month;

        /**
         * Add loading animation
         *
         */
        $calendar_container.find('.wpbs-calendar').append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
        $calendar_container.addClass('wpbs-is-loading');
        $calendar_container.find('select').attr('disabled', true);

        /**
         * Set ajaxurl in the front-end
         *
         */
        if (typeof wpbs_ajaxurl != 'undefined')
            ajaxurl = wpbs_ajaxurl;


        /**
         * Make the request
         *
         */
        $.post(ajaxurl, data, function (response) {

            $calendar_container.replaceWith(response);

            $('.wpbs-container').each(function () {
                resize_calendar($(this));
                wpbs_mark_selected_dates($(this).parents('.wpbs-main-wrapper'));
                wpbs_set_off_screen_date_limits($(this).parents('.wpbs-main-wrapper'));

                if (!$(this).siblings('form').length) {
                    $(this).removeClass('wpbs-enable-hover')
                }
            });


        });

    }


    /**
     * Refreshed the output of the calendar with the given data
     *
     */
    function refresh_calendar_overview($calendar_container, current_year, current_month) {

        var $calendar_container = $calendar_container;

        if ($calendar_container.hasClass('wpbs-is-loading'))
            return false;

        /**
         * Prepare the calendar data
         *
         */
        var data = $calendar_container.data();

        data['action'] = 'wpbs_refresh_calendar_overview';
        data['current_year'] = current_year;
        data['current_month'] = current_month;

        /**
         * Add loading animation
         *
         */
        $calendar_container.find('.wpbs-overview-inner').append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
        $calendar_container.addClass('wpbs-is-loading');
        $calendar_container.find('select').attr('disabled', true);

        /**
         * Set ajaxurl in the front-end
         *
         */
        if (typeof wpbs_ajaxurl != 'undefined')
            ajaxurl = wpbs_ajaxurl;


        /**
         * Make the request
         *
         */
        $.post(ajaxurl, data, function (response) {

            $calendar_container.replaceWith(response);

            $('.wpbs-overview-container').each(function () {
                resize_calendar_overview($(this));
            });

        });

    }


    /**
     * Resize the calendars on page load
     *
     */
    $('.wpbs-container').each(function () {
        resize_calendar($(this));
    });

    /**
     * Resize the calendars on page resize
     *
     */
    $(window).on('resize', function () {

        $('.wpbs-container').each(function () {
            resize_calendar($(this));
        });

    });


    /**
     * Handles the navigation of the Previous button
     *
     */
    $(document).on('click', '.wpbs-container .wpbs-prev', function (e) {

        e.preventDefault();

        // Set container
        var $container = $(this).closest('.wpbs-container');

        // Set the current year and month that are displayed in the calendar
        var current_month = $container.data('current_month');
        var current_year = $container.data('current_year');

        // Calculate the 
        var navigate_count = 1;

        // Take into account jump months option
        if (typeof $container.data('jump_months') != 'undefined' && $container.data('jump_months') == '1')
            navigate_count = parseInt($container.data('months_to_show'));

        for (var i = 1; i <= navigate_count; i++) {

            current_month -= 1;

            if (current_month < 1) {
                current_month = 12;
                current_year -= 1;
            }

        }

        refresh_calendar($container, current_year, current_month);

    });

    /**
     * Handles the navigation of the Next button
     *
     */
    $(document).on('click', '.wpbs-container .wpbs-next', function (e) {

        e.preventDefault();

        // Set container
        var $container = $(this).closest('.wpbs-container');

        // Set the current year and month that are displayed in the calendar
        var current_month = $container.data('current_month');
        var current_year = $container.data('current_year');

        // Calculate the 
        var navigate_count = 1;

        // Take into account jump months option
        if (typeof $container.data('jump_months') != 'undefined' && $container.data('jump_months') == '1')
            navigate_count = parseInt($container.data('months_to_show'));

        for (var i = 1; i <= navigate_count; i++) {

            current_month += 1;

            if (current_month > 12) {
                current_month = 1;
                current_year += 1;
            }

        }

        refresh_calendar($container, current_year, current_month);

    });

    /**
     * Handles the navigation of the Month Selector for the Single Calendar
     *
     */
    $(document).on('change', '.wpbs-container .wpbs-select-container select', function () {

        // Set container
        var $container = $(this).closest('.wpbs-container');

        var date = new Date($(this).val() * 1000);

        var year = date.getFullYear();
        var month = date.getMonth() + 1;

        refresh_calendar($container, year, month);

    });


    /**
     * Handles the navigation of the Month Selector for the Calendar Overview
     *
     */
    $(document).on('change', '.wpbs-overview-container .wpbs-select-container select', function () {

        // Set container
        var $container = $(this).closest('.wpbs-overview-container');

        var date = new Date($(this).val() * 1000);

        var year = date.getFullYear();
        var month = date.getMonth() + 1;

        refresh_calendar_overview($container, year, month);

    });


    /**
     * Handles display of the tooltip
     *
     */
    $(document).on('mouseenter touchstart', '.wpbs-container .wpbs-date, .wpbs-overview-container .wpbs-date', function (e) {

        var $date = $(this);

        if ($date.parents('.wpbs-date-selection-in-progress').length && e.type != 'touchstart') {
            return false;
        }

        if ($date.find('.wpbs-tooltip').length = 0)
            return false;

        var $tooltip = $date.find('.wpbs-tooltip');


        // Calculate position
        var offset_left, overflow_left;

        // Get the right overflow
        var overflow_right = $(window).width() - ($date.offset().left + $tooltip.outerWidth(true));

        // Does it overflow in the RHS?
        if (overflow_right < 0) {
            //Get the left overflow
            overflow_left = 0 - ($tooltip.outerWidth(true) - $date.offset().left);
            // Does it overflow in the LHS as well? On which site is the overflow greater? 
            if (overflow_left < 0 && overflow_right > overflow_left) {
                // Overflows more in the left
                offset_left = $date.offset().left;
            } else {
                // Overflows more in the right
                offset_left = $date.offset().left - $tooltip.outerWidth(true) + $date.outerWidth(true);
            }
        } else {
            // It doesn't overflow.
            offset_left = $date.offset().left;
        }

        $tooltip.css('left', offset_left);
        $tooltip.css('top', $date.offset().top - $tooltip.outerHeight() - $(window).scrollTop() - 2);

        $date.addClass('wpbs-tooltip-active');

    });

    /**
     * Handle hiding of the tooltip
     *
     */
    $(document).on('mouseleave', '.wpbs-container .wpbs-date, .wpbs-overview-container .wpbs-date', function () {

        var $date = $(this);

        if ($date.find('.wpbs-tooltip').length = 0)
            return false;

        $date.removeClass('wpbs-tooltip-active');

    });

    $(window).scroll(function () {

        $('.wpbs-date').removeClass('wpbs-tooltip-active');

    });

    /**
     * Handle the initialization of the Overview Calendars
     *
     */
    $('.wpbs-overview-container').each(function () {
        resize_calendar_overview($(this));
    });

    /**
     * Resize the calendars on page resize
     *
     */
    $(window).on('resize', function () {

        $('.wpbs-overview-container').each(function () {
            resize_calendar_overview($(this));
        });

    });

    /**
     * Re-calculate prices on form elements change
     * 
     */
    $(document).on("change keyup", ".wpbs-form-field-product_radio input, .wpbs-form-field-product_checkbox input, .wpbs-form-field-product_dropdown select, .wpbs-form-field-radio input, .wpbs-form-field-checkbox input, .wpbs-form-field-dropdown select, .wpbs-form-field-payment_method input, .wpbs-form-field-inventory select", function () {
        wpbs_calculate_price($(this).parents('.wpbs-main-wrapper'));
    })

    /**
     * Event Listener for refreshing pricing tables
     * 
     */
    $(document).on('wpbs_calculate_price', function () {
        $('.wpbs-main-wrapper').each(function () {
            wpbs_calculate_price($(this));
        })
    })

    /**
     * Show Payment method description
     * 
     */
    $(document).on("change", ".wpbs-form-field-payment_method input[type='radio']", function () {
        $(this).parents('.wpbs-form-field-payment_method').find('p.wpbs-payment-method-description-open').removeClass('wpbs-payment-method-description-open');
        $(this).parent().next('p').addClass('wpbs-payment-method-description-open');
    })

    /**
     * Do not allow anything else than numbers on Phone form fields
     * 
     */
    $('body').on('keydown', ".wpbs-form-field-phone input", function (e) {

        var key = e.which || e.keyCode;

        if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
            // numbers   
            key >= 48 && key <= 57 ||
            // Numeric keypad
            key >= 96 && key <= 105 ||
            // comma, period and minus, . on keypad
            // plus
            key == 107 || key == 109 || key == 189 || key == 187 || key == 57 || key == 48 || key == 32 ||
            // Backspace and Tab and Enter
            key == 8 || key == 9 || key == 13 ||
            // Home and End
            key == 35 || key == 36 ||
            // left and right arrows
            key == 37 || key == 39 ||
            // Del and Ins
            key == 46 || key == 45)
            return true;

        return false;
    });

    /***********************************
     * Form Scripts
     *
     **********************************/


    /**
     * Submitting the form
     * 
     */

    $(".wpbs-main-wrapper").on('submit', '.wpbs-form-container', function (e) {
        e.preventDefault();

        $form = $(this);

        var $calendar_wrapper = $form.parents('.wpbs-main-wrapper');
        var $calendar = $calendar_wrapper.find('.wpbs-container');

        // Show Loader
        $form.append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
        $form.addClass('wpbs-is-loading');
        $form.find('.wpbs-form-submit-button button').attr('disabled', true);

        /**
         * Set ajaxurl in the front-end
         *
         */
        if (typeof wpbs_ajaxurl != 'undefined')
            ajaxurl = wpbs_ajaxurl;

        /**
         * Prepare the calendar data
         *
         */
        var data = {};

        data['action'] = 'wpbs_submit_form';

        data['form'] = $form.data()
        data['calendar'] = $calendar.data();

        data['wpbs_token'] = wpbs_ajax.token;
        data['wpbs_permalink'] = wpbs_ajax.permalink;
        data['form_data'] = $form.serialize();



        $.post(ajaxurl, data, function (response) {

            response = JSON.parse(response);

            // If validation failed, we show the form again
            if (response.success === false) {

                // Scroll to the top of the calendar
                if ($(window).scrollTop() > $form.offset().top) {
                    $('html, body').stop().animate({ scrollTop: $form.offset().top })
                }

                $form.replaceWith(response.html);
                wpbs_render_recaptcha();
                wpbs_calculate_price($calendar_wrapper);
                wpbs_display_selected_dates($calendar_wrapper);

                // Revalidate coupon code
                if ($calendar_wrapper.find('.wpbs-coupon-code input').val()) {
                    $calendar_wrapper.find('.wpbs-coupon-code-add').trigger('click');
                }

                // If validation succeeded, we show the form confirmation
            } else if (response.success === true) {

                // Tracking Script
                if (response.tracking_script) {
                    $("<script>" + response.tracking_script + "</script>").insertAfter($form);
                }

                if (response.confirmation_type == 'redirect') {
                    // Redirect
                    window.location.href = response.confirmation_redirect_url;
                } else {
                    // Message

                    var confirmation_message = (typeof response.confirmation_message !== 'undefined') ? response.confirmation_message : '<p>The form was successfully submitted.</p>';

                    if ($form.parents('.wpbs-payment-confirmation').length) {
                        $form.parents('.wpbs-payment-confirmation').replaceWith('<div class="wpbs-form-confirmation-message">' + confirmation_message + '</div>');
                    } else {
                        $form.replaceWith('<div class="wpbs-form-confirmation-message">' + confirmation_message + '</div>');

                        // Scroll to the top of the calendar
                        $('html, body').stop().animate({ scrollTop: $calendar_wrapper.offset().top })
                    }

                    // Refresh the Calendar

                    // Clear Selection
                    wpbs_remove_selection_dates($calendar_wrapper);
                    $calendar_wrapper.data('future_date_limit', 'infinite');
                    $calendar_wrapper.data('past_date_limit', 'infinite');

                    var current_month = $calendar.data('current_month');
                    var current_year = $calendar.data('current_year');
                    refresh_calendar($calendar, current_year, current_month);

                }

            }
        });
    })


    /**
     * Render all captchas on Window Load
     * 
     */
    $(window).on('load', function () {
        wpbs_render_recaptcha();
    })

    /**
     * Function that renders the Google reCAPTCHA
     * 
     */
    function wpbs_render_recaptcha() {

        // Check if we're using reCAPTCHA v2
        if ($(".wpbs-google-recaptcha-v2").length) {

            $(".wpbs-google-recaptcha-v2").each(function () {
                $recaptcha = $(this);

                if ($recaptcha.find('iframe').length) {
                    return true;
                }

                grecaptcha.render($recaptcha.attr('id'), {
                    'sitekey': $recaptcha.data('sitekey')
                });
            });

        }

        // ... or reCAPTCHA v3
        if ($(".wpbs-google-recaptcha-v3").length) {
            $(".wpbs-google-recaptcha-v3").each(function () {
                $recaptcha = $(this);

                grecaptcha.execute($recaptcha.data('sitekey'), { action: "wpbs_form" }).then(function (token) {
                    var recaptchaResponse = $recaptcha.get(0);
                    recaptchaResponse.value = token;
                });

            });
        }

    }


    /***********************************
     * Calendar Selection Scripts
     *
     **********************************/

    /**
     * Check if a date selection already exists on page load.
     * 
     */
    $('.wpbs-main-wrapper').each(function () {
        wpbs_mark_selected_dates($(this));
        wpbs_set_off_screen_date_limits($(this));
    })

    /**
     * Handle date selection clicks
     * 
     */
    $(document).on('click', '.wpbs-container .wpbs-is-bookable', function () {

        $el = $(this);

        $calendar_instance = $el.parents('.wpbs-main-wrapper');

        // Exit if the user clicks on a calendar gap.
        if ($el.hasClass('wpbs-gap')) {
            return false;
        }

        // Exit if there is no form attached to the calendar
        if ($calendar_instance.hasClass('wpbs-main-wrapper-form-0')) {
            return false;
        }

        /**
         * Multiple Date Selection
         */
        if ($calendar_instance.find('.wpbs-form-container[data-selection_type="multiple"]').length) {

            if (wpbs_get_selection_start_date($calendar_instance) === false) {
                // No dates selected

                // Set off-screen limits
                $calendar_instance.data('future_date_limit', 'infinite');
                $calendar_instance.data('past_date_limit', 'infinite');

                // Set starting date
                wpbs_set_selection_start_date(wpbs_get_element_date($el), $calendar_instance);

                // Search for limits
                wpbs_set_off_screen_date_limits($calendar_instance);

                // Display selected dates in form
                wpbs_display_selected_dates($calendar_instance);

                // Trigger mouseenter
                if (!wpbs_is_touch_device())
                    $el.trigger('mouseenter');

            } else if (wpbs_get_selection_start_date($calendar_instance) !== false && wpbs_get_selection_end_date($calendar_instance) === false) {
                // Only start day is selected

                // Trigger mouseenter - fixes single date selection on mobile devices
                if (wpbs_is_touch_device())
                    $el.trigger('mouseenter');

                // Don't allow user to click and end selection on an invalid date
                if (!$el.hasClass('wpbs-date-hover')) {
                    return false;
                }

                // Set ending date
                wpbs_set_selection_end_date(wpbs_get_element_date($el), $calendar_instance);

                // Select the dates
                wpbs_mark_selected_dates($calendar_instance);

                // Enable CSS hovering
                $calendar_instance.find('.wpbs-container').addClass('wpbs-enable-hover');

                wpbs_validate_date_selection($calendar_instance);

            } else if (wpbs_get_selection_start_date($calendar_instance) !== false && wpbs_get_selection_end_date($calendar_instance) !== false) {
                // Both start and end day selected, clear selection and start selection again.

                // Clear Selection
                wpbs_remove_selection_dates($calendar_instance);

                $(document).trigger('wpbs_dates_deselected', [$calendar_instance]);

                // Set off-screen limits
                $calendar_instance.data('future_date_limit', 'infinite');
                $calendar_instance.data('past_date_limit', 'infinite');

                // Set starting date
                wpbs_set_selection_start_date(wpbs_get_element_date($el), $calendar_instance);

                // Search for limits
                wpbs_set_off_screen_date_limits($calendar_instance);

                // Display selected dates in form
                wpbs_display_selected_dates($calendar_instance);

                // Trigger mouseenter
                if (!wpbs_is_touch_device())
                    $el.trigger('mouseenter');
            }

        }

        /**
        * Single Date Selection
        */
        if ($calendar_instance.find('.wpbs-form-container[data-selection_type="single"]').length) {
            wpbs_remove_selection_dates($calendar_instance);
            wpbs_set_selection_start_date(wpbs_get_element_date($el), $calendar_instance);
            wpbs_set_selection_end_date(wpbs_get_element_date($el), $calendar_instance);
            $el.addClass('wpbs-date-selected');

            // Select the dates
            wpbs_mark_selected_dates($calendar_instance);

            // Display selected dates in form
            wpbs_display_selected_dates($calendar_instance);

            wpbs_validate_date_selection($calendar_instance);
        }

    });

    /**
     * Mouseenter event on dates
     * 
     */
    $(document).on('mouseenter', '.wpbs-container .wpbs-is-bookable', function () {

        $el = $(this);

        $calendar_instance = $el.parents('.wpbs-main-wrapper');

        // Exit if there is no form attached to the calendar
        if ($calendar_instance.hasClass('wpbs-main-wrapper-form-0')) {
            return false;
        }

        // Only hover if start date is selected and end date is empty.
        if (wpbs_get_selection_start_date($calendar_instance) === false || wpbs_get_selection_end_date($calendar_instance) !== false) return false;


        // Disable CSS hovering
        $calendar_instance.find('.wpbs-container').removeClass('wpbs-enable-hover');

        // The date we're hovering on
        current_date = wpbs_get_element_date($el);

        // The starting date
        selection_start_date = wpbs_get_selection_start_date($calendar_instance);
        // Clear all hovers and add them again below
        $calendar_instance.find('.wpbs-container .wpbs-date').removeClass('wpbs-date-hover');

        // The loops
        if (current_date > selection_start_date) {
            // Forward selection
            start_date = selection_start_date;
            end_date = current_date;

            // Loop through dates
            for (var i = start_date; i <= end_date; i.setUTCDate(i.getUTCDate() + 1)) {
                if (wpbs_mark_hover_selection(i, $calendar_instance) === false) break;
            }

        } else {
            // Backward selection
            start_date = current_date;
            end_date = selection_start_date;

            // Loop through dates
            for (var i = end_date; i >= start_date; i.setUTCDate(i.getUTCDate() - 1)) {
                if (wpbs_mark_hover_selection(i, $calendar_instance) === false) break;
            }
        }

        // Show the selection with split days
        if ($calendar_instance.find('.wpbs-form-container[data-selection_style="split"]').length) {
            $calendar_instance.find(".wpbs-date").removeClass('wpbs-selected-first').removeClass('wpbs-selected-last');
            $calendar_instance.find(".wpbs-date .wpbs-legend-icon-select").remove();

            // Get dates again.
            selection_start_date = wpbs_get_selection_start_date($calendar_instance);
            current_date = wpbs_get_element_date($el);

            if (current_date > selection_start_date) {
                // Forward selection
                start_date = selection_start_date;
                end_date = current_date;
            } else {
                // Backward selection
                start_date = current_date;
                end_date = selection_start_date;
            }

            // Check if start or end dates are visible on screen
            if ($calendar_instance.find('.wpbs-container .wpbs-date[data-day="' + start_date.getUTCDate() + '"][data-month="' + (start_date.getUTCMonth() + 1) + '"][data-year="' + start_date.getUTCFullYear() + '"]').length) {
                $calendar_instance.find(".wpbs-date-hover").first().addClass('wpbs-selected-first').find('.wpbs-legend-item-icon').append('<div class="wpbs-legend-icon-select"><svg height="100%" width="100%" viewBox="0 0 50 50" preserveAspectRatio="none"><polygon points="0,50 50,50 50,0" /></svg></div>');
            }

            if ($calendar_instance.find('.wpbs-container .wpbs-date[data-day="' + end_date.getUTCDate() + '"][data-month="' + (end_date.getUTCMonth() + 1) + '"][data-year="' + end_date.getUTCFullYear() + '"]').length) {
                $calendar_instance.find(".wpbs-date-hover").last().addClass('wpbs-selected-last').find('.wpbs-legend-item-icon').append('<div class="wpbs-legend-icon-select"><svg height="100%" width="100%" viewBox="0 0 50 50" preserveAspectRatio="none"><polygon points="0,0 0,50 50,0" /></svg></div>');
            }

        }

    })

    /**
     * Set selection start date
     * 
     */
    function wpbs_set_selection_start_date(date, $calendar_instance) {

        // Add date selection class
        $calendar_instance.addClass('wpbs-date-selection-in-progress');
        $calendar_instance.removeClass('wpbs-dates-selected');

        $calendar_instance.find(".wpbs-container").data('start_date', date.getTime());
    }

    /**
     * Set selection end date
     * 
     */
    function wpbs_set_selection_end_date(date, $calendar_instance) {

        // Remove date selection class
        $calendar_instance.removeClass('wpbs-date-selection-in-progress');

        start_date = wpbs_get_selection_start_date($calendar_instance);

        if (start_date.getTime() > date) {
            // If start date is greater than end date, put them in the correct order.
            wpbs_set_selection_start_date(date, $calendar_instance);
            start_date.setUTCDate(start_date.getUTCDate());
            $calendar_instance.find(".wpbs-container").data('end_date', start_date.getTime());
        } else {
            // If not, just save end date as is.
            $calendar_instance.find(".wpbs-container").data('end_date', date.getTime());
        }
    }

    /**
     * Get selection start date
     * 
     */
    function wpbs_get_selection_start_date($calendar_instance) {
        if (typeof $calendar_instance.find(".wpbs-container").data('start_date') === 'undefined' || $calendar_instance.find(".wpbs-container").data('start_date') == "") {
            return false;
        }
        date = new Date($calendar_instance.find(".wpbs-container").data('start_date'))
        return date;
    }

    /**
     * Get selection end date
     * 
     */
    function wpbs_get_selection_end_date($calendar_instance) {
        if (typeof $calendar_instance.find(".wpbs-container").data('end_date') === 'undefined' || $calendar_instance.find(".wpbs-container").data('end_date') == "") {
            return false;
        }
        date = new Date($calendar_instance.find(".wpbs-container").data('end_date'))
        return date;
    }

    /**
     * Clear date selection
     * 
     */
    function wpbs_remove_selection_dates($calendar_instance) {
        $calendar_instance.find(".wpbs-container").data('start_date', 0);
        $calendar_instance.find(".wpbs-container").data('end_date', 0);
        $calendar_instance.find('.wpbs-container .wpbs-date').removeClass('wpbs-date-selected');
        $calendar_instance.find('.wpbs-container .wpbs-date').removeClass('wpbs-date-hover');

        $calendar_instance.find(".wpbs-container .wpbs-date").removeClass('wpbs-selected-first').removeClass('wpbs-selected-last');
        $calendar_instance.find(".wpbs-container .wpbs-date .wpbs-legend-icon-select").remove();

        $calendar_instance.data('future_date_limit', 'infinite');
        $calendar_instance.data('past_date_limit', 'infinite');

        // Clear Prices
        wpbs_clear_price($calendar_instance);
    }

    /**
     * Handle date hovering classes
     * 
     * @param date 
     */
    function wpbs_mark_hover_selection(date, $calendar_instance) {
        $el = $calendar_instance.find('.wpbs-container .wpbs-date[data-day="' + date.getUTCDate() + '"][data-month="' + (date.getUTCMonth() + 1) + '"][data-year="' + date.getUTCFullYear() + '"]');
        // Check if date is bookable
        if ($el.length && !$el.hasClass('wpbs-is-bookable')) return false;

        // Check if we are hovering over changeovers
        changeover_start = $calendar_instance.find(".wpbs-container").data('changeover_start');
        changeover_end = $calendar_instance.find(".wpbs-container").data('changeover_end');

        if (changeover_start && changeover_end) {

            var hovered_dates = {};

            // Create an object with the hovered dates

            // Add hovered elements
            $calendar_instance.find('.wpbs-date-hover').each(function () {
                hovered_date_legend = 'normal';
                if ($(this).hasClass('wpbs-legend-item-' + changeover_start)) hovered_date_legend = 'start';
                if ($(this).hasClass('wpbs-legend-item-' + changeover_end)) hovered_date_legend = 'end';
                hovered_dates["" + $(this).data('year') + wpbs_pad($(this).data('month'), 2) + wpbs_pad($(this).data('day'), 2)] = hovered_date_legend;
            })

            // Add current element as well
            hovered_date_legend = 'normal';
            if ($el.hasClass('wpbs-legend-item-' + changeover_start)) hovered_date_legend = 'start';
            if ($el.hasClass('wpbs-legend-item-' + changeover_end)) hovered_date_legend = 'end';
            hovered_dates["" + $el.data('year') + wpbs_pad($el.data('month'), 2) + wpbs_pad($el.data('day'), 2)] = hovered_date_legend;

            // The rule is that if a start changeover exists in an array, we shouln't allow the selection past an end changeover

            // Assume no start date found
            start_date_found = false;

            // Wether or not we should exit the selection
            exit_selection = false;

            // Loop through the object
            $.each(hovered_dates, function (date, hovered_date_legend) {
                // We found a starting date
                if (hovered_date_legend == 'start') start_date_found = true;

                // Now if we find an ending date and a starting date was previously found, we exit.
                if (hovered_date_legend == 'end' && start_date_found === true) {
                    exit_selection = true
                    return;
                }
            })

            // Exit here as well.
            if (exit_selection === true) {
                return false;
            }

        }

        // When dates are off screen, we save limits

        // Past date limit
        if ($calendar_instance.data('past_date_limit') != 'infinite' && date.getTime() < $calendar_instance.data('past_date_limit')) return false;

        //Future date limit
        if ($calendar_instance.data('future_date_limit') != 'infinite' && date.getTime() > $calendar_instance.data('future_date_limit')) return false;

        $el.addClass('wpbs-date-hover');

        return true;
    }

    /**
     * Handle date selection classes
     * 
     * @param date 
     */
    function wpbs_mark_selection(date, $calendar_instance) {
        $el = $calendar_instance.find('.wpbs-container .wpbs-date[data-day="' + date.getUTCDate() + '"][data-month="' + (date.getUTCMonth() + 1) + '"][data-year="' + date.getUTCFullYear() + '"]');
        $el.addClass('wpbs-date-selected');
    }

    /**
     * Handle date selection classes for split start
     * 
     * @param date 
     */
    function wpbs_mark_selection_split_start(date, $calendar_instance) {

        $el = $calendar_instance.find('.wpbs-container .wpbs-date[data-day="' + date.getUTCDate() + '"][data-month="' + (date.getUTCMonth() + 1) + '"][data-year="' + date.getUTCFullYear() + '"]');
        $el.addClass('wpbs-selected-first').find('.wpbs-legend-item-icon').append('<div class="wpbs-legend-icon-select"><svg height="100%" width="100%" viewBox="0 0 50 50" preserveAspectRatio="none"><polygon points="0,50 50,50 50,0" /></svg></div>');
    }

    /**
     * Handle date selection for split end
     * 
     * @param date 
     */
    function wpbs_mark_selection_split_end(date, $calendar_instance) {
        $el = $calendar_instance.find('.wpbs-container .wpbs-date[data-day="' + date.getUTCDate() + '"][data-month="' + (date.getUTCMonth() + 1) + '"][data-year="' + date.getUTCFullYear() + '"]');
        $el.addClass('wpbs-selected-last').find('.wpbs-legend-item-icon').append('<div class="wpbs-legend-icon-select"><svg height="100%" width="100%" viewBox="0 0 50 50" preserveAspectRatio="none"><polygon points="0,0 0,50 50,0" /></svg></div>');
    }

    /**
     * Handle date selection classes
     * 
     * @param date 
     */
    function wpbs_mark_selected_dates($calendar_instance) {
        // Check if start and end dates exist
        if (wpbs_get_selection_start_date($calendar_instance) === false) return;
        if (wpbs_get_selection_end_date($calendar_instance) === false) return;

        // Remove existing classes
        $calendar_instance.find(".wpbs-date").removeClass('wpbs-date-selected');
        $calendar_instance.find(".wpbs-date").removeClass('wpbs-date-hover');

        // Loop through dates
        for (var i = wpbs_get_selection_start_date($calendar_instance); i <= wpbs_get_selection_end_date($calendar_instance); i.setUTCDate(i.getUTCDate() + 1)) {
            wpbs_mark_selection(i, $calendar_instance);
        }

        // Show the selection with split days
        if ($calendar_instance.find('.wpbs-form-container[data-selection_style="split"]').length) {
            wpbs_mark_selection_split_start(wpbs_get_selection_start_date($calendar_instance), $calendar_instance);
            wpbs_mark_selection_split_end(wpbs_get_selection_end_date($calendar_instance), $calendar_instance);
        }

        $calendar_instance.addClass('wpbs-dates-selected');

        // Calculate price
        wpbs_calculate_price($calendar_instance);

        wpbs_display_selected_dates($calendar_instance);

        $(document).trigger('wpbs_dates_selected', [$calendar_instance]);

    }

    /**
     * Verify the limits of the next and previous dates
     * 
     */
    function wpbs_set_off_screen_date_limits($calendar_instance) {
        // Check if no starting date was selected
        if (wpbs_get_selection_start_date($calendar_instance) === false) {
            return false;
        }

        // If we already found both limits, stop looking
        if ($calendar_instance.data('future_date_limit') != 'infinite' && $calendar_instance.data('past_date_limit') != 'infinite') {
            return false;
        }

        var future_dates = [];
        var past_dates = [];
        var selected_date = wpbs_get_selection_start_date($calendar_instance).getTime();

        // Loop through all visible dates and search for a limit
        $calendar_instance.find('.wpbs-date').not('.wpbs-is-bookable').not('.wpbs-gap').each(function () {
            date = wpbs_get_element_date($(this)).getTime();
            if (date > selected_date) {
                future_dates.push(date);
            } else {
                past_dates.push(date);
            }
        })

        //Sort and save nearest limit
        if (future_dates.length && $calendar_instance.data('future_date_limit') == 'infinite') {
            future_dates.sort();
            $calendar_instance.data('future_date_limit', future_dates[0]);
        }

        if (past_dates.length && $calendar_instance.data('past_date_limit') == 'infinite') {
            past_dates.sort().reverse();
            $calendar_instance.data('past_date_limit', past_dates[0]);
        }
    }

    /**
     * Transform a .wpbs-date elements data attributes into a Date() object
     * 
     * @param $el 
     */
    function wpbs_get_element_date($el) {
        date = new Date(Date.UTC($el.data('year'), $el.data('month') - 1, $el.data('day'), 0, 0, 0));
        return date;
    }

    /**
     * Calculate the price for the selected days
     * 
     */
    function wpbs_calculate_price($calendar_instance) {

        if (!$calendar_instance.find('.wpbs-form-field-total').length) {
            return;
        }

        if (!$calendar_instance.find('.wpbs-container').data('start_date') || !$calendar_instance.find('.wpbs-container').data('end_date')) {
            wpbs_clear_price($calendar_instance);
            return;
        }

        /**
         * Set ajaxurl in the front-end
         *
         */
        if (typeof wpbs_ajaxurl != 'undefined')
            ajaxurl = wpbs_ajaxurl;

        /**
         * Prepare the calendar data
         *
         */

        $calendar_instance.find('.wpbs-form-field-total').append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
        $calendar_instance.find('.wpbs-form-field-total').addClass('wpbs-is-loading')

        $form = $calendar_instance.find('form');
        $calendar = $calendar_instance.find('.wpbs-container');

        var data = {};

        data['action'] = 'wpbs_calculate_pricing';
        data['form'] = $form.data()
        data['calendar'] = $calendar.data();
        data['post_data'] = $form.serialize();

        /**
         * Make Ajax Request
         * 
         */
        $.post(ajaxurl, data, function (response) {
            $calendar_instance.find('.wpbs-form-field-total .wpbs-overlay').remove();
            $calendar_instance.find('.wpbs-form-field-total').removeClass('wpbs-is-loading');
            $calendar_instance.find('.wpbs-form-field-total .wpbs-total-price').html(response);

        })

    }

    /**
     * Clear the price calculation
     * 
     */
    function wpbs_clear_price($calendar_instance) {
        if (!$calendar_instance.find('.wpbs-form-field-total').length)
            return;

        $calendar_instance.find('.wpbs-form-field-total input').val('');

        $calendar_instance.find('.wpbs-form-field-total .wpbs-total-price').html($calendar_instance.find('.wpbs-total-price').data('string-select-dates'));

    }

    /**
     * Display the selected dates in the form
     * 
     */
    function wpbs_display_selected_dates($calendar_instance) {

        // Exit if option is not enabled.
        if (!$calendar_instance.find('.wpbs-form-selected-dates').length) {
            return false;
        }

        moment.locale($calendar_instance.find('.wpbs-container').data('language'));

        nice_start_date = nice_end_date = '-';

        // Set proper UTC dates
        if (wpbs_get_selection_start_date($calendar_instance)) {
            start_date = wpbs_get_selection_start_date($calendar_instance);
            nice_start_date = moment.utc(start_date).format(wpbs_ajax.time_format);
        }

        if (wpbs_get_selection_end_date($calendar_instance)) {
            end_date = wpbs_get_selection_end_date($calendar_instance);
            nice_end_date = moment.utc(end_date).format(wpbs_ajax.time_format);
        }

        $calendar_instance.find(".wpbs-form-selected-dates .wpbs-form-field-start-date .wpbs-form-field-input").html(nice_start_date)
        $calendar_instance.find(".wpbs-form-selected-dates .wpbs-form-field-end-date .wpbs-form-field-input").html(nice_end_date)
    }

    /**
     * Validates the date selection instantly, without requiring the form to be submitted
     * 
     */
    function wpbs_validate_date_selection($calendar_instance) {

        var $form = $calendar_instance.find('.wpbs-form-container');

        var $calendar = $calendar_instance.find('.wpbs-container');

        $form.find('.wpbs-form-general-error').remove();

        /**
         * Set ajaxurl in the front-end
         *
         */
        if (typeof wpbs_ajaxurl != 'undefined')
            ajaxurl = wpbs_ajaxurl;

        /**
         * Prepare the calendar data
         *
         */
        var data = {};

        data['action'] = 'wpbs_validate_date_selection';

        data['form'] = $form.data()
        data['calendar'] = $calendar.data();

        data['wpbs_token'] = wpbs_ajax.token;
        data['form_data'] = $form.serialize();

        $.post(ajaxurl, data, function (response) {
            response = JSON.parse(response);

            // If validation failed, we show the form again
            if (response.success === false) {
                $form.replaceWith(response.html);
                wpbs_render_recaptcha();
                wpbs_display_selected_dates($calendar_instance);

                // Revalidate coupon code
                if ($calendar_instance.find('.wpbs-coupon-code input').val()) {
                    $calendar_instance.find('.wpbs-coupon-code-add').trigger('click');
                }

            }

        })

    }

    /**
     * Check for touch device
     * 
     */
    function wpbs_is_touch_device() {
        var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
        var mq = function (query) {
            return window.matchMedia(query).matches;
        }

        if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
            return true;
        }

        var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
        return mq(query);
    }

    /**
     * Coupons
     * 
     */


    /**
     * Add Coupon Code
     * 
     */
    $("body").on('click', '.wpbs-coupon-code-add', function (e) {
        e.preventDefault();

        var $button = $(this);
        var $input = $button.siblings('input');
        var $calendar_instance = $button.parents('.wpbs-main-wrapper');


        $button.parents('.wpbs-form-field').find('.wpbs-form-field-error').remove();


        /**
         * Set ajaxurl in the front-end
         *
         */
        if (typeof wpbs_ajaxurl != 'undefined')
            ajaxurl = wpbs_ajaxurl;

        /**
         * Prepare the calendar data
         *
         */

        $form = $calendar_instance.find('form');
        $calendar = $calendar_instance.find('.wpbs-container');

        var data = {};

        data['action'] = 'wpbs_apply_coupon';
        data['form'] = $form.data();
        data['calendar'] = $calendar.data();
        data['coupon_code'] = $input.val();

        /**
         * Make Ajax Request
         * 
         */
        $.post(ajaxurl, data, function (response) {
            response = JSON.parse(response)
            if (response.success === true) {
                wpbs_calculate_price($calendar_instance);
                $input.prop('readonly', true);
                $button.html('<svg aria-hidden="true" focusable="false"role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z" class=""></path></svg>');
                $button.removeClass('wpbs-coupon-code-add').addClass('wpbs-coupon-code-remove');
            }

            if (response.success === false) {
                $button.parents('.wpbs-form-field').append('<div class="wpbs-form-field-error"><small>' + response.error + '</small></div>');
            }

        })

    });

    /**
     * Add Coupon Code
     * 
     */
    $("body").on('click', '.wpbs-coupon-code-remove', function (e) {
        e.preventDefault();

        var $button = $(this);
        var $input = $button.siblings('input');
        var $calendar_instance = $button.parents('.wpbs-main-wrapper');



        $input.val('').prop('readonly', false);
        $button.addClass('wpbs-coupon-code-add').removeClass('wpbs-coupon-code-remove');
        $button.text($button.data('label'));

        wpbs_calculate_price($calendar_instance);

    })

    /**
     * Final Payment Form
     * 
     */
    $(document).on('submit', '#wpbs-final-payment-form', function (e) {
        e.preventDefault();

        var data = {};

        data['action'] = 'wpbs_save_final_payment';
        data['post_data'] = $(this).serialize();

        /**
         * Set ajaxurl in the front-end
         *
         */
        if (typeof wpbs_ajaxurl != 'undefined')
            ajaxurl = wpbs_ajaxurl;


        /**
         * Make the request
         *
         */
        $.post(ajaxurl, data, function (response) {
            $(".wpbs-final-payment-confirmation").html(response);
        });
    })



    /**
     * Elementor element resize
     * 
     */
    if ($('body').hasClass('elementor-editor-active')) {

        /**
         * Runs every 250 milliseconds to check if a calendar was just loaded
         * and if it was, trigger the window resize to show it
         *
         */
        setInterval(function () {

            $('.wpbs-container-loaded').each(function () {

                if ($(this).attr('data-just-loaded') == '1') {
                    $(window).trigger('resize');
                    $(this).attr('data-just-loaded', '0');
                }

            });

        }, 250);

    }

    /**
     * Check if a calendar is hidden and wait for it to become visible. 
     * When it does, trigger a window resize to properly display the calendar.
     * 
     */

    var wpbs_frontend_visible_calendars = $('.wpbs-container:visible').length;
    function wpbs_check_if_calendar_is_visible() {

        // If no calendars are embedded, exit
        if (!$('.wpbs-container').length)
            return false;

        // Add .visible class
        $('.wpbs-container:visible').addClass('wpbs-visible');

        // If a calendar just became visible, trigger a resize
        if (wpbs_frontend_visible_calendars != $('.wpbs-container.wpbs-visible').length) {
            $(window).trigger('resize');
            wpbs_frontend_visible_calendars = $('.wpbs-container.wpbs-visible').length;
        }

        // Remove .visible class
        $('.wpbs-container:not(:visible)').removeClass('wpbs-visible');

        // If all calendars are visible, exit
        if ($('.wpbs-container.wpbs-visible').length == $('.wpbs-container').length) {
            return false;
        }

        // Keep checking every 250ms
        setTimeout(wpbs_check_if_calendar_is_visible, 250);

    }
    // Manually start the first check
    wpbs_check_if_calendar_is_visible();

    /**
     * Scroll the window to the calendar if it has the "wpbs-scroll-to-calendar" class
     */
    $(window).on('load', function () {
        if ($(".wpbs-scroll-to-calendar").length) {
            $('html, body').stop().animate({ scrollTop: $(".wpbs-scroll-to-calendar").offset().top })
        }

    });

});

function wpbs_lazy_load_script(src, callback) {
    var s = document.createElement("script");
    s.src = src;
    s.async = true;
    s.onreadystatechange = s.onload = function () {
        if (!callback.done && (!s.readyState || /loaded|complete/.test(s.readyState))) {
            callback.done = true;
            callback();
        }
    };
    document.querySelector("head").appendChild(s);
};
