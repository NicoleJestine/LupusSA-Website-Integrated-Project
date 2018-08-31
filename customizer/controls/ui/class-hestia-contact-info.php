<?php
/**
 * A custom text control for Contact info.
 *
 * @package Hestia
 * @since Hestia 1.1.10
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * A custom text control for Contact info.
 *
 * @since Hestia 1.0
 */
class Hestia_Contact_Info extends WP_Customize_Control {

	/**
	 * Enqueue function.
	 */
	public function enqueue() {
		Hestia_Plugin_Install_Helper::instance()->enqueue_scripts();
	}

	/**
	 * Render content for the control.
	 *
	 * @since Hestia 1.0
	 */
	public function render_content() {
		if ( defined( 'PIRATE_FORMS_VERSION' ) ) {
			printf(
				/* translators: %s is Path in plugin wrapped */
				esc_html__( 'You should be able to see the form on your front-page. You can configure settings from %s, in your WordPress dashboard.', 'hestia' ),
				/* translators: %s is Path in plugin*/
				sprintf( '<a href="%s" target="_blank">%s</a>', admin_url( 'admin.php?page=pirateforms-admin' ), esc_html__( 'Settings > Pirate Forms', 'hestia' ) )
			);
		} else {
			printf(
				/* translators: %1$s is Plugin name */
				esc_html__( 'In order to add a contact form to this section, you need to install the %s plugin.', 'hestia' ),
				esc_html( 'Pirate Forms' )
			);
			echo $this->create_plugin_install_button( 'pirate-forms' );
		}
	}

	/**
	 * Create plugin install button.
	 *
	 * @param string $slug plugin slug.
	 *
	 * @return bool
	 */
	public function create_plugin_install_button( $slug ) {
		return Hestia_Plugin_Install_Helper::instance()->get_button_html( $slug );
	}
}
