<?php
/**
 * Newspack Chart Block - horizontal bar chart base spec.
 *
 * @package WordPress
 */

// phpcs:disable
?>

<mjml>
	<mj-head>
		<mj-title><?php echo $title; ?></mj-title>
		<mj-style>
		<?php
			$default_css = file_get_contents( dirname( __FILE__ ) . '/email-template-mjml.css' );
			$css         = $default_css . "\n" . $custom_css;

			echo esc_html( $css );
		?>
		</mj-style>
		<?php if ( isset( $preview_text ) ): ?>
			<mj-preview><?php echo $preview_text; ?></mj-preview>
		<?php endif; ?>
	</mj-head>
	<mj-body background-color="<?php echo $background_color; ?>">
		<?php echo $body; ?>
	</mj-body>
</mjml>
