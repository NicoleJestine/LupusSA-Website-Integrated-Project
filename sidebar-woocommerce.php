<?php
/**
 * The Sidebar for WooCommerce containing the main widget areas.
 *
 * @package Hestia
 * @since Hestia 1.0
 * @modified 1.1.30
 */

$hestia_sidebar_layout = get_theme_mod( 'hestia_page_sidebar_layout', 'full-width' );

if ( is_active_sidebar( 'sidebar-woocommerce' ) ) { ?>
	<div class="col-md-3 shop-sidebar-wrapper">
		<aside id="secondary" class="shop-sidebar" role="complementary">
			<?php dynamic_sidebar( 'sidebar-woocommerce' ); ?>
		</aside><!-- .sidebar .widget-area -->
	</div>
	<?php
} elseif ( is_customize_preview() ) {
	hestia_sidebar_placeholder( 'col-md-3 shop-sidebar-wrapper col-md-offset-1', 'sidebar-woocommerce' );
} ?>
