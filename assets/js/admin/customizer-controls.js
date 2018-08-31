/**
 * File customizer-controls.js
 *
 * The file for generic customizer controls.
 *
 * @package Hestia
 */

jQuery( document ).ready(
	function () {
		'use strict';

		wp.customize(
			'hestia_team_content', function ( value ) {
				value.bind(
					function () {
						var authors_values;
						var result = '';

						if ( jQuery.isFunction( wp.customize._value.hestia_authors_on_blog ) ) {
							authors_values = wp.customize._value.hestia_authors_on_blog();
						}
						jQuery( '#customize-control-hestia_team_content .customizer-repeater-general-control-repeater-container' ).each(
							function () {
								var title = jQuery( this ).find( '.customizer-repeater-title-control' ).val();
								var id = jQuery( this ).find( '.social-repeater-box-id' ).val();
								if ( typeof (title) !== 'undefined' && title !== '' && typeof (id) !== 'undefined' && id !== '' ) {
									result += '<option value="' + id + '" ';
									if ( authors_values && authors_values !== 'undefined' ) {
										if ( authors_values.indexOf( id ) !== -1 ) {
											result += 'selected';
										}
									}
									result += '>' + title + '</option>';
								}
							}
						);

						jQuery( '#customize-control-hestia_authors_on_blog .repeater-multiselect-team' ).html( result );
					}
				);
			}
		);

		/* Move controls to Widgets sections. Used for sidebar placeholders */
		if ( typeof wp.customize.control( 'hestia_placeholder_sidebar_1' ) !== 'undefined' ) {
			wp.customize.control( 'hestia_placeholder_sidebar_1' ).section( 'sidebar-widgets-sidebar-1' );
		}
		if ( typeof wp.customize.control( 'hestia_placeholder_sidebar_woocommerce' ) !== 'undefined' ) {
			wp.customize.control( 'hestia_placeholder_sidebar_woocommerce' ).section( 'sidebar-widgets-sidebar-woocommerce' );
		}

		jQuery( '#customize-theme-controls' ).on(
			'click', '.hestia-link-to-top-menu', function () {
				wp.customize.section( 'menu_locations' ).focus();
			}
		);

		jQuery( '.focus-customizer-header-image' ).on( 'click', function ( e ) {
			e.preventDefault();
			wp.customize.section( 'header_image' ).focus();
		} );


		/**
		 * Toggle section user clicks on customizer shortcut.
		 */
		var customize = wp.customize;
		customize.previewer.bind(
			'hestia-customize-disable-section', function ( data ) {
				jQuery( '[data-customize-setting-link=' + data + ']' ).trigger( 'click' );
			}
		);

		customize.previewer.bind(
			'hestia-customize-focus-control', function ( data ) {
				wp.customize.control( data ).focus();
			}
		);

		// Toggle visibility of Header Video notice when active state change.
        customize.control( 'header_video', function( headerVideoControl ) {
            headerVideoControl.deferred.embedded.done( function() {
                var toggleNotice = function() {
                    var section = customize.section( headerVideoControl.section() ), noticeCode = 'video_header_not_available';
					section.notifications.remove( noticeCode );
                };
                toggleNotice();
                headerVideoControl.active.bind( toggleNotice );
            } );
        } );



		function get_value_by_key( obj, key){
			var result;
			for( var i in obj ){
				if ( typeof obj[i][key] !== 'undefined' ) {
					result = obj[i][key];
					break;
				}
			}

			return result || false;
		}

		var dependentControls = ['hestia_slider_type','hestia_slider_alignment'];
		dependentControls.forEach(function(dependentControl){
            wp.customize(
                dependentControl, function ( value ) {
                    value.bind(
                        function (to) {
                            var i, controlName, selector;

                            var controls = get_value_by_key(
                            	wp.customize.control('hestia_slider_tabs').params.controls,
                                dependentControl);

                            var sectionName = wp.customize.control(dependentControl).section();
                            var sectionContainer = wp.customize.section(sectionName).container;

                            var allControlsArray = Object.values(controls);
                            allControlsArray = [].concat.apply([], allControlsArray);
                            var controlsToShow = controls[to];

                            for( i in allControlsArray ) {
                                controlName = allControlsArray[i];
                                if( controlName === 'widgets' ){
                                    jQuery( sectionContainer ).children( 'li[class*="widget"]' ).css( 'display', 'none' );
                                } else {
                                    selector = wp.customize.control(controlName).selector;
                                    jQuery(selector).hide();
                                }
                            }

                            for( i in controlsToShow ){
                                controlName = controlsToShow[i];
                                if( controlName === 'widgets' ){
                                    jQuery( sectionContainer ).children( 'li[class*="widget"]' ).css( 'display', 'list-item' );
                                } else {
                                    selector = wp.customize.control(controlName).selector;
                                    jQuery(selector).show();
                                }
                            }

                        }
                    );
                }
            );
		});
	}
);