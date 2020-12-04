import React from 'react';
import Select from 'react-select';

const { ServerSideRender, PanelBody, SelectControl }  = wp.components;

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { __ } 				= wp.i18n;


/**
 * Block inspector controls options
 *
 */

// The options for the Calendars dropdown
var calendars = [];

for( var i = 0; i < wpbs_calendars.length; i++ ) {

    calendars.push( { value : wpbs_calendars[i].id, label : wpbs_calendars[i].name } );

}

// The option for the Language dropdown
var languages = [];

languages[0] = { value : 'auto', label : __( 'Auto', 'wp-booking-system-search') };

for( var i = 0; i < wpbs_languages.length; i++ ) {

    languages.push( { value : wpbs_languages[i].code, label : wpbs_languages[i].name } );

}

// Register the block
registerBlockType( 'wp-booking-system/search-widget', {

	// The block's title
    title : 'Calendar Search Widget',

    // The block's icon
    icon : 'search',

    // The block category the block should be added to
    category : 'wp-booking-system',

    // The block's attributes, needed to save the data
	attributes : {

		calendars : {
            type : 'string',
            default : null
        },
        language : {
            type    : 'string',
            default : 'auto'
        },

		title : {
            type    : 'string',
            default : 'yes'
        },

        mark_selection : {
            type    : 'string',
            default : 'yes'
        }

	},

	edit : function( props ) {

		const selected = ( typeof props.attributes.calendars != 'undefined' ? JSON.parse( props.attributes.calendars ) : {} )
    	const handleSelectChange = ( calendars ) => props.setAttributes( { calendars: JSON.stringify( calendars ) } );

		return [

			<ServerSideRender 
				block 	   = "wp-booking-system/search-widget"
				attributes = { props.attributes } />,

			<InspectorControls key="inspector">

				<PanelBody
					title       = { __( 'Calendars', 'wp-booking-system-search') }
                    initialOpen = { true } >

                    <Select
                    	label    = { __( 'Calendars', 'wp-booking-system-search') }
                        name     = 'select-two'
                        value    = { selected }
                        onChange = { handleSelectChange }
                        options  = { calendars }
						isMulti  = 'true' />

					<p class="description">{ __( 'Select the calendars you wish to be included in the search, or leave empty to show all calendars.', 'wp-booking-system-search') }</p>

                    <SelectControl
						label   = { __( 'Language', 'wp-booking-system-search') }
                        value   = { props.attributes.language }
                        options = { languages }
                        onChange = { (new_value) => props.setAttributes( { language : new_value } ) } />
					
					<SelectControl
						label   = { __( 'Widget Title', 'wp-booking-system-search') }
                        value   = { props.attributes.title }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system-search') },
                            { value : 'no',  label : __( 'No', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { title : new_value } ) } />
					
					<SelectControl
						label   = { __( 'Automatically Mark Selection', 'wp-booking-system-search') }
                        value   = { props.attributes.mark_selection }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system-search') },
                            { value : 'no',  label : __( 'No', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { mark_selection : new_value } ) } />

				</PanelBody>

			</InspectorControls>
		];
	},

	save : function() {
		return null;
	}

});


jQuery(function ($) {

	/**
	 * Runs every 250 milliseconds to check if a calendar was just loaded
	 * and if it was, trigger the window resize to show it
	 *
	 */
	setInterval(function () {

        jQuery('.wpbs-search-container-loaded').each(function () {
            if (jQuery(this).attr('data-just-loaded') == '1') {
                jQuery(window).trigger('resize');
                jQuery(this).attr('data-just-loaded', '0');
            }
        });
		
	}, 250);
});