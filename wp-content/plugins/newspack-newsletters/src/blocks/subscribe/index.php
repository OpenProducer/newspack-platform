<?php
/**
 * Newspack Blocks.
 *
 * @package Newspack
 */

namespace Newspack_Newsletters\Blocks\Subscribe;

defined( 'ABSPATH' ) || exit;

const FORM_ACTION = 'newspack_newsletters_subscribe';

/**
 * Register block from metadata.
 */
function register_block() {
	register_block_type_from_metadata(
		__DIR__ . '/block.json',
		array(
			'render_callback' => __NAMESPACE__ . '\\render_block',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\register_block' );

/**
 * Enqueue front-end scripts.
 */
function enqueue_scripts() {
	$handle = 'newspack-newsletters-subscribe-block';
	\wp_enqueue_style(
		$handle,
		plugins_url( '../../../dist/subscribeBlock.css', __FILE__ ),
		[],
		filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/subscribeBlock.css' )
	);

	$use_captcha  = method_exists( '\Newspack\Recaptcha', 'can_use_captcha' ) && \Newspack\Recaptcha::can_use_captcha();
	$dependencies = [ 'wp-polyfill', 'wp-i18n' ];
	if ( $use_captcha ) {
		$dependencies[] = \Newspack\Recaptcha::SCRIPT_HANDLE;
	}

	\wp_enqueue_script(
		$handle,
		plugins_url( '../../../dist/subscribeBlock.js', __FILE__ ),
		$dependencies,
		filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/subscribeBlock.js' ),
		true
	);
	\wp_script_add_data( $handle, 'async', true );
	\wp_script_add_data( $handle, 'amp-plus', true );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

/**
 * Generate a unique ID for each subscription form.
 *
 * The ID for each form instance is unique only for each page render.
 * The main intent is to be able to pass this ID to analytics so we
 * can identify what type of form it is, so the ID doesn't need to be
 * predictable nor consistent across page renders.
 *
 * @return string A unique ID string to identify the form.
 */
function get_form_id() {
	return \wp_unique_id( 'newspack-subscribe-' );
}

/**
 * Render Registration Block.
 *
 * @param array[] $attrs Block attributes.
 */
function render_block( $attrs ) {
	$list_config = \Newspack_Newsletters_Subscription::get_lists_config();
	if ( empty( $list_config ) || \is_wp_error( $list_config ) ) {
		return;
	}
	$block_id        = \wp_rand( 0, 99999 );
	$subscribed      = false;
	$message         = '';
	$email           = '';
	$lists           = array_keys( $list_config );
	$list_map        = array_flip( $lists );
	$available_lists = array_values( array_intersect( $lists, $attrs['lists'] ) );

	if ( empty( $available_lists ) ) {
		$available_lists = [ $lists[0] ];
	}

	if ( \is_user_logged_in() ) {
		$email = \wp_get_current_user()->user_email;
	} elseif ( class_exists( '\Newspack\Reader_Activation' ) ) {
		try {
			if ( \Newspack\Reader_Activation::is_enabled() ) {
				$email = \Newspack\Reader_Activation::get_auth_intention_value();
			}
		} catch ( \Throwable $th ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			// Fail silently.
		}
	}

	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( isset( $_REQUEST['newspack_newsletters_subscribed'] ) ) {
		$subscribed = \absint( $_REQUEST['newspack_newsletters_subscribed'] );
		if ( isset( $_REQUEST['message'] ) ) {
			$message = \sanitize_text_field( $_REQUEST['message'] );
		}
		if ( isset( $_REQUEST['npe'] ) ) {
			$email = \sanitize_text_field( $_REQUEST['npe'] );
		}
		if ( isset( $_REQUEST['lists'] ) && is_array( $_REQUEST['lists'] ) ) {
			$list_map = array_flip( array_map( 'sanitize_text_field', $_REQUEST['lists'] ) );
		}
	}
	// phpcs:enable
	ob_start();
	?>
	<div class="newspack-newsletters-subscribe <?php echo esc_attr( get_block_classes( $attrs ) ); ?>">
		<?php if ( $subscribed ) : ?>
			<p class="message"><?php echo \esc_html( $message ); ?></p>
		<?php else : ?>
			<form id="<?php echo esc_attr( get_form_id() ); ?>">
				<?php \wp_nonce_field( FORM_ACTION, FORM_ACTION ); ?>
				<?php
				/**
				 * Action to add custom fields before the form fields of the Newsletter Subscription block.
				 *
				 * @param array $attrs Block attributes.
				 */
				do_action( 'newspack_newsletters_subscribe_block_before_form_fields', $attrs );
				?>
				<?php if ( 1 < count( $available_lists ) ) : ?>
					<div class="newspack-newsletters-lists">
						<ul>
						<?php
						foreach ( $available_lists as $list_id ) :
							if ( ! isset( $list_config[ $list_id ] ) ) {
								continue;
							}
							$list        = $list_config[ $list_id ];
							$checkbox_id = sprintf( 'newspack-newsletters-%s-list-checkbox-%s', $block_id, $list_id );
							?>
							<li>
								<span class="list-checkbox">
									<input
										type="checkbox"
										name="lists[]"
										value="<?php echo \esc_attr( $list_id ); ?>"
										id="<?php echo \esc_attr( $checkbox_id ); ?>"
										<?php if ( isset( $list_map[ $list_id ] ) ) : ?>
											checked
										<?php endif; ?>
									/>
								</span>
								<span class="list-details">
									<label for="<?php echo \esc_attr( $checkbox_id ); ?>">
										<span class="list-title"><?php echo \esc_html( $list['title'] ); ?></span>
										<?php if ( $attrs['displayDescription'] ) : ?>
											<span class="list-description"><?php echo \esc_html( $list['description'] ); ?></span>
										<?php endif; ?>
									</label>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
					</div>
				<?php else : ?>
					<input type="hidden" name="lists[]" value="<?php echo \esc_attr( $available_lists[0] ); ?>" />
				<?php endif; ?>
				<?php
				if ( $attrs['displayNameField'] ) :
					$name_placeholder      = $attrs['namePlaceholder'];
					$last_name_placeholder = $attrs['lastNamePlaceholder'];
					$display_last_name     = $attrs['displayLastNameField'];
					if ( empty( $name_placeholder ) ) {
						$name_placeholder = $display_last_name ? __( 'First Name', 'newspack-newsletters' ) : __( 'Name', 'newspack-newsletters' );
					}
					?>
					<div class="newspack-newsletters-name-input">
						<input type="text" name="name" placeholder="<?php echo \esc_attr( $name_placeholder ); ?>" />
						<?php if ( $display_last_name ) : ?>
							<input type="text" name="last_name" placeholder="<?php echo \esc_attr( $last_name_placeholder ); ?>" />
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="newspack-newsletters-email-input">
					<input
						type="email"
						name="npe"
						autocomplete="email"
						placeholder="<?php echo \esc_attr( $attrs['placeholder'] ); ?>"
						value="<?php echo esc_attr( $email ); ?>"
					/>
					<input
						class="nphp"
						tabindex="-1"
						aria-hidden="true"
						type="email"
						name="email"
						autocomplete="email"
						placeholder="<?php echo \esc_attr( $attrs['placeholder'] ); ?>"
						value=""
					/>
					<input type="submit" value="<?php echo \esc_attr( $attrs['label'] ); ?>" />
				</div>
			</form>
			<div class="newspack-newsletters-subscribe-response">
				<?php if ( ! empty( $message ) ) : ?>
					<p><?php echo \esc_html( $message ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Utility to assemble the class for a server-side rendered block.
 *
 * @param array $attrs Block attributes.
 *
 * @return string Class list separated by spaces.
 */
function get_block_classes( $attrs = [] ) {
	$classes = [];
	if ( isset( $attrs['align'] ) && ! empty( $attrs['align'] ) ) {
		$classes[] = 'align' . $attrs['align'];
	}
	if ( isset( $attrs['className'] ) ) {
		array_push( $classes, $attrs['className'] );
	}
	if ( 1 < count( $attrs['lists'] ) ) {
		$classes[] = 'multiple-lists';
	}
	return implode( ' ', $classes );
}

/**
 * Send the form response to the client, whether it's a JSON or GET request.
 *
 * @param mixed $data The response to send to the client.
 */
function send_form_response( $data ) {
	$is_error = \is_wp_error( $data );
	if ( \wp_is_json_request() ) {
		if ( ! $is_error ) {
			$message = __( 'Thank you for subscribing!', 'newspack' );
		} else {
			$message = $data->get_error_message();
		}
		\wp_send_json( compact( 'message', 'data' ), \is_wp_error( $data ) ? 400 : 200 );
		exit;
	} elseif ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
		$args_to_remove = [
			'_wp_http_referer',
			FORM_ACTION,
		];
		if ( ! $is_error ) {
			$args_to_remove = array_merge( $args_to_remove, [ 'email', 'lists' ] );
		}
		\wp_safe_redirect(
			\add_query_arg(
				[
					'newspack_newsletters_subscribed' => $is_error ? '0' : '1',
					'message'                         => $is_error ? $data->get_error_message() : __( 'Thank you for subscribing!', 'newspack' ),
				],
				\remove_query_arg( $args_to_remove )
			)
		);
		exit;
	}
}

/**
 * Process newsletter signup form.
 */
function process_form() {
	if ( ! isset( $_REQUEST[ FORM_ACTION ] ) || ! \wp_verify_nonce( \sanitize_text_field( $_REQUEST[ FORM_ACTION ] ), FORM_ACTION ) ) {
		return;
	}

	// Honeypot trap.
	if ( ! empty( $_REQUEST['email'] ) ) {
		return send_form_response( [ 'email' => \sanitize_email( $_REQUEST['email'] ) ] );
	}

	// reCAPTCHA test.
	if ( method_exists( '\Newspack\Recaptcha', 'can_use_captcha' ) && \Newspack\Recaptcha::can_use_captcha() ) {
		$captcha_token  = isset( $_REQUEST['captcha_token'] ) ? \sanitize_text_field( $_REQUEST['captcha_token'] ) : '';
		$captcha_result = \Newspack\Recaptcha::verify_captcha( $captcha_token );
		if ( \is_wp_error( $captcha_result ) ) {
			return send_form_response( $captcha_result );
		}
	}

	if ( ! isset( $_REQUEST['npe'] ) || empty( $_REQUEST['npe'] ) ) {
		return send_form_response( new \WP_Error( 'invalid_email', __( 'You must enter a valid email address.', 'newspack-newsletters' ) ) );
	}

	if ( ! isset( $_REQUEST['lists'] ) || ! is_array( $_REQUEST['lists'] ) || empty( $_REQUEST['lists'] ) ) {
		return send_form_response( new \WP_Error( 'no_lists', __( 'You must select a list.', 'newspack-newsletters' ) ) );
	}

	// The "true" email address field is called `npe` due to the honeypot strategy.
	$last_name = isset( $_REQUEST['last_name'] ) ? \sanitize_text_field( $_REQUEST['last_name'] ) : '';
	$name      = trim(
		sprintf(
			'%s %s',
			isset( $_REQUEST['name'] ) ? \sanitize_text_field( $_REQUEST['name'] ) : '',
			$last_name
		)
	);
	$email     = \sanitize_email( $_REQUEST['npe'] );
	$lists     = array_map( 'sanitize_text_field', $_REQUEST['lists'] );
	$popup_id  = isset( $_REQUEST['newspack_popup_id'] ) ? (int) $_REQUEST['newspack_popup_id'] : false;
	$metadata  = [
		'current_page_url'                => home_url( add_query_arg( array(), \wp_get_referer() ) ),
		'newspack_popup_id'               => $popup_id,
		'newsletters_subscription_method' => 'newsletters-subscription-block',
	];

	$result = \Newspack_Newsletters_Subscription::add_contact(
		[
			'name'     => $name ?? null,
			'email'    => $email,
			'metadata' => $metadata,
		],
		$lists
	);

	if ( ! \is_user_logged_in() && \class_exists( '\Newspack\Reader_Activation' ) && \Newspack\Reader_Activation::is_enabled() ) {
		$metadata = array_merge( $metadata, [ 'registration_method' => 'newsletters-subscription' ] );
		if ( $popup_id ) {
			$metadata['registration_method'] = 'newsletters-subscription-popup';
		}
		\Newspack\Reader_Activation::register_reader( $email, $name, true, $metadata );
	}

	/**
	 * Fires after subscribing a user to a list.
	 *
	 * @param string         $email  Email address of the reader.
	 * @param array|WP_Error $result Contact data if it was added, or error otherwise.
	 * @param array          $metadata Some metadata about the subscription. Always contains `current_page_url`, `newspack_popup_id` and `newsletters_subscription_method` keys.
	 */
	\do_action( 'newspack_newsletters_subscribe_form_processed', $email, $result, $metadata );

	return send_form_response( $result );
}
add_action( 'template_redirect', __NAMESPACE__ . '\\process_form' );
