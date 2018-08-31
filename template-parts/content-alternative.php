<?php
/**
 * The alternative template for displaying post content on blog
 *
 * Used for index/archive/search.
 *
 * @package Hestia
 * @since Hestia 1.0
 */
?>
<article
		id="post-<?php the_ID(); ?>" <?php ( is_sticky() && is_home() && ! is_paged() ) ? post_class( 'card card-raised card-blog' ) : post_class( 'card card-plain card-blog' ); ?>>
	<div class="row alternative-blog-row">
		<?php if ( has_post_thumbnail() ) { ?>
		<div class="col-ms-7 col-sm-7">
			<?php } else { ?>
			<div class="col-sm-12">
				<?php } ?>
				<h6 class="category text-info"><?php hestia_category(); ?></h6>
				<?php
				the_title(
					sprintf(
						'<h2 class="card-title"><a href="%s" title="%s" rel="bookmark">', esc_url( get_permalink() ), the_title_attribute(
							array(
								'echo' => false,
							)
						)
					), '</a></h2>'
				);
				?>
				<div class="card-description">
					<p>
						<?php
						$hestia_more = strpos( $post->post_content, '<!--more' );
						if ( $hestia_more ) :
							echo get_the_content();
						else :
							echo get_the_excerpt();
						endif;
						?>
					</p>
				</div>
				<div class="author">
					<?php
					echo apply_filters(
						'hestia_blog_post_meta', sprintf(
							/* translators: %1$s is Author name wrapped, %2$s is Time */
							esc_html__( 'By %1$s, %2$s', 'hestia' ),
							sprintf(
								/* translators: %1$s is Author name, %2$s is author link */
								'<a href="%2$s" title="%1$s"><b>%1$s</b></a>',
								esc_html( get_the_author() ),
								esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
							),
							sprintf(
								/* translators: %1$s is Time since post, %2$s is author Close tag */
								esc_html__( '%1$s ago %2$s', 'hestia' ),
								sprintf(
									/* translators: %1$s is Time since, %2$s is Link to post */
									'<a href="%2$s"><time>%1$s</time>',
									esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ),
									esc_url( get_permalink() )
								),
								'</a>'
							)
						)
					);
					?>
				</div>
			</div>

			<?php if ( has_post_thumbnail() ) { ?>
				<div class="col-ms-5 col-sm-5">
					<div class="card-image">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail( 'hestia-blog' ); ?>
						</a>
					</div>
				</div>
			<?php } ?>
		</div>
</article>
