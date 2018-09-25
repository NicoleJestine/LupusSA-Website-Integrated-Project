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
		$this->add_featured_posts_controls();
		$this->add_blog_layout_controls();
		$this->add_blog_post_content_controls();
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
					'priority' => 45,
				)
			)
		);
	}

	/**
	 * Add category dropdown control
	 */
	private function add_featured_posts_controls() {

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_featured_posts_label',
				array(
					'sanitize_callback' => 'wp_kses',
				),
				array(
					'label'    => esc_html__( 'Featured Posts', 'hestia' ),
					'section'  => 'hestia_blog_layout',
					'priority' => 10,
				),
				'Hestia_Customizer_Heading'
			)
		);

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
					'label'    => esc_html__( 'Categories:', 'hestia' ),
					'choices'  => $options,
					'priority' => 15,
				)
			)
		);
	}

	/**
	 * Add blog layout controls
	 */
	private function add_blog_layout_controls() {
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_alternative_blog_layout',
				array(
					'default'           => 'blog_normal_layout',
					'sanitize_callback' => 'hestia_sanitize_blog_layout_control',
				),
				array(
					'label'       => esc_html__( 'Blog', 'hestia' ) . ' ' . esc_html__( 'Layout', 'hestia' ),
					'section'     => 'hestia_blog_layout',
					'priority'    => 25,
					'choices'     => array(
						'blog_alternative_layout'  => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqAgMAAAAjP0ATAAAACVBMVEX///8+yP/V1dXG9YqxAAAAS0lEQVRYw2NgGAXDE4RCQMDAKONahQ5WUKBs1AujXqDEC6NgiANRSDyH0EwZRvJZ1UCBslEvjHqBZl4YBYMUjNb1o14Y9cIoGH4AALJWvPSk+QsLAAAAAElFTkSuQmCC',
						),
						'blog_normal_layout'       => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqAgMAAAAjP0ATAAAACVBMVEX///8+yP/V1dXG9YqxAAAAPklEQVR42mNgGAXDE4RCQMDAKONahQ5WUKBs1AujXqDEC6NgtOAazTKjXhgtuEbBaME1mutHvTBacI0C4gEAenW95O4Ccg4AAAAASUVORK5CYII=',
						),
						'blog_alternative_layout2' => array(
							'url'      => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqCAMAAABpj1iyAAAACVBMVEUAyv/V1dX////o4eoDAAAAfUlEQVR42u3ZoQ0AMAgAQej+Q3cDCI6QQyNOvKGNt3KwsLCwsLB2sKKc4V6/iIWFhYWFhYWFhXWN5cQ4xcpyhos9K8tZytKW5CWvLclLXltYWFhYWFj+Ez0kYWFhYWFhYWFhYTkxrrGyHC/N2pK85LUleclrCwsLCwvrMOsDUDxdDThzw38AAAAASUVORK5CYII=',
							'redirect' => 'https://themeisle.com/themes/hestia-pro/upgrade?utm_medium=customizer&utm_source=image&utm_campaign=blogpro',
						),
					),
					'subcontrols' => array(
						'blog_alternative_layout' => array(),
						'blog_normal_layout'      => array(),
					),
				),
				'Hestia_Customize_Control_Radio_Image'
			)
		);
	}

	/**
	 * Add blog post content controls
	 */
	private function add_blog_post_content_controls() {

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_blog_settings_label',
				array(
					'sanitize_callback' => 'wp_kses',
				),
				array(
					'label'    => esc_html__( 'Blog Settings', 'hestia' ),
					'section'  => 'hestia_blog_layout',
					'priority' => 20,
				),
				'Hestia_Customizer_Heading'
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_disable_categories',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => 'one',
				),
				array(
					'type'     => 'select',
					'priority' => 40,
					'section'  => 'hestia_blog_layout',
					'label'    => esc_html( 'Display', 'hestia-pro' ) . ' ' . esc_html__( 'Blog', 'hestia' ) . ' ' . esc_html__( 'Categories:', 'hestia' ),
					'choices'  => array(
						'none' => esc_html__( 'None', 'hestia' ),
						'one'  => esc_html( 'First', 'hestia-pro' ),
						'all'  => esc_html( 'All', 'hestia-pro' ),
					),
				)
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_blog_post_content_type',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => 'excerpt',
				),
				array(
					'priority'    => 45,
					'section'     => 'hestia_blog_layout',
					'label'       => esc_html__( 'Blog Post Content', 'hestia' ),
					'choices'     => array(
						'excerpt' => esc_html__( 'Excerpt', 'hestia' ),
						'content' => esc_html__( 'Content', 'hestia' ),
					),
					'subcontrols' => array(
						'excerpt' => array(
							'hestia_excerpt_length',
						),
						'content' => array(),
					),
				),
				'Hestia_Select_Hiding'
			)
		);

		$excerpt_default = hestia_get_excerpt_default();
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_excerpt_length',
				array(
					'default'           => $excerpt_default,
					'sanitize_callback' => 'absint',
				),
				array(
					'label'    => esc_html__( 'Excerpt length', 'hestia' ),
					'section'  => 'hestia_blog_layout',
					'priority' => 50,
					'type'     => 'number',
				)
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_pagination_type',
				array(
					'default'           => 'number',
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'    => esc_html__( 'Post Pagination', 'hestia' ),
					'section'  => 'hestia_blog_layout',
					'priority' => 55,
					'type'     => 'select',
					'choices'  => array(
						'number'   => esc_html__( 'Number', 'hestia' ),
						'infinite' => esc_html__( 'Infinite Scroll', 'hestia' ),
					),
				)
			)
		);

	}
}
