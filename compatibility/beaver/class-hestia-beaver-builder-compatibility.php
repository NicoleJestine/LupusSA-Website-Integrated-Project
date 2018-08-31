<?php
/**
 * Beaver Builder Compatibility class.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Beaver_Builder_Compatibility
 */
class Hestia_Beaver_Builder_Compatibility extends Hestia_Abstract_Main {

	/**
	 * Initialize features.
	 */
	public function init() {
		add_action( 'after_setup_theme', array( $this, 'header_footer_support' ) );
		add_action( 'wp', array( $this, 'header_footer_render' ), 100 );
	}


	/**
	 * Add header and footer support for beaver.
	 *
	 * @since  1.1.24
	 * @access public
	 */
	public function header_footer_render() {

		if ( ! class_exists( 'FLThemeBuilderLayoutData' ) ) {
			return;
		}

		// Get the header ID.
		$header_ids = FLThemeBuilderLayoutData::get_current_page_header_ids();

		// If we have a header, remove the theme header and hook in Theme Builder's.
		if ( ! empty( $header_ids ) ) {
			remove_action( 'hestia_do_header', array( 'Hestia_Header', 'the_header_content' ) );
			remove_action( 'hestia_do_header', array( 'Hestia_Header_Addon', 'hestia_the_header_content' ) );
			add_action( 'hestia_do_header', 'FLThemeBuilderLayoutRenderer::render_header' );
		}

		// Get the footer ID.
		$footer_ids = FLThemeBuilderLayoutData::get_current_page_footer_ids();

		// If we have a footer, remove the theme footer and hook in Theme Builder's.
		if ( ! empty( $footer_ids ) ) {
			remove_action( 'hestia_do_footer', array( 'Hestia_Footer', 'the_footer_content' ) );
			add_action( 'hestia_do_footer', 'FLThemeBuilderLayoutRenderer::render_footer' );
		}
	}

	/**
	 * Add theme support for header and footer.
	 *
	 * @since  1.1.24
	 * @access public
	 */
	public function header_footer_support() {
		add_theme_support( 'fl-theme-builder-headers' );
		add_theme_support( 'fl-theme-builder-footers' );
	}

}
