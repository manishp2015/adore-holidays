jQuery(function ($) {
    var wpbs_s_display_date_format = wpbs_s_localized_data.date_format;
    var wpbs_s_date_format = 'yy-m-d';

    /**
     * The datepickers
     * 
     */
    wpbs_s_search_widget_initialize_datepickers();
    

    $(document).on('click', ".wpbs_s-search-widget-datepicker-submit", function (e) {
        e.preventDefault();

        var $button = $(this);
        var $container = $button.parents('.wpbs_s-search-widget');
        var $form = $container.find('.wpbs_s-search-widget-form');


        $form.addClass('wpbs_s-searching');
        $button.prop('disabled', true);

        $container.find(".wpbs_s-search-widget-results-wrap").empty();
        $container.find(".wpbs_s-search-widget-error-field").empty();

        var data = {
            action: 'wpbs_s_search_calendars',
            start_date: $form.find('.wpbs_s-search-widget-datepicker-standard-format-start-date').val(),
            end_date: $form.find('.wpbs_s-search-widget-datepicker-standard-format-end-date').val(),
            args: $container.data(),
            wpbs_s_token: wpbs_s_localized_data.search_form_nonce
        }

        $.post(wpbs_s_localized_data.ajax_url, data, function (response) {
            $form.removeClass('wpbs_s-searching');
            $button.prop('disabled', false);

            $container.replaceWith(response)
            wpbs_s_search_widget_initialize_datepickers();

            // Add padding to form
            wpbs_s_search_widget_add_padding();
            wpbs_s_search_results_widget_add_padding();
            wpbs_s_search_widget_size();
            $container.animate({ opacity: 1 }, 200);
        });
    });


    // Add padding to form
    wpbs_s_search_widget_add_padding();
    wpbs_s_search_results_widget_add_padding();
    wpbs_s_search_widget_size();

    $(window).on('load', function () {
        $(".wpbs_s-search-widget").animate({ opacity: 1 }, 200);
    });


    $(window).bind('load resize', function () {
        // Add padding to form
        wpbs_s_search_widget_add_padding();
        wpbs_s_search_results_widget_add_padding();
        wpbs_s_search_widget_size();
    });


    function wpbs_s_search_widget_add_padding() {
        $(".wpbs_s-search-widget .wpbs_s-search-widget-form").css('padding-right', $(".wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field.wpbs_s-search-widget-field-submit").width());
    };


    function wpbs_s_search_results_widget_add_padding() {
        $(".wpbs_s-search-widget-results-wrap .wpbs_s-search-widget-result").each(function () {
            $result = $(this);
            $result.css('padding-right', $result.find(".wpbs_s-search-widget-result-button").outerWidth(true) + 40);
        });
    };


    function wpbs_s_search_widget_size() {
        $(".wpbs_s-search-widget").each(function () {
            $widget = $(this);
            if ($widget.width() < 500) {
                $widget.addClass('small');
            } else {
                $widget.removeClass('small');
            };
        });
    };


    function wpbs_s_search_widget_initialize_datepickers() {

        if ($('body').hasClass('wp-admin')) return false;

        $('.wpbs_s-search-widget').each(function () {
            
            $instance = $(this);
    
            var start_date = $instance.find(".wpbs_s-search-widget-datepicker-start-date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: wpbs_s_display_date_format,
                minDate: 0,
                maxDate: wpbs_s_datepicker_get_date($instance.find(".wpbs_s-search-widget-datepicker-end-date")[0]),
                altFormat: wpbs_s_date_format,
                altField: $instance.find('.wpbs_s-search-widget-datepicker-standard-format-start-date'),
                showOtherMonths: true,
                beforeShow: function () {
                    $('#ui-datepicker-div').addClass('wpbs-datepicker');
                },
                onClose: function () {
                    $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                    $instance.find(".wpbs_s-search-widget-datepicker-end-date").focus();
                },
            }).on("change", function () {
                end_date.datepicker("option", "minDate", wpbs_s_datepicker_get_date(this));
                wpbs_datepicker_update_state('wpbs-search-start-date', $instance.find(".wpbs_s-search-widget-datepicker-standard-format-start-date").val())
            })

            var end_date = $instance.find(".wpbs_s-search-widget-datepicker-end-date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: wpbs_s_display_date_format,
                minDate: wpbs_s_datepicker_get_date($instance.find(".wpbs_s-search-widget-datepicker-start-date")[0]),
                altFormat: wpbs_s_date_format,
                altField: $instance.find('.wpbs_s-search-widget-datepicker-standard-format-end-date'),
                showOtherMonths: true,
                beforeShow: function () {
                    $('#ui-datepicker-div').addClass('wpbs-datepicker');
                },
                onClose: function () {
                    $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                },
            }).on("change", function () {
                start_date.datepicker("option", "maxDate", wpbs_s_datepicker_get_date(this));
                wpbs_datepicker_update_state('wpbs-search-end-date', $instance.find(".wpbs_s-search-widget-datepicker-standard-format-end-date").val())
            });


        });
    };

    /**
	 * Helper function to get the date of a datepicker element
	 * 
	 */
    function wpbs_s_datepicker_get_date(element) {
        var date;
        try {
            date = $.datepicker.parseDate(wpbs_s_display_date_format, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    };

    /**
	 * Helper function to change the url history state
	 * 
	 * @param key 
	 * @param value 
	 */
	function wpbs_datepicker_update_state(key, value) {
		var baseUrl = [location.protocol, '//', location.host, location.pathname].join(''),
			urlQueryString = document.location.search,
			newParam = key + '=' + value,
			params = '?' + newParam;

		// If the "search" string exists, then build params from it
		if (urlQueryString) {
			keyRegex = new RegExp('([\?&])' + key + '[^&]*');

			// If param exists already, update it
			if (urlQueryString.match(keyRegex) !== null) {
				params = urlQueryString.replace(keyRegex, "$1" + newParam);
			} else { // Otherwise, add it to end of query string
				params = urlQueryString + '&' + newParam;
			}
		}
		window.history.replaceState({}, "", baseUrl + params);
	};

});