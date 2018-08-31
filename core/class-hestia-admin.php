<?php
/**
 * The admin class that handles all the dashboard integration.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Admin
 */
class Hestia_Admin {

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
	}

	/**
	 * Add the about page.
	 */
	public function do_about_page() {
		/*
		 * About page instance
		 */
		$config = array(
			// Menu name under Appearance.
			'menu_name'           => apply_filters( 'hestia_about_page_filter', __( 'About Hestia', 'hestia' ), 'menu_name' ),
			// Page title.
			'page_name'           => apply_filters( 'hestia_about_page_filter', __( 'About Hestia', 'hestia' ), 'page_name' ),
			// Main welcome title
			/* translators: s - theme name */
			'welcome_title'       => apply_filters( 'hestia_about_page_filter', sprintf( __( 'Welcome to %s! - Version ', 'hestia' ), 'Hestia' ), 'welcome_title' ),
			// Main welcome content
			'welcome_content'     => apply_filters( 'hestia_about_page_filter', esc_html__( 'Hestia is a modern WordPress theme for professionals. It fits creative business, small businesses (restaurants, wedding planners, sport/medical shops), startups, corporate businesses, online agencies and firms, portfolios, ecommerce (WooCommerce), and freelancers. It has a multipurpose one-page design, widgetized footer, blog/news page and a clean look, is compatible with: Flat Parallax Slider, Photo Gallery, Travel Map and Elementor Page Builder . The theme is responsive, WPML, Retina ready, SEO friendly, and uses Material Kit for design.', 'hestia' ), 'welcome_content' ),
			/**
			 * Tabs array.
			 *
			 * The key needs to be ONLY consisted from letters and underscores. If we want to define outside the class a function to render the tab,
			 * the will be the name of the function which will be used to render the tab content.
			 */
			'tabs'                => array(
				'getting_started'     => __( 'Getting Started', 'hestia' ),
				'recommended_actions' => __( 'Recommended Actions', 'hestia' ),
				'recommended_plugins' => __( 'Useful Plugins', 'hestia' ),
				'support'             => __( 'Support', 'hestia' ),
				'changelog'           => __( 'Changelog', 'hestia' ),
				'free_pro'            => __( 'Free vs PRO', 'hestia' ),
			),
			// Support content tab.
			'support_content'     => array(
				'first'  => array(
					'title'        => esc_html__( 'Contact Support', 'hestia' ),
					'icon'         => 'dashicons dashicons-sos',
					'text'         => esc_html__( 'We want to make sure you have the best experience using Hestia, and that is why we have gathered all the necessary information here for you. We hope you will enjoy using Hestia as much as we enjoy creating great products.', 'hestia' ),
					'button_label' => esc_html__( 'Contact Support', 'hestia' ),
					'button_link'  => esc_url( 'https://themeisle.com/contact/' ),
					'is_button'    => true,
					'is_new_tab'   => true,
				),
				'second' => array(
					'title'        => esc_html__( 'Documentation', 'hestia' ),
					'icon'         => 'dashicons dashicons-book-alt',
					'text'         => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Hestia.', 'hestia' ),
					'button_label' => esc_html__( 'Read full documentation', 'hestia' ),
					'button_link'  => 'http://docs.themeisle.com/article/569-hestia-documentation',
					'is_button'    => false,
					'is_new_tab'   => true,
				),
				'third'  => array(
					'title'        => esc_html__( 'Changelog', 'hestia' ),
					'icon'         => 'dashicons dashicons-portfolio',
					'text'         => esc_html__( 'Want to get the gist on the latest theme changes? Just consult our changelog below to get a taste of the recent fixes and features implemented.', 'hestia' ),
					'button_label' => esc_html__( 'Changelog', 'hestia' ),
					'button_link'  => esc_url( '#changelog' ),
					'is_button'    => false,
					'is_new_tab'   => false,
				),
				'fourth' => array(
					'title'        => esc_html__( 'Create a child theme', 'hestia' ),
					'icon'         => 'dashicons dashicons-admin-customizer',
					'text'         => esc_html__( "If you want to make changes to the theme's files, those changes are likely to be overwritten when you next update the theme. In order to prevent that from happening, you need to create a child theme. For this, please follow the documentation below.", 'hestia' ),
					'button_label' => esc_html__( 'View how to do this', 'hestia' ),
					'button_link'  => 'http://docs.themeisle.com/article/14-how-to-create-a-child-theme',
					'is_button'    => false,
					'is_new_tab'   => true,
				),
				'fifth'  => array(
					'title'        => esc_html__( 'Speed up your site', 'hestia' ),
					'icon'         => 'dashicons dashicons-controls-skipforward',
					'text'         => esc_html__( 'If you find yourself in a situation where everything on your site is running very slowly, you might consider having a look at the documentation below where you will find the most common issues causing this and possible solutions for each of the issues.', 'hestia' ),
					'button_label' => esc_html__( 'View how to do this', 'hestia' ),
					'button_link'  => 'http://docs.themeisle.com/article/63-speed-up-your-wordpress-site',
					'is_button'    => false,
					'is_new_tab'   => true,
				),
				'sixth'  => array(
					'title'        => esc_html__( 'Build a landing page with a drag-and-drop content builder', 'hestia' ),
					'icon'         => 'dashicons dashicons-images-alt2',
					'text'         => esc_html__( 'In the documentation below you will find an easy way to build a great looking landing page using a drag-and-drop content builder plugin.', 'hestia' ),
					'button_label' => esc_html__( 'View how to do this', 'hestia' ),
					'button_link'  => 'http://docs.themeisle.com/article/219-how-to-build-a-landing-page-with-a-drag-and-drop-content-builder',
					'is_button'    => false,
					'is_new_tab'   => true,
				),
			),
			// Getting started tab
			'getting_started'     => array(
				'first'  => array(
					'title'               => esc_html__( 'Recommended actions', 'hestia' ),
					'text'                => esc_html__( 'We have compiled a list of steps for you to take so we can ensure that the experience you have using one of our products is very easy to follow.', 'hestia' ),
					'button_label'        => esc_html__( 'Recommended actions', 'hestia' ),
					'button_link'         => esc_url( '#recommended_actions' ),
					'is_button'           => false,
					'recommended_actions' => true,
					'is_new_tab'          => false,
				),
				'second' => array(
					'title'               => esc_html__( 'Read full documentation', 'hestia' ),
					'text'                => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Hestia.', 'hestia' ),
					'button_label'        => esc_html__( 'Documentation', 'hestia' ),
					'button_link'         => 'http://docs.themeisle.com/article/569-hestia-documentation',
					'is_button'           => false,
					'recommended_actions' => false,
					'is_new_tab'          => true,
				),
				'third'  => array(
					'title'               => esc_html__( 'Go to the Customizer', 'hestia' ),
					'text'                => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.', 'hestia' ),
					'button_label'        => esc_html__( 'Go to the Customizer', 'hestia' ),
					'button_link'         => esc_url( admin_url( 'customize.php' ) ),
					'is_button'           => true,
					'recommended_actions' => false,
					'is_new_tab'          => true,
				),
			),
			// Free vs PRO array.
			'free_pro'            => array(
				'free_theme_name'     => 'Hestia',
				'pro_theme_name'      => 'Hestia Pro',
				'pro_theme_link'      => apply_filters( 'hestia_upgrade_link_from_child_theme_filter', 'https://themeisle.com/themes/hestia-pro/upgrade/' ),
				/* translators: s - theme name */
				'get_pro_theme_label' => sprintf( __( 'Get %s now!', 'hestia' ), 'Hestia Pro' ),
				'banner_link'         => 'http://docs.themeisle.com/article/647-what-is-the-difference-between-hestia-and-hestia-pro',
				'banner_src'          => get_template_directory_uri() . '/assets/img/free_vs_pro_banner.png',
				'features'            => array(
					array(
						'title'       => __( 'Mobile friendly', 'hestia' ),
						'description' => __( 'Responsive layout. Works on every device.', 'hestia' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'WooCommerce Compatible', 'hestia' ),
						'description' => __( 'Ready for e-commerce. You can build an online store here.', 'hestia' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Frontpage Sections', 'hestia' ),
						'description' => __( 'Big title, Features, About, Team, Testimonials, Subscribe, Blog, Contact', 'hestia' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Background image', 'hestia' ),
						'description' => __( 'You can use any background image you want.', 'hestia' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Section Reordering', 'hestia' ),
						'description' => __( 'The ability to reorganize your Frontpage Sections more easily and quickly.', 'hestia' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Shortcodes for each section', 'hestia' ),
						'description' => __( 'Display a frontpage section wherever you like by adding its shortcode in page or post content.', 'hestia' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Header Slider', 'hestia' ),
						'description' => __( 'You will be able to add more content to your site header with an awesome slider.', 'hestia' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Fully Customizable Colors', 'hestia' ),
						'description' => __( 'Change colors for the header overlay, header text and navbar.', 'hestia' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Jetpack Portfolio', 'hestia' ),
						'description' => __( 'Portfolio section with two possible layouts.', 'hestia' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Pricing Plans Section', 'hestia' ),
						'description' => __( 'A fully customizable pricing plans section.', 'hestia' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => __( 'Quality Support', 'hestia' ),
						'description' => __( '24/7 HelpDesk Professional Support', 'hestia' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
				),
			),
			// Plugins array.
			'recommended_plugins' => array(
				'already_activated_message' => esc_html__( 'Already activated', 'hestia' ),
				'version_label'             => esc_html__( 'Version: ', 'hestia' ),
				'install_label'             => esc_html__( 'Install and Activate', 'hestia' ),
				'activate_label'            => esc_html__( 'Activate', 'hestia' ),
				'deactivate_label'          => esc_html__( 'Deactivate', 'hestia' ),
				'content'                   => array(
					array(
						'slug' => 'elementor',
					),
					array(
						'slug' => 'translatepress-multilingual',
					),
					array(
						'slug' => 'beaver-builder-lite-version',
					),
					array(
						'slug' => 'wp-product-review',
					),
					array(
						'slug' => 'intergeo-maps',
					),
					array(
						'slug' => 'visualizer',
					),
					array(
						'slug' => 'adblock-notify-by-bweb',
					),
					array(
						'slug' => 'nivo-slider-lite',
					),
				),
			),
			// Required actions array.
			'recommended_actions' => array(
				'install_label'    => esc_html__( 'Install and Activate', 'hestia' ),
				'activate_label'   => esc_html__( 'Activate', 'hestia' ),
				'deactivate_label' => esc_html__( 'Deactivate', 'hestia' ),
				'content'          => array(
					'themeisle-companion' => array(
						'title'       => 'Orbit Fox Companion',
						'description' => __( 'It is highly recommended that you install the companion plugin to have access to the Frontpage features, Team and Testimonials sections.', 'hestia' ),
						'check'       => defined( 'THEMEISLE_COMPANION_VERSION' ),
						'plugin_slug' => 'themeisle-companion',
						'id'          => 'themeisle-companion',
					),
					'pirate-forms'        => array(
						'title'       => 'Pirate Forms',
						'description' => __( 'Makes your Contact section more engaging by creating a good-looking contact form. Interaction with your visitors has never been easier.', 'hestia' ),
						'check'       => defined( 'PIRATE_FORMS_VERSION' ),
						'plugin_slug' => 'pirate-forms',
						'id'          => 'pirate-forms',
					),
					'elementor'           => array(
						'title'       => 'Elementor',
						'check'       => ( defined( 'ELEMENTOR_VERSION' ) || ! hestia_check_passed_time( 12 * MONTH_IN_SECONDS ) ),
						'plugin_slug' => 'elementor',
						'id'          => 'elementor',
					),
				),
			),
		);
		Hestia_About_Page::init( apply_filters( 'hestia_about_page_array', $config ) );
	}

	/**
	 * Enqueue Customizer Script.
	 */
	public function enqueue_customizer_script() {
		wp_enqueue_script(
			'hestia-customizer-preview', get_template_directory_uri() . '/assets/js/admin/customizer.js',
			array(
				'jquery',
				'customize-preview',
			), HESTIA_VERSION, true
		);
	}

	/**
	 * Enqueue customizer controls script.
	 */
	public function enqueue_customizer_controls() {
		wp_enqueue_style( 'hestia-customizer-style', get_template_directory_uri() . '/assets/css/customizer-style.css', array(), HESTIA_VERSION );
		wp_enqueue_script(
			'hestia_customize_controls', get_template_directory_uri() . '/assets/js/admin/customizer-controls.js',
			array(
				'jquery',
				'customize-preview',
			), HESTIA_VERSION, true
		);
	}

	/**
	 * Add inline style for editor.
	 *
	 * @param string $init Setup TinyMCE.
	 *
	 * @return mixed
	 */
	function editor_inline_style( $init ) {

		$accent_color  = get_theme_mod( 'accent_color', apply_filters( 'hestia_accent_color_default', '#e91e63' ) );
		$headings_font = get_theme_mod( 'hestia_headings_font' );
		$body_font     = get_theme_mod( 'hestia_body_font' );

		$custom_css = '';

		// Check if accent color is exists.
		if ( ! empty( $accent_color ) ) {
			$custom_css .= 'body.mce-content-body a { color: ' . esc_attr( $accent_color ) . '; }';
		}

		// Check if font family for body exists.
		if ( ! empty( $body_font ) ) {
			$custom_css .= 'body, p { font-family: ' . esc_attr( $body_font ) . ' !important; }';
		}

		// Check if font family for headings exists.
		if ( ! empty( $headings_font ) ) {
			$custom_css .= 'h1, h2, h3, h4, h5, h6 { font-family: ' . esc_attr( $headings_font ) . ' !important; }';
		}

		?>

		<script type="text/javascript">
			function hestiaEditorInlineStyle( ed ) {
				ed.onInit.add( function () {
					<?php
					// Added font family for body in editor.
					if ( ! empty( $body_font ) ) {
						?>
					tinymce.activeEditor.dom.loadCSS( 'https://fonts.googleapis.com/css?family=<?php echo esc_attr( $body_font ); ?>' );
						<?php
					}

					// Added font family for headings in editor.
					if ( ! empty( $headings_font ) ) {
						?>
					tinymce.activeEditor.dom.loadCSS( 'https://fonts.googleapis.com/css?family=<?php echo esc_attr( $headings_font ); ?>' );
					<?php } ?>

					// Added custom CSS in editor.
					tinyMCE.activeEditor.dom.addStyle(<?php echo json_encode( $custom_css ); ?>);
				} );
			};
		</script>

		<?php
		if ( wp_default_editor() == 'tinymce' ) {
			$init['setup'] = 'hestiaEditorInlineStyle';
		}

		return $init;
	}
}
