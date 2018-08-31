<?php
/**
 * Layout functions needed in global scope.
 *
 * @package Hestia
 */

if ( ! function_exists( 'hestia_wp_link_pages' ) ) {
	/**
	 * Display a custom wp_link_pages for singular view.
	 *
	 * @param array $args arguments.
	 *
	 * @since Hestia 1.0
	 * @return string
	 */
	function hestia_wp_link_pages( $args = array() ) {
		$defaults = array(
			'before'           => '<ul class="nav pagination pagination-primary">',
			'after'            => '</ul>',
			'link_before'      => '',
			'link_after'       => '',
			'next_or_number'   => 'number',
			'nextpagelink'     => esc_html__( 'Next page', 'hestia' ),
			'previouspagelink' => esc_html__( 'Previous page', 'hestia' ),
			'pagelink'         => '%',
			'echo'             => 1,
		);

		$r = wp_parse_args( $args, $defaults );
		$r = apply_filters( 'wp_link_pages_args', $r );

		global $page, $numpages, $multipage, $more, $pagenow;

		$output = '';
		if ( $multipage ) {
			if ( 'number' == $r['next_or_number'] ) {
				$output .= $r['before'];
				for ( $i = 1; $i < ( $numpages + 1 ); $i = $i + 1 ) {
					$j       = str_replace( '%', $i, $r['pagelink'] );
					$output .= ' ';
					$output .= $r['link_before'];
					if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) ) {
						$output .= _wp_link_page( $i );
					} else {
						$output .= '<span class="page-numbers current">';
					}
					$output .= $j;
					if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) ) {
						$output .= '</a>';
					} else {
						$output .= '</span>';
					}
					$output .= $r['link_after'];
				}
				$output .= $r['after'];
			} else {
				if ( $more ) {
					$output .= $r['before'];
					$i       = $page - 1;
					if ( $i && $more ) {
						$output .= _wp_link_page( $i );
						$output .= $r['link_before'] . $r['previouspagelink'] . $r['link_after'] . '</a>';
					}
					$i = $page + 1;
					if ( $i <= $numpages && $more ) {
						$output .= _wp_link_page( $i );
						$output .= $r['link_before'] . $r['nextpagelink'] . $r['link_after'] . '</a>';
					}
					$output .= $r['after'];
				}
			}// End if().
		}// End if().

		if ( $r['echo'] ) {
			echo wp_kses(
				$output, array(
					'div'  => array(
						'class' => array(),
						'id'    => array(),
					),
					'ul'   => array(
						'class' => array(),
					),
					'a'    => array(
						'href' => array(),
					),
					'li'   => array(),
					'span' => array(
						'class' => array(),
					),
				)
			);
		}

		return $output;
	}
}

if ( ! function_exists( 'hestia_comments_template' ) ) {
	/**
	 * Custom list of comments for the theme.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_comments_template() {
		if ( is_user_logged_in() ) {
			$current_user = get_avatar( wp_get_current_user(), 64 );
		} else {
			$current_user = '<img src="' . get_template_directory_uri() . '/assets/img/placeholder.jpg" height="64" width="64"/>';
		}

		$args = array(
			'class_form'         => 'form media-body',
			'class_submit'       => 'btn btn-primary pull-right',
			'title_reply_before' => '<h3 class="hestia-title text-center">',
			'title_reply_after'  => '</h3> <span class="pull-left author"> <div class="avatar">' . $current_user . '</div> </span>',
			'must_log_in'        => '<p class="must-log-in">' .
									sprintf(
										wp_kses(
											/* translators: %s is Link to login */
											__( 'You must be <a href="%s">logged in</a> to post a comment.', 'hestia' ), array(
												'a' => array(
													'href' => array(),
												),
											)
										), esc_url( wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) )
									) . '</p>',
			'comment_field'      => '<div class="form-group label-floating is-empty"> <label class="control-label">' . esc_html__( 'What\'s on your mind?', 'hestia' ) . '</label><textarea id="comment" name="comment" class="form-control" rows="6" aria-required="true"></textarea><span class="hestia-input"></span> </div>',
		);

		return $args;
	}
}

