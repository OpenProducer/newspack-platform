<?php
/**
 * Template part for the Collection intro section.
 *
 * @package Newspack\Collections
 */

use Newspack\Collections\Query_Helper;
use Newspack\Collections\Template_Helper;

$collection       = ( $args['collection'] ?? null ); // Allow overriding the collection post object by passing it as an argument.
$collection       = $collection instanceof WP_Post ? $collection : get_post( $collection ); // The collection argument could be either post object or post ID.
$is_latest        = ( $args['is_latest'] ?? false );
$collection_title = get_the_title( $collection );
$permalink        = match ( true ) { // Allow overriding the permalink by passing it as a string or a boolean argument.
	is_string( $args['permalink'] ?? null )  => $args['permalink'],               // If the argument is a string, use it as the permalink.
	( $args['permalink'] ?? false ) === true => get_the_permalink( $collection ), // If a `true` boolean, use the collection permalink.
	default                                  => false,                            // If not provided (or if a falsey value), don't use a permalink.
};

/**
 * Fires before the collection intro section.
 *
 * @param WP_Post $collection The collection post.
 */
do_action( 'newspack_collections_intro_before', $collection );
?>

<!-- Intro Section -->
<div class="collection-intro">

	<!-- Cover Card -->
	<div class="collection-intro__card">
		<?php
		/**
		 * Fires before the collection image card in the intro section.
		 *
		 * @param WP_Post $collection The collection post.
		 */
		do_action( 'newspack_collections_intro_image_card', $collection );

		echo wp_kses_post( Template_Helper::render_image( $collection, $permalink ) );
		?>
	</div>

	<!-- Content -->
	<div class="collection-intro__content">
		<?php
		/**
		 * Fires before the collection meta in the intro content section.
		 *
		 * @param WP_Post $collection The collection post.
		 */
		do_action( 'newspack_collections_intro_before_meta', $collection );
		?>

		<div class="collection-intro__content__meta">
			<?php if ( $is_latest ) : ?>
				<h6 class="wp-block-heading latest-collection-heading has-primary-color has-text-color has-link-color has-normal-font-size"><?php echo esc_html_e( 'Latest', 'newspack-plugin' ); ?></h6>
			<?php endif; ?>
			<h1>
				<?php if ( $permalink ) : ?>
					<a href="<?php echo esc_url( $permalink ); ?>">
						<?php echo wp_kses_post( $collection_title ); ?>
					</a>
				<?php else : ?>
					<?php echo wp_kses_post( $collection_title ); ?>
				<?php endif; ?>
			</h1>
			<?php echo wp_kses_post( Template_Helper::render_meta_text( $collection, 1 ) ); ?>
		</div>

		<div class="collection-intro__content__description">
			<?php
			/**
			 * Fires before the collection description in the intro section.
			 *
			 * @param WP_Post $collection The collection post.
			 */
			do_action( 'newspack_collections_intro_before_description', $collection );

			echo wp_kses_post( get_the_content( null, false, $collection ) );
			?>
		</div>

		<?php
		$ctas = Query_Helper::get_ctas( $collection );
		if ( $ctas ) :
			?>
			<div class="collection-buttons">
				<?php foreach ( $ctas as $cta ) : ?>
					<?php echo wp_kses_post( Template_Helper::render_cta( $cta ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
/**
 * Fires after the collection intro section.
 *
 * @param WP_Post $collection The collection post.
 */
do_action( 'newspack_collections_intro_after', $collection );
?>
