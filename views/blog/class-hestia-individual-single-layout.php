<?php
/**
 * Feature to control layout on individual posts and pages.
 *
 * @package hestia
 * @since 1.1.58
 */

/**
 * Class Hestia_Individual_Single_Layout
 */
class Hestia_Individual_Single_Layout extends Hestia_Abstract_Main {

	/**
	 * Hestia_Single_Layout constructor.
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
		add_filter( 'theme_mod_hestia_page_sidebar_layout', array( $this, 'get_individual_layout' ) );
		add_filter( 'theme_mod_hestia_blog_sidebar_layout', array( $this, 'get_individual_layout' ) );
	}

	/**
	 * Register meta box to control layout on pages and posts.
	 *
	 * @since 1.1.58
	 */
	public function add_meta_box() {
		global $post;
		if ( empty( $post ) ) {
			return;
		}

		$page_template = get_post_meta( $post->ID, '_wp_page_template', true );

		// Register the metabox only for the default and page with sidebar templates.
		$allowed_templates = array(
			'default',
			'page-templates/template-page-sidebar.php',
		);

		if ( ! in_array( $page_template, $allowed_templates ) && ! empty( $page_template ) ) {
			return;
		}
		add_meta_box(
			'hestia-individual-layout', esc_html__( 'Layout', 'hestia' ), array(
				$this,
				'meta_box_content',
			), array(
				'post',
				'page',
			), 'side', 'low'
		);
	}


	/**
	 * The metabox content.
	 *
	 * @since 1.1.58
	 */
	public function meta_box_content() {
		// $post is already set, and contains an object: the WordPress post
		global $post;
		$values   = get_post_custom( $post->ID );
		$selected = isset( $values['hestia_layout_select'] ) ? esc_attr( $values['hestia_layout_select'][0] ) : '';
		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'hestia_individual_layout_nonce', 'individual_layout_nonce' );
		?>
		<p>
			<select name="hestia_layout_select" id="hestia_layout_select">
				<option value="default" <?php selected( $selected, 'default' ); ?>><?php echo esc_html__( 'Default', 'hestia' ); ?></option>
				<option value="full-width" <?php selected( $selected, 'full-width' ); ?>><?php echo esc_html__( 'Full Width', 'hestia' ); ?></option>
				<option value="sidebar-left" <?php selected( $selected, 'sidebar-left' ); ?>><?php echo esc_html__( 'Left Sidebar', 'hestia' ); ?></option>
				<option value="sidebar-right" <?php selected( $selected, 'sidebar-right' ); ?>><?php echo esc_html__( 'Right Sidebar', 'hestia' ); ?></option>
			</select>
		</p>
		<?php
	}


	/**
	 * Save metabox data.
	 *
	 * @param string $post_id Post id.
	 *
	 * @since 1.1.58
	 */
	public function save_meta_box( $post_id ) {
		// Bail if we're doing an auto save
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// if our nonce isn't there, or we can't verify it, bail
		if ( ! isset( $_POST['individual_layout_nonce'] ) || ! wp_verify_nonce( $_POST['individual_layout_nonce'], 'hestia_individual_layout_nonce' ) ) {
			return;
		}
		// if our current user can't edit this post, bail
		if ( ! current_user_can( 'edit_post' ) ) {
			return;
		}
		if ( isset( $_POST['hestia_layout_select'] ) ) {

			$valid = array(
				'default',
				'full-width',
				'sidebar-left',
				'sidebar-right',
			);

			$value = wp_unslash( $_POST['hestia_layout_select'] );

			update_post_meta( $post_id, 'hestia_layout_select', in_array( $value, $valid ) ? $value : 'default' );
		}
	}

	/**
	 * Hook into the theme mod to change it as we see fit.
	 *
	 * @param string $layout the single post / page layout.
	 *
	 * @return string $layout
	 */
	public function get_individual_layout( $layout ) {
		$individual_layout = get_post_meta( get_the_ID(), 'hestia_layout_select', true );

		if ( empty( $individual_layout ) ) {
			return $layout;
		}

		if ( $individual_layout === 'default' ) {
			return $layout;
		}

		return $individual_layout;
	}
}
