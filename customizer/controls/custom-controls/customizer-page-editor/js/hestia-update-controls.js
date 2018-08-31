/**
 * Update controls
 *
 * @package Hestia
 */

/* global requestpost */
/* global wp */
/* global WPEditorWidget */

( function( $ ) {
	'use strict';
	wp.customize(
		'page_on_front', function( value ) {

			value.bind(
				function( newval ) {

					$.ajax(
						{
							url: requestpost.ajaxurl,
							type: 'post',
							data: {
								action: 'hestiaUpdateFrontPageChange',
								pid: newval
							},
							success: function (result) {
								if (result !== '' && result !== 'undefined' ) {

									result      = JSON.parse( result );
									var html, content = result.post_content;
									jQuery( '#hestia_page_editor' ).val( content );
									WPEditorWidget.setEditorContent( 'hestia_page_editor' );

									if (result.post_thumbnail !== '' && result.post_thumbnail !== 'undefined') {
										wp.customize.instance( requestpost.thumbnail_control ).set( result.post_thumbnail );
										html = '<label for="hestia_feature_thumbnail-button">' +
										'<span class="customize-control-title">' + requestpost.thumbnail_label + '</span>' +
										'</label>' +
										'<div class="attachment-media-view attachment-media-view-image landscape">' +
										'<div class="thumbnail thumbnail-image">' +
										'<img class="attachment-thumb" src="' + result.post_thumbnail + '" draggable="false" alt=""> ' +
										'</div>' +
										'<div class="actions">' +
										'<button type="button" class="button remove-button">Remove</button>' +
										'<button type="button" class="button upload-button control-focus" id="hestia_feature_thumbnail-button">Change Image</button> ' +
										'<div style="clear:both"></div>' +
										'</div>' +
										'</div>';
									} else {
                                        wp.customize.instance( requestpost.thumbnail_control ).set( '' );
										html = '<label class="customize-control-title" for="customize-media-control-button-105">About background</label>' +
                                            '<div class="customize-control-notifications-container" style="display: none;"><ul></ul></div>' +
                                            '<div class="attachment-media-view">\n' +
                                            '<div class="placeholder">' +
                                            'No image selected' +
                                            '</div>' +
                                            '<div class="actions">' +
                                            '<button type="button" class="button default-button">Default</button>' +
                                            '<button type="button" class="button upload-button" id="customize-media-control-button-105">Select image</button>' +
                                            '</div>' +
                                            '</div>';
									}
                                    wp.customize.control( requestpost.thumbnail_control ).container['0'].innerHTML = html;
									wp.customize.instance( requestpost.editor_control ).previewer.refresh();
								}
							}
						}
					);

				}
			);
		}
	);
} )( jQuery );
