<?php
/**
 * Upsell Manager
 *
 * @package Hestia
 */

/**
 * Class Hestia_Upsell_Manager
 */
class Hestia_Upsell_Manager extends Hestia_Register_Customizer_Controls {
	/**
	 * Add the controls.
	 */
	public function add_controls() {
		if ( ! $this->should_display_upsells() ) {
			return;
		}

		$this->register_type( 'Hestia_Section_Upsell', 'section' );
		$this->register_type( 'Hestia_Control_Upsell', 'control' );
		$this->add_main_upsell();
		$this->add_front_page_sections_upsells();
	}

	/**
	 * Change controls
	 */
	public function change_controls() {
		$this->change_customizer_object( 'section', 'hestia_front_page_sections_upsell_section', 'active_callback', '__return_true' );
	}

	/**
	 * Adds main
	 */
	private function add_main_upsell() {
		$this->add_section(
			new Hestia_Customizer_Section(
				'hestia_upsell_main_section',
				array(
					'title'    => esc_html__( 'View PRO Features', 'hestia' ),
					'priority' => 0,
				)
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_upsell_main_control',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'section'            => 'hestia_upsell_main_section',
					'priority'           => 100,
					'options'            => array(
						esc_html__( 'Header Slider', 'hestia' ),
						esc_html__( 'Fully Customizable Colors', 'hestia' ),
						esc_html__( 'Jetpack Portfolio', 'hestia' ),
						esc_html__( 'Pricing Plans Section', 'hestia' ),
						esc_html__( 'Section Reordering', 'hestia' ),
						esc_html__( 'Quality Support', 'hestia' ),
					),
					'explained_features' => array(
						esc_html__( 'You will be able to add more content to your site header with an awesome slider.', 'hestia' ),
						esc_html__( 'Change colors for the header overlay, header text and navbar.', 'hestia' ),
						esc_html__( 'Portfolio section with two possible layouts.', 'hestia' ),
						esc_html__( 'A fully customizable pricing plans section.', 'hestia' ),
						esc_html__( 'Drag and drop panels to change the order of sections.', 'hestia' ),
						esc_html__( 'The ability to reorganize your Frontpage Sections more easily and quickly.', 'hestia' ),
						esc_html__( '24/7 HelpDesk Professional Support', 'hestia' ),
					),
					'button_url'         => esc_url( apply_filters( 'hestia_upgrade_link_from_child_theme_filter', 'https://themeisle.com/themes/hestia-pro/upgrade/' ) ),
					'button_text'        => esc_html__( 'Get the PRO version!', 'hestia' ),
				),
				'Hestia_Control_Upsell'
			)
		);
	}

	/**
	 * Add upsell section under Front Page Sections panel.
	 */
	private function add_front_page_sections_upsells() {
		$this->add_section(
			new Hestia_Customizer_Section(
				'hestia_front_page_sections_upsell_section',
				array(
					'panel'              => 'hestia_frontpage_sections',
					'priority'           => 500,
					'options'            => array(
						esc_html__( 'Jetpack Portfolio', 'hestia' ),
						esc_html__( 'Pricing Plans Section', 'hestia' ),
						esc_html__( 'Section Reordering', 'hestia' ),
					),

					'button_url'         => esc_url( apply_filters( 'hestia_upgrade_link_from_child_theme_filter', 'https://themeisle.com/themes/hestia-pro/upgrade/' ) ),
					'button_text'        => esc_html__( 'Get the PRO version!', 'hestia' ),
					'explained_features' => array(
						esc_html__( 'Portfolio section with two possible layouts.', 'hestia' ),
						esc_html__( 'A fully customizable pricing plans section.', 'hestia' ),
						esc_html__( 'The ability to reorganize your Frontpage sections more easily and quickly.', 'hestia' ),
					),
				),
				'Hestia_Section_Upsell'
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_control_to_enable_upsell_section',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'section' => 'hestia_front_page_sections_upsell_section',
					'type'    => 'hidden',
				)
			)
		);
	}

	/**
	 * Check if should display upsell.
	 *
	 * @since 1.1.45
	 * @access public
	 * @return bool
	 */
	private function should_display_upsells() {
		$current_time    = time();
		$show_after      = 12 * HOUR_IN_SECONDS;
		$activation_time = get_option( 'hestia_time_activated' );

		if ( empty( $activation_time ) ) {
			return false;
		}

		if ( $current_time < $activation_time + $show_after ) {
			return false;
		}

		return true;
	}
}
