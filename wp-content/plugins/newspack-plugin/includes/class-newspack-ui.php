<?php
/**
 * Class for Newspack UI styles.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Class for reCAPTCHA integration.
 */
class Newspack_UI {
	/**
	 * Initialize hooks.
	 */
	public static function init() {
		\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_styles' ] );
		\add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_styles' ] );
		\add_filter( 'the_content', [ __CLASS__, 'load_demo' ] );
		// Only run if the site is using a block theme.
		if ( wp_theme_has_theme_json() ) {
			\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'colors_css_wrap' ] );
		}
	}

	/**
	 * Enqueue styles for the Newspack UI.
	 */
	public static function enqueue_styles() {
		\wp_enqueue_style(
			'newspack-ui',
			Newspack::plugin_url() . '/dist/newspack-ui.css',
			[],
			NEWSPACK_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'newspack-ui',
			Newspack::plugin_url() . '/dist/newspack-ui.js',
			[],
			NEWSPACK_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Adds inline styles CSS for the element/button colors from the theme.json.
	 * See: https://developer.wordpress.org/reference/functions/wp_get_global_styles/
	 */
	public static function colors_css_wrap() {
		$global_styles = wp_get_global_styles();

		$custom_css = 'body {';
		if ( isset( $global_styles['elements']['button']['color']['background'] ) ) {
			$custom_css .= '--newspack-ui-color-primary: ' . $global_styles['elements']['button']['color']['background'] . ';';
		}
		if ( isset( $global_styles['elements']['button']['color']['text'] ) ) {
			$custom_css .= '--newspack-ui-color-against-primary: ' . $global_styles['elements']['button']['color']['text'] . ';';
		}
		$custom_css .= '}';
		wp_add_inline_style( 'newspack-ui', $custom_css );
	}

	/**
	 * Make a page to demo these components
	 */
	public static function return_demo_content() {
		ob_start();
		?>
		<div class="newspack-ui">
			<h1>Component Demo</h1>

			<ul>
				<li><a href="?ui-demo#typography">Typography</a></li>
				<li><a href="?ui-demo#boxes">Boxes</a></li>
				<li><a href="?ui-demo#form-elements">Form Elements</a></li>
				<li><a href="?ui-demo#checkbox-radio-lists">Checkbox/Radio Lists</a></li>
				<li><a href="?ui-demo#order-table">Order table</a></li>
				<li><a href="?ui-demo#buttons">Buttons</a></li>
				<li><a href="#buttons-icon">Buttons Icon</a></li>
				<li><a href="?ui-demo#modals">Modals</a></li>
			</ul>

			<hr>

			<h2 id="typography">Typography</h2>

			<p class="newspack-ui__font--2xs">2X-Small text</p>
			<p class="newspack-ui__font--xs">X-Small text</p>
			<p>Small text (default)</p>
			<p class="newspack-ui__font--m">Medium text</p>
			<p class="newspack-ui__font--l">Large text</p>
			<p class="newspack-ui__font--xl">X-Large text</p>
			<p class="newspack-ui__font--2xl">2X-Large text</p>
			<p class="newspack-ui__font--3xl">3X-Large text</p>
			<p class="newspack-ui__font--4xl">4X-Large text</p>
			<p class="newspack-ui__font--5xl">5X-Large text</p>
			<p class="newspack-ui__font--6xl">6X-Large text</p>

			<hr>

			<h2 id="boxes">Boxes</h2> <?php // TODO: figure out correct name. ?>

			<div class="newspack-ui__box">
				<p>Default box style</p>
			</div>

			<div class="newspack-ui__box newspack-ui__box--border">
				<p>Border box style</p>
			</div>

			<div class="newspack-ui__box newspack-ui__box--success">
				<p>"Success" box style</p>
			</div>

			<div class="newspack-ui__box newspack-ui__box--success newspack-ui__box--text-center">
				<span class="newspack-ui__icon newspack-ui__icon--success">
					<?php \Newspack\Newspack_UI_Icons::print_svg( 'check' ); ?>
				</span>
				<p>
					<strong>Success box style, plus icon + <code>newspack-ui__box--text-center</code> class.</strong>
				</p>
				<p>Plus a little bit of text below it.</p>
			</div>

			<div class="newspack-ui__box newspack-ui__box--warning newspack-ui__box--text-center">
				<span class="newspack-ui__icon newspack-ui__icon--warning">
					<?php \Newspack\Newspack_UI_Icons::print_svg( 'check' ); ?>
				</span>
				<p>
					<strong>Warning box style, plus icon + <code>newspack-ui__box--text-center</code> class.</strong>
				</p>
				<p>Plus a little bit of text below it.</p>
			</div>

			<div class="newspack-ui__box newspack-ui__box--error newspack-ui__box--text-center">
				<span class="newspack-ui__icon newspack-ui__icon--error">
					<?php \Newspack\Newspack_UI_Icons::print_svg( 'check' ); ?>
				</span>
				<p>
					<strong>Error box style, plus icon + <code>newspack-ui__box--text-center</code> class.</strong>
				</p>
				<p>Plus a little bit of text below it.</p>
			</div>

			<hr>

			<h2 id="notices">Notices</h2>

			<div class="newspack-ui__notice">
				<div>
					<p>Default notice style</p>
				</div>
			</div>

			<div class="newspack-ui__notice newspack-ui__notice--success">
				<div>
					<p>"Success" notice style</p>
				</div>
			</div>

			<div class="newspack-ui__notice newspack-ui__notice--warning">
				<div>
					<p>"Warning" notice style</p>
				</div>
			</div>

			<div class="newspack-ui__notice newspack-ui__notice--error">
				<div>
					<p>"Error" notice style</p>
				</div>
			</div>

			<div class="newspack-ui__notice">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'info' ); ?>
				<div>
					<p>Default notice with icon style</p>
				</div>
			</div>

			<div class="newspack-ui__notice newspack-ui__notice--success">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'check' ); ?>
				<div>
					<p>"Success" notice with icon style</p>
				</div>
			</div>

			<div class="newspack-ui__notice newspack-ui__notice--warning">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'info' ); ?>
				<div>
					<p>"Warning" notice with icon style</p>
				</div>
			</div>

			<div class="newspack-ui__notice newspack-ui__notice--error">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'error' ); ?>
				<div>
					<p>"Error" notice with icon style</p>
				</div>
			</div>

			<hr>

			<h2 id="form-elements">Form elements</h2>
			<form>
				<p>
					<label for="text-input-demo">Text input</label>
					<input type="text" placeholder="Regular text">
				</p>

				<p>
					<label for="email-input-demo">Email input <abbr class="newspack-ui__required" title="required">*</abbr></label>
					<input type="email" placeholder="Email Address">
				</p>

				<p>
					<label for="text-input-demo">Text input <span class="newspack-ui__label-optional">(additional text)</span> <abbr class="newspack-ui__required" title="required">*</abbr></label>
					<input type="text" placeholder="Regular text">
					<span class="newspack-ui__helper-text">Some helper text.</span>
				</p>

				<p>
					<label for="text-input-demo" class="newspack-ui__field-error">Text input <span class="newspack-ui__label-optional">(additional text)</span></label>
					<input type="text" placeholder="Regular text" class="newspack-ui__field-error">
					<span class="newspack-ui__helper-text">Some helper text.</span>
					<span class="newspack-ui__helper-text newspack-ui__inline-error">An error message.</span>
				</p>

				<p>
					<label>
						<input type="radio" name="radio-control-demo">
						This is a radio input.
						<span class="newspack-ui__helper-text">Some helper text.</span>
					</label>
				</p>

				<p>
					<label>
						<input type="radio" name="radio-control-demo">
						This is a radio input.
					</label>
				</p>

				<p>
					<label class="newspack-ui__field-error">
						<input type="radio" name="radio-control-demo">
						This is a radio input.
						<span class="newspack-ui__helper-text">Some helper text.</span>
						<span class="newspack-ui__helper-text newspack-ui__inline-error">An error message.</span>
					</label>
				</p>

				<p>
					<label>
						<input type="checkbox">
						This is a checkbox input.
					</label>
				</p>

				<p>
					<label class="newspack-ui__field-error">
						<input type="checkbox">
						This is a checkbox input.
					</label>
				</p>
			</form>


			<hr>

			<h2 id="checkbox-radio-lists">Checkbox/Radio Lists</h2>

			<label class="newspack-ui__input-card">
				<input type="radio" name="list-radio-option" checked>
				<strong>The Weekly</strong>
				<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
			</label>

			<label class="newspack-ui__input-card">
				<input type="radio" name="list-radio-option">
				<strong>The Weekly</strong>
				<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
			</label>

			<br>

			<label class="newspack-ui__input-card">
				<input type="checkbox" name="checkbox-option-1">
				<strong>The Weekly</strong>
				<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
			</label>

			<label class="newspack-ui__input-card">
				<input type="checkbox" name="checkbox-option-1">
				<strong>The Weekly</strong>
				<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
			</label>

			<label class="newspack-ui__input-card">
				<input type="checkbox" name="checkbox-option-2">
				<strong>The Weekly</strong>
				<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
			</label>

			<br>

			<label class="newspack-ui__input-card">
				<input type="radio" name="list-radio-option" checked>
				<strong>The Weekly</strong>
				<span class="newspack-ui__badge newspack-ui__badge--primary">Badge</span>
				<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
			</label>

			<label class="newspack-ui__input-card">
				<input type="radio" name="list-radio-option">
				<strong>The Weekly</strong>
				<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
			</label>

			<hr>

			<h2 id="order-table">Order table</h2>
			<h3 id="order_review_heading">Transaction details</h3>
			<div id="order_review" class="woocommerce-checkout-review-order newspack-ui__box">
				<table class="shop_table woocommerce-checkout-review-order-table" style="position: static; zoom: 1;">
					<thead>
						<tr>
							<th class="product-name">Product</th>
							<th class="product-total">Subtotal</th>
						</tr>
					</thead>
					<tbody>
						<tr class="cart_item">
							<td class="product-name">
								Donate: Yearly&nbsp; <strong class="product-quantity">×&nbsp;1</strong>
							</td>
							<td class="product-total">
								<span class="subscription-price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>180.00</bdi></span> <span class="subscription-details"> / year</span></span>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr class="cart-subtotal">
							<th>Subtotal</th>
							<td><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>180.00</bdi></span></td>
						</tr>

						<tr class="tax-rate tax-rate-ca-bc-gst-5-1">
							<th>GST 5%</th>
							<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>9.00</span></td>
						</tr>
						<tr class="tax-rate tax-rate-ca-bc-pst-7-2">
							<th>PST (7%)</th>
							<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>12.60</span></td>
						</tr>
						<tr class="order-total">
							<th>Total</th>
							<td><strong><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>201.60</bdi></span></strong> </td>
						</tr>
					</tfoot>
				</table>
			</div>

			<hr>

			<h2 id="badges">Badges</h2>
			<span class="newspack-ui__badge newspack-ui__badge--primary">Badge</span><br>
			<span class="newspack-ui__badge newspack-ui__badge--secondary">Badge</span><br>
			<span class="newspack-ui__badge newspack-ui__badge--outline">Badge</span><br>
			<span class="newspack-ui__badge newspack-ui__badge--success">Badge</span><br>
			<span class="newspack-ui__badge newspack-ui__badge--error">Badge</span><br>
			<span class="newspack-ui__badge newspack-ui__badge--warning">Badge</span><br>


			<hr>

			<h2 id="buttons">Buttons</h2>
			<p><code>newspack-ui__button--primary</code>, <code>--branded</code>, <code>--secondary</code>, <code>--ghost</code>, and <code>--destructive</code> classes for colours/borders, and <code>newspack-ui__button--wide</code> for being 100% wide</p>
			<button class="newspack-ui__button newspack-ui__button--primary">Primary Button</button><br>
			<button class="newspack-ui__button newspack-ui__button--primary" disabled>Primary Button Disabled</button><br>
			<button class="newspack-ui__button newspack-ui__button--branded">Branded Button</button><br>
			<button class="newspack-ui__button newspack-ui__button--branded" disabled>Branded Button Disabled</button><br>
			<button class="newspack-ui__button newspack-ui__button--secondary">Secondary Button</button><br>
			<button class="newspack-ui__button newspack-ui__button--secondary" disabled>Secondary Button Disabled</button><br>
			<button class="newspack-ui__button newspack-ui__button--ghost">Ghost Button</button><br>
			<button class="newspack-ui__button newspack-ui__button--ghost" disabled>Ghost Button Disabled</button><br>
			<button class="newspack-ui__button newspack-ui__button--outline">Outline Button</button><br>
			<button class="newspack-ui__button newspack-ui__button--outline" disabled>Outline Button Disabled</button><br>
			<button class="newspack-ui__button newspack-ui__button--destructive">Destructive Button</button><br>
			<button class="newspack-ui__button newspack-ui__button--destructive" disabled>Destructive Button Disabled</button><br>
			<button class="newspack-ui__button newspack-ui__button--secondary">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'google' ); ?>
				<span>
					Sign in with Google
				</span>
			</button>

			<h3>Wide buttons</h3>
			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--wide">Primary Button</button>
			<button class="newspack-ui__button newspack-ui__button--branded newspack-ui__button--wide">Branded Button</button>
			<button class="newspack-ui__button newspack-ui__button--secondary newspack-ui__button--wide">Secondary Button</button>
			<button class="newspack-ui__button newspack-ui__button--ghost newspack-ui__button--wide">Ghost Button</button>
			<button class="newspack-ui__button newspack-ui__button--secondary newspack-ui__button--wide">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'google' ); ?>
				<span>
					Sign up with Google
				</span>
			</button>

			<hr>

			<p>Uses the <code>newspack-ui__button--x-small</code> and <code>newspack-ui__button--small</code> classes to get different sizes (medium is the default for this button styles).</p>

			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--x-small">X-Small Button</button><br />
			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--x-small">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'account' ); ?>
				X-Small Button
			</button><br />
			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--x-small">
				X-Small Button
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'account' ); ?>
			</button><br />
			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--small">Small Button</button><br />
			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--small">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'account' ); ?>
				Small Button
			</button><br />
			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--small">
				Small Button
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'account' ); ?>
			</button><br />
			<button class="newspack-ui__button newspack-ui__button--primary">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'account' ); ?>
				Medium Button (default)
			</button><br />
			<button class="newspack-ui__button newspack-ui__button--primary">
				Medium Button (default)
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'account' ); ?>
			</button>

			<hr>

			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--loading">
				<span>Loading Button</span>
			</button><br>

			<button class="newspack-ui__button newspack-ui__button--outline newspack-ui__button--small newspack-ui__button--loading">
				<span>Loading Button</span>
			</button><br>

			<button class="newspack-ui__button newspack-ui__button--destructive newspack-ui__button--x-small newspack-ui__button--loading">
				<span>Loading Button</span>
			</button>

			<hr>

			<h2>Segmented Controls</h2>

			<div class="newspack-ui__segmented-control">
				<div class="newspack-ui__segmented-control__tabs">
					<button class="newspack-ui__button newspack-ui__button--small selected">Tab One</button>
					<button class="newspack-ui__button newspack-ui__button--small">Tab Two</button>
					<button class="newspack-ui__button newspack-ui__button--small">Tab Three</button>
					<button class="newspack-ui__button newspack-ui__button--small">Tab Four</button>
					<button class="newspack-ui__button newspack-ui__button--small">Tab Five</button>
				</div>
			</div>

			<div class="newspack-ui__segmented-control">
				<div class="newspack-ui__segmented-control__tabs">
					<button class="newspack-ui__button newspack-ui__button--medium selected">Tab One</button>
					<button class="newspack-ui__button newspack-ui__button--medium">Tab Two</button>
					<button class="newspack-ui__button newspack-ui__button--medium">Tab Three</button>
					<button class="newspack-ui__button newspack-ui__button--medium">Tab Four</button>
				</div>
			</div>

			<hr>

			<div class="newspack-ui__segmented-control">
				<div class="newspack-ui__segmented-control__tabs">
					<button class="newspack-ui__button newspack-ui__button--small selected">Monthly</button>
					<button class="newspack-ui__button newspack-ui__button--small">Annually</button>
				</div>
				<div class="newspack-ui__segmented-control__content">
					<div class="newspack-ui__segmented-control__panel">
						<label class="newspack-ui__input-card">
							<input type="checkbox" name="checkbox-option-1">
							<strong>Monthly Option 1</strong>
							<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
						</label>

						<label class="newspack-ui__input-card">
							<input type="checkbox" name="checkbox-option-1">
							<strong>Monthly Option 2</strong>
							<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
						</label>
					</div><!-- .newspack-ui__segmented-control__panel -->
					<div class="newspack-ui__segmented-control__panel">
						<label class="newspack-ui__input-card">
							<input type="checkbox" name="checkbox-option-1">
							<strong>Annual Option 1</strong>
							<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
						</label>

						<label class="newspack-ui__input-card">
							<input type="checkbox" name="checkbox-option-1">
							<strong>Annual Option 2</strong>
							<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
						</label>
					</div><!-- .newspack-ui__segmented-control__panel -->
				</div><!-- .newspack-ui__segmented-control__content -->
			</div><!-- .newspack-ui__segmented-control -->

			<hr>

			<h2 id="buttons-icon">Buttons Icon</h2>

			<p>Uses the same classes as the <code>newspack-ui__button</code> but we add an extra class to it <code>newspack-ui__button--icon</code></p>
			<button class="newspack-ui__button newspack-ui__button--icon">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>
			<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--icon">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>
			<button class="newspack-ui__button newspack-ui__button--branded newspack-ui__button--icon">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>
			<button class="newspack-ui__button newspack-ui__button--secondary newspack-ui__button--icon">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>
			<button class="newspack-ui__button newspack-ui__button--ghost newspack-ui__button--icon">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>
			<button class="newspack-ui__button newspack-ui__button--outline newspack-ui__button--icon">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>
			<button class="newspack-ui__button newspack-ui__button--destructive newspack-ui__button--icon">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>

			<p>Uses the <code>newspack-ui__button--small</code> and <code>newspack-ui__button--medium</code> CSS classes to get different sizes (x-small is the default).</p>

			<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--small">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>

			<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--medium">
				<?php \Newspack\Newspack_UI_Icons::print_svg( 'menu' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open Menu', 'newspack-plugin' ); ?></span>
			</button>

			<hr>

			<h2 id="modals">Modals</h2>

			<div class="newspack-ui__box">

				<div class="newspack-ui__modal">
					<header class="newspack-ui__modal__header">
						<h2>This is a header</h2>
						<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost newspack-ui__modal__close">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'newspack-plugin' ); ?></span>
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'close' ); ?>
						</button>
					</header>

					<section class="newspack-ui__modal__content">
						<p>This is the modal content</p>
					</section>

					<footer class="newspack-ui__modal__footer">
						<p>This is the modal footer.</p>
					</footer>
				</div><!-- .newspack-ui__modal -->
			</div><!-- .newspack-ui__box -->

			<h2>Small size</h2>

			<div class="newspack-ui__box">
				<div class="newspack-ui__modal newspack-ui__modal--small">
					<header class="newspack-ui__modal__header">
						<h2>Auth Modal Contents Default</h2>

						<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost newspack-ui__modal__close">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'newspack-plugin' ); ?></span>
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'close' ); ?>
						</button>
					</header>

					<section class="newspack-ui__modal__content">

						<button class="newspack-ui__button newspack-ui__button--secondary newspack-ui__button--wide">
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'google' ); ?>
							Sign in with Google
						</button>

						<div class="newspack-ui__word-divider">
							Or
						</div>

						<form>
							<p>
								<label for="email-input-demo">Email input</label>
								<input type="email" placeholder="Email Address">
							</p>

							<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--wide">Sign In</button>
							<button class="newspack-ui__button newspack-ui__button--ghost newspack-ui__button--wide">Sign in to existing account</button>
						</form>
					</section>

					<footer class="newspack-ui__modal__footer">
						<p>This is the modal footer.</p>
					</footer>
				</div><!-- .newspack-ui__modal--small -->
			</div><!-- .newspack-ui__box -->

			<div class="newspack-ui__box">
				<div class="newspack-ui__modal newspack-ui__modal--small">
					<header class="newspack-ui__modal__header">
						<h2>Auth Modal Contents OTP</h2>

						<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost newspack-ui__modal__close">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'newspack-plugin' ); ?></span>
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'close' ); ?>
						</button>
					</header>

					<section class="newspack-ui__modal__content">
						<form>
							<p>
								<label>Entry the code sent to your email</label>
								<div class="newspack-ui__code-input">
									<input type="text" maxlength="1">
									<input type="text" maxlength="1">
									<input type="text" maxlength="1">
									<input type="text" maxlength="1">
									<input type="text" maxlength="1">
									<input type="text" maxlength="1">
								</div>
							</p>

							<p class="newspack-ui__helper-text">Sign in by entering the code sent to email@address.com, or by clicking the magic link in the email.</p>

							<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--wide">Continue</button>
							<button class="newspack-ui__button newspack-ui__button--secondary newspack-ui__button--wide">Resend Code</button>
							<button class="newspack-ui__button newspack-ui__button--ghost newspack-ui__button--wide">Go Back</button>
						</form>
					</section>
				</div><!-- .newspack-ui__modal--small -->
			</div><!-- .newspack-ui__box -->

			<div class="newspack-ui__box">
				<div class="newspack-ui__modal newspack-ui__modal--small">
					<header class="newspack-ui__modal__header">
						<h2>Auth Modal Contents Success</h2>

						<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost newspack-ui__modal__close">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'newspack-plugin' ); ?></span>
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'close' ); ?>
						</button>
					</header>

					<section class="newspack-ui__modal__content">

						<div class="newspack-ui__box newspack-ui__box--success newspack-ui__box--text-center">
							<span class="newspack-ui__icon newspack-ui__icon--success">
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'check' ); ?>
							</span>

							<p>
								<strong>Success! Your account was created and you're signed in.</strong>
							</p>

							<p>In the future, you'll sign in with a code sent to your email. If you'd rather use a password, you can set one in <a href="#">My Account</a>.</p>
						</div>


						<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--wide">Continue</button>
					</section>
				</div><!-- .newspack-ui__modal--small -->
			</div><!-- .newspack-ui__box -->


			<div class="newspack-ui__box">
				<div class="newspack-ui__modal newspack-ui__modal--small">
					<header class="newspack-ui__modal__header">
						<h2>Auth Modal Contents Success + PW</h2>

						<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost newspack-ui__modal__close">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'newspack-plugin' ); ?></span>
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'close' ); ?>
						</button>
					</header>

					<section class="newspack-ui__modal__content">

						<div class="newspack-ui__box newspack-ui__box--success newspack-ui__box--text-center">
							<span class="newspack-ui__icon newspack-ui__icon--success">
								<?php \Newspack\Newspack_UI_Icons::print_svg( 'check' ); ?>
							</span>

							<p>
								<strong>Success! Your account was created and you're signed in.</strong>
							</p>
						</div>

						<form>
							<p>
								<label>Set a display name</label>
								<input type="text">
								<span class="newspack-ui__helper-text">This will be used to address you in emails, and when you leave comments.</span>
							</p>

							<p>
								<label>Create password</label>
								<input type="password">
							</p>
							<p>
								<label>Confirm Password</label>
								<input type="password">
								<span class="newspack-ui__helper-text">If you don't set a password, you can always log in with a magic link or one-time code sent to your email.</span>
							</p>

							<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--wide">Continue</button>
							<button class="newspack-ui__button newspack-ui__button--ghost newspack-ui__button--wide">Skip for now</button>
						</form>
					</section>
				</div><!-- .newspack-ui__modal--small -->
			</div><!-- .newspack-ui__box -->


			<div class="newspack-ui__box">
				<div class="newspack-ui__modal newspack-ui__modal--small">
					<header class="newspack-ui__modal__header">
						<h2>Auth Modal Newsletter Sign Up</h2>

						<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost newspack-ui__modal__close">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'newspack-plugin' ); ?></span>
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'close' ); ?>
						</button>
					</header>

					<section class="newspack-ui__modal__content">

						<p>Get the best of The News Paper directly to your email inbox.<br>
						<span class="newspack-ui__color-text-gray">Sending to: email@address.</span></p>

						<label class="newspack-ui__input-card">
							<input type="checkbox" name="checkbox-option-1">
							<span>
								<strong>The Weekly</strong>
								<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
							</span>
						</label>

						<label class="newspack-ui__input-card">
							<input type="checkbox" name="checkbox-option-2">
							<span>
								<strong>The Weekly</strong>
								<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
							</span>
						</label>

						<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--wide">Continue</button>
					</section>
				</div><!-- .newspack-ui__modal--small -->
			</div><!-- .newspack-ui__box -->

			<div class="newspack-ui__box">
				<div class="newspack-ui__modal newspack-ui__modal--small">
					<header class="newspack-ui__modal__header">
						<h2>Change Subscription</h2>

						<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost newspack-ui__modal__close">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'newspack-plugin' ); ?></span>
							<?php \Newspack\Newspack_UI_Icons::print_svg( 'close' ); ?>
						</button>
					</header>

					<section class="newspack-ui__modal__content">
						<div class="newspack-ui__segmented-control">
							<div class="newspack-ui__segmented-control__tabs">
								<button class="newspack-ui__button newspack-ui__button--small selected">Monthly</button>
								<button class="newspack-ui__button newspack-ui__button--small">Annually</button>
							</div>
							<div class="newspack-ui__segmented-control__content">
								<div class="newspack-ui__segmented-control__panel">
									<label class="newspack-ui__input-card">
										<input type="checkbox" name="checkbox-option-1">
										<strong>Monthly Option 1</strong>
										<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
									</label>

									<label class="newspack-ui__input-card">
										<input type="checkbox" name="checkbox-option-1">
										<strong>Monthly Option 2</strong>
										<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
									</label>
								</div><!-- .newspack-ui__segmented-control__panel -->
								<div class="newspack-ui__segmented-control__panel">
									<label class="newspack-ui__input-card">
										<input type="checkbox" name="checkbox-option-1">
										<strong>Annual Option 1</strong>
										<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
									</label>

									<label class="newspack-ui__input-card">
										<input type="checkbox" name="checkbox-option-1">
										<strong>Annual Option 2</strong>
										<span class="newspack-ui__helper-text">Friday roundup of the most relevant stories.</span>
									</label>
								</div><!-- .newspack-ui__segmented-control__panel -->
							</div><!-- .newspack-ui__segmented-control__content -->
						</div><!-- .newspack-ui__segmented-control -->
						<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--wide">Change Subscription</button>
						<button class="newspack-ui__button newspack-ui__button--ghost newspack-ui__button--wide">Cancel</button>
					</section>
				</div><!-- .newspack-ui__modal--small -->
			</div><!-- .newspack-ui__box -->

			<button id="open-modal-example" class="newspack-ui__button newspack-ui__button--primary">Open Modal</button>
			<div id="newspack-modal-example" class="newspack-ui__modal-container">
				<div class="newspack-ui__modal-container__overlay"></div>
				<div class="newspack-ui__modal newspack-ui__modal--small">
						<header class="newspack-ui__modal__header">
							<h2>Auth Modal Contents Default</h2>

							<button class="newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost newspack-ui__modal__close">
								<span class="screen-reader-text"><?php esc_html_e( 'Close', 'newspack-plugin' ); ?></span>
								<?php \Newspack\Newspack_UI_Icons::print_svg( 'close' ); ?>
							</button>
						</header>

						<section class="newspack-ui__modal__content">

							<button class="newspack-ui__button newspack-ui__button--secondary newspack-ui__button--wide">
								<?php \Newspack\Newspack_UI_Icons::print_svg( 'google', 20 ); ?>
								Sign in with Google
							</button>

							<div class="newspack-ui__word-divider">
								Or
							</div>

							<form>
								<p>
									<label for="email-input-demo">Email input</label>
									<input type="email" placeholder="Email Address">
								</p>

								<button class="newspack-ui__button newspack-ui__button--primary newspack-ui__button--wide">Sign In</button>
								<button class="newspack-ui__button newspack-ui__button--ghost newspack-ui__button--wide">Sign in to existing account</button>
							</form>
						</section>

						<footer class="newspack-ui__modal__footer">
							<p>This is the modal footer.</p>
						</footer>
					</div><!-- .newspack-ui__modal__small -->
			</div> <!-- .newspack-ui__modal-container -->
			<script>
				( function() {
					var newspackModal = document.getElementById( 'newspack-modal-example' );
					var openModal = document.getElementById( 'open-modal-example' );
					var closeModal = newspackModal.querySelector( '.newspack-ui__modal__close' );
					openModal.onclick = function() {
						newspackModal.setAttribute( 'data-state', 'open' );
					}
					closeModal.onclick = function() {
						newspackModal.setAttribute( 'data-state', 'closed' );
					}
				} )();
			</script>

		</div><!-- .newspack-ui -->
		<?php
		return ob_get_clean();
	}

	/**
	 * Append the demo content when the ui-demo query string is used.
	 *
	 * @param string $content The page content.
	 * @return string Modified $content with demo appended.
	 */
	public static function load_demo( $content ) {
		if ( isset( $_REQUEST['ui-demo'] ) && current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$content .= self::return_demo_content();
		}
		return $content;
	}
}
Newspack_UI::init();
