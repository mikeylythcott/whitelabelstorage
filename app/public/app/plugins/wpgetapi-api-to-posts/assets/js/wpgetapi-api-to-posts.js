/**
 * Conditional logic for CMB2 library
 * @author    Awran5 <github.com/awran5>
 * @version   1.1.0
 * @license   under GPL v2.0 (https://github.com/awran5/CMB2-conditional-logic/blob/master/LICENSE)
 * @copyright © 2018 Awran5. All rights reserved.
 * 
 */

window.WpGetApiImporter = window.WpGetApiImporter || {};

(function (window, document, $, wpgetapiimporter, undefined) {

  	'use strict'
  	var $document;

  	/**
     * Start the JS.
     */
    wpgetapiimporter.onReady = function () {
    	$document = $( document );

        var $page = wpgetapiimporter.page();

        wpgetapiimporter.tabs();
        wpgetapiimporter.conditionals();
        wpgetapiimporter.mapping();
        //wpgetapiimporter.hideSaveButton();

        $document
            .on( 'cmb2_remove_row cmb2_add_row cmb2_shift_row cmb2_shift_rows_start', wpgetapiimporter.conditionals )
            .on( 'change', '.wpgetapi .cmb-type-mapping select', wpgetapiimporter.mapping );

    }


    wpgetapiimporter.hideSaveButton = function () {

    	// hide the save button on dashboard
        $( '.toplevel_page_wpgetapi_importer_dashboard input[name=submit-cmb]' ).hide();

    }

    wpgetapiimporter.mapping = function ( e ) {

    	var $parent, $field;

    	// change
        if( e && e.type.length > 0 ) {

            $parent = $(this).closest( '.cmb-td' );
            $field = $(this);

            wpgetapiimporter.mapField( $parent, $field );

        } else {

    		$( '.wpgetapi .cmb-type-mapping' ).each( ( i, el ) => {
    			
		        $parent = $( el );
		        $field = $parent.find( 'select' );

		        wpgetapiimporter.mapField( $parent, $field );

	    	});

	    }

    };


    wpgetapiimporter.mapField = function ( $parent, $field ) {

    	if( $field.val() === '_meta_array' ) {
    		$parent.find( '.step_down.input-wrap input').val('');
    		$parent.find( '.step_down.input-wrap').hide();
    	} else {
    		$parent.find( '.step_down.input-wrap').show();
    	}

    	if( $field.val() === '_meta' && $field.val() !== '_meta_array' ) { 
		            
            $parent.find( '.value.input-wrap').show();

            if( ! $parent.find( '.value.input-wrap input').val() )
            	$parent.find( '.value.input-wrap input').val( $parent.find( '.cmb-th label').text() );
            
            $parent.find( '.prefix.input-wrap').hide();
            $parent.find( '.att_name.input-wrap').hide();

        } else if( $field.val() === '_attribute' ) { 
		            
            $parent.find( '.att_name.input-wrap').show();

            if( ! $parent.find( '.att_name.input-wrap input').val() )
            	$parent.find( '.att_name.input-wrap input').val( $parent.find( '.cmb-th label').text() );
            
            $parent.find( '.prefix.input-wrap').hide();
            $parent.find( '.value.input-wrap').hide();

        } else if( $field.val() === '_featured_image' || $field.val() === '_all_images' || $field.val() === '_gallery_images' ) { 

            $parent.find( '.prefix.input-wrap').show();

            $parent.find( '.value.input-wrap').hide();
            $parent.find( '.att_name.input-wrap').hide();
		
		} else {

            $parent.find( '.value.input-wrap').hide();
            $parent.find( '.prefix.input-wrap').hide();
            $parent.find( '.att_name.input-wrap').hide();

        }

    }

  	wpgetapiimporter.tabs = function () {

  		// Initial check
	    if( $('.cmb-tabs').length ) {
	        $('.cmb-tabs').each(function() {
	            // Activate first tab
	            if( ! $(this).find('.cmb-tab.active').length ) {
	                $(this).find('.cmb-tab').first().addClass('active');

	                $($(this).find('.cmb-tab').first().data('fields')).addClass('cmb-tab-active-item');
	                
	                // Support for groups and repeatable fields
	                $($(this).find('.cmb-tab').first().data('fields')).find('.cmb-repeat .cmb-row, .cmb-repeatable-group .cmb-row').addClass('cmb-tab-active-item');
	            }
	        });
	    }

	    $('body').on('click.cmbTabs', '.cmb-tabs .cmb-tab', function(e) {
	        var tab = $(this);

	        if( ! tab.hasClass('active') ) {
	            var tabs = tab.closest('.cmb-tabs');
	            var form = tabs.next('.cmb2-wrap');

	            // Hide current active tab fields
	            form.find(tabs.find('.cmb-tab.active').data('fields')).fadeOut(10, function() {
	                
	                $(this).removeClass('cmb-tab-active-item');

	                form.find(tab.data('fields')).fadeIn(10, function() {

	                    $(this).addClass('cmb-tab-active-item');

	                    // Support for groups and repeatable fields
	                    $(this).find('.cmb-repeat-table .cmb-row, .cmb-repeatable-group .cmb-row').addClass('cmb-tab-active-item');
	                });

	                
	            });

	            // Update tab active class
	            tabs.find('.cmb-tab.active').removeClass('active');
	            tab.addClass('active');

	            // hide the save button on importer and creator
	            if ( $( $(tab)[0] ).is( '[id^="tab-3"], [id*="tab-3"], [id^="tab-5"], [id*="tab-5"]' ) ) {
		        	$( '.cmb-form input[name=submit-cmb]' ).hide();
		        } else {
		        	$( '.cmb-form input[name=submit-cmb]' ).show();
		        }

		        //wpgetapiimporter.mapping();
	            //wpgetapiimporter.trigger( 'tab_changed', tabs )
	        }

	       
	    });
	   
	    // Adding a new group element needs to get the active class also
	    $('body').on('click', '.cmb-add-group-row', function() {
	        $(this).closest('.cmb-repeatable-group').find('.cmb-row').addClass('cmb-tab-active-item');
	    });

	    // Adding a new repeatable element needs to get the active class also
	    $('body').on('click', '.cmb-add-row-button', function() {
	        $(this).closest('.cmb-repeat').find('.cmb-row').addClass('cmb-tab-active-item');
	    });

	    // Initialize on widgets area
	    $(document).on('widget-updated widget-added', function(e, widget) {

	        if( widget.find('.cmb-tabs').length ) {

	            widget.find('.cmb-tabs').each(function() {
	                // Activate first tab
	                if( ! $(this).find('.cmb-tab.active').length ) {
	                    $(this).find('.cmb-tab').first().addClass('active');

	                    $($(this).find('.cmb-tab').first().data('fields')).addClass('cmb-tab-active-item');

	                    // Support for groups and repeatable fields
	                    $($(this).find('.cmb-tab').first().data('fields')).find('.cmb-repeat .cmb-row, .cmb-repeatable-group .cmb-row').addClass('cmb-tab-active-item');
	                }
	            });

	        }

	    });
  	}

  	wpgetapiimporter.conditionals = function () {

		$('[data-conditional-id]').each((i, el) => {
		  	
		  	let condName = el.dataset.conditionalId,
				condValue = el.dataset.conditionalValue,
				inverted = (el.dataset.conditionalInvert) ? true : false,
				condParent = el.closest('.cmb-row'),
				inGroup = condParent.classList.contains('cmb-repeat-group-field');
				
		  	let initAction = (inverted === true) ? 'show' : 'hide';

		  	// Check if the field is in group
		  	if (inGroup) {
				let groupID = condParent.closest('.cmb-repeatable-group').getAttribute('data-groupid'),
			  	iterator = condParent.closest('.cmb-repeatable-grouping').getAttribute('data-iterator');
					// change the select name with group ID added
					condName = `${groupID}[${iterator}][${condName}]`;
		  	}

		  	// Check if value is matching
		  	function valueMatch(value) {

				let checkCondition = condValue.includes(value) && value !== '';

				// Invert if needed
				if (inverted === true)
				  	checkCondition = !checkCondition;

				return checkCondition;

		  	}

		  	function conditionalField(field, action) {

				if ((action == 'hide' && inverted === false) || (action != 'hide' && inverted !== false)) {
				  	field.addClass('hidden');
				} else {
				  	field.removeClass('hidden');
				}

		  	}

		  	function checkboxInit(field) {

				if ((!field.checked && inverted === false) || (field.checked && inverted !== false)) {
				  	return false;
				} else {
				  	return true;
				}

		  	}

		  	// Select the field by name and loop through
		  	$('[name="' + condName + '"]').each(function (i, field) {

				// Select field
				if ("select-one" === field.type) {

				  	if ( ! valueMatch(field.value)) 
						conditionalField($(condParent), initAction);

					// Check on change
					$(field).on('change', function (event) {
						( ! valueMatch(event.target.value)) ? conditionalField($(condParent), 'hide') : conditionalField($(condParent), 'show');
					});

				}

				// Checkbox field
				else if ("checkbox" === field.type) {
						
					// Hide the row if the value doesn't match and not checked
					if (!checkboxInit(field))
						conditionalField($(condParent), initAction);

					// Check on change
					$(field).on('change', function (event) {
						(event.target.checked) ? conditionalField($(condParent), 'hide') : conditionalField($(condParent), 'show');
					});

				}

				// Radio field
				else if ("radio" === field.type) {

				  	// Hide the row if the value doesn't match and not checked
				  	if (!valueMatch(field.value) && checkboxInit(field))
						conditionalField($(condParent), initAction);

				  	// Check on change
				  	$(field).on('change', function (event) {
						(valueMatch(event.target.value)) ? conditionalField($(condParent), 'show') : conditionalField($(condParent), 'hide');
				  	});

				}

		  	});

		});

  	}

  	/**
	 * Triggers a jQuery event on the document object.
	 *
	 * @since  2.2.3
	 *
	 * @param  {string} evtName The name of the event to trigger.
	 *
	 * @return {void}
	 */
	wpgetapiimporter.trigger = function( evtName ) {
		var args = Array.prototype.slice.call( arguments, 1 );
		args.push( wpgetapiimporter );
		$document.trigger( evtName, args );
	};

	/**
	 * Triggers a jQuery event on the given jQuery object.
	 *
	 * @since  2.2.3
	 *
	 * @param  {object} $el     The jQuery element object.
	 * @param  {string} evtName The name of the event to trigger.
	 *
	 * @return {void}
	 */
	wpgetapiimporter.triggerElement = function( $el, evtName ) {
		var args = Array.prototype.slice.call( arguments, 2 );
		args.push( wpgetapiimporter );
		$el.trigger( evtName, args );
	};


	/**
     * Gets jQuery object containing all . Caches the result.
     *
     * @since  1.0.0
     *
     * @return {Object} jQuery object containing all.
     */
    wpgetapiimporter.page = function() {
        if ( wpgetapiimporter.$page ) {
            return wpgetapiimporter.$page;
        }
        wpgetapiimporter.$page = $('.wpgetapi');
        return wpgetapiimporter.$page;
    };

    $( wpgetapiimporter.onReady );

}(window, document, jQuery, window.WpGetApiImporter));
