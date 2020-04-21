<?php
/**
 * Register Newspack block patterns.
 *
 * @package Newspack_Blocks
 */

/**
 * Homepage Posts.
 */
add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-1.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-1.png', __FILE__ ),
				'title'    => __( 'Style 1', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-2.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-2.png', __FILE__ ),
				'title'    => __( 'Style 2', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-3.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-3.png', __FILE__ ),
				'title'    => __( 'Style 3', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-4.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-4.png', __FILE__ ),
				'title'    => __( 'Style 4', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-5.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-5.png', __FILE__ ),
				'title'    => __( 'Style 5', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-6.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-6.png', __FILE__ ),
				'title'    => __( 'Style 6', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-7.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-7.png', __FILE__ ),
				'title'    => __( 'Style 7', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-8.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-8.png', __FILE__ ),
				'title'    => __( 'Style 8', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-9.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-9.png', __FILE__ ),
				'title'    => __( 'Style 9', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-10.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-10.png', __FILE__ ),
				'title'    => __( 'Style 10', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-11.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-11.png', __FILE__ ),
				'title'    => __( 'Style 11', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-12.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-12.png', __FILE__ ),
				'title'    => __( 'Style 12', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-13.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-13.png', __FILE__ ),
				'title'    => __( 'Style 13', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-14.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-14.png', __FILE__ ),
				'title'    => __( 'Style 14', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-15.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-15.png', __FILE__ ),
				'title'    => __( 'Style 15', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-16.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-16.png', __FILE__ ),
				'title'    => __( 'Style 16', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-17.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-17.png', __FILE__ ),
				'title'    => __( 'Style 17', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-18.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-18.png', __FILE__ ),
				'title'    => __( 'Style 18', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-19.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-19.png', __FILE__ ),
				'title'    => __( 'Style 19', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/homepage-posts/style-20.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Homepage Posts', 'newspack-blocks' ),
				'content'  => $decode['content'],
				'image'    => plugins_url( 'src/patterns/images/homepage-posts/style-20.png', __FILE__ ),
				'title'    => __( 'Style 20', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

/**
 * Subscribe.
 */
add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page', 'post', 'newspack_popups_cpt' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/subscribe/style-1.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Subscribe', 'newspack-blocks' ),
				'content'  => str_replace(
					[
						'Subscribe to our newsletter',
					],
					[
						__( 'Subscribe to our newsletter', 'newspack-blocks' ),
					],
					$decode['content']
				),
				'image'    => plugins_url( 'src/patterns/images/subscribe/style-1.png', __FILE__ ),
				'title'    => __( 'Style 1', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page', 'post', 'newspack_popups_cpt' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/subscribe/style-2.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Subscribe', 'newspack-blocks' ),
				'content'  => str_replace(
					[
						'Subscribe to our newsletter',
					],
					[
						__( 'Subscribe to our newsletter', 'newspack-blocks' ),
					],
					$decode['content']
				),
				'image'    => plugins_url( 'src/patterns/images/subscribe/style-2.png', __FILE__ ),
				'title'    => __( 'Style 2', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page', 'post', 'newspack_popups_cpt' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/subscribe/style-3.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Subscribe', 'newspack-blocks' ),
				'content'  => str_replace(
					[
						'Subscribe to our newsletter',
					],
					[
						__( 'Subscribe to our newsletter', 'newspack-blocks' ),
					],
					$decode['content']
				),
				'image'    => plugins_url( 'src/patterns/images/subscribe/style-3.png', __FILE__ ),
				'title'    => __( 'Style 3', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page', 'post', 'newspack_popups_cpt' ], true ) ) {
			$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/subscribe/style-4.json'), true ); //phpcs:ignore
			$patterns[] = [
				'category' => __( 'Subscribe', 'newspack-blocks' ),
				'content'  => str_replace(
					[
						'Subscribe to our newsletter',
					],
					[
						__( 'Subscribe to our newsletter', 'newspack-blocks' ),
					],
					$decode['content']
				),
				'image'    => plugins_url( 'src/patterns/images/subscribe/style-4.png', __FILE__ ),
				'title'    => __( 'Style 4', 'newspack-blocks' ),
			];
		}
		return $patterns;
	},
	10,
	2
);

/**
 * Donation.
 */
add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page', 'post', 'newspack_popups_cpt' ], true ) ) {
			$newspack_donations_configured = false;
			if ( class_exists( 'Newspack\Donations' ) ) {
				$settings = Newspack\Donations::get_donation_settings();
				if ( ! is_wp_error( $settings ) && $settings['created'] ) {
					$newspack_donations_configured = true;
				}
			}
			if ( $newspack_donations_configured ) {
				$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/donation/style-1.json'), true ); //phpcs:ignore
				$patterns[] = [
					'category' => __( 'Donation', 'newspack-blocks' ),
					'content'  => str_replace(
						[
							'Support our publication',
						],
						[
							__( 'Support our publication', 'newspack-blocks' ),
						],
						$decode['content']
					),
					'image'    => plugins_url( 'src/patterns/images/donation/style-1.png', __FILE__ ),
					'title'    => __( 'Style 1', 'newspack-blocks' ),
				];
			}
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page', 'post', 'newspack_popups_cpt' ], true ) ) {
			$newspack_donations_configured = false;
			if ( class_exists( 'Newspack\Donations' ) ) {
				$settings = Newspack\Donations::get_donation_settings();
				if ( ! is_wp_error( $settings ) && $settings['created'] ) {
					$newspack_donations_configured = true;
				}
			}
			if ( $newspack_donations_configured ) {
				$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/donation/style-2.json'), true ); //phpcs:ignore
				$patterns[] = [
					'category' => __( 'Donation', 'newspack-blocks' ),
					'content'  => str_replace(
						[
							'Support our publication',
							'Please join us!',
						],
						[
							__( 'Support our publication', 'newspack-blocks' ),
							__( 'With the support of readers like you, we provide thoughtfully researched articles for a more informed and connected community. This is your chance to support credible, community-based, public-service journalism. Please join us!', 'newspack-blocks' ),
						],
						$decode['content']
					),
					'image'    => plugins_url( 'src/patterns/images/donation/style-2.png', __FILE__ ),
					'title'    => __( 'Style 2', 'newspack-blocks' ),
				];
			}
		}
		return $patterns;
	},
	10,
	2
);

add_filter(
	'newspack_blocks_patterns',
	function( $patterns, $post_type ) {
		if ( in_array( $post_type, [ 'page', 'post', 'newspack_popups_cpt' ], true ) ) {
			$newspack_donations_configured = false;
			if ( class_exists( 'Newspack\Donations' ) ) {
				$settings = Newspack\Donations::get_donation_settings();
				if ( ! is_wp_error( $settings ) && $settings['created'] ) {
					$newspack_donations_configured = true;
				}
			}
			if ( $newspack_donations_configured ) {
				$decode = json_decode( file_get_contents( NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/patterns/markup/donation/style-3.json'), true ); //phpcs:ignore
				$patterns[] = [
					'category' => __( 'Donation', 'newspack-blocks' ),
					'content'  => str_replace(
						[
							'Support our publication',
						],
						[
							__( 'Support our publication', 'newspack-blocks' ),
						],
						$decode['content']
					),
					'image'    => plugins_url( 'src/patterns/images/donation/style-3.png', __FILE__ ),
					'title'    => __( 'Style 3', 'newspack-blocks' ),
				];
			}
		}
		return $patterns;
	},
	10,
	2
);
