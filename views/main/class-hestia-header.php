<?php
/**
 * Header View Manager
 *
 * @package Hestia
 */

/**
 * Class Hestia_Header_Manager
 */
class Hestia_Header extends Hestia_Abstract_Main {
	/**
	 * Add hooks for the front end.
	 */
	public function init() {
		add_action( 'hestia_do_header', array( $this, 'the_header_content' ) );
		add_action( 'hestia_do_header', array( $this, 'hidden_sidebars' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'top_bar_style' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'modify_primary_menu' ) );
	}

	/**
	 * Render the header content.
	 */
	public function the_header_content() {

		hestia_before_header_trigger();
		$this->the_top_bar();

		?>
		<nav class="navbar navbar-default navbar-fixed-top <?php echo esc_attr( $this->header_class() ); ?>">
			<?php hestia_before_header_content_trigger(); ?>
			<div class="container">
				<div class="navbar-header">
					<div class="title-logo-wrapper">
						<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"
								title="<?php bloginfo( 'name' ); ?>">
							<?php echo $this->logo(); ?></a>
					</div>
				</div>
				<?php

				$this->navbar_sidebar();

				wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container'       => 'div',
						'container_class' => 'collapse navbar-collapse',
						'container_id'    => 'main-navigation',
						'menu_class'      => 'nav navbar-nav navbar-right',
						'fallback_cb'     => 'Hestia_Bootstrap_Navwalker::fallback',
						'walker'          => new Hestia_Bootstrap_Navwalker(),
					)
				);
				hestia_before_navbar_toggle_trigger();

				if ( has_nav_menu( 'primary' ) || current_user_can( 'edit_theme_options' ) ) :
					?>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="sr-only"><?php esc_html_e( 'Toggle Navigation', 'hestia' ); ?></span>
					</button>
					<?php
				endif;
				?>
			</div>

			<?php hestia_after_header_content_trigger(); ?>
		</nav>
		<?php
		hestia_after_header_trigger();
	}

	/**
	 * Render the navigation bar Sidebar.
	 */
	public function navbar_sidebar() {
		$header_alignment = get_theme_mod( 'hestia_header_alignment', 'left' );

		if ( $header_alignment !== 'right' ) {
			return;
		}

		if ( is_active_sidebar( 'header-sidebar' ) ) {
			?>
			<div class="header-sidebar-wrapper">
				<div class="header-widgets-wrapper">
					<?php
					dynamic_sidebar( 'header-sidebar' );
					?>
				</div>
			</div>
			<?php
		}
		if ( ! is_active_sidebar( 'header-sidebar' ) && is_customize_preview() ) {
			hestia_sidebar_placeholder( 'hestia-sidebar-header', 'header-sidebar', 'no-variable-width header-sidebar-wrapper' );
		}
	}

	/**
	 * Display the hidden sidebars to enable the customizer panels.
	 */
	public function hidden_sidebars() {
		echo '<div style="display: none">';
		if ( is_customize_preview() ) {
			dynamic_sidebar( 'sidebar-top-bar' );
			dynamic_sidebar( 'header-sidebar' );
			dynamic_sidebar( 'subscribe-widgets' );
			dynamic_sidebar( 'sidebar-big-title' );
		}
		echo '</div>';
	}

	/**
	 * Get the header class.
	 *
	 * @return string
	 */
	private function header_class() {
		$class              = '';
		$is_nav_transparent = get_theme_mod( 'hestia_navbar_transparent', true );
		if ( get_option( 'show_on_front' ) === 'page' && is_front_page() && ! is_page_template() && $is_nav_transparent ) {
			$class = 'navbar-color-on-scroll navbar-transparent';
		}

		$header_alignment = get_theme_mod( 'hestia_header_alignment', 'left' );
		if ( ! empty( $header_alignment ) ) {
			$class .= ' hestia_' . $header_alignment;
		}

		$has_full_screen_menu = get_theme_mod( 'hestia_full_screen_menu', false );
		if ( (bool) $has_full_screen_menu === true ) {
			$class .= ' full-screen-menu';
		}

		$is_top_bar_hidden = get_theme_mod( 'hestia_top_bar_hide', true );
		if ( (bool) $is_top_bar_hidden === false ) {
			$class .= ' header-with-topbar';
		}

		if ( ! is_home() && ! is_front_page() ) {
			$class .= ' navbar-not-transparent';
		}

		return $class;
	}

	/**
	 * Display your custom logo if present.
	 *
	 * @since Hestia 1.0
	 */
	private function logo() {
		if ( get_theme_mod( 'custom_logo' ) ) {
			$logo          = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
			$alt_attribute = get_post_meta( get_theme_mod( 'custom_logo' ), '_wp_attachment_image_alt', true );
			if ( empty( $alt_attribute ) ) {
				$alt_attribute = get_bloginfo( 'name' );
			}
			$logo = '<img src="' . esc_url( $logo[0] ) . '" alt="' . esc_attr( $alt_attribute ) . '">';
		} else {
			$logo = '<p>' . get_bloginfo( 'name' ) . '</p>';
		}

		return $logo;
	}

	/**
	 * The top bar markup.
	 */
	private function the_top_bar() {
		$top_bar_is_hidden = get_theme_mod( 'hestia_top_bar_hide', true );

		if ( (bool) $top_bar_is_hidden === true ) {
			return;
		}
		$top_bar_wrapper_class = $this->get_top_bar_wrapper_class();
		echo '<div class="' . esc_attr( $top_bar_wrapper_class ) . '">';
		$this->header_top_bar();
		echo '</div>';
	}

	/**
	 * Get top bar wrapper classes.
	 */
	private function get_top_bar_wrapper_class() {
		$top_bar_class   = array( 'hestia-top-bar' );
		$has_placeholder = $this->top_bar_has_placeholder();

		if ( $has_placeholder ) {
			array_push( $top_bar_class, 'placeholder' );
		}
		return implode( ' ', $top_bar_class );
	}

	/**
	 * Check if placeholder should be visible.
	 *
	 * @return bool
	 */
	private function top_bar_has_placeholder() {
		return is_customize_preview() && current_user_can( 'edit_theme_options' ) && ! has_nav_menu( 'top-bar-menu' ) && ! is_active_sidebar( 'sidebar-top-bar' );
	}

	/**
	 * Display placeholder on top bar.
	 */
	private function maybe_render_placeholder() {
		if ( ! $this->top_bar_has_placeholder() ) {
			return;
		}
		echo '<div class="' . esc_attr( $this->top_bar_sidebar_class() ) . '">';
		hestia_display_customizer_shortcut( 'hestia-top-bar-widget' );
		echo esc_html__( 'This sidebar is active but empty. In order to use this layout, please add widgets in the sidebar', 'hestia' );
		echo '</div>';
	}

	/**
	 * Function to display header top bar.
	 *
	 * @since 1.1.40
	 *
	 * @access public
	 */
	public function header_top_bar() {
		?>
		<div class="container">
			<div class="row">
				<?php
				/**
				 * Call for sidebar
				 */
				$this->maybe_render_placeholder();
				if ( is_active_sidebar( 'sidebar-top-bar' ) ) {
					?>
					<div class="<?php echo esc_attr( $this->top_bar_sidebar_class() ); ?>">
						<?php dynamic_sidebar( 'sidebar-top-bar' ); ?>
					</div>
					<?php
				}
				?>
				<div class="<?php echo esc_attr( $this->top_bar_menu_class() ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'top-bar-menu',
							'depth'          => 1,
							'container'      => 'div',
							'container_id'   => 'top-bar-navigation',
							'menu_class'     => 'nav top-bar-nav',
							'fallback_cb'    => 'Hestia_Bootstrap_Navwalker::fallback',
							'walker'         => new Hestia_Bootstrap_Navwalker(),
						)
					);
					?>
				</div>
			</div><!-- /.row -->
		</div><!-- /.container -->
		<?php
	}



	/**
	 * Get the top bar sidebar class.
	 *
	 * @return string top bar sidebar class.
	 */
	private function top_bar_sidebar_class() {
		$top_bar_alignment = get_theme_mod( 'hestia_top_bar_alignment', apply_filters( 'hestia_top_bar_alignment_default', 'right' ) );
		$sidebar_class     = 'pull-left';
		if ( ! empty( $top_bar_alignment ) && $top_bar_alignment === 'left' ) {
			$sidebar_class = 'pull-right';
		}
		$sidebar_class .= ' col-md-6';
		if ( ! has_nav_menu( 'top-bar-menu' ) && ! current_user_can( 'edit_theme_options' ) ) {
			$sidebar_class .= ' col-md-12';
		}

		return $sidebar_class;
	}

	/**
	 * Get the top bar menu class.
	 *
	 * @return string top bar menu class.
	 */
	private function top_bar_menu_class() {
		$top_bar_alignment = get_theme_mod( 'hestia_top_bar_alignment', apply_filters( 'hestia_top_bar_alignment_default', 'right' ) );
		$menu_class        = 'pull-right';
		if ( ! empty( $top_bar_alignment ) && $top_bar_alignment === 'left' ) {
			$menu_class = 'pull-left';
		}
		if ( is_active_sidebar( 'sidebar-top-bar' ) || $this->top_bar_has_placeholder() ) {
			$menu_class .= ' col-md-6 top-widgets-placeholder';
		} else {
			$menu_class .= ' col-md-12';
		}

		return $menu_class;
	}

	/**
	 * Get top bar style from customizer controls.
	 *
	 * @since 1.1.48
	 */
	private function top_bar_css() {
		$custom_css = '';

		$hestia_top_bar_background = get_theme_mod( 'hestia_top_bar_background_color', '#363537' );
		if ( ! empty( $hestia_top_bar_background ) ) {
			$custom_css .= '.hestia-top-bar, .hestia-top-bar .widget.widget_shopping_cart .cart_list {
			background-color: ' . esc_html( $hestia_top_bar_background ) . '
		}
		.hestia-top-bar .widget .label-floating input[type=search]:-webkit-autofill {
			-webkit-box-shadow: inset 0 0 0px 9999px ' . esc_html( $hestia_top_bar_background ) . '
		}';
		}

		$hestia_top_bar_text_color = get_theme_mod( 'hestia_top_bar_text_color', '#ffffff' );
		if ( ! empty( $hestia_top_bar_background ) ) {
			$custom_css .= '.hestia-top-bar, .hestia-top-bar .widget .label-floating input[type=search], .hestia-top-bar .widget.widget_search form.form-group:before, .hestia-top-bar .widget.widget_product_search form.form-group:before, .hestia-top-bar .widget.widget_shopping_cart:before {
			color: ' . esc_html( $hestia_top_bar_text_color ) . '
		} 
		.hestia-top-bar .widget .label-floating input[type=search]{
			-webkit-text-fill-color:' . esc_html( $hestia_top_bar_text_color ) . ' !important 
		}';
		}

		$hestia_top_bar_link_color = get_theme_mod( 'hestia_top_bar_link_color', '#ffffff' );
		if ( ! empty( $hestia_top_bar_link_color ) ) {
			$custom_css .= '.hestia-top-bar a, .hestia-top-bar .top-bar-nav li a {
			color: ' . esc_html( $hestia_top_bar_link_color ) . '
		}';
		}

		$hestia_top_bar_link_color_hover = get_theme_mod( 'hestia_top_bar_link_color_hover', '#eeeeee' );
		if ( ! empty( $hestia_top_bar_link_color_hover ) ) {
			$custom_css .= '.hestia-top-bar a:hover, .hestia-top-bar .top-bar-nav li a:hover {
			color: ' . esc_html( $hestia_top_bar_link_color_hover ) . '
		}';
		}

		return $custom_css;
	}

	/**
	 * Add top bar style.
	 */
	public function top_bar_style() {
		wp_add_inline_style( 'hestia_style', $this->top_bar_css() );
	}

	/**
	 * Filter Primary Navigation to add navigation cart and search.
	 *
	 * @param string $markup the markup for the navigation addons.
	 * @access public
	 * @return mixed
	 */
	public function modify_primary_menu( $markup ) {
		if ( 'primary' !== $markup['theme_location'] ) {
			return $markup;
		}
		$markup['items_wrap'] = $this->display_filtered_navigation();
		return $markup;
	}

	/**
	 * Display navigation.
	 *
	 * @return string
	 */
	private function display_filtered_navigation() {
		$nav  = '<ul id="%1$s" class="%2$s">';
		$nav .= '%3$s';
		$nav .= apply_filters( 'hestia_after_primary_navigation_addons', $this->search_in_menu() );
		$nav .= '</ul>';
		return $nav;
	}

	/**
	 * Display search form in menu.
	 */
	private function search_in_menu() {
		$search_in_menu = get_theme_mod( 'hestia_search_in_menu', false );

		if ( (bool) $search_in_menu === false ) {
			return false;
		}

		$form = '
		<li class="hestia-search-in-menu">
			<form role="search" method="get" class="hestia-search-in-nav" action="' . esc_url( home_url( '/' ) ) . '">
				<div class="hestia-nav-search">
					<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'hestia' ) . '</span>
					<span class="search-field-wrapper">
					
					<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'hestia' ) . '" value="' . get_search_query() . '" name="s" />
					</span>
					<span class="search-submit-wrapper">
					<button type="submit" class="search-submit hestia-search-submit" ><i class="fa fa-search"></i></button>
					</span>
				</div>
			</form>
			<div class="hestia-toggle-search">
				<i class="fa fa-search"></i>
			</div>
		</li>';

		return $form;
	}
}
