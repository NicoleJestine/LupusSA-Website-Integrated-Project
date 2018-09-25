<?php
/**
 * Customizer functionality for the Blog settings panel.
 *
 * @package Hestia
 * @since Hestia 1.1.10
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * A customizer control to display text in customizer.
 *
 * @since Hestia 1.1.42
 */
class Hestia_Button extends WP_Customize_Control {


	/**
	 * Control id
	 *
	 * @var string $id Control id.
	 */
	public $id = '';

	/**
	 * Button class.
	 *
	 * @var mixed|string
	 */
	public $button_class = '';

	/**
	 * Icon class.
	 *
	 * @var mixed|string
	 */
	public $icon_class = '';

	/**
	 * Button text.
	 *
	 * @var mixed|string
	 */
	public $button_text = '';

	/**
	 * Hestia_Button constructor.
	 *
	 * @param WP_Customize_Manager $manager Customizer manager.
	 * @param string               $id Control id.
	 * @param array                $args Argument.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		$this->id = $id;
	}

	/**
	 * Render content for the control.
	 *
	 * @since Hestia 1.1.42
	 */
	public function render_content() {
		if ( ! empty( $this->button_text ) ) {
			echo '<button type="button" class="button menu-shortcut ' . esc_attr( $this->button_class ) . '" tabindex="0">';
			if ( ! empty( $this->button_class ) ) {
				echo '<i class="fa ' . esc_attr( $this->icon_class ) . '" style="margin-right: 10px"></i>';
			}
				echo esc_html( $this->button_text );
			echo '</button>';
		}
	}
}
