<?php
/**
 * Class Google\Site_Kit\Modules\Tag_Manager
 *
 * @package   Google\Site_Kit
 * @copyright 2019 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Modules;

use Google\Site_Kit\Context;
use Google\Site_Kit\Core\Modules\Module;
use Google\Site_Kit\Core\Modules\Module_Settings;
use Google\Site_Kit\Core\Modules\Module_With_Debug_Fields;
use Google\Site_Kit\Core\Modules\Module_With_Scopes;
use Google\Site_Kit\Core\Modules\Module_With_Scopes_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Settings;
use Google\Site_Kit\Core\Modules\Module_With_Settings_Trait;
use Google\Site_Kit\Core\Authentication\Clients\Google_Site_Kit_Client;
use Google\Site_Kit\Core\REST_API\Data_Request;
use Google\Site_Kit\Core\Util\Debug_Data;
use Google\Site_Kit\Modules\Tag_Manager\Settings;
use Google\Site_Kit_Dependencies\Google_Service_Exception;
use Google\Site_Kit_Dependencies\Google_Service_TagManager;
use Google\Site_Kit_Dependencies\Google_Service_TagManager_Account;
use Google\Site_Kit_Dependencies\Google_Service_TagManager_Container;
use Google\Site_Kit_Dependencies\Google_Service_TagManager_ListAccountsResponse;
use Google\Site_Kit_Dependencies\Google_Service_TagManager_ListContainersResponse;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use WP_Error;
use Exception;

/**
 * Class representing the Tag Manager module.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Tag_Manager extends Module implements Module_With_Scopes, Module_With_Settings, Module_With_Debug_Fields {
	use Module_With_Scopes_Trait, Module_With_Settings_Trait;

	/**
	 * Container usage context for web.
	 */
	const USAGE_CONTEXT_WEB = 'web';

	/**
	 * Container usage context for AMP.
	 */
	const USAGE_CONTEXT_AMP = 'amp';

	/**
	 * Settings instance.
	 *
	 * @since 1.2.0
	 * @var Settings
	 */
	protected $settings;

	/**
	 * Map of container usageContext to option key for containerID.
	 *
	 * @var array
	 */
	protected $context_map = array(
		self::USAGE_CONTEXT_WEB => 'containerID',
		self::USAGE_CONTEXT_AMP => 'ampContainerID',
	);

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_scopes_hook();

		add_action( // For non-AMP.
			'wp_head',
			function() {
				$this->print_gtm_js();
			}
		);

		add_action( // For non-AMP.
			'wp_footer',
			function() {
				$this->print_gtm_no_js();
			}
		);

		$print_amp_gtm = function() {
			// This hook is only available in AMP plugin version >=1.3, so if it
			// has already completed, do nothing.
			if ( ! doing_action( 'amp_print_analytics' ) && did_action( 'amp_print_analytics' ) ) {
				return;
			}

			$this->print_amp_gtm();
		};
		// Which actions are run depends on the version of the AMP Plugin
		// (https://amp-wp.org/) available. Version >=1.3 exposes a
		// new, `amp_print_analytics` action.
		// For all AMP modes, AMP plugin version >=1.3.
		add_action( 'amp_print_analytics', $print_amp_gtm );
		// For AMP Standard and Transitional, AMP plugin version <1.3.
		add_action( 'wp_footer', $print_amp_gtm, 20 );
		// For AMP Reader, AMP plugin version <1.3.
		add_action( 'amp_post_template_footer', $print_amp_gtm, 20 );

		add_filter( // Load amp-analytics component for AMP Reader.
			'amp_post_template_data',
			function( $data ) {
				return $this->amp_data_load_analytics_component( $data );
			}
		);
	}

	/**
	 * Gets required Google OAuth scopes for the module.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of Google OAuth scopes.
	 */
	public function get_scopes() {
		return array(
			'https://www.googleapis.com/auth/tagmanager.readonly',
			'https://www.googleapis.com/auth/tagmanager.edit.containers',
			'https://www.googleapis.com/auth/tagmanager.manage.accounts',
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
			__( 'Create tags without updating code', 'google-site-kit' ),
		);

		$info['settings'] = $this->get_settings()->get();

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
		$container_id = $this->get_data( 'container-id', array( 'usageContext' => $this->get_usage_context() ) );

		if ( is_wp_error( $container_id ) || ! $container_id ) {
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
			'tagmanager_account_id'       => array(
				'label' => __( 'Tag Manager account ID', 'google-site-kit' ),
				'value' => $settings['accountID'],
				'debug' => Debug_Data::redact_debug_value( $settings['accountID'] ),
			),
			'tagmanager_container_id'     => array(
				'label' => __( 'Tag Manager container ID', 'google-site-kit' ),
				'value' => $settings['containerID'],
				'debug' => Debug_Data::redact_debug_value( $settings['containerID'], 7 ),
			),
			'tagmanager_amp_container_id' => array(
				'label' => __( 'Tag Manager AMP container ID', 'google-site-kit' ),
				'value' => $settings['ampContainerID'],
				'debug' => Debug_Data::redact_debug_value( $settings['ampContainerID'], 7 ),
			),
			'tagmanager_use_snippet'      => array(
				'label' => __( 'Tag Manager snippet placed', 'google-site-kit' ),
				'value' => $settings['useSnippet'] ? __( 'Yes', 'google-site-kit' ) : __( 'No', 'google-site-kit' ),
				'debug' => $settings['useSnippet'] ? 'yes' : 'no',
			),
		);
	}

	/**
	 * Outputs Tag Manager script.
	 *
	 * @since 1.0.0
	 */
	protected function print_gtm_js() {
		if ( ! $this->should_output_snippet() ) {
			return;
		}

		// On AMP, do not print the script tag, falling back to 'amp_analytics_entries' below.
		if ( $this->context->is_amp() ) {
			return;
		}

		$container_id = $this->get_data( 'container-id', array( 'usageContext' => self::USAGE_CONTEXT_WEB ) );

		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return;
		}

		?>
		<!-- Google Tag Manager added by Site Kit -->
		<script>( function( w, d, s, l, i ) {
				w[l] = w[l] || [];
				w[l].push( {'gtm.start': new Date().getTime(), event: 'gtm.js'} );
				var f = d.getElementsByTagName( s )[0],
					j = d.createElement( s ), dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore( j, f );
			} )( window, document, 'script', 'dataLayer', '<?php echo esc_js( $container_id ); ?>' );
		</script>
		<!-- End Google Tag Manager -->
		<?php
	}

	/**
	 * Outputs Tag Manager iframe for when the browser has JavaScript disabled.
	 *
	 * @since 1.0.0
	 */
	protected function print_gtm_no_js() {
		if ( ! $this->should_output_snippet() ) {
			return;
		}

		// On AMP, do not print the script tag.
		if ( $this->context->is_amp() ) {
			return;
		}

		$container_id = $this->get_data( 'container-id', array( 'usageContext' => self::USAGE_CONTEXT_WEB ) );

		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return;
		}

		?>
		<!-- Google Tag Manager (noscript) added by Site Kit -->
		<noscript>
			<iframe src="<?php echo esc_url( "https://www.googletagmanager.com/ns.html?id=$container_id" ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	}

	/**
	 * Outputs Tag Manager <amp-analytics> tag.
	 *
	 * @since 1.0.0
	 */
	protected function print_amp_gtm() {
		if ( ! $this->should_output_snippet() ) {
			return;
		}

		if ( ! $this->context->is_amp() ) {
			return;
		}

		$container_id = $this->get_data( 'container-id', array( 'usageContext' => self::USAGE_CONTEXT_AMP ) );

		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return;
		}

		?>
		<!-- Google Tag Manager added by Site Kit -->
		<amp-analytics config="<?php echo esc_url( "https://www.googletagmanager.com/amp.json?id=$container_id" ); ?>" data-credentials="include"></amp-analytics>
		<!-- End Google Tag Manager -->
		<?php
	}

	/**
	 * Checks whether or not the code snippet should be output.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	protected function should_output_snippet() {
		// Don't output snippets for Site Kit existing tag checks.
		if ( $this->context->input()->filter( INPUT_GET, 'tagverify', FILTER_VALIDATE_BOOLEAN ) ) {
			return false;
		}

		return $this->get_settings()->get()['useSnippet'];
	}

	/**
	 * Loads AMP analytics script if opted in.
	 *
	 * This only affects AMP Reader mode, the others are automatically covered.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data AMP template data.
	 * @return array Filtered $data.
	 */
	protected function amp_data_load_analytics_component( $data ) {
		if ( isset( $data['amp_component_scripts']['amp-analytics'] ) ) {
			return $data;
		}

		$container_id = $this->get_data( 'container-id', array( 'usageContext' => self::USAGE_CONTEXT_AMP ) );

		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return $data;
		}

		$data['amp_component_scripts']['amp-analytics'] = 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js';
		return $data;
	}

	/**
	 * Gets the current container usage context based on the current AMP mode (defaults to 'web').
	 *
	 * @return string
	 */
	protected function get_usage_context() {
		return Context::AMP_MODE_PRIMARY === $this->context->get_amp_mode()
			? self::USAGE_CONTEXT_AMP
			: self::USAGE_CONTEXT_WEB;
	}

	/**
	 * Sanitizes a string to be used for a container name.
	 *
	 * @since 1.0.4
	 *
	 * @param string $name String to sanitize.
	 *
	 * @return string
	 */
	public static function sanitize_container_name( $name ) {
		// Remove any leading or trailing whitespace.
		$name = trim( $name );
		// Must not start with an underscore.
		$name = ltrim( $name, '_' );
		// Decode entities for special characters so that they are stripped properly.
		$name = wp_specialchars_decode( $name, ENT_QUOTES );
		// Convert accents to basic characters to prevent them from being stripped.
		$name = remove_accents( $name );
		// Strip all non-simple characters.
		$name = preg_replace( '/[^a-zA-Z0-9_., -]/', '', $name );
		// Collapse multiple whitespaces.
		$name = preg_replace( '/\s+/', ' ', $name );

		return $name;
	}

	/**
	 * Returns the mapping between available datapoints and their services.
	 *
	 * @since 1.0.0
	 *
	 * @return array Associative array of $datapoint => $service_identifier pairs.
	 */
	protected function get_datapoint_services() {
		return array(
			// GET / POST.
			'connection'          => '',
			'account-id'          => '',
			'container-id'        => '',
			// GET.
			'accounts'            => 'tagmanager',
			'accounts-containers' => 'tagmanager',
			'containers'          => 'tagmanager',
			'tag-permission'      => 'tagmanager',
			// POST.
			'settings'            => '',
		);
	}

	/**
	 * Creates a request object for the given datapoint.
	 *
	 * @since 1.0.0
	 *
	 * @param Data_Request $data Data request object.
	 *
	 * @return RequestInterface|callable|WP_Error Request object or callable on success, or WP_Error on failure.
	 */
	protected function create_data_request( Data_Request $data ) {
		switch ( "{$data->method}:{$data->datapoint}" ) {
			case 'GET:account-id':
				return function() {
					$option = $this->get_settings()->get();

					if ( empty( $option['accountID'] ) ) {
						return new WP_Error( 'account_id_not_set', __( 'Tag Manager account ID not set.', 'google-site-kit' ), array( 'status' => 404 ) );
					}
					return $option['accountID'];
				};
			case 'POST:account-id':
				if ( ! isset( $data['accountID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
				}
				return function() use ( $data ) {
					$this->get_settings()->merge( array( 'accountID' => $data['accountID'] ) );
					return true;
				};
			// Intentional fallthrough.
			case 'GET:accounts':
			case 'GET:accounts-containers':
				return $this->get_tagmanager_service()->accounts->listAccounts();
			case 'GET:connection':
				return function() {
					$option = $this->get_settings()->get();

					$connection = array(
						'accountID'      => '',
						'containerID'    => '',
						'ampContainerID' => '',
					);

					return array_intersect_key( $option, $connection );
				};
			case 'POST:connection':
				return function() use ( $data ) {
					$this->get_settings()->merge(
						array(
							'accountID'   => $data['accountID'],
							'containerID' => $data['containerID'],
						)
					);
					return true;
				};
			case 'GET:container-id':
				return function() use ( $data ) {
					$option        = $this->get_settings()->get();
					$usage_context = $data['usageContext'] ?: self::USAGE_CONTEXT_WEB;

					if ( empty( $this->context_map[ $usage_context ] ) ) {
						return new WP_Error(
							'invalid_param',
							sprintf(
								/* translators: 1: Invalid parameter name, 2: list of valid values */
								__( 'Request parameter %1$s is not one of %2$s', 'google-site-kit' ),
								'usageContext',
								implode( ', ', array_keys( $this->context_map ) )
							),
							array( 'status' => 400 )
						);
					}

					$option_key = $this->context_map[ $usage_context ];

					if ( empty( $option[ $option_key ] ) ) {
						return new WP_Error(
							'container_id_not_set',
							__( 'Tag Manager container ID not set.', 'google-site-kit' ),
							array( 'status' => 404 )
						);
					}

					return $option[ $option_key ];
				};
			case 'POST:container-id':
				if ( ! isset( $data['containerID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'containerID' ), array( 'status' => 400 ) );
				}

				$usage_context = $data['usageContext'] ?: self::USAGE_CONTEXT_WEB;

				if ( empty( $this->context_map[ $usage_context ] ) ) {
					return new WP_Error(
						'invalid_param',
						sprintf(
							/* translators: 1: Invalid parameter name, 2: list of valid values */
							__( 'Request parameter %1$s is not one of %2$s', 'google-site-kit' ),
							'usageContext',
							implode( ', ', array_keys( $this->context_map ) )
						),
						array( 'status' => 400 )
					);
				}

				$option_key = $this->context_map[ $usage_context ];

				return function() use ( $data, $option_key ) {
					$this->get_settings()->merge( array( $option_key => $data['containerID'] ) );
					return true;
				};
			case 'GET:containers':
				if ( ! isset( $data['accountID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
				}
				return $this->get_tagmanager_service()->accounts_containers->listAccountsContainers( "accounts/{$data['accountID']}" );
			case 'POST:settings':
				$required_params = array( 'accountID', 'usageContext' );

				if ( self::USAGE_CONTEXT_WEB === $data['usageContext'] ) { // No AMP.
					$required_params[] = $this->context_map[ self::USAGE_CONTEXT_WEB ];
				} elseif ( self::USAGE_CONTEXT_AMP === $data['usageContext'] ) { // Primary AMP.
					$required_params[] = $this->context_map[ self::USAGE_CONTEXT_AMP ];
				} else { // Secondary AMP.
					array_push( $required_params, ...array_values( $this->context_map ) );
				}

				foreach ( $required_params as $required_param ) {
					if ( ! isset( $data[ $required_param ] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), $required_param ), array( 'status' => 400 ) );
					}
				}

				return function() use ( $data ) {
					$option = $data->data;

					try {
						if ( 'container_create' === $data['containerID'] ) {
							$option['containerID'] = $this->create_container( $data['accountID'], self::USAGE_CONTEXT_WEB );
						}
						if ( 'container_create' === $data['ampContainerID'] ) {
							$option['ampContainerID'] = $this->create_container( $data['accountID'], self::USAGE_CONTEXT_AMP );
						}
					} catch ( Exception $e ) {
						return $this->exception_to_error( $e, $data->datapoint );
					}

					$this->get_settings()->merge( $option );

					return $this->get_settings()->get();
				};
			case 'GET:tag-permission':
				return function () use ( $data ) {
					if ( ! isset( $data['tag'] ) ) {
						return new WP_Error(
							'missing_required_param',
							/* translators: %s: Missing parameter name */
							sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'tag' ),
							array( 'status' => 400 )
						);
					}

					$accounts = $this->get_data( 'accounts' );

					if ( is_wp_error( $accounts ) ) {
						return $accounts;
					}

					try {
						return $this->get_account_for_container( $data['tag'], $accounts );
					} catch ( Exception $exception ) {
						return new WP_Error(
							'tag_manager_existing_tag_permission',
							/* translators: %s: Container ID */
							sprintf( __( 'We’ve detected there’s already an existing Tag Manager tag on your site (%s), but your account doesn’t seem to have the necessary access to this container. You can either remove the existing tag and connect to a different account, or request access to this container from your team.', 'google-site-kit' ), $data['tag'] ),
							array( 'status' => 403 )
						);
					}
				};

		}

		return new WP_Error( 'invalid_datapoint', __( 'Invalid datapoint.', 'google-site-kit' ) );
	}

	/**
	 * Creates GTM Container.
	 *
	 * @since 1.0.0
	 * @param string       $account_id    The account ID.
	 * @param string|array $usage_context The container usage context(s).
	 *
	 * @return string Container public ID.
	 * @throws Exception Throws an exception if raised during container creation.
	 */
	protected function create_container( $account_id, $usage_context = self::USAGE_CONTEXT_WEB ) {
		$restore_defer = $this->with_client_defer( false );

		// Use site name for container, fallback to domain of reference URL.
		$container_name = get_bloginfo( 'name' ) ?: wp_parse_url( $this->context->get_reference_site_url(), PHP_URL_HOST );
		// Prevent naming conflict (Tag Manager does not allow more than one with same name).
		if ( self::USAGE_CONTEXT_AMP === $usage_context ) {
			$container_name .= ' AMP';
		}
		$container_name = self::sanitize_container_name( $container_name );

		$container = new Google_Service_TagManager_Container();
		$container->setName( $container_name );
		$container->setUsageContext( (array) $usage_context );

		try {
			$new_container = $this->get_tagmanager_service()->accounts_containers->create( "accounts/{$account_id}", $container );
		} catch ( Exception $exception ) {
			$restore_defer();
			throw $exception;
		}

		$restore_defer();

		return $new_container->getPublicId();
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
			case 'GET:accounts':
				/* @var Google_Service_TagManager_ListAccountsResponse $response List accounts response. */
				return $response->getAccount();
			case 'GET:accounts-containers':
				/* @var Google_Service_TagManager_ListAccountsResponse $response List accounts response. */
				$response = array(
					// TODO: Parse this response to a regular array.
					'accounts'   => $response->getAccount(),
					'containers' => array(),
				);
				if ( 0 === count( $response['accounts'] ) ) {
					return $response;
				}
				if ( $data['accountID'] ) {
					$account_id = $data['accountID'];
				} else {
					$account_id = $response['accounts'][0]->getAccountId();
				}

				$containers = $this->get_data(
					'containers',
					array(
						'accountID'    => $account_id,
						'usageContext' => $data['usageContext'] ?: self::USAGE_CONTEXT_WEB,
					)
				);

				if ( is_wp_error( $containers ) ) {
					return $response;
				}

				return array_merge( $response, compact( 'containers' ) );
			case 'GET:containers':
				/* @var Google_Service_TagManager_ListContainersResponse $response Response object. */
				$usage_context = $data['usageContext'] ?: self::USAGE_CONTEXT_WEB;
				/* @var Google_Service_TagManager_Container[] $containers Filtered containers. */
				$containers = array_filter(
					(array) $response->getContainer(),
					function ( Google_Service_TagManager_Container $container ) use ( $usage_context ) {
						return array_intersect( (array) $usage_context, $container->getUsageContext() );
					}
				);

				return array_values( $containers );
		}

		return $response;
	}

	/**
	 * Finds the account for the given container *public ID* from the given list of accounts.
	 *
	 * There is no way to query a container by its public ID (the ID that identifies the container on the client)
	 * so we must find it by listing the containers of the available accounts and matching on the public ID.
	 *
	 * @since 1.2.0
	 *
	 * @param string                              $container_id Container public ID (e.g. GTM-ABCDEFG).
	 * @param Google_Service_TagManager_Account[] $accounts     All accounts available to the current user.
	 *
	 * @return array {
	 *     @type Google_Service_TagManager_Account   $account   Account model instance.
	 *     @type Google_Service_TagManager_Container $container Container model instance.
	 * }
	 * @throws Exception Thrown if the given container ID does not belong to any of the given accounts.
	 */
	private function get_account_for_container( $container_id, $accounts ) {
		foreach ( (array) $accounts as $account ) {
			/* @var Google_Service_TagManager_Account $account Tag manager account */
			$containers = $this->get_data(
				'containers',
				array(
					'accountID'    => $account->getAccountId(),
					'usageContext' => array_keys( $this->context_map ),
				)
			);

			if ( is_wp_error( $containers ) ) {
				break;
			}

			foreach ( (array) $containers as $container ) {
				/* @var Google_Service_TagManager_Container $container Container instance */
				if ( $container_id === $container->getPublicId() ) {
					return compact( 'account', 'container' );
				}
			}
		}
		throw new Exception( __( 'No account found for given container', 'google-site-kit' ) );
	}

	/**
	 * Gets the configured TagManager service instance.
	 *
	 * @since 1.2.0
	 *
	 * @return Google_Service_TagManager instance.
	 * @throws Exception Thrown if the module did not correctly set up the service.
	 */
	private function get_tagmanager_service() {
		return $this->get_service( 'tagmanager' );
	}

	/**
	 * Sets up information about the module.
	 *
	 * @since 1.0.0
	 *
	 * @return array Associative array of module info.
	 */
	protected function setup_info() {
		return array(
			'slug'        => 'tagmanager',
			'name'        => _x( 'Tag Manager', 'Service name', 'google-site-kit' ),
			'description' => __( 'Tag Manager creates an easy to manage way to create tags on your site without updating code.', 'google-site-kit' ),
			'cta'         => __( 'Tag management made simple.', 'google-site-kit' ),
			'order'       => 6,
			'homepage'    => __( 'https://tagmanager.google.com/', 'google-site-kit' ),
			'learn_more'  => __( 'https://marketingplatform.google.com/about/tag-manager/', 'google-site-kit' ),
			'group'       => __( 'Marketing Platform', 'google-site-kit' ),
			'tags'        => array( 'marketing' ),
			'depends_on'  => array( 'analytics' ),
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
			'tagmanager' => new Google_Service_TagManager( $client ),
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
}
