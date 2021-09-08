<?php
/**
 * Server-side render functions for the Author Profile block.
 *
 * @package WordPress
 */

/**
 * Dynamic block registration.
 */
function newspack_blocks_register_author_profile() {
	$block_json = json_decode(
		file_get_contents( __DIR__ . '/block.json' ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		true
	);

	register_block_type(
		'newspack-blocks/' . $block_json['name'],
		[
			'attributes'      => $block_json['attributes'],
			'render_callback' => 'newspack_blocks_render_block_author_profile',
		]
	);
}

/**
 * Given a numeric ID, get the corresponding WP user or Co-authors Plus guest author.
 *
 * @param int $author_id Author ID to look up.
 * @param int $avatar_size Size of the avatar image to fetch.
 * @return object|boolean Author object in standardized format, or false if none exists.
 */
function newspack_blocks_get_author_or_guest_author( $author_id, $avatar_size = 128 ) {
	$author = false;

	// First, see if the $author_id is a guest author.
	if ( class_exists( 'CoAuthors_Guest_Authors' ) ) {
		$author = ( new CoAuthors_Guest_Authors() )->get_guest_author_by( 'id', $author_id );

		// Format CAP guest author object to return to the render function.
		if ( $author && isset( $author->ID ) ) {
			$author = [
				'id'     => $author_id,
				'name'   => $author->display_name,
				'bio'    => $author->description,
				'avatar' => function_exists( 'coauthors_get_avatar' ) ? coauthors_get_avatar( $author, $avatar_size ) : false,
				'url'    => esc_urL(
					get_site_urL(
						null,
						'?author_name=' . get_post_meta( $author_id, 'cap-user_login', true )
					)
				),
				'email'  => WP_REST_Newspack_Authors_Controller::get_email( $author_id ),
				'social' => WP_REST_Newspack_Authors_Controller::get_social( $author_id ),
			];
		}
	}

	// If $author is still false, see if it's a standard WP User.
	if ( ! $author ) {
		$author = get_user_by( 'id', $author_id );

		// Format WP user object to return to the render function.
		if ( $author && isset( $author->data ) ) {
			$author = [
				'id'     => $author_id,
				'name'   => $author->data->display_name,
				'bio'    => get_the_author_meta( 'description', $author_id ),
				'avatar' => get_avatar( $author_id, $avatar_size ),
				'url'    => esc_urL( get_author_posts_url( $author_id ) ),
				'email'  => WP_REST_Newspack_Authors_Controller::get_email( $author_id, false, $author->data->user_email ),
				'social' => WP_REST_Newspack_Authors_Controller::get_social( $author_id ),
			];
		}
	}

	return $author;
}

/**
 * Block render callback.
 *
 * @param array $attributes Block attributes.
 */
function newspack_blocks_render_block_author_profile( $attributes ) {
	// Bail if there's no author ID for this block.
	if ( empty( $attributes['authorId'] ) ) {
		return;
	}

	// Get the author by ID.
	$author = newspack_blocks_get_author_or_guest_author( intval( $attributes['authorId'] ), intval( $attributes['avatarSize'] ) );

	// Bail if there's no author or guest author with the saved ID.
	if ( empty( $author ) ) {
		return;
	}

	Newspack_Blocks::enqueue_view_assets( 'author-profile' );

	// Whether to render links to the author's archive page.
	$show_archive_link = $attributes['showArchiveLink'] && ! empty( $author['url'] );

	// Combine social links and email, which are shown together.
	$social_links = $attributes['showSocial'] && ! empty( $author['social'] ) ? $author['social'] : [];
	if ( $attributes['showEmail'] && ! empty( $author['email'] ) ) {
		$social_links['email'] = $author['email'];
	}

	// Add classes to the block.
	$classes = Newspack_Blocks::block_classes( 'author-profile' );

	if ( $attributes['avatarAlignment'] ) {
		$classes .= ' avatar-' . $attributes['avatarAlignment'];
	}

	if ( isset( $attributes['className'] ) ) {
		$classes .= ' ' . $attributes['className'];
	}

	ob_start();

	?>
	<div class="<?php echo esc_attr( $classes ); ?>">
		<?php if ( $attributes['showAvatar'] && $author['avatar'] ) : ?>
			<div class="wp-block-newspack-blocks-author-profile__avatar">
				<figure style="border-radius: <?php echo esc_attr( $attributes['avatarBorderRadius'] ); ?>; width: <?php echo esc_attr( $attributes['avatarSize'] ); ?>px;">
					<?php echo wp_kses_post( $author['avatar'] ); ?>
				</figure>
			</div>
		<?php endif; ?>
		<div class="wp-block-newspack-blocks-author-profile__bio">
			<h3>
				<?php if ( $show_archive_link ) : ?>
				<a href="<?php echo esc_url( $author['url'] ); ?>">
				<?php endif; ?>
					<?php echo esc_html( $author['name'] ); ?>
				<?php if ( $show_archive_link ) : ?>
				</a>
				<?php endif; ?>
			</h3>

			<?php if ( $attributes['showBio'] && ! empty( $author['bio'] ) ) : ?>
				<?php
				$bio = $author['bio'];

				if ( $show_archive_link ) {
					$bio .= sprintf(
						// Translators: "more by this author" link.
						__( ' <a href="%1$s">More by %2$s</a>', 'newspack-blocks' ),
						$author['url'],
						$author['name']
					);
				}

				echo wp_kses_post( wpautop( $bio ) );
				?>
			<?php endif; ?>

			<?php if ( $attributes['showEmail'] || $attributes['showSocial'] ) : ?>
				<ul class="wp-block-newspack-blocks-author-profile__social-links">
					<?php foreach ( $social_links as $service => $social_data ) : ?>
						<li>
							<a href="<?php echo esc_url( $social_data['url'] ); ?>">
								<?php if ( ! empty( $social_data['svg'] ) ) : ?>
									<span>
										<?php echo Newspack_Blocks::sanitize_svg( $social_data['svg'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
								<?php endif; ?>
								<span class="<?php echo ! empty( $social_data['svg'] ) ? esc_attr( 'hidden' ) : esc_attr( 'visible' ); ?>">
									<?php echo esc_html( $service ); ?>
								</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

add_action( 'init', 'newspack_blocks_register_author_profile' );
