<?php
/**
 * The template for displaying Collections archives.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Newspack\Collections
 *
 * @phpcs:disable WordPress.Security.NonceVerification.Recommended
 */

use Newspack\Collections\Query_Helper;
use Newspack\Collections\Settings;
use Newspack\Collections\Template_Helper;

get_header();

/**
 * Fires at the start of the collections archive template.
 */
do_action( 'newspack_collections_archive_start' );
?>

<section id="primary" class="content-area">
	<header class="page-header">
		<h1 class="page-title"><span class="page-description"><?php echo esc_html( Settings::get_collection_label() ); ?></span></h1>
	</header><!-- .page-header -->

	<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :
			$selected_year = isset( $_GET['np_collections_year'] ) ? sanitize_text_field( $_GET['np_collections_year'] ) : '';

			// Render the intro section only if no year filter is applied, it's the first page of results and "Highlight Most Recent Collection" setting is enabled.
			if ( empty( $selected_year ) && ! is_paged() && Settings::get_setting( 'highlight_latest' ) ) :
				get_template_part(
					Template_Helper::TEMPLATE_PARTS_DIR . 'newspack-collection-intro',
					null,
					[
						'is_latest' => true,
						'permalink' => true,
					]
				);

				// Advance the loop to the next post so it doesn't render twice.
				the_post();
				echo wp_kses_post( Template_Helper::render_separator( 'is-latest-collection' ) );
			endif;
			?>

			<!-- Filter controls -->
			<form class="collections-filter" method="get">
				<?php
				$selected_category = isset( $_GET['np_collections_category'] ) ? sanitize_text_field( $_GET['np_collections_category'] ) : '';
				$available_years   = Query_Helper::get_available_years( $selected_category );
				?>

				<div class="collections-filter__select">
					<label for="year"><?php esc_html_e( 'Year:', 'newspack-plugin' ); ?></label>
					<select name="year" id="year">
						<option value="" <?php selected( $selected_year, '' ); ?>><?php esc_html_e( 'All', 'newspack-plugin' ); ?></option>
						<?php foreach ( $available_years as $available_year ) : ?>
							<option value="<?php echo esc_attr( $available_year ); ?>" <?php selected( $selected_year, $available_year ); ?>><?php echo esc_html( $available_year ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<?php
				$categories = Query_Helper::get_collection_categories();

				if ( count( $categories ) > 1 ) :
					?>
					<div class="collections-filter__select">
						<label for="category"><?php echo esc_html( Settings::get_setting( 'category_filter_label', _x( 'Publication:', 'collections category filter label', 'newspack-plugin' ) ) ); ?></label>
						<select name="category" id="category">
							<option value="" <?php selected( $selected_category, '' ); ?>><?php esc_html_e( 'All', 'newspack-plugin' ); ?></option>
							<?php foreach ( $categories as $category ) : ?>
								<option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( $selected_category, $category->slug ); ?>><?php echo esc_html( $category->name ); ?></option>
							<?php endforeach; ?>
							?>
						</select>
					</div>
				<?php endif; ?>

			</form> <!-- .collections-filter -->

			<?php
			echo wp_kses_post( Template_Helper::render_separator() );

			/**
			 * Fires after the filter controls in the archive template.
			 *
			 * @param string $selected_year     The selected year filter.
			 * @param string $selected_category The selected category filter.
			 */
			do_action( 'newspack_collections_archive_after_filters', $selected_year, $selected_category );
			?>

			<!-- Collections grid -->
			<div class="collections-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$collection_id = get_the_ID();
					?>

					<div class="collection-item">
						<?php echo wp_kses_post( Template_Helper::render_image( $post ) ); ?>

						<div class="collection-content">
							<h3 class="has-normal-font-size">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>

							<?php echo wp_kses_post( Template_Helper::render_meta_text( $collection_id ) ); ?>
						</div>

						<?php
						$ctas = Query_Helper::get_ctas( $collection_id, 1 );
						if ( ! empty( $ctas ) ) :
							?>
							<div class="collection-buttons">
								<?php foreach ( $ctas as $cta ) : ?>
									<?php echo wp_kses_post( Template_Helper::render_cta( $cta ) ); ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>

					<?php
					/**
					 * Fires after each collection item in the archive grid.
					 *
					 * @param int $collection_id The collection post ID.
					 */
					do_action( 'newspack_collections_archive_after_item', $collection_id );
				endwhile;
				?>
			</div> <!-- .collections-grid -->

			<?php
			/**
			 * Fires before the navigation in the archive template.
			 */
			do_action( 'newspack_collections_archive_before_navigation' );

			// Use Newspack theme navigation if it exists, otherwise use core navigation.
			if ( function_exists( 'newspack_the_posts_navigation' ) ) {
				newspack_the_posts_navigation();
			} else {
				the_posts_navigation();
			}

		else :
			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

</section><!-- #primary -->

<?php
/**
 * Fires at the end of the collections archive template.
 */
do_action( 'newspack_collections_archive_end' );

get_footer();
