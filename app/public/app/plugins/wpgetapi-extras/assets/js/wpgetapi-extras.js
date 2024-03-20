/**
 * Conditional logic for CMB2 library
 * @author    Awran5 <github.com/awran5>
 * @version   1.1.0
 * @license   under GPL v2.0 (https://github.com/awran5/CMB2-conditional-logic/blob/master/LICENSE)
 * @copyright © 2018 Awran5. All rights reserved.
 * 
 */

window.WpGetApiExtras = window.WpGetApiExtras || {};

(function (window, document, $, wpgetapiextras, undefined) {

  	'use strict'

  	/**
     * Start the JS.
     */
    wpgetapiextras.onReady = function () {

        var $page = wpgetapiextras.page();

        wpgetapiextras.conditionals();

        wpgetapiextras.wpDataTablesButton();
        wpgetapiextras.createTable();
        wpgetapiextras.reconnectTable();

        wpgetapiextras.conditionalsOverride();

        $page
            .on( 'cmb2_remove_row cmb2_add_row cmb2_shift_row cmb2_shift_rows_start', wpgetapiextras.conditionals )
            .on( 'cmb2_remove_row cmb2_add_row cmb2_shift_row cmb2_shift_rows_start cmb2_shift_rows_complete', wpgetapiextras.wpDataTablesButton )
            .on( 'change', '.wpgetapi-actions-before select, .field-actions select', wpgetapiextras.conditionalsOverride );

    }


    // hard coding form conditionals
  	wpgetapiextras.conditionalsOverride = function () {

  		$( '.wpgetapi #endpoints_repeat .cmb-repeatable-grouping' ).each((i, el) => {

			var action = $( el ).find( '.field-actions select').val();

			if( action.indexOf( 'contact_form_7_' ) >= 0 || action.indexOf( 'wpforms_' ) >= 0 || action.indexOf( 'gravity_forms_' ) >= 0 ) {
				
				$( el ).find( "div[class^='field-forms-'],div[class*=' field-forms-']").removeClass( 'hidden' );
			
				// validation and display options
				wpgetapiextras.hideShowValidationFields( el, action );
				wpgetapiextras.hideShowDisplayFields( el );

				// if CF7 form, never display the 'error field id'
				// remove wpdatatables
				if( action.indexOf( 'contact_form_7_' ) >= 0 ) {
					
					$( el ).find( '.field-forms-validation-field').addClass( 'hidden' );
					$( el ).find( '.field-forms-display select option[value="wpdatatables"]').remove();
				
				} else if( action.indexOf( 'wpforms_' ) >= 0 || action.indexOf( 'gravity_forms_' ) >= 0 ) {

					// add wpdatatables to WPForms & Gravity Forms
					if ( $( '.field-forms-display select option[value="wpdatatables"]' ).length == 0) {
						$( el ).find( '.field-forms-display select').append( $("<option></option>")
	        				.attr( 'value', 'wpdatatables' )
	        				.text( 'Connected wpDataTable' ) );
					}
					
				}

				// wpforms can't do validation from API
				if( action.indexOf( 'wpforms_' ) >= 0 ) {
					$( el ).find( '.field-forms-validation').addClass( 'hidden' );
				}

			} else {
				// if not the forms plugins, hide all and return
				$( el ).find( "div[class^='field-forms-'],div[class*=' field-forms-']").addClass( 'hidden' );
				
			}

			// if no action, hide all
			// checking for 2 because we have opening and closing div fields
			if( $( el ).find( '.wpgetapi-actions-before' ).find( '.cmb-row:not(.hidden)' ).length === 2 || ! action || action == '' ) {
				$( el ).find( '.wpgetapi-actions-before' ).addClass( 'hidden' );
			} else {
				$( el ).find( '.wpgetapi-actions-before' ).removeClass( 'hidden' );
			}

		});

  	}

  	wpgetapiextras.hideShowValidationFields = function ( el, action ) {
  		var validation = $( el ).find( '.field-forms-validation select').val();
  		var validation_type = $( el ).find( '.field-forms-validation-type select').val();

		if( validation == 'after' || action.indexOf( 'wpforms_' ) >= 0 ) {
			$( el ).find( '.field-forms-validation-type').addClass( 'hidden' );
			$( el ).find( '.field-forms-validation-value').addClass( 'hidden' );
			$( el ).find( '.field-forms-validation-message').addClass( 'hidden' );
			$( el ).find( '.field-forms-validation-field').addClass( 'hidden' );
		} else {
			$( el ).find( '.field-forms-validation-type').removeClass( 'hidden' );
			$( el ).find( '.field-forms-validation-value').removeClass( 'hidden' );
			$( el ).find( '.field-forms-validation-message').removeClass( 'hidden' );
			$( el ).find( '.field-forms-validation-field').removeClass( 'hidden' );
		}

		// if validation_type is 'send_if_200', never display the 'validation value field'
		if( validation_type == 'send_if_200' || validation == 'after' )
			$( el ).find( '.field-forms-validation-value').addClass( 'hidden' );


  	}

  	wpgetapiextras.hideShowDisplayFields = function ( el ) {
  		var display = $( el ).find( '.field-forms-display select').val();

		if( display == 'form_confirmation' || display == 'wpdatatables' ) {
			$( el ).find( '.field-forms-display-keys').addClass( 'hidden' );
			$( el ).find( '.field-forms-success-url').addClass( 'hidden' );
			$( el ).find( '.field-forms-success-value').addClass( 'hidden' );
			$( el ).find( '.field-forms-error-url').addClass( 'hidden' );
			$( el ).find( '.field-forms-error-value').addClass( 'hidden' );
		} else if( display == 'api_response' ) {
			$( el ).find( '.field-forms-display-keys').removeClass( 'hidden' );
			$( el ).find( '.field-forms-success-url').addClass( 'hidden' );
			$( el ).find( '.field-forms-success-value').addClass( 'hidden' );
			$( el ).find( '.field-forms-error-url').addClass( 'hidden' );
			$( el ).find( '.field-forms-error-value').addClass( 'hidden' );
		} else if( display == 'redirect' ) {
			$( el ).find( '.field-forms-display-keys').addClass( 'hidden' );
			$( el ).find( '.field-forms-success-url').removeClass( 'hidden' );
			$( el ).find( '.field-forms-success-value').removeClass( 'hidden' );
			$( el ).find( '.field-forms-error-url').removeClass( 'hidden' );
			$( el ).find( '.field-forms-error-value').removeClass( 'hidden' );
		}
  	}


    /**
     * Create a new wpdatatable
     */
    wpgetapiextras.createTable = function () {

    	jQuery(document).ready(function($) {

			$('body').on( 'click', '.wpgetapi .create-wpdatatable', function(){
 
		        var $this = $(this);
		        var $buttonWrap = $this.parents( '.wpdatatables-buttons' );
		        var endpoint_id = $buttonWrap.data( 'endpoint-id' );
		        var wpdatatable_root = $buttonWrap.data( 'wpdatatable-root' );
		        var api_id = $buttonWrap.data( 'api-id' );

		        // disable button
		        $this.attr( 'disabled', true );
		        $( '.wpgetapi .wpdatatables-buttons .processing' ).text( '...calling API' );
		        async function fnAsync() {
				  	await wpgetapiextras.createDummyFile( api_id, endpoint_id );
				  	await wpgetapiextras.createInitialTable( api_id, endpoint_id, wpdatatable_root );
				  	wpgetapiextras.finaliseTable( api_id, endpoint_id );
				}
				fnAsync();

			});

		});

    }


    /**
     * Step 1 create our actual JSON file.
     * We don't have a table id yet, so jsut create dummy file.
     * There is a method to this madness as to why this is created before the table.
     */
    wpgetapiextras.createDummyFile = function ( api_id, endpoint_id ) {

    	return new Promise((resolve) => {
		   
		    jQuery.ajax({
	            url: ajaxurl,
	            method: 'POST',
	            dataType: 'json',
	            data: {
	                action: 'wpgetapi_create_dummy_file',
	                api_id: api_id,
	                endpoint_id: endpoint_id
	            },
	            success: function(data){
	            	console.log("1st");
	                console.log( 'File created' );
	                $( '.wpgetapi .wpdatatables-buttons .processing' ).text( '...JSON file created' );
	                resolve();
	            }
	        });
		    
		 });
    	
    }

    /**
     * Step 2
     */
    wpgetapiextras.createInitialTable = function ( api_id, endpoint_id, wpdatatable_root ) {
    	return new Promise((resolve) => {
		    
	    	jQuery.ajax({
	            url: ajaxurl,
	            method: 'POST',
	            dataType: 'json',
	            data: {
	                wdtNonce: $('#wdtNonce').val(),
	                action: 'wpdatatables_save_table_config',
	                table: '{"id":null,"title":"' + api_id + ' ' + endpoint_id + '","show_title":0,"table_description":"","show_table_description":false,"tools":1,"responsive":1,"responsiveAction":"icon","hide_before_load":0,"fixed_layout":0,"scrollable":0,"verticalScroll":0,"sorting":1,"word_wrap":0,"table_type":"nested_json","file_location":"wp_media_lib","server_side":0,"auto_refresh":0,"content":"","info_block":1,"pagination":1,"paginationAlign":"right","paginationLayout":"full_numbers","simpleResponsive":0,"simpleHeader":0,"stripeTable":0,"cellPadding":10,"removeBorders":0,"borderCollapse":"collapse","borderSpacing":0,"verticalScrollHeight":600,"filtering":1,"global_search":1,"cache_source_data":0,"auto_update_cache":0,"editable":0,"popover_tools":0,"mysql_table_name":"","connection":"","edit_only_own_rows":0,"userid_column_id":null,"inline_editing":0,"filtering_form":0,"clearFilters":0,"display_length":10,"showRowsPerPage":true,"id_editing_column":false,"editor_roles":"","table_html":"","datatable_config":null,"tabletools_config":{"print":1,"copy":1,"excel":1,"csv":1,"pdf":0},"pdfPaperSize":"A4","pdfPageOrientation":"portrait","showTableToolsIncludeHTML":0,"showTableToolsIncludeTitle":0,"columns":[],"currentOpenColumn":null,"var1":"","var2":"","var3":"","currentUserIdPlaceholder":"1","currentUserLoginPlaceholder":"AdminUser","currentPostIdPlaceholder":"","wpdbPlaceholder":"' + wpgetapi_extras.pre +'","jsonAuthParams":{"url":"' + wpgetapi_extras.upload_url +'/wpdatatables-dummy.json","method":"get","authOption":"","username":"","password":"","customHeaders":[],"root":"' + wpdatatable_root + '"}}'
	            },
	            success: function(data){

	            	if( typeof data.error != 'undefined' ){
	            		console.log(data);
	            		console.log(data.error);
                        //return;
                    }

	            	console.log("2nd");
	            	$( '.wpgetapi .wpdatatables-buttons .processing' ).text( '...wpDataTable created' );
	  				resolve();	              
	            },
                error: function( data ){
                    console.log('There was an error while trying to save the table! '+data.statusText+' '+data.responseText);
                }
	        });
        });
        
    }



    /**
     * Step 3
     */
    wpgetapiextras.finaliseTable = function ( api_id, endpoint_id ) {

	    var data = {
			'action': 'wpgetapi_create_wpdatatable',
			'api_id': api_id,
			'endpoint_id': endpoint_id,
		};

		// send and reload
		jQuery.post(ajaxurl, data, function( response ) {
			console.log("3rd");
			$( '.wpgetapi .wpdatatables-buttons .processing' ).text( '...finalizing' );
			location.reload();

		});

    }


    /**
     * 
     */
    wpgetapiextras.reconnectTable = function () {

    	jQuery(document).ready(function($) {

			$('body').on( 'click', '.wpgetapi .reconnect-wpdatatable', function(){
 
		        var $this = $(this);
		        var $buttonWrap = $this.parents( '.wpdatatables-buttons' );
		        var endpoint_id = $buttonWrap.data( 'endpoint-id' );
		        var api_id = $buttonWrap.data( 'api-id' );
		        var table_id = $buttonWrap.data( 'wpdatatable-id' );

		        // disable button
		        $this.attr( 'disabled', true );

				var data = {
					'action': 'wpgetapi_reconnect_wpdatatable',
					'api_id': api_id,
					'endpoint_id': endpoint_id,
					'table_id': table_id
				};

				// send and reload
				jQuery.post(ajaxurl, data, function( response ) {
					//console.log(response)
					location.reload();

				});

			});

		});

    }

    /**
     * 
     */
    wpgetapiextras.wpDataTablesButton = function ( e ) {
        
        // change, keyup, add_row etc 
        if( e && e.type.length > 0 ) {

            var $this = $( this );
            var $group = $this.parents( '.cmb-repeatable-grouping' );

            // if we are adding a group
            if( e.type == 'cmb2_add_row' ) {

                $group = $( '.cmb-repeatable-grouping' ).last();
                var $createButton = $group.find( '.create-wpdatatable' );
                $group.find( '.connected' ).addClass( 'hidden' );

                $createButton.text( 'Create wpDataTable from this endpoint' ).addClass( 'button-secondary' ).removeClass( 'button-link' );
            }

            // if we are adding a group
            if( e.type == 'cmb2_shift_rows_complete' ) {
            	
            	// And swap them all
				$( '.cmb-repeatable-grouping').each( function( index ) {

					var $group = $( this );
	                var $buttonWrap = $group.find( '.wpdatatables-buttons' );

	                // get our values
	                var endpoint_id = $group.find( '.field-id input' ).val();
	                var wpdatatable_id = $group.find( '.field-wpdatatables input' ).val();
	                var wpdatatable_root = $group.find( '.field-wpdatatables-root input' ).val();
                	wpdatatable_root = wpdatatable_root == '' ? 'root' : wpdatatable_root;

	                // if we have a wpdatatable set for this endpoint
	                if( wpdatatable_id ) {

	                	var $editButton = $group.find( '.edit-wpdatatable' );

	                	$buttonWrap.attr( 'data-wpdatatable-id', wpdatatable_id );
	                	$buttonWrap.attr( 'data-wpdatatable-root', wpdatatable_root );
	                	$buttonWrap.find( 'p.text.connected span' ).text( wpdatatable_id );
	                	
	                	// edit button
	                	if ('URLSearchParams' in window) {
	                		var url = new URL( $editButton.attr('href') );
						    url.searchParams.set('table_id', wpdatatable_id);
						    $editButton.attr('href', url );
						}

	                }

	            });

            }

            if( $group.length > 0 ) {

                

            }

        } else {

            $('.cmb-repeatable-grouping').each(function( index, value ) {

                var $group = $( this );
                var $buttonWrap = $group.find( '.wpdatatables-buttons' );

                // get our values
                var endpoint_id = $group.find( '.field-id input' ).val();
                var wpdatatable_id = $group.find( '.field-wpdatatables input' ).val();
                var wpdatatable_root = $group.find( '.field-wpdatatables-root input' ).val();
                wpdatatable_root = wpdatatable_root == '' ? 'root' : wpdatatable_root;

                // set the endpoint id
                $buttonWrap.attr( 'data-endpoint-id', endpoint_id );
                $buttonWrap.attr( 'data-wpdatatable-root', wpdatatable_root );

                // if we have a wpdatatable set for this endpoint
                if( wpdatatable_id ) {

                	var $createButton = $group.find( '.create-wpdatatable' );
                	var $editButton = $group.find( '.edit-wpdatatable' );

                	$buttonWrap.attr( 'data-wpdatatable-id', wpdatatable_id );
                	$buttonWrap.find( 'p.text.connected span' ).text( wpdatatable_id );

                	// edit button
                	if ('URLSearchParams' in window) {
                		var url = new URL( $editButton.attr('href') );
					    url.searchParams.set('table_id', wpdatatable_id);
					    $editButton.attr('href', url );
					}

                	// create button
                	$createButton.text( 'Create new wpDataTable' ).removeClass( 'button-secondary' ).addClass( 'button-link' );
                	$group.find( '.connected' ).removeClass( 'hidden' );

                }

            });

        }

    }

  	wpgetapiextras.conditionals = function () {

  		//wpgetapiextras.conditionalsOverride();

		$('[data-conditional-id]').each((i, el) => {

		  	let condName = el.dataset.conditionalId,
		  		origName = el.dataset.conditionalId,
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

				let checkCondition = condValue.includes(value) && value !== '' ;

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

		  	function conditionalFieldParent(origName, value ) {

				if( origName == 'actions' ) {

					$( '.wpgetapi-actions-before .cmb-row [data-conditional-parent]' ).each((i, ele) => {

						let condParentValue = ele.dataset.ConditionalParent;
						let parent = ele.closest('.cmb-row');

						if( value !== '' && ele.dataset.conditionalParent.includes(value) ) {
							conditionalField( $(parent), 'show' );
						} else {
							conditionalField( $(parent), 'hide' );
						}

					});

				}

		  	}

		  	// Select the field by name and loop through
		  	$('[name="' + condName + '"]').each(function (i, field) {

				// Select field
				if ("select-one" === field.type) {

				  	if ( ! valueMatch(field.value)) 
						conditionalField($(condParent), initAction);

					//console.log( origName, field.value );
					conditionalFieldParent( origName, field.value );

					// Check on change
					$(field).on('change', function (event) {
						
						( ! valueMatch(event.target.value)) ? conditionalField($(condParent), 'hide') : conditionalField($(condParent), 'show');

						conditionalFieldParent( origName, event.target.value );

						//wpgetapiextras.conditionalsOverride();
						
					});

				}

				// Checkbox field
				else if ("checkbox" === field.type) {
						
					// Hide the row if the value doesn't match and not checked
					if (!checkboxInit(field))
						conditionalField($(condParent), initAction);

				}

				// Radio field
				else if ("radio" === field.type) {

				  	// Hide the row if the value doesn't match and not checked
				  	if (!valueMatch(field.value) && checkboxInit(field))
						conditionalField($(condParent), initAction);


				}

		  	});

		});

  	}


	/**
     * Gets jQuery object containing all . Caches the result.
     *
     * @since  1.0.0
     *
     * @return {Object} jQuery object containing all.
     */
    wpgetapiextras.page = function() {
        if ( wpgetapiextras.$page ) {
            return wpgetapiextras.$page;
        }
        wpgetapiextras.$page = $('.wpgetapi');
        return wpgetapiextras.$page;
    };

    $( wpgetapiextras.onReady );

}(window, document, jQuery, window.WpGetApiExtras));
