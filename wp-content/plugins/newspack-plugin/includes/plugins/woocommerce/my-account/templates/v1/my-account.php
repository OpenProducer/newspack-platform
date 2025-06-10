<?php
/**
 * My Account page template with no header or footer.
 *
 * @package Newspack
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#main"><?php _e( 'Skip to content', 'newspack' ); ?></a>

	<div id="content" class="site-content">

		<section id="primary" class="content-area">
			<main id="main" class="site-main">
				<?php

				/* Start the Loop */
				while ( have_posts() ) :
					the_post();
					?>

					<div class="main-content">
						<?php the_content(); ?>
					</div>

				<?php endwhile; ?>

			</main><!-- #main -->
		</section><!-- #primary -->

	</div><!-- #content -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
