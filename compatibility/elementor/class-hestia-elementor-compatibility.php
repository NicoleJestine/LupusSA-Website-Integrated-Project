<?php
/**
 * Elementor Compatibility class.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Elementor_Compatibility
 */
class Hestia_Elementor_Compatibility extends Hestia_Abstract_Main {

	/**
	 * Initialize features.
	 */
	public function init() {
		add_action( 'after_switch_theme', array( $this, 'set_elementor_flag' ) );

		if ( ! $this->should_load_feature() ) {
			return;
		}

		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_elementor_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'page_builder_enqueue' ) );

		add_action( 'wp_ajax_hestia_pagebuilder_hide_frontpage_section', array( $this, 'hestia_pagebuilder_hide_frontpage_section' ) );
		add_action( 'wp_ajax_hestia_elementor_deactivate_default_styles', array( $this, 'hestia_elementor_deactivate_default_styles' ) );

	}

	/**
	 * Section deactivation
	 */
	function hestia_pagebuilder_hide_frontpage_section() {
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'hestia-pagebuilder-nonce' ) ) {
			return;
		}
		$section = $_POST['section'];
		if ( ! empty( $section ) ) {
			if ( $section == 'products' ) {
				$theme_mod = esc_html( 'hestia_shop_hide' );
			} else {
				$theme_mod = esc_html( 'hestia_' . $section . '_hide' );
			}
			if ( ! empty( $theme_mod ) ) {
				set_theme_mod( $theme_mod, 1 );
			}
		}
		die();
	}

	/**
	 * Elementor default styles disabling.
	 */
	function hestia_elementor_deactivate_default_styles() {
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'hestia-elementor-notice-nonce' ) ) {
			return;
		}
		$reply = $_POST['reply'];
		if ( ! empty( $reply ) ) {
			if ( $reply == 'yes' ) {
				update_option( 'elementor_disable_color_schemes', 'yes' );
				update_option( 'elementor_disable_typography_schemes', 'yes' );
			}
			update_option( 'hestia_had_elementor', 'yes' );
		}
		die();
	}
	/**
	 * Set flag for elementor.
	 */
	public function set_elementor_flag() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			update_option( 'hestia_had_elementor', 'no' );
		}
	}

	/**
	 * Enqueue page builder scripts.
	 */
	public function page_builder_enqueue() {
		if ( ( $this->is_beaver_preview() || $this->is_elementor_preview() ) && is_front_page() ) {
			wp_enqueue_script( 'hestia-builder-integration', get_template_directory_uri() . '/assets/js/admin/hestia-pagebuilder.js', array(), HESTIA_VERSION );
			wp_localize_script(
				'hestia-builder-integration', 'hestiaBuilderIntegration', array(
					'ajaxurl'    => admin_url( 'admin-ajax.php' ),
					'nonce'      => wp_create_nonce( 'hestia-pagebuilder-nonce' ),
					'hideString' => esc_html__( 'Disable section', 'hestia' ),
				)
			);
		}

		$had_elementor = get_option( 'hestia_had_elementor' );
		// Ask user if he wants to disable default styling for plugin.
		if ( $had_elementor == 'no' && $this->is_elementor_preview() ) {
			wp_enqueue_script( 'hestia-elementor-notice', get_template_directory_uri() . '/assets/js/admin/hestia-elementor-notice.js', array(), HESTIA_VERSION );
			wp_localize_script(
				'hestia-elementor-notice', 'hestiaElementorNotice', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'hestia-elementor-notice-nonce' ),
				)
			);
		}
	}

	/**
	 * Enqueue styles for elementor.
	 */
	public function enqueue_elementor_styles() {
		$disabled_color_schemes      = get_option( 'elementor_disable_color_schemes' );
		$disabled_typography_schemes = get_option( 'elementor_disable_typography_schemes' );

		if ( $disabled_color_schemes === 'yes' && $disabled_typography_schemes === 'yes' ) {
			wp_enqueue_style( 'hestia-elementor-style', get_template_directory_uri() . '/assets/css/page-builder-style.css', array(), HESTIA_VERSION );
		}
	}

	/**
	 * Utility to check if feature should be loaded.
	 *
	 * @return bool
	 */
	private function should_load_feature() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) && ! defined( 'FL_BUILDER_VERSION' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if we're in Elementor Preview.
	 *
	 * @return bool
	 */
	function is_elementor_preview() {
		if ( class_exists( 'Elementor\Plugin' ) ) {
			if ( Elementor\Plugin::$instance->preview->is_preview_mode() == true ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if we're in Beaver Builder Preview.
	 *
	 * @return bool
	 */
	function is_beaver_preview() {
		if ( class_exists( 'FLBuilderModel' ) ) {
			if ( FLBuilderModel::is_builder_active() == true ) {
				return true;
			}
		}

		return false;
	}
}
