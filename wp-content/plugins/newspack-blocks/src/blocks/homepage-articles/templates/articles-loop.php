<?php
/**
 * Articles loop template.
 *
 * @package WordPress
 * @global \WP_Query $article_query Article query.
 * @global array     $attributes
 * @global array     $newspack_blocks_post_id
 */

call_user_func(
	function( $data ) {
		$attributes    = $data['attributes'];
		$article_query = $data['article_query'];

		global $newspack_blocks_post_id;
		$post_counter = 0;
		do_action( 'newspack_blocks_homepage_posts_before_render' );

		// Create a callable closure that can be added and removed.
		$newspack_blocks_excerpt_length = function( $length ) use ( $attributes ) {
			if ( $attributes['excerptLength'] ) {
				return $attributes['excerptLength'];
			}

			return 55;
		};

		// If showing excerpt, filter the length using the block attribute.
		if ( $attributes['showExcerpt'] ) {
			add_filter( 'excerpt_length', $newspack_blocks_excerpt_length, 999 );
		}

		while ( $article_query->have_posts() ) {
			$article_query->the_post();
			$newspack_blocks_post_id[ get_the_ID() ] = true;
			$post_counter++;
			echo Newspack_Blocks::template_inc( __DIR__ . '/article.php', array( 'attributes' => $attributes ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// Remove the excerpt_length filter so it doesn't affect excerpts outside this block instance.
		if ( $attributes['showExcerpt'] ) {
			remove_filter( 'excerpt_length', $newspack_blocks_excerpt_length, 999 );
		}
		do_action( 'newspack_blocks_homepage_posts_after_render' );
		wp_reset_postdata();
	},
	$data // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
);
