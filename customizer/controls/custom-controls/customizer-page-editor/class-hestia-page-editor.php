<?php
/**
 * Page editor control
 *
 * @package Hestia
 * @since Hestia 1.1.3
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Class to create a custom tags control
 */
class Hestia_Page_Editor extends WP_Customize_Control {

	/**
	 * Flag to include sync scripts if needed
	 *
	 * @var bool|mixed
	 */
	private $needsync = false;

	/**
	 * Hestia_Page_Editor constructor.
	 *
	 * @param WP_Customize_Manager $manager Manager.
	 * @param string               $id Id.
	 * @param array                $args Constructor args.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		if ( ! empty( $args['needsync'] ) ) {
			$this->needsync = $args['needsync'];
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @since   1.1.0
	 * @access  public
	 * @updated Changed wp_enqueue_scripts order and dependencies.
	 */
	public function enqueue() {
		wp_enqueue_style( 'hestia_text_editor_css', get_template_directory_uri() . '/inc/customizer/controls/custom-controls/customizer-page-editor/css/hestia-page-editor.css', array(), HESTIA_VERSION );
		wp_enqueue_script(
			'hestia_text_editor', get_template_directory_uri() . '/inc/customizer/controls/custom-controls/customizer-page-editor/js/hestia-text-editor.js', array(
				'jquery',
				'customize-preview',
			), HESTIA_VERSION, false
		);
		if ( $this->needsync === true ) {
			wp_enqueue_script( 'hestia_controls_script', get_template_directory_uri() . '/inc/customizer/controls/custom-controls/customizer-page-editor/js/hestia-update-controls.js', array( 'jquery', 'hestia_text_editor' ), HESTIA_VERSION, true );
			wp_localize_script(
				'hestia_controls_script', 'requestpost', array(
					'ajaxurl'           => admin_url( 'admin-ajax.php' ),
					'thumbnail_control' => 'hestia_feature_thumbnail', // name of image control that needs sync
					'editor_control'    => 'hestia_page_editor', // name of control (theme_mod) that needs sync
					'thumbnail_label'   => esc_html__( 'About background', 'hestia' ), // name of thumbnail control
				)
			);
		}
	}

	/**
	 * Render the content on the theme customizer page
	 */
	public function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_textarea( $this->value() ); ?>" id="<?php echo esc_attr( $this->id ); ?>" class="editorfield">
			<button data-editor-id="<?php echo esc_attr( $this->id ); ?>" class="button edit-content-button"><?php _e( '(Edit)', 'hestia' ); ?></button>
		</label>
		<?php
	}
}
