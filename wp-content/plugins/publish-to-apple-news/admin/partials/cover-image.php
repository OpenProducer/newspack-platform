<?php
/**
 * Publish to Apple News partials: Cover Image template
 *
 * phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
 *
 * @global WP_Post $post
 *
 * @package Apple_News
 */

$apple_cover_image_id      = get_post_meta( $post->ID, 'apple_news_coverimage', true );
$apple_cover_image_caption = get_post_meta( $post->ID, 'apple_news_coverimage_caption', true );

?>
<div class="apple-news-coverimage-image-container">
	<div class="apple-news-coverimage-image">
		<?php
		if ( ! empty( $apple_cover_image_id ) ) {
			echo wp_get_attachment_image( $apple_cover_image_id, 'medium' );
			$apple_add_hidden    = 'hidden';
			$apple_remove_hidden = '';
		} else {
			$apple_add_hidden    = '';
			$apple_remove_hidden = 'hidden';
		}
		?>
	</div>
	<input name="apple_news_coverimage"
		class="apple-news-coverimage-id"
		type="hidden"
		value="<?php echo esc_attr( $apple_cover_image_id ); ?>"
	/>
	<input type="button"
		class="button-primary apple-news-coverimage-add <?php echo esc_attr( $apple_add_hidden ); ?>"
		value="<?php esc_attr_e( 'Add image', 'apple-news' ); ?>"
	/>
	<input type="button"
		class="button-primary apple-news-coverimage-remove <?php echo esc_attr( $apple_remove_hidden ); ?>"
		value="<?php esc_attr_e( 'Remove image', 'apple-news' ); ?>"
	/>
</div>
<div>
	<label for="apple-news-coverimage-caption"><?php esc_html_e( 'Cover Image Caption:', 'apple-news' ); ?></label>
	<textarea id="apple-news-coverimage-caption" name="apple_news_coverimage_caption"><?php echo esc_textarea( $apple_cover_image_caption ); ?></textarea>
</div>
