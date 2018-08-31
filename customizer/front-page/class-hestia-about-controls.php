<?php
/**
 * About controls.
 *
 * @package Hestia
 */

/**
 * Class Hestia_About_Controls
 */
class Hestia_About_Controls extends Hestia_Register_Customizer_Controls {
	/**
	 * Add controls.
	 */
	public function add_controls() {
		$this->add_about_section();
		$this->add_hiding_control();
		$this->add_content_control();
		$this->add_pagebuilder_button_control();
		$this->add_background_control();
	}

	/**
	 * Add the section.
	 */
	private function add_about_section() {
		$this->add_section(
			new Hestia_Customizer_Section(
				'hestia_about',
				array(
					'title'          => esc_html__( 'About', 'hestia' ),
					'panel'          => 'hestia_frontpage_sections',
					'priority'       => apply_filters( 'hestia_section_priority', 15, 'hestia_about' ),
					'hiding_control' => 'hestia_about_hide',
				),
				'Hestia_Hiding_Section'
			)
		);
	}

	/**
	 * Add hiding control.
	 */
	private function add_hiding_control() {
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_about_hide',
				array(
					'sanitize_callback' => 'hestia_sanitize_checkbox',
					'default'           => false,
					'transport'         => $this->selective_refresh,
				),
				array(
					'type'     => 'checkbox',
					'label'    => esc_html__( 'Disable section', 'hestia' ),
					'section'  => 'hestia_about',
					'priority' => 1,
				)
			)
		);
	}

	/**
	 * Add about section content editor control.
	 */
	private function add_content_control() {
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_page_editor',
				array(
					'default'           => $this->get_about_content_default(),
					'sanitize_callback' => 'wp_kses_post',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'           => esc_html__( 'About Content', 'hestia' ),
					'section'         => 'hestia_about',
					'priority'        => 10,
					'needsync'        => true,
					'active_callback' => array( $this, 'should_display_content_editor' ),
				),
				'Hestia_Page_Editor',
				array(
					'selector'        => '.hestia-about-content',
					'settings'        => 'hestia_page_editor',
					'render_callback' => array( $this, 'about_content_render_callback' ),
				)
			)
		);
	}

	/**
	 * Add the page builder button control.
	 */
	private function add_pagebuilder_button_control() {
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_elementor_edit',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'           => esc_html__( 'About Content', 'hestia' ),
					'section'         => 'hestia_about',
					'priority'        => 14,
					'active_callback' => 'hestia_edited_with_pagebuilder',
				),
				'Hestia_PageBuilder_Button'
			)
		);
	}

	/**
	 * Add the background image control.
	 */
	private function add_background_control() {
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_feature_thumbnail',
				array(
					'sanitize_callback' => 'esc_url_raw',
					'default'           => get_template_directory_uri() . '/assets/img/contact.jpg',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'           => esc_html__( 'About background', 'hestia' ),
					'section'         => 'hestia_about',
					'priority'        => 15,
					'active_callback' => array( $this, 'is_static_page' ),
				),
				'WP_Customize_Image_Control'
			)
		);
	}

	/**
	 * Get default content for page editor control.
	 *
	 * @return string
	 */
	private function get_about_content_default() {
		$front_page_id = get_option( 'page_on_front' );
		if ( empty( $front_page_id ) ) {
			return '';
		}
		$content = get_post_field( 'post_content', $front_page_id );

		return $content;
	}

	/**
	 * Callback for About section content editor
	 *
	 * @return bool
	 */
	public function should_display_content_editor() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			return ! hestia_edited_with_pagebuilder();
		}

		return false;
	}

	/**
	 * About section content render callback.
	 */
	public function about_content_render_callback() {
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'frontpage' );
			endwhile;
		else : // I'm not sure it's possible to have no posts when this page is shown, but WTH
			get_template_part( 'template-parts/content', 'none' );
		endif;
	}

	/**
	 * Page editor control active callback function
	 *
	 * @return bool
	 */
	public function is_static_page() {
		return 'page' === get_option( 'show_on_front' );
	}
}
