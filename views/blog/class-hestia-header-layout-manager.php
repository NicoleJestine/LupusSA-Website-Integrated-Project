<?php
/**
 * Hestia Header Layout Manager.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Header_Layout_Manager
 */
class Hestia_Header_Layout_Manager extends Hestia_Abstract_Main {
	/**
	 * Init layout manager.
	 */
	public function init() {

		add_filter( 'get_the_archive_title', array( $this, 'filter_archive_title' ) );

		// Single Post
		add_action( 'hestia_before_single_post_wrapper', array( $this, 'post_header' ) );
		add_action( 'hestia_before_single_post_content', array( $this, 'post_before_content' ) );

		// Page
		add_action( 'hestia_before_single_page_wrapper', array( $this, 'page_header' ) );
		add_action( 'hestia_before_page_content', array( $this, 'page_before_content' ) );

		// Index
		add_action( 'hestia_before_index_wrapper', array( $this, 'generic_header' ) );
		// Search
		add_action( 'hestia_before_search_wrapper', array( $this, 'generic_header' ) );
		// Attachment
		add_action( 'hestia_before_attachment_wrapper', array( $this, 'generic_header' ) );
		// Archive
		add_action( 'hestia_before_archive_content', array( $this, 'generic_header' ) );

		add_action( 'hestia_before_woocommerce_wrapper', array( $this, 'generic_header' ) );

	}