if ( ! function_exists( 'hestia_comments_list' ) ) {
	/**
	 * Custom list of comments for the theme.
	 *
	 * @since Hestia 1.0
	 *
	 * @param string  $comment comment.
	 * @param array   $args    arguments.
	 * @param integer $depth   depth.
	 */
	function hestia_comments_list( $comment, $args, $depth ) {
		?>
		<div <?php comment_class( empty( $args['has_children'] ) ? 'media' : 'parent media' ); ?>
				id="comment-<?php comment_ID(); ?>">
			<?php if ( $args['type'] != 'pings' ) : ?>
				<a class="pull-left" href="<?php echo esc_url( get_comment_author_url( $comment ) ); ?> ">
					<div class="comment-author avatar vcard">
						<?php
						if ( $args['avatar_size'] != 0 ) {
							echo get_avatar( $comment, 64 );
						}
						?>
					</div>
				</a>
			<?php endif; ?>
			<div class="media-body">
				<h4 class="media-heading">
					<?php echo get_comment_author_link(); ?>
					<small>
						<?php
						printf(
							/* translators: %1$s is Date, %2$s is Time */
							esc_html__( '&#183; %1$s at %2$s', 'hestia' ),
							get_comment_date(),
							get_comment_time()
						);
						edit_comment_link( esc_html__( '(Edit)', 'hestia' ), '  ', '' );
						?>
					</small>
				</h4>
				<?php comment_text(); ?>
				<div class="media-footer">
					<?php
					echo get_comment_reply_link(
						array(
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => sprintf( '<i class="fa fa-mail-reply"></i> %s', esc_html__( 'Reply', 'hestia' ) ),
						),
						$comment->comment_ID,
						$comment->comment_post_ID
					);
					?>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'hestia_single_pagination' ) ) {
	/**
	 * Display pagination on single page and single portfolio.
	 */
	function hestia_single_pagination() {
		?>
		<div class="section section-blog-info">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="row">
						<div class="col-md-12">
							<?php
							hestia_wp_link_pages(
								array(
									'before'      => '<div class="text-center"> <ul class="nav pagination pagination-primary">',
									'after'       => '</ul> </div>',
									'link_before' => '<li>',
									'link_after'  => '</li>',
								)
							);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'hestia_get_image_sizes' ) ) {
	/**
	 * Output image sizes for attachment single page.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_get_image_sizes() {

		/* If not viewing an image attachment page, return. */
		if ( ! wp_attachment_is_image( get_the_ID() ) ) {
			return '';
		}

		/* Set up an empty array for the links. */
		$links = array();

		/* Get the intermediate image sizes and add the full size to the array. */
		$sizes   = get_intermediate_image_sizes();
		$sizes[] = 'full';

		/* Loop through each of the image sizes. */
		foreach ( $sizes as $size ) {

			/* Get the image source, width, height, and whether it's intermediate. */
			$image = wp_get_attachment_image_src( get_the_ID(), $size );

			/* Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size. */
			if ( ! empty( $image ) && ( ( ! empty( $image[3] ) && true === $image[3] ) || 'full' === $size ) ) {
				$links[] = '<a target="_blank" class="image-size-link" href="' . esc_url( $image[0] ) . '">' . $image[1] . ' &times; ' . $image[2] . '</a>';
			}
		}

		/* Join the links in a string and return. */

		return join( ' <span class="sep">|</span> ', $links );
	}
}

if ( ! function_exists( 'hestia_sidebar_placeholder' ) ) {
	/**
	 * Display sidebar placeholder.
	 *
	 * @param string $class_to_add Classes to add on container.
	 * @param string $sidebar_id   Id of the sidebar used as a class to differentiate hestia-widget-placeholder for blog and shop pages.
	 * @param string $classes      Classes to add to placeholder.
	 *
	 * @access public
	 * @since  1.1.24
	 */
	function hestia_sidebar_placeholder( $class_to_add, $sidebar_id, $classes = 'col-md-3 blog-sidebar-wrapper' ) {
		$content = apply_filters( 'hestia_sidebar_placeholder_content', esc_html__( 'This sidebar is active but empty. In order to use this layout, please add widgets in the sidebar', 'hestia' ) );
		?>
		<div class="<?php echo esc_attr( $classes ); ?>">
			<aside id="secondary" class="blog-sidebar <?php echo esc_attr( $class_to_add ); ?>" role="complementary">
				<div class="hestia-widget-placeholder
				<?php
				if ( ! empty( $sidebar_id ) ) {
					echo esc_attr( $sidebar_id );
				}
				?>
				">
					<?php
					the_widget( 'WP_Widget_Text', 'text=' . $content );
					?>
				</div>
			</aside><!-- .sidebar .widget-area -->
		</div>
		<?php
	}
}

if ( ! function_exists( 'hestia_display_customizer_shortcut' ) ) {
	/**
	 * This function display a shortcut to a customizer control.
	 *
	 * @param string $class_name        The name of control we want to link this shortcut with.
	 * @param bool   $is_section_toggle Tells function to display eye icon if it's true.
	 */
	function hestia_display_customizer_shortcut( $class_name, $is_section_toggle = false ) {
		if ( ! is_customize_preview() ) {
			return;
		}
		$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
				<path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path>
			</svg>';
		if ( $is_section_toggle ) {
			$icon = '<i class="fa fa-eye"></i>';
		}
		echo
			'<span class="hestia-hide-section-shortcut customize-partial-edit-shortcut customize-partial-edit-shortcut-' . esc_attr( $class_name ) . '">
		<button class="customize-partial-edit-shortcut-button">
			' . $icon . '
		</button>
	</span>';
	}
}

