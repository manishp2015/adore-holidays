jQuery(function ($) {

	/*
     * Strips one query argument from a given URL string
     *
     */
    function remove_query_arg(key, sourceURL) {

        var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";

        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }

            rtn = rtn + "?" + params_arr.join("&");

        }

        if (rtn.split("?")[1] == "") {
            rtn = rtn.split("?")[0];
        }

        return rtn;
    }


    /*
     * Adds an argument name, value pair to a given URL string
     *
     */
    function add_query_arg(key, value, sourceURL) {

        return sourceURL + '&' + key + '=' + value;

    }


	/**
	 * Initialize colorpicker
	 *
	 */
    $('.wpbs-colorpicker').wpColorPicker();

	/**
	 * Initialize Chosen
	 *
	 */
    if (typeof $.fn.chosen != 'undefined') {

        $('.wpbs-chosen').chosen();

    }

	/**
	 * Links that have the inactive class should do nothing
	 *
	 */
    $(document).on('click', 'a.wpbs-inactive, input[type=submit].wpbs-inactive', function () {

        return false;

    });

    /**
     * Image Upload Scripts
     * 
     */
    $('.wpbs-media-upload-button').click(function (e) {
        e.preventDefault();
        var $button = $(this);
        var image = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function (e) {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $button.siblings('.wpbs-media-upload-preview').attr('src', image_url);
                $button.siblings('.wpbs-media-upload-url').val(image_url);
                $('.wpbs-media-upload-remove').removeClass('wpbs-hide');
            });
    });

    $('.wpbs-media-upload-remove').click(function () {
        $(this).siblings('.wpbs-media-upload-preview').attr('src', '');
        $(this).siblings('.wpbs-media-upload-url').val('');
        $('.wpbs-media-upload-remove').addClass('wpbs-hide');
    })

	/**
	 * Initialize the sortable function on the Calendar Legend List Table
	 *
	 */
    $('table.wpbs_legend_items tbody').sortable({
        handle: '.wpbs-move-legend-item',
        containment: '#wpcontent',
        placeholder: 'wpbs-list-table-sort-placeholder',
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();

            $helper.children().each(function (index) {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).width());
            });

            return $helper;
        },
        update: function (e, ui) {

            var legend_item_ids = [];

            $('table.wpbs_legend_items tbody tr .wpbs-move-legend-item').each(function () {
                legend_item_ids.push($(this).data('id'));
            })

            var data = {
                action: 'wpbs_sort_legend_items',
                token: $('.tablenav.bottom [name="wpbs_token"]').val(),
                calendar_id: $('[name="calendar_id"]').val(),
                legend_item_ids: legend_item_ids
            }

            // Add table wrapper and overlay
            $('table.wpbs_legend_items').wrap('<div class="wpbs-wp-list-table-wrapper"></div>');
            $('table.wpbs_legend_items').closest('.wpbs-wp-list-table-wrapper').append('<div class="wpbs-overlay"><div class="spinner"></div></div>');

            // Make sort ajax call
            $.post(ajaxurl, data, function (response) {

                response = JSON.parse(response);

                if (!response.success) {

                    window.location.replace(response.redirect_url_error);

                } else {

                    // Remove table wrapper and overlay
                    $('table.wpbs_legend_items').siblings('.wpbs-overlay').remove();
                    $('table.wpbs_legend_items').unwrap('.wpbs-wp-list-table-wrapper');

                }


            });

        }
    });

    $('table.wpbs_legend_items tbody').disableSelection();


	/**
	 * Handle show/hide of the second color option for Legend Item add/edit screen
	 *
	 */
    $(document).on('change', 'select[name="legend_item_type"]', function () {

        if ($(this).val() == 'single')
            $('#wpbs-legend-item-color-2').closest('.wp-picker-container').hide();
        else
            $('#wpbs-legend-item-color-2').closest('.wp-picker-container').show();

    });

    $(document).ready(function () {

        if ($('select[name="legend_item_type"]').length > 0)
            $('select[name="legend_item_type"]').trigger('change');


        if ($("body.wp-booking-system_page_wpbs-calendars .wp-list-table.wpbs_legend_items.wpbs_legend_items").length > 0 && $(window).width() < 1100) {
            $("body.wp-booking-system_page_wpbs-calendars .wp-list-table.wpbs_legend_items.wpbs_legend_items #the-list tr").each(function () {
                $tr = $(this);
                $tr.find('td.name.column-name a.row-title').clone().addClass('remove-link').insertAfter($tr.find('td.sort.column-sort .wpbs-move-legend-item.ui-sortable-handle'));
                $tr.find('td .remove-link').removeAttr('href')
            });
        };

    });

	/**
	 * Tab Navigation
	 *
	 */
    $(document).on('click', '.wpbs-nav-tab', function (e) {
        e.preventDefault();

        // Nav Tab activation
        $('.wpbs-nav-tab').removeClass('wpbs-active').removeClass('nav-tab-active');
        $(this).addClass('wpbs-active').addClass('nav-tab-active');

        // Show tab
        $('.wpbs-tab').removeClass('wpbs-active');

        var nav_tab = $(this).attr('data-tab');
        $('.wpbs-tab[data-tab="' + nav_tab + '"]').addClass('wpbs-active');
        $('input[name=active_tab]').val(nav_tab);

        // Change http referrer
        $_wp_http_referer = $('input[name=_wp_http_referer]');

        var _wp_http_referer = $_wp_http_referer.val();

        if (_wp_http_referer) {
            _wp_http_referer = remove_query_arg('tab', _wp_http_referer);
            _wp_http_referer = remove_query_arg('section', _wp_http_referer);
            $_wp_http_referer.val(add_query_arg('tab', $(this).attr('data-tab'), _wp_http_referer));
        }


    });


	/**
	 * Link Calendar
	 *
	 */
    $('#wpbs-settings-field-wrapper-calendar-link-' + $('[name="calendar_link_type"]').val()).show();

    $(document).on('change', '[name="calendar_link_type"]', function () {

        $('#wpbs-settings-field-wrapper-calendar-link-internal').hide();
        $('#wpbs-settings-field-wrapper-calendar-link-external').hide();

        $('#wpbs-settings-field-wrapper-calendar-link-' + $(this).val()).show();

    });

    /**
	 * Calendar Title Translations Toggle
	 */
    $(".wrap.wpbs-wrap #titlediv .titlewrap-toggle").click(function (e) {
        e.preventDefault();
        $(this).toggleClass('open');
        $(".titlewrap-translations").slideToggle();

    });

    /**
     * Toggle settings translations
     * 
     */
    $(document).on('click', '.wpbs-settings-field-show-translations', function (e) {
        e.preventDefault();
        $(this).parents('.wpbs-settings-field-translation-wrapper').find(".wpbs-settings-field-translations").slideToggle();
        $(this).toggleClass('open');
    })


	/**
	 * Modifies the modal inner height to permit the scrollbar to function properly
	 *
	 */
    $(window).resize(function () {

        $('.wpbs-modal-inner').outerHeight($('.wpbs-modal.wpbs-active').outerHeight() - $('.wpbs-modal.wpbs-active .wpbs-modal-header').outerHeight() - $('.wpbs-modal.wpbs-active .wpbs-modal-nav-tab-wrapper').outerHeight());

    });

	/**
	 * Close modal window
	 *
	 */
    $(document).on('click', '.wpbs-modal-close', function (e) {

        e.preventDefault();

        $(this).closest('.wpbs-modal').find('.wpbs-modal-inner').scrollTop(0);

        $(this).closest('.wpbs-modal').removeClass('wpbs-active');
        $(this).closest('.wpbs-modal').siblings('.wpbs-modal-overlay').removeClass('wpbs-active');

        $(window).resize();

    });

	/**
	 * Close modal on clicking the modal overlay
	 *
	 */
    $(document).on('click', '.wpbs-modal-overlay.wpbs-active', function (e) {

        $('.wpbs-modal.wpbs-active').find('.wpbs-modal-close').click();

    });

	/**
	 * Open Shortcode Generator modal
	 *
	 */
    $(document).on('click', '#wpbs-shortcode-generator-button', function (e) {

        e.preventDefault();

        $('#wpbs-modal-add-calendar-shortcode, #wpbs-modal-add-calendar-shortcode-overlay').addClass('wpbs-active');

        $(window).resize();

        $('.wpbs-modal.wpbs-active').click();

    });

	/**
	 * Builds the shortcode for the Single Calendar and inserts it in the WordPress text editor
	 *
	 */
    $(document).on('click', '#wpbs-insert-shortcode-single-calendar', function (e) {

        e.preventDefault();

        // Begin shortcode
        var shortcode = '[wpbs ';

        $('#wpbs-modal-add-calendar-shortcode.wpbs-active .wpbs-shortcode-generator-field-calendar').each(function () {

            shortcode += $(this).data('attribute') + '="' + $(this).val() + '" ';

        });

        // End shortcode
        shortcode = shortcode.trim();
        shortcode += ']';

        window.send_to_editor(shortcode);

        $(this).closest('.wpbs-modal').find('.wpbs-modal-close').first().trigger('click');

    });


	/**
	 * Make the Selected Calendars available for selection if "Display Calendars" value is set
	 * to "Selected Calendars"
	 *
	 */
    $(document).on('change', '#modal-add-calendar-overview-shortcode-calendars', function () {

        if ($(this).val() == '2')
            $('#modal-add-calendar-overview-shortcode-selected-calendars').parent().removeClass('wpbs-element-disabled');
        else
            $('#modal-add-calendar-overview-shortcode-selected-calendars').parent().addClass('wpbs-element-disabled');

    });


	/**
	 * Builds the shortcode for the Overview Calendar and inserts it in the WordPress text editor
	 *
	 */
    $(document).on('click', '#wpbs-insert-shortcode-overview-calendar', function (e) {

        e.preventDefault();

        // Begin shortcode
        var shortcode = '[wpbs-overview ';

        // Add the calendars shortcode attribute
        var calendars = $('#modal-add-calendar-overview-shortcode-calendars').val();

        if (calendars == '1')
            shortcode += 'calendars="all" ';

        // For selected calendars we want to maintain the order selected by the user, so grabbing
        // the value from the multiple select won't be enough, as this does not maintain the order
        else {

            var $select = $('#modal-add-calendar-overview-shortcode-selected-calendars');
            var select_val = '';

            $select.siblings('.chosen-container').find('li.search-choice a').each(function () {

                select_val += $select.find('option').eq($(this).data('option-array-index')).val() + ','

            });

            // Trim the last comma
            select_val = select_val.slice(0, -1);

            shortcode += 'calendars="' + select_val + '" ';

        }

        // Add the rest of the attributes
        $('#wpbs-modal-add-calendar-shortcode.wpbs-active .wpbs-shortcode-generator-field-calendar-overview').each(function () {

            shortcode += $(this).data('attribute') + '="' + $(this).val() + '" ';

        });

        // End shortcode
        shortcode = shortcode.trim();
        shortcode += ']';

        window.send_to_editor(shortcode);

        $(this).closest('.wpbs-modal').find('.wpbs-modal-close').first().trigger('click');

    });

	/**
	 * Register and deregister website functionality
	 *
	 */
    $(document).on('click', '#wpbs-register-website-button, #wpbs-deregister-website-button', function (e) {

        e.preventDefault();

        window.location = add_query_arg('serial_key', $('[name="serial_key"]').val(), $(this).attr('href'));

    });

    $(document).on('click', '#wpbs-check-for-updates-button', function (e) {

        if ($(this).attr('disabled') == 'disabled')
            e.preventDefault();

    });


	/**
	 * iCal Import forms
	 *
	 */
    $(document).on('change', '#wpbs-ical-file-import input, #wpbs-ical-file-import select', function () {

        $('#wpbs-ical-file-import input, #wpbs-ical-file-import select').each(function () {

            if ($(this).val() == '') {

                $('#wpbs-ical-file-import input[type=submit]').attr('disabled', true);
                return false;

            } else
                $('#wpbs-ical-file-import input[type=submit]').attr('disabled', false);

        });

    });

    $(document).on('change', '#ical_url_import_split_days', function () {

        if ($(this).prop('checked')) {
            $('#wpbs-ical-url-import .wpbs-settings-field-conditional').show();
        } else {
            $('#wpbs-ical-url-import .wpbs-settings-field-conditional').hide();
        }

    });

    $(document).on('change keyup', '#wpbs-ical-url-import input, #wpbs-ical-url-import select, #ical_url_import_split_days', function () {

        $('#wpbs-ical-url-import input, #wpbs-ical-url-import select:visible').each(function () {

            if ($(this).val() == '') {

                $('#wpbs-ical-url-import input[type=submit]').attr('disabled', true);
                return false;

            } else
                $('#wpbs-ical-url-import input[type=submit]').attr('disabled', false);

        });

    });

    /**
     * Toggle wrapper fields
     */
    $(document).on('change', '.wpbs-settings-wrap-toggle', function () {
        $($(this).data('target')).toggleClass('wpbs-settings-wrapper-show');
    })

    /**
     * Pricing page tabs
     * 
     */
    $(document).on('click', '.wpbs-payment-tab-navigation a', function (e) {
        e.preventDefault();
        $('.wpbs-payment-tab-navigation a').removeClass('current');
        $(this).addClass('current');

        $('.wpbs-payment-tabs .wpbs-tab').removeClass('wpbs-section-active');
        $('.wpbs-payment-tabs .wpbs-tab[data-tab="' + $(this).data('tab') + '"]').addClass('wpbs-section-active');

        // Change http referrer
        $_wp_http_referer = $('input[name=_wp_http_referer]');

        var _wp_http_referer = $_wp_http_referer.val();

        if (_wp_http_referer) {
            _wp_http_referer = remove_query_arg('section', _wp_http_referer);
            $_wp_http_referer.val(add_query_arg('section', $(this).attr('data-tab'), _wp_http_referer));
        }
    })

	/**
	 * iCal Settings page fields
	 *
	 */
    $(document).on('change', '#wpbs-settings-field-ical-refresh-times-wrapper select', function () {

        if ($(this).val() == 'custom')
            $('#wpbs-settings-field-ical-custom-refresh-time-wrapper').show();
        else
            $('#wpbs-settings-field-ical-custom-refresh-time-wrapper').hide();

    });

    $('#wpbs-settings-field-ical-refresh-times-wrapper select').trigger('change');


	/**
	 * Move the calendar from the sidebar to the main content and back in the calendar edit screen 
	 * when resizing the window
	 *
	 */
    $(window).on('resize', function () {

        // Move the calendar from the sidebar to the main content
        if ($(window).innerWidth() < 850) {

            $('.wpbs-container').closest('.postbox').detach().prependTo('#post-body-content');

        } else {

            $('.wpbs-container').closest('.postbox').detach().prependTo('#postbox-container-1');

        }

    });

    $(window).trigger('resize');


    /**
     * Dnyamically calculate calendar editor Bookings column width.
     * 
     */
    $(document).ready(function () {
        wpbs_calendar_editor_dynamic_layout();
    })

    $(window).on('resize', wpbs_calendar_editor_dynamic_layout);

    /**
     * reCaptcha toggle boxes
     * 
     */
    $("#recaptcha_type").change(function () {
        $(".wpbs-settings-field-wrapper-recapthca").addClass('wpbs-hide');
        $(".wpbs-settings-field-wrapper-recapthca-" + $(this).val()).removeClass('wpbs-hide');
    }).trigger('change');

    /**
     * Submit the form when changing the ledeng's auto pending <select>
     */

    $(".wpbs-auto-accept-booking-as").change(function () {
        $(this).parents('form').submit();
    })

    /**
     * Form subtabs navigation
     * 
     */
    $(document).on('click', '.wpbs-form-tab-navigation a', function (e) {
        e.preventDefault();
        var $parent = $(this).parents('.wpbs-tab');

        $parent.find('.wpbs-form-tab-navigation a').removeClass('current');
        $(this).addClass('current');

        $parent.find('.wpbs-form-section').removeClass('wpbs-section-active');
        $parent.find('.wpbs-form-section[data-tab="' + $(this).data('tab') + '"]').addClass('wpbs-section-active');

        // Change http referrer
        $_wp_http_referer = $('input[name=_wp_http_referer]');

        var _wp_http_referer = $_wp_http_referer.val();

        if (_wp_http_referer) {
            _wp_http_referer = remove_query_arg('section', _wp_http_referer);
            $_wp_http_referer.val(add_query_arg('section', $(this).attr('data-tab'), _wp_http_referer));
        }
    })

    /**
     * Addons
     */
    $(".wpbs-addon-button-install").click(function () {
        $(this).addClass('button-disabled');
        $(this).text('Installing...')
    });

    $(".wpbs-addon-inner-text-wrap").wpbs_adjust_height();

    $(window).on('resize', function () {
        $(".wpbs-addon-inner-text-wrap").wpbs_adjust_height();
    });

    $(document).ready(function () {
        // Hide booking IDs on mobile if empty
        $(".wpbs-calendar-date-booking-id").each(function () {
            $this = $(this);
            if ($this.text() == '\xa0') {
                $this.parent().addClass('hide');
            };
        })
    });

    /**
     * Add Tax row in Settings
     * 
     */
    $(document).on('click', ".wpbs-settings-tax-add", function (e) {
        e.preventDefault();
        $tax_row = $(".wpbs-tax-fields .wpbs-tax-field").first().clone();
        $tax_row.find('input').val('');
        $tax_row.find('select').prop('selectedIndex', 0);
        $tax_row.find('select[multiple] option').removeProp('selected');
        $tax_row.find('.wpbs-tax-type').addClass('wpbs-hide')
        $tax_row.find('.wpbs-tax-type-percentage').removeClass('wpbs-hide')
        $tax_row.find(".wpbs-tax-datepicker").removeClass('hasDatepicker').removeAttr('id');
        $tax_row.find(".wpbs-tax-datepicker-wrap").each(function () {
            var $instance = $(this);
            $instance.find(".wpbs-tax-datepicker").datepicker({
                dateFormat: 'dd MM',
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                selectOtherMonths: true,
                altFormat: 'd/m/yy',
                altField: $instance.find('.wpbs-tax-datepicker-timestamp'),
                beforeShow: function () {
                    $('#ui-datepicker-div').addClass('wpbs-datepicker');
                },
                onClose: function () {
                    $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                }
            });
        });

        if (typeof $.fn.chosen != 'undefined') {
            $tax_row.find('.wpbs-settings-tax-calendars .chosen-container').remove();
            $tax_row.find('.wpbs-settings-tax-calendars .wpbs-chosen').chosen();
        }
        $tax_row.appendTo($(".wpbs-tax-fields"));
        wpbs_tax_fields_reset_names()
    });

    /**
     * Remove Tax row in Settings
     * 
     */
    $(document).on('click', ".wpbs-settings-tax-remove", function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to delete this row?"))
            return false;

        $(this).parents('.wpbs-tax-field').remove();

        wpbs_tax_fields_reset_names()

    });

    /**
     * Assign a number to each tax field.
     * 
     */
    function wpbs_tax_fields_reset_names() {
        $(".wpbs-tax-field").each(function (i) {
            var $field = $(this);
            $field.find('[data-name]').each(function () {
                $input = $(this);
                var name = $input.data('name').replace('id', i);
                $input.attr('name', name);
            });
        })
    }

    /**
     * Tax Datepickers
     * 
     */
    $(".wpbs-tax-datepicker-wrap").each(function () {
        $instance = $(this);

        $instance.find(".wpbs-tax-datepicker").datepicker({
            dateFormat: 'dd MM',
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            altFormat: 'd/m/yy',
            altField: $instance.find('.wpbs-tax-datepicker-timestamp'),
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('wpbs-datepicker');
            },
            onClose: function () {
                $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
            }
        })
    });

    /**
     * Tax Type Toggle
     * 
     */
    $(".wpbs-tax-fields").on("change", ".wpbs-payment_tax_type", function () {
        $parent = $(this).parents('.wpbs-tax-field');
        $parent.find(".wpbs-tax-type").addClass('wpbs-hide');
        $parent.find(".wpbs-tax-type-" + $(this).val()).removeClass('wpbs-hide');
    }).trigger('change');

    /**
     * Add Email Template row in Settings
     * 
     */
    $(document).on('click', ".wpbs-settings-email-template-add", function (e) {
        e.preventDefault();
        $('<input type="hidden" name="wpbs_settings[form_email_template_name][]" />').insertAfter($(this));
        $(this).parents('form').submit();

    });

    /**
     * Remove Email Template row in Settings
     * 
     */
    $(document).on('click', ".wpbs-settings-email-template-remove", function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to delete this row?"))
            return false;

        $(this).parents('.wpbs-email-template-field').remove();
    });

    /**
     * Toggle button styling fields
     * 
     */
    $("#form_styling").change(function () {
        if ($(this).val() == 'theme') {
            $(".wpbs-settings-fields-form-styling").hide();
        } else {
            $(".wpbs-settings-fields-form-styling").show();
        }
    }).trigger('change');

    /**
     * iCalendar Export warning message
     * 
     */
    $("#ical-export-legend-items").change(function () {
        if ($(this).find('option:selected').length > 1) {
            $(this).siblings('.wpbs-warning').show();
        } else {
            $(this).siblings('.wpbs-warning').hide();
        }
    }).trigger('change');

    /**
     * Final Payment Options fields
     * 
     */
    $("#payment_part_payments_method").change(function () {
        if ($(this).val() == 'initial') {
            $("#wpbs-final-payment-options").show();
        } else {
            $("#wpbs-final-payment-options").hide();
        }
    }).trigger('change');

    /**
     * Change the coupon value icon
     */
    $("#payment_part_payments_amount_type").change(function () {
        wpbs_change_deposit_value_icon();
    })
    wpbs_change_deposit_value_icon();

    function wpbs_change_deposit_value_icon() {
        $(".wpbs-deposit-value-field-inner .deposit-type").hide();
        $(".wpbs-deposit-value-field-inner .deposit-type-" + $("#payment_part_payments_amount_type").val()).show();
    }

    /**
     * Select all text when clicking on it
     */
    $(".wpbs-wrap").on('click', '.wpbs-select-on-click, .wpbs-email-tag div', function (e) {
        node = $(this)[0];
        if (document.body.createTextRange) {
            const range = document.body.createTextRange();
            range.moveToElementText(node);
            range.select();
        } else if (window.getSelection) {
            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(node);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    });

    /**
     * Calendar Notes - Add Note
     * 
     */
    $("#wpbs-calendar-add-note").click(function (e) {
        e.preventDefault();
        $button = $(this);
        $note = $button.siblings('#wpbs-calendar-note-content')

        $button.prop('disabled', true);
        $note.prop('disabled', true);

        // Prepare the data
        var data = {
            action: 'wpbs_calendar_add_note',
            calendar_id: $('input[name="calendar_id"]').val(),
            note: $note.val(),
        }

        // Send the request
        $.post(ajaxurl, data, function (response) {

            $button.prop('disabled', false);
            $note.prop('disabled', false).val('');

            if (response != '0') {
                $(".wpbs-calendar-notes").prepend(response);
            }
        });
    })

    /**
     * Calendar Notes - Remove Note
     * 
     */
    $(document).on('click', '.wpbs-calendar-note-remove', function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to delete this note?"))
            return false;

        var $button = $(this);
        var $wrap = $button.parents('.wpbs-calendar-note');

        $wrap.css('opacity', 0.4);

        // Prepare the data
        var data = {
            action: 'wpbs_calendar_remove_note',
            calendar_id: $('input[name="calendar_id"]').val(),
            note_id: $button.data('note-id'),
        }

        // Send the request
        $.post(ajaxurl, data, function () {
            $wrap.remove();
        });

    });


});