	/**
	 * Remove "Category:", "Tag:", "Author:" from the archive title.
	 *
	 * @param string $title Archive title.
	 */
	public function filter_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_year() ) {
			$title = get_the_date( 'Y' );
		} elseif ( is_month() ) {
			$title = get_the_date( 'F Y' );
		} elseif ( is_day() ) {
			$title = get_the_date( 'F j, Y' );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		}
		return $title;
	}

	/**
	 * The single post header.
	 */
	public function post_header() {
		$layout = get_theme_mod( 'hestia_header_layout', 'default' );
		if ( $layout === 'classic-blog' ) {
			return;
		}

		$this->display_header( $layout, 'post' );
	}

	/**
	 * The single page header.
	 */
	public function page_header() {
		$layout = get_theme_mod( 'hestia_header_layout', 'default' );

		if ( class_exists( 'WooCommerce' ) && ( is_cart() || is_checkout() || is_shop() ) ) {
			$layout = 'default';
		}

		if ( $layout === 'classic-blog' ) {
			return;
		}
		$this->display_header( $layout, 'page' );
	}

	/**
	 * Display page header on single page and on full width page template.
	 *
	 * @param string $layout header layout.
	 * @param string $type post / page / other.
	 */
	private function display_header( $layout, $type ) {
		echo '<div id="primary" class="' . esc_attr( $this->boxed_page_layout_class() ) . ' page-header header-small"
				' . $this->parallax_attribute() . '>';
		if ( $type === 'post' && $layout !== 'no-content' ) {
			$this->render_header();
		}

		if ( $type === 'page' && $layout === 'default' ) {
			$this->render_header();
		}

		if ( $type === 'generic' ) {
			$this->render_header();
		}

			$this->render_header_background();
		echo '</div>';
	}

	/**
	 * Determine whether or not to add parallax attribute on header.
	 */
	private function parallax_attribute() {
		if ( class_exists( 'WooCommerce' ) && is_product() ) {
			return '';
		}
		return 'data-parallax="active"';
	}

	/**
	 * Display header content based on layout.
	 */
	private function render_header() {
		$layout = get_theme_mod( 'hestia_header_layout', 'default' );

		if ( ! is_single() && ! is_page() ) {
			$layout = 'default';
		}

		if ( is_attachment() ) {
			$layout = 'default';
		}

		if ( $this->is_shop_without_header_content() ) {
			$layout = 'default';
		}

		if ( $layout === 'default' && ! $this->is_shop_without_header_content() ) {
			?>
			<div class="container">
			<div class="row">
			<div class="col-md-10 col-md-offset-1 text-center">
			<?php
		}

		$this->header_content( $layout );
		$this->maybe_render_post_meta( $layout );
		if ( $layout === 'classic-blog' ) {
			the_post_thumbnail();
		}
		if ( $layout === 'default' && ! $this->is_shop_without_header_content() ) {
			?>
			</div>
			</div>
			</div>
			<?php
		}
	}

	/**
	 * Header content display.
	 *
	 * @param string $header_layout the header layout.
	 */
	private function header_content( $header_layout ) {
		$title_class = 'hestia-title';

		if ( $header_layout !== 'default' ) {
			$title_class .= ' title-in-content';
		}
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_shop() ) {
				echo '<h1 class="' . esc_attr( $title_class ) . '">';
				woocommerce_page_title();
				echo '</h1>';

				return;
			}
			if ( is_product() || is_cart() || is_checkout() ) {
				return;
			}
		}
		if ( is_archive() ) {
			the_archive_title( '<h1 class="hestia-title">', '</h1>' );
			the_archive_description( '<h5 class="description">', '</h5>' );

			return;
		}
		if ( is_search() ) {
			echo '<h1 class="' . esc_attr( $title_class ) . '">';
			/* translators: search result */
			printf( esc_html__( 'Search Results for: %s', 'hestia' ), get_search_query() );
			echo '</h1>';

			return;
		}
		if ( is_front_page() && ( get_option( 'show_on_front' ) === 'posts' ) ) {
			echo '<h1 class="' . esc_attr( $title_class ) . '">';
			echo get_bloginfo( 'description' );
			echo '</h1>';

			return;
		}
		if ( is_page() ) {
			echo '<h1 class="' . esc_attr( $title_class ) . '">';
			single_post_title();
			echo '</h1>';

			return;
		}
			echo '<h1 class="' . esc_attr( $title_class ) . ' entry-title">';
			single_post_title();
			echo '</h1>';

			return;
	}

	/**
	 * Check if post meta should be displayed.
	 *
	 * @param string $header_layout the header layout.
	 */
	private function maybe_render_post_meta( $header_layout ) {
		if ( ! is_single() ) {
			return;
		}

		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_product() ) {
				return;
			}
		}

		global $post;
		$author_id        = $post->post_author;
		$author_name      = get_the_author_meta( 'display_name', $author_id );
		$author_posts_url = get_author_posts_url( get_the_author_meta( 'ID', $author_id ) );

		if ( $header_layout === 'default' ) {
			echo '<h4 class="author">';
		} else {
			echo '<p class="author meta-in-content">';
		}

		echo apply_filters(
			'hestia_single_post_meta', sprintf(
				/* translators: %1$s is Author name wrapped, %2$s is Date*/
				esc_html__( 'Published by %1$s on %2$s', 'hestia' ),
				/* translators: %1$s is Author name, %2$s is Author link*/
				sprintf(
					'<a href="%2$s" class="vcard author"><strong class="fn">%1$s</strong></a>',
					esc_html( $author_name ),
					esc_url( $author_posts_url )
				),
				/* translators: %s is Date */
				sprintf(
					'<time class="date updated published" datetime="%2$s">%1$s</time>',
					esc_html( get_the_time( get_option( 'date_format' ) ) ), esc_html( get_the_date( DATE_W3C ) )
				)
			)
		);
		if ( $header_layout === 'default' ) {
			echo '</h4>';
		} else {
			echo '</p>';
		}
	}

	/**
	 * Get the sidebar layout.
	 *
	 * @return mixed|string
	 */
	public function get_page_sidebar_layout() {
		$sidebar_layout    = get_theme_mod( 'hestia_blog_sidebar_layout', 'sidebar-right' );
		$individual_layout = get_post_meta( get_the_ID(), 'hestia_layout_select', true );
		if ( ! empty( $individual_layout ) && $individual_layout !== 'default' ) {
			$sidebar_layout = $individual_layout;
		}

		return $sidebar_layout;
	}

	/**
	 * Get the sidebar layout.
	 *
	 * @return mixed|string
	 */
	public function get_blog_sidebar_layout() {
		$sidebar_layout    = get_theme_mod( 'hestia_page_sidebar_layout', 'full-width' );
		$individual_layout = get_post_meta( get_the_ID(), 'hestia_layout_select', true );
		if ( ! empty( $individual_layout ) && $individual_layout !== 'default' ) {
			$sidebar_layout = $individual_layout;
		}

		return $sidebar_layout;
	}


	/**
	 * Add the class to account for boxed page layout.
	 *
	 * @return string
	 */
	private function boxed_page_layout_class() {
		$layout = get_theme_mod( 'hestia_general_layout', 1 );

		if ( isset( $layout ) && $layout == 1 ) {
			return 'boxed-layout-header';
		}

		return '';
	}


	/**
	 * Render the header background div.
	 */
	private function render_header_background() {
		$background_image            = $this->get_page_background();
		$customizer_background_image = get_background_image();

		$header_filter_div = '<div class="header-filter';

		/* Header Image */
		if ( ! empty( $background_image ) ) {
			$header_filter_div .= '" style="background-image: url(' . esc_url( $background_image ) . ');"';
			/* Gradient Color */
		} elseif ( empty( $customizer_background_image ) ) {
			$header_filter_div .= ' header-filter-gradient"';
			/* Background Image */
		} else {
			$header_filter_div .= '"';
		}
		$header_filter_div .= '></div>';

		echo apply_filters( 'hestia_header_wrapper_background_filter', $header_filter_div );

	}


	/**
	 *  Handle Pages and Posts Header image.
	 *  Single Product: Product Category Image > Header Image > Gradient
	 *  Product Category: Product Category Image > Header Image > Gradient
	 *  Shop Page: Shop Page Featured Image > Header Image > Gradient
	 *  Blog Page: Page Featured Image > Header Image > Gradient
	 *  Single Post: Featured Image > Gradient
	 */
	private function get_page_background() {
		// Default header image
		$thumbnail                 = get_header_image();
		$use_header_image_sitewide = get_theme_mod( 'hestia_header_image_sitewide', false );

		// If the option to use Header Image Sitewide is enabled, return header image and exit function.
		if ( (bool) $use_header_image_sitewide === true ) {
			return esc_url( apply_filters( 'hestia_header_image_filter', $thumbnail ) );
		}

		$shop_id = get_option( 'woocommerce_shop_page_id' );
		if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {

			// Single product page
			if ( is_product() ) {
				$terms = get_the_terms( get_queried_object_id(), 'product_cat' );
				// If product has categories
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $term ) {
						if ( ! empty( $term->term_id ) ) {
							$category_thumbnail = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
						}
						// Get product category's image
						if ( ! empty( $category_thumbnail ) ) {
							$thumb_tmp = wp_get_attachment_url( $category_thumbnail );
						} // End if().
					}
				}
			} elseif ( is_product_category() ) {
				global $wp_query;
				$category = $wp_query->get_queried_object();
				if ( ! empty( $category->term_id ) ) {
					$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
				}
				if ( ! empty( $thumbnail_id ) ) {
					// Get category featured image
					$thumb_tmp = wp_get_attachment_url( $thumbnail_id );
				} else {
					if ( ! empty( $shop_id ) ) {
						// Get shop page featured image
						$thumb_tmp = get_the_post_thumbnail_url( $shop_id );
						if ( ! empty( $thumb_tmp ) ) {
							$thumbnail = $thumb_tmp;
						}
					}
				}
			} else {
				// Shop page
				if ( ! empty( $shop_id ) ) {
					// Get shop page featured image
					$thumb_tmp = get_the_post_thumbnail_url( $shop_id );
				}
			}// End if().
		} else {
			// Get featured image
			if ( is_home() ) {
				$page_for_posts_id = get_option( 'page_for_posts' );
				if ( ! empty( $page_for_posts_id ) ) {
					$thumb_tmp = get_the_post_thumbnail_url( $page_for_posts_id );
				}
			} else {
				$thumb_tmp = get_the_post_thumbnail_url();
			}
		}// End if().

		if ( ! empty( $thumb_tmp ) ) {
			$thumbnail = $thumb_tmp;
		}

		return esc_url( apply_filters( 'hestia_header_image_filter', $thumbnail ) );
	}

	/**
	 * Single page before content.
	 */
	public function page_before_content() {

		$layout = get_theme_mod( 'hestia_header_layout', 'default' );

		if ( $layout === 'default' ) {
			return;
		}
		if ( class_exists( 'WooCommerce' ) && ( is_product() || is_cart() || is_checkout() ) ) {
			return;
		}
		$this->render_header();
	}


	/**
	 * Single post before content.
	 */
	public function post_before_content() {
		$layout = get_theme_mod( 'hestia_header_layout', 'default' );

		if ( $layout === 'default' ) {
			return;
		}
		$this->render_header();
	}

	/**
	 * Generic header used for index | search | attachment | WooCommerce.
	 */
	public function generic_header() {
		$this->display_header( 'default', 'generic' );
	}

	/**
	 * Check if page is WooCommerce without header content [cart/checkout/shop]
	 *
	 * @return bool
	 */
	private function is_shop_without_header_content() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}
		if ( is_cart() || is_checkout() ) {
			return true;
		}
		return false;
	}
}
