<?php
/**
 * Child theme compatibility.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Child_Compat
 */
class Hestia_Child_Compat extends Hestia_Abstract_Main {
	/**
	 * Add all the hooks necessary.
	 */
	public function init() {

		if ( wp_get_theme()->Name === 'Orfeo' || wp_get_theme()->Name === 'Orfeo Pro' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_orfeo_inline_styles' ) );
		}
	}

	/**
	 * Add inline styles for Orfeo
	 */
	public function add_orfeo_inline_styles() {
		wp_add_inline_style( apply_filters( 'hestia_orfeo_inline_style_handle', 'hestia_style' ), $this->orfeo_inline_style() );
	}

	/**
	 * Orfeo inline style
	 */
	private function orfeo_inline_style() {

		$custom_css = '';

		/* When Home is Blog (Your lastest posts) make background color white */
		$custom_css .= '
			.home.blog .hestia-blogs {
				background-color: #fff !important;
			}
		';

		/* Limit notification width on WooCommerce Checkout Page */
		$custom_css .= '
			.woocommerce-checkout #hestia-checkout-coupon .woocommerce-message,
			.woocommerce-checkout #hestia-checkout-coupon .woocommerce-error {
				margin-left: auto;
				margin-right: auto;
			}
		';

		/**
		 * Remove box shadow from all buttons
		 * Add opacity 0.75 on buttons hover
		 */
		$custom_css .= '
			.btn,
			button,
			.button {
				box-shadow: none !important;
			}
			
			.btn:hover,
			button:hover,
			.button:hover {
				opacity: 0.75;
			}
		';

		/* Align button buttons in Big Title section */
		$custom_css .= '
			.carousel .buttons .btn-primary + .btn-right {
				margin-left: 15px;
			}		
			.carousel .buttons .btn,
			.carousel .buttons .btn-right {
				margin: 15px;
			}
		';

		/* Style Big Title Section because .header class is not its wrapper anymore */
		$custom_css .= '
			.carousel .hestia-big-title-content .hestia-title {
				font-weight: 800;
			}
			.carousel .hestia-big-title-content .sub-title {
				font-family: inherit;
				font-size: 19px;
				font-weight: 300;
				line-height: 26px;
				margin: 0 0 8px;
			}
			.carousel .hestia-big-title-content .buttons .btn,
			.carousel .hestia-big-title-content .buttons .btn-right {
				border-radius: 30px;
				font-family: inherit;
				font-size: 14px;
				font-weight: 600;
				line-height: 24px;
				padding: 11px 30px;
			}
			.carousel .hestia-big-title-content .buttons .btn-right {
				background-color: transparent;
				border: 2px solid #fff;
				padding: 9px 28px;
			}
		';

		return $custom_css;
	}

}
