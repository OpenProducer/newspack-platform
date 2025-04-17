<?php
/**
 * Template for the lite site archive page
 *
 * @package newspack
 */

?>
<!DOCTYPE html>
<html lang="<?php bloginfo( 'language' ); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php bloginfo( 'name' ); ?></title>
	<?php require __DIR__ . '/lite-site-styles.php'; ?>
</head>
<body>
	<header class="back">
		<a href="<?php echo esc_url( home_url() ); ?>" ><?php esc_html_e( 'View full site', 'newspack-plugin' ); ?></a>
	</header>
	<h1><?php bloginfo( 'name' ); ?></h1>
	<hr class="separator">
	<ul>
	<?php
	$query_args = [
		'posts_per_page' => Lite_Site::get_number_of_posts(),
		'post_status'    => 'publish',
	];

	$categories = Lite_Site::get_categories();
	if ( ! empty( $categories ) ) {
		$query_args['category__in'] = $categories;
	}

	$recent_posts = get_posts( $query_args );

	foreach ( $recent_posts as $current_post ) {
		printf(
			'<li><a href="/%s/%d">%s</a></li>',
			esc_attr( Lite_Site::get_url_base() ),
			esc_attr( $current_post->ID ),
			esc_html( $current_post->post_title )
		);
	}
	?>
	</ul>
	<?php
	$footer_html = Lite_Site::get_footer_html();
	if ( ! empty( $footer_html ) ) :
		?>
		<hr class="separator">
		<footer class="site-footer">
			<?php echo wp_kses_post( $footer_html ); ?>
		</footer>
	<?php endif; ?>
</body>
</html>
