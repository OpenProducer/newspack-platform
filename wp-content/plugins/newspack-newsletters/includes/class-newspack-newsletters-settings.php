<?php
/**
 * Newspack Newsletters Settings Page
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manages Settings page.
 */
class Newspack_Newsletters_Settings {
	/**
	 * Set up hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'add_plugin_page' ] );
		add_action( 'admin_init', [ __CLASS__, 'page_init' ] );
		add_action( 'update_option_newspack_newsletters_public_posts_slug', [ __CLASS__, 'update_option_newspack_newsletters_public_posts_slug' ], 10, 2 );
	}

	/**
	 * Retreives list of settings.
	 *
	 * @return array Settings list.
	 */
	public static function get_settings_list() {
		$settings_list = array(
			array(
				'description' => __( 'Service Provider', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_service_provider',
				'options'     => array(
					array(
						'name'  => __( 'Select service provider', 'newspack-newsletter' ),
						'value' => '',
					),
					array(
						'name'  => __( 'Mailchimp', 'newspack-newsletters' ),
						'value' => 'mailchimp',
					),
					array(
						'name'  => __( 'Constant Contact', 'newspack-newsletters' ),
						'value' => 'constant_contact',
					),
					array(
						'name'  => __( 'Campaign Monitor', 'newspack-newsletters' ),
						'value' => 'campaign_monitor',
					),
				),
				'type'        => 'select',
			),
			array(
				'description' => __( 'Mailchimp API Key', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_mailchimp_api_key',
				'type'        => 'text',
			),
			array(
				'description' => __( 'Constant Contact API Key', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_constant_contact_api_key',
				'type'        => 'text',
			),
			array(
				'description' => __( 'Constant Contact API Access token', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_constant_contact_api_access_token',
				'type'        => 'text',
			),
			array(
				'description' => __( 'Campaign Monitor API Key', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_campaign_monitor_api_key',
				'type'        => 'text',
			),
			array(
				'description' => __( 'Campaign Monitor Client ID', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_campaign_monitor_client_id',
				'type'        => 'text',
			),
			array(
				'description' => __( 'MJML Application ID', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_mjml_api_key',
				'type'        => 'text',
			),
			array(
				'description' => __( 'MJML Secret Key', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_mjml_api_secret',
				'type'        => 'text',
			),
			array(
				'default'           => 'newsletter',
				'description'       => __( 'Public Newsletter Posts Slug', 'newspack-newsletters' ),
				'key'               => 'newspack_newsletters_public_posts_slug',
				'sanitize_callback' => 'sanitize_title',
				'type'              => 'text',
			),
		);

		if ( class_exists( 'Jetpack' ) && \Jetpack::is_module_active( 'related-posts' ) ) {
			$settings_list[] = array(
				'description' => __( 'Disable Related Posts on public newsletter posts?', 'newspack-newsletters' ),
				'key'         => 'newspack_newsletters_disable_related_posts',
				'type'        => 'checkbox',
			);
		}

		$settings_list[] = array(
			'description' => __( 'Use Mailchimp Tags?', 'newspack-newsletters' ),
			'key'         => 'newspack_newsletters_use_mailchimp_tags',
			'type'        => 'checkbox',
			'provider'    => 'mailchimp',
		);

		return $settings_list;
	}

	/**
	 * Add options page
	 */
	public static function add_plugin_page() {
		add_submenu_page(
			'edit.php?post_type=' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
			__( 'Newsletters Settings', 'newspack-newsletters' ),
			__( 'Settings', 'newspack-newsletters' ),
			'manage_options',
			'newspack-newsletters-settings-admin',
			[ __CLASS__, 'create_admin_page' ]
		);
	}

	/**
	 * Options page callback
	 */
	public static function create_admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Newsletters Settings', 'newspack-newsletters' ); ?></h1>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'newspack_newsletters_options_group' );
				do_settings_sections( 'newspack-newsletters-settings-admin' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public static function page_init() {
		add_settings_section(
			'newspack_newsletters_options_group',
			null,
			null,
			'newspack-newsletters-settings-admin'
		);
		foreach ( self::get_settings_list() as $setting ) {
			if ( isset( $setting['provider'] ) && get_option( 'newspack_newsletters_service_provider', null ) !== $setting['provider'] ) {
				continue;
			}
			$args = [
				'sanitize_callback' => ! empty( $setting['sanitize_callback'] ) ? $setting['sanitize_callback'] : 'sanitize_text_field',
			];
			register_setting(
				'newspack_newsletters_options_group',
				$setting['key'],
				$args
			);
			add_settings_field(
				$setting['key'],
				$setting['description'],
				[ __CLASS__, 'newspack_newsletters_settings_callback' ],
				'newspack-newsletters-settings-admin',
				'newspack_newsletters_options_group',
				$setting
			);
		};
	}

	/**
	 * Render settings fields.
	 *
	 * @param string $setting Setting key.
	 */
	public static function newspack_newsletters_settings_callback( $setting ) {
		$key         = $setting['key'];
		$type        = $setting['type'];
		$description = $setting['description'];
		$default     = ! empty( $setting['default'] ) ? $setting['default'] : false;
		$value       = get_option( $key, $default );

		if ( 'select' === $type ) {
			$options     = $setting['options'];
			$add_options = '';

			foreach ( $options as $option ) {
				$add_options .= '<option ' . ( $value === $option['value'] ? 'selected' : '' ) . ' value="' . esc_attr( $option['value'] ) . '">' . esc_html( $option['name'] ) . '</option>';
			}

			$allowed_html = array(
				'option' => array(
					'value'    => array(),
					'selected' => array(),
				),
			);

			printf(
				'<select id="%s" name="%s" value="%s">%s</select>',
				esc_attr( $key ),
				esc_attr( $key ),
				esc_attr( $value ),
				wp_kses( $add_options, $allowed_html )
			);
		} elseif ( 'checkbox' === $type ) {
			?>
				<input
					type="checkbox"
					id="<?php echo esc_attr( $key ); ?>"
					name="<?php echo esc_attr( $key ); ?>"
					<?php if ( ! empty( $value ) ) : ?>
						checked
					<?php endif; ?>
				/>
			<?php
		} else {
			printf(
				'<input type="text" id="%s" name="%s" value="%s" class="widefat" />',
				esc_attr( $key ),
				esc_attr( $key ),
				esc_attr( $value )
			);
		}
	}

	/**
	 * Hook into public posts slug option after save, to flush permalinks.
	 *
	 * @param string $old_value The old value.
	 * @param string $new_value The new value.
	 */
	public static function update_option_newspack_newsletters_public_posts_slug( $old_value, $new_value ) {
		Newspack_Newsletters::register_cpt();
		flush_rewrite_rules(); // phpcs:ignore
	}
}

if ( is_admin() ) {
	Newspack_Newsletters_Settings::init();
}
