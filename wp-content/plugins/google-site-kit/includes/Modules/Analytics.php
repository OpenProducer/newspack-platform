<?php
/**
 * Class Google\Site_Kit\Modules\Analytics
 *
 * @package   Google\Site_Kit
 * @copyright 2019 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Modules;

use Google\Site_Kit\Core\Modules\Module;
use Google\Site_Kit\Core\Modules\Module_Settings;
use Google\Site_Kit\Core\Modules\Module_With_Admin_Bar;
use Google\Site_Kit\Core\Modules\Module_With_Debug_Fields;
use Google\Site_Kit\Core\Modules\Module_With_Screen;
use Google\Site_Kit\Core\Modules\Module_With_Screen_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Scopes;
use Google\Site_Kit\Core\Modules\Module_With_Scopes_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Settings;
use Google\Site_Kit\Core\Modules\Module_With_Settings_Trait;
use Google\Site_Kit\Core\Authentication\Clients\Google_Site_Kit_Client;
use Google\Site_Kit\Core\REST_API\Data_Request;
use Google\Site_Kit\Core\Util\Debug_Data;
use Google\Site_Kit\Modules\Analytics\Settings;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_DateRangeValues;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_GetReportsResponse;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_Report;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_ReportData;
use Google\Site_Kit_Dependencies\Google_Service_Exception;
use Google\Site_Kit_Dependencies\Google_Service_Analytics;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_GetReportsRequest;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_ReportRequest;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_Dimension;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_DimensionFilter;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_DimensionFilterClause;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_DateRange;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_Metric;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_OrderBy;
use Google\Site_Kit_Dependencies\Google_Service_Analytics_Accounts;
use Google\Site_Kit_Dependencies\Google_Service_Analytics_Account;
use Google\Site_Kit_Dependencies\Google_Service_Analytics_Webproperties;
use Google\Site_Kit_Dependencies\Google_Service_Analytics_Webproperty;
use Google\Site_Kit_Dependencies\Google_Service_Analytics_Profile;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use WP_Error;
use Exception;

/**
 * Class representing the Analytics module.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Analytics extends Module
	implements Module_With_Screen, Module_With_Scopes, Module_With_Settings, Module_With_Admin_Bar, Module_With_Debug_Fields {
	use Module_With_Screen_Trait, Module_With_Scopes_Trait, Module_With_Settings_Trait;

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_scopes_hook();

		$this->register_screen_hook();

		/**
		 * This filter only exists to be unhooked by the AdSense module if active.
		 *
		 * @see \Google\Site_Kit\Modules\Analytics\Settings::register
		 */
		add_filter( 'googlesitekit_analytics_adsense_linked', '__return_false' );

		add_action( // For non-AMP.
			'wp_enqueue_scripts',
			function() {
				$this->enqueue_gtag_js();
			}
		);

		$print_amp_gtag = function() {
			// This hook is only available in AMP plugin version >=1.3, so if it
			// has already completed, do nothing.
			if ( ! doing_action( 'amp_print_analytics' ) && did_action( 'amp_print_analytics' ) ) {
				return;
			}

			$this->print_amp_gtag();
		};
		// Which actions are run depends on the version of the AMP Plugin
		// (https://amp-wp.org/) available. Version >=1.3 exposes a
		// new, `amp_print_analytics` action.
		// For all AMP modes, AMP plugin version >=1.3.
		add_action( 'amp_print_analytics', $print_amp_gtag );
		// For AMP Standard and Transitional, AMP plugin version <1.3.
		add_action( 'wp_footer', $print_amp_gtag, 20 );
		// For AMP Reader, AMP plugin version <1.3.
		add_action( 'amp_post_template_footer', $print_amp_gtag, 20 );

		add_filter( // Load amp-analytics component for AMP Reader.
			'amp_post_template_data',
			function( $data ) {
				return $this->amp_data_load_analytics_component( $data );
			}
		);

		add_action(
			'wp_head',
			function () {
				if ( $this->is_tracking_disabled() ) {
					$this->print_tracking_opt_out();
				}
			},
			0
		);
	}

	/**
	 * Checks whether or not tracking snippet should be contextually disabled for this request.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	protected function is_tracking_disabled() {
		$exclusions = $this->get_data( 'tracking-disabled' );
		$disabled   = in_array( 'loggedinUsers', $exclusions, true ) && is_user_logged_in();

		/**
		 * Filters whether or not the Analytics tracking snippet is output for the current request.
		 *
		 * @since 1.1.0
		 *
		 * @param $disabled bool Whether to disable tracking or not.
		 */
		return (bool) apply_filters( 'googlesitekit_analytics_tracking_disabled', $disabled );
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
			'https://www.googleapis.com/auth/analytics',
			'https://www.googleapis.com/auth/analytics.readonly',
			'https://www.googleapis.com/auth/analytics.manage.users',
			'https://www.googleapis.com/auth/analytics.edit',
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
			__( 'Audience overview', 'google-site-kit' ),
			__( 'Top pages', 'google-site-kit' ),
			__( 'Top acquisition sources', 'google-site-kit' ),
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
		$connection = $this->get_data( 'connection' );
		if ( is_wp_error( $connection ) ) {
			return false;
		}

		foreach ( (array) $connection as $value ) {
			if ( empty( $value ) ) {
				return false;
			}
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
		$this->options->delete( 'googlesitekit_analytics_adsense_linked' );
	}

	/**
	 * Checks if the module is active in the admin bar for the given URL.
	 *
	 * @since 1.4.0
	 *
	 * @param string $url URL to determine active state for.
	 * @return bool
	 */
	public function is_active_in_admin_bar( $url ) {
		if ( ! $this->is_connected() ) {
			return false;
		}

		return $this->has_data_for_url( $url );
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
			'analytics_account_id'  => array(
				'label' => __( 'Analytics account ID', 'google-site-kit' ),
				'value' => $settings['accountID'],
				'debug' => Debug_Data::redact_debug_value( $settings['accountID'] ),
			),
			'analytics_property_id' => array(
				'label' => __( 'Analytics property ID', 'google-site-kit' ),
				'value' => $settings['propertyID'],
				'debug' => Debug_Data::redact_debug_value( $settings['propertyID'], 7 ),
			),
			'analytics_profile_id'  => array(
				'label' => __( 'Analytics profile ID', 'google-site-kit' ),
				'value' => $settings['profileID'],
				'debug' => Debug_Data::redact_debug_value( $settings['profileID'] ),
			),
			'analytics_use_snippet' => array(
				'label' => __( 'Analytics snippet placed', 'google-site-kit' ),
				'value' => $settings['useSnippet'] ? __( 'Yes', 'google-site-kit' ) : __( 'No', 'google-site-kit' ),
				'debug' => $settings['useSnippet'] ? 'yes' : 'no',
			),
		);
	}

	/**
	 * Outputs gtag snippet.
	 *
	 * @since 1.0.0
	 */
	protected function enqueue_gtag_js() {
		// Bail early if we are checking for the tag presence from the back end.
		if ( $this->context->input()->filter( INPUT_GET, 'tagverify', FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		// On AMP, do not print the script tag.
		if ( $this->context->is_amp() ) {
			return;
		}

		$use_snippet = $this->get_data( 'use-snippet' );
		if ( is_wp_error( $use_snippet ) || ! $use_snippet ) {
			return;
		}

		$tracking_id = $this->get_data( 'property-id' );
		if ( is_wp_error( $tracking_id ) ) {
			return;
		}

		wp_enqueue_script( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			'google_gtagjs',
			'https://www.googletagmanager.com/gtag/js?id=' . esc_attr( $tracking_id ),
			false,
			null,
			false
		);
		wp_script_add_data( 'google_gtagjs', 'script_execution', 'async' );

		wp_add_inline_script(
			'google_gtagjs',
			'window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}'
		);

		$gtag_opt = array();

		if ( $this->context->get_amp_mode() ) {
			$gtag_opt['linker'] = array(
				'domains' => array( $this->get_home_domain() ),
			);
		}

		$anonymize_ip = $this->get_data( 'anonymize-ip' );
		if ( ! is_wp_error( $anonymize_ip ) && $anonymize_ip ) {
			// See https://developers.google.com/analytics/devguides/collection/gtagjs/ip-anonymization.
			$gtag_opt['anonymize_ip'] = true;
		}

		/**
		 * Filters the gtag configuration options for the Analytics snippet.
		 *
		 * You can use the {@see 'googlesitekit_amp_gtag_opt'} filter to do the same for gtag in AMP.
		 *
		 * @since 1.0.0
		 *
		 * @see https://developers.google.com/gtagjs/devguide/configure
		 *
		 * @param array $gtag_opt gtag config options.
		 */
		$gtag_opt = apply_filters( 'googlesitekit_gtag_opt', $gtag_opt );

		if ( ! empty( $gtag_opt['linker'] ) ) {
			wp_add_inline_script(
				'google_gtagjs',
				'gtag(\'set\', \'linker\', ' . wp_json_encode( $gtag_opt['linker'] ) . ' );'
			);
		}
		unset( $gtag_opt['linker'] );

		wp_add_inline_script(
			'google_gtagjs',
			'gtag(\'js\', new Date());'
		);

		if ( empty( $gtag_opt ) ) {
			wp_add_inline_script(
				'google_gtagjs',
				'gtag(\'config\', \'' . esc_attr( $tracking_id ) . '\');'
			);
		} else {
			wp_add_inline_script(
				'google_gtagjs',
				'gtag(\'config\', \'' . esc_attr( $tracking_id ) . '\', ' . wp_json_encode( $gtag_opt ) . ' );'
			);
		}
	}

	/**
	 * Outputs gtag <amp-analytics> tag.
	 *
	 * @since 1.0.0
	 */
	protected function print_amp_gtag() {
		// Bail early if we are checking for the tag presence from the back end.
		if ( $this->context->input()->filter( INPUT_GET, 'tagverify', FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		if ( ! $this->context->is_amp() ) {
			return;
		}

		$use_snippet = $this->get_data( 'use-snippet' );
		if ( is_wp_error( $use_snippet ) || ! $use_snippet ) {
			return;
		}

		$tracking_id = $this->get_data( 'property-id' );
		if ( is_wp_error( $tracking_id ) ) {
			return;
		}

		$gtag_amp_opt = array(
			'vars'            => array(
				'gtag_id' => $tracking_id,
				'config'  => array(
					$tracking_id => array(
						'groups' => 'default',
						'linker' => array(
							'domains' => array( $this->get_home_domain() ),
						),
					),
				),
			),
			'optoutElementId' => '__gaOptOutExtension',
		);

		/**
		 * Filters the gtag configuration options for the amp-analytics tag.
		 *
		 * You can use the {@see 'googlesitekit_gtag_opt'} filter to do the same for gtag in non-AMP.
		 *
		 * @since 1.0.0
		 *
		 * @see https://developers.google.com/gtagjs/devguide/amp
		 *
		 * @param array $gtag_amp_opt gtag config options for AMP.
		 */
		$gtag_amp_opt_filtered = apply_filters( 'googlesitekit_amp_gtag_opt', $gtag_amp_opt );

		// Ensure gtag_id is set to the correct value.
		if ( ! is_array( $gtag_amp_opt_filtered ) ) {
			$gtag_amp_opt_filtered = $gtag_amp_opt;
		}

		if ( ! isset( $gtag_amp_opt_filtered['vars'] ) || ! is_array( $gtag_amp_opt_filtered['vars'] ) ) {
			$gtag_amp_opt_filtered['vars'] = $gtag_amp_opt['vars'];
		}

		$gtag_amp_opt_filtered['vars']['gtag_id'] = $tracking_id;
		?>
		<amp-analytics type="gtag" data-credentials="include">
			<script type="application/json">
				<?php echo wp_json_encode( $gtag_amp_opt_filtered ); ?>
			</script>
		</amp-analytics>
		<?php
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

		$use_snippet = $this->get_data( 'use-snippet' );
		if ( is_wp_error( $use_snippet ) || ! $use_snippet ) {
			return $data;
		}

		$tracking_id = $this->get_data( 'property-id' );
		if ( is_wp_error( $tracking_id ) ) {
			return $data;
		}

		$data['amp_component_scripts']['amp-analytics'] = 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js';
		return $data;
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
			'connection'                   => '',
			'account-id'                   => '',
			'property-id'                  => '',
			'profile-id'                   => '',
			'internal-web-property-id'     => '',
			'use-snippet'                  => '',
			'tracking-disabled'            => '',
			// GET.
			'anonymize-ip'                 => '',
			'goals'                        => 'analytics',
			'accounts-properties-profiles' => 'analytics',
			'properties-profiles'          => 'analytics',
			'profiles'                     => 'analytics',
			'tag-permission'               => '',
			'report'                       => 'analyticsreporting',
			// POST.
			'settings'                     => '',
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
						return new WP_Error( 'account_id_not_set', __( 'Analytics account ID not set.', 'google-site-kit' ), array( 'status' => 404 ) );
					}
					return $option['accountID'];
				};
			case 'POST:account-id':
				if ( ! isset( $data['accountID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
				}
				return function() use ( $data ) {
					$this->get_settings()->merge(
						array(
							'accountID'     => $data['accountID'],
							'adsenseLinked' => false,
						)
					);
					return true;
				};
			case 'GET:accounts-properties-profiles':
				return $this->get_service( 'analytics' )->management_accounts->listManagementAccounts();
			case 'GET:anonymize-ip':
				return function() {
					$option = $this->get_settings()->get();

					return (bool) $option['anonymizeIP'];
				};
			case 'GET:connection':
				return function() {
					$connection = array(
						'accountID'             => '',
						'propertyID'            => '',
						'profileID'             => '',
						'internalWebPropertyID' => '',
					);

					$option = $this->get_settings()->get();

					return array_intersect_key( $option, $connection );
				};
			case 'POST:connection':
				return function() use ( $data ) {
					$this->get_settings()->merge(
						array(
							'accountID'             => $data['accountID'],
							'propertyID'            => $data['propertyID'],
							'profileID'             => $data['profileID'],
							'internalWebPropertyID' => $data['internalWebPropertyID'],
							'adsenseLinked'         => false,
						)
					);
					return true;
				};
			case 'GET:goals':
				$connection = $this->get_data( 'connection' );
				if (
					empty( $connection['accountID'] ) ||
					empty( $connection['internalWebPropertyID'] ) ||
					empty( $connection['profileID'] )
				) {
					// This is needed to return and emulate the same error format from Analytics API.
					return function() {
						return array(
							'error' => array(
								'code'    => 400,
								'message' => __( 'Analytics module needs to be configured.', 'google-site-kit' ),
								'status'  => 'INVALID_ARGUMENT',
							),
						);
					};
				}
				$service = $this->get_service( 'analytics' );
				return $service->management_goals->listManagementGoals( $connection['accountID'], $connection['propertyID'], $connection['profileID'] );
			case 'GET:internal-web-property-id':
				return function() {
					$option = $this->get_settings()->get();

					if ( empty( $option['internalWebPropertyID'] ) ) {
						return new WP_Error( 'internal_web_property_id_not_set', __( 'Analytics internal web property ID not set.', 'google-site-kit' ), array( 'status' => 404 ) );
					}
					return $option['internalWebPropertyID'];
				};
			case 'POST:internal-web-property-id':
				if ( ! isset( $data['internalWebPropertyID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'internalWebPropertyID' ), array( 'status' => 400 ) );
				}
				return function() use ( $data ) {
					$this->get_settings()->merge(
						array(
							'internalWebPropertyID' => $data['internalWebPropertyID'],
							'adsenseLinked'         => false,
						)
					);
					return true;
				};
			case 'GET:profile-id':
				return function() {
					$option = $this->get_settings()->get();

					if ( empty( $option['profileID'] ) ) {
						return new WP_Error( 'profile_id_not_set', __( 'Analytics profile ID not set.', 'google-site-kit' ), array( 'status' => 404 ) );
					}
					return $option['profileID'];
				};
			case 'POST:profile-id':
				if ( ! isset( $data['profileID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'profileID' ), array( 'status' => 400 ) );
				}
				return function() use ( $data ) {
					$this->get_settings()->merge(
						array(
							'profileID'     => $data['profileID'],
							'adsenseLinked' => false,
						)
					);
					return true;
				};
			case 'GET:profiles':
				if ( ! isset( $data['accountID'] ) ) {
					return new WP_Error(
						'missing_required_param',
						/* translators: %s: Missing parameter name */
						sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ),
						array( 'status' => 400 )
					);
				}
				if ( ! isset( $data['propertyID'] ) ) {
					return new WP_Error(
						'missing_required_param',
						/* translators: %s: Missing parameter name */
						sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'propertyID' ),
						array( 'status' => 400 )
					);
				}

				return $this->get_service( 'analytics' )->management_profiles->listManagementProfiles( $data['accountID'], $data['propertyID'] );
			case 'GET:properties-profiles':
				if ( ! isset( $data['accountID'] ) ) {
					return new WP_Error(
						'missing_required_param',
						/* translators: %s: Missing parameter name */
						sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ),
						array( 'status' => 400 )
					);
				}

				return $this->get_service( 'analytics' )->management_webproperties->listManagementWebproperties( $data['accountID'] );
			case 'GET:property-id':
				return function() {
					$option = $this->get_settings()->get();

					if ( empty( $option['propertyID'] ) ) {
						return new WP_Error( 'property_id_not_set', __( 'Analytics property ID not set.', 'google-site-kit' ), array( 'status' => 404 ) );
					}
					return $option['propertyID'];
				};
			case 'POST:property-id':
				if ( ! isset( $data['propertyID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'propertyID' ), array( 'status' => 400 ) );
				}
				return function() use ( $data ) {
					$this->get_settings()->merge(
						array(
							'propertyID'    => $data['propertyID'],
							'adsenseLinked' => false,
						)
					);
					return true;
				};
			case 'GET:report':
				$date_range = $data['dateRange'] ?: 'last-28-days';

				$dimensions = array_map(
					function ( $name ) {
						$dimension = new Google_Service_AnalyticsReporting_Dimension();
						$dimension->setName( $name );

						return $dimension;
					},
					array_filter( explode( ',', $data['dimensions'] ) )
				);

				$request_args         = compact( 'dimensions' );
				$request_args['page'] = $data['url'];

				if ( ! empty( $data['limit'] ) ) {
					$request_args['row_limit'] = $data['limit'];
				}

				$request = $this->create_analytics_site_data_request( $request_args );

				if ( is_wp_error( $request ) ) {
					return $request;
				}

				$date_ranges = array(
					$this->parse_date_range(
						$date_range,
						$data['compareDateRanges'] ? 2 : 1
					),
				);

				// When using multiple date ranges, it changes the structure of the response,
				// where each date range becomes an item in a list.
				if ( ! empty( $data['multiDateRange'] ) ) {
					$date_ranges[] = $this->parse_date_range( $date_range, 1, 1, true );
				}

				$date_ranges = array_map(
					function ( $date_range ) {
						list ( $start_date, $end_date ) = $date_range;
						$date_range                     = new Google_Service_AnalyticsReporting_DateRange();
						$date_range->setStartDate( $start_date );
						$date_range->setEndDate( $end_date );

						return $date_range;
					},
					$date_ranges
				);
				$request->setDateRanges( $date_ranges );

				$metrics = array_map(
					function ( $metric_def ) {
						$metric_def = array_merge(
							array(
								'alias'      => '',
								'expression' => '',
							),
							(array) $metric_def
						);
						$metric     = new Google_Service_AnalyticsReporting_Metric();
						$metric->setAlias( $metric_def['alias'] );
						$metric->setExpression( $metric_def['expression'] );

						return $metric;
					},
					(array) $data['metrics']
				);
				$request->setMetrics( $metrics );

				// Order by.
				$orderby = array_map(
					function ( $order_def ) {
						$order_def = array_merge(
							array(
								'fieldName' => '',
								'sortOrder' => '',
							),
							(array) $order_def
						);
						$order_by  = new Google_Service_AnalyticsReporting_OrderBy();
						$order_by->setFieldName( $order_def['fieldName'] );
						$order_by->setSortOrder( $order_def['sortOrder'] );

						return $order_by;
					},
					(array) $data['orderby']
				);
				$request->setOrderBys( $orderby );

				// Batch reports requests.
				$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
				$body->setReportRequests( array( $request ) );

				return $this->get_analyticsreporting_service()->reports->batchGet( $body );
			case 'POST:settings':
				if ( ! isset( $data['accountID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
				}
				if ( ! isset( $data['propertyID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'propertyID' ), array( 'status' => 400 ) );
				}
				if ( ! isset( $data['internalWebPropertyID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'internalWebPropertyID' ), array( 'status' => 400 ) );
				}
				if ( ! isset( $data['profileID'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'profileID' ), array( 'status' => 400 ) );
				}
				if ( ! isset( $data['useSnippet'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'useSnippet' ), array( 'status' => 400 ) );
				}

				return function() use ( $data ) {
					$property_id              = null;
					$internal_web_property_id = null;

					if ( '0' === $data['propertyID'] ) {
						$is_new_property = true;
						$restore_defer   = $this->with_client_defer( false );
						$property        = new Google_Service_Analytics_Webproperty();
						$property->setName( wp_parse_url( $this->context->get_reference_site_url(), PHP_URL_HOST ) );
						try {
							$property = $this->get_service( 'analytics' )->management_webproperties->insert( $data['accountID'], $property );
						} catch ( Google_Service_Exception $e ) {
							$restore_defer();
							$message = $e->getErrors();
							if ( isset( $message[0] ) && isset( $message[0]['message'] ) ) {
								$message = $message[0]['message'];
							}
							return new WP_Error( $e->getCode(), $message );
						} catch ( Exception $e ) {
							$restore_defer();
							return new WP_Error( $e->getCode(), $e->getMessage() );
						}
						$restore_defer();
						/* @var Google_Service_Analytics_Webproperty $property Property instance. */
						$property_id              = $property->getId();
						$internal_web_property_id = $property->getInternalWebPropertyId();
					} else {
						$is_new_property          = false;
						$property_id              = $data['propertyID'];
						$internal_web_property_id = $data['internalWebPropertyID'];
					}
					$profile_id = null;
					if ( '0' === $data['profileID'] ) {
						$restore_defer = $this->with_client_defer( false );
						$profile       = new Google_Service_Analytics_Profile();
						$profile->setName( __( 'All Web Site Data', 'google-site-kit' ) );
						try {
							$profile = $this->get_service( 'analytics' )->management_profiles->insert( $data['accountID'], $property_id, $profile );
						} catch ( Google_Service_Exception $e ) {
							$restore_defer();
							$message = $e->getErrors();
							if ( isset( $message[0] ) && isset( $message[0]['message'] ) ) {
								$message = $message[0]['message'];
							}
							return new WP_Error( $e->getCode(), $message );
						} catch ( Exception $e ) {
							$restore_defer();
							return new WP_Error( $e->getCode(), $e->getMessage() );
						}
						$restore_defer();
						$profile_id = $profile->id;
					} else {
						$profile_id = $data['profileID'];
					}
					// Set default profile for new property.
					if ( $is_new_property ) {
						$restore_defer = $this->with_client_defer( false );
						$property      = new Google_Service_Analytics_Webproperty();
						$property->setDefaultProfileId( $profile_id );
						try {
							$property = $this->get_service( 'analytics' )->management_webproperties->patch( $data['accountID'], $property_id, $property );
						} catch ( Google_Service_Exception $e ) {
							$restore_defer();
							$message = $e->getErrors();
							if ( isset( $message[0] ) && isset( $message[0]['message'] ) ) {
								$message = $message[0]['message'];
							}
							return new WP_Error( $e->getCode(), $message );
						} catch ( Exception $e ) {
							$restore_defer();
							return new WP_Error( $e->getCode(), $e->getMessage() );
						}
						$restore_defer();
					}
					$this->get_settings()->merge(
						array(
							'accountID'             => $data['accountID'],
							'propertyID'            => $property_id,
							'internalWebPropertyID' => $internal_web_property_id,
							'profileID'             => $profile_id,
							'useSnippet'            => ! empty( $data['useSnippet'] ),
							'anonymizeIP'           => (bool) $data['anonymizeIP'],
							'trackingDisabled'      => (array) $data['trackingDisabled'],
							'adsenseLinked'         => false,
						)
					);
					return $this->get_settings()->get();
				};
			case 'GET:tag-permission':
				return function() use ( $data ) {
					if ( ! isset( $data['tag'] ) ) {
						return new WP_Error(
							'missing_required_param',
							/* translators: %s: Missing parameter name */
							sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'tag' ),
							array( 'status' => 400 )
						);
					}
					$accounts               = $this->get_data( 'accounts-properties-profiles' );
					$has_access_to_property = $this->has_access_to_property( $data['tag'], $accounts['accounts'] );

					if ( empty( $has_access_to_property ) ) {
						return new WP_Error(
							'google_analytics_existing_tag_permission',
							sprintf(
							/* translators: %s: Property id of the existing tag */
								__( 'We\'ve detected there\'s already an existing Analytics tag on your site (ID %s), but your account doesn\'t seem to have access to this Analytics property. You can either remove the existing tag and connect to a different account, or request access to this property from your team.', 'google-site-kit' ),
								$data['tag']
							),
							array( 'status' => 403 )
						);
					}

					return $has_access_to_property;
				};
			case 'GET:tracking-disabled':
				return function() {
					$option = $this->get_settings()->get();

					return $option['trackingDisabled'];
				};
			case 'GET:use-snippet':
				return function() {
					$option = $this->get_settings()->get();
					return ! empty( $option['useSnippet'] );
				};
			case 'POST:use-snippet':
				if ( ! isset( $data['useSnippet'] ) ) {
					/* translators: %s: Missing parameter name */
					return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'useSnippet' ), array( 'status' => 400 ) );
				}
				return function() use ( $data ) {
					$this->get_settings()->merge( array( 'useSnippet' => $data['useSnippet'] ) );
					return true;
				};
		}

		return new WP_Error( 'invalid_datapoint', __( 'Invalid datapoint.', 'google-site-kit' ) );
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
			case 'GET:accounts-properties-profiles':
				/* @var Google_Service_Analytics_Accounts $response listManagementAccounts response. */
				$accounts            = (array) $response->getItems();
				$account_ids         = array_map(
					function ( Google_Service_Analytics_Account $account ) {
						return $account->getId();
					},
					$accounts
				);
				$properties_profiles = array(
					'properties' => array(),
					'profiles'   => array(),
				);

				if ( ! empty( $data['existingAccountID'] ) && ! empty( $data['existingPropertyID'] ) ) {
					// If there is an existing tag, pass it through to ensure only the existing tag is matched.
					$properties_profiles = $this->get_data(
						'properties-profiles',
						array(
							'accountID'          => $data['existingAccountID'],
							'existingPropertyID' => $data['existingPropertyID'],
						)
					);
				} else {
					// Get the account ID from the saved settings - returns WP_Error if not set.
					$account_id = $this->get_data( 'account-id' );
					// If the saved account ID is in the list of accounts the user has access to, it's a match.
					if ( in_array( $account_id, $account_ids, true ) ) {
						$properties_profiles = $this->get_data( 'properties-profiles', array( 'accountID' => $account_id ) );
					} else {
						// Iterate over each account in reverse so if there is no match,
						// the last $properties_profiles will be from the first account (selected by default).
						foreach ( array_reverse( $accounts ) as $account ) {
							/* @var Google_Service_Analytics_Account $account Analytics account object. */
							$properties_profiles = $this->get_data( 'properties-profiles', array( 'accountID' => $account->getId() ) );

							if ( ! is_wp_error( $properties_profiles ) && isset( $properties_profiles['matchedProperty'] ) ) {
								break;
							}
						}
					}
				}

				if ( is_wp_error( $properties_profiles ) ) {
					return $properties_profiles;
				}

				return array_merge( compact( 'accounts' ), $properties_profiles );
			case 'GET:goals':
				if ( is_array( $response ) ) {
					return $response;
				}
				// TODO: Parse this response to a regular array.
				break;
			case 'GET:profiles':
				// TODO: Parse this response to a regular array.
				$response = $response->getItems();

				return $response;
			case 'GET:properties-profiles':
				/* @var Google_Service_Analytics_Webproperties $response listManagementWebproperties response. */
				$properties = (array) $response->getItems();
				$response   = array(
					'properties' => $properties,
					'profiles'   => array(),
				);

				if ( 0 === count( $properties ) ) {
					return $response;
				}

				$found_property = new Google_Service_Analytics_Webproperty();
				$current_url    = $this->context->get_reference_site_url();

				// If requested for a specific property, only match by property ID.
				if ( ! empty( $data['existingPropertyID'] ) ) {
					$property_id  = $data['existingPropertyID'];
					$current_urls = array();
				} else {
					$property_id  = $this->get_data( 'property-id' );
					$current_urls = $this->permute_site_url( $current_url );
				}

				// If there's no match for the saved account ID, try to find a match using the properties of each account.
				foreach ( $properties as $property ) {
					/* @var Google_Service_Analytics_Webproperty $property Property instance. */
					if (
						// Attempt to match by property ID.
						$property->getId() === $property_id ||
						// Attempt to match by site URL, with and without http/https and 'www' subdomain.
						in_array( untrailingslashit( $property->getWebsiteUrl() ), $current_urls, true )
					) {
						$found_property              = $property;
						$response['matchedProperty'] = $property;
						break;
					}
				}

				// If no match is found, fetch profiles for the first property if available.
				if ( ! $found_property->getAccountId() && $properties ) {
					$found_property = array_shift( $properties );
				} elseif ( ! $found_property->getAccountId() ) {
					// If no found property, skip the call to 'profiles' as it would be empty/fail.
					return $response;
				}

				$profiles = $this->get_data(
					'profiles',
					array(
						'accountID'  => $found_property->getAccountId(),
						'propertyID' => $found_property->getId(),
					)
				);

				if ( is_wp_error( $profiles ) ) {
					return $profiles;
				}

				$response['profiles'] = $profiles;

				return $response;
			case 'GET:report':
				/* @var Google_Service_AnalyticsReporting_GetReportsResponse $response Response object. */
				if ( $this->is_adsense_request( $data ) ) {
					$is_linked = empty( $response->error );
					$this->get_settings()->merge( array( 'adsenseLinked' => $is_linked ) );
				}

				return $response->getReports();
		}

		return $response;
	}

	/**
	 * Creates a new Analytics site request for the current site and given arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Additional arguments.
	 *
	 *     @type array  $dimensions List of request dimensions. Default empty array.
	 *     @type string $start_date Start date in 'Y-m-d' format. Default empty string.
	 *     @type string $end_date   End date in 'Y-m-d' format. Default empty string.
	 *     @type string $page       Specific page URL to filter by. Default empty string.
	 *     @type int    $row_limit  Limit of rows to return. Default 100.
	 * }
	 * @return Google_Service_AnalyticsReporting_ReportRequest|WP_Error Analytics site request instance.
	 */
	protected function create_analytics_site_data_request( array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'dimensions' => array(),
				'start_date' => '',
				'end_date'   => '',
				'page'       => '',
				'row_limit'  => 100,
			)
		);

		$profile_id = $this->get_data( 'profile-id' );
		if ( is_wp_error( $profile_id ) ) {
			return $profile_id;
		}

		$request = new Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId( $profile_id );

		if ( ! empty( $args['dimensions'] ) ) {
			$request->setDimensions( (array) $args['dimensions'] );
		}

		if ( ! empty( $args['start_date'] ) && ! empty( $args['end_date'] ) ) {
			$date_range = new Google_Service_AnalyticsReporting_DateRange();
			$date_range->setStartDate( $args['start_date'] );
			$date_range->setEndDate( $args['end_date'] );
			$request->setDateRanges( array( $date_range ) );
		}

		if ( ! empty( $args['page'] ) ) {
			$dimension_filter = new Google_Service_AnalyticsReporting_DimensionFilter();
			$dimension_filter->setDimensionName( 'ga:pagePath' );
			$dimension_filter->setOperator( 'EXACT' );
			$args['page'] = str_replace( trim( $this->context->get_reference_site_url(), '/' ), '', $args['page'] );
			$dimension_filter->setExpressions( array( $args['page'] ) );
			$dimension_filter_clause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
			$dimension_filter_clause->setFilters( array( $dimension_filter ) );
			$request->setDimensionFilterClauses( array( $dimension_filter_clause ) );
		}

		if ( ! empty( $args['row_limit'] ) ) {
			$request->setPageSize( $args['row_limit'] );
		}

		return $request;
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
			'slug'        => 'analytics',
			'name'        => _x( 'Analytics', 'Service name', 'google-site-kit' ),
			'description' => __( 'Get a deeper understanding of your customers. Google Analytics gives you the free tools you need to analyze data for your business in one place.', 'google-site-kit' ),
			'cta'         => __( 'Get to know your customers.', 'google-site-kit' ),
			'order'       => 3,
			'homepage'    => __( 'https://analytics.google.com/analytics/web', 'google-site-kit' ),
			'learn_more'  => __( 'https://marketingplatform.google.com/about/analytics/', 'google-site-kit' ),
			'group'       => __( 'Marketing Platform', 'google-site-kit' ),
		);
	}

	/**
	 * Gets the configured Analytics Reporting service object instance.
	 *
	 * @return Google_Service_AnalyticsReporting The Analytics Reporting API service.
	 */
	private function get_analyticsreporting_service() {
		return $this->get_service( 'analyticsreporting' );
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
			'analytics'          => new Google_Service_Analytics( $client ),
			'analyticsreporting' => new Google_Service_AnalyticsReporting( $client ),
		);
	}

	/**
	 * Verifies that user has access to the property found in the existing tag.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property_id   Property found in the existing tag.
	 * @param array  $accounts      List of accounts to loop through properties.
	 * @return mixed False if user has no access to the existing property or array with account id and property found.
	 */
	protected function has_access_to_property( $property_id, $accounts ) {

		if ( empty( $property_id ) || empty( $accounts ) ) {
			return false;
		}

		$response = false;

		foreach ( $accounts as $account ) {
			$account_id = $account->getId();
			$properties = $this->get_data( 'properties-profiles', array( 'accountID' => $account_id ) );

			if ( is_wp_error( $properties ) ) {
				continue;
			}
			$existing_property_match = array_filter(
				$properties['properties'],
				function( $property ) use ( $property_id ) {
					return $property->getId() === $property_id;
				}
			);

			if ( ! empty( $existing_property_match ) ) {
				$response = array(
					'accountID'  => $account_id,
					'propertyID' => $property_id,
				);
				break;
			}
		}

		return $response;
	}

	/**
	 * Determines whether the given request is for an adsense request.
	 *
	 * @param Data_Request $data Data request object.
	 *
	 * @return bool
	 */
	private function is_adsense_request( $data ) {
		foreach ( (array) $data['metrics'] as $metric ) {
			if ( isset( $metric->expression ) && 0 === strpos( $metric->expression, 'ga:adsense' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Gets the hostname of the home URL.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	private function get_home_domain() {
		return wp_parse_url( home_url(), PHP_URL_HOST );
	}

	/**
	 * Outputs the user tracking opt-out script.
	 *
	 * This script opts out of all Google Analytics tracking, for all measurement IDs, regardless of implementation.
	 * E.g. via Tag Manager, etc.
	 *
	 * @since 1.5.0
	 * @link https://developers.google.com/analytics/devguides/collection/analyticsjs/user-opt-out
	 */
	private function print_tracking_opt_out() {
		?>
		<!-- <?php esc_html_e( 'Google Analytics user opt-out added via Site Kit by Google', 'google-site-kit' ); ?> -->
		<?php if ( $this->context->is_amp() ) : ?>
			<script type="application/ld+json" id="__gaOptOutExtension"></script>
		<?php else : ?>
			<script type="text/javascript">window["_gaUserPrefs"] = { ioo : function() { return true; } }</script>
		<?php endif; ?>
		<?php
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
	 * Checks whether Analytics data exists for the given URL.
	 *
	 * @since 1.4.0
	 *
	 * @param string $url The url to check data for.
	 * @return bool
	 */
	protected function has_data_for_url( $url ) {
		if ( ! $url ) {
			return false;
		}

		$transient_key = 'googlesitekit_analytics_has_data_' . md5( $url );
		$has_data      = get_transient( $transient_key );

		if ( false === $has_data ) {
			/* @var Google_Service_AnalyticsReporting_Report[]|WP_Error $reports Array of reporting report instances. */
			$reports = $this->get_data(
				'report',
				array(
					'url'     => $url,
					'metrics' => array(
						array( 'expression' => 'ga:users' ),
						array( 'expression' => 'ga:sessions' ),
					),
				)
			);

			if ( is_wp_error( $reports ) ) {
				$reports = array(); // Bypass data check and cache.
			}

			foreach ( $reports as $report ) {
				/* @var Google_Service_AnalyticsReporting_Report $report Report instance. */
				$report_data = $report->getData();
				/* @var Google_Service_AnalyticsReporting_ReportData $report_data Report data instance. */
				foreach ( $report_data->getTotals() as $date_range_values ) {
					/* @var Google_Service_AnalyticsReporting_DateRangeValues $date_range_values Values instance. */
					if (
						isset( $date_range_values[0], $date_range_values[1] )
						&& ( 0 < $date_range_values[0] || 0 < $date_range_values[1] )
					) {
						$has_data = true;
						break 2;
					}
				}
			}

			// Cache "data found" status for one day, "no data" status for one hour.
			set_transient( $transient_key, (int) $has_data, $has_data ? DAY_IN_SECONDS : HOUR_IN_SECONDS );
		}

		return (bool) $has_data;
	}
}
