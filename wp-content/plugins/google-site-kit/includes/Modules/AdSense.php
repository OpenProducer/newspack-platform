<?php
/**
 * Class Google\Site_Kit\Modules\AdSense
 *
 * @package   Google\Site_Kit
 * @copyright 2021 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Modules;

use Google\Site_Kit\Core\Modules\Module;
use Google\Site_Kit\Core\Modules\Module_Settings;
use Google\Site_Kit\Core\Modules\Module_With_Debug_Fields;
use Google\Site_Kit\Core\Modules\Module_With_Screen;
use Google\Site_Kit\Core\Modules\Module_With_Screen_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Scopes;
use Google\Site_Kit\Core\Modules\Module_With_Scopes_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Settings;
use Google\Site_Kit\Core\Modules\Module_With_Settings_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Assets;
use Google\Site_Kit\Core\Modules\Module_With_Assets_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Owner;
use Google\Site_Kit\Core\Modules\Module_With_Owner_Trait;
use Google\Site_Kit\Core\REST_API\Exception\Invalid_Datapoint_Exception;
use Google\Site_Kit\Core\Assets\Asset;
use Google\Site_Kit\Core\Assets\Script;
use Google\Site_Kit\Core\Authentication\Clients\Google_Site_Kit_Client;
use Google\Site_Kit\Core\REST_API\Data_Request;
use Google\Site_Kit\Core\Tags\Guards\Tag_Verify_Guard;
use Google\Site_Kit\Core\Util\Debug_Data;
use Google\Site_Kit\Core\Util\Method_Proxy_Trait;
use Google\Site_Kit\Modules\AdSense\AMP_Tag;
use Google\Site_Kit\Modules\AdSense\Settings;
use Google\Site_Kit\Modules\AdSense\Tag_Guard;
use Google\Site_Kit\Modules\AdSense\Web_Tag;
use Google\Site_Kit_Dependencies\Google_Service_AdSense;
use Google\Site_Kit_Dependencies\Google_Service_AdSense_Account;
use Google\Site_Kit_Dependencies\Google_Service_AdSense_Alert;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use WP_Error;

/**
 * Class representing the AdSense module.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class AdSense extends Module
	implements Module_With_Screen, Module_With_Scopes, Module_With_Settings, Module_With_Assets, Module_With_Debug_Fields, Module_With_Owner {
	use Method_Proxy_Trait;
	use Module_With_Assets_Trait;
	use Module_With_Owner_Trait;
	use Module_With_Scopes_Trait;
	use Module_With_Screen_Trait;
	use Module_With_Settings_Trait;

	/**
	 * Module slug name.
	 */
	const MODULE_SLUG = 'adsense';

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_scopes_hook();

		$this->register_screen_hook();

		if ( $this->is_connected() ) {
			/**
			 * Release filter forcing unlinked state.
			 *
			 * This is hooked into 'init' (default priority of 10), so that it
			 * runs after the original filter is added.
			 *
			 * @see \Google\Site_Kit\Modules\Analytics::register()
			 * @see \Google\Site_Kit\Modules\Analytics\Settings::register()
			 */
			add_action(
				'googlesitekit_init',
				function () {
					remove_filter( 'googlesitekit_analytics_adsense_linked', '__return_false' );
				}
			);
		}

		// AdSense tag placement logic.
		add_action( 'template_redirect', $this->get_method_proxy( 'register_tag' ) );
	}

	/**
	 * Gets required Google OAuth scopes for the module.
	 *
	 * @since 1.0.0
	 * @since 1.9.0 Changed to `adsense.readonly` variant.
	 *
	 * @return array List of Google OAuth scopes.
	 */
	public function get_scopes() {
		return array(
			'https://www.googleapis.com/auth/adsense.readonly',
		);
	}

	/**
	 * Returns all module information data for passing it to JavaScript.
	 *
	 * @since 1.0.0
	 *
	 * @return array Module information data.
	 */
	public function prepare_info_for_js() {
		$info = parent::prepare_info_for_js();

		$info['provides'] = array(
			__( 'Monetize your website', 'google-site-kit' ),
			__( 'Intelligent, automatic ad placement', 'google-site-kit' ),
		);

		return $info;
	}

	/**
	 * Checks whether the module is connected.
	 *
	 * A module being connected means that all steps required as part of its activation are completed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if module is connected, false otherwise.
	 */
	public function is_connected() {
		$settings = $this->get_settings()->get();

		if ( empty( $settings['accountSetupComplete'] ) || empty( $settings['siteSetupComplete'] ) ) {
			return false;
		}

		return parent::is_connected();
	}

	/**
	 * Cleans up when the module is deactivated.
	 *
	 * @since 1.0.0
	 */
	public function on_deactivation() {
		$this->get_settings()->delete();
	}

	/**
	 * Gets an array of debug field definitions.
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public function get_debug_fields() {
		$settings = $this->get_settings()->get();

		return array(
			'adsense_account_id'            => array(
				'label' => __( 'AdSense account ID', 'google-site-kit' ),
				'value' => $settings['accountID'],
				'debug' => Debug_Data::redact_debug_value( $settings['accountID'], 7 ),
			),
			'adsense_client_id'             => array(
				'label' => __( 'AdSense client ID', 'google-site-kit' ),
				'value' => $settings['clientID'],
				'debug' => Debug_Data::redact_debug_value( $settings['clientID'], 10 ),
			),
			'adsense_account_status'        => array(
				'label' => __( 'AdSense account status', 'google-site-kit' ),
				'value' => $settings['accountStatus'],
			),
			'adsense_use_snippet'           => array(
				'label' => __( 'AdSense snippet placed', 'google-site-kit' ),
				'value' => $settings['useSnippet'] ? __( 'Yes', 'google-site-kit' ) : __( 'No', 'google-site-kit' ),
				'debug' => $settings['useSnippet'] ? 'yes' : 'no',
			),
			'adsense_web_stories_adunit_id' => array(
				'label' => __( 'Web Stories Ad Unit ID', 'google-site-kit' ),
				'value' => $settings['webStoriesAdUnit'],
				'debug' => $settings['webStoriesAdUnit'],
			),
		);
	}

	/**
	 * Gets map of datapoint to definition data for each.
	 *
	 * @since 1.12.0
	 *
	 * @return array Map of datapoints to their definitions.
	 */
	protected function get_datapoint_definitions() {
		return array(
			'GET:adunits'        => array( 'service' => 'adsense' ),
			'GET:accounts'       => array( 'service' => 'adsense' ),
			'GET:alerts'         => array( 'service' => 'adsense' ),
			'GET:clients'        => array( 'service' => 'adsense' ),
			'GET:earnings'       => array( 'service' => 'adsense' ),
			'GET:notifications'  => array( 'service' => '' ),
			'GET:tag-permission' => array( 'service' => '' ),
			'GET:urlchannels'    => array( 'service' => 'adsense' ),
		);
	}

	/**
	 * Creates a request object for the given datapoint.
	 *
	 * @since 1.0.0
	 *
	 * @param Data_Request $data Data request object.
	 * @return RequestInterface|callable|WP_Error Request object or callable on success, or WP_Error on failure.
	 *
	 * @throws Invalid_Datapoint_Exception Thrown if the datapoint does not exist.
	 */
	protected function create_data_request( Data_Request $data ) {
		switch ( "{$data->method}:{$data->datapoint}" ) {
			case 'GET:accounts':
				$service = $this->get_service( 'adsense' );
				return $service->accounts->listAccounts();
			case 'GET:adunits':
				if ( ! isset( $data['accountID'] ) || ! isset( $data['clientID'] ) ) {
					$option            = $this->get_settings()->get();
					$data['accountID'] = $option['accountID'];
					if ( empty( $data['accountID'] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
					}
					$data['clientID'] = $option['clientID'];
					if ( empty( $data['clientID'] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'clientID' ), array( 'status' => 400 ) );
					}
				}
				$service = $this->get_service( 'adsense' );
				return $service->accounts_adunits->listAccountsAdunits( $data['accountID'], $data['clientID'] );
			case 'GET:alerts':
				if ( ! isset( $data['accountID'] ) ) {
					$option            = $this->get_settings()->get();
					$data['accountID'] = $option['accountID'];
					if ( empty( $data['accountID'] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
					}
				}
				$service = $this->get_service( 'adsense' );
				return $service->accounts_alerts->listAccountsAlerts( $data['accountID'] );
			case 'GET:clients':
				if ( ! isset( $data['accountID'] ) ) {
					return new WP_Error(
						'missing_required_param',
						/* translators: %s: Missing parameter name */
						sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ),
						array( 'status' => 400 )
					);
				}
				$service = $this->get_service( 'adsense' );
				return $service->accounts_adclients->listAccountsAdclients( $data['accountID'] );
			case 'GET:earnings':
				$start_date = $data['startDate'];
				$end_date   = $data['endDate'];
				if ( ! strtotime( $start_date ) || ! strtotime( $end_date ) ) {
					$dates = $this->date_range_to_dates( $data['dateRange'] ?: 'last-28-days' );
					if ( is_wp_error( $dates ) ) {
						return $dates;
					}

					list ( $start_date, $end_date ) = $dates;
				}

				$args = array(
					'start_date' => $start_date,
					'end_date'   => $end_date,
				);

				$metrics = $this->parse_string_list( $data['metrics'] );
				if ( ! empty( $metrics ) ) {
					$args['metrics'] = $metrics;
				}

				$dimensions = $this->parse_string_list( $data['dimensions'] );
				if ( ! empty( $dimensions ) ) {
					$args['dimensions'] = $dimensions;
				}

				$orderby = $this->parse_earnings_orderby( $data['orderby'] );
				if ( ! empty( $orderby ) ) {
					$args['sort'] = $orderby;
				}

				if ( ! empty( $data['limit'] ) ) {
					$args['limit'] = $data['limit'];
				}

				return $this->create_adsense_earning_data_request( array_filter( $args ) );
			case 'GET:notifications':
				return function() {
					$alerts = $this->get_data( 'alerts' );
					if ( is_wp_error( $alerts ) || empty( $alerts ) ) {
						return array();
					}
					$alerts = array_filter(
						$alerts,
						function( Google_Service_AdSense_Alert $alert ) {
							return 'SEVERE' === $alert->getSeverity();
						}
					);

					// There is no SEVERE alert, return empty.
					if ( empty( $alerts ) ) {
						return array();
					}

					/**
					 * First Alert
					 *
					 * @var Google_Service_AdSense_Alert $alert
					 */
					$alert = array_shift( $alerts );
					return array(
						array(
							'id'            => 'adsense-notification',
							'description'   => $alert->getMessage(),
							'isDismissible' => true,
							'winImage'      => 'sun-small.png',
							'format'        => 'large',
							'severity'      => 'win-info',
							'ctaURL'        => $this->get_account_url(),
							'ctaLabel'      => __( 'Go to AdSense', 'google-site-kit' ),
							'ctaTarget'     => '_blank',
						),
					);
				};
			case 'GET:tag-permission':
				return function() use ( $data ) {
					if ( ! isset( $data['clientID'] ) ) {
						return new WP_Error(
							'missing_required_param',
							/* translators: %s: Missing parameter name */
							sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'clientID' ),
							array( 'status' => 400 )
						);
					}

					return array_merge(
						array( 'clientID' => $data['clientID'] ),
						$this->has_access_to_client( $data['clientID'] )
					);
				};
			case 'GET:urlchannels':
				if ( ! isset( $data['accountID'] ) ) {
					return new WP_Error(
						'missing_required_param',
						/* translators: %s: Missing parameter name */
						sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ),
						array( 'status' => 400 )
					);
				}
				if ( ! isset( $data['clientID'] ) ) {
					return new WP_Error(
						'missing_required_param',
						/* translators: %s: Missing parameter name */
						sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'clientID' ),
						array( 'status' => 400 )
					);
				}
				$service = $this->get_service( 'adsense' );
				return $service->accounts_urlchannels->listAccountsUrlchannels( $data['accountID'], $data['clientID'] );
		}

		return parent::create_data_request( $data );
	}

	/**
	 * Parses a response for the given datapoint.
	 *
	 * @since 1.0.0
	 *
	 * @param Data_Request $data Data request object.
	 * @param mixed        $response Request response.
	 *
	 * @return mixed Parsed response data on success, or WP_Error on failure.
	 */
	protected function parse_data_response( Data_Request $data, $response ) {
		switch ( "{$data->method}:{$data->datapoint}" ) {
			// Intentional fallthrough.
			case 'GET:accounts':
			case 'GET:alerts':
			case 'GET:clients':
			case 'GET:urlchannels':
				return $response->getItems();
			case 'GET:earnings':
				return $response;
		}

		return parent::parse_data_response( $data, $response );
	}

	/**
	 * Gets the service URL for the current account or signup if none.
	 *
	 * @since 1.25.0
	 *
	 * @return string
	 */
	protected function get_account_url() {
		$profile = $this->authentication->profile();
		$option  = $this->get_settings()->get();
		$query   = array(
			'source'     => 'site-kit',
			'utm_source' => 'site-kit',
			'utm_medium' => 'wordpress_signup',
			'url'        => rawurlencode( $this->context->get_reference_site_url() ),
		);

		if ( ! empty( $option['accountID'] ) ) {
			$url = sprintf( 'https://www.google.com/adsense/new/%s/home', $option['accountID'] );
		} else {
			$url = 'https://www.google.com/adsense/signup/new';
		}

		if ( $profile->has() ) {
			$query['authuser'] = $profile->get()['email'];
		}

		return add_query_arg( $query, $url );
	}

	/**
	 * Parses the orderby value of the data request into an array of earning orderby format.
	 *
	 * @since 1.15.0
	 *
	 * @param array|null $orderby Data request orderby value.
	 * @return string[] An array of reporting orderby strings.
	 */
	protected function parse_earnings_orderby( $orderby ) {
		if ( empty( $orderby ) || ! is_array( $orderby ) ) {
			return array();
		}

		$results = array_map(
			function ( $order_def ) {
				$order_def = array_merge(
					array(
						'fieldName' => '',
						'sortOrder' => '',
					),
					(array) $order_def
				);

				if ( empty( $order_def['fieldName'] ) || empty( $order_def['sortOrder'] ) ) {
					return null;
				}

				return ( 'ASCENDING' === $order_def['sortOrder'] ? '+' : '-' ) . $order_def['fieldName'];
			},
			// When just object is passed we need to convert it to an array of objects.
			wp_is_numeric_array( $orderby ) ? $orderby : array( $orderby )
		);

		$results = array_filter( $results );
		$results = array_values( $results );

		return $results;
	}

	/**
	 * Gets an array of dates for the given named date range.
	 *
	 * @param string $date_range Named date range.
	 *                           E.g. 'last-28-days'.
	 *
	 * @return array|WP_Error Array of [startDate, endDate] or WP_Error if invalid named range.
	 */
	private function date_range_to_dates( $date_range ) {
		switch ( $date_range ) {
			case 'today':
				return array(
					gmdate( 'Y-m-d', strtotime( 'today' ) ),
					gmdate( 'Y-m-d', strtotime( 'today' ) ),
				);
			// Intentional fallthrough.
			case 'last-7-days':
			case 'last-14-days':
			case 'last-28-days':
			case 'last-90-days':
				return $this->parse_date_range( $date_range );
		}

		return new WP_Error( 'invalid_date_range', __( 'Invalid date range.', 'google-site-kit' ) );
	}

	/**
	 * Creates a new AdSense earning request for the current account, site and given arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Additional arguments.
	 *
	 *     @type array  $dimensions List of request dimensions. Default empty array.
	 *     @type array  $metrics    List of request metrics. Default empty array.
	 *     @type string $start_date Start date in 'Y-m-d' format. Default empty string.
	 *     @type string $end_date   End date in 'Y-m-d' format. Default empty string.
	 *     @type int    $row_limit  Limit of rows to return. Default none (will be skipped).
	 * }
	 * @return RequestInterface|WP_Error AdSense earning request instance.
	 */
	protected function create_adsense_earning_data_request( array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'dimensions' => array(),
				'metrics'    => array(),
				'start_date' => '',
				'end_date'   => '',
				'limit'      => '',
				'sort'       => array(),
			)
		);

		$option     = $this->get_settings()->get();
		$account_id = $option['accountID'];
		if ( empty( $account_id ) ) {
			return new WP_Error( 'account_id_not_set', __( 'AdSense account ID not set.', 'google-site-kit' ) );
		}

		$opt_params = array(
			'locale' => get_locale(),
			'metric' => array( 'EARNINGS', 'PAGE_VIEWS_RPM', 'IMPRESSIONS' ),
		);

		if ( ! empty( $args['dimensions'] ) ) {
			$opt_params['dimension'] = (array) $args['dimensions'];
		}

		if ( ! empty( $args['metrics'] ) ) {
			$opt_params['metric'] = (array) $args['metrics'];
		}

		if ( ! empty( $args['sort'] ) ) {
			$opt_params['sort'] = (array) $args['sort'];
		}

		if ( ! empty( $args['limit'] ) ) {
			$opt_params['maxResults'] = (int) $args['limit'];
		}

		$host = wp_parse_url( $this->context->get_reference_site_url(), PHP_URL_HOST );
		if ( ! empty( $host ) ) {
			$opt_params['filter'] = 'DOMAIN_NAME==' . $host;
		}

		$service = $this->get_service( 'adsense' );
		return $service->accounts_reports->generate( $account_id, $args['start_date'], $args['end_date'], $opt_params );
	}

	/**
	 * Sets up information about the module.
	 *
	 * @since 1.0.0
	 *
	 * @return array Associative array of module info.
	 */
	protected function setup_info() {
		$idenfifier_args = array(
			'source' => 'site-kit',
			'url'    => $this->context->get_reference_site_url(),
		);

		return array(
			'slug'        => self::MODULE_SLUG,
			'name'        => _x( 'AdSense', 'Service name', 'google-site-kit' ),
			'description' => __( 'Earn money by placing ads on your website. It’s free and easy.', 'google-site-kit' ),
			'cta'         => __( 'Monetize Your Site.', 'google-site-kit' ),
			'order'       => 2,
			'homepage'    => add_query_arg( $idenfifier_args, 'https://www.google.com/adsense/start' ),
			'learn_more'  => __( 'https://www.google.com/intl/en_us/adsense/start/', 'google-site-kit' ),
		);
	}

	/**
	 * Sets up the Google services the module should use.
	 *
	 * This method is invoked once by {@see Module::get_service()} to lazily set up the services when one is requested
	 * for the first time.
	 *
	 * @since 1.0.0
	 * @since 1.2.0 Now requires Google_Site_Kit_Client instance.
	 *
	 * @param Google_Site_Kit_Client $client Google client instance.
	 * @return array Google services as $identifier => $service_instance pairs. Every $service_instance must be an
	 *               instance of Google_Service.
	 */
	protected function setup_services( Google_Site_Kit_Client $client ) {
		return array(
			'adsense' => new Google_Service_AdSense( $client ),
		);
	}

	/**
	 * Sets up the module's settings instance.
	 *
	 * @since 1.2.0
	 *
	 * @return Module_Settings
	 */
	protected function setup_settings() {
		return new Settings( $this->options );
	}

	/**
	 * Sets up the module's assets to register.
	 *
	 * @since 1.9.0
	 *
	 * @return Asset[] List of Asset objects.
	 */
	protected function setup_assets() {
		$base_url = $this->context->url( 'dist/assets/' );

		return array(
			new Script(
				'googlesitekit-modules-adsense',
				array(
					'src'          => $base_url . 'js/googlesitekit-modules-adsense.js',
					'dependencies' => array(
						'googlesitekit-vendor',
						'googlesitekit-api',
						'googlesitekit-data',
						'googlesitekit-modules',
						'googlesitekit-datastore-site',
						'googlesitekit-datastore-user',
						'googlesitekit-google-charts',
					),
				)
			),
		);
	}

	/**
	 * Verifies that user has access to the given client and account.
	 *
	 * @since 1.9.0
	 *
	 * @param string $client_id  Client found in the existing tag.
	 * @return array {
	 *      AdSense account access data.
	 *      @type string $account_id The AdSense account ID for the given client.
	 *      @type bool   $permission Whether the user has access to this account and client.
	 * }
	 */
	protected function has_access_to_client( $client_id ) {
		if ( empty( $client_id ) ) {
			return array(
				'account_id' => '',
				'permission' => false,
			);
		}

		$account_has_client = function ( $account_id ) use ( $client_id ) {
			// Try to get clients for that account.
			$clients = $this->get_data( 'clients', array( 'accountID' => $account_id ) );
			if ( is_wp_error( $clients ) ) {
				// No access to the account.
				return false;
			}
			// Ensure there is access to the client.
			foreach ( $clients as $client ) {
				if ( $client->getId() === $client_id ) {
					return true;
				}
			}

			return false;
		};

		$parsed_account_id = $this->parse_account_id( $client_id );

		if ( $account_has_client( $parsed_account_id ) ) {
			return array(
				'account_id' => $parsed_account_id,
				'permission' => true,
			);
		}

		$accounts = $this->get_data( 'accounts' );
		if ( is_wp_error( $accounts ) ) {
			$accounts = array();
		}

		foreach ( $accounts as $account ) {
			/* @var Google_Service_AdSense_Account $account AdSense account instance. */
			if ( $account->getId() === $parsed_account_id ) {
				continue;
			}
			if ( $account_has_client( $account->getId() ) ) {
				return array(
					'account_id' => $account->getId(),
					'permission' => true,
				);
			}
		}

		return array(
			'account_id' => $parsed_account_id,
			'permission' => false,
		);
	}

	/**
	 * Determines the AdSense account ID from a given AdSense client ID.
	 *
	 * @since 1.9.0
	 *
	 * @param string $client_id AdSense client ID.
	 * @return string AdSense account ID, or empty string if invalid client ID.
	 */
	protected function parse_account_id( $client_id ) {
		if ( ! preg_match( '/^ca-(pub-[0-9]+)$/', $client_id, $matches ) ) {
			return '';
		}
		return $matches[1];
	}

	/**
	 * Registers the AdSense tag.
	 *
	 * @since 1.24.0
	 */
	private function register_tag() {
		// TODO: 'amp_story' support can be phased out in the long term.
		if ( is_singular( array( 'amp_story' ) ) ) {
			return;
		}

		$module_settings = $this->get_settings();
		$settings        = $module_settings->get();

		if ( $this->context->is_amp() ) {
			$tag = new AMP_Tag( $settings['clientID'], self::MODULE_SLUG );
			$tag->set_story_ad_slot_id( $settings['webStoriesAdUnit'] );
		} else {
			$tag = new Web_Tag( $settings['clientID'], self::MODULE_SLUG );
		}

		if ( ! $tag->is_tag_blocked() ) {
			$tag->use_guard( new Tag_Verify_Guard( $this->context->input() ) );
			$tag->use_guard( new Tag_Guard( $module_settings ) );

			if ( $tag->can_register() ) {
				$tag->register();
			}
		}
	}

}
