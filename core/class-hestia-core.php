<?php
/**
 * The file that defines the core theme class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themeisle.com
 * @package    Hestia
 * @subpackage Hestia/core
 */

/**
 * The core theme class.
 *
 * This is used to define admin-specific hooks, and
 * public-facing site hooks.
 *
 * @package    Hestia
 * @author     Themeisle <friends@themeisle.com>
 */
class Hestia_Core {

	/**
	 * Features that will be loaded.
	 *
	 * @access   protected
	 * @var array $features_to_load Features that will be loaded.
	 */
	protected $features_to_load;

	/**
	 * Define the core functionality of the theme.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, addons, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->maybe_load_addons();
		$this->define_hooks();
		$this->define_features();
		$this->prepare_features();
	}

	/**
	 * Define the features that will be loaded.
	 */
	private function define_features() {
		$this->features_to_load = apply_filters(
			'hestia_filter_main_features', array(
				'appearance-controls',
				'tweaks',
				'customizer-page-editor-helper',
				'customizer-main',
				'customizer-notices',
				'header-controls',
				'header',
				'footer',
				'colors',
				'color-controls',
				'general-controls',
				'big-title-section',
				'big-title-controls',
				'about-section',
				'about-controls',
				'shop-section',
				'shop-controls',
				'blog-section',
				'blog-section-controls',
				'contact-section',
				'contact-controls',
				'subscribe-section',
				'subscribe-controls',
				'typography-manager',
				'typography-controls',
				'inline-style-manager',
				'public-typography',
				'blog-settings-controls',
				'customizer-scroll-ui',
				'upsell-manager',
				'featured-posts',
				'authors-section',
				'individual-single-layout',
				'additional-views',
				'sidebar-layout-manager',
				'header-layout-manager',
				'elementor-compatibility',
				'beaver-builder-compatibility',
				'admin-notices-manager',
				'child-compat',
				'child-compat-customizer',
			)
		);
	}

	/**
	 * Check if addons are available and load them if necessary.
	 *
	 * @access private
	 */
	private function maybe_load_addons() {
		if ( ! class_exists( 'Hestia_Addon_Manager' ) ) {
			return;
		}
		$addon_manager = new Hestia_Addon_Manager();
		$addon_manager->init();
	}

	/**
	 * Check Features and register them.
	 *
	 * @access  private
	 */
	private function prepare_features() {
		$factory = new Hestia_Feature_Factory();
		foreach ( $this->features_to_load as $feature_name ) {
			$feature = $factory::build( $feature_name );
			if ( $feature !== null ) {
				$feature->init();
			}
		}
	}

	/**
	 * Register all of the hooks related to the functionality
	 * of the theme setup.
	 *
	 * @access   private
	 */
	private function define_hooks() {

		$plugin_admin = new Hestia_Admin();
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'customize_preview_init', array( $plugin_admin, 'enqueue_customizer_script' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $plugin_admin, 'enqueue_customizer_controls' ) );
		add_filter( 'tiny_mce_before_init', array( $plugin_admin, 'editor_inline_style' ) );
		add_filter( 'init', array( $plugin_admin, 'do_about_page' ) );

		$front_end = new Hestia_Public();
		add_filter( 'frontpage_template', array( $front_end, 'filter_front_page_template' ) );
		add_action( 'after_switch_theme', array( $front_end, 'theme_activated' ), 0 );
		add_action( 'after_setup_theme', array( $front_end, 'setup_theme' ) );
		add_action( 'widgets_init', array( $front_end, 'initialize_widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $front_end, 'enqueue_scripts' ) );
		add_action( 'elementor/frontend/before_register_styles', array( $front_end, 'enqueue_before_elementor' ) );
	}
}