function wpbs_calendar_editor_dynamic_layout() {

    jQuery(".wpbs-wrap-edit-calendar .wpbs-calendar-date-booking-ids, .wpbs-wrap-edit-calendar .wpbs-calendar-date-booking-ids-header").css('min-width', 0);

    wpbs_bookings_max_width = 0;
    jQuery(".wpbs-wrap-edit-calendar .wpbs-calendar-date-booking-ids").each(function () {
        if (jQuery(this).width() > wpbs_bookings_max_width) {
            wpbs_bookings_max_width = jQuery(this).width();
        }
    })

    jQuery(".wpbs-wrap-edit-calendar .wpbs-calendar-date-booking-ids, .wpbs-wrap-edit-calendar .wpbs-calendar-date-booking-ids-header").css('min-width', wpbs_bookings_max_width + 1);

    var pricing_width = (jQuery(".wpbs-calendar-date-price-header").length) ? 87 : 0;
    var inventory_width = (jQuery(".wpbs-calendar-date-inventory-header").length) ? 87 : 0;

    jQuery(".wpbs-wrap-edit-calendar .wpbs-calendar-date-description, .wpbs-wrap-edit-calendar .wpbs-calendar-date-tooltip, .wpbs-wrap-edit-calendar .wpbs-calendar-date-description-header, .wpbs-wrap-edit-calendar .wpbs-calendar-date-tooltip-header").css('width', (jQuery(".wpbs-calendar-editor").width() - (187 + wpbs_bookings_max_width + 7 + 7 + 1 + pricing_width + inventory_width)) / 2);

    jQuery(".wpbs-wrap-edit-calendar .wpbs-calendar-date-description-ical").css('width', (jQuery(".wpbs-calendar-editor").width() - (187 + wpbs_bookings_max_width + 7 + 1))).css('margin-left', wpbs_bookings_max_width + 7);

    jQuery(".wpbs-calendar-editor").css({ opacity: 1 });
};

function wpbs_nl2br(str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}


/***** Adjust Height Function *****/
jQuery.fn.wpbs_adjust_height = function () {
    var maxHeightFound = 0;
    this.css('min-height', '1px');

    if (this.is('a')) {
        this.removeClass('loaded');
    };

    this.each(function () {
        if ($(this).height() > maxHeightFound) {
            maxHeightFound = $(this).height();
        }
    });
    this.css('min-height', maxHeightFound);
    if (this.is('a')) {
        this.addClass('loaded');
    };
};