if ( ! function_exists( 'hestia_no_content_get_header' ) ) {
	/**
	 * Header for page builder blank template
	 *
	 * @since  1.1.24
	 * @access public
	 */
	function hestia_no_content_get_header() {

		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?> class="no-js">
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="profile" href="http://gmpg.org/xfn/11">
			<?php wp_head(); ?>
		</head>

		<body <?php body_class(); ?>>
		<?php
		do_action( 'hestia_page_builder_content_body_before' );
	}
}

if ( ! function_exists( 'hestia_no_content_get_footer' ) ) {
	/**
	 * Footer for page builder blank template
	 *
	 * @since  1.1.24
	 * @access public
	 */
	function hestia_no_content_get_footer() {
		do_action( 'hestia_page_builder_content_body_after' );
		wp_footer();
		?>
		</body>
		</html>
		<?php
	}
}

if ( ! function_exists( 'hestia_is_external_url' ) ) {
	/**
	 * Utility to check if URL is external
	 *
	 * @param string $url Url to check.
	 *
	 * @return string
	 */
	function hestia_is_external_url( $url ) {
		$link_url = parse_url( $url );
		$home_url = parse_url( home_url() );

		if ( ! empty( $link_url['host'] ) ) {
			if ( $link_url['host'] !== $home_url['host'] ) {
				return ' target="_blank"';
			}
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'hestia_hex_rgb' ) ) {
	/**
	 * HEX colors conversion to RGB.
	 *
	 * @param string $input Color in hex format.
	 *
	 * @return array|string RGB string.
	 * @since Hestia 1.0
	 */
	function hestia_hex_rgb( $input ) {

		$default = 'rgb(0,0,0)';

		// Return default if no color provided
		if ( empty( $input ) ) {
			return $default;
		}

		// Sanitize $color if "#" is provided
		if ( $input[0] == '#' ) {
			$input = substr( $input, 1 );
		}

		// Check if color has 6 or 3 characters and get values
		if ( strlen( $input ) == 6 ) {
			$hex = array( $input[0] . $input[1], $input[2] . $input[3], $input[4] . $input[5] );
		} elseif ( strlen( $input ) == 3 ) {
			$hex = array( $input[0] . $input[0], $input[1] . $input[1], $input[2] . $input[2] );
		} else {
			return $default;
		}

		// Convert hexadeciomal color to rgb(a)
		$rgb = array_map( 'hexdec', $hex );

		return $rgb;
	}
}

if ( ! function_exists( 'hestia_rgb_to_rgba' ) ) {
	/**
	 * Add opacity to rgb.
	 *
	 * @param array $rgb     RGB color.
	 * @param int   $opacity Opacity value.
	 *
	 * @return string
	 */
	function hestia_rgb_to_rgba( $rgb, $opacity ) {

		if ( ! is_array( $rgb ) ) {
			return '';
		}
		// Check for opacity
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		return esc_html( $output );
	}
}

if ( ! function_exists( 'hestia_hex_rgba' ) ) {
	/**
	 * HEX colors conversion to RGBA.
	 *
	 * @param array|string $input   RGB color.
	 * @param int          $opacity Opacity value.
	 *
	 * @return string
	 */
	function hestia_hex_rgba( $input, $opacity = 1 ) {
		$rgb = hestia_hex_rgb( $input );

		return hestia_rgb_to_rgba( $rgb, $opacity );
	}
}

if ( ! function_exists( 'hestia_add_animationation' ) ) {
	/**
	 * Add animation attribute for animate-on-scroll.
	 *
	 * @param string $animation_type the type of animation.
	 *
	 * @return string
	 */
	function hestia_add_animationation( $animation_type ) {
		if ( ! defined( 'HESTIA_PRO_FLAG' ) ) {
			return '';
		}
		$enable_animations = apply_filters( 'hestia_enable_animations', true );
		$output            = '';
		if ( $enable_animations && ! empty( $animation_type ) ) {
			$output .= ' data-aos="';
			$output .= $animation_type;
			$output .= '" ';
		}

		return $output;
	}
}

if ( ! function_exists( 'hestia_layout' ) ) {
	/**
	 * Returns class names used for the main page/post content div
	 * Based on the Boxed Layout and Header Layout customizer options
	 *
	 * @since    Hestia 1.0
	 * @modified 1.1.64
	 */
	function hestia_layout() {

		/**
		 * For the Page Builder Full Width template don't add any extra classes (except main)
		 */
		if ( is_page_template( 'page-templates/template-pagebuilder-full-width.php' ) ) {
			return 'main';
		}

		$layout_class = 'main ';

		$hestia_general_layout = get_theme_mod( 'hestia_general_layout', 1 );

		/**
		 * Add main-raised class when the Boxed Layout option is enabled
		 */
		if ( isset( $hestia_general_layout ) && $hestia_general_layout == 1 ) {
			$layout_class .= ' main-raised ';
		}

		/**
		 * For WooCommerce pages don't add any extra classes (except main or main-raised)
		 */
		if ( class_exists( 'WooCommerce' ) && ( is_product() || is_cart() || is_checkout() ) ) {
			return $layout_class;
		}

		$hestia_header_layout = get_theme_mod( 'hestia_header_layout', 'default' );

		/**
		 * For other internal posts/pages or static frontpage add extra clsses based on the header layout
		 * Possible cases: default, no-content or classic-blog
		 */
		$layout_class .= ! is_singular() || ( is_front_page() && ! is_page_template() ) ? '' : $hestia_header_layout;

		return $layout_class;
	}
}

if ( ! function_exists( 'hestia_limit_content' ) ) {
	/**
	 * Function that limits a text to $limit words, words that are separated by $separator
	 *
	 * @param array  $input     Content to limit.
	 * @param int    $limit     Max size.
	 * @param string $separator Separator.
	 * @param bool   $show_more Flag to decide if '...' should be added at the end of result.
	 *
	 * @return string
	 */
	function hestia_limit_content( $input, $limit, $separator = ',', $show_more = true ) {
		if ( $limit === 0 ) {
			return '';
		}
		$length = sizeof( $input );
		$more   = $length > $limit ? apply_filters( 'hestia_text_more', ' ...' ) : '';
		$result = '';
		$index  = 0;
		foreach ( $input as $word ) {
			if ( $index < $limit || $limit < 0 ) {
				$result .= $word;
				if ( $length > 1 && $index !== $length - 1 && $index !== $limit - 1 ) {
					$result .= $separator;
					if ( $separator === ',' ) {
						$result .= ' ';
					}
				}
			}
			$index ++;
		}
		if ( $show_more === true ) {
			$result .= $more;
		}

		return $result;
	}
}

if ( ! function_exists( 'hestia_edited_with_pagebuilder' ) ) {
	/**
	 * This function returns whether the theme use or not one of the following page builders:
	 * SiteOrigin, WP Bakery, Elementor, Divi Builder or Beaver Builder.
	 *
	 * @since 1.1.63
	 * @return bool
	 */
	function hestia_edited_with_pagebuilder() {
		$frontpage_id = get_option( 'page_on_front' );
		/**
		 * Exit with false if there is no page set as frontpage.
		 */
		if ( intval( $frontpage_id ) === 0 ) {
			return false;
		}
		/**
		 * Elementor, Beaver Builder, Divi and Siteorigin mark if the page was edited with its editors in post meta
		 * so we'll have to check if plugins exists and the page was edited with page builder.
		 */
		$post_meta            = ! empty( $frontpage_id ) ? get_post_meta( $frontpage_id ) : '';
		$page_builders_values = array(
			'elementor'  => ! empty( $post_meta['_elementor_edit_mode'] ) && $post_meta['_elementor_edit_mode'][0] === 'builder' && class_exists( 'Elementor\Plugin' ),
			'beaver'     => ! empty( $post_meta['_fl_builder_enabled'] ) && $post_meta['_fl_builder_enabled'][0] === '1' && class_exists( 'FLBuilder' ),
			'siteorigin' => ! empty( $post_meta['panels_data'] ) && class_exists( 'SiteOrigin_Panels' ),
			'divi'       => ! empty( $post_meta['_et_pb_use_builder'] ) && $post_meta['_et_pb_use_builder'][0] === 'on' && class_exists( 'ET_Builder_Plugin' ),
		);
		/**
		 * WP Bakery (former Visual Composer) doesn't store a flag in meta data to say whether or not the page
		 * is edited with it so we have to check post content if it contains shortcodes from plugin.
		 */
		$post_content = get_post_field( 'post_content', $frontpage_id );
		if ( ! empty( $post_content ) ) {
			$page_builders_values['wpbakery'] = class_exists( 'Vc_Manager' ) && strpos( $post_content, '[vc_' ) !== false;
		}
		/**
		 * Check if at least one page builder returns true and return true if it does.
		 */
		foreach ( $page_builders_values as $page_builder ) {
			if ( $page_builder === true ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'hestia_category' ) ) {
	/**
	 * Display the first category of the post.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_category() {
		$category = get_the_category();
		if ( $category ) {
			/* translators: %s is Category name */
			echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'hestia' ), $category[0]->name ) ) . '" ' . '>' . esc_html( $category[0]->name ) . '</a> ';
		}
	}
}
