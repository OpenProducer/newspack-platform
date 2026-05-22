<?php
/**
 * Overlay Menu Trigger Block.
 *
 * @package Newspack
 */

namespace Newspack\Blocks\Overlay_Menu;

defined( 'ABSPATH' ) || exit;

/**
 * Overlay_Menu_Trigger_Block Class.
 */
final class Overlay_Menu_Trigger_Block {

	// Inline SVG for the menu (overlay) icon.
	const ICON_MENU = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M5 5v1.5h14V5H5zm0 7.8h14v-1.5H5v1.5zM5 19h14v-1.5H5V19z"/></svg>';

	/**
	 * Initializes the block.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_block' ] );
	}

	/**
	 * Registers the block type.
	 *
	 * @return void
	 */
	public static function register_block() {
		register_block_type_from_metadata(
			__DIR__ . '/block.json',
			[
				'render_callback' => [ __CLASS__, 'render_block' ],
			]
		);
	}

	/**
	 * Block render callback.
	 *
	 * @param array     $attributes Block attributes.
	 * @param string    $content    Unused — no InnerBlocks.
	 * @param \WP_Block $block      Block instance (provides instanceId context from parent).
	 *
	 * @return string Block HTML.
	 */
	public static function render_block( array $attributes, string $content, \WP_Block $block ) {
		$instance_id  = $block->context['newspack-overlay-menu/instanceId'] ?? '';
		$trigger_text = $attributes['triggerText'] ?? __( 'Menu', 'newspack-plugin' );

		// Display mode from block style class in className (default = icon + text).
		$classes    = explode( ' ', (string) ( $attributes['className'] ?? '' ) );
		$show_icon  = ! in_array( 'is-style-text-only', $classes, true );
		$text_class = in_array( 'is-style-icon-only', $classes, true ) ? 'screen-reader-text' : '';

		$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => 'overlay-menu__trigger wp-block-button__link wp-element-button' ] );

		ob_start();
		?>
		<button
			<?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			type="button"
			aria-expanded="false"
			aria-controls="newspack-overlay-panel-<?php echo esc_attr( $instance_id ); ?>"
			aria-label="<?php echo esc_attr( $trigger_text ); ?>"
		>
			<?php if ( $show_icon ) : ?>
				<span class="overlay-menu__icon" aria-hidden="true">
					<?php echo self::ICON_MENU; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			<?php endif; ?>
			<span class="<?php echo esc_attr( $text_class ); ?>">
				<?php echo esc_html( $trigger_text ); ?>
			</span>
		</button>
		<?php
		return ob_get_clean();
	}
}

Overlay_Menu_Trigger_Block::init();
