<?php
/**
 * Class Google\Site_Kit\Core\Conversion_Tracking
 *
 * @package   Google\Site_Kit\Core\Modules
 * @copyright 2024 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Core\Conversion_Tracking;

use Google\Site_Kit\Context;
use Google\Site_Kit\Core\Storage\Options;
use Google\Site_Kit\Core\Conversion_Tracking\Conversion_Event_Providers\Contact_Form_7;
use Google\Site_Kit\Core\Conversion_Tracking\Conversion_Event_Providers\Mailchimp;
use Google\Site_Kit\Core\Conversion_Tracking\Conversion_Event_Providers\OptinMonster;
use Google\Site_Kit\Core\Conversion_Tracking\Conversion_Event_Providers\PopupMaker;
use Google\Site_Kit\Core\Conversion_Tracking\Conversion_Event_Providers\WooCommerce;
use Google\Site_Kit\Core\Conversion_Tracking\Conversion_Event_Providers\WPForms;
use LogicException;

/**
 * Class for managing conversion tracking.
 *
 * @since 1.126.0
 * @access private
 * @ignore
 */
class Conversion_Tracking {

	/**
	 * Context object.
	 *
	 * @var Context
	 */
	private $context;

	/**
	 * Conversion_Tracking_Settings instance.
	 *
	 * @since 1.127.0
	 * @var Conversion_Tracking_Settings
	 */
	protected $conversion_tracking_settings;

	/**
	 * REST_Conversion_Tracking_Controller instance.
	 *
	 * @since 1.127.0
	 * @var REST_Conversion_Tracking_Controller
	 */
	protected $rest_conversion_tracking_controller;

	/**
	 * Supported conversion event providers.
	 *
	 * @since 1.126.0
	 * @var array
	 */
	public static $providers = array(
		Contact_Form_7::CONVERSION_EVENT_PROVIDER_SLUG => Contact_Form_7::class,
		Mailchimp::CONVERSION_EVENT_PROVIDER_SLUG      => Mailchimp::class,
		OptinMonster::CONVERSION_EVENT_PROVIDER_SLUG   => OptinMonster::class,
		PopupMaker::CONVERSION_EVENT_PROVIDER_SLUG     => PopupMaker::class,
		WooCommerce::CONVERSION_EVENT_PROVIDER_SLUG    => WooCommerce::class,
		WPForms::CONVERSION_EVENT_PROVIDER_SLUG        => WPForms::class,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.126.0
	 *
	 * @param Context $context Plugin context.
	 * @param Options $options Optional. Option API instance. Default is a new instance.
	 */
	public function __construct( Context $context, Options $options = null ) {
		$this->context                             = $context;
		$options                                   = $options ?: new Options( $context );
		$this->conversion_tracking_settings        = new Conversion_Tracking_Settings( $options );
		$this->rest_conversion_tracking_controller = new REST_Conversion_Tracking_Controller( $this->conversion_tracking_settings );
	}

	/**
	 * Registers the class functionality.
	 *
	 * @since 1.126.0
	 */
	public function register() {
		$this->conversion_tracking_settings->register();
		$this->rest_conversion_tracking_controller->register();

		add_action(
			'wp_enqueue_scripts',
			function() {
				// Do nothing if neither Ads nor Analytics snippet has been inserted.
				if ( ! did_action( 'googlesitekit_ads_init_tag' ) && ! did_action( 'googlesitekit_analytics-4_init_tag' ) ) {
					return;
				}

				$active_providers = $this->get_active_providers();

				array_walk(
					$active_providers,
					function( Conversion_Events_Provider $active_provider ) {
						$script_asset = $active_provider->register_script();
						$script_asset->enqueue();
					}
				);
			}
		);
	}

	/**
	 * Gets the instances of active conversion event providers.
	 *
	 * @since 1.126.0
	 *
	 * @return array List of active Conversion_Events_Provider instances.
	 * @throws LogicException Thrown if an invalid conversion event provider class name is provided.
	 */
	public function get_active_providers() {
		$active_providers = array();

		foreach ( self::$providers as $provider_slug => $provider_class ) {
			if ( ! is_string( $provider_class ) || ! $provider_class ) {
				throw new LogicException(
					sprintf(
						/* translators: %s: provider slug */
						__( 'A conversion event provider class name is required to instantiate a provider: %s', 'google-site-kit' ),
						$provider_slug
					)
				);
			}

			if ( ! class_exists( $provider_class ) ) {
				throw new LogicException(
					sprintf(
						/* translators: %s: provider classname */
						__( "The '%s' class does not exist", 'google-site-kit' ),
						$provider_class
					)
				);
			}

			if ( ! is_subclass_of( $provider_class, Conversion_Events_Provider::class ) ) {
				throw new LogicException(
					sprintf(
						/* translators: 1: provider classname 2: Conversion_Events_Provider classname */
						__( "The '%1\$s' class must extend the base conversion event provider class: %2\$s", 'google-site-kit' ),
						$provider_class,
						Conversion_Events_Provider::class
					)
				);
			}

			$instance = new $provider_class( $this->context );

			if ( $instance->is_active() ) {
				$active_providers[ $provider_slug ] = $instance;
			}
		}

		return $active_providers;
	}

}
