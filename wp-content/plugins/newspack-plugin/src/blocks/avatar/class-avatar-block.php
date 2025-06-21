<?php
/**
 * Avatar Block.
 *
 * @package Newspack
 */

namespace Newspack\Blocks\Avatar;

use Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Avatar_Block Class
 */
final class Avatar_Block {
	/**
	 * Initializes the block.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_block' ] );
		add_action( 'init', [ __CLASS__, 'register_block_styles' ] );
	}

	/**
	 * Register newspack avatar block styles.
	 *
	 * @return void
	 */
	public static function register_block_styles() {
		if ( is_plugin_active( 'co-authors-plus/co-authors-plus.php' ) && function_exists( 'get_coauthors' ) ) {
			register_block_style(
				'newspack/avatar',
				[
					'name'  => 'stacked',
					'label' => __( 'Stacked', 'newspack-plugin' ),
				]
			);
		}
	}

	/**
	 * Register newpack avatar block.
	 *
	 * @return void
	 */
	public static function register_block() {
		register_block_type_from_metadata(
			__DIR__ . '/block.json',
			[
				'render_callback' => [ __CLASS__, 'render_block' ],
				'uses_context'    => [ 'postId', 'postType' ],
			]
		);
	}

	/**
	 * Block render callback.
	 *
	 * @param array  $attributes The block attributes.
	 * @param string $content    The block content.
	 * @param object $block      The block.
	 *
	 * @return string The block HTML.
	 */
	public static function render_block( array $attributes, string $content, $block ) {
		$post_id = $block->context['postId'] ?? null;

		if ( empty( $post_id ) ) {
			return '';
		}

		$image_size     = $attributes['size'] ?? 48;
		$link_to_author = $attributes['linkToAuthorArchive'] ?? false;
		$authors        = [];

		if ( function_exists( 'get_coauthors' ) ) {
			$authors = get_coauthors( $post_id );
		} else {
			$authors[] = get_userdata( get_post_field( 'post_author', $post_id ) );
		}

		$wrapper_attributes = get_block_wrapper_attributes( [ 'style' => '--avatar-size: ' . esc_attr( $image_size ) . 'px;' ] );
		$duotone_preset     = $attributes['style']['color']['duotone'] ?? null;
		$duotone_class      = self::newspack_get_duotone_class_name( $duotone_preset );

		ob_start();
		?>
		<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			foreach ( $authors as $index => $author ) :
				$avatar_url  = get_avatar_url( $author->ID, [ 'size' => $image_size * 2 ] );
				$author_name = esc_attr( $author->display_name );
				$author_url  = get_author_posts_url( $author->ID );

				$border_attributes = function_exists( 'get_block_core_avatar_border_attributes' )
					? get_block_core_avatar_border_attributes( $attributes )
					: [
						'class' => '',
						'style' => '',
					];

				$class = 'avatar avatar-' . esc_attr( $image_size ) . ' photo wp-block-newspack-avatar__image ' . ( $border_attributes['class'] ?? '' );
				?>
				<div class="newspack-avatar-wrapper <?php echo esc_attr( $duotone_class ); ?>">
					<?php if ( $link_to_author ) : ?>
						<a href="<?php echo esc_url( $author_url ); ?>" class="wp-block-newspack-avatar__link">
							<img
								src="<?php echo esc_url( $avatar_url ); ?>"
								class="<?php echo esc_attr( $class ); ?>"
								alt="<?php echo esc_attr( $author_name ); ?>"
								width="<?php echo esc_attr( $image_size ); ?>"
								height="<?php echo esc_attr( $image_size ); ?>"
								style="<?php echo esc_attr( $border_attributes['style'] ?? '' ); ?>"
							/>
						</a>
					<?php else : ?>
						<img
							src="<?php echo esc_url( $avatar_url ); ?>"
							class="<?php echo esc_attr( $class ); ?>"
							alt="<?php echo esc_attr( $author_name ); ?>"
							width="<?php echo esc_attr( $image_size ); ?>"
							height="<?php echo esc_attr( $image_size ); ?>"
							style="<?php echo esc_attr( $border_attributes['style'] ?? '' ); ?>"
						/>
					<?php endif; ?>
					<div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * This function is used to get the duotone class name from the preset value.
	 *
	 * @param  mixed $preset_value Duotone preset value.
	 * @return string Constructed class name.
	 */
	public static function newspack_get_duotone_class_name( $preset_value ) {
		if ( str_starts_with( $preset_value, 'var:preset|duotone|' ) ) {
			$slug = str_replace( 'var:preset|duotone|', '', $preset_value );
			return 'wp-duotone-' . sanitize_title( $slug );
		}
		return '';
	}
}

Avatar_Block::init();
