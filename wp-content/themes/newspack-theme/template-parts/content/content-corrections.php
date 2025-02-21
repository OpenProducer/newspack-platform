<?php
/**
 * Template part for displaying content of corrections in archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Newspack
 */

$related_post_id = get_post_meta( get_the_ID(), 'newspack_correction-post-id', true );
$correction_type = '';
if ( method_exists( 'Newspack\Corrections', 'get_correction_type' ) ) {
	$correction_type = Newspack\Corrections::get_correction_type() . ', ';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-container">

		<b>
			<?php echo esc_html( $correction_type ); ?>
			<?php echo get_the_date(); ?>
			<?php the_time(); ?>
		</b>
		<?php echo esc_html( get_the_content() ); ?><br/>
		<a href="<?php echo esc_url( get_permalink( $related_post_id ) ); ?>">
			<u><?php echo esc_html( get_the_title( $related_post_id ) ); ?></u>
		</a>

	</div><!-- .entry-container -->
</article><!-- #post-${ID} -->
