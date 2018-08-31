<?php
/**
 * Customizer blog settings controls.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Blog_Settings_Controls
 */
class Hestia_Blog_Settings_Controls extends Hestia_Register_Customizer_Controls {

	/**
	 * Add controls
	 */
	public function add_controls() {
		$this->add_blog_settings_section();
		$this->add_featured_posts_category_dropdown();
	}

	/**
	 * Add blog settings section
	 */
	private function add_blog_settings_section() {
		$this->add_section(
			new Hestia_Customizer_Section(
				'hestia_blog_layout',
				array(
					'title'    => apply_filters( 'hestia_blog_layout_control_label', esc_html__( 'Blog Settings', 'hestia' ) ),
					'priority' => 30,
				)
			)
		);
	}

	/**
	 * Add category dropdown control
	 */
	private function add_featured_posts_category_dropdown() {
		$options    = array(
			0 => ' -- ' . esc_html__( 'Disable section', 'hestia' ) . ' -- ',
		);
		$categories = get_categories();
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$cat_id             = $category->term_id;
				$cat_name           = $category->name;
				$options[ $cat_id ] = $cat_name;
			}
		}

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_featured_posts_category',
				array(
					'sanitize_callback' => 'hestia_sanitize_array',
					'default'           => apply_filters( 'hestia_featured_posts_category_default', 0 ),
				),
				array(
					'type'     => 'select',
					'section'  => 'hestia_blog_layout',
					'label'    => esc_html__( 'Featured Posts', 'hestia' ),
					'choices'  => $options,
					'priority' => 10,
				)
			)
		);
	}
}
