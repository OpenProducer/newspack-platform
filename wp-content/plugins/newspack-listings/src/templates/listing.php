<?php
/**
 * Template for a single listing item within a curated list.
 *
 * @global array $attributes Block attributes.
 * @package WordPress
 */

use \Newspack_Listings\Newspack_Listings_Core as Core;
use \Newspack_Listings\Utils as Utils;

call_user_func(
	function( $data ) {
		$attributes = $data['attributes'];
		$post       = $data['post'];

		if ( empty( $attributes ) || empty( $post ) ) {
			return;
		}

		?>
	<li class="newspack-listings__listing">
	<article class="newspack-listings__listing-post">
		<a class="newspack-listings__listing-link" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
			<?php if ( $attributes['showImage'] ) : ?>
				<?php
				$featured_image = get_the_post_thumbnail( $post->ID, 'large' );
				if ( ! empty( $featured_image ) ) :
					?>
					<figure class="newspack-listings__listing-featured-media">
						<?php echo wp_kses_post( $featured_image ); ?>
						<?php if ( $attributes['showCaption'] ) : ?>
						<figcaption class="newspack-listings__listing-caption">
							<?php echo wp_kses_post( get_the_post_thumbnail_caption( $post->ID ) ); ?>
						</figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>
			<?php endif; ?>

			<div class="newspack-listings__listing-meta">
				<?php
				if ( $attributes['showCategory'] ) :
					$categories = get_the_terms( $post->ID, Core::NEWSPACK_LISTINGS_CAT );

					if ( is_array( $categories ) && 0 < count( $categories ) ) :
						?>
						<div class="newspack-listings__category cat-links">
							<?php
							$category_index = 0;
							foreach ( $categories as $category ) {
								$term_url = get_term_link( $category->slug, Core::NEWSPACK_LISTINGS_CAT );

								if ( empty( $term_url ) ) {
									$term_url = '#';
								}
								echo wp_kses_post( $category->name );

								if ( $category_index + 1 < count( $categories ) ) {
									echo esc_html( ', ' );
								}

								$category_index ++;
							}
							?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<h3 class="newspack-listings__listing-title"><?php echo wp_kses_post( $post->post_title ); ?></h3>
				<?php if ( $attributes['showAuthor'] && empty( get_post_meta( $post->ID, 'newspack_listings_hide_author', true ) ) ) : ?>
				<cite>
					<?php echo wp_kses_post( __( 'By', 'newspack-listings' ) . ' ' . get_the_author_meta( 'display_name', $post->post_author ) ); ?>
				</cite>
				<?php endif; ?>

				<?php
				if ( $attributes['showExcerpt'] ) {
					echo wp_kses_post( Utils\get_listing_excerpt( $post ) );
				}
				?>

				<?php
				if ( $attributes['showTags'] ) :
					$tags = get_the_terms( $post->ID, Core::NEWSPACK_LISTINGS_TAG );

					if ( is_array( $tags ) && 0 < count( $tags ) ) :
						?>
						<p class="newspack-listings__tags">
							<strong><?php echo esc_html( __( 'Tagged: ', 'newspack-listings' ) ); ?></strong>
							<?php
							$tag_index = 0;
							foreach ( $tags as $tag ) {
								echo wp_kses_post( $tag->name );

								if ( $tag_index + 1 < count( $tags ) ) {
									echo esc_html( ', ' );
								}

								$tag_index ++;
							}
							?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</a>
	</article>
	</li>
		<?php
	},
	$data // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
);